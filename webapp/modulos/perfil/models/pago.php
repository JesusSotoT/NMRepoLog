<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    //require("models/connection.php"); // funciones mySQL 
    require_once dirname(__DIR__) .'/models/connection_sqli_manual.php'; // funciones mySQLi 
    //ini_set("display_errors",1); error_reporting(E_ALL);
    include_once dirname(__DIR__) .'/models/srpago.php';
    include_once dirname(__DIR__) .'/models/paypal.php';
    include_once '../../libraries/srpago/srpago.php';
    include_once '../../libraries/paypal/paypal.php';

    class PagoModel extends Connection {

        function __construct()
        {
            $this->connect(true);
        }

        public function grid()
        {
            $registros = array();

            $sql = "SELECT id AS customer, idclient AS usuario FROM customer WHERE instancia = '". $_SESSION["accelog_nombre_instancia"] ."';";
            $sql = $this->queryArray($sql);
            $sql = "
                    (SELECT pago.id, pago.referencia, 'Tarjeta' AS metodo, 1 AS tipo, pago.total, pago.creado FROM pago_srpago AS pago INNER JOIN venta_producto AS venta ON venta.idclient = pago.id_customer WHERE pago.id_customer = ". $sql["rows"][0]["usuario"] ." AND venta.idcustomer = ". $sql["rows"][0]["customer"] ." AND pago.pagado = 1 GROUP BY pago.referencia ORDER BY pago.id DESC)
                     UNION 
                    (SELECT pago.id, pago.referencia, 'Paypal' AS metodo, 2 AS tipo, pago.total, pago.creado FROM pago_paypal AS pago INNER JOIN venta_producto AS venta ON venta.idclient = pago.id_customer WHERE pago.id_customer = ". $sql["rows"][0]["usuario"] ." AND venta.idcustomer = ". $sql["rows"][0]["customer"] ." AND pago.pagado = 1 GROUP BY pago.referencia ORDER BY pago.id DESC);
                ";
            /*$sql = "
                    (SELECT pago.id, pago.referencia, 'Tarjeta' AS metodo, 1 AS tipo, pago.total, pago.creado FROM pago_srpago AS pago INNER JOIN venta_producto AS venta ON venta.idclient = pago.id_customer WHERE pago.pagado = 1 GROUP BY pago.referencia ORDER BY pago.id DESC)
                     UNION 
                    (SELECT pago.id, pago.referencia, 'Paypal' AS metodo, 2 AS tipo, pago.total, pago.creado FROM pago_paypal AS pago INNER JOIN venta_producto AS venta ON venta.idclient = pago.id_customer WHERE pago.pagado = 1 GROUP BY pago.referencia ORDER BY pago.id DESC);
                ";*/
            $sql = $this->queryArray($sql);
            foreach ($sql["rows"] as &$pago) {
                $pago = (object) $pago;
                $item = array();
                $item[0] = $pago->creado;
                $item[1] = $pago->referencia;
                $item[2] = $pago->metodo;
                $item[3] = "$". number_format($pago->total, 2);
                $item[4] = "<a href='javascript:mostrarProductos(\"". $pago->id ."\",\"". $pago->tipo ."\");'><i class='fa fa-address-book'></i></a>";
                $registros[] = $item;
            }

            return array("status" => true, "registros" => $registros);
        }

        public function productos($pago, $tipo)
        {
            $registros = array();

            $sql = "SELECT producto.appname AS producto, producto.version, venta.tipo AS periodo, venta.precio FROM venta_producto AS venta INNER JOIN appdescrip AS producto ON producto.idapp = venta.idapp WHERE ". (($tipo == 1) ? "venta.id_pago_srpago" : "venta.id_pago_paypal") ." = ". $pago .";";
            $sql = $this->queryArray($sql);
            foreach ($sql["rows"] as $producto) {
                $producto = (object) $producto;
                $item = array();
                $item[0] = $producto->producto;
                $item[1] = $producto->version;
                $item[2] = ucfirst($producto->periodo);
                $item[3] = "$". number_format($producto->precio, 2);
                $registros[] = $item;
            }

            return array("status" => true, "registros" => $registros);
        }

        public function obtenerPagoPendiente($resumido = true)
        {
            $sql = "SELECT id AS customer, idclient AS usuario FROM customer WHERE instancia = '". $_SESSION["accelog_nombre_instancia"] ."';";
            $sql = $this->queryArray($sql);
            $cliente = $sql["rows"][0]["usuario"];
            $customer = $sql["rows"][0]["customer"];
            $sql = "SELECT p.appname AS producto, p.version AS version, i.version AS periodo, p.costomensual AS precio_mensual, p.costoanual AS precio_anual, i.amount AS precio, i.limitdate, i.initdate, i.id AS id_venta, i.idapp, p.idAnual, p.idMensual FROM appclient AS i INNER JOIN appdescrip AS p ON p.idapp = i.idapp WHERE i.idclient = ". $cliente ." AND i.idcustomer = ". $customer ." AND i.limitdate < DATE(NOW());";
            $sql = $this->queryArray($sql);
            $productos = $sql["rows"];
            foreach ($productos as &$producto) {
                $inicial = new DateTime($producto["initdate"]);
                $final = new DateTime($producto["limitdate"]);
                $diferencia = $inicial->diff($final)->days;
                if(is_null($producto["periodo"])) {
                    $producto["periodo"] = ($diferencia <= 31) ? "mensual" : "anual";
                }
                if(is_null($producto["precio"])) {
                    $tipo = "precio_". $producto["periodo"];
                    $producto["precio"] = $producto[$tipo];
                }
                $producto["id"] = ($producto["periodo"] == "mensual") ? $producto["idMensual"] : $producto["idAnual"];
                unset($producto["idMensual"]);
                unset($producto["idAnual"]);
                unset($producto["precio_mensual"]);
                unset($producto["precio_anual"]);
                if($resumido){
                    $producto["periodo"] = ucfirst($producto["periodo"]);
                    $producto["precio"] = "$" . number_format($producto["precio"], 2);
                    unset($producto["id"]);
                    unset($producto["idapp"]);
                    unset($producto["limitdate"]);
                    unset($producto["initdate"]);
                    unset($producto["id_venta"]);
                    $producto = array_values($producto);
                }
            }
            $respuesta = array("status" => true, "registros" => $productos);
            if(!$resumido){
                unset($respuesta["status"]);
                $respuesta["productos"] = $respuesta["registros"];
                unset($respuesta["registros"]);
                $respuesta["cliente"] = $cliente;
                $respuesta["customer"] = $customer;
            }
            return $respuesta;
        }

        public function pagar($tipo, $tarjeta = null, $request = null)
        {
            $resumen = $this->obtenerPagoPendiente(false);
            if($tipo == 1){
                $srpago = new SrPagoModel();
                $pago = $srpago->pagar($resumen, $tarjeta);
            }else{
                $paypal = new PaypalModel();
                if(!is_null($request)) {
                    $pago = $paypal->pagar(array_merge($resumen, $request));
                } else {
                    $pago = $paypal->crearTicket($resumen);
                }
            }
            return $pago;
        }

    }

?>