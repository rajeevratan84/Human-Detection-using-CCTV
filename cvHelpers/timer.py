import timeit
import time
from datetime import datetime
from datetime import date

class Timer():
    def __init__(self):
        self.timeDiff = 0
        self.timerReset = False 
        self.timerStarted = False
    
    def startTimer(self):
        if self.getTimer() is 0:
            self.timerReset = False
            self.timeDiff = False
            self.startTime = datetime.now()
            self.timerStarted = True
    
    def getTimer(self):
        if self.timerStarted:
            if not self.timerReset:
                self.timeDiff = (datetime.now() - self.startTime).seconds
            else:
                self.timeDiff = 0
        return self.timeDiff
    
    def restartTimer(self):
        self.timeDiff = 0
        self.startTime = datetime.now()
        
    def stopResetTimer(self):
        self.timerReset = True
        self.timeDiff = 0
        self.startTime = datetime.now()
        self.timerStarted = False