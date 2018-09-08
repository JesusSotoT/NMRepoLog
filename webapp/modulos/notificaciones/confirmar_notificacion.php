<?php

/*
 * TEn caso de se aceptado el contacto modifica el campo aceptado de la tabla contacto.
 */
    $idnotificacion = "";
    if( isset($_GET) && !empty($_GET) && $_GET['idnotificacion'] > 0 ){
        $idnotificacion = $_GET['idnotificacion'];
        
        include("../../netwarelog/catalog/conexionbd.php");
        
        $sQuery = "Update notificaciones Set leido = 1, fechalectura = '".date('Y-m-d H:i:s')."' Where idnotificacion = $idnotificacion;";
        
        $conexion->consultar($sQuery);
    }
	echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
?>
