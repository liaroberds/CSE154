//Lia Roberds, CSE 154 AA, HW6, bestreads.js
//This javascript file loads the books into the HTML page from the Php server and
//handles responses from the user, such as clicking on books or on the home button.

(function() {
	'use strict';
	
	var URL = "https://webster.cs.washington.edu/students/lroberds/hw6/";
	
	window.onload = function() {
		getAll();
		document.getElementById("back").onclick = showAllBooks;
	};
	
	//Makes a request to the Php server with a parameter of mode=books
	function getAll() {
		var request = new XMLHttpRequest();
		request.onload = displayAll;
		request.open("GET",URL + "bestreads.php?mode=books", true);
		request.send();
	}
	
	//Handles the request from mode=books. Extracts the XML data with the list of all books
	//and inputs each book into the "allbooks" section of the HTML page.
	function displayAll() {
		showAllBooks();
		var allbookarea = document.getElementById("allbooks");
		var booksXML = this.responseXML;
		var books = booksXML.getElementsByTagName("book");
		for(var i=0; i < books.length; i++) {
			var folderName = books[i].lastChild.innerHTML;
			var bookDiv = document.createElement('div');
			bookDiv.setAttribute('id', folderName);
			var img = document.createElement('img');
			img.setAttribute('src', getCover(folderName));
			img.setAttribute('alt', "cover");
			var title = document.createElement('p');
			title.innerHTML = books[i].firstChild.innerHTML;
			bookDiv.appendChild(img);
			bookDiv.appendChild(title);
			allbookarea.appendChild(bookDiv);
			bookDiv.onclick = displaySingle; //event handler
		}
	}
	
	//Handles the event of the user clicking on a book div.
	//Displays the singlebook section of the HTML and makes calls to other functions to input info
	function displaySingle() {
		showSingleBook();
		var folderName = this.id;
		var cover = getCover(folderName);
		document.getElementById("cover").setAttribute("src", cover);
		getInfo(folderName);
		getDescription(folderName);
		getReviews(folderName);
	}
	
	//Gets the book cover from the files on the server
	function getCover(folderName) {
		return (URL + "books/" + folderName + "/cover.jpg");
	}
	
	//Makes a request to the php server with a parameter of mode=info and the book title
	function getInfo(folderName) {
		var request = new XMLHttpRequest();
		request.onload = displayInfo;
		request.open("GET",URL + "bestreads.php?mode=info&title=" + folderName, true);
		request.send();
	}
	
	//Handles the request from mode=info. Extracts the JSON data with the info about the book
	//and inputs it into the "bookinfo" section of the HTML page.
	function displayInfo() {
		var info = JSON.parse(this.responseText);
		var ids = ["title", "author", "stars"];
		for(var i = 0; i < ids.length; i++) {
			document.getElementById(ids[i]).innerHTML = info[i];
		}
	}
	
	//Makes a request to the php server with a parameter of mode=description and the book title
	function getDescription(folderName) {
		var request = new XMLHttpRequest();
		request.onload = displayDescription;
		request.open("GET",URL + "bestreads.php?mode=description&title=" + folderName, true);
		request.send();
	}

	//Handles the request from mode=description. Extracts the text data with the description of the book
	//and inputs it into the "description" section of the HTML page.
	function displayDescription() {
		document.getElementById("description").innerHTML = this.responseText;
	}
	
	//Makes a request to the php server with a parameter of mode=reviews and the book title
	function getReviews(folderName) {
		var request = new XMLHttpRequest();
		request.onload = displayReviews;
		request.open("GET",URL + "bestreads.php?mode=reviews&title=" + folderName, true);
		request.send();
	}
	
	//Handles the request from mode=reviews. Extracts the HTML data with the books reviews
	//and inputs it into the "reviews" section of the HTML page.
	function displayReviews() {
		document.getElementById("reviews").innerHTML = this.responseText;
	}
	
	//Hides the single book and shows all of the books.
	function showAllBooks() {
		document.getElementById("allbooks").style.display = 'block';
		document.getElementById("singlebook").style.display = 'none';
	}
	
	//Hides all of the books and shows a single book.
	function showSingleBook() {
		document.getElementById("allbooks").style.display = 'none';
		document.getElementById("singlebook").style.display = 'block';
	}
})();