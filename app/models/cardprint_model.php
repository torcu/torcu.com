<?php

class Cardprint_model extends Model {

	public function __construct()
	{
		global $config;
		$config['db_host'] 	= 'localhost'; // Database host (e.g. localhost)
		$config['db_name'] 	= 'cardprint'; // Database name
		$config['db_username'] 	= 'cardprint_user'; // Database username
		$config['db_password'] 	= 'cardprint_pwd'; // Database password
		$config['db_charset'] 	= 'utf8'; //Database charset
		parent::__construct();
	}

	public function getSomething($id)
	{
		$id = $this->escapeString($id);
		$result = $this->query('SELECT * FROM something WHERE id="'. $id .'"');
		return $result;
	}

	public function getUserInfo($comp_id,$user_id)
	{
		$result = $this->query('SELECT * FROM customers WHERE cust_id="'. $user_id .'"');
		return $result;
	}

	public function getUserEntries($comp_id,$user_id)
	{
		$result = $this->query('SELECT * FROM entries WHERE ent_com_id="'.$comp_id.'" AND ent_cust_id="'. $user_id .'"  ORDER BY ent_date DESC');
		return $result;
	}

	public function geCompanyInfo($com_id)
	{
		return 'info';
	}

	public function getBalance($comp_id,$user_id)
	{
		$result = $this->query('SELECT SUM(ent_print) as total_print, SUM(ent_copy) as total_copies, SUM(ent_charge) as total_charge, SUM(ent_charge)-(SUM(ent_copy)+SUM(ent_print)) as balance FROM entries WHERE ent_com_id="'.$comp_id.'" AND ent_cust_id="'. $user_id .'"');
		return $result;
	}

	public function addEntry($ent_type,$comp_id,$user_id,$qty,$date)
	{

		$sQuery  = 'INSERT INTO entries (ent_com_id, ent_cust_id, '.$ent_type;
		if($date!="null")
			$sQuery .= ', ent_date';
		$sQuery .= ') VALUES ('.$comp_id.', '.$user_id.', '.$qty;
		if($date!="null")
			$sQuery .= ', "'.$date.'"';
		$sQuery .= ')';
		$result = $this->execute($sQuery);
		return $result;
	}

	public function checkUserCredentials($user,$pwd,$remember=false)
    {
		$result = $this->query('SELECT com_id FROM companies WHERE com_email="'.$user.'" AND com_pwd="'.md5($pwd).'"');
		return $result;
	}

}
