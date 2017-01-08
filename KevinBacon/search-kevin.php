<!-- Lia Roberds, CSE 154 AA, HW7, search-kevin.php -->
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
	#Gets id of Kevin Bacon
	$kevin_id = get_actor_id('Kevin', 'Bacon');
	#Checks if Kevin Bacon was searched with Kevin Bacon
	if($id === $kevin_id) { ?> 
		<p> You searched for Kevin Bacon, please use "All movies" to search which movies he has
			been in. <p> 
		<?php
	#Executes the query to find movies that both the given actor and Kevin Bacon were in
	} else { 
		$query_movies = "SELECT m.name, m.year
						FROM movies m JOIN roles r1 ON m.id = r1.movie_id
						JOIN roles r2 ON r1.movie_id = r2.movie_id 
						WHERE r1.actor_id != r2.actor_id
						AND r1.actor_id = '$id' 
						AND r2.actor_id = '$kevin_id'
						ORDER BY m.year DESC, m.name";
		$rows = perform_query($query_movies);
		$name = get_actor_name($id);
		if($rows->rowCount() > 0) {
			$caption = "All movies with $name and Kevin Bacon.";
			create_table($rows, $caption);
		} else { ?>
			<p> <?= $name ?> has not been in any movies with Kevin Bacon. </p> 
		<?php }
	}
#If the actor was not found in the database, prints statment
} else { 
	not_found();
}
bottom();
?>