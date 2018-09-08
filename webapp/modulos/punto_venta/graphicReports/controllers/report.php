<?php 
	/*=============================================================================
	=            controllers/report.php - Miguel Angel Velazco Martinez           =
	==============================================================================*/
	
	
	
	/*-----  End of controllers/report.php - Miguel Angel Velazco Martinez ------*/
	

	require '../models/reports.php';

	$report = new Report();

	$method = $_GET[ 'method' ];

	if ($method != 'getSucursals')
	{
		echo $report->$method( $_GET['initDate'], $_GET['finalDate'], $_GET['sucursal'] );
	}
	else
	{
		echo $report->$method();
	}
?>