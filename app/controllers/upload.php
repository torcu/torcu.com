<?php

class Upload extends Controller {

	function index()
	{
		$data = array();
		$error = false;
		$files = array();

	    $uploaddir = ROOT_DIR.'static/upload/';
	    foreach($_FILES as $file)
	        if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name'])))
	            $files[] = $uploaddir .$file['name'];
	        else
	            $error = true;

	    $data = ($error) ? array('error' => 1, 'msg' => 'Error uploading file '.$file['name']) : array('error'=>0, 'file' => $file['name']);
		die(json_encode($data));
	}

	private function unzip($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true) {
		$res=array('error'=>0,'files'=>array());
		if ($zip = zip_open($src_file)) {
			if ($zip) {
				$splitter = ($create_zip_name_dir === true) ? "." : "/";
				if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
				create_dirs($dest_dir);
				while ($zip_entry = zip_read($zip)) {
					$pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
					if ($pos_last_slash !== false)
						create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
					if (zip_entry_open($zip,$zip_entry,"r"))  {
						$file_name = $dest_dir.zip_entry_name($zip_entry);
						if ($overwrite === true || $overwrite === false && !is_file($file_name)) {
							$fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							@file_put_contents($file_name, $fstream );
							chmod($file_name, 0777);
							if ($file_name[strlen($file_name)-1] != "/")
								$res['files'][] = zip_entry_name($zip_entry);
						}
						zip_entry_close($zip_entry);
					}
				}
				zip_close($zip);
			} else
				$res['error'] = 1;
		} else
			$res['error'] = 1;
		return $res;
	}

	private function create_dirs($path) {
		if (!is_dir($path)) {
			$directory_path = "";
			$directories = explode("/",$path);
			array_pop($directories);
			foreach($directories as $directory) {
				$directory_path .= $directory."/";
				if (!is_dir($directory_path)) {
					mkdir($directory_path);
					chmod($directory_path, 0777);
				}
			}
		}
	}

}