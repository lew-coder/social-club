<?php
session_start();
include("connection.php");
include("functions.php");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Social Club</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel ="stylesheet" href="style.css">
</head>
<body>

<?php //receiving userID from search function and getting user information from database
if (isset($_GET['userID'])) {
    $user = $_GET['userID'];
    $sqlUser = "SELECT * FROM Users WHERE userID = ?";
    $stmt = $con->prepare($sqlUser);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $resultUser = $stmt->get_result();
    if ($resultUser->num_rows > 0) {
        while ($userRow = $resultUser->fetch_assoc()) {
            $userName = $userRow['userName'];
		}
	}
}
?>

<!-- remaining code is the same as the index (home) page -->

<div class="myDiv-container">
	<div class="myDiv">
		<img src="logo1.jpg" alt="logo">
		<h1><?php echo $userName;?></h1>
		<img src="profilepic.png" alt="profile pic">
	</div>
</div>

<div class="intro">
	<br><a href="index.php">Home Page</a><br>
	<br><a href="addpost.php">Add New Post</a><br>
	<br><a href="logout.php">Logout</a><br><br>
</div>

<div class="PostDiv">
<?php
if(isset($_GET['userID'])) {
    $userID = $_GET['userID'];
    $sql = "SELECT * FROM Post WHERE userID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$userID = $row['userID'];
			$sqeal = "SELECT * FROM Users Where userID = '$userID'";
			$resulto = $con->query($sqeal);
			if ($resulto->num_rows > 0) {
				while($row = $resulto->fetch_assoc()) {
					$userName = $row['userName'];
					$sequel = "SELECT * FROM Post WHERE userID = '$userID'";
					$results = $con->query($sequel);
					if ($results->num_rows > 0) {
						while($row = $results->fetch_assoc()) {
							echo '<div class="postcontainer">';
							echo "<br>" . $userName . ": " . $row["postTitle"] . "<br>";
							echo "<br>";
							echo '<img src="' . $row['postImage'] . '" width="500" height="500" alt="Post Image">';
							echo "<br><br> &#8226; " . $row["postDesc"] . "<br>";
							echo "<br>";
							echo '<div id="vote-system">';
							echo '<button id="upvote-button">&#x25B2;</button>';
							echo '<button id="downvote-button">&#x25BC;</button>';
							echo '</div>';
							echo "<br>";
							echo '<a href="deletepost.php?searchTerm=' . $row['postID'] . '">Delete</a>';
							echo "<br><br>";
							echo '<form method="post" enctype="multipart/form-data">';
							echo '<div class="comment">Comment</div>';
							echo '<input id="text" type="text" name="commentDesc">';
							echo '<input type="hidden" name="postID" value="' . $row['postID'] . '">';
							echo "<br>";
							echo '<input type="submit" value="Submit">';
							echo '</form>';
							echo "<br>";
							echo '<div class="PostDiv">';
							echo "Comments: <br>";
							$pc = $row["postID"];
							$sqlC = "SELECT * FROM Comments WHERE postID = '$pc'";
							$resultC = $con->query($sqlC);
							if ($resultC->num_rows > 0) {
								while($row = $resultC->fetch_assoc()) {
									$commentD = $row['commentDesc'];
									$user = $row['userID'];
									$sqlCN = "SELECT * FROM Users WHERE userID = '$user'";
									$resultCN = $con->query($sqlCN);
									if ($resultCN->num_rows > 0) {
										while($row = $resultCN->fetch_assoc()) {
											echo "<br>" . $row['userName'] . ": " . $commentD;
										}
									}
								}
							}
						}
						echo "</div>";
						echo "<br>";
						echo "<br>";
						echo "<br>";
					}
				}
			}
		}
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $commentDesc = $_POST['commentDesc'];
    $postID = $_POST['postID'];
    $userID = $_SESSION['userID'];
    $stmt = $con->prepare("INSERT INTO Comments (postID, userID, commentDesc) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $postID, $userID, $commentDesc);
    if ($stmt->execute()) {
		echo "<meta http-equiv='refresh' content='0'>";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
</div>

<script>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>