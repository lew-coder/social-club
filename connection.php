<?php
$dbhost = "lochnagar.abertay.ac.uk";
$dbuser = "sql2310187";
$dbpass = "findlaw-certain-belize-muslim";
$dbname = "sql2310187";

if (!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname))//If connection fails
{
	
	die("Failed to connect!");//Stop code and inform user that the connection failed
}
?>
