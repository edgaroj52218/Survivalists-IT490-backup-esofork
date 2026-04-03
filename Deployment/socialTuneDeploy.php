#!/usr/bin/php
<?php

error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require __DIR__.'vendor/autoload.php';

// Connection to the deployment mongo
$uri = 'mongodb://100.97.21.49:27017/';
$mongoClient = new MongoDB\Client($uri);

$database = $mongoClient->SocialTuneDeployment;
$collection = $database->packages;



?>
