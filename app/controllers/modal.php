<?php

class Modal extends Controller {
	
	function index()
	{
		$template = $this->loadView('modal/index');
		//$template->loadJS('//code.jquery.com/jquery-migrate-1.2.1.js');
		$template->loadJS('jquery.modal');
		$template->loadCSS('jquery.modal');
		$template->set('title', 'modal');
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