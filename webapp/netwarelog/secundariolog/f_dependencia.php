<?php

	$dependenciatabla = $regd{'dependenciatabla'};
	$campovalor = $regd{'dependenciacampovalor'};
	$campodesc = $regd{'dependenciacampodescripcion'};

        //echo "    - ".$reg{'nombrecampo'}." Dependencia: ".$regd{'tipodependencia'}." -     ";
	if($regd{'tipodependencia'}=="S"){
		
		//Esto es para que cargue los campos dependientes en caso de existir.
		$script_dependenciascargar.="dependenciascompuestas('".$reg{'idcampo'}."'); \n ";
		///////
		
		$objeto="
				<div id='i".$reg{'idcampo'}."_div'>
				<select id='i".$reg{'idcampo'}."' name='i".$reg{'idcampo'}."' class=' nminputselect ' onchange='campo_onchange(this,true)' ".$deshabilitado."  >";
		
			
			
				// S E C C I O N   E S P E C I A L :: ACCELOG_NIVELES   --antes para sistema de Intermerk eliminar este fragmento para otros sistemas CCP
				
					$sqlw="";
					$sql_accelog_niveles = "select nombrecampo_empleados, nombreestructura from accelog_niveles where idestructura=".$idestructura." and nombrecampo_empleados='".$reg{'nombrecampo'}."'";		
					$result_accelog_niveles = $conexion->consultar($sql_accelog_niveles);
					$sql_an_w="";
					$aplicar_an = 0;
					//echo $sql_accelog_niveles;
                    while($rs_an = $conexion->siguiente($result_accelog_niveles)){
						
						$aplicar_an = -1;
						
						$nombrecampo_empleados_an = $rs_an{"nombrecampo_empleados"};
						$nombreestructura_an = $rs_an{"nombreestructura"};
												
						$idempleado = $_SESSION["accelog_idempleado"];			
						$sql_an_especial = " select ".$nombrecampo_empleados_an." from ".$nombreestructura_an." where idempleado=".$idempleado;
						$result_an_especial = $conexion->consultar($sql_an_especial);
						while($rs_an_especial = $conexion->siguiente($result_an_especial)){
							if($sql_an_w!=="") $sql_an_w.=" or ";							
							$sql_an_w.=" ".$nombrecampo_empleados_an." = '".$rs_an_especial{$nombrecampo_empleados_an}."' ";
						}
								
					}

					if($aplicar_an){
						if($sqlw!==""){
							$sqlw.=" and ";
						} else {
							$sqlw = " where ";	
						}
						$sqlw.=" (".$sql_an_w.") ";
						//echo $sqlw;
                        //exit();
					}
					
					
				///////////////
		
		
		
			$campodesc=str_replace(",",",' ',",$campodesc);	
			$campodesc=" concat(".$campodesc.") as catalog_campodesc ";	
	
			$sql="select ".$campovalor.", ".$campodesc." from ".$dependenciatabla." ".$sqlw." order by catalog_campodesc";
			//echo $sql;
			$campodesc="catalog_campodesc";
			$rsdependenciasimple = $conexion->consultar($sql);
                        
                        
                        $inicio_parciallog = 0;
			while($regsimple=$conexion->siguiente($rsdependenciasimple)){
				
                                                            
				$seleccionado = "";
				if($a==0){
					if($reg_m[$reg{'nombrecampo'}]==$regsimple{$campovalor}) {
						
						$seleccionado="selected";
                                                
                        //parciallog
                        $valor = $regsimple{$campovalor};
                                                
					} else {
						$seleccionado="";
					}
				} else {
                                    
                    //parciallog
                    if($inicio_parciallog==0) $valor=$regsimple{$campovalor}; else $inicio_parciallog=1;
                                    
                }
				
				$objeto.="<option value='".$regsimple{$campovalor}."' ".$seleccionado." >".$regsimple{$campodesc}."</option>";				
			}
			$conexion->cerrar_consulta($rsdependenciasimple);
			
		$objeto.="</select></div>";

		/*" <input type='button' value='...' onclick='btn_i".$reg{'idcampo'}."_click();' /> ";
		$objeto.="<script>";
		$objeto.="  function btn_i".$reg{'idcampo'}."_click(){";

			$sql_dt = "select idestructura from catalog_estructuras where nombreestructura='".$dependenciatabla."' ";
			$rs_dt = $conexion->consultar($sql_dt);
			if($reg_dt=$conexion->siguiente($rs_dt)){
				$idestructura_dt = $reg_dt["idestructura"];
			}
			$conexion->cerrar_consulta($rs_dt);

		$objeto.="     window.open('../catalog/gestor.php?idestructura=".$idestructura_dt."&ticket=testing');";
		$objeto.="  }";
		$objeto.="</script>";*/
		
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
			
			//if(strlen($sqlw)!=0) $sqlw.="%20and%20";
			//$sqlw.="%20".$regdf{'nombrecampo'}."%20%3D%20%27\"+".$controles->getlinea($regdf{'nombrecampo'})."+\"%27%20";			
			if(strlen($sqlw)!=0) $sqlw.=" and ";
			$sqlw.=" ".$regdf{'nombrecampo'}." = '\"+".$controles->getlinea($regdf{'nombrecampo'})."+\"' ";

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
							  var vic=\"".$reg{'idcampo'}."\";		
							  var vcv=\"".$campovalor."\";
							  var vcd=\"".$campodesc."\";
							  var vdt=\"".$dependenciatabla."\";
							  var vsw=\"".$sqlw."\";		
							  var vsm=\"".$seleccionado_m."\";	
							  var vde=\"".$deshabilitado."\";
							  var vfo=\"".$reg{'formato'}."\"; 
							  //var a = \"f_dependenciacompuesta.php?ic=\"+ic+\"&cv=\"+cv+\"&cd=\"+cd+\"&dt=\"+dt+\"&sw=\"+sw+\"&sm=\"+sm+\"&de=\"+de+\"&fo=\"+fo;	
							  var a = \"f_dependenciacompuesta.php\";	
							  
							  //if(idcampo=='48'){
							  	//document.getElementById('divdepurar').innerHTML = document.getElementById('divdepurar').innerHTML + ' envié:".$reg{'nombrecampo'}." con '+a;
							  //}
							
							
							  //$(\"#div".$reg{'idcampo'}."\").load(\"f_dependenciacompuesta.php?ic=\"+ic+\"&cv=\"+cv+\"&cd=\"+cd+\"&dt=\"+dt+\"&sw=\"+sw+\"&sm=\"+sm+\"&de=\"+de,function(response,status,xhr){								
							  
								//Funcionando con GET
								//$(\"#div".$reg{'idcampo'}."\").load(a,function(response,status,xhr){
								
								$(\"#div".$reg{'idcampo'}."\").load(a,{
												ic: vic, 
												cv: vcv,
												cd: vcd,
												dt: vdt,
												sw: vsw,
												sm: vsm,
												de: vde,
												fo: vfo 
											},function(response,status,xhr){


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
		$objeto = "<div id='div".$reg{'idcampo'}."'><select  id='i".$reg{'idcampo'}."' name='i".$reg{'idcampo'}."' class=' nminputselect '></select></div>";
		
		$para_grabar="select";		
		$controles->agregar($reg{'idcampo'},$reg{'nombrecampo'}," document.getElementById(\"i".$reg{'idcampo'}."\").value ",$para_grabar,$llave);
		
		if($reg{'requerido'}){
			$script_validacion.=$controles->regresarequerido($reg{'nombrecampo'},$reg{'nombrecampousuario'},"document.getElementById(\"i".$reg{'idcampo'}."\").selectedIndex","-1");				
		}	
		
	}

?>
