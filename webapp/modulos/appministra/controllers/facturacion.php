<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/facturacion.php");

class Facturacion extends Common
{
	public $FacturacionModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->FacturacionModel = new FacturacionModel();
		$this->FacturacionModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->FacturacionModel->close();
	}

	function facturacion(){
		$datos = $this->FacturacionModel->datos();
		$serieFolio = $this->FacturacionModel->serieFolio();
		$regimen = $this->FacturacionModel->regimen();
		require('views/facturacion/facturacion.php');
	}

	function save(){
		$id = $_POST['id'];
		$rfc = $_POST['rfc'];
		$regimen = $_POST['regimen'];
		$pais = $_POST['pais'];
		$razon = $_POST['razon'];
		$domicilio = $_POST['domicilio'];
		$num_ext = $_POST['num_ext'];
		$colonia = $_POST['colonia'];
		$ciudad = $_POST['ciudad'];
		$municipio = $_POST['municipio'];
		$estado = $_POST['estado'];
		$cp = $_POST['cp'];
		$cer = $_POST['cer'];
		$key = $_POST['key'];
		$clave = $_POST['clave'];
		$lugar_exp = $_POST['lugar_exp'];
		$ticket = $_POST['ticket'];
		$pac = $_POST['pac'];
		$userFC = $_POST['userFC'];
		$passFC = $_POST['passFC'];
		$passCiec = $_POST['passCiec'];
		$noVersion = $_POST['noVersion'];
		$save = $this->FacturacionModel->save($id,$rfc,$regimen,$pais,$razon,$domicilio,$num_ext,$colonia,$ciudad,$municipio,$estado,$cp,$cer,$key,$clave,$lugar_exp,$ticket,$pac,$userFC,$passFC,$passCiec,$noVersion);
		echo json_encode($save);
	}

	function saveSF(){
		$idSF = $_POST['idSF'];
		$serie = $_POST['serie'];
		$folio = $_POST['folio'];
		$serie_h = $_POST['serie_h'];
		$folio_h = $_POST['folio_h'];
		$serie_nc = $_POST['serie_nc'];
		$folio_nc = $_POST['folio_nc'];
		$serie_cp = $_POST['serie_cp'];
		$saveSF = $this->FacturacionModel->saveSF($idSF,$serie,$folio,$serie_h,$folio_h,$serie_nc,$folio_nc,$serie_cp,$folio_cp);
		echo json_encode($saveSF);
	}
	
	/* Javier w/h */
	function obtener_series(){
		$Result = $this->FacturacionModel->obtener_series();
		$arr = array();
		while($registro = $Result->fetch_assoc()){
			$obj = array();
			foreach ($registro as $clave => $valor) {
				if ($clave == 'id') {
					$params = "\"".$registro['id']."\", \"".$registro['serie']."\", \"".$registro['folio']."\"";
					$obj[$clave] = "<a href='#' onclick='modificar($params)' title='Modificar'>$valor</a>";
					$obj['delete'] = "<a href='#' class='fa fa-trash fa-1x' onclick='eliminar_ms($valor)'></a>";
				} else {
					$obj[$clave] = $valor;
				}
			}
			$arr[] = $obj;
		}
		echo json_encode($arr);
	}

	function agregar_serie(){
		$Result = $this->FacturacionModel->agregar_serie($_POST);
		echo json_encode($Result);
	}

	function modificar_serie(){
		$Result = $this->FacturacionModel->modificar_serie($_POST);
		echo json_encode($Result);
	}

	function eliminar_serie(){
		$Result = $this->FacturacionModel->eliminar_serie($_POST);
		echo json_encode($Result);
	}

}

?>