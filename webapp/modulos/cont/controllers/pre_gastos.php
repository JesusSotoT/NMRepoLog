<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/pre_gastos.php");

class Pre_Gastos extends Common
{
	public $Pre_GastosModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->Pre_GastosModel = new Pre_GastosModel();
		$this->Pre_GastosModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->Pre_GastosModel->close();
	}

	function Index()
	{
		$finalidades = $this->Pre_GastosModel->funcionalGasto(0,0);//Todas las Funcionalizades
		$claves = $this->Pre_GastosModel->getClaves();//Todas las Funcionalizades
		require('views/presupuestal/gasto.php');
	}

	function BuscaFuncion()
	{
		$Funcion = $this->Pre_GastosModel->funcionalGasto(intval($_POST['fin']),intval($_POST['fun']));//Todas las Funcionalizades
		$option = "<option value='0'>Seleccione una opción</option>";
		while($f = $Funcion->fetch_assoc())
		{
			$option .= "<option value='".$f['num']."'>".$f['num']." / ".$f['des']."</option>";
		}
		echo $option;
	}

	function Guardar()
	{
		$callback = $this->Pre_GastosModel->Guardar($_POST);
		echo $callback;	
	}

	function Eliminar()
	{
		$callback = $this->Pre_GastosModel->Eliminar($_POST['idd']);
		echo $callback;	
	}
	
		
}

?>
