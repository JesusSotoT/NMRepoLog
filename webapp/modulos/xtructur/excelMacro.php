<?php
ini_set("max_input_time","20000"); 
ini_set("max_execution_time","20000");

$cookie_xtructur = unserialize($_COOKIE['xtructur']);
$timefile=$cookie_xtructur['time'];
$opcion=$_GET['o'];
$foldfile='csvxlsm/'.$opcion.'-'.$timefile.'.csv';

/** Error reporte */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('America/Mexico_City');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Incluir PHPExcel */
require_once '../../libraries/PHPExcel/PHPExcel.php';
require_once '../../libraries/PHPExcel/PHPExcel/IOFactory.php';

$objReader = PHPExcel_IOFactory::createReader('CSV');
$objReader->setDelimiter(",");
//$objReader->setInputEncoding('UTF-16LE');
$objPHPExcel = $objReader->load($foldfile);
$sheetData = $objPHPExcel->getActiveSheet();

$sheet = $objPHPExcel->getSheet(0);
$highestRow = $sheet->getHighestRow();
$highestColumn = $sheet->getHighestColumn();


$objReader2 = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel2 = $objReader2->load("a.xlsx");

$objPHPExcel2->setActiveSheetIndex(0);
$worksheet2 = $objPHPExcel2->getActiveSheet();


for ($row = 1; $row <= $highestRow; $row++) { 
    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,  null, true, false);
    $col=0;
    foreach ($rowData[0] as $kf => $vf) {
    	$worksheet2->setCellValueByColumnAndRow($col, $row, $vf);
    	$col++;
    }
  }

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="hacermacro.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel2, 'Excel2007');
$objWriter->save('php://output');
exit();



	$objReader = PHPExcel_IOFactory::createReader('Excel2007');
	$objPHPExcelR = $objReader->load("xlsmacro.xlsm");

	$objPHPExcelR->setActiveSheetIndex(0);
	$worksheet = $objPHPExcelR->getActiveSheet();


	
	$worksheet->setCellValue('A2', 'Jorge');
	$worksheet->setCellValue('B2', '10');
	$worksheet->setCellValue('C2', '1000');

	$worksheet->setCellValue('A3', 'Luis');
	$worksheet->setCellValue('B3', '10');
	$worksheet->setCellValue('C3', '1500');

	$worksheet->setCellValue('A4', 'Jorge');
	$worksheet->setCellValue('B4', '9');
	$worksheet->setCellValue('C4', '5000');

	
	//$objPHPExcelR->setActiveSheetIndex(0);
	//$worksheet = $objPHPExcelR->getActiveSheet();

/*
	$baseRow = 3;
	$i = 0;
	$r = 0;

	$worksheet->insertNewRowBefore($baseRow,2);
	
	for($a=6; $a<9; $a++){
		$row = $baseRow + $a;
		
		$b = 10 - $a; 
		
		$worksheet->setCellValue('A'.$row, '200'.$b)
		->setCellValue('B'.$row, '400'.$b)
		->setCellValue('C'.$row, '600'.$b)
		->setCellValue('D'.$row, '800'.$b)
		->setCellValue('E'.$row, '100'.$b)
		->setCellValue('F'.$row, '300'.$b);
		
	}*/
	
	//$worksheet->removeRow($baseRow-1,1);
	
	//$objPHPExcelR->setActiveSheetIndex(0);

//=====OUTPUT XLSX NORMAL

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="jajaja.xlsm"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelR, 'Excel2007');
$objWriter->save('php://output');
exit();

//=========================


//=====OUTPUT XLSM CON MACROS

$objPHPExcel = $objPHPExcelR;
header("Content-Type: application/vnd.ms-excel.sheet.macroEnabled.12");
header('Content-Disposition: attachment;filename="storeInfo.xlsm"');
header('Cache-Control: max-age=0');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcelR, 'Excel2007');
exit();

//=========================


