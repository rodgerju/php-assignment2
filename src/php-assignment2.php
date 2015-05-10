<?php
ini_set('display_errors', 'On');
include 'storedInfo.php';
header('Content-Type: text/HTML');

$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "rodgerju-db", $myPassword, "rodgerju-db");
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} else {
	echo "Connection success";
}

if(isset($_POST['Category']) && $_POST['Category'] != "allMovies")
{
	$catFilter = $_POST['Category'];
	if(!($stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM video_store WHERE category = ?"))) {
		echo "Prepare failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	$stmt->bind_param("s", $catFilter);
	if (!$stmt->execute()) 
	{
		echo "Execute failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
}

else 
{
	if(!($stmt = $mysqli->prepare("SELECT id, name, category, length, rented FROM video_store"))) {
		echo "Prepare failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	if (!$stmt->execute()) {
		echo "Execute failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
}

$out_id = NULL;
$out_name = NULL;
$out_category = NULL;
$out_length = NULL;
$out_rented = NULL;

if (!$stmt->bind_result($out_id, $out_name, $out_category, $out_length, $out_rented)) {
	echo "Binding Failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo '<div>';
echo '<p> <table border = "1"> <tr> <td> ID <td> Name <td> Category <td> length <td> rented <td> checkout <td> remove';
while ($stmt->fetch()) {
	echo "<tr> <td> $out_id <td> $out_name <td> $out_category <td> $out_length <td> $out_rented";
	echo '<td> <form method="post" action ="http://web.engr.oregonstate.edu/~rodgerju/cs290/php-assign2/movie_checkout.php">';
	echo "<button>Check out/in</button> 
			<input type='hidden' name='id' value=$out_id>
			<input type='hidden' name='check' value=$out_rented>
			</form>";
	echo '<td> <form method="post" action ="http://web.engr.oregonstate.edu/~rodgerju/cs290/php-assign2/movie_remove.php">';
	echo "<button>Remove</button> 
			<input type='hidden' name='id' value=$out_id> 
			</form>";

}
echo '</table></p></div>';

echo '<div>
	<form method="post" action = "http://web.engr.oregonstate.edu/~rodgerju/cs290/php-assign2/movie_add.php">
		<fieldset>
			<legend>New Video</legend>
			<p>Title: <input type="text" name="mname"/></p>
			<p>Category: <input type="text" name="mcat"/></p>
			<p>Length: <input type="number" name="mlength" min="1"/></p>
			<p><input type="submit"/></p>
		</fieldset>
	</form>
	</div>';

if(!($stmt = $mysqli->prepare("SELECT DISTINCT category FROM video_store where category != ''"))) {
	echo "Prepare failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!$stmt->execute()) {
	echo "Execute failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

if (!$stmt->bind_result($out_category)) {
	echo "Binding Failed: (". $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
echo '<div>
	<form method="post" action = "http://web.engr.oregonstate.edu/~rodgerju/cs290/php-assign2/php-assignment2.php">
		<fieldset>
			<legend>Search by Category</legend>
			<p>Category: </p>
			<select name="Category">';
			while($stmt->fetch()) {
				echo "<option value='$out_category'>$out_category</option>";
			}
			echo "<option value='allMovies'>All</option>";
		echo '<p><input type="submit"/></p>
		</fieldset>
	</form>
	</div>';

	echo '<form method="post" action ="http://web.engr.oregonstate.edu/~rodgerju/cs290/php-assign2/movie_truncate.php">';
	echo "<button>Remove All Values</button> 
			<input type='hidden' name='command' value='remove'> 
			</form>";
?>