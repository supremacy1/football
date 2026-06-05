import http.cookiejar
import urllib.request
import urllib.parse
import re
import time

cj = http.cookiejar.CookieJar()
op = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(cj))
req = urllib.request.Request('http://localhost:8000/register', headers={'User-Agent': 'Mozilla/5.0'})
res = op.open(req)
html = res.read().decode('utf-8', 'ignore')
m = re.search(r'<input[^>]+name=["\']_token["\'][^>]+value=["\']([^"\']+)["\']', html)
print('TOKEN_FOUND', bool(m))
if not m:
    raise SystemExit(1)
token = m.group(1)
print('TOKEN', token[:10] + '...')
unique = str(int(time.time()))
data = {
    '_token': token,
    'name': 'Test User ' + unique,
    'username': 'testuser' + unique,
    'email': 'test' + unique + '@example.com',
    'date_of_birth': '1990-01-01',
    'favorite_club_id': '',
    'password': 'Password123!',
    'password_confirmation': 'Password123!',
}
encoded = urllib.parse.urlencode(data).encode('utf-8')
req2 = urllib.request.Request('http://localhost:8000/register', data=encoded, headers={'User-Agent': 'Mozilla/5.0'})
res2 = op.open(req2)
print('STATUS', res2.getcode())
print('URL', res2.geturl())
body = res2.read().decode('utf-8', 'ignore')
print('BODY_START', body[:500])
