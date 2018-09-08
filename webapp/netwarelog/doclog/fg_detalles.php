<?php


        //PARCIALLOG
        $parciallog_detalle = new clparciallog($_SESSION['nombreestructuradetalle'],$_SESSION["accelog_idperfil"],$conexion);        


	//Obtiene los controles utilizados en la captura
	$controlesd = $_SESSION['controlesd'];
	$nombrescamposd = $controlesd->getcampos();
	$idestructuradetalle = $_SESSION['idestructuradetalle'];

	$filas = $conexion->escapalog($_REQUEST["txt_filasdetalles"]);


	//Obteniendo el nombre de tabla detalle...
		$nombreestructuradetalle = $_SESSION["nombreestructuradetalle"];
		$campofolio = $_SESSION["campofolio"];
		$campoidlinea = $_SESSION["campoidlinea"];
	////
		
		
	//NUEVO
	if($a==1){
		
		//VALOR DEL CAMPO FOLIO
		$valorcampofolio = $catalog_id_utilizado;
		
	} else {		
		
		//VALOR DEL CAMPO FOLIO 
		$valorcampofolio = $conexion->escapalog($_REQUEST['VALORCAMPOFOLIO']);
		
		$sql = " delete from ".$nombreestructuradetalle." where ".$campofolio." = '".$valorcampofolio."' ";
		$conexion->consultar($sql);					
	} 
	
	
	//Recorriendo las filas
	//Inicia en uno por que la fila 0 es la fila de los encabezados de la tabla
	for($fila=1;$fila<=$filas;$fila++){		
				
		//echo "<br><br>".$fila."<br>";		
		
		// inicio recorriendo los campos por fila
		
			$sqlcampos = "";
			$sqlvalores = "";
			$brincarfila = true;
			foreach ($nombrescamposd as $nombrecampo => $idcampo){

																$brincarcampo = false;

                                $idcampo = $idcampo."_".$fila;

                                

                                $para_grabar=$controlesd->getgrabar($nombrecampo);						

                                //echo "entre ".$nombrecampo." ".(!isset($_REQUEST["i".$idcampo]))." <br> ";
                                //Esto por que la fila se pudo haber eliminado
                                if(!isset($_REQUEST["i".$idcampo])){
                                                                        
                                    if($para_grabar=="date"||$para_grabar=="datetime"||$para_grabar=="datetime_seg"||$para_grabar=="datetime_seg_hr"){
                                            if(!isset($_REQUEST["i".$idcampo."_2"])){
                                                  $brincarcampo = true;
                                            }
                                    } else if($para_grabar=="time") {
                                            if(!isset($_REQUEST["i".$idcampo."t"])){
                                                  $brincarcampo = true;
                                            }									
                                    } else if($para_grabar=="archivo") {                                        
                                    		$brincarcampo = false;

                                	} else {
                                            $brincarcampo = true;									
                                    }
																		
																		//$accelog_access->nmerror_log("\nENTRE | VALIDA PARCIALLOG: ".$nombrecampo." - ".$idcampo."=".$_REQUEST["i".$idcampo."_2"]."\n");

                               } else { //if(!isset($_REQUEST["i".$idcampo]))
                                   
                                    $brincarcampo = false;	
                                    $brincarfila = false;
                                        
                                    //PARCIALLOG
                                    $permiso_parciallog = $parciallog_detalle->get_permiso($nombrecampo);
                                    //echo $nombrecampo." ".$permiso_parciallog." <br>  ";
                                    if($permiso_parciallog!="M"){
                                        
                                        $brincarcampo=true;

                                        if($sqlcampos!="") $sqlcampos.=", ";
                                        $sqlcampos.=$nombrecampo;

                                        if($sqlvalores!="") $sqlvalores.=", ";
                                        $sqlvalores.="'".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
                                        
                                    }                                        
                                        
                                }

																$accelog_access->nmerror_log("\n>>>".$nombrecampo." BRINCAR:".$brincarcampo."\n");
                                //if($brincarcampo){ echo " |BRINCADO| "; }


							/*No puede haber otro autonúmerico en el detalle, además de idlinea
	                        $para_grabar=$controlesd->getgrabar($nombrecampo);
	                        if($para_grabar=="auto_increment"){ //Este no debe incluirse en el insert
	                            $brincarcampo=true;
	                        }
							*/
							
							
	                        if($utilizaidorganizacion){
	                            if($nombrecampo==$campo_idorganizacion){
	                                $brincarcampo=true;
	                            }
	                        }
	                        //echo $nombrecampo." ".$brincarcampo."  --".$campo_idorganizacion."   ---".$utilizaidorganizacion."  <br>";


                                

                                
                                
                                


				if(!$brincarcampo){ //Esto quiere decir que no se detectara desde el request


					//echo $nombrecampo." : ".$idcampo." = ".$_REQUEST["i".$idcampo]." |       ";

					


					if($sqlcampos!="") $sqlcampos.=", ";
					$sqlcampos.=$nombrecampo;

					if($sqlvalores!="") $sqlvalores.=", ";
					switch($para_grabar){

						case "auto_increment":
							$sqlvalores.=$conexion->escapalog($_REQUEST["i".$idcampo]);
							break;
						
						case "varchar":
							$sqlvalores.="'".$conexion->escapalog($_REQUEST["i".$idcampo])."'";
							break;
						
						case "archivo":

							//Almacena el nombre del archivo
							$dato_archivo="";
							if(strlen($_FILES["i".$idcampo]["name"])==0){
								$dato_archivo=$_REQUEST["i".$idcampo."dato"];
							} else {
								$dato_archivo=time()."___".basename($_FILES["i".$idcampo]["name"]);
							}								
							$sqlvalores.="'".$dato_archivo."'";


						
							//Crea la estructura de directorios para el formulario
							$directorioarchivo="../archivos";
							if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);
						
							$directorioarchivo.="/".$_SESSION["accelog_idorganizacion"];
							if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);
						
							$directorioarchivo.="/".$nombreestructura;
							if(!is_dir($directorioarchivo)) mkdir($directorioarchivo);
						
						
							$directorioarchivo.="/".$dato_archivo;
							if(is_file($directorioarchivo)){
								//echo "<b>Ya existe un archivo con el mismo nombre ".$dato_archivo.", el archivo no se ha subido.</b><br><br><hr>";
							} else {					
								if(move_uploaded_file($_FILES["i".$idcampo]['tmp_name'],$directorioarchivo)){

									//Eliminando el archivo anterior
									$directorioarchivoant=$directorioarchivo."/".$_REQUEST["i".$idcampo."dato"];
									if(is_file($directorioarchivoant)){
										unlink($directorioarchivoant);								
									}

								}
							}
												
							//$directorioarchivo="../archivos/".$nombreestructura."/";
							//move_uploaded_file($_FILES["i".$idcampo]['tmp_name'],)												
							//$tmp_archivo = .basename($_FILES["i".$idcampo]['tmp_name']);
							//echo $archivo;						
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
							$accelog_access->nmerror_log($sqlvalores);
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
			
			
			} //foreach		
								
		////////// fin recorriendo los campos por fila
		
		
		
		//BRINCAR FILA SI ESTA FUE ELIMINADA POR EL USUARIO
		if(!$brincarfila){
																								
				/* EL IDORGANIZACION SERA SOLO PARA LA TABLA TITULO
				if($utilizaidorganizacion){
	                $sqlcampos.=",".$campo_idorganizacion;
	                $sqlvalores.=",".$idorganizacion;
	            }
				*/				

				$sql = " insert into ".$nombreestructuradetalle." 
						 	(".$campofolio.",".$sqlcampos.") 
						 values 
							(".$valorcampofolio.",".$sqlvalores.") ";	
							
				//echo $sql."<br><br>";
				//error_log($sql);
				//$accelog_access->nmerror_log($sql);
				$conexion->consultar($sql);

				//Actualización debido a las transacciones -- 2010-11-21
				$catalog_id_utilizado = $conexion->insert_id();

		        //REGISTRO TRANSACCIONES -- 2010-10-01
		        //$conexion->transaccion("DOCLOG - INSERCION - ".$nombreestructura,$sql);
					
			
		} else {//if(!$brincarfila)
			
			//echo "FILA BRINCADA<br>";
			
		}//if(!$brincarfila)
		

		
		
		
	} //for($fila=1;$fila<=$filas;$fila++)

?>
