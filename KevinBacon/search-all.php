<!-- Lia Roberds, CSE 154 AA, HW7, search-all.php -->
<?php 
include("common.php");
top(); 
?>
<h1> Results: </h1>
<?php
#Gets id of the actor typed in by the user. Calls function in common.php
$id = get_actor_id($_GET["firstname"] . "%", $_GET["lastname"]);
#Checks if the actor exists in the database
if($id) {
	#This query gets the list of all movies the given actor has been in
	$query_movies = "SELECT m.name, m.year
			FROM movies m
			JOIN roles r ON m.id = r.movie_id
			WHERE r.actor_id = '$id'
			ORDER BY m.year DESC, m.name";
	$rows = perform_query($query_movies);
	#Checks if the actor has been in any movies
 	if($rows) {
		$name = get_actor_name($id);
		$caption = "All movies with $name.";
		create_table($rows, $caption);
	}
#If the actor was not found in the database, prints statment
} else { 
	not_found();
}
bottom(); 
?>