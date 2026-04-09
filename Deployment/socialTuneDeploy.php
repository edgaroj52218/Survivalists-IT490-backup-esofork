#!/usr/bin/php
<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');
require_once('vendor/autoload.php');

// this allows me to frab the args  (bundle name, version, and env)
$bundle = $argv[1];
$version = $argv[2];
$env = strtoupper($argv[3]);


// trying to do failed and passed
$status = $argv[4] ?? "new";

// Connection to the deployment mongo
$uri = 'mongodb://100.97.21.49:27017/';
$mongoClient = new MongoDB\Client($uri);

$database = $mongoClient->SocialTuneDeployment;
$collection = $database->packages;

// this allows me to insert the new arriving version to db 
if ($status == "new") {
   $collection->insertOne(["bundle"=>$bundle, "version"=>$version, "status"=>"new", "env"=>$env, "timestamp"=>time()]);
} else {
   $collection->insertOne(["bundle"=>$bundle, "version"=>$version, "status"=>$status, "env"=>$env, "timestamp"=>time()]);
}

client = new rabbitMQClient("testRabbitMQ.ini","deploymentServer");

$request = ["type"=>"install","bundle"=>$bundle,"version"=>$version,"environment"=>$env];

$response = $client->send_request($request);

echo "Deployment triggered\n";

?>
