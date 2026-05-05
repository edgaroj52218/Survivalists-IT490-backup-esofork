#!/usr/bin/php
<?php

require_once('vendor/autoload.php');

$uri = 'mongodb://100.97.21.49:27017/';
$mongoClient = new MongoDB\Client($uri);

$collection = $mongoClient->SocialTuneDeployment->versionCounter;

// here were getting the last version
$lastVersion = $collection->findOne(array("name" => "socialtune"));

// if it is null i add 0 so the increment starts at 1

if ($lastVersion === null) {
    $numberVersion = 0;
} else {
    $numberVersion = intval($lastVersion["version"]);
}

$newVersion = $numberVersion + 1;

$collection->updateOne(array("name" => "socialtune"), array('$set' => array("version" => $newVersion)), array('upsert'=>true));
echo $newVersion;

?>
