<?php

	include("conexionbd.php");
	
	session_start();
	
	$sqlw = $_GET['sw'];
	$sqlw = str_replace("\\","",$sqlw);
	
	
	//Revisando si la captura tiene archivos...
	$sqlcampofile = "select idcampo,nombrecampo from catalog_campos where tipo='archivo' and ";
	$sqlcampofile.= "idestructura = ".$_SESSION['secundariolog_idestructura'];
	$resultcampofile = $conexion->consultar($sqlcampofile);
	while($rscc=$conexion->siguiente($resultcampofile)){
		$sql_nombrearchivo = " select ".$rscc{"nombrecampo"}." from ".$_SESSION['secundariolog_nombreestructura']." where ".$sqlw;
		$resultcampofile_datos = $conexion->consultar($sql_nombrearchivo);
		while($rscc_datos=$conexion->siguiente($resultcampofile_datos)){
			$directorioarchivo="../archivos/".$_SESSION["accelog_idorganizacion"]."/".$_SESSION["secundariolog_nombreestructura"];
			$directorioarchivo.="/".$rscc_datos{$rscc{"nombrecampo"}};
			if(file_exists($directorioarchivo)){
				unlink($directorioarchivo);				
				//echo "NOMBRE CAMPO: ".$rscc{"nombrecampo"};
				echo "<br>El archivo [ <b>".$rscc_datos{$rscc{"nombrecampo"}}."</b> ] ha sido eliminado del servidor.<br><br><hr>";
			} else {
				echo "<br>El archivo [ <b>".$rscc_datos{$rscc{"nombrecampo"}}."</b> ] no fue encontrado en el servidor.<br><br><hr>";
			}			
		}		
	}
	
	
	
	
	$sql = " delete from ".$_SESSION['secundariolog_nombreestructura']." where ".$sqlw;
	//echo $sql;		
	$conexion->consultar($sql);

        //REGISTRO TRANSACCIONES -- 2010-10-01
        $conexion->transaccion("CATALOG - ELIMINACION - ".$_SESSION['secundariolog_nombreestructura'],$sql);


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
			//window.location="b.php?m=0&primeravez=1";
		</script>
		
		&nbsp; &nbsp; &nbsp; <b><font color=gray>El registro ha sido eliminado correctamente.</font></b>	
	</body>
</html>