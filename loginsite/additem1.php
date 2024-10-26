<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = CheckLogin($con);
$requiredRank = 0;

// Check if user is appropriate rank to view page; if not, log them out
if(!CheckRankAccess($requiredRank, $user_data)){
    header("Location: logout.php");
    die(); // Ensure no further code is executed after a redirect
}

// Add item to database     
if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $itemName = $_POST['itemName'];
    $itemCost = floatval($_POST['itemCost']); // Convert to decimal number

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["itemImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["itemImage"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["itemImage"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Proceed if no upload checks have failed
    if ($uploadOk == 1) {
        // Check if there's an error with the file
        if ($_FILES["itemImage"]["error"] == 0) {
            // Try to move the file from the temporary directory to the uploads directory
            if (move_uploaded_file($_FILES["itemImage"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars(basename($_FILES["itemImage"]["name"])). " has been uploaded.";
                $itemImage = $target_file; // Path to be stored in the database

                // Prepare SQL statement to prevent SQL injection
                $stmt = $con->prepare("INSERT INTO Items (itemName, itemImage, itemCost) VALUES (?, ?, ?)");
                $stmt->bind_param("ssd", $itemName, $itemImage, $itemCost);

                if($stmt->execute()) {
                    echo "Item Added Successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                // Provide detailed error information
                echo "The file was uploaded to the temporary directory, but moving it to '$target_file' failed.";
                if (!is_writable($target_dir)) {
                    echo " Error: The target directory '$target_dir' is not writable.";
                }
                if (!is_dir($target_dir)) {
                    echo " Error: The target directory '$target_dir' does not exist.";
                }
            }
        } else {
            // If the file upload encountered an actual error, display it
            echo "File upload error. Error code: " . $_FILES["itemImage"]["error"];
        }
    } else {
        echo "File did not pass the upload checks.";
    }
}
?>

<!DOCTYPE html>
<html>
<body>

<h1>Add Items Page</h1>

<a href="index.php">Home</a><br>
<a href="logout.php">Logout</a><br>

<div id="newItemBox">            
    <form method="post" enctype="multipart/form-data">
        <div style="font-size: 20px;margin: 10px;color: white;">Add New Item</div>
        
        <input id="text" type="text" name="itemName"><br><br>
        <input id="file" type="file" name="itemImage"><br><br>
        <input id="text" type="text" name="itemCost"><br><br>

        <input id="button" type="submit" value="Add New Item"><br><br>
    
    </form>
</div>

</body>
</html>
