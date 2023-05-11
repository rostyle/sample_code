import requests

url = ""
license_key = ""

payload = {"license_key": license_key}
response = requests.post(url, data=payload)

if response.status_code == 200:
    response_json = response.json()
    status = response_json.get("status", None)
    if status is not None:
        print(f"Status: {status}")
    else:
        print("Error: 'status' not found in the response.")
else:
    print(f"Error: Request failed with status code {response.status_code}")
