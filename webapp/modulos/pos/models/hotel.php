<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class HotelModel extends Connection {
    public function indexGridProductos($limit){
       // $query = "SELECT * from app_productos order by id asc";
/*  COMENTE ESTA CONSULTA PARA QUITAR EL CAMPO Y LA TABLA DE USUARIOS Y EMPLEADOS PARA HACER LA CONSULTA MAS RAPIDA ADEMAS DE LOS LIMITES
        $query = "SELECT us.idempleado ,c.id_proveedor as idProve,c.costo,p.*";
        $query .= "from app_productos p ";
        $query .= "left join app_costos_proveedor c on c.id_producto=p.id ";
        $query .= "left join accelog_usuarios us on us.idempleado=p.idempleado ";
        $query .= "group by p.id ";
        $query .= "order by p.id asc ".$limit;
*/        //echo $query;
        $query = "SELECT c.id_proveedor as idProve,c.costo,p.*";
        $query .= "from app_productos p ";
        $query .= "left join app_costos_proveedor c on c.id_producto=p.id ";
        $query .= "where p.status = 1 or p.status = 0 ";
        $query .= "group by p.id ";
        $query .= "order by p.id asc ";
        $rest = $this->queryArray($query);
        
        return array('productos' => $rest['rows'], 'total' => $rest['total'] );
    }

    public function guardarLay($dato)
    {
        $sql = "INSERT INTO hoteles (estatus, clave , nombre ,  id_clasificacion)
                VALUES  ('{$dato[1]}' , '{$dato[2]}' , '{$dato[3]}' , '99')";
                $this->queryArray( $sql );
    }

    public function validarProductos($val)
    {
        $sql = "SELECT  clave, nombre
                FROM    hoteles
                GROUP BY clave 
                HAVING COUNT(clave) > 1; ";

        $res = $this->query($sql);
        return $res;
    }

    public function traeCargados($clas)
    {
        $sql = "SELECT  id, estatus, clave, nombre
                FROM    hoteles WHERE id_clasificacion = $clas";
        $res = $this->query($sql);
        return $res;
    }

    public function borrar($val)
    {
        $sql = "DELETE FROM hoteles WHERE id_clasificacion = $val;";
        $this->query($sql);
    }

    public function confirmar($val)
    {
        $sql = "UPDATE hoteles SET id_clasificacion = NULL WHERE id_clasificacion = $val";
        $this->query($sql);
    }

    public function inactivarHotelLay($idProd,$num)
    {
        $myQuery = "UPDATE hoteles SET id_clasificacion = $num WHERE id = $idProd";
        $this->query($myQuery);
        return $myQuery;
    }

    public function reactivarHotelLay($idProd,$num)
    {
        $myQuery = "UPDATE hoteles SET id_clasificacion = $num WHERE id = $idProd";
        $this->query($myQuery);
        return $myQuery;
    }

    function guardar($clave, $nombre, $estatus)
    {
        $sql = "INSERT INTO hoteles (clave , nombre , estatus)
                VALUES  ('$clave' , '$nombre' , $estatus)";
        $res = $this->queryArray( $sql );
        return $res['insertId'];
    }
    function mostrar($id="")
    {
        $sql = "SELECT  *
                FROM    hoteles
                WHERE   id LIKE '%$id%'; ";
        $res = $this->queryArray( $sql );
        return $res['rows'];
    }
    function editar($id, $clave, $nombre, $estatus)
    {
        $sql = "UPDATE  hoteles
                SET     clave='$clave' ,
                        nombre='$nombre' ,
                        estatus='$estatus'
                WHERE   id = '$id'; ";
        $res = $this->queryArray( $sql );
        return $res;
    }

}
?>
