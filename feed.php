<?php
//require 'vendor/autoload.php';
error_reporting(E_ALL);
ini_set("display_errors", 1);

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

    $allUsers = $userCollection->find();
    //paste here
    foreach ($allUsers as $users) {

    if (!isset($users['posts'])) continue;

    $updatedPosts = [];

    foreach ($users['posts'] as $post) {

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
    $userCollection->updateOne(
        ['_id' => $users['_id']],['$set' => ['posts' => $updatedPosts]]);
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
    <title>View Friend Profile | SocialTune</title>
    <link rel="stylesheet" href="/user/style.css">
    <script src="https://kit.fontawesome.com/95d0fccd5e.js" crossorigin="anonymous"></script>

    <style>
/* ill move this to the style later on */

.commentBox { 
     margin-top: 12px; 
}

.commentRow {
    display: flex;
    gap: 8px;
    margin-top: 8px;
    width: 100%;
}

.commentInput {
    padding: 12px 15px;
    border: 1px solid #ddd;
    flex: 1;
    border-radius: 6px;
    font-size: 13px;
    min-width: 0;
}

.commentBtn {
    padding: 11px 12px;
    background: #FF4D6D;
    color: white;
    border: none;
    cursor: pointer;
    font-weight: bold; 
    border-radius: 6px;
}

.showCommentsBtn {
    background: none;
    border: none;
    color: #555;
    font-size: 13px;
    cursor: pointer;
    margin-top: 11px;
}

.commentList {
    display: none;
    margin-top: 10px;
}

.singleComment {
    padding: 10px;
    border-radius: 4px;
    border-left: 3px solid #00A8A8;
    margin-bottom: 5px;
    background-color: #F9F9F9;
    color: black;
     width: 100%;
    box-sizing: border-box;
    font-size: 14px;
}

.userName { 
    font-weight: bold; 
}
.time { 
	font-size: 11px; 
	color: gray;
}
.text { 
	font-size: 14px; 
}
    </style>
</head>

<body>

<nav>
    <div class="nav-left">
        <h3 class="logo">SocialTune</h3>

        <!-- will eventually adjust nav items accordingly for future deliverables AKA these are just placeholders -->
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

<?php
// need to access each of the users in the logged in user's following array
$followingList = $user['following'] ?? [];

// for each user in the following array
foreach ($followingList as $userFollowed) {

    // get the current user's posts array that the pointer is pointing at during iterating round
    // following feed in this case will not include the logged in user's personal posts

    $followedUser = $userCollection->findOne(['username' => $userFollowed]);
    $userPosts = $followedUser['posts'];

    foreach ($userPosts as $postsMadeByUserFollowed) {

// trying public and private  --- THIS PART WORKS
	$post = (array)$postsMadeByUserFollowed;
        $postStatus = isset($post['postStatus']) ? $post['postStatus'] : 'public'; 
            if ($postStatus === 'private' && $followedUser['username'] !== $user['username']) {
                continue;
             }




        echo "<div class='post-container'>";
        echo "<div class='post-row'>";
        echo "<div class='user-profile'>";
        echo "<i class='fa-solid fa-circle-user'></i>";
        echo "<div>";

        echo "<p>";

        // KATE -- like and comment section
        $post = (array)$postsMadeByUserFollowed;

        $posterUsername = $postsMadeByUserFollowed->username;
        $media = $postsMadeByUserFollowed->media;
        $content = $postsMadeByUserFollowed->content;
        $postedAt = $postsMadeByUserFollowed->postedAt;

        $postId = isset($post['_id']) ? (string)$post['_id'] : uniqid(); // ADDED THIS KATEEEEE PUBLIC
        $currentUser = $user['username'];

        $likes = isset($post['likes']) ? (array)$post['likes'] : [];
        $isLiked = in_array($currentUser, $likes);
        $likeCount = count($likes);

        $comments = isset($post['comments']) ? (array)$post['comments'] : [];

        echo "<a href='userProfileBio.php?username=".$posterUsername."'>".$posterUsername."</a>";
        echo "</p>";

        echo "<span>";
        echo $postedAt;
        echo "</span>";

	 $mediaData = json_decode($media, true);
             if (isset($mediaData['name'])) {
                $displayMedia = $mediaData['id'] . " | " . $mediaData['name'] . " | " . $mediaData['type'];
             } elseif (isset($mediaData['title'])) {
                 $displayMedia = $mediaData['id'] . " | " . $mediaData['title'] . " | " . $mediaData['type'];
             } else {
                 $displayMedia = $media;
              }

        echo "<p class='post-text'>";
        echo $displayMedia;
        echo "<hr style='margin-top:10px; margin-bottom: 8px;  width:100%; margin-left:0; border:none; border-top:1px solid black;'>" . $content;
        echo "</p>";
        echo "</div>";
        echo "</div>";

        echo "<a href='#'><i class='fas fa-ellipsis-v'></i></a>";
        echo "</div>";

        // like button
        echo "<div style='display:flex; align-items:center; gap:8px; margin-top:15px;'>";
	    // i used this the font awesome icons for the like button. reference: https://fontawesome.com/icons/heart
		// for the onclick function to manage the  like and unlike interaction i used this reference: https://developer.mozilla.org/en-US/docs/Web/API/GlobalEventHandlers/onclick
        echo "<i 
        class='" . ($isLiked ? "fa-solid" : "fa-regular") . " fa-heart'
        id='likeIcon_$postId'
        style='cursor:pointer; color:" . ($isLiked ? "red" : "gray") . "; font-size:20px;'
        onclick=\"likePost('$postId','$posterUsername')\">
        </i>";

        echo "<span id='likeCount_$postId'>$likeCount</span>";
        echo "</div>";

        // comment section
        echo "<div class='commentBox'>";

        echo "<div class='commentRow'>";
        echo "<input type='text' class='commentInput' id='commentInput_$postId' placeholder='Leave a comment...'>";
        echo "<button class='commentBtn' onclick=\"addComment('$postId','$posterUsername')\">Post</button>";
        echo "</div>";

        echo "<button class='showCommentsBtn' onclick=\"toggleComments('$postId')\">";
        echo "View comments (" . count($comments) . ")";
        echo "</button>";

        echo "<div id='commentsBox_$postId' class='commentList'>";

        foreach ($comments as $c) {
			 // in this part each comment is treated as an array so i can grab values like username and text
            $c = (array)$c;

            echo "<div class='singleComment'>";
            echo "<span class='userName'>" . $c['username'] . "</span>"; // this shows who wrote te comment
	     // here i manage the timestamp so we see when it was posted. i added gm to date so time display in UTC which matches how time() saves it
            echo "<span class='time'> - " . gmdate("H:i", $c['createdAt']) . "</span>";
            echo "<div class='text'>" . $c['comment'] . "</div>";
            echo "</div>";
        }

        echo "</div>";
        echo "</div>";

        echo "</div>";
    }
}
?>

</div>

<div class="footer">
    <p>&copy SocialTune, Inc. All rights reserved.</p>
</div>

</body>
</html>

<script>

let loggedInUser = "<?php echo $user['username']; ?>";
function likePost(postId, owner) {

    fetch('likePost.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `post_id=${postId}&post_owner=${owner}`
    })
    .then(res => res.text())
    .then(res => {

        let icon = document.getElementById('likeIcon_' + postId);
        let countEl = document.getElementById('likeCount_' + postId);

           // for the parseInt i used this reference: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/parseInt
        let count = parseInt(countEl.innerText) || 0;
        // this part will change the icon and it will also update the count of the likes
        if (res === "liked") {
            icon.classList.remove("fa-regular");
            icon.classList.add("fa-solid");
            icon.style.color = "red";
            count++;
        } else if (res === "unliked") {
            icon.classList.remove("fa-solid");
            icon.classList.add("fa-regular");
            icon.style.color = "gray";
            count--;
        }

        countEl.innerText = count;
    });
}

function addComment(postId, owner) {
     // in this part we grab what the person typed
    let commentInput = document.getElementById('commentInput_' + postId);
    let commentText = commentInput.value.trim();

    if (!commentText) return;

    fetch('commentPost.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `post_id=${postId}&post_owner=${owner}&comment=${encodeURIComponent(commentText)}`
    })
    .then(response => response.text())
    .then(result => {

        if (result === "notAllowed") {
            alert("You must follow each other to comment.");
            return;
        }

        if (result === "commentAdded") {

            let commentsBox = document.getElementById('commentsBox_' + postId);
            commentsBox.style.display = "block";
	    // since im not getting the time as soon as i post, im gonna get the current date. For padStart i used this reference: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/padStart
	    let now = new Date();
             // i added UTC so it matches the gmdate
             let time = now.getUTCHours().toString().padStart(2,'0') + ":" + now.getUTCMinutes().toString().padStart(2,'0'); 
	      //here we just create a new comment element and it will be added to the page
            let newComment = document.createElement("div");
	    newComment.className = "singleComment";
            newComment.innerHTML = "<span class='userName'>" + loggedInUser + "</span><span class='time'> - " + time + "</span><div class='text'>" + commentText + "</div>";
              // i used this ref for the appenchild: https://developer.mozilla.org/en-US/docs/Web/API/Node/appendChild
            commentsBox.appendChild(newComment);

            commentInput.value = "";
        }
    });
}

// in this part i dont want the comments to accumulate so this part will show or hide them
function toggleComments(postId) {
    let box = document.getElementById('commentsBox_' + postId);
    if (box.style.display === "none" || box.style.display === "") {
        box.style.display = "block";
    } else {
        box.style.display = "none";
    }
}
</script>
