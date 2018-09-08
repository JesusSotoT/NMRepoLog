<html>
	<head>
<!-- ///////////////// ******** ---- 		CSS		------ ************ ////////////////// -->

	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<!-- Iconos font-awesome -->
    	<link rel="stylesheet" href="../../libraries/font-awesome-4.7.0/css/font-awesome-4.7.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
		
<!-- ///////////////// ******** ---- 		FIN CSS		------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 		JS		------ ************ ////////////////// -->
	 
	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- bootstrap JavaScript -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
		
	<!--  Sistema  -->
		<script src="js/herramientas.js"></script>
		
<!-- ///////////////// ******** ---- 		FIN JS		------ ************ ////////////////// -->
	</head>
	<body>
		<div class="container" style="padding-top: 25px">
			<div class="panel panel-default">
				<div class="panel-heading">
	    			<div class="alert alert-warning" role="alert">
	                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
	                    <span class="sr-only">Warn:</span>
	                    Puede que alguna informacion no aparezca en la version nueva, ya que no existe en la version antigua
	                </div>
					<div class="row">
						<div class="col-md-2 col-xs-2">
							<label>Instancia vieja</label>
							<select id="instancia_vieja" class="selectpicker" data-live-search="true" data-width="75%"><?php
								foreach ($instancias as $key => $value) { ?>
									<option value="<?php echo $value['db'] ?>"><?php echo $value['instancia'] ?></option><?php
								} ?>
							</select>
						</div>
						<div class="col-md-2 col-xs-2">
							<label>Instancia nueva</label>
							<select id="instancia_nueva" class="selectpicker" data-live-search="true" data-width="75%"><?php
								foreach ($instancias as $key => $value) { ?>
									<option value="<?php echo $value['db'] ?>"><?php echo $value['instancia'] ?></option><?php
								} ?>
							</select>
						</div>
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_proveedores"> Proveedores 
								</label>
	                        </div>
	                    </div>
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_productos"> Productos 
								</label>
	                        </div>
						</div>
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_clientes"> Clientes
								</label>
	                        </div>
						</div>
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_unidades"> U. de medida
								</label>
	                        </div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_cxc"> Cuentas por cobrar
								</label>
	                        </div>
						</div>
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_cxp"> Cuentas por pagar
								</label>
	                        </div>
						</div>
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_alm"> Almacenes
								</label>
	                        </div>
						</div>
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_suc"> Sucursales
								</label>
	                        </div>
						</div>
						<div class="col-md-2 col-xs-2" style="padding-top: 15px">
	                        <div class="checkbox">
								<label>
									<input checked="1" type="checkbox" id="check_exi"> Existencia
								</label>
	                        </div>
						</div>
						<div class="col-md-1 col-xs-1" style="padding-top: 15px; float: right; margin-right: 10px;">
							<button 
								id="btn_mudar_instancia"
								onclick="herramientas.mudar_instancia({
									instancia_vieja: $('#instancia_vieja').val(),
									instancia_nueva: $('#instancia_nueva').val(),
									proveedores: $('#check_proveedores').prop('checked'),
									productos: $('#check_productos').prop('checked'),
									unidades: $('#check_unidades').prop('checked'),
									clientes: $('#check_clientes').prop('checked'),
									cxc: $('#check_cxc').prop('checked'),
									cxp: $('#check_cxp').prop('checked'),									
									alm: $('#check_alm').prop('checked'),
									suc: $('#check_suc').prop('checked'),
									exi: $('#check_exi').prop('checked'),
									btn: 'btn_mudar_instancia'
								})"
								class="btn btn-success" 
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>">
								<i class="fa fa-check"></i> Ok
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>

<!-- Cambiamos los select por select con buscador -->
<script>
	$('.selectpicker').selectpicker('refresh');
</script>