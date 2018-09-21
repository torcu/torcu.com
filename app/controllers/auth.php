<?php

class Auth extends Controller {

	//public function __construct()
    //{
    //    parent::__construct();
    //   // constructor code
    //}

    function index()
    {
		$auth=$this->loadHelper('auth_helper');
	
		$decrypted = $auth->checkToken($_SESSION['torcucomsession'],true);
		print_r($decrypted);
				
		if(!$auth->access())
			$this->redirect(LOGIN);
			
		$template = $this->loadView('auth/index');		
		$template->set('someval', $this->getValue('id',100));
		$template->set('title', 'auth page');
        $template->render();
    }
	
	function login()
    {
		$auth=$this->loadHelper('auth_helper');
        
        if($this->getValue('redirect'))
			$redirect = $this->getValue('redirect');
		else 
			$redirect = join("/",$this->getOptionsFromURL(__METHOD__));
		
		if($auth->access())
			$this->redirect($redirect);
			
		if($this->getValue('user') && $this->getValue('pwd')) {
			$auth_model = $this->loadModel('Auth_model');
			$check_user = $auth_model->checkUserCredentials($this->getValue('user'),md5($this->getValue('pwd')));			
			if (isset($check_user[0]['user_id'])) {
				$_SESSION['torcucomsession'] = $auth->getToken($check_user['user_id']);
				$this->redirect($redirect);
			}
		}
	
		$template = $this->loadView('auth/login');
		$template->set('redirect', $redirect);
		$template->set('title', 'please log in');
		
		$template->loadCSS('signin');		
        $template->render();
    }

}
