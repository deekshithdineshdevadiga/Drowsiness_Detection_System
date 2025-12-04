import sys
from deepface import DeepFace
import cv2
import os
import time
from datetime import date
from datetime import datetime	
import shutil
from os import listdir
#configs = Properties()
import mediapipe as mp
from mediapipe.python.solutions.drawing_utils import _normalized_to_pixel_coordinates
os.chdir(os.path.dirname(os.path.abspath(__file__)))
mp_face = mp.solutions.face_detection.FaceDetection(
    model_selection=1, # model selection
    min_detection_confidence=0.5 # confidence threshold
)
mp_face_mesh = mp.solutions.face_mesh
face_mesh = mp_face_mesh.FaceMesh(min_detection_confidence=0.5, min_tracking_confidence=0.5)
#configs = Properties()
path = "enrollment_images/"
for image in os.listdir(path):
	frame=cv2.imread(path+image)
	print(frame)
	image_rows, image_cols, _ = frame.shape
    # image_input=frame
	image_input = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    # dount=dount+1
	results = mp_face.process(image_input)
	if not results.detections: 
		continue
	for detection in results.detections:
          print("@@@@@@@@@@@@@@@@@@@")
          try: 
              location = detection.location_data
              relative_bounding_box = location.relative_bounding_box
              rect_start_point = _normalized_to_pixel_coordinates(
				  relative_bounding_box.xmin, relative_bounding_box.ymin, image_cols,
				  image_rows)
              rect_end_point = _normalized_to_pixel_coordinates(
				  relative_bounding_box.xmin + relative_bounding_box.width,
				  relative_bounding_box.ymin + relative_bounding_box.height, image_cols,
				  image_rows)
             
              xleft,ytop=rect_start_point
              xright,ybot=rect_end_point

              face = image_input[ytop: ybot, xleft: xright]
              img_h, img_w, img_c = face.shape
              results1 = face_mesh.process(face)
              cv2.imwrite("database_bw/" + image, face)
              os.remove(path+image)
          except Exception as e: 
            print(e)
            continue
face=cv2.imread('aaa.jpg')
try:
    df = DeepFace.find(img_path = face,db_path = "./database_bw",  model_name = "Facenet512",distance_metric ="cosine",enforce_detection=False,detector_backend="mediapipe")
    print("----------------------------------------------")
except Exception as e:
    print("Exists")
#f=open('./pickle/pickle_Exists.txt', 'w')
#f.write("hi")
#f.close
source='./database_bw/representations_facenet512.pkl'
destination='./database'
shutil.copy(source,destination)




























