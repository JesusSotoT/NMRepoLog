<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Pedidos Activos</title>

<!-- **	/////////////////////////- -				 CSS 				--///////////////////// **-->

	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- jqueryui -->
		<link rel="stylesheet" href="../../libraries/jquery-ui-1.11.4/jquery-ui.min.css">
	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- tooltipster -->
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/tooltipster.css">
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-light.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-noir.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-punk.css" />
		<link rel="stylesheet" href="../../libraries/tooltipster-master/css/themes/tooltipster-shadow.css" />
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	    
	<!-- ** Sistema -->
		<link rel="stylesheet" type="text/css" href="css/reset.css">
		<link rel="stylesheet" type="text/css" href="css/pedidos/pedidos.css">
		
<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->
	
<!-- **	//////////////////////////- -				 JS 					--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
		<script type="text/javascript" src="js/jquery.scrollTo.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- tooltipster  -->
		<script src="../../libraries/tooltipster-master/js/jquery.tooltipster.min.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
		
	<!-- ** Sistema -->
		<script type="text/javascript" src="js/pedidos/pedidos.js"></script>

<!-- **	//////////////////////////- -				FIN JS 				--///////////////////// **-->

		<script type="text/javascript">
			$(document).ready(function() {
				$('#tdmenu', window.parent.document).hide('slow', function() {
					$("#tdocultar", window.parent.document).trigger("click");
				});
				pedidos.zona();
			});
		</script>

	</head>
	<body>
		<div class="col-md-12 container-fluid zona" >
			<div class="col-md-12 well"><?php
				$clases[0]='default';
				$clases[1]='warning';
				$clases[2]='primary';
				$clases[3]='success';
				$clases[4]='info';
				
				$posi=0;
				
				foreach ($lugares["rows"] as $key => $value) { ?>
					<button lugar="<?php echo substr($value['lugar'], 0, 9); ?>" id="<?php echo $value["id"] ?>" class="btn btn-<?php echo $clases[$posi] ?> btn-lg div-btn" style="height: 200px; width: 350px">
						<i style="font-size: 50px" ><?php echo substr($value['lugar'], 0, 9); ?></i>
					</button><?php
					
					$posi++;
					$posi=($posi>4)?0:$posi;
				} ?>
			</div>
		</div>
		<label class="error"></label>
		<div class="comandas col-xs-12">
			<div class="col-xs-12 navbar navbar-default navbar-fixed-top">
				<div class="col-xs-12">
					<label class="col-xs-12 flecha abajo" id="flechaArriba" >&uarr;</label>
				</div>
			</div>
			<div class="col-xs-12 navbar navbar-default navbar-fixed-bottom">
				<div class="col-xs-12">
					<label class="col-xs-12 flecha arriba" id="flechaAbajo" >&darr;</label>
				</div>
			</div>
			<div id="dialog" title="Cancelar" style="display:none;">
				<p>
					&iquest;Deseas continuar con la eliminacion del producto?
				</p>
				<div class="col-md-12">
					<input type="button" class="btn btn-success" id="btnAceptarDialog" value="Aceptar">
					<input type="button" class="btn btn-danger" id="btnCancelDialog" value="Cancelar">
				</div>
			</div>
			<div id="dialog2" title="Basic dialog" style="display:none;">
				<p>
					Se ha cancelado la orden
				</p>
				<input type="button" class="btn btn-success" id="btnAceptarElimino" value="Aceptar">
			</div>
			<div id="autoPrint" title="Auto Impresi&oacute;n" style="display:none;">
				<p>
					&iquest;Quieres imprimir tickets automaticamente?
				</p>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading" style="font-size: 25px">
				<div class="row">
					<div class="col-md-2">
						<button id="btn_vista" onclick="pedidos.cambiar_vista()" class="btn btn-warning btn-lg">
							<i class="fa fa-th-large"></i>  /  <i class="fa fa-list"></i>
						</button>
					</div>
					<div class="col-md-10" id="titulo" style="padding-top: 1%">
						<!-- En esta div se carga el titulo del area -->
					</div>
				</div>
			</div>
			<div class="panel-body">
				<div class="row" id="contenedor" style="display: none">
					<div class="col-md-6">
						<div class="panel panel-info">
							<div class="panel-heading" style="font-size: 25px">
								<i class="fa fa-clock-o"></i> Pendientes
							</div>
							<div class="panel-body" id="div_pendientes" style="overflow: scroll;height: 65%">
								<!-- En esta div se cargan los pedidos pendientes -->
							</div>
							<div class="col-md-6">			
								<div class="panel-body" id="clasif">
									<table class="table table-striped table-bordered">
										<tr >
											<td class="danger" >Para llevar</td>
											<td class="info" > A domicilio</td>
											<td >Normal</td>
										</tr>
									</table>
								</div>
						</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-success">
							<div class="panel-heading" style="font-size: 25px">
								<i class="fa fa-check"></i> Terminados
							</div>
							<div class="panel-body" id="div_terminados" style="overflow: scroll;height: 65%">
								<!-- En esta div se cargan los pedidos terminados -->
							</div>
						</div>
						<div class="panel panel-danger">
							<div class="panel-heading" style="font-size: 25px">
								<i class="fa fa-trash"></i> Cancelados
							</div>
							<div class="panel-body" id="div_eliminados" style="overflow: scroll;height: 65%">
								<!-- En esta div se cargan los pedidos eliminados  -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>