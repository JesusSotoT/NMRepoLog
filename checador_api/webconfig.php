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
    }elseif($_SERVER['SERVER_NAME']=="localhost" || $_SERVER['SERVER_NAME']=="192.168.1.52"){
            $servidor  = "192.168.1.11";
            $usuariobd = "nmdevel";
            $clavebd = "nmdevel";
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

    if(array_search('checador_api',$arrInstanciaG) !=0){
        $strInstanciaG = $arrInstanciaG[array_search('checador_api',$arrInstanciaG) - 1];
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

    $tipobd	= "mysql";
    $accelog_salt = "$2a$07$".$accelog_variable."aaaaaaa$";

?>
