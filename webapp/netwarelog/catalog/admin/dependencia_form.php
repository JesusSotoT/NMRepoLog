<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../conexionbd.php");		

	//session_start();

	//CSRF
	$reset_vars = true;
	include "../clases/clcsrf.php";



	$idestructura=$_SESSION['idestructura'];
	$nombreestructura=$_SESSION['nombreestructura'];
	$idcampo=$conexion->escapalog($_GET['idcampo']);
	$nombrecampo=$conexion->escapalog($_GET['nombrecampo']);
	$nombrecampousuario=$conexion->escapalog($_GET['nombrecampousuario']);
		
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
		$dependenciacampodescripcion=urlencode($reg{'dependenciacampodescripcion'});
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
						  "&tipo=D"+
						  "&nombreestructura=<?php echo $dependenciatabla?>&i="+randomnumber;
				console.debug(urldesc);
				//alert("2 entre "+urldesc);
				$("#divcmbdescripcion").load(urldesc);
				$("#divcmbdescripcion").load(urldesc);
				
								
				urlvalor = "dependencia_form_campos.php"+
						   "?datosel=<?php echo $dependenciacampovalor?>"+
						   "&nombreobjeto=cmbvalor&"+
						   "&tipo=C&"+
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
			<?php 
				//CSRF - FORM
				echo $csrf->input_token($token_id,$token_value);	 
			?>

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
								<select multiple id="cmbdescripcion" name="cmbdescripcion[]"></select>
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
					</th></tr>
	
				
                    
                    <!-- INICIO DEPENDENCIA COMPUESTA CON TITULOS-->                    
					
                        <tr id="trcompuestadoclog1" class="compuestadoclog">
                            <th colspan="2" align="left">   
                                 <br><b>DocLog</b> >> sección para documentos, dependencias de títulos:
                            </th>
                        </tr>


                        <tr id="trcompuestadoclog2"  class="listadofila">				
                            <td>Dependencia Campo:</td>
                            <td>
                                <input type="text" id="txtcampo" style="width:120px;">
                                <input type="button" value="+" id="btnagregar" style="width:25px;cursor:pointer" onclick="btnagregar_click()">
                                <input type="button" value="-" id="btnremover" style="width:25px;cursor:pointer" onclick="btnremover_click()">
                            </td>
                        </tr>
                        
                        <tr id="trcompuestadoclog3"  class="listadofila" valign="top">				
                            <td>Listado dependencias:</td>
                            <td>
                                <select class="doclog" id="lstcampostitulos" name="lstcampostitulos[]" multiple="multiple"  size=5>
                                	<?php
										//obteniendo lo ya seleccionado
										$sql = "SELECT * FROM doclog_dependenciasfiltros_detalles where idcampo = ".$idcampo;
										$result_dependenciasfiltros_detalles = $conexion->consultar($sql);
										while($rs_dfd = $conexion->siguiente($result_dependenciasfiltros_detalles)){
											$nombrecampotitulo = $rs_dfd{'nombrecampotitulo'};
											echo "<option value='".$nombrecampotitulo."'>".$nombrecampotitulo."</option>";
										}
										$conexion->cerrar_consulta($result_dependenciasfiltros_detalles);
										
									
                                    ?>
                                </select>
                            </td>
                        </tr>
                                           
                    <!-- FIN DEPENDENCIA COMPUESTA CON TITULOS-->
                    
                    

				</tbody>
			</table>
			<input name="txtidcampo" type="hidden" value="<?php echo $idcampo?>">
			
					
			
			<!--SCRIPTS-->
			<script type="text/javascript">		
				
				
				function btnagregar_click(){
					var scampo = document.getElementById("txtcampo").value;
					var lstcampostitulos = document.getElementById("lstcampostitulos");
										
                       //Evitar duplicados...
                       for(var i=0; i<=lstcampostitulos.options.length-1; i++){
                           if(scampo==lstcampostitulos.options[i].text){
                               return;
                           }
                       }					
					
					var opcion = new Option(scampo);					
					lstcampostitulos.options.add(opcion);
					
					document.getElementById("txtcampo").value="";
					
				}
				
				function btnremover_click(){
					var lista = document.getElementById("lstcampostitulos");
					if(lista.selectedIndex==-1){
						alert("Seleccione un campo para eliminar.")
					} else {
					   lista.options.remove(lista.selectedIndex);                           
					}    
				}
				
				
				function cmbtipodependencia_change(){
					var vcmb = 	document.getElementById("cmbtipodependencia");
					var vdivcompuesta = document.getElementById("divcompuesta");
					
					var trcompuestadoclog1 = document.getElementById("trcompuestadoclog1");
					var trcompuestadoclog2 = document.getElementById("trcompuestadoclog2");
					var trcompuestadoclog3 = document.getElementById("trcompuestadoclog3");
							
									
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
						
						trcompuestadoclog1.style.height = "100%";
						trcompuestadoclog1.style.visibility = "visible";	
						trcompuestadoclog2.style.height = "100%";
						trcompuestadoclog2.style.visibility = "visible";	
						trcompuestadoclog3.style.height = "100%";
						trcompuestadoclog3.style.visibility = "visible";	
											
					} else {
						vdivcompuesta.style.height = "5px";						
						vdivcompuesta.style.visibility = "hidden";		
						
						trcompuestadoclog1.style.height = "5px";
						trcompuestadoclog1.style.visibility = "hidden";	
						trcompuestadoclog2.style.height = "5px";
						trcompuestadoclog2.style.visibility = "hidden";	
						trcompuestadoclog3.style.height = "5px";
						trcompuestadoclog3.style.visibility = "hidden";	
															
					}
				}		
				
				function cmbdependenciatabla_change(){
					var vcmb = document.getElementById("cmbdependenciatabla");					
					if(vcmb.value=="ninguna"){						
						$("#divcmbvalor").html("<select name='cmbvalor' id='cmbvalor'></select>");
						$("#divcmbdescripcion").html("<select name='cmbdescripcion[]' id='cmbdescripcion'></select>");						
					} else {						
						$("#divcmbvalor").load("dependencia_form_campos.php?tipo=C&nombreobjeto=cmbvalor&nombreestructura="+vcmb.value);
						$("#divcmbdescripcion").load("dependencia_form_campos.php?tipo=D&nombreobjeto=cmbdescripcion&nombreestructura="+vcmb.value);						
					}					
				}
				
			
				function guardar(){
									
					
					var lstcampostitulos = document.getElementById("lstcampostitulos");
					for (var i=0;i<lstcampostitulos.options.length;i++) {
						lstcampostitulos.options[i].selected = true;
					}					
					
					
					var vcmbdescripcion = document.getElementById("cmbdescripcion");
					var vcmbtipodependencia = document.getElementById("cmbtipodependencia");
										
					if((vcmbdescripcion.selectedIndex==-1)&&(vcmbtipodependencia.value!="N")) {
						alert('Seleccione un campo descripción de la tabla.');
					} else {

						/*var selected = $('#sel option:selected');
						if(selected==null){
							alert('Seleccione al menos un campo descripción 
						} else {*/
							var frm = document.getElementById("frm");
							frm.submit();
						/*}*/

					}
				}
				
				function regresar(){
					window.open("campo.php","_self");
				}
				
				
				cmbtipodependencia_change();
				
			</script>
		</form>
	</body>
</html>

<?php
	//CERRAR LA BASE
	$conexion->cerrar();
?>

