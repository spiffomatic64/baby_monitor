#!/usr/bin/env python

import pyaudio
import struct
import math
from datetime import datetime
import time
import signal
import sys
import re
import MySQLdb
import smtplib
import audioop

min_interval = 4 * 60
interval_to_check = min_interval * 5 
hour_secs = 60 * 60 * 1000
keep = hour_secs * 12

FORMAT = pyaudio.paInt16
CHANNELS = 1
RATE = 44100
CHUNK = RATE /8

def get_vol(stream):
    data = stream.read(CHUNK,exception_on_overflow=False)
    rms = audioop.rms(data, 2)    # here's where you calculate the volume
    return rms
        
stop = False        
        
def signal_handler(signal, frame):
    global stop 
    stop = True
    print('You pressed Ctrl+C!')
    
def wait_for_db():
    db = False

    while db == False:
        try:
            # Here we connect to a mysql database named "test" that is running locally
            db=MySQLdb.connect(host="localhost",user="username",passwd="password",port=3306,db="database")
            # This creates a cursor which allows us to use "stored procedures"
            c=db.cursor()
        except Exception as e: 
            print(e)
            time.sleep(1)
            
    db.close()
        
signal.signal(signal.SIGINT, signal_handler)
print('Press Ctrl+C to stop')
        
p = pyaudio.PyAudio()

stream = p.open(format=FORMAT,
                channels=CHANNELS,
                rate=RATE,
                input=True,
                frames_per_buffer=CHUNK)

counter = interval_to_check
start_time = time.time()
data = []

db = False

wait_for_db()

while stop == False:
    counter += 1
    
    vol = get_vol(stream)
    data.append(vol)
    date = int(time.time() * 1000)
    
    if counter % 4 == 0:
        temp = 0
        for x in data:
            temp += x
        volume = temp / len(data)
        db=MySQLdb.connect(host="localhost",user="username",passwd="password",port=3306,db="database")
        c=db.cursor()
        c.execute("""INSERT INTO `volume` (`date`, `volume`) VALUES (%s, %s);""" % (date,volume,))
        db.commit()
        db.close()
        #print(date,volume)
        data = []
        
    if counter > interval_to_check:
        before = date - keep
        db=MySQLdb.connect(host="localhost",user="username",passwd="password",port=3306,db="database")
        c=db.cursor()
        c.execute("""DELETE FROM `volume` WHERE `date` < %s;""" % (before,))
        print("deleting before: %s" % before)
        db.commit()
        db.close()
        counter = 0
        
        

