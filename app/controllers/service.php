<?php

class Service extends Controller {

	function index()
    {
		require_once(LIB_DIR . 'nusoap/nusoap.php');
		require_once(LIB_DIR . 'nusoap/nua.php');

		$server = new nusoap_server;
		$server->configureWSDL('server', 'urn:server');
		$server->wsdl->schemaTargetNamespace = 'urn:server';
		 
		//FirstApplicationData
		$server->register('NUA.FirstApplicationData',
			array(
				'AddrLn1' => 'xsd:string',
				'AddrLn2' => 'xsd:string',
				'AddrLn3' => 'xsd:string',
				'AppRef' => 'xsd:string',
				'Consent' => 'xsd:string',
				'Country' => 'xsd:string',
				'County' => 'xsd:string',
				'DOB' => 'xsd:string',
				'EmailAddr' => 'xsd:string',
				'FirstName' => 'xsd:string',
				'Gender' => 'xsd:string',
				'Mnumber' => 'xsd:string',
				'PostalCod' => 'xsd:string',
				'Prod' => 'xsd:string',
				'SrcCode' => 'xsd:string',
				'Surname' => 'xsd:string',
				'Title' => 'xsd:string'
			),  										//parameter
			array('return' => 'xsd:int'),  				//output
			'urn:server',   							//namespace
			'urn:server#CreateApplication',  			//soapaction
			'rpc', 										//style
			'encoded', 									//use
			'Test Create Application');  				//description

		//UpdatedApplicationData
		$server->register('NUA.UpdatedApplicationData',
			array(
				'Consent' => 'xs:string',
				'EmailAddr' => 'xs:string',
				'EmpName' => 'xs:string',
				'EmpStat' => 'xs:string',
				'IntAmt' => 'xs:decimal',
				'MarStat' => 'xs:string',
				'MntRpyAmt' => 'xs:decimal',
				'Mnumber' => 'xs:string',
				'National' => 'xs:string',
				'NumCCs' => 'xs:int',
				'NumDep' => 'xs:int',
				'OLCCmr' => 'xs:int',
				'Occ' => 'xs:string',
				'OthHHInc' => 'xs:int',
				'Premise' => 'xs:string',
				'Purpose' => 'xs:string',
				'RentInc' => 'xs:int',
				'ReqLnAmt' => 'xs:int',
				'ReqTerm' => 'xs:string',
				'ResStat' => 'xs:string',
				'SalaryPayment' => 'xs:string',
				'SocWelfInc' => 'xs:int',
				'SrcCode' => 'xs:string',
				'Subpremise' => 'xs:string',
				'TimeInJob' => 'xs:int',
				'TmCurAdd' => 'xs:int',
				'TmInRoI' => 'xs:int',
				'TotMthLnRpy' => 'xs:int',
				'TotMthRntMrt' => 'xs:int',
				'TotNMthInc' => 'xs:int',
				'ValCCLims' => 'xs:int',
				'ValODLims' => 'xs:int',
				'ValSavsInv' => 'xs:int'
			),  										//parameter
			array('return' => 'xsd:string'),  			//output
			'urn:server',   							//namespace
			'urn:server#UpdatedApplication',  			//soapaction
			'rpc', 										//style
			'encoded', 									//use
			'Test Update Application');  				//description

		//FinishApplicationData
		$server->register('NUA.FinishApplicationData',
			array(
				'AppRef' => 'xsd:string',
				'MchMMM' => 'xsd:string',
				'MchPoB' => 'xsd:string',
				'MrktPEmail' => 'xsd:string',
				'MrktPPost' => 'xsd:string',
				'MrktPTe' => 'xsd:string',
				'SrcCod' => 'xsd:string',
				'WorkTN' => 'xsd:string'
			),  										//parameter
			array('return' => 'xsd:string'),  			//output
			'urn:server',   							//namespace
			'urn:server#FinishApplication',  			//soapaction
			'rpc', 										//style
			'encoded', 									//use
			'Test Finish Application');  				//description

		//	//this is the second webservice entry point/function 
		//	$server->register('login',
		//				array('username' => 'xsd:string', 'password'=>'xsd:string'),  //parameters
		//				array('return' => 'tns:Person'),  //output
		//				'urn:server',   //namespace
		//				'urn:server#loginServer',  //soapaction
		//				'rpc', // style
		//				'encoded', // use
		//				'Check user login');  //description
		//	 
		//	//first function implementation
		//	function hello($username) {
		//	        return 'Howdy, '.$username.'!';
		//	}
		//	 
		//	//second function implementation 
		//	function login($username, $password) {
		//	        //should do some database query here
		//	        // .... ..... ..... .....
		//	        //just some dummy result
		//	        return array(
		//			'id_user'=>1,
		//			'fullname'=>'John Reese',
		//			'email'=>john@reese.com,
		//			'level'=>99
		//		);
		//	}
		 
		$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
		$server->service(file_get_contents("php://input"));
    }
    
}
