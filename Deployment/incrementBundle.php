#!/usr/bin/php
<?php

require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');
require_once('vendor/autoload.php');

$uri = 'mongodb://100.97.21.49:27017/';
$mongoClient = new MongoDB\Client($uri);

$collection = $mongoClient->SocialTuneDeployment->versionCounter;

// here were getting the last version
$lastVersion = $collection->findOne(array("name" => "socialtune"));

// incrementing by one 
$newVersion = intval($lastVersion["version"]) + 1;

$collection->updateOne(array("name" => "socialtune"), array('$set' => array("version" => $newVersion)));
echo $newVersion;

?>
