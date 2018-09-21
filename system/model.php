<?php

class Model {

	private $connection;

	public function __construct()
	{
		global $config;
		$this->connection = new PDO('mysql:host='.$config['db_host'].';dbname='.$config['db_name'].';charset='.$config['db_charset'], $config['db_username'], $config['db_password']);
		$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}

	public function escapeString($string)
	{
		return mysql_real_escape_string($string);
	}

	public function escapeArray($array)
	{
	    array_walk_recursive($array, create_function('&$v', '$v = mysql_real_escape_string($v);'));
		return $array;
	}
	
	public function to_bool($val)
	{
	    return !!$val;
	}
	
	public function to_date($val)
	{
	    return date('Y-m-d', $val);
	}
	
	public function to_time($val)
	{
	    return date('H:i:s', $val);
	}
	
	public function to_datetime($val)
	{
	    return date('Y-m-d H:i:s', $val);
	}
	
	public function query($query)
	{
		try {
			$results = $this->connection->query($query);
		} catch(PDOException $ex) {
			die("MySQL error: ".$ex->getMessage());
		}
		return $results->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function last_insert_id() {
		return $this->connection->lastInsertId();
	}

	public function execute($query)
	{
		try {
			$results = $this->connection->query($query);
		} catch(PDOException $ex) {
			die("MySQL error: ".$ex->getMessage());
		}
		return $results;
	}
    
}
?>
