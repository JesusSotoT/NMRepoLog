<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class SupervisorModel extends Connection
{
   
   public function grid($organismo){
        $registros = array();
        if($organismo == 0){
            $supervisores = 'SELECT * FROM inovekia_supervisor
                            WHERE activo = 1
                            LIMIT 100;';

            $supervisores = $this->queryArray($supervisores);
            $supervisores = $supervisores['rows'];

            foreach ($supervisores as $supervisor) {
                $item = array();
                $item[0] = $supervisor['nombre'];
                $registros[] = $item;
            }

        }else{
            $supervisores = 'SELECT isa.id, isa.nombre, iso.id_organismo 
                            FROM inovekia_supervisor AS isa 
                            LEFT JOIN inovekia_supervisor_organismo AS iso ON iso.id_supervisor = isa.id
                            WHERE isa.activo = 1 AND (iso.id_supervisor IS NULL OR iso.id_organismo = '. $organismo .')
                            LIMIT 100;';

            $supervisores = $this->queryArray($supervisores);
            $supervisores = $supervisores['rows'];

            foreach ($supervisores as $supervisor) {
                $item = array();
                $item[0] = $supervisor['nombre'];

                if(is_null($supervisor['id_organismo'])){
                    $item[1] = "<a href='javascript:seleccionarSupervisor(\"". $organismo ."\", \"". $supervisor['id'] ."\");'><i class='fa fa-check'></i></a>";            
                }else{
                    $item[1] = "<a href='javascript:eliminarSupervisor(\"". $organismo ."\", \"". $supervisor['id'] ."\");'><i class='fa fa-close'></i></a>";
                }

                $registros[] = $item;
            }
        }
        return array("status" => true, "registros" => $registros);
    }

    public function seleccionar($organismo, $supervisor){
        $seleccionar = "INSERT INTO inovekia_supervisor_organismo VALUES(null, ". $supervisor .", ". $organismo .", 1, '". date("Y-m-d H:i:s") ."', '". date("Y-m-d H:i:s") ."');";
        $seleccionar = $this->queryArray($seleccionar);
        if($seleccionar['insertId'] > 0){
            $respuesta = array("status" => true);
        } else {
            $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        return $respuesta;
    }

    public function eliminar($organismo, $supervisor){
        $eliminar = "DELETE FROM inovekia_supervisor_organismo WHERE id_organismo = ". $organismo ." AND id_supervisor = ". $supervisor .";";
        $eliminar = $this->queryArray($eliminar);
        
        if($eliminar['status']){
            $respuesta = array("status" => true);
        } else {
            $respuesta = array("status" => false, "mensaje" => "No fue posible guardar la información");
        }
        
        return $respuesta;
    }

}

?>