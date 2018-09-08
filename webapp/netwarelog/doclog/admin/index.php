<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../../catalog/conexionbd.php");
	
	$conexion->revisa_sesion();

	//Si se desea ocultar los deshabilitados.
	if(empty($_COOKIE['doclog_ocultar_deshabilitados'])){
		$ocultar = 0;
	} else {
		$ocultar = $_COOKIE['doclog_ocultar_deshabilitados'];		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<LINK href="estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--TITULO VENTANA-->
		<title>doclog</title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
		<meta name="author-icons" content="Rachel Fu"><!-- Date: 2010-04-28 -->		
	</head>

	<body>

		<!--TITULO-->
		<div class="titulo">doclog</div>
		<br>
		
		
		<!--MENU SUPERIOR-->
		<div class="menusuperior">
			<table border="0">
				<tr align="middle">
					<td  style="border:none;">
						<a title="Nuevo documento" class="nuevo" href="documento_form.php?iddocumento=-1">
						<img class="btn" src="../../catalog/img/nuevo.png">
						</a>
					</td>
					<td  style="border:none;">
						<a title="Nuevo documento" class="catalog"						
						   	href="documento_form.php?iddocumento=-1">
						   Nuevo Documento
						</a>
					</td>
					</a>
				</tr>
			</table>
		<!--<a title="Regresar al menú principal" class="regresar" target="_parent"  href="..\..\accelog\"><img class="btn" alt="nuevo" src="../img/regresar.png"></a>-->
		</div>
		
		
		<table class="listado" border="1">
		  <tbody>
			<tr class="titulo">
				<td>Acciones</td>
				<td>Documento</td>
				<td>Nombre</td>
				<td>Creación</td>
				<td>Modificación</td>
				<td>Estatus</td>
			</tr>
			
			<?php

				//Documentos o palabras para OCULTAR:
				$tblsocultar = array(
									'ParcialLog'
									);

				$sql = "select * from doclog_titulos ";
				if( $ocultar == "1" ) $sql = $sql." where estatus!='D'";
				$sql = $sql." order by nombredocumento ";				
				$result = $conexion->consultar($sql);
				while($reg=$conexion->siguiente($result)){


					//Brincando tablas ocultas ...
					$brincartabla = false;	
					foreach($tblsocultar as $tbl){
						$debug="\n".$reg{'nombredocumento'}." contiene ".$tbl." >>> ".strpos($reg{'nombredocumento'},$tbl);
						$accelog_access->nmerror_log($debug);
						if(strpos($reg{'nombredocumento'},$tbl)===0){
							$brincartabla = true;
						}
					}		
					if($brincartabla) continue; 
					///////////////		



					?>
						<tr class="listadofila" title="El id de este documento es: <?php echo $reg{'iddocumento'}?>">
							<td>
								
								<!--EDITAR-->
								<a class="editar" title="Editar datos del documento"
									href="documento_form.php?iddocumento=<?php echo $reg{'iddocumento'}?>"><img 
									border="0" class="btn" src="../../catalog/img/editar.png"></a>							
																
								
								<!--ACTIVAR-->
								<a class="activar" title="Activar documento" 
									href="javascript:activar('<?php echo $reg{'iddocumento'}?>','<?php 
									echo $reg{'nombredocumento'}?>','<?php echo $reg{'observaciones'} ?>');"><img 
									border="0" class="btn" src="../../catalog/img/activar.png"></a>
								
								<!--PRELIMINAR-->
								<a class="preliminar" title="Ver la captura en forma preliminar" 
									href="../../<?php echo $link_gestor_doclog; ?>?iddocumento=<?php 
									echo $reg{'iddocumento'}?>&ticket=testing"><img 
									border="0" class="btn" src="../../catalog/img/preliminar.png"></a>

								<?php if($reg{'estatus'}=="G"||$reg{'estatus'}=="A"){ ?>
								
									<!--DESHABILITAR-->
									<a class="deshabilitar" title="Deshabilitar documento"
										href="javascript:deshabilitar('<?php echo $reg{'iddocumento'}?>','<?php 
										echo $reg{'nombredocumento'}?>')"><img 
										border="0" class="btn" src="../../catalog/img/deshabilitar.png"></a>						
								
								<?php } else { ?>
									
									<!--HABILITAR-->
									<a class="habilitar" title="Habilitar documento"
										href="javascript:habilitar('<?php echo $reg{'iddocumento'}?>','<?php 
										echo $reg{'nombredocumento'}?>')"><img border="0" class="btn" 
										src="../../catalog/img/habilitar.png"></a>	
														
								<?php } ?>
											
								
							</td>
							<td><?php echo $reg{'nombredocumento'}?></td>
							<td><?php echo $reg{'observaciones'}?></td>
							<td><?php echo $conexion->fechamx($reg{'fechacreacion'});?></td>
							<td><?php echo $conexion->fechamx($reg{'fechamodificacion'});?></td>
							
							<!--ESTATUS-->
							<td><?php
							if($reg{'estatus'}=="G") echo "<font color='#228822'><b>Generado</b></font>";
							if($reg{'estatus'}=="D") echo "Deshabilitado";
							if($reg{'estatus'}=="A") echo "<font color='#222288'><b>Activo</b></font>";
							?></td>							
							
						</tr>
					<?php
				}
				$conexion->cerrar_consulta($result);
			?>				
		  </tbody>	
		</table>		
		<br>
		<input 
		type="checkbox" 
		name="chkocultardes" 
		id="chkocultardes"
		<?php if($ocultar=="1") echo "checked='checked'"; ?>
		onchange="chkocultardes_onchange()"> 
		Ocultar los documentos deshabilitados.
		<font color="#cfcfcf">
		| icons autor: Rachel Fu | Powered by: php & jquery.
		</font>
		
		<br>
		
				
		<script type="text/javascript">
			function activar(id,nombre,descripcion){
				var respuesta=confirm("¿Desea activar el documento: "+nombre+" ?");
				if(respuesta){
					window.location = "documento_activar.php?iddocumento="+id+"&nombredocumento="+nombre+"&descripcion="+descripcion;
				}
			}		
			function deshabilitar(id,nombre){
				var respuesta=confirm("¿Desea deshabilitar el documento: "+nombre+" ?");
				if(respuesta){
					window.location = "documento_deshabilitar.php?iddocumento="+id;
				}
			}
			function habilitar(id,nombre){
				var respuesta=confirm("¿Desea habilitar el documento:"+nombre+" a estatus 'Generado' nuevamente?");
				if(respuesta){
					window.location = "documento_habilitar.php?iddocumento="+id;
				}
			}			
			function chkocultardes_onchange(){
				var vchkocultardes=document.getElementById("chkocultardes");
				if(vchkocultardes.checked!=""){
					window.location = "documento_gc.php?ocultar=1";
				} else {
					window.location = "documento_gc.php?ocultar=0";					
				}
			}
		</script>
		
		<br><br>
		<?php 
			if($instalarbase=="1"){
			echo "<div class='msg'>Instalación efectuada con éxito...
				  <br> Para quitar esta leyenda edite el archivo webconfig,
				  con el parámetro instalabase = 0.</div>";
		 } ?>
						
	</body>
	
</html>
<?php
	//CERRAR LA BASE
	$conexion->cerrar();
?>
