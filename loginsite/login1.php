<?php 
session_start();
	include("connection.php");
	include("functions.php");
	
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['token']) && ValidateToken($_POST['token']))
{
		$userName = $_POST['userName'];
	
	if(!empty($userName) && !empty($_POST['userPassword']) && !is_numeric($userName))
	{				
		$sqlQuery = $con->prepare("select * from Users where userName = ? limit 1");
		$sqlQuery->bind_param("s", $userName);
	
		if ($sqlQuery->execute()) {		
			$result = $sqlQuery->get_result();
			if ($result) {
				$user_data = $result->fetch_assoc();
				if ($user_data) {
				
					if (password_verify($_POST['userPassword'], $user_data['userPassword'])) {			
						$_SESSION['userID'] = $user_data['userID'];
						header("Location: index.php");
						die;
					}								
				} 
				else {
					echo "wrong username or password!";
				}
			} 
		}		
	}
	else
	{
		echo "wrong username or password!";
	}
}	
GenerateToken();
?>



<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
</head>

<body>

<h1>This is the login page</h1>

</body>
</html>

<body>
	<div id="box">	
		<form method="post">
			<div style="font-size: 20px;margin: 10px;color: white;">Login</div>
			<input id="textBoxUserName" type="text" name="userName"><br><br>
			<input id="textBoxPassword" type="password" name="userPassword"><br><br>
			<input type="hidden" name="token" value = "<?=$_SESSION['token']?>">
			<input id="button" type="submit" value="Login"><br><br>		
		</form>
	</div>
	
	
	<br><h4>Don't Have an account?</h4>
	<a href="signup.php">Click to Signup</a><br><br>
</body>
</html>


