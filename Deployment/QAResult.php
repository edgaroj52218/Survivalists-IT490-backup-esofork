#!/usr/bin/php

<?php

require_once('vendor/autoload.php');
$bundle = $argv[1];
$version = $argv[2];
// this is expect it to mark it as passed or failed
$status = $argv[3];
$env = $argv[4] ?? "QA";

$uri = 'mongodb://100.97.21.49:27017/';
$mongoClient = new MongoDB\Client($uri);
$database = $mongoClient->SocialTuneDeployment;
$collection = $database->packages;

$collection->insertOne(["bundle"=>$bundle, "version"=>$version, "status"=>$status, "env"=>$env, "timestamp"=>time()]);

echo "The status was updated to: $status on $env\n";

?>
