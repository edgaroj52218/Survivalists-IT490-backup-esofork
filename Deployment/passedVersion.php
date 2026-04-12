#!/usr/bin/php
<?php

require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');
require_once('vendor/autoload.php');

$uri = 'mongodb://100.97.21.49:27017/';
$mongoClient = new MongoDB\Client($uri);
$collection = $mongoClient->SocialTuneDeployment->packages;

$passedVersion = $collection->findOne(array("bundle"=>"socialtune", "status"=>"passed"), array("sort"=>array("timestamp>

// if it cant find a passed version i added this logic so it gives us an error instead of not showing anything.
if ($passedVersion == null) {
    exit(1);
}

echo $passedVersion["version"];
?>
