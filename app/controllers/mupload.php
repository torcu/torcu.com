<?php

class Mupload extends Controller {

	function index()
	{
		$template = $this->loadView('mupload/index');
		$template->set('title', 'Mobile upload test');
		$template->render();
	}

	function upload()
	{
		die('test finished');
		if (isset($_FILES['myFile'])) {
    			// Example:
    			move_uploaded_file($_FILES['myFile']['tmp_name'], "uploads/" . $_FILES['myFile']['name']);
    			die('successful');
		}
	}
}
