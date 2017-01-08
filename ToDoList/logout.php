<?php
# Lia Roberds, CSE 154, HW8, logout.php
# This file logs the user out and redirects them to the start page
 
include("common.php");
session_start();
session_destroy();

redirect("start.php", "You have been logged out.");
?>