<?php 
session_start();
include("connection.php");
include("functions.php");

if(isset($_GET['itemID'])) {
    $itemID = $_GET['itemID'];
    $sql = "SELECT * FROM Items WHERE itemID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $itemID);
    $stmt->execute();
    $result = $stmt->get_result();
    	
    if($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<h1>" . $row['itemName'] . "</h1>";
        echo "<p>Price: " . $row['itemCost'] . "</p>";
        echo "<img src='" . $row['itemImage']. "' alt='Item Image'>";
        // Add more item details as needed
    } else {
        echo "Item not found.";
    }
} else {
    echo "No item specified.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cards</title>
    <style>
		
		// this is really bad practice dont put CSS in HTML Files make a CSS file and Link the HTML files to that - USE W3 SCHOOLS TO DO THE VOTING SYSTEM BETTER!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
        #vote-system button {
            font-size: 24px;
            border: none;
            background: none;
            cursor: pointer;
            padding: 10px;
            outline: none;
        }

        .voted {
            color: orange;
        }
    </style>
</head>
<body>

    <div id="vote-system">
        <button id="upvote-button">&#x25B2;</button>
        <button id="downvote-button">&#x25BC;</button>
    </div>

    <script>
        document.getElementById('upvote-button').addEventListener('click', function() {
            this.classList.add('voted');
            document.getElementById('downvote-button').classList.remove('voted');
        });

        document.getElementById('downvote-button').addEventListener('click', function() {
            this.classList.add('voted');
            document.getElementById('upvote-button').classList.remove('voted');
        });
    </script>
</body>
</html>
