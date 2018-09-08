<?php

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../conexionbd.php");		
	
	$nombreestructura = $_GET['nombreestructura'];
	$nombreobjeto = $_GET['nombreobjeto'];
	
	$datosel="";
	if(!empty($_GET['datosel'])){
		$datosel = $_GET['datosel'];		
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
		<select name="<?php echo $nombreobjeto ?>" id="<?php echo $nombreobjeto ?>">
			<?php
				while($reg=$conexion->siguiente($result)){ ?>
					<option  
						<?php if($reg{'nombrecampo'}==$datosel) { echo "selected"; }  ?>   
						value="<?php echo $reg{'nombrecampo'}?>"><?php echo $reg{'nombrecampo'} ?></option>
				<?php 
				}			
				$conexion->cerrar_consulta($result);
			?>
		</select>
	<?php
	
	//CERRAR LA BASE
	$conexion->cerrar();
?>

