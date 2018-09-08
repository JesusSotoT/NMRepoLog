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
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
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
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- tooltipster  -->
		<script src="../../libraries/tooltipster-master/js/jquery.tooltipster.min.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
		
	<!-- ** Sistema -->
		<script type="text/javascript" src="js/pedidos/pedidos.js"></script>
		<script type="text/javascript" src="js/comandas/reimprime.js"></script>

<!-- **	//////////////////////////- -				FIN JS 				--///////////////////// **-->

		<script type="text/javascript">
			$(document).ready(function() {
				$('#tdmenu', window.parent.document).hide('slow', function() {
					$("#tdocultar", window.parent.document).trigger("click");
				});
				pedidos.zona();
			});
		</script>
		<style type="text/css">
		.div_scroll_x {
			overflow-x: auto;
			white-space: nowrap;
		}
		::-webkit-scrollbar {
			width: 0px; /* remove scrollbar space */
			background: transparent; /* optional: just make scrollbar invisible */
		}
		.div-coman {
			color: white;
			font-size: 20px;
			background: -webkit-linear-gradient(to bottom right, #763F8B, #2C2146); /* Standard syntax */
			background: -o-linear-gradient(to bottom right, #763F8B, #2C2146); /* Standard syntax */
			background: -moz-linear-gradient(to bottom right, #763F8B, #2C2146); /* Standard syntax */
			background: linear-gradient(to bottom right, #763F8B, #2C2146); /* Standard syntax */
		}
		.div-coman:hover {
			color: #DCB435
		}
		</style>
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
		<div class="row ped-conts" style="margin:0; display:none; ">
			<div style="width: 90%; margin-left: 5%; background-color: #714789; padding: 0.5vw; border-radius: 0.7vw;">
				<button style="float: left;
			    background-color: transparent;
			    color: white;
			    font-size: 2vw;
			    padding: 0;
			    margin-left: 0.1vw;" 
			    id="btn_vista" onclick="pedidos.cambiar_vista()" class="btn">
					<i class="fa fa-th-large"></i>  /  <i class="fa fa-list"></i>
				</button>
				<div id="titulo" style="color: white;
							    padding: 0.4vw;
							    margin-left: 7vw;
							    font-size: 2vw;">
				</div>
				<!-- En esta div se carga el titulo del area -->
			</div>
		</div>
		<div class="row ped-conts" style="width: 90%; margin: 0; margin-left: 5%; margin-top: 10px; display:none;">
			<div class="col-md-4 col-xs-4">
				<button id="btn-pen" onclick="pedidos.change_view(1);" class="btn btn-lg btn-view" style="color: white; background-color: #714789; font-size: 20px;"><i class="fa fa-clock-o" ></i> Pendientes</button>
			</div>
			<div class="col-md-4 col-xs-4" style="text-align: center;">
				<button id="btn-ter" onclick="pedidos.change_view(2);" class="btn btn-lg btn-view" style="color: #714789; font-size:20px; background-color: white; border: solid;"><i class="fa fa-check"></i> Terminados</button>
			</div>
			<div class="col-md-4 col-xs-4" style="text-align: right;">
				<button id="btn-can" onclick="pedidos.change_view(3);" class="btn btn-lg btn-view" style="color: #714789; font-size:20px; background-color: white; border: solid;"><i class="fa fa-trash"></i> Cancelados</button>
			</div>
		</div>
		<div class="row ped-conts" id="contenedor" style="margin: 0; display:none; ">
			<div class="col-md-12" style="margin: 0;
			    width: 92%;
			    margin-left: 4%;
			    padding: 0;">
				<div id="div_pendientes" style="margin-top: 14px;overflow: scroll;height: 70vh">
					<!-- En esta div se cargan los pedidos pendientes -->
				</div>
			</div>
			<div class="col-md-12"style="margin: 0;
			    width: 92%;
			    margin-left: 4%;
			    padding: 0;">
				<div id="div_terminados" style="margin-top: 14px;overflow: scroll;height: 70vh; display: none;">
					<div align="center">
						<h3><span class="label label-default">* Aqui aparecen los pedidos terminados *</span></h3>
					</div>
				</div>
			</div>
			<div class="col-md-12"style="margin: 0;
			    width: 92%;
			    margin-left: 4%;
			    padding: 0;">
				<div id="div_eliminados" style="margin-top: 14px; overflow: scroll;height: 70vh; display: none;">
					<div align="center">
						<h3><span class="label label-default">* Aqui aparecen los pedidos eliminados *</span></h3>
					</div>
				</div>
			</div>
		</div>
		
	<!-- Modal eliminar pedido -->
		<div id="modal_eliminar_pedido" class="modal fade" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" onclick="$('#modal_eliminar_pedido').click()">
							&times;
						</button>
						<h4 class="modal-title">Eliminar pedido</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-5 col-xs-5">
								<div class="input-group">
									<span class="input-group-addon"> <i class="fa fa-trash-o"></i> Merma </span>
									<select id="merma" class="selectpicker" data-width="30%">
										<option value="1">Si</option>
										<option selected value="2">No</option>
									</select>
								</div>
							</div>
							<div class="col-md-7 col-xs-7">
								<div class="input-group">
									<span class="input-group-addon"> <i class="fa fa-pencil"></i> </span>
									<input
										onkeypress="(((document.all) ? event.keyCode : event.which)==13) pedidos.eliminar({pedido: pedidos.pedido_seleccionado, comentario: $('#comentario_eliminar').val(), merma: $('#merma').val(), btn: 'btn_eliminar_pedido'})"
										id="comentario_eliminar"
										placeholder="Comentarios"
										type="text"
									class="form-control">
									<span class="input-group-btn">
										<button
											onclick="pedidos.eliminar({
												pedido: pedidos.pedido_seleccionado, 
												comentario: $('#comentario_eliminar').val(), 
												merma: $('#merma').val(),
												btn: 'btn_eliminar_pedido'
											})"
											id="btn_eliminar_pedido"
											class="btn btn-danger"
											data-loading-text="<i class='fa fa-refresh fa-spin'></i>">
											<i class="fa fa-trash"></i> Eliminar
										</button> 
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal eliminar persona -->
		<script>
			$(".selectpicker").selectpicker("refresh");
			$(".panel-heading").trigger("click");
		</script>
	</body>
</html>