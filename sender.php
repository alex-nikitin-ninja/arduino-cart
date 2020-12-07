<?php
// sudo chmod 777 /dev/ttyUSB0
// stty -F /dev/ttyUSB0 cs8 9600 ignbrk -brkint -icrnl -imaxbel -opost -onlcr -isig -icanon -iexten -echo -echoe -echok -echoctl -echoke noflsh -ixon -crtscts

function accelCalibration($v) {
	$r = floatval($v) * 255 / 100;
	$r = intval($r);
	return $r;
}

function wheelCalibration($v) {
	$servoMidPoint = 90;
	$trim = -7;
	$r = $servoMidPoint + floatval($v) * 90 / 100 + $trim;
	$r = max(0, min(intval($r), 180));
	return $r;
}

function getPacket() {
	$f = file_get_contents("tmp/state.json");
	while ($f === false) {
		$f = file_get_contents("tmp/state.json");
	}
	$f = json_decode($f, true);

	$packet = false;
	if (!is_null($f)) {
		$f['accel'] = isset($f['accel']) ? $f['accel'] : '0';
		$f['wheel'] = isset($f['wheel']) ? $f['wheel'] : '0';

		$packet = "M:" . accelCalibration($f['accel']) . ";W:" . wheelCalibration($f['wheel']) . ";";
	}

	return $packet;
}

$fp = fopen("/dev/ttyUSB0", "w+");
if (!$fp) {
	echo "Error opening port";
	exit();
}

$response = '';
while (1) {
	$packet = getPacket();
	print_r($packet);
	print_r("\n");
	if ($packet !== false) {
		fwrite($fp, $packet);
	}

	// wait for X seconds
	usleep(0.05 * 1000 * 1000);
}

fclose($fp);
