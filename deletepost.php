<?php
session_start();
include("connection.php");
include("functions.php");

$user_data = CheckLogin($con);
$requiredRank = 2; //required user rank to delete any posts is 2

if (!CheckRankAccess($requiredRank, $user_data)) {
    header("Location: index.php");
    die();
}

if (isset($_GET['searchTerm']) && !empty(trim($_GET['searchTerm']))) {
    $searchTerm = trim($_GET['searchTerm']);
    $postID = $searchTerm;

    $stmt = $con->prepare("DELETE FROM Post WHERE postID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($con->error));
    }

    $stmt->bind_param("i", $postID);

    if ($stmt->execute()) {
        echo "Post deleted successfully";
    } else {
        echo "Error deleting post: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}

$con->close();
?>