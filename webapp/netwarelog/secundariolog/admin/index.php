<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../conexionbd.php");
	
	//Si se desea ocultar los deshabilitados.
	if(empty($_COOKIE['catalog_ocultar_deshabilitados'])){
		$ocultar = 0;
	} else {
		$ocultar = $_COOKIE['catalog_ocultar_deshabilitados'];		
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<LINK href="estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--TITULO VENTANA-->
		<title>catalog</title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
		<meta name="author-icons" content="Rachel Fu"><!-- Date: 2010-04-28 -->		
	</head>

	<body>

		<!--TITULO-->
		<div class="titulo">catalog</div>
		<br>
		
		
		<!--MENU SUPERIOR-->
		<div class="menusuperior">
			<table border="0">
				<tr align="middle">
					<td  style="border:none;">
						<a title="Nueva estructura" class="nuevo" href="estructura_form.php?idestructura=-1">
						<img class="btn" src="../img/nuevo.png">
						</a>
					</td>
					<td  style="border:none;">
						<a title="Nueva estructura" class="catalog"						
						   	href="estructura_form.php?idestructura=-1">
						   Nueva Estructura
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
				<td>Estructura</td>
				<td>Descripción</td>
				<td>Creación</td>
				<td>Modificación</td>
				<td>Estatus</td>
			</tr>
			
			<?php
				$sql = "select * from catalog_estructuras ";
				if( $ocultar == "1" ) $sql = $sql." where estatus!='D'";
				$sql = $sql." order by nombreestructura ";				
				$result = $conexion->consultar($sql);
				while($reg=$conexion->siguiente($result)){
					?>
						<tr class="listadofila" title="El id de esta estructura es: <?php echo $reg{'idestructura'}?>">
							<td>
								
								<!--EDITAR-->
								<a class="editar" title="Editar datos de la estructura"
									href="estructura_form.php?idestructura=<?php echo $reg{'idestructura'}?>"
                                    ><img class="btn" src="../img/editar.png"></a>							
								
								<!--DEFINIR CAMPOS-->
								<a class="campos" title="Editar campos"
									href="campo_abrir.php?idestructura=<?php echo $reg{'idestructura'}?>&nombreestructura=<?php echo $reg{'nombreestructura'}?>"
                                    ><img src="../img/campos.png" class="btn"></a>
								
								<!--ACTIVAR-->
								<a class="activar" title="Activar estructura" 
									href="javascript:activar('<?php echo $reg{'idestructura'}?>','<?php echo $reg{'nombreestructura'}?>','<?php echo $reg{'descripcion'} ?>');"
                                    ><img class="btn" src="../img/activar.png"></a>
								
								<!--PRELIMINAR-->
								<a class="preliminar" title="Ver la captura en forma preliminar" 
									href="../../<?php echo $link_gestor; ?>?idestructura=<?php echo $reg{'idestructura'}?>&ticket=testing"
                                    ><img class="btn" src="../img/preliminar.png"></a>

								<?php if($reg{'estatus'}=="G"||$reg{'estatus'}=="A"){ ?>
								
									<!--DESHABILITAR-->
									<a class="deshabilitar" title="Deshabilitar estructura"
										href="javascript:deshabilitar('<?php echo $reg{'idestructura'}?>','<?php echo $reg{'nombreestructura'}?>')">
										<img class="btn" src="../img/deshabilitar.png">
									</a>						
								
								<?php } else { ?>
									
									<!--HABILITAR-->
									<a class="habilitar" title="Habilitar estructura"
										href="javascript:habilitar('<?php echo $reg{'idestructura'}?>','<?php echo $reg{'nombreestructura'}?>')">
										<img class="btn" src="../img/habilitar.png">
									</a>	
														
								<?php } ?>
											
								
					    </td>
							<td><?php echo $reg{'nombreestructura'}?></td>
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
				var respuesta=confirm("¿Desea activar la estructura: "+nombre+" ?");
				if(respuesta){
					window.location = "estructura_activar.php?idestructura="+id+"&nombreestructura="+nombre+"&descripcion="+descripcion;
				}
			}		
			function deshabilitar(id,nombre){
				var respuesta=confirm("¿Desea deshabilitar la estructura: "+nombre+" ?");
				if(respuesta){
					window.location = "estructura_deshabilitar.php?idestructura="+id;
				}
			}
			function habilitar(id,nombre){
				var respuesta=confirm("¿Desea habilitar la estructura:"+nombre+" a estatus 'Generada' nuevamente?");
				if(respuesta){
					window.location = "estructura_habilitar.php?idestructura="+id;
				}
			}			
			function chkocultardes_onchange(){
				var vchkocultardes=document.getElementById("chkocultardes");
				if(vchkocultardes.checked!=""){
					window.location = "estructura_gc.php?ocultar=1";
				} else {
					window.location = "estructura_gc.php?ocultar=0";					
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