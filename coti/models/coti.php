<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class CotiModel extends Connection
{

    public function estatusCotizacion($a,$b){
        $SQL = "SELECT cadenaCoti, aceptada from app_requisiciones_venta where id='$a' AND cadenaCoti='$b'";
        $resultSelect = $this->queryArray($SQL);
        return $resultSelect['rows'];
    }

    public function conversacion($cot){
        $SQL = "SELECT * from app_requisiciones_venta_comentarios where id_coti='$cot' order by fecha_comentario desc";
        $resultSelect = $this->queryArray($SQL);
        if ($resultSelect["total"] > 0) {
            $SQLx = "UPDATE app_requisiciones_venta_comentarios SET nuevo=0 where id_coti='$cot'";
            $this->query($SQLx);
            return $resultSelect['rows'];
        }else{
            return 0;
        }
        
    }

    public function aceptarCoti($coti){
        $SQL = "UPDATE app_requisiciones_venta SET aceptada=1 where id='$coti'";
        $resultSelect = $this->queryArray($SQL);

    }

     public function comentar($coti,$com,$p){
    date_default_timezone_set('America/Mexico_City');
    $fecha=date('Y-m-d H:i:s');
        echo $SQL = "INSERT into app_requisiciones_venta_comentarios(id_coti,fecha_comentario,comentario,quien)  values($coti,'$fecha','$com','$p');";
        $resultSelect = $this->queryArray($SQL);

    }


} ///fin de la clase
?>
