#!/usr/bin/php
<?php

require_once('rabbitMQLib.inc');
require_once('vendor/autoload.php');

function requestProcessor($request) {

    if (!isset($request['type'])) {
        return array("returnCode" => '1', "message" => "this is an invalid request type."); 
    }

    
    print_r($request);

    return ["returnCode" => 0];
}

$server = new rabbitMQServer("testRabbitMQ.ini", "deploymentServer");

echo "deploymentServer BEGIN".PHP_EOL;

$server->process_requests('requestProcessor');
echo "deploymentServer END".PHP_EOL;
?>

