<?php

	include("conexionbd.php");


	if(session_id()=='') session_start();
        

        //PARCIALLOG
        include("clases/clparciallog.php");
        $parciallog = new clparciallog($_SESSION['secundariolog_nombreestructura'],$_SESSION["accelog_idperfil"],$conexion);


        
	$idestructura = $_SESSION['secundariolog_idestructura'];
	$nombreestructura = $_SESSION['secundariolog_nombreestructura'];
	$descripcion = $_SESSION['secundariolog_descripcion'];

        $utilizaidorganizacion = $_SESSION['secundariolog_utilizaidorganizacion'];
        $idorganizacion=$_SESSION["accelog_idorganizacion"];
        $campo_idorganizacion = $_SESSION["accelog_campo_idorganizacion"];
        //echo "-".$_SESSION["accelog_campo_idorganizacion"]."- ";
        //echo $url_dominio;


    ///// PREPARAR PAGINACION SOLICITADA POR USUARIO ////////

    if(!isset($_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']])){
    	$_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']]=0;
    }

    $filas = 0;
	if(isset($_POST["txtf"])) $filas = $_POST["txtf"];
	if($filas==0){
		if($_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']]!=0){
			$_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']]-=$filas_pagina;					
		}
	} else {
		if($_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']."_limite"]!="1"){
			$_SESSION["secundariolog_pag_".$_SESSION['secundariolog_nombreestructura']]+=$filas_pagina;			
		}				
	}

    ////////////	


//CSRF
$reset_vars = false;
include("../catalog/clases/clcsrf.php");	


	$m=$_GET['m'];

    $primeravez=0;
    if(isset($_GET['primeravez'])){
        $primeravez=1;
    } else {

			//CSRF
			if(!$csrf->check_valid('post')){
				$accelog_access->raise_404(); 
				exit();
			}

		}


	$columnas="";
	$filas="";
	$filtros="";
	$campos=array();
	$tipo=array();
	$llave=array();

        $validables=array();


	$script_paralinks="";


	//Obteniendo encabezado ...
	$sql = " 
               select * from catalog_campos  
               where idestructura=".$idestructura." 
                     and formato<>'O' ".$parciallog->get_where_excluircampos()."
               order by orden";
	//echo $sql;
	$result = $conexion->consultar($sql);
	$sqlw = "";
        

        //S E C C I O N   P A R A  M O S T R A R    E S T A T U S      P O R     D E F E C T O
           $nombrecampoestatus="";
           $valorestatus="";
           
        if($primeravez==1){ //MODIFICACION 2011-05-05   
               $sqlestatus = " SELECT c.nombrecampo,me.valordefault  FROM configuracion_manejadorestatus me 
                                    inner join catalog_campos c on me.idcampo=c.idcampo 
                                where me.idestructura=".$idestructura."  limit 1";
                                
                $resultestatus = $conexion->consultar($sqlestatus);
                if(($rsestatus = $conexion->siguiente($resultestatus))){
                    $nombrecampoestatus = $rsestatus{"nombrecampo"};
                    $valorestatus = $rsestatus{"valordefault"};
                        
                        
                        if($nombrecampoestatus <> ""){ 
                                $sqlw = " ".$nombrecampoestatus." = ".$valorestatus;   
                        }
                }
                $conexion->cerrar_consulta($resultestatus);

				$_SESSION["secundariolog_pag_".$nombreestructura]=0;

         } 




        // S E C C I O N   E S P E C I A L :: ACCELOG_NIVELES   --antes para sistema de Intermerk eliminar este fragmento para otros sistemas CCP
		
			$sql_accelog_niveles = "select nombrecampo_empleados, nombreestructura from accelog_niveles where idestructura=".$idestructura;		
			$result_accelog_niveles = $conexion->consultar($sql_accelog_niveles);
			$sql_an_w="";
			$aplicar_an = 0;
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
				}
				$sqlw.=" (".$sql_an_w.") ";
				//echo $sqlw;			
			}
			
			
			
			/* LO ANTERIOR
			if($idestructura==93){
				$idempleado =  $_SESSION["accelog_idempleado"];
				$sql_especial = " select idccp from empleados where idEmpleado=".$idempleado."  ";
				$result_especial = $conexion->consultar($sql_especial);
				if(($rs_especial = $conexion->siguiente($result_especial))){
					$idccp = $rs_especial{"idccp"};
				}
				$conexion->cerrar_consulta($result_especial);
				
				if($primeravez==1){ //MODIFICACION 2010-09-24 Solo debe tener seleccionado por omisión el ccp pero no bloquear.
					$sqlw = " idccp = ".$idccp;   
				}
				
			}*/
			
        ///////////////



	while($reg = $conexion->siguiente($result)){
		$columnas.="<td align='center'>".$reg{'nombrecampousuario'}."</td>";

                


                //Obteniendo valor ...
                $valor="";
		if(!empty($_REQUEST["i".$reg{'idcampo'}])){
			$valor= mysql_real_escape_string($conexion->escapalog($_REQUEST["i".$reg{'idcampo'}]));
			
                        if($valor=="TODOS_VAL_2010-08-26") $valor="";

                        if($valor!=""){
                            if($sqlw!="") $sqlw.=" and ";
                            if($reg{'tipo'}=='int'||$reg{'tipo'}=='double'||$reg{'tipo'}=='auto_increment'){
                                    $sqlw.="".$reg{'nombrecampo'}."='".$valor."'";
                            } else {
                                    $sqlw.="".$reg{'nombrecampo'}." like '%".$valor."%'";
                            }
                        }

		} else {

                    if($primeravez==1){
                        if($reg{'tipo'}=="date"){
                            $valor=date("Y-m-d");
                            if($sqlw!="") $sqlw.=" and ";
                             $sqlw.="".$reg{'nombrecampo'}." = '".$valor."'";
                        }
                        if($reg{'tipo'}=="datetime"){
                            $valor=date("Y-m-d H:i:s");
                            if($sqlw!="") $sqlw.=" and ";
                             $sqlw.="".$reg{'nombrecampo'}." = '".$valor."'";
                        }
                    }


                }


                //Checando si es validable
                $sql_validable = " select * from catalog_dependencias where idcampo=".$reg{'idcampo'};
				//echo $sql_validable." <br> ";
                $result_datos_validable = $conexion->consultar($sql_validable);
				$es_validable=false;
                if(($rs_datos_validable=$conexion->siguiente($result_datos_validable))){
					if($rs_datos_validable{'tipodependencia'}!="N"){
						$es_validable=true;						
					}
				}
				
				if($es_validable){
					
                    	$validables["validable"][$reg{'idcampo'}] = "S";
                    	$validables["tabla"][$reg{'idcampo'}] = $rs_datos_validable{'dependenciatabla'};
                    	$validables["campodescripcion"][$reg{'idcampo'}] = $rs_datos_validable{'dependenciacampodescripcion'};

						
						//Si es validable anexa el combo en vez de campo de búsqueda
                    	$filtros.="<td>
                        <select  id='i".$reg{'idcampo'}."' name='i".$reg{'idcampo'}."'
                            title='Segmento de búsqueda, aplique un filtro sobre el campo: ".$reg{'nombrecampousuario'}.".'
                            onchange='subirinfo()' >";

                            $sql_para_filtro = "
                                    select ".$reg{'nombrecampo'}.", ".$rs_datos_validable{'dependenciacampodescripcion'}."
                                    from ".$rs_datos_validable{'dependenciatabla'}."
                                    order by ".$rs_datos_validable{'dependenciacampodescripcion'}."
                                    ";
                            $result_para_filtro = $conexion->consultar($sql_para_filtro);
							
							
							$seltodos = "selected";
							//echo $rs_datos_validable{'dependenciatabla'}."   ";
							if($primeravez==1){
								if(($nombrecampoestatus<>""&&$nombrecampoestatus==$reg{'nombrecampo'})){								
									$seltodos = "";
									$valor = $valorestatus;
								} 							
							}

							
                             $filtros.="<option value='TODOS_VAL_2010-08-26'   ".$seltodos."   >TODOS";
                            while($rs_para_filtro=$conexion->siguiente($result_para_filtro)){
                                $sel_para_filtro="";
                                if($rs_para_filtro{$reg{'nombrecampo'}}==$valor){
                                    $sel_para_filtro="selected";
                                }
                                $filtros.="
                                    <option value='".$rs_para_filtro{$reg{'nombrecampo'}}."'   ".$sel_para_filtro."   >
                                        ".$rs_para_filtro{$rs_datos_validable{'dependenciacampodescripcion'}};
                            }
                            $conexion->cerrar_consulta($result_para_filtro);
                            $filtros.="</td>";    
                                                                                    
                } else {
	
                    $validables["validable"][$reg{'idcampo'}] = "N";
                    $validables["tabla"][$reg{'idcampo'}] = "";
                    $validables["campodescripcion"][$reg{'idcampo'}] = "";

                    $filtros.="<td><input class='input_filtro' id='i".$reg{'idcampo'}."' name='i".$reg{'idcampo'}."'
					size='20' type='text' onkeydown='input_keydown(event)' value='".$valor."'
					title='Segmento de búsqueda, aplique un filtro sobre el campo: ".$reg{'nombrecampousuario'}.".' />
					</td>";

                }
                $conexion->cerrar_consulta($result_datos_validable);

                //////

		
		
		

					
		$campos[$reg{'idcampo'}]=$reg{'nombrecampo'};
		$tipo[$reg{'idcampo'}]=$reg{'tipo'};
		$formato[$reg{'idcampo'}]=$reg{'formato'};
		if($reg{'llaveprimaria'}){
			$llave[$reg{'nombrecampo'}]=1;
		} else {
			$llave[$reg{'nombrecampo'}]=0;
		}
	}
	$conexion->cerrar_consulta($result);
	




        if($utilizaidorganizacion){
            if($sqlw!=""){
                $sqlw=" where  (".$sqlw.") and ".$campo_idorganizacion." = ".$idorganizacion;
            } else {
                $sqlw=" where  ".$campo_idorganizacion." = ".$idorganizacion;
            }
        } else {
            if($sqlw!="") $sqlw=" where ".$sqlw;
        }

        //echo $sqlw;


		//PAGINACION
		if(!isset($_SESSION["secundariolog_pag_".$nombreestructura])){
			$_SESSION["secundariolog_pag_".$nombreestructura]=0;			
		} else {
			if($_SESSION["secundariolog_pag_".$nombreestructura]<0){
				$_SESSION["secundariolog_pag_".$nombreestructura]=0;					
			}			
		}
		$pagina = $_SESSION["secundariolog_pag_".$nombreestructura];					
		////////
		

	// CONSULTA PRINCIPAL ...
	//Obteniendo datos ...
	$sql = " select * from ".$nombreestructura."  ".$sqlw." limit ".$pagina.",".$filas_pagina;
	//echo $sql;
	
	
	
        //MOSTRAR SQL
        //echo $sql;
        

	$result = $conexion->consultar($sql);
	$i=0;
	$f=0;
	while($reg = $conexion->siguiente($result)){
		$f=$f+1;
		
		if($i==0){
			$filas.="<tr class='busqueda_fila'>";	
			$i=1;
		} else {
			$filas.="<tr class='busqueda_fila2'>";				
			$i=0;
		}
			$sqlw="";
			foreach($campos as $idcampo => $nombrecampo){

                                //echo $nombrecampo;
				if($llave[$nombrecampo]==1){
					if($sqlw!="") $sqlw.="%20and%20";
					$sqlw.=$nombrecampo."%20%3D%20%27".$reg{$nombrecampo}."%27";
				}

                                if($validables["validable"][$idcampo]=="S"){
                                    $sql_validable = " 
                                        select ".$validables["campodescripcion"][$idcampo]."
                                        from ".$validables["tabla"][$idcampo]."
                                        where ".$nombrecampo." = '".$reg{$nombrecampo}."'                                    
                                            ";
                                     $result_datos_validable = $conexion->consultar($sql_validable);
                                     if(($rs_datos_validable = $conexion->siguiente($result_datos_validable))){
                                        $info=$rs_datos_validable{$validables["campodescripcion"][$idcampo]};
                                     } else {
                                         $info=$reg{$nombrecampo};
                                     }
                                     $conexion->cerrar_consulta($result_datos_validable);

                                } else { // else de if($validables["validable"][$idcampo]=="S")


									if($tipo[$idcampo]=="archivo_base"){
										$info = $reg{$nombrecampo."_name"};
									} else {
																				
	                                    $info=$reg{$nombrecampo};

										if($formato[$idcampo]=="#"){
											$info = "****";
										}
																				
									} //if($tipo[$idcampo]=="archivo_base")
									
									
                                } //if($validables["validable"][$idcampo]=="S")



                                        $filas.="<td>";
                                        $filas.="<a id='a".$idcampo.$f."' class='a_registro' href='#' title='Seleccionar registro.'>";
                                        $filas.=$info;
                                        $filas.="</a>";
                                        $filas.="</td>";

			}
			
			foreach($campos as $idcampo => $nombrecampo){

				if($m==0){ 
					$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', 'javascript:eliminar(\"".$sqlw."\")');";	
				} else {
					$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', 'f.php?a=0&sw=".$sqlw."'); ";	
				}				
				
				//$script_paralinks.="\n $(\"#a".$idcampo.$f."[href]\")='".$link."sw=".$sqlw."'; ";
				//$script_paralinks.="\n $(\"#a".$idcampo.$f."\").attr('href', '".$link."sw=".$sqlw."'); ";				
			}
			
		$filas.="</tr>";
	}
	
	//PAGINACION
	if($f<$filas_pagina){
		$_SESSION["secundariolog_pag_".$nombreestructura."_limite"] = "1";			
	} else {
		$_SESSION["secundariolog_pag_".$nombreestructura."_limite"] = "0";
	}
	////////

	$conexion->cerrar_consulta($result);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>						
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $descripcion ?></title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->				
		
		<!--RECURSOS EXTERNOS CSS-->		
		<LINK href="css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">
			//INICIALIZA EL JQUERY
			$(document).ready(function(){
				window.parent.$("li").removeClass("focus");
				if(<?php echo $m; ?>==1){
					window.parent.$("#liedit").addClass("focus");
				} else {
					window.parent.$("#lidelete").addClass("focus");
				}
				
				<?php echo $script_paralinks; ?>

				$(".nmcatalogbusquedaselect").select2({
					language: "es"
				});
				$(".nmcatalogbusquedaselect").next(".select2").find(".select2-selection").focus(function() {
					//console.log($(this).parent().parent().prev());
					$(this).parent().parent().prev().select2("open");
				});
		 	});		
		
			function eliminar(sw){
				if(confirm("¿Esta seguro de querer eliminar el registro?")){
					window.location="e.php?sw="+sw;					
				}
			}
			
			function input_keydown(evt){
				var key = evt.keyCode;
				if(key==13){
					subirinfo();
				}
				
			}

			function subirinfo(){
                document.getElementById("frm").submit();
			}

			//si f=1 muestra las siguientes $filas_pagina filas si es 0 muestra las $filas_pagina filas previas
			function mostrar_filas(f){
				/*var surl = "b_paginacion.php?f="+f+"&m=<?php echo $m; ?>";
				alert(surl);
				document.location = surl;*/
				document.getElementById("txtf").value = f;			
				//alert("ok");
				subirinfo();
			}

		
		</script>
	</head>
	<body>
								
		<div class="tipo">
			<table>
				<tbody>
					<tr>
						<td><input type='button' value='<' onclick="mostrar_filas(0)" title='Regresar a las <?php echo $filas_pagina; ?> filas previas'></td>
						<td><input type='button' value='>' onclick="mostrar_filas(1)" title='Mostrar las siguientes <?php echo $filas_pagina; ?> filas'></td>
						<td><a href="javascript:window.print();"><img src="../repolog/img/impresora.png" border="0"></a></td>
						<td><b><?php echo $descripcion; ?></b></td>
					</tr>
				</tbody>
			</table>
		</div>
		<br>
		
		<table class="busqueda" border="1" cellpadding="3" cellspacing="1">
			<tr class="tit_tabla_buscar">
				<?php echo $columnas; ?>
			</tr>
			
			<tr class="titulo_filtros" title='Segmento de búsqueda'>
				
				<!--FORMULARIO-->
				<form id="frm" name="frm" method="post" action="b.php?m=<?php echo $m; ?>" >
					
					<?php 
					//CSRF - FORM
					echo $csrf->input_token($token_id,$token_value);	 
					?>			

				    <?php echo $filtros; ?>				
				
				    <input type="hidden" name="txtf" id="txtf" />
				    <input type="hidden" name="rand" value="<?php echo rand(1,100); ?>" />

				</form>
				
			</tr>
			
			
			<?php echo $filas; ?>
			
		</table>
		
	</body>
</html>
<?php
	$conexion->cerrar();
?>
