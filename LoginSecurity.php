<?php
	//Checking session name for security.
	if(!isset($_SESSION['username'])){
		header("LOCATION: login.php");
	}
?>