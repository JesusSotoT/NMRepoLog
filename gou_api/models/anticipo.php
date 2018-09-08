<?php
    //ini_set("display_errors", 1); error_reporting(E_ALL);
    //Carga la clase de coneccion con sus metodos para consultas o transacciones
    require_once("models/connection_sqli_manual.php"); // funciones mySQLi

    class AnticipoModel extends Connection
    {
        public $Seguridad;

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

        
        public function listado($id){
            try {
                $query = "SELECT c.id, c.concepto, c.fecha_creacion, (SELECT importe FROM cont_movimientos WHERE IdPoliza = c.id AND TipoMovto = 'Cargo' LIMIT 1) AS monto FROM cont_polizas AS c WHERE idUser = '$id' AND Anticipo = 1 AND c.activo = 1;";
                $result = $this->queryArray($query);
                if($result["status"]){
                    include_once 'models/comprobante.php';
                    foreach ($result["rows"] as &$anticipo) {
                        $total = 0;
                        $comprobantes = new ComprobanteModel(null);
                        $comprobantes->connect();
                        $listado = $comprobantes->listado($anticipo["id"]);
                        foreach ($listado["rows"] as $comprobante) {
                            $total += $comprobante["importe"];
                        }
                        $anticipo["monto"] -= $total;
                    }
                    $json = array("status" => true, "rows" => $result["rows"]);
                }else{
                    $json = array("status" => false, "mensaje" => $result["msg"]);
                }
            } catch (Exception $e) {
                $json = array("status" => false, "mensaje" => $e->getMessage());
            } 
            return $json;       
        }

    }
?>
