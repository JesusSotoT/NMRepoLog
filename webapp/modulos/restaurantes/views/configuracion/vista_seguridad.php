<html>
	<head>
	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- bootstrap theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	
	<!-- jquery -->
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>

	<!-- bootstrap JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	
	<!--  Notify  -->
		<script src="js/notify.js"></script>
	
	<!-- configuracion.js -->
		<script src="js/configuracion/configuracion.js"></script>
	</head>
	<body>
		<div class="row">
			<div class="col-xs-6">
				<div class="panel panel-default" style="width: 90%">
					<div class="panel-heading">
						<h4><strong>Contraseña de seguridad</strong></h4>
					</div>
					<div class="panel-body">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingOne">
									<h4 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne"> Mostrar contraseña </a></h4>
								</div>
								<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
									<div class="panel-body">
										<div class="row">
											<div class="col-xs-12">
												<h3><small>Contraseña actual:</small></h3>
												<div class="input-group">
													<span class="input-group-addon"> <i class="fa fa-lock"></i> </span>
													<input id="pass" type="text" class="form-control" readonly value="<?php echo $pass ?>">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading" role="tab" id="headingTwo">
									<h4 class="panel-title"><a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo"> Cambiar contraseña </a></h4>
								</div>
								<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
									<div class="panel-body">
										<form id="form_seguridad">
											<h3><small>Introduce la nueva contraseña:</small></h3>
											<div class="input-group">
												<span class="input-group-addon"> <i class="fa fa-lock"></i> </span>
												<input id="pass1" type="text" class="form-control" placeholder="Pass">
											</div>
											<h3><small>Confirma contraseña:</small></h3>
											<div class="input-group">
												<span class="input-group-addon"> <i class="fa fa-lock"></i> </span>
												<input id="pass2" type="text" class="form-control" placeholder="Pass">
											</div>
											<br />
											<div role="group" align="right">
												<button onclick="configuracion.guardar_pass({pass1:$('#pass1').val(), pass2:$('#pass2').val()})" type="button" class="btn btn-success">
													Guardar
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>