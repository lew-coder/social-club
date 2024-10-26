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
		
		if(!preg_match("/^[0-6]+$/", $userRank)){
			$Error = "Please enter a valid userRank";
		}
		else if(!preg_match("/^[a-zA-Z]+$/", $userName)){
			$Error = "Please enter a valid user name";
		}
		else if(!preg_match("/^[\w\-\.]+@+[\w\-\.]+[\w\-\.]+$/", $userEmail)){
			$Error = "Please enter a valid email";
		}
		else{
			
			$userRank = SanitiseInput($userRank);
			$userName = SanitiseInput($userName);	
			$userEmail = SanitiseInput($userEmail);	
			
			
			$userPassword = password_hash($userPassword, PASSWORD_DEFAULT);
						
				if(InsertNewUser($con, $userRank, $userName, $userEmail, $userPassword))
				{
					header("Location: login.php");
					die;		
				}
				else
				{
					echo "Query unsuccessful!";
					die();
				} 	

		}	
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Sign Up Page</title>
</head>

<body>

<h1>This is the Sign Up page</h1>

<div id="box">
		
		<form method="post">
			<div> <?php 
			if(isset($Error) && $Error != ""){
				echo $Error;
			}
			
			?> </div>
		
		
			<div style="font-size: 20px;margin: 10px;color: white;">Signup</div>
			<input id="text" type="text" name="userRank" required><br><br>
			<input id="text" type="text" name="userName" required><br><br>
			<input id="text" type="text" name="userEmail" required><br><br>
			<input id="text" type="password" name="userPassword" required><br><br>

			<input id="button" type="submit" value="Signup"><br><br>
		
		</form>
	</div>
	
	<a href="login.php">Back to Login</a><br><br>
</body>
</html>