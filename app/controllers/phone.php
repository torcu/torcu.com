<?php

class Phone extends Controller {

	function e164()
	{
		list($phone,$region) = $this->args();
		$convert = file_get_contents('http://www.torcu.com/phone/convert/'.$phone.'/'.$region);
		die($convert);

	}

	function convert()
	{
		list($phone,$region) = $this->args();
		$template = $this->loadView('phone/convert');
		$template->set('phone', $phone);
		$template->set('region', $region);
		$template->set('title', 'phone');
		$template->render();
	}

   	function index()
        {
                list($phone,$region) = $this->args();
                $template = $this->loadView('phone/convert');
                $template->set('phone', $phone);
                $template->set('region', $region);
                $template->set('title', 'phone');
                $template->render();
        }




}
