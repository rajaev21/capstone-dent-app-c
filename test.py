import time 
import hashlib

print(time.ctime())

name = "test name"
hashed = hashlib.md5(name.encode()).hexdigest()
if name == hashed:
    print(hashed)