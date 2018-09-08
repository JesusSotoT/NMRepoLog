<?php
	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
	include("../catalog/conexionbd.php");
	include("clases/clseguridad.php");		

	if(session_id()=='') session_start();
		
	$_SESSION['idestructura']=$_GET['idestructura'];
	
	//Obtiene la descripción de la estructura
	$sql = " 
			select nombreestructura, descripcion, utilizaidorganizacion,linkproceso, linkprocesoantes, columnas
			from catalog_estructuras 
			where idestructura=".$_SESSION['idestructura']."
		   ";
	$result = $conexion->consultar($sql);
	while($reg=$conexion->siguiente($result)){
		$_SESSION['descripcion']=$reg{'descripcion'};
		$_SESSION['nombreestructura']=$reg{'nombreestructura'};
        $_SESSION['utilizaidorganizacion']=$reg{'utilizaidorganizacion'};
        $_SESSION['linkproceso']=$reg{'linkproceso'};
        $_SESSION['linkprocesoantes']=$reg{'linkprocesoantes'};
		$_SESSION['catalog_columnas']=$reg{'columnas'};
	}
	$conexion->cerrar_consulta($result);




	//SEGURIDAD Y PERMISOS DE USUARIO/////////////////////////////////////////////
	$usuario = new usuario();
	$usuario->setopciones($_SESSION["accelog_opciones"],$_SESSION["idestructura"]);	
	$_SESSION['letadd'] = $usuario->getagregar();
	$_SESSION['letmod'] = $usuario->getmodificar();
	$_SESSION['letdel'] = $usuario->geteliminar();
	//Añadir el permiso para leer cuando no tiene modificar y eliminar.
	///////////////////////////////////////////////////////////////////////////////



	
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
