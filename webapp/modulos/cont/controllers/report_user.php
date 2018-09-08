<?php
require('common.php');//Carga la funciones comunes top y footer
require("models/report_usermodel.php");

class Report_User extends Common
{
	public $Report_UserModel;

	function __construct(){
		$this->Report_UserModel = new Report_UserModel();
		$this->Report_UserModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->Report_UserModel->close();
	}

	function reportusers() {
		$var = $this->Report_UserModel->set_collation();
		require('views/reports/reportusers.php');
	}

	function reportusers_despues() {
		$fecha_antes = $_REQUEST['f3_3']."-".$_REQUEST['f3_1']."-".$_REQUEST['f3_2'];
		$fecha_despues = $_REQUEST['f4_3']."-".$_REQUEST['f4_1']."-".$_REQUEST['f4_2'];
		$datos = $this->Report_UserModel->get_reporte_usuarios($fecha_antes, $fecha_despues);
		$empresa = $this->Report_UserModel->empresa();
		$logo = $this->Report_UserModel->logo();
		require('views/reports/reportusers_despues.php');
	}

}

?>
