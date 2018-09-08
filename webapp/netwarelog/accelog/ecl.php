<?php


		//error_log("I N I C I O");


    include "../catalog/conexionbd.php";
		session_start();

		//CSRF
		$reset_vars = false;
		include("../catalog/clases/clcsrf.php");	
		//error_log("recibi token: ".$token_id." con el valor ".$token_value);
		if(!$csrf->check_valid('post')){
				echo "Problema con la recuperación.";
				exit;
		}

		//error_log("token valido");
		$correo = $conexion->escapalog($_POST["c"]);
		//error_log("revisando el correo: ".$correo);
		$sql = "select nombreusuario from administracion_usuarios where correoelectronico='".$correo."'";	
		//error_log($sql);
		$result = $conexion->consultar($sql);
		if($rs=$conexion->siguiente($result)){
			//error_log("Correo encontrado");
			//error_log("Creando la contraseña");
			if($accelog_access->envia_clave_temporal($rs{"nombreusuario"},$accelog_salt,$conexion,$correo,$netwarelog_correo_usu,$netwarelog_correo_pwd)){
				$mensaje="";
				$mensaje.="Se ha enviado un correo electrónico a: [".$correo."], ";
				$mensaje.="donde aparecerá una contraseña temporal, una vez que logre ";
				$mensaje.="acceder al sistema favor de modificarla.";
			} else {
				$mensaje="Problema con el envio";
			}
		}else{
			//error_log("Correo no encontrado");
			$mensaje="El correo no ha sido encontrado en el registro de usuarios.";
		}

		$conexion->cerrar();
		//error_log("base de datos cerrada");

		echo $mensaje;
?>
