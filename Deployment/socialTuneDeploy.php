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

// info for the package
$bundleName = "socialtune";
$version = "1.0";
$status = "new";

// in this part im gonna insert the info to the package
$collection->updateOne(array("bundleName" => $bundleName, "version" => $version), array('$set' => array("status" => $status)), array("upsert" => true));



$client = new rabbitMQClient("testRabbitMQ.ini", "testDeployment");

$request = array("type" => "install", "bundle" => $bundleName, "version" => $version, "environment" => "QA");

$response = $client->send_request($request);

// printing the output
print_r(array("message" => "Deployment triggered", "bundle" => $bundleName, "version" => $version, "status" => $status,"response" => $response));

?>
