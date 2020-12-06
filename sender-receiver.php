<?php

$receiver = popen("php receiver.php", 'w');
$sender = popen("php sender.php", 'w');

pclose($receiver);
pclose($sender);


