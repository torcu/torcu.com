<?php
/*
 * SimpleCache v1.3.0
 * By Gilbert Pellegrom
 * http://dev7studios.com
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
class GapiCache {

	var $cache_path = 'cache/';
	var $cache_time = 3600;

	function get_data($label, $url)
	{
		if ($data = $this->get_cache($label))
			return $data;
		else {
			$data = $this->do_curl($url);
			$this->set_cache($label, $data);
			return $data;
		}
	}

	function set_cache($label, $data)
	{
		$this->cache_path=sys_get_temp_dir().'/';
		file_put_contents($this->cache_path.$this->safe_filename($label).'.gapi', $data);
	}

	function get_cache($label)
	{
		$this->cache_path=sys_get_temp_dir().'/';
		if ($this->is_cached($label)){
            $filename = $this->cache_path.$this->safe_filename($label).'.gapi';
			return file_get_contents($filename);
		}
		return false;
	}

	function is_cached($label)
	{
		$this->cache_path=sys_get_temp_dir().'/';
		$filename = $this->cache_path.$this->safe_filename($label).'.gapi';

		if (file_exists($filename) && (filemtime($filename) + $this->cache_time >= time()))
			return true;

		return false;
	}

	function do_curl($url)
	{
		if(function_exists("curl_init")){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
			$content = curl_exec($ch);
			curl_close($ch);
			return $content;
		} else
			return file_get_contents($url);
	}

	function safe_filename($filename)
	{
		return preg_replace('/[^0-9a-z\.\_\-]/i','', strtolower($filename));
	}
}