<?php
	// sudo chmod 777 /dev/ttyUSB0
	// stty -F /dev/ttyUSB0 cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts

	$fp = fopen("/dev/ttyUSB0", "w+");
	if(!$fp) {
		echo "Error opening port";
		die();
	}

	fwrite($fp, '' . 0x00);
	
	echo fread($fp, 2);

	fclose($fp);