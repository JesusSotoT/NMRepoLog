<?php
require('controllers/sobrerecibo.php');
require("models/dispersion.php");

class Dispersion extends Sobrerecibo
{
	
	public $SobrereciboModel;
	public $Dispersion;
	
	function __construct()
	{
		
		$this->DispersionModel = new DispersionModel();
		$this->SobrereciboModel = $this->DispersionModel;
		$this->DispersionModel->connect();	
	}
	
	function __destruct()
	{
		
		$this->DispersionModel->close();
	}

	function dispersion(){
		$cargarDatosDispersos=$this->DispersionModel->cargarDatosDispersos();
		
		require("views/prenomina/dispersion.php");
	}

	function cargaDeDatos(){
		
		$tipoPago          = $this->DispersionModel->tipoPago();
		$nominaAutorizada  = $this->DispersionModel->nominaAutorizada();
       
		if(!$cargaDeDatos->num_rows>0){
			$cargaDeDatos=0;
		}
		 $cargaDeDatos      = $this->DispersionModel->cargaDeDatos($_REQUEST['tipoperiodo']);

		require("views/prenomina/nuevadispersion.php");
	}

	

	function actualizaStatus(){

		 echo $actualizaStatus=$this->DispersionModel->actualizaStatus($_REQUEST['empleId'],$_REQUEST['nominadesc'],$_REQUEST['consecutivo'],$_REQUEST['fechainicio'],$_REQUEST['txtfecha'],$_REQUEST['tipopago'], $_REQUEST['tableData']);

	}


	function accionEliminarDispersion(){ 
		
		echo $accionEliminarDispersion = $this->DispersionModel->accionEliminarDispersion($_REQUEST['idEmpleado'],$_REQUEST['idnomp']);
	}
	}

	?>


