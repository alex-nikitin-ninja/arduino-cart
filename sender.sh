#!/bin/bash
stty -F /dev/ttyUSB0 cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts
echo "Press [CTRL+C] to stop.."

# while true
# do
# 	echo "$(cat packet.txt)" > /dev/ttyUSB0
# 	sleep 0.25
# done

php sender.php
