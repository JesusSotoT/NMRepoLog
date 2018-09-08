<html lang="en">
	<head>
		<meta charset="UTF-8">
<!-- ///////////////// ******** ---- 		CSS		------ ************ ////////////////// -->

	<!-- jquery-ui -->
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- Select con buscador  -->
		<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />

	<!-- Sistema -->
		<!-- <link rel="stylesheet" type="text/css" href="css/reset.css"> -->
		<!-- <link rel="stylesheet" type="text/css" href="css/pedidos/pedidos.css"> -->

<!-- ///////////////// ******** ---- 			FIN CSS		------ ************ ////////////////// -->

<!-- ///////////////// ******** ---- 			JS			------ ************ ////////////////// -->

	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- jquery.scrollTo.js -->
		<script type="text/javascript" src="js/jquery.scrollTo.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>

	<!-- ** Sistema -->
		<script type="text/javascript" src="js/pedidos/pedidos.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				pedidos.autocompleteProductos();

				pedidos.listar_ajustes();

				$('#btnAsignar').bind('click', function() {
					pedidos.asignaridPropina();
				});
			});
			function click_save_h(){
				console.log("submit");
	            var formData = new FormData(document.getElementById("myFormLogo"));
	            formData.append("dato", "valor");
	            //formData.append(f.attr("name"), $(this)[0].files[0]);
	            $.ajax({
	                url: "ajax.php?c=comandas&f=uploadfileLogoEmpresa",
	                type: "post",
	                dataType: "json",
	                data: formData,
	                cache: false,
	                contentType: false,
	       			processData: false
	            })
	                .done(function(res){
	                  	console.log(res);
	                 	var $mensaje = 'Configuración de hibrido guardado con exito';
						$.notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'success',
						});
	                });
	        }
		</script>
		
<!-- ///////////////// ******** ---- 			FIN JS			------ ************ ////////////////// -->
	</head>
	<body>
		<br />
		<div class="col-md-12 container-fluid" >
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Operacion / Ticket</h4>
				</div>
				<div class="panel-body">
					<div class="row">
						<!-- <div class="form-group col-md-4">
						<label class="sr-only col-md-2" for="txtPropina">Producto Propina</label>
						<input
						type="text"
						class="input-sm col-md-9"
						id="txtPropina"
						placeholder="Busca el producto propina, que previamente diste de alta.">
						<input type="button" value="Asignar" id="btnAsignar" class="btn btn-success">
						</div> -->
						<div class="col-md-4">
							<div class="input-group">
								<div class="input-group-addon" id="text_tipo_operacion">
									Tipo Operación:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({tipo: $('#tipo_operacion').val()})"
								id="tipo_operacion"
								class="selectpicker">
									<option value="1">Terminar Pedidos Después de Pago</option>
									<option value="2">Mantener Pedidos Después de Pago</option>
									<option value="3">Comida rapida</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									<input
									onclick="pedidos.mostrar_consumo({mostrar:$('#check_consumo').prop('checked')})"
									type="checkbox"
									id="check_consumo"/>
								</div>
								<input
								id="text_consumo"
								type="text"
								disabled="disabled"
								class="form-control"
								value="Mostrar consumo">
							</div>
						</div>

						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Clave SAT:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({id_consumo_clave: $('#id_consumo_clave').val()})"
								id="id_consumo_clave"
								class="selectpicker">
									<?php 
										foreach ($clave_consumo as $key => $value) {
									?>
										<option value="<?php echo $value['id'] ?>" title=" <?php echo $value['clave'] ?>"><?php echo $value['servicio']?></option>
									<?php											
										}
									 ?>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Pedir contraseña:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({pedir_pass: $('#pedir_pass').val()})"
								id="pedir_pass"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Idioma:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({idioma: $('#idioma').val()})"
								id="idioma"
								class="selectpicker">
									<option value="1">Español</option>
									<option value="2">Ingles</option>
								</select>
							</div>
						</div>						
						
					</div>
					<div class="row" style="padding-top: 15px">
						<div class="col-md-4">
							<div class="input-group">
								<div class="input-group-addon">
									Imprimir pedido general:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({imprimir_pedido_general: $('#imprimir_pedido_general').val()})"
								id="imprimir_pedido_general"
								class="selectpicker">
									<option value="1">Si</option>
									<option selected value="2">No</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Mostrar comanda:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({mostrar_info_comanda: $('#mostrar_info_comanda').val()})"
								id="mostrar_info_comanda"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>						
						<div class="col-md-3">
							<div class="input-group">
								<div class="input-group-addon">
									Desglose Complementos y Extras:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({una_linea: $('#una_linea').val()})"
								id="una_linea"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>	

						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Mostrar dolares:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({mostrar_dolares: $('#mostrar_dolares').val()})"
								id="mostrar_dolares"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 container-fluid" >
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Configuración pedidos</h4>
				</div>
				<div class="panel-body">
					<blockquote style="font-size: 14px">
						<p>
							Elija cada cuantos minutos quiere que se muestre el color en pedidos en minutos
						</p>
					</blockquote>
					<div class="row">
						<div class="col-md-2">
							<div class="input-group" style="width: 100%">
								<div class="input-group-addon" style="width: 20%; padding: 0; margin:0;" id="text_tipo_operacion">
									<div style="width: 100%; min-height: 100%; height: 32px; background-color:#DCB435;">&nbsp;</div>
								</div>
								<input id="time_amarillo" min="0" style="width: 100%; text-align: center;" type="number" class="form-control">
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group" style="width: 100%">
								<div class="input-group-addon" style="width: 20%; padding: 0; margin:0;" id="text_tipo_operacion">
									<div style="width: 100%; min-height: 100%; height: 32px; background-color:#bf2334;">&nbsp;</div>
								</div>
								<input id="time_rojo" min="0" style="width: 100%; text-align: center;" type="number" class="form-control">
							</div>
						</div>
						<div class="col-md-2" style="float: right; text-align: right;">
							<button onclick="pedidos.actualizar_configuracion({time_amarillo: $('#time_amarillo').val(), time_rojo: $('#time_rojo').val()})" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 container-fluid" >
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4>Configuración Hibrido</h4>
				</div>
				<div class="panel-body">
					<blockquote style="font-size: 14px">
						<p>
							Elija la configuracion para la aplicacion hibrida.
						</p>
					</blockquote>
					<div class="row">
						<form id="myFormLogo"  method="post" enctype="multipart/form-data">
							<div class="col-md-10">
		                  			Logo: 
		                  			<div class="row">
					                    <div class="col-sm-6">
					                     <!-- <input type="hidden" id="imagen" name="imagen" value=""> -->
					                        <div style="padding-left:3%">
					                          <input type="file" size="40" name="myfile">
					                        </div>
					                    </div>
					                    </div>
				                
							</div>
							<div class="col-md-2" style="float: right; text-align: right;">
								<button type="button"  onclick="click_save_h()" class="btn btn-success btnMenu" id="btnimagen"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 container-fluid" >
			<div 
				class="panel panel-success" id="accordion_info_ticket">
				<div 
					class="panel-heading"
					data-toggle="collapse" 
					data-parent="#accordion_info_ticket" 
					href="#tab_info_ticket" 
					id="switch_info_ticket"
					style="cursor: pointer"
					onclick="pedidos.actualizar_configuracion({switch_info_ticket: $(this).attr('infoticket')})"
					infoticket="2"
					aria-controls="collapse">
					<div class="row">
						<div class="col-md-6">
							<h4><i class="fa fa-ticket"></i> Información ticket</h4>
						</div>
						<div class="col-md-6" id="txt_info_ticket" align="right" style="padding-top: 10px">
							Información ticket activada
						</div>
					</div>
				</div>
				<div
					id="tab_info_ticket" 
					class="panel-collapse collapse in panel-body" 
					role="tabpanel" 
					aria-labelledby="heading_info_ticket">
					<blockquote style="font-size: 14px">
						<p>
							¿Que información desea mostrar en el ticket?
						</p>
					</blockquote>
					<div class="row">
						<div class="col-md-4">
							<div class="input-group">
								<div class="input-group-addon">
									Mostrar información de la empresa:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({mostrar_info_empresa: $('#mostrar_info_empresa').val()})"
								id="mostrar_info_empresa"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Mostrar cliente:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({mostrar_nombre: $('#mostrar_nombre').val()})"
								id="mostrar_nombre"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Mostrar domicilio:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({mostrar_domicilio: $('#mostrar_domicilio').val()})"
								id="mostrar_domicilio"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Mostrar telefono:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({mostrar_tel: $('#mostrar_tel').val()})"
								id="mostrar_tel"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Mostrar IVA:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({mostrar_iva: $('#mostrar_iva').val()})"
								id="mostrar_iva"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12 container-fluid" >
			<div 
				class="panel panel-success" id="accordion_propina">
				<div 
					class="panel-heading"
					data-toggle="collapse" 
					data-parent="#accordion_propina" 
					href="#tab_propina" 
					id="switch_propina"
					style="cursor: pointer"
					onclick="pedidos.actualizar_configuracion({switch_propina: $(this).attr('propina')})"
					propina="2"
					aria-controls="collapse">
					<div class="row">
						<div class="col-md-6">
							<h4><i class="fa fa-money"></i> Propina</h4>
						</div>
						<div class="col-md-6" id="txt_propina" align="right" style="padding-top: 10px">
							Propina activada
						</div>
					</div>
				</div>
				<div
					id="tab_propina" 
					class="panel-collapse collapse in panel-body" 
					role="tabpanel" 
					aria-labelledby="heading_propina">
					<blockquote style="font-size: 14px">
						<p>
							<strong>SD</strong> - Servicio a domicilio, <strong>PLL</strong> - Para llevar, 
							<strong>CA</strong> -  Calculo automatico
						</p>
					</blockquote>
					<div class="row">
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									<input
									onclick="pedidos.mostrar_propina({mostrar:$('#check_propina').prop('checked')})"
									type="checkbox"
									id="check_propina"/>
								</div>
								<input
								id="text_propina"
								type="text"
								disabled="disabled"
								class="form-control"
								value="Mostrar propina">
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<span class="input-group-addon">CA <i class="fa fa-percent"></i></span>
								<input 
									id="calculo_automatico" 
									onchange="pedidos.actualizar_configuracion({calculo_automatico:  ' '+$(this).val()})"
									type="number" 
									min="0"
									class="form-control">
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Mostrar SD  y PLL:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({mostrar_sd: $('#mostrar_sd').val()})"
								id="mostrar_sd"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Aplicar a:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({aplicar_a: $('#aplicar_a').val()})"
								id="aplicar_a"
								class="selectpicker">
									<option value="1">Total</option>
									<option value="2">Sub total</option>
								</select>
							</div>
						</div>
						<div class="col-md-2">
							<div class="input-group">
								<div class="input-group-addon">
									Facturar propina:
								</div>
								<select
								onchange="pedidos.actualizar_configuracion({facturar_propina: $('#facturar_propina').val()})"
								id="facturar_propina"
								class="selectpicker">
									<option value="1">Si</option>
									<option value="2">No</option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>