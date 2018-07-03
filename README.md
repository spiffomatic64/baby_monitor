# baby_monitor
netv and raspberrypi based baby monitor

## Demo
[![Nerdy Baby monitor](http://i3.ytimg.com/vi/h77OJGggoXA/hqdefault.jpg)](https://www.youtube.com/watch?v=h77OJGggoXA)

### Current features:
- Hdmi overlay via mjpg
- Custom solution to avoid memory leak in netv browser
- motion activation
- sound actiation
- timelapse nightly
- Environment monitoring (temp/hum/gas sensors)
- Graphing data using plotly

### Requirements:
hardware: netv, raspberrypi, webcam, microphone, arduino, bunch-o-sensors (im using the mq2,5,7, and dht22)
software: mysql, python, apache, php, motion
python libraries: MySQLdb, serial, pyaudio


Im leaving out a ton, if you want to try this, let me know and Ill add whatever is missing.
