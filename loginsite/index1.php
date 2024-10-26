<?php 
session_start();

	include("connection.php");
	include("functions.php");
	
	
	$user_data = CheckLogin($con);
	$requiredRank = 0;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Alexander's Data Demo Page</title>
</head>

<body>

<h1>Welcome to the index page!</h1>

<h3>Hello <?php echo $user_data['userName']; ?>, we've been expecting you<h3>

<a href="logout.php">Logout</a><br>

<h3>Check out the new set trailer<h3>

<iframe 
width="420" height="345" 
src="https://www.youtube.com/embed/si8G0pR7zSI">
</iframe>


<?php 

if(CheckRankAccess($requiredRank,$user_data)){

	echo "<br>" . "User Location " . htmlentities($user_data['userLocation']) . "<br>";
?>	
	<br><a href="additem.php">Add New Item</a><br>
<?php 	
	DisplayAllUsers($con);
}
else{
	echo "<br>" . "Nothing to see here. Move along" . "<br>";
}
?>	

<h2>Our cards for sale</h2>
<!-- Search form -->
<form action="searchItem.php" method="get">
    <input type="text" name="searchTerm" placeholder="Search for an item...">
    <input type="submit" value="Search">
</form>
<?php 	
// display items for sale
$sql = "SELECT * FROM Items";
$result = $con->query($sql);    
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<br> Card Name: ". $row["itemName"]." - Price: ". $row["itemCost"]."<br>";
      
        // old button 
		//<button type="button" onclick="alert('You clicked on <?php echo addslashes($row['itemName']);')">Add to Basket!</button>
		
		// new button 
        echo "<a href='viewitem.php?itemID=" . urlencode($row['itemID']) . "' class='button'>View Item</a><br>";        
    }
} 
else {
    echo "0 item results" . "<br>";
}

?>	
</body>
</html>

