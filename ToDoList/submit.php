<?php
# Lia Roberds, CSE 154, HW8, submit.php
# This file takes requests from the form on the todolist page and adds/deletes items from the list

include("common.php");
session_start();
$name = $_SESSION["name"];
$todotxt = "todo_$name.txt";
$newlist = [];

#The user wants to delete a list element
if($_POST["action"] == "delete") {
	$oldlist = file($todotxt, FILE_IGNORE_NEW_LINES); //returns an array
	$index = $_POST["index"];
	for($i = 0; $i < count($oldlist) - 1; $i++) {
		if($i < $index) {
			$newlist[$i] = $oldlist[$i];
		}else {
			$newlist[$i] = $oldlist[$i + 1];
		}
	}
	$array_to_string = implode("\n", $newlist);
	file_put_contents($todotxt, $array_to_string . "\n");
#The user wants to add a list element
}else if($_POST["action"] == "add") {
	file_put_contents($todotxt, $_POST["item"] ."\n", FILE_APPEND);
}
redirect("todolist.php", "Update successful!");
