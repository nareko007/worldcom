<?php 

//Using singleton pattern to connect the database 

class Database {
	private $_connection;
	private static $_instance; //The single instance
	private $host = "localhost";
	private $username = "root";
	private $password = "root";
	private $database = "worldcom";
	
	public static function getInstance() {
		if(!self::$_instance) { 
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	// Constructor
	private function __construct() {
		$this->_connection = new mysqli($this->host, $this->username, $this->password, $this->database);
	
		// Error handling
		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
				 E_USER_ERROR);
		}
	}
	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}
?>