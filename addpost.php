<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = CheckLogin($con);

//adds item to database     
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $postTitle = $_POST['postTitle'];
    $postDesc = $_POST['postDesc'];
	$userID = $_SESSION['userID'];
	
	date_default_timezone_set("Europe/London"); //creating a post date for BST
	$Date = date("d-m-Y H:i:s");
	$postDate = $Date;

    //handles file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["postImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    //checks if image file is fake
    $check = getimagesize($_FILES["postImage"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    //checking if file exists already
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    //check file size, limit to 5MB
    if ($_FILES["postImage"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    //only allowing certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    //if no upload checks have failed
    if ($uploadOk == 1) {
        if ($_FILES["postImage"]["error"] == 0) {
            //try to move the file from the temp directory to the end directory
            if (move_uploaded_file($_FILES["postImage"]["tmp_name"], $target_file)) {
                echo "Your image file - ". htmlspecialchars(basename($_FILES["postImage"]["name"])). " - has been uploaded.";
                $postImage = $target_file; //file path to be stored in the database

                //prepares SQL statement to prevent SQL injection
                $stmt = $con->prepare("INSERT INTO Post (postTitle, postImage, postDesc, postDate, userID) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $postTitle, $postImage, $postDesc, $postDate, $userID);

                if($stmt->execute()) {
                    echo "<br>Post Added Successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                //echos error information if upload fails
                echo "The file was uploaded to the temporary directory, but moving it to '$target_file' failed.";
                if (!is_writable($target_dir)) {
                    echo " Error: The target directory '$target_dir' is not writable.";
                }
                if (!is_dir($target_dir)) {
                    echo " Error: The target directory '$target_dir' does not exist.";
                }
            }
        } else {
            //displays error message in full
            echo "File upload error. Error code: " . $_FILES["postImage"]["error"];
        }
    } else {
        echo "File did not pass the upload checks.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Social Club</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel ="stylesheet" href="style.css">
</head>
<body>

<div class="myDiv-container">
	<div class="myDiv">
		<img src="logo1.jpg" alt="logo">
		<h1>Add Post</h1>
	</div>
</div>

<br>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-auto center-bg">

<div class="intro">
	<br><a href="index.php">Home</a><br><br>
	<a href="logout.php">Logout</a><br><br>
</div>

<div class="postbox">
	<form method="post" enctype="multipart/form-data">

		<p>Post Title:</p>
		<input id="text" type="text" name="postTitle"><br><br>

		<p>Post Image:</p>
		<input id="file" type="file" name="postImage"><br><br>
		
		<p>Post Caption:</p>
		<input id="text" type="text" name="postDesc"><br><br>

		<input id="button" type="submit" value="Add New Post"><br><br>
	
	</form>
</div>

	</div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
