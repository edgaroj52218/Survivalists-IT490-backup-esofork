<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__.'/vendor/autoload.php';

$client = new MongoDB\Client("mongodb://100.120.179.21:27017/");
$db = $client->survivalists_db;
$users = $db->reg_users;

$allUsers = $users->find();

foreach ($allUsers as $user) {
    // here if the user doesnt have posts we just skip them
    if (!isset($user['posts'])) {
        continue;
    }

    $updatedPosts = [];
    $needsUpdate = false;

    foreach ($user['posts'] as $post) {
        // if postStatus isn't there were just gonna add it as public by default
        if (!isset($post['postStatus'])) {
            $post['postStatus'] = 'public';
            $needsUpdate = true;
        }

        $updatedPosts[] = $post;
    }

    // here were only gonna update mongo if the  actually changed the post
    if ($needsUpdate) {
        $users->updateOne(
            ['_id' => $user['_id']],
            ['$set' => ['posts' => $updatedPosts]]
        );
    }
}

echo "All the posts now have the postStatus field";
?>
