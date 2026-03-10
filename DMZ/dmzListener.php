#!/usr/bin/php
<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require_once('../path.inc');
require_once('../get_host_info.inc');
require_once('../rabbitMQLib.inc');
require '../../vendor/autoload.php';
require_once('api/tidalAPI.php');

$uri = 'mongodb://100.105.160.23:27017/';
$mongoClient = new MongoDB\Client($uri);
$database = $mongoClient->survivalists_db;
$tidalDatabase = $database->tidal_db;

function searchRequest($userInput, $userFilters = null) {

    global $tidalDatabase;

    $tidalDb = $tidalDatabase;

    $searchInfo = $tidalDb->findOne(array('userInput' => $userInput));

    if ($searchInfo && (time() - $searchInfo['time']) < 604800) { // hold data for 7 days
        $result = $searchInfo['results'];
    } else {
        $apiResult = userSearch($userInput);

        if ($searchInfo) {
            $tidalDb->updateOne(array('userInput' => $userInput),
               ['$set' => array('results' => $apiResult, 'time' => time())]);

        } else {
	    $tidalDb->insertOne(array('userInput' => $userInput, 'results' => $apiResult, 'time' => time()));

	}

	$result = $apiResult;
    }

    // i referenced the following link to understand isset with array elements: https://www.php.net/manual/en/function.isset.php
    // i need to fix this since is giving me successful artists, but i dont get albums or tracks.
    if ($userFilters && isset($result['data']['relationships'][$userFilters]['data'])) {
	$dataFiltered = $result['data']['relationships'][$userFilters]['data'];
	$finalResults = [];

	foreach ($dataFiltered as $data) {
	    foreach ($result['included'] as $includedData) {
		if ($includedData['id'] == $data['id']) {
		    $finalResults[] = $includedData;
	 	}
	    }
   	}

	$result = $finalResults;
    }

	print_r(array("returnCode" => '0', "userInput" => $userInput, "results" => $result));
	return(array("returnCode" => '0', "userInput" => $userInput, "results" => $result)); 
}

function requestProcessor($request) {

    if (!isset($request['type'])) {
        return array("returnCode" => '1', "message" => "This is an invalid request type.");
    }

    switch ($request['type']) {
        case "searchRequest":
            $userFilters = null;
            if (isset($request['userFilters'])) {
                $userFilters = $request['userFilters']; 
            }
	    return searchRequest($request['userInput'], $userFilters);
    }

    return array("returnCode" => '0', "message" => "Server received request and processed");
}

$dmzServer = new rabbitMQServer("testRabbitMQ.ini", "testDMZ");

echo "dmzRabbitMQServer BEGIN".PHP_EOL;
$dmzServer->process_requests('requestProcessor');
echo "dmzRabbitMQServer END".PHP_EOL;
exit();
?>  
