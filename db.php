<?php
	$db_hostname ='localhost';
	$db_username ='root';
	$db_password ='';
	$db_name = "food_inventory";
		
	$dbh = mysql_connect($db_hostname, $db_username, $db_password);

	mysql_select_db($db_name)
		or die("Unable to select database: ".mysql_error());
?>