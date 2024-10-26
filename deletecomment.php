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
    $commentID = $searchTerm;

    $stmt = $con->prepare("DELETE FROM Comments WHERE commentID = ?");
    if ($stmt === false) {
        die("Prepare failed: " . htmlspecialchars($con->error));
    }

    $stmt->bind_param("i", $commentID);

    if ($stmt->execute()) {
        echo "Comment deleted successfully";
    } else {
        echo "Error deleting comment: " . htmlspecialchars($stmt->error);
    }

    $stmt->close();
}

$con->close();
?>