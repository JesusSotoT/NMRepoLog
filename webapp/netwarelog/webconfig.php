<?php

    ini_set('session.cookie_httponly',1);
    set_time_limit(3600);

    //determinando el servidor
    if($_SERVER['SERVER_NAME']=="edu.netwarmonitor.com"){
            $servidor ="unmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
            $usuariobd="unmdev";
            $clavebd="&=98+69unmdev";
            $bd = "nmdev";
            $accelog_variable = "netappmitranetwarelog1";
    //}elseif($_SERVER['SERVER_NAME']=="localhost1"){
    }else if($_SERVER['SERVER_NAME']=="localhost" || $_SERVER['SERVER_NAME']=="192.168.1.135"){
            $servidor  = "192.168.1.11";
            $usuariobd = "root";
            $clavebd = "root";
            $bd = "nmdev";
            $accelog_variable = "netappmitranetwarelog1";
    }else{
            $servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
            $usuariobd = "nmdevel";
            $clavebd = "nmdevel";
            $bd = "nmdev";
            $accelog_variable = "netappmitranetwarelog1";
    }


    $version='';
    $misproductos='';
    $arrInstanciaG = explode("/",$_SERVER['REQUEST_URI']);
    // if(array_search('facturar',$arrInstanciaG)!=0 || array_search('restaurantes_externo',$arrInstanciaG)!=0 || array_search('kiosko',$arrInstanciaG)!=0 || array_search('appministra_api',$arrInstanciaG)!=0 || array_search('foodware_api',$arrInstanciaG)!=0 || array_search('inovekia_consultor',$arrInstanciaG)!=0 || array_search('inovekia_empresario',$arrInstanciaG)!=0 || array_search('netwarstore',$arrInstanciaG)!=0 || array_search('hibrido_api',$arrInstanciaG)!=0){
    //     $strInstanciaG = $arrInstanciaG[array_search('facturar',$arrInstanciaG) - 1];
    //     $strInstanciaG = $arrInstanciaG[array_search('kiosko',$arrInstanciaG) - 1];
    //     $strInstanciaG = $arrInstanciaG[array_search('restaurantes_externo',$arrInstanciaG) - 1];
    // 	$strInstanciaG = $arrInstanciaG[array_search('appministra_api',$arrInstanciaG) - 1];
    //     $strInstanciaG = $arrInstanciaG[array_search('hibrido_api',$arrInstanciaG) - 1];
    //     $strInstanciaG = $arrInstanciaG[array_search('inovekia_consultor',$arrInstanciaG) - 1];
    //     $strInstanciaG = $arrInstanciaG[array_search('inovekia_empresario',$arrInstanciaG) - 1];
    //     $strInstanciaG = $arrInstanciaG[array_search('foodware_api',$arrInstanciaG) - 1];
    //     $strInstanciaG = $arrInstanciaG[array_search('netwarstore',$arrInstanciaG) - 1];
    // 	// echo "instancia: ".$strInstanciaG;
    // }else{
    //     $strInstanciaG = $arrInstanciaG[array_search('webapp',$arrInstanciaG) - 1];
    // }

    if(array_search('facturar', $arrInstanciaG) != 0){
        $strInstanciaG = $arrInstanciaG[array_search('facturar',$arrInstanciaG) - 1];
    } else if (array_search('restaurantes_externo',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('restaurantes_externo',$arrInstanciaG) - 1];
    } else if(array_search('kiosko',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('kiosko',$arrInstanciaG) - 1];
    } else if(array_search('coti',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('coti',$arrInstanciaG) - 1];
    } else if(array_search('appministra_api',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('appministra_api',$arrInstanciaG) - 1];
    } else if(array_search('foodware_api',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('foodware_api',$arrInstanciaG) - 1];
    } else if(array_search('inovekia_consultor',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('inovekia_consultor',$arrInstanciaG) - 1];
    } else if(array_search('inovekia_empresario',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('inovekia_empresario',$arrInstanciaG) - 1];
    } else if(array_search('netwarstore',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('netwarstore',$arrInstanciaG) - 1];
    } else if(array_search('hibrido_api',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('hibrido_api',$arrInstanciaG) - 1];
    } else if(array_search('gou_api',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('gou_api',$arrInstanciaG) - 1];
    } else if(array_search('checador_api',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('checador_api',$arrInstanciaG) - 1];
    } else if(array_search('visorpdf.php',$arrInstanciaG)!=0){
        $strInstanciaG = $arrInstanciaG[array_search('visorpdf.php',$arrInstanciaG) - 1];
    }else {
        $strInstanciaG = $arrInstanciaG[array_search('webapp',$arrInstanciaG) - 1];
    }

    	// echo "instancia: ".$strInstanciaG;
    	// exit();
    $idcustomer=0;
    if(isset($_COOKIE['inst_lig']))
        $strInstanciaG = $_COOKIE['inst_lig'];
    //if($strInstanciaG=="mlog1"){
    if($strInstanciaG=="mlog"){
        // $usuariobd="root";
        // $clavebd="root";
        $usuariobd = "nmdevel";
        $clavebd = "nmdevel";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
        $estatus_cobranza = 0;
    }else {
        $objConG = mysqli_connect($servidor,$usuariobd , $clavebd, "netwarstore");
        $strSqlG = "SELECT id,usuario_db,pwd_db,nombre_db,cobranza FROM customer WHERE instancia = '" . $strInstanciaG . "';";
        //$strSqlG = "SELECT id,usuario_db,pwd_db,nombre_db FROM customer WHERE instancia = 'chilessecosjr_demo';";
        $rstWebconfigG = mysqli_query($objConG, $strSqlG);
        while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
            $usuariobd = $objWebconfigG['usuario_db'];
            $clavebd = $objWebconfigG['pwd_db'];
            $bd = $objWebconfigG['nombre_db'];
            $accelog_variable = str_replace('_dbmlog', '', $objWebconfigG['nombre_db']) . "mlog";
            $idcustomer=$objWebconfigG['id'];
            $estatus_cobranza = $objWebconfigG['cobranza'];
        }
        unset($objWebconfigG);
        mysqli_free_result($rstWebconfigG);
        unset($rstWebconfigG);

        //Recupera la version del Sistema
        $strSqlG = "SELECT version FROM $bd.netwarelog_version";
        $rstWebconfigG = mysqli_query($objConG, $strSqlG);
        while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
            $version = $objWebconfigG['version'];
        }
        unset($objWebconfigG);
        mysqli_free_result($rstWebconfigG);
        unset($rstWebconfigG);

        //Recupera los productos que tiene instalados
        $misproductos="";
        $strSqlG = "select idapp from appclient where idcustomer=$idcustomer group by idapp";
        $rstWebconfigG = mysqli_query($objConG, $strSqlG);
        while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
            $misproductos=$misproductos.$objWebconfigG['idapp']."|";
        }
        unset($objWebconfigG);
        mysqli_free_result($rstWebconfigG);
        unset($rstWebconfigG);

        mysqli_close($objConG);
        unset($strSqlG);
    }
    session_start();
    $_SESSION['bd']=$bd;
    $_SESSION['version']=$version;
    $_SESSION['misproductos']=$misproductos;
    $_SESSION['accelog_variable']=$accelog_variable;
    $_SESSION['estatus_cobranza']=$estatus_cobranza;
    $pathcliente = "/warehouse/" . $strInstanciaG . "/";
    $tipobd	= "mysql";
    $instalarbase= "0";
    $link_regreso= "../accelog/";
    $link_gestor = "catalog/gestor.php";
    $crear_tablas_organizacion_empleados = 0;
    $crear_tablas = 0;
    $accelog_salt = "$2a$07$".$accelog_variable."aaaaaaa$";
    $tabla_organizacion = "organizaciones";
    $campo_idorganizacion = "idorganizacion";
    $campo_nombre_org = "nombreorganizacion";
    $idestructura_organizacion = "1";
    $tabla_empleados = "empleados";
    $campo_idempleado = "idempleado";
    $campo_nombre_emp = "nombre";
    $campo_paterno_emp = "apellido1";
    $campo_materno_emp = "apellido2";
    $idestructura_empleados = "2";
    $super_usu = "yoda";
    $super_pwd = "vader";
    $super_perfil = "NMPerfil";
    $super_nombre_org = "eNFoto";
    $super_idorganizacion = "1";
    $super_idempleado = "1";
    $super_nombre = "Yoda";
    $super_paterno = "De";
    $super_materno = "Kana";
    $permiso_otras_organizaciones_desc = "Permitir el acceso a otras organizaciones.";
    $permiso_otras_organizaciones_id = "NMOTRAS_ORG";
    $url_repolog_para_accelog = "../repolog/";
    $instalarbase_repolog = "0";
    $fase_desarrollo = 1;
    $tamano_buffer = "120M";
    $url_repolog="../repolog/";
    $url_catalog = "../catalog/";
    $url_doclog_para_accelog = "../doclog/";
    $link_gestor_doclog = "doclog/abrir.php";
    $filas_pagina = 11;
    $netwarelog_correo_usu = "soporte@netwaremonitor.com";
    $netwarelog_correo_pwd = "&=98+69netware";
    $tiempo_timeout = 10000;

    global $accelog_salt_perfil;
    $accelog_salt_perfil = $accelog_salt;
    
    // configuracion 
    if((!isset($_REQUEST["netwarstore"]) && !isset($_REQUEST["_tipo"])) || (isset($_REQUEST["_tipo"]) && $_REQUEST["_tipo"] != "api")) include "accelog.php";

?>
