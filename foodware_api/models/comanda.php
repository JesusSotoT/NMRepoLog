<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class ComandaModel extends Conexion
    {
        private $Seguridad;

        function __construct($seguridad)
        {
            $this->Seguridad = $seguridad;
            $_REQUEST["api"] = true;
            $_REQUEST["json"] = 1;
            require_once("../webapp/modulos/restaurantes/controllers/comandas.php");
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
    

        public function obtenerInformacion(){
            $comanda = new comandas();
            return json_decode($comanda->menuMesas(), true);
        }

        public function obtenerMesas($zona){
            try{
                $comanda = new comandas();
                if(!is_null($zona)){
                    $informacion = json_decode($comanda->areas($_REQUEST), true);
                } else {
                    $informacion = json_decode($comanda->menuMesas(), true);
                }
                if(array_key_exists("tables", $_SESSION)){
                    $json = array("status" => true, "rows" => $_SESSION["tables"]);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerAreas(){
            try{

                $comanda = new comandas();
                $informacion = json_decode($comanda->menuMesas(), true);
                if(array_key_exists("areas", $informacion)){
                    $json = array("status" => true, "rows" => $informacion["areas"]);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerEmpleados(){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->menuMesas(), true);
                if(array_key_exists("empleados", $informacion)){
                    $json = array("status" => true, "rows" => $informacion["empleados"]);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerConfiguracion(){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->menuMesas(), true);
                if(array_key_exists("configuracion", $informacion)){
                    $configuracion = array();
                    $configuracion[] = $informacion["configuracion"];
                    $json = array("status" => true, "rows" => $configuracion);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

       public function iniciarSesion($datos){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->iniciar_sesion($datos), true);
                if($informacion["status"]==2){
                    $json = array("status" => true);
                } else {
                    $json = array("status" => false, "mensaje" => "Usuario o Contraseña incorrectos");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerDepartamentos(){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->getDeparments(), true);
                if(array_key_exists("departamentos", $informacion)){
                    $departamentos = array();
                    $departamentos[] = $informacion["departamentos"];
                    //$productos = array();
                    //$productos[] = $informacion["productos"];
                    $json = array("status" => true, "rows" => $departamentos);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerFamilias($departamento){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->getFamilies($departamento), true);
                if(array_key_exists("familia", $informacion)){
                    $familias = array();
                    $familias[] = $informacion["familia"];
                    //$productos = array();
                    //$productos[] = $informacion["productos"];
                    $json = array("status" => true, "rows" => $familias );
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerLineas($familia){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->getLines($familia), true);
                if(array_key_exists("linea", $informacion)){
                    $linea = array();
                    $linea[] = $informacion["linea"];
                    // $productos = array();
                    //$productos[] = $informacion["productos"];
                    $json = array("status" => true, "rows" => $linea);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerProductosAll(){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->getDeparments(), true);
                if(array_key_exists("productos", $informacion)){
                    //$departamentos = array();
                    //$departamentos[] = $informacion["departamentos"];
                    $productos = array();
                    $productos[] = $informacion["productos"];
                    $json = array("status" => true, "rows" => $productos);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerProductosDep($departamento){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->getFamilies($departamento), true);
                if(array_key_exists("productos", $informacion)){
                    //$familias = array();
                    //$familias[] = $informacion["familia"];
                    $productos = array();
                    $productos[] = $informacion["productos"];
                    $json = array("status" => true, "rows" => $productos);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function obtenerProductosFam($familia){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->getLines($familia), true);
                if(array_key_exists("linea", $informacion)){
                    //$linea = array();
                    //$linea[] = $informacion["linea"];
                    $productos = array();
                    $productos[] = $informacion["productos"];
                    $json = array("status" => true, "rows" => $productos);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

         public function obtenerProductosLin($linea){
            try{
                $comanda = new comandas();
                $informacion = json_decode($comanda->buscar_productos($linea), true);
                if(array_key_exists("producto", $informacion)){
                    //$linea = array();
                    //$linea[] = $informacion["linea"];
                    //$productos = array();
                    //$productos[] = $informacion["nombre"];
                    $json = array("status" => true, "rows" => $informacion["producto"]);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }
            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function detallesProducto($idProducto){
            try{
                $comanda = new comandas();

                $result = json_decode($comanda->getItemsProduct($idProducto), true);

                if (!empty($result["detalles"])) {
                    $json = array("status" => true, "rows" => $result["detalles"]);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }

            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function eliminarMesas($idMesas){
            try{
                $comanda = new comandas();

                $informacion = json_decode($comanda->eliminar_mesas($idMesas), true);

                if(array_key_exists("status", $informacion)){

                    $json = array("status" => true, "rows" => array($informacion));
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }

            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function juntarMesas($idMesas){
            try{
                $comanda = new comandas();

                $informacion = json_decode($comanda->juntar_mesas($idMesas), true);

                if(array_key_exists("status", $informacion)){
                    $json = array("status" => true, "rows" => array($informacion));
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }

            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function guardarPedido($objetoPedido){
            try{
                $comanda = new comandas();

                $informacion = json_decode($comanda->guardar_pedido($objetoPedido), true);
                
                if (!empty($informacion["resultado"])) {
                    $json = array("status" => true, "rows" => $informacion);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }

            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

        public function enviarCaja($datosPedido){
            try{
                $comanda = new comandas();

                $informacion = json_decode($comanda->cerrar_comanda($datosPedido), true);
                
                if (!empty($informacion["close"])) {
                    $json = array("status" => true, "rows" => $informacion["close"]);
                } else {
                    $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion");
                }

            } catch(Exception $e){
                $json = array("status" => false, "mensaje" => "No se ha podido obtener la informacion. [". $e->getMessage() ."]");
            }
            return $json;
        }

    }
?>
