<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>catalog</title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->
	</head>
	<body>
		
		<?php
				//RECUERDA AGREGAR LA FUNCION $conexion->cerrar();
				include("../../catalog/conexionbd.php");
				include("../clases/cldocumento.php");

				$documento = new documento();
				$documento->setiddocumento($_GET['iddocumento']);
				$documento->setnombredocumento($_GET['nombredocumento']);	
				$documento->setobservaciones($_GET['descripcion']);	
				$documento->activar($conexion);

		?>
				
	</body>
</html>
