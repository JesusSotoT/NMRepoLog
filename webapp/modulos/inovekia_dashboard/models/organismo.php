<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class OrganismoModel extends Connection
{
   
   public function grid(){
        $registros = array();

        $organismoes = 'SELECT * FROM netwarstore.inovekia_organismo WHERE activo = 1;';
        $organismoes = $this->queryArray($organismoes);
        $organismoes = $organismoes['rows'];

        foreach ($organismoes as $organismo) {
            $item = array();
            $item[0] = $organismo['nombre'];
            $item[1] = "<a href='javascript:mostrarConsultores(\"". $organismo['id'] ."\");'><i class='fa fa-address-book'></i></a>";
            $registros[] = $item;
        }
        return array("status" => true, "registros" => $registros);
    }

}

?>