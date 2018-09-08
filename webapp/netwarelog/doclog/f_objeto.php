<?php
	
	$longitud = $reg{'longitud'}; 
	if($reg{'longitud'}<=0){
		$longitud = 50; //LONGITUD MAXIMA
	}

	$tamano = "50"; //TAMAÑO MAXIMO
	if($reg{'longitud'}<$tamano){
		$tamano=$reg{'longitud'};
	}	

	// SWITCH DEL TIPO DE CAMPO
	$objeto = "";
		
	
	$para_grabar=$reg{'tipo'};
	switch($reg{'tipo'}){
	
	
		//TIPO AUTO_INCREMENT
		case "auto_increment":
			
			//EN CASO DE EDICION
			$omision="(Autonúmerico)";
			if($a==0){
				$omision=$reg_m[$reg{'nombrecampo'}];
			}
			
		
			$objeto = "
						<input id='i".$reg{'idcampo'}."'        ".$deshabilitado."
						       name='i".$reg{'idcampo'}."' 
						       type='text' 
							   disabled size='15' 
							   style='text-align:right;color:#555555;'	
							   value='".$omision."' 
							   onchange='campo_onchange(this,true)'								
					 />";							
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," document.getElementById(\"i".$reg{'idcampo'}."\").value ",$para_grabar,$llave);
			break;
		
		
		
		//TIPO ARCHIVO
		case "archivo":
			$type_input ="file";
			$formato=trim($reg{'formato'});
			//echo $formato;
			if($formato==="#"){
				$type_input ="password";				
			}
		
				$objeto = "<input type='hidden' name='MAX_FILE_SIZE' value='10000000'/>";
				if($valor=="") $valor = "";
					//$objeto.= "<b id='image'>".$valor."</b>";
					//$type_input="hidden";
					$objeto.="<input 
							type='hidden' value='".$valor."'
							id='i".$reg{'idcampo'}."_ant' name='i".$reg{'idcampo'}."_ant' />";										
				
				$objeto.= "
					<table border=0>
						<tr>
							<td>
			 					<a title='Descargar archivo' href=\"../descarga_archivo_fisico.php?d=1&f=".$valor."&ne=".$_SESSION['nombreestructura']."\" target='blank'><img src='../catalog/img/download.png'></a>
							</td>
							<td>
			 					<a title='Ver archivo' href=\"../descarga_archivo_fisico.php?d=0&f=".$valor."&ne=".$_SESSION['nombreestructura']."\" target='blank'><img src='../catalog/img/preview.png'></a>
							</td>								
							<td>
								&nbsp; <b id='image'>".$valor."</b>
							</td>
							<td>																		
								<input id='i".$reg{'idcampo'}."'        ".$deshabilitado."
								       name='i".$reg{'idcampo'}."' 
								       type='".$type_input."' 
									   accept='".$valor."'							   
									   class='archivo' ".$alt." 
									   value='".$valor."'
									   onchange='campo_onchange(this,true)'
									   title='Aquí puede modificar el archivo almacenado'
							  	   />
							</td>
						</tr>
					</table>	";
					
											
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," document.getElementById(\"i".$reg{'idcampo'}."\").value ",$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},$controles->getlinea($reg{'nombrecampo'}),"''");
			}
			break;
		
					
			
			
			
	
		//TIPO VARCHAR 	
		case "varchar":	
			$type_input ="text";
			$formato=trim($reg{'formato'});
			//echo $formato;
			if($formato==="#"){
				$type_input ="password";				
			}
			
			if($longitud<=100){
				
				$objeto = "
						<input id='i".$reg{'idcampo'}."'        ".$deshabilitado."
						       name='i".$reg{'idcampo'}."' 
						       type='".$type_input."' 
							   size='".$tamano."' 
							   maxlength='".$longitud."' 
							   ".$class." ".$alt." 
							   value='".$valor."'
							   onchange='campo_onchange(this,true)'
					  	   />";
				
			} else {
				
				$objeto = "
						<textarea id='i".$reg{'idcampo'}."'        ".$deshabilitado."
						       name='i".$reg{'idcampo'}."' 
							   onKeyUp='return maximaLongitud(this,".$longitud.")'
							   ".$class." ".$alt."  cols='50' rows='3'
							   onchange='campo_onchange(this,true)'
					  	   >".$valor."</textarea>";
				
				
				
			}
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," document.getElementById(\"i".$reg{'idcampo'}."\").value ",$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},$controles->getlinea($reg{'nombrecampo'}),"''");
			}
			break;		
		
		
		//TIPO BIG INT									
		case "bigint":
		
		
			$objeto = "
				<input id='i".$reg{'idcampo'}."'        ".$deshabilitado."
				       name='i".$reg{'idcampo'}."' 
				       type='text' 
					   size='20' 
					   maxlength='18' 
					   ".$class." ".$alt." 
					   value='".$valor."'
					   onkeydown='campo_keydown()' 
					   onblur='campo_onchange(this,false)'
					   onkeypress='return soloint(event)'
					   style='text-align:right'
			  	   />";				
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," regresanumero(document.getElementById(\"i".$reg{'idcampo'}."\").value) ",$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."\").value","''");
			}						
			break;	
		
		//TIPO INT										
		case "int":
			$objeto = "
				<input id='i".$reg{'idcampo'}."'        ".$deshabilitado."
			       name='i".$reg{'idcampo'}."' 
			       type='text' 
				   size='10' 
				   maxlength='9' 
				   ".$class." ".$alt." 		
				   value='".$valor."'	
				   onkeydown='campo_keydown()' 									   
				   onblur='campo_onchange(this,false)'
				   onkeypress='return soloint(event)'	
				   style='text-align:right'							
		  	      />";				
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," regresanumero(document.getElementById(\"i".$reg{'idcampo'}."\").value) ",$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."\").value","''");
			}						
			break;
		
		//TIPO DOUBLE 
		case "double":
			$objeto = "
				<input id='i".$reg{'idcampo'}."'        ".$deshabilitado."
			       name='i".$reg{'idcampo'}."' 
			       type='text' 
				   size='35' 
				   maxlength='100' 
				   ".$class." ".$alt." 	
				   value='".$valor."'	
				   onkeydown='campo_keydown()' 												
				   onblur='campo_onchange(this,false)'
				   onkeypress='return solonum(event,this)'		
				   style='text-align:right'						
		  	     />";				
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," regresanumero(document.getElementById(\"i".$reg{'idcampo'}."\").value) ",$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."\").value","''");
			}						
			break;	

		//TIPO BOOLEAN 
		case "boolean":


			//VALOR POR OMISION	
			if($valor=="0"||strtoupper($valor)=="NO"||strtoupper($valor)=="FALSE"){
				$seleccionaSi="";
				$seleccionaNo="selected";			
			} else 	{
				$seleccionaSi="selected";
				$seleccionaNo="";
			}

			
			//EN CASO DE EDICION
			if($a==0){
				if(!$reg_m[$reg{'nombrecampo'}]){
					$seleccionaSi="";
					$seleccionaNo="selected";				
				}				
			}		
	
			$objeto = "
				<select class='seleccion' name='i".$reg{'idcampo'}."' id='i".$reg{'idcampo'}."' onchange='campo_onchange(this,true)' ".$deshabilitado." >
					<option value='-1' ".$seleccionaSi.">Sí</option>
					<option value='0'  ".$seleccionaNo.">No</option>
				</select>
				";				
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," document.getElementById(\"i".$reg{'idcampo'}."\").value ",$para_grabar,$llave);
			break;	
		
		case "date":
		
			//EN CASO DE EDICION
			
			$dia=""; $mes=""; $anual="";
			$hora="";$minutos="";$segundos="";$ampm="";

			if($a==0){
				
				if($reg_m[$reg{'nombrecampo'}]!="0000-00-00 00:00:00"){
					$fecha_m = strtotime($reg_m[$reg{'nombrecampo'}]);
					$dia = date("d",$fecha_m);
					$mes = date("m",$fecha_m);
					$anual = date("Y",$fecha_m);		
			  	
					$hora_m = strtotime($reg_m[$reg{'nombrecampo'}]);
					$hora = "0".date("h",$hora_m);		
					if(strlen($hora)==3) $hora=date("h",$hora_m);
					$minutos = "0".date("i",$hora_m);
					if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
					$ampm = date("A",$hora_m);										
				}
				
			} else {
				
				$dia = date("d");
				$mes = date("m");
				$anual = date("Y");				
				
				$hora = "0".date("h");		
				if(strlen($hora)==3) $hora=date("h");
				$minutos = "0".date("i");
				if(strlen($minutos)==3) $minutos=date("i");		
				$ampm = date("A");						
			}
			
			$objeto = $fechas->regresaobjetofecha($reg{'idcampo'},-1,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado,false,"");
			$linea_para_fecha=" document.getElementById(\"i".$reg{'idcampo'}."_3\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."_1\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."_2\").value";	
				
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);
			
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_3\").value","''");
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_1\").value","''");
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_2\").value","''");								
			}						
			$script_validacion.=$controles->validafecha("document.getElementById(\"i".$reg{'idcampo'}."_3\").value","document.getElementById(\"i".$reg{'idcampo'}."_1\").value","document.getElementById(\"i".$reg{'idcampo'}."_2\").value",$reg{'nombrecampousuario'});
						
			
			break;

		case "time":										
	
			$hora="";$minutos="";$segundos="";$ampm="";
	
			//EN CASO DE EDICION
			if($a==0){
				
				if($reg_m[$reg{'nombrecampo'}]!="0000-00-00 00:00:00"){
					$hora_m = strtotime($reg_m[$reg{'nombrecampo'}]);
					$hora = "0".date("h",$hora_m);		
					if(strlen($hora)==3) $hora=date("h",$hora_m);
					$minutos = "0".date("i",$hora_m);
					if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
					$ampm = date("A",$hora_m);						
				}
			}else{		
				$hora = "0".date("h");		
				if(strlen($hora)==3) $hora=date("h");
				$minutos = "0".date("i");
				if(strlen($minutos)==3) $minutos=date("i");		
				$ampm = date("A");		
			}
		
			$objeto = $fechas->regresaobjetohora2($reg{'idcampo'},$hora,$minutos,$ampm,$deshabilitado);
			$linea_para_fecha=" document.getElementById(\"i".$reg{'idcampo'}."t\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."ampm\").value ";
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."t\").value","''");
			}						
			$script_validacion.=$controles->validahora("document.getElementById(\"i".$reg{'idcampo'}."t\").value","document.getElementById(\"i".$reg{'idcampo'}."ampm\").value",$reg{'nombrecampousuario'});			
			break;
	
	
	
		case "datetime":				


			$dia=""; $mes=""; $anual="";
			$hora="";$minutos="";$segundos="";$ampm="";

			//EN CASO DE EDICION
			if($a==0){
				
				if($reg_m[$reg{'nombrecampo'}]!="0000-00-00 00:00:00"){
					$fecha_m = strtotime($reg_m[$reg{'nombrecampo'}]);
					$dia = date("d",$fecha_m);
					$mes = date("m",$fecha_m);
					$anual = date("Y",$fecha_m);		
					
					$hora_m = strtotime($reg_m[$reg{'nombrecampo'}]);
					$hora = "0".date("h",$hora_m);		
					if(strlen($hora)==3) $hora=date("h",$hora_m);
					$minutos = "0".date("i",$hora_m);
					if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
					$ampm = date("A",$hora_m);										
				}
				
			} else {
				
				$dia = date("d");
				$mes = date("m");
				$anual = date("Y");				
				
				$hora = "0".date("h");		
				if(strlen($hora)==3) $hora=date("h");
				$minutos = "0".date("i");
				if(strlen($minutos)==3) $minutos=date("i");		
				$ampm = date("A");						
			}				

			$incluirsegundos=false;								
			$objeto = $fechas->regresaobjetofecha($reg{'idcampo'},-1,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado,$incluirsegundos,"");
			$linea_para_fecha=" document.getElementById(\"i".$reg{'idcampo'}."_3\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."_1\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."_2\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."t\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."ampm\").value ";	
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);	
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_3\").value","''");
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_1\").value","''");
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_2\").value","''");								
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."t\").value","''");
			}			
			$script_validacion.=$controles->validafecha("document.getElementById(\"i".$reg{'idcampo'}."_3\").value","document.getElementById(\"i".$reg{'idcampo'}."_1\").value","document.getElementById(\"i".$reg{'idcampo'}."_2\").value",$reg{'nombrecampousuario'});			
			$script_validacion.=$controles->validahora("document.getElementById(\"i".$reg{'idcampo'}."t\").value","document.getElementById(\"i".$reg{'idcampo'}."ampm\").value",$reg{'nombrecampousuario'});										
			break;
			
			
			
			
		case "datetime_seg":				

			$dia=""; $mes=""; $anual="";
			$hora="";$minutos="";$segundos="";$ampm="";

			//EN CASO DE EDICION
			if($a==0){


				if($reg_m[$reg{'nombrecampo'}]!="0000-00-00 00:00:00"){
					$fecha_m = strtotime($reg_m[$reg{'nombrecampo'}]);
					$dia = date("d",$fecha_m);
					$mes = date("m",$fecha_m);
					$anual = date("Y",$fecha_m);

					$hora_m = strtotime($reg_m[$reg{'nombrecampo'}]);
				
					$hora = "0".date("h",$hora_m);
					if(strlen($hora)==3) $hora=date("h",$hora_m);
					$minutos = "0".date("i",$hora_m);
					if(strlen($minutos)==3) $minutos=date("i",$hora_m);
					$segundos = "0".date("s",$hora_m);
					if(strlen($segundos)==3) $segundos=date("s",$hora_m);
					
					$ampm = date("A",$hora_m);
				}


			} else {

				$dia = date("d");
				$mes = date("m");
				$anual = date("Y");				

				$hora = "0".date("h");		
				if(strlen($hora)==3) $hora=date("h");
				$minutos = "0".date("i");
				if(strlen($minutos)==3) $minutos=date("i");	
				$segundos = "0".date("s");
				if(strlen($segundos)==3) $segundos=date("s");	
					
				$ampm = date("A");						
			}				

			$incluirsegundos=true;
			$objeto = $fechas->regresaobjetofecha($reg{'idcampo'},-1,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado,$incluirsegundos,$segundos);
			$linea_para_fecha=" document.getElementById(\"i".$reg{'idcampo'}."_3\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."_1\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."_2\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."t\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."ampm\").value ";	
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);	
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_3\").value","''");
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_1\").value","''");
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_2\").value","''");								
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."t\").value","''");
			}			
			$script_validacion.=$controles->validafecha("document.getElementById(\"i".$reg{'idcampo'}."_3\").value","document.getElementById(\"i".$reg{'idcampo'}."_1\").value","document.getElementById(\"i".$reg{'idcampo'}."_2\").value",$reg{'nombrecampousuario'});			
			$script_validacion.=$controles->validahora("document.getElementById(\"i".$reg{'idcampo'}."t\").value","document.getElementById(\"i".$reg{'idcampo'}."ampm\").value",$reg{'nombrecampousuario'});										
			break;
			
			
			
		case "datetime_seg_hr":	

			$dia=""; $mes=""; $anual="";
			$hora="";$minutos="";$segundos="";$ampm="";

			//EN CASO DE EDICION
			if($a==0){
	
				if($reg_m[$reg{'nombrecampo'}]!="0000-00-00 00:00:00"){
					$fecha_m = strtotime($reg_m[$reg{'nombrecampo'}]);
					$dia = date("d",$fecha_m);
					$mes = date("m",$fecha_m);
					$anual = date("Y",$fecha_m);		

					$hora_m = strtotime($reg_m[$reg{'nombrecampo'}]);
				
					$hora = "0".date("H",$hora_m);		
					if(strlen($hora)==3) $hora=date("H",$hora_m);
					$minutos = "0".date("i",$hora_m);				
					if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
					$segundos = "0".date("s",$hora_m);				
					if(strlen($segundos)==3) $segundos=date("s",$hora_m);
				}

			} else {

				$dia = date("d");
				$mes = date("m");
				$anual = date("Y");				

				$hora = "0".date("H");		
				if(strlen($hora)==3) $hora=date("H");
				$minutos = "0".date("i");
				if(strlen($minutos)==3) $minutos=date("i");	
				$segundos = "0".date("s");
				if(strlen($segundos)==3) $segundos=date("s");	
					
			}				

			$incluirsegundos=true;
			$objeto = $fechas->regresaobjetofecha($reg{'idcampo'},-1,$dia,$mes,$anual,$hora,$minutos,"0",$deshabilitado,$incluirsegundos,$segundos);
			$linea_para_fecha=" document.getElementById(\"i".$reg{'idcampo'}."_3\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."_1\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."_2\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$reg{'idcampo'}."t\").value";

			
			$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);	
			if($reg{'requerido'}){
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_3\").value","''");
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_1\").value","''");
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."_2\").value","''");								
				$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."t\").value","''");
			}			
			$script_validacion.=$controles->validafecha("document.getElementById(\"i".$reg{'idcampo'}."_3\").value","document.getElementById(\"i".$reg{'idcampo'}."_1\").value","document.getElementById(\"i".$reg{'idcampo'}."_2\").value",$reg{'nombrecampousuario'});			
			$script_validacion.=$controles->validahora_hr("document.getElementById(\"i".$reg{'idcampo'}."t\").value",$reg{'nombrecampousuario'});
			break;		
		
					
	}		
							
	
		

?>
