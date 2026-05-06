<?php
require __DIR__.'/vendor/autoload.php';

if (!isset($_COOKIE['SessionKey'])) {
    header('Location: login.html');
    exit();
}

$client = new MongoDB\Client("mongodb://100.120.179.21:27017/");
$db = $client->survivalists_db;
$collection = $db->reg_users;
 // here we need to  get the rating from the url that i did on userProfile
$selectedRating = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;
$users = $collection->find();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Similar Rated Songs</title>
    <link rel="stylesheet" href="/user/style.css">
    <script src="https://kit.fontawesome.com/95d0fccd5e.js"></script>

    <style>
        body {
            background: #FDF5DF;
         }

        .container {
            width: 60%;
            margin: auto;
            text-align: center;
            display: unset !important;
        }

        h2 {
            margin: 30px 0;
            letter-spacing: 2px;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 15px;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 70%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .left i {
            font-size: 30px;
        }

        .info {
            text-align: left;
        }

        .stars {
            color: #f4c542;
            font-size: 20px;
        }

        .badge {
            background: #2c7a7b;
            width: 6px;
            height: 100%;
            border-radius: 10px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
<nav>
        <div class="nav-left">
            <h3 class="logo">SocialTune</h3>

            <!-- will eventually adjust nav items accordingly for future deliverables AKA these are just placeholders for now -->
            <!-- will use fontawesome icons for navbars -->

            <ul>
            <!-- updated links on nav bar -->
                <li><a href="feed.php">Feed</a></li>
                &nbsp;
                &nbsp;
                <li><a href="dashboard2.php">Search Library</a></li>
            </ul>
        </div>
        <div class="nav-right">
            <div class="nav-user-icon online">
                <a href="userProfile.php">Profile</a>
            </div>
        </div>

    </nav>
<div class="container">
    <h2>SIMILAR RATED SONGS</h2>

    <?php
     // foreach loop so im able to go through all the user we have
    foreach ($users as $user) {

        foreach ($user['posts'] as $post) {
		// if the post doesnt matcch the rating, then we just ignore it
            if (!isset($post['rating']) || $post['rating'] != $selectedRating) {
                continue;
             }

            $media = json_decode($post['media'], true);
            $title = $media['title'] ?? $media['name'];
            $type = $media['type'];
            $username = $user['username'];
            $rating = $post['rating'];
            $content = $post['content'];

            $stars = str_repeat("&#9733; ", $rating); //star based on the rating

            echo "
            <div class='card'>
                
                <div class='left'>
                    <div class='badge'></div>

                    <i class='fa-solid fa-circle-user'></i>

                    <div class='info'>
                        <strong>$username</strong><br>
                        $title | $type <br>
                        <small>$content</small>
                     </div>
                 </div>

                <div class='stars'>$stars</div>

            </div>
            ";
        }
    }
    ?>

</div>

</body>
</html>
