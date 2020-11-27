<?php
// sudo chmod 777 /dev/ttyUSB0
// stty -F /dev/ttyUSB0 cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts

function accelCalibration($v) {
	return floatval($v) * 255 / 100;
}

function wheelCalibration($v) {
	$wheelMidPoint = 70;
	return $wheelMidPoint + floatval($v) * 70 / 100;
}

function getPacket() {
	$f = file_get_contents("tmp/state.json");
	$f = json_decode($f, true);

	$f['accel'] = isset($f['accel']) ? $f['accel'] : '0';
	$f['wheel'] = isset($f['wheel']) ? $f['wheel'] : '0';

	$packet = "M:" . accelCalibration($f['accel']) . ";W:" . wheelCalibration($f['wheel']) . ";";
	return $packet;
}

// $fp = fopen("/dev/ttyUSB0", "w+");
// if(!$fp) {
// 	echo "Error opening port";
// 	exit();
// }

while (1) {
	// print_r(getPacket());
	// sleep(1);
	// // exit();
	fwrite($fp, getPacket());
	fread($fp, 2);
}

fclose($fp);
