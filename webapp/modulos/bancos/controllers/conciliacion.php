<?php   
   
require('controllers/cheques.php');
require("models/conciliacion.php");

// /////  - - - - - -      CONCILIACION  POLIZAS/DOCUMENTOS   - - - - - - - - -   // /////
class Conciliacion extends Cheques{
// * * * * *     FUNCIONES BASE      * * * * *
	public $ConciliacionModel;
	public $ChequesModel;
	
	function __construct(){
		$this->ConciliacionModel = new ConciliacionModel();
		$this->ChequesModel = $this->ConciliacionModel;
		$this->ConciliacionModel->connect();
	}

	function __destruct(){
		$this->ConciliacionModel->close();
	}
	function conciliacionpd(){
		$acontia = $this->ChequesModel->validaAcontia();
		$bancos	 = $this->ChequesModel->validaBancos();
		require('views/conciliacion/conciliacionpd.php');
		
	}
	
// /////  - - - - - -       FIN CONCILIACION     - - - - - - - - -   // /////
} ?>