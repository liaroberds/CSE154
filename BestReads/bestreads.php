<?php
#Lia Roberds, CSE 154 AA, HW6, bestreads.php
#This php file takes files from my local host, re-writes them in XML, JSON, text, or HTML format
#and prints them to webpages in the webster server.
	
$url = "books/";

#Sends the code to the appropriate function based on the mode parameter
$mode = $_GET["mode"];
if($mode == "books") {
	outputBooksAsXML($url);
} else {
	$title = $_GET["title"];
	if($mode == "info") {
		outputInfoAsJSON($url, $title);
	}else if($mode == "description") {
		outputDescriptionAsTXT($url, $title);
	}else if($mode == "reviews") {
		outputReviewAsHTML($url, $title);
	}
}

#Writes the list of all available books in XML format and prints to the appropriate Php page
function outputBooksAsXML($url){
	$folders = glob($url."*");
	$dom = new DOMDocument('1.0');
	$books_node = $dom->createElement('books');
	$dom->appendChild($books_node);
	foreach($folders as $folder_name_withpath) {
		$book_node = $dom->createElement('book');
		$books_node->appendChild($book_node);
		$info_array = explode("\n", file_get_contents("$folder_name_withpath/info.txt"));
		list($booktitle, $author, $stars) = $info_array;
		$title_node = $dom->createElement('title', "$booktitle");
		$book_node->appendChild($title_node);
		$folder_name = substr($folder_name_withpath, 6);
		$folder_node = $dom->createElement('folder', "$folder_name");
		$book_node->appendChild($folder_node);
	}
	header("Content-type: text/xml");
	print $dom->saveXML();
}

#Writes the info about a single book in JSON format and prints to the appropriate Php page
function outputInfoAsJSON($url, $title){
	$info_array = explode("\n", file_get_contents($url."$title/"."info.txt"));
	list($booktitle, $author, $stars) = $info_array;
	print (json_encode($info_array));
}

#Writes a description about a single book in text format and prints to the appropriate Php page
function outputDescriptionAsTXT($url, $title){
	print file_get_contents($url."/$title/description.txt");
}

#Writes the reviews about a single book in HTML format and prints to the appropriate Php page
function outputReviewAsHTML($url, $title){
	$review_files = glob($url."/$title/review*.txt");
	foreach($review_files as $review_file){
		$review_txt = file_get_contents($review_file);
		$review_array = explode("\n", $review_txt);
		list($reviewer, $rating, $detail) = $review_array;
		?>
			<h3><?= $reviewer ?><span><?= $rating ?></span></h3>
			<p><?= $detail ?></p>
		<?php
	}
}
?>