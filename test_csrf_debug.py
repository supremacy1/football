import http.client, urllib.request, urllib.parse, http.cookiejar
http.client.HTTPConnection.debuglevel = 1
cj = http.cookiejar.CookieJar()
opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))
url = 'http://localhost:8000/register'
print('GET', url)
resp = opener.open(url)
print('GET status', resp.getcode())
print('Cookies after GET:')
for c in cj:
    print(c)
body = resp.read().decode('utf-8')
from html.parser import HTMLParser
class TokenParser(HTMLParser):
    def __init__(self):
        super().__init__()
        self.token=None
    def handle_starttag(self, tag, attrs):
        if tag=='input':
            d=dict(attrs)
            if d.get('name')=='_token':
                self.token=d.get('value')
parser=TokenParser()
parser.feed(body)
print('token', parser.token)
print('POSTing token with cookies')
data = urllib.parse.urlencode({'_token': parser.token, 'name':'Test', 'email':'test@example.com', 'favorite_club_id':'1', 'password':'Password123!', 'password_confirmation':'Password123!'}).encode()
req = urllib.request.Request(url, data=data)
resp = opener.open(req)
print('POST status', resp.getcode())
print('POST final URL', resp.geturl())
print('Cookies after POST:')
for c in cj:
    print(c)
