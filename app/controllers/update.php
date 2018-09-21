<?php

class Update extends Controller {
	
	private $salt = '2d52ee2c1b19b9b971098ae7dc9e78a8';
    private $version = '1.3.6';

    private $ga_version = 1;
    private $ga_tid = 'UA-53702211-1';
    private $ga_url = 'http://www.google-analytics.com/collect';
	private $curl_proxy = ''; // host:port
  	private $curl_proxy_auth = ''; // user:pwd

	public function index()
	{
		$auth=$this->loadHelper('auth_helper');
		if(!$auth->access())
			$this->redirect(LOGIN);
		
		$template = $this->loadView('update/index');
		$template->loadCSS('bootstrap.min2');
		$template->loadCSS('bootstrap-wysihtml5');
		$template->loadCSS('prettify');
		
		$template->loadJS('bootstrap/bootstrap.min');
		$template->loadJS('editor/wysihtml5-0.3.0');
		$template->loadJS('editor/prettify');
		$template->loadJS('editor/bootstrap-wysihtml5');
			
		$template->set('title', 'Update');
		$template->render();
	}

	private function validate_request() {
		if ($this->getValue('c') == md5($this->salt.gmdate('YmdH')))
			return true;
		return false;
	}
	
	public function getversion()
	{
		$track = $this->gaTrack('Get version');
		if (!$this->validate_request()) die();
		list($store,$extension) = $this->args();
		$file = ROOT_DIR.'static/files/'.$store.'/'.$extension.'/version.txt';
		if (file_exists($file)) {
			$version = trim(file_get_contents($file));
			die($version);
		} else {
			die();
		}
	}
	
	public function getnotes()
	{	
		$track = $this->gaTrack('Get notes');
		if (!$this->validate_request()) die();
		list($store,$extension) = $this->args();
		$file = ROOT_DIR.'static/files/'.$store.'/'.$extension.'/changenotes.txt';
		if (file_exists($file)) {
			$notes = file_get_contents($file);
			die($notes);
		} else {
			die();
		}
	}

	public function hash()
	{
		$track = $this->gaTrack('Check install');
		if (!$this->validate_request()) die();

		list($store,$extension) = $this->args();
		$res = array();
		$i=0;
		include_once('priv/releases/'.$store.'/'.$extension.'/module_config.php');
		foreach ($files as $local => $remote) {
			$res[$i]['local'] = $remote;
			$res[$i]['remote'] = $local;
			$file = ROOT_DIR.'priv/releases/'.$store.'/'.$extension.'/'.$local;
			$res[$i]['hash'] = sha1_file($file);
			$i++;
		}
		die(json_encode($res));
	}

	public function getfile()
	{
		if (!$this->validate_request()) die();
		
		$file = ROOT_DIR.'/priv/'.$this->getValue('f').'.zip';
		ob_clean();
		if (!is_file($file)) {
			header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
			echo 'File not found';
		} else if (!is_readable($file)) {
			header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
			echo 'File not readable';
		} else {
			header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
			header("Content-Type: application/zip");
			header("Content-Transfer-Encoding: Binary");
			header("Content-Length: ".filesize($file));
			header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
			readfile($file);
			exit;
		}
	}

	private function gaTrack($action)
	{
		$page = $_SERVER['REDIRECT_URL'];
		$page_elements = split('/',$page);

		$data['v'] 	 = 1; // The version of the measurement protocol
		$data['tid'] = $this->ga_tid; // Google Analytics account ID (UA-98765432-1)
		$data['cid'] = $this->getValue('cid', $this->genUUID());
		$data['dr']  = $this->getValue('f', (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:""));
		
		$data['t']   = 'pageview';

		$data['uip'] = (isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:"");
		$data['dh']  = (isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:""); // The domain of the site that is associated with the Google Analytics ID
		$data['dl']  = (isset($_SERVER['REDIRECT_URL'])?$_SERVER['REDIRECT_URL'] : ""); // The landing page

		//Custom dimensions
		$data['cd1'] = $page_elements[3];
		$data['cd2'] = $page_elements[4];
		$data['cd3'] = $action;

		//$data['dr'] = $_SERVER['HTTP_REFERER']; // The URL of the site that is sending the visit. Format: http%3A%2F%2Fexample.com
		//$data['dp'] = (isset($_REQUEST['path']) ? $_REQUEST['path'] : ""); // The page that will receive the pageview
		//$data['dt'] = (isset($_REQUEST['page_title']) ? $_REQUEST['page_title'] : ""); // The title of the page that receives the pageview. In my case, this is a "virtual" page. So, I'm passing the title through the URL.
		//$data['cs'] = (isset($_REQUEST['utm_source']) ? $_REQUEST['utm_source'] : ""); // The source of the visit (e.g. google)
		//$data['cm'] = (isset($_REQUEST['utm_medium']) ? $_REQUEST['utm_medium'] : ""); // The medium (e.g. cpc)
		//$data['cn'] = (isset($_REQUEST['utm_campaign']) ? $_REQUEST['utm_campaign'] : ""); // The name of the campaign
		//$data['ck'] = (isset($_REQUEST['utm_term']) ? $_REQUEST['utm_term'] : ""); // The keyword that the user searched for
		//$data['cc'] = (isset($_REQUEST['utm_content']) ? $_REQUEST['utm_content'] : ""); // Used to differentiate ads or links that point to the same URL.

		$content = http_build_query($data);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->ga_url);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-type: application/x-www-form-urlencoded'));
		curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		
		if ($this->curl_proxy)
      		curl_setopt($ch, CURLOPT_PROXY, $this->curl_proxy);

    	if ($this->curl_proxy_auth)
      		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->curl_proxy_auth);

		$result = curl_exec($ch);
		curl_close($ch);

		return true;
	}

	private function genUUID() { // Generates a UUID. A UUID is required for the measurement protocol.
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
		// 32 bits for "time_low"
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
		// 16 bits for "time_mid"
		mt_rand( 0, 0xffff ),
		// 16 bits for "time_hi_and_version",
		// four most significant bits holds version number 4
		mt_rand( 0, 0x0fff ) | 0x4000,
		// 16 bits, 8 bits for "clk_seq_hi_res",
		// 8 bits for "clk_seq_low",
		// two most significant bits holds zero and one for variant DCE1.1
		mt_rand( 0, 0x3fff ) | 0x8000,
		// 48 bits for "node"
		mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
		);
	}		  
}
