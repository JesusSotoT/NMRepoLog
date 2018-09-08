<!-- Modal Cliente no existe-->
	<div class="modal fade" id="modal_cliente" tabindex="-1" role="dialog" aria-labelledby="add_cliente">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal_cliente" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="add_cliente">Registro de cliente</h4>
				</div>
				<form id="modal_cliente">
					<div class="modal-body">
						<h5><strong>Los campos con * son obligatorios</strong></h5>
						<!-- Nombre y direccion -->
						<div class="row">
							<!-- Nombre -->
							<div class="col-md-6">
								<div class="input-group">
									<label>* Nombre: </label>
									<input id="nombre" required="1" type="text" class="form-control" placeholder="Pedro paramo">
								</div>
							</div>
	
							<!-- Direccion -->
							<div class="col-md-6">
								<div class="input-group">
									<label>* Direccion: </label>
									<input id="direccion" required="1" type="text" class="form-control" placeholder="Algun lugar">
								</div>
							</div>
						</div>
						<br />
	
						<!-- Numero interior y exterior -->
						<div class="row">
							<!-- Num. int. -->
							<div class="col-md-6">
								<div class="input-group">
									<label>Num. int.: </label>
									<input id="num_int" type="number" class="form-control" placeholder="0000">
								</div>
							</div>
	
							<!-- Num. Ext. -->
							<div class="col-md-6">
								<div class="input-group">
									<label>Num. Ext.: </label>
									<input id="num_ext" required="1" type="number" class="form-control" placeholder="0000">
								</div>
							</div>
						</div>
						<br />
	
						<!-- Colonia y codigo postal -->
						<div class="row">
							<!-- Colonia -->
							<div class="col-md-6">
								<div class="input-group">
									<label>Colonia: </label>
									<input id="colonia" required="1" type="text" class="form-control" placeholder="Colonia">
								</div>
							</div>
	
							<!-- Codigo postal -->
							<div class="col-md-6">
								<div class="input-group">
									<label>CP: </label>
									<input id="cp" required="1" type="number" maxlength="5" max="99999" class="form-control" placeholder="00000">
								</div>
							</div>
						</div>
						<br />
	
						<!-- Estados y Municipios -->
						<div class="row">
							<!-- Estados -->
							<div class="col-md-6">
								<div class="input-group">
									<label>Estado: </label>
									<select required="1" style="width: 100px" id="estado" onchange="info_municipios({id_estado: $('#estado').val()})">
										<option value="">-- Seleccionar --</option>
									</select>
								</div>
							</div>
	
							<!-- Municipios -->
							<div class="col-md-6">
								<div class="input-group">
									<label>Municipio: </label>
									<select required="1" style="width: 100px" id="municipio">
										<option value="">-- Seleccionar --</option>
									</select>
								</div>
							</div>
	
							<!-- Cambiamos los select por select con buscador -->
							<script type="text/javascript">
								$objeto = [];
	
								// Creamos un arreglo con los id de los select
								$objeto[0] = 'municipio';
								$objeto[1] = 'estado';
	
								// Mandamos llamar la funcion que crea el buscador
								select_buscador($objeto);
							</script>
						</div>
						<br />
	
						<!-- E-mail y Telefono -->
						<div class="row">
							<!-- E-mail -->
							<div class="col-md-6">
								<div class="input-group">
									<label>E-mail: </label>
									<input id="mail" type="email" class="form-control" placeholder="ejemplo@ejem.com">
								</div>
							</div>
	
							<!-- Telefono -->
							<div class="col-md-6">
								<div class="input-group">
									<label>Telefono: </label>
									<input id="tel" type="tel" class="form-control" placeholder="0123456789">
								</div>
							</div>
						</div>
						<br />
					</div>
	
					<!-- Botones -->
					<div class="modal-footer">
						<button id="cerrar_modal" type="button" class="btn btn-default" data-dismiss="modal">
							Cerrar
						</button>
						<button type="button" class="btn btn-primary"  onclick="agregar_cliente({formulario: 'datos_cliente'})">
							Guardar
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<!-- FIN Modal Cliente no existe-->