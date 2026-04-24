<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require __DIR__.'/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://100.120.179.21:27017/");
$db = $client->survivalists_db;
$users = $db->reg_users;

$allUsers = $users->find();

foreach ($allUsers as $user) {

    if (!isset($user['posts'])) continue;

    $updatedPosts = [];

    foreach ($user['posts'] as $post) {

        // here we nneed to make sure the object id actually exists on mongo
        if (!isset($post['_id'])) {
	    // in this part we do like a new mongo id (unique)for the post
            $post['_id'] = new MongoDB\BSON\ObjectId();
        }
        // this if is to make sure that the array for likes exist
        if (!isset($post['likes'])) {
            $post['likes'] = [];
          }

        if (!isset($post['comments'])) {
            $post['comments'] = [];
         }

        $updatedPosts[] = $post;
    }

    // this updates  the posts from the user in mongo
    $users->updateOne(
        ['_id' => $user['_id']],['$set' => ['posts' => $updatedPosts]]);
}

echo "everything was completed successfully!";
