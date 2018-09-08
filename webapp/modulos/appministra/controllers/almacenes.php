<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/almacenes.php");

class Almacenes extends Common
{
	public $AlmacenesModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->AlmacenesModel = new AlmacenesModel();
		$this->AlmacenesModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->AlmacenesModel->close();
	}

	function index()
	{
		$tipos = $this->AlmacenesModel->tipos();
		$padres = $this->AlmacenesModel->padres();
		$sucursales = $this->AlmacenesModel->sucursales();
		$estados = $this->AlmacenesModel->estados();
		$municipios = $this->AlmacenesModel->municipios(1);
		$empleados = $this->AlmacenesModel->empleados();
		$clasificadores = $this->AlmacenesModel->clasificadores();
		require("views/almacenes/index.php");
	}

	function listaAlmacenes()
	{
		$json = json_encode($this->AlmacenesModel->listaAlmacenes());
		echo $json;
	}

	function infopadre()
	{
		$res = $this->AlmacenesModel->infopadre($_POST['id']);
		$res = $res->fetch_object();
		echo $res->id_sucursal."Ω".$res->id_estado."Ω".$res->id_municipio."Ω".$res->direccion."Ω".$res->id_empleado_encargado."Ω".$res->telefono."Ω".$res->ext."Ω".$res->id_clasificador."Ω".$res->es_consignacion;
	}

	function getmunicipios()
	{
		$res = $this->AlmacenesModel->getmunicipios($_POST['id']);
		$select = '';
		while($r = $res->fetch_assoc())
		{
			$select .= "<option value='".$r['idmunicipio']."'>".$r['municipio']."</option>";
		}
		echo $select;
	}

	function getdatos()
	{
		$res = $this->AlmacenesModel->getdatos($_POST['id']);
		$datos = $res->fetch_assoc();
		echo $datos['codigo_manual']."Ω".$datos['nombre']."Ω".$datos['id_almacen_tipo']."Ω".$datos['id_padre']."Ω".$datos['id_sucursal']."Ω".$datos['id_estado']."Ω".$datos['id_municipio']."Ω".$datos['direccion']."Ω".$datos['id_empleado_encargado']."Ω".$datos['telefono']."Ω".$datos['ext']."Ω".$datos['es_consignacion']."Ω".$datos['id_clasificador']."Ω".$datos['activo'];

	}

	function guardar()
	{
		echo $this->AlmacenesModel->guardar(intval($_POST['id']),$_POST['clave'],$_POST['nombre'],$_POST['tipo'],$_POST['depende'],$_POST['sucursal'],$_POST['estado'],$_POST['municipio'],$_POST['direccion'],$_POST['encargado'],$_POST['telefono'],$_POST['ext'],$_POST['consignacion'],$_POST['clasificador'],$_POST['status']);
		//echo "status: ".$_POST['status'];
	}

	
	
}


?>
