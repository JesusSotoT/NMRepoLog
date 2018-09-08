<?php include("funcionesPv.php");?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Punto de venta</title>
	<meta charset="utf-8" />
	<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">  
	<link rel="stylesheet" href="punto_venta.css" />
	<script type="text/javascript" src="punto_venta.js" ></script>
</head>
<body>

	
	<?php echo detalleventa($_POST['id']); ?>
	
</body>
</html>	