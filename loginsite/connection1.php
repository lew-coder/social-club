<?php

$dbhost = "lochnagar.abertay.ac.uk";
$dbuser = "sql2310187";
$dbpass = "findlaw-certain-belize-muslim";
$dbname = "sql2310187";

if (!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))
{
	
	die("Failed to connect!");
}
?>
