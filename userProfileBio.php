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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/user/style.css">
    <script src="https://kit.fontawesome.com/95d0fccd5e.js" crossorigin="anonymous"></script>
</head>
<style>
    body {
            max-width: 100%;
            background-color: #FDF5DF;
    }
    .navbar {
        background-color: #5EBEC4 !important;
    }
    .sidebar-container {
          width: 100%;
          height: auto;
          margin: 10px 0;
          padding: 20px;
          background-color: white;
          border-radius: 10px;
          border-left: 6px solid #5EBEC4;
          border-top: none;
          border-right: none;
          border-bottom: none;
        }
    .sidebar-container a {
	     font-size: 10px;
	     font-weight: normal;
	     text-align: right;
        }
	.sidebar-container h3 {
	    font-weight: bold !important;
	}
	.sidebar-container h5 {
	     font-weight: bold !important;
	}
          .info-container {
	     display: flex;
	     flex-direction: row;
	     align-items: center;
             gap: 7px;
              width: 100%;
  	     height: auto;
             margin: 10px 0;
             padding: 20px;
             background-color: white;
             border-radius: 10px;
	     border: 1px solid #D3D3D3;
	     border-top: 6px solid #5EBEC5;
             box-sizing: border-box;
	  }
	.info-container i {
	    margin-top: 0 !important;
	    top: 14px;
	    position: relative;
	    align-self: flex-start;
	}
	.info-container h3 {
	    font-weight: bold !important;
	    margin-bottom: 0;
	}
	.info-container p {
	    margin-bottom: 0 !important;
	    margin-top: 0;
	    font-size: 13px;
	}
	.info-container span {
	    display: block;
	    font-size: 15px;
	}
post-container {
	    width: 90% !important;
	}
.post-container a {
    text-decoration: none;
}
	.user-profile i {
	    margin-top: 10px !important;
	}
 
	.post-row {
	    display: flex;
	    margin-top: 10px;
	}
	.col-lg-4 {
	    padding-right: 20px;
            margin-top: 0;
	}
	.col-lg-8 {
	    padding-top: 0;
	    margin-top: 0;
	}
	.row {
	    align-items: flex-start;
	    margin-top: 15px !important;
	}
	.info-container .post-text {
	    margin-left: -42px;
	    margin-bottom: 0;
            font-size: 15px !important;
	}
	.post-container .post-text {
             margin-left: 0;
	}
</style>
<body>
     <!-- nav here -->
    <!-- navbar ref from bootstrap: https://getbootstrap.com/docs/5.3/components/navbar/ -->
    <div class="nav-container">
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">SocialTune</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="feed.php">Feed</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="dashboard2.php">Library</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link active dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo $username; ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="userProfile.php">View profile</a></li>
            <li><a class="dropdown-item" href="login.html">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
</div>
   
<!-- profile page -->
<div class="profile-container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="info-container">
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

	                <p class="post-text">
	                    <strong>Name:</strong> <?= $bio['name'] ?? '-' ?><br>
	                    <strong>DOB:</strong> <?= $bio['dob'] ?? '-' ?><br>
	                    <strong>Age:</strong> <?= $bio['age'] ?? '-' ?><br>
	                    <strong>Facebook:</strong> <?= $bio['facebook'] ?? '-' ?><br>
	                    <strong>Instagram:</strong> <?= $bio['instagram'] ?? '-' ?>
	                </p>
	            </div>
        	</div>
	    </div>
	</div>
        <div class="row">

            <div class="col-lg-4">
                <div class="sidebar-container">
                    <h5>Following</h5>

                    <?php

                    // RETRIEVE USERNAMES OF PEOPLE USER IS FOLLOWING
                    // will use foreach loop to retrieve each username of the items in the logged in User's follower array
                    // REF for foreach loop w/ arrays: https://www.php.net/manual/en/control-structures.foreach.php
                    // REF for pulling logged in User object data from Mongo: https://stackoverflow.com/questions/26716035/display-mongodb-collections-using-html-file
                    $following = $user['following'];

                    echo "<div class='friends-box'>";

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

                <div class="sidebar-container mb-3">
                    <div class="d-flex justify-content-center-between align-items-center mb-2">
                        <h5>Favorite Tracks</h5>
                    </div>
		    <!-- <p>RETRIEVE ARTISTS FROM USER_LIBRARY DATABASE</p> -->
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

                <div class="sidebar-container mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>Favorite Artists</h5>
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

                <div class="sidebar-container mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5>Favorite Albums</h5>
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

            <div class="col-lg-8">
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
		    echo "<div class='post-container'>";
		    echo "<div class='post-row'>";
		    echo "<div class='user-profile''>";
		    echo "<i class='fa-solid fa-circle-user'></i>";
	            echo "<div style='flex:1'>";
		    echo "<p>";
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
