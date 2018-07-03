#!/usr/bin/env bash

mkdir /root/snapshots/`date +%Y_%m_%d` -p
mv /var/lib/motion/snapshots/*.jpg /root/snapshots/`date +%Y_%m_%d`/
cd /root/snapshots/`date +%Y_%m_%d`
ffmpeg -r 30 -pattern_type glob -i '*.jpg' -s hd720 -vcodec libx264 /root/timelapse/`date +%Y_%m_%d`.mp4 
rm -rf /var/lib/motion/snapshots/*
