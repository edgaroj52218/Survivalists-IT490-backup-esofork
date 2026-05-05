<?php
require __DIR__.'/vendor/autoload.php';

if (!isset($_COOKIE['SessionKey'])) {
    exit();
}

$uri = "mongodb://100.120.179.21:27017/";
$client = new MongoDB\Client($uri);
$db = $client->survivalists_db;
$userCollection = $db->reg_users;

$user = $userCollection->findOne(['keySession' => $_COOKIE['SessionKey']]);
$username = $user['username'];

 // this part i use the POST method to being able to get the data.
$postIndex = (int)$_POST['postIndex'];
$newStatus = $_POST['postStatus'];

 // i fixed this part so it could only allow thee valid values, and if it is invalid, itll send them back to userProfile
if ($newStatus !== 'public' && $newStatus !== 'private') {
    header('Location: userProfile.php');
    exit();
}

// for this part i used this reference: https://www.php.net/manual/en/function.iterator-to-array.php
//  the idea here is to pull the posts array,being able to update the right one and then we can push it back
$posts = iterator_to_array($user['posts']);
if (isset($posts[$postIndex])) {
     $posts[$postIndex]['postStatus'] = $newStatus;
    $userCollection->updateOne( //fixed this since it was not updating correctly.
         ['username' => $username],
          ['$set' => ['posts' => array_values($posts)]]);
}

header('Location: userProfile.php');
exit();
?>
