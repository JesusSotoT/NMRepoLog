<?php include("funcionesPv.php"); ?>
<!DOCTYPE HTML>
<html lang="es">
<head>
    <title>Ingreso mercancia</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
  <LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />

	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="punto_venta.css" />
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<script type="text/javascript" src="punto_venta.js" ></script>
	<script type="text/javascript" src="../punto_venta/js/jquery.alphanumeric.js" ></script>
	<script>
		
		$(function(){  $(".num").numeric(); $(".float").numeric({allow:"."}); });
		$(".float").numeric();
		$('.float').bind("cut copy paste",function(e) {
			e.preventDefault();
		}); 
		function validaneg(){
			if($("#cantidad").val()<0){
				alert("No puedes tener numero negativos.");
				$("#cantidad").val(0).focus();
			}
		}
	</script>
	<style type="text/css">
		.btnMenu{
			border-radius: 0; 
			width: 100%;
			margin-bottom: 0.3em;
			margin-top: 0.3em;
		}
		.row
		{
			margin-top: 0.5em !important;
		}
		h4, h3{
			background-color: #eee;
			padding: 0.4em;
		}
		.nmwatitles, [id="title"] {
			padding: 8px 0 3px !important;
			background-color: unset !important;
		}
		.rfinal{
			margin-bottom: 5em !important;
		}
	</style>
</head>
<body>

	<div class="container rfinal">
		<div class="row">
			<div class="col-md-1">
			</div>
			<div class="col-md-10">
				<h3 class="nmwatitles text-center">Recepciones de mercancia</h3>
				<h4>Seleccione el producto  para poder ver sus existencias en almacen y posteriormente ingrese la cantidad que desea ingresar a inventario.	</h4>
				<h4>Filtros producto</h4>
				<div class="row">
					<div class="col-md-4">
						<label>Departamento:</label>
						<?php echo departamentos2();?>
					</div>
					<div class="col-md-4">
						<label>Familia:</label>
						<?php echo familias2();?>
					</div>
					<div class="col-md-4">
						<label>Linea:</label>
						<?php echo lineas2();?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>*Producto:</label>
						<span id="span-productos">
							<?php echo productosexistencias();?>
						</span>
						<img src="img/preloader.gif" id="preloader">
					</div>
				</div>
				<h4>Existencias:</h4>
				<div class="row">
					<div class="col-md-12">
						<div class="table-responsive" id="detalle-producto">
							<?php echo existenciasSucursal(0);?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>*Sucursal:</label>
						<?php echo sucursales();?>
					</div>
					<div class="col-md-6">
						<label>*Cantidad:</label>
						<input type="text" id="cantidad" class="float form-control" onblur="validaneg();"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Proveedor:</label>
						<span id="span-proveedor">
							<?php echo proveedores(0);?>
						</span>
					</div>
					<div class="col-md-6">
						<label>Costo:</label>
						<input type="text" id="costo" class="float form-control"/>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3 col-md-offset-9">
						<button type="button" onclick="Inproduct();" class="btn btn-primary btnMenu">Ingresar producto</button>
						<img src="img/preloader.gif" id="preloader2">
					</div>
				</div>
			</div>
			<div class="col-md-1">
			</div>
		</div>
	</div>	
	
</body>
</html>	

<script>
	$("#preloader").hide();
	
	$("#preloader2").hide();  
	
</script>	