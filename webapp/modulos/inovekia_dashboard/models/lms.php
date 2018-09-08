<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class LmsModel extends Connection
{

	public function obtenerSeguimiento($consultor, $empresario, $curso){
		if($empresario != -1){
        	$seleccionar = "SELECT seguimiento FROM netwarstore.inovekia_seguimiento WHERE id_curso = ". $curso ." AND id_consultor = ". $consultor ." AND id_empresario = ". $empresario ." ORDER BY ultimo_slide DESC LIMIT 1;";
        } else {
        	$seleccionar = "SELECT seguimiento FROM seguimiento_inovekia WHERE id_curso = ". $curso ." AND id_empleado = ". $empresario ." ORDER BY ultimo_slide DESC LIMIT 1;";
        }
        $seleccionar_resultado = $this->queryArray($seleccionar);
        if($seleccionar_resultado['status']){
        	$respuesta = array("status" => true, "seguimiento" => ($seleccionar_resultado['total'] > 0) ? $seleccionar_resultado['rows'][0]['seguimiento'] : '');
        } else {
        	$respuesta = array("status" => false, "mensaje" => "No fue posible obtener la información");
        }
        return $respuesta;
    }

    public function guardarSeguimiento($consultor, $seguimiento){
    	if($seguimiento["id_empresario"] != -1){
        	$insertar = "INSERT INTO netwarstore.inovekia_seguimiento VALUES(null, ". $consultor .", ". $seguimiento["id_empresario"] .", ". $seguimiento["id_curso"] .", ". $seguimiento["ultimo_slide"] .", '". $seguimiento["seguimiento"] ."', ". $seguimiento["latitud"] .", ". $seguimiento["longitud"] .", '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."', 1);";
        } else {
        	$insertar = "INSERT INTO seguimiento_inovekia VALUES(null, ". $consultor .", ". $seguimiento["id_curso"] .", ". $seguimiento["ultimo_slide"] .", '". $seguimiento["seguimiento"] ."', ". $seguimiento["latitud"] .", ". $seguimiento["longitud"] .", '". date("Y-m-d H:i:s") ."');";
        }
        $insertar_resultado = $this->queryArray($insertar);
        if($insertar_resultado['status']){
        	$respuesta = array("status" => true);
        } else {
        	$respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        return $respuesta;
    }

}

?>