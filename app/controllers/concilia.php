<?php

class Concilia extends Controller {

	private $default_sep = '	';

	function index()
	{
		//$auth=$this->loadHelper('auth_helper');
		//if(!$auth->access())
		//	$this->redirect(LOGIN);

		$template = $this->loadView('concilia/index');
		$template->loadJS('jquery.form');
		$template->set('title', 'Conciliador Datanet');
		$template->set('debug', $this->getValue('debug',0));
		$template->render();
	}

	function index2()
	{
		$template = $this->loadView('concilia/index2');
		$template->loadJS('jquery.form');
		$template->set('title', 'Conciliador Datanet');
		$template->set('debug', $this->getValue('debug',0));
		$template->render();
	}

	function cartera_objetivo()
	{
		$conciliamodel = $this->loadModel('Concilia_model');

		$template = $this->loadView('concilia/compare');
		$sql = 'SELECT * FROM CARTERA_OBJETIVO WHERE 1 LIMIT 0,999';
		$result = $conciliamodel->query($sql);
		$i = 0;
	
		foreach($result as $c) {
			foreach (array('BRANCH','OPERACION','RID','EXPENSE') as $header) 
				$res[$i][$header] = $this->cell($c[$header]);
			$i++;
		}

		if($this->getValue('load',0))
			$this->load_cartera_objetivo();

		$template = $this->loadView('concilia/cartera_objetivo');
		$template->set('title', 'Conciliador Datanet - Cartera objetivo');
		$template->set('debug', $this->getValue('debug',0));
		$template->set('cartera', $res);
		$template->render();
	}

	function bulk()
	{

		$conciliamodel = $this->loadModel('Concilia_model');

		$template = $this->loadView('concilia/bulk');

		if($this->getValue('load',0))
			$this->load_cartera_objetivo();

		$template = $this->loadView('concilia/bulk');
		$template->loadJS('jquery-sortable');
		$template->set('title', 'Conciliador Datanet - procesar archivos');
		$template->set('debug', $this->getValue('debug',0));
		$template->render();
	}

	function compare()
	{

		ini_set('max_execution_time', 600);
		ini_set('post_max_size', '128');
		ini_set('upload_max_filesize', '128M');

		if($this->getValue('compare',0))
			$this->excel();

		$files = explode(",", $this->getValue('files',''));

		$template = $this->loadView('concilia/compare');
		$template->set('title', 'Conciliador Datanet');
		$template->set('files', $files);
		$template->set('debug', $this->getValue('debug',0));
		$template->set('filter', $this->getValue('filter',''));
		$template->loadJS('bootstrap/bootstrap.min');
		$template->loadJS('bootstrap/multiselect');
		$template->loadCSS('bootstrap-multiselect');

		$import = $this->createtables($files);
		if($import['columns'][0] != $import['columns'][1]) {
			$import['errors'][] = 'No coinciden las columnas:<br/><b>PRO:</b> '.@join(", ", $import['columns'][0]).'<br/><b>PRE:</b> '.@join(", ", $import['columns'][1]);
		}

		//$this->debug_die();

		$template->set('data', $import);
		$template->render();
	}


	function createtables($files)
	{
		$conciliamodel = $this->loadModel('Concilia_model');
		$encoding = $this->loadHelper('encoding_helper');
		
		$res = array();
		$d = 0;
		$debug= $this->getValue('debug',0);
		$adeals = array();

		foreach($files as $file) {

			$tablename = @basename($file, '.'.end(explode(".", basename($file))));
			$dropSql = 'DROP TABLE IF EXISTS `concilia`.`'.$this->name($tablename).'`';
			$result = $conciliamodel->execute($dropSql);
			$res['tables'][] = $this->name($tablename);
			$res['files'][] = basename($file);
			$res['error'] = array();

			$sep 	 = $this->getValue('sep',$this->default_sep);
			$filter  = $this->getValue('filter','');
			$deal    = $this->reserved($this->name($this->getValue('deal','-')));

			switch($filter) {
				case 'C':
					$sql = 'SELECT OPERACION FROM CARTERA_OBJETIVO WHERE 1 LIMIT 0,999';
					$result = $conciliamodel->query($sql);
					$adeals = array_column($result, 'OPERACION');
				break;
				case 'F':
					$sql = 'SELECT FAC_INTERNAL_ID FROM FACILITIES_OBJETIVO WHERE 1 LIMIT 0,999';
					$result = $conciliamodel->query($sql);
					$adeals = array_column($result, 'FAC_INTERNAL_ID');
				break;
				default:
					$adeals = array();
					$filter = false;
			}

			$this->debug('FILTRAR type '.$filter.' COLUMNA:'.$deal.' EN: ', $adeals);

			$row = 0;
			$count = 0;
			$insert = array();
			$columns = array();
			$objetivo = array();
			$deal_column = '';
			$in = 0;
			$notin = 0;

			switch ($sep) {
				case 'tab':
					$sep = "	";
				break;
				case 'cust':
					$sep = $this->getValue('cust',$this->default_sep);
				break;
			}

			if (($handle = fopen(ROOT_DIR.'static/upload/'.$file, "r")) !== FALSE) {

				$result = $conciliamodel->execute('SET NAMES utf8');

				while (($data = fgetcsv($handle, 5000, $sep)) !== FALSE) {
					if($row==0) {
						$count = count($data);

						$sql= 'CREATE TABLE '.$this->name($tablename).' (CID int(11) NOT NULL AUTO_INCREMENT, ';

						$this->debug('HEADERS IN FILE: ',$data);

						for($i=0;$i<$count; $i++) {

							$column_name = $this->reserved($this->name($data[$i]));

							$columns[$d][] = $column_name;
		 					$sql .= $column_name.' VARCHAR(255), ';
		 					if($column_name == $deal)
		 						$deal_column = $i;
		 				}

		 				$sql .= 'MATCHED tinyint(1) NOT NULL DEFAULT \'0\', PRIMARY KEY (`CID`)) DEFAULT CHARSET=utf8 ';

						$result = $conciliamodel->execute($sql);

						$this->debug('CREATE TABLE:',$result);

						$row++;
					} else {

						$values = array();
						$deal_column = 0;

	        			for ($c=0; $c<$count; $c++)
	        				@$values[] = $this->cell($data[$c]);
	        				
	        			if(count($values)==$count && count(array_filter($values))>0)
	        				if(empty($filter) || in_array(@$values[$deal_column],$adeals)) {
	        					$insert[] = '(\''.join("','",$values).'\')';
	        					$in++;
	        				} else {
	        					$notin++;
	        				}

	        			$values=null;
	        		}
    			}

				$this->debug($in.' EN CARTERA OBJETIVO, '.$notin.' NO EN CARTERA. SQL INSERT '.count($insert).' ROWS: ',$insert);

    			if(count($insert) > 0) {
    				$sql = "INSERT INTO ".$this->name($tablename)." (".join(",",$columns[$d]).") VALUES ".join(', ',$insert);
    				$result = $conciliamodel->execute($sql);
    			}

		 		fclose($handle);
	 		} else {
	 			$res['error'][] = ("Could not read file ".$file);
	 		}
	 		$res['columns'][] = $columns[$d];
	 		$d++;
	 	}

	 	return $res;
	}

	function name($text) {

		$text = utf8_encode(trim(ltrim($text)));
		$text = preg_replace('/[[:^print:]]/', '', $text);
		$text = filter_var($text, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
		$text = str_replace('-', '_', $text);
		$text = str_replace('/', '_', $text);
		$text = str_replace(' ', '_', $text);
		$text = str_replace('.', '_', $text);
		$text = str_replace("'","",$text);

		//$text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
		$text = preg_replace("%[^-/+|\w ]%", '', $text);
		$text = preg_replace("/[\/_|+ -]+/", '_', $text);
		//$text = preg_replace('/[[:^print:]]/', '', utf8_encode(trim($text)));

		return substr(strtoupper($text),0, 64);
	}

	function cell($text) {

		$text = Encoding_helper::toUTF8(trim(ltrim($text)));		
		$text = preg_replace('/[[:^print:]]/', '', $text);

		$text = str_replace(' -TRADING','',$text);
		$text = str_replace(' - TRADING','',$text);
		$text = str_replace('-TRADING','',$text);
		$text = str_replace("'","",$text);
		//$text = str_replace('"',"",$text);

		//return strtoupper($text);
		return $text;
	}

	function excel()
	{

		require_once(LIB_DIR . 'PHPExcel/PHPExcel.php');

		$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite3;
		$cacheSettings = array('cacheTime' => 600);
    	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

		$conciliamodel = $this->loadModel('Concilia_model');

		$tables  = $this->getValue('tables',array());
		$columns = $this->getValue('columns',array());
		$headers = $this->getValue('headers',array());
		$filter  = $this->getValue('filter','');
		$deal    = $this->getValue('deal','DEAL_NOT_SET');
		$debug   = $this->getValue('debug',false);
		$bykey   = $this->getValue('bykey',0);

		$ignore_format = $this->getValue('ignore_format',0);
		$ignore_hours  = $this->getValue('ignore_hours',0);
		$ignore_trail  = $this->getValue('ignore_trail',0);
		$ignore_cols   = $this->getValue('ignore_cols',array());

		$adiffs  = array();
		$amatch  = array();
		$acount  = array(0,0,0,0,0);

		if(empty($columns))
			$columns = $headers;

		$this->debug('REQUEST',$_GET);
		$this->debug('colums',$columns);

		// $redfont = array('font'  => array('bold'  => true,'color' => array('rgb' => 'FF0000')));
		// $redcell = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'F28A8C')));
    	// $greencell = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00FF00')));

		if($debug) {
			$sSQL = "SELECT OPERACION FROM CARTERA_OBJETIVO LIMIT 0,99999";
			$result = $conciliamodel->query($sSQL);
			$this->debug('CARTERA OBJETIVO',$result);
		}

		$objPHPExcel = new PHPExcel();
		$objPHPExcel->getProperties()->setCreator("torcuato")
									 ->setLastModifiedBy("torcuato")
									 ->setTitle('CONCILIACION_'.substr($tables[0],10).'.xls')
									 ->setSubject('CONCILIACION_'.substr($tables[0],10))
									 ->setDescription('CONCILIACION_'.substr($tables[0],10))
									 ->setKeywords("conciliacion, datanet")
									 ->setCategory("conciliacion datanet");

		$wsPro = new PHPExcel_Worksheet($objPHPExcel, 'Conciliado');
		$objPHPExcel->addSheet($wsPro, 0);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->fromArray(array_merge(array('F_PRO','F_PRE'),$headers),NULL,'A1');
		
		$wsDesc  = new PHPExcel_Worksheet($objPHPExcel, 'Desconciliaciones');
		$objPHPExcel->addSheet($wsDesc, 1);
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->fromArray(array_merge(array('FILA'),$headers),NULL,'B1');

		$wsFormat  = new PHPExcel_Worksheet($objPHPExcel, 'Errores de Formato');
		$objPHPExcel->addSheet($wsFormat, 2);
		$objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->fromArray(array_merge(array('FILA'),$headers),NULL,'B1');
		$index = 3;

		// CONCILIADOS
		$sql  = 'SELECT PRO.CID as procid, PRE.CID as precid ';
		$sql .= 'FROM `'.$tables[0].'` AS PRO ';
		$sql .= 'RIGHT JOIN `'.$tables[1].'` AS PRE USING ('.join(', ',$columns).') ';

		switch($filter) {
			case 'C':
				$sql .= 'WHERE PRO.'.$deal.' IN (SELECT OPERACION FROM CARTERA_OBJETIVO) ';
			break;
			case 'F':
				$sql .= 'WHERE PRO.'.$deal.' IN (SELECT FAC_INTERNAL_ID FROM FACILITIES_OBJETIVO) ';
			break;
		}
		$sql .= 'LIMIT 0,99999';

		$result = $conciliamodel->query($sql);

		$this->debug('SQL MATCH LINES '.$tables[0], $sql);
		$this->debug('RESULT MATCH LINES '.$tables[0], $result);

		foreach ($result as $m) {
			if(!empty($m['procid']))
				$amatch['PRO'][] = $m['procid'];
			if(!empty($m['precid']))
				$amatch['PRE'][] = $m['precid'];
		}

		// FLAG MATCHED=1
		if(!empty($amatch['PRO']) && !empty($amatch['PRE'])) {
			foreach(array(0=>'PRO',1=>'PRE') as $i=>$env) {
				$sql = 'UPDATE `'.$tables[$i].'` SET MATCHED=1 WHERE CID IN ('.join(', ',$amatch[$env]).')';
				$result = $conciliamodel->execute($sql);
			}
		}
		unset($result);

		// DESCONCILIACIONES POR CLAVE
		$matched_desc = array('PRO'=>'','PRE'=>'');
		$rowCON = 2;
		$rowPRO=2;
		$rowPRE=3;
		$rowPRO_format=2;
		$rowPRE_format=3;

		$sql  = 'SELECT DISTINCT PRO.CID as PROCID, PRE.CID as PRECID';

		foreach($headers as $header) {
			$sql .= ', PRO.'.$header.' AS PRO_'.$header;
			$sql .= ', PRE.'.$header.' AS PRE_'.$header;
		}

		$sql .= ' FROM `'.$tables[0].'` AS PRO LEFT JOIN `'.$tables[1].'` AS PRE';
		$sql .= ' USING ('.join(', ',$columns).')';
		$sql .= ' WHERE PRO.MATCHED=1 AND PRE.MATCHED=1';

		switch($filter) {
			case 'C':
				$sql .= ' AND PRO.'.$deal.' IN (SELECT OPERACION FROM CARTERA_OBJETIVO) AND PRE.'.$deal.' IN (SELECT OPERACION FROM CARTERA_OBJETIVO)';
			break;
			case 'F':
				$sql .= ' AND PRO.'.$deal.' IN (SELECT FAC_INTERNAL_ID FROM FACILITIES_OBJETIVO) AND PRE.'.$deal.' IN (SELECT FAC_INTERNAL_ID FROM FACILITIES_OBJETIVO)';
			break;
		}
		$sql .= ' LIMIT 0,99999';

		$result = $conciliamodel->query($sql);

		$this->debug('SQL SELECT CONCILIACIONES/DESCONCILIACIONES'.$tables[0], $sql);
		$this->debug('OPCIONES:', array('IGNORAR_HORAS'=>$ignore_hours,'IGNORAR_FORMATOS'=>$ignore_format,'IGNORAR_COLUMNAS'=>$ignore_cols));

		foreach ($result as $res) {

			$line_pro = array();
			$line_pre = array();

			foreach ($headers as $header) {
				$line_pro[] = $res['PRO_'.$header];
				$line_pre[] = $res['PRE_'.$header];
			}

			if ($line_pro === $line_pre) {

				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->fromArray(array_merge(array($res['PROCID']+1,$res['PRECID']+1),$line_pro),NULL,'A'.$rowCON);
				$rowCON++;
				$acount[0]++;

			} else {

				$error_count 	= 0;
				$ignored_count 	= 0;
				$format_count 	= 0;
				$rounding_count = 0;

				foreach ($headers as $header) {
					if(!isset($res['PRO_'.$header]) || !isset($res['PRE_'.$header]) || $res['PRO_'.$header] != $res['PRE_'.$header]) {
						if (!in_array($header,$ignore_cols)) {

							if ($this->compare_dates($res['PRE_'.$header],$res['PRO_'.$header]) && $ignore_hours) {
								$ignored_count++;
							} elseif ($this->num($res['PRO_'.$header]) === $this->num($res['PRE_'.$header]) && $ignore_format) {
								$ignored_count++;
							} elseif (is_numeric($this->num($res['PRO_'.$header])) && is_numeric($this->num($res['PRE_'.$header])) && abs(abs($this->num($res['PRO_'.$header]))-abs($this->num($res['PRE_'.$header]))) <= 0.01) {
								$error_count++;
							}  else {
								$error_count++;
							}
							
							$this->debug('COMPARISON: '.$res['PRO_'.$header].' == '.$res['PRE_'.$header].' ? '.$this->num($res['PRO_'.$header]).' == '.$this->num($res['PRE_'.$header]).' ? ', ($this->num($res['PRO_'.$header]) == $this->num($res['PRE_'.$header]) && $ignore_format)?'TRUE':'FALSE');

						}
					}
				}

				$this->debug('LINEA', array('PRO'=>$line_pro,'PRE'=>$line_pre,'RESULTADOS'=>array('error_count'=>$error_count,'ignored_count'=>$ignored_count)));

				$matched_desc['PRO'][] = $res['PROCID'];
				$matched_desc['PRE'][] = $res['PRECID'];

				if ($error_count == 0 && $ignored_count == 0) {
					
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->fromArray(array_merge(array($res['PROCID']+1,$res['PRECID']+1),$line_pro),NULL,'A'.$rowCON);
					$rowCON++;
					$acount[0]++;
					//continue;

				} else if ($error_count > 0) {

					$objPHPExcel->setActiveSheetIndex(1);
					$cell = 'A';
					
					$objPHPExcel->getActiveSheet()->setCellValue("$cell"."$rowPRO", 'PRO');
					$objPHPExcel->getActiveSheet()->setCellValue("$cell"."$rowPRE", 'PRE');
					$cell++;
	
					$objPHPExcel->getActiveSheet()->fromArray(array_merge(array($res['PROCID']+1),$line_pro),NULL,'B'.$rowPRO);
					$objPHPExcel->getActiveSheet()->setCellValue("$cell"."$rowPRE", $res['PRECID']+1);
					$cell++;
					foreach ($headers as $header) {
						$pos2 = "$cell"."$rowPRE";

						$objPHPExcel->getActiveSheet()->setCellValue($pos2, $res['PRE_'.$header]);
	
						if(!isset($res['PRO_'.$header]) || !isset($res['PRE_'.$header]) || $res['PRO_'.$header] != $res['PRE_'.$header]) {
							if (in_array($header,$ignore_cols)) {
								$this->cellcolor($objPHPExcel,$pos2,'FFB547');
							} elseif ($this->num($res['PRO_'.$header]) == $this->num($res['PRE_'.$header])) {
								$this->cellcolor($objPHPExcel,$pos2,'74F274');
							} elseif ($this->compare_dates($res['PRE_'.$header],$res['PRO_'.$header])) {
								$this->cellcolor($objPHPExcel,$pos2,'F0B547');
							} elseif  (is_numeric($this->num($res['PRO_'.$header])) && is_numeric($this->num($res['PRE_'.$header])) && abs(abs($this->num($res['PRO_'.$header]))-abs($this->num($res['PRE_'.$header]))) <= 0.01) {
								$this->cellcolor($objPHPExcel,$pos2,'FFB547');
							} else {
								$this->cellcolor($objPHPExcel,$pos2,'F27474');
							}
						}
						$cell++;
					}
					$rowPRO+=3;
					$rowPRE+=3;
					$acount[1]++;

				} else {

					$objPHPExcel->setActiveSheetIndex(2);
					$cell = 'A';
					
					$objPHPExcel->getActiveSheet()->setCellValue("$cell"."$rowPRO_format", 'PRO');
					$objPHPExcel->getActiveSheet()->setCellValue("$cell"."$rowPRE_format", 'PRE');
					$cell++;
	
					$objPHPExcel->getActiveSheet()->fromArray(array_merge(array($res['PROCID']+1),$line_pro),NULL,'B'.$rowPRO_format);
					$objPHPExcel->getActiveSheet()->setCellValue("$cell"."$rowPRE_format", $res['PRECID']+1);
					$cell++;

					foreach ($headers as $header) {
						$pos3 = "$cell"."$rowPRE_format";

						$objPHPExcel->getActiveSheet()->setCellValue($pos3, $res['PRE_'.$header]);
	
						if(!isset($res['PRO_'.$header]) || !isset($res['PRE_'.$header]) || $res['PRO_'.$header] != $res['PRE_'.$header]) {
							if (in_array($header,$ignore_cols)) {
								$this->cellcolor($objPHPExcel,$pos3,'FFB547');
							} elseif ($this->num($res['PRO_'.$header]) == $this->num($res['PRE_'.$header])) {
								$this->cellcolor($objPHPExcel,$pos3,'74F274');
							} elseif ($this->compare_dates($res['PRE_'.$header],$res['PRO_'.$header])) {
								$this->cellcolor($objPHPExcel,$pos3,'F0B547');
							} elseif (is_numeric($this->num($res['PRO_'.$header])) && is_numeric($this->num($res['PRE_'.$header])) && abs(abs($this->num($res['PRO_'.$header]))-abs($this->num($res['PRE_'.$header]))) <= 0.01) {
								$this->cellcolor($objPHPExcel,$pos3,'FFB547');
							} else {
								$this->cellcolor($objPHPExcel,$pos3,'F27474');
							}
						}
						$cell++;
					}
					$rowPRO_format+=3;
					$rowPRE_format+=3;
					$acount[2]++;

				}
			}
			unset($res);
		}
		unset($result);


		//FLAG DESCONCILIACIONES MATCHED=2
		foreach(array(0=>'PRO',1=>'PRE') as $i=>$env)
			if(!empty($matched_desc[$env])) {
				$sql = 'UPDATE `'.$tables[$i].'` SET MATCHED=2 WHERE CID IN ('.join(', ',$matched_desc[$env]).')';
				$result = $conciliamodel->execute($sql);
				$this->debug('SQL SELECT DESC MATCH=2'.$tables[0], $sql);
			}


		$wsPROvsPRE = new PHPExcel_Worksheet($objPHPExcel, 'Solo en PRO');
		$wsPREvsPRO = new PHPExcel_Worksheet($objPHPExcel, 'Solo en PRE');
		$objPHPExcel->addSheet($wsPROvsPRE, 3);
		$objPHPExcel->addSheet($wsPREvsPRO, 4);
		$index = 3;

		// EN PRO Y NO EN PRE
		foreach(array(0=>'PRO',1=>'PRE') as $i=>$env) {
			$sql  = 'SELECT CID, '.join(', ',$headers).' FROM `'.$tables[$i].'` AS '.$env.' ';
			$sql .= 'WHERE '.$env.'.MATCHED=0';

			switch($filter) {
				case 'C':
					$sql .= ' AND '.$env.'.'.$deal.' IN (SELECT OPERACION FROM CARTERA_OBJETIVO)';
				break;
				case 'F':
					$sql .= ' AND '.$env.'.'.$deal.' IN (SELECT FAC_INTERNAL_ID FROM FACILITIES_OBJETIVO)';
				break;
			}
			$sql .= ' LIMIT 0,99999';

			$this->debug('SQL SOLO EN '.$env, $sql);

			$result = $conciliamodel->query($sql);

			$this->debug('RESULT SOLO EN '.$env, $result);

			$objPHPExcel->setActiveSheetIndex($index);
			$objPHPExcel->getActiveSheet()->fromArray(array_merge(array('FILA'),$headers),NULL,'A1');

			$row=2;
			for ($i=0;$i<count($result);$i++) {
	    		$cell = 'A';
	    		$result[$i]['CID']++;
	    		foreach ($result[$i] as $key => $val) {
	    			$pos = "$cell"."$row";
	    			$objPHPExcel->getActiveSheet()->setCellValue($pos, $result[$i][$key]);
	    			$cell++;
	    		}
	    		$row++;
	    		$acount[$index]++;
	    	}
	    	unset($result);
	    	$index++;
		}


		$this->debug('Counter: ',$acount);
		$this->debug('Generated Excel');

		// Renombrar pestañas
		$objPHPExcel->setActiveSheetIndex(1);
		$objPHPExcel->getActiveSheet()->setTitle('Desconciliaciones ('.$acount[1].')');

		$objPHPExcel->setActiveSheetIndex(2);
		$objPHPExcel->getActiveSheet()->setTitle('Errores de Formato ('.$acount[2].')');

		$objPHPExcel->setActiveSheetIndex(3);
		$objPHPExcel->getActiveSheet()->setTitle('Solo V6 ('.$acount[3].')');

		$objPHPExcel->setActiveSheetIndex(4);
		$objPHPExcel->getActiveSheet()->setTitle('Solo V7 ('.$acount[4].')');

		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle('Conciliados ('.$acount[0].')');

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="CONCILIACION_'.substr($tables[0],10).'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->setPreCalculateFormulas(false);
		
		$this->debug('IOFactory');

		$this->debug_die();

		$objWriter->save('php://output');
	}

	public function load_cartera_objetivo() {

		require_once(LIB_DIR . 'PHPExcel/PHPExcel/IOFactory.php');
		//  Include PHPExcel_IOFactory
		//include 'PHPExcel/IOFactory.php';

		//$inputFileName = './sampleData/example1.xls';
		$inputFileName =  ROOT_DIR.'static/upload/'.$this->getValue('filename','');

		//  Read your Excel workbook
		try {
		    $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
		    $objPHPExcel = $objReader->load($inputFileName);
		} catch(Exception $e) {
		    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
		}

		//  Get worksheet dimensions
		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$highestColumn = $sheet->getHighestColumn();

		//  Loop through each row of the worksheet in turn
		for ($row = 1; $row <= $highestRow; $row++){
		    //  Read a row of data into an array
		    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
		                                    NULL,
		                                    TRUE,
		                                    FALSE);

		    array_walk_recursive($rowData, 'Concilia::encode_utf8');
			//var_dump($rowData);
		}

		die();
	}

	private function encode_utf8(&$item, $key)
	{
    	$item = utf8_encode($item);
	}

	private function cellColor(&$obj, $cells, $color) {
	    $obj->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	             'rgb' => $color
	        )
	    ));
	}

	private function compare_dates($d1,$d2) {
		if(DateTime::createFromFormat('d/m/Y G:i', $d1) !== FALSE || DateTime::createFromFormat('d/m/Y G:i', $d2) !== FALSE)
			if (date("Ymd",strtotime($d1)) === date("Ymd",strtotime($d2)))
				return true;
		return false;
	}

	private function num($num,$factor=1) {

	    if(preg_match('/\d+%/', $num))
			$num = num(substr($num,0,-1),0.01);

		if(preg_match('/^([+-]?)(?=\d|\.\d)\d*([\.\,]?\d*)?([Ee]([+-]?\d+))?$/', $num))
			return (float)$factor * (float)strtr($num,array(','=>'.'));

		if (!is_numeric(strtr($num,array(','=>'', '.'=>'', '-'=>''))))
			return $num;

	    $dotPos = strrpos($num, '.');
	    $commaPos = strrpos($num, ',');
	    $sep = (($dotPos > $commaPos) && $dotPos) ? $dotPos : ((($commaPos > $dotPos) && $commaPos) ? $commaPos : false);

	    if (!$sep)
	        return (float)$factor * floatval(preg_replace("/[^0-9]/", "", $num));

	    return (float)$factor * floatval(
	        preg_replace("/[^0-9]/", "", substr($num, 0, $sep)) . '.' .
	        preg_replace("/[^0-9]/", "", substr($num, $sep+1, strlen($num)))
	    );
	}

	private function reserved($name) {
		if(in_array($name, array('ACCESSIBLE', 'ADD', 'ALL', 'ALTER', 'ANALYZE', 'AND', 'AS', 'ASC', 'ASENSITIVE', 'BEFORE', 'BETWEEN', 'BIGINT', 'BINARY', 'BLOB', 'BOTH', 'BY', 'CALL', 'CASCADE', 'CASE', 'CHANGE', 'CHAR', 'CHARACTER', 'CHECK', 'COLLATE', 'COLUMN', 'CONDITION', 'CONSTRAINT', 'CONTINUE', 'CONVERT', 'CREATE', 'CROSS', 'CURRENT_DATE', 'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'CURRENT_USER', 'CURSOR', 'DATABASE', 'DATABASES', 'DAY_HOUR', 'DAY_MICROSECOND', 'DAY_MINUTE', 'DAY_SECOND', 'DEC', 'DECIMAL', 'DECLARE', 'DEFAULT', 'DELAYED', 'DELETE', 'DESC', 'DESCRIBE', 'DETERMINISTIC', 'DISTINCT', 'DISTINCTROW', 'DIV', 'DOUBLE', 'DROP', 'DUAL', 'EACH', 'ELSE', 'ELSEIF', 'ENCLOSED', 'ESCAPED', 'EXISTS', 'EXIT', 'EXPLAIN', 'FALSE', 'FETCH', 'FLOAT', 'FLOAT4', 'FLOAT8', 'FOR', 'FORCE', 'FOREIGN', 'FROM', 'FULLTEXT', 'GET', 'GRANT', 'GROUP', 'HAVING', 'HIGH_PRIORITY', 'HOUR_MICROSECOND', 'HOUR_MINUTE', 'HOUR_SECOND', 'IF', 'IGNORE', 'IN', 'INDEX', 'INFILE', 'INNER', 'INOUT', 'INSENSITIVE', 'INSERT', 'INT', 'INT1', 'INT2', 'INT3', 'INT4', 'INT8', 'INTEGER', 'INTERVAL', 'INTO', 'IO_AFTER_GTIDS', 'IO_BEFORE_GTIDS', 'IS', 'ITERATE', 'JOIN', 'KEY', 'KEYS', 'KILL', 'LEADING', 'LEAVE', 'LEFT', 'LIKE', 'LIMIT', 'LINEAR', 'LINES', 'LOAD', 'LOCALTIME', 'LOCALTIMESTAMP', 'LOCK', 'LONG', 'LONGBLOB', 'LONGTEXT', 'LOOP', 'LOW_PRIORITY', 'MASTER_BIND', 'MASTER_SSL_VERIFY_SERVER_CERT', 'MATCH', 'MAXVALUE', 'MEDIUMBLOB', 'MEDIUMINT', 'MEDIUMTEXT', 'MIDDLEINT', 'MINUTE_MICROSECOND', 'MINUTE_SECOND', 'MOD', 'MODIFIES', 'NATURAL', 'NOT', 'NO_WRITE_TO_BINLOG', 'NULL', 'NUMERIC', 'ON', 'OPTIMIZE', 'OPTION', 'OPTIONALLY', 'OR', 'ORDER', 'OUT', 'OUTER', 'OUTFILE', 'PARTITION', 'PRECISION', 'PRIMARY', 'PROCEDURE', 'PURGE', 'RANGE', 'READ', 'READS', 'READ_WRITE', 'REAL', 'REFERENCES', 'REGEXP', 'RELEASE', 'RENAME', 'REPEAT', 'REPLACE', 'REQUIRE', 'RESIGNAL', 'RESTRICT', 'RETURN', 'REVOKE', 'RIGHT', 'RLIKE', 'SCHEMA', 'SCHEMAS', 'SECOND_MICROSECOND', 'SELECT', 'SENSITIVE', 'SEPARATOR', 'SET', 'SHOW', 'SIGNAL', 'SMALLINT', 'SPATIAL', 'SPECIFIC', 'SQL', 'SQLEXCEPTION', 'SQLSTATE', 'SQLWARNING', 'SQL_BIG_RESULT', 'SQL_CALC_FOUND_ROWS', 'SQL_SMALL_RESULT', 'SSL', 'STARTING', 'STRAIGHT_JOIN', 'TABLE', 'TERMINATED', 'THEN', 'TINYBLOB', 'TINYINT', 'TINYTEXT', 'TO', 'TRAILING', 'TRIGGER', 'TRUE', 'UNDO', 'UNION', 'UNIQUE', 'UNLOCK', 'UNSIGNED', 'UPDATE', 'USAGE', 'USE', 'USING', 'UTC_DATE', 'UTC_TIME', 'UTC_TIMESTAMP', 'VALUES', 'VARBINARY', 'VARCHAR', 'VARCHARACTER', 'VARYING', 'WHEN', 'WHERE', 'WHILE', 'WITH', 'WRITE', 'XOR', 'YEAR_MONTH', 'ZEROFILL', 'GET', 'IO_AFTER_GTIDS', 'IO_BEFORE_GTIDS', 'MASTER_BIND', 'ONE_SHOT', 'PARTITION', 'SQL_AFTER_GTIDS', 'SQL_BEFORE_GTIDS')))
			return '_'.$name;
		else
			return $name;
	}

	private function debug($msg, $var='') {
		if(@$_GET['debug']=="1") {
			echo '<pre>MEM:'.memory_get_peak_usage().' - '.$msg."\n";
			print_r($var);
			echo '</pre>'."\n";
		}
	}

	private function debug_die($msg="died") {
		if(@$_GET['debug']=="1") {
			die('died');
		}
	}

}