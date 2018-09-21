<?php

class Modal2 extends Controller {
	
	function index()
	{
		$template = $this->loadView('modal2/index');
		$template->loadJS('//code.jquery.com/ui/1.10.4/jquery-ui.js');
		$template->loadCSS('//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
		$template->set('title', 'modal2');
		$template->render();
	}

	function feedme()
	{
		list($modid,$other) = $this->args();
		$res  = '<div class="modal">';
  		$res .= '<p>hiya! '.$modid.', '.$other.'</p>';
		$res .= '</div>';
		die($res);
	}
    
}