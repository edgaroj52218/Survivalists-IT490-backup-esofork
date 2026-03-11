<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

    // session authentication
	if (!isset($_COOKIE['SessionKey'])) { // WEB REFERENCE USED: https://www.geeksforgeeks.org/php/php-cookies/
		header('Location: login.html');
		exit();
	}

// $username = $_POST['username'];

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

$addFavoriteArtistRequest = [
	'type' => 'addFavoriteArtist',
	'username' => $_POST['username'],
	'artist' => $_POST['artist']
];

$serverResponse = $client->send_request($addFavoriteArtistRequest);

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
