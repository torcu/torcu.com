<?php

class Node extends Controller {

    function index()
    {
		$template = $this->loadView('node/index');
		$template->set('title', 'node test');
        $template->render();
    }

}
