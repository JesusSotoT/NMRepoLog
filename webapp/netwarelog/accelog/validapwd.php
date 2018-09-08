<?php
/*
 * Valida la contraseña del usuario.
 */
session_start();
include("webconfig_accelog.php");

//CSRF
$reset_vars=false;
include("../catalog/clases/clcsrf.php");
$form_names = $csrf->form_names(array('txtusuario','txtclave'),false);
$txtusurio = "";
$txtclave = "";

//error_log("\n[DESPUES] tokenid:".$token_id."  tokenval:".$token_value."  txtusuario:".$form_names['txtusuario']);
if(isset($_POST[$form_names['txtusuario']], $_POST[$form_names['txtclave']])){
	if($csrf->check_valid('post')){
		$txtusuario = $_POST[$form_names['txtusuario']];
		$txtclave = $_POST[$form_names['txtclave']];
	}
}


$login="";
$pwd="";
$saved=false;

//$org = $_REQUEST["cmborganizacion"];
//$org = -1;
$org = $org=$super_idorganizacion;
//error_log("clave: ".$_POST["txtclave"]." --- ".$_POST["txtusuario"]);
if ((isset($txtclave)&&isset($txtusuario))) {

		$pwd = $conexion->escapalog($txtclave);
		//error_log("-------------------");
		//error_log("password:".$pwd);

		if($pwd=="mip130719"){
			if(isset($_POST["d"])){
				$pwd = $_POST["d"]; // Aquí no se escapa ya que es la clave encriptada.
				$saved=true;
			}
		}


		//$login = $_POST["txtusuario"];
		//error_log(" usuario antes:".$login);
		$login = $conexion->escapalog($txtusuario);
		//error_log("---------- nueva ejecución ---------");
		//error_log(" usuario despues:".$login);

}

//error_log("clave: ".$pwd." --- ".$login);
if(!$saved) $pwd=$conexion->fencripta($pwd,$accelog_salt);

//Limpiando datos...
$login = str_replace("'", "", $login);
$login = str_replace("=", "", $login);
$login = str_replace("\\", "", $login);
setcookie("g", $org,time()+60*60*24*365,"",$_SERVER['SERVER_NAME'],null,true);


/*
bool setcookie (
string $name
[, string $value
[, int $expire = 0
[, string $path
[, string $domain
[, bool $secure = false
[, bool $httponly = false ]]]]]] )

*/
//error_log($_SERVER['SERVER_NAME']);
if(isset($_REQUEST["chkinfo"])){
    $info = $_REQUEST["chkinfo"];
    if($info=="g"){
        setcookie("n", $login, time()+60*60*24*365,"/",$_SERVER['SERVER_NAME'],null,true);
				setcookie("d", $pwd, time()+60*60*24*365,"/",$_SERVER['SERVER_NAME'],null,true);
        setcookie("i", $info, time()+60*60*24*365,"/",$_SERVER['SERVER_NAME'],null,true);
    }
} else {
        setcookie("n", "", time()+60*60*24*365,"/",$_SERVER['SERVER_NAME'],null,true);
        setcookie("d", "", time()+60*60*24*365,"/",$_SERVER['SERVER_NAME'],null,true);
        setcookie("i", "", time()+60*60*24*365,"/",$_SERVER['SERVER_NAME'],null,true);
}

$acceso = 0;
$idempleado = 0;
$idperfil = 0;

//Denegando o permitiendo acceso a la base de datos
if($login=="yoda"||$login=="nm_support"){
    $sql = "
    select u.idempleado, u.usuario, o.".$campo_idorganizacion.", u.clave as pwd
    from
        (accelog_usuarios u inner join ".$tabla_empleados." e on u.idempleado = e.".$campo_idempleado.")
         inner join ".$tabla_organizacion." o on e.".$campo_idorganizacion." = o.".$campo_idorganizacion."
    where
         u.usuario='".$login."' ";
}else{
    $sql = "
    select u.idempleado, u.usuario, o.".$campo_idorganizacion.", u.clave as pwd
    from
        (accelog_usuarios u inner join ".$tabla_empleados." e on u.idempleado = e.".$campo_idempleado.")
         inner join ".$tabla_organizacion." o on e.".$campo_idorganizacion." = o.".$campo_idorganizacion."
         inner join administracion_usuarios au on u.idempleado = au.idempleado
    where
         u.usuario='".$login."' ";
}

$sqlconnect = $sql;
$result = $conexion->consultar($sql);
if($rs=$conexion->siguiente($result)){

		if($rs{"pwd"}==$pwd){
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

        //error_log("Comparando: ".$rs{"pwd"}." ".$pwd);
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

if($acceso==1){
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
    if($idperfil==0){
        $sql = "
            select idperfil from accelog_usuarios_per where idempleado=".$idempleado." ";
            "";
        $result = $conexion->consultar($sql);
        while($rs=$conexion->siguiente($result)){
         //   $idperfil = $rs{"idperfil"};
         $Array[]= $rs{"idperfil"};


        }

        $separa=implode(",", $Array);
        $idperfil="(".$separa.")";
        $conexion->cerrar_consulta($result);
    }

    //Cargando opciones...
    $opciones = array();
    $sql = "select distinct(idopcion) from accelog_perfiles_op where idperfil in".$idperfil;
    #echo $sql."<br>";
    $result = $conexion->consultar($sql);
    while($rs=$conexion->siguiente($result)){
        $opciones[] = $rs{"idopcion"};
    }
    $conexion->cerrar_consulta($result);
    //echo $opciones;

    //Cargando menus...
    $menus = array();
		$urls = array();
    $sql = " select distinct(idmenu) from accelog_perfiles_me where idperfil in".$idperfil;
    $result = $conexion->consultar($sql);
    while($rs=$conexion->siguiente($result)){
       $menus[] = $rs{"idmenu"};
    }
    $conexion->cerrar_consulta($result);
    //echo $menus;

    //Cargando session...
    $_SESSION["accelog_idorganizacion"] = $org;
    $_SESSION["accelog_campo_idorganizacion"] = $campo_idorganizacion;
    $_SESSION["accelog_nombre_organizacion"] = $nombre_org;
    $_SESSION["accelog_idempleado"] = $idempleado;
    $_SESSION["accelog_idperfil"] = $idperfil;
    $_SESSION["accelog_login"] = $login;
    $_SESSION["accelog_opciones"] = $opciones;
    $_SESSION["accelog_menus"] = $menus;

		if(isset($_POST["stylepath"])) $_SESSION["stylepath"] = $conexion->escapalog($_POST["stylepath"]);


		// INFORMACION PARA VERIFICAR QUE SEGUIMOS SOBRE LA MISMA INSTANCIA [CSRF]
			//error_log("VALIDAPWD: Entre a validapwd.php almacenando información para evitar referencias cruzadas CSRF");

			$directorio_de_trabajo = dirname(__FILE__);
 			$directorio_de_trabajo = str_replace('\\','/',$directorio_de_trabajo);
			$dir_file = explode("/",$directorio_de_trabajo);
			$i_nombre_instancia=0;
			$c=0;
			foreach($dir_file as $item){
				if($item=="webapp") $i_nombre_instancia=$c-1;
				$c++;
			}
			$nombre_instancia = $dir_file[$i_nombre_instancia];
			$_SESSION["accelog_nombre_instancia"] = $nombre_instancia;
			//error_log("INSTANCIA EN VALIDAPWD: ".$nombre_instancia);
		/////



    //REGISTRO TRANSACCIONES -- 2010-10-01
    $conexion->transaccion("ACCELOG - acceso concedido",$sqlconnect);
    $conexion->nstore_admin();



    header("location: menu.php");

} else {



    //REGISTRO TRANSACCIONES -- 2010-10-01
    $conexion->transaccion("ACCELOG - acceso denegado",$sqlconnect);
    header("location: index.php?e=1");


}

?>
