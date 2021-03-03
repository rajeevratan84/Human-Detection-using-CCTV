import timeit

class SimpleFrameRate():
    def __init__(self):
        self.nLast = 0.0
        self.nCount = 0
        self.timer = timeit.default_timer()
        
    def checkClock(self):
        newTime = timeit.default_timer()
        timeDiff = newTime - self.timer
        
        if timeDiff > 1:
            self.nLast = self.nCount / timeDiff
            self.nCount = 0
            self.timer = newTime            
      
    def addFrame(self):
        self.nCount = self.nCount + 1
        
    def getFrameRate(self):
        self.checkClock()
        return self.nLast    
    