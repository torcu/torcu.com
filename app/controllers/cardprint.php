<?php

class Cardprint extends Controller {

	private $login = 'cardprint/login';

	private function  _loginpage()
	{
		global $config;
		return join("/",array($this->login,substr($_SERVER['REQUEST_URI'],strlen($config['base_url']))));
	}

	public function index()
	{
		$auth=$this->loadHelper('auth_helper');
		if(!$auth->access()) $this->redirect($this->_loginpage());

		$template = $this->loadView('cardprint/index');
		$template->loadJS('cardprint/cardprint');

		$template->set('title', 'CardPrint');
		$template->set('style',$this->getValue('css','skins/compact'));
		$template->set('lang',$this->getValue('lang','es'));
		$template->render();
	}

	public function getUser()
	{
		$cardprint_model = $this->loadModel('cardprint_model');
		$user = $cardprint_model->getUserInfo('1','1');
		return json_encode($user);
	}

	public function getEntries()
	{
		$res = array('user'=>array(),'entries'=>array(),'balance'=>array(),'error'=>array('code'=>0,'message'=>''));
		$auth= $this->loadHelper('auth_helper');
		
		if(!$auth->access()) {
			$res['error'] = array('code'=>1,'message'=>'reconnect');
		} else {
			$user  = $this->getValue('uid',1);
			$company = '1';
			$cardprint_model = $this->loadModel('cardprint_model');
			$res['user']    = $cardprint_model->getUserInfo($company,$user);
			$res['entries'] = $cardprint_model->getUserEntries($company,$user);
			$res['balance'] = $cardprint_model->getBalance($company,$user);
		}
		return json_encode($res);
	}

	public function addCopies()
	{
		$auth= $this->loadHelper('auth_helper');
		
		if(!$auth->access()) {
			$res['error'] = array('code'=>1,'message'=>'reconnect');
		} else {
			$user  = $this->getValue('uid',1);
			$qty   = $this->getValue('n',0);
			$date  = $this->getValue('d',"null");
			$company = '1';
			$cardprint_model = $this->loadModel('cardprint_model');
			$balance = $cardprint_model->getBalance($company,$user);

			if($balance[0]['balance']-$qty < 0) {
				$res['error'] = array('code'=>2,'message'=>'overdrawn');
			} else {
				$result = $cardprint_model->addEntry('ent_copy', $company,$user,$qty,$date);
				$res['error'] = array('code'=>0,'message'=>'');
			}
		}
		return json_encode($res);
	}

	public function addPrints()
	{
		$auth= $this->loadHelper('auth_helper');
		
		if(!$auth->access()) {
			$res['error'] = array('code'=>1,'message'=>'reconnect');
		} else {
			$user  = $this->getValue('uid',1);
			$qty   = $this->getValue('n',0);
			$date  = $this->getValue('d',"null");
			$company = '1';
			$cardprint_model = $this->loadModel('cardprint_model');
			$balance = $cardprint_model->getBalance($company,$user);

			if($balance[0]['balance']-$qty < 0) {
				$res['error'] = array('code'=>2,'message'=>'overdrawn');
			} else {
				$result = $cardprint_model->addEntry('ent_print', $company,$user,$qty,$date);
				$res['error'] = array('code'=>0,'message'=>'');
			}
		}
		return json_encode($res);
	}

	public function topUp()
	{
		$auth= $this->loadHelper('auth_helper');
		
		if(!$auth->access()) {
			$res['error'] = array('code'=>1,'message'=>'reconnect');
		} else {
			$user  = $this->getValue('uid',1);
			$qty   = $this->getValue('n',0);
			$date  = $this->getValue('d',"null");

			$company = '1';
			$cardprint_model = $this->loadModel('cardprint_model');
			
			$result = $cardprint_model->addEntry('ent_charge', $company,$user,$qty,$date);
			$res['error'] = array('code'=>0,'message'=>'');
			
		}
		return json_encode($res);
	}

	public function balance()
	{
		$user = '2';
		$company = '1';

		$cardprint_model = $this->loadModel('cardprint_model');
		$balance = $cardprint_model->getBalance($company,$user);
		return json_encode($balance);
	}

	function login()
    {
		$auth=$this->loadHelper('auth_helper');
		$redirect = $this->getValue('redirect', join("/",$this->getOptionsFromURL(__METHOD__)));

		if($auth->access())
			$this->redirect($redirect);

		if($this->getValue('user') && $this->getValue('pwd')) {
			$auth_model = $this->loadModel('cardprint_model');
			$check_user = $auth_model->checkUserCredentials($this->getValue('user'),$this->getValue('pwd'));

			if (@$check_user[0]['com_id']) {
				$_SESSION['torcucomsession'] = $auth->getToken($check_user[0]['com_id']);
				session_write_close();
				$this->redirect($redirect);
			}
		}

		$template = $this->loadView('cardprint/login');
		$template->set('redirect', $redirect);
		$template->set('title', 'CardPrint login');
		$template->loadCSS('signin');
        $template->render();
	}
}
