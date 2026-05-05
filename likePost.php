<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://100.120.179.21:27017/");
$db = $client->survivalists_db;
$users = $db->reg_users;

// this us to make sure get got right data from the request
if (!isset($_POST['post_id'], $_POST['post_owner'])) {
    http_response_code(400);
    echo "invalid_request";
    exit;
}

// here we wanna make sure we get the data from the cookie
if (!isset($_COOKIE['SessionKey'])) {
    echo "not_logged_in";
    exit;
}

$currentUserDoc = $users->findOne(['keySession' => $_COOKIE['SessionKey']]);
$currentUser = $currentUserDoc['username'];

$postId = new MongoDB\BSON\ObjectId($_POST['post_id']);
$postOwner = $_POST['post_owner'];

// here we get the user that is the owner  of the post 
$userDoc = $users->findOne(['username' => $postOwner]);

if (!$userDoc) {
    echo "user_not_found";
    exit;
}

// this goes through the post from the user to find the onw we  want
$postFound = null;

foreach ($userDoc['posts'] as $post) {
    if ((string)$post['_id'] === (string)$postId) {
        $postFound = (array)$post;
        break;
    }
}

if (!$postFound) {
    echo "post_not_found";
    exit;
}

$likes = isset($postFound['likes']) ? (array)$postFound['likes'] : [];

// here if the user already liked the post he/she wants,  we remove the like. The else statement manages the opposite
if (in_array($currentUser, $likes)) {

    $users->updateOne(
        ['username' => $postOwner,'posts._id' => $postId],
        ['$pull' => ['posts.$.likes' => $currentUser]]);

    echo "unliked";

} else {

    $users->updateOne(
        ['username' => $postOwner,'posts._id' => $postId],
        ['$addToSet' => ['posts.$.likes' => $currentUser]]);

    echo "liked";
}
