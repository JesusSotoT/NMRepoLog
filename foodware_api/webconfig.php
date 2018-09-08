<?php

    ini_set('session.cookie_httponly',1);
    set_time_limit(3600);

    global $servidor, $usuariobd, $clavebd, $bd, $tipobd;

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

    if(array_search('foodware_api',$arrInstanciaG) !=0){
        $strInstanciaG = $arrInstanciaG[array_search('foodware_api',$arrInstanciaG) - 1];
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

    $_SESSION['bd'] = $bd;
    $tipobd = "mysql";
    global $accelog_salt;
    $accelog_salt = "$2a$07$".$accelog_variable."aaaaaaa$";
    global $tabla_organizacion;
    $tabla_organizacion = "organizaciones";
    global $campo_idorganizacion;
    $campo_idorganizacion = "idorganizacion";
    global $campo_nombre_org;
    $campo_nombre_org = "nombreorganizacion";
    global $tabla_empleados;
    $tabla_empleados = "empleados";
    global $campo_idempleado;
    $campo_idempleado = "idempleado";
    global $super_idorganizacion;
    $super_idorganizacion = "1";

?>
