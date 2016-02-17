<?php
/*
 * Class Database: interface to the movie database from PHP.
 *
 * You must:
 *
 * 1) Change the function userExists so the SQL query is appropriate for your tables.
 * 2) Write more functions.
 *
 */
class Database {
	private $host;
	private $userName;
	private $password;
	private $database;
	private $conn;
	
	/**
	 * Constructs a database object for the specified user.
	 */
	public function __construct($host, $userName, $password, $database) {
		$this->host = $host;
		$this->userName = $userName;
		$this->password = $password;
		$this->database = $database;
	}
	
	/** 
	 * Opens a connection to the database, using the earlier specified user
	 * name and password.
	 *
	 * @return true if the connection succeeded, false if the connection 
	 * couldn't be opened or the supplied user name and password were not 
	 * recognized.
	 */
	public function openConnection() {
		try {
			$this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", 
					$this->userName,  $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$error = "Connection error: " . $e->getMessage();
			print $error . "<p>";
			unset($this->conn);
			return false;
		}
		return true;
	}
	
	/**
	 * Closes the connection to the database.
	 */
	public function closeConnection() {
		$this->conn = null;
		unset($this->conn);
	}

	/**
	 * Checks if the connection to the database has been established.
	 *
	 * @return true if the connection has been established
	 */
	public function isConnected() {
		return isset($this->conn);
	}
	
	/**
	 * Execute a database query (select).
	 *
	 * @param $query The query string (SQL), with ? placeholders for parameters
	 * @param $param Array with parameters 
	 * @return The result set
	 */
	private function executeQuery($query, $param = null) {
		try {
			$stmt = $this->conn->prepare($query);
			$stmt->execute($param);
			$result = $stmt->fetchAll();
		} catch (PDOException $e) {
			$error = "*** Internal error: " . $e->getMessage() . "<p>" . $query;
			die($error);
		}
		return $result;
	}
	
	/**
	 * Execute a database update (insert/delete/update).
	 *
	 * @param $query The query string (SQL), with ? placeholders for parameters
	 * @param $param Array with parameters 
	 * @return The number of affected rows
	 */
	private function executeUpdate($query, $param = null) {
		// ...
	}
	
	/**
	 * Check if a user with the specified user id exists in the database.
	 * Queries the Users database table.
	 *
	 * @param userId The user id 
	 * @return true if the user exists, false otherwise.
	 */
	public function userExists($userId) {
		$sql = "select * from Users WHERE Users.userName = ?";
		$result = $this->executeQuery($sql, array($userId));
		return count($result) == 1; 
	}

	/*
	 * *** Add functions ***
	 */

	public function getMovieDates($movieName) {
		$sql = "select performanceDate from Performance where Performance.movieName = ? order by performanceDate";
		$result = $this->executeQuery($sql,array($movieName));
		//print_r($result);
		return $result;
	}

	public function getMovieNames() {
		$sql = "select * from Movies order by movieName";
		$result = $this->executeQuery($sql);
		//print_r($result);
		return $result;
	}

public function getPerformance($movieName,$movieDate){
		

		$sql = "select * from Performance where Performance.movieName = ? and Performance.performanceDate = ? order by performanceDate";
		$result = $this->executeQuery($sql,array($movieName, $movieDate));
		//print_r($result);
		return $result;


	}

public function bookTicket($movieName,$movieDate,$userId){
		$update = "update Performance set remainingSeats = remainingSeats - 1 where Performance.movieName = ? and Performance.performanceDate = ?";
		$update = $this->applyChanges($update,array($movieName, $movieDate));
		
		$insert = "INSERT INTO Ticket (userName,performanceDate,movieName) VALUES (?, ?, ?)";
		$insert = $this->executeQuery($insert,array($userId,$movieDate,$movieName));

		$get = "select reservNbr from Ticket where userName = ? and performanceDate = ? and movieName = ?";
		$get = $this->executeQuery($get,array($userId,$movieDate,$movieName));
		//print_r($result);
		return $get[0];
}

}
?>
