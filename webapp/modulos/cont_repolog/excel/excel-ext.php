<?php
function createExcel($filename, $arrydata) {
	$excelfile = "xlsfile://tmp/".$filename;  
	$fp = fopen($excelfile, "w+");  
	if (!is_resource($fp)) {  
		die("Error al crear $excelfile");  
	}
	fwrite($fp, serialize($arrydata));
	fclose($fp);
	header("Content-Type: application/vnd.ms-excel");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
	header ("Content-Disposition: attachment; filename=\"" . $filename . "\"" );
	//header("Content-Type: application/vnd.ms-excel");
	//header("Expires: 0");
	//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	//header("content-disposition: attachment;filename=pruebas_excel.xls");
	readfile($excelfile);  
}
?>