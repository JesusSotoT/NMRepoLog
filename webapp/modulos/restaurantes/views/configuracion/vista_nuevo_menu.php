<?php
// Valida que existan menus o recetas
	if (!empty($menus)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se hay datos para mostrar *</span></h3>
		</div><?php
		
		return 0;
	} ?>

<div class="col-md-7"><?php
// Valida que existan menus
	if (empty($menus)) { ?>
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="panel-group" id="accordion_menus" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div hrefer class="panel-heading" id="heading_menus" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_menus" href="#tab_menus" aria-controls="collapse_menus" aria-expanded="true">
							<h4>
								<strong>Menus</strong>
							</h4>
						</div>
						<div id="tab_menus" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_menus">
							<div class="panel-body" id="arbol">
								<!-- En esta div se carga el arbol con los productos -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>
			configuracion.convertir_arbol({id: 'arbol', datos: <?php echo json_encode($datos) ?>});
		</script><?php
	} ?>
</div> <!-- Fin lado izquierdo -->
<div class="col-md-5">
	<div class="panel panel-<?php echo $panel ?>">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-8">
					<h4><strong> Estilo </strong></h4>
				</div>
				<div class="col-md-4" align="right">
					<button 
						id="btn_agregar_menu"
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						onclick="configuracion.imprimir_menu({
									btn:'btn_agregar_menu', 
									nombre:$('#nombre').val(), 
									nombre_restaurante:$('#nombre_restaurante').val(), 
									estilo:$('#estilo').val(),
									div:'div_imprimir_menu'
								})"
						class="btn btn-success btn-lg">
						<i class="fa fa-check"></i> Ok
					</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-12">
					<h3><small>Nombre del restaurante:</small></h3>
					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-font"></i></span>
						<input required="1" id="nombre_restaurante" type="text" class="form-control"/>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-7">
					<h3><small>Nombre del menu:</small></h3>
					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-font"></i></span>
						<input required="1" id="nombre" type="text" class="form-control"/>
					</div>
				</div>
				<div class="col-md-5">
					<h3><small>Estilo:</small></h3>
					<div class="input-group input-group-lg">
						<select 
							id="estilo"
							data-width="100%" 
							class="selectpicker" 
							onchange="$('#imagen_menu').prop('src', 'images/Menu_Digital/Menu_'+$(this).val()+'.jpg')">
							<option value="1">Alternativo</option>
							<option value="2">Clasico</option>
							<option value="3">Organico Vintage</option>
							<option value="4">Tradicional</option>
						</select>
					</div>
				</div>
			</div><br /> <br />
			<div class="row">
				<div class="col-md-12">
					<img id="imagen_menu" src="images/Menu_Digital/Menu_1.jpg" alt=" " class="img-responsive img-rounded img-thumbnail">
				</div>
			</div>
		</div>
	</div>
</div><!-- Fin lado derecho -->

<!-- Modal imprimir_menu-->
<div class="modal fade" id="modal_imprimir_menu" role="dialog" aria-labelledby="titulo_imprimir_menu">
	<div class="modal-dialog" role="document" style="width: 750px">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-11">
					<div class="btn-group" role="group" id="div_botones">
						<button 
							id="btn_imprimir_guardar"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							onclick="configuracion.agregar_menu({
										btn:'btn_imprimir_guardar', 
										imprimir:1,
										div_imprimir:'div_imprimir_menu',
										nombre:$('#nombre').val(), 
										nombre_restaurante:$('#nombre_restaurante').val(), 
										estilo:$('#estilo').val()
									})"
							class="btn btn-success btn-lg">
							<i class="fa fa-check"></i> Guardar e imprimir
						</button>
						<button 
							id="btn_imprimir_actualizar"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							style="display: none"
							onclick="configuracion.actualizar_menu({
										btn:'btn_imprimir_guardar', 
										imprimir:1,
										div_imprimir:'div_imprimir_menu',
										nombre:$('#nombre').val(), 
										nombre_restaurante:$('#nombre_restaurante').val(), 
										id_menu:$(this).attr('id_menu'), 
										estilo:$('#estilo').val()
									})"
							class="btn btn-primary btn-lg">
							<i class="fa fa-check"></i> Actualizar e imprimir
						</button>
						<button 
							id="btn_imprimir"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							onclick="$('#div_imprimir_menu').printArea();"
							class="btn btn-default btn-lg">
							<i class="fa fa-print"></i> Imprimir
						</button>
						<button 
							id="btn_guardar_menu"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							onclick="configuracion.agregar_menu({
										btn:'btn_guardar_menu',
										nombre:$('#nombre').val(), 
										nombre_restaurante:$('#nombre_restaurante').val(), 
										estilo:$('#estilo').val()
									})"
							class="btn btn-default btn-lg">
							<i class="fa fa-save"></i> Guardar
						</button>
						<button 
							id="btn_actualizar"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							style="display: none"
							onclick="configuracion.actualizar_menu({
										btn:'btn_guardar',
										nombre:$('#nombre').val(), 
										nombre_restaurante:$('#nombre_restaurante').val(), 
										id_menu:$(this).attr('id_menu'), 
										estilo:$('#estilo').val()
									})"
							class="btn btn-default btn-lg">
							<i class="fa fa-save"></i> Actualizar
						</button>
					</div>
				</div>
				<div class="col-md-1" align="right">
					<button 
						id="btn_cerrar_imprimir_menu" 
						type="button" 
						class="close" 
						data-dismiss="modal" 
						aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			</div>
			<div class="modal-body" id="div_imprimir_menu">
				<!-- En esta div se carga la vista del imprimir_menu de caja -->
			</div>
		</div>
	</div>
</div>
<!-- FIN Modal imprimir_menu -->