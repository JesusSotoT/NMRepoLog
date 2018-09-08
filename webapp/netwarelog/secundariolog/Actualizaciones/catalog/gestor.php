<?php
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("conexionbd.php");
	include("clases/clseguridad.php");		
	
	$usuario = new usuario();
	$usuario->setticket($_GET['ticket']);
	
	session_start();
	
	$_SESSION['letadd'] = $usuario->getagregar();
	$_SESSION['letmod'] = $usuario->getmodificar();
	$_SESSION['letdel'] = $usuario->geteliminar();
	//Añadir el permiso para leer cuando no tiene modificar y eliminar.
	
	
	$_SESSION['idestructura']=$_GET['idestructura'];
	
	//Obtiene la descripción de la estructura
	$sql = " 
			select nombreestructura, descripcion 
			from catalog_estructuras 
			where idestructura=".$_SESSION['idestructura']."
		   ";
	$result = $conexion->consultar($sql);
	while($reg=$conexion->siguiente($result)){
		$_SESSION['descripcion']=$reg{'descripcion'};
		$_SESSION['nombreestructura']=$reg{'nombreestructura'};
	}
	$conexion->cerrar_consulta($result);
	
	//Checando si tiene tabla física
	if(!$conexion->existetabla($_SESSION['nombreestructura'])){
		?>
		  La tabla de la estructura: <?php echo $_SESSION['nombreestructura'] ?> no existe.
		  <script type="text/javascript">
			alert("La tabla de la estructura: <?php echo $_SESSION['nombreestructura'] ?> no existe.");
		  </script>
		<?php
	} else {
		header("Location: g.php");		
	}
	
?>
