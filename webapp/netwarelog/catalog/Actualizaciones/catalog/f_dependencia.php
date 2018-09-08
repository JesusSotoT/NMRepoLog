<?php

	$dependenciatabla = $regd{'dependenciatabla'};
	$campovalor = $regd{'dependenciacampovalor'};
	$campodesc = $regd{'dependenciacampodescripcion'};


	if($regd{'tipodependencia'}=="S"){
		
		//Esto es para que cargue los campos dependientes en caso de existir.
		$script_dependenciascargar="dependenciascompuestas('".$reg{'idcampo'}."'); \n ";
		///////
		
		$objeto="<select id='i".$reg{'idcampo'}."' name='i".$reg{'idcampo'}."' class='seleccion' onchange='campo_onchange(this,true)' ".$deshabilitado."  >";
		
			$sql="select ".$campovalor.", ".$campodesc." from ".$dependenciatabla." order by ".$campodesc;
			$rsdependenciasimple = $conexion->consultar($sql);
			while($regsimple=$conexion->siguiente($rsdependenciasimple)){
				
				$seleccionado = "";
				if($a==0){
					if($reg_m[$reg{'nombrecampo'}]==$regsimple{$campovalor}) {
						$seleccionado="selected";
					} else {
						$seleccionado="";
					}
				}
				
				$objeto.="<option value='".$regsimple{$campovalor}."' ".$seleccionado." >".$regsimple{$campodesc}."</option>";				
			}
			$conexion->cerrar_consulta($rsdependenciasimple);
			
		$objeto.="</select>";	
		
		$para_grabar="select";
		$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," document.getElementById(\"i".$reg{'idcampo'}."\").value ",$para_grabar,$llave);
		
		if($reg{'requerido'}){
			$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."\").selectedIndex","-1");				
		}	
		
	} else {
		
		//Checando los campos dependientes...
		$sql = " select nombrecampo from catalog_dependenciasfiltros where idcampo=".$reg{'idcampo'}." ";
		$rsdf = $conexion->consultar($sql);
		
		$sqlw=""; //SQL que será necesario para armar la consulta compuesta.
		
		$script_dependenciacompuesta_condicion=""; //Condición para ejecutar el llenado de la dependencia compuesta
		
		
		while($regdf=$conexion->siguiente($rsdf)){
			
			if($script_dependenciacompuesta_condicion!="") $script_dependenciacompuesta_condicion.="||";
			$script_dependenciacompuesta_condicion.="(idcampo==\"".$controles->getidcampo($regdf{'nombrecampo'})."\")";
			
			if(strlen($sqlw)!=0) $sqlw.="%20and%20";
			$sqlw.="%20".$regdf{'nombrecampo'}."%20%3D%20%27\"+".$controles->getlinea($regdf{'nombrecampo'})."+\"%27%20";			
		}
		$conexion->cerrar_consulta($rsdf);
		
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
			$seleccionado_m=$reg_m[$reg{'nombrecampo'}];
		}
		
		
		$script_dependenciacompuesta.= " 
						 
						
						 //CAMPO: ".$reg{'nombrecampo'}."
						 //alert('idcampo: '+idcampo+' --EFWEFWEF23423r23d');
						 //alert('".$script_dependenciacompuesta_condicion."');						
						 if(".$script_dependenciacompuesta_condicion."||idcampo==\"iniciando\"){
							  //document.getElementById('divdepurar').innerHTML = document.getElementById('divdepurar').innerHTML + ' entre:".$reg{'nombrecampo'}." ';
							
							  //var randomnumber=Math.floor(Math.random()*11);
							  var ic=\"".$reg{'idcampo'}."\";		
							  var cv=\"".$campovalor."\";
							  var cd=\"".$campodesc."\";
							  var dt=\"".$dependenciatabla."\";
							  var sw=\"".$sqlw."\";		
							  var sm=\"".$seleccionado_m."\";	
							  var de=\"".$deshabilitado."\";
							  var a = \"f_dependenciacompuesta.php?ic=\"+ic+\"&cv=\"+cv+\"&cd=\"+cd+\"&dt=\"+dt+\"&sw=\"+sw+\"&sm=\"+sm+\"&de=\"+de;	
							  
							  //if(idcampo=='48'){
							  //	document.getElementById('divdepurar').innerHTML = document.getElementById('divdepurar').innerHTML + ' envié:".$reg{'nombrecampo'}." con '+a;
							  //}
							
							
							  $(\"#div".$reg{'idcampo'}."\").load(\"f_dependenciacompuesta.php?ic=\"+ic+\"&cv=\"+cv+\"&cd=\"+cd+\"&dt=\"+dt+\"&sw=\"+sw+\"&sm=\"+sm+\"&de=\"+de,function(response,status,xhr){								
								campo_onchange(document.getElementById('i".$reg{'idcampo'}."'),true);
								//dependenciascompuestas('".$reg{'idcampo'}."');
								//alert('entre a dependencia compuesta me llamo:'+idcampo+' y yo soy:".$reg{'nombrecampo'}."');
								//document.getElementById('txtesperarcompuesta').value = 0;
								/*
								document.getElementById('divdepurar').innerHTML = 		
										document.getElementById('divdepurar').innerHTML + 
										' -- soy <b>".$reg{'nombrecampo'}."</b> entre por:' + idcampo + 
										'  idm=' + document.getElementById('i64').value + 
										'  mande:' + response + 
										' s:' + status + 
										' x:' + xhr.status + ' --    ';
										*/
							  });	
							
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
		$objeto = "<div id='div".$reg{'idcampo'}."'><select  id='i".$reg{'idcampo'}."' name='i".$reg{'idcampo'}."' class='seleccion'></select></div>";
		
		$para_grabar="select";		
		$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," document.getElementById(\"i".$reg{'idcampo'}."\").value ",$para_grabar,$llave);
		
		if($reg{'requerido'}){
			$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."\").selectedIndex","-1");				
		}	
		
	}

?>