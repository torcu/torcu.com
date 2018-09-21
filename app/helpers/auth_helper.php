<?php

class Auth_helper {

	private $session_expiry = 1800; // seconds
	private $key = '30214d253d315e7c596c404940';
	
	function access()
	{
		if(isset($_SESSION['torcucomsession'])) {
			if($this->checkToken($_SESSION['torcucomsession'])) {
				$token_info = $this->checkToken($_SESSION['torcucomsession'],true);
				$_SESSION['torcucomsession'] = $this->getToken($token_info['uid']);
				session_write_close();
				return true;
			}
		}
		return false;
	}
	
	function getToken($uid)
	{
		$input = $uid.':'.time();
		$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB),MCRYPT_DEV_URANDOM);
		$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $input, MCRYPT_MODE_CBC, $iv);
		return bin2hex($encrypted).'-'.bin2hex($iv);
	}
	
	function checkToken($encrypted,$toArray=false)
	{
		$encrypted = explode('-',$encrypted);
		$str = pack("H*", $encrypted[0]);
		$iv =  pack("H*", $encrypted[1]);
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $str, MCRYPT_MODE_CBC, $iv);
		
		list($uid, $time) = explode(':',$decrypted);
			
		if ($toArray)
			return array('uid'=>$uid, 'time'=>$time);
			
		if(time()-$time < $this->session_expiry)
			return true;
			
		return false;
	}

}
