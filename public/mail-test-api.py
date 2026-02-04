import requests

url = "https://diy-lab.net/mail-api.php"

data = {
    "api_key": "your-secret-key",
    "to": "chaser.cb750@gmail.com",
    "subject": "【DIY LAB】メールAPIテスト",
    "body": "これはPythonから送信したテストメールです。"
}

response = requests.post(url, data=data)

print("status:", response.status_code)
print("body:", response.text)
