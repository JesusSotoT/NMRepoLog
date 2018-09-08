<?php
//ini_set('display_errors', 1);
require("models/connection_sqli.php"); // funciones mySQLi

class cotizacionModel extends Connection {
     
    public function __construct() {
        session_start();
        cotizacionModel::simple();
        cotizacionModel::propina();
        cotizacionModel::sessiooon();
        //unset($_SESSION["sucursal"]);
        //unset($_SESSION["caja"]);
        //unset($_SESSION["simple"]);
        //$_SESSION["simple"] = true;
    }

    function sessiooon(){
         if (!isset($_SESSION["sesionid"])) {
            $_SESSION["sesionid"] = session_id();
         }
        

    }



}

?>