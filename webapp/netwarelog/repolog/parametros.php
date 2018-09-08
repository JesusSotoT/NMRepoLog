<?php
/* 
 * Utiliza la información de los otros webconfig para operar en repolog/admin/
 */

        include("webconfig_repolog.php"); //Este archivo ya carga la base de datos
        $link_catalog_local = $url_catalog;
        include($link_catalog_local."/conexionbd.php");

?>
