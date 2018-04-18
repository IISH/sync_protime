<?php 
class class_pdo {
	private $server;
	private $user;
	private $password;
	private $database;
	private $driver;
	private $port;
	private $conn;

	function __construct($database) {
		$this->server = trim($database["host"]);
		$this->user = trim($database["username"]);
		$this->password = trim($database["password"]);
		$this->database = trim($database["database"]);
		$this->driver = trim($database["driver"]);
		$this->port = trim($database["port"]);

		if ( $this->driver == '' ) {
			$this->driver = 'mysql';
		}

		//
		$this->connect();
	}

	private function connect() {
		try {
			$this->conn = new PDO($this->driver . ':host=' . $this->server . ( $this->port != '' ? ':' . $this->port : '') . ';dbname=' . $this->database, $this->user, $this->password);
		} catch (PDOException $e) {
			die('Connection failed to: ' . $this->server . ', message: ' . $e->getMessage());
		}
	}

	public function getConnection() {
		return $this->conn;
	}

	public function close() {
		$this->conn = null;
	}

	public function __toString() {
		return "Class: " . get_class($this) . "\nserver: " . $this->server . "\nuser: " . $this->user . "\ndatabase: " . $this->database . "\ndriver: "  . $this->driver . "\n";
	}
}
