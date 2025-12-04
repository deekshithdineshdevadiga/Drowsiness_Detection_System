import cv2
import numpy as np
import mediapipe as mp
import os, threading, queue, heapq, tempfile, shutil
from jproperties import Properties
from datetime import datetime

# ─────── Configuration ──────────────────────────────────────────
os.environ["TF_ENABLE_ONEDNN_OPTS"] = "0"
os.environ["TF_CPP_MIN_LOG_LEVEL"] = "3"

configs = Properties()
with open("./EZWitness_config.properties", "rb") as fh:
    configs.load(fh)

rtsp_url = configs.get("rtsp_url_cam1").data
if not rtsp_url:
    print("RTSP URL missing in EZWitness_config.properties")
    exit()

base_video_folder = "drowsiness_videos"
custom_name = "camera1"

# ─────── FaceMesh + EAR helpers ─────────────────────────────────
mp_face_mesh = mp.solutions.face_mesh
facemesh_model = mp_face_mesh.FaceMesh(
    max_num_faces=1, refine_landmarks=True,
    min_detection_confidence=0.5, min_tracking_confidence=0.5
)

def distance(p1, p2):
    return np.linalg.norm(np.array(p1) - np.array(p2))

def ear_for_eye(lm, idx, w, h):
    pts = [(int(lm[i].x * w), int(lm[i].y * h)) for i in idx if i < len(lm)]
    if len(pts) != 6: return None
    return (distance(pts[1], pts[5]) + distance(pts[2], pts[4])) / (2.0 * distance(pts[0], pts[3]))

def avg_ear(lm, l_idx, r_idx, w, h):
    l = ear_for_eye(lm, l_idx, w, h)
    r = ear_for_eye(lm, r_idx, w, h)
    return None if (l is None or r is None) else (l + r) / 2.0

def get_face_bbox(landmarks, w, h, margin=20):
    x_coords = [int(p.x * w) for p in landmarks]
    y_coords = [int(p.y * h) for p in landmarks]
    x_min = max(min(x_coords) - margin, 0)
    y_min = max(min(y_coords) - margin, 0)
    x_max = min(max(x_coords) + margin, w)
    y_max = min(max(y_coords) + margin, h)
    return x_min, y_min, x_max, y_max

def align_face(image, left_eye_pts, right_eye_pts, desired_width=224, desired_height=224):
    left_eye_center = np.mean(left_eye_pts, axis=0)
    right_eye_center = np.mean(right_eye_pts, axis=0)

    dy = right_eye_center[1] - left_eye_center[1]
    dx = right_eye_center[0] - left_eye_center[0]
    angle = np.degrees(np.arctan2(dy, dx))

    desired_right_eye_x = 1.0 - 0.35
    dist = np.sqrt((dx ** 2) + (dy ** 2))
    desired_dist = (0.35 - 0.15) * desired_width
    scale = desired_dist / dist

    eyes_center_np = (left_eye_center + right_eye_center) / 2
    eyes_center = (int(eyes_center_np[0]), int(eyes_center_np[1]))

    M = cv2.getRotationMatrix2D(eyes_center, angle, scale)

    tX = desired_width * 0.5
    tY = desired_height * 0.4
    M[0, 2] += (tX - eyes_center[0])
    M[1, 2] += (tY - eyes_center[1])

    output = cv2.warpAffine(image, M, (desired_width, desired_height), flags=cv2.INTER_CUBIC)
    return output

left_idx = [362, 385, 387, 263, 373, 380]
right_idx = [33, 160, 158, 133, 153, 144]

EAR_THRESH, BLINK_THRESH, DROWSY_THRESH = 0.18, 6, 25
OPEN_RESET, MIN_LANDMARKS = 10, 400

# ─────── Threaded VideoCapture ──────────────────────────────────
class VideoCapture:
    def __init__(self, src):
        self.cap = cv2.VideoCapture(0) if src == "0" else cv2.VideoCapture(src, cv2.CAP_FFMPEG)
        if not self.cap.isOpened():
            print("Camera error")
            exit()
        self.q = queue.Queue()
        threading.Thread(target=self._reader, daemon=True).start()

    def _reader(self):
        while True:
            ret, f = self.cap.read()
            if not ret:
                print("Stream stopped")
                os._exit(1)
            if not self.q.empty():
                try:
                    self.q.get_nowait()
                except queue.Empty:
                    pass
            self.q.put(f)

    def read(self):
        return self.q.get()

# ─────── Main loop ──────────────────────────────────────────────
print("RTSP:", rtsp_url)
print("Capturing…")

cap = VideoCapture(rtsp_url)

blink = no_face = open_eye = drowsy = 0
recording = False
vw = None
rec_start = None
frames_temp_dir = None
best_heap = []

try:
    while True:
        frame = cap.read()
        h, w = frame.shape[:2]
        rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        result = facemesh_model.process(rgb)

        status, col = "Processing…", (255, 255, 255)
        avgEAR = None

        if result.multi_face_landmarks:
            lm = result.multi_face_landmarks[0].landmark
            if len(lm) < MIN_LANDMARKS:
                status, col = "No face", (0, 255, 255)
                no_face += 1
                blink = open_eye = drowsy = 0
            else:
                avgEAR = avg_ear(lm, left_idx, right_idx, w, h)
                if avgEAR is not None:
                    if avgEAR < EAR_THRESH:
                        blink += 1
                        if blink <= BLINK_THRESH:
                            status, col = "Blink", (255, 255, 0)
                        else:
                            drowsy += 1
                            if drowsy >= DROWSY_THRESH and not recording:
                                ts_base = datetime.now().strftime("%Y%m%d_%H%M%S")
                                datedir = datetime.now().strftime("%Y-%m-%d")
                                full_dir = os.path.join(base_video_folder, datedir)
                                os.makedirs(full_dir, exist_ok=True)

                                video_name = f"drowsy_{custom_name}_{ts_base}.mp4"
                                video_path = os.path.join(full_dir, video_name)
                                frames_dir = os.path.join(full_dir, f"frames_{custom_name}_{ts_base}")
                                os.makedirs(frames_dir, exist_ok=True)

                                frames_temp_dir = tempfile.mkdtemp()
                                best_heap = []

                                vw = cv2.VideoWriter(video_path, cv2.VideoWriter_fourcc(*"H264"), 30, (w, h))
                                recording = True
                                rec_start = datetime.now()
                            status, col = "Drowsy", (0, 0, 255)
                    else:
                        open_eye += 1
                        if open_eye >= OPEN_RESET: drowsy = 0
                        blink = 0
                        status, col = "Not Drowsy", (0, 255, 0)

        # ── recording block ────────────────────────────────────
        if recording:
            elapsed = (datetime.now() - rec_start).total_seconds()
            if elapsed >= 60:
                vw.release()
                vw = None
                recording = False
                rec_start = None

                os.makedirs(frames_dir, exist_ok=True)
                for i, (_, pth) in enumerate(sorted(best_heap)[:20]):
                    shutil.copy(pth, os.path.join(frames_dir, f"best_{i+1:02}.jpg"))
                shutil.rmtree(frames_temp_dir)
                frames_temp_dir = None
                best_heap = []
            else:
                vw.write(frame)

                if avgEAR is not None and frames_temp_dir:
                    x1, y1, x2, y2 = get_face_bbox(lm, w, h)

                    now = datetime.now()
                    fname_time = now.strftime("%Y-%m-%d_%H-%M-%S-%f")

                    # Save grayscale tight face crop
                    face_img = frame[y1:y2, x1:x2]
                    gray_face = cv2.cvtColor(face_img, cv2.COLOR_BGR2GRAY)
                    grayscale_dir = os.path.join(frames_dir, "grayscale_faces")
                    os.makedirs(grayscale_dir, exist_ok=True)
                    cv2.imwrite(os.path.join(grayscale_dir, f"cam1_{fname_time}.jpg"), gray_face)

                    # Save aligned face
                    left_eye_pts = [(int(lm[i].x * w), int(lm[i].y * h)) for i in left_idx]
                    right_eye_pts = [(int(lm[i].x * w), int(lm[i].y * h)) for i in right_idx]
                    aligned_face = align_face(frame, left_eye_pts, right_eye_pts)
                    aligned_dir = os.path.join(frames_dir, "aligned_faces")
                    os.makedirs(aligned_dir, exist_ok=True)
                    cv2.imwrite(os.path.join(aligned_dir, f"aligned_{now.strftime('%H%M%S_%f')}.jpg"), aligned_face)

                    # Save loose face crop (similar to your example)
                    loose_margin = 50
                    lx1 = max(0, x1 - loose_margin)
                    ly1 = max(0, y1 - loose_margin)
                    lx2 = min(w, x2 + loose_margin)
                    ly2 = min(h, y2 + loose_margin)
                    loose_crop = frame[ly1:ly2, lx1:lx2]

                    # Optional blue tint (comment out if not needed)
                    # loose_crop = cv2.applyColorMap(loose_crop, cv2.COLORMAP_OCEAN)

                    loose_dir = os.path.join(frames_dir, "loose_faces")
                    os.makedirs(loose_dir, exist_ok=True)
                    cv2.imwrite(os.path.join(loose_dir, f"loose_{now.strftime('%H%M%S_%f')}.jpg"), loose_crop)

                    # Save face for heap (best faces)
                    fname = f"{avgEAR:.4f}_{now.strftime('%H%M%S_%f')}.jpg"
                    fpath = os.path.join(frames_temp_dir, fname)
                    cv2.imwrite(fpath, face_img)
                    heapq.heappush(best_heap, (avgEAR, fpath))
                    if len(best_heap) > 20:
                        _, old = heapq.heappop(best_heap)
                        os.remove(old)

        cv2.putText(frame, f"Drowsiness: {status}", (10, 30),
                    cv2.FONT_HERSHEY_SIMPLEX, 1, col, 2)
        cv2.imshow("IP Camera Stream", frame)
        if cv2.waitKey(1) & 0xFF == ord('q'):
            break

except KeyboardInterrupt:
    print("Interrupted")

finally:
    if vw: vw.release()
    if frames_temp_dir and os.path.isdir(frames_temp_dir):
        shutil.rmtree(frames_temp_dir)
    cv2.destroyAllWindows()
