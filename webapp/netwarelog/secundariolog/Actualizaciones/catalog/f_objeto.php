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
	
		//TIPO VARCHAR 	
		case "varchar":	
			$objeto = "
					<input id='i".$reg{'idcampo'}."'        ".$deshabilitado."
					       name='i".$reg{'idcampo'}."' 
					       type='text' 
						   size='".$tamano."' 
						   maxlength='".$longitud."' 
						   ".$class." ".$alt." 
						   value='".$valor."'
						   onchange='campo_onchange(this,true)'
				  	   />";
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
		
			$seleccionaSi="selected";
			$seleccionaNo="";
			
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
			if($a==0){
				$fecha_m = strtotime($reg_m[$reg{'nombrecampo'}]);
				$dia = date("d",$fecha_m);
				$mes = date("m",$fecha_m);
				$anual = date("Y",$fecha_m);		
			} else {
				$dia = date("d");
				$mes = date("m");
				$anual = date("Y");				
			}
		
			$objeto = $fechas->regresaobjetofecha($reg{'idcampo'},0,$dia,$mes,$anual,0,0,0,$deshabilitado);
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
		
			//EN CASO DE EDICION
			if($a==0){
				$hora_m = strtotime($reg_m[$reg{'nombrecampo'}]);
				$hora = "0".date("h",$hora_m);		
				if(strlen($hora)==3) $hora=date("h",$hora_m);
				$minutos = "0".date("i",$hora_m);
				if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
				$ampm = date("A",$hora_m);						
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

			//EN CASO DE EDICION
			if($a==0){
				
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
								
			$objeto = $fechas->regresaobjetofecha($reg{'idcampo'},-1,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado);
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
							
	}
		

?>