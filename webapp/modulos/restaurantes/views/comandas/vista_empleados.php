<html>
	<head>
<!-- ///////////////// ******** ---- 	CSS		------ ************ ////////////////// -->

	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<!-- bootstrap theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/css/bootstrap-select.min.css">
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		
<!-- ///////////////// ******** ---- 	FIN	CSS		------ ************ ////////////////// -->
	
<!-- ///////////////// ******** ---- 	JS		------ ************ ////////////////// -->

	<!-- JQuery -->
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<!-- jqueryui -->
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
	<!-- bootstrap JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.9.3/js/bootstrap-select.min.js"></script>
	<!--  Notify  -->
		<script src="js/notify.js"></script>
		
	<!-- Sistema -->
		<script type="text/javascript" src="js/comandas/comandas.js"></script>
		
<!-- ///////////////// ******** ---- 	FIN JS		------ ************ ////////////////// -->
	
	</head>
	<body>
	<!-- Empleados -->
		<div class="row">
			<div class="col-md-12"><?php
				$clases[0] = 'default';
				$clases[1] = 'success';
				$clases[2] = 'warning';
				$clases[3] = 'primary';
				$clases[4] = 'danger';
				$clases[5] = 'info';
						
				$posi = 0;
				
				foreach ($_SESSION['permisos']['empleados'] as $key => $value) { ?>
					<div class="pull-left" style="padding:5px">
						<button 
							type="button" 
							class="btn btn-<?php echo $clases[$posi] ?> btn-lg" 
							onclick="comandas.modal_login({empleado:'<?php echo $value['usuario'] ?>', id:'<?php echo $value['id'] ?>'})" 
							style="width: 110px;" 
							data-toggle="modal" 
							data-target="#modal_pass">
							<i class="fa fa-user"></i> <br>
							<i style="font-size: 15px" ><?php echo substr($value['usuario'], 0, 8); ?></i>
						</button>
					</div><?php
				
					$posi++;
					$posi = ($posi > 5) ? 0 : $posi;
					
				} ?>
			</div>
		</div>
	<!-- FIN Empleados -->
	
	<!-- Modal pass-->
		<div class="modal fade" id="modal_pass" tabindex="-1" role="dialog" aria-labelledby="titulo_pass" data-backdrop="static">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button id="btn_cerrar_pass" type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title" id="titulo_pass">Ingresar</h4>
					</div>
					<div class="modal-body">
						<input readonly="1" id="id_empleado" type="text" class="form-control" style="visibility:hidden" />
						<div class="row">
							<div class="col-xs-6">
								<div class="input-group">
									<span class="input-group-addon"> <i class="fa fa-user"></i> </span>
									<input readonly="1" id="empleado" type="text" class="form-control" />
								</div>
							</div>
							<div class="col-xs-6">
								<div class="input-group">
									<span class="input-group-addon"> <i class="fa fa-lock"></i> </span>
									<input autocomplete="off" name="empleado" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.iniciar_sesion({empleado: $('#id_empleado').val(),pass:$('#pass_empleado').val()})" id="pass_empleado" type="password" class="form-control" autofocus="autofocus">
								</div>
							</div>
						</div>
					</div>
				
				<!-- Botones -->
					<div class="modal-footer">
						<button type="button" class="btn btn-primary"  onclick="comandas.iniciar_sesion({empleado: $('#id_empleado').val(),pass:$('#pass_empleado').val()})">
							<i class="fa fa-sign-in"></i> Entrar
						</button>
					</div>
				</div>
			</div>
		</div>
	<!-- FIN Modal pass-->
	
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
								<button id="btn_guardar" data-loading-text="<i class='fa fa-refresh fa-spin'></i>"  onclick="comandas.guardar_asignacion({empleado: $('#id_empleado').val(),vista:1})" class="btn btn-success btn-lg" type="button">
									<i class="fa fa-check"></i> Guardar
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
	
// Bloquea las mesas asignadas para que no las pueda seleccionar el usuario
	comandas.bloquear_mesas();
</script>