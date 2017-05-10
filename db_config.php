<?php
	$db_host = "localhost";
	$db_user = "root";
	$db_pass = "";
	$db_name = "signsertrol";	
	
	$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
	if ($mysqli->connect_errno) {
 	   echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
?>