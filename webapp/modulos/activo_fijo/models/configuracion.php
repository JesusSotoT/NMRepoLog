<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ConfiguracionModel extends Connection
{
    public function ejercicios()
    {
        $myQuery = "SELECT* FROM app_ejercicios";
        $resultados = $this->query($myQuery);
        return $resultados;
    }

    public function lista_altas_bajas($tipo)
    {
        return $this->query("SELECT* FROM afi_cat_".$tipo);
    }

    public function lista_bienes()
    {
        return $this->query("SELECT* FROM afi_categorias_bienes");
    }

    public function lista_formulas()
    {
        return $this->query("SELECT* FROM afi_formulas");
    }

    public function lista_inpc()
    {
        return $this->query("SELECT* FROM afi_inpc");
    }

    public function lista_depr()
    {
        return $this->query("SELECT* FROM afi_porc_depr");
    }

    public function guardar_registro($vars)
    {
        $myQuery = "INSERT INTO ";
        if($vars['tipo'] == 'altas')
            $myQuery .= "afi_cat_altas(id,codigo,nombre,activo) VALUES(0,'".$vars['primero']."','".$vars['segundo']."',".$vars['estatus'].")";

        if($vars['tipo'] == 'bajas')
            $myQuery .= "afi_cat_bajas(id,codigo,nombre,activo) VALUES(0,'".$vars['primero']."','".$vars['segundo']."',".$vars['estatus'].")";

        if($vars['tipo'] == 'bienes')
            $myQuery .= "afi_categorias_bienes(id,codigo,nombre,activo) VALUES(0,'".$vars['primero']."','".$vars['segundo']."',".$vars['estatus'].")";

        if($vars['tipo'] == 'formulas')
            $myQuery .= "afi_formulas(id,nombre,formula,activo) VALUES(0,'".$vars['primero']."','".$vars['segundo']."',".$vars['estatus'].")";

        if($vars['tipo'] == 'inpc')
            $myQuery .= "afi_inpc(id,anio,mes,indice,activo) VALUES(0,'".$vars['primero']."','".$vars['segundo']."',".$vars['indice'].",".$vars['estatus'].")";

        if($vars['tipo'] == 'depr')
            $myQuery .= "afi_porc_depr(id,nombre,porciento,activo) VALUES(0,'".$vars['primero']."','".$vars['segundo']."',".$vars['estatus'].")";

        return $this->insert_id($myQuery);
    }

     public function actualizar_registro($vars)
    {
        $myQuery = "UPDATE ";
        if($vars['tipo'] == 'altas')
            $myQuery .= "afi_cat_altas SET codigo = '".$vars['primero']."', nombre = '".$vars['segundo']."', activo = ".$vars['estatus'];

        if($vars['tipo'] == 'bajas')
            $myQuery .= "afi_cat_bajas SET codigo = '".$vars['primero']."', nombre = '".$vars['segundo']."', activo = ".$vars['estatus'];

        if($vars['tipo'] == 'bienes')
            $myQuery .= "afi_categorias_bienes SET codigo = '".$vars['primero']."', nombre = '".$vars['segundo']."', activo = ".$vars['estatus'];

        if($vars['tipo'] == 'formulas')
            $myQuery .= "afi_formulas SET nombre = '".$vars['primero']."', formula = '".$vars['segundo']."', activo = ".$vars['estatus'];

        if($vars['tipo'] == 'inpc')
            $myQuery .= "afi_inpc SET anio = '".$vars['primero']."', mes = '".$vars['segundo']."', indice = ".$vars['indice'].", activo = ".$vars['estatus'];

        if($vars['tipo'] == 'depr')
            $myQuery .= "afi_porc_depr SET nombre = '".$vars['primero']."', porciento = '".$vars['segundo']."', activo = ".$vars['estatus'];

        $myQuery .= " WHERE id = ".$vars['id_reg'];

        return $this->query($myQuery);
    }

    public function get_data_ref($vars)
    {
        $myQuery = "SELECT* FROM ";
        if($vars['tipo'] == 'altas')
            $myQuery .= "afi_cat_altas WHERE id = ".$vars['id'];

        if($vars['tipo'] == 'bajas')
            $myQuery .= "afi_cat_bajas WHERE id = ".$vars['id'];

        if($vars['tipo'] == 'bienes')
            $myQuery .= "afi_categorias_bienes WHERE id = ".$vars['id'];

        if($vars['tipo'] == 'formulas')
            $myQuery .= "afi_formulas WHERE id = ".$vars['id'];

        if($vars['tipo'] == 'inpc')
            $myQuery .= "afi_inpc WHERE id = ".$vars['id'];

        if($vars['tipo'] == 'depr')
            $myQuery .= "afi_porc_depr WHERE id = ".$vars['id'];

        $res = $this->query($myQuery);
        $res = $res->fetch_array();
        return $res;
    }
}
?>
