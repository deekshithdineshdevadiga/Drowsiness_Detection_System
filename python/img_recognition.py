from deepface import DeepFace
import cv2
import glob, os
import time
from datetime import date
from datetime import datetime
import datetime as dt
import uuid
from jproperties import Properties
import mediapipe as mp
from mediapipe.python.solutions.drawing_utils import _normalized_to_pixel_coordinates

mp_face = mp.solutions.face_detection.FaceDetection(
    model_selection=1, # model selection
    min_detection_confidence=0.5 # confidence threshold
)
mp_face_mesh = mp.solutions.face_mesh
face_mesh = mp_face_mesh.FaceMesh(min_detection_confidence=0.5, min_tracking_confidence=0.5)
configs = Properties()

multi_win=0
with open('fr_allconfig/EZWitness_config.properties', 'rb') as read_prop:
    configs.load(read_prop)
rtsp_url=configs.get("rtsp_url_cam1").data
database=configs.get("database_bw").data
database_bw=configs.get("database_bw").data
localsite=configs.get("localsite").data
detected_img=configs.get("detected_img").data
log_data=configs.get("log_data").data
log_unimg=configs.get("log_unimg").data
#message=configs.get("message_cam1").data
cam_name=configs.get("cam1_name").data
scr=configs.get("cam1_screen").data
main_display_img=configs.get("main_display_img").data
font_2fr=configs.get("font_2fr").data
font_fr=configs.get("font_fr").data
xml_file=configs.get("xml_file").data
detector_name = configs.get("detector_name").data
in_ip= configs.get("in_ip").data
in_port= configs.get("in_port").data
training_txt=configs.get("training_txt").data
training_img=configs.get("training_img").data  
l_count=0
unknown_count=0


while(True):
    # time.sleep(0.2)
    today = date.today()
    time_now = datetime.now()
    present_time= time_now.strftime("%H:%M:%S")
    f = open("img_recognition.txt", "w")
    f.write(str(present_time))
    f.close()
    specific_date = date.today()
    #specific_date = today
    folder_path = f'C:/xampp/htdocs/testing/admin/detected_img/{specific_date}/'
    image_files = glob.glob(os.path.join(folder_path, "*.jpg"))
    path_unknown = str(log_unimg)+"/"+str(specific_date)
    file_name=''

    for image_file in image_files:
        file_name = os.path.basename(image_file)
        if (os.path.exists(file_name)):
            print("no files")
            time.sleep(5)
            continue
        print("+++++++++++++++++++++++++++++++++++++++++++++++")
        print("++++++++++++++++NEXT IMAGE+++++++++++++++++++++")
        face=f"C:/xampp/htdocs/testing/admin/detected_img/{specific_date}/{file_name}"
        image=cv2.imread(face)
        if(True):
            current_time = datetime.now()
            date_format = "%Y-%m-%d_%H-%M-%S-%f"
            date1=current_time.strftime("%Y-%m-%d_%H-%M-%S-%f")
        
            file_path='C:/xampp/htdocs/testing/python/time.txt'
            if os.path.exists(file_path):
                with open(file_path,'r')as file:
                    date2=file.read()

                date_format = "%Y-%m-%d_%H-%M-%S-%f"
                datetime1 = datetime.strptime(date1, date_format)
                datetime2 = datetime.strptime(date2, date_format)
                difference = (datetime1 - datetime2).total_seconds()
                if(difference<10):
                    print("Sleeping mode")
                    time.sleep(10)
                    continue
            try: 
                
                    data = DeepFace.find(img_path = face,db_path = database,  model_name = "Facenet512",distance_metric ="cosine",enforce_detection=False,detector_backend="mediapipe")
                    #df=df[0]
# df=df[0]
                    print(data)
                    df=data[0]
                    match_img=df.iloc[:,0][0]
                    print(match_img)
                    rage_veri=df.iloc[:,-1][0]
                    xt = match_img.replace("\\", "/").split("/")
                    print(xt)

                    yt=xt[len(xt)-1]
                    p_imgid=yt[:-3]
                    print(p_imgid)
                    xna=p_imgid.split("_")
                    print("Match efidss :",xna)
                    efid=xna[0]
                    print("Match efid :",efid)
                    pname=xna[1]
                    rad=uuid.uuid1()
                    dup_count=0
                    print('--------------------------------------')
                    print('--------------------------------------')
                    print("Match Image :",match_img)
                    print("Range :",rage_veri)
                    print("Name :",pname)
                    print('--------------------------------------')  
	
                    if df.empty:
                        continue
                    my_dict = {}
	
                    for i in range(len(df)):
                        im=df.iloc[:,0][i].split("/")
                        db_img=im[-1]
                        de=db_img.split("_")
                        eid=de[0]
		
                        if eid in my_dict:
                            my_dict[eid] += 1
                        else:
                            my_dict[eid] = 1
                    print(my_dict)
                    max_key = max(my_dict, key=my_dict.get)
                    print("-------------------------------")
                    print("Max Key :",max_key)
                    if efid!=max_key:
                        print("Efid :",efid)
                        print("MaxKey :",max_key)
                        # if not isExist:
                        #     os.makedirs(path_attd)
                        # os.replace(f'/opt/lampp/htdocs/ez/admin/detected_img/{today}/{file_name}', f'/opt/lampp/htdocs/ez/python/log_unknownimg/{today}/{file_name}')
                        # continue
           

                    print(pname)
                    print("###############################################")
    

                    # today = date.today()
                    now = datetime.now()
                    current_time = now.strftime("%H:%M:%S")
                    ctime = now.strftime("%H-%M-%S-%f")
                    path_attd = str(localsite)+"/"+str(specific_date)
                    file_parts = file_name.split('_')
                    date_part = file_parts[1]  # '2024-04-04'
                    time_part = file_parts[2]
                    cam_name = file_parts[0]  # '13-07-26-209730.jpg'
                    print(file_name)
                    print(time_part)
                    # Split the time part based on hyphens
                    time_parts = time_part.split('-')
                    cap_time = time_parts[0] + ':' + time_parts[1] + ':' + time_parts[2]
                    print(cap_time)
          

                    print("writing log start")
                    f = open(str(log_data)+"/capture_data"+str(ctime)+".txt", "a")
                    edetail=efid+","+str(cam_name)+","+str(specific_date)+","+str(cap_time)+",/"+str(specific_date)+'/'+str(file_name)+','+str(rage_veri)+"~"
                    print("Log data :",edetail)
                    f.write(edetail)
                    f.close()
                    print("END")
                    
                    isExist = os.path.exists(path_attd)
                    if not isExist:
                        os.makedirs(path_attd)
                    os.replace(f'C:/xampp/htdocs/testing/admin/detected_img/{specific_date}/{file_name}', f'C:/xampp/htdocs/testing/admin/cap_img/{specific_date}/{file_name}') 
                    
                    fa = open(str(path_attd)+"/capture_VERIFY.txt", "a")
                    prevName=pname
                    edl=str(cam_name)+","+str(specific_date)+","+str(cap_time)+","+str(rage_veri)+","+str(yt)+","+str(specific_date)+'/'+str(file_name)+"~\n"
                    print("Verify text:",edl)
                    fa.write(edl)
                    fa.close()

                    # else:
                    #  print("Inside else")
                    #  raise Exception("Not identified")
                    
            except Exception as e:
                print("inside exception",e)
                isExist = os.path.exists(path_unknown)
                if not isExist:
                    os.makedirs(path_unknown)
                os.replace(f'C:/xampp/htdocs/testing/admin/detected_img/{specific_date}/{file_name}', f'C:/xampp/htdocs/testing/python/log_unknownimg/{specific_date}/{file_name}')
                      