<?php
# Lia Roberds, CSE 154, HW8, start.php
# This file creates the initial web page describing the site with the form for the user to log in 

include("common.php");
#Redirect to todolist if user is already logged in
if(check_logged_in()) {
	redirect("todolist.php", "Welcome back.");
}
top(); #HTML
?>
<h2> What Cow? </h2>
<h3> Sign in to access everything you were supposed to remember. </h3>

<?php #Sends action to login.php with post method to log the user in ?>
<form id="loginform" action="login.php" method="post">
	<div><input name="name" placeholder="User Name" type="text" size="8" autofocus="autofocus" />
		</div>
	<div><input name="password" placeholder="Password" type="password" size="8" /></div>
	<div><input type="submit" value="Log in" /></div>
</form>

<p> If you do not have an account, one will be created for you. </p>
<p> User names must be between 3-8 characters (lowercase letters or numbers only) and must start 
	with a letter. </p>
<p> Passwords must be between 6-12 characters long, begin with a number and end with a symbol. 
	</p>

<p> <em>(last login from this computer was <?= $_COOKIE["date"] ?>)</em> </p>
<?php
bottom(); #HTML
?>