<?php

    //ini_set('display_errors', 1);
    //error_reporting(E_ALL);
    //Carga la funciones comunes
    require_once('commonapi.php');
    //Carga el modelo para este controlador
    require_once("models/cajaapi.php");

    class Cajaapi extends Commonapi
    {
        public static $OBTENERRESUMENVENTA = array(
                "ver_descuento" => array("nulo" => true, "vacio" => false, "tipo" => "boolean"),
                "ver_selectores" => array("nulo" => true, "vacio" => false, "tipo" => "boolean"),
                "descuento" => array("nulo" => true, "vacio" => false, "tipo" => "decimal"),
                "venta" => array("nulo" => false, "vacio" => false, "tipo" => "json"),
                "identificador" => array("nulo" => false, "vacio" => false, "tipo" => "string")
                );
        public static $ELIMINARVENTA, $ENVIARTICKET = array("id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));
        public static $OBTENERVENTA = array(
                "id" => array("nulo" => true, "vacio" => false, "tipo" => "entero"),
                "tipo" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                );
        public static $GUARDARVENTA = array(
                "descuento" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                "cliente" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "tipo_pago" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "comentarios" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                "referencia" => array("nulo" => false, "vacio" => true, "tipo" => "string"),
                "cambio" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                "pago" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                "venta" => array("nulo" => false, "vacio" => false, "tipo" => "json"),
                "sucursal" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "token_venta" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "facturacion" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "comprobante" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                );
        public static $FACTURARVENTA = array(
                "cliente" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "pago" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                "venta" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "facturacion" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "comprobante" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                );
        public static $ENVIARFACTURA = array(
                "uuid" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "correo" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "azurian" => array("nulo" => false, "vacio" => false, "tipo" => "json"),
                "comprobante" => array("nulo" => false, "vacio" => false, "tipo" => "entero")
                );
        public static $SUSPENDERVENTA = array(
                "venta" => array("nulo" => false, "vacio" => false, "tipo" => "json"),
                "cliente" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "documento" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "impuestos" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                "monto" => array("nulo" => false, "vacio" => false, "tipo" => "decimal"),
                "sucursal" => array("nulo" => false, "vacio" => false, "tipo" => "entero"),
                "nombreCliente" => array("nulo" => false, "vacio" => false, "tipo" => "string"),
                "descuento" => array("nulo" => false, "vacio" => false, "tipo" => "decimal")
                );
        public static $LISTARVENTASSUSPENDIDAS = array("nombre" => array("nulo" => true, "vacio" => false, "tipo" => "string"));
        public static $CONTINUARVENTA = array("id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));
        public static $ELIMINARVENTASUSPENDIDA = array("id" => array("nulo" => false, "vacio" => false, "tipo" => "entero"));
        
        public $CajaapiModel;

        function __construct(){
            parent::__construct();
            //Se crea el objeto que instancia al modelo que se va a utilizar
            $this->CajaapiModel = new CajaapiModel();
            $this->CajaapiModel->connect();
        }

        function __destruct(){
            parent::__destruct();
            //Se destruye el objeto que instancia al modelo que se va a utilizar
            $this->CajaapiModel->close();
        } 

        function obtenerResumenVenta(){
            $ver_descuento = (isset($_REQUEST["ver_descuento"])) ? 1 : 0;
            $ver_selectores = (isset($_REQUEST["ver_selectores"])) ? 1 : 0;
            $descuento = (isset($_REQUEST["descuento"])) ? $_REQUEST["descuento"] : 0;
            $productos = json_decode($_REQUEST["venta"], true);
            $detalle_sin_descuento = $this->CajaapiModel->obtenerResumenVenta($productos["venta"], 0, true);

            if($ver_selectores == 1){
                require_once("models/cliente.php");
                require_once("models/tipo_pago.php");
                $ClienteModel = new ClienteModel();
                $ClienteModel->connect();
                $TipoPagoModel = new TipoPagoModel();
                $TipoPagoModel->connect();
            }

            if($ver_descuento == 1 && $ver_selectores == 1){
                $detalle_con_descuento = $this->CajaapiModel->obtenerResumenVenta($productos["venta"], $descuento, true);
                $detalle = array( "detalle_con_descuento" => $detalle_con_descuento, 
                                "detalle_sin_descuento" => $detalle_sin_descuento,
                                "clientes" => $ClienteModel->obtenerCliente("1=1")["rows"],
                                "tipos_pago" => $TipoPagoModel->obtenerTipoPago("1=1")["rows"]);
            }else if($ver_descuento == 1 && $ver_selectores == 0){
                $detalle_con_descuento = $this->CajaapiModel->obtenerResumenVenta($productos["venta"], $descuento, true);
                $detalle = array( "detalle_con_descuento" => $detalle_con_descuento, 
                                "detalle_sin_descuento" => $detalle_sin_descuento);
            }else if($ver_descuento == 0 && $ver_selectores == 1){
                $detalle = array( "detalle_sin_descuento" => $detalle_sin_descuento,
                                "clientes" => $ClienteModel->obtenerCliente("1=1")["rows"],
                                "tipos_pago" => $TipoPagoModel->obtenerTipoPago("1=1")["rows"]);
            }else{
                $detalle = array( "detalle_sin_descuento" => $detalle_sin_descuento );
            }

            $data = $this->Seguridad->generaTokenGenerico($_REQUEST["identificador"]);
            $detalle["token_venta"] = $data;

            parent::responder($detalle);
        }

        function obtenerVenta(){
            $filtros = (isset($_REQUEST["id"])) ? "idVenta = :id" : "1=1";
            $parametros = array("id" => $_REQUEST["id"]);
            $tipo = ($_REQUEST["tipo"] > 2 || $_REQUEST["tipo"] < 0) ? 0 : $_REQUEST["tipo"];
            $resultado = $this->CajaapiModel->obtenerVenta($filtros, $parametros, $tipo);
            
            parent::responder($resultado);
        }

        function guardarVenta(){
            $this->CajaapiModel->prepararSession($this->Seguridad->Usuario);
            $guardar_venta = $this->CajaapiModel->guardarVenta($_REQUEST["token_venta"]);
            parent::responder($guardar_venta);
        }

        function facturarVenta(){
            //ini_set('display_errors', 1);
            //error_reporting(E_ALL);
            if(!isset($_SESSION)){
                session_start();
                $_SESSION = json_decode(base64_decode($_REQUEST["session"]), true);
            }
            $guardar_factura = $this->CajaapiModel->facturarVenta($_REQUEST["token_venta"]);
            $respuesta = array();
            $respuesta["status"] = $guardar_factura["status"];
            $extras = array();
            $extras["facturacion"] = array();
            unset($guardar_factura["status"]);
            $extras["facturacion"]["tipo"] = $guardar_factura["success"];
            if(!$respuesta["status"]) $extras["facturacion"]["mensaje"] = $guardar_factura["mensaje"];
            if($guardar_factura["success"] == 1){
                unset($guardar_factura["success"]);
                $extras["facturacion"]["datos"] = $guardar_factura;
            }
            $respuesta["extra"] = $extras;
            
            parent::responder($respuesta);
        }

        function eliminarVenta(){
            $id = $_REQUEST['id'];
            $res = $this->CajaapiModel->eliminarVenta($id);
            
            parent::responder($res);
        }

        function enviarTicket(){
            $venta = $this->CajaapiModel->obtenerVenta("idVenta = :id", array("id" => $_REQUEST["id"]))["rows"][0];
            $email = $_REQUEST["email"];
            $resultado = $this->CajaapiModel->enviarTicket($email, $venta);
            
            parent::responder($resultado);
        }

        function suspenderVenta(){
            $productosJson = json_decode($_REQUEST["venta"], true);
            $productos = $productosJson['venta'];
            $id_cliente = $_REQUEST["cliente"];
            $documento = $_REQUEST["documento"];
            $impuestos = $_REQUEST["impuestos"];
            $monto = $_REQUEST["monto"];
            $sucursal = $_REQUEST["sucursal"];
            $nombre_cliente = $_REQUEST["nombreCliente"];
            $descuento = $_REQUEST["descuento"];
            $empleado = $this->Seguridad->Usuario;
            
            $respuesta = $this->CajaapiModel->suspenderVenta($id_cliente, $documento, $impuestos, $monto, $sucursal, $nombre_cliente, $productos, $descuento, $empleado);
            
            parent::responder($respuesta);
        }

        function listarVentasSuspendidas(){
            $filtros = (isset($_REQUEST["nombre"])) ? "identi LIKE CONCAT('%', :nombre, '%')" : "1=1";
            $parametros = array('nombre' => (isset($_REQUEST["nombre"])) ? $_REQUEST["nombre"] : null);
            $resultado = $this->CajaapiModel->listarVentasSuspendidas($filtros, $parametros);
            parent::responder($resultado);
        }

        function continuarVenta(){
            $id = $_REQUEST['id'];
            $resultado = $this->CajaapiModel->continuarVenta($id);
            parent::responder($resultado);

        }

        function eliminarVentaSuspendida(){
            $id = $_REQUEST['id'];
            $resultado = $this->CajaapiModel->eliminarVentaSuspendida($id);
            parent::responder($resultado);
        }

    }

?>
