<?php
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/sucursal.php");

class Sucursal extends Common
{
	public $SucursalModel;

	function __construct(){
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->SucursalModel = new SucursalModel();
		$this->SucursalModel->connect();
	}

	function __destruct(){
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->SucursalModel->close();
	}

	//Inicializamos el select de estado y organizaciones a la vista para generar una nueva sucursal
	function nuevaSucursal(){
		//obtenemos los estados
		$estadosSelect = '';
		$estados = $this->SucursalModel->obtenerEstados();
		while($estado = $estados->fetch_assoc()){
			$estadosSelect .= "<option value='".$estado['idestado']."'>".$estado['estado']."</option>"; 
		}
		//obtenemos las organizaciones
		$orgaSelect = '';
		$organizaciones = $this->SucursalModel->obtenerOrganizaciones();
		while($organizacion = $organizaciones->fetch_assoc()){
			$orgaSelect .= "<option value='".$organizacion['id']."'>".$organizacion['nombre']."</option>"; 
		}
		//obtenemos los almacenes
		$almaSelect = '';
		$almacenes = $this->SucursalModel->obtenerAlmacenes();
		while($almacen = $almacenes->fetch_assoc()){
			$almaSelect .= "<option value='".$almacen['id']."'>".$almacen['nombre']."</option>"; 
		}
		require('views/sucursales/agregarSucursal.php');
	}

	function obtenerMunicipios($id){
		if (!isset($id)) {
			$id = $_POST['idmunicipio'];	
		}
		$municipiosSelect = '';
		$municipios = $this->SucursalModel->obtenerMunicipios($id);
		while($municipio = $municipios->fetch_assoc()){
			if ($_POST['seleccionado'] == $municipio['idmunicipio']) {
				$municipiosSelect .= "<option value='".$municipio['idmunicipio']."' selected>".$municipio['municipio']."</option>";
			} else {
				$municipiosSelect .= "<option value='".$municipio['idmunicipio']."'>".$municipio['municipio']."</option>"; 
			}
		}
		echo($municipiosSelect);
	}

	function validarFormulario(){
		$validacion = $this->SucursalModel->validarFormulario($_POST);
		if ($validacion == 0) {
			$result = $this->SucursalModel->agregarSucursal($_POST);
			echo($result);
		} else {
			echo("La sucursal que se desea ingresar ya existe");
		}
	}
 
	function verSucursales(){
		//Obtenemos las sucursales
		$sucursales = $this->SucursalModel->obtenerSucursales();
		require('views/sucursales/versucursales.php');
	}

	function obtenerSucursal(){
		//Obtenemos el id de la sucursal que se desea
		$id = $_POST['id'];
		//Obtenemos la información de la sucursal
		$result = $this->SucursalModel->obtenerSucursal($id);
		$sucursal = $result->fetch_assoc();
		//Empaquetamos la información en un array para enviarla al ajax
		$arr = array("nombre"=>$sucursal['nombre'], "direccion"=>$sucursal['direccion'], "estado"=>$sucursal['idEstado'], "municipio"=>$sucursal['idMunicipio'], "codigoPostal"=>$sucursal['codigoPostal'], "telefono"=>$sucursal['telefono'], "contacto"=>$sucursal['contacto'], "organizacion"=>$sucursal['idOrganizacion'], "clave"=>$sucursal['clave'], "activo"=>$sucursal['activo'], "almacen"=>$sucursal['almacen']);
		//Convertimos el array en json
		echo json_encode($arr);
	}

	function modificarSucursal(){
		//$validacion = $this->SucursalModel->validarFormulario($_POST);
		//if ($validacion == 0){
		$result = $this->SucursalModel->modificarSucursal($_POST);
		echo($result);
		//} else {
		//	echo("La sucursal que se desea ingresar ya existe");
		//}
	}

	function sucursalesArbol(){
		require('views/sucursales/sucursalesarbol.php');
	}

}
?>
