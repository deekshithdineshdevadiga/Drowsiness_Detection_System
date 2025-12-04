
from deepface import DeepFace
from retinaface import RetinaFace
import matplotlib.pyplot as plt
import cv2
import numpy as np
import glob, os
import time
from datetime import date
from datetime import datetime
import datetime as dt
import imutils
from PIL import ImageFont, ImageDraw, Image
import random
import uuid
import threading, queue
import subprocess
import sys
import keyboard
from deepface.detectors import FaceDetector
import threading

from jproperties import Properties
configs = Properties()

multi_win=0
with open('/var/www/html/kmc/python/fr_allconfig/kiocl_config.properties', 'rb') as read_prop:
    configs.load(read_prop)

rtsp_url=configs.get("rtsp_picsys").data
database=configs.get("database").data
localsite=configs.get("localsite").data
log_data=configs.get("log_data").data
log_unimg=configs.get("log_unimg").data
message=configs.get("message_cam2").data
cam_name=configs.get("cam2_name").data
scr=configs.get("cam2_screen").data
main_display_img=configs.get("main_display_img").data
font_2fr=configs.get("font_2fr").data
font_fr=configs.get("font_fr").data
xml_file=configs.get("xml_file").data
detector_name = configs.get("detector_name").data

font_2 = ImageFont.truetype(font_2fr, 60) 

if True:
        def display_window():
                multi_win=0
                get = lambda cmd: subprocess.check_output(cmd).decode("utf-8")

                # get the data on all currently connected screens, their x-resolution
                screendata = [l.split() for l in get(["xrandr"]).splitlines() if " connected" in l]
                screendata = sum([[(w[0], s.split("+")[-2]) for s in w if s.count("+") == 2] for w in screendata], [])

                def get_class(classname):
                        # function to get all windows that belong to a specific window class (application)
                        w_list = [l.split()[0] for l in get(["wmctrl", "-l"]).splitlines()]
                        return [w for w in w_list if classname in get(["xprop", "-id", w])]


                try:
                        # determine the left position of the targeted screen (x)
                        pos = [sc for sc in screendata if sc[0] == scr][0]
                except IndexError:
                        # warning if the screen's name is incorrect (does not exist)
                        print(scr, "does not exist. Check the screen name")
                else:
                        for w in get_class('OUTface'):
                                # first move and resize the window, to make sure it fits completely inside the targeted screen
                                # else the next command will fail...
                                subprocess.Popen(["wmctrl", "-ir", w, "-e", "0,"+str(int(pos[1])+100)+",100,300,300"])
                                # maximize the window on its new screen
                                subprocess.Popen(["xdotool", "windowsize", "-sync", w, "100%", "100%"])
                multi_win=multi_win+1

# bufferless VideoCapture
class VideoCapture:

  def __init__(self, name):
    self.cap = cv2.VideoCapture(name,cv2.CAP_FFMPEG)
    print(self.cap)
    if(self.cap.isOpened()):
      self.q = queue.Queue()
      t = threading.Thread(target=self._reader)
      t.daemon = True
      t.start()
    else:
      print("Alert ! Camera disconnected")
      #err_msg=='ERROR'
      
      #kiocl_err = cv2.imread("kmc_image/kmc_main.jpeg")
      #cv2.imshow('Face Recognition', kiocl_err)
      
  # read frames as soon as they are available, keeping only most recent one
  #def cam_error():
    #draw.rectangle(((19, 145), (1376, 732)), fill="white")
    #draw.text((400, 220), 'Camera is not Working, Please contact Administrator', font=font_2,fill="#ff0000")

  def _reader(self):
    global err_msg
    while True:
      ret, frame = self.cap.read()
      #cv2.waitKey(1)
      if not ret:
        print("cam is not running")
        err_msg=1
        break
        
      if not self.q.empty():
        try:
          self.q.get_nowait()   # discard previous (unprocessed) frame
        except queue.Empty:
          pass
      self.q.put(frame)

  def read(self):
    #self.lock = threading.Lock()
    #self.lock.acquire()
    q_resl=self.q.get()
    #self.lock.release()
    return q_resl


start_time = time.time()
reg_name=''
#Load the cascade
#face_cascade = cv2.CascadeClassifier('haarcascade_frontalface_default.xml')
#faceCascade = cv2.CascadeClassifier('/home/inkadmin/kmc/xmlfile/haarcascade_frontalface_default.xml')
kmcimage = cv2.imread(main_display_img)
# Convert the image to RGB (OpenCV uses BGR)  
cv2_im_rgb = cv2.cvtColor(kmcimage,cv2.COLOR_BGR2RGB)
# Pass the image to PIL  
pil_im = Image.fromarray(cv2_im_rgb) 
draw = ImageDraw.Draw(pil_im) 
font = ImageFont.truetype(font_fr, 60)
draw.text((450, 53), message, font=font,fill="#000")
#video_cap = cv2. VideoCapture("/home/ezmlr/hostel_FR/out_video/2022-03-11-17-00-44.mp4",cv2.CAP_FFMPEG)

video_cap = VideoCapture(rtsp_url)
#if err_msg=='ERROR':
  #print("error")
  #kmcimage = cv2.imread("/home/inkadmin/kmc/kmc_image/kmc_main.jpeg")
  #cv2.imshow('OUTface', kmcimage)
#video_cap = VideoCapture('rtsp://admin:VEER_12345@192.168.1.121:554/cam/realmonitor?channel=1&subtype=1')
faceCascade = cv2.CascadeClassifier(xml_file)
c=0
new_image='';
fcount=0
i=1
yax=120
dount=0
name_list=[]
dup_name=''
active=0
dup_count=0
dis_count=0
rad = uuid.uuid1()
font = ImageFont.truetype(font_2fr, 35)

global err_msg
err_msg=0
while(True):
    dis_count=dis_count+1
    n = datetime.now()
    date_time = n.strftime("%d/%m/%Y, %I:%M:%S %p")

    
    cv2_im_processed = cv2.cvtColor(np.array(pil_im), cv2.COLOR_RGB2BGR)
    imS = cv2.resize(cv2_im_processed, (1280, 730))
    cv2.namedWindow("OUTface", cv2.WND_PROP_FULLSCREEN)
    cv2.setWindowProperty("OUTface",cv2.WND_PROP_FULLSCREEN,cv2.WINDOW_FULLSCREEN)

    cv2.imshow('OUTface', imS)

    if err_msg==1:
        print('err inside'+str(err_msg))
        kmcimage = cv2.imread(main_display_img)
    # Convert the image to RGB (OpenCV uses BGR)
        # Pass the image to PIL  
        draw.rectangle(((19, 145), (1376, 732)), fill="white")
        draw.text((400, 220), 'Please contact Administrator', font=font_2,fill="#ff0000")
        #print('err last'+str(err_msg))
        display_window()
        cv2.waitKey(1)
        continue
    
    font = ImageFont.truetype(font_2fr, 35)
    draw.rectangle(((892, 85), (1271, 120)), fill="white")
    draw.text((870, 78), str(date_time), font=font,fill="#000")   

    frame = video_cap.read()
  
    if 1 == 2:
        print("Nothing in the frame")
        video_cap = VideoCapture(rtsp_url)
        #video_cap =VideoCapture('rtsp://admin:VEER_12345@192.168.1.121:554/cam/realmonitor?channel=1&subtype=1')
      
        continue;

    else:
        img = frame
        pre_face=frame
        #frame = frame[10:580, 50:530] 
        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        faces = faceCascade.detectMultiScale(gray)
        faces = faceCascade.detectMultiScale(
            gray,
            scaleFactor=1.4,
            minSize=(100, 100),
            minNeighbors=5,
            maxSize=(220, 220)
        )
        #detector = FaceDetector.build_model(detector_name)
        #faces = FaceDetector.detect_faces(detector, detector_name, img)
        face_count=0
        # Draw rectangle around the faces
        #for face_reg in faces:
        for (x, y, w, h) in faces:
            #face, region = face_reg
            #face_count=face_count+1
            #if face_count>5:
                #break
            #cv2.imshow('Mysec system', face) 
            face = img[y:y+h, x:x+w]
            #cv2.imshow('Mysec system', face) 
            #cv2.waitKey(50)
            time.sleep(0.05)
            pre_face=face
            
	#cv2.putText(img, str(id), (x+5,y-5), font, 1, (255,255,255), 2)
            c = c + 1
            try:
                df = DeepFace.find(img_path = face,db_path = database,  model_name = "VGG-Face",distance_metric ="cosine",enforce_detection=False,detector_backend="skip")
                match_img=df.iloc[:,0][0]
                rage_veri=df.iloc[:,1][0]
            except:
               # cv2.imwrite('log_unknownimg/capture'+ str(c) +'.jpg', face) #save the image
                print("cccccccccccccc")
                continue
            xt = match_img.split("/")
            yt=xt[7]
            zt=yt.split(".")
            p_imgid=zt[0]
            xna=p_imgid.split("_")
            efid=xna[0]
            pname=xna[1]
           
            rad=uuid.uuid1()
            dup_count=0
            #for dup_name in name_list:
            print('--------------------------------------')
            print(match_img)
            print(rage_veri)
            print(pname)
            print('--------------------------------------')
            #Same name skipimg

            if(pname in name_list):
              #dup_count=1
              continue
		
            if rage_veri < 0.15:
                print('*****************************')
                print(match_img)
                print(rage_veri)
                print(pname)
                print('***************************')
                if i==6:
                    draw.rectangle(((19, 145), (1376, 732)), fill="white")
                    l_count=len(name_list)
                    if l_count>0:
                        del name_list[0]
                    i=1
                #if reg_name != pname and dup_count==0:
               
                if True:
                    dount=0
                    dis_count=0
                    #cv2.rectangle(img, (x, y), (x+w, y+h), (0, 255, 0), 2)
                    #cv2.putText(img, str(p_imgid), (x+5,y-5), font, 1, (255,255,255), 2)
                    uname=pname
                    name_list.append(uname)
                    
                    #size_list = len(name_list)
                    display_name(name_list)
                    
                    #cv2.rectangle(img, (x, y), (x+w, y+h), (0, 255, 0), 2)
                    #cv2.putText(img, str(p_imgid), (x+5,y-5), font, 1, (255,255,255), 2)
                    
                    radIMG=uuid.uuid1()
                    cv2.imwrite(str(localsite)+'/img_'+ str(radIMG) +'.jpg', face) #save the image
                    #cv2.imwrite('/opt/lampp/htdocs/kmc/admin/images/img_'+ str(radIMG) +'.jpg', face) #save the image
                    f = open(str(log_data)+"/capture_data.txt", "a")
                    today = date.today()
                    now = datetime.now()
                    current_time = now.strftime("%H:%M:%S")
                    eid=xt[0]
                    edetail=efid+","+str(cam_name)+","+str(today)+","+str(current_time)+",img_"+str(radIMG)+".jpg,"+str(rage_veri)+"~"
                    f.write(edetail)
                    f.close()
                    
                    i=i+1
                    
                    
                    reg_name=pname
            '''else:
                now = datetime.now()
                unix_num = uuid.uuid4()
                unknow_id=str(unix_num)[:6]
                f = open(str(log_data)+"/capture_data.txt", "a")
                today = date.today()
                current_time = now.strftime("%H:%M:%S")
                edetail=str(unknow_id)+","+str(cam_name)+","+str(today)+","+str(current_time)+",img_"+str(rad)+".jpg,0.00000~"
                cv2.imwrite(str(log_unimg)+'/img_'+ str(rad) +'.jpg', face) #save the image
                f.write(edetail)
                f.close()'''
       
        
        if dount>5 and len(name_list)>0:
                dount=0
                i=1
                l_count=0
                reg_name=''
                l_count=len(name_list)
                del name_list[0]
                delete_display_name(name_list)
                
                
                #cv2.imshow('Face Recognition', imS)
        def display_name(disname_lst):
                draw.rectangle(((19, 145), (1376, 732)), fill="white") 
                name_len=len(disname_lst) 
                if name_len>4:
                        del name_list[0]
                if name_len<3:
                        dis_y=380
                else:
                        dis_y=220 
                
                for att_name in name_list:
                        draw.text((465, dis_y), att_name, font=font_2,fill="#000")
                        dis_y=dis_y+80
        def delete_display_name(disname_lst):
                dis_y=220
                draw.rectangle(((19, 145), (1376, 732)), fill="white")
                for att_name in name_list:
                        draw.text((465, dis_y), att_name, font=font_2,fill="#000")
                        dis_y=dis_y+80

        if multi_win==0:
                multi_win=multi_win+1
                display_window()
       
        #cv2.imshow('Mysec system', frame)        
        cv2.waitKey(1)
        dount=dount+1

cv2.destroyAllwindows()
