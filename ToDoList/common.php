<?php
# Lia Roberds, CSE 154, HW8, common.php
# This file contains methods that are shared on multiple php pages. 
# Includes the HTML for the top and bottom of the page.

# This containers the HTML for the top of the web page
function top() {
	if(!isset($_SESSION)) {
		session_start();
	}
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
			<title>Remember the Cow</title>
			<link href="https://webster.cs.washington.edu/css/cow-provided.css" type="text/css" 
				rel="stylesheet" />
			<link href="cow.css" type="text/css" rel="stylesheet" />
			<link href="https://webster.cs.washington.edu/images/todolist/favicon.ico" type="image/ico"
				rel="shortcut icon" />
		</head>

		<body>
			<div class="headfoot">
				<h1>
					<img src="https://webster.cs.washington.edu/images/todolist/logo.gif" alt="logo" />
					Remember<br />the Cow
				</h1>
			</div>
					
			<?php		
			if(isset($_SESSION["message"])) {
				?>
				<div id="message"> <?= $_SESSION["message"] ?> </div>
				<?php
				unset($_SESSION["message"]);
			} ?>
			<div id="main">
<?php
}

# This contains the HTML for the bottom of the webpage
function bottom() {
?>
		</div>
		<div class="headfoot">
			<p>
				<q>Remember The Cow is nice, but it's a total copy of another site.</q> - PCWorld
					<br />
				All pages and content &copy; Copyright CowPie Inc.
			</p>

			<div id="w3c">
				<a href="https://webster.cs.washington.edu/validate-html.php">
					<img src="https://webster.cs.washington.edu/images/w3c-html.png" alt="Valid 
						HTML" /></a>
				<a href="https://webster.cs.washington.edu/validate-css.php">
					<img src="https://webster.cs.washington.edu/images/w3c-css.png" alt="Valid 
						CSS" /></a>
			</div>
		</div>
	</body>
</html>
<?php
}

# This redirects the user to the appropriate web page and saves a message to display
function redirect($url) {
	if(!isset($_SESSION)) {
		session_start();
	}
	$_SESSION["message"] = $message;
	header("Location: $url");
	die();
}

# This logs the user in by creating a session variable with the user's name
# Also, creates a cookie of when the computer was last logged in
function login($name, $message) {
	session_start();
	$_SESSION["name"] = $name;
	setcookie("date", date ("D y M d, g:i:s a"), time() + (60*60*24*7));
	redirect("todolist.php", $message);
}

# Checks if the user is logged in by checking if the session has a stored "name" element
function check_logged_in() {
	session_start();
	if(isset($_SESSION["name"])) {
		return true;
	} else {
		return false;
	}
}