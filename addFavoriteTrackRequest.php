<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

    // session authentication
	if (!isset($_COOKIE['SessionKey'])) { // WEB REFERENCE USED: https://www.geeksforgeeks.org/php/php-cookies/
		header('Location: login.html');
		exit();
	}

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

// access stored session key
// find corresponding User object in reg_users database w/ that session key
// access that User object and store its username

$addFavoriteTrackRequest = [
	'type' => 'addFavoriteTrack',
	'username' => $_POST['username'],
	'title' => $_POST['title'],
	'artist' => $_POST['artist']
];

$response = $client->send_request($addFavoriteTrackRequest);

//this is to see if it works
echo "<pre>";
print_r($serverResponse);
echo "</pre>";
?>
<!DOCTYPE html>
<html>
<body>
<a href="feed.html">View post.</a>
</body>
</html>
