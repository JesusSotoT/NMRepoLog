<?php
/**
 * @author chais
 */
 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class importarModel extends Connection{

    public function saveClientes($clientes){
        //echo json_encode($clientes);
        $sql = "INSERT INTO comun_cliente (nombre, direccion) VALUES ";
        foreach ($clientes as $k => $v) {
             $sql .= '("'.$v['nombre'].'","'.$v['direccion'].'"),';
        }
        $sql = substr($sql, 0, -1);

        echo $sql;

    }

    public function saveProductos($productos){
        //echo json_encode($clientes);
        $sql = "INSERT INTO app_productos (nombre, direccion) VALUES ";
        foreach ($productos as $k => $v) {
             $sql .= '("'.$v['nombre'].'","'.$v['direccion'].'"),';
        }
        $sql = substr($sql, 0, -1);

        echo $sql;

    }


} ?>