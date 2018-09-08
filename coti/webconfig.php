<?php
ini_set('session.cookie_httponly',1);
set_time_limit(3600);

//determinando el servidor
if($_SERVER['SERVER_NAME']=="edu.netwarmonitor.com"){
        $servidor ="unmdbplus.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
        $usuariobd="unmdev";
        $clavebd="&=98+69unmdev";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
}elseif($_SERVER['SERVER_NAME']=="localhost"){
        $servidor ="192.168.1.11";
        $usuariobd="nmdevel";
        $clavebd="nmdevel";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
}else{
        $servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
        $usuariobd = "nmdevel";
        $clavebd = "nmdevel";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
}

$arrInstanciaG = explode("/",$_SERVER['REQUEST_URI']);

if(array_search('coti',$arrInstanciaG) !=0){
    $strInstanciaG = $arrInstanciaG[array_search('coti',$arrInstanciaG) - 1];
}else{
    exit();
}

if($strInstanciaG=="mlog"){
    $usuariobd = "nmdevel";
    $clavebd = "nmdevel";
    $bd = "nmdev";
    $accelog_variable = "netappmitranetwarelog1";
}else {
    $objConG = mysqli_connect($servidor,$usuariobd , $clavebd, "netwarstore");
    $strSqlG = "SELECT * FROM customer WHERE instancia = '" . $strInstanciaG . "';";
    $rstWebconfigG = mysqli_query($objConG, $strSqlG);
    while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
        $usuariobd = $objWebconfigG['usuario_db'];
        $clavebd = $objWebconfigG['pwd_db'];
        $bd = $objWebconfigG['nombre_db'];
        $accelog_variable = str_replace('_dbmlog', '', $objWebconfigG['nombre_db']) . "mlog";
    }
    unset($objWebconfigG);
    mysqli_free_result($rstWebconfigG);
    unset($rstWebconfigG);
    mysqli_close($objConG);
    unset($strSqlG);
}

$_SESSION['bd']=$bd;
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
//include "accelog.php";
?>
