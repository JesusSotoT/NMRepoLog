<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../conexionbd.php");		

	session_start();
	$idestructura=$_SESSION['idestructura'];
	$nombreestructura=$_SESSION['nombreestructura'];
	$idcampo=$_GET['idcampo'];
	$nombrecampo=$_GET['nombrecampo'];
	$nombrecampousuario=$_GET['nombrecampousuario'];
		
	$titulo="Depedencia del campo: ".$nombrecampousuario;

	$tipodependencia="N";
	$dependenciatabla="";
	$dependenciacampovalor="";
	$dependenciacampodescripcion="";
	
	$sql = "
		select *
		from catalog_dependencias
		where idcampo=".$idcampo;		
	$result = $conexion->consultar($sql);
	
	if($reg = $conexion->siguiente($result)){
		$tipodependencia=$reg{'tipodependencia'};
		$dependenciatabla=$reg{'dependenciatabla'};
		$dependenciacampovalor=$reg{'dependenciacampovalor'};
		$dependenciacampodescripcion=$reg{'dependenciacampodescripcion'};
	}
	$conexion->cerrar_consulta($result);
			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		
		<LINK href="estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $titulo?></title>		
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
	
		
	   <script type="text/JavaScript" src="../js/jquery.js"></script> 	
	   <script type="text/javascript">
	     $(document).ready(function(){	     	
				cargacombos();
	     });	 
	     
	     
	     function cargacombos(){	     	
			<?php if($dependenciatabla!="N"){ ?>
				var urlvalor;
				var urldesc;
				var randomnumber=Math.floor(Math.random()*10000);
				
				//alert(randomnumber);
				
				urldesc = "dependencia_form_campos.php"+
						  "?datosel=<?php echo $dependenciacampodescripcion?>"+
						  "&nombreobjeto=cmbdescripcion"+
						  "&nombreestructura=<?php echo $dependenciatabla?>&i="+randomnumber;
				//alert("2 entre "+urldesc);
				$("#divcmbdescripcion").load(urldesc);
				$("#divcmbdescripcion").load(urldesc);
				
								
				urlvalor = "dependencia_form_campos.php"+
						   "?datosel=<?php echo $dependenciacampovalor?>"+
						   "&nombreobjeto=cmbvalor&"+
						   "&nombreestructura=<?php echo $dependenciatabla?>&i="+randomnumber;
				$("#divcmbvalor").load(urlvalor);	
				$("#divcmbvalor").load(urlvalor);	
								
			<?php } ?>	     		     	
	     }    
	   </script>		
		
		
	</head>

	<body>				
		<div class="titulo"><?php echo $titulo?></div>
		<br>
		<a title="Guardar datos" class="nuevo" href="javascript:guardar();"><img class="btn" src="../img/guardar.png"></a>
		<a title="Regresar ..." class="regresar" href="javascript:regresar();"><img class="btn" alt="nuevo" src="../img/regresar.png"></a>		
				
		<!-- INICIO DEL FORMULARIO-->
		<form id="frm" action="dependencia_guardar.php" method="post">
			<table class="formulario">
				<tbody>
					
					<!--NOMBRE DEL CAMPO-->
					<tr class="listadofila">
						<td>Nombre campo:</td>
						<td><b><?php echo $nombrecampo?></b></td>
					</tr>
					
					<!--TIPO DEPENDENCIA-->
					<tr class="listadofila">
						<td>Tipo Dependencia:</td>
						<td>
							<select name="cmbtipodependencia" id="cmbtipodependencia" onchange="cmbtipodependencia_change();">
								<option <?php if($tipodependencia=="N") echo "selected";  ?>  value="N">Ninguna</option>
								<option <?php if($tipodependencia=="S") echo "selected";  ?>  value="S">Simple</option>
								<option <?php if($tipodependencia=="C") echo "selected";  ?>  value="C">Compuesta</option>
							</select>
						</td>
					</tr>				
					
					
					<!--DEPENDENCIA TABLA-->
					<tr id="deptabla" 
						style="<?php
						if($tipodependencia!="N"){
							echo "visibility:visible";
						} else {
							echo "visibility:hidden";
						}
						?>" 
						class="listadofila">
						<td>Dependencia Tabla:</td>
						<td>
							<select name="cmbdependenciatabla" id="cmbdependenciatabla" onchange="cmbdependenciatabla_change();">
								<option value="ninguna">Ninguna</option>
								<?php
									$sql="
										select nombreestructura
										from catalog_estructuras
										order by nombreestructura
										";
									$result = $conexion->consultar($sql);
									while($reg=$conexion->siguiente($result)){ ?>
										<option  
											<?php if($dependenciatabla==$reg{'nombreestructura'}) echo "selected" ?>
											value="<?php echo $reg{'nombreestructura'}?>"><?php echo $reg{'nombreestructura'}?>
										</option>
									<?php }
									$conexion->cerrar_consulta($result);
								
								?>								
							</select>							
						</td>
					</tr>
					
					
					<!--DEPENDENCIA CAMPO VALOR-->
					<tr id="depcampovalor" 
						style="<?php
						if($tipodependencia!="N"){
							echo "visibility:visible";
						} else {
							echo "visibility:hidden";
						}
						?>" 
						class="listadofila">					
						<td>Dependencia Campo:</td>
						<td>
							<div id="divcmbvalor">
								<select id="cmbvalor" name="cmbvalor"></select>
							</div>
						</td>
					</tr>
					
					<!--DEPENDENCIA CAMPO DESCRIPCION-->
					<tr id="depcampodescripcion" 
						style="<?php
						if($tipodependencia!="N"){
							echo "visibility:visible";
						} else {
							echo "visibility:hidden";
						}
						?>" 
						class="listadofila">
						<td>Campo Descripción:</td>
						<td>
							<div id="divcmbdescripcion">
								<select id="cmbdescripcion" name="cmbdescripcion"></select>
							</div>
						</td>
					</tr>					
					
					</div>
										
					<tr><th colspan="2" align="left">
							<!--SECCION DE CAMPOS PARA TIPO DEPENDENCIA COMPUESTA-->
							<div id="divcompuesta" style="<?php
								if($tipodependencia=="C"){
									echo "visibility: visible;height: 100%";
								} else {
									echo "visibility: hidden;height: 5px";
								}
							?>">
								Seleccione los otros campos de la captura en la cual depende:
								<br>
								<?php
									$sql = "
										select nombrecampo 
										from catalog_campos 
										where idestructura=".$idestructura."
										order by orden
									";
									$result = $conexion->consultar($sql);
									while($reg=$conexion->siguiente($result)){ 
										if($reg{'nombrecampo'}!=$nombrecampo){ ?>						
											<input type="checkbox" 
												   <?php
												   		$sql = " 
															select nombrecampo 
															from catalog_dependenciasfiltros 
															where idcampo=".$idcampo." and nombrecampo='".$reg{'nombrecampo'}."'
															";
														if($conexion->existe($sql)){
															echo "checked='checked'";
														}else{
															echo "";
														}				
												   ?>
												   name="chk<?php echo $reg{'nombrecampo'}?>"> 
												   <?php echo $reg{'nombrecampo'}?> <br>
										<?php }						
									}					
									$conexion->cerrar_consulta($result);
								?>
								<br>
							</div>					
					</td></tr>

					
					
					
					
					
				</tbody>
			</table>
			<input name="txtidcampo" type="hidden" value="<?php echo $idcampo?>">
			
					
			
			<!--SCRIPTS-->
			<script type="text/javascript">		
							
				function cmbtipodependencia_change(){
					var vcmb = 	document.getElementById("cmbtipodependencia");
					var vdivcompuesta = document.getElementById("divcompuesta");		
									
					var vdeptabla = document.getElementById("deptabla");
					var vdepcampovalor = document.getElementById("depcampovalor");
					var vdepcampodescripcion = document.getElementById("depcampodescripcion");
					
					if(vcmb.value!="N"){
						vdeptabla.style.visibility = "visible";
						vdepcampovalor.style.visibility = "visible";
						vdepcampodescripcion.style.visibility = "visible";
					} else {
						vdeptabla.style.visibility = "hidden";
						vdepcampovalor.style.visibility = "hidden";
						vdepcampodescripcion.style.visibility = "hidden";						
					}
					
					
					if(vcmb.value=="C"){
						vdivcompuesta.style.height = "100%";
						vdivcompuesta.style.visibility = "visible";
					} else {
						vdivcompuesta.style.height = "5px";						
						vdivcompuesta.style.visibility = "hidden";						
					}
				}		
				
				function cmbdependenciatabla_change(){
					var vcmb = document.getElementById("cmbdependenciatabla");					
					if(vcmb.value=="ninguna"){						
						$("#divcmbvalor").html("<select name='cmbvalor' id='cmbvalor'></select>");
						$("#divcmbdescripcion").html("<select name='cmbdescripcion' id='cmbdescripcion'></select>");						
					} else {						
						$("#divcmbvalor").load("dependencia_form_campos.php?nombreobjeto=cmbvalor&nombreestructura="+vcmb.value);
						$("#divcmbdescripcion").load("dependencia_form_campos.php?nombreobjeto=cmbdescripcion&nombreestructura="+vcmb.value);						
					}					
				}
				
			
				function guardar(){
					var vcmbdescripcion = document.getElementById("cmbdescripcion");
					var vcmbtipodependencia = document.getElementById("cmbtipodependencia");
										
					if((vcmbdescripcion.selectedIndex==-1)&&(vcmbtipodependencia.value!="N")) {
						alert('Seleccione un campo descripción de la tabla.');
					} else {
						var frm = document.getElementById("frm");
						frm.submit();
					}
				}
				
				function regresar(){
					window.open("campo.php","_self");
				}
			</script>
		</form>
	</body>
</html>

<?php
	//CERRAR LA BASE
	$conexion->cerrar();
?>

