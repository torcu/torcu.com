<?php

class Analytics extends Controller {

	function index()
	{

		$template = $this->loadView('analytics/index');
		$template->set('title', 'analytics');
		$template->render();
	}

}
