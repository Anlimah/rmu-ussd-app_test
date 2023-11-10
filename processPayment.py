import json
import redis
import requests

channel_name = 'payment_channel'
target_url = 'https://test.pay.rmuictonline.com'

redis_client = redis.StrictRedis(host='localhost', port=6379, db=0)
pubsub = redis_client.pubsub()
pubsub.subscribe(channel_name)

print("Started...")
for message in pubsub.listen():
    print("Listening...", end=" ")
    if message['type'] == 'message':
        payment_data = json.loads(message['data'])
        print("\n", json.dumps(payment_data))
        response = requests.post(target_url, data=json.dumps(payment_data))
        if response.status_code == 200:
            print("Payment data sent successfully.")
        else:
            print("Error sending payment data:", response.text)
