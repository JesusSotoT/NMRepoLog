<?php
require('common.php');
require("models/catalogos.php");

class CatalogosRfc extends Common
{
	public $CatalogosModel;
	
	function __construct()
	{
		
		$this->CatalogosModel = new CatalogosModel();
		$this->CatalogosModel->connect();
	}

	function __destruct()
	{
		
		$this->CatalogosModel->close();
	}
	
	function rfcEmpleados(){
		$rfcEmp=$this->CatalogosModel->rfcEmpleados();
		$number = 1;
		while ($e = $rfcEmp->fetch_object()){ 
			echo $number.'|'.$e->rfc.'|'.(chr(13).chr(10));
			$number = $number+1;
		}
	}
}	

?>