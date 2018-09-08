<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("conexionbd.php");	
	
	//CONTROL DE OBJETOS
	include("clases/clcontroles.php");
	$controles = new controles();
	
	//OBJETO ESPECIAL PARA FECHAS
	include("clases/clfechas.php");
	$fechas = new fechas();
	
	session_start();
	$idestructura=$_SESSION['idestructura'];
	$descripcion=$_SESSION['descripcion'];
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
			
			$sql_c = "select nombrecampo from catalog_campos where idestructura=".$idestructura." order by orden ";
			$result_c=$conexion->consultar($sql_c);
			while($registro_c = $conexion->siguiente($result_c)){
				
				$reg_m[$registro_c{'nombrecampo'}]=$registro_m{$registro_c{'nombrecampo'}};
				
			}
			$conexion->cerrar_consulta($result_c);			
			
		}		
		$conexion->cerrar_consulta($result_m);
	}
	/////
	
	//En este script se manda llamar la funcion de dependencias compuestas
	//con todos los id de las dependencias simples para evitar el iniciando.
	$script_dependenciascargar;
	
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
	$captura = "";
	
	//SIMBOLO PESOS ES PARA OBJETOS NUMERICOS
	$simbolo_pesos="";
			
	$sql = "select * from catalog_campos where idestructura=".$idestructura." order by orden";
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
			$captura.="
					<tr class='listadofila' title='".$reg{'descripcion'}."' valign='middle' >
						<td class='campo'>".$reg{'nombrecampousuario'}.": <font color=silver>".$requerido."</font>
						<td align='right'>".$simbolo_pesos."</td>
						<td>".$objeto."</td>
						<!--<td class='ayuda'>".$reg{'descripcion'}."</td>-->					
					</tr>		
					";		
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
	
	
	$_SESSION['controles']=$controles;
	
	
	$poner_input_m="";
	
	//SCRIPT PARA REPEDITOS
	if($a==1){
		$script_repetidos="
				var sw = '".$script_repetidos."';
				var qs = 'f_repetidos.php?nc=".$campox."&ne=".$_SESSION['nombreestructura']."&sw='+sw;
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
		
			//INICIALIZANDO JQUERY
			$(document).ready(function(){
				<?php echo $script_inputmask; ?>		   						
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
				
				/*
				document.getElementById('divdepurar').innerHTML = 
						document.getElementById('divdepurar').innerHTML + 
						' --entro para:'+idcampo+' ';
				*/
				
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
		<form id="frm" action="fg.php" method="post" onSubmit="return valida();">
			
           	<?php echo $poner_input_m; ?>
			
			<table border="0" class="campos" cellpadding="3" cellspacing="0">
				
				<!-- TIPO DE REGISTRO -->
				<tr><td>
					<div class="tipo"><b><?php if($a==1) echo "Registro nuevo"; else echo "Editar registro"; ?></b></div>			
				</td></tr>
				
				<!-- CAMPOS -->
				<?php echo $captura; ?>
				
				<!--BOTONES-->
				<tr>
					<td></td><td></td>
					<td>
						<input type="submit" value="Guardar">
						<div id="divdepurar"></div>
					</td>				
				</tr>				
				
			</table>
			
		</form>		
		
	</body>
</html>