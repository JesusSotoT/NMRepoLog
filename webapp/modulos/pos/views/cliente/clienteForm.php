<?php
//echo json_encode($datosCliente);
function randpass() {
	$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	$pass = array(); //remember to declare $pass as an array
	$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	for ($i = 0; $i < 8; $i++) {
		$n = rand(0, $alphaLength);
		$pass[] = $alphabet[$n];
	}
	return implode($pass); //turn the array into a string
}
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
		  $(".numeros").numeric();
		});
		
		function enviarCorreoPortal(){
			$('#btnenviarCorreo').prop('disabled',true); 
			$('#btnenviarCorreo').text( $('#btnenviarCorreo').attr('txt-click') );

			correoportal=$('#correoportal').val();
			userportal=$('#userportal').val();
			passportal=$('#passportal').val();
			nombre=$('#nombre').val();

			if(correoportal=='' || userportal=='' || passportal==''){
				alert('Los campos no pueden estar vacios.');
				$('#btnenviarCorreo').prop('disabled',false); 
				$('#btnenviarCorreo').text( $('#btnenviarCorreo').attr('txt-original') );
				return false
			}

			$.ajax({
			url:"ajax.php?c=cliente&f=correoPortal",
			type: 'POST',
			data:{correoportal:correoportal,userportal:userportal,passportal:passportal,nombre:nombre},
			success: function(data){
				if(data==1){
					alert('Correo enviado al proveedor');
				}else{
					alert('Error en el proceso de envio');
				}
				$('#btnenviarCorreo').prop('disabled',false); 
				$('#btnenviarCorreo').text( $('#btnenviarCorreo').attr('txt-original') );

			}
		  });
		}
	</script>
  <style>
	.select2-selection{
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
		  if($idCliente!=''){
			echo '<span class="label label-warning">Editando</span>';
		  }else{
			echo '<span class="label label-success">Nuevo</span>';
		  }

		?>
		</div>
	</div>
  <div class="panel panel-default">
  <div class="panel-heading"><h5>Cliente<?php
						if(isset($datosCliente)){echo ' ('.$datosCliente['basicos'][0]['nombre'].')';}?></h5></div>
  <div class="panel-body">
	<div style="heigth:400px;">
	  <div id="tabsCliente">
		<ul class="nav nav-tabs">
		  <li class="active"><a data-toggle="tab" href="#basicos">Datos Básicos</a></li>
		  <li><a data-toggle="tab" href="#datosFacturacion">Datos de Facturación</a></li>
		  <li><a data-toggle="tab" href="#datosEnvios">Datos de Envío</a></li>
		  <li><a data-toggle="tab" href="#datosCredito">Crédito</a></li>
		  <li><a data-toggle="tab" href="#datosComisiones">Comisiones</a></li>
		  <li><a data-toggle="tab" href="#datosContables">Datos Contables</a></li>
		  <li><a data-toggle="tab" href="#accesoPortal">Acceso al portal</a></li>
		</ul>
	  </div>
	  <div class="tab-content" style="height:450px;">
		<div id="basicos" class="tab-pane fade in active">
		  <div class="row">
			<div class="col-sm-2">
			  <label class="control-label" for="email">ID</label>
			  <input id="idCliente" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['id'];}?>" readonly placeholder="(Autonumérico)">
			</div>
			<div class="col-sm-3">
				<label class="control-label"><span style="color:red;">* </span>Código</label>
				<input id="codigo" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['codigo'];}?>">
			</div>
			<div class="col-sm-3">
			  <label>Clasificador del Cliente</label>
			  <select id="tipoClas" class="form-control">
				<?php
				  foreach ($clasificadores as $keyClas => $valueClas) {
							   if(isset($datosCliente)){
								  if($datosCliente['basicos'][0]['id_clasificacion']==$valueClas['id']){
									echo '<option value="'.$valueClas['id'].'" selected>'.$valueClas['nombre'].'/'.$valueClas['clave'].'</option>';
								  }
								}
					echo '<option value="'.$valueClas['id'].'">'.$valueClas['nombre'].'/'.$valueClas['clave'].'</option>';
				  }
				?>
			  </select>
			</div>

		  </div>

		  <div class="row">
			<div class="col-sm-6">
			  <label class="control-label"><span style="color:red;">*</span> Nombre</label>
			  <input id="nombre" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombre'];}?>">
			</div>
			<div class="col-sm-6">
				<label class="control-label">Nombre Comercial</label>
				<input id="tienda" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['nombretienda'];}?>">
			</div>
		  </div>

		  <div class="row">
			<div class="col-sm-6">
			  <label class="control-label">Dirección</label>
			  <input id="direccion" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['direccion'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Exterior</label>
			  <input id="numext" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['num_ext'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Interior</label>
			  <input id="numint" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['num_int'];}?>">
			</div>
		  </div>

		  <div class="row">
			<div class="col-sm-2">
			  <label class="control-label">Colonia</label>
			  <input id="colonia" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['colonia'];}?>">
			</div>
			<div class="col-sm-2">
				<label class="control-label">Código Postal</label>
				<input id="cp" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['cp'];}?>">
			</div>
			<div class="col-sm-2">
				<div class="row">
					<div class="col-sm-8">
						<label class="control-label"><span style="color:red;">*</span> País</label>
						<select id="selectPais" class="form-control" >
							<option value="<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['idPais'];} ?>">
								<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descPais'];} ?>
							</option>
						</select>
					</div>
					<div class="col-sm-1">
						<label class="control-label"></label>
						<button type="button" data-toggle="modal" data-target="#nuevoPais" class="btn btn-success">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>
<!-- Modal -->
<div class="modal fade" id="nuevoPais"  role="dialog" aria-labelledby="nuevoPais" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" >Agregar nuevo País</h5>
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
				<div class="row">
					<div class="col-sm-8">
						<label class="control-label">Estado</label>
						<select id="selectEstado" class="form-control" >
							<option value="<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['idEstado'];} ?>">
								<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descEstado'];} ?>
							</option>
						</select>
					</div>
					<div class="col-sm-1">
						<label class="control-label"></label>
						<button type="button" data-toggle="modal" data-target="#nuevoEstado" class="btn btn-success">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>
<!-- Modal -->
<div class="modal fade" id="nuevoEstado"  role="dialog" aria-labelledby="nuevoEstado" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" >Agregar nuevo Estado</h5>
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
				<div class="row">
					<div class="col-sm-8">
						<label class="control-label">Municipio</label>
						<select id="selectMunicipio" class="form-control" >
							<option value="<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['idMunicipio'];} ?>">
								<?php if(isset($datosCliente)){echo $datosCliente['basicos'][0]['descMunicipio'];} ?>
							</option>
						</select>
					</div>
					<div class="col-sm-1">
						<label class="control-label"></label>
						<button type="button" data-toggle="modal" data-target="#nuevoMunicipio" class="btn btn-success">
							<i class="fa fa-plus" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div>
<!-- Modal -->
<div class="modal fade" id="nuevoMunicipio"  role="dialog" aria-labelledby="nuevoMunicipio" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" >Agregar nuevo Municipio</h5>
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
			<div class="col-sm-2">
			  <label class="control-label">Ciudad</label>
			  <input id="ciudad" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['ciudad'];}?>">
			</div>
		  </div>

		  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Email</label>
			  <input id="email" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['email'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Celular</label>
			  <input id="celular" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['celular'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Teléfono 1</label>
			  <input id="tel1" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['telefono1'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Teléfono 2</label>
			  <input id="tel2" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['telefono2'];}?>">
			</div>
		  </div>

		  <div class="row">
				<div class="col-sm-3" style="height:130px;">
					<div class="form-group">
						<label class="control-label">Fecha de cumpleaños
						</label>
						<div class='input-group date' id='datetimepicker10'>
							<input id='cumpleanos' type='text' class="form-control" placeholder="dd / mm" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['cumpleanos'];}
					?>"/>
							<span class="input-group-addon">
								<span class="glyphicon glyphicon-calendar">
								</span>
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

		<!--  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">RFC</label>
			  <input id="rfc" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['rfc'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">CURP</label>
			  <input id="curp" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['curp'];}?>">
			</div>
		  </div> -->

		<!--  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Dias de Credito</label>
			  <input id="diasCredito" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dias_credito'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Limite de Credito</label>
			  <input id="limiteCredito" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['limite_credito'];}?>">
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Moneda</label>
			  <!--<input id="moneda" class="form-control" type="text" value=""> -->
			 <!-- <select id="moneda" class="form-control">
				<?php

				  foreach ($moneda as $keyMon => $valueMon) {
					echo '<option value="'.$valueMon['coin_id'].'">'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
				  }

				?>
			  </select>
			</div>
			<div class="col-sm-3"></div>
		  </div> -->



		  <div class="row"><br>
			<div class="col-sm-10"></div>
			<!--<div class="col-sm-1"><button type="button" class="btn btn-primary" onclick="guardaCliente();"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</button></div> -->
		  </div>
		</div><!-- Fin del div basicos -->
		<div id="datosFacturacion" class="tab-pane fade">
		  <div class="row"><br>
			<div class="col-sm-8">
			  <blockquote>
				<p>Si los datos de Facturación son los mismos que los básicos, transfiérelos de los básicos a facturación con el botón de Transferir.</p>
			  </blockquote>
			</div>
			<div class="col-sm-3">
			  <button class="btn btn-info btn-block" onclick="trans();"><i class="fa fa-exchange" aria-hidden="true"></i> Transferir</button>
			</div>

		  </div>
				  <div class="row">
					<div class="col-sm-6">
					  <input type="hidden" id="idComunFact" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['id'];}?>">

					  <label class="control-label"><span style="color:red;">*</span> Razón Social</label>
					  <input type="text" id="razonSocial" class="form-control datFc" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['razon_social'];}?>">
					</div>
				  </div>
				  <div class="row">
					  <div class="col-sm-3">
						<label class="control-label"><span style="color:red;">*</span> RFC</label>
						<input id="rfc" class="form-control datFc" type="text" value="<?php
								  if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['rfc'];}?>">
					  </div>
					  <div class="col-sm-3">
						<label class="control-label">CURP</label>
						<input id="curp" class="form-control datFc" type="text" value="<?php
								  if(isset($datosCliente)){echo $datosCliente['basicos'][0]['curp'];}?>">
					  </div>
					  <div class="col-sm-3">
						<label class="control-label"><span style="color:red;">*</span> Email</label>
						<input type="text" id="emailFacturacion" class="form-control" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['correo'];}?>">
					  </div>
				  </div>
				  <div class="row">
					<div class="col-sm-6">
					  <label class="control-label"><span style="color:red;">*</span> Dirección de Facturación</label>
					  <input id="direccionFact" class="form-control datFc" type="text" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['domicilio'];}?>">
					</div>
					<div class="col-sm-3">
					  <label class="control-label"><span style="color:red;">*</span> Exterior e Interior F.</label>
					  <input id="numextFact" class="form-control datFc" type="text" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['num_ext'];}?>">
					</div>
				   <!-- <div class="col-sm-3">
					  <label class="control-label">Interior F.</label>
					  <input id="numintFact" class="form-control" type="text" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['num_int'];}?>">
					</div> -->
				  </div>
				  <div class="row">
					<div class="col-sm-2">
					  <label class="control-label"><span style="color:red;">*</span> Colonia</label>
					  <input id="coloniaFact" class="form-control datFc" type="text" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['colonia'];}?>">
					</div>
					<div class="col-sm-2">
						<label class="control-label"><span style="color:red;">*</span> Código Postal</label>
						<input id="cpFact" class="form-control numeros datFc" type="text" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['cp'];}?>">
					</div>
					<div class="col-sm-2">
						<label class="control-label"><span style="color:red;">*</span> País</label>
						<select id="paisFact2" class="form-control" onchange="estadosFc();">
						  <option value="0">-Selecciona un pais</option>
						  <?php
							foreach ($paises as $key => $value) {
							  if(isset($datosCliente)){
								if($datosClienteFact['fact'][0]['idPais']==$value['idpais']){
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
						  <option value="0">-Selecciona un estado</option>
							<?php
								foreach ($estados as $key => $value) {
									if(isset($datosClienteFact)){
										if($datosClienteFact['fact'][0]['estado']==$value['idestado']){
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
						  <option value='0'>-Selecciona un municipio--</option>
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
				  </div>
				  <div class="row">
					<div class="col-sm-3">
					  <label><span style="color:red;">*</span> Ciudad</label>
					  <input id="ciudadFact" type="text" class="form-control datFc"value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['ciudad'];}?>">
					</div>
					<div class="col-sm-3" style="display: none;">
					   <label><span style="color:red;">*</span> País</label>
					  <input id="paisFact" type="text" class="form-control datFc" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['pais'];}?>">
					</div>
					<div class="col-sm-3">
					   <label>Régimen Fiscal</label>
					  <input id="regimenFact" type="text" class="form-control datFc" value="<?php
								if(isset($datosClienteFact)){echo $datosClienteFact['fact'][0]['regimen_fiscal'];}?>">
					</div>
				  </div>
		</div><!-- fin del Tab de facturacion -->
		<div id="datosCredito" class="tab-pane fade">
		  <div class="row">
		  <div class="col-sm-3">
			<label>Tipo de Crédito</label>
			<select name="tipoDeCredito" id="tipoDeCredito" class="form-control">
			  <option value="0">-Selecciona un Crédito-</option>
			  <?php
				foreach ($tipoCredito as $keyCre => $valueCre) {
					if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['id_tipo_credito']==$valueCre['id']){
						  echo '<option value="'.$valueCre['id'].'" selected>'.$valueCre['nombre'].'/'.$valueCre['clave'].'</option>';
					  }
					}
					echo '<option value="'.$valueCre['id'].'">'.$valueCre['nombre'].'/'.$valueCre['clave'].'</option>';
				}

			  ?>
			</select>
		  </div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Días de Crédito</label>
			  <input id="diasCredito" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dias_credito'];}?>">
			</div>

			<div class="col-sm-3">
			  <label>Saldo</label>
			  <input id="saldo" type="text" class="form-control numeros" readonly>
			</div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Límite de Crédito</label>
			  <input id="limiteCredito" class="form-control numeros" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['limite_credito'];}?>">
			</div>
		  </div>
		  <?php
				$x = '';
				$y = '';
				  if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['permitir_vtas_credito']==1){
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
				<label>
				  <input id="checkVc" type="checkbox" value="" <?php echo $x; ?>>
				  Permitir ventas a crédito
				</label>
			  </div>
			  <div class="checkbox disabled">
				<label>
				  <input id="checkLc" type="checkbox" value="" <?php echo $y; ?>>
				  Permitir exceder límite de crédito
				</label>
			  </div>
			</div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label>Descuento por pronto pago (%)</label>
			  <input id="descuentoPP" type="text" class="form-control numeros" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['dcto_pronto_pago'];}?>">
			</div>
			<div class="col-sm-3">
			  <label>Intereses Moratorios (%)</label>
			  <input id="interesesMoratorios" type="text" class="form-control numeros" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['intereses_moratorios'];}?>">
			</div>
			<div class="col-sm-3"></div>
			<div class="col-sm-3"></div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label class="control-label">Moneda</label>
			  <!--<input id="moneda" class="form-control" type="text" value=""> -->
			  <select id="moneda" class="form-control">
				<?php

				  foreach ($moneda as $keyMon => $valueMon) {
					if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['id_moneda']==$valueMon['coin_id']){
						  echo '<option value="'.$valueMon['coin_id'].'" selected>'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
					  }
					}
					echo '<option value="'.$valueMon['coin_id'].'">'.$valueMon['description'].'/'.$valueMon['codigo'].'</option>';
				  }

				?>
			  </select>
			</div>
			<div class="col-sm-3">
			  <label class="control-label">Lista de Precio</label>
			  <select id="listaPrecio" class="form-control">
				<option value="0" selected>-Ninguna-</option>
				<?php
				foreach ($listaPre as $key1 => $value1) {
				  if(isset($datosCliente)){
					  if($datosCliente['basicos'][0]['id_lista_precios']==$value1['id']){
						  echo '<option value="'.$value1['id'].'" selected>'.$value1['nombre'].'</option>';
					  }else{
						 echo '<option value="'.$value1['id'].'">'.$value1['nombre'].'</option>';
					  }
					  
				  }				  
				}
				?>
			  </select>
			</div>
		  </div>
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
			  <label>Número de Cuenta</label>
			  <input type="text" id="cuentaBanc" class="form-control" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['numero_cuenta_banco'];}?>">
			</div>
			<div class="col-sm-3"></div>
		  </div>
		</div><!-- Fin de tab credito -->
		<div id="datosComisiones" class="tab-pane fade">
		  <div class="row">
			<div class="col-sm-3">
			  <label>Comisión de Venta (%)</label>
			  <input id="comisionVenta" type="text" class="form-control" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['comision_vta'];}?>">
			</div>
			<div class="col-sm-3">
			  <label>Comisión de Cobranza (%)</label>
			  <input id="comisionCobranza" type="text" class="form-control" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['comision_cobranza'];}?>">
			</div>
		  </div>
		  <div class="row">
			<div class="col-sm-3">
			  <label>Vendedor</label>
			  <select id="vendedor" class="form-control">
				<option value="0">-Selecciona Vendedor-</option>
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


				?>
			  </select>
			</div>
		  </div>
		</div><!-- Fin del tab de comisiones  -->
		<div id="datosEnvios" class="tab-pane fade">
		  <div class="row">
		   <div class="col-sm-4">
			  <label>Domicilio de Envíos</label>
			  <textarea id="enviosDom" cols="30" rows="5" class="form-control"><?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['envios'];}?></textarea>
		   </div>
		  </div>
		</div><!-- Fin del tab de envios-->

		<div id="datosContables" class="tab-pane fade">
			<br>
		  <div class="row">
		  	<div class="col-sm-3">
			  <label>Cuenta Contable</label>
				  <select id="cuentaCont" class="form-control">
						<option value="0">-Selecciona Cuenta-</option>
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
				<div class="col-sm-3" id="preopolizas_provision_container">
					<label for="prepolizas_provision">Prepoliza de provision:</label>
					<select id="prepolizas_provision" class="form-control">
						<option value="0">Seleccione una prepoliza.</option>
						<?php
							while($prepoliza_pr = $prepolizas_prov->fetch_assoc()){
								$id     = $prepoliza_pr['id'];
								$nombre = $prepoliza_pr['nombre'];
								$selected = '';
								if($datosCliente['basicos'][0]['id_prepoliza'] == $id)
									$selected = 'selected';
								echo("<option value='$id' $selected>$nombre</option>");
							}
						?>
					</select>
				</div>
				<div class="col-sm-3" id="preopolizas_pago_container">
					<label for="prepolizas_pago">Prepoliza de pago:</label>
					<select id="prepolizas_pago" class="form-control">
						<option value="0">Seleccione una prepoliza.</option>
						<?php
							while($prepoliza_pa = $prepolizas_pago->fetch_assoc()){
								$id     = $prepoliza_pa['id'];
								$nombre = $prepoliza_pa['nombre'];

								$selected = '';
								if($datosCliente['basicos'][0]['id_prepoliza_pagos'] == $id)
									$selected = 'selected';
								echo("<option value='$id' $selected>$nombre</option>");
							}
						?>
					</select>
				</div>
				<div class="col-sm-3" id="cuentas_gastos_container">
					<label for="cuentas_gastos">Seleccione la cuenta de gasto</label>
					<select id="cuentas_gastos" class="form-control">
						<option value="0">Seleccione una cuenta de gasto.</option>
						<?php 
						if(intval($cuentasGastos->num_rows))
						{
							while ($cuenta_gasto = $cuentasGastos->fetch_assoc()) 
							{
								$id     = $cuenta_gasto['id'];
								$nombre = $cuenta_gasto['nombre'];

								$selected = '';
								if($datosCliente['basicos'][0]['id_cuenta_gasto'] == $id)
									$selected = 'selected';

								echo("<option value='$id' $selected>$nombre</option>");
							}
						}
							
						?>
					</select>
				</div>
		  </div>
		</div><!-- Fin del tab Datos Contable-->
		<div id="accesoPortal" class="tab-pane fade">
		  <div class="row">
		  <div class="col-sm-12" style="margin-top: 20px;">
			   <div class="col-sm-2">
					<b>Correo:</b>
			   </div>  
			   <div class="col-sm-10">
					<input style="width:300px;" id="correoportal" class="form-control" type="text" value="<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['email'];}?>" readonly>
			   </div>

		   </div>
		   <div class="col-sm-12" style="margin-top: 10px;">
			   <div class="col-sm-2">
					<b>Usuario:</b>
			   </div>  
			   <div class="col-sm-10">
					<input style="width:300px;" id="userportal" class="form-control" type="text" value="usuarioCliente_<?php
						if(isset($datosCliente)){echo $datosCliente['basicos'][0]['id'];}?>" readonly>
			   </div>

		   </div>
		   <div class="col-sm-12" style="margin-top: 10px;">
			   <div class="col-sm-2">
					<b>Contraseña:</b>
			   </div>  
			   <div class="col-sm-10">
					<input style="width:300px;" id="passportal" class="form-control" type="password" value="<?php echo randpass(); ?>" readonly>
			   </div>
			   
		   </div>
		   <div class="col-sm-12" style="margin-top: 10px;">
				<div class="col-sm-2">
				&nbsp;
			   </div> 
			   <div class="col-sm-10">
					<button id="btnenviarCorreo" txt-original='Enviar correo' txt-click='Enviando correo' type="button" class="btn btn-default" onclick="enviarCorreoPortal();">Enviar correo</button>
			   </div>  
			   
		   </div>
		  </div>
		</div><!-- Fin del tab accesoPortal-->
	  </div>  <!-- Fin del div de los tabs -->
  </div><!-- fin de contenedor overflow -->
  </div> <!-- Fin del Panel Body -->
  </div>
</div>
  <!--          Molda Success           -->
  <div id="modalSuccess" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content panel-success">
			<div class="modal-header panel-heading">
				<h4 id="modal-label">Exito!</h4>
			</div>
			<div class="modal-body">
				<p>Tu Cliente se guardo existosamente</p>
			</div>
			<div class="modal-footer">
				<button id="modal-btnconf2-uno" type="button" class="btn btn-default" onclick="back();">Continuar</button>
			</div>
		</div>
	</div>
  </div>
</body>
</html>
