<?php
	
	$longitud = $rsd{'longitud'}; 
	if($rsd{'longitud'}<=0){
		$longitud = 50; //LONGITUD MAXIMA
	}

	$tamano = "20"; //TAMAÑO MAXIMO
	if($rsd{'longitud'}<$tamano){
		$tamano=$rsd{'longitud'};
	}	

	// SWITCH DEL TIPO DE CAMPO
	$objeto = "";
		
	
	$para_grabar=$rsd{'tipo'};
	
	$tipo_campo_detalle = $rsd{'tipo'};
	
	
	switch($tipo_campo_detalle){
	
	
		//TIPO AUTO_INCREMENT
		case "auto_increment":
			
			//EN CASO DE EDICION
			$omision="(Autonúmerico)";
			if($a==0){
				if(isset($reg_m[$rsd{'nombrecampo'}])){
					$omision=$reg_m[$rsd{'nombrecampo'}];	
				}				
			}
			
		
			$objeto = "
						<input id='".$IDCAMPO."'        ".$deshabilitado."
						       name='".$IDCAMPO."' 
						       type='text' 
							   disabled size='15' 
							   style='text-align:right;color:#555555;'	
							   value='".$omision."' 
							   onchange='campo_onchange(this,true)'								
					 />";							
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," document.getElementById(\"".$IDCAMPO."\").value ",$para_grabar,$llave);
			break;
		
		
		
		//TIPO ARCHIVO
		case "archivo":
			$type_input ="file";
			$formato=trim($rsd{'formato'});
			//echo $formato;
			if($formato==="#"){
				$type_input ="password";				
			}
		
				$objeto = "<input type='hidden' name='MAX_FILE_SIZE' value='10000000'/>";
				if($valor!==""){
					$objeto.= "<b>".$valor."</b>";
					$type_input="hidden";
				}

                $objeto.= " <a id='".$IDCAMPO."'></a> 
                				<input type='hidden' id='".$IDCAMPO."dato' name='".$IDCAMPO."dato' value=''> 
                                <input id='".$IDCAMPO."'        ".$deshabilitado."
                                       name='".$IDCAMPO."' 
                                       type='".$type_input."' 
                                           accept='".$valor."'                                                     
                                           class='archivo' ".$alt." 
                                           value='".$valor."'
                                           onchange='campo_onchange(this,true)'
                                   />";





			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," document.getElementById(\"i".$rsd{'idcampo'}."\").value ",$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($reg{'nombrecampo'},$rsd{'nombrecampousuario'},$controlesd->getlinea($rsd{'nombrecampo'}),"''");
			}
			break;
		
					
			
			
			
	
		//TIPO VARCHAR 	
		case "varchar":	
			$type_input ="text";
			$formato=trim($rsd{'formato'});
			//echo $formato;
			if($formato==="#"){
				$type_input ="password";				
			}
			
			if($longitud<=100){
				
				$objeto = "
						<input id='".$IDCAMPO."'        ".$deshabilitado."
						       name='".$IDCAMPO."' 
						       type='".$type_input."' 
							   size='".$tamano."' 
							   maxlength='".$longitud."' 
							   ".$class." ".$alt." 
							   value='".$valor."'
							   onchange='campo_onchange(this,true)'
					  	   />";
				
			} else {
				
				$objeto = "
						<textarea id='".$IDCAMPO."'        ".$deshabilitado."
						       name='".$IDCAMPO."' 
							   onKeyUp='return maximaLongitud(this,".$longitud.")'
							   ".$class." ".$alt."  cols='50' rows='3'
							   onchange='campo_onchange(this,true)'
					  	   >".$valor."</textarea>";
				
				
				
			}
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," document.getElementById(\"".$IDCAMPO."\").value ",$para_grabar,$llave);
			if($reg{'requerido'}){
                            $script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},$controlesd->getlinea($rsd{'nombrecampo'}),"''");
			}
			break;		
		
		
		//TIPO BIG INT									
		case "bigint":
		
		
			$objeto = "
				<input id='".$IDCAMPO."'        ".$deshabilitado."
				       name='".$IDCAMPO."' 
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
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," regresanumero(document.getElementById(\"".$IDCAMPO."\").value) ",$para_grabar,$llave);
			if($rsd{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"".$IDCAMPO."\").value","''");
			}						
			break;	
		
		//TIPO INT										
		case "int":
			$objeto = "
				<input id='".$IDCAMPO."'        ".$deshabilitado."
			       name='".$IDCAMPO."' 
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
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," regresanumero(document.getElementById(\"".$IDCAMPO."\").value) ",$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"".$IDCAMPO."\").value","''");
			}						
			break;
		
		//TIPO DOUBLE 
		case "double":
			$objeto = "
				<input id='".$IDCAMPO."'        ".$deshabilitado."
			       name='".$IDCAMPO."' 
			       type='text' 
				   size='".$tamano."' 
				   maxlength='100' 
				   ".$class." ".$alt." 	
				   value='".$valor."'	
				   onkeydown='campo_keydown()' 												
				   onblur='campo_onchange(this,false)'
				   onkeypress='return solonum(event,this)'		
				   style='text-align:right'						
		  	     />";				
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," regresanumero(document.getElementById(\"i".$rsd{'idcampo'}."\").value) ",$para_grabar,$llave);
			if($reg{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."\").value","''");
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
			/*if($a==0){
				if(!$reg_m[$reg{'nombrecampo'}]){
					$seleccionaSi="";
					$seleccionaNo="selected";				
				}				
			}*/		
	
			$objeto = "
				<select class='seleccion' name='".$IDCAMPO."' id='".$IDCAMPO."' onchange='campo_onchange(this,true)' ".$deshabilitado." >
					<option value='-1' ".$seleccionaSi.">Sí</option>
					<option value='0'  ".$seleccionaNo.">No</option>
				</select>
				";				
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," document.getElementById(\"".$IDCAMPO."\").value ",$para_grabar,$llave);
			break;	
		
		case "date":
		
			//EN CASO DE EDICION
			/*if($a==0){
				// Esto para detalle deberá ser diferente .... $fecha_m = strtotime($reg_m[$rsd{'nombrecampo'}]);
				$dia = date("d",$fecha_m);
				$mes = date("m",$fecha_m);
				$anual = date("Y",$fecha_m);		
			} else {*/
				$dia = date("d");
				$mes = date("m");
				$anual = date("Y");				
			/*}*/
		
			//echo "<br>IDCAMPO=".$deshabilitado."<br>";
		
			$objeto = $fechasd->regresaobjetofecha($IDCAMPO,0,$dia,$mes,$anual,0,0,0,$deshabilitado,false,false);
			$script_fechas.="\n".$fechasd->script_fechas;
			$linea_para_fecha=" document.getElementById(\"".$IDCAMPO."_3\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"".$IDCAMPO."_1\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"".$IDCAMPO."_2\").value";	
				
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);
			
			if($reg{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"".$IDCAMPO."_3\").value","''");
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"".$IDCAMPO."_1\").value","''");
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"".$IDCAMPO."_2\").value","''");								
			}						
			$script_validacion.=$controlesd->validafecha("document.getElementById(\"".$IDCAMPO."_3\").value","document.getElementById(\"".$IDCAMPO."_1\").value","document.getElementById(\"".$IDCAMPO."_2\").value",$rsd{'nombrecampousuario'});
						
			
			break;

		case "time":										
		
			//EN CASO DE EDICION
			/*if($a==0){
				// Esto tendra que ser diferente en doclog .... $hora_m = strtotime($reg_m[$rsd{'nombrecampo'}]);
				$hora = "0".date("h",$hora_m);		
				if(strlen($hora)==3) $hora=date("h",$hora_m);
				$minutos = "0".date("i",$hora_m);
				if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
				$ampm = date("A",$hora_m);						
			}else{		*/
				$hora = "0".date("h");		
				if(strlen($hora)==3) $hora=date("h");
				$minutos = "0".date("i");
				if(strlen($minutos)==3) $minutos=date("i");		
				$ampm = date("A");		
			/*}*/
		
			$objeto = $fechasd->regresaobjetohora2($IDCAMPO,$hora,$minutos,$ampm,$deshabilitado);
			$linea_para_fecha=" document.getElementById(\"".$IDCAMPO."t\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"".$IDCAMPO."ampm\").value ";
			
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);
			
			if($reg{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"".$IDCAMPO."t\").value","''");
			}						
			$script_validacion.=$controlesd->validahora("document.getElementById(\"".$IDCAMPO."t\").value","document.getElementById(\"".$IDCAMPO."ampm\").value",$rsd{'nombrecampousuario'});			
			break;
	
	
	
		case "datetime":				

			//EN CASO DE EDICION
			/*if($a==0){
				
				
				// Esto debera ser diferente en doclog .... $fecha_m = strtotime($reg_m[$rsd{'nombrecampo'}]);
				$dia = date("d",$fecha_m);
				$mes = date("m",$fecha_m);
				$anual = date("Y",$fecha_m);		
				
				// Esto igual deberá ser diferente en doclog ..... $hora_m = strtotime($reg_m[$rsd{'nombrecampo'}]);
				$hora = "0".date("h",$hora_m);		
				if(strlen($hora)==3) $hora=date("h",$hora_m);
				$minutos = "0".date("i",$hora_m);
				if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
				$ampm = date("A",$hora_m);										
				
				
			} else {*/
				
				$dia = date("d");
				$mes = date("m");
				$anual = date("Y");				
				
				$hora = "0".date("h");		
				if(strlen($hora)==3) $hora=date("h");
				$minutos = "0".date("i");
				if(strlen($minutos)==3) $minutos=date("i");		
				$ampm = date("A");						
			/*}*/				

			$incluirsegundos=false;								
			$objeto = $fechasd->regresaobjetofecha($IDCAMPO,-1,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado,$incluirsegundos,"");
			$script_fechas.="\n".$fechasd->script_fechas;			
			$linea_para_fecha=" document.getElementById(\"i".$IDCAMPO."_3\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."_1\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."_2\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."t\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."ampm\").value ";	
			
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);	
			
			if($reg{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_3\").value","''");
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_1\").value","''");
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_2\").value","''");								
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."t\").value","''");
			}			
			$script_validacion.=$controlesd->validafecha("document.getElementById(\"i".$IDCAMPO."_3\").value","document.getElementById(\"i".$rsd{'idcampo'}."_1\").value","document.getElementById(\"i".$rsd{'idcampo'}."_2\").value",$rsd{'nombrecampousuario'});			
			$script_validacion.=$controlesd->validahora("document.getElementById(\"i".$IDCAMPO."t\").value","document.getElementById(\"i".$rsd{'idcampo'}."ampm\").value",$rsd{'nombrecampousuario'});										
			break;
			
			
			
			
		case "datetime_seg":				

			//EN CASO DE EDICION
			/*if($a==0){

				// Esto debera ser diferente en doclog ....$fecha_m = strtotime($reg_m[$rsd{'nombrecampo'}]);
				$dia = date("d",$fecha_m);
				$mes = date("m",$fecha_m);
				$anual = date("Y",$fecha_m);		

				// Esto debera ser diferente en doclog ....$hora_m = strtotime($reg_m[$rsd{'nombrecampo'}]);
				
				$hora = "0".date("h",$hora_m);		
				if(strlen($hora)==3) $hora=date("h",$hora_m);
				$minutos = "0".date("i",$hora_m);				
				if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
				$segundos = "0".date("s",$hora_m);				
				if(strlen($segundos)==3) $segundos=date("s",$hora_m);		
				
				
				$ampm = date("A",$hora_m);										

			} else {*/

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
			/*}*/				

			$incluirsegundos=true;
			$objeto = $fechasd->regresaobjetofecha($IDCAMPO,-1,$dia,$mes,$anual,$hora,$minutos,$ampm,$deshabilitado,$incluirsegundos,$segundos);
			$script_fechas.="\n".$fechasd->script_fechas;
			$linea_para_fecha=" document.getElementById(\"i".$IDCAMPO."_3\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."_1\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."_2\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."t\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."ampm\").value ";	
			
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);
				
			if($reg{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_3\").value","''");
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_1\").value","''");
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_2\").value","''");								
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."t\").value","''");
			}			
			$script_validacion.=$controlesd->validafecha("document.getElementById(\"i".$IDCAMPO."_3\").value","document.getElementById(\"i".$rsd{'idcampo'}."_1\").value","document.getElementById(\"i".$rsd{'idcampo'}."_2\").value",$rsd{'nombrecampousuario'});			
			$script_validacion.=$controlesd->validahora("document.getElementById(\"i".$IDCAMPO."t\").value","document.getElementById(\"i".$rsd{'idcampo'}."ampm\").value",$rsd{'nombrecampousuario'});										
			break;
			
			
		case "datetime_seg_hr":				

			//EN CASO DE EDICION
			/*if($a==0){

				// Esto debera ser diferente en doclog ....$fecha_m = strtotime($reg_m[$rsd{'nombrecampo'}]);
				$dia = date("d",$fecha_m);
				$mes = date("m",$fecha_m);
				$anual = date("Y",$fecha_m);		

				// Esto debera ser diferente en doclog ....$hora_m = strtotime($reg_m[$rsd{'nombrecampo'}]);
				
				$hora = "0".date("h",$hora_m);		
				if(strlen($hora)==3) $hora=date("h",$hora_m);
				$minutos = "0".date("i",$hora_m);				
				if(strlen($minutos)==3) $minutos=date("i",$hora_m);		
				$segundos = "0".date("s",$hora_m);				
				if(strlen($segundos)==3) $segundos=date("s",$hora_m);		
				
				
				$ampm = date("A",$hora_m);										

			} else {*/

				$dia = date("d");
				$mes = date("m");
				$anual = date("Y");				

				$hora = "0".date("h");		
				if(strlen($hora)==3) $hora=date("h");
				$minutos = "0".date("i");
				if(strlen($minutos)==3) $minutos=date("i");	
				$segundos = "0".date("s");
				if(strlen($segundos)==3) $segundos=date("s");	
									
			/*}*/				

			$incluirsegundos=true;
			$objeto = $fechasd->regresaobjetofecha($IDCAMPO,-1,$dia,$mes,$anual,$hora,$minutos,"0",$deshabilitado,$incluirsegundos,$segundos);
			$script_fechas.="\n".$fechasd->script_fechas;
			$linea_para_fecha=" document.getElementById(\"i".$IDCAMPO."_3\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."_1\").value";
			$linea_para_fecha.="+\"-\"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."_2\").value";
			$linea_para_fecha.="+\" \"+";
			$linea_para_fecha.="document.getElementById(\"i".$IDCAMPO."t\").value";
		
			$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'},$linea_para_fecha,$para_grabar,$llave);
				
			if($reg{'requerido'}){
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_3\").value","''");
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_1\").value","''");
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."_2\").value","''");								
				$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."t\").value","''");
			}			
			$script_validacion.=$controlesd->validafecha("document.getElementById(\"i".$IDCAMPO."_3\").value","document.getElementById(\"i".$rsd{'idcampo'}."_1\").value","document.getElementById(\"i".$rsd{'idcampo'}."_2\").value",$rsd{'nombrecampousuario'});			
			$script_validacion.=$controlesd->validahora_hr("document.getElementById(\"i".$IDCAMPO."t\").value",$rsd{'nombrecampousuario'});										
			break;
			
						
	}
		

?>
