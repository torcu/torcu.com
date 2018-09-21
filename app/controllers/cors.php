<?php

class Cors extends Controller {

	public function test() {
		header('Content-Type: application/json');
		$a=array("prueba cors"=>1);
        	echo json_encode($a);
	}
}
