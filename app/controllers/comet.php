<?php

class Comet extends Controller {

	private $file = 'comet/data.txt';
		
	function index()
	{
		$template = $this->loadView('comet/index');	
		$template->loadJS('prototype');
		$template->set('title', 'Comet');
		$template->loadCSS('//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
		$template->render();
	}
	
	function call() {
		$template = $this->loadView('comet/callserver');	
		$template->loadJS('prototype');
		$template->set('title', 'Call server');
		$template->loadCSS('//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
		$template->render();
	}

	function call2() {
		$auth=$this->loadHelper('auth_helper');
		if(!$auth->access())
			$this->redirect(LOGIN);
		$template = $this->loadView('comet/callserver');	
		$template->loadJS('prototype');
		$template->set('title', 'Call server');
		$template->loadCSS('//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
		$template->render();
	}
	
	function getitem()
	{
		die($this->randomString());
	}

	function server()
	{
		$filename  = STATIC_DIR . $this->file;

		// store new message in the file
		//$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
		if ($this->getValue('msg'))

		if (isset($_GET['msg']))
		{
			file_put_contents($filename,$_GET['msg']);
			die();
		} 

		// infinite loop until the data file is not modified
		//$lastmodif    = isset($_GET['timestamp']) ? $_GET['timestamp'] : 0;
		$currentmodif = filemtime($filename);
		while ($currentmodif <= $this->getValue('timestamp',0)) // check if the data file has been modified
		{
			usleep(10000); // sleep 10ms to unload the CPU
			clearstatcache();
			$currentmodif = filemtime($filename);
		}

		// return a json array
		$response = array();
		$response['msg']       = file_get_contents($filename);
		$response['timestamp'] = $currentmodif;
		echo json_encode($response);
		flush();
	}
	
	private function randomString($length = 30)
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	   //sleep(rand(0,2));
	    return $randomString;
	}
	
}