<?php
	//echo json_encode($datosCliente);
 ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Formulario de Cliente</title>

		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
		<script src="../../libraries/jquery.min.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../../libraries/numeric.js"></script>
		<script src="js/cliente.js"></script>
		<script src="../../libraries/numeric.js"></script>

		<!--Select 2 -->
		<script src="../../libraries/select2/dist/js/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
		<!-- datetimepicker -->
		<link rel="stylesheet" href="../../libraries/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css" />
		<script src="../../libraries/bootstrap-datetimepicker/js/moment.js"></script>
		<script src="../../libraries/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>

		<script>
			$(document).ready(function() {
				$('#numeros').numeric();
				$('#tipoClas').select2({'width':'100%'});
				$('#tipoDeCredito').select2({'width':'100%'});
				$('#moneda').select2({'width':'100%'});
				$('#banco').select2({'width':'100%'});
				$('#vendedor').select2({'width':'100%'});
				$('#cuentaCont').select2({'width':'100%'});
				$('#paisFact2').select2({'width':'100%'});
				$('#estadoFact').select2({'width':'100%'});
				$('#municipiosFact').select2({'width':'100%'});
				$(".numeros").numeric();
			});
		</script>

		<style>
			.select2-selection {
				height: 34px !important;
			}
		</style>
	</head>

	<body>
		<div class="container-fluid well">
			<div class="row">
				<div class="col-sm-1">
					<button class="btn btn-default" onclick="back();"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Regresar</button>
				</div>
				<div class="col-sm-1">
					<button type="button" class="btn btn-primary" onclick="guardaCliente();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button>
				</div>

				<div class="col-sm-1">
					<?php
						if($idCliente!='') {
							echo '<span class="label label-warning">Editando</span>';
						} else {
							echo '<span class="label label-success">Nuevo</span>';
						}
					?>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"> <h4> Cliente <?php
					if(isset($datosCliente)) { echo ' ('.$datosCliente['basicos'][0]['nombre'].')'; } ?> </h4> </div>

				<div class="panel-body">
					<div style="heigth:400px;">
						<div id="tabsCliente">
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#basicos">Datos básicos</a></li>
								<li><a data-toggle="tab" href="#datosFacturacion">Datos de facturación</a></li>
								<li><a data-toggle="tab" href="#datosEnvios">Datos de envío</a></li>
								<li><a data-toggle="tab" href="#datosCredito">Crédito</a></li>
								<li><a data-toggle="tab" href="#datosComisiones">Comisiones</a></li>
								<li><a data-toggle="tab" href="#datosContables">Datos contables</a></li>
								<li><a data-toggle="tab" href="#dContactos">Contactos</a></li>
								<li><a data-toggle="tab" href="#dCondiciones">Condiciones de venta y pago</a></li>
							</ul>
						</div>

						<div class="tab-content" style="height:450px;">
							<!-- P A N E L   D A T O S   B A S I C O S -->
							<div id="basicos" class="tab-pane fade in active"> <br>
								<div class="row">
									<div class="col-sm-1">
										<label class="control-label" for="email">ID</label>
										<input id="idCliente" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['id']; } ?>" readonly placeholder="(Autonumérico)">
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span style="color:red;">* </span>Código</label>
										<input id="codigo" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['codigo']; } ?>">
									</div>
									<div class="col-sm-2">
										<label>Clasificador del cliente</label>
										<select id="tipoClas" class="form-control">
											<?php
												foreach ($clasificadores as $keyClas => $valueClas) {
													if(isset($datosCliente)) {
														if($datosCliente['basicos'][0]['id_clasificacion']==$valueClas['id']) {
															echo '<option value="'.$valueClas['id'].'" selected>'.$valueClas['nombre'].'/'.$valueClas['clave'].'</option>';
														}
													}
													echo '<option value="'.$valueClas['id'].'">'.$valueClas['nombre'].'/'.$valueClas['clave'].'</option>';
												}
											?>
										</select>
									</div>
									<div class="col-sm-4">
										<label class="control-label"><span style="color:red;">*</span> Nombre</label>
										<input id="nombre" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['nombre']; } ?>">
									</div>
									<div class="col-sm-3">
										<label class="control-label">Contacto</label>
										<input id="tienda" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['contacto']; } ?>">
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-4">
										<label class="control-label">Dirección</label>
										<input id="direccion" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['direccion']; } ?>">
									</div>
									<div class="col-sm-1">
										<label class="control-label">Exterior</label>
										<input id="numext" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['num_ext']; } ?>">
									</div>
									<div class="col-sm-2">
										<label class="control-label">Interior</label>
										<input id="numint" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['num_int']; } ?>">
									</div>
									<div class="col-sm-3">
										<label class="control-label">Colonia</label>
										<input id="colonia" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['colonia']; } ?>">
									</div>
									<div class="col-sm-2">
										<label class="control-label">Código postal</label>
										<input id="cp" class="form-control numeros" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['cp']; } ?>">
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-2">
										<label class="control-label"><span style="color:red;">*</span> País</label>
										<select id="selectPais" class="form-control" >
											<option value="<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['idPais'];} ?>">
												<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descPais'];} ?>
											</option>
										</select>
									</div>
									<div class="col-sm-1">
										<label class="control-label"> &nbsp; </label> <br>
										<button type="button" data-toggle="modal" data-target="#nuevoPais" class="btn btn-success">
											<i class="fa fa-plus" aria-hidden="true"> </i>
										</button>
									</div>
									<!-- M O D A L   N U E V O   P A I S -->
									<div class="modal fade" id="nuevoPais"  role="dialog" aria-labelledby="nuevoPais" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" >Agregar nuevo país</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<input type="text" id="inputNuevoPais" class="form-control" placeholder="Nombre de país">
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
													<button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoPais">Aceptar</button>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-2">
										<label class="control-label">Estado</label>
										<select id="selectEstado" class="form-control" >
											<option value="<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['idEstado'];} ?>">
												<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descEstado'];} ?>
											</option>
										</select>
									</div>
									<div class="col-sm-1">
										<label class="control-label"> &nbsp; </label> <br>
										<button type="button" data-toggle="modal" data-target="#nuevoEstado" class="btn btn-success">
											<i class="fa fa-plus" aria-hidden="true"></i>
										</button>
									</div>
									<!-- M O D A L   N U E V O   E S T A D O -->
									<div class="modal fade" id="nuevoEstado"  role="dialog" aria-labelledby="nuevoEstado" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" >Agregar nuevo estado</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<select id="selectPais2" class="form-control" ></select>
													<input type="text" id="inputNuevoEstado" class="form-control" placeholder="Nombre de estado">
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
													<button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoEstado">Aceptar</button>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-2">
										<label class="control-label">Municipio</label>
										<select id="selectMunicipio" class="form-control" >
											<option value="<?php if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['idMunicipio']; } ?>">
												<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descMunicipio'];} ?>
											</option>
										</select>
									</div>
									<div class="col-sm-1">
										<label class="control-label"> &nbsp; </label> <br>
										<button type="button" data-toggle="modal" data-target="#nuevoMunicipio" class="btn btn-success">
											<i class="fa fa-plus" aria-hidden="true"></i>
										</button>
									</div>
									<!-- M O D A L   N U E V O   M U N I C I P I O -->
									<div class="modal fade" id="nuevoMunicipio"  role="dialog" aria-labelledby="nuevoMunicipio" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" >Agregar nuevo municipio</h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div class="modal-body">
													<select id="selectPais3" class="form-control" ></select>
													<select id="selectEstado3" class="form-control" ></select>
													<input type="text" id="inputNuevoMunicipio" class="form-control" placeholder="Nombre de municipio">
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
													<button type="button" class="btn btn-primary" data-dismiss="modal" id="btnNuevoMunicipio">Aceptar</button>
												</div>
											</div>
										</div>
									</div>

									<div class="col-sm-3">
										<label class="control-label">Ciudad</label>
										<input id="ciudad" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['ciudad']; } ?>">
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-4">
										<label class="control-label">Correo electrónico</label>
										<input id="email" class="form-control" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['email']; } ?>">
									</div>
									<div class="col-sm-2">
										<label class="control-label">Celular</label>
										<input id="celular" class="form-control numeros" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['celular']; } ?>">
									</div>
									<div class="col-sm-2">
										<label class="control-label">Teléfono 1</label>
										<input id="tel1" class="form-control numeros" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['telefono1']; } ?>">
									</div>
									<div class="col-sm-2">
										<label class="control-label">Teléfono 2</label>
										<input id="tel2" class="form-control numeros" type="text" value="<?php
											if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['telefono2']; } ?>">
									</div>
									<div class="col-sm-2" style="height:130px;">
										<div class="form-group">
											<label class="control-label">Fecha de cumpleaños</label>
											<div class='input-group date' id='datetimepicker10'>
												<input id='cumpleanos' type='text' class="form-control" placeholder="dd / mm" value="<?php
													if(isset($datosCliente)) { echo $datosCliente['basicos'][0]['cumpleanos']; } ?>"/>
												<span class="input-group-addon">
													<span class="glyphicon glyphicon-calendar"> </span>
												</span>
											</div>
											<script type="text/javascript">
												$(function () {
													$('#datetimepicker10').datetimepicker({
														viewMode: 'months',
														format: 'DD/MM'
													});
												});
											</script>
										</div>
									</div>
								</div>
							</div><!-- Fin del div basicos -->

							<!-- P A N E L   D A T O S   F A C T U R A C I O N -->
							<div id="datosFacturacion" class="tab-pane fade"> <br>
								<div class="row">
									<div class="col-sm-8">
										<blockquote>
											<p>Si los datos de Facturación son los mismos que los básicos, transfiérelos con el botón Transferir.</p>
										</blockquote>
									</div>
									<div class="col-sm-3">
										<button class="btn btn-info btn-block" onclick="trans();"><i class="fa fa-exchange" aria-hidden="true"></i> Transferir</button>
									</div>
								</div> <br>
								
								<div class="row">
									<div class="col-sm-2">
										<label class="control-label"><span style="color:red;">*</span> R.F.C.</label>
										<input id="rfc" class="form-control datFc" type="text" value="<?php
											if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['rfc'];}?>">
									</div>
									<div class="col-sm-2">
										<label class="control-label">CURP</label>
											<input id="curp" class="form-control datFc" type="text" value="<?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['curp'];}?>">
									</div>
									<div class="col-sm-4">
										<input type="hidden" id="idComunFact" value="<?php
											if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['id'];}?>">
										<label class="control-label"><span style="color:red;">*</span> Razón social</label>
										<input type="text" id="razonSocial" class="form-control datFc" value="<?php
											if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['razon_social'];}?>">
									</div>
									<div class="col-sm-4">
										<label class="control-label"><span style="color:red;">*</span>Correo electrónico</label>
										<input type="text" id="emailFacturacion" class="form-control" value="<?php
											if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['correo'];}?>">
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-4">
										<label class="control-label"><span style="color:red;">*</span> Dirección de facturación</label>
										<input id="direccionFact" class="form-control datFc" type="text" value="<?php
											if(isset($datosClienteFact)) { echo $datosClienteFact['fact'][0]['domicilio']; } ?>">
									</div>
									<div class="col-sm-3">
										<label class="control-label"><span style="color:red;">*</span> Exterior e interior</label>
										<input id="numextFact" class="form-control datFc" type="text" value="<?php
											if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['num_ext'];}?>">
									</div>
									<div class="col-sm-3">
										<label class="control-label"><span style="color:red;">*</span> Colonia</label>
										<input id="coloniaFact" class="form-control datFc" type="text" value="<?php
											if(isset($datosClienteFact)) { echo $datosClienteFact['fact'][0]['colonia']; } ?>">
									</div>
									<div class="col-sm-2">
										<label class="control-label"><span style="color:red;">*</span> Código postal</label>
										<input id="cpFact" class="form-control numeros datFc" type="text" value="<?php
											if(isset($datosClienteFact)) { echo $datosClienteFact['fact'][0]['cp']; } ?>">
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-3">
										<label class="control-label"><span style="color:red;">*</span> País</label>
										<select id="paisFact2" class="form-control" onchange="estadosFc();">
											<option value="0">Selecciona un pais</option>
											<?php
												foreach ($paises as $key => $value) {
													if(isset($datosCliente)) {
														if($datosClienteFact['fact'][0]['idPais']==$value['idpais']) {
															echo '<option value="'.$value['idpais'].'" selected>'.$value['pais'].'</option>';
														}
													}
													echo '<option value="'.$value['idpais'].'">'.$value['pais'].'</option>';
												}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<label class="control-label"><span style="color:red;">*</span> Estado</label>
										<select id="estadoFact" class="form-control datFc" onchange="municipiosFc();">
											<option value="0">Selecciona un estado</option>
											<?php
												foreach ($estados as $key => $value) {
													if(isset($datosClienteFact)) {
														if($datosClienteFact['fact'][0]['estado']==$value['idestado']) {
															echo '<option value="'.$value['idestado'].'" selected>'.$value['estado'].'</option>';
														}
													}
													echo '<option value="'.$value['idestado'].'">'.$value['estado'].'</option>';
												}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<label class="control-label"><span style="color:red;">*</span> Municipio</label>
										<select  id="municipiosFact" class="form-control datFc">
											<option value='0'>Selecciona un municipio</option>
											<?php
												foreach ($municipiosFc as $keyMu => $valueMu) {
													if(isset($datosClienteFact)){
														if($datosClienteFact['fact'][0]['idMunicipio']==$valueMu['idmunicipio']){
															echo '<option value="'.$valueMu['idmunicipio'].'" selected>'.$valueMu['municipio'].'</option>';
														}
													}
													echo '<option value="'.$valueMu['idmunicipio'].'">'.$valueMu['municipio'].'</option>';
												}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<label><span style="color:red;">*</span> Ciudad</label>
										<input id="ciudadFact" type="text" class="form-control datFc"value="<?php
											if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['ciudad'];}?>">
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-3" style="display: none;">
										<label><span style="color:red;">*</span> País</label>
										<input id="paisFact" type="text" class="form-control datFc" value="<?php
										if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['pais'];}?>">
									</div>
									<div class="col-sm-3">
										<label>Régimen fiscal</label>
										<input id="regimenFact" type="text" class="form-control datFc" value="<?php
											if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['regimen_fiscal'];}?>">
									</div>
								</div>
							</div><!-- fin del Tab de facturacion -->

							<!-- P A N E L   D A T O S   D E   E N V I O -->
							<div id="datosEnvios" class="tab-pane fade"> <br>
								<div class="row">
									<div class="col-sm-4">
										<label>Domicilio de envíos</label>
										<textarea id="enviosDom" cols="30" rows="5" class="form-control"><?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['envios'];}?></textarea>
									</div>
								</div>
							</div><!-- Fin del tab de envios-->

							<!-- P A N E L   C R E D I T O -->
							<div id="datosCredito" class="tab-pane fade"> <br>
								<?php
									$x = '';
									$y = '';
									if(isset($datosCliente)) {
										if($datosCliente['basicos'][0]['permitir_vtas_credito']==1) {
											$x = 'checked';
										}else{
											$x = '';
										}

										if($datosCliente['basicos'][0]['permitir_exceder_limite']==1){
											$y = 'checked';
										}else{
											$y= '';
										}
									}
								?>
								<div class="row">
									<div class="col-sm-3">
										<div class="checkbox">
											<label> <input id="checkVc" type="checkbox" value="" <?php echo $x; ?>> Permitir ventas a crédito </label>
										</div>
										<div class="checkbox disabled">
											<label><input id="checkLc" type="checkbox" value="" <?php echo $y; ?>> Permitir exceder límite de crédito </label>
										</div>
									</div>
									<div class="col-sm-3">
										<label>Tipo de crédito</label>
										<select name="tipoDeCredito" id="tipoDeCredito" class="form-control">
											<option value="0">Selecciona un crédito</option>
											<?php
												foreach ($tipoCredito as $keyCre => $valueCre) {
													if(isset($datosCliente)) {
														if($datosCliente['basicos'][0]['id_tipo_credito']==$valueCre['id']) {
															echo '<option value="'.$valueCre['id'].'" selected>'.$valueCre['nombre'].'/'.$valueCre['clave'].'</option>';
														}
													}
													echo '<option value="'.$valueCre['id'].'">'.$valueCre['nombre'].'/'.$valueCre['clave'].'</option>';
												}
											?>
										</select>
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-1">
										<label class="control-label">Días crédito</label>
										<input id="diasCredito" class="form-control numeros" type="text" value="<?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dias_credito'];}?>">
									</div>
									<div class="col-sm-2">
										<label class="control-label">Límite de crédito</label>
										<input id="limiteCredito" class="form-control numeros" type="text" value="<?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['limite_credito'];}?>">
									</div>
									<div class="col-sm-3">
										<label>Saldo</label>
										<input id="saldo" type="text" class="form-control numeros" readonly>
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-3">
										<label>Descuento por pronto pago (%)</label>
										<input id="descuentoPP" type="text" class="form-control numeros" value="<?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dcto_pronto_pago'];}?>">
									</div>
									<div class="col-sm-3">
										<label>Interés moratorio (%)</label>
										<input id="interesesMoratorios" type="text" class="form-control numeros" value="<?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['intereses_moratorios'];}?>">
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-3">
										<label class="control-label">Moneda</label>
										<select id="moneda" class="form-control">
											<?php
												foreach ($moneda as $keyMon => $valueMon) {
													if(isset($datosCliente)){
														if($datosCliente['basicos'][0]['id_lista_precios']==$valueMon['coin_id']){
															echo '<option value="'.$valueMon['coin_id'].'" selected>'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
														}
													}
													echo '<option value="'.$valueMon['coin_id'].'">'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
												}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<label class="control-label">Lista de precio</label>
										<select id="listaPrecio" class="form-control">
											<option value="0" selected>Ninguna</option>
											<?php
												foreach ($listaPre as $key1 => $value1) {
													if(isset($datosCliente)){
														if($datosCliente['basicos'][0]['id_lista_precios']==$value1['id']){
															echo '<option value="'.$value1['id'].'" selected>'.$value1['nombre'].'</option>';
														}
													}
													echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'</option>';
												}
											?>
										</select>
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-3">
										<label>Banco</label>
										<select id="banco" class="form-control">
											<?php
												foreach ($bancos as $keyBan => $valueBan) {
													if(isset($datosCliente)){
														if($datosCliente['basicos'][0]['idBanco']==$valueBan['idbanco']){
															echo '<option value="'.$valueBan['idbanco'].'" selected>'.$valueBan['nombre'].'/'.$valueBan['Clave'].'</option>';
														}
													}
													echo '<option value="'.$valueBan['idbanco'].'">'.$valueBan['nombre'].'/'.$valueBan['Clave'].'</option>';
												}
											?>
										</select>
									</div>
									<div class="col-sm-3">
										<label>Número de cuenta</label>
										<input type="text" id="cuentaBanc" class="form-control" value="<?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['numero_cuenta_banco'];}?>">
									</div>
								</div>
							</div><!-- Fin de tab credito -->

							<!-- P A N E L   C O M I S I O N E S -->
							<div id="datosComisiones" class="tab-pane fade"> <br>
								<div class="row">
									<div class="col-sm-4">
										<label>Vendedor</label>
										<select id="vendedor" class="form-control">
											<option value="0">Selecciona un vendedor</option>
											<?php
												$empleados['empleados'];
												foreach ($empleados['empleados'] as $key8 => $value8) {
													if(isset($datosCliente)){
														if($datosCliente['basicos'][0]['idVendedor']==$value8['idEmpleado']){
															echo '<option value="'.$value8['idEmpleado'].'" selected>'.$value8['nombreEmpleado'].' '.$value8['apellidoMaterno'].'</option>';
														}
													}
													echo '<option value="'.$value8['idEmpleado'].'">'.$value8['nombreEmpleado'].' '.$value8['apellidoMaterno'].'</option>';
												}
											?>
										</select>
									</div>
								</div> <br>

								<div class="row">
									<div class="col-sm-2">
										<label>Comisión de venta (%)</label>
										<input id="comisionVenta" type="text" class="form-control" value="<?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['comision_vta'];}?>">
									</div>
									<div class="col-sm-2">
										<label>Comisión de cobranza (%)</label>
										<input id="comisionCobranza" type="text" class="form-control" value="<?php
											if(isset($datosCliente)){echo $datosCliente['basicos'][0]['comision_cobranza'];}?>">
									</div>
								</div>
							</div><!-- Fin del tab de comisiones  -->

							<!-- P A N E L   D A T O S   C O N T A B L E S -->
							<div id="datosContables" class="tab-pane fade"> <br>
								<div class="row">
									<div class="col-sm-4">
										<label>Cuenta contable</label>
										<select id="cuentaCont" class="form-control">
											<option value="0">Selecciona una cuenta</option>
											<?php
												foreach ($cuentas as $keyCont => $valueCont) {
													if(isset($datosCliente)){
														if($datosCliente['basicos'][0]['cuenta']==$valueCont['account_id']){
															echo '<option value="'.$valueCont['account_id'].'" selected>'.$valueCont['description'].' ('.$valueCont['manual_code'].')'.'</option>';
														}
													}
													echo '<option value="'.$valueCont['account_id'].'">'.$valueCont['description'].' ('.$valueCont['manual_code'].')'.'</option>';
												}
											?>
										</select>
									</div>
								</div>
							</div><!-- Fin del tab Datos Contable-->

							<!-- P A N E L   CONTACTOS -->
							<div id="dContactos" class="tab-pane fade"> <br>
								<div class="row">
									<div class="col-sm-3">
										<label class="control-label" for="email">Nombre</label>
										<input type="text" id="nom_contacto" class="form-control" value="">
									</div>
									<div class="col-sm-2">
										<label class="control-label" for="email">Puesto | Cargo</label>
										<input type="text" id="puesto_contacto" class="form-control" value="" />										
									</div>
									<div class="col-sm-2">
										<label class="control-label" for="email">Correo electrónico</label>
										<input type="text" id="correo_contacto" class="form-control" value="" />										
									</div>									
									<div class="col-sm-2">
										<label class="control-label" for="email">Teléfono</label>
										<input type="text" id="tel_contacto" class="form-control" value="" />										
									</div>
									<div class="col-sm-2">
										<label class="control-label" for="email">No. Celular</label>
										<input type="text" id="cel_contacto" class="form-control" value="" />										
									</div>
									<div class="col-sm-1">
										<label class="control-label"></label> <br />
										<button type="button" data-toggle="modal" data-target="#nuevoPais" class="btn btn-success btn-sm" title="Agregar contacto">
											<i class="fa fa-plus cursor" aria-hidden="true"></i>
										</button>
									</div>
								</div> <br /> <br />
								
								<div class="row">
									<div class="col-sm-10">
										<table id="contactList" class="table">
											<thead>
												<tr>
													<th></th>
													<th>Nombre</th>
													<th>Puesto | Cargo</th>
													<th>Correo electrónico</th>
													<th>Teléfono</th>
													<th>No. Celular</th>
												</tr>
											</thead>
											<tbody></tbody>
										</table>
									</div>
								</div>
							</div><!-- Fin del tab Contactos -->

							<!-- P A N E L   CONDICIONES DE VENTA Y PAGO -->
							<div id="dCondiciones" class="tab-pane fade"> <br>
								<div class="row">
									<div class="col-sm-2">
										<label class="control-label" for="email"> Condiciones de venta </label> <br />
										<input type="radio" name="optVenta" value="" <?php echo $x; ?> > Contado &nbsp; &nbsp;
										<input type="radio" name="optVenta" value="" <?php echo $y; ?>> Crédito

									</div>
									<div class="col-sm-2">
										<label class="control-label" for="email"> Días de revisión </label>
										<input type="text" id="vtaDias" class="form-control" value=""/>
									</div>
									<div class="col-sm-2">
										<label class="control-label" for="email"> Lugar de revisión </label>
										<input type="text" id="vtaLugar" class="form-control" value=""/>
									</div>
									<div class="col-sm-1">
										<label class="control-label" for="email"> Horario </label>
										<input type="text" id="vtaHorario" class="form-control" value=""/>
									</div>
								</div> <br />
								
								<div class="row">
									<div class="col-sm-2">
										<label class="control-label" for="email"> Condiciones de pago </label> <br />
										<input type="radio" name="optCond" value="" <?php echo $x; ?> > Contado &nbsp; &nbsp;
										<input type="radio" name="optCond" value="" <?php echo $y; ?>> Crédito
									</div>
									<div class="col-sm-2">
										<label class="control-label" for="email"> Días de pago </label>
										<input type="text" id="vtaDias" class="form-control" value=""/>
									</div>
									<div class="col-sm-2">
										<label class="control-label" for="email"> Lugar de pago </label>
										<input type="text" id="vtaLugar" class="form-control" value=""/>
									</div>
									<div class="col-sm-1">
										<label class="control-label" for="email"> Horario </label>
										<input type="text" id="vtaHorario" class="form-control" value=""/>
									</div>
									<div class="col-sm-3">
										<label class="control-label" for="email"> Forma de pago </label>
										<input type="text" id="vtaHorario" class="form-control" value=""/>
									</div>
								</div> <br />
								
								<div class="row">
									<div class="col-sm-2">
										<label class="control-label" for="email"> Documentar anticipado </label> <br />
										<input type="radio" name="optDocumenta" value="" <?php echo $x; ?> > Sí &nbsp; &nbsp;
										<input type="radio" name="optDocumenta" value="" <?php echo $y; ?>> No
									</div>
									<div class="col-sm-2">
										<label class="control-label" for="email"> Tipo de mercancía </label>
										<input type="text" id="vtaDias" class="form-control" value=""/>
									</div>
								</div>
							</div><!-- Fin del tab Condiciones de venta y pago-->

						</div>  <!-- Fin del div de los tabs -->
					</div><!-- fin de contenedor overflow -->
				</div> <!-- Fin del Panel Body -->
			</div>
		</div>

		<!--          MODAL SUCCESS          -->
		<div id="modalSuccess" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content panel-success">
					<div class="modal-header panel-heading">
						<h4 id="modal-label">Exito!</h4>
					</div>
					<div class="modal-body">
						<p>Tu Cliente se guardó exitosamente</p>
					</div>
					<div class="modal-footer">
						<button id="modal-btnconf2-uno" type="button" class="btn btn-default" onclick="back();">Continuar</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
