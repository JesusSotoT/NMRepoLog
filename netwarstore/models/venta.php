<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class VentaModel extends aConnection
    {
        private $Seguridad;

        function __construct($seguridad)
        {
            $this->Seguridad = $seguridad;
        }

        public function object_to_array($data) {
            if (is_array($data) || is_object($data)) {
                $result = array();
                foreach ($data as $key => $value) {
                    $result[$key] = $this->object_to_array($value);
                }
                return $result;
            }
            return $data;
        }

        public function venta($productos, $total){
            try{
                //ini_set('display_errors', 1);
                //error_reporting(E_ALL);
                if(!isset($_SESSION)) session_start();
                $_SESSION['accelog_idempleado'] = 1;
                $_SESSION["sucursal"] = 1;
                $_SESSION['accelog_nombre_instancia'] = "nmwadmin"; //TODO: Cambiar a produccion
                $_REQUEST["netwarstore"] = true;
                include_once("../webapp/modulos/pos/models/caja.php");
                $caja = new CajaModel();
                $caja->connect();
                $productos = explode("|", $productos);
                foreach ($productos as $producto) {
                    if($producto != ""){
                        $caja->agregaProducto($producto, 1, '', '', '', '');
                    }
                }
                
                $agregar_pago = $caja->agregaPago("1", "(04)+Tarjeta+de+credito", $_SESSION['caja']['cargos']['total'], "", "");
                $guardar_venta = $caja->guardarVenta("", "0", "1", "0", "", "", "");
                $facturar = $caja->facturar("0", $guardar_venta["idVenta"], 1, "false", "", "1");
                $pendiente_facturacion = $caja->pendienteFacturacion("0", "", "", $guardar_venta["idVenta"], "", $facturar["azurian"], "1");
                $json = array("success" => true, "ruta" => "http://www.netwarmonitor.mx/clientes/". $_SESSION['accelog_nombre_instancia'] ."/kiosko", "codigo" => $this->obtenerCodigoFacturacion($caja, $guardar_venta["idVenta"]));
            } catch(Exception $e){
                $json = array("error" => false, "mensaje" => $e->getMessage());
            }
            return $json;
        }

        private function obtenerCodigoFacturacion($caja, $id_venta){
            $venta = $caja->datosventa($id_venta);
            $longuitud = strlen($_SESSION['accelog_nombre_instancia']);
            $codinstancia = $_SESSION['accelog_nombre_instancia'][0].$_SESSION['accelog_nombre_instancia'][$longuitud-1];
            $fecha = str_replace('-', '', $venta[0]['fecha']);
            $fecha = str_replace(':', '', $fecha);
            $fecha = str_replace(' ', '', $fecha);
            return $codinstancia.dechex($fecha.$venta[0]['folio']);
        }

    }
?>
