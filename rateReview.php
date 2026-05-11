<?php
	if (!isset($_COOKIE['SessionKey'])) { // WEB REFERENCE USED: https://www.geeksforgeeks.org/php/php-cookies/
		header('Location: login.html');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>SocialTune - Review</title>
	<link rel="stylesheet" href="css/rateReview.css">
</head>

<body>
	<div class="pageWrapper">
		<div class="dashboard">
			<div class="profileUser">
				<!-- i got the image from here: https://unsplash.com/photos/collection-of-various-music-album-covers-998pvuxqK6Y -->
				<img src="images/dashboardImage.jpg" alt="User">
				<h1>Leave a Review!</h1>
			</div>
		<div class="content">
			<p></p>
			<form class="searchBar" id="searchForm">
				<input type="text" name="userInput" class="searchInput" placeholder="Search for a track or album." required>
				<button type="submit" class="searchButton">Find</button>

				<div class="radioButtons">
					<span>Filter by: </span>
					<label><input type="radio" name="userFilters[]" value="albums" checked>Albums</label>
					<label><input type="radio" name="userFilters[]" value="tracks">Tracks</label>
				</div>
			</form>
			<div id="results"></div>
		</div>
		<!-- I added the arrow effect on the login, register and dashboard because i saw it in one website and i thought it looked good and modern. I got the link from:
        https://www.w3schools.com/charsets/ref_utf_arrows.asp -->
		<a href="userProfile.php" class="logoutButton">Back to Profile &rarr;</a>
		</div>
	<div class="postCard">
		<h2>Your Rating</h2>

		<!-- reference:https://www.geeksforgeeks.org/javascript/star-rating-using-html-css-and-javascript/ -->
		 <!-- reference:https://www.compart.com/en/unicode/U+2605 -->
		 <div id="starRating">
			<span>Rate: </span>
			<button class="starBtn" onclick="setStar(1)">&#x2605;</button>
			<button class="starBtn" onclick="setStar(2)">&#x2605;</button>
			<button class="starBtn" onclick="setStar(3)">&#x2605;</button>
			<button class="starBtn" onclick="setStar(4)">&#x2605;</button>
			<button class="starBtn" onclick="setStar(5)">&#x2605;</button>
			<p id="starFeedback"></p>
		 </div>

		 <button id="submitRatinBtn" disabled>Submit Rating</button>
		 <div id="postFeedback"></div>
	</div>

	</div>
</body>
</html>

<script>
</script>