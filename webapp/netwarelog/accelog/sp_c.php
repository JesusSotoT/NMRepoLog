<?php 
	session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
				ini_set('session.cookie_httponly',1);


        $org = $_POST["o"];
        $nombre_org = $_POST["n"];
        $log = $_POST["l"];
        $emp = $_POST["e"];
        $idperfil=$_POST["per"];
        $campo_idorganizacion=$_POST["cio"];
				if(isset($_POST["url_vinc"])){
					$url=$_POST["url_vinc"];
				} else {
					$url="";
				}

				// obteniendo datos de la sesion

					//session_start();
					if(!isset($_SESSION["accelog_idorganizacion"])) header("location: salir.php"); //$accelog_access->raise_404();
        	$opciones = $_SESSION["accelog_opciones"];

					$menus=array();
					$menus = $_SESSION["accelog_menus"];


					//Agregando la url valida
					require_once "../accelog_claccess.php";
					$accelog_access = new claccess();

					$accelog_access->add_url($url);
					$urls = array();
					$urls = $_SESSION["accelog_urls"];
					//echo implode(",",$urls);
					//echo "-".implode("<br> --- ",$_SESSION["accelog_menus_urls"])."-";

					//Control de tabs
					if(isset($_SESSION["accelog_tabs"])){
						$tabs = $_SESSION["accelog_tabs"];
					} else {
						$tabs = array();
					}

					//error_log("cambiando el id de sesion");
					$accelog_nombre_instancia = $_SESSION["accelog_nombre_instancia"];

					session_write_close();
				/////

        $p = $_POST["p"];
				$tabs[] = $p;

        session_id($p);
        session_start();

        $_SESSION["accelog_idorganizacion"]=$org;
        $_SESSION["accelog_campo_idorganizacion"] = $campo_idorganizacion;
        $_SESSION["accelog_nombre_organizacion"]=$nombre_org;
        $_SESSION["accelog_login"]=$log;
        $_SESSION["accelog_idempleado"] = $emp;
        $_SESSION["accelog_idperfil"]=$idperfil;

				$_SESSION["accelog_tabs"]=$tabs;
				$_SESSION["accelog_urls"]=$urls;
				$_SESSION["accelog_nombre_instancia"]=$accelog_nombre_instancia;



        /*
        //Cargando opciones...
        $opciones = array();
        $sql = " select idopcion from accelog_perfiles_op where idperfil = ".$idperfil;
        $result = $conexion->consultar($sql);
        while($rs=$conexion->siguiente($result)){
            $opciones[] = $rs{"idopcion"};
        }

        //Cargando menus...
        $menus = array();
        $sql = " select idopcion from accelog_perfiles_me where idperfil = ".$idperfil;
        $result = $conexion->consultar($sql);
        while($rs=$conexion->siguiente($result)){
           $menus[] = $rs{"idmenu"};
        }*/

        $_SESSION["accelog_opciones"] = $opciones;
        $_SESSION["accelog_menus"] = $menus;

        //echo "cargue opciones y menus ";

       //$_SESSION["acceso"] = valor especifico

?>
