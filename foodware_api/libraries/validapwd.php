<?php

    include_once('libraries/conexionbd.php');

    /*
     * Valida la contraseña del usuario.
     */
    session_start();

    $txtusuario = $_REQUEST['txtusuario'];
    $txtclave = $_REQUEST['txtclave'];


    $login = $txtusuario;
    $pwd = $txtclave;
    $saved = false;

    $org = $org = $super_idorganizacion;

    if(!$saved) $pwd = $conexion->fencripta($pwd, $accelog_salt);

    $acceso = 0;
    $idempleado = 0;
    $idperfil = 0;

    //Denegando o permitiendo acceso a la base de datos
    $sql = "
    select u.idempleado, u.usuario, o.".$campo_idorganizacion.", u.clave as pwd
    from
        (accelog_usuarios u inner join ".$tabla_empleados." e on u.idempleado = e.".$campo_idempleado.")
         inner join ".$tabla_organizacion." o on e.".$campo_idorganizacion." = o.".$campo_idorganizacion."
         inner join administracion_usuarios au on u.idempleado = au.idempleado
    where
         u.usuario='".$login."' ";

    $sqlconnect = $sql;
    $result = $conexion->consultar($sql);
    if($rs = $conexion->siguiente($result)){
    	if($rs{"pwd"} == $pwd){
    	    $acceso = 1;
    	    $idempleado = $rs{"idempleado"};
    	    $org = $rs{"idorganizacion"};
    	} else {
    		$acceso = 0;
    	}
    } else {
        #Checking Empleado 1 user_master --- usuario master
        $sql = "
        select u.idempleado, u.usuario, o.".$campo_idorganizacion.", u.clave as pwd
        from
            (accelog_usuarios u inner join ".$tabla_empleados." e on u.idempleado = e.".$campo_idempleado.")
             inner join ".$tabla_organizacion." o on e.".$campo_idorganizacion." = o.".$campo_idorganizacion."
        where
             u.usuario='".$login."' and u.idempleado=1 ";
        $conexion->cerrar_consulta($result);
        $result = $conexion->consultar($sql);
        if($rs=$conexion->siguiente($result)){
            if($rs{"pwd"}==$pwd){
                $acceso = 1;
                $idempleado = $rs{"idempleado"};
                $org = $rs{"idorganizacion"};
            } else {
                $acceso = 0;
            }
        }
    }
    $conexion->cerrar_consulta($result);

    if($acceso == 1){
        //Obteniendo el nombre de la organización...
        $nombre_org = "";
        $sql = "
            select ".$campo_idorganizacion.", ".$campo_nombre_org."
            from ".$tabla_organizacion."
            where ".$campo_idorganizacion."=".$org."
             ";
        $result = $conexion->consultar($sql);
        while($rs=$conexion->siguiente($result)){
            $nombre_org = $rs{$campo_nombre_org};
        }
        $conexion->cerrar_consulta($result);

        //Obteniendo perfil...
        if($idperfil == 0){
            $sql = "
                select idperfil from accelog_usuarios_per where idempleado=".$idempleado." ";
                "";
            $result = $conexion->consultar($sql);
            while($rs=$conexion->siguiente($result)){
            
            $Array[] = $rs{"idperfil"};


            }

            $separa = implode(",", $Array);
            $idperfil = "(".$separa.")";
            $conexion->cerrar_consulta($result);
        }

        //Cargando opciones...
        $opciones = array();
        $sql = "select distinct(idopcion) from accelog_perfiles_op where idperfil in".$idperfil;
        
        $result = $conexion->consultar($sql);
        while($rs=$conexion->siguiente($result)){
            $opciones[] = $rs{"idopcion"};
        }
        $conexion->cerrar_consulta($result);

        //Cargando session...
        $_SESSION["accelog_idorganizacion"] = $org;
        $_SESSION["accelog_campo_idorganizacion"] = $campo_idorganizacion;
        $_SESSION["accelog_nombre_organizacion"] = $nombre_org;
        $_SESSION["accelog_idempleado"] = $idempleado;
        $_SESSION["accelog_idperfil"] = $idperfil;
        $_SESSION["accelog_login"] = $login;
        $_SESSION["accelog_opciones"] = $opciones;
        $_SESSION["accelog_menus"] = $menus;

    	if(isset($_REQUEST["stylepath"])) $_SESSION["stylepath"] = $conexion->escapalog($_REQUEST["stylepath"]);

    	// INFORMACION PARA VERIFICAR QUE SEGUIMOS SOBRE LA MISMA INSTANCIA [CSRF]	
    	$servidor = explode("/", $_SERVER['REQUEST_URI']);
        $nombre_instancia = $servidor[array_search('clientes', $servidor) + 1];
    	$_SESSION["accelog_nombre_instancia"] = $nombre_instancia;

    } else {

    }

?>
