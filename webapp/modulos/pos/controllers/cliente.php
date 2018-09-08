<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/cliente.php");

class Cliente extends Common
{
	public $ClienteModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar

		$this->ClienteModel = new ClienteModel();
		$this->ClienteModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ClienteModel->close();
	} 

	function indexGrid(){
		$clientes = $this->ClienteModel->indexGrid();
		
		require('views/cliente/gridCliente.php');
	}
	function index()
	{   
		$idCliente = $_GET['idCliente'];

		$paises = $this->ClienteModel->paises();
		$estados = $this->ClienteModel->estados();
		$municipiosFc = $this->ClienteModel->munici();
		$listaPre = $this->ClienteModel->listaPrecios();
		$moneda = $this->ClienteModel->moneda();
		$tipoCredito = $this->ClienteModel->creditos();
		$clasificadores = $this->ClienteModel->clasificadoresTipos(0);
		$empleados = $this->ClienteModel->obtenEmple();
		$bancos = $this->ClienteModel->bancos();
		$cuentas = $this->ClienteModel->cuentas();
		
		$cuentaGastoP = $this->ClienteModel->obtener_cuenta_gasto_padre();
		$cuentaGastoP = $cuentaGastoP->fetch_assoc();
		if ($cuentaGastoP !== NULL) {
			$cuentasGastos = $this->ClienteModel->obtener_cuentas_gasto($cuentaGastoP['account_code']);
		}
		
		$prepolizas_pago = $this->ClienteModel->obtener_prepolizas_pago($cuentaGasto);
		$prepolizas_prov = $this->ClienteModel->obtener_prepolizas_provision($cuentaGasto);
		/*$almacenes = $this->ClienteModel->almacenes(); /*
		/*foreach ($proveedores as $key => $value) {
		 echo ''.$value['razon_social'].'<br>';
		} */
		if($idCliente!=''){
			$datosCliente = $this->ClienteModel->datosCliente($idCliente);
			$datosClienteFact = $this->ClienteModel->datosClienteFact($idCliente);
			$id_claisf = $datosCliente['basicos'][0]['id_clasificacion'];
			$clasificadores = $this->ClienteModel->clasificadoresTipos($id_claisf);
		} 
		//print_r($datosCliente);
		require('views/cliente/clienteForm.php');
	}
	function estados2(){
		$idPais = $_POST['pais'];
		$estados2 = $this->ClienteModel->estados2($idPais);

		echo json_encode($estados2);
	}
	function municipios(){
		$idEstado = $_POST['estado'];
		$municipios = $this->ClienteModel->municipios($idEstado);

		echo json_encode($municipios);
	}
	function guardaCliente(){
   
		$idCliente = $_POST['idCliente'];
		$codigo = $_POST['codigo'];
		$nombre = $_POST['nombre'];
		$tienda = $_POST['tienda'];
		$numint = $_POST['numint']; 
		$numext = $_POST['numext'];
		$direccion = $_POST['direccion'];
		$colonia = $_POST['colonia']; 
		$cp = $_POST['cp'];
		$pais = $_POST['pais'];
		$estado = $_POST['estado'];  
		$municipio = $_POST['municipio'];
		$email = $_POST['email'];
		$celular = $_POST['celular'];
		$tel1 =  $_POST['tel1'];
		$tel2 = $_POST['tel2'];
		$ciudad = $_POST['ciudad'];
		$cumpleanos = $_POST['cumpleanos'];
		$rfc = $_POST['rfc'];
		$curp = $_POST['curp'];
		$diasCredito = $_POST['diasCredito'] ;
		$limiteCredito = $_POST['limiteCredito'];
		$moneda = $_POST['moneda'];
		$listaPrecio = $_POST['listaPrecio'];
		$regimenFact = $_POST['regimenFact'];


		$idComunFact = $_POST['idComunFact'];
		$razonSocial = $_POST['razonSocial'];
		$emailFacturacion = $_POST['emailFacturacion'];
		$direccionFact = $_POST['direccionFact'];
		$numextFact = $_POST['numextFact'];
		$numintFact = $_POST['numintFact'];
		$coloniaFact = $_POST['coloniaFact'];
		$cpFact = $_POST['cpFact'];
		$paisFact = $_POST['paisFact'];
		$estadoFact = $_POST['estadoFact'];
		$municipiosFact = $_POST['municipiosFact'];
		$ciudadFact = $_POST['ciudadFact'];
		$tipoDeCredito = $_POST['tipoDeCredito'];
		$descuentoPP = $_POST['descuentoPP'];
		$interesesMoratorios = $_POST['interesesMoratorios'];
		$perVenCre = $_POST['perVenCre'];
		$perExLim = $_POST['perExLim'];
		$comisionVenta = $_POST['comisionVenta'];
		$comisionCobranza = $_POST['comisionCobranza'];
		$empleado = $_POST['empleado'];
		$enviosDom = $_POST['enviosDom'];
		$tipoClas = $_POST['tipoClas'];

		$banco = $_POST['banco'];
		$numCuenta = $_POST['numCuenta'];
		$cuentaCont = $_POST['cuentaCont'];

		$bandera = $_POST['flag'];

		$prepolizas_provision = $_POST['prepolizas_provision'];
		$prepolizas_pago = $_POST['prepolizas_pago'];
		$cuentas_gastos = $_POST['cuentas_gastos'];


		if($idCliente!=''){    
			$cliente = $this->ClienteModel->updateCliente($idCliente,$codigo,$nombre,$tienda,$numint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$cumpleanos,$rfc,$curp,$diasCredito,$limiteCredito,$moneda,$listaPrecio,$razonSocial,$emailFacturacion,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$tipoDeCredito,$descuentoPP,$interesesMoratorios,$perVenCre,$perExLim,$comisionVenta,$comisionCobranza,$empleado,$enviosDom,$tipoClas,$idComunFact,$regimenFact,$banco,$numCuenta,$cuentaCont,$pais,$paisFact,$prepolizas_provision,$prepolizas_pago,$cuentas_gastos); 
		}else{
			$cliente = $this->ClienteModel->guardaCliente($idCliente,$codigo,$nombre,$tienda,$numint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$cumpleanos,$rfc,$curp,$diasCredito,$limiteCredito,$moneda,$listaPrecio,$razonSocial,$emailFacturacion,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$tipoDeCredito,$descuentoPP,$interesesMoratorios,$perVenCre,$perExLim,$comisionVenta,$comisionCobranza,$empleado,$enviosDom,$tipoClas,$idComunFact,$regimenFact,$banco,$numCuenta,$cuentaCont,$pais,$paisFact,$bandera,$prepolizas_provision,$prepolizas_pago,$cuentas_gastos);           
		 }

		echo json_encode($cliente);
	}
	function borraCliente(){

		$idCliente = $_POST['id'];

		$res = $this->ClienteModel->borraCliente($idCliente);

		echo json_encode($res);
	}
	function activaCliente(){

		$idCliente = $_POST['id'];

		$res = $this->ClienteModel->activaCliente($idCliente);

		echo json_encode($res);
	}

	function buscarLocalizacion() {

		if( isset( $_GET['pais'] ) )
			$parentLoc = $_GET['pais'];
		else if( isset( $_GET['estado'] ) )
			$parentLoc = $_GET['estado'];
		else
			$parentLoc = -1;

		echo $this->ClienteModel->buscarLocalizacion( $_GET['idLoc'], $_GET['patron'] , $parentLoc );

	} 

	function nuevoPais() {
		$nombre = (filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));

		echo $this->ClienteModel->nuevoPais( $nombre );
	}

	function nuevoEstado() {
		$nombre = (filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
		$idPais = (filter_var($_POST['idPais'], FILTER_SANITIZE_NUMBER_INT));

		echo $this->ClienteModel->nuevoEstado( $nombre , $idPais );
	}

	function nuevoMunicipio() {
		$nombre = (filter_var($_POST['nombre'], FILTER_SANITIZE_STRING));
		$idEstado = (filter_var($_POST['idEstado'], FILTER_SANITIZE_NUMBER_INT));

		echo $this->ClienteModel->nuevoMunicipio( $nombre , $idEstado );
	}


	function correoPortal(){
		$correoportal = $_POST['correoportal'];
		$userportal = $_POST['userportal'];
		$passportal = $_POST['passportal'];
		$nombre = $_POST['nombre'];

		$existe = $this->ClienteModel->existeClientePortal($correoportal,$userportal,$passportal);

		if($existe['total']>0){
			$passportal=$existe['rows'][0]['clave'];
		}else{
			$this->ClienteModel->guardarUsuarioPortal($correoportal,$userportal,$passportal,$nombre);
		}

		$this->ClienteModel->enviaCorreoPortal($correoportal,$userportal,$passportal,$nombre);

	}

	function modificarPortal(){
		$passportal2 = $_POST['passportal2'];
		$cliente = $_POST['cliente'];


		$this->ClienteModel->modificaUsuarioPortal($passportal2,$cliente);
		

	}

}


?>
