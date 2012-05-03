<?php

/*
	CRUD PDO Class
	Nov 2011,
	Wim Norder
	wim-norder@yandex.com 
*/

/*
Connect to MySQL
$db = new db("mysql:host=127.0.0.1;port=8889;dbname=mydb", "dbuser", "dbpasswd");

Connect to SQLite
$db = new db("sqlite:db.sqlite");
*/

class db extends PDO {
	private $error;
	private $sql;
	private $bind;
	private $errorCallbackFunction;
	private $errorMsgFormat;

	public function __construct($dsn, $user="", $passwd="") {
		$options = array(
			PDO::ATTR_PERSISTENT => true, 
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try {
			parent::__construct($dsn, $user, $passwd, $options);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
		}
	}

	private function debug() {
		if(!empty($this->errorCallbackFunction)) {
			$error = array("Error" => $this->error);
			if(!empty($this->sql))
				$error["SQL Statement"] = $this->sql;
			if(!empty($this->bind))
				$error["Bind Parameters"] = trim(print_r($this->bind, true));

			$backtrace = debug_backtrace();
			if(!empty($backtrace)) {
				foreach($backtrace as $info) {
					if($info["file"] != __FILE__)
						$error["Backtrace"] = $info["file"] . " at line " . $info["line"];	
				}		
			}

			$msg = "";
			if($this->errorMsgFormat == "html") {
				if(!empty($error["Bind Parameters"]))
					$error["Bind Parameters"] = "<pre>" . $error["Bind Parameters"] . "</pre>";
				$css = trim(file_get_contents(dirname(__FILE__) . "/error.css"));
				$msg .= '<style type="text/css">' . "\n" . $css . "\n</style>";
				$msg .= "\n" . '<div class="db-error">' . "\n\t<h3>SQL Error</h3>";
				foreach($error as $key => $val)
					$msg .= "\n\t<label>" . $key . ":</label>" . $val;
				$msg .= "\n\t</div>\n</div>";
			}
			elseif($this->errorMsgFormat == "text") {
				$msg .= "SQL Error\n" . str_repeat("-", 50);
				foreach($error as $key => $val)
					$msg .= "\n\n$key:\n$val";
			}

			$func = $this->errorCallbackFunction;
			$func($msg);
		}
	}

	### public function delete($table, $where, $bind="") { }
	/**** DELETION ********************************************************
	
	#1
	$db->delete("mytable", "Age < 30");

	#2 
	$lname = "Doe";
	$bind = array(
	    ":lname" => $lname);
	
	$db->delete("mytable", "LName = :lname", $bind);
	
	*//////////////////////////////////////////////////////////////////////
	public function delete($table, $where, $bind="") {
		$sql = "DELETE FROM " . $table . " WHERE " . $where . ";";
		$this->run($sql, $bind);
	}

	private function filter($table, $info) {
		$driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
		if($driver == 'sqlite') {
			$sql = "PRAGMA table_info('" . $table . "');";
			$key = "name";
		}
		elseif($driver == 'mysql') {
			$sql = "DESCRIBE " . $table . ";";
			$key = "Field";
		}
		else {	
			$sql = "SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table . "';";
			$key = "column_name";
		}	

		if(false !== ($list = $this->run($sql))) {
			$fields = array();
			foreach($list as $record)
				$fields[] = $record[$key];
			return array_values(array_intersect($fields, array_keys($info)));
		}
		return array();
	}

	private function cleanup($bind) {
		if(!is_array($bind)) {
			if(!empty($bind))
				$bind = array($bind);
			else
				$bind = array();
		}
		return $bind;
	}

	### public function insert($table, $info) { }
	/**** INSERTION ********************************************************
	
	$insert = array(
		"FName" => "John",
		"LName" => "Doe",
		"Age" => 26,
		"Gender" => "male"
	);
	
	$db->insert("mytable", $insert);
	
	*//////////////////////////////////////////////////////////////////////
	public function insert($table, $info) {
		$fields = $this->filter($table, $info);
		$sql = "INSERT INTO " . $table . " (" . implode($fields, ", ") . ") VALUES (:" . implode($fields, ", :") . ");";
		$bind = array();
		foreach($fields as $field)
			$bind[":$field"] = $info[$field];
		return $this->run($sql, $bind);
	}

	// RAW SQL Query String
	### public function run($sql, $bind="") { }
	/**********************************************************************
	
	$sql = "CREATE TABLE mytable (
		ID int(11) NOT NULL AUTO_INCREMENT,
		FName varchar(50) NOT NULL,
		LName varchar(50) NOT NULL,
		Age int(11) NOT NULL,
		Gender enum('male','female') NOT NULL,
		PRIMARY KEY (ID)
	    ) ENGINE=InnoDB  DEFAULT CHARSET=utf-8 AUTO_INCREMENT=11 ;";
	    
	$db->run($sql);
	
	*//////////////////////////////////////////////////////////////////////
	public function run($sql, $bind="") {
		$this->sql = trim($sql);
		$this->bind = $this->cleanup($bind);
		$this->error = "";

		try {
			$pdostmt = $this->prepare($this->sql);
			if($pdostmt->execute($this->bind) !== false) {
				if(preg_match("/^(" . implode("|", array("select", "describe", "pragma")) . ") /i", $this->sql))
					return $pdostmt->fetchAll(PDO::FETCH_ASSOC);
				elseif(preg_match("/^(" . implode("|", array("delete", "insert", "update")) . ") /i", $this->sql))
					return $pdostmt->rowCount();
			}	
		} catch (PDOException $e) {
			$this->error = $e->getMessage();	
			$this->debug();
			return false;
		}
	}

	### public function select($table, $where="", $bind="", $fields="*") { }
	/***** SELECT *********************************************************
	
		#1
		$results = $db->select("mytable");
		
		#2
		$results = $db->select("mytable", "Gender = 'male'");
		
		#3
		$search = "J";
		$bind = array(
		    ":search" => "%$search"
		);
		
		$results = $db->select("mytable", "FName LIKE :search", $bind);
	
	*//////////////////////////////////////////////////////////////////////
	public function select($table, $where="", $bind="", $fields="*") {
		$sql = "SELECT " . $fields . " FROM " . $table;
		if(!empty($where))
			$sql .= " WHERE " . $where;
		$sql .= ";";
		return $this->run($sql, $bind);
	}


	/*
	
	CSS Styled Version (default)
	$db->setErrorCallbackFunction("myErrorHandler", "text");
	
	PHP echo Function
	$db->setErrorCallbackFunction("echo");
	
	*/
	public function setErrorCallbackFunction($errorCallbackFunction, $errorMsgFormat="html") {
		//	Проблема описана здесь:
		#	http://php.net/manual/en/functions.variable-functions.php
		#	Variable functions for won't work with language constructs such as echo and print, but... so these are replaced with print_r.
		//
		if(in_array(strtolower($errorCallbackFunction), array("echo", "print")))
			$errorCallbackFunction = "print_r";

		if(function_exists($errorCallbackFunction)) {
			$this->errorCallbackFunction = $errorCallbackFunction;	
			if(!in_array(strtolower($errorMsgFormat), array("html", "text")))
				$errorMsgFormat = "html";
			$this->errorMsgFormat = $errorMsgFormat;	
		}	
	}

	### public function update($table, $info, $where, $bind="") { }
	/***** UPDATE **********************************************************
		
		#1
		$update = array(
		    "FName" => "Jane",
		    "Gender" => "female"
		);
		$db->update("mytable", $update, "FName = 'John'");
		
		#2
		$update = array(
		    "Age" => 24
		);
		$fname = "Jane";
		$lname = "Doe";
		$bind = array(
		    ":fname" => $fname,
		    ":lname" => $lname
		);
		
		$db->update("mytable", $update, "FName = :fname AND LName = :lname", $bind);
		
	*//////////////////////////////////////////////////////////////////////
	public function update($table, $info, $where, $bind="") {
		$fields = $this->filter($table, $info);
		$fieldSize = sizeof($fields);

		$sql = "UPDATE " . $table . " SET ";
		for($f = 0; $f < $fieldSize; ++$f) {
			if($f > 0)
				$sql .= ", ";
			$sql .= $fields[$f] . " = :update_" . $fields[$f]; 
		}
		$sql .= " WHERE " . $where . ";";

		$bind = $this->cleanup($bind);
		foreach($fields as $field)
			$bind[":update_$field"] = $info[$field];
		
		return $this->run($sql, $bind);
	}
}	
?>
