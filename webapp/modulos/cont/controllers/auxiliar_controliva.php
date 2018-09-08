<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/auxiliar_controlIva.php");

class auxiliar_controlIva extends Common
{
	public $auxiliar_controlIvaModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->auxiliar_controlIvaModel = new auxiliar_controlIvaModel();
		$this->auxiliar_controlIvaModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->auxiliar_controlIvaModel->close();
	}

	function Inicial()
	{
		$periodos = $this->auxiliar_controlIvaModel->ejercicios();
		$proveedores1 = $this->auxiliar_controlIvaModel->proveedores();
		$proveedores2 = $this->auxiliar_controlIvaModel->proveedores();
		$flujo = $this->auxiliar_controlIvaModel->flujo();
		$tasas = $this->auxiliar_controlIvaModel->tasas();
		require('views/fiscal/diot/AuxiliarControlIva.php');
	}
	function VerReporte()
	{
		$empresa = $this->auxiliar_controlIvaModel->empresa();
		$logo = $this->auxiliar_controlIvaModel->logo();
		$fecha_normal = explode('-',$_POST['fecha_ini']);
		$inicio = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];

		$fecha_normal = explode('-',$_POST['fecha_fin']);
		$fin = $fecha_normal[2]."/".$fecha_normal[1]."/".$fecha_normal[0];
		
		

		if($_POST['periodoAcreditamiento'])
		{
			$eje1=$this->auxiliar_controlIvaModel->nombreEjercicio($_POST['ejercicio']);
			$eje=$eje1->fetch_object();
			$rango_inicio = $_POST['periodo_inicio'];
			$rango_fin = $_POST['periodo_fin'];
			$periodoAcreditamiento = 1;
			$perIni=$this->NombrePeriodo($rango_inicio);
			$perFin=$this->NombrePeriodo($rango_fin);
			$fecha = "<td colspan='2'><b style='font-size:18px;color:black;'>$empresa</b><br>
			          <b style='font-size:15px;'>Auxiliar de movimientos de control de IVA</b>
			          <br>Ejercicio <b>$eje->NombreEjercicio</b> Periodo De <b>$perIni</b> A <b>$perFin</b><br><br></td>";
		}
		else
		{
			$rango_inicio = $_POST['fecha_ini'];
			$rango_fin = $_POST['fecha_fin'];
			$periodoAcreditamiento = 0;
			$fecha = "<td colspan='2'><b style='font-size:18px;color:black;'>$empresa</b><br>
			          <b style='font-size:15px;'>Auxiliar de movimientos de control de IVA</b><br>
			          Del <b>$inicio</b> Al <b>$fin</b><br><br></td>";
		}


		$flujo = 0;
		if($_POST['filtraflujo']){$flujo = $_POST['flujo'];}

		$tasas[0] = $_POST['otraTasaNum'];
		if(isset($_POST['tasas'])){
			foreach ($_POST['tasas'] as $key) {
				 $tasas[] = $key;	
			 }
// 			
		}else{
			for($i=1;$i<=6;$i++)
			{
				$tasas[$i] = $_POST['tasas-'.$i];
			}
		}
		//var_dump($tasas);

		$datos = $this->auxiliar_controlIvaModel->obtenerDatos($rango_inicio,$rango_fin,$periodoAcreditamiento,$_POST['prov'],$_POST['pinicial'],$_POST['pfinal'],$flujo,$_POST['noAplica'],$tasas,$_POST['ejercicio']);
		if($_POST['porProv'])
		{
			require('views/fiscal/diot/AuxiliarControlIvaPorProveedorReporte.php');
		}
		else
		{
			require('views/fiscal/diot/AuxiliarControlIvaReporte.php');
		}
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
