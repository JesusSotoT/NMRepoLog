<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class AjustesInventarioModel extends Connection {
    public function ajustes(){
        $sql = "SELECT  fecha, usuario, COUNT(*) movimientos
                FROM    app_inventario_movimientos m
                INNER JOIN accelog_usuarios u ON m.id_empleado = u.idempleado
                WHERE   referencia LIKE 'Ajuste % de inventario %'
                GROUP BY fecha
                ORDER BY fecha DESC;";
        $res = $this->queryArray($sql);
        
        return $res['rows'];
    }
    public function movimientos($fecha) {
        $sql = "SELECT  p.nombre, m.id_producto, m.id_producto_caracteristica, ps.serie serie, l.no_lote lote,  m.cantidad, ao.nombre id_almacen_origen, ad.nombre id_almacen_destino, m.tipo_traspaso
                FROM    app_inventario_movimientos m
                INNER JOIN  app_productos p ON m.id_producto = p.id
                LEFT JOIN   app_producto_serie_rastro rs ON m.id = rs.id_mov
                LEFT JOIN   app_producto_serie ps ON rs.id_serie = ps.id
                LEFT JOIN   app_producto_lotes l ON m.id_lote = l.id
                LEFT JOIN   app_almacenes ao ON m.id_almacen_origen = ao.id
                LEFT JOIN   app_almacenes ad ON m.id_almacen_destino = ad.id
                WHERE   referencia LIKE 'Ajuste % de inventario %' AND fecha = '$fecha'";
        $res = $this->queryArray($sql);
        
        return $res['rows'];
    }

    function getCaracteristicas()
        {
            $sql = "SELECT p.id ID_P, h.id ID_H, p.nombre CRARACTERISTICA_PADRE, h.nombre CARACTERISTICA_HIJA
                FROM    app_caracteristicas_padre p
                INNER JOIN  app_caracteristicas_hija h ON p.id = h.id_caracteristica_padre
                ORDER BY ID_P, ID_H;";
            $result = $this->queryArray($sql);

            return $result['rows'];
        }
}
?>
