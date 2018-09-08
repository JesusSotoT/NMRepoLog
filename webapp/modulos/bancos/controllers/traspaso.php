<?php
 require('common.php');

//Carga el modelo para este controlador
require("models/traspaso.php");

class Traspaso extends Common
{
	public $TraspasoModel;
	
	function __construct()
	{
		
		$this->TraspasoModel = new TraspasoModel();
		$this->TraspasoModel->connect();
	}

	function __destruct()
	{
		

		$this->TraspasoModel->close();
	}
	// T R A S P A S O S //
	function vertraspaso(){
		$documentos = $this->TraspasoModel->documentos();
		$cuentasbancariaslista = $this->TraspasoModel->cuentasbancariaslista();
		$or = $this->TraspasoModel->organizacion();
		require("views/documentos/traspaso.php");
	}
	function infoDocumento(){
		$info =  $this->TraspasoModel->infoDocumento($_REQUEST['idDocumento']);	
		echo $info['folio']."/".$info['importe']."/".$info['concepto']."/".$info['referencia'];
	}
	function editar(){
		$ed = $this->TraspasoModel->editados($_REQUEST['idDocumento']);
		
		echo $ed['fecha']."//".$ed['fechaaplicacion']."//".$ed['folio']."//".$ed['importe']."//".$ed['referencia']."//".$ed['concepto']."//".$ed['idbeneficiario']."//".$ed['status']."//".$ed['conciliado']."//".$ed['impreso']."//".$ed['asociado']."//".$ed['proceso']."//".$ed['idclasificador']."//".$ed['posibilidadpago']."//".$ed['id'];
	}
	function crearTraspaso(){// deposito no lleva categoria
	
		$this->TraspasoModel->crearTraspaso($_REQUEST['fechadestino'], 0, $_REQUEST['importe'], $_REQUEST['referencia'], $_REQUEST['concepto'], $_REQUEST['iddestino'], 4, 0, $proceso, $clasificador, $idDocumento);
		//$this->TraspasoModel-
	}
	// F I N  T R A S P A S O //
}
?>