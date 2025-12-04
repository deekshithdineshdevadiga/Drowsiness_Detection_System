import os
import requests
import time
from datetime import date
from datetime import datetime
import datetime as dt
# import sys
# from deepface import DeepFace	
#data=sys.argv[1]
#print(data)
# images=[]
# list files in img directory
fsize=0
# files = os.listdir(path)
# for file in files:
#         images.append(os.path.join(path, file))

# data1=''
# data2=''
# result=''
vc=0
if True:
	for filename in os.listdir('C:/xampp/htdocs/testing/python/log_data'):
		with open(os.path.join('C:/xampp/htdocs/testing/python/log_data', filename), 'r') as f:
			data=f.read()
			f.close()
			try:
				newdata= data.rstrip("~")
				arr = newdata.split("~")
				print(arr)
				print(filename)
				fsize=len(arr)-1
				d=newdata.split(",")
				emp_id=d[0]
				img_name=d[4]
			except:
				os.remove("C:/xampp/htdocs/testing/python/log_data/"+ filename)
    			
			# for x in images:
			# 	if emp_id in x:
			# 		c=c+1
			# 		print(x)

			data1=d[0]+","+d[1]+","+d[2]+","+d[3]+","+d[4]+","+d[5]+"~"
			response = requests.post('http://localhost/testing/admin/py_synk_data.php', data={'data': data1})	
			print(response.status_code)
			print(response.text)
			os.remove("C:/xampp/htdocs/testing/python/log_data/"+ filename)
						
	time.sleep(1)				
			


