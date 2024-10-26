<?php
session_start();
include("connection.php");
include("functions.php");

if(isset($_GET['searchTerm']) && !empty(trim($_GET['searchTerm']))) { //trim removes whitespace from searchterm variable if it is not empty
    $searchTerm = trim($_GET['searchTerm']);
    $stmt = $con->prepare("SELECT userID FROM Users WHERE userName LIKE ?");
    $searchTerm = "%".$searchTerm."%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Debugging output
        echo "Redirecting to user: " . $row['userName'];
        // Actual redirection
        header("Location: otheruserpage.php?userID=" . $row['userID']); //sends user to this page which will automatically generate their profile page
        exit;
    } else {
        echo "Item not found. <a href='index.php'>Go back</a>";
    }
    $stmt->close();
} else {
    echo "No search term specified. <a href='index.php'>Go back</a>";
}
?>

