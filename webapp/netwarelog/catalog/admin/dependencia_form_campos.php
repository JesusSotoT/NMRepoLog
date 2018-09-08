<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../conexionbd.php");		
	
	$nombreestructura = $conexion->escapalog($_GET['nombreestructura']);
	$nombreobjeto = $conexion->escapalog($_GET['nombreobjeto']);
	$tipo = $conexion->escapalog($_GET['tipo']);

	//error_log($tipo);
	if($tipo=="D"){
		$comboopcion="multiple size=\"5\" width=\"300\"";
	} else {
		$comboopcion="size=\"1\"";
	}
	
	$datosel="";
	if(!empty($_GET['datosel'])){
		$datosel = urldecode($_GET['datosel']);		
		//error_log("datosel: ".$datosel);
	}

	$sql = "
		select nombrecampo 
		from catalog_campos c inner join catalog_estructuras e 
		     on c.idestructura = e.idestructura
		where nombreestructura='".$nombreestructura."'
		";
	$result=$conexion->consultar($sql);
	//echo $sql;
	?>
		<table style="border:none;padding:0px" cellpadding="0" cellspacing="0">
		<tr>
		<td style="border:none;">
		<select <?php echo $comboopcion; ?> name="<?php 
						echo $nombreobjeto;
						if($tipo=="D") echo "[]";
						?>" id="<?php echo $nombreobjeto ?>">
			<?php
				//error_log("\n datosel:".$datosel);
				while($reg=$conexion->siguiente($result)){ ?>
					<option  
						<?php
							if($tipo=="D"){
								if(strrpos($datosel,$reg{'nombrecampo'})===false){
										echo "";
								} else {
										echo "selected";
								}
							} else { 
								if($reg{'nombrecampo'}==$datosel) { echo "selected"; }  
							}

						?>   
						value="<?php echo $reg{'nombrecampo'}?>"><?php echo $reg{'nombrecampo'} ?></option>
				<?php 
				}			
				$conexion->cerrar_consulta($result);
			?>
		</select>
		</td>
		<?php 
			if($tipo=="D"){
				?>
				<td style="border:none;">
				Presione la tecla Control en Windows<br>
				ó la tecla Command en Mac para <br>
				seleccionar más de un campo.
				</td>
				<?php	
			}
		?>
		</tr>
		</table>
	<?php
	
	//CERRAR LA BASE
	$conexion->cerrar();
?>

