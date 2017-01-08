<?php
# Lia Roberds, CSE 154, HW8, todolist.php
# This file show's the user's to do list and lets them add/delete items from it

include("common.php");
#Redirect to start if user is already logged in
if(!check_logged_in()) {
	redirect("start.php", "You must be logged in to access your to-do list.");
}
top(); #HTML
$name = $_SESSION["name"]
?>
<h2> <?= $name ?> 's To-Do List</h2>

<ul id="todolist">
	<?php
	$todotxt = "todo_$name.txt";
	if(file_exists($todotxt)) {
		$list = file($todotxt, FILE_IGNORE_NEW_LINES);
		#loops through text file to print each element of the list and create a form to delete
		for($i = 0; $i < count($list); $i++) {
			?>
			<li>
				<?=htmlspecialchars($list[$i]) ?>
				<form action="submit.php" method="post">
					<input type="hidden" name="action" value="delete" />
					<input type="hidden" name="index" value="<?= $i ?>" />
					<input type="submit" value="Delete" />
				</form>
			</li>
			<?php
		}
	}
	#creates the form to add new elements to the list ?>
	<li>
		<form action="submit.php" method="post">
			<input type="hidden" name="action" value="add" />
			<input name="item" type="text" size="25" autofocus="autofocus" />
			<input type="submit" value="Add" />
		</form>
	</li>
</ul>

<div>
	<?php
	#logs the user out when clicked by sending request to logout.php ?>
	<a href="logout.php"><strong>Log Out</strong></a>
	<em>(logged in since <?= $_COOKIE["date"] ?>)</em>
</div>
<?php
bottom(); #HTML
?>