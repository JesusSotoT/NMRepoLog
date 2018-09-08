<?php

	include("clases/clcontroles.php");
	include("clases/clutilerias.php");

	
	$utilerias = new utilerias();


	include("conexionbd.php");

	//CSRF
	$reset_vars = false;
	include("clases/clcsrf.php");	
	if(!$csrf->check_valid('post')){
			$accelog_access->raise_404(); 
			exit();
	}


	if(session_id()=='') {
    session_start();
	}	
        
        //PARCIALLOG
        include("clases/clparciallog.php");
        $parciallog = new clparciallog($_SESSION['nombreestructura'],$_SESSION["accelog_idperfil"],$conexion);
        
   	
	$a = $_SESSION['catalog_nuevo'];
	$nombreestructura = $_SESSION['nombreestructura'];
	$controles = $_SESSION['controles'];
	//var_dump($controles);
	//echo $controles;
	$nombrescampos = $controles->getcampos();

        $utilizaidorganizacion = $_SESSION['utilizaidorganizacion'];
        $idorganizacion=$_SESSION["accelog_idorganizacion"];
        $campo_idorganizacion = $_SESSION["accelog_campo_idorganizacion"];
        $linkproceso = $_SESSION["linkproceso"];
	
	$sql="";
	
	
	if($a==1){
		//NUEVO
		
		
	
		$sqlcampos = "";
		$sqlvalores = "";
		foreach ($nombrescampos as $nombrecampo => $idcampo){

                        $brincarcampo = false;                        
                        
                        $para_grabar=$controles->getgrabar($nombrecampo);
                        if($para_grabar=="auto_increment"){ //Este no debe incluirse en el insert
                            $brincarcampo=true;
                        }

                        if($utilizaidorganizacion){
                            if($nombrecampo==$campo_idorganizacion){
                                $brincarcampo=true;
                            }
                        }
                        //echo $nombrecampo." ".$brincarcampo."  --".$campo_idorganizacion."   ---".$utilizaidorganizacion."  <br>";

                        
                        
                        //PARCIALLOG
                        $permiso_parciallog = $parciallog->get_permiso($nombrecampo);
                        if($permiso_parciallog!="M"){
                            $brincarcampo=true;
                        }
                        /////
                        
                        
			
			if(!$brincarcampo){ //Esto quiere decir que no se detectara desde el request

				if($sqlcampos!="") $sqlcampos.=", ";
				$sqlcampos.=$nombrecampo;
				if($para_grabar=="archivo_base"){
					$sqlcampos.=", ".$nombrecampo."_name, ".$nombrecampo."_size, ".$nombrecampo."_type ";					
				}

				if($sqlvalores!="") $sqlvalores.=", ";
				switch($para_grabar){

					case "auto_increment":
						$sqlvalores.=$conexion->escapalog($_REQUEST["i".$idcampo]);
						break;
						
					case "varchar":
						if($idcampo==15){
							$sqlvalores.="'".$conexion->escapalog($_REQUEST["i".$idcampo],false,true)."'";
						} else {
							$sqlvalores.="'".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
						}
						break;
						
					case "archivo":
												
						//Crea la estructura de directorios para el formulario
						$directorioarchivo="../archivos";
						if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);
						
						$directorioarchivo.="/".$_SESSION["accelog_idorganizacion"];
						if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);
						
						$directorioarchivo.="/".$nombreestructura;
						if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);
						
						$dato_archivo = time()."___".basename($_FILES["i".$idcampo]['name']);	
						$directorioarchivo.="/".$dato_archivo;
						if(is_file($directorioarchivo)){
							echo "<b>Ya existe un archivo con el mismo nombre, el archivo no se ha subido.</b><br><br><hr>";
						} else {
							if($conexion->valida_archivo($directorioarchivo)){
								if(!move_uploaded_file($_FILES["i".$idcampo]['tmp_name'],$directorioarchivo)){
									//echo "<b>El archivo no se subió correctamente, favor de revisar.</b><br><br><hr>";
								} else {
									//Almacena el nombre del archivo
									$dato_archivo=basename($_FILES["i".$idcampo]["name"]);
								}
							} else {
								echo "Los datos se han almacenado sin embargo no esta permitido subir ese tipo de archivos ";
							}
						}

						$sqlvalores.="'".$dato_archivo."'";

												
						//$directorioarchivo="../archivos/".$nombreestructura."/";
						//move_uploaded_file($_FILES["i".$idcampo]['tmp_name'],)												
						//$tmp_archivo = .basename($_FILES["i".$idcampo]['tmp_name']);
						//echo $archivo;						
						break;
						
					case "archivo_base":
						//Obtiene el nombre del archivo y lo coloca en el campo...
						if(!empty($_FILES["i".$idcampo]["tmp_name"])){
							if($conexion->valida_archivo($_FILES["i".$idcampo]["name"])){

								//echo "imprimiendo _FILES...".$_FILES["i".$idcampo]["tmp_name"];
								$binario_nombre_temporal = $_FILES["i".$idcampo]["tmp_name"];
								$sqlvalores.="'".addslashes(fread(fopen($binario_nombre_temporal, "rb"), filesize($binario_nombre_temporal)))."',";						
								$sqlvalores.="'".$_FILES["i".$idcampo]["name"]."',";
								$sqlvalores.="'".$_FILES["i".$idcampo]["size"]."',";
								$sqlvalores.="'".$_FILES["i".$idcampo]["type"]."'";							

							} else {
								echo "Los datos se han almacenado sin embargo no esta permitido subir ese tipo de archivos ";
							}

						} else {
							$sqlvalores.="'','','',''";							
						}
						break;											

					case "boolean":
						$sqlvalores.="'".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
						break;
						
					case "select":
						if(empty($_REQUEST["i".$idcampo])){
							$sqlvalores.="'-1'";
						} else {
							$sqlvalores.="'".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
						}						
						break;

					case "bigint":
						$sqlvalores.=$utilerias->getnumero($conexion->escapalog($_REQUEST["i".$idcampo]));
						break;

					case "int":
						$sqlvalores.=$utilerias->getnumero($conexion->escapalog($_REQUEST["i".$idcampo]));
						break;

					case "double":
						$sqlvalores.=$utilerias->getnumero($conexion->escapalog($_REQUEST["i".$idcampo]));
						break;

					case "date":
						$dia = $conexion->escapalog($_REQUEST["i".$idcampo."_2"]);
						$mes = $conexion->escapalog($_REQUEST["i".$idcampo."_1"]);
						$anual = $conexion->escapalog($_REQUEST["i".$idcampo."_3"]);
						$sqlvalores.="'".$anual."-".$mes."-".$dia."'";
						break;
						
					case "time":
						$hora = $conexion->escapalog($_REQUEST["i".$idcampo."t"]);
						$ampm = $conexion->escapalog($_REQUEST["i".$idcampo."ampm"]);
						$horamilitar = strtotime($hora." ".$ampm);
						$horamilitar = date("H:i:s",$horamilitar);						
						$sqlvalores.="'".$horamilitar."'";
						break;
						
					case "datetime":
						$dia = $conexion->escapalog($_REQUEST["i".$idcampo."_2"]);
						$mes = $conexion->escapalog($_REQUEST["i".$idcampo."_1"]);
						$anual = $conexion->escapalog($_REQUEST["i".$idcampo."_3"]);

						$hora = $conexion->escapalog($_REQUEST["i".$idcampo."t"]);
						$ampm = $conexion->escapalog($_REQUEST["i".$idcampo."ampm"]);
						$horamilitar = strtotime($hora." ".$ampm);
						$horamilitar = date("H:i:s",$horamilitar);

						$sqlvalores.="'".$anual."-".$mes."-".$dia." ".$horamilitar."'";
						break;
						
					case "datetime_seg":
						$dia = $conexion->escapalog($_REQUEST["i".$idcampo."_2"]);
						$mes = $conexion->escapalog($_REQUEST["i".$idcampo."_1"]);
						$anual = $conexion->escapalog($_REQUEST["i".$idcampo."_3"]);

						$hora = $conexion->escapalog($_REQUEST["i".$idcampo."t"]);
						$ampm = $conexion->escapalog($_REQUEST["i".$idcampo."ampm"]);
						$horamilitar = strtotime($hora." ".$ampm);
						$horamilitar = date("H:i:s",$horamilitar);

						$sqlvalores.="'".$anual."-".$mes."-".$dia." ".$horamilitar."'";
						break;
						

					case "datetime_seg_hr":
						$dia = $conexion->escapalog($_REQUEST["i".$idcampo."_2"]);
						$mes = $conexion->escapalog($_REQUEST["i".$idcampo."_1"]);
						$anual = $conexion->escapalog($_REQUEST["i".$idcampo."_3"]);

						$hora = $conexion->escapalog($_REQUEST["i".$idcampo."t"]);
						//$ampm = $_REQUEST["i".$idcampo."ampm"];
						//$horamilitar = strtotime($hora." ".$ampm);
						//$horamilitar = date("H:i:s",$horamilitar);

						$sqlvalores.="'".$anual."-".$mes."-".$dia." ".$hora."'";
						break;						
						
						
						
				}
				
			}
			
			
		}

                if($utilizaidorganizacion){
                    $sqlcampos.=",".$campo_idorganizacion;
                    $sqlvalores.=",".$idorganizacion;
                }
	
		$sql = " insert into ".$nombreestructura." (".$sqlcampos.") values (".$sqlvalores.") ";	
		//error_log($sql);
		//echo $sql;
		$conexion->consultar($sql);

		//Actualización debido a las transacciones -- 2010-11-21
		$catalog_id_utilizado = $conexion->insert_id();

        //REGISTRO TRANSACCIONES -- 2010-10-01
        $conexion->transaccion("CATALOG - INSERCION - ".$nombreestructura,$sql);

		
	} else {	
		//EDICION



			$sqlcampos = "";
			$sqlvalores = "";
			foreach ($nombrescampos as $nombrecampo => $idcampo){
                            
                            $brincarcampo=false;
                            if($utilizaidorganizacion){
                                if($nombrecampo==$campo_idorganizacion){
                                    $brincarcampo=true;
                                }
                            } 

                            
                            
                            //Esto quiere decir que si es campo
                            if(!$brincarcampo){
                                    $para_grabar=$controles->getgrabar($nombrecampo);                                								
                            }
                               
                            
                            
                            
                            //PARCIALLOG
                            $permiso_parciallog = $parciallog->get_permiso($nombrecampo);
                            if($permiso_parciallog!="M"){
                                $brincarcampo=true;
                            }
                            /////     
                            
                            
                            
                            
                            //Solo se evalua si es archivo y si hay datos
                            if(($para_grabar=="archivo_base"||$para_grabar=="archivo")&&(!$brincarcampo)){
                                    if(empty($_FILES["i".$idcampo]["tmp_name"])){
                                            $brincarcampo=true;
                                    }
                            }

                            
                            
                           
                            //Ahora si a evaluar el campo
                            if(!$brincarcampo){


				if($controles->getllave($nombrecampo)==0){

					if($sqlvalores!="") $sqlvalores.=", ";
					$sqlvalores.=$nombrecampo;
					switch($para_grabar){

						case "auto_increment":
							$sqlvalores.="='".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
							break;

						case "varchar":
							//error_log("[catalog/fg.php]\nidcampo:".$idcampo);
							if($idcampo==15){
								//error_log("[catalog/fg.php]\nMe tope con el campo url");
								$sqlvalores.="='".$conexion->escapalog($_REQUEST["i".$idcampo],false,true)."'";
							} else {
								$sqlvalores.="='".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
							}
							break;

						case "archivo": 
							//MODIFICACION

							//Almacena el nombre del archivo
							$dato_archivo="";
							if(strlen($_FILES["i".$idcampo]["name"])==0){
								$dato_archivo=$_REQUEST["i".$idcampo."_ant"];
							} else {
								if($nombreestructura=='pvt_configura_facturacion'){
									$dato_archivo = basename($_FILES["i".$idcampo]['name']);
								}else{
									$dato_archivo=time()."___".basename($_FILES["i".$idcampo]["name"]);
								}
							}								
							$sqlvalores.="='".$dato_archivo."'";

									
							//Crea la estructura de directorios para el formulario
							$directorioarchivo="../archivos";
							if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);
						
							$directorioarchivo.="/".$_SESSION["accelog_idorganizacion"];
							if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);
						
							$directorioarchivo.="/".$nombreestructura;
							if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);

							if($nombreestructura=='pvt_configura_facturacion'){
								$directorioarchivo='../../modulos/SAT/cliente/';
								$dato_archivo = basename($_FILES["i".$idcampo]['name']);
								if(!preg_match('/(.cer)$|(.key)$/', $dato_archivo)){
									exit();
								}		
							}else{
								$dato_archivo = time()."___".basename($_FILES["i".$idcampo]['name']);	
							}

							$directorioarchivo.="/".$dato_archivo;

							if($conexion->valida_archivo($directorioarchivo)){
								if(move_uploaded_file($_FILES["i".$idcampo]['tmp_name'],$directorioarchivo)){
									
									//Eliminando el archivo anterior
									$directorioarchivoant=$directorioarchivo."/".$_REQUEST["i".$idcampo."_ant"];
									if(is_file($directorioarchivoant)){
										unlink($directorioarchivoant);								
									}

								} 
							}


							break;	

						case "archivo_base":
					
							if($conexion->valida_archivo($_FILES["i".$idcampo]["name"])){
								//Como no se brinco el campo entonces quiere decir que se modificará
								$binario_nombre_temporal = $_FILES["i".$idcampo]["tmp_name"];
								$sqlvalores.="='".addslashes(fread(fopen($binario_nombre_temporal, "rb"), filesize($binario_nombre_temporal)))."',";						
								$sqlvalores.=$nombrecampo."_name='".$_FILES["i".$idcampo]["name"]."',";
								$sqlvalores.=$nombrecampo."_size='".$_FILES["i".$idcampo]["size"]."',";
								$sqlvalores.=$nombrecampo."_type='".$_FILES["i".$idcampo]["type"]."'";
							} else {
								echo "Los datos se han almacenado sin embargo no esta permitido subir ese tipo de archivos ";
							}
							break;

						case "boolean":
							$sqlvalores.="='".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
							break;

						case "select":
                                                        if(empty($_REQUEST["i".$idcampo])){
                                                                $sqlvalores.="='-1'";
                                                        } else {
                                                                $sqlvalores.="='".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
                                                        }
                                                        break;

						case "bigint":
							$sqlvalores.="=".$utilerias->getnumero($_REQUEST["i".$idcampo]);
							break;

						case "int":
							$sqlvalores.="=".$utilerias->getnumero($_REQUEST["i".$idcampo]);
							break;

						case "double":
							$sqlvalores.="=".$utilerias->getnumero($_REQUEST["i".$idcampo]);
							break;

						case "date":
							$dia = $conexion->escapalog($_REQUEST["i".$idcampo."_2"]);
							$mes = $conexion->escapalog($_REQUEST["i".$idcampo."_1"]);
							$anual = $conexion->escapalog($_REQUEST["i".$idcampo."_3"]);
							$sqlvalores.="='".$anual."-".$mes."-".$dia."'";
							break;

						case "time":
							$hora = $conexion->escapalog($_REQUEST["i".$idcampo."t"]);
							$ampm = $conexion->escapalog($_REQUEST["i".$idcampo."ampm"]);
							$horamilitar = strtotime($hora." ".$ampm);
							$horamilitar = date("H:i:s",$horamilitar);						
							$sqlvalores.="='".$horamilitar."'";
							break;

						case "datetime":
							$dia = $conexion->escapalog($_REQUEST["i".$idcampo."_2"]);
							$mes = $conexion->escapalog($_REQUEST["i".$idcampo."_1"]);
							$anual = $conexion->escapalog($_REQUEST["i".$idcampo."_3"]);

							$hora = $conexion->escapalog($_REQUEST["i".$idcampo."t"]);
							$ampm = $conexion->escapalog($_REQUEST["i".$idcampo."ampm"]);
							$horamilitar = strtotime($hora." ".$ampm);
							$horamilitar = date("H:i:s",$horamilitar);

							$sqlvalores.="='".$anual."-".$mes."-".$dia." ".$horamilitar."'";
							break;

						case "datetime_seg":
							$dia = $conexion->escapalog($_REQUEST["i".$idcampo."_2"]);
							$mes = $conexion->escapalog($_REQUEST["i".$idcampo."_1"]);
							$anual = $conexion->escapalog($_REQUEST["i".$idcampo."_3"]);

							$hora = $conexion->escapalog($_REQUEST["i".$idcampo."t"]);
							$ampm = $conexion->escapalog($_REQUEST["i".$idcampo."ampm"]);
							$horamilitar = strtotime($hora." ".$ampm);
							$horamilitar = date("H:i:s",$horamilitar);

							$sqlvalores.="='".$anual."-".$mes."-".$dia." ".$horamilitar."'";
							break;

						case "datetime_seg_hr":
							$dia = $conexion->escapalog($_REQUEST["i".$idcampo."_2"]);
							$mes = $conexion->escapalog($_REQUEST["i".$idcampo."_1"]);
							$anual = $conexion->escapalog($_REQUEST["i".$idcampo."_3"]);

							$hora = $conexion->escapalog($_REQUEST["i".$idcampo."t"]);
							//$ampm = $_REQUEST["i".$idcampo."ampm"];
							//$horamilitar = strtotime($hora." ".$ampm);
							//$horamilitar = date("H:i:s",$horamilitar);

							$sqlvalores.="='".$anual."-".$mes."-".$dia." ".$hora."'";
							break;

					}
				}

                             }//Campo idorganizacion
                             
			}//foreach
			
			$sqlw_m = $_REQUEST['sw_m'];
			$sqlw_m = str_replace("\\","",$sqlw_m);

			$sql = " update ".$nombreestructura." set ".$sqlvalores." where ".$sqlw_m;
			//error_log($sql);
			//$sql = " insert into ".$nombreestructura." (".$sqlcampos.") values (".$sqlvalores.") ";	
			//echo $sql;
			//error_log($sql);
			                      
			$conexion->consultar($sql);

			//Actualización Everardo 2011-03-18
         $catalog_id_utilizado=$conexion->extraerdeentre($sqlw_m,"'","'");
			////////
			
			
            //REGISTRO TRANSACCIONES -- 2010-10-01
            $conexion->transaccion("CATALOG - EDICION - ".$nombreestructura,$sql);

	}

        if($linkproceso!=""){
            include($linkproceso);
        }

        $conexion->cerrar();
	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>						
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Netwarmonitor</title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->				
		
		<!--RECURSOS EXTERNOS CSS-->		
		<LINK href="css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	</head>
	<body>
		&nbsp; &nbsp; &nbsp; <b><font color=gray>Información almacenada con éxito.</font></b>	
	</body>
</html>
