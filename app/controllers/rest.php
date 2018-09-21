<?php

class Rest extends Controller {

	public function hi() {
		header('Content-Type: application/json');
		$a=array("response"=>"hola!");
        	echo json_encode($a);
	}
	
	public function bye() {
		header('Content-Type: application/json');
		$a=array("response"=>"adios!");
        	echo json_encode($a);
	}
}
