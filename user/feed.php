<?php
	if (!isset($_COOKIE['SessionKey'])) { // WEB REFERENCE USED: https://www.geeksforgeeks.org/php/php-cookies/
		header('Location: login.html');
		exit();
	}

    
?>

<!-- video ref used for basic page template: https://www.youtube.com/watch?v=NljIHlZRTTE (PT 1) -->

<!-- video ref used for basic page template: https://www.youtube.com/watch?v=RrWUAmh93r4 (PT 2) -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SocialTune</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/95d0fccd5e.js" crossorigin="anonymous"></script>
</head>

<body>

    <nav>
        <div class="nav-left">
            <img src="../images/sample.png" alt="logo" class="logo">

            <!-- will eventually adjust nav items accordingly for future deliverables AKA these are just placeholders for now -->
            <!-- will use fontawesome icons for navbars -->

        </div> 

        <!-- centering search box on nav bar-->
        <div class="nav-middle">
            <div class="search-box">
                <img src="../images/search.png" alt="search"><input type="text" placeholder="Search">
            </div>
        </div>

        <div class="nav-right">
            <div class="nav-user-icon online">
                <img src="../images/user.jpg" alt="user icon">
            </div>
        </div>

        <!-- settings -->
        <!-- the original tutorial utilized javascript so I found a resource that utilizes a hidden checkbox to show dropdown -->
        <!-- reference: https://codepen.io/markcaron/pen/wdVmpB -->

        <!-- <div class="dropdown"> -->
            <!-- <input type="checkbox" id="settings-dropdown" value="" name="settings-checkbox"> -->
            <!-- <label for="settings-dropdown"> <img src="../images/profile-pic.png"
                    alt="profile-pic">
            </label> -->
            
        </div>
    </nav>

    <div class="container">

        <!-- left sidebar -->

        <div class="left-sidebar">

            <!-- will eventually adjust these accordingly for future deliverables AKA these are just placeholders for now -->
            <!-- will use fontawesome icons for navbars -->

            <div class="important-links">
                <a href="#"><img src="../images/news.png" alt="shortcut"> Profile </a>
                <a href="#"><img src="../images/friends.png" alt="shortcut"> Latest Tunes </a>
                <a href="#"><img src="../images/friends.png" alt="shortcut"> Friends </a>
            </div>
        </div>

        <!-- main sidebar -->

        <div class="main-content">

        <!-- for every element in the post collection, create them a post container -->
        <!--parsing values of each post to populate the post container's placeholder text -->
            <div class="write-post-container">
                <div class="user-profile">
                    <img src="../images/profile-pic.png" alt="profile pic">
                    <div>
                        <p>RETRIEVE USERNAME FROM USER DATABASE</p>
                    </div>
                </div>

                <div class="post-input-container">
                    <textarea rows="3" placeholder="What are you listening to, RETRIEVE USERNAME FROM USER DATABASE?"></textarea>
                    
                    <!-- should be some sort of search text box that drops down and populates with related seach results -->
                    
                    <div class="add-post-links"> <!-- figure out the embed logic for playback -->
                        <a href="#"><img src="../images/video.png" alt="video">Live Video</a> 
                    </div>
                </div>
            </div>

            <div class="post-container">
                <div class="post-row">
                    <div class="user-profile">
                        <img src="../images/profile-pic.png" alt="profile pic"> <!-- will modify to user icons instead of images -->
                        <div>
                            <p>RETRIEVE USERNAME FROM POST DATABASE</p>
                            <span>RETRIEVE DATE OBJECT FROM POST DATABASE</span>
                        </div>
                    </div>
                    <a href="#"><i class="fas fa-ellipsis-v"></i></a>
                </div>
                <p class="post-text">RETRIEVE USERNAME FROM POST DATABASE</p>
                <div class="post-row">
                    <div class="activity-icons">
                        <div><img src="../images/like.png" alt="like"> RETRIEVE COUNTER OBJECT FROM POST DATABASE</div>
                        <div><img src="../images/comments.png" alt="comments"> RETRIEVE COUNTER OBJECT FROM POST DATABASE</div>
                        <div><img src="../images/share.png" alt="shares"> RETRIEVE COUNTER OBJECT FROM POST DATABASE</div>

                    </div>
                </div>
            </div>

        </div>

        <!-- right sidebar -->

        <div class="right-sidebar">

            <!-- events -->

            <div class="sidebar-title">
                <h4>Recommendations</h4>
            </div>

            <div class="event">
                <div class="right-event">
                    <h4>RETRIEVE ARTIST FROM TIDALAPI DATABASE</h4>
                    <p>&nbspRETRIEVE TRACK TITLE FROM TIDALAPI DATABASE</p>
                    <a href="#">More Info</a> <!-- will redirect to track, album, artist view page -->
                </div>
            </div>
            
        </div>
    </div>

    <div class="footer">
        <p>&copy SocialTune, Inc. All rights reserved.</p>
    </div>

</body>

</html>