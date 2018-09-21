<?php

class Main extends Controller {
	
	function index()
	{
		
		$template = $this->loadView('common/main');
		$template->set('title', 'dev');
		$template->render();
	}
    
}