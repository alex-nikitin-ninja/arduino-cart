<?php
// sudo chmod 777 /dev/ttyUSB0
// stty -F /dev/ttyUSB0 cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts

$fp = fopen("/dev/ttyUSB0", "r");
if (!$fp) {
	echo "Error opening port";
	exit();
}

$response = '';
while (1) {
	
	$response = '';
	while(substr_count($response, 'OK') === 0) {
		$response .= fread($fp, 1);
	}
	// print_r($response . "\n");

	// wait for X seconds
	usleep(0.1 * 1000 * 1000);
}

fclose($fp);
