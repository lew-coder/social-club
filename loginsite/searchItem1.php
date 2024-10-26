<?php
session_start();
include("connection.php");
include("functions.php");

if(isset($_GET['searchTerm']) && !empty(trim($_GET['searchTerm']))) {
    $searchTerm = trim($_GET['searchTerm']);
    $stmt = $con->prepare("SELECT itemID FROM Items WHERE itemName LIKE ?");
    $searchTerm = "%".$searchTerm."%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Debugging output
        echo "Redirecting to item with ID: " . $row['itemID'];
        // Actual redirection
        header("Location: viewitem.php?itemID=" . $row['itemID']);
        exit;
    } else {
        echo "Item not found. <a href='index.php'>Go back</a>";
    }
    $stmt->close();
} else {
    echo "No search term specified. <a href='index.php'>Go back</a>";
}
?>



