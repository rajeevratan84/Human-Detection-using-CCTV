# import the necessary packages
import numpy as np
import os
import cv2
from os import listdir
from os.path import isfile, join
import imutils
import time
import copy 
from imutils.io import TempFile
from yolo3 import YoloDetector
from cvHelpers.framerate import SimpleFrameRate
from cvHelpers.cvMotion import cvMotion
from cvHelpers.timer import Timer
from cvHelpers.videoCapture import IPVideoCapture
from notifications import TwilioNotifier
from configuration.settings import Conf
from database.rdsDB import RDSDatabase
from notifications import MessageGenerator

conf = Conf("config.json")
tn = TwilioNotifier(conf)
objDetector = YoloDetector(conf)
db = RDSDatabase(conf)
messenger = MessageGenerator(conf)

motionDetector = cvMotion()
fps = SimpleFrameRate()
timer = Timer()
disarmTimer = Timer()


# Check Camera Box ROIs and insert default if not registed
boxes = objDetector.getBoxCords(db.getROI())
print("Boxes = ", boxes)
if boxes:
    print("Default boxes are: " + str(boxes))
else:
    print("Adding Default Boxes")
    db.addDefaultBoxSettings()

# Check Alarm Status
if db.getAlarmStatus() is not None:
    armed = int(db.getAlarmStatus())
    if armed:
        print("Alarm is Armed")
    else:
        print("Alarm is Disarmed")
else:
    # Add default values
    armed = 1
    print("Adding default settings for new customer")
    db.addDefaultSchedule()
    
send_notification = True

CAM_ADDRESS = conf["camera_rtsp_address"]
#cap = IPVideoCapture(0) 
cap = IPVideoCapture(CAM_ADDRESS) 

print("here 1")
frame_count = 0
ret, frame = cap.read()
print("here 2")
cv2.imwrite("test.jpg",frame)

frame = imutils.resize(frame, width=480)
displayFrame = frame.copy()

W = None
H = None
motion_detected = False 

while True:
    ret, frame = cap.read()
    frame_count += 1
    fps.addFrame()
    
    if frame_count % 180 == 0:
        # Get ROI Boxed Region of Interest
        boxes = objDetector.getBoxCords(db.getROI())
        # Check armed status
        alarm_stats = int(db.getAlarmStatus())
        if db.getAlarmStatusSchedule() != alarm_stats:
            print("User changed alarm")
            timerCount2 = disarmTimer.getTimer()
            print("Disarm Timer = " + str(timerCount2))
            disarmTimer.startTimer()
                           
            if disarmTimer.getTimer() >  conf["disarm_threshold_seconds"]:
                db.updateAlarmStatus(db.getAlarmStatusSchedule())
                disarmTimer.stopResetTimer()
                
        if alarm_stats == 0:
            print("Disarmed!")
            send_notification, armed = False, False 
        else:
            print("Armed!")
            armed = True
            if timerCount == 0:
                send_notification = True
            if  timerCount > conf["notification_threshold_seconds"]:
                send_notification = True
                timer.stopResetTimer()

    if frame_count % 15 == 0:
        print((time.strftime('%H:%M:%S', time.gmtime(frame_count/30))))
        print("FPS -", str(FPS))
        
        
        if frame is None:
            break

        # resize the frame and convert the frame to grayscale
        #frame = imutils.resize(frame, width=480)
        #gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        #displayFrame = frame.copy()
        #motion_detected = motionDetector.changeObserved(gray)
        #print( 'Motion Detected' + str(motion_detected))
        #if motionDetector.changeObserved(frame):
        #ourString = 'Motion Detected'
        #cv2.putText(displayFrame, ourString, (50,50), cv2.FONT_HERSHEY_SIMPLEX, 1, (240,170,0), 2)
        if armed:
            personDetected, person_count, frame = objDetector.getDetectedObjects(frame, boxes)
        else:
            personDetected = False
            
        # we ensure at least one detection exists
        if personDetected:# or motion_detected:
            print(armed, send_notification)
            smsSent =  copy.copy(send_notification)
            
            if armed:
                tempImg = TempFile(ext=".jpg")
                cv2.imwrite(tempImg.path, frame)  
                cv2.putText(frame, str(personDetected), (10,460), cv2.FONT_HERSHEY_SIMPLEX,2, (0,255,0), 2)
                timer.startTimer()
                print("Person Triggered Alarm")
                try:
                    sms_body = messenger.getSMSBody(person_count)
                    print(sms_body)
                    tn.send(sms_body, tempImg, smsSent)   
                    url = "https://icstoragett.s3.us-east-2.amazonaws.com" + tempImg.path[2:]   
                    db.updateCustomerAlertDB(smsSent, sms_body, url)
                    send_notification = False          
                except:
                    print("sending failed")
                    pass            
                
        timerCount = timer.getTimer()
        print("Timer = " + str(timerCount))
        
        if  timerCount > conf["notification_threshold_seconds"]:
            send_notification = True
            timer.stopResetTimer()
            print("Timer Reset")
    
    FPS = 'FPS: {0:.2f}'.format(fps.getFrameRate())
    #imgOut = imutils.resize(displayFrame, width=960)
    cv2.putText(frame, FPS, (10,460), cv2.FONT_HERSHEY_PLAIN, 2, (0,255,0), 2)

    cv2.imshow('CCTV', frame)
    #cv2.imshow("detections", frame)

    if cv2.waitKey(1) == 13: #13 is the Enter Key
        break

    # if the frame dimensions are empty, set them
    if W is None or H is None:
        (H, W) = frame.shape[:2]
            
cap = cv2.VideoCapture(0)
cap.release()
cv2.destroyAllWindows()