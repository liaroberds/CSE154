// Lia Roberds, CSE 154, HW4, fifteen.js
// This javascript creates the puzzle and controls the user's interaction with the puzzle
// Extra Features #1 and #5

(function() {
	'use strict';

	var SIZE = 4;
	var PIX = 100;
	var blankRow = null;
	var blankCol = null;

	//Sets up the puzzle grid and the event handlers when the page loads
	//Note: the event handlers that interact with the tiles are in the createTiles method
	window.onload = function() {
		createOptions();
		createOutput();
		for(var i = 0; i < SIZE; i++) {
			for(var j = 0; j < SIZE; j++) {
				createCells(i, j);
				createTiles(i, j);
			}
		}
		changeBackground();
		document.getElementById("photoSelect").onchange = changeBackground;
		document.getElementById("shufflebutton").onclick = shuffle;
	};

	//EXTRA FEATURE #5. Adds the option to choose a new background
	function createOptions() {
		var controlarea = document.getElementById('controls');
		var backgrounds = document.createElement('div');
		controlarea.appendChild(backgrounds);
		backgrounds.innerHTML = "Select a new background: ";
		var selectPhotos = document.createElement('select');
		selectPhotos.setAttribute("id", "photoSelect");
		backgrounds.appendChild(selectPhotos);
		var photos = ["background.jpg", "Puppy.jpg", "Cows.jpg", "Rowing.jpg", "Flowers.jpg", "Gdansk.jpg"];
		for(var i = 0; i < photos.length; i++) {
			var opt = document.createElement('option');
			opt.value = photos[i];
			opt.innerHTML = photos[i];
			if(i == 0) {
				opt.selected = "selected";
			}
			selectPhotos.appendChild(opt);
		}
	}
	
	//EXTRA FEATURE #1. Adds the section to show the state of the puzzle
	function createOutput() {
		var output = document.getElementById('output');
		var notify = document.createElement('div');
		notify.setAttribute("id", "notify");
		output.appendChild(notify);
		notSolved();
	}
	
	//When the page loads, this creates the underlying grid
	//Each "cell" is a child node of "puzzlearea"
	function createCells(row, col) {
		var area = document.getElementById("puzzlearea"); //parent
		var cell = document.createElement("div"); //new child
		cell.className = "cell";
		cell.setAttribute("id", "cell" + row + col);
		cell.style.left = (col * PIX) + "px";
		cell.style.top = (row * PIX) + "px";
		area.appendChild(cell);
		return cell;
	}
	
	//When the page loads, this creates the puzzle tiles
	//Each "tile" is the child node of a "cell"
	function createTiles(row, col) {
		var tileNum = (row * SIZE) + col + 1;
		if(tileNum < (SIZE * SIZE)) {
			var cell = document.getElementById("cell" + row + col); //parent
			var tile = document.createElement('div'); //new child
			tile.className = "tile";
			tile.setAttribute("id", "tile" + tileNum);
			tile.innerHTML = "" + tileNum;
			cell.appendChild(tile);
			tile.onmouseover = mouseover; //event handler
			tile.onmouseout = mouseout; //event handler
			tile.onclick = move; //event handler
			return tile;
		}else if(tileNum === SIZE*SIZE) {
			updateBlank(row, col);
		}
	}
	
	//Changes the background to a new image in the server when selected
	function changeBackground() {
		var list = document.getElementById("photoSelect");
		var photoName = list.options[list.selectedIndex].text;
		for(var i = 0; i < SIZE; i++) {
			for(var j = 0; j < SIZE; j++) {
				var tileNum = (i * SIZE) + j + 1;
				if(tileNum < (SIZE * SIZE)) {
					var tile = document.getElementById("tile" + tileNum);
					tile.style.backgroundImage = "url('"+photoName+"')";
					tile.style.backgroundPosition = "" + (j*-1*PIX) +"px " + (i*-1*PIX) + "px";
				}
			}
		}
	}

	//Shuffles the board by locating the tiles that neighbor the blank square and randomly
	//one to swap with the blank
	function shuffle() {
		notSolved();
		for(var i=0; i < 1000; i++) {
			var row = blankRow;
			var col = blankCol;
			var neighbors = checkNeighbors(row, col);
			var rand = parseInt(Math.random() * neighbors.length);
			var chosenTile = document.getElementById(neighbors[rand]);
			row = getRow(chosenTile);
			col = getCol(chosenTile);
			chosenTile.parentNode.removeChild(chosenTile);
			document.getElementById("cell" + blankRow + blankCol).appendChild(chosenTile);
			updateBlank(row, col);
		}
	}
	
	//Changes CSS when the mouse hovers over a tile neighboring the blank tile
 	function mouseover() {
		var row = getRow(this);
		var col = getCol(this);
		if (checkNeighbors(row, col) === "blank") {
			this.classList.remove("tile");
			this.classList.add("hover");
		}
	}
	
	//Changes CSS when the mouse is no longer over the tile
	function mouseout() {
		this.classList.remove("hover");
		this.classList.add("tile");
	}
	
	//Moves a tile if it is next to the blank tile
	function move() {
		var row = getRow(this);
		var col = getCol(this);
		if(checkNeighbors(row, col) === "blank") {
			this.parentNode.removeChild(this);
			document.getElementById("cell" + blankRow + blankCol).appendChild(this);
			updateBlank(row, col);
			checkSolution();
		}
	}
	
	//Checks if the puzzle is solved
	function checkSolution() {
		for(var i = 0; i < SIZE; i++) {
			for(var j = 0; j < SIZE; j++) {
				var tileNum = (i * SIZE) + j + 1;
				if(document.getElementById("cell" + i + j).hasChildNodes()) {
					if(document.getElementById("cell" + i + j).firstChild.id !== "tile" + tileNum) {
						notSolved();
						return false; //exit loop when solution is proved not complete
					}
				}
			}
		}
		var notify = document.getElementById("notify");
		notify.innerHTML = "YOU WIN!";
		notify.classList.remove("notsolved");
		notify.classList.add("win");
		return true;
	}
	
	//HELPER FUNCTIONS		
	//Updates the row and col of the blank tile
	function updateBlank(row, col) {
		blankRow = row;
		blankCol = col;
	}
	
	//Returns the row position of the given tile
	function getRow(tile) {
		return (parseInt(tile.parentNode.style.top) / PIX);
	}
	
	//Returns the tile position of the given tile
	function getCol(tile) {
		return (parseInt(tile.parentNode.style.left) / PIX);
	}
	
	//Changes class for CSS when the puzzle is not solved 
	function notSolved() {
		var notify = document.getElementById("notify");
		notify.innerHTML = "STATUS: Not Solved";
		notify.classList.remove("win");
		notify.classList.add("notsolved");
	}
	
	//Returns array of neighbors when passed the blank cell
	//OR, returns "blank" if the tile neighbors the blank cell
	function checkNeighbors(row, col) {
		var condition1 = [col, SIZE - 1, row, SIZE - 1];
		var condition2 = [0, col, 0, row];
		var position = ["" + row + (col - 1), "" + row + (col + 1), "" + (row - 1) + col, "" + (row + 1) + col];
		var neighbors = [];
		for(var i=0; i < position.length; i++) {
			if(condition1[i] > condition2[i]) {
				if(document.getElementById("cell" + position[i]).hasChildNodes()) {
					neighbors.push(document.getElementById("cell" + position[i]).firstChild.id);
				}else {
					return "blank";
				}
			}
		}
		return neighbors;
	}
})();