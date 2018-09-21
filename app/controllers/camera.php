<?php

class Camera extends Controller {

        function index()
        {
                //$auth=$this->loadHelper('auth_helper');
                //if(!$auth->access())
                //        $this->redirect('auth/login');

                $template = $this->loadView('camera/index');

                $template->loadJS('bootstrap/bootstrap.min');

                $template->set('title', 'Camera');
                $template->render();
        }

        function move()
        {
                list($dir) = $this->getOptionsFromURL(__METHOD__);
 		//$var=file_get_contents('http://localhost:88/'.$dir);
		$var=$dir;
		die($var);
        }

	function start()
	{
		$out = shell_exec('sudo motion start');
		$out .=  'camera started with pid '.file_get_contents('/var/run/motion/motion.pid'); 
		die($out);
	}

	function stop()
	{
		$out = shell_exec('sudo killmotion');
		die($out);
	}

	function status()
	{
		if (file_exists('/var/run/motion/motion.pid'))
			die('1');
		else
			die('0');
	}
}
