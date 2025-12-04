import cv2
import numpy as np
import mediapipe as mp
import os, threading, queue, requests, heapq, tempfile, shutil
from jproperties import Properties          # pip install jproperties
from datetime import datetime

# ─────── Configuration ──────────────────────────────────────────
os.environ["TF_ENABLE_ONEDNN_OPTS"] = "0"   # silence TF warnings
os.environ["TF_CPP_MIN_LOG_LEVEL"] = "3"

configs = Properties()
with open("./EZWitness_config.properties", "rb") as fh:
    configs.load(fh)

rtsp_url   = configs.get("rtsp_url_cam1").data      # "rtsp://user:pass@ip/..."
if not rtsp_url:
    print("RTSP URL missing in EZWitness_config.properties"); exit()

base_video_folder = "drowsiness_videos"  # top‑level output directory
custom_name       = "camera1"            # identifier for this camera

# ─────── FaceMesh + EAR helpers ─────────────────────────────────
mp_face_mesh   = mp.solutions.face_mesh
facemesh_model = mp_face_mesh.FaceMesh(
    max_num_faces=1, refine_landmarks=True,
    min_detection_confidence=0.5, min_tracking_confidence=0.5
)

def distance(p1, p2): return np.linalg.norm(np.array(p1) - np.array(p2))

def ear_for_eye(lm, idx, w, h):
    try:
        pts = [(int(lm[i].x * w), int(lm[i].y * h)) for i in idx if i < len(lm)]
        if len(pts) != 6: return None
        return (distance(pts[1], pts[5]) + distance(pts[2], pts[4])) / \
               (2.0*distance(pts[0], pts[3]))
    except: return None

def avg_ear(lm, l_idx, r_idx, w, h):
    l = ear_for_eye(lm, l_idx, w, h); r = ear_for_eye(lm, r_idx, w, h)
    return None if (l is None or r is None) else (l+r)/2.0

left_idx  = [362, 385, 387, 263, 373, 380]
right_idx = [33, 160, 158, 133, 153, 144]

EAR_THRESH, BLINK_THRESH, DROWSY_THRESH = 0.18, 6, 25
OPEN_RESET, MIN_LANDMARKS = 10, 400

# ─────── Threaded VideoCapture ──────────────────────────────────
class VideoCapture:
    def __init__(self, src):
        self.cap = cv2.VideoCapture(0) if src == "0" else cv2.VideoCapture(src, cv2.CAP_FFMPEG)
        if not self.cap.isOpened(): print("Camera error"); exit()
        self.q = queue.Queue(); threading.Thread(target=self._reader, daemon=True).start()
    def _reader(self):
        while True:
            ret, f = self.cap.read()
            if not ret: print("Stream stopped"); os._exit(1)
            if not self.q.empty():
                try: self.q.get_nowait()
                except queue.Empty: pass
            self.q.put(f)
    def read(self): return self.q.get()

# ─────── Main loop ──────────────────────────────────────────────
print("RTSP:", rtsp_url); print("Capturing…")
cap = VideoCapture(rtsp_url)

blink=no_face=open_eye=drowsy=0
recording=False; vw=None; rec_start=None
frames_temp_dir=None; best_heap=[]  # (ear, path)

try:
    while True:
        frame = cap.read(); h,w = frame.shape[:2]
        rgb = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        result = facemesh_model.process(rgb)

        status, col = "Processing…",(255,255,255)
        avgEAR=None

        if result.multi_face_landmarks:
            lm = result.multi_face_landmarks[0].landmark
            if len(lm) < MIN_LANDMARKS:
                status,col = "No face",(0,255,255)
                no_face+=1; blink=open_eye=drowsy=0
            else:
                avgEAR = avg_ear(lm, left_idx, right_idx, w, h)
                if avgEAR is not None:
                    if avgEAR < EAR_THRESH:
                        blink+=1
                        if blink<=BLINK_THRESH:
                            status,col = "Blink",(255,255,0)
                        else:
                            drowsy+=1
                            if drowsy>=DROWSY_THRESH and not recording:
                                # ───── start recording ─────
                                ts_base = datetime.now().strftime("%Y%m%d_%H%M%S")
                                datedir = datetime.now().strftime("%Y-%m-%d")
                                full_dir= os.path.join(base_video_folder, datedir)
                                os.makedirs(full_dir, exist_ok=True)

                                video_name = f"drowsy_{custom_name}_{ts_base}.mp4"
                                video_path = os.path.join(full_dir, video_name)
                                frames_dir = os.path.join(full_dir, f"frames_{custom_name}_{ts_base}")
                                os.makedirs(frames_dir, exist_ok=True)

                                frames_temp_dir = tempfile.mkdtemp()   # temp store
                                best_heap=[]

                                try:
                                    requests.post("http://localhost/ez1backup/admin/videosave.php",
                                                   data={"video": video_name,"camera":custom_name,
                                                         "date":datedir,"time":datetime.now().strftime("%H:%M:%S")})
                                except Exception as e: print("PHP log fail:", e)

                                vw=cv2.VideoWriter(video_path, cv2.VideoWriter_fourcc(*"H264"),30,(w,h))
                                recording=True; rec_start=datetime.now()
                            status,col = "Drowsy",(0,0,255)
                    else:
                        open_eye+=1
                        if open_eye>=OPEN_RESET: drowsy=0
                        blink=0; status,col="Not Drowsy",(0,255,0)

        # ── recording block ────────────────────────────────────
        if recording:
            elapsed=(datetime.now()-rec_start).total_seconds()
            if elapsed>=60:        # stop after 1 minute
                vw.release(); vw=None; recording=False; rec_start=None

                # pick best 20 frames
                os.makedirs(frames_dir, exist_ok=True)
                for i,(_,pth) in enumerate(sorted(best_heap)[:20]):
                    shutil.copy(pth, os.path.join(frames_dir,f"best_{i+1:02}.jpg"))
                shutil.rmtree(frames_temp_dir); frames_temp_dir=None; best_heap=[]
            else:
                vw.write(frame)
                if avgEAR is not None and frames_temp_dir:
                    fname = f"{avgEAR:.4f}_{datetime.now().strftime('%H%M%S_%f')}.jpg"
                    fpath = os.path.join(frames_temp_dir,fname)
                    cv2.imwrite(fpath, frame)
                    heapq.heappush(best_heap,(avgEAR,fpath))
                    if len(best_heap)>20:   # keep heap size ≤20
                        _,old = heapq.heappop(best_heap); os.remove(old)

        cv2.putText(frame,f"Drowsiness: {status}",(10,30),
                    cv2.FONT_HERSHEY_SIMPLEX,1,col,2)
        cv2.imshow("IP Camera Stream",frame)
        if cv2.waitKey(1)&0xFF==ord('q'): break

except KeyboardInterrupt:
    print("Interrupted")
finally:
    if vw: vw.release()
    if frames_temp_dir and os.path.isdir(frames_temp_dir): shutil.rmtree(frames_temp_dir)
    cv2.destroyAllWindows()
