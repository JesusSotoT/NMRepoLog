<?php
//ini_set('display_errors', 1);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
require("models/caja.php");

class Caja extends Common
{
    public $CajaModel;

    function __construct()
    {
        //Se crea el objeto que instancia al modelo que se va a utilizar

        $this->CajaModel = new CajaModel();
        $this->CajaModel->connect();
    }

    function __destruct()
    {
        //Se destruye el objeto que instancia al modelo que se va a utilizar
        $this->CajaModel->close();
    }
    function pintaRegistros() {

        echo json_encode($this->CajaModel->pintaRegistros());
      //print_r($_SESSION['caja']);
    }
    function getInfoProducto(){
        $id = $_POST['id'];
        $res = $this->CajaModel->getInfoProducto($id);

        echo json_encode($res);

    }

    function indexCaja(){
        //evesmax - agregar id cliente
        $empleado=$_SESSION["accelog_idempleado"];
        $cliente = $this->CajaModel->buscaClienteP($empleado);

        $cliente = $cliente['rows'][0]['id'];

        $proTouchContainer = $this->CajaModel->touchProducts();
        $estados = $this->CajaModel->estados();
        $listaPre = $this->CajaModel->listaPrecios();
        $moneda = $this->CajaModel->moneda();
        $estados = $this->CajaModel->estados();
        $municipios = $this->CajaModel->munici();
        $clientes = $this->CajaModel->clientePedido($cliente);

        require('views/caja/caja.php');
    }
    function indexPedido() {
        unset($_SESSION['caja']);
        //evesmax - agregar id cliente
        // $empleado=$_SESSION["accelog_idempleado"];
        //$cliente = $this->CajaModel->buscaClienteP($empleado);
        //$cliente = $cliente['rows'][0]['id'];

        $moneda = $this->CajaModel->moneda();
        $proTouchContainer = $this->CajaModel->touchProducts();
        $estados = $this->CajaModel->estados();
        $listaPre = $this->CajaModel->listaPrecios();
        $moneda = $this->CajaModel->moneda();
        $estados = $this->CajaModel->estados();
        $municipios = $this->CajaModel->munici();
        //$clientes = $this->CajaModel->clientePedido($cliente);
        require('views/caja/cajaPedido.php');
    }
    
    function indexPedido2(){
        unset($_SESSION['caja']);
        $idPedido = $_GET['pe'];
      
        //evesmax - agregar id cliente
        $empleado=$_SESSION["accelog_idempleado"];
        $cliente = $this->CajaModel->buscaClienteP($empleado);

        $cliente = $cliente['rows'][0]['id'];
        $caracteristicas = '';
        $proTouchContainer = $this->CajaModel->touchProducts();
        $estados = $this->CajaModel->estados();
        $listaPre = $this->CajaModel->listaPrecios();
        $moneda = $this->CajaModel->moneda();
        $estados = $this->CajaModel->estados();
        $municipios = $this->CajaModel->munici();
        $clientes = $this->CajaModel->clientePedido($cliente);
       
        if($idPedido!=''){
            $prods = $this->CajaModel->prodPedi($idPedido);
            $clientes = $this->CajaModel->clientePedido($prods['generales'][0]['idCliente']);   

            foreach ($prods['productos'] as $key => $value) {
                if($value['caracteristicas']!='0'){
                    $caracteristicas = $value['caracteristicas'];
                }else{
                    $caracteristicas = '';
                }
                $res = $this->CajaModel->agregaProducto($value['codigo'], $value['cantidad'],$caracteristicas,$cliente,1);
//                $res4 = $this->CajaModel->recalcula($value['idProducto'],$value['cantidad'],$value['precio']);
                if($value['descuentoCantidad']!='' && $value['descuentoCantidad'] > 0){
                     $resultado = $this->CajaModel->cambiaCantidad($value['idProducto'], $value['descuentoCantidad'], $value['tipoDes']);
                }
            }
            if($prods['generales'][0]['descCant']!=''){
                $resrrr = $this->CajaModel->descuentoGeneral($prods['generales'][0]['descCant']);
            } 

        }
        //print_r($_SESSION['caja']);
        require('views/caja/cajaPedido2.php');
    }
    function buscaClientes() {
        $term = $_GET["term"];

        $resultado = $this->CajaModel->buscaClientes($term);

        echo json_encode($resultado);
    }
    function crearOrden(){
        $pos = $_POST['stringDatos'];

        $res = $this->CajaModel->crearOrden($pos);
    }
    function buscaProductos() {
        $term = $_GET["term"];

        $resultado = $this->CajaModel->buscaProductos($term);

        echo json_encode($resultado);
    }
    function agregaProducto() {
        $idProducto = $_POST["id"];
        $cantidadInicial = $_POST["cantidad"];
        $caracteristicas = $_POST['caracter'];
        $cliente = $_POST['cliente'];
        $xyz = $_POST['xyz'];
/// ** Comanda
	// Consulta si es comanda
		$comanda=strpos($idProducto, "COM");
		if ($comanda!== false) {
		// Inicializamos variables
			$objeto['codigo']=$idProducto;
			session_start();
			$_SESSION['comanda']='';
			$_SESSION['comanda']['codigo']=$idProducto;

		// Valida si es comanda individual
			$persona=strpos($idProducto, "P");
			if ($persona!== false) {
				$persona = explode("P", $idProducto);
				$_SESSION['comanda']['codigo']=$persona[0];
				$_SESSION['comanda']['persona']=$persona[1];
				$objeto['persona']=$persona[1];
				$objeto['codigo']=$persona[0];

			// Valida que la comanda no este pagada, si ya existe regresa el ID de la comanda y de la venta
				$coincidencia=$this->CajaModel->listar_comandas($objeto);
				if($coincidencia['total']>0){
					$resp['status']=3;
					$resp['estatus']=true;
					$resp['comanda']=$coincidencia['rows'][0]['id'];
					$resp['id_venta']=$coincidencia['rows'][0]['id_venta'];

				// Limpia la sesion
					$_SESSION['comanda']='';

					echo json_encode($resp);
					return 0;
				}

		// Valida que la comanda no este pagada, si ya existe regresa el ID de la comanda y de la venta
			}else{
				$coincidencia=$this->CajaModel->listar_comandas($objeto);
				if($coincidencia['total']>0){
					$resp['status']=3;
					$resp['estatus']=true;
					$resp['comanda']=$coincidencia['rows'][0]['id'];
					$resp['id_venta']=$coincidencia['rows'][0]['id_venta'];

				// Limpia la sesion
					$_SESSION['comanda']='';

					echo json_encode($resp);
					return 0;
				}
			}

		// Consulta los pedidos de la comanda
			$pedidos=$this->CajaModel->listar_pedidos($objeto);
			$pedidos=$pedidos['rows'];

		// ** Valida que la comanda tenga pedidos
			if (empty($pedidos)) {
				$resp['status']=4;
				$resp['estatus']=true;

			// Limpia la sesion
				$_SESSION['comanda']='';

				echo json_encode($resp);
				return 0;
			}

			foreach ($pedidos as $key => $value) {
		        $resultado = $this->CajaModel->agregaProducto($value['codigo'], $value['cantidad']);

			// Consulta los extras si existen
				if (!empty($value['adicionales'])) {
				// Obtiene los productos extra
					$extras=$this->CajaModel->listar_productos($value);
					$extras=$extras['rows'];

					foreach ($extras as $k => $v) {
						$extra['id']=$v['id'];
						$extra['descripcion']='(Extra)';
						$descripcion=$this->CajaModel->cambiar_descipcion($extra);

		        		$resul_extra = $this->CajaModel->agregaProducto($v['codigo'], 1);
					}
				}
			}

			echo json_encode($resultado);
			return 0;
		}
/// ** FIN Comanda

/// ** Sub Comanda
	// Consulta si es comanda sub comanda
		$sub_comanda=strpos($idProducto, 'SUB');
		if ($sub_comanda!== false) {
		// Inicializamos variables
			session_start();
			$_SESSION['sub_comanda']='';
			$_SESSION['sub_comanda']=$idProducto;
			$objeto['codigo']=$idProducto;

		// Valida que la comanda no este pagada, si ya existe regresa el ID de la comanda y de la venta
			$coincidencia=$this->CajaModel->listar_sub_comandas($objeto);
			if($coincidencia['total']>0){
				$resp['status']=3;
				$resp['estatus']=true;
				$resp['comanda']=$coincidencia['rows'][0]['id'];
				$resp['id_venta']=$coincidencia['rows'][0]['id_venta'];

			// Limpia la sesion
				$_SESSION['sub_comanda']='';

				echo json_encode($resp);
				return 0;
			}

		// Consulta los pedidos de la comanda
			$pedidos=$this->CajaModel->listar_pedidos_sub_comanda($objeto);
			$pedidos=$pedidos['rows'];

		// ** Valida que la comanda tenga pedidos
			if (empty($pedidos)) {
				$resp['status']=4;
				$resp['estatus']=true;

			// Limpia la sesion
				$_SESSION['comanda']='';

				echo json_encode($resp);
				return 0;
			}

			foreach ($pedidos as $key => $value) {
		        $resultado = $this->CajaModel->agregaProducto($value['codigo'], $value['cantidad']);

			// Consulta los extras si existen
				if (!empty($value['adicionales'])) {
				// Obtiene los productos extra
					$extras=$this->CajaModel->listar_productos($value);
					$extras=$extras['rows'];

					foreach ($extras as $k => $v) {
						$extra['id']=$v['id'];
						$extra['descripcion']='(Extra)';
						$descripcion=$this->CajaModel->cambiar_descipcion($extra);

		        		$resul_extra = $this->CajaModel->agregaProducto($v['codigo'], 1);
					}
				}
			}

			echo json_encode($resultado);
			return 0;
		}
/// ** FIN Sub Comanda

// ** Producto normal
        $resultado = $this->CajaModel->agregaProducto($idProducto,$cantidadInicial,$caracteristicas,$cliente,$xyz);
        echo json_encode($resultado);
// ** FIN Producto normal
    }

    function cargaRfcs() {
        $idCliente = $_POST['idCliente'];

        $resultado = $this->CajaModel->cargaRfcs($idCliente);

        echo json_encode($resultado);
    }
    function calculaImpuestos(){
        unset($_SESSION['caja']);
               $productos = '44-100-1-1/66-100-1-1/';
        //$idProducto = 'IVA00012';
        //$cantidadInicial = 1;
        //$resultado = $this->CajaModel->agregaProducto($idProducto,$cantidadInicial);
        $res = $this->CajaModel->calculaImpuestos($productos);
         //require('views/caja/caja.php');
       // exit();
    }
    function agregaPago() {
        $tipos = $_POST["tipo"];
        $tipostr = $_POST["tipostr"];
        $cantidad = $_POST["cantidad"];
        $txtReferencia = $_POST["txtReferencia"];

        $resultado = $this->CajaModel->agregaPago($tipos, $tipostr, $cantidad, $txtReferencia);

        echo json_encode($resultado);
    }
    function guardarVenta() {
        $idFact = $_POST["idFact"];
        $documento = $_POST["documento"];
        $cliente = $_POST["cliente"];
        $suspendida = $_POST["suspendida"];
        $propina = $_POST["propina"];
        $comentario = $_POST["comentario"];

	//** Comanda
		session_start();
		if (!empty($_SESSION['comanda'])) {
			$objeto['codigo']=$_SESSION['comanda']['codigo'];
			$objeto['persona']=$_SESSION['comanda']['persona'];

		// Guarda la venta
			$resultado = $this->CajaModel->guardarVenta($cliente, $idFact, $documento, $suspendida, $propina, $comentario);

		// Consulta el ID de la venta
			$objeto['id_venta'] = $this->CajaModel->id_venta();
			$objeto['id_venta'] = $objeto['id_venta']['rows'][0]['id_venta'];

		// ** Comanda individual
			if (!empty($objeto['persona'])) {
				$ticket = $this->CajaModel->cambiar_tickets($objeto);
			}

		// Paga la comanda
			$comanda= $this->CajaModel->pagar_comanda($objeto);

		// Limpia la sesion
			$_SESSION['comanda']='';

			echo json_encode($resultado);

			return 0;
		}

	//** SUB Comanda
		if (!empty($_SESSION['sub_comanda'])) {
			$objeto['codigo']=$_SESSION['sub_comanda'];

		// Guarda la venta
			$resultado = $this->CajaModel->guardarVenta($cliente, $idFact, $documento, $suspendida, $propina, $comentario);

		// Consulta el ID de la venta
			$objeto['id_venta'] = $this->CajaModel->id_venta();
			$objeto['id_venta'] = $objeto['id_venta']['rows'][0]['id_venta'];

		// Paga la comanda
			$sub_comanda= $this->CajaModel->pagar_sub_comanda($objeto);

		// Limpia la sesion
			$_SESSION['sub_comanda']='';

		// Regresa el resultado
			echo json_encode($resultado);
			return 0;
		}

        $resultado = $this->CajaModel->guardarVenta($cliente, $idFact, $documento, $suspendida, $propina, $comentario);

        echo json_encode($resultado);
    }
    function guardarPedido(){
        $idFact = $_POST["idFact"];
        $documento = $_POST["documento"];
        $cliente = $_POST["cliente"];
        $suspendida = $_POST["suspendida"];
        $comentario = $_POST["comentario"];
        $moneda = $_POST['moneda'];
        $obs = $_POST['obs'];

        $resultado = $this->CajaModel->guardarPedido($cliente, $idFact, $documento, $suspendida, $comentario,$moneda,$obs);

        echo json_encode($resultado);
    }

    function actualizaPedido(){
        $idFact = $_POST["idFact"];
        $documento = $_POST["documento"];
        $cliente = $_POST["cliente"];
        $suspendida = $_POST["suspendida"];
        $comentario = $_POST["comentario"];
        $moneda = $_POST['moneda'];
        $idPedido = $_POST['pedido'];
        $obs = $_POST['obs'];
        $resultado = $this->CajaModel->actualizaPedido($cliente, $idFact, $documento, $suspendida, $comentario,$moneda,$idPedido,$obs);

        echo json_encode($resultado);
    }
    function facturar() {

        $idFact = $_POST["idFact"];
        $idVenta = $_POST["idVenta"];
        $doc = $_POST["doc"];
        $mensaje = $_POST["mensaje"];
        $consumo = $_POST["consumo"];

        if($doc == 3)
        {

            $resultado = $this->CajaModel->facturarRecibo($idFact, $idVenta, 0,$mensaje,$consumo);
        }else
        {
            if($doc == 2)
            {
                $bloqueado = 0;
            }else
            {
                $bloqueado = 1;
            }

            $resultado = $this->CajaModel->facturar($idFact, $idVenta, $bloqueado,$mensaje,$consumo);
        }

        echo json_encode($resultado);
    }
    function pendienteFacturacion(){

        $azurian = $_POST["azurian"];
        $idFact = $_POST["idFact"];
        $monto = $_POST["monto"];
        $cliente = $_POST["cliente"];
        $trackId = $_POST["trackId"];
        $idVenta = $_POST["idVenta"];
        $documento = $_POST["doc"];

        $resultado = $this->CajaModel->pendienteFacturacion($idFact,$monto,$cliente,$idVenta,$trackId,$azurian,$documento);

        echo json_encode($resultado);
    }
    function ticket(){
        $venta = $_POST['idVenta'];

        //echo '((('.$venta.')))';
        require('views/caja/ticket.php');
    }
    function guardarFacturacion(){

        $UUID = $_POST['UUID'];
        $noCertificadoSAT = $_POST['noCertificadoSAT'];
        $selloCFD = $_POST['selloCFD'];
        $selloSAT = $_POST['selloSAT'];
        $FechaTimbrado = $_POST['FechaTimbrado'];
        $idComprobante = $_POST['idComprobante'];
        $idFact = $_POST['idFact'];
        $idVenta = $_POST['idVenta'];
        $noCertificado = $_POST['noCertificado'];
        $trackId = $_POST['trackId'];
        $monto = $_POST['monto'];
        $cliente = $_POST['cliente'];
        $idRefact = $_POST['idRefact'];
        $azurian = $_POST['azurian'];
        $tipoComp = $_POST['tipoComp'];
        $estatus = $_POST['estatus'];

        if($_POST['doc'] == 3)
        {
            $tipoComp = "R";
        }

        $resultado = $this->CajaModel->guardarFacturacion($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$trackId,$monto,$cliente,$idRefact,$azurian,$estatus);

        echo json_encode($resultado);
    }
    function envioFactura(){
        $uid = $_POST['uid'];
        $correo = $_POST['correo'];
        $azurian = $_POST['azurian'];
        $doc = $_POST['doc'];

        $resultado = $this->CajaModel->envioFactura($uid, $correo, $azurian,$doc);

        echo json_encode($resultado);
    }
    function datosorganizacion(){
        $res = $this->CajaModel->datosorganizacion();
        return $res;
    }
    function datosventa($idVenta){
        $res = $this->CajaModel->datosventa($idVenta);
        return $res;
    }
    function formatofecha($fecha)
    {
        list($anio,$mes,$rest)=explode("-",$fecha);
        list($dia,$hora)=explode(" ",$rest);

        return $dia."/".$mes."/".$anio." ".$hora;
    }
    function productosventa($idVenta){
        $res = $this->CajaModel->productosventa($idVenta);
        return $res;
    }
    function cambiaCantidad() {
        $idProducto = $_POST["id"];
        $descuento = $_POST["cantidad"];
        $tipo = $_POST["tipo"];
        
        $resultado = $this->CajaModel->cambiaCantidad($idProducto, $descuento, $tipo);
        //echo $precionuevo;
        echo json_encode($resultado);
    }
    function datosretiro($idRetiro){
        $res = $this->CajaModel->datosretiro($idRetiro);
        return $res;
    }
    function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
    return $data;
    }
    function getExisCara(){
        $caracteristicas = $_POST['a'];
        $idProducto = $_POST['producto'];
        //echo 'rrrrr'.$idProducto;
        $res = $this->CajaModel->getExisCara($idProducto,$caracteristicas);

        echo json_encode($res);
    }
    function pagos($idVenta){
        $res = $this->CajaModel->pagos($idVenta);
        return $res;
    }
    function eliminaProducto() {
        $idProducto = $_POST["id"];
        $resultado = $this->CajaModel->eliminaProducto($idProducto);
        echo json_encode($resultado);
    }
    function cancelarCaja() {

        $resultado = $this->CajaModel->cancelarCaja();
        echo json_encode($resultado);
    }
    function suspenderVenta() {

        $idFact = $_POST['idFact'];
        $documento = $_POST['documento'];
        $cliente = $_POST['cliente'];
        $nombre = $_POST['nombre'];
        $suspendida = $_POST['suspendida'];

        $resultado = $this->CajaModel->suspenderVenta($idFact, $documento, $cliente, $nombre, $suspendida);

        echo json_encode($resultado);
    }
    function cargarSuspendida() {
        $id_susp = $_POST['id_susp'];
        $resultado = $this->CajaModel->cargarSuspendida($id_susp);

        echo json_encode($resultado);
    }
    function recalcula(){
        $idProducto = $_POST['idProducto'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $xyz = $_POST['xyz'];
        $result = $this->CajaModel->recalcula($idProducto,$cantidad,$precio,$xyz);

        echo json_encode($result);
    }
    function eliminarSuspendida() {
        $suspendida = $_POST['suspendida'];
        $resultado = $this->CajaModel->eliminarSuspendida($suspendida);

        echo json_encode($resultado);
    }
    function ventasCaja(){
        $resultado = $this->CajaModel->ventasCaja();
        echo json_encode($resultado);
    }
    function detalleVenta(){
        $idVenta = $_POST['idVenta'];
        $resultado = $this->CajaModel->detalleVenta($idVenta);
        echo json_encode($resultado);
    }
    function cancelarVenta(){
        $idVenta = $_POST['idVenta'];
        $resultado = $this->CajaModel->cancelarVenta($idVenta);
        echo json_encode($resultado);
    }
    function descuentoGeneral(){
        $descuento = $_POST['descuento'];
        $res = $this->CajaModel->descuentoGeneral($descuento);
        echo json_encode($res);
    }
    function verificaRfcmodal(){
        $rfc = $_POST['rfc'];
        $res = $this->CajaModel->verificaRfcmodal($rfc);
        echo json_encode($res);
    }
    function datosFacturacionCliente(){
        $idFact = $_POST['id'];
        $datos = $this->CajaModel->datosFacturacionCliente($idFact);
        echo json_encode($datos);
    }
    function guardaClientFact(){
        $idFac = $_POST['idFac'];
        $rfc = $_POST['rfc'];
        $razSoc = $_POST['razSoc'];
        $email = $_POST['email'];
        $pais = $_POST['pais'];
        $regimen = $_POST['regimen'];
        $domicilio = $_POST['domicilio'];
        $numero = $_POST['numero'];
        $cp = $_POST['cp'];
        $col = $_POST['col'];
        $estado = $_POST['estado'];
        $municipio = $_POST['municipio'];
        $ciudad = $_POST['ciudad'];

        if($idFac!=''){
            $dataFact = $this->CajaModel->updateDatosFac($idFac,$rfc,$razSoc,$email,$pais,$regimen,$domicilio,$numero,$cp,$col,$estado,$municipio,$ciudad);
        }else{
            $dataFact = $this->CajaModel->newClientDatfact($idFac,$rfc,$razSoc,$email,$pais,$regimen,$domicilio,$numero,$cp,$col,$estado,$municipio,$ciudad);
        }
        echo json_encode($dataFact);

    }
    function oneFact(){
        $idComunFactu = $_POST['idComunFactu'];
        $idVenta = $_POST['venta'];

        $respuesta = $this->CajaModel->oneFact($idComunFactu,$idVenta);

        echo json_encode($respuesta);
    }
    function Iniciarcaja($sucursal,$monto){

        $sucursal = $_POST['sucursal'];
        $monto = $_POST['monto'];

        $resultado = $this->CajaModel->Iniciarcaja($sucursal,$monto);

        echo json_encode($resultado);
    }
    function obtenCorte(){
        $init = $_POST['desde'];
        $end = $_POST['hasta'];
        $onlyShow = $_POST['show'];
        $iduser=$_POST['user'];

        $resultado = $this->CajaModel->getCut($init, $end, $onlyShow, $iduser);
        echo json_encode($resultado);
    }
    function eliminarPago() {
        $pago = $_POST['pago'];
        $resultado = $this->CajaModel->eliminarPago($pago);

        echo json_encode($resultado);
    }
    function crearCorte(){

        $fecha_inicio     = $_POST['fecha_inicio'];
        $fecha_fin        = $_POST['fecha_fin'];
        $saldo_inicial    = $_POST['saldo_inicial'];
        $monto_venta      = $_POST['monto_ventas'];
        $saldo_disponible = $_POST['saldo_disponible'];
        $retiro_caja      = $_POST['retiro_caja'];
        $deposito_caja    = $_POST['deposito_caja'];
        $retiros          = $_POST['retiros'];

        $resp = $this->CajaModel->crearCorte($fecha_inicio,$fecha_fin,$saldo_inicial,$monto_venta,$saldo_disponible,$retiro_caja,$deposito_caja,$retiros);

        echo json_encode($resp);
    }
    function ventasGrid(){
        $ventasGrid = $this->CajaModel->ventasGrid();
        $ventasIndex = $this->CajaModel->ventasIndex();
        //print_r($ventasIndex);
        require('views/caja/ventas.php');
    }
    function refresh(){
        $ventasGrid = $this->CajaModel->ventasGrid();
        echo json_encode($ventasGrid);
    }
    function buscarVentas(){
        $cliente = $_POST['cliente'];
        $empleado = $_POST['empleado'];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];

        $res = $this->CajaModel->buscarVentas($cliente,$empleado,$desde,$hasta);

        echo json_encode($res);
    }
    function buscaVentaCaja(){
        $idVenta = $_POST['idVenta'];

        $res = $this->CajaModel->buscaVentaCaja($idVenta);
        echo json_encode($res);

    }
    function graficar(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];

        $respG = $this->CajaModel->graficar($desde,$hasta);

        echo json_encode($respG);
    }
    function cortesGrid(){
        $cortes = $this->CajaModel->getCortes();
        $ventasIndex = $this->CajaModel->ventasIndex();
        require('views/caja/corte.php');
    }
    function verCorte(){
        $idCorte = $_GET['idCorte'];
        $corteInfo = $this->CajaModel->saldosCorte($idCorte);

        require('views/caja/verCorte.php');
    }
    function enviarTicket(){
        $idVenta = $_POST['idVenta'];
        $correo = $_POST['correo'];

        $envio = $this->CajaModel->enviarTicket($idVenta,$correo);

        echo json_encode($envio);
    }
    function imprimeCorte($idCorte){
        //$idCorte = $_POST['corte'];
        $corteInfo = $this->CajaModel->saldosCorte($idCorte);

        $init = $corteInfo[0]['fechainicio'];
        $end = $corteInfo[0]['fechafin'];
        $onlyShow = 1;
        $iduser=$corteInfo[0]['idEmpleado'];

        $resultado = $this->CajaModel->getCut($init, $end, $onlyShow, $iduser);
        //print_r($resultado);
        return $resultado;
    }
    function saldosCorte($idCorte){
        $corteInfo = $this->CajaModel->saldosCorte($idCorte);
        return $corteInfo;
    }
    function cortesfiltrados(){
        $empleado =  $_POST['empleado'];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];

        $resul = $this->CajaModel->cortesfiltrados($empleado,$desde,$hasta);

        echo json_encode($resul);
    }
    function gridFacturas(){
        $facturas = $this->ClienteModel->gridFacturas();

        require('views/caja/reporteFacturas.php');
    }
    function obtenCaracteristicas(){
        $idProducto = $_POST["id"];
        $cantidadInicial = $_POST["cantidad"];

        $car = $this->CajaModel->obtenCaracteristicas($idProducto);
        
        echo json_encode($car);
    }
    function productosMoneda(){
        $moneda = $_POST['coin'];

        $prods = $this->CajaModel->productosMoneda($moneda);

        echo json_encode($prods);
    }

    function listaCompra(){
        require('views/pedido/listaCompra.php');
    }
    function listaCompraA(){
        require('views/pedido/listaCompraA.php');
    }
    function clienteLog(){
        $empleado=$_SESSION["accelog_idempleado"];
        $cliente = $this->CajaModel->buscaClienteP($empleado);

        echo $idcliente = $cliente['rows'][0]['id'];
    }
    function selectListaCompra(){
        $idclienteLog = $_POST['idclienteLog'];
        $selectListaCompra = $this->CajaModel->selectListaCompra($idclienteLog);
        echo json_encode($selectListaCompra);
    }
    function listarCompra(){

        //$empleado=$_SESSION["accelog_idempleado"]; // -> 3 adminpos

        $desde      = $_POST['desde'];
        $hasta      = $_POST['hasta'];
        $idcliente  = $_POST['cliente'];
        $idproducto = $_POST['producto'];
        $user       = $_POST['user'];

        $lista    = $this->CajaModel->listarCompra($desde,$hasta,$idcliente,$idproducto,$user); // solo para el array de las carac

        foreach ($lista['prod'] as $key => $v) { // formato correcto de caracteristica 
            $caracteristicas = $v['caracteristicas'];

            $cra = preg_replace('/\*/', ',', $caracteristicas);
            $cra = trim($cra,',');
            $cra = preg_replace('/([0-9])+/', '\'\0\'', $cra);

            $arrca[] = array(
                id               => $v['id'],
                nombre           => $v['nombre'],
                caract           => $cra, 
                                  
            );
        }

        $caract1     = $this->CajaModel->caract();
        $Padre      = $caract1['padre'];
        $Hija       = $caract1['hija'];
        $padre1     = '';
        $hija1      = '';

        foreach ($arrca as $key => $val) { // separa las caracteristicas manteniendo la relacion por id
                $id                 = $val['id'];
                $caract             = $val['caract'];
                if($caract =="'0'"){
                                    
                }else{

                    $exparray=explode(',', $caract);                                        
                    foreach ($exparray as $k => $v) {
                        $expv=explode('=>', $v);                                           

                        $ip=$expv[0];
                        $ip = str_replace("'", "", $ip); /// elimina las comillas
                        $ip = $ip*1;

                        $ih=$expv[1];
                        $ih = str_replace("'", "", $ih); /// elimina las comillas
                        $ih = $ih*1;

                        foreach ($Padre as $key => $valor) {
                            $idPadre = $valor['id'];
                            $nombreP = $valor['nombre'];
                            if($idPadre == $ip){
                                $padre1 = $nombreP;
                            }
                        }
                        foreach ($Hija as $key => $valor) {
                            $idHija = $valor['id'];
                            $nombreH = $valor['nombre'];
                            if($idHija == $ih){
                                $hija1 = $nombreH;
                            }
                        }
                        $arrCaract[] = array(
                            id           => $id,
                            padre1       => $padre1,                                            
                            hija1        => $hija1,
                        );
                    }
                }                                
        }// Fin foreach principal
            
        $arrCaractR=array();
        foreach ($arrCaract as $key => $value) { // combina las caracteristicas por id
            $id      = $value['id'];
            $padre1  = $value['padre1'];
            $hija1   = $value['hija1'];
            if(array_key_exists($id, $arrCaractR)){
                $arrCaractR[$id]['id']=$id;
                $arrCaractR[$id]['caractR'].=$padre1.": ".$hija1." ";
            }else{
                $arrCaractR[$id]['id']=$id;
                $arrCaractR[$id]['caractR'].=$padre1.": ".$hija1." ";
            }
        }


        //$list = $this->CajaModel->listarCompra($desde,$hasta,$idcliente,$idproducto);
        $caract = '';
        foreach ($lista['prod'] as $key => $val) {
            $id                 = $val['id'];
 
            $prove = $this->CajaModel->listaProveedores($val['id']);
            foreach ($arrCaractR as $key => $value) {
                $idCar      = $value['id'];
                $idCar      = $idCar*1;
                $caractR    = $value['caractR'];
                if($idCar == $id){
                    $caract = "(".$caractR.")";
                    break;
                }
            }
            $arrESTR[] = array(
                        id               => $id,
                        idprod           => $val['idprod'],
                        nombre           => $val['nombre'],
                        cantidad         => $val['cantidad'],
                        costoCompra      => $val['costoCompra'],
                        impuestos        => $val['impuestos'],
                        caract           => $caract,
                        caracteristicas  => $val['caracteristicas'],
                        unidad           => $val['unidad'], 
                        proveedor        => $prove, 
                        );
            $caract = '';
        }
        //print_r($arrESTR);

        foreach ($lista['prod2'] as $key => $v) { // formato correcto de caracteristica 
            $caracteristicas = $v['caracteristicas'];

            $cra = preg_replace('/\*/', ',', $caracteristicas);
            $cra = trim($cra,',');
            $cra = preg_replace('/([0-9])+/', '\'\0\'', $cra);

            $arrca2[] = array(
                id               => $v['id'],
                nombre           => $v['nombre'],
                caract           => $cra,                                            
            );
        }

        foreach ($arrca2 as $key => $val) { // separa las caracteristicas manteniendo la relacion por id
                $id                 = $val['id'];
                $caract             = $val['caract'];
                if($caract =="'0'"){
                                    
                }else{

                    $exparray=explode(',', $caract);                                        
                    foreach ($exparray as $k => $v) {
                        $expv=explode('=>', $v);                                           

                        $ip=$expv[0];
                        $ip = str_replace("'", "", $ip); /// elimina las comillas
                        $ip = $ip*1;

                        $ih=$expv[1];
                        $ih = str_replace("'", "", $ih); /// elimina las comillas
                        $ih = $ih*1;

                        foreach ($Padre as $key => $valor) {
                            $idPadre = $valor['id'];
                            $nombreP = $valor['nombre'];
                            if($idPadre == $ip){
                                $padre1 = $nombreP;
                            }
                        }
                        foreach ($Hija as $key => $valor) {
                            $idHija = $valor['id'];
                            $nombreH = $valor['nombre'];
                            if($idHija == $ih){
                                $hija1 = $nombreH;
                            }
                        }
                        $arrCaract2[] = array(
                            id           => $id,
                            padre1       => $padre1,                                            
                            hija1        => $hija1,
                        );
                    }
                }                                
        }// Fin foreach principal

        $arrCaractR2=array();
        foreach ($arrCaract2 as $key => $value) { // combina las caracteristicas por id
            $id      = $value['id'];
            $padre1  = $value['padre1'];
            $hija1   = $value['hija1'];
            if(array_key_exists($id, $arrCaractR2)){
                $arrCaractR2[$id]['id']=$id;
                $arrCaractR2[$id]['caractR'].=$padre1.": ".$hija1." ";
            }else{
                $arrCaractR2[$id]['id']=$id;
                $arrCaractR2[$id]['caractR'].=$padre1.": ".$hija1." ";
            }
        }

        //$list1 = $this->CajaModel->listarCompra($desde,$hasta,$idcliente,$idproducto);
        $caract2 = '';
        foreach ($lista['prod2'] as $key => $val) {
            $id                 = $val['id'];
            foreach ($arrCaractR as $key => $value) {
                $idCar      = $value['id'];
                $idCar      = $idCar*1;
                $caractR    = $value['caractR'];
                if($idCar == $id){
                    $caract2 = "(".$caractR.")";
                    break;
                }
            }
            $arrESTR2[] = array(
                        id               => $id,
                        idprod           => $val['idprod'],
                        nombre           => $val['nombre'],
                        cantidad         => $val['cantidad'],
                        costoCompra      => $val['costoCompra'],
                        impuestos        => $val['impuestos'],
                        caract           => $caract2,
                        caracteristicas  => $val['caracteristicas'],
                        nombreCliente    => $val['nombreCliente'],
                        unidad           => $val['unidad'],
                        );
            $caract2 = '';
        }

        $multArraiA = array('prod' => $arrESTR, 'prod2' => $arrESTR2);
        echo json_encode($multArraiA);
    }



    
    function listarCompra2(){

        //$empleado=$_SESSION["accelog_idempleado"]; // -> 3 adminpos

        $desde      = $_POST['desde'];
        $hasta      = $_POST['hasta'];
        $idcliente  = $_POST['cliente'];
        $idproducto = $_POST['producto'];
        $user       = $_POST['user'];

        $lista    = $this->CajaModel->listarCompra2($desde,$hasta,$idcliente,$idproducto,$user); // solo para el array de las carac

        $caract1     = $this->CajaModel->caract();
        $Padre      = $caract1['padre'];
        $Hija       = $caract1['hija'];
        $padre1     = '';
        $hija1      = '';
        $contClie = 0;
        $aux = 0;

        
        foreach ($lista as $key => $v) { // formato correcto de caracteristica 
            $caracteristicas = $v['caracteristicas'];

            $cra = preg_replace('/\*/', ',', $caracteristicas);
            $cra = trim($cra,',');
            $cra = preg_replace('/([0-9])+/', '\'\0\'', $cra);

            $arrca2[] = array(
                id               => $v['id'],
                nombre           => $v['nombre'],
                caract           => $cra,                                            
            );
        }

        foreach ($arrca2 as $key => $val) { // separa las caracteristicas manteniendo la relacion por id
                $id                 = $val['id'];
                $caract             = $val['caract'];
                if($caract =="'0'"){
                                    
                }else{

                    $exparray=explode(',', $caract);                                        
                    foreach ($exparray as $k => $v) {
                        $expv=explode('=>', $v);                                           

                        $ip=$expv[0];
                        $ip = str_replace("'", "", $ip); /// elimina las comillas
                        $ip = $ip*1;

                        $ih=$expv[1];
                        $ih = str_replace("'", "", $ih); /// elimina las comillas
                        $ih = $ih*1;

                        foreach ($Padre as $key => $valor) {
                            $idPadre = $valor['id'];
                            $nombreP = $valor['nombre'];
                            if($idPadre == $ip){
                                $padre1 = $nombreP;
                            }
                        }
                        foreach ($Hija as $key => $valor) {
                            $idHija = $valor['id'];
                            $nombreH = $valor['nombre'];
                            if($idHija == $ih){
                                $hija1 = $nombreH;
                            }
                        }
                        $arrCaract2[] = array(
                            id           => $id,
                            padre1       => $padre1,                                            
                            hija1        => $hija1,
                        );
                    }
                }                                
        }// Fin foreach principal

        $arrCaractR2=array();
        foreach ($arrCaract2 as $key => $value) { // combina las caracteristicas por id
            $id      = $value['id'];
            $padre1  = $value['padre1'];
            $hija1   = $value['hija1'];
            if(array_key_exists($id, $arrCaractR2)){
                $arrCaractR2[$id]['id']=$id;
                $arrCaractR2[$id]['caractR'].=$padre1.": ".$hija1." ";
            }else{
                $arrCaractR2[$id]['id']=$id;
                $arrCaractR2[$id]['caractR'].=$padre1.": ".$hija1." ";
            }
        }

        // inserta la caracteristicas combinadas al arreglo final
        $caract2 = '';
        foreach ($lista as $key => $val) {
            $id                 = $val['id'];
            foreach ($arrCaractR2 as $key => $value) {
                $idCar      = $value['id'];
                $idCar      = $idCar*1;
                $caractR    = $value['caractR'];
                if($idCar == $id){
                    $caract2 = "(".$caractR.")";
                    break;
                }
            }
            $clinete = $val['nombreCliente'];
            if($clinete != $clineteAnt){
                $contClie = 0;
                //$arrESTR2[] = array( idclinete => $val['idCliente'], cliente => $val['nombreCliente'], aux => 1);
                $aux = 1;
            }else{
                $aux = 2;
            }
            $contClie++;

            $arrESTR2[$val['idCliente']][] = array(
                        idclinete => $val['idCliente'],
                        cliente    => $val['nombreCliente'],
                        compra         => $val['cantidad'].' '.$val['unidad'].' '.$val['nombre'].' '.$caract2,
                        aux => $aux,
                        contClie => $contClie,
                        nombre           => $val['nombre'],
                        caract           => $caract2
                        );
            $caract2 = '';
            $clineteAnt = $val['nombreCliente'];
        }

         function cmp($a, $b){
            return (count($b) - count($a));
        }
        usort($arrESTR2, 'cmp'); //$array is your array 
        $arrayAfterSort = $arrESTR2;


        foreach($arrayAfterSort as $item)
        {
            foreach($item as $key => $value)
            {
                $arrFinal[] = array(
                        idclinete => $value['idclinete'],
                        cliente    => $value['cliente'],
                        compra         => $value['compra'],
                        aux => $value['aux'],
                        contClie => $value['contClie'],
                        nombre           => $value['nombre'],
                        caract           => $$value['caract2']
                        );
            }
        }  
        echo json_encode($arrFinal);
    }
    
    function buscarClasificadores() {
        if( isset( $_GET['departamento'] ) )
            $parentClasific = $_GET['departamento'];
        else if( isset( $_GET['familia'] ) )
            $parentClasific = $_GET['familia'];
        else
            $parentClasific = -1;

        echo $this->CajaModel->buscarClasificadores( $_GET['clasificador'], $_GET['patron'] , $parentClasific );
    }

    function cargarMas(){
        $ran = floatval($_POST['rango']);
        $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : "";
        $familia = isset($_POST['familia']) ? $_POST['familia'] : "";
        $linea = isset($_POST['linea']) ? $_POST['linea'] : "";

        $limit = 'limit '.$ran.' , 100';
        //echo $limit;
        $proTouchContainer = $this->CajaModel->touchProducts2($departamento, $familia, $linea, $limit);

        echo json_encode($proTouchContainer);
    }

}


?>
