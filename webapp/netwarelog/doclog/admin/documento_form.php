<?php
	$iddocumento=$_GET['iddocumento'];
	$nombredocumento="";
	$observaciones="";
	$titulo="Nuevo documento";
    $linkantes="";
	$linkdespues="";
	$idestructuratitulo="";
	$columnas=0;
    $utilizaidorganizacion=0;
	
	
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../../catalog/conexionbd.php");


	//CSRF
	$reset_vars = true;
	include "../../catalog/clases/clcsrf.php";
	

	if($iddocumento!=-1){
				
		$sql = "
			select nombredocumento, observaciones, 
				   linkantes, linkdespues, idestructuratitulo, 
				   columnas, utilizaidorganizacion
			from doclog_titulos
			where iddocumento=".$iddocumento;
                
		$result = $conexion->consultar($sql);
		if($reg = $conexion->siguiente($result)){
			$nombredocumento = $reg{'nombredocumento'};
			$observaciones = $reg{'observaciones'};
            //$utilizaidorganizacion=$reg{'utilizaidorganizacion'};
            $linkantes=$reg{'linkantes'};
			$linkdespues=$reg{'linkdespues'};
			$idestructuratitulo=$reg{'idestructuratitulo'};
			//$columnas = $reg{'columnas'};
		}
		$conexion->cerrar_consulta($result);
		
		$titulo = "Editar documento";
			
	}
	
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
	</head>

	<body>		
		<div class="titulo"><?php echo $titulo?></div>
		<br>
		<a title="Guardar datos" class="nuevo" href="javascript:guardar();"><img class="btn" src="../img/guardar.png"></a>
		<a title="Regresar ..." class="regresar" href="javascript:regresar();"><img class="btn" alt="nuevo" src="../img/regresar.png"></a>		
		
		
		<form id="frm" action="documento_guardar.php" method="post">
	
			<?php 
				//CSRF - FORM
				echo $csrf->input_token($token_id,$token_value);	 
			?>
	
			<table class="formulario">
				<tbody>
					
					<tr class="listadofila">
						<td>Nombre:</td>
						<td><input name="txtnombre" id="txtnombre" 							
							type="text" maxlength="100" size="70" value="<?php echo $nombredocumento; ?>"></td>
					</tr>
					
					
					<tr class="listadofila">
						<td>Observaciones:</td>
						<td><input name="txtobs" id="txtobs" type="text" 
								maxlength="80" size="70" value="<?php echo $observaciones; ?>"></td>
					</tr>
					
					<!--IDORGANIZACION				
					<tr class="listadofila">
						<td>Utiliza el campo Id. Organización:</td>
                                                <?php
                                                    if($utilizaidorganizacion){
                                                        $sel = "checked";
                                                    }else{
                                                        $sel = "";
                                                    }
                                                ?>
						<td><input name="chkorg" id="chkorg" type="checkbox"  value="1" <?php echo $sel; ?>  ></td>
					</tr>-->
					
					
					<!--COLUMNAS
					<tr class="listadofila">
						<td>Columnas del formulario:</td>
						<td><input name="txtcolumnas" id="txtcolumnas" 
									type="text" maxlength="200" size="70"
                                    title="El link se llamara a través de una instrucción include() si es solo el nombre de un archivo php este archivo se buscara desde la carpeta de catalog por lo que en caso de que el archivo se encuentre en otra carpeta añadir la ruta relativa con: '../'  "
                                    value="<?php echo $columnas; ?>"></td>
					</tr>
					-->
					
					
					<!--LINK PROCESO ANTES
					<tr class="listadofila">
						<td>Link antes del proceso Guardar:</td>
						<td>--><input name="txtlinkantes" id="txtlinkantes" 
									type="hidden" maxlength="200" size="70"
                                    title="El link se llamara a través de una instrucción include() antes del proceso de guardar, si es solo el nombre de un archivo php este archivo se buscara desde la carpeta de catalog por lo que en caso de que el archivo se encuentre en otra carpeta añadir la ruta relativa con: '../'  "
                                    value="<?php echo $linkantes; ?>"><!--</td>
					</tr>-->


					<!--LINK PROCESO DESPUES
					<tr class="listadofila">
						<td>Link después del proceso Guardar:</td>
						<td>--><input name="txtlinkdespues" id="txtlinkdespues" 
									type="hidden" maxlength="200" size="70"
                                    title="El link se llamara a través de una instrucción include() antes del proceso de guardar, si es solo el nombre de un archivo php este archivo se buscara desde la carpeta de catalog por lo que en caso de que el archivo se encuentre en otra carpeta añadir la ruta relativa con: '../'  "
                                    value="<?php echo $linkdespues; ?>"><!--</td>
					</tr>-->					
					
					<!--Estructura Título-->
					<tr class="listadofila">
						<td>Estructura <b>título</b>:</td>
						<td>
							
							<?php

									//Tablas o palabras para OCULTAR:
									$tblsocultar = array(
										'accelog',
										'administracion_usuarios',
										'organizaciones',
										'empleados'
									);


								$sql = "
									select idestructura, nombreestructura
									from catalog_estructuras
									order by nombreestructura										
									";
									//echo $sql;
								$result = $conexion->consultar($sql);
								$listadoestructuras = "";
								while($rs = $conexion->siguiente($result)){
								
									//Brincando tablas ocultas ...
										$brincartabla = false;	
										foreach($tblsocultar as $tbl){
											//$debug="\n".$rs{'nombreestructura'}." contiene ".$tbl." >>> ".strpos($reg{'nombreestructura'},$tbl);
											//$accelog_access->nmerror_log($debug);
											if(strpos($rs{'nombreestructura'},$tbl)===0){
												$brincartabla = true;
											}
										}		
										if($brincartabla) continue; 
									///////////////		
	
									$seleccionado = "";
									if($idestructuratitulo==$rs{"idestructura"}){
										$seleccionado = "selected";	
									}	
									
									$listadoestructuras.="
										<option value='".$rs{"idestructura"}."' ".$seleccionado." >
										".$rs{"nombreestructura"}."
										</option>																																						
										";
								}
								$conexion->cerrar_consulta($result);
							?>							
							
							
							<select name="cmbestructuratitulo" id="cmbestructuratitulo">
								<?php echo $listadoestructuras; ?>
							</select>														
						</td>
					</tr>					
					
					<!-- TABLA DETALLE -->
					<tr class="listadofila">
						<td>Estructura(s) <b>detalle(s)</b>:</td>
						<td>
							<table cellpadding='0' cellspacing='0' border='0'>
								<tbody>
									<tr valign='middle'>
										<td  class='detalles' title='Seleccione una estructura para detalle'>
											<select name="cmbestructuradetalle" id="cmbestructuradetalle">
												<?php echo $listadoestructuras; ?>
											</select>
										</td>
										<td  class='detalles' title='Agregar estructura para detalle'>
											<a href='javascript:add();'><img border='0' src='../img/mas.png'></a>
										</td>
										<td  class='detalles' title='Quitar estructura de detalle'>
											<a href='javascript:remove();'><img border='0' src='../img/menos.png'></a>
										</td>
									</tr>
								</tbody>
							</table>
							<select name="lstdetalles[]" id="lstdetalles" multiple="multiple"
									title='Estructuras para detalle seleccionadas'
									size=3 width='10'
									style='width:375px'
									><?php
									
									$sql = "
										select idestructuradetalle, nombreestructura
										from 
											doclog_detalles d inner join catalog_estructuras c 
											on d.idestructuradetalle = c.idestructura 
										where iddocumento=".$iddocumento."
									";
									$resultdocs = $conexion->consultar($sql);
									while($rs = $conexion->siguiente($resultdocs)){
										echo "<option value='".$rs{"idestructuradetalle"}."'>".$rs{"nombreestructura"}."</option>";
									}
									$conexion->cerrar_consulta($resultdocs);
									
									
									?></select>
							
						</td>
					</tr>					
					
				</tbody>
				<input name="txtiddocumento" type="hidden" value="<?php echo $iddocumento; ?>">
			
				<script>
				
					function add(){					
						
						var cmbestructuratitulo = document.getElementById("cmbestructuratitulo");
                        var cmbestructuradetalle = document.getElementById("cmbestructuradetalle");

						if(cmbestructuratitulo.value==cmbestructuradetalle.value){
							alert("La estructura seleccionada esta marcada como título.");
							return;
						}


                        var sestructuradetalle = cmbestructuradetalle.options[cmbestructuradetalle.selectedIndex].text;
						var iestructuradetalle = cmbestructuradetalle.value;

                        var slinea = sestructuradetalle;
                        var opcion = new Option(slinea, iestructuradetalle);
                        var lista = document.getElementById("lstdetalles");

                       //Evitar duplicados...
                       for(var i=0; i<=lista.options.length-1; i++){
                           if(slinea==lista.options[i].text){
                               return;
                           }
                       }

                        lista.options.add(opcion);						
						
					}
					
					function remove(){
                        var lista = document.getElementById("lstdetalles");
                        if(lista.selectedIndex==-1){
                            alert("Seleccione una estructura para quitar del documento.")
                        } else {
                           lista.options.remove(lista.selectedIndex);                           
                        }
					}
					
					
				</script>
				
				
				
			</table>

<br>&nbsp;NOTA: Se tomará de referencia los link a proceso Antes y Después definidos en el título de CataLog.

			<script>
				function guardar(){
					var txtnombre = document.getElementById("txtnombre");					
					if(txtnombre.value=='') {
						alert('Capture el nombre del documento.');						
					} else {

						
						var lstdetalles = document.getElementById("lstdetalles");
						for (var i=0;i<lstdetalles.options.length;i++) {
							lstdetalles.options[i].selected = true;
						}
						
						var frm = document.getElementById("frm");						
						frm.submit();
					}
				}			
				function regresar(){
					document.location = "index.php";
				}
			</script>
		</form>
	</body>
</html>
<?php
	$conexion->cerrar();
?>
