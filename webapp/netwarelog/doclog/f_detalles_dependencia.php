<?php

	$dependenciatabla = $regd{'dependenciatabla'};
	$campovalor = $regd{'dependenciacampovalor'};
	$campodesc = $regd{'dependenciacampodescripcion'};
	

        //echo "    - ".$reg{'nombrecampo'}." Dependencia: ".$regd{'tipodependencia'}." -     ";
	if($regd{'tipodependencia'}=="S"){
		
		//Esto es para que cargue los campos dependientes en caso de existir.
		$IDCAMPOsini = substr($IDCAMPO,1);
		$script_detalles_dependenciascargar.="dependenciascompuestas_detalles(('".$IDCAMPOsini."').replace(/__FILA/g,'_'+filas)); \n ";
		//$script_detalles_dependenciascargar.=" dependenciascompuestas_detalles('".$IDCAMPO."'); ";		
		///////
		
		//Objeto que almacena su valor por omisión cuando es edicion
		$objeto="<input type=hidden id='".$IDCAMPO."_omision' name='".$IDCAMPO."_omision' >"; 
		
		$objeto.="<select style='width:200px' id='".$IDCAMPO."' name='".$IDCAMPO."'  class='seleccion' onchange='campo_onchange(this,true)' ".$deshabilitado."  >";
	


			$campodesc=str_replace(",",",' ',",$campodesc);	
			$campodesc=" concat(".$campodesc.") as catalog_campodesc ";	
			$sql="select ".$campovalor.", ".$campodesc." from ".$dependenciatabla." order by catalog_campodesc";
			//error_log("[doclog/f_detalles_dependencia.php:27]\n".$sql);
			$campodesc="catalog_campodesc";
			$rsdependenciasimple = $conexion->consultar($sql);
			
			$inicio_dependencia=1;
			while($regsimple=$conexion->siguiente($rsdependenciasimple)){
				
				$seleccionado = "";
				if($a==0){
					
					/* Tendrá que ser diferente que en el título...
					if($reg_m[$rsd{'nombrecampo'}]==$regsimple{$campovalor}) {
						$seleccionado="selected";
					} else {
						$seleccionado="";
					}
					*/
				}
				
				if($inicio_dependencia==1){
					$inicio_dependencia=0;
					$seleccionado="selected";
				}
				
				$datoacolocar=$regsimple{$campodesc};
				$datoacolocar=str_ireplace("\n"," ",$datoacolocar);
				$datoacolocar=str_ireplace("\"","''",$datoacolocar);
				
				$objeto.="<option value='".$regsimple{$campovalor}."' ".$seleccionado." >".$datoacolocar."</option>";				
			}
			$conexion->cerrar_consulta($rsdependenciasimple);
			
		$objeto.="</select>";	
		
		$para_grabar="select";
		$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," document.getElementById(\"".$IDCAMPO."\").value ",$para_grabar,$llave);
		//echo $rsd{'nombrecampo'};
		
		if($reg{'requerido'}){
			$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"".$IDCAMPO."\").selectedIndex","-1");				
		}	
		
	} else {
		
		//CHECANDO CAMPOS DEPENDIENTES DE DETALLES
			$sql = " select nombrecampo from catalog_dependenciasfiltros where idcampo=".$rsd{'idcampo'}." ";
			//echo($sql);
			$rsdf = $conexion->consultar($sql);
			
			$sqlw=""; //SQL que será necesario para armar la consulta compuesta.
			
			$script_detalles_dependenciacompuesta_condicion=""; //Condición para ejecutar el llenado de la dependencia compuesta
					
			while($regdf=$conexion->siguiente($rsdf)){
			
				if($script_detalles_dependenciacompuesta_condicion!="") $script_detalles_dependenciacompuesta_condicion.="||";
				$script_detalles_dependenciacompuesta_condicion.="(sidcampo.indexOf(\"".$controlesd->getidcampo($regdf{'nombrecampo'})."\")!=-1)";
				
				if(strlen($sqlw)!=0) $sqlw.="%20and%20";
				//$sqlw.="%20".$regdf{'nombrecampo'}."%20%3D%20%27\"+document.getElementById('i'+sidcampo).value+\"%27%20";	
				// esta estaba antes $sqlw.="%20".$regdf{'nombrecampo'}."%20%3D%20%27\"+$('#i'+sidcampo).val()+\"%27%20";	
				$sqlw.="%20".$regdf{'nombrecampo'}."%20%3D%20%27\"+$('#i".$controlesd->getidcampo($regdf{'nombrecampo'})."_'+filaactual).val()+\"%27%20";
			}
			
			$conexion->cerrar_consulta($rsdf);
		
		
		//CHECANDO LOS CAMPOS DEPENDIENTES DE TITULOS
			$sql = " select nombrecampotitulo from doclog_dependenciasfiltros_detalles where idcampo=".$rsd{'idcampo'}." ";
			//echo $sql;
			$rsdfd = $conexion->consultar($sql);
			
			$script_detalles_dependenciacompuestatitulos_condicion=""; //Condición que si se cumple debe eliminar todos los registros del detalle.
			
			
			$script_detalles_dependenciascargar_iniciando="";
			while($regdfd=$conexion->siguiente($rsdfd)){
				if($regdfd{'nombrecampotitulo'}!=""){
					//echo "'".$regdfd{'nombrecampotitulo'}."'";
				
					if($script_detalles_dependenciacompuestatitulos_condicion!="") $script_detalles_dependenciacompuestatitulos_condicion.="||";
					$script_detalles_dependenciacompuestatitulos_condicion.="(sidcampo==\"".$controles->getidcampo($regdfd{'nombrecampotitulo'})."\")";
				
					//if(strlen($sqlw)!=0) $sqlw.="%20and%20";
					if(strlen($sqlw)!=0) $sqlw.=" and ";
					//$sqlw.="%20".$regdf{'nombrecampo'}."%20%3D%20%27\"+document.getElementById('i'+sidcampo).value+\"%27%20";	
					//$sqlw.="%20".$regdfd{'nombrecampotitulo'}."%20%3D%20%27\"+".$controles->getlinea($regdfd{'nombrecampotitulo'})."+\"%27%20";			
					$sqlw.=" ".$regdfd{'nombrecampotitulo'}." = '\"+".$controles->getlinea($regdfd{'nombrecampotitulo'})."+\"' ";			
				
					$script_detalles_dependenciascargar_iniciando.="dependenciascompuestas_detalles('".$controles->getidcampo($regdfd{'nombrecampotitulo'})."_'+filas); \n";
				
				}
								
			}
			$conexion->cerrar_consulta($rsdfd);
		
			$script_detalles_dependenciascargar.=$script_detalles_dependenciascargar_iniciando;
		
		
		//%20 <space>
		//%27 '
		//%3D =
		
		/*
		Código de respaldo
		  //var qs=\"f_dependenciacompuesta.php?ic=\"+ic+\"&cv=\"+cv+\"&cd=\"+cd+\"&dt=\"+dt+\"&sw=\"+sw;
		  //alert(qs);
	      //$(\"#div".$reg{'idcampo'}."\").load(qs);
		  //$(\"#div".$reg{'idcampo'}."\").load(\"f_dependenciacompuesta.php?ic=\"+ic+\"&cv=\"+cv+\"&cd=\"+cd+\"&dt=\"+dt+\"&sw=\"+sw);
		  //alert(sw);		
		*/
				
		//$script_dependenciacompuesta;
		
		$seleccionado_m="";
		if($a==0){
			//$seleccionado_m=$reg_m[$rsd{'nombrecampo'}];
		}
		
		if(strlen($script_detalles_dependenciacompuestatitulos_condicion)>0){
			$script_detalles_dependenciacompuesta.="							 
				 if(".$script_detalles_dependenciacompuestatitulos_condicion."){
					 quitartodaslasfilas();
				 }	
			";
		}
		
		$script_detalles_dependenciastodo=$script_detalles_dependenciacompuesta_condicion;
		if($script_detalles_dependenciastodo!="") $script_detalles_dependenciastodo="( ".$script_detalles_dependenciastodo." )&&";
		//if($script_detalles_dependenciastodo!="") $script_detalles_dependenciastodo.="||";
		//$script_detalles_dependenciastodo.=$script_detalles_dependenciacompuestatitulos_condicion;
		//if($script_detalles_dependenciastodo!="") $script_detalles_dependenciastodo.="||";
		
		
		$script_detalles_dependenciacompuesta.= " 												 
						
						 //CAMPO: ".$rsd{'nombrecampo'}."
						 //alert('idcampo: '+idcampo+' --EFWEFWEF23423r23d');
						 //alert('sidcampo='+sidcampo+'  ".$script_detalles_dependenciacompuesta_condicion."   cargandofilasiniciales='+cargandofilasiniciales);				
						 if(".$script_detalles_dependenciastodo."(!cargandofilasiniciales)){	
						 //if((".$script_detalles_dependenciastodo."sidcampo==\"iniciando\")&&(!cargandofilasiniciales)){							 
							  //document.getElementById('divdepurar').innerHTML = document.getElementById('divdepurar').innerHTML + ' entre:".$rsd{'nombrecampo'}." ';
							
							  var randomnumber=Math.floor(Math.random()*110);
							  var vic=\"".$rsd{'idcampo'}."_\"+filaactual;		
							  var vcv=\"".$campovalor."\";
							  var vcd=\"".$campodesc."\";
							  var vdt=\"".$dependenciatabla."\";
							  var vsw=\"".$sqlw."\";	
							  //alert(decodeURIComponent(sw.replace(/\+/g,  ' ')));	
								
							  //Seleccionar valor por omisión:
							  var vsm=\"".$seleccionado_m."\";
								//alert(sm);
							  var nombrecampoomision = 'i".$rsd{'idcampo'}."_'+filaactual+'_omision';
							  if(document.getElementById(nombrecampoomision)!=null) sm=document.getElementById(nombrecampoomision).value;
							  //alert(nombrecampoomision+' '+document.getElementById(nombrecampoomision)+' '+document.getElementById(nombrecampoomision).value);
							  							  							  
							  var vde=\"".$deshabilitado."\";
							  
							  var vfo=\"".$rsd{'formato'}."\";
							  var vfa=filaactual; //enviando fila actual
							  //var a = \"f_detalles_dependenciacompuesta.php?ic=\"+ic+\"&cv=\"+cv+\"&cd=\"+cd+\"&dt=\"+dt+\"&sw=\"+sw+\"&sm=\"+sm+\"&de=\"+de+\"&fo=\"+fo+\"&fa=\"+fa;									  							  
							  //var a = \"f_detalles_dependenciacompuesta.php?ic=\"+ic+\"&cv=\"+cv+\"&cd=\"+cd+\"&dt=\"+dt+\"&sw=\"+sw+\"&sm=\"+sm+\"&de=\"+de+\"&fo=\"+fo+\"&fa=\"+fa+\"&randomn=\"+randomnumber;									  							  							
							  //var a = \"f_detalles_dependenciacompuesta.php?randomn=\"+randomnumber;									  							  							
							  var a = \"f_detalles_dependenciacompuesta.php\";									  							  							
							  
								//aqui aqui
								//alert(a);
							  
							  //if(idcampo=='48'){
							  	//document.getElementById('divdepurar').innerHTML = document.getElementById('divdepurar').innerHTML + ' envié:".$rsd{'nombrecampo'}." con '+a;
							  //}
							  
							  //alert(a);							  
							  //alert(\" sidcampo=\"+sidcampo+\"   #divi".$rsd{'idcampo'}."_\"+fa+\"  procesa: \"+a);
							  
							  //Proteje la recursividad
							  //alert(sidcampo+\"!=".$rsd{'idcampo'}."_\"+fa);
							  if(sidcampo!=\"".$rsd{'idcampo'}."_\"+vfa){
									
									document.body.style.cursor = 'wait';									  																		
									var combo = $.ajax({
										type: \"POST\",
									  url: a,
										data: {
												ic: vic, 
												cv: vcv,
												cd: vcd,
												dt: vdt,
												sw: vsw,
												sm: vsm,
												de: vde,
												fo: vfo,
												fa: vfa
										},
									  async: false
									 }).responseText;
									//alert(\" \"+fa+\"        ".$rsd{'nombrecampo'}."         url:\"+a+\"          select:\"+combo);
									$(\"#divi".$rsd{'idcampo'}."_\"+vfa).html(combo);
									document.body.style.cursor='auto';
									
									campo_onchange(document.getElementById('i".$rsd{'idcampo'}."_'+vfa),true);
																																		
									
							  } //if(sidcampo=='".$rsd{'idcampo'}."_\"+fa)
							
								//alert(esperarcompuesta);
							
							  //while(document.getElementById('txtesperarcompuesta').value == 1){
								//setTimeout('return 0',1);
							  	//document.getElementById('divdepurar').innerHTML = document.getElementById('divdepurar').innerHTML + 'esperando';
							  //}
							
													  
							  //alert(document.getElementById('i48').value);
								/*
								for(i=0;i<=10000;i++)
								{
									setTimeout('return 0',1);
								}
								*/
								//alert(esperarcompuesta);							
						} 
																								
						";
		//$objeto = "<div id='div".$rsd{'idcampo'}."'><select  id='".$IDCAMPO."' name='".$IDCAMPO."' class='seleccion'><option value='0'>Ninguno</select></div>";
		
		//Objeto que almacena su valor por omisión cuando es edicion
		$objeto="<input type=hidden id='".$IDCAMPO."_omision' name='".$IDCAMPO."_omision' >"; 		
		$objeto.="<div id='div".$IDCAMPO."'><select  id='".$IDCAMPO."' name='".$IDCAMPO."' class='seleccion'></select></div>";
		
		$para_grabar="select";		
		$controlesd->agregar($rsd{'idcampo'},$rsd{'nombrecampo'}," document.getElementById(\"i".$rsd{'idcampo'}."\").value ",$para_grabar,$llave);
		
		if($reg{'requerido'}){
			$script_validacion.=$controlesd->regresarequerido($rsd{'nombrecampo'},$rsd{'nombrecampousuario'},"document.getElementById(\"i".$rsd{'idcampo'}."\").selectedIndex","-1");				
		}	
		
	}

?>
