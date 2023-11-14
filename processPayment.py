import json
import redis
import requests
import logging

# Set your desired logging level
logging.basicConfig(level=logging.INFO)

channel_name = 'paymentChannel'
target_url = 'https://pay.rmuictonline.com'

redis_client = redis.StrictRedis(host='localhost', port=6379, db=0)
pubsub = redis_client.pubsub()
pubsub.subscribe(channel_name)

logging.info("Started...")

for message in pubsub.listen():
    logging.info("Listening...")

    if message['type'] == 'message':
        payment_data = json.loads(message['data'])

        try:
            response = requests.post(target_url, data=json.dumps(payment_data))
            response.raise_for_status()
            logging.info("Status: Success %s", payment_data)
        except requests.exceptions.RequestException as e:
            logging.error("Status: Error %s. Error message: %s", payment_data, str(e))
