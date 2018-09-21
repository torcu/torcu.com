<?php

class LazyLoad extends Controller {
		
	function index()
	{
		$template = $this->loadView('lazyload/index');	
		$template->loadJS('lazyload');
		$template->set('title', 'LazyLoad');
		$template->loadCSS('//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
		$template->render();
	}
	
	function getitem()
	{
		die($this->randomString());
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