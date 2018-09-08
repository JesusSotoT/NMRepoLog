<?php
//echo 1;
require ('../../wsinvoice/config_api.php');
require ('../../wsinvoice/lib/fpdf.php');
require ('../../wsinvoice/lib/QRcode.php');
require ('../../wsinvoice/class.invoice.pdf.php');
//echo 2;
	// Recordatorio: Mudar archivo a controlador.
	$caja = $_REQUEST['caja'];
	$obser = $_REQUEST['ob'];
	$path = "../";
	//$path = "../../../../../mlog/webapp/modulos/cont/";
	if(isset($_COOKIE['inst_lig']))
		$path = "../../../../../".$_COOKIE['inst_lig']."/webapp/modulos/cont/";
	if (isset($_REQUEST['dir'])) {
		$data = $path.$_REQUEST['dir'];
	} else {
		$data = $path.'xmls/facturas/temporales/'.$_REQUEST['name'];
	}
	

	
	//var_dump($logo);
	if(file_exists($logo)){
	  $logo = $_REQUEST['logo'];
	} else {
	  $logo = '../../';
	}
	if($caja==1){
		$logo = '../../../../netwarelog/archivos/1/organizaciones/'.$_REQUEST['logo'];
		//echo $logo;
	}else{
		$logo = '';
	}
	//echo 'sss'.$_REQUEST['logo'];
	if($_REQUEST['logo']!='logo.png' && $_REQUEST['logo']!='' ){
		$logo = '../../../../netwarelog/archivos/1/organizaciones/'.$_REQUEST['logo'];
	}else{
		$logo = '';
	}
	//echo $logo;
	
	//Si no existe en temporales buscara en las carpetas de con id de polizas
	if(!file_exists($data)){
	  $data = $path.'xmls/facturas/'.$_REQUEST['id'].'/'.$_REQUEST['name'];
	}

	//Si no existe en temporales buscara en las carpeta de documentos bancarios en su respectiva id
	if (!file_exists($data)) {
	  $data = $path."xmls/facturas/documentosbancarios/".$_REQUEST['id']."/".$_REQUEST['name'];
	}


	// Recordatorio: Hacer que el rgb se pueda escoger
	// Color actual en hex: #03a9f4, Azul cuadros netwarmonitor logo.
	$intRed = 3;
	$intGreen = 139;
	$intBlue = 204;
	$strPDFFile = "muestra.pdf";
	if($_REQUEST['nominas']==1){
		$namexml = $_REQUEST['name'];
	}else{
		$namexml = "";
	}
	//echo $namexml;
	//echo '<br>'.$data;
	//$logo = '';
	//echo 3;
	//echo($logo. "<br>");
	$objXmlToPDf = new invoiceXmlToPdf($data, $logo, $intRed, $intGreen, $intBlue, $strPDFFile,$namexml,$caja,$obser);
//echo 4;
	$objXmlToPDf->genPDF();
//echo 5;
?>
