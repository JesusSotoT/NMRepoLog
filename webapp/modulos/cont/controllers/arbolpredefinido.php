<?php
require('common.php');//Carga la funciones comunes top y footer
require("models/arbolpredefinido.php");

class ArbolPredefinido extends Common
{
	public $ArbolPredefinidoModel;

	function __construct(){
	$this->ArbolPredefinidoModel = new ArbolPredefinidoModel();
	$this->ArbolPredefinidoModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ArbolPredefinidoModel->close();
	}

	function index()
	{
			$inputMask = $this->ArbolPredefinidoModel->getMask();
			$accountMode = $this->ArbolPredefinidoModel->getAccountMode();
			$accounts = $this->ArbolPredefinidoModel->getAccounts();
			$coins = $this->ArbolPredefinidoModel->fillSelect("coin_id","description","cont_coin");
			$nature = $this->ArbolPredefinidoModel->fillSelect("nature_id","description","cont_nature");
			$status = $this->ArbolPredefinidoModel->fillSelect('status_id','description','cont_account_status');
			$classification = $this->ArbolPredefinidoModel->fillSelect("classification_id","description","cont_classification");
			$type = $this->ArbolPredefinidoModel->fillSelect("type_id","description",'cont_main_type');
			$oficial = $this->ArbolPredefinidoModel->oficial();
			$tipo_niveles = $this->ArbolPredefinidoModel->getAccountMode();
			//$subcuentade = $this->ArbolPredefinidoModel->fillSelect("account_id","CONCAT('( ',manual_code,' ) ',description)","cont_accounts","main_account != 3 ORDER BY account_code");
			$tipoinstancia = $this->ArbolPredefinidoModel->tipoinstancia();
			$tipoinstancia_2 = $tipoinstancia; 
			require('views/arbol/index.php');
	}

	function subcuentas()
	{
		$where = '';
		if($_POST['idcuenta'])
		{
			$account_code = $this->ArbolPredefinidoModel->datosCuenta($_POST['idcuenta']);
			$account_code = $account_code['account_code'];
			$where = "AND account_code NOT LIKE '$account_code.%'";
		}
		$subcuentade = $this->ArbolPredefinidoModel->fillSelect("account_id","CONCAT('( ',manual_code,' ) ',description)","cont_accounts","main_account != 3 AND removed = 0 $where AND account_code != '1' AND account_code != '2' AND account_code != '4' ORDER BY account_code");
		echo $subcuentade;
	}

	function guardaCuenta()
	{
		echo $this->ArbolPredefinidoModel->guardar($_POST['numero'],$_POST['nombre'],$_POST['nombre_idioma'],$_POST['subcuentade'],$_POST['naturaleza'],$_POST['moneda'],$_POST['clasificacion'],$_POST['digito'],$_POST['estatus'],$_POST['idcuenta']);	
	}

	function eliminarCuenta()
	{
		echo $this->ArbolPredefinidoModel->eliminarCuenta($_POST['idcuenta']);	
	}

	function datosCuenta()
	{
		$datos = $this->ArbolPredefinidoModel->datosCuenta($_POST['idCuenta']);
		echo json_encode($datos);
	}

	function corregirEstructura()
	{
		echo $this->ArbolPredefinidoModel->corregirEstructura($_POST);
	}

	function getAccounts()
		{
			$json = json_encode($this->ArbolPredefinidoModel->getAccounts());
			echo $json;
		}

}
?>
