#!/usr/bin/php
<?php

require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');
require_once('vendor/autoload.php');

$uri = 'mongodb://100.97.21.49:27017/';
$mongoClient = new MongoDB\Client($uri);
$collection = $mongoClient->SocialTuneDeployment->packages;

$newVersion = $collection->findOne(array("bundle"=>"socialtune", "status"=>"new"), array("sort"=>array("timestamp"=>-1)>

// if it cant find a new version i added this logic so it gives us an error instead of not showing anything.
if ($newVersion == null) {
    exit(1);
}
echo $newVersion["version"];
?>
