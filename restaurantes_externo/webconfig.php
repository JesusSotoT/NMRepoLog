<?php
ini_set('session.cookie_httponly',1);
set_time_limit(3600);

//CONFIGURACION CATALOG Y BASE DE DATOS ///////////////////////////////////////////////////////////////////////////////////////////////

//Tipo de servidor de base de datos
//mysql --> Servidor de MySQL
//mssql --> Servidor de Microsoft SQL Server
$tipobd	   = "mysql";

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

if(array_search('facturar',$arrInstanciaG)!=0 || array_search('restaurantes_externo',$arrInstanciaG)!=0){
    $strInstanciaG = $arrInstanciaG[array_search('facturar',$arrInstanciaG) - 1];
    $strInstanciaG = $arrInstanciaG[array_search('restaurantes_externo',$arrInstanciaG) - 1];

	// echo "instancia: ".$strInstanciaG;
}else{
    $strInstanciaG = $arrInstanciaG[array_search('webapp',$arrInstanciaG) - 1];
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
//Servidor
// $servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com"; //"54.200.46.66";

//Usuario para conectarse a MySQL
// $usuariobd = "nmdevel"; //"_umlog0000000075"; //"_umlog0000000067";//"nmdevel";

//Clave del usuario anterior
// $clavebd   = "nmdevel"; //"_pmlog00000000754452"; //"_pmlog00000000676928";//"nmdevel";

//Nombre de la base de datos: netwaremonitor cambielo por el nombre real.
// $bd	   = "nmdev"; //"_dbmlog0000000075"; //"_dbmlog0000000067"; //"nmdev";
//$bd	   = "_dbmlog0000000879"; //"_dbmlog0000000075"; //"_dbmlog0000000067"; //"nmdev";

//$bd	   = "test_coras"; //"_dbmlog0000000075"; //"_dbmlog0000000067"; //"nmdev";


//INSTALACION DE LA BASE PARA CATALOG
// $instalarbase	= "0";
//Si es la primera vez que abrirá catalog en este proyecto
//Dejelo con el 1, posterior a la apertura puede cambiarlo a 0.
//Esta señal crea las tablas necesarias en la base automáticamente.
//NOTA: El usuario debe tener permisos para crear tablas.

//LINK DE REGRESO
$link_regreso	= "../accelog/";
//Cuando hagan click en el botón de regresar
//el sistema buscará este link.

//LINK DEL GESTOR
$link_gestor    = "catalog/gestor.php";
//Este es el link que se grabara en la base de datos para llamar al gestor.




//CONFIGURACION ACCELOG /////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Se crearan las tablas en caso de no existir de organizacion y empleados
//con los parametros establecidos a continuación.
$crear_tablas_organizacion_empleados = 0;

//Esta variable indica que se generen las tablas y la información necesaria de catalog/accelog/repolog ...
$crear_tablas = 0;

//SALT para crear contraseñas con el algoritmo blow_fish más información en:

// ACCELOG_SALT ////////////////////////////////////////////////////////////
// Esta variable tendrá un elemento random y el nombre del dominio
// No debe exceder de los 14 caracteres y deberán estar comprendidos
// entre 0-9, A-Z, a-z, esto con el fin de utilizar el estándar blue_fish
$accelog_variable = "netappmitranetwarelog1"; //"0000000067mlog";
$accelog_salt = "$2a$07$".$accelog_variable."$";

/*$accelog_variable = "0000000014mlog";
$accelog_salt = "$2a$07$".$accelog_variable."$";*/


///////////////////////////////////////////////////////////////////////////


//******************************
// DATOS TABLA DE ORGANIZACION
$tabla_organizacion = "organizaciones";
$campo_idorganizacion = "idorganizacion";
$campo_nombre_org = "nombreorganizacion";
$idestructura_organizacion = "1";


//******************************
// DATOS TABLA DE EMPLEADOS
$tabla_empleados = "empleados";
$campo_idempleado = "idempleado";
$campo_nombre_emp = "nombre"; //Nombre empleado
$campo_paterno_emp = "apellido1"; //Nombre empleado
$campo_materno_emp = "apellido2"; //Nombre empleado
$idestructura_empleados = "2";



//******************************
// SUPERUSUARIO
//
// NOTA: Este usuario se creará al instalar el sistema posteriormente con el usuario y clave señalados
// La primera vez que se entra al sistema se puede cambiar la contraseña.
$super_usu = "yoda";
$super_pwd = "vader";
$super_perfil = "NMPerfil";

//Datos del empleado que es el super usuario
$super_nombre_org = "eNFoto";
$super_idorganizacion = "1";
$super_idempleado = "1";
$super_nombre = "Yoda";
$super_paterno = "De";
$super_materno = "Kana";


//***************************************************
$permiso_otras_organizaciones_desc = "Permitir el acceso a otras organizaciones.";
$permiso_otras_organizaciones_id = "NMOTRAS_ORG";




//CONFIGURACION REPOLOG /////////////////////////////////////////////////////////////////////////////////////////////////////////////

$url_repolog_para_accelog = "../repolog/";

// En este caso esta en 0 asumiendo que accelog ya lo instala.
$instalarbase_repolog = "0";

$fase_desarrollo = 1;

//Tamaño de archivos pdf y correo electrónico, por omisión 120 Mb.
$tamano_buffer = "120M";

//URL de estilo por omisión para correos
//$url_estilo = "http://www.enfoto.com.mx/webapp/netwarelog/utilerias/"; 2013-10-02


//CONFIGURACIONES GENERALES /////////////////////////////////////////////////////////////////////////////////////////////////////////////

$url_repolog="../repolog/";

$url_catalog = "../catalog/";

//$url_dominio="http://proasa.enetmax.com/"; 2013-10-02


//CONFIGURACION DOCLOG /////////////////////////////////////////////////////////////////////////////////////////////////////////////

$url_doclog_para_accelog = "../doclog/";
$link_gestor_doclog    = "doclog/abrir.php";


// PAGINACION DEL CATALOG Y DOCLOG /////////////////////////////////////////////////////////////////////////////////////////////////
$filas_pagina = 11;



// CONFIGURACION PARA EL ENVIO DE CORREOS ///////////////////////////////////////////////////////////////////////////////////////
$netwarelog_correo_usu = "soporte@netwaremonitor.com";
$netwarelog_correo_pwd = "&=98+69netware";


$tiempo_timeout = 10000;



//VALIDANDO ACCESO A LA OPCION
include "accelog.php";
/////


?>
