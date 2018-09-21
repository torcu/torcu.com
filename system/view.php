<?php

class View {

	private $pageVars = array();
	private $template;
	private $files = array('css'=>array(),'js'=>array());

	public function __construct($template)
	{
		$this->template = APP_DIR .'views/'. $template .'.php';
	}

	public function set($var, $val)
	{
		$this->pageVars[$var] = $val;
	}

	public function render()
	{
		extract($this->files);
		extract($this->pageVars);
		
		ob_start();
		require($this->template);
		echo ob_get_clean();
	}
	
	public function loadCSS($css)
	{
		$this->files['css'][] = $css;
	}
	
	public function loadJS($js)
	{
		$this->files['js'][] = $js;
	}
    
}
