import cv2
import requests  
import time  


class IPVideoCapture:
    def __init__(self, cam_address, cam_force_address=None, blocking=False):
        """
        :param cam_address: ip address of the camera feed
        :param cam_force_address: ip address to disconnect other clients (forcefully take over)
        :param blocking: if true read() and reconnect_camera() methods blocks until ip camera is reconnected
        """

        self.cam_address = cam_address
        self.cam_force_address = cam_force_address
        self.blocking = blocking
        self.capture = None
        
        # NOTE: Can be changed. Used to throttle down printing
        self.RECONNECTION_PERIOD = 0.5  

        self.reconnect_camera()

    def reconnect_camera(self):
        print("Reconnecting...")
        while True:
            try:
                if self.cam_force_address is not None:
                    requests.get(self.cam_force_address)

                self.capture = cv2.VideoCapture(self.cam_address)

                if not self.capture.isOpened():
                    raise Exception("Could not connect to a camera: {0}".format(self.cam_address))

                print("Connected to a camera: {}".format(self.cam_address))

                break
            except Exception as e:
                print(e)

                if self.blocking is False:
                    break

                time.sleep(self.RECONNECTION_PERIOD)

    def read(self):
        """
        Reads frame and if frame is not received tries to reconnect the camera

        :return: ret - bool witch specifies if frame was read successfully
                 frame - opencv image from the camera
        """

        ret, frame = self.capture.read()

        if ret is False:
            self.reconnect_camera()

        return ret, frame