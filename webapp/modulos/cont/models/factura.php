<?php
require("models/connection_sqli_manual.php"); // funciones mySQLi
class FacturaModel extends Connection {
  //Obtenemos los datos de una factura a traves del uuid, nos trai los datos generales de la factura
  //y el xml convertido en JSON para extraer todos los datos.
  function obtener_datos_factura($uuid){
    $myQuery = "SELECT * FROM cont_facturas WHERE uuid='$uuid'";
    $Result = $this->query($myQuery);
    return $Result;
  }
}
