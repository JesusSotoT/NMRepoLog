<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../catalog/conexionbd.php");

	//CSRF
	$reset_vars = true;
	include "../catalog/clases/clcsrf.php";
	
	//INFO DOCLOG
	$VALORCAMPOFOLIO = "";
	
	//CONTROL DE OBJETOS
	include("clases/clcontroles.php");
	$controles = new controles();
	$controlesd = new controles();
	
	//OBJETO ESPECIAL PARA FECHAS
	include("clases/clfechas.php");
	$fechas = new fechas();
	
	include("clases/clfechasdetalles.php");	
	$fechasd = new fechasdetalles();
	$fechasd->noponeri();
	
	
//Inicio de la session
	if(session_id()=='') session_start();
        
        //PARCIALLOG
        include("../catalog/clases/clparciallog.php");
        $parciallog = new clparciallog($_SESSION['nombreestructura'],$_SESSION["accelog_idperfil"],$conexion);
        
        
        
        
	
	//Información de la estructura
	$idestructura=$_SESSION['idestructura'];
	$descripcion=$_SESSION['descripcion'];	
    $utilizaidorganizacion = $_SESSION['utilizaidorganizacion'];        
    $campo_idorganizacion = $_SESSION["accelog_campo_idorganizacion"];
	$linkprocesoantes = $_SESSION['linkprocesoantes'];
	$catalog_columnas = $_SESSION['catalog_columnas'];
	$catalog_columna=0; //inicializa


	$a=$_GET['a'];
	$_SESSION['catalog_nuevo']=$a;
	
	
	//OBTIENE LA INFORMACION EN CASO DE SER PARA MODIFICAR
	$reg_m=array();
	$sqlw_p="";
	if($a==0){
		$sqlw_p=$_GET['sw'];
		$sqlw_p = str_replace("\\","",$sqlw_p);		
		//echo $sqlw_p;
		
		$sql_m = "select * from ".$_SESSION['nombreestructura']." where ".$sqlw_p;
		//echo $sql_m;
		$result_m = $conexion->consultar($sql_m);
		if($registro_m = $conexion->siguiente($result_m)){
			
			$sql_c = "select nombrecampo from catalog_campos where idestructura=".$idestructura." and formato<>'O' order by orden ";
			$result_c=$conexion->consultar($sql_c);
			while($registro_c = $conexion->siguiente($result_c)){				
				
				$reg_m[$registro_c{'nombrecampo'}]=$registro_m{$registro_c{'nombrecampo'}};
				
			}
			$conexion->cerrar_consulta($result_c);			
			
			
			/// INFORMACION ESPECIFICA DE FUNCIONAMIENTO DEL DOCLOG
				$VALORCAMPOFOLIO = $registro_m{$_SESSION["campofolio"]};
			/////////
			
			
		}		
		$conexion->cerrar_consulta($result_m);
	}
	/////
	
	//En este script se manda llamar la funcion de dependencias compuestas
	//con todos los id de las dependencias simples para evitar el iniciando.
	$script_dependenciascargar="";
	
	//SCRIPT PARA CHECAR SI NO HAY REGISTROS CON LA LLAVE YA SELECCIONADA
	$script_repetidos="";
	$campox="";
	
	//SCRIPT PARA CALCULAR FORMULAS DESPUES DE PERDER EL FOCO
	$script_dependenciacompuesta="";

	//SCRIPT PARA VALIDAR TIPOS DE DATOS Y REQUERIDOS
	$script_validacion="";
	
	//SCRIPT PARA CALCULAR FORMULAS DESPUES DE PERDER EL FOCO
	$script_calculaformulas="";

	//SCRIPT POR ALGUNOS CAMPOS QUE REQUIEREN MASCARAS O ALGO EN ESPECIAL
	$script_inputmask="";
	
	//CAMPOS NECESARIOS PARA LA CAPTURA
	$captura="<tr class='listadofila' title='Folio' valign='middle' >";
	$ultimocampocondependenciacompuesta = "";
	
	
	
	//SIMBOLO PESOS ES PARA OBJETOS NUMERICOS
	$simbolo_pesos="";
			
	$sql = "select * from catalog_campos where idestructura=".$idestructura." and formato <>'O' order by orden";
	$result = $conexion->consultar($sql);
	while($reg = $conexion->siguiente($result)){					

               
		if(strlen($campox)==0){ 
			$campox=$reg{'nombrecampo'};
		}

		
		$sql = " select * from catalog_dependencias where idcampo=".$reg{'idcampo'};
		$rsdependencias = $conexion->consultar($sql);
		$tienedependencia=false;
		if($regd = $conexion->siguiente($rsdependencias)){
			if($regd{'tipodependencia'}!="N"){
				$tienedependencia=true;
			}
		}
		
		//EN CASO DE EDICION DESHABILITAR LOS CAMPOS LLAVE
		$deshabilitado="";
		$llave="0";
		if($reg{'llaveprimaria'}) $llave="1";
		if($a==0){
			if($llave=="1") $deshabilitado="disabled";
		}
		
		
		$simbolo_pesos="";
		

		if($tienedependencia){

			//ARMA LA DEPENDENCIA SIMPLE O COMPUESTA
			include("f_dependencia.php");
			//$objeto="esto tiene dependencia";
			
		} else {
		
			//VALOR POR OMISION PARA NUEVOS
			$valor = $reg{'valor'};
			if($valor==="NA") $valor="";
			
			//EN CASO DE EDICION
			if($a==0){
                            $valor=$reg_m[$reg{'nombrecampo'}];
			}			
										
			// FORMATO -- MASCARAS DE ENTRADA  --> obtiene el $script_inputmask
			 include("f_formato.php");
			/////
		
			// VERIFICANDO EL OBJETO QUE UTILIZARA EL CAMPO PARA EL FORMULARIO  --> obtiene la variable $objeto
                         include("f_objeto.php");
                        ///////
	
			// ARMAR EL SCRIPT DE FORMULA
			 include("f_formula.php");
			/////
	

		}
		$conexion->cerrar_consulta($rsdependencias);
		
		// REQUERIDO
			$requerido="";
			if($reg{'requerido'}){
				 $requerido="*";
				 $script_requerido="
					alert('validar requeridos y tipos de datos');
                                    ";
			}
		////
		
		
		// CONSTRUYENDO EL FORMULARIO
                        $quitarcampo = false;
                        if($utilizaidorganizacion){
                            if($reg{'nombrecampo'}==$campo_idorganizacion){
                                $quitarcampo = true;
                                
                                //Campo idorganizacion --- mover por parciallog
                                $idorg=$_SESSION["accelog_idorganizacion"];
                                $captura.="<td><input type='hidden'  id='i".$reg{'idcampo'}."' value=".$idorg."></td>";
                            }
                        }
                        
                        
                        
                                                
                        ////////////////////////////////////////////////////////////////////////////////
                        //PARCIALLOG
                        ////////////////////////////////////////////////////////////////////////////////

                            if($a==0){ //en caso de edición
                               $valor_registro_parciallog=$reg_m[$reg{'nombrecampo'}];
                            }			

                            $permiso_parciallog = $parciallog->get_permiso($reg{'nombrecampo'});
                            if($permiso_parciallog=="O"){

                                $quitarcampo = true;       
                                $captura.="<input type='hidden'  name='i".$reg{'idcampo'}."'  id='i".$reg{'idcampo'}."' value='".$valor_registro_parciallog."' /> ";

                            } else if($permiso_parciallog=="L"){
                                
                                $quitarcampo = false;
                                $tamano = "50"; //TAMAÑO MAXIMO
                                if($reg{'longitud'}<$tamano){
                                    $tamano=$reg{'longitud'};
                                }	                                
                                
                                $valor_registro_parciallog_traducido = $valor_registro_parciallog;
                                
                                if(($tienedependencia)&&($a==0)){                                    
                                    $sql_validable = " 
                                        select ".$campodesc." 
                                        from ".$dependenciatabla." 
                                        where ".$reg{'nombrecampo'}." = '".$valor_registro_parciallog."'                                    
                                      ";
                                     //echo $sql_validable;
                                     $result_datos_validable = $conexion->consultar($sql_validable);
                                     if(($rs_datos_validable = $conexion->siguiente($result_datos_validable))){
                                        //echo "entre  ".$rs_datos_validable{$campodesc};
                                        $valor_registro_parciallog_traducido=$rs_datos_validable{$campodesc};
                                     } 
                                     $conexion->cerrar_consulta($result_datos_validable);                                        
                                } 
                                
                                $objeto =" <input type='text'  name='i".$reg{'idcampo'}."dis'   id='i".$reg{'idcampo'}."dis' size='".$tamano."'  disabled value='".$valor_registro_parciallog_traducido."' /> ";
                                $objeto.=" <input type='hidden'  name='i".$reg{'idcampo'}."'  id='i".$reg{'idcampo'}."' value='".$valor_registro_parciallog."' /> ";                                         
                                
                            }
                            
                        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        // FIN PARCIALLOG //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////                        
                        
                        
                        
                        
                        
						
                        if(!$quitarcampo){
	
                            //Preparando columnas
                            if($catalog_columna>$catalog_columnas){
                                    $catalog_columna=0;
                                    $captura.="</tr>";
                                    $captura.="<tr class='listadofila' title='".$reg{'descripcion'}."' valign='middle' >";
                            }
                            $catalog_columna+=1;

                            //Colocando campo
                            if(empty($captura)){
                                    $captura.="<tr class='listadofila' title='".$reg{'descripcion'}."' valign='middle' >";								
                            }
                            $captura.="                                            
                                 <td class='campo'><label id='lbl".$reg{'idcampo'}."'>
                                    ".$reg{'nombrecampousuario'}.": </label><font color=silver>".$requerido."</font> <br>
                                    ".$simbolo_pesos." ".$objeto."
                                 </td>
                                ";
								
                        } 

		////	
		
		// CHECANDO PARA SCRIPT REPETIDOS EN CASO DE NUEVO O PARA EDITAR EN CASO DE MODIFICAR
			if($a==1){
				if($reg{'llaveprimaria'}){
					if(($reg{'tipo'}!="auto_increment")||$a==0){
						if($script_repetidos!="") $script_repetidos.="%20and%20";
						$script_repetidos.="%20".$reg{'nombrecampo'}."%20%3D%20%27'+escape(".$controles->getlinea($reg{'nombrecampo'}).")+'%27";
					}
				}				
			} else {
				$script_repetidos="";
			}
		/////
			
		
	}			
	$conexion->cerrar_consulta($result);		
	
	$captura.="</tr>";
	
	
	$_SESSION['controles']=$controles;
	
	
	$poner_input_m="";
	
	//SCRIPT PARA REPEDITOS
	if($a==1){
		$script_repetidos="
				var sw = '".$script_repetidos."';
				var qs = 'f_repetidos.php?nc=".$campox."&ne=".$_SESSION['nombreestructura']."&sw='+sw;
				$('#divrepetido').load(qs, function(){
					
					//alert(qs);
					if(document.getElementById('repetido')!=null){
						if(document.getElementById('repetido').value==0){
							//alert('submiendo');
							var f = document.getElementById('frm');
							f.submit();
							//alert('no esta repetido.');
							//alert('perfecto enviando submit...');						
							//return true;
						} else {
							alert('La captura ya se encuentra registrada previamente.');
							return false;
						}
					}
				});
				";
	} else {
		$poner_input_m="<input type=\"hidden\" id=\"sw_m\" name=\"sw_m\" value=\"".$sqlw_p."\">";
		
		$script_repetidos=" 
			//var f = document.getElementById('frm');
			//alert('me dejo');
			//f.submit();	
			return true;
		";
	}
	//////
	
	//$('#result').load('ajax/test.html', function() {
	//  alert('Load was performed.');
	//});
	
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
		
		<!-- CODIGO JAVASCRIPT -->
		<script type="text/javascript" src="js/misutils.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>

		<script type="text/javascript" src="js/mootools.js"></script>
		<script type="text/javascript" src="js/imask.js"></script>

		<script type="text/javascript" src="js/maskedinput.js"></script>
		<script type="text/javascript" src="js/view.js"></script>
		<script type="text/javascript" src="js/calendar.js"></script>
		
		
		
		<script type="text/javascript">
		
			var modifico=false;				
			
			var cargandoparaeditar=true;
			var ultimocampocondependenciacompuesta="<?php echo $ultimocampocondependenciacompuesta; ?>";
			//alert(ultimocampocondependenciacompuesta);
		
			//INICIALIZANDO JQUERY
			$(document).ready(function(){
				<?php echo $script_inputmask; ?>		
				
				$('#secundariolog').hide();
				//alert("entre");   						
		 	});		
		
			function loadbody(){
				
				loadimask();
				
				calculaformulas();				
				//dependenciascompuestas("iniciando");
				<?php echo $script_dependenciascargar; ?>
			}
		
			function loadimask(){
				
				//INICIALIZANDO IMASK
				var mascaras_campos = new iMask({  
				    onFocus: function(obj) {  
				        obj.setStyles({"background-color":"#ff8", border:"1px solid #880"});  
				    },  

				    onBlur: function(obj) {  
				        obj.setStyles({"background-color":"#fff", border:"1px solid #ccc"});  
				    },  

				    onValid: function(event, obj) {  
				        obj.setStyles({"background-color":"#8f8", border:"1px solid #080"});  
				    },  

				    onInvalid: function(event, obj) {  
				        if(!event.shift) {  
				            obj.setStyles({"background-color":"#f88", border:"1px solid #800"});  
				        }  
				    }  
				});								
				
			}			
		
		
			
			function calculaformulas(){
				
				calcula_formulas_detalles();				
				
				<?php echo $script_calculaformulas; ?>
				
			}	
		
			function valida(){
				var validado=true;								
				
				<?php echo $script_validacion; ?>
				
				if(validado){
					<?php echo $script_repetidos; ?>
				}				
				
				return false;
			}
			
			function dependenciascompuestas(idcampo){	
				
				/*document.getElementById('divdepurar').innerHTML =
						document.getElementById('divdepurar').innerHTML + 
						' --entro para:'+idcampo+' ';*/
				
				
				<?php echo $script_dependenciacompuesta; ?>
				/*
				for(i=0;i<=5000;i++)
				{
					setTimeout('return 0',1);
				}			
				document.getElementById('divdepurar').innerHTML = document.getElementById('divdepurar').innerHTML + ' sali --';
				*/
				
				
			}
			
			
			function campo_keydown(){
				modifico=true;
			}
			
			
			function campo_onchange(obj,change){

			
				if(obj==null) return;
				//alert("entre");
				//alert(obj.name);
			
				var hubocambio=false;
				if(change){
					hubocambio=true;
				} else {
					if(modifico){
						hubocambio=true;
					}
				}
				
				if(hubocambio){
					
					var sidcampo = obj.name.toString().substr(1);
					var idcampo = parseInt(sidcampo);
					
					//alert(sidcampo);
					
					//CALCULANDO FORMULAS
					calculaformulas();	
					
					//REVISANDO LAS DEPENDENCIAS COMPUESTAS
					dependenciascompuestas(idcampo);						
					dependenciascompuestas_detalles(sidcampo);
					
				}
				
				modifico=false;
			}
						
		</script>		
		
	</head>
	<body onload="loadbody()">
            
		<div id="divrepetido"></div>


		<script>
			var div_secundariolog="";
			var div_secundariolog_q="";
			var div_secundariolog_o="";
			var div_secundariolog_d="";
			var div_secundariolog_c="";
			var div_secundariolog_v="";

			function btn_cerrar_secundariolog_click(){
				//alert(div_secundariolog_q);
				var url_q="";
				url_q+="f_secundariolog.php?q="+div_secundariolog_q+"&o="+div_secundariolog_o;
				url_q+="&c="+div_secundariolog_c+"&v="+div_secundariolog_v;
				url_q+="&d="+div_secundariolog_d;
				//alert(url_q);
				$("#"+div_secundariolog).load(url_q);
				$("#secundariolog").fadeOut(); 
			}
		</script>
		<div id="secundariolog">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tbody>
					<tr valign="top">
						<td><iframe scroll="none" id="frsecundariolog"></iframe></td>
						<td><input type="button" id="btn_cerrar_secundariolog" 
							value = "x"
							onclick="btn_cerrar_secundariolog_click()" /></td>							
					</tr>
				</tbody>
			</table>					
		</div>		

                               
		<!--FORMULARIO-->
		<form id="frm" enctype="multipart/form-data" action="fg.php" method="post" onSubmit="return valida();">
			<?php 
				//CSRF - FORM
				echo $csrf->input_token($token_id,$token_value);	 
			?>			

			<input type='hidden' name='VALORCAMPOFOLIO' value='<?php echo $VALORCAMPOFOLIO; ?>'>
			
			
           	<?php echo $poner_input_m; ?>
			
			<table border="0" class="campos" cellpadding="3" cellspacing="0">
				
				<!-- TIPO DE REGISTRO -->
				<tr><td>
					<div class="tipo">
						<a href="javascript:window.print();"><img src="../repolog/img/impresora.png" border="0"></a>
						&nbsp; &nbsp; <b><?php if($a==1) echo "Registro nuevo"; else echo "Editar registro"; ?></b>
					</div>			
					<br>
				</td></tr>
				
				<!-- CAMPOS -->
				<?php echo $captura; ?>
				
				
			</table>	
			
				<!--LINK PROCESO ANTES-->
				<?php
					if(!empty($linkprocesoantes)){
						include $linkprocesoantes;
					}					
				?>
				
				<br>
				<?php
					include "f_detalles.php";				
				?>
				
				<br>
				<input id="send" type="submit" value="Guardar">
				
				<div id="divdepurar"></div>
							
		</form>		
		
	</body>
</html>
