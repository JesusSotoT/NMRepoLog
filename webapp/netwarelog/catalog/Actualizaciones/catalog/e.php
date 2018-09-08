<?php

	include("conexionbd.php");
	
	session_start();
	
	$sqlw = $_GET['sw'];
	$sqlw = str_replace("\\","",$sqlw);
	
	$sql = " delete from ".$_SESSION['nombreestructura']." where ".$sqlw;
	//echo $sql;	
	
	$conexion->consultar($sql);
	$conexion->cerrar();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" 
"http://www.w3.org/TR/html4/strict.dtd">
<html lang="sp">
	<head>						
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title><?php echo $descripcion ?></title>
		<meta name="generator" content="TextMate 	http://macromates.com/">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-04-28 -->				
		
		<!--RECURSOS EXTERNOS CSS-->		
		<LINK href="css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<script type="text/javascript">
			//alert("El registro ha sido eliminado correctamente.");
			window.location="b.php?m=0";
		</script>
		
		&nbsp; &nbsp; &nbsp; <b><font color=gray>El registro ha sido eliminado correctamente.</font></b>	
	</body>
</html>