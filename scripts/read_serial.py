#!/usr/bin/env python

import serial
import signal
import sys
import re
import MySQLdb
import time
import threading

ser = None
running = True

def send_to_serial(data):
    global ser
    
    if ser != None:
        ser.write(data)
        return True
    else:
        return False
    

def do_something_every_runner(something,args,every,check):
    start = time.time() - every
    
    while running:
        if (time.time() - start) > every:
            if something(args):
                start = time.time()
        time.sleep(check)
    
    
def do_something_every(something,args,every,check=0.1):
    t = threading.Thread(target=do_something_every_runner, args=[something,args,every,check])
    t.start()


def signal_handler(signal, frame):
    global running 
    running = False
    print('You pressed Ctrl+C!')

signal.signal(signal.SIGINT, signal_handler)
print('Press Ctrl+C to stop')


# Here we connect to a mysql database named "test" that is running locally
#db=MySQLdb.connect(host="localhost",user="root",passwd="starcraft",port=3306,db="basement")
# This creates a cursor which allows us to use "stored procedures"
#c=db.cursor()

do_something_every(send_to_serial,'01234t',5)

date = 0
mq2 = 0
mq5 = 0
mq7 = 0
mq8 = 0
mq135 = 0
hum = 0.0
temp = 0.0

    
with serial.Serial('/dev/ttyUSB0', 9600, timeout=1) as ser:
    while running == True:
        line = ser.readline().strip()
        #Analog pin: (\d+) Value: (\d+)
        #Humidity: (\d+\.\d+) %\s+Temperature: (\d+\.\d+)
        
        m = re.search('Analog pin: (\d+) Value: (\d+)', line)
            
        if m:
            print(m.group(1),m.group(2))
            if m.group(1) == "14":
                mq2 = m.group(2)
            if m.group(1) == "15":
                mq5 = m.group(2)
            if m.group(1) == "16":
                mq7 = m.group(2)
            if m.group(1) == "17":
                mq8 = m.group(2)
            if m.group(1) == "18":
                mq135 = m.group(2)
                
        else:
            m = re.search('Humidity: (\d+\.\d+) %\s+Temperature: (\d+\.\d+)', line)
        
            if m:
                date = int(time.time() * 1000)
                
                print(m.group(1),m.group(2))
                hum = m.group(1)
                temp = m.group(2)
                
                
        
