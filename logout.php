<?php
session_start();

if(isset($_SESSION['userID']))
{
	unset($_SESSION['userID']); //removed userID from session variable
}

header("Location: login.php"); //redirects user to login page
die;

?>

