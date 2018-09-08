<html>
	<head>
<!-- **	/////////////////////////- -				 CSS 				--///////////////////// **-->

	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- bootstrap min CSS -->
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<!-- Select2 -->
			<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css">
			<style>
				.select2-selection{
					height: 46px!important;
				}
			</style>


<!-- **	//////////////////////////- -				FIN CSS 				--///////////////////// **-->

<!-- **	//////////////////////////- -				JS 				--///////////////////// **-->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	<!-- Select 2 -->
		<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<!-- Sistema -->
	<script src="js/numeric.js" type="text/javascript"></script>
		<script src="js/recetas/recetas.js"></script>


<!-- **	//////////////////////////- -				FIN JS 				--///////////////////// **-->

		<title>Recetas</title>
	</head>
	<body>
		<div class="row">
			<div class="col-xs-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-12" style="margin-top: 10px">
								<button
									id="btn_nueva"
									onclick="recetas.vista_nueva({vista: '<?php echo $vista ?>', div:'div_recetas', btn:'btn_nueva', panel:'success'})"
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
									type="button"
									class="btn btn-success btn-lg"
									style="width: 170px; margin-top: 0.5%;">
									<i class="fa fa-plus"></i> Agregar
								</button>
								<button
									id="btn_editar"
									onclick="recetas.vista_editar({vista: '<?php echo $vista ?>',div:'div_editar', btn:'btn_editar', panel:'primary'})"
									data-toggle="modal"
									data-target="#modal_editar"
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
									type="button"
									class="btn btn-primary btn-lg"
									style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-pencil"></i> Modificar
								</button>
								<button
									id="btn_eliminar"
									onclick="recetas.vista_eliminar({div:'div_eliminar',btn:'btn_eliminar',panel:'danger'})"
									data-toggle="modal"
									data-target="#modal_eliminar"
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
									type="button"
									class="btn btn-danger btn-lg"
									style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-trash"></i> Eliminar
								</button>
								<button
									id="btn_copiar"
									onclick="recetas.vista_copiar({vista: '<?php echo $vista ?>', div:'div_copiar', btn:'btn_copiar', panel:'warning'})"
									data-toggle="modal"
									data-target="#modal_copiar"
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
									type="button"
									class="btn btn-warning btn-lg"
									style="width: 170px; margin-top: 0.5%">
									<i class="fa fa-clone"></i> Copiar
								</button>
							</div>
						</div>
					</div>
				    <div class="panel-body">
						<div id="div_recetas" class="row" style="overflow: scroll;height:75%">
							<!-- En esta div se carga el contenido de las recetas -->
						</div>
				  	</div>
				</div>
			</div>
		</div>
	<!-- Modal Copiar-->
		<div class="modal fade" id="modal_copiar" role="dialog" aria-labelledby="titulo_copiar">
			<div class="modal-dialog" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_copiar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_copiar">Copiar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_copiar">
								<!-- En esta div se cargan las recetas e insumos preparados -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal copiar-->
	<!-- Modal editar-->
		<div class="modal fade" id="modal_editar" role="dialog" aria-labelledby="titulo_editar">
			<div class="modal-dialog" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_editar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_editar">Modificar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_editar">
								<!-- En esta div se cargan las recetas e insumos preparados -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal editar -->
	<!-- Modal eliminar-->
		<div class="modal fade" id="modal_eliminar" role="dialog" aria-labelledby="titulo_eliminar">
			<div class="modal-dialog" style="width: 90%" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_eliminar" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_eliminar">Eliminar</h4>
					</div>
					<div class="modal-body">
						<div class="row" style="overflow: scroll;height:60%">
							<div class="col-xs-12" id="div_eliminar">
								<!-- En esta div se cargan las recetas e insumos preparados -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal editar -->
	</body>
</html>
<script>
// Carga la vista para editar las recetas
	// $('#btn_nueva').click();
	//$('#btn_editar').click();
</script>
