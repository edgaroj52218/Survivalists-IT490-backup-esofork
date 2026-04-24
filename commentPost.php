<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://100.120.179.21:27017/");
$db = $client->survivalists_db;
$users = $db->reg_users;

// here we neeed to vali
if (!isset($_POST['post_id'], $_POST['post_owner'], $_POST['comment'])) {
    echo "invalidRequest";
    exit;
}

// we use the cookie insted to verify if the user is loggd in
if (!isset($_COOKIE['SessionKey'])) {
    echo "notLoggedIn";
    exit;
}

$currentUserDoc = $users->findOne(['keySession' => $_COOKIE['SessionKey']]);

if (!$currentUserDoc) {
    echo "invalidSession";
    exit;
}

$currentUser = $currentUserDoc['username'];

$postId = new MongoDB\BSON\ObjectId($_POST['post_id']);
$postOwner = $_POST['post_owner'];
$commentText = trim($_POST['comment']);

if ($commentText === "") {
    echo "emptyComment";
    exit;
}

// this part will add the comment to the post
$users->updateOne(
    ['username' => $postOwner,'posts._id' => $postId],
    ['$push' => ['posts.$.comments' => ['username' => $currentUser,'comment' => $commentText,'createdAt' => time()]]]);

echo "commentAdded";
