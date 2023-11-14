import json
import redis
import requests

channel_name = 'paymentChannel'
target_url = 'https://pay.rmuictonline.com'

redis_client = redis.StrictRedis(host='localhost', port=6379, db=0)
pubsub = redis_client.pubsub()
pubsub.subscribe(channel_name)

for message in pubsub.listen():
    print("Listening...", end=" ")
    if message['type'] == 'message':
        payment_data = json.loads(message['data'])
        response = requests.post(target_url, data=json.dumps(payment_data))
        if response.status_code == 200:
            print("Status: Success", {
                "pay_category":payment_data["pay_category"],
                "country_code":payment_data['country_code'],
                "phone_number":payment_data["phone_number"],
                "pay_method":payment_data["pay_method"],
                "amount":payment_data["amount"]})
        else:
            print("Status: Error",{
                "pay_category":payment_data["pay_category"],
                "country_code":payment_data['country_code'],
                "phone_number":payment_data["phone_number"],
                "pay_method":payment_data["pay_method"],
                "amount":payment_data["amount"]})
