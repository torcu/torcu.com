<?php

class Controller {
	
	public function loadModel($name)
	{
		require(APP_DIR .'models/'. strtolower($name) .'.php');

		$model = new $name;
		return $model;
	}
	
	public function loadView($name)
	{
		$view = new View($name);
		return $view;
	}
	
	public function loadPlugin($name)
	{
		require(APP_DIR .'plugins/'. strtolower($name) .'.php');
	}
	
	public function loadHelper($name)
	{
		require(APP_DIR .'helpers/'. strtolower($name) .'.php');
		$helper = new $name;
		return $helper;
	}
	
	public function redirect($loc)
	{
		global $config;
		header('Location: '. $config['base_url'] . $loc);
	}
	   
	public function getValue($val,$default=NULL)
	{
		if (isset($_GET[$val]) && !empty($_GET[$val]))
			return $_GET[$val];
		elseif (isset($_POST[$val]) && !empty($_POST[$val]))
			return $_POST[$val];
		
		return $default;
	}
	
	public function getOptionsFromURL($method) {
		$method = strtolower(join("/",explode("::",$method)));
		$optionstr = ltrim(rtrim(substr($_SERVER['REDIRECT_URL'],strpos($_SERVER['REDIRECT_URL'], $method)+strlen($method)),"/"),"/");
		return explode("/",$optionstr);
	}

	public function args() {
		$backtrace = debug_backtrace();
		$caller = strtolower($backtrace[1]['class']."/".$backtrace[1]['function']);
		$optionstr = ltrim(rtrim(substr($_SERVER['REDIRECT_URL'],strpos($_SERVER['REDIRECT_URL'], $caller)+strlen($caller)),"/"),"/");
		return explode("/",$optionstr);
	}
}
