<?php 
session_start();

	include("connection.php");
	include("functions.php");
		
	$user_data = CheckLogin($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Social Club</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel ="stylesheet" href="style.css">
</head>

<body>
<br>
<div class="myDiv-container">
	<div class="myDiv">
		<img src="logo1.jpg" alt="logo">
		<h1>Social Club</h1>
	</div>
</div>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto center-bg">

<div class="intro">
	<h3>Hey <?php echo $user_data['userName']; ?>, it's been a while, see what everyone has been up to...<h3> <!-- uses login credentials to echo username -->
	<br>
	<a href="searchuser.php?searchTerm=<?php echo $user_data['userName'];?>">Profile Page</a><br> <!-- Links to other web pages on the server -->
	<br><a href="addpost.php">Add New Post</a><br>
	<br><a href="logout.php">Logout</a><br><br>
	
	<form action="searchuser.php" method="get">
		<input type="text" name="searchTerm" placeholder="Search for a User...">
		<input type="submit" value="Search">
	</form>
	
	<br><br><h4>Posts:</h4>
</div>

<div class="PostDiv">
  <div class="postcontainer">
<?php
$sql = "SELECT * FROM Users";
$result = $con->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) { //queries server for database row of each user
		$userID = $row['userID'];
		$userName = $row['userName'];
		$sequel = "SELECT * FROM Post WHERE userID = '$userID'";
		$results = $con->query($sequel); //queries database for post details of each user
		if ($results->num_rows > 0) {
			while($row = $results->fetch_assoc()) {
				echo "<br>" . $userName . ": " . $row["postTitle"] . "<br>"; //outputing user posts
				echo "<br>";
				echo '<img src="' . $row['postImage'] . '" width="450" height="450" alt="Post Image">';
				echo "<br><br> &#8226; " . $row["postDesc"] . "<br>";
				echo "<br>";
				echo '<div id="vote-system">';
				echo '<button id="upvote-button">&#x25B2;</button>'; //outputting upvote & downvote buttons below post
				echo '<button id="downvote-button">&#x25BC;</button>';
				echo '</div>';
				echo "<br>";
				echo '<a href="deletepost.php?searchTerm=' . $row['postID'] . '">Delete</a>'; //adding a delete post button linked to another website page
				echo "<br><br>";
				echo '<form method="post" enctype="multipart/form-data">';
				echo '<div class="comment">Comment</div>';
				echo '<input id="text" type="text" name="commentDesc">'; //adding a comment box
				echo '<input type="hidden" name="postID" value="' . $row['postID'] . '">'; //adding a hidden postID to each post
				echo "<br>";
				echo '<input type="submit" value="Submit">';
				echo '</form>';
				echo "<br>";
				echo "Comments: <br>";
				$pc = $row["postID"];
				$sqlC = "SELECT * FROM Comments WHERE postID = '$pc'"; //selecting comments for specific postID from database
				$resultC = $con->query($sqlC);
				if ($resultC->num_rows > 0) {
					while($row = $resultC->fetch_assoc()) {
						$commentD = $row['commentDesc'];
						$commentid = $row['commentID'];
						$user = $row['userID'];
						$sqlCN = "SELECT * FROM Users WHERE userID = '$user'";
						$resultCN = $con->query($sqlCN);
						if ($resultCN->num_rows > 0) {
							while($row = $resultCN->fetch_assoc()) {
								echo "<br>" . $row['userName'] . ": " . $commentD; //echoing comments from each user on specific posts
								echo '<a href="deletecomment.php?searchTerm=' . $commentid . '">Delete</a>'; //adding a delete comment button linked to another website page
							}
						}
					}
				}
				echo "<br>";
				echo "<br>";
				echo "<br>";
			}
		}
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { //seperate code block for inserting comment data so that it is specfic to user and post
    $commentDesc = $_POST['commentDesc'];
    $postID = $_POST['postID'];
    $userID = $_SESSION['userID'];
    $stmt = $con->prepare("INSERT INTO Comments (postID, userID, commentDesc) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $postID, $userID, $commentDesc);
    if ($stmt->execute()) {
		echo "<meta http-equiv='refresh' content='0'>"; //refreshing page after adding a comment so it appears instantly
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>

<script> <!-- Adding a JS DOM Event so that upvote and downvote buttons can be pressed and the information from the press is passed after the press -->
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded and parsed');
    
    var upvoteButton = document.getElementById('upvote-button');
    var downvoteButton = document.getElementById('downvote-button');
    
    if (upvoteButton && downvoteButton) {
        upvoteButton.addEventListener('click', function() {
            console.log('Upvote button clicked');
            this.classList.add('voted');
            downvoteButton.classList.remove('voted');
        });

        downvoteButton.addEventListener('click', function() {
            console.log('Downvote button clicked');
            this.classList.add('voted');
            upvoteButton.classList.remove('voted');
        });
    } else {
        console.error('Buttons not found');
    }
});
</script>

<h4>Logo Rave:</h4>
<iframe src="carousel.html"></iframe> <!-- bootstrap image carousel implementation -->
</div>
</div>

    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

