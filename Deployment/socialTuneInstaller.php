#!/usr/bin/php
<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');
require_once('vendor/autoload.php');


// Connection to the deployment mongo

$uri = 'mongodb://100.97.21.49:27017/';
$mongoClient = new MongoDB\Client($uri);

$database = $mongoClient->SocialTuneDeployment;
$collection = $database->packages;

// this will handle the requests from the install  that are coming from rabbitmq and then it will update 
// the package status on mongo
function requestProcessor($request)
{
    global $collection;

    if (!isset($request['type'])) {
        return array("returnCode" => 1, "message" => "Invalid request");
    }

    if ($request['type'] == "install") {

        $bundle = $request['bundle'];
        $version = $request['version'];
         $environment = $request['environment'];

        echo "Installing $bundle version $version on $environment...\n";

       $status = "passed";
       if ($version != "1.0") {
           $status = "failed";
       }
        // here im trying to update the mongo status
        $collection->updateOne(array("bundleName" => $bundle,"version" => $version), array('$set' => array("status" => $status, "environment" => $environment)), array("upsert" => true));

        echo "The installation was complete: $status\n";

        return array("returnCode" => 0, "message" => "Installed successfully");
    }

    return array("returnCode" => 1, "message" => "Unknown type");
}

$server = new rabbitMQServer("testRabbitMQ.ini", "testDeployment");

echo "SocialTune Installer running...\n";

$server->process_requests('requestProcessor');
