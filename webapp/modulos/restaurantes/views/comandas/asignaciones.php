<html>
	<head>
<!-- **	/////////////////////////- -				 CSS 				--///////////////////// **-->

	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
		<link rel="stylesheet" href="../../libraries/jquery-ui-1.11.4/jquery-ui.theme.min.css">
	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- gridstack -->
	    <link rel="stylesheet" href="../../libraries/gridstack.js-master/dist/gridstack.css"/>
	    
	<!-- ** Sistema -->
		<link rel="stylesheet" type="text/css" href="css/comandas/comandas.css">
		
<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->
	
<!-- **	//////////////////////////- -				JS 				--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- tooltipster  -->
		<script src="../../libraries/tooltipster-master/js/jquery.tooltipster.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
		
	<!-- ** Sistema -->
		<script type="text/javascript" src="js/comandas/comandas.js"></script>

<!-- **	//////////////////////////- -				FIN JS 				--///////////////////// **-->
	</head>
	<body>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-4">
						<!-- Div para generar espacio -->
					</div>
					<div class="col-xs-8">
    					<div class="input-group input-group-lg">
							<input id="pass" type="password" class="form-control" placeholder="pass">
							<span class="input-group-btn">
								<button id="btn_autorizar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-success" type="button" onclick="comandas.autorizar_asignacion({pass:$('#pass').val()})">
									<i class="fa fa-check"></i> Autorizar
								</button>
								<button id="btn_reiniciar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-danger" type="button" onclick="comandas.reiniciar_asignacion({pass:$('#pass').val()})">
									<i class="fa fa-refresh"></i> Reiniciar
								</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<input readonly="1" id="id_empleado" type="text" class="form-control" style="visibility:hidden" />
				<div class="row">
					<div id="div_empleados" class="col-md-12" style="overflow: scroll;height:70%">
						<!-- En esta div se cargan los empleados -->
					</div>
				</div>
			</div>
		</div>
		
	<!-- Modal Mesas -->
		<div class="modal fade" style="height:95%" id="modal_mesas" tabindex="-1" role="dialog" aria-labelledby="titulo_mesas" data-backdrop="static">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_mesas" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_mesas">Seleccionar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:55%">
							<div class="col-xs-12" id="contenedor">
								<!-- En esta div se cargar las mesas -->
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<div class="row">
							<div class="col-xs-12">
								<button data-loading-text="<i class='fa fa-refresh fa-spin'></i>" id="btn_guardar" onclick="comandas.guardar_asignacion({empleado: $('#id_empleado').val(),vista:2})" class="btn btn-success btn-lg" type="button">
									<i class="fa fa-check"></i> Ok
								</button>
								<button id="cerrar_modal" type="button" class="btn btn-danger btn-lg" data-dismiss="modal">
									<i class="fa fa-ban"></i> Cancelar
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal Mesas -->
	</body>
</html>
<script>
// Consulta las mesas ylas agrega a la div
	comandas.listar_mesas({div:'contenedor', asignar:1});
	
// Consulta los empleados y los agrega a la div
	comandas.listar_empleados({div:'div_empleados'});
</script>