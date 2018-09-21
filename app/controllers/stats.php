<?php

class Stats extends Controller {
		
	private $ga_email = 'mail@torcu.com';
	private $ga_password = 'fox mulder';
	private $ga_profile_id = '77425084';
	private $ga_token = '';


	private $TCDB_TW_SITE = '';
	private $TCDB_TW_COUNT = 1345;
	private $TCDB_TW_PREV = 1230;
	private $TCDB_TW_CURR_DATE = '2014/04/30';
	private $TCDB_TW_PREV_DATE = '2014/03/17';

	private $TCDB_FB_SITE = '';
	private $TCDB_FB_COUNT = 239;
	private $TCDB_FB_PREV = 112;
	private $TCDB_FB_CURR_DATE = '2014/04/30';
	private $TCDB_FB_PREV_DATE = '2014/03/17';
		
	function index()
	{
		require_once(LIB_DIR.'gapi/gapi.php');
		require_once(LIB_DIR.'gapi/gapi.cache.php');
		
		$cache = new GapiCache();
		
		if($data = $cache->get_cache('geochart.index'))
		    $ga = unserialize($data);
		else {
			$ga = new gapi($this->ga_email,$this->ga_password);
			$ga->requestReportData($this->ga_profile_id,array('country'), array('visits'));
		    $cache->set_cache('geochart.index', serialize($ga));
		}
			
		$template = $this->loadView('stats/index');
		
		$template->set('ga',$ga);		
		$template->set('title', 'Stats');
		
		//$template->loadCSS('//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css');
		$template->loadCSS('//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css');
		$template->loadJS('//www.google.com/jsapi');
		$template->render();
	}
	
	function geoChartData()
	{
		require_once(LIB_DIR.'gapi/gapi.php');
		require_once(LIB_DIR.'gapi/gapi.cache.php');
		
		$cache = new GapiCache();
		$cachelabel = 'geochartdata.'.$this->getValue('country');
		
		if($data = $cache->get_cache($cachelabel))
			$res = $data;
		else {
			$ga = new gapi($this->ga_email,$this->ga_password);
			$country = $this->getValue('country');
			$cache->set_cache($cachelabel, serialize($ga));
			$region_data = $this->getRegionData($country);
		
			if(!$country || !$region_data)
			{
				$a['region'] = '1';
				$a['mode'] = 'regions';
				$ga->requestReportData($this->ga_profile_id,array('country'), array('visits'));
				$a['data'][] = array('Country', 'Visits');
				foreach ($ga->getResults() as $result)
					$a['data'][] = array($result->getCountry(),$result->getVisits());
			}
			else
			{
				$a['region'] = $region_data['code'];
				$a['mode'] = 'markers';
				$filter = 'country == '.$country;
				$ga->requestReportData($this->ga_profile_id,array('city'), array('visits'),'-visits',$filter);
				$a['data'][] = array('City', 'Visits');
				foreach ($ga->getResults() as $result)
					$a['data'][] = array($result->getCity(),$result->getVisits());
			}
			$res=json_encode($a);
			$cache->set_cache($cachelabel, json_encode($a));
		}
		die($res);
	}
	
	function getRegionData($country)
	{
		require_once(LIB_DIR.'gapi/geo_countries.php');
		if(isset($region[$country]))
			return $region[$country];
		else 
			return false;
	}
	
	function social()
	{
		$url=$this->getValue('url');
		$tweet = $this->get_tweets($url);

		echo $tweet['count'].' tweets. That\'s '.($tweet['count']-$tweet['prev']).' more since '.$tweet['date_prev'];

		//$likes = $this->get_likes($url);
		//$pluses = $this->get_plusones($url);
		//echo "tweets: ".$tweets."\n"."likes: ".$likes."\n"."pluses: ".$pluses;
	}
	
    function get_tweets($url)
    {
	//	$json_string = file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url='.$url);
	//	$json = json_decode($json_string, true);
		$json['count'] = $this->TCDB_TW_COUNT;

		$res['count'] = intval($json['count']);
		$res['prev'] = $this->TCDB_TW_PREV;
		$res['date_prev'] = $this->TCDB_TW_PREV_DATE;
		return $res;
	}

	function get_likes($url)
	{
		$json_string = file_get_contents('http://graph.facebook.com/?ids='.$url);
		$json = json_decode($json_string, true);
		return intval($json[$url]['shares']);
	}

	function get_plusones($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$url.'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$curl_results = curl_exec ($curl);
		curl_close ($curl);
		$json = json_decode($curl_results, true);
		return intval($json[0]['result']['metadata']['globalCounts']['count'] );
	}

	
	
}