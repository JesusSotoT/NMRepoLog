<?php
/* 
 * Utiliza la información de los otros webconfig 
 */

		session_start(true);
		
        //include("../../webconfig.php"); //Este archivo ya carga la base de datos
        $link_catalog_local = "../../netwarelog/catalog/";
        include($link_catalog_local."conexionbd.php");

        

        define("USUARIO",$usuariobd);
        define("CLAVE",$clavebd);
        define("SERVIDORDB",$servidor);
	define("SERVIDORCODIGO","../../../webapp-empresa");

		$idempleado = $_SESSION["accelog_idempleado"];

        
?>
