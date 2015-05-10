<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'storedInfo.php';
header('Content-Type: text/HTML');


if (!($_POST == null))
{
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "rodgerju-db", $myPassword, "rodgerju-db");
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	} else {
	echo "Connection success <br>";
	}
	
	$checkoutID = $_POST['id'];
	$checkoutVal = $_POST['check'];

	if ($checkoutVal == 0)
	{
		$checkoutVal = 1;
	}
	else
	{
		$checkoutVal = 0;
	}

	if(!($stmt = $mysqli->prepare("UPDATE video_store SET rented =? WHERE id =?")))
	{
		echo "Prepare failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	$stmt->bind_param("ii", $checkoutVal, $checkoutID);
	if (!$stmt->execute()) 
	{
		echo "Execute failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
}

echo 'Click 
	<a href=http://web.engr.oregonstate.edu/~rodgerju/cs290/php-assign2/php-assignment2.php> 
	here </a> to return';
?>