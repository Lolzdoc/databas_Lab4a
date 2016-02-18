<?php
	require_once('database.inc.php');

	session_start();
	$db = $_SESSION['db'];
	$userId = $_SESSION['userId'];
	$_SESSION['movieDate'];

	$db->openConnection();

	//$performance = $db->getPerformance($_SESSION['movieName'],$_SESSION['movieDate']);



	$bookingNumber = $db->bookTicket($_SESSION['movieName'],$_SESSION['movieDate'],$userId);
	$performance = $db->getPerformance($_SESSION['movieName'],$_SESSION['movieDate']);

	$db->closeConnection();
?>




<html>
<head><title>Booking 4</title><head>
<body><h1>Booking 4</h1>


	Current user: <?php print $userId ?><br>
	<p>
	Movie: <?php print $_SESSION['movieName']?><br>
	Date: <?php print $_SESSION['movieDate']?><br>
	Theater: <?php print $performance[0]['theaterName']?><br>
	Free Seats: <?php print $performance[0]['remainingSeats']?><br>

	bookingNbr : <?php if ($bookingNumber != -1) {
		print $bookingNumber;
		} else {
		print "Failure, unable too book a ticket!";
		}	?>
	<br>
	<p>
	<form method=post action="booking1.php">
		<input type=submit value="Book a new ticket">
	</form>


</body>
</html>

