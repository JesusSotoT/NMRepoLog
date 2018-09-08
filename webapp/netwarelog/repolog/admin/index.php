<?php

	//Cargando los parametros:
    include("parametros.php");

	$conexion->revisa_sesion();
        
	//Si se desea ocultar los deshabilitados.
	if(empty($_COOKIE['repolog_ocultar_deshabilitados'])){
		$ocultar = 0;
	} else {
		$ocultar = $_COOKIE['repolog_ocultar_deshabilitados'];		
	}
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
                <LINK href="<?php echo $link_catalog_local; ?>/admin/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--TITULO VENTANA-->
		<title>repolog</title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-08-07 -->
		<meta name="author-icons" content="Rachel Fu"><!-- Date: 2010-08-07 -->
	</head>

	<body>

		<!--TITULO-->
		<div class="titulo">repolog</div>
		<br>
		
		
		<!--MENU SUPERIOR-->
		<div class="menusuperior">
		<a title="Nuevo reporte" class="nuevo" href="reporte_form.php?id=-1"><img class="btn" src="<?php echo $link_catalog_local; ?>/img/nuevo.png"></a>
		<a title="Regresar al menú principal" class="regresar" target="_parent"  href="..\..\accelog\"><img class="btn" alt="nuevo" src="<?php echo $link_catalog_local; ?>/img/regresar.png"></a>
		</div>
		
		
		<table class="listado" border="1">
		  <tbody>
			<tr class="titulo">
				<td>Acciones</td>
				<td>Reporte</td>
				<td>Descripción</td>
				<td>Creación</td>
				<td>Modificación</td>
				<td>Estatus</td>
			</tr>
			
			<?php
				$sql = "select * from repolog_reportes ";
				if( $ocultar == "1" ) $sql = $sql." where estatus!='D'";
				$sql = $sql." order by nombrereporte ";
				$result = $conexion->consultar($sql);
				while($reg=$conexion->siguiente($result)){
					?>
						<tr class="listadofila" title="El id de este reporte es: <?php echo $reg{'idreporte'}?>">
							<td>
								
								<!--EDITAR-->
								<a class="editar" title="Editar datos del reporte"
									href="reporte_form.php?id=<?php echo $reg{'idreporte'}?>">
									<img class="btn" src="<?php echo $link_catalog_local; ?>/img/editar.png">
								</a>							
								
								
								<!--ACTIVAR-->
								<a class="activar" title="Añade el reporte a accelog como un menú."
									href="javascript:activar('<?php echo $reg{'idreporte'}?>','<?php echo $reg{'nombrereporte'}?>','<?php echo $reg{'descripcion'} ?>');">
									<img class="btn" src="<?php echo $link_catalog_local; ?>/img/activar.png">
								</a>
								
								<!--PRELIMINAR-->
								<a class="preliminar" title="Ver el reporte en funcionamiento"
									href="../repolog.php?i=<?php echo $reg{'idreporte'}?>">
									<img class="btn" src="<?php echo $link_catalog_local; ?>/img/preliminar.png">
								</a>

								<?php if($reg{'estatus'}=="G"||$reg{'estatus'}=="A"){ ?>
								
									<!--DESHABILITAR-->
									<a class="deshabilitar" title="Deshabilitar reporte - se borrará el link dentro del accelog para el reporte."
										href="javascript:deshabilitar('<?php echo $reg{'idreporte'}?>','<?php echo $reg{'nombrereporte'}?>')">
										<img class="btn" src="<?php echo $link_catalog_local; ?>/img/deshabilitar.png">
									</a>						
								
								<?php } else { ?>
									
									<!--HABILITAR-->
									<a class="habilitar" title="Habilitar reporte - se vuelve a almacenar el link de repolog dentro del menú de accelog."
                                                                                href="javascript:activar('<?php echo $reg{'idreporte'}?>','<?php echo $reg{'nombrereporte'}?>','<?php echo $reg{'descripcion'} ?>');">
										<img class="btn" src="<?php echo $link_catalog_local; ?>/img/habilitar.png">
									</a>	
														
								<?php } ?>
											
								
							</td>
							<td><?php echo $reg{'nombrereporte'}?></td>
							<td><?php echo $reg{'descripcion'}?></td>
							<td><?php echo $conexion->fechamx($reg{'fechacreacion'});?></td>
							<td><?php echo $conexion->fechamx($reg{'fechamodificacion'});?></td>
							
							<!--ESTATUS-->
							<td><?php
							if($reg{'estatus'}=="G") echo "<font color='#228822'><b>Generada</b></font>";
							if($reg{'estatus'}=="D") echo "Deshabilitada";
							if($reg{'estatus'}=="A") echo "<font color='#222288'><b>Activa</b></font>";
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
		Ocultar las estructuras deshabilitadas.
		<font color="#cfcfcf">
		| icons autor: Rachel Fu | Powered by: php & jquery.
		</font>
		
		<br>
		
				
		<script type="text/javascript">
			function activar(id,nombre,descripcion){
				var respuesta=confirm("¿Desea activar el reporte: "+nombre+" ?");
				if(respuesta){
					window.location = "reporte_activar.php?idreporte="+id+"&nombrereporte="+nombre+"&descripcion="+descripcion;
				}
			}		
			function deshabilitar(id,nombre){
				var respuesta=confirm("¿Desea deshabilitar el reporte: "+nombre+" ?");
				if(respuesta){
					window.location = "reporte_deshabilitar.php?idreporte="+id;
				}
			}
			function chkocultardes_onchange(){
				var vchkocultardes=document.getElementById("chkocultardes");
				if(vchkocultardes.checked!=""){
					window.location = "reporte_gc.php?ocultar=1";
				} else {
					window.location = "reporte_gc.php?ocultar=0";
				}
			}
		</script>
		
		<br><br>
		<?php 
			if($instalarbase_repolog=="1"){
			echo "<div class='msg'>Instalación efectuada con éxito...
				  <br> Para quitar esta leyenda edite el archivo webconfig_repolog,
				  con el parámetro instalabase = 0.</div>";
		 } ?>
						
	</body>
	
</html>
<?php
	//CERRAR LA BASE
	$conexion->cerrar();
?>
