<?php

function InsertNewUser($con, $userRank, $userName, $userEmail, $userPassword){

$sqlQuery = $con->prepare("INSERT INTO Users (userRank, userName, userEmail, userPassword) VALUES (?, ?, ?, ?)"); //Inserts data received from form into database
if ($sqlQuery === false) {
    echo "<br>" . "error preparing" . "<br>";
}

if (!$sqlQuery->bind_param("isss", $userRank, $userName, $userEmail, $userPassword)) { //If the data could not be binded to the SQL database query return an echo
    echo "<br>" . "error binding" . "<br>";
}

$querySuccessful = true;

if (!$sqlQuery->execute()) { //if the query could not be executed
    $querySuccessful = false;   
}
	return $querySuccessful;
}


function SanitiseInput($input){
	
	$input = trim($input); //trim strips whitespace from a string
	$input = addslashes($input); //addslashes adds backslashes to characters that need to be escaped
	return $input;
}

function CheckLogin($con)
{
	if(isset($_SESSION['userID']))
	{
		$userID = $_SESSION['userID']; //sets userID variable to the userID of the logged in user
		$query = "select * from Users where userID = '$userID' limit 1"; //selects row of database based on userID value

		$result = mysqli_query($con,$query);
		if($result && mysqli_num_rows($result) > 0)
		{
			$user_data = mysqli_fetch_assoc($result);
			return $user_data; //outputs data row of Users table for user logged in
		}
	}

	header("Location: login.php"); //if the login details of the user logged in do not match up with what is in the database, the user is logged out
	die;
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; //available characters for the function
    $randomString = ''; //creates an empty variable
    $max = strlen($characters) - 1;
    
    for ($i = 0; $i < $length; $i++) { //loops for the length of the $length variable
        $randomString .= $characters[rand(0, $max)]; //adds a random $character each loop from the characters variable
    }
    return $randomString; //returns the random string made up of the $characters variable
}

function GenerateToken(){
	$_SESSION['token'] = generateRandomString(20); //sets the current sessions login token to the random string made previously which will be 20 digits long
}

function ValidateToken($formToken){
	
	if($formToken === $_SESSION['token']){ //checks if the current session token matches the token on the website
		
		return True;
	}
	
	return false;
}

function CheckRankAccess($requiredRank,$user_data)
{
	$userRank = $user_data['userRank'];	//pulls user rank from login data
	if($userRank >= $requiredRank)
	{
		return true; //returns true if the logged in user rank is above or equal to the required rank
	}
	else{
		return false;
	}
}

?>

