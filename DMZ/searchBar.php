<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

require_once(__DIR__.'/path.inc');
require_once(__DIR__.'/get_host_info.inc');

require_once(__DIR__.'/rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testDMZ");



?>
