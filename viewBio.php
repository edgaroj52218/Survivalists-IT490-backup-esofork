<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__.'/vendor/autoload.php';

if (!isset($_COOKIE['SessionKey'])) {
    header('Location: login.html');
    exit();
}

$client = new MongoDB\Client("mongodb://100.120.179.21:27017/");
$db = $client->survivalists_db;
$userCollection = $db->reg_users;

$currentUser = $userCollection->findOne(['keySession' => $_COOKIE['SessionKey']]);

$username = $_GET['username'] ?? null;

if (!$username) {
    die("the username was not found");
}

$profileUser = $userCollection->findOne(['username' => $username]);

if (!$profileUser) {
    die("the username wasnot found");
}

$posts = $profileUser['posts'] ?? [];
$bio = $profileUser['bio'] ?? [];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $username ?> Profile</title>
    <link rel="stylesheet" href="/user/style.css">
    <script src="https://kit.fontawesome.com/95d0fccd5e.js"></script>
</head>

<body>

<nav>
    <div class="nav-left">
        <h3 class="logo">SocialTune</h3>
        <ul>
            <li><a href="feed.php">Feed</a></li>
            <li><a href="dashboard2.php">Search Library</a></li>
        </ul>
    </div>
</nav>

<div class="profile-container">
     <!-- bio part -->
    <div class="post-container">
        <div class="post-row">
            <div class="user-profile">
                <i class="fa-solid fa-circle-user"></i>
                <div>
                    <p><?= $profileUser['username'] ?></p>
                    <span><?= $bio['email'] ?? '' ?></span>
                </div>
            </div>
        </div>

        <p class="post-text">
            <strong>Name:</strong> <?= $bio['name'] ?? '-' ?><br>
            <strong>DOB:</strong> <?= $bio['dob'] ?? '-' ?><br>
            <strong>Age:</strong> <?= $bio['age'] ?? '-' ?><br>
             <strong>Facebook:</strong> <?= $bio['facebook'] ?? '-' ?><br>
             <strong>Instagram:</strong> <?= $bio['instagram'] ?? '-' ?>
        </p>
    </div>
</div>

</body>
</html>
