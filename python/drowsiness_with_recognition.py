import cv2
import numpy as np
import mediapipe as mp
import os
import threading
import queue
import requests
from jproperties import Properties
from datetime import datetime
import face_recognition

# Suppress TensorFlow warnings
os.environ["TF_ENABLE_ONEDNN_OPTS"] = "0"
os.environ["TF_CPP_MIN_LOG_LEVEL"] = "3"

# Load configurations
configs = Properties()
with open("./EZWitness_config.properties", "rb") as read_properties:
    configs.load(read_properties)

# Get RTSP URL
rtsp_url = configs.get("rtsp_url_cam1").data
if rtsp_url is None:
    print("Error: RTSP URL not found in the config file.")
    exit()

print(f"RTSP URL: {rtsp_url}")
print("Capturing started...")

# Load known faces
known_face_encodings = []
known_face_names = []

known_faces_dir = "known_faces"
for filename in os.listdir(known_faces_dir):
    if filename.lower().endswith((".jpg", ".jpeg", ".png")):
        path = os.path.join(known_faces_dir, filename)
        image = face_recognition.load_image_file(path)
        encodings = face_recognition.face_encodings(image)
        if encodings:
            known_face_encodings.append(encodings[0])
            known_face_names.append(os.path.splitext(filename)[0])

# Initialize MediaPipe FaceMesh
mp_face_mesh = mp.solutions.face_mesh
facemesh_model = mp_face_mesh.FaceMesh(
    max_num_faces=1, refine_landmarks=True, min_detection_confidence=0.5, min_tracking_confidence=0.5
)

def distance(p1, p2):
    return np.linalg.norm(np.array(p1) - np.array(p2))

def get_ear(landmarks, indices, frame_width, frame_height):
    try:
        coords = [(int(landmarks[i].x * frame_width), int(landmarks[i].y * frame_height)) for i in indices if i < len(landmarks)]
        if len(coords) != 6:
            return None
        P2_P6 = distance(coords[1], coords[5])
        P3_P5 = distance(coords[2], coords[4])
        P1_P4 = distance(coords[0], coords[3])
        return (P2_P6 + P3_P5) / (2.0 * P1_P4)
    except:
        return None

def calculate_avg_ear(landmarks, left_eye, right_eye, width, height):
    left_ear = get_ear(landmarks, left_eye, width, height)
    right_ear = get_ear(landmarks, right_eye, width, height)
    if left_ear is None or right_ear is None:
        return None
    return (left_ear + right_ear) / 2.0

left_eye_idxs = [362, 385, 387, 263, 373, 380]
right_eye_idxs = [33, 160, 158, 133, 153, 144]

dc, blink_counter, no_face_frames, open_eye_counter, drowsy_frames = 0, 0, 0, 0, 0
NO_FACE_THRESHOLD = 10
BLINK_THRESHOLD = 6
DROWSINESS_THRESHOLD = 25
EAR_THRESHOLD = 0.18
OPEN_EYE_RESET = 10
MIN_LANDMARKS_THRESHOLD = 400

base_video_folder = "drowsiness_videos"
custom_name = "camera1"

class VideoCapture:
    def __init__(self, name):
        self.cap = cv2.VideoCapture(0) if name == "0" else cv2.VideoCapture(name, cv2.CAP_FFMPEG)
        if self.cap.isOpened():
            self.q = queue.Queue()
            t = threading.Thread(target=self._reader, daemon=True)
            t.start()
        else:
            print("Alert! Camera disconnected.")
            exit()

    def _reader(self):
        while True:
            ret, frame = self.cap.read()
            if not ret:
                print("Camera is not running. Exiting...")
                self.cap.release()
                os._exit(1)
            if not self.q.empty():
                try:
                    self.q.get_nowait()
                except queue.Empty:
                    pass
            self.q.put(frame)

    def read(self):
        return self.q.get()

cap = VideoCapture(rtsp_url)

video_writer, is_recording = None, False
record_start_time = None

try:
    while True:
        frame = cap.read()
        height, width, _ = frame.shape
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        results = facemesh_model.process(rgb_frame)

        # Face recognition
        small_frame = cv2.resize(frame, (0, 0), fx=0.25, fy=0.25)
        rgb_small_frame = small_frame[:, :, ::-1]
        face_locations = face_recognition.face_locations(rgb_small_frame)
        face_encodings = face_recognition.face_encodings(rgb_small_frame, face_locations)

        recognized_names = []
        for face_encoding in face_encodings:
            matches = face_recognition.compare_faces(known_face_encodings, face_encoding)
            name = "Unknown"
            face_distances = face_recognition.face_distance(known_face_encodings, face_encoding)
            if len(face_distances) > 0:
                best_match_index = face_distances.argmin()
                if matches[best_match_index]:
                    name = known_face_names[best_match_index]
            recognized_names.append(name)

        drowsiness_status, color = "Processing...", (255, 255, 255)

        if results.multi_face_landmarks:
            landmarks = results.multi_face_landmarks[0].landmark
            if len(landmarks) < MIN_LANDMARKS_THRESHOLD:
                drowsiness_status, color = "No face detected", (0, 255, 255)
                no_face_frames += 1
                blink_counter = open_eye_counter = drowsy_frames = 0
            else:
                no_face_frames = 0
                avg_ear = calculate_avg_ear(landmarks, left_eye_idxs, right_eye_idxs, width, height)
                if avg_ear is not None:
                    if avg_ear < EAR_THRESHOLD:
                        blink_counter += 1
                        if blink_counter <= BLINK_THRESHOLD:
                            drowsiness_status, color = "Blink", (255, 255, 0)
                        else:
                            drowsy_frames += 1
                            if drowsy_frames >= DROWSINESS_THRESHOLD and not is_recording:
                                timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
                                date_folder = datetime.now().strftime("%Y-%m-%d")
                                full_path = os.path.join(base_video_folder, date_folder)
                                os.makedirs(full_path, exist_ok=True)
                                video_filename = f"drowsy_{custom_name}_{timestamp}.mp4"
                                video_path = os.path.join(full_path, video_filename)

                                formatted_date = datetime.now().strftime("%Y-%m-%d")
                                formatted_time = datetime.now().strftime("%H:%M:%S")
                                person_name = recognized_names[0] if recognized_names else "Unknown"

                                try:
                                    response = requests.post("http://localhost/ez1backup/admin/videosave.php", data={
                                        "video": video_filename,
                                        "camera": custom_name,
                                        "date": formatted_date,
                                        "time": formatted_time,
                                        "person": person_name
                                    })
                                    print(f"Logged to PHP: {response.text}")
                                except Exception as e:
                                    print(f"Failed to send to PHP: {e}")
                                video_writer = cv2.VideoWriter(video_path, cv2.VideoWriter_fourcc(*"H264"), 30, (width, height))
                                is_recording = True
                                record_start_time = datetime.now()
                            drowsiness_status, color = "Drowsy", (0, 0, 255)
                    else:
                        open_eye_counter += 1
                        if open_eye_counter >= OPEN_EYE_RESET:
                            drowsy_frames = 0
                        blink_counter = 0
                        drowsiness_status, color = "Not Drowsy", (0, 255, 0)

        if is_recording:
            elapsed_time = (datetime.now() - record_start_time).total_seconds()
            if elapsed_time >= 60:
                video_writer.release()
                video_writer = None
                is_recording = False
                record_start_time = None
            else:
                video_writer.write(frame)

        # Draw drowsiness status
        cv2.putText(frame, f"Drowsiness: {drowsiness_status}", (10, 30), cv2.FONT_HERSHEY_SIMPLEX, 1, color, 2, cv2.LINE_AA)

        # Draw face names
        for (top, right, bottom, left), name in zip(face_locations, recognized_names):
            top *= 4
            right *= 4
            bottom *= 4
            left *= 4
            cv2.rectangle(frame, (left, top), (right, bottom), (0, 255, 0), 2)
            cv2.rectangle(frame, (left, bottom - 35), (right, bottom), (0, 255, 0), cv2.FILLED)
            cv2.putText(frame, name, (left + 6, bottom - 6), cv2.FONT_HERSHEY_DUPLEX, 0.9, (0, 0, 0), 1)

        cv2.imshow("IP Camera Stream", frame)
        if cv2.waitKey(1) & 0xFF == ord("q"):
            break

except KeyboardInterrupt:
    print("Exiting...")

finally:
    if video_writer:
        video_writer.release()
    cv2.destroyAllWindows()