import time
from datetime import datetime, timedelta

d = datetime.now() - timedelta(hours=4, minutes=0)

d.strftime('%d %b %Y, %I:%M:%S %p')

class MessageGenerator:
    def __init__(self, conf):
        # store the configuration object
        self.conf = conf
  
    def getSMSBody(self, person_count):
        #timeStr = time.ctime()
        timeS = datetime.now() - timedelta(hours=4, minutes=0)
        timeStr = timeS.strftime('%d %b %Y, %I:%M:%S %p')
        if person_count == 1:
            camera_info = self.conf["camera_info"]
            msg = "A person has been detected in your " + camera_info + " at " + timeStr
            return msg
        if person_count > 1:
            camera_info = self.conf["camera_info"]
            msg = str(person_count) + " persons have been detected in your " + camera_info + " camera at " + timeStr
            return msg