<?php 
session_start(); //creates a session or resumes session
include("connection.php");
include("functions.php"); //including required web pages

$loginpopup = ''; //creating empty variable for JS DOM Event

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['token']) && ValidateToken($_POST['token'])) //validates session token vs website token
{
		$userName = $_POST['userName'];
	
	if(!empty($userName) && !empty($_POST['userPassword']) && !is_numeric($userName)) //if the username & password are not empty and the username is made up of letters
	{				
		$sqlQuery = $con->prepare("select * from Users where userName = ? limit 1"); //prepares to check database for a username match
		$sqlQuery->bind_param("s", $userName); //binds username found to the Query
	
		if ($sqlQuery->execute()) {	//if the query is succesful
			$result = $sqlQuery->get_result();
			if ($result) {
				$user_data = $result->fetch_assoc();
				if ($user_data) { //checking login details vs database user data
				
					if (password_verify($_POST['userPassword'], $user_data['userPassword'])) {	//verifying username and password vs database
						$_SESSION['userID'] = $user_data['userID'];
						$loginpopup = 'Login Success'; //makes empty variable created equel text
						header("Location: index.php"); //sends user to home page
						die; //ends script
					}
					else { //if userdata check fails
					$loginpopup = 'Wrong Login Credentials';
					}
				}
				else { //if user data check fails
					$loginpopup = 'Wrong Login Credentials';
				}
			} 
		}		
	}
	else //if no data is input into login form
	{
		$loginpopup = 'Please Input Login Credentials';
	}
}	
GenerateToken(); //website token generated last
?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel ="stylesheet" href="style.css"> <!-- Implementing CSS from stylesheet -->
	
</head>

<body>

<div class="myDiv-container"> <!-- Div created for this sections CSS -->
	<div class="myDiv">
		<img src="logo1.jpg" alt="logo">
		<h1>Social Club - Login</h1>
	</div>
</div>

<br><br>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto center-bg">

<div class="postbox">	<!-- Div created for this sections CSS -->
	<form method="post"> <!-- Login form -->
		<input id="textBoxUserName" type="text" name="userName" placeholder="Username"><br><br>
		<input id="textBoxPassword" type="password" name="userPassword" placeholder="Password"><br><br>
		<input type="hidden" name="token" value = "<?=$_SESSION['token']?>">
		<input id="button" type="submit" value="Login"><br><br>
		<h4>Don't Have an account? <a href="signup.php">Click to SignUp</a></h4>
		</div>
	</form>
	<p class="message" id="message"></p> <!-- JS DOM Event pop up -->
</div>

	</div>
  </div>
</div>

<script> <!-- JS DOM Event code to echo message to user on succesful/unsuccessful login attempt, as JS would execute instantly without an Even listener -->
    document.addEventListener('DOMContentLoaded', function() {
        const message = "<?php echo $loginpopup; ?>";
        if (message) {
            const messageElement = document.getElementById('message');
            messageElement.textContent = message;
			messageElement.style.color = message === 'Login Success' ? 'green' : 'red';
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>