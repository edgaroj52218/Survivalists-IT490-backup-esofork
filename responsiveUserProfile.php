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

    $user = $userCollection->findOne(['keySession' => $_COOKIE['SessionKey']]);
     
    $username = $user['username'];

    // logic for following user when follow button pressed
    if(isset($_POST['follow_user'])){

        $followUser = $_POST['follow_user']; // retrieve userFollowed username

        // ADD the sent username to the logged in user's following array
        $userCollection->updateOne(
            ["username" => $username],
            [
                '$addToSet' => [
                    "following" => $followUser
                ]
            ]
        );

        // BUG: when a user is followed, their follower count has not been going up
        // ADD the logged in user's username to the follower array of the followed user
        $userCollection->updateOne(
            ["username" => $followUser],
            [
                '$addToSet' => [
                    "followers" => $username
                ]
            ]
        );

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // logic for unfollowing user when unfollow button pressed
    if(isset($_POST['unfollow_user'])){

        $unfollowUser = $_POST['unfollow_user']; // retrieve userUnfollowed username

        // remove the sent username from the logged in user's following array
        $userCollection->updateOne(
            ["username" => $username],
            [
                '$pull' => [
                    "following" => $unfollowUser
                ]
            ]
        );

        // remove the logged in user's username from the follower array of the followed user
        $userCollection->updateOne(
            ["username" => $unfollowUser],
            [
                '$pull' => [
                    "followers" => $username
                ]
            ]
        );
        
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!-- video ref used for basic page template: https://www.youtube.com/watch?v=NljIHlZRTTE (PT 1) -->

<!-- video ref used for basic page template: https://www.youtube.com/watch?v=RrWUAmh93r4 (PT 2) -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/user/style.css">

    <script src="https://kit.fontawesome.com/95d0fccd5e.js" crossorigin="anonymous"></script>
    <style>

        body {
            max-width: 100%;
	    background-color: #FDF5DF;
        }
	.navbar {
	    background-color: #5EBEC4 !important;
	}
        .sidebar-container {
          width: 280px;
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

              width: 98%;
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
	}
	.post-btn {
             width: 30px;
             height: auto;
             border: 1px solid black;

}
        .post-container {
            display: flex;
            justify-content: space-between;
          width: 100%;
  	height: auto;
  	margin: 10px;
  	padding: 25px;
  	background-color: white;
}

.post-container a {
    text-decoration: none;
}
	.user-profile i {
	    margin-top: 10px !important;
	}
</style>

</head>
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

    <!-- all user profile info here -->
    <div class="profile-container">
        
        <!-- user header/bio here -->
        <div class="row">
            <div class="col-12">

            <!-- user info -->
            <div class=info-container>
                    <i class="fa-solid fa-circle-user"></i>
<div>
                        <?php
                        echo "<h3>";

                        // RETRIEVE USERNAME BY LOOKING UP STORED SESSION KEY
                        $username = $user['username'];
                        echo $username;

                        echo "</h3>";
                        ?>
			<a href='bio.php'>View Bio</a>
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

        </div>

        <!-- user content -->
        <div class="row">
            <div class="col-lg-4">
                <!-- sidebar elements here (following, favorites, who to follow) -->
                <div class="sidebar-container">
                    <h5>Following</h5>
<?php
                    echo "<p>";

                    // <p>RETRIEVE COUNTER OF FOLLOWING FROM DATABASE</p>
                    $followingCount = count($user['following']);
                    if ($followingCount == null) {
                        echo "Not following anyone yet.";
                    } else {
                        echo $followingCount;
                    }
                    echo "</p>";

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
                        echo ($document);
                        echo "</i>";
                        echo "</div>";
                    };

                    echo "</div>";
                    ?>
                </div>
                <div class="sidebar-container mb-3"><div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>Favorite Tracks</h5>
	            <a href="addFavoriteTrack.php">Add a track</a>
                    </div>
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
                <div class="sidebar-container mb-3"><div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>Favorite Artists</h5>
		    <a href="addFavoriteArtist.php">Add an artist</a>
                    </div>
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
                <div class="sidebar-container mb-3"><div class="d-flex justify-content-between align-items-center mb-2">
                    <h5>Favorite Albums</h5>
		    <a href="addFavoriteAlbum.php">Add an album</a>
		    </div>
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
                <div class="sidebar-container">
                    <h5>Who to Follow</h5>
                
                       <?php



                            // FOREACH LOOP THAT WILL GO THROUGH THE DIFFERENT REGISTERED USER OBJECTS' USERNAMES IN REG_USERS COLLECTION (MINUS THE LOGGED IN USER)

                            // REF: https://www.tutorialspoint.com/php_mongodb/php_mongodb_limit_records.htm



                            // scrapped and fixed original follow button logic 



                            $filter = [];



                            $options = ['limit' => 5]; // show top 5 results

                            

                            // goes through reg_users database and limits to five

                            $users = $userCollection->find($filter, $options);



                            if(isset($user['following'])) {

                                $followingList = $user['following']; // retrieve logged in user's following list

                            } else {

                                $followingList = [];

                            }



                            // loop through each of the five recommended users

                            foreach($users as $document) {


                                $recommendUsername = $document['username']; // username of the current user during iteration



                                // needs to make sure recommended user is not logged in user

                                if($recommendUsername != $username) {



                                    // populate the table w/ current user pointer's username

                                    echo "<div class='user-curation d-flex justify-content-between align-items-center mb-2'>";

                                    echo $recommendUsername;

                                    echo "&nbsp";



                                    $isFollowing = false; 



                                    // loop through the following list of logged in user



                                    // for every person followed in followingList

                                    foreach($followingList as $userFollowed) {

                                        if($userFollowed == $recommendUsername) { // check to see if current user pointer in following array == one of the recommended usernames

                                            $isFollowing = true;

                                            break; 

                                        }

                                    }



                                    // follow/unfollow button

                                    if($isFollowing) {



                                        echo "<form method='post' style='display:inline'>";

                                        echo "<input type='hidden' name='unfollow_user' value='";

                                        echo $recommendUsername; //when pressed, send the affected username to remove from user's following array

                                        echo "'>";

                                        echo "<button type='submit' class='unfollow-btn'>Unfollow</button>"; // button styling

                                        echo "</form>";



                                    } else {



                                        echo "<form method='post' style='display:inline'>";

                                        echo "<input type='hidden' name='follow_user' value='";

                                        echo $recommendUsername; //vice versa

                                        echo "'>";

                                        echo "<button type='submit' class='follow-btn'>Follow</button>"; // diff button styling for unfollowing

                                        echo "</form>";                



                                    }



                                    echo "</div>";



                                }

                            }

                        ?>
                 </div>
	    </div>
            <div class="col-lg-8">
                    <a href="mediaSearch.php"><button class='createPost-btn'>+</button></a>

                <?php
		$postIndex = 0; // this is to track which post we're on
                $userPosts = $user['posts'];
                foreach ($userPosts as $document) {
		    $media = $document->media;
                    $content = $document->content;
                    $postedAt = $document->postedAt;
                    // here we check if the post is private or public, and if it doesnt have a status, it just makes it public
                    $currentStatus = isset($document['postStatus']) ? $document['postStatus'] : 'public';

                    echo "<div class='post-container'>";
                    echo "<div class='post-row'>";
                    echo "<div class='user-profile'>";
                    echo "<i class='fa-solid fa-circle-user'>";
                    echo "</i>";
                    // <!-- will modify to user icons instead of images -->
                    echo "<div style='flex:1'>";
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

                    // wrapped stars in an <a> tag so that user can click on the rating to be recommended more simiarly rated songs (tooltip)
                    // hovering over stars will trigger tooltip that tells user they can view other recommendations with the same rating

		    echo "<a href='recommendations.php?rating=".$rating."' title='See similarly rated media'>"; 
                    echo $stars;
                    echo "</a>";

                    //echo "&nbsp";
                    echo "<br>";
                    echo $content;
                    echo "</p>";

                    echo "</div>";
                    echo "</div>";

		    // 3 DOTS LOGIC - KATE
	            // 3 dots with dropdown for public/private toggle
                    // REF for dropdown: https://www.w3schools.com/howto/howto_css_dropdown.php
                    echo "<div class='post-options' style='position: relative'>";
                    echo "<span class='dotsBtn' onclick='toggleDropdown(this)' style='cursor:pointer;'>&#8942;</span>";
                    echo "<div class='statusDropdown' style='display:none; position:absolute; right:0; background:#fff; border:1px solid black; border-radius:6px; padding:6px; z-index:10; min-width:120px'>";

		    // logic for the public part
                    echo "<form method='post' action='updatePostStatus.php'>";
                    echo "<input type='hidden' name='postIndex' value='" . $postIndex . "'>"; // post id so we know which one update
                    echo "<input type='hidden' name='postStatus' value='public'>";
                     // im using bold to highlight the status which the post is on. 
                    echo "<button type='submit' style='display:block; width:100%; background:none; border:none; text-align:left; padding:4px 8px; cursor:pointer;" . ($currentStatus === 'public' ? "font-weight:bold;" : "") . "'>Public</button>";
                    echo "</form>";

		     // logic for the private part
                    echo "<form method='post' action='updatePostStatus.php'>";
                    echo "<input type='hidden' name='postIndex' value='" . $postIndex . "'>"; //  post id so we know  which one update
                    echo "<input type='hidden' name='postStatus' value='private'>";
                    echo "<button type='submit' style='display:block; width:100%; background:none; border:none; text-align:left; padding:4px 8px; cursor:pointer;" . ($currentStatus === 'private' ? "font-weight:bold;" : "") . "'>Private</button>";
                    echo "</form>";

                    //echo "<a href='#'>";
                    //echo "<i class='fas fa-ellipsis-v'></i></a>";
                    echo "</div>";
		    echo "</div>";
                    echo "</div>";
		    echo "</div>";
			// each post need a unique id so thats why we have to increment the indx
		    $postIndex++;

                };
                ?>
            </div>
        </div>

    </div>
    <div class="footer">
        <p>&copy SocialTune, Inc. All rights reserved.</p>
    </div>
    <script>
     // logic so we can intereact with the 3 dots
      // i used this reference to manage the next element: https://developer.mozilla.org/en-US/docs/Web/API/Element/nextElementSibling
    function toggleDropdown(el) {
        // this part tries to find the dropdown thats next to the dots, and if its showing it hides and if its hidding, it shows it
        const dropdown = el.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
     }

     // here if our user touches somewhere else,, it will close the dropdown 
     // i used this reference: https://developer.mozilla.org/en-US/docs/Web/API/Element/click_event
    document.addEventListener('click', function(e) {
          // not clicking the buttons so we just close everything. Refrence for the classList: https://developer.mozilla.org/en-US/docs/Web/API/Element/classList
        if (!e.target.classList.contains('dotsBtn')) {
            document.querySelectorAll('.statusDropdown').forEach(function(d) {
                d.style.display = 'none';
            });
        }
    });
    
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
