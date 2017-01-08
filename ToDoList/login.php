<?php
# Lia Roberds, CSE 154, HW8, login.php
# This file submits data from the form on the start page and logs the user in if it has the right
# user name and password and redirects them to their to do list

include("common.php");
if(isset($_POST["name"]) && isset($_POST["password"])) {
	$name = $_POST["name"];
	$password = $_POST["password"];
	$txt = "users.txt";
	
	#create a file if it doesn't already exist
	if(!file_exists($txt)) {
		file_put_contents($txt, "test1:1test!\n");
	}

	#print "Check if user exists in file \n";
	$users = file($txt, FILE_IGNORE_NEW_LINES);
	foreach($users as $user) {
		$user_info = explode(":", $user);
		if($user_info[0] === $name) {
		#print "username exists, now check if password is correct \n";
			if($user_info[1] === $password) {
				#print "password is correct, load session and redirect to todolist \n";
				login($name, "Welcome back!");
			} else {
				#print "password is incorrect, redirect back to start \n";
				redirect("start.php", "Password is incorrect");
			}
		}
	}
	
	#print "user does not exist, check regex";
	$name_regex = '/^[a-z][\da-z]{2,7}$/';
	$password_regex = '/^\d.{4,10}\W$/';
	if(preg_match($name_regex, $name) && preg_match($password_regex, $password)) {
		#regex is good, create account, start session, redirect to todolist
		file_put_contents($txt, $name .":". $password . "\n", FILE_APPEND);
		login($name, "A new account has been created.");
	} else {
		#regex is bad, redirect to start
		redirect("start.php", "Not correct user name and password format.");
	}
} else {
	#Either the username or password is blank
	redirect("start.php", "Not enough information entered to log in.");
}
?>