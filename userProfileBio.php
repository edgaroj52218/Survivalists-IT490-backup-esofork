<?php
require __DIR__.'/vendor/autoload.php';

if (!isset($_COOKIE['SessionKey'])) { // WEB REFERENCE USED: https://www.geeksforgeeks.org/php/php-cookies/
    header('Location: login.html');
    exit();

} else {
    $uri = "mongodb://100.120.179.21:27017/";

    $client = new MongoDB\Client($uri);
    $database = $client->survivalists_db;
    $userCollection = $database->reg_users;

    $userCurrent = $userCollection->findOne(['keySession' => $_COOKIE['SessionKey']]);
    
    // Target profile user
    $username = $_GET['username'] ?? null;

    if (!$username) {
        die("the user wasnt found");
    }

    $user = $userCollection->findOne(['username' => $username]);

    if (!$user) {
        die("the user wast found");
    }

    $username = $user['username'];
     $bio = $user['bio'] ?? [];

}
?>

<!-- video ref used for basic page template: https://www.youtube.com/watch?v=NljIHlZRTTE (PT 1) -->

<!-- video ref used for basic page template: https://www.youtube.com/watch?v=RrWUAmh93r4 (PT 2) -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile | SocialTune</title>
    <link rel="stylesheet" href="/user/style.css">
    <script src="https://kit.fontawesome.com/95d0fccd5e.js" crossorigin="anonymous"></script>
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

    <!-- profile page -->
    <div class="profile-container">
        <!-- <img src="../images/cover.png" alt="cover photo" class="cover-img"> -->
        <div class="profile-details">
            <div class="pd-left">
                <div class="pd-row">
                    <i class="fa-solid fa-circle-user"></i>
                    <div>
                        <?php
                        echo "<h3>";

                        // RETRIEVE USERNAME BY LOOKING UP STORED SESSION KEY
                        $username = $user['username'];
                        echo $username;

                        echo "</h3>";
                        ?>
                        <span><?= $bio['email'] ?? '' ?></span>
                        <?php
                        echo "<p>";

                        // <p>RETRIEVE FOLLOWER COUNTER FROM DATABASE</p>
                        $followerCount = count($user['followers']);

                        if ($followerCount == null || $followerCount == 0) {
                            echo "No followers yet.";
                        } else {
                            echo "Followed by $followerCount other(s)";
                        }
                        echo "</p>";

                        ?>
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

        <div class="profile-info">

            <div class="info-col">
                <div class="profile-intro">
                    <div class="title-box">
                        <h3>Following</h3>
                    </div>

                    <?php
                    
                    echo "<div class='friends-box'>";

                    // RETRIEVE USERNAMES OF PEOPLE USER IS FOLLOWING
                    // will use foreach loop to retrieve each username of the items in the logged in User's follower array
                    // REF for foreach loop w/ arrays: https://www.php.net/manual/en/control-structures.foreach.php
                    // REF for pulling logged in User object data from Mongo: https://stackoverflow.com/questions/26716035/display-mongodb-collections-using-html-file
                    $following = $user['following'];

                    foreach ($following as $document) {

                        echo "<div class='user-curation'>";
                        echo "<i class='fa-solid fa-user'>";
                        echo "&nbsp";
//			echo "<a href='userProfile.php?='";
//			echo "urlencode($document)";
                        echo ($document);
			//echo "</a>";
                        echo "</i>";
                        echo "</div>";
                    };

                    echo "</div>";
                    ?>
                </div>
                <div class="profile-intro">
                    <div class="title-box">
                        <h3>Favorite Tracks</h3>
                    </div>
                    <!-- <p>RETRIEVE TRACKS FROM USER_LIBRARY DATABASE</p> -->
                    <?php
                    echo "<div class='friends-box'>";

                    // will use foreach loop to retrieve each track of the items in the logged in User's favoriteTracks
                    $favoriteTracks = $user['library']['favoriteTracks'];
                    // $favTrackTitle = json_encode($favoriteTracks['title']);
                    // $favTrackArtist = json_encode($favoriteTracks['artist']);

                    foreach ($favoriteTracks as $document) {
                        // echo "<i class='fa-solid fa-user'>";
                        // echo "</i>";

                        echo "<div class='user-curation'>";

                        $favTrackTitle = ($document['title']);
                        $favTrackArtist = ($document['artist']);
                        echo "$favTrackTitle by $favTrackArtist";
                        echo "</div>";
                    };

                    echo "</div>";
                    ?>
                </div>
                <div class="profile-intro">
                    <div class="title-box">
                        <h3>Favorite Artists</h3>
                    </div>
                    <!-- <p>RETRIEVE ARTISTS FROM USER_LIBRARY DATABASE</p> -->
                    <div class="friends-box">
                        <!-- if statement that adds users to box when user has followers/friends -->
                        <?php
                        echo "<div class='friends-box'>";

                        // will use foreach loop to retrieve each artist in the logged in User's favoriteArtist array
                        $favoriteArtists = $user['library']['favoriteArtists'];
                        // $favTrackArtist = json_encode($favoriteArtists['artist']);

                        foreach ($favoriteArtists as $document) {
                            // echo "<i class='fa-solid fa-user'>";
                            // echo "</i>";

                            echo "<div class='user-curation'>";

                            $favArtist = ($document['artist']);
                            echo "$favArtist";
                            echo "</div>";
                        };

                        echo "</div>";
                        ?>
                    </div>
                </div>
                <div class="profile-intro">
                    <div class="title-box">
                        <h3>Favorite Albums</h3>
                    </div>
                    <!-- <p>RETRIEVE ALBUMS FROM USER_LIBRARY DATABASE</p> -->
                    <div class="friends-box">
                        <!-- if statement that adds users to box when user has followers/friends -->
                        <?php
                        echo "<div class='friends-box'>";

                        // will use foreach loop to retrieve each album in the logged in User's favoriteAlbum array
                        $favoriteAlbums = $user['library']['favoriteAlbums'];
                        // $favTrackAlbum = json_encode($favoriteTracks['album']);

                        foreach ($favoriteAlbums as $document) {
                            // echo "<i class='fa-solid fa-user'>";
                            // echo "</i>";

                            echo "<div class='user-curation'>";

                            $favAlbum = ($document['album']);
                            $favArtist = ($document['artist']);
                            echo "$favAlbum by $favArtist";
                            echo "</div>";
                        };

                        echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
            <div class="post-col">
                <!-- commented out the write post container because searchBar.php will be implemented from kate, so media and content can be populated and inserted on that page instead -->

                <!-- <div class="write-post-container">
                    <div class="user-profile">
                        <i class="fa-solid fa-circle-user"></i>                    
                    </div>

                </div> -->

                <?php
                $userPosts = $user['posts'];
                foreach ($userPosts as $document) {
		    $post = (array)$document;
	            $postStatus = isset($post['postStatus']) ? $post['postStatus'] : 'public'; 
           	    if ($postStatus === 'private' && $user['username'] !== $userCurrent['username']) {
                        continue;
                    }

                    echo "<div class='post-container'>";
                    echo "<div class='post-row'>";
                    echo "<div class='user-profile'>";
                    echo "<i class='fa-solid fa-circle-user'>";
                    echo "</i>";
                    // <!-- will modify to user icons instead of images -->
                    echo "<div>";
                    // <!-- <p>RETRIEVE USERNAME FROM POST DATABASE</p> -->

                    echo "<p>";

                    // RETRIEVE USERNAME BY LOOKING UP STORED SESSION KEY
                    $username = $user['username'];

                    // user posts are populated as objects into posts array will need to access them as strings or arrays to get the postDetails
                    // ref (went with the accessing of the MongoDB document's properties): https://www.mongodb.com/community/forums/t/accessing-object-value-from-nested-objects-in-mongodb-with-php/200733


                    $media = $document->media;
                    $content = $document->content;
                    $postedAt = $document->postedAt;

		    // ratings which will allow for more similarly rated items
                    $rating = $document->rating; // will read the user's rating of the content and display the corresponding amount of stars

		    // different cases to handle rating value and hold the HTML for numbner of stars
                    switch ($rating) {
                        case 1:
                            $stars = "
                                        <class='star'> &#9733;</>
                                     ";
                            break;
                        case 2:
                            $stars = "
                                        <class='star'> &#9733;</>
                                        <class='star'> &#9733;</>
                                     ";
                            break;
                        case 3:
                            $stars = "
                                        <class='star'> &#9733;</>
                                        <class='star'> &#9733;</>
                                        <class='star'> &#9733;</>
                                     ";
                            break;
                    }


                    echo $username;

                    echo "</p>";

                    echo "<span>";

                    echo $postedAt;

                    echo "</span>";

                    // RETRIEVE DATE OBJECT FROM LOGGED IN USER'S POSTS ARRAY BY ITERATING W/ FOREACH LOOP


                    echo "<p class='post-text'>";
                    $mediaData = json_decode($media, true);
                    $mediaTitle = $mediaData['title'] ?? $mediaData['name'];
                    echo $mediaData['id'] . " | " . $mediaTitle . " | " . $mediaData['type'] . " | ";
		    echo "<a href='recommendations.php?rating=".$rating."' title='See similarly rated media'>"; 
                    echo $stars;
                    echo "</a>";
                    //echo "&nbsp";
                    echo "<br>";
                    echo $content;
                    echo "</p>";

                    echo "</div>";
                    echo "</div>";
                    echo "<a href='#'>";
                    echo "<i class='fas fa-ellipsis-v'></i></a>";
                    echo "</div>";

                    echo "</div>";
                };
                ?>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>&copy SocialTune, Inc. All rights reserved.</p>
    </div>

</body>

</html>
