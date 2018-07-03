#!/usr/bin/env python

import serial
import signal
import sys
import re
import MySQLdb
import time
import threading

hour_secs = 60 * 60 * 1000
keep = hour_secs * 12
ser = None
running = True

def get_env_data():
    global ser
    
    if ser != None:
        try:
            ser.write('01234t')
            return True
        except Exception as e: 
            print(e)
            time.sleep(0.5)
            return False
    else:
        return False
    

def do_something_every_runner(something,every,check):
    start = time.time() - every
    
    while running:
        if (time.time() - start) > every:
            if something():
                start = time.time()
        time.sleep(check)
    
    
def do_something_every(something,every,check=0.1):
    t = threading.Thread(target=do_something_every_runner, args=[something,every,check])
    t.start()


def signal_handler(signal, frame):
    global running 
    running = False
    print('You pressed Ctrl+C!')

signal.signal(signal.SIGINT, signal_handler)
print('Press Ctrl+C to stop')
        
def rotate_sql():
    date = int(time.time() * 1000)
    
    before = date - keep
    try:
        db=MySQLdb.connect(host="localhost",user="username",passwd="password",port=3306,db="database")
        c=db.cursor()
        c.execute("""DELETE FROM `environment` WHERE `date` < %s;""" % (before,))
        print("deleting before: %s" % before)
        db.commit()
        db.close()
        
        return True
    except Exception as e: 
        print(e)
        time.sleep(1)
        return False
        
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

wait_for_db()

do_something_every(rotate_sql,300)
do_something_every(get_env_data,5)

date = 0
mq2 = 0
mq4 = 0
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
            #print(m.group(1),m.group(2))
            if m.group(1) == "14":
                mq2 = m.group(2)
            if m.group(1) == "15":
                mq4 = m.group(2)
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
                
                #print(m.group(1),m.group(2))
                hum = m.group(1)
                temp = m.group(2)
                try:
                    db=MySQLdb.connect(host="localhost",user="username",passwd="password",port=3306,db="database")
                    c=db.cursor()
                    c.execute("""INSERT INTO `environment` (`date`, `mq2`, `mq4`, `mq7`, `mq8`, `mq135`, `humidity`, `temp`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s);""" % (date,mq2,mq4,mq7,mq8,mq135,hum,temp))
                    db.commit()
                    db.close()
                except Exception as e: 
                    print(e)
                    
                mq2 = 0
                mq4 = 0
                mq7 = 0
                mq8 = 0
                mq135 = 0
                hum = 0.0
                temp = 0.0
                
                
        
