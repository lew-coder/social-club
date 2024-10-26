<?php 
session_start();

	include("connection.php");
	include("functions.php");
	
	
	$user_data = CheckLogin($con);
?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $user_data['userName']; ?></title>
</head>

<body>

<h1><?php echo $user_data['userName']; ?></h1>

<br><a href="index.php">Home Page</a><br>

<br><a href="addpost.php">Add New Post</a><br>

<br><a href="logout.php">Logout</a><br>

<?php
$userID = $_SESSION['userID'];

$sql = "SELECT * FROM Users WHERE userID = '$userID'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<br> User Name: ". $row["userName"]."<br>";
	}
}
$sql = "SELECT * FROM Post WHERE userID = '$userID'";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<br> Post Title: ". $row["postTitle"]."<br>";
		echo '<img src="' . $row['postImage'] . '" width="500" height="500" alt="Post Image">';
		echo "<br> Post Caption: ". $row["postDesc"]."<br>";
		echo "<br>";
		echo "<br>";
	}
}
else {
	echo "Nothing posted yet";
}
?>


</body>
</html>