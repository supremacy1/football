import urllib.request
req = urllib.request.Request('http://localhost:8000/register', headers={'User-Agent':'Mozilla/5.0'})
res = urllib.request.urlopen(req)
print(res.getcode())
for k, v in res.headers.items():
    if k.lower().startswith('set-cookie'):
        print(k + ': ' + v)
    if k.lower() == 'set-cookie':
        print('EXACT SET-COOKIE:', v)
print('ALL HEADERS')
print(res.headers)
