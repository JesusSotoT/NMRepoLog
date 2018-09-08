<?php
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../conexionbd.php");	
	session_start();
	$idestructura=$_SESSION['idestructura'];
	$nombreestructura=$_SESSION['nombreestructura'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<LINK href="estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>catalog</title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
	</head>

	<body>
		<div class="titulo">Estructura: <?php echo $nombreestructura?></div>
		<br>
		<div class="menusuperior">		
		<a title="Nuevo campo" class="nuevo" href="campo_form.php?idcampo=-1"><img class="btn"  src="../img/nuevo.png"></a>
		<a title="Regresar al listado de estructuras" class="regresar" href="index.php"><img class="btn"  src="../img/regresar.png"></a>
		</div>		
		<table class="listado" border="1">			
		  <tbody>
			<tr class="titulo">
				<td>Acciones</td>
				<td>Campo</td>
				<td>Campo usuario</td>
				<td>Tipo</td>
				<td>Longitud</td>
				<td>Requerido</td>				
				<td>Orden</td>
				<td>Llave</td>
			</tr>
			
			<?php
				$sql = "select * from catalog_campos where idestructura=".$idestructura." order by orden ";
				$result = $conexion->consultar($sql);
				$f=0;
				while($reg=$conexion->siguiente($result)){
					$f+=1;
					?>
						<tr class="listadofila">
							<td>
								
								<!--EDITAR-->
								<a class="editar" title="Editar campo"
									href="campo_form.php?idcampo=<?php echo $reg{'idcampo'}?>">
									<img class="btn" src="../img/editar.png">
								</a>							
								
								<a class="dependencias" title="Editar dependencias"
									href="dependencia_form.php?idcampo=<?php echo $reg{'idcampo'}; 
										?>&nombrecampo=<?php echo $reg{'nombrecampo'}; 
										?>&nombrecampousuario=<?php echo $reg{'nombrecampousuario'}; ?>">
									<img class="btn" src="../img/dependencias.png">
								</a>
								
								<!--ELIMINAR-->
								<a class="eliminar" title="Eliminar campo"
									href="javascript:eliminar('<?php echo $reg{'idcampo'}?>','<?php echo $reg{'nombrecampo'}?>')">
									<img class="btn" src="../img/deshabilitar.png">
								</a>								
							</td>
							
							<td><?php
								if($reg{'llaveprimaria'}==-1){
									echo "<div class='llaveprincipal' title='Llave'><img src='../img/llave.gif'>";	
								} else {
									echo "<div>";
								}
								echo $reg{'nombrecampo'};
								echo "</div>";
							?></td>
							
							<td><?php echo $reg{'nombrecampousuario'}?></td>
							<td><?php echo $reg{'tipo'}?></td>
							<td><?php echo $reg{'longitud'}?></td>
							<td>
								<?php
									if($reg{'requerido'}){
										echo "Sí";
									} else {
										echo "No";
									}
								?>
							</td>
							<td><?php echo $reg{'orden'}?></td>
 							<td align='center' >
								<input 
									type="checkbox" 
									id="chk<?php echo $reg{'idcampo'} ?>"
									title="Marcar <?php echo $reg{'nombrecampo'} ?> como llave primaria" 
									<?php
										if($reg{'llaveprimaria'}==-1){
											echo "checked='checked'";
										}
									?> 
									onclick="llave(<?php echo $reg{'idcampo'} ?>,'chk<?php echo $reg{'idcampo'} ?>');"
									/>
							</td>
						
						</tr>
					<?php
				}
				$conexion->cerrar_consulta($result);
			?>				
		  </tbody>	
		</table>
		<script type="text/javascript">
			function llave(id,chkcampo){				
				var vchk = document.getElementById(chkcampo);				
				if(vchk.checked){
					window.location = "campo_llave.php?idcampo="+id+"&llave=1"
				} else {
					window.location = "campo_llave.php?idcampo="+id+"&llave=0"
				}
			}
			function eliminar(id,nombre){
				var respuesta=confirm("¿Desea eliminar el campo:"+nombre+"?");
				if(respuesta){
					window.location = "campo_eliminar.php?idcampo="+id;
				}
			}
		</script>
		<br><br>
		
		
	</body>
	
</html>
<?php
	//CERRAR LA BASE
	$conexion->cerrar();
?>