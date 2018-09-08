<?php
	include dirname(__FILE__)."/phpqrcode/qrlib.php";
 	
	$positionPath="";
 	if(isset($_REQUEST['path']))
		$positionPath=$_REQUEST['path'];
 	$strFileName=str_replace(dirname(__FILE__)."/","./",$_REQUEST['file']);
 
	$file= fopen($strFileName, 'r');
	$data = fread($file, filesize($strFileName));
	fclose($file);
	
	QRcode::png($data);
	//@unlink($strFileName);
?>
