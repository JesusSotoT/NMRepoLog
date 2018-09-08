<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class TarjetaModel extends Connection {


    public function guardarLay($dato)
    {
        $sql = "INSERT INTO app_tarjeta (estatus, clave , banco ,  id_clasificacion)
                VALUES  ('{$dato[1]}' , '{$dato[2]}' , '{$dato[3]}' , '99')";
                $this->queryArray( $sql );
    }

    public function validarProductos($val)
    {
        $sql = "SELECT  clave, banco
                FROM    app_tarjeta
                GROUP BY clave 
                HAVING COUNT(clave) > 1; ";

        $res = $this->query($sql);
        return $res;
    }

    public function traeCargados($clas)
    {
        $sql = "SELECT  id, estatus, clave, banco
                FROM    app_tarjeta WHERE id_clasificacion = $clas";
        $res = $this->query($sql);
        return $res;
    }

    public function borrar($val)
    {
        $sql = "DELETE FROM app_tarjeta WHERE id_clasificacion = $val;";
        $this->query($sql);
    }

    public function confirmar($val)
    {
        $sql = "UPDATE app_tarjeta SET id_clasificacion = NULL WHERE id_clasificacion = $val";
        $this->query($sql);
    }

    public function inactivarLay($idProd,$num)
    {
        $myQuery = "UPDATE app_tarjeta SET id_clasificacion = $num WHERE id = $idProd";
        $this->query($myQuery);
        return $myQuery;
    }

    public function reactivarLay($idProd,$num)
    {
        $myQuery = "UPDATE app_tarjeta SET id_clasificacion = $num WHERE id = $idProd";
        $this->query($myQuery);
        return $myQuery;
    }

    function guardar($clave, $nombre, $estatus)
    {
        $sql = "INSERT INTO app_tarjeta (clave , banco , estatus)
                VALUES  ('$clave' , '$nombre' , $estatus)";
        $res = $this->queryArray( $sql );
        return $res['insertId'];
    }
    function mostrar($id="")
    {
        $sql = "SELECT  *
                FROM    app_tarjeta
                WHERE   id LIKE '%$id%'; ";
        $res = $this->queryArray( $sql );
        return $res['rows'];
    }
    function editar($id, $clave, $nombre, $activo)
    {
        $sql = "UPDATE  app_tarjeta
                SET     clave='$clave' ,
                        banco='$nombre' ,
                        estatus='$activo'
                WHERE   id = '$id'; ";
        $res = $this->queryArray( $sql );
        return $res;
    }

}
?>
