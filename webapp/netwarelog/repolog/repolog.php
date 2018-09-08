<?php
/* 
 * Este modulo arma el sql de un id dado
 */

    session_start();
    $idorg = $_SESSION["accelog_idorganizacion"];

    include("parametros.php");
    
    $idreporte = mysql_real_escape_string($_GET["i"]);
    $_SESSION["repolog_idreporte"]=$idreporte;
    
    $sql = "select * from repolog_reportes where idreporte=".$idreporte;
    $result = $conexion->consultar($sql);
    $url_include = "";
	$nombrereporte="";

    $sql = "";
    while($rs=$conexion->siguiente($result)){
        $descripcion = $rs{'descripcion'};
        $sql.=" select ".$rs{'sql_select'};
        $sql.=" from ".$rs{'sql_from'};
        if(($rs{'sql_where'}!=null)&&($rs{'sql_where'}!=""))            $sql.=" where ".$rs{'sql_where'};
        if(($rs{'sql_groupby'}!=null)&&($rs{'sql_groupby'}!=""))    $sql.=" group by ".$rs{'sql_groupby'};
        if(($rs{'sql_having'}!=null)&&($rs{'sql_having'}!=""))          $sql.=" having ".$rs{'sql_having'};
        if(($rs{'sql_orderby'}!=null)&&($rs{'sql_orderby'}!=""))       $sql.=" order by ".$rs{'sql_orderby'};
        $idestiloomision = $rs{"idestiloomision"};
        $url_include = $rs{"url_include"};
        $url_include_despues = $rs{"url_include_despues"};
        $nombrereporte=$rs{"nombrereporte"};
        $subtotales_agrupaciones = $rs{"subtotales_agrupaciones"};
        $subtotales_funciones = $rs{"subtotales_funciones"};
        $subtotales_subtotal = $rs{"subtotales_subtotal"};
    }    
    $conexion->cerrar_consulta($result);

    $_SESSION["url_include"] =$url_include;
    $_SESSION["url_include_despues"] =$url_include_despues;
    $_SESSION["desc"] = $descripcion;
    $_SESSION["sequel"]=$sql;
    $_SESSION["iestilo"] = $idestiloomision;
		$_SESSION["nombrereporte"] = $nombrereporte;
    $_SESSION["subtotales_agrupaciones"] = $subtotales_agrupaciones;
    $_SESSION["subtotales_funciones"] = $subtotales_funciones;   
    $_SESSION["subtotales_subtotal"] = $subtotales_subtotal;   
    $caracter_pregunta = "[";
    if(strrpos($sql,$caracter_pregunta)){
        $url_siguiente = "filtros.php";
    } else {
        $url_siguiente = "reporte.php";
    }

    header("Location: ".$url_siguiente);


?>
