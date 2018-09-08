<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/caja/simulaFactura.js" ></script>

	<script>
		$(document).ready(function() {
			pendiente.init();
		});
	</script>

	<title>Genera Pendiente por Facturar</title>
</head>
<body>
	<div class="well col-xs-12">
		<div class="col-xs-4"></div>
		<div class="col-xs-4">
			<input type="text" id="ventas" placeholder="1-100">
			<button type="button" id="btnGenerar" class="btn btn-default">Generar</button>
		</div>
		<div class="col-xs-4"></div>
	</div>
</body>
</html>