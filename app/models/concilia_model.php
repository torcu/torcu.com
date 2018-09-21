<?php

class Concilia_model extends Model {

	public function __construct()
	{
		global $config;
		$config['db_host'] 	= 'localhost'; // Database host (e.g. localhost)
		$config['db_name'] 	= 'concilia'; // Database name
		$config['db_username'] 	= 'root'; // Database username
		$config['db_password'] 	= ''; // Database password
		$config['db_charset'] 	= 'utf8'; //Database charset
		parent::__construct();
	}
	
	public function getSomething($id)
	{
		$id = $this->escapeString($id);
		$result = $this->query('SELECT * FROM something WHERE id="'. $id .'"');
		return $result;
	}

	public function create_table($sql)
	{
		$result = $this->query($sql);
		return $result;
	}
}

