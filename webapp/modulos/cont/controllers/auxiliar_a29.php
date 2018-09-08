<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/auxiliar_a29.php");

class auxiliar_a29 extends Common
{
	public $auxiliar_a29Model;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->auxiliar_a29Model = new auxiliar_a29Model();
		$this->auxiliar_a29Model->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->auxiliar_a29Model->close();
	}

	function Inicial()
	{
		$periodos = $this->auxiliar_a29Model->ejercicios();
		$proveedores1 = $this->auxiliar_a29Model->proveedores();
		$proveedores2 = $this->auxiliar_a29Model->proveedores();
		require('views/fiscal/diot/AuxiliarFormatoA29.php');
	}
	function VerReporte()
	{
		$empresa = $this->auxiliar_a29Model->empresa();
		$logo = $this->auxiliar_a29Model->logo();
		$fecha_normal = explode('-',$_POST['fecha_ini']);
		$inicio = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];

		$fecha_normal = explode('-',$_POST['fecha_fin']);
		$fin = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];

		


		if($_POST['periodoAcreditamiento'])
		{
			$eje1= $this->auxiliar_a29Model->nombreEjercicio($_POST['ejercicio']);
			$eje=$eje1->fetch_object();
			$rango_inicio = $_POST['ejercicio']."/".$_POST['periodo_inicio'];
			$rango_fin = $_POST['periodo_fin'];
			$perInicio=$_POST['periodo_inicio'];
			$perIni=$this->NombrePeriodo($perInicio);
			$perFin=$this->NombrePeriodo($rango_fin);
			$periodoAcreditamiento = 1;
			$fecha = "<td colspan=2><b style='font-size:18px;color:black;'>".$empresa."</b> <br> 
			          <b style='font-size:15px;'>Auxiliar del Formato A29</b> <br> 
			          Ejercicio: <b>".$eje->NombreEjercicio."</b>  Periodo De <b>".$perIni."</b> A <b>$perFin</b><br><br></td>";
		}
		else
		{
			$rango_inicio = $_POST['fecha_ini'];
			$rango_fin = $_POST['fecha_fin'];
			$periodoAcreditamiento = 0;
			$fecha = "<td colspan=2><b style='font-size:18px;color:black;'>".$empresa."</b> <br>
			          <b>Auxiliar del Formato A29</b> <br> 
			          Del <b>$inicio</b> Al <b>$fin</b> <br><br></td>";
		}

		$datos = $this->auxiliar_a29Model->obtenerDatos($rango_inicio,$rango_fin,$periodoAcreditamiento,$_POST['prov'],$_POST['pinicial'],$_POST['pfinal']);		

		
		require('views/fiscal/diot/AuxiliarFormatoA29Reporte.php');
	}

	function NombrePeriodo($periodo)
	{
		$p;
		switch(intval($periodo))
			{
				case 1:$p  = 'Enero';break;
				case 2:$p  = 'Febrero';break;
				case 3:$p  = 'Marzo';break;
				case 4:$p  = 'Abril';break;
				case 5:$p  = 'Mayo';break;
				case 6:$p  = 'Junio';break;
				case 7:$p  = 'Julio';break;
				case 8:$p  = 'Agosto';break;
				case 9:$p  = 'Septiembre';break;
				case 10:$p = 'Octubre';break;
				case 11:$p = 'Noviembre';break;
				case 12:$p = 'Diciembre';break;
			}
			return $p;
	}
}

?>
