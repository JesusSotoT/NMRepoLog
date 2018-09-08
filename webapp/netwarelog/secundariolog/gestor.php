<?php

	//SECUNDARIOLOG --

	//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();

	include("conexionbd.php");


		if(session_id()=='') {
    	session_start();
		}
		
	$_SESSION['secundariolog_idestructura']=$_GET['idestructura'];
	
	//Obtiene la descripción de la estructura
	$sql = " 
			select nombreestructura, descripcion, utilizaidorganizacion,linkproceso, linkprocesoantes, columnas
			from catalog_estructuras 
			where idestructura=".$_SESSION['secundariolog_idestructura']."
		   ";
//echo $sql;
	$result = $conexion->consultar($sql);	
	while($reg=$conexion->siguiente($result)){
		$_SESSION['secundariolog_descripcion']=$reg{'descripcion'};
		$_SESSION['secundariolog_nombreestructura']=$reg{'nombreestructura'};
        $_SESSION['secundariolog_utilizaidorganizacion']=$reg{'utilizaidorganizacion'};
        $_SESSION['secundariolog_linkproceso']=$reg{'linkproceso'};
        $_SESSION['secundariolog_linkprocesoantes']=$reg{'linkprocesoantes'};
		$_SESSION['secundariolog_catalog_columnas']=$reg{'columnas'};
	}
	$conexion->cerrar_consulta($result);




	//SEGURIDAD Y PERMISOS DE USUARIO/////////////////////////////////////////////
	$usuario = new usuario();
	$usuario->setopciones($_SESSION["accelog_opciones"],$_SESSION["secundariolog_idestructura"]);	
	$_SESSION['secundariolog_letadd'] = $usuario->getagregar();
	$_SESSION['secundariolog_letmod'] = $usuario->getmodificar();
	$_SESSION['secundariolog_letdel'] = $usuario->geteliminar();
	//Añadir el permiso para leer cuando no tiene modificar y eliminar.
	///////////////////////////////////////////////////////////////////////////////



	
	//Checando si tiene tabla física
	if(!$conexion->existetabla($_SESSION['secundariolog_nombreestructura'])){
		?>
		  La tabla de la estructura: <?php echo $_SESSION['secundariolog_nombreestructura'] ?> no existe.
		  <script type="text/javascript">
			alert("La tabla de la estructura: <?php echo $_SESSION['secundariolog_nombreestructura'] ?> no existe.");
		  </script>
		<?php
	} else {
		header("Location: g.php");		
	}
	
?>
