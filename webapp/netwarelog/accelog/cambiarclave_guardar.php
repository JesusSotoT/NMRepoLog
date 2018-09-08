<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    include "webconfig_accelog.php";

    //session_start();

		//CSRF
		$reset_vars = false;
		include("../catalog/clases/clcsrf.php");	
		if(!$csrf->check_valid('post')){
				$accelog_access->raise_404(); 
				exit();
		}

    $login = $_SESSION["accelog_login"];
    $claveactualbase = "";
    $claveactual = $conexion->fencripta($_POST['txtpwdactual'],$accelog_salt);
    $clavenueva = $conexion->fencripta($_POST['txtpwdnueva'],$accelog_salt);
    $claveconfirmar = $conexion->fencripta($_POST['txtpwdconfirmar'],$accelog_salt);


    $sql = " select clave from accelog_usuarios where usuario = '".$login."' ";
    $result = $conexion->consultar($sql);
    if(($rs=$conexion->siguiente($result))){
        $claveactualbase = $rs{'clave'};
    }
    $conexion->cerrar_consulta($result);


    //CONDICIONES
    $condiciones = true;
    if($clavenueva!=$claveconfirmar){
         header("location: cambiarclave.php?p=1");
         $condiciones = false;
    } else {
        if($claveactual!=$claveactualbase){
             header("location: cambiarclave.php?e=1");
             $condiciones = false;
        }
    }

    

    //SI LA CONTRASEÑA ACTUAL ESTA BIEN ENTONCES
    if($condiciones){
        $sql = " update accelog_usuarios set clave = '".$clavenueva."' where usuario ='".$login."' ";
        $conexion->consultar($sql);
        ?>
            <html>
                <body>
                    <img src="img/ok.png" title="http://www.pixel-mixer.com/">
                    <font color="gray" face="Tahoma,Arial" size="2">
                      Clave modificada con éxito.
                    </font>
                </body>
            </html>
        <?php
    }


?>
