<?php

//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/proveedores.php");
//require("models/cliente.php");

class Proveedores extends Common
{
	public $ProveedoresModel;

	function __construct()
	{
		//Se crea el objeto que instancia al modelo que se va a utilizar
		$this->ProveedoresModel = new ProveedoresModel();
		$this->ProveedoresModel->connect();
	}

	function __destruct()
	{
		//Se destruye el objeto que instancia al modelo que se va a utilizar
		$this->ProveedoresModel->close();
	} 

	function indexGrid(){
		$proveedores = $this->ProveedoresModel->indexGrid();
		require('views/proveedores/gridProveedores.php');
	}

	function index()
	{   
		//$idCliente = $_GET['idCliente'];
		$idProveedor = $_GET['idProveedor'];

		$paises = $this->ProveedoresModel->paises();
		$estados = $this->ProveedoresModel->estados();
		$municipiosFc = $this->ProveedoresModel->munici();
		$listaPre = $this->ProveedoresModel->listaPrecios();
		$moneda = $this->ProveedoresModel->moneda();
		$tipoCredito = $this->ProveedoresModel->creditos();
		$clasificadores = $this->ProveedoresModel->clasificadoresTipos();
		$empleados = $this->ProveedoresModel->obtenEmple();
		$bancos = $this->ProveedoresModel->bancos();
		$cuentas = $this->ProveedoresModel->cuentas();

		$tipoProveedor = $this->ProveedoresModel->tipoProveedor();
		$cuentap = $this->ProveedoresModel->cuentap();
		$cuentaCliente = $this->ProveedoresModel->cuentaCliente();
		$tipoTercero = $this->ProveedoresModel->tipoTercero();
		$tipoOpercaion = $this->ProveedoresModel->tipoOpercaion();
		$tipoIva = $this->ProveedoresModel->tipoIva();
		$saldoProv = $this->ProveedoresModel->saldoProv($idProveedor);

		$cuentaGastoP = $this->ProveedoresModel->obtener_cuenta_gasto_padre();
		$cuentaGastoP = $cuentaGastoP->fetch_assoc();
		if ($cuentaGastoP !== NULL) {
			$cuentasGastos = $this->ProveedoresModel->obtener_cuentas_gasto($cuentaGastoP['account_code']);
		}
		
		$prepolizas_pago = $this->ProveedoresModel->obtener_prepolizas_pago($cuentaGasto);
		$prepolizas_prov = $this->ProveedoresModel->obtener_prepolizas_provision($cuentaGasto);

		if($idProveedor!=''){
			$datosProveedor = $this->ProveedoresModel->datosProveedor($idProveedor);
			$tasas = $this->ProveedoresModel->tasas($idProveedor, $datosProveedor['basicos'][0]['idTasaPrvasumir']);
			//$datosProveedorFact = $this->ProveedoresModel->datosProveedorFact($idProveedor);
		}
		require('views/proveedores/proveedoresForm.php');
	}

	function obtener_prepolizas_pago(){
		$cuentaGastoP = $this->ProveedoresModel->obtener_cuenta_gasto_padre();
		$cuentaGastoP = $cuentaGastoP->fetch_assoc();
		$prepolizas_pago = $this->ProveedoresModel->obtener_prepolizas_pago($cuentaGasto);
		
		while($pre_pago = $prepolizas_pago->fetch_assoc()){
			$temp = array();
			foreach ($pre_pago as $campo => $valor) {
				$temp[$campo] = $valor;
			}
			$arr[] = $temp;
		}

		echo json_encode($arr);
	}

	function obtener_prepolizas_provision(){
		$cuentaGastoP = $this->ProveedoresModel->obtener_cuenta_gasto_padre();
		$cuentaGastoP = $cuentaGastoP->fetch_assoc();
		$prepolizas_prov = $this->ProveedoresModel->obtener_prepolizas_provision($cuentaGasto);
		$arr = array();

		while($pre_prov = $prepolizas_prov->fetch_assoc()){
			$temp = array();
			foreach ($pre_prov as $campo => $valor) {
				$temp[$campo] = $valor;
			}
			$arr[] = $temp;
		}

		echo json_encode($arr);
	}	

	function estados2(){
		$idPais = $_POST['pais'];
		$estados2 = $this->ProveedoresModel->estados2($idPais);
		echo json_encode($estados2);  
	}
	function municipios(){
		$idEstado = $_POST['estado'];
		$municipios = $this->ProveedoresModel->municipios($idEstado);
		echo json_encode($municipios);
	}

	function tipoTerceroOperacion2(){
		$tipoTercero = $_POST['tipoTercero'];
		$tipoOpercaion = $this->ProveedoresModel->tipoOpercaion2($tipoTercero);
		echo json_encode($tipoOpercaion);
	}
	function tasas(){
		$idProveedor = $_POST['idProveedor'];
		$idtasaAsumir = $_POST['idtasaAsumir'];
		$tasas = $this->ProveedoresModel->tasas($idProveedor,$idtasaAsumir);
		echo json_encode($tasas);
	}
	function borraProve(){
		$id = $_POST['id'];

		$res = $this->ProveedoresModel->borraProve($id);
		echo $res;
	}

	function borraContactoProve(){
		$id = $_POST['id'];

		$res = $this->ProveedoresModel->borraContactoProve($id);
		echo $res;
	}

	function activaProve(){
		$id = $_POST['id'];

		$res = $this->ProveedoresModel->activaProve($id);
		echo $res;
	}
	


	function saveProveedor(){
		$idProveedor = $_POST['idProveedor'];
		$codigo = $_POST['codigo'];
		$tipoClas = $_POST['tipoClas'];
		$razon_social = $_POST['razon_social'];
		$rfc = $_POST['rfc'];
		$nombre_comercial = $_POST['nombre_comercial'];
		$calle = $_POST['calle'];
		$no_ext = $_POST['no_ext'];
		$no_int = $_POST['no_int'];
		$colonia = $_POST['colonia'];
		$cp = $_POST['cp'];
		$pais = $_POST['pais'];
		$estado = $_POST['estado'];
		$municipios = $_POST['municipios'];
		$ciudad = $_POST['ciudad'];
		$nombre_contacto = $_POST['nombre_contacto'];
		$email = $_POST['email'];
		$telefono = $_POST['telefono'];
		$web = $_POST['web'];
		$stringCont = $_POST['stringCont'];
		$diasCredito = $_POST['diasCredito'];
		$saldo = $_POST['saldo'];
		$limiteCredito = $_POST['limiteCredito'];
		$tipo = $_POST['tipo'];
		$cuenta = $_POST['cuenta'];
		$beneficiario = $_POST['beneficiario'];
		$cuentaCliente = $_POST['cuentaCliente'];
		$tipoTercero = $_POST['tipoTercero'];
		$tipoTerceroOperacion = $_POST['tipoTerceroOperacion'];
		$numidfiscal = $_POST['numidfiscal'];
		$nombrextranjero = $_POST['nombrextranjero'];
		$nacionalidad = $_POST['nacionalidad'];
		$ivaretenido = $_POST['ivaretenido'];
		$isretenido = $_POST['isretenido'];
		$idtipoiva = $_POST['idtipoiva'];
		$tasa = $_POST['tasa'];
		$tasaAsumir = $_POST['tasaAsumir'];
		$tasas = $_POST['tasas'];
		$stringBanco = $_POST['stringBanco'];
		$aux = $_POST['aux'];
		$minimoPieza = $_POST['minimoPieza'];
		$minimoImportePedido = $_POST['minimoImportePedido'];
		$lugarEntrega = $_POST['lugarEntrega'];
		$prepolizas_provision = $_POST['prepolizas_provision'];
		$prepolizas_pago = $_POST['prepolizas_pago'];
		$cuentas_gastos = $_POST['cuentas_gastos'];
		$rfcFac = $_POST['rfcF'];
		$razonSocialF = $_POST['razonSocialF'];
		$emailFacturacion = $_POST['emailFacturacion'];

		$saveProvedor = $this->ProveedoresModel->saveProvedor($idProveedor,$codigo,$tipoClas,$razon_social,$rfc,$nombre_comercial,$calle,$no_ext,$no_int,$colonia,$cp,$pais,$estado,$municipios,$nombre_contacto,$email,$telefono,$web,$stringCont,$diasCredito,$saldo,$limiteCredito,$tipo,$cuenta,$beneficiario,$cuentaCliente,$tipoTercero,$tipoTerceroOperacion,$numidfiscal,$nombrextranjero,$nacionalidad,$ivaretenido,$isretenido,$idtipoiva,$tasa,$tasas,$stringBanco,$aux,$ciudad,$tasaAsumir,$minimoPieza,$minimoImportePedido,$lugarEntrega,$prepolizas_provision,$prepolizas_pago,$cuentas_gastos,$rfcFac,$razonSocialF,$emailFacturacion);
		echo json_encode($saveProvedor);
	}

	function correoPortal(){
        $correoportal = $_POST['correoportal'];
        $userportal = $_POST['userportal'];
        $passportal = $_POST['passportal'];
        $nombre = $_POST['nombre'];

        $existe = $this->ProveedoresModel->existeProveedorPortal($correoportal,$userportal,$passportal);

        if($existe['total']>0){
            $passportal=$existe['rows'][0]['clave'];
        }else{
            $this->ProveedoresModel->guardarUsuarioPortal($correoportal,$userportal,$passportal,$nombre);
        }

        $this->ProveedoresModel->enviaCorreoPortal($correoportal,$userportal,$passportal,$nombre);

    }
}
?>
