<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("conexionbd.php");


	//CSRF
	$reset_vars = false;
	include "../catalog/clases/clcsrf.php";	
        
	//CONTROL DE OBJETOS
	include("clases/clcontroles.php");
	$controles = new controles();
	
        
	//OBJETO ESPECIAL PARA FECHAS
	include("clases/clfechas.php");
	$fechas = new fechas();
                               	
	//Inicio de la session
	if(session_id()=='') session_start();
	
        
        
        //PARCIALLOG
        include("clases/clparciallog.php");
        $parciallog = new clparciallog($_SESSION['secundariolog_nombreestructura'],$_SESSION["accelog_idperfil"],$conexion);
        
        
        
        
        
	//Información de la estructura
	$idestructura=$_SESSION['secundariolog_idestructura'];
	$descripcion=$_SESSION['secundariolog_descripcion'];	
        $utilizaidorganizacion = $_SESSION['secundariolog_utilizaidorganizacion'];        
        $campo_idorganizacion = $_SESSION["accelog_campo_idorganizacion"];
	$linkprocesoantes = $_SESSION['secundariolog_linkprocesoantes'];
	$catalog_columnas = $_SESSION['secundariolog_catalog_columnas'];
	$catalog_columna=0; //inicializa


	$a=$_GET['a'];
	$_SESSION['secundariolog_catalog_nuevo']=$a;
	
	
	//OBTIENE LA INFORMACION EN CASO DE SER PARA MODIFICAR
	$reg_m=array();
	$sqlw_p="";
	if($a==0){
		$sqlw_p=$_GET['sw'];
		$sqlw_p = str_replace("\\","",$sqlw_p);		
		//echo $sqlw_p;
		
		$sql_m = "select * from ".$_SESSION['secundariolog_nombreestructura']." where ".$sqlw_p;
		//echo $sql_m;
		$result_m = $conexion->consultar($sql_m);
		if($registro_m = $conexion->siguiente($result_m)){
			
			$sql_c = "select nombrecampo,tipo from catalog_campos where idestructura=".$idestructura." and formato<>'O' order by orden ";
			$result_c=$conexion->consultar($sql_c);
			while($registro_c = $conexion->siguiente($result_c)){
				
				if($registro_c{"tipo"}=="archivo_base"){
					$reg_m[$registro_c{'nombrecampo'}]=$registro_m{$registro_c{'nombrecampo'}."_name"};
				} else {
					$reg_m[$registro_c{'nombrecampo'}]=$registro_m{$registro_c{'nombrecampo'}};
				}			
				
			}
			$conexion->cerrar_consulta($result_c);			
			
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
	$captura="";
	
	//SIMBOLO PESOS ES PARA OBJETOS NUMERICOS
	$simbolo_pesos="";
			

	$sql = "select * from catalog_campos where idestructura=".$idestructura." and formato<>'O' order by orden";
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
		
                $valor = "";
                
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
                        
                        //ID ORGANIZACION
                        if($utilizaidorganizacion){
                            if($reg{'nombrecampo'}==$campo_idorganizacion){
                                $quitarcampo = true;

                                //Campo idorganizacion --- mover por parciallog
                                $idorg=$_SESSION["accelog_idorganizacion"];
                                $captura.="<input type='hidden'  id='i".$reg{'idcampo'}."' value=".$idorg.">";                                
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
                            $catalog_columna+=1;
                            if($catalog_columna>$catalog_columnas){
                                    $catalog_columna=0;
                                    //$captura.="</tr>";
                                    $captura.="</div>";
                                    //$captura.="<tr class='listadofila' title='".$reg{'descripcion'}."' valign='middle' >";
                                    $captura.="<div class='row'>";
                            }

                            //Colocando campo
                            if(empty($captura)){
                                    //$captura.="<tr class='listadofila' title='".$reg{'descripcion'}."' valign='middle' >";								
                                    $captura.="<div class='row'>";
                            }
                            //Agrenando Label Evesmax
                            /*$captura.="                                            
	                             <td class='campo'><label id='lbl".$reg{'idcampo'}."'>
                                            ".$reg{'nombrecampousuario'}.": </label><font color=silver>".$requerido."</font> <br>
                                            ".$simbolo_pesos." ".$objeto."
                                     </td>
                                    ";*/
                            if($simbolo_pesos=="$"){
                                $captura.="
                             	<div class='col-md-4 nmfieldcell'>
                             		<label id='lbl".$reg{'idcampo'}."' for='i".$reg{'idcampo'}."'>
                             			".$reg{'nombrecampousuario'}.": 
										<font style='color:#FF0000; font-weight:bold;'>".$requerido."</font>
									</label>
									<div class='input-group'>												
										<div class='input-group-addon'>$</div>
                             		    ".$objeto."
                             		</div>
                             	</div>
                             	";                 		
                            } else {
                            	$captura.="
                             	<div class='col-md-4 nmfieldcell'>
                             		<label id='lbl".$reg{'idcampo'}."' for='i".$reg{'idcampo'}."'>
                             			".$reg{'nombrecampousuario'}.": 
										<font style='color:#FF0000; font-weight:bold;'>".$requerido."</font>
									</label>
                             		".$simbolo_pesos." ".$objeto."
                             	</div>
                             	";
                            }
                        } 

		////	
		
		// CHECANDO PARA SCRIPT REPETIDOS EN CASO DE NUEVO O PARA EDITAR EN CASO DE MODIFICAR
			if($a==1){
				if($reg{'llaveprimaria'}){
					if(($reg{'tipo'}!="auto_increment")||$a==0){
						if($script_repetidos!="") $script_repetidos.="%20and%20";
						$script_repetidos.="%20".$reg{'nombrecampo'}."%20%3D%20%27'+".$controles->getlinea($reg{'nombrecampo'})."+'%27";
					}
				}				
			} else {
				$script_repetidos="";
			}
		/////
			
		
	}			
	$conexion->cerrar_consulta($result);		
	
	$captura.="</tr>";
	
	
	$_SESSION['secundariolog_controles']=$controles;
	
	
	$poner_input_m="";
	
	//SCRIPT PARA REPEDITOS
	if($a==1){
		$script_repetidos="
				var sw = '".$script_repetidos."';
				var qs = 'f_repetidos.php?nc=".$campox."&ne=".$_SESSION['secundariolog_nombreestructura']."&sw='+sw;
				$('#divrepetido').load(qs, function(){
					
					//alert(qs);
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

	    <?php include('../design/css.php');?>
        <LINK href="../design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

  		<!--  ##### BOOTSTRAP & FONT ###### -->
    	<link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    	<link href="../../libraries/select2/dist/css/select2.min.css" rel="stylesheet">       
 
   		<!--  ##### BEGIN: BOOTSTRAP & JQUERY ###### -->
		<script src="../../libraries/jquery.min.js"></script>
   		<script src="../../libraries/jquery-migrate.min.js"></script>   		
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script> 
		<script src="../../libraries/select2/dist/js/select2.min.js"></script>
		<script src="../../libraries/select2/dist/js/i18n/es.js"></script>
		<!--  ##### END: BOOTSTRAP & JQUERY ###### -->  
		
		<!-- CODIGO JAVASCRIPT -->
		<script type="text/javascript" src="js/misutils.js"></script>
		<script type="text/javascript" src="js/mootools.js"></script>
		<script type="text/javascript" src="js/imask.js"></script>
		<script type="text/javascript" src="js/maskedinput.js"></script>
		<script type="text/javascript" src="js/view.js"></script>
		<script type="text/javascript" src="js/calendar.js"></script>
		
		
		<script type="text/javascript">
		
			var modifico=false;					
		
			//INICIALIZANDO JQUERY
			$(document).ready(function(){
				<?php echo $script_inputmask; ?>

				<?php  if($a==1){ // Nuevo   ?>					
				window.parent.$("li").removeClass("focus");
				window.parent.$("#linew").addClass("focus");
				<?php  } ?>

				// Select Dependences
				$('.nminputselect').select2({
                	language: "es",
                	width: "100%"
                });
				$('.nminputselect').next(".select2").find(".select2-selection").focus(function() {
					$(this).parent().parent().prev().select2("open");
				});

				// Select Boolean
				$('.nminputselect_boolean').select2({
					language: "es",
					width: "60px"
				});
				$('.nminputselect_boolean').next(".select2").find(".select2-selection").focus(function() {
					$(this).parent().parent().prev().select2("open");
				});


				
		 	});		
		
			function loadbody(){
				//INICIALIZANDO IMASK
				new iMask({  
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
				
				calculaformulas();				
				//dependenciascompuestas("iniciando");
				<?php echo $script_dependenciascargar; ?>
			}
		
			
			function calculaformulas(){
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
					
					//CALCULANDO FORMULAS
					calculaformulas();	
					
					//REVISANDO LAS DEPENDENCIAS COMPUESTAS
					dependenciascompuestas(idcampo);
				}
				
				modifico=false;
			}
						
		</script>		
		
	</head>
	<body onload="loadbody()">
            
		<div id="divrepetido"></div>
                               
		<!--FORMULARIO-->
		<form id="frm" enctype="multipart/form-data" action="fg.php" method="post" onSubmit="return valida();">
			<?php 
				//CSRF - FORM
				echo $csrf->input_token($token_id,$token_value);	 
			?>					
           	<?php echo $poner_input_m; ?>
			
				<!-- CAMPOS -->
				<?php echo $captura; ?>
			
				<!--LINK PROCESO ANTES-->
				<?php
					if(!empty($linkprocesoantes)){
						include $linkprocesoantes;
					}					
				?>
				
				<div class="row">
					<div class="col-md-4 nmfieldcell" style="padding-left:45px !important;">
						<input id="send" type="submit" value="Guardar" class="btn btn-primary">
					</div>
				</div>
				<br><br>
				
				<div id="divdepurar"></div>
							
		</form>		
		
	</body>
</html>
