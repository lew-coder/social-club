<?php

function InsertNewUser($con, $userRank, $userName, $userEmail, $userPassword){

$sqlQuery = $con->prepare("INSERT INTO Users (userRank, userName, userEmail, userPassword) VALUES (?, ?, ?, ?)");
if ($sqlQuery === false) {
    echo "<br>" . "error preparing" . "<br>";
}

if (!$sqlQuery->bind_param("isss", $userRank, $userName, $userEmail, $userPassword)) {
    echo "<br>" . "error binding" . "<br>";
}

$querySuccessful = true;

if (!$sqlQuery->execute()) {
    $querySuccessful = false;   
}
	return $querySuccessful;
}


function SanitiseInput($input){
	
	$input = trim($input);
	$input = addslashes($input); 
	return $input;
}


function DisplayAllUsers($con)
{
	
$sql = "SELECT userID, userName FROM Users";
	$result = $con->query($sql);	
	if ($result->num_rows > 0) {
		echo "<br>" . "We have results!" . "<br>";
		while($row = $result->fetch_assoc()) {
			echo "<br> User ID: ". $row["userID"]." - User Name: ". $row["userName"]."<br>";
		}
	} 
	else {
		echo "0 results" . "<br>";
	}
}


function CheckLogin($con)
{
	if(isset($_SESSION['userID']))
	{
		$userID = $_SESSION['userID'];
		$query = "select * from Users where userID = '$userID' limit 1";

		$result = mysqli_query($con,$query);
		if($result && mysqli_num_rows($result) > 0)
		{
			$user_data = mysqli_fetch_assoc($result);
			return $user_data;
		}
	}

	header("Location: login.php");
	die;
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    $max = strlen($characters) - 1;
    
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $max)];
    }
    return $randomString;
}

function GenerateToken(){
	$_SESSION['token'] = generateRandomString(20); 
}

function ValidateToken($formToken){
	
	if($formToken === $_SESSION['token']){
		
		return True;
	}
	
	return false;
}

function CheckRankAccess($requiredRank,$user_data)
{
	$userRank = $user_data['userRank'];	
	if($userRank <= $requiredRank)
	{
		return true;
	}
	else{
		return false;
	}
}

?>

