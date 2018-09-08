<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/liquidaciones.php");

class Liquidaciones extends Common
{
	public $LiquidacionesModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->LiquidacionesModel = new LiquidacionesModel();
		$this->LiquidacionesModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->LiquidacionesModel->close();
	}

	function cancelaciones()
	{
		require('views/liquidaciones/cancelaciones.php');
	}
	function liquidaciones()
	{
		require('views/liquidaciones/liquidaciones.php');
	}
	function porcentajeope()
	{
		require('views/liquidaciones/porcentajeope.php');
		
	}
	function pagoterceros()
	{
		require('views/liquidaciones/pagoterceros.php');
	}
  function listaOS(){
    $listaOS = $this->LiquidacionesModel->listarOS();
    echo json_encode ($listaOS);
  }
  function listaGastosConcepto(){
    $listaGastosConcepto = $this->LiquidacionesModel->listarGastosConcepto();
    echo json_encode ($listaGastosConcepto);
  }
 function listaAnticipos(){
 	$idordenservicio = $_POST['idordenservicio'];
    $listaAnticipos = $this->LiquidacionesModel->listarAnticipos($idordenservicio);
    echo json_encode ($listaAnticipos);
  }
 function datosLiquidacion(){
 	$idordenservicio = $_POST['idordenservicio'];
    $datosLiquidacion = $this->LiquidacionesModel->datosLiquidacionM($idordenservicio);
    echo json_encode ($datosLiquidacion);
  }
function datosAnticipoOperador(){
 	$idordenservicio = $_POST['idordenservicio'];
    $datosAnticipoOperador = $this->LiquidacionesModel->datosAnticipoOperadorM($idordenservicio);
    echo json_encode ($datosAnticipoOperador);
  }
function listaFormaspago(){
    $listaFormaspago = $this->LiquidacionesModel->listarFormaspago();
    echo json_encode ($listaFormaspago);
  }
function listaCuenta(){
    $listaCuenta = $this->LiquidacionesModel->listarCuenta();
    echo json_encode ($listaCuenta);
  }
 function listaConvenios(){
 	$idsolicitud = $_POST['idsolicitud'];
    $listaConvenios = $this->LiquidacionesModel->listarConvenios($idsolicitud);
    echo json_encode ($listaConvenios);
  }
  
function saveAnticipo(){
	$Anfecha = $_POST['Anfecha'];
	$AnOS = $_POST['AnOS'];
	$Anoperador = $_POST['Anoperador'];
	$Anformapago = $_POST['Anformapago'];
	$Ancuenta = $_POST['Ancuenta'];
	$Anreferencia = $_POST['Anreferencia'];
	$Animporte = $_POST['Animporte'];
	$estatus = 2; // AUTORIZADO
	$tipo = 0; // anticipo normal
    $saveAnticipo = $this->LiquidacionesModel->savedAnticipo($Anfecha,$AnOS,$Anoperador,$Anformapago,$Ancuenta,$Anreferencia,$Animporte,$estatus,$$tipo);
    echo ($saveAnticipo);
  }


function listarendimientos(){
	$idsolicitud = $_POST['idsolicitud'];
$showrend = $this->LiquidacionesModel->listadorendimientos($idsolicitud);
echo json_encode($showrend); 
}



function listaporcentajeope(){
  $listaporcentajes = $this->LiquidacionesModel->listarporcentajes();
  echo json_encode($listaporcentajes);
}

function listaConductores(){
  $listaope = $this->LiquidacionesModel->listarconductores();
  echo json_encode($listaope);
}

function saveporcentaje(){
	$operador = $_POST['operador'];
	$porcentaje = $_POST['porcentaje'];
	$save = $this->LiquidacionesModel->savedporcentaje($operador,$porcentaje);
	echo json_encode($save);
}

function edit_ope(){
	$operador = $_POST['idempleado'];
	$edit = $this->LiquidacionesModel->editedope($operador);
	echo json_encode($edit);

}
  
function Save1partliq(){
$idsolicitud = $_POST['idsolicitud'];
$idordenservicio = $_POST['idordenservicio'];
$idcliente = $_POST['idcliente'];
$kminicial = $_POST['kminicial'];
$kmdescarga = $_POST['kmdescarga'];
$kmfinal = $_POST['kmfinal'];
$kmrecorridos = $_POST['kmrecorridos'];
$kmcargado = $_POST['kmcargado'];
$kmvacio = $_POST['kmvacio'];
$save1part = $this->LiquidacionesModel->Save1partliqM($idsolicitud,$idordenservicio,$idcliente,$kminicial,$kmdescarga,$kmfinal,$kmrecorridos,$kmcargado,$kmvacio);
echo json_encode($save1part);
} 

function showliqComplete(){
$idsolicitud = $_POST['idsolicitud'];
$idordenservicio = $_POST['idordenservicio'];
$idcliente = $_POST['idcliente'];

$showliqComplete1 = $this->LiquidacionesModel->showliqComplete($idsolicitud,$idordenservicio,$idcliente);
echo json_encode($showliqComplete1);
}

function SaveCMBTarg(){
	$inpNoValeCMBTarg = $_POST['inpNoValeCMBTarg'];
	$inpCantCMBTarg = $_POST['inpCantCMBTarg'];
	$CMBTargcostlit = $_POST['CMBTargcostlit'];
	$idordenservicio = $_POST['idordenservicio'];
	$litXcant = $_POST['litXcant'];
	$saveCBMTarg = $this->LiquidacionesModel->SaveCMBTargM($inpNoValeCMBTarg,$inpCantCMBTarg,$CMBTargcostlit,$idordenservicio,$litXcant);
	echo json_encode($saveCBMTarg);
}





}/// fin class

?>