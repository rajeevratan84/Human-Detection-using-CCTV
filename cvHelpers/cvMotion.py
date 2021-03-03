import numpy as np


class cvMotion():

    def __init__(self):
        self.previous_roi = []
        self.first_frame = False
        self.frame_count = 0
        
    def firstFrame(self):
        """
        Detects if this is the first frame
        """
        if(self.frame_count >= 1):
            self.first_frame = False
            return self.first_frame

        if(self.previous_roi == None or self.previous_roi == [] ):
            self.first_frame = True
            self.frame_count += 1
            return self.first_frame


    def changeObserved(self, image, threshold = 100):
        """
        Detects if the frame has changed noticably from the previous frame.
        Returns True for first frame
        Returns covariance array if array of images passed
        """
        #covariance_array = []
        mse = []
        
        FirstFrame = self.firstFrame()

        if FirstFrame:
            self.previous_roi = np.zeros(image.shape, np.uint8)
        
        mse = self.mse(self.previous_roi, image)

        #Record frame for next image comparison
        self.previous_roi = image
        #print(mse)
        if FirstFrame:
            return True
        else:
            motion = mse > threshold
            #print(motion)
            return motion # True indicates change
    
    
    def mse(self, image1, image2):
        """Returns the mean square difference between two images.
        Values greater than 1 (typically range from 1 to 100 when changes occur)
        indicate a significant change in images.
        """
        err = 0
        try:
            gotdata1 = image1.shape[2]
        except IndexError:
            gotdata1 = None
        try:
            gotdata2 = image2.shape[2]
        except IndexError:
            gotdata2 = None
        if (gotdata1 is not None):
            image1 = cv2.cvtColor(image1, cv2.COLOR_BGR2GRAY)
            if (gotdata2 is not None):
                image2 = cv2.cvtColor(image2, cv2.COLOR_BGR2GRAY)
                err = 0
        try:
            err = np.sum((image1.astype("float") - image2.astype("float")) ** 2)
            err /= float(image1.shape[0] * image2.shape[1])
        except:
            pass
        return err
    
 