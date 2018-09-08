<?php
    
    if($a==1){
        //Inicializa Variables
        $nombre="";
        $apellido1="";
        $apellido2="";
        $visible=-1;
        $administrador=0;
        $usuario="";
        $clave="";

        //Recupera Datos del Registro 
                $sQuery = "SELECT * FROM administracion_usuarios WHERE idadmin='".$catalog_id_utilizado."'";
                $result = $conexion->consultar($sQuery);
                while($rs = $conexion->siguiente($result)){
                    $nombre=$rs{"nombre"};
                    $apellido1=$rs{"apellidos"};
                    $apellido2="";
                    $usuario=$rs{"nombreusuario"};
                    $clave=$rs{"clave"};
                    $idper=$rs{"idperfil"};
								}
								$conexion->cerrar_consulta($result);
								
								$sqlquitaclave = "update administracion_usuarios set ";
								$sqlquitaclave.= "  clave='nolacambies_mlog_1-oct-2013', ";
								$sqlquitaclave.= "  confirmaclave='nolacambies_mlog_1-oct-2013' ";
								$sqlquitaclave.= "  where idadmin='".$catalog_id_utilizado."' ";
								$conexion->consultar($sqlquitaclave);
								//error_log($sqlquitaclave);
								//echo $sqlquitaclave;

                
        //Agrega Registro a Empleados
            $sqlin="Insert Into empleados (nombre,apellido1,apellido2,visible,administrador,idorganizacion) 
                    values ('".$nombre."','".$apellido1."','".$apellido2."',$visible,$administrador,$idorganizacion) ";
        //echo "$sqlin";
            $conexion->consultar($sqlin);
            $idnewemp = $conexion->insert_id();
        //Agrega registro a Usuarios
            $sqlin="Insert Into accelog_usuarios (idempleado,usuario,clave) 
                    values ('".$idnewemp."','".$usuario."','".$conexion->fencripta($clave,$accelog_salt)."') ";
        //echo "$sqlin";
            $conexion->consultar($sqlin);

        //Asigna Perfiles
        if($_POST['perfil']==2)
        {
            $perfil=$_POST['perfil'];    
            $sqlin="Insert Into accelog_usuarios_per (idperfil,idempleado) 
                    values ('".$perfil."','".$idnewemp."') ";
            $conexion->consultar($sqlin);
        }
            else
            {
              foreach($_POST['perfiles'] as $valor) 
            { 
                 $sqlin="Insert Into accelog_usuarios_per (idperfil,idempleado) 
                    values ('".$valor."','".$idnewemp."') ";
                    $conexion->consultar($sqlin);

            }
            }
        
            
        //echo "$sqlin";
         
        //Edita Administracion
//            $sqlin="update administracion_usuarios set idempleado='$idnewemp'";
            $sqlin="update administracion_usuarios set idempleado='".$idnewemp."' where nombreusuario='".$usuario."'";
        //echo "$sqlin";
            $conexion->consultar($sqlin);
            //Aqui guarda en las tablas people y employees del POS
		if($_SESSION['idestructura']==47){
		$idposquery = "select idadmin from administracion_usuarios where nombreusuario='".$_POST['i198']."'";	
		//$idposquery = "select idadmin from administracion_usuarios where nombreusuario='".$catalog_id_utilizado."'";	
		//echo $sql;
		$idposquery2 = $conexion->consultar($idposquery);
		$idposres = mysql_fetch_array($idposquery2);
				
		$sqlp = "insert into people values ('".$_POST['i196']."','".$_POST['i197']."','---','".$_POST['i201']."','---','---','---','---','---','---','---',".$idposres['idadmin'].")";	
		$conexion->consultar($sqlp);
        $sqlp = "insert into rest_people values ('".$_POST['i196']."','".$_POST['i197']."','---','".$_POST['i201']."','---','---','---','---','---','---','---',".$idposres['idadmin'].")";  
        $conexion->consultar($sqlp);
		$pass=md5($_POST['i199']);
		$sqle = "insert into employees values ('".$_POST['i198']."','$pass',".$idposres['idadmin'].",0)";	
		$conexion->consultar($sqle);
        $sqle = "insert into rest_employees values ('".$_POST['i198']."','$pass',".$idposres['idadmin'].",0)";   
        $conexion->consultar($sqle);

		}


		//----------------------------------------------------------------- EDICION
    }else{
        
        $nombre="";
        $apellido1="";
        $apellido2="";
        $visible=-1;
        $administrador=0;
        $usuario="";
        $clave="";
				$correo="";

        //Recuperar el idadmin
        /*
            $sQuery = "SELECT * FROM administracion_usuarios WHERE nombreusuario='".$catalog_id_utilizado."'";
            $result = $conexion->consultar($sQuery);            
            if($rs = $conexion->siguiente($result)){
                $catalog_id_utilizado = $rs{"idadmin"};
            }
            $conexion->cerrar_consulta($result);
        */


        //Recupera Datos del Registro 
                $sQuery = "SELECT * FROM administracion_usuarios WHERE idadmin='".$catalog_id_utilizado."'";
								error_log($sQuery);
                $result = $conexion->consultar($sQuery);
                while($rs = $conexion->siguiente($result)){
										//error_log("entre");
                    $nombre=$rs{"nombre"};
                    $apellido1=$rs{"apellidos"};
                    $apellido2="";
                    $usuario=$rs{"nombreusuario"};
                    $clave=$rs{"clave"};
                    $idper=$rs{"idperfil"};
                    $idemp=$rs{"idempleado"};
										$correo=$rs{"correoelectronico"}; 
								}
								$conexion->cerrar_consulta($result);        

				//edita Administración Usuarios

				$sql_modifica_clave = "";
				$sql_modifica_password = "";
	
				if($clave!="nolacambies_mlog_1-oct-2013"){

					$clave = $conexion->fencripta($clave,$accelog_salt);
					$sql_modifica_clave = ", clave='".$clave."' ";
					$sql_modifica_password = ", password='".$clave."' ";
			
				} 
					
				//edita Empleados
        $sqlin="update accelog_usuarios set usuario='".$usuario."' ".$sql_modifica_clave."  where idempleado='".$idemp."'";
        $conexion->consultar($sqlin);
          
        $sqlin="update accelog_usuarios_per set idperfil='".$idper."' Where idempleado='".$idemp."'";
        error_log($sqlin);
       	$conexion->consultar($sqlin);

        $sqlin="update empleados set nombre='".$nombre."', apellido1='".$apellido1."', administrador=0  where idempleado='".$idemp."'";
       	$conexion->consultar($sqlin);


        //Edita Usuarios
        if($_SESSION['idestructura']==47){
        	$idemp=$idemp-1;
					$sqle="update employees set username='$usuario' ".$sql_modifica_password."  where person_id='$idemp'";
          $conexion->consultar($sqle);
					$sqle="update rest_employees set username='$usuario' ".$sql_modifica_password."  where person_id='$idemp'";
          $conexion->consultar($sqle);	
					$sqle="update people set first_name='".$nombre."', last_name='".$apellido1."',email='".$correo."'  where person_id='$idemp'";
          $conexion->consultar($sqle);
					$sqle="update rest_people set first_name='".$nombre."', last_name='".$apellido1."',email='".$correo."'  where person_id='$idemp'";
          $conexion->consultar($sqle);	
				}


				$sqlquitaclave = "update administracion_usuarios set ";
				$sqlquitaclave.= "  clave='nolacambies_mlog_1-oct-2013', ";
				$sqlquitaclave.= "  confirmaclave='nolacambies_mlog_1-oct-2013' ";
				$sqlquitaclave.= "  where idadmin='".$catalog_id_utilizado."' ";
				$conexion->consultar($sqlquitaclave);



    }
                
?>
