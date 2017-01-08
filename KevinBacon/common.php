<!-- Lia Roberds, CSE 154 AA, HW7, common.php -->
<?php
#This provides the HTML for the top of the webpage
function top() { 
?>
<!DOCTYPE html>
<html>
	<head>
		<title>My Movie Database (MyMDb)</title>
		<meta charset="utf-8" />
		<link href="https://webster.cs.washington.edu/images/kevinbacon/favicon.png" 
			type="image/png" rel="shortcut icon" />

		<!-- Link to your CSS file that you should edit -->
		<link href="bacon.css" type="text/css" rel="stylesheet" />
	</head>

	<body>
		<div id="frame">
			<div id="banner">
				<a href="mymdb.php"><img src="https://webster.cs.washington.edu/images/
					kevinbacon/mymdb.png" alt="banner logo" /></a>
				My Movie Database
			</div>

			<div id="main">
<?php
}

#This returns the ID of the actor that was given by the user. Ties are broken by the higher
#number of films the actor has been in, then the lower id number. Only 1 id number is returned.
function get_actor_id($firstname, $lastname) {
	$query_id = "SELECT id
			FROM actors 
			WHERE first_name LIKE '$firstname' 
			AND last_name = '$lastname'
			ORDER BY film_count DESC, id
			LIMIT 1";
	$result = perform_query($query_id);
	$id = null;
	foreach ($result as $result_id) {
		$id = $result_id["id"];
	}
	return $id;
}

#This returns the official name from the database that matches the given id
#This is called after get_actor_id and is used to print the name on the webpage
function get_actor_name($id) {
	$query_name = "SELECT first_name, last_name
			FROM actors 
			WHERE id = '$id'";
	$result = perform_query($query_name);
	$name = null;
	foreach ($result as $result_name) {
		$name = $result_name["first_name"] . " " . $result_name["last_name"];
	}
	return $name;
}

#This performs any query given as a paramter and gets the result from the imdb server
#Returns rows as a PDO object if successful, else and errors tatement if there was an error
function perform_query($query){
	try {
		$db = new PDO("mysql:dbname=imdb;host=localhost", "lroberds", "FZtaVI3e7D");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$rows = $db->query($query);
		return $rows;
	} catch (PDOException $ex) {
	?>
		<p>Sorry, a database error occurred.</p>
		<p>(Error details: <?= $ex->getMessage() ?>)</p>
	<?php
	}
}

#This translates the PDO object into an HTML table that inserted into the webpage
#Assigns a class of "odd" to every odd numbered row for the zebra stripping 
function create_table($rows, $caption) { ?>
	<table>
		<caption> <?= $caption ?> </caption>
		<tr>
			<th> # </th>
			<th> Title </th>
			<th> Year </th>
		</tr>
		<?php $count = 1;
		foreach ($rows as $row) {
			if($count % 2 == 1) {
				?> <tr class="odd"> <?php
			} else {
				?> <tr> <?php
			} ?>
					<td> <?= $count ?> </td>
					<td> <?= $row["name"] ?> </td>
					<td> <?= $row["year"] ?> </td>
				</tr>
			<?php $count++;
		} ?>
	</table>	
<?php }

#This provides an HTML statement if the actor is not in the database
function not_found() {
	?> <p> Sorry, actor not found </p> <?php
}

#This provides the HTML for the provides of the webpage
function bottom() {
?>
				<!-- form to search for every movie by a given actor -->
				<form action="search-all.php" method="get">
					<fieldset>
						<legend>All movies</legend>
						<div>
							<input name="firstname" type="text" size="12" placeholder="first name"
								autofocus="autofocus" /> 
							<input name="lastname" type="text" size="12" placeholder="last name" /> 
							<input type="submit" value="go" />
						</div>
					</fieldset>
				</form>

				<!-- form to search for movies where a given actor was with Kevin Bacon -->
				<form action="search-kevin.php" method="get">
					<fieldset>
						<legend>Movies with Kevin Bacon</legend>
						<div>
							<input name="firstname"type="text" size="12" placeholder="first name"/>
							<input name="lastname" type="text" size="12" placeholder="last name"/> 
							<input type="submit" value="go" />
						</div>
					</fieldset>
				</form>
			</div> <!-- end of #main div -->
		
			<div id="w3c">
				<a href="https://webster.cs.washington.edu/validate-html.php"><img src="https://
					webster.cs.washington.edu/images/w3c-html.png" alt="Valid HTML5" /></a>
				<a href="https://webster.cs.washington.edu/validate-css.php"><img src="https://
					webster.cs.washington.edu/images/w3c-css.png" alt="Valid CSS" /></a>
			</div>
		</div> <!-- end of #frame div -->
	</body>
</html>
<?php
}
?>
