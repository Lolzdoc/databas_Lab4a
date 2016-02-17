<?php
	require_once('database.inc.php');

	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$movieSelected = $_POST['movieName'];
	$_SESSION['movieName'] = $movieSelected;
	$db->openConnection();
	
	$MovieDates = $db->getMovieDates($movieSelected);
	$db->closeConnection();
?>




<html>
<head><title>Booking 2</title><head>
<body><h1>Booking 2</h1>
	Current user: <?php print $userId ?><br>
	Selected Movie: <?php print $movieSelected ?>
	<p>
	Movies showing:
	<p>
	<form method=post action="booking3.php">
		<select name="movieDate" size=10>
		<?php
			$first = true;
			foreach ($MovieDates as $dates) {
				if ($first) {
					print "<option selected>";
					$first = false;
				} else {
					print "<option>";
				}
				print $dates[0];
			}
		?>
		</select>		
		<input type=submit value="Select date">
	</form>
</body>
</html>
