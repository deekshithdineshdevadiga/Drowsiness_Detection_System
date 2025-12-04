from imutils import paths
import face_recognition
import os
import cv2
import pickle

path = "/var/www/html/ez/python/database_bw/"
PICKLE_FILE = "database/face_encodings.pkl"  # Define the pickle file name

known_encodings = []  # Initialize empty lists
known_names = []

image_files = [f for f in os.listdir(path) if f.endswith(('.jpg', '.jpeg', '.png'))]
total_files = len(image_files)

print(f"Found {total_files} images in the directory.")

for i, filename in enumerate(image_files, 1):
    img_path = os.path.join(path, filename)
    print(f"[{i}/{total_files}] Processing: {filename}...")

    image = face_recognition.load_image_file(img_path)
    face_locations = face_recognition.face_locations(image)
    encodings = face_recognition.face_encodings(image, face_locations)

    if len(encodings) > 0:
        known_encodings.append(encodings[0])
        known_names.append(os.path.splitext(filename)[0])
        print(f"✓ Face found in {filename}")
    else:
        print(f"✗ No face found in {filename}")

# Save to pickle file
data = {"encodings": known_encodings, "names": known_names}
with open(PICKLE_FILE, "wb") as f:
    pickle.dump(data, f)

print(f"Face encoding process completed. Data saved to {PICKLE_FILE}.")