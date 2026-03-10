<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

    // session authentication
	if (!isset($_COOKIE['SessionKey'])) { // WEB REFERENCE USED: https://www.geeksforgeeks.org/php/php-cookies/
		header('Location: login.html');
		exit();
	}

$username = $_POST['username'];

$client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

//this will show up on the queue
$createPost = array(
    'type' => 'createPost',
    'username' => $userInput,
    'media' => $media,
    'content' => $content,
    'postedAt' => time()
);

$serverResponse = $client->send_request($createPost);

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
