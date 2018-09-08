<?php

    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    //require("models/connection.php"); // funciones mySQL 
    require_once 'models/connection_sqli_manual.php'; // funciones mySQLi 
    //ini_set("display_errors",1); error_reporting(E_ALL);
    require_once 'models/perfil.php';

    class ProductoModel extends Connection {

        private $perfil;

        function __construct()
        {
            $this->connect(true);
            $this->perfil = new PerfilModel();
        }

        public function listado()
        {
            $sql = "SELECT id AS customer, idclient AS usuario FROM customer WHERE instancia = '". $_SESSION["accelog_nombre_instancia"] ."';";
            $sql = $this->queryArray($sql);
            $sql = "SELECT p.idapp AS id, p.appname AS producto, p.version AS version, i.initdate AS inicio, i.limitdate AS fin, i.version AS periodo, p.costomensual AS precio_mensual, p.costoanual AS precio_anual, i.amount AS precio, NULL AS porcentaje FROM appclient AS i INNER JOIN appdescrip AS p ON p.idapp = i.idapp WHERE i.idclient = ". $sql["rows"][0]["usuario"] ." AND i.idcustomer = ". $sql["rows"][0]["customer"] ." AND i.idstatus != 2;";
            $sql = $this->queryArray($sql);
            $productos = $sql["rows"];
            foreach ($productos as &$producto) {
                $producto["ok"] = !(strtotime(date("Y-m-d")) > strtotime($producto["fin"]));
                $producto = (object) $producto;
                $inicial = new DateTime($producto->inicio);
                $final = new DateTime($producto->fin);
                $diferencia = $inicial->diff($final)->days;
                $hoy = new DateTime(date("Y-m-d"));
                $restante = (strtotime(date("Y-m-d")) >= strtotime($producto->fin)) ? 0 : $hoy->diff($final)->days;
                if(is_null($producto->periodo)) {
                    $producto->periodo = ($diferencia <= 31) ? "mensual" : "anual";
                }
                if(is_null($producto->precio)) {
                    $tipo = "precio_". $producto->periodo;
                    $producto->precio = $producto->$tipo;
                }
                $producto->porcentaje = number_format(($restante == $diferencia) ? 0 : ((($diferencia - $restante) * 100) / $diferencia), 2);
                $producto->producto = ucfirst($producto->producto);
                $producto->version = ucfirst($producto->version);
                $producto->periodo = ucfirst($producto->periodo);
            }
            return array("status" => true, "productos" => $productos);
        }

    }

?>