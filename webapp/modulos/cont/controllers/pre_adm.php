<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/pre_adm.php");

class Pre_Adm extends Common
{
	public $Pre_AdmModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->Pre_AdmModel = new Pre_AdmModel();
		$this->Pre_AdmModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->Pre_AdmModel->close();
	}

	function Index()
	{
		$sector_publico = $this->Pre_AdmModel->sectores(0,0,0,0,1);//Todas las Funcionalizades
		$claves = $this->Pre_AdmModel->getClavesAdm();//Todas las Funcionalizades
		require('views/presupuestal/adm.php');
	}

	function BuscaTipo()
	{
		$Funcion = $this->Pre_AdmModel->sectores(intval($_POST['pub']),intval($_POST['fin']),intval($_POST['eco1']),intval($_POST['eco2']),intval($_POST['idd']));//Todas las Funcionalizades
		$option = "<option value='0'>Seleccione una opción</option>";
		while($f = $Funcion->fetch_assoc())
		{
			$option .= "<option value='".$f['num']."'>".$f['num']." / ".$f['des']."</option>";
		}
		echo $option;
	}

	function Guardar()
	{
		$callback = $this->Pre_AdmModel->Guardar($_POST);
		echo $callback;	
	}

	function Eliminar()
	{
		$callback = $this->Pre_AdmModel->Eliminar($_POST['idd']);
		echo $callback;	
	}
	
		
}

?>
