<?php

class Panels extends Controller {
		
	function index()
	{
		$template = $this->loadView('panels/index');
		$template->loadJS('//code.jquery.com/ui/1.10.4/jquery-ui.js');
		$template->loadJS('panels');
		$template->loadCSS('panels');
		$template->set('title', 'Panels');
		$template->loadCSS('//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
		$template->render();
	}
	
	function getcontent()
	{
		die($this->randomString());
	}
	
	private function randomString($length = 10)
	{
		$length=(int)rand(1,5)*100;

	    $characters = '012 3456789a bcdefghi jklm nopqrstuvw xyzABCDEFGH IJKLMN OPQRST UVWXYZ';
	    $randomString = $length.' ';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}
	
}