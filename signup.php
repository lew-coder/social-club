<?php 
session_start();

	include("connection.php");
	include("functions.php");

	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		$userRank = (int) $_POST['userRank'];
		$userName = $_POST['userName'];	
		$userEmail = $_POST['userEmail'];	
		$userPassword = $_POST['userPassword'];

		$Error = "";
		
		if(!preg_match("/^[12]+$/", $userRank)){ //checking that user has no input invalid characters
			$Error = "Please enter a valid userRank";
		}
		else if(!preg_match("/^[a-zA-Z]+$/", $userName)){ //checking that user has no input invalid characters
			$Error = "Please enter a valid user name";
		}
		else if(!preg_match("/^[\w\-\.]+@+[\w\-\.]+[\w\-\.]+$/", $userEmail)){ //checking that user has no input invalid characters
			$Error = "Please enter a valid email";
		}
		else{
			
			$userRank = SanitiseInput($userRank); //removes any unsafe characters from the users input
			$userName = SanitiseInput($userName);	
			$userEmail = SanitiseInput($userEmail);	
			
			
			$userPassword = password_hash($userPassword, PASSWORD_DEFAULT); //creates a hashed version of the users password to be stored in the database
						
				if(InsertNewUser($con, $userRank, $userName, $userEmail, $userPassword)) //inserting new users information from sign up form into database
				{
					header("Location: login.php");
					die;		
				}
				else
				{
					echo "Query unsuccessful!"; //if insertion fails, echos query unsuccessful
					die();
				} 	

		}	
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sign Up Page</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel ="stylesheet" href="style.css">
</head>

<body>

<div class="myDiv-container">
	<div class="myDiv">
		<img src="logo1.jpg" alt="logo">
		<h1>Social Club - SignUp</h1>
	</div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto center-bg">
		
<div class="postbox">
		
		<form method="post">
			<div> <?php 
			if(isset($Error) && $Error != ""){ //if there is an error with signup form then this is echod to the user
				echo $Error;
			}
			
			?> </div>
			
			<br><br>
			<input id="text" type="text" name="userRank" placeholder="User Rank" required><br><br>
			<input id="text" type="text" name="userName" placeholder="Username" required><br><br>
			<input id="text" type="text" name="userEmail" placeholder="Email Address" required><br><br>
			<input id="text" type="password" name="userPassword" placeholder="Password" required><br><br>

			<input id="button" type="submit" value="Signup"><br><br>
		
		</form>
	
	<a href="login.php">Back to Login</a><br><br>
	</div>
	
	</div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>