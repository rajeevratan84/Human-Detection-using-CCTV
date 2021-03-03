# import the necessary packages
from twilio.rest import Client
import boto3
from threading import Thread
import os


class TwilioNotifier:
	def __init__(self, conf):
		# store the configuration object
		self.conf = conf

	def send(self, msg, tempImage, send_notification):
		# start a thread to upload the file and send it
		t = Thread(target=self._send, args=(msg, tempImage, send_notification,))
		t.start()

	def _send(self, msg, tempImage, send_notification):
		# create a s3 client object
		s3 = boto3.client("s3",
			aws_access_key_id=self.conf["aws_access_key_id"],
			aws_secret_access_key=self.conf["aws_secret_access_key"],
		)

		# get the filename and upload the video in public read mode
		filename = tempImage.path[tempImage.path.rfind("/") + 1:]
		s3.upload_file(tempImage.path, self.conf["s3_bucket"],
			filename, ExtraArgs={"ACL": "public-read",
			"ContentType": "image/jpg"})

		# get the bucket location and build the url
		location = s3.get_bucket_location(
			Bucket=self.conf["s3_bucket"])["LocationConstraint"]
		url = "https://s3-{}.amazonaws.com/{}/{}".format(location,
			self.conf["s3_bucket"], filename)
		print("sent to AWS S3 Bucket")

		# initialize the twilio client and send the message
		if send_notification:
			client = Client(self.conf["twilio_sid"],
				self.conf["twilio_auth"])
			client.messages.create(to=self.conf["twilio_to"], 
				from_=self.conf["twilio_from"], body=msg, media_url=url)
			print("SMS sent")
			#client = Client(self.conf["twilio_sid"], self.conf["twilio_auth"])
			#client.messages.create(to=self.conf["twilio_to_2"], from_=self.conf["twilio_from"], body=msg, media_url=url)
			#print("SMS 2 sent")
		# delete the temporary file
		os.remove(filename)
		#tempImage.cleanup()