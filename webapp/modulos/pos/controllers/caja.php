<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);
//Carga la funciones comunes top y footer
require('common.php');

//Carga el modelo para este controlador
global $api_lite;
if(!isset($api_lite)) require("models/caja.php");
else require $api_lite . "/modulos/pos/models/caja.php";

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

    function indexCaja(){

        $limit = 'limit 0,100';
        $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : "";
        $familia = isset($_POST['familia']) ? $_POST['familia'] : "";
        $linea = isset($_POST['linea']) ? $_POST['linea'] : "";
        $proTouchContainer = $this->CajaModel->touchProducts($departamento, $familia, $linea, $limit);

        $estados = $this->CajaModel->estados();
        $listaPre = $this->CajaModel->listaPrecios();
        $moneda = $this->CajaModel->moneda();
        $estados = $this->CajaModel->estados();
        $municipios = $this->CajaModel->munici();
        $configDatos = $this->CajaModel->configDatos();
        $formasDePago = $this->CajaModel->formasDePago();
        $clientes = $this->CajaModel->ventasIndex();

        $selectAlmacenes = $this->CajaModel->buscarAlmacenes();
		$_SESSION['propinas'] = '';
		unset($_SESSION['propinas']);

	// Consulta los ajustes de Foodware si tiene Foodware
	    session_start();
		if (in_array(2156, $_SESSION['accelog_menus'])) {
	        $ajustes_foodware = $this -> CajaModel -> listar_ajustes_foodware($objeto);
			$ajustes_foodware = $ajustes_foodware['rows'][0];
		}
        require('views/caja/caja.php');
    }
    function indexCaja2(){
        /*for ($i=1; $i < 50; $i++) { 
            echo 'INSERT into app_producto_impuesto(id_producto,id_impuesto,formula) values("'.$i.'",1,0);<br>';
        } */
        $limit = 'limit 0,100';
        $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : "";
        $familia = isset($_POST['familia']) ? $_POST['familia'] : "";
        $linea = isset($_POST['linea']) ? $_POST['linea'] : "";
        $proTouchContainer = $this->CajaModel->touchProducts($departamento, $familia, $linea, $limit);

        $estados = $this->CajaModel->estados();
        $listaPre = $this->CajaModel->listaPrecios();
        $moneda = $this->CajaModel->moneda();
        $estados = $this->CajaModel->estados();
        $municipios = $this->CajaModel->munici();
        $configDatos = $this->CajaModel->configDatos();
        $formasDePago = $this->CajaModel->formasDePago();
        $clientes = $this->CajaModel->ventasIndex();
        $proPPagos = $this->CajaModel->productosProntipagos();

        $selectAlmacenes = $this->CajaModel->buscarAlmacenes();
        $seriesCfdi = $this->CajaModel->seriesCfdi();
        $usoCFDI =$this->CajaModel->usoCFDI();
       
        $versionFac = $this->CajaModel->versionFacturacion();
        $_SESSION['propinas'] = '';
        unset($_SESSION['propinas']);

    // Consulta los ajustes de Foodware si tiene Foodware
        session_start();
        if (in_array(2156, $_SESSION['accelog_menus'])) {
            $ajustes_foodware = $this -> CajaModel -> listar_ajustes_foodware($objeto);
            $ajustes_foodware = $ajustes_foodware['rows'][0];
        }
        require('views/caja/caja3.php');
    }
    function cargarMas(){
        $ran = floatval($_POST['rango']);
        $departamento = isset($_POST['departamento']) ? $_POST['departamento'] : "";
        $familia = isset($_POST['familia']) ? $_POST['familia'] : "";
        $linea = isset($_POST['linea']) ? $_POST['linea'] : "";

        $limit = 'limit '.$ran.' , 100';
        //echo $limit;
        $proTouchContainer = $this->CajaModel->touchProducts($departamento, $familia, $linea, $limit);
//para precio por sucursal
        foreach ($proTouchContainer as $key => $value) {
            $resListPreTmp = $this->CajaModel->listaPreciosDe($value['id']);
            $proTouchContainer[$key]['precio'] = ( count($resListPreTmp) == 0 ? number_format($value['precio'],2) : number_format($resListPreTmp[0]['precio'],2) );
            $proTouchContainer[$key]['precio']  = str_replace(',', '', $proTouchContainer[$key]['precio']);
        }

        echo json_encode($proTouchContainer);
    }
    function buscaClientes() {
        $term = $_GET["term"];

        $resultado = $this->CajaModel->buscaClientes($term);

        echo json_encode($resultado);
    }
    function buscaVendedores() {
        $term = $_GET["term"];

        $resultado = $this->CajaModel->buscaVendedores($term);

        echo json_encode($resultado);
    }
    function enviaParaPronti(){
        $sku = $_POST['prodPronti'];
        $monto = $_POST['monto'];
        $referencia = $_POST['referencia'];

        $resultado = $this->CajaModel->enviaParaPronti($sku,$monto,$referencia);

        echo json_encode($resultado);
    }
    function guardaVentaPronti(){
        $idProducto = $_POST['idProducto'];
        $monto = $_POST['monto'];
        $referencia = $_POST['referencia'];
        $res = $this->CajaModel->guardaVentaPronti($idProducto,$monto,$referencia);

        echo json_encode($res);
    }
    function buscaProductos() {
        $term = $_GET["term"];

        $resultado = $this->CajaModel->buscaProductos($term);

        echo json_encode($resultado);
    }
    function agregPodCar(){
        $idProducto = $_POST["id"];
        $cantidadInicial = $_POST["cantidad"];
        $caracteristicas = $_POST['caracter'];

        $res = $this->CajaModel->agregPodCar($idProducto,$cantidadInicial,$caracteristicas);
        echo json_encode($res);
    }
    

    function escomanda(){

        session_start();
        $idComanda = (substr($_SESSION['comanda']['codigo'],3)*1);
        if($idComanda == ''){
            echo '0';
            exit;
        }else{
        
            $datosComanda = $this->CajaModel->datosComanda($idComanda);
            $bandera    = 0;
            $reimprime  = 1;
            $tipo       = 0;
            $num_comensales = 1;
            $tipo_operacion = 1;
            $personas   = 1;        
            $idmesa     = $datosComanda['rows'][0]['idmesa'];
            $empleado   = $datosComanda['rows'][0]['empleado'];
            $logo       = $datosComanda['rows'][0]['logo'];
            $objeto['bandera']          = $bandera;
            $objeto['reimprime']        = $reimprime;
            $objeto['idmesa']           = $idmesa;
            $objeto['tipo']             = $tipo;
            $objeto['num_comensales']   = $num_comensales;
            $objeto['tipo_operacion']   = $tipo_operacion;
            $objeto['personas']         = $personas;
            $objeto['mesero']           = $empleado;
                    
            $comanda = $this -> CajaModel -> closeComanda2($idComanda,$bandera,$idmesa,$tipo);

            // Valida el logo
            $src = '../../netwarelog/archivos/1/organizaciones/' . $logo;
            $comanda['logo'] = (file_exists($src)) ? $src : '';

            //Consulta los campos para mostrar en el tickect seleccionado en configuración
            $que_mostrar = $this -> CajaModel -> get_que_mostrar_ticket();

            //Consulta la organizacion
            $organizacion = $this -> CajaModel ->datos_organizacion();

            //Consulta datos de la sucursal
            $datos_sucursal =  $this -> CajaModel -> datos_sucursal($idmesa);

            // Borra informacion de comanda
            $_SESSION['comanda'] = '';

            require('preticket.php');
        }
    }
    
    function agregaProducto() {

        $idProducto = $_POST["id"];
        $cantidadInicial = $_POST["cantidad"];
        $caracteristicas = $_POST['caracter'];
        $cliente = $_POST['cliente'];
        $series = $_POST['series'];
        $lotes = $_POST['lotes'];
        $borrarSesion = $_POST['borrarSesion'];
        if($borrarSesion==1){
            unset($_SESSION['pagos-caja']);
            unset($_SESSION['caja']);
        }

        $sele = $this->CajaModel->esComanda($idProducto);
        
        /// ch@ 
        session_start();
        $comandaR = (substr($_SESSION['comanda']['codigo'],3)*1);
        $f = 0;
        /// ch@ fin

    if($sele==0){

    
/// ** Comanda
	// Consulta si es comanda
		if( strrpos($idProducto, "COM") !== FALSE){
            $f = 1;
		  // Inicializamos variables
			$objeto['codigo'] = $idProducto;
			//session_start();
			$_SESSION['comanda'] = '';
			$_SESSION['comanda']['codigo'] = $idProducto;

		  // Consulta los datos de la comanda
			$detalles_mesa = $this->CajaModel->detalles_mesa($objeto);

			$detalles_mesa = $detalles_mesa['rows'][0];
			$_SESSION['detalles_mesa'] = $detalles_mesa;
			$_SESSION['detalles_mesa']['codigo'] = $idProducto;

		  // Valida si es comanda individual
			$persona = strpos($idProducto, "P");
            
			if ($persona !== false) {
				$persona = explode("P", $idProducto);
				$_SESSION['comanda']['codigo'] = $persona[0];
				$_SESSION['comanda']['persona'] = $persona[1];
				$objeto['persona'] = $persona[1];
				$objeto['codigo'] = $persona[0];

				$_SESSION['detalles_mesa']['persona'] = $persona[1];

			// Valida que la comanda no este pagada, si ya existe regresa el ID de la comanda y de la venta
				$coincidencia = $this->CajaModel->listar_comandas($objeto);

				if($coincidencia['total'] > 0){
					$resp['status'] = 3;
					$resp['estatus'] = true;
					$resp['comanda'] = $coincidencia['rows'][0]['id'];
					$resp['id_venta'] = $coincidencia['rows'][0]['id_venta'];

				// Limpia la sesion
					$_SESSION['comanda'] = '';
					unset($_SESSION['detalles_mesa']);

					global $api_lite;
                    if(!isset($api_lite)) echo json_encode($resp);
					return 0;
				}
		      // Valida que la comanda no este pagada, si ya existe regresa el ID de la comanda y de la venta
			}else{
				$coincidencia = $this->CajaModel->listar_comandas($objeto);

				if($coincidencia['total'] > 0){
					$resp['status'] = 3;
					$resp['estatus'] = true;
					$resp['comanda'] = $coincidencia['rows'][0]['id'];
					$resp['id_venta'] = $coincidencia['rows'][0]['id_venta'];

				// Limpia la sesion
					$_SESSION['comanda'] = '';
					unset($_SESSION['detalles_mesa']);

					global $api_lite;
                    if(!isset($api_lite)) echo json_encode($resp);
					return 0;
				}
			}

		      // Consulta los pedidos de la comanda
			$pedidos = $this->CajaModel->listar_pedidos($objeto);
			$pedidos = $pedidos['rows'];            
            //print_r($pedidos2); exit();
            foreach ($pedidos as $key => $value) {
                
                if($value['id_promocion'] != 0){
                    $precio = 0;
                    $promocion = $this -> CajaModel -> get_promocion($value['id_promocion']);
                    $pedidos[$key]['nombre'] = $promocion['nombre'];
                    $promociones = $this -> CajaModel -> get_promociones($value['tipin'], $value['id_promocion']);

                    $promociones = $promociones['rows'];
                    $act_c = $key;
                    if($promocion['tipo'] == 1){
                        foreach ($promociones as $k => $v) {
                            $precio += $v['precio'];
                        }
                        $desc = (100 - $promocion['descuento']) / 100;
                        $precio = $precio * $desc;
                        $pedidos[$act_c]['precio'] = $precio;
                        
                    } else if($promocion['tipo'] == 2){
                        foreach ($promociones as $k => $v) {
                            if($k%2==0){
                                $precio += $v['precio'];
                            }
                        }
                        $pedidos[$act_c]['precio'] = $precio;
                    } else if($promocion['tipo'] == 4){
                        foreach ($promociones as $k => $v) {
                            $precio += $promocion['precio_fijo'] + $v['sumaExtras']*1;
                        }
                        $pedidos[$act_c]['precio'] = $precio;
                        
                    } else if($promocion['tipo'] == 3){
                        for ($x=0; $x < $promocion['cantidad_descuento']; $x++) { 
                            $promociones[(count($promociones)-1) - $x]['precio'] = 0;
                        }
                        foreach ($promociones as $k => $v) {
                            $precio += $v['precio'];
                        }
                        $pedidos[$act_c]['precio'] = $precio;
                    } else if($promocion['tipo'] == 5){
                        //print_r($promociones);
                        foreach ($promociones as $k => $v) {
                            if($v['comprar'] == 1){
                                $precio += $v['precio'];
                            }
                        }
                        $pedidos[$act_c]['precio'] = $precio;
                    } 
                    foreach ($promociones as $key2 => $value2) {
                        if ($key2 == 0) {
                            $pedidos[$key]['nombre'] = $pedidos[$key]['nombre'].' ['.$value2['nombre'];
                        } else{
                            $pedidos[$key]['nombre'] = $pedidos[$key]['nombre'].', '.$value2['nombre'];
                        }
                        if ($key2 == (count($promociones) - 1)) {
                            $pedidos[$key]['nombre'] = $pedidos[$key]['nombre'].']';

                        }
                        $con = count($pedidos[$key]['products']);
                        $pedidos[$key]['products'][$con]['id'] = $value2['idproducto'];
                        $pedidos[$key]['products'][$con]['precio'] = $value2['precio'];
                        //n ch@ costo en pedidos con promocion
                        $pedidos[$key]['products'][$con]['costo'] = $value2['costo'];
                        //n ch@ costo en pedidos con promocion fin

                    }
                    
                } 
            }
            
		  // ** Valida que la comanda tenga pedidos
			if (empty($pedidos)) {
				$resp['status'] = 4;
				$resp['estatus'] = true;

			// Limpia la sesion
				$_SESSION['comanda'] = '';

				global $api_lite;
                if(!isset($api_lite)) echo json_encode($resp);
				return 0;
			}
            //print_r($pedidos); exit();
			foreach ($pedidos as $key => $value) {
                //print_r($value['codigo']);
                if($value['id_promocion'] != 0){
                     $resultado = $this->CajaModel->agregaProductoPromo($value);
                } else if($value['tipo_desc'] == '%' and $value['monto_desc'] > 0){ /// IF PARA DESCUENTOS DESDE COMANDA                                         
                    $monto_desc = $value['monto_desc'];
                    $tipo_desc = $value['tipo_desc'];
                    $idcar = 'desc'.$tipo_desc.$monto_desc;
                    $idcar2 = $value['idProducto'].'_desc'.$tipo_desc.$monto_desc;

                    $resultado = $this->CajaModel->agregaProducto($value['codigo'], $value['cantidad'], $idcar, '','','',$tipo_desc,$monto_desc);
                    /// aplica desc
                    $resultado = $this->CajaModel->cambiaCantidad($idcar2,$monto_desc,$tipo_desc,'',0);
                                                                                    /// IF PARA DESCUENTOS DESDE COMANDA  fin
                } else {
    		        $resultado = $this->CajaModel->agregaProducto($value['codigo'], $value['cantidad'], '', '');
                }   

			     

                 // Consulta los extras si existen
				if (!empty($value['adicionales'])) {
				// Obtiene los productos extra
					$extras=$this->CajaModel->listar_productos($value);
					$extras=$extras['rows'];

					foreach ($extras as $k => $v) {
						$extra['id'] = $v['id'];
						$extra['descripcion'] = '(Extra)';
						$descripcion = $this->CajaModel->cambiar_descipcion($extra);

		        		$resultado = $this->CajaModel->agregaProducto($v['codigo'], $value['cantidad'], '', '');
					}
				}

                if (!empty($value['complementos'])) {
                // Obtiene los productos extra
                    $comp=$this->CajaModel->listar_productos_comp($value);
                    $comp=$comp['rows'];

                    foreach ($comp as $k => $v) {
                        $com['id'] = $v['id'];
                        $com['descripcion'] = '(complemento)';
                        $descripcion = $this->CajaModel->cambiar_descipcion($com);

                        $resultado = $this->CajaModel->agregaProducto($v['codigo'], 1, '', '');
                    }
                }
			}
			
			$resultado['comanda'] = $idProducto;
			//print_r($_SESSION['caja']);
            //echo 'ikrjdiejdiejdiejdiej';

			global $api_lite;
            if(!isset($api_lite)) echo json_encode($resultado);
			return 0;
		}
    }    
/// ** FIN Comanda

/// ** Sub Comanda
	// Consulta si es comanda sub comanda
		if( preg_match("/^SUB(\d{5})$/",$idProducto) ){
            $f = 1;
		// Inicializamos variables
			//session_start();
		// Consulta los datos de la comanda
			$objeto['codigo_sub'] = $idProducto;
			$detalles_mesa = $this->CajaModel->detalles_mesa($objeto);
			$detalles_mesa = $detalles_mesa['rows'][0];
			$_SESSION['detalles_mesa'] = $detalles_mesa;

			$_SESSION['sub_comanda'] = '';
			$_SESSION['sub_comanda'] = $idProducto;
			$objeto['codigo'] = $idProducto;


		// Valida que la comanda no este pagada, si ya existe regresa el ID de la comanda y de la venta
			$coincidencia = $this->CajaModel->listar_sub_comandas($objeto);
			if($coincidencia['total'] > 0){
				$resp['status'] = 3;
				$resp['estatus'] = true;
				$resp['comanda'] = $coincidencia['rows'][0]['id'];
				$resp['id_venta'] = $coincidencia['rows'][0]['id_venta'];

			// Limpia la sesion
				$_SESSION['sub_comanda'] = '';

				global $api_lite;
                if(!isset($api_lite)) echo json_encode($resp);
				return 0;
			}

		// Consulta los pedidos de la comanda
			$pedidos = $this->CajaModel->listar_pedidos_sub_comanda($objeto);
			$pedidos = $pedidos['rows'];

		// ** Valida que la comanda tenga pedidos
			if (empty($pedidos)) {
				$resp['status']=4;
				$resp['estatus']=true;

			// Limpia la sesion
				$_SESSION['comanda']='';

				global $api_lite;
                if(!isset($api_lite)) echo json_encode($resp);
				return 0;
			}

			foreach ($pedidos as $key => $value) {
		        $resultado = $this->CajaModel->agregaProducto($value['codigo'], $value['cantidad'],'');

			// Consulta los extras si existen
				if (!empty($value['adicionales'])) {
				// Obtiene los productos extra
					$extras=$this->CajaModel->listar_productos($value);
					$extras=$extras['rows'];

					foreach ($extras as $k => $v) {
						$extra['id'] = $v['id'];
						$extra['descripcion'] = '(Extra)';
						$descripcion = $this->CajaModel->cambiar_descipcion($extra);

		        		$resul_extra = $this->CajaModel->agregaProducto($v['codigo'], 1, '');
					}
				}

                if (!empty($value['complementos'])) {
                // Obtiene los productos extra
                    $comp=$this->CajaModel->listar_productos_comp($value);
                    $comp=$comp['rows'];

                    foreach ($comp as $k => $v) {
                        $com['id'] = $v['id'];
                        $com['descripcion'] = '(complemento)';
                        $descripcion = $this->CajaModel->cambiar_descipcion($com);

                        $resultado = $this->CajaModel->agregaProducto($v['codigo'], 1, '', '');
                    }
                }
			}

			global $api_lite;
            if(!isset($api_lite)) echo json_encode($resultado);
			return 0;
		}

/// ** FIN Sub Comanda

        $pos = strpos($idProducto, 'PMP');

        // Nótese el uso de ===. Puesto que == simple no funcionará como se espera
        // porque la posición de 'a' está en el 1° (primer) caracter.
        if ($pos !== false) {
           $res = $this->CajaModel->agregaPedido($idProducto);
            global $api_lite;
            if(!isset($api_lite)) echo json_encode($res);

            return 0;
        }
// ** Producto normal
        if($series!=''){
            foreach ($series as $key => $value) {
                $seriesString .=$value.','; 
            }
        }
        //var_dump($lotes);
        /*$lotesString = '';
        if($lotes!=''){
            foreach ($lotes as $key => $value) {
                $lotesString .=$value.','; 
            }
        } */
        $resultado = $this->CajaModel->agregaProducto($idProducto,$cantidadInicial,$caracteristicas,$cliente,$seriesString,$lotes);

        
        // agrega producot a la comanda de foodware
        
        if($comandaR != '' and $f == 0 and $comandaR != 0){
            $this->CajaModel->agregaProductoComanda($idProducto,$comandaR);
        }
        

    
    global $api_lite;
    if(!isset($api_lite)) echo json_encode($resultado);
// ** FIN Producto normal
    }

    function cargaRfcs() {
        $idCliente = $_POST['idCliente'];

        $resultado = $this->CajaModel->cargaRfcs($idCliente);

        echo json_encode($resultado);
    }
    function checalimitecredito(){

         $cliente = $_POST['cliente'];
         $monto = $_POST['monto'];

         $resultado = $this->CajaModel->checalimitecredito($cliente,$monto);

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

    function obtenerLeyenda() {
        return $this->CajaModel->obtenerLeyenda();
    }

    function agregaPago() {
        $tipos = $_POST["tipo"];
        $tipostr = $_POST["tipostr"];
        $cantidad = $_POST["cantidad"];
        $txtReferencia = $_POST["txtReferencia"];
        $tarjeta = $_POST['tarjeta'];

        $resultado = $this->CajaModel->agregaPago($tipos, $tipostr, $cantidad, $txtReferencia,$tarjeta);


		session_start();
        // echo json_encode($_SESSION['pagos-caja']);
        global $api_lite;
        if(!isset($api_lite)) echo json_encode($resultado);
    }
    function checarPagos() {
        $resultado = $this->CajaModel->checarPagos();

        echo json_encode($resultado);
    }
    function updatevista(){
        $res = $this->CajaModel->updatevista();

        echo json_encode($res);
    }
    function guardarVenta() {
		session_start();
        $idFact = $_POST["idFact"];
        $documento = $_POST["documento"];
        $cliente = $_POST["cliente"];
        $suspendida = $_POST["suspendida"];
        $propina = $_POST["propina"];
        $comentario = $_POST["comentario"];
        $idPedido = $_POST['idPedido'];
        $moneda = $_POST['moneda'];
        $vendedor = $_POST['vendedor'];
        $propinas = $_POST["propinas"];
        $tipoCambio = $_POST['tipocambio'];
        $usarPuntos = $_POST['usarPuntos'];
        $totalPuntosInput = $_POST['totalPuntosInput'];
        $tr = $_POST['tr'];
	//** Comanda
		if (!empty($_SESSION['comanda'])) {
			$objeto['codigo'] = $_SESSION['comanda']['codigo'];
			$objeto['persona'] = $_SESSION['comanda']['persona'];

        // ch@ actualiza estatus de bitacora repartidores
            $codigo = $objeto['codigo'];
            function substring_index($subject, $delim, $count){ // elimina los caracteres despues de la P y despues los e primeros
                if($count < 0){
                    return substr(implode($delim, array_slice(explode($delim, $subject), $count)), 3) ;
                }else{
                    return substr(implode($delim, array_slice(explode($delim, $subject), 0, $count)), 3);
                }
            }
           
            $id_comanda = substring_index($codigo,'P',1);

            $resulRepa = $this->CajaModel->updateRepa($id_comanda);
            $resulmesa = $this->CajaModel->updateMesa($id_comanda);
        // ch@ fin

		// Guarda la venta
			$resultado = $this->CajaModel->guardarVenta($cliente, $idFact, $documento, $suspendida, $propinas, $comentario,$moneda,1,1,$id_comanda);

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
			$_SESSION['comanda'] = '';

			global $api_lite;
            if(!isset($api_lite)) echo json_encode($resultado);

			return 0;
		}

	//** SUB Comanda
		if (!empty($_SESSION['sub_comanda'])) {
			$objeto['codigo'] = $_SESSION['sub_comanda'];

		// Guarda la venta
			$resultado = $this->CajaModel->guardarVenta($cliente, $idFact, $documento, $suspendida, $propina, $comentario,$moneda);

		// Consulta el ID de la venta
			$objeto['id_venta'] = $this->CajaModel->id_venta();
			$objeto['id_venta'] = $objeto['id_venta']['rows'][0]['id_venta'];

		// Paga la comanda
			$sub_comanda = $this->CajaModel->pagar_sub_comanda($objeto);

		// Limpia la sesion
			$_SESSION['sub_comanda'] = '';

		// Regresa el resultado
			global $api_lite;
            if(!isset($api_lite)) echo json_encode($resultado);
			return 0;
		}

        $vendedorDefault = $this->CajaModel->verMiUsuario();

        $resultado = $this->CajaModel->guardarVenta($cliente, $idFact, $documento, $suspendida, $propina, $comentario,$moneda, ( empty($vendedor) ) ? $vendedorDefault : $vendedor, $tipoCambio, 0,$usarPuntos,$totalPuntosInput,$tr);

        global $api_lite;
        if(!isset($api_lite)) echo json_encode($resultado);
        else return $resultado;
    }
    function facturar() {

        $idFact = $_POST["idFact"];
        $idVenta = $_POST["idVenta"];
        $doc = $_POST["doc"];
        $mensaje = $_POST["mensaje"];
        $consumo = $_POST["consumo"];
        $moneda = $_POST['moneda'];
        $tipoCambio = $_POST['tipocambio'];
        $serie = $_POST['serie'];
        $usoCfdi = $_POST['usoCfdi'];
        $mpCat = $_POST['mpCat'];
        $relacion = $_POST['relacion'];
        $uuidRelacion = $_POST['uuidRelacion'];

        if($doc == 3)
        {

            $resultado = $this->CajaModel->facturarRecibo($idFact, $idVenta, 0,$mensaje,$consumo,$moneda,$tipoCambio);
        }else
        {
            if($doc == 2 || $doc == 5)
            {
                $bloqueado = 0;
            }else
            {
                $bloqueado = 1;
            }
            $versionFac = $this->CajaModel->versionFacturacion();
            if($versionFac == '3.3'){                       
                $resultado = $this->CajaModel->facturar33($idFact, $idVenta, $bloqueado,$mensaje,$consumo,$doc,$moneda,$tipoCambio,$serie,$usoCfdi,$mpCat,$relacion,$uuidRelacion);            
            }else{
                $resultado = $this->CajaModel->facturar($idFact, $idVenta, $bloqueado,$mensaje,$consumo,$doc,$moneda,$tipoCambio,$serie);
            }
            
        }

        global $api_lite;
        if(!isset($api_lite)) echo json_encode($resultado);
        else return $resultado;
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

        global $api_lite;
        if(!isset($api_lite)) echo json_encode($resultado);
        else return $resultado;
    }
    function ticket(){
        $venta = $_POST['idVenta'];

        //echo '((('.$venta.')))';
        require('views/caja/ticket.php');
    }
    function guardaTIDPe(){
        $trackId = $_POST['trackId'];
        $id = $_POST['id'];

        $res = $this->CajaModel->guardaTIDPe($trackId,$id);

        echo json_encode($res);
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
        $serie = $_POST['serie'];

        if($_POST['doc'] == 3)
        {
            $tipoComp = "R";
        }
        if($_POST['doc'] == 5)
        {
            $tipoComp = "H";
        }

        $resultado = $this->CajaModel->guardarFacturacion($UUID,$noCertificadoSAT,$selloCFD,$selloSAT,$FechaTimbrado,$idComprobante,$idFact,$idVenta,$noCertificado,$tipoComp,$trackId,$monto,$cliente,$idRefact,$azurian,$estatus,$serie);

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
    function datoscliente($idCliente){
        $res = $this->CajaModel->datoscliente($idCliente);
        return $res;
    }
    function datosSucursal($idventa){
        $res = $this->CajaModel->datosSucursal($idventa);
        return $res;
    }
    function datosventa($idVenta){
        $res = $this->CajaModel->datosventa($idVenta);
        return $res;
    }
    function datosventa2(){
        $idVenta = $_POST['idVenta'];
        $res = $this->CajaModel->datosventa($idVenta);
        global $api_lite;
        if(!isset($api_lite)) echo json_encode($res);
        else return $res;
    }
    function formatofecha($fecha)
    {
        list($anio,$mes,$rest)=explode("-",$fecha);
        list($dia,$hora)=explode(" ",$rest);

        return $dia."/".$mes."/".$anio." ".$hora;
    }
    function configTikcet(){
        $res = $this->CajaModel->configTikcet();
        return $res;
    }
    function productosventa($idVenta){
        $res = $this->CajaModel->productosventa($idVenta);
        return $res;
    }
    function datosretiro($idRetiro){
        $res = $this->CajaModel->datosretiro($idRetiro);
        return $res;
    }
    function datosabono($idAbono){
        $res = $this->CajaModel->datosabono($idAbono);
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
    function eliminaDescuento(){
        $res = $this->CajaModel->eliminaDescuento();
        echo json_encode($res);
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
        $field = $_POST['field'];
        $result = $this->CajaModel->recalcula($idProducto,$cantidad,$precio, $field);

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
        //print_r($res);
        global $api_lite;
        if(!isset($api_lite)) echo json_encode($res);
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
    function obtenCorte($json = 0){
        $init = $_POST['desde'];
        $end = $_POST['hasta'];
        $onlyShow = $_POST['show'];
        $iduser=$_POST['user'];

        $resultado = $this->CajaModel->getCut($init, $end, $onlyShow, $iduser);
        if ($json === 0)
            echo json_encode($resultado);
        else
            return $resultado;
    }
    function eliminarPago() {
        $pago = $_POST['pago'];
        $resultado = $this->CajaModel->eliminarPago($pago);

        echo json_encode($resultado);
    }
    function getExisCara(){
        $caracteristicas = $_POST['a'];
        $idProducto = $_POST['producto'];
        //echo 'rrrrr'.$idProducto;
        $res = $this->CajaModel->getExisCara($idProducto,$caracteristicas);

        echo json_encode($res);
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
        $arqueo           = $_POST['arqueo'];
        $tipoCorte        = $_POST['tipoCorte'];
        //echo 'retiro_caja='.$retiro_caja;
        switch ($tipoCorte) {
            case '1': //Normal
                $resp = $this->CajaModel->crearCorteNormal($fecha_inicio,$fecha_fin,$saldo_inicial,$monto_venta,$saldo_disponible,$retiro_caja,$deposito_caja,$retiros, json_encode($arqueo));
                break;
            case '2': //Parcial
            $_SESSION['corteParcial']['inicial']=( isset($_SESSION['corteParcial']['inicial']) ) 
                    ? $_SESSION['corteParcial']['inicial'] 
                    : $fecha_inicio;
                $resp = $this->CajaModel->crearCorteParcial($fecha_inicio,$fecha_fin,$saldo_inicial,$monto_venta,$saldo_disponible,$retiro_caja,$deposito_caja,$retiros, json_encode($arqueo));
                break;
            case '3': // Z
                $_SESSION['corteParcial']['final'] = $fecha_inicio;

                $resp = $this->CajaModel->crearCorteParcial($fecha_inicio,$fecha_fin,$saldo_inicial,$monto_venta,$saldo_disponible,$retiro_caja,$deposito_caja,$retiros, json_encode($arqueo));

                $cortes = $this->CajaModel->cortesfiltrados($_SESSION['accelog_idempleado'], $_SESSION['corteParcial']['inicial'], $_SESSION['corteParcial']['final']);

                $saldo_inicial    = $cortes['cortes'][0]['saldoinicialcaja'];
                
                $fecha_inicio     = $_SESSION['corteParcial']['inicial'];
                $fecha_fin        = $_POST['fecha_fin'];
                $monto_venta      = 0.0;
                $retiro_caja      = 0.0;
                $deposito_caja    = 0.0;
                $retiros          = 0.0;
                $arqueo           = [];
                $tipoCorte        = 3;
                foreach ($cortes['cortes'] as $key => $value) {
                    $monto_venta      += $value['montoventa'];
                    $retiro_caja      += $value['retirocaja'];
                    $deposito_caja    += $value['abonocaja'];
                    $retiros          += $value['retiros'];
                }
                $saldo_disponible = $saldo_inicial + $monto_venta;

                $resp = $this->CajaModel->crearCorteZ($fecha_inicio,$fecha_fin,$saldo_inicial,$monto_venta,$saldo_disponible,$retiro_caja,$deposito_caja,$retiros, json_encode($arqueo));
                foreach ($cortes['cortes'] as $key => $value) {
                    $this->CajaModel->actulizaCampoZ( $value['idCortecaja'], $resp['idCorte'] );
                }

                unset( $_SESSION['corteParcial'] );
                break;
            default:
                # code...
                break;
        }
        //$resp = $this->CajaModel->crearCorte($fecha_inicio,$fecha_fin,$saldo_inicial,$monto_venta,$saldo_disponible,$retiro_caja,$deposito_caja,$retiros, json_encode($arqueo), $tipoCorte);
        

        echo json_encode($resp);
    }
    function ventasGrid(){
        $ventasGrid = $this->CajaModel->ventasGrid();
        $ventasIndex = $this->CajaModel->ventasIndex();
        $sucursales = $this->CajaModel->getSucursales();

	// Consulta las vias de contacto
        $vias_contacto = $this->CajaModel->listar_vias_contacto();
		$vias_contacto = $vias_contacto['rows'];

        $selectAlmacenes = $this->CajaModel->buscarAlmacenes();
        //print_r($ventasIndex);
        require('views/caja/ventas.php');
    }
    function comisionesGrid(){
        $ventasGrid = $this->CajaModel->comisionesGrid();
        $ventasIndex = $this->CajaModel->empleadosComision();
        $sucursales = $this->CajaModel->getSucursales();

    // Consulta las vias de contacto
        $vias_contacto = $this->CajaModel->listar_vias_contacto();
        $vias_contacto = $vias_contacto['rows'];

        //print_r($ventasIndex);
        require('views/caja/comisiones.php');
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
        $idSucursal = $_POST['sucursal'];
        $via_contacto = $_POST['via_contacto'];

        $res = $this->CajaModel->buscarVentas($cliente,$empleado,$desde,$hasta,$idSucursal, $via_contacto);

        echo json_encode($res);
    }
    function buscarComisiones(){
        $empleado = $_POST['empleado'];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $idSucursal = $_POST['sucursal'];

        $res = $this->CajaModel->buscarComisiones($empleado,$desde,$hasta,$idSucursal);

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
        $orderby = $_POST['orderby'];
        $idSucursal = $_POST['sucursal'];
        $idEmpleado = $_POST['empleado'];
        $cliente = $_POST['cliente'];

        $respG = $this->CajaModel->graficar($desde,$hasta,$orderby,$idSucursal,$idEmpleado,$cliente);

        echo json_encode($respG);
    }
    function graficarComision(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $idSucursal = $_POST['sucursal'];

        $respG = $this->CajaModel->graficarComision($desde,$hasta,$idSucursal);

        echo json_encode($respG);
    }
    function cortesGrid(){
        $cortes = $this->CajaModel->getCortes();
        $ventasIndex = $this->CajaModel->ventasIndex();

        foreach ($cortes['cortes'] as $key => $value) {
            if( !(is_null($value['corteZ']) || $value['corteZ'] == -1) ){
                unset( $cortes['cortes'][$key] );
            }
        }

        require('views/caja/corte.php');
    }
    function verCorte(){
        $idCorte = $_GET['idCorte'];
        $corteInfo = $this->CajaModel->saldosCorte($idCorte);
        $cortes = $this->CajaModel->getCortes();

        foreach ($cortes['cortes'] as $key => $value) {
            if( $value['corteZ'] != $idCorte ){
                unset( $cortes['cortes'][$key] );
            }
        }

        require('views/caja/verCorte.php');
    }

    function enviarRecibo(){
        $idVenta = $_POST['idVenta'];
        $correo = $_POST['correo'];

        $envio = $this->CajaModel->enviarRecibo($idVenta,$correo);

        echo json_encode($envio);
    }


    function enviarTicket(){
        $idVenta = $_POST['idVenta'];
        $correo = $_POST['correo'];
        $asunto = $_POST['asunto'];
        $mensaje = $_POST['mensaje'];
        $envio = $this->CajaModel->enviarTicket($idVenta,$correo,$asunto,$mensaje);

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

        foreach ($resul['cortes'] as $key => $value) {
            if( !(is_null($value['corteZ']) || $value['corteZ'] == -1) ){
                unset( $resul['cortes'][$key] );
            }
        }

        echo json_encode($resul);
    }
    function gridFacturas(){
        $limit = 'limit 0,100';
        $clientes = $this->CajaModel->ventasIndex();
        $facturas = $this->CajaModel->gridFacturas($limit);
        $conexion_acontia = $this->CajaModel->conexion_acontia();
        $conexion_acontia = $conexion_acontia->fetch_assoc();
        $sucUsus = $this->CajaModel->sucUsus();
        require('views/caja/reporteFacturas.php');
    }
    function buscarFacturas(){
        //$cliente = $_POST['cliente'];
        $desde = $_GET['desde'];
        $hasta = $_GET['hasta'];
        $tipo = $_GET['tipo'];
        $empleado = $_GET['empleado'];
        $sucursal = $_GET['sucursal'];

        $conexion_acontia = $this->CajaModel->conexion_acontia();
        $conexion_acontia = $conexion_acontia->fetch_assoc();
        $facturas = $this->CajaModel->buscarFacturas($desde,$hasta,$tipo,$empleado,$sucursal);

        //echo json_encode(['conexion_acontia' => $conexion_acontia , 'facturas' => $busca ]);
        require 'views/caja/listafacturas.php'; 

    }
    function muestraMasFact(){
        $ran = floatval($_POST['rango']);
        $limit = 'limit '.$ran.' , 100';

        $conexion_acontia = $this->CajaModel->conexion_acontia();
        $conexion_acontia = $conexion_acontia->fetch_assoc();
        $facturas = $this->CajaModel->muestraMasFact($limit);

        //echo json_encode(['conexion_acontia' => $conexion_acontia , 'facturas' => $facturas ]);
        require 'views/caja/listafacturas.php'; 
    }

    //Funcion que verifica si la factura tiene una poliza de pagos y suma los importes
    function sumaImportesFacturas($uuid)
    {
        return $this->CajaModel->sumaImportesFacturas($uuid);
    }
    
    //cambia el estatus del pedido
    function estatusPedido(){
        $idPedido = $_POST['idPedido'];
        $idVenta = $_POST['idVenta'];

        $ress = $this->CajaModel->estatusPedido($idPedido,$idVenta);

        echo json_encode($ress);
    }
    function cancelaFactura(){
        $id = $_POST['id'];

        $res = $this->CajaModel->cancelaFactura($id);
        echo $res;
    }
    function cancelaFacturaEstatus(){
        $id = $_REQUEST['id'];
        
        $res = $this->CajaModel->cancelaFacturaEstatus($id);

        echo json_encode($res);

    }
    
    function buscarVentasPendientes(){
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $empleado = $_POST['empleado'];
        $sucursal = $_POST['sucursal'];
        $busca = $this->CajaModel->buscarVentasPendientes($desde,$hasta,$empleado,$sucursal);

        echo json_encode($busca);
    }
    function pendienteFacturar(){
        $clientes = $this->CajaModel->ventasIndex();
        $ventas = $this->CajaModel->gridPendienteFact();
        $rfcs = $this->CajaModel->comunFactRfcs();
        $seriesCfdi = $this->CajaModel->seriesCfdi();
        $configDatos = $this->CajaModel->configDatos();
        $sucUsus = $this->CajaModel->sucUsus();
        require('views/caja/pendienteFactura.php');
    }
    function factPendiente(){
        $cliente = $_POST['cliente'];
        $id = $_POST['id'];
        $obser = $_POST['obser'];
        $serie = $_POST['serie'];
        
        $respFact = $this->CajaModel->factPendiente($id,$cliente,$obser,$serie);

        echo json_encode($respFact);

    }
    function clientePenFac(){
        $id = $_POST['id'];


        $resp = $this->CajaModel->clientePenFac($id);
        echo json_encode($resp);
    }
    function obtenCaracteristicas(){
        $idProducto = $_POST["id"];
        $cantidadInicial = $_POST["cantidad"];

        $car = $this->CajaModel->obtenCaracteristicas($idProducto);
        //print_r($car); exit();
        echo json_encode($car);
    }
    function productosMoneda(){
        $moneda = $_POST['coin'];

        $prods = $this->CajaModel->productosMoneda($moneda);

        echo json_encode($prods);
    }
    function allfs(){
        $id = $_POST['id'];
        $res = $this->CajaModel->allfs($id);
    }
    function getInfoProducto(){
        $id = $_POST['id'];
        $res = $this->CajaModel->getInfoProducto($id);

        echo json_encode($res);

    }
    function cambiaCantidad() {
        $idProducto = $_POST["id"];
        $descuento = $_POST["cantidad"];
        $tipo = $_POST["tipo"];
        $nombre = $_POST['nombre'];

        $resultado = $this->CajaModel->cambiaCantidad($idProducto, $descuento, $tipo,$nombre);
        //echo $precionuevo;
        echo json_encode($resultado);
    }
    function rFac(){
        $uuid = $_POST['uuid'];
        $email = $_POST['email'];
        $cuerpoMsg = $_POST['msg'];

        $res = $this->CajaModel->rFac($uuid,$email,$cuerpoMsg);

        echo json_encode($res);
    }

    function origenPac(){
        $id = $_POST['id'];
        $res = $this->CajaModel->origenPac($id);

        echo json_encode($res);
    }
    function enviaCortePdf(){
        $idCorte = $_POST['idCorte'];
        $saldos = $this->saldosCorte($idCorte);
        $resumenCorte = $this->imprimeCorte($idCorte);

        include "../SAT/PDF/html2pdf/html2pdf.class.php";
        $pdf = new HTML2PDF('P', 'A4', 'fr');

        include 'views/caja/cortePdf.php';

        $this->CajaModel->enviaCorteCaja($idCorte,$contenido);

    }
    function verAcuse(){
        $id = $_POST['id'];
        $resp = $this->CajaModel->verAcuse($id);

        echo json_encode($resp);
    }
    function enviarAcuse(){
        $idFact = $_POST['idFact'];
        $correo = $_POST['correo'];
        $imprime = $_POST['imprime'];
        $resp = $this->CajaModel->enviarAcuse($idFact,$correo,$imprime);

        echo json_encode($resp);
    }
    function checatarjetaregalo()
    {
        $numero = $_POST['numero'];
        $monto = $_POST['monto'];

        $resultado = $this->CajaModel->checatarjetaregalo($numero,$monto);

        echo json_encode($resultado);
    }
    function gridTarjetasRegalo(){

        $tarjetas = $this->CajaModel->gridTarjetasRegalo();
        $clientes = $this->CajaModel->buscaClientes("");
        $estados = $this->CajaModel->estados();
        $listaPre = $this->CajaModel->listaPrecios();
        $moneda = $this->CajaModel->moneda();
        $municipios = $this->CajaModel->munici();
        require('views/caja/gridTarjetasRegalos.php');

    }
    function guardarTarjeta(){
        $idCard = $_POST['idCard'];
        $numero = $_POST['numero'];
        $monto = $_POST['monto'];
        $puntos = $_POST['puntos'];
        $cliente = $_POST['cliente'];

        if($idCard!=''){
            $resp = $this->CajaModel->modificaTarjeta($idCard, $numero, $monto, $puntos, $cliente);
        }else{
            $resp = $this->CajaModel->guardarTarjeta($numero,$monto,$puntos,$cliente);
        }
        echo json_encode($resp);
    }
    function desactivaGift(){
        $id = $_POST['idGiftCard'];

        $resp = $this->CajaModel->desactivaGift($id);

        echo json_encode($resp);
    }
     function activaGift(){
        $id = $_POST['idGiftCard'];

        $resp = $this->CajaModel->activaGift($id);

        echo json_encode($resp);
    }
    function verTarjeta(){
        $id = $_POST['idCard'];

        $resp = $this->CajaModel->verTarjeta($id);

        echo json_encode($resp);
    }
    function configDatos(){
        $res = $this->CajaModel->configDatos();

        echo json_encode($res);
    }
    function dowloadZip(){
        $cadena = $_POST['cadena'];
        unlink('../facturas/notas/facturas.zip');
        $zip = new ZipArchive;
        $zip_path = "../facturas/notas/facturas.zip";
          if ($zip->open($zip_path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
            die ("An error occurred creating your ZIP file.");
          }
        $uuids = explode("*", $cadena);
        foreach ($uuids as $key => $value) {
            $zip->addFile('../facturas/'.$value.'.xml');
            $zip->addFile('../facturas/'.$value.'.pdf');
            $zip->addFile('../cont/xmls/facturas/temporales/'.$value.'.xml');
        }

        $zip->close();
        echo json_encode(array('estatus' => 1));
    }
    function infoFact(){
        $id = $_POST['id'];
        $re = $this->CajaModel->infoFact($id);

        echo json_encode($re);
    }
    function creaNota(){
        $monto = $_POST['monto'];
        $montosiniva = $_POST['montosiniva'];
        $iva = $_POST['iva'];
        $total = $_POST['total'];
        $idFac = $_POST['idFac'];

        $res = $this->CajaModel->creaNota($monto,$montosiniva,$iva,$total,$idFac);
        echo json_encode($res);
    }
    function guardaNota(){
        $res = $this->CajaModel->guardaNota($_POST["UUID"],$_POST["noCertificadoSAT"],$_POST["selloCFD"],$_POST["selloSAT"],$_POST["FechaTimbrado"],$_POST["idComprobante"],$_POST["idFact"],$_POST["idVenta"],$_POST["noCertificado"],$_POST["tipoComp"],$_POST["monto"],$_POST["cliente"],$_POST["trackId"],$_POST["idRefact"],$_POST["azurian"],$_POST["total"],$_POST['idFacRela']);

        echo json_encode($res);
    }
    function aplicaCortesiaPP(){
        $idProducto = $_POST['idProducto'];

        $res = $this->CajaModel->cambiaCantidad($idProducto,'100','C');
        echo json_encode($res);
    }
    function aplicaCortesiaGeneral(){
        $res = $this->CajaModel->aplicaCortesiaGeneral();
        echo json_encode($res);
    }
    function obtenTotal(){
        $arrayName = array('total' => $_SESSION['caja']['cargos']['total'] );
        echo json_encode($arrayName);
    }

///////////////// ******** ---- 				listar_comandas				------ ************ //////////////////
//////// Trasforma el objeto para consultar a la BD y cargar los resultados a la vista
	// Como parametros puede recibir:

	function listar_comandas($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las comandas y las regresa en un array
		$comandas = $this ->CajaModel->listar_comandas_pendientes($objeto);
		$resp['comandas'] = $comandas;

	// Valida si existen comandas
		if ($comandas['total'] > 0) {
			if ($objeto['json'] == 1) {
				$resp['status'] = 1;
				echo json_encode($resp);
			}else{
				$comandas = $comandas['rows'];
				require ('views/caja/listar_comandas.php');
			}
		} else {
			if ($objeto['json'] == 1) {
				$resp['status'] = 2;
				echo json_encode($resp);
			}
		}
	}

///////////////// ******** ---- 			FIN listar_comandas				------ ************ //////////////////

///////////////// ******** ---- 				tipo_cambio					------ ************ //////////////////
//////// Consulta el tipo de cambio y lo devuelve
	// Como parametros puede recibir:
		// moneda -> ID de la moneda

	function tipo_cambio($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las comandas y las regresa en un array
		$tipo_cambio = $this -> CajaModel -> listar_tipo_cambio($objeto);
		$tipo_cambio = $tipo_cambio['rows'][0]['tipo_cambio'];

		return $tipo_cambio;
	}

///////////////// ******** ---- 				FIN tipo_cambio				------ ************ //////////////////

///////////////// ******** ---- 			listar_ajustes_foodware			------ ************ //////////////////
//////// Consulta los ajustes de Foodware y los devuelve en un array
	// Como parametros puede recibir:

	function listar_ajustes_foodware($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las comandas y las regresa en un array
		$ajustes_foodware = $this -> CajaModel -> listar_ajustes_foodware($objeto);
		$ajustes_foodware = $ajustes_foodware['rows'][0];

		return $ajustes_foodware;
	}

///////////////// ******** ---- 		FIN listar_ajustes_foodware			------ ************ //////////////////

///////////////// ******** ---- 		listar_detalles_comanda				------ ************ //////////////////
//////// Consulta los datos de la comanda y los devuelve en un array
	// Como parametros puede recibir:
		// id_venta -> ID de la venta

	function listar_detalles_comanda($objeto) {
	// Si el objeto viene vacio(llamado desde el index) se le asigna el $_REQUEST que manda el Index
	// Si no conserva su valor normal
		$objeto = (empty($objeto)) ? $_REQUEST : $objeto;

	// Consulta las comandas y las regresa en un array
		$listar_detalles_comanda = $this -> CajaModel -> listar_detalles_comanda($objeto);
		$listar_detalles_comanda = $listar_detalles_comanda['rows'][0];

		return $listar_detalles_comanda;
	}

///////////////// ******** ---- 		FIN listar_detalles_comanda			------ ************ //////////////////

    function obtenerConfigVenta() {
        return $this->CajaModel->obtenerConfigVenta();
    }

    function obtenerGarantiaVenta() {
        $idVenta = $_GET['idVenta'];
        echo  json_encode( $this->CajaModel->obtenerGarantiaVenta( $idVenta ) );
    }

    function reclamarGarantia() {
        $datos = array("idVenta" => $_POST['idVenta'], 
        				"idAlmacen" => $_POST['idAlmacen'], 
        				"comentario"  => $_POST['comentario'], 
        				"tablaVentaProducto" => $_POST['tablaVentaProducto']
				);

        echo  json_encode( $this->CajaModel->reclamarGarantia( $datos['idVenta'] , $datos['idAlmacen'] , $datos['comentario'] , $datos['tablaVentaProducto'] ) );
    }

    function productosEnGarantia() {

        echo  json_encode( $this->CajaModel->productosEnGarantia( $_GET['id'] ) );
    }

    function detalleMovimientoGarantia() {
        echo  json_encode( $this->CajaModel->detalleMovimientoGarantia( $_GET['idVentaProducto'] ) );
    }

    function atenderMovimientoGarantia() {
        echo  json_encode( $this->CajaModel->atenderMovimientoGarantia( $_POST['idVentaProducto'] ) );
    }

    function arqueoCaja() {
        echo json_encode( $this->CajaModel->arqueoCaja( json_encode($_POST) ) );
    }

    function obtenerArqueoCaja() {
        echo  $this->CajaModel->obtenerArqueoCaja( $_POST['idCorte']) ;
    }

    function productosDevueltos() {
        echo  json_encode( $this->CajaModel->productosDevueltos( $_GET['id'] ) );
    }

    function detalleMovimientoDevueltos() {
        echo  json_encode( $this->CajaModel->detalleMovimientoDevueltos( $_GET['idVentaProducto'] ) );
    }

    function devolucion() {
        $datos = array("idVenta" => $_POST['idVenta'], 
                        "idAlmacen" => $_POST['idAlmacen'], 
                        "comentario"  => $_POST['comentario'], 
                        "tablaVentaProducto" => $_POST['tablaVentaProducto'],
                        "subtotal" => $_POST['subtotal'],
                        "total" => $_POST['total']
                );

        echo  json_encode( $this->CajaModel->devolucion( $datos['idVenta'] , $datos['idAlmacen'] , $datos['comentario'], $datos['subtotal'], $datos['total'] , $datos['tablaVentaProducto'] ) );
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

    function productos() {
        $this->CajaModel->productos( $_GET['departamento'] , $_GET['familia'] , $_GET['linea']);
    }

    function change_status($objeto) {
        $objeto = (empty($objeto)) ? $_REQUEST : $objeto;

        $res = $this->CajaModel->change_status($objeto);
        if ($res) {
            $data['status'] = 1;
            echo json_encode($data);
        } else {
            $data['status'] = 2;
            echo json_encode($data);
        }
    
    }

    function guardar_tipo_pago($objeto) {
        $objeto = (empty($objeto)) ? $_REQUEST : $objeto;

        $res = $this->CajaModel->guardar_tipo_pago($objeto);
        if ($res) {
            $data['status'] = 1;
            $data['id'] = $res;
            echo json_encode($data);
        } else {
            $data['status'] = 2;
            echo json_encode($data);
        }
    
    }

    function editar_tipo_pago($objeto) {
        $objeto = (empty($objeto)) ? $_REQUEST : $objeto;

        $res = $this->CajaModel->editar_tipo_pago($objeto);
        if ($res) {
            $data['status'] = 1;
            echo json_encode($data);
        } else {
            $data['status'] = 2;
            echo json_encode($data);
        }
    
    }

    function delete_tipo_pago($objeto) {
        $objeto = (empty($objeto)) ? $_REQUEST : $objeto;

        $res = $this->CajaModel->delete_tipo_pago($objeto);
        if ($res) {
            $data['status'] = 1;
            echo json_encode($data);
        } else {
            $data['status'] = 2;
            echo json_encode($data);
        }
    
    }
    /*
    function gs1(){
        $codigoB = $_POST['id'];

        header("Access-Control-Allow-Origin: *");

        $authorization = 'Authorization: Bearer dad9d500-05c3-11e7-a925-8b1470d1c8c2';
        $url = 'https://api.mexico.q-aggregator.com/product?gtin='.$codigoB.'&f=j'; 
        $version = "Accept-version: 1.4.0";

        //initiaize
        $ch = curl_init();  
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
    
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization, $version));               
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        //execute 
         $json = curl_exec($ch); 
         
         $json = json_decode($json, true);
         $status        = $json['status'];
         $nombre        = $json['data']['query_by_gtin_response:queryByGtinResponse']['productData']['productDataRecord']['module'][0]['bpi:basicProductInformationModule']['productName']['value'];
         $proveedor     = $json['data']['query_by_gtin_response:queryByGtinResponse']['productData']['informationProviderName'];
         $nameContact   = $json['data']['query_by_gtin_response:queryByGtinResponse']['productData']['productDataRecord']['module'][0]['bpi:basicProductInformationModule']['packagingSignatureLine']['partyContactName']['value'];
         $unidad        = $json['data']['query_by_gtin_response:queryByGtinResponse']['productData']['productDataRecord']['module'][2]['pqi:productQuantityInformationModule']['netContent']['measurementUnitCode'];
         $neto          = $json['data']['query_by_gtin_response:queryByGtinResponse']['productData']['productDataRecord']['module'][2]['pqi:productQuantityInformationModule']['netContent']['value'];

         $product[] = array(
                                status          => $status,
                                id              => $codigoB,
                                nombre          => $nombre,
                                proveedor       => $proveedor,
                                nameContact     => $nameContact,
                                unidad          => $unidad,
                                neto            => $neto,
                        );

         echo json_encode($product);
         
        if($json === false){
            echo curl_error($ch); exit();
        }

        //close curl session / free resources
        curl_close($ch);
        //decode the json string into an array
        //$json = json_decode($json, true);
    }
    function save_gs1(){
        session_start();
        $instancia = $_SESSION['accelog_nombre_instancia'];
        
        $codigo = $_POST['codigo'];
        $result = $_POST['result'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $desc   = $_POST['desc'];

        $result = $this->CajaModel->save_gs1($codigo,$result,$nombre,$precio,$desc,$instancia);
        echo json_encode($result);
    }
    */

    /*function guardaMerma(){
        $productos = $_POST['productos'];

        $rest = $this->InventarioModel->guardaMerma($productos);
        echo json_encode($rest);
    }*/

    function guardaMerma() {
        $datos = array("idVenta" => $_POST['idVenta'], 
                        "idAlmacen" => $_POST['idAlmacen'], 
                        "comentario"  => $_POST['comentario'], 
                        "tablaVentaProducto" => $_POST['tablaVentaProducto'],
                        "subtotal" => $_POST['subtotal'],
                        "total" => $_POST['total']
                );
$idAlmacen = $this->CajaModel->obtenAlm();
        echo  json_encode( $this->CajaModel->guardaMerma( $datos['idVenta'] , $idAlmacen , $datos['comentario'], $datos['subtotal'], $datos['total'] , $datos['tablaVentaProducto'] ) );
    }

    function obtenerSeriesYLotes(){
        echo json_encode( $this->CajaModel->obtenerSeriesYLotes( $_GET['idVentaProducto'] ) );
    }

    /* ==== MOD CHRIS - tipo de documento === */
    function editar_tipo_documento(){
        $td=trim($_POST['td'],',');
        $this->CajaModel->saveTipoDocumento($td);
    }
    /* ==== FIN MOD === */

    function buscarFacturasCliente(){
        $cliente = $_POST['cliente'];
        $desde = $_POST['desde'];
        $hasta = $_POST['hasta'];
        $tipo = $_POST['tipo'];

        $busca = $this->CajaModel->buscarFacturasCliente($cliente,$desde,$hasta,$tipo);

        echo json_encode($busca);
    }

    function relacion_polizas_facturas(){
        $uuid = $_POST['uuid'];
        $Result = $this->CajaModel->relacion_facturas_pagos($uuid);
        $arr = array();
        for ($i=0; $i <= ($Result->num_rows-1); $i++) { 
            $arr[$i] = $Result->fetch_assoc();
        }
        echo json_encode($arr);
    }
    function creaCxC(){
        $idVenta = $_POST['idVenta'];
        $res = $this->CajaModel->creaCxC($idVenta);
        echo json_encode($res);
    }


    //////////ch@ ////////////////
    /*
        function preticket(){

            $organizacion = $this->CajaModel->datosorganizacion();
            echo json_encode($organizacion);

            //Consulta los campos para mostrar en el tickect seleccionado en configuración
            $que_mostrar = $this -> CajaModel -> get_que_mostrar_ticket();
            //Consulta la organizacion
            

            //echo json_encode($que_mostrar);
            //echo json_encode($organizacion);

            session_start();
            $caja = $_SESSION['caja'];
            //$res = $_SESSION;
            $html = '';
            $html .= require('preticket.php');
            $html .= '<h1>es para pasar html</h1>';
            
            echo $html;
        }
    */
    //////////ch@ fin ////////////////


    function tipodecambio(){
        $moneda = $_POST['moneda'];

        $res = $this->CajaModel->tipodecambio($moneda);

        echo json_encode($res);
    }
    function validaClienteFact(){
        $cliente = $_POST['cliente'];
        $venta = $_POST['venta'];
        $res = $this->CajaModel->validaClienteFact($cliente,$venta);

        echo json_encode($res);
    }
    function guardarPolitica(){
        $tipo = $_POST['tipo'];
        $dinero = $_POST['dinero'];
        $porcentaje = $_POST['porcentaje'];
        $puntos = $_POST['puntos'];
        $nombre = $_POST['nombreP'];
        //echo $dinero;

        $res = $this->CajaModel->guardarPolitica($tipo,$dinero,$porcentaje,$puntos,$nombre);
        echo json_encode($res);
    }
    function getPointsCard(){
        $numTarjeta = $_POST['numTarjeta'];
        $res = $this->CajaModel->getPointsCard($numTarjeta);
        echo json_encode($res);
    }

    function puedeDevolverCancelar() {
        echo json_encode(  $this->CajaModel->puedeDevolverCancelar() );
    }

    function autorizacionDevolverCancelar() {
        $pass = $_GET['password'];
        echo json_encode(  $this->CajaModel->autorizacionDevolverCancelar( $pass ) );
    }
    function ventasFact(){
          $idVenta = $_POST['id'];
        echo json_encode(  $this->CajaModel->ventasFact2( $idVenta ) );
    }
    function comprobantesPago(){
        $cliprov = 1;
        $idCliente=1;
        $xmlDocRelaciondos = "<pago10:Pago FechaPago='2017-11-10T12:00:00' FormaDePagoP='03' MonedaP='MXN' Monto='1989.68'><pago10:DoctoRelacionado IdDocumento='BACBCC9A-F9C4-4E28-BDE4-6F34B0D6399C' Folio='49505' MonedaDR='MXN' MetodoDePagoDR='PPD' NumParcialidad='1' ImpSaldoAnt='1989.68' ImpPagado='1989.68' ImpSaldoInsoluto='0.0' /></pago10:Pago>";
        $cadoriDocRelaciondos = '1.0|2017-11-10T12:00:00|03|MXN|1989.68|BACBCC9A-F9C4-4E28-BDE4-6F34B0D6399C|49505|MXN|PPD|1|1989.68|1989.68|0.0';
        //$xmlDocRelaciondos = "<pago10:Pago FechaPago='2017-08-22T14:37:50' FormaDePagoP='01' MonedaP='MXN' Monto='1' />";
        //$cadoriDocRelaciondos = '1.0|2017-08-22T14:37:50|01|MXN|1';
        $idCliente = $_POST['idcliprov'];
        $cliprov = $_POST['cliprov'];
        $xmlDocRelaciondos = $_POST['doctorel'];
        $cadoriDocRelaciondos = '1.0|'.$_POST['cadena'];        
        $this->CajaModel->comprobantesPago($idCliente,$xmlDocRelaciondos,$cadoriDocRelaciondos,$cliprov);
    }
    function pdf33(){
        
    $uid = $_POST['uid'];
    //echo $uid.')';
    require ('../wsinvoice/config_api.php');
    require ('../wsinvoice/lib/fpdf.php');
    require ('../wsinvoice/lib/QRcode.php');
    require ('../wsinvoice/class.invoice.pdf.php');
    
    //echo 2;
    // Recordatorio: Mudar archivo a controlador.
    $caja = 3;
    $obser = '';
    $path = "../";
    //$path = "../../../../../mlog/webapp/modulos/cont/";
    if(isset($_COOKIE['inst_lig']))
        $path = "../../../../../".$_COOKIE['inst_lig']."/webapp/modulos/cont/";
    if (isset($_REQUEST['dir'])) {
        $data = $path.$_REQUEST['dir'];
    } else {
        $data = $path.'xmls/facturas/temporales/'.$_REQUEST['name'];
    }
    

    
    //var_dump($logo);
    if(file_exists($logo)){
      $logo = $_POST['logo'];
    } else {
      $logo = '../../';
    }
    if($caja==1){
        $logo = '../../../../netwarelog/archivos/1/organizaciones/'.$_POST['logo'];
        //echo $logo;
    }else{
        $logo = '';
    }
    //echo 'sss'.$_REQUEST['logo'];
    if($_POST['logo']!='logo.png' && $_POST['logo']!='' ){
        $logo = '../../../../netwarelog/archivos/1/organizaciones/'.$_POST['logo'];
    }else{
        $logo = '';
    }
    //echo $logo;
    
    //Si no existe en temporales buscara en las carpetas de con id de polizas
    if(!file_exists($data)){
      $data = $path.'xmls/facturas/'.$_REQUEST['id'].'/'.$_REQUEST['name'];
    }

    //Si no existe en temporales buscara en las carpeta de documentos bancarios en su respectiva id
    if (!file_exists($data)) {
      $data = $path."xmls/facturas/documentosbancarios/".$_REQUEST['id']."/".$_REQUEST['name'];
    }
    //../xmls/facturas/temporales/388C67CE-EEBA-4C86-A680-C71E46247BB3.xml
    $data = '../cont/xmls/facturas/temporales/'.$uid.'.xml';



    // Recordatorio: Hacer que el rgb se pueda escoger
    // Color actual en hex: #03a9f4, Azul cuadros netwarmonitor logo.
    $intRed = 3;
    $intGreen = 139;
    $intBlue = 204;
    $strPDFFile = "muestra.pdf";
    if($_REQUEST['nominas']==1){
        $namexml = $_REQUEST['name'];
    }else{
        $namexml = "";
    }
    $namexml = $uid.'.xml';
    //echo 3;
    //echo($logo. "<br>");
    //echo $data;
    $logo = '';
    //echo '1';

    $objXmlToPDf = new invoiceXmlToPdf($data, $logo, $intRed, $intGreen, $intBlue, $strPDFFile,$namexml,$caja,$obser);
    //echo '2SSSS';
//echo 4;
    $objXmlToPDf->genPDF();
    //echo '4aaaaaa';

    }

    function gridComplementosDePago(){
       
        $limit = 'limit 0,100';
        //$clientes = $this->CajaModel->ventasIndex();
        $facturas = $this->CajaModel->gridFacturasComplementosPago($limit);


        //$conexion_acontia = $this->CajaModel->conexion_acontia();
        //$conexion_acontia = $conexion_acontia->fetch_assoc();
        
        $sucUsus = $this->CajaModel->sucUsus();
        


        require('views/caja/reporteComplementosDePago.php');
    }

    function muestraMasFactComplementosPago(){
        $ran = floatval($_POST['rango']);
        $limit = 'limit '.$ran.' , 100';

        //$conexion_acontia = $this->CajaModel->conexion_acontia();
        //$conexion_acontia = $conexion_acontia->fetch_assoc();
        $facturas = $this->CajaModel->muestraMasFactComplementosPago($limit);

        //echo json_encode(['conexion_acontia' => $conexion_acontia , 'facturas' => $facturas ]);
        require 'views/caja/listaComplementosDePago.php'; 
    }

    function buscarFacturasComPag(){
        //$cliente = $_POST['cliente'];
        $desde = $_GET['desde'];
        $hasta = $_GET['hasta'];
        $empleado = $_GET['empleado'];
        $sucursal = $_GET['sucursal'];

        //$conexion_acontia = $this->CajaModel->conexion_acontia();
        //$conexion_acontia = $conexion_acontia->fetch_assoc();
        $facturas = $this->CajaModel->buscarFacturasComPag($desde,$hasta,$empleado,$sucursal);

        //echo json_encode(['conexion_acontia' => $conexion_acontia , 'facturas' => $busca ]);
        require 'views/caja/listaComplementosDePago.php'; 

    }

}

?>
