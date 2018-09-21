<?php

class Barcode extends Controller {

	function index()
	{
		$auth=$this->loadHelper('auth_helper');
		if(!$auth->access())
			$this->redirect(LOGIN);
		$template = $this->loadView('barcode/index');
		$template->set('title', 'Barcodes');
		$template->render();
	}

	function generate()
	{
		$auth=$this->loadHelper('auth_helper');
		if(!$auth->access())
			$this->redirect(LOGIN);
	
		$pagew = $this->getValue('pw',40);
		$pageh = $this->getValue('ph',10);
		$x = $this->getValue('ml',0);
		$y = $this->getValue('mt',0);
		$width = $this->getValue('bw',40);
		$height = $this->getValue('bh',10);
		$start = $this->getValue('start',0);
		$end = $this->getValue('end',0);
		$fsize = $this->getValue('fsize',10);
        	$dwl = $this->getValue('dwl',"");
		$bcw = $this->getValue('bcw',0.4);
		$drawbox = $this->getValue('drb',"off");
       		$stretch = (bool)(empty($this->getValue('stretch','')))?false:true;

        	$type = $this->getValue('type',"EAN13");
        	$list = $this->getValue('list',"");

		require_once(LIB_DIR . 'tcpdf/tcpdf.php');
		$pdf = new TCPDF("",'mm',array($pagew,$pageh));
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('torcu.com');
		$pdf->SetTitle('Barcodes');
		$pdf->SetSubject('Barcodes');
		$pdf->SetKeywords('barcodes, torcu.com');
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(0,0,0,true);
		$pdf->SetAutoPageBreak(false);
		$pdf->SetDisplayMode('real');
		$pdf->SetFont('helvetica','',$fsize);

		$style = array(
		    'position' => '',
		    'align' => 'C',
		    'stretch' => true,
		    'fitwidth' => false,
		    'cellfitalign' => '',
		    'stretchtext' => $stretch,
		    'border' => false,
		    'fgcolor' => array(0,0,0,100),
		    'bgcolor' => false, //array(255,255,255),
		    'text' => true,
		    'font' => 'helvetica',
		    'fontsize' => $fsize
		);
		
		$box_style = array('all' => array('width'=>0.5, 'cap'=>'square', 'join'=>'mitter', 'dash'=>0, 'phase'=>0));

		$lCodes = Array();

		if ($end >= $start) {
			for ($i=$start;$i<=$end;$i++) {
				$lCodes[] = $i;
			}
		}
		if (!empty($list)) {
			$lCodes = array_merge($lCodes, explode("\n", str_replace("\r", "", $list)));
		}
		$codelen = strlen($lCodes[0]);

		switch($type) {
			case 'EAN13':
				foreach($lCodes as $i) {
					if(is_numeric($i)) {
						$code = str_pad($i, 12, "0", STR_PAD_LEFT);
						$pdf->AddPage();
						$pdf->write1DBarcode($code.$this->ean13CheckSum($code), 'EAN13', $x, $y, $width, $height, $bcw, $style, 'N');
						
						if ($drawbox=="on") {
							$pdf->setVisibility('screen');
							$pdf->setDrawColor(0,0,255);
							$pdf->Rect(0,0,$pagew,$pageh,$box_style,false,false);
							$pdf->setVisibility('all');
							$pdf->setDrawColor(0,0,0,100);
						}
						
					}
				}
			break;
			default:
				foreach($lCodes as $i) {
				  $i = str_pad($i, $codelen, "0", STR_PAD_LEFT);
					$pdf->AddPage();
					$pdf->write1DBarcode($i, $type, $x, $y, $width, $height, $bcw, $style, 'N');
					
					if ($drawbox=="on") {
						$pdf->setVisibility('screen');
						$pdf->setDrawColor(0,0,255);
						$pdf->Rect(0,0,$pagew,$pageh,$box_style,false,false);
						$pdf->setVisibility('all');
						$pdf->setDrawColor(0,0,0,100);
					}
				}
			break;
		}
		
		if(!empty($dwl))
        	$pdf->Output('barcodes.pdf', 'D');
        else
        	$pdf->Output('barcodes.pdf', 'I');
	}

	function ean13CheckSum($ean)
	{
		$checksum = 0;
		foreach (str_split(strrev($ean)) as $pos => $val)
			$checksum += $val * (3 - 2 * ($pos % 2));
		return ((10 - ($checksum % 10)) % 10);
	}

}