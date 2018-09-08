<?php
// Valida si se deben de ocultar los botones
$style = ($configuraciones['tipo_operacion'] == 3) ? ' display: none;' : '' ; ?>

<!-- Div pagar -->
<div 
	style=" z-index:100; position:absolute; width:98%; height:98%; background:#ffffff;opacity:0.92;filter:alpha(opacity=92);visibility:hidden" 
	align="center" 
	class="GtableCloseComanda">
	<div style="background:#A4A4A4;width:70%;border-radius:10px;padding:5px 0px">
		<div style="width:98%;height:30px" align="right">
			<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
				<div 
					onclick="$('.GtableCloseComanda').css('visibility', 'hidden');"
					style="width:30px;height:30px;font-weight:600;border-radius:20px;background:#424242;font-size:23px;color:#ffffff" 
					align="center" 
					class="btnClose">
					x
				</div>
			</a>
		</div>
		<div style="font-size:16px;font-family:verdana;font-weight:600;color:#ffffff;width:80%">
			Select the type of account to be made
		</div>
		<div style="margin-top:20px; padding:0% 5%" class="row">
			<div class="col-md-2">
				<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
					<div 
						style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" 
						align="center"
						onclick="comandera.cerrar_comanda({
							bandera: 1,
							nombre: comandera['datos_mesa_comanda']['nombre'],
							idComanda: comandera['datos_mesa_comanda']['id_comanda'],
							tel: comandera['datos_mesa_comanda']['tel'],
							idmesa: comandera['datos_mesa_comanda']['id_mesa'],
							tipo: comandera['datos_mesa_comanda']['tipo'],
							ids_mesas: comandera['datos_mesa_comanda']['ids_mesas'],
							tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion'],
							personas: comandera.datos_mesa_comanda['num_personas'],
							mesero: comandera.datos_mesa_comanda['mesero'],
							f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp']
						})">
						<div style="padding-top:10px">Individual</div>
					</div>
				</a>
			</div>
			<div class="col-md-2">
				<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
					<div 
						style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" 
						align="center" 
						onclick="comandera.cerrar_comanda({
							bandera: 0,
							nombre: comandera['datos_mesa_comanda']['nombre'],
							idComanda: comandera['datos_mesa_comanda']['id_comanda'],
							tel: comandera['datos_mesa_comanda']['tel'],
							idmesa: comandera['datos_mesa_comanda']['id_mesa'],
							ids_mesas: comandera['datos_mesa_comanda']['ids_mesas'],
							tipo: comandera['datos_mesa_comanda']['tipo'],
							tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion'],
							personas: comandera.datos_mesa_comanda['num_personas'],
							mesero: comandera.datos_mesa_comanda['mesero'],
							f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp']
						})">
						<div style="padding-top:10px">All together</div>
					</div>
				</a>
			</div><?php
			
		// Valida que tenga permiso para pagar directo de caja
			$permiso_pagar = in_array(2156, $_SESSION["accelog_menus"]);
			if (!empty($permiso_pagar)) { ?>
				<div class="col-md-2">
					<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
						<div 
							style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" 
							align="center" 
							onclick="comandera.cerrar_comanda({
								bandera: 2,
								pedir: 1,
								nombre: comandera['datos_mesa_comanda']['nombre'],
								idComanda: comandera['datos_mesa_comanda']['id_comanda'],
								tel: comandera['datos_mesa_comanda']['tel'],
								idmesa: comandera['datos_mesa_comanda']['id_mesa'],
								ids_mesas: comandera['datos_mesa_comanda']['ids_mesas'],
								tipo: comandera['datos_mesa_comanda']['tipo'],
								tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
								id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
								num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
								tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion'],
								mesero: comandera.datos_mesa_comanda['mesero'],
								f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp']
							})">
							<div style="padding-top:10px">Pay</div>
						</div>
					</a>
				</div><?php
			} ?>
		
			<div class="col-md-2">
				<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
					<div 
						style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" 
						align="center" 
						onclick="comandera.cerrar_comanda({
							bandera: 3,
							nombre: comandera['datos_mesa_comanda']['nombre'],
							idComanda: comandera['datos_mesa_comanda']['id_comanda'],
							tel: comandera['datos_mesa_comanda']['tel'],
							tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
							idmesa: comandera['datos_mesa_comanda']['id_mesa'],
							ids_mesas: comandera['datos_mesa_comanda']['ids_mesas'],
							tipo: comandera['datos_mesa_comanda']['tipo'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']
						})">
						<div style="padding-top:10px">Send to box</div>
					</div>
				</a>
			</div>
			<div 
				class="col-md-2" 
				onclick="comandera.cerrar_personalizado({
							servicio: comandera['datos_mesa_comanda']['tipo'],
							nombre: comandera['datos_mesa_comanda']['nombre'],
							tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
							direccion: comandera['datos_mesa_comanda']['direccion'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							idComanda: comandera['datos_mesa_comanda']['id_comanda'],
							idmesa: comandera['datos_mesa_comanda']['id_mesa'],
							tipo: comandera['datos_mesa_comanda']['tipo'],
							div:'contenedor_personalizar'
						})"
				data-toggle="modal" 
				data-target="#div_personalizar">
				<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
					<div 
						style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" 
						align="center">
						<div style="padding-top:10px">Divide</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</div><!-- Fin div pagar -->
<div class="row"><!-- Departamentos, familias y lineas -->
	<div class="colo-md-4 col-xs-4">
		<div class="row">
			<div class="col-md-3 col-xs-3">
				<div class="input-group  has-success" style="width: 110px;">
					<input type="number" class="form-control" id="tiempo" value="1">
					<span class="input-group-btn">
						<button
							onclick="comandera.actualizar_tiempo_pedidos({
								btn: 'btn_tiempo',
								id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
								tiempo: $('#tiempo').val()
							})"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							type="button" 
							class="btn"
							id="btn_tiempo"
							style="background-color: #209775; color: white">
							<i class="fa fa-plus"></i> <i class="fa fa-clock-o"></i>
						</button> 
					</span>
				</div>
			</div>
			<div class="col-md-3 col-xs-3">
				<div class="btn-group" role="group" aria-label="...">
					<button
						data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
						id="btn_cerrar_persona"
						type="button"
						class="btn"
						onclick="comandera.cerrar_comanda_persona({
							btn: 'btn_cerrar_persona',
							id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
							id_mesa: comandera['datos_mesa_comanda']['id_mesa'],
							persona: comandera['datos_mesa_comanda']['persona_seleccionada'],
							personas: comandera.datos_mesa_comanda['num_personas'],
							mesero: comandera.datos_mesa_comanda['mesero'],
							f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp']
						})"
						style="background-color: #CEA42F; color: #714789">
						<i class="fa fa-credit-card"></i> <kbd id="text_cerrar_persona" style="background-color: #CEA42F; color: #714789">0</kbd>
					</button>
				</div>
			</div>
			<div class="col-md-3 col-xs-3">
				<button 
					onclick="comandera.agregar_persona_comandera({
						num_personas: comandera['datos_mesa_comanda']['num_personas'],
						id_comanda: comandera['datos_mesa_comanda']['id_comanda']
					})" 
					type="button" 
					class="btn btn-default ">
					<i class="fa fa-plus" style="color: #2C2146"></i> <i class="fa fa-pencil-square-o" style="color: #763F8B"></i>
				</button>
			</div>
			<div class="col-md-3 col-xs-3">
				<div class="input-group  has-error" style="width: 110px">
					<input type="number" class="form-control" id="borrar_persona">
					<span class="input-group-btn">
						<button 
							type="button" 
							class="btn" 
							data-toggle="modal" 
							data-target="#modal_eliminar_persona"
							 style="background-color: #D6872D">
							<i class="fa fa-minus"></i> <i class="fa fa-pencil-square-o"></i>
						</button>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-4 col-xs-4">
		<div class="btn-group" role="group" aria-label="...">
			<button
				onclick="comandera.productos = ''; comandera.area_inicio()"
				class="btn"
				style="background-color: #209775; color: white;">
				<i class="fa fa-cubes fa-lg"></i> Products
			</button>
			<button
				onclick="comandera.listar_combos({
					div: 'div_productos',
					tipo: 7,
					persona: comandera.datos_mesa_comanda['persona_seleccionada'],
					comanda: comandera['datos_mesa_comanda']['id_comanda']
				})"
				class="btn"
				style="background-color: #DCB435; color: #1C1443">
				<i class="fa fa-braille fa-lg" style="color: #763F8B"></i> Combos
			</button>
			<button
				onclick="comandera.listar_complementos({
					div: 'div_productos',
					comanda: comandera['datos_mesa_comanda']['id_comanda'],
					pedido: comandera['datos_mesa_comanda']['pedido_seleccionado']
				})"
				class="btn"
				style="background-color: #714789; color: #DCB435">
				<i class="fa fa-plus-circle fa-lg" style="color: #DCB435"></i> Accessories
			</button>
		</div>
	</div>
	<div class="col-md-4 col-xs-4"><!-- Buscador  -->
		<div class="input-group">
			<input onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.buscar_productos({texto: $('#texto').val(), comanda: comandera['datos_mesa_comanda']['id_comanda'], div:'div_productos'})" type="search" id="texto" class="form-control" placeholder="pasta, cut, breakfast, omelet, etc.">
			<span class="input-group-btn">
				<button
					onclick="comandas.buscar_productos({
						texto: $('#texto').val(),
						comanda: comandera['datos_mesa_comanda']['id_comanda'],
						div: 'div_productos'
					})"
					class="btn btn-default"
					type="button">&nbsp;
					<i class="fa fa-search"></i>
				</button> 
			</span>
		</div>
	</div><!-- FIN Buscador -->
</div><br />
<!-- FIN Departamentos, familias y lineas -->

<!-- Abajo -->
<div class="row">
	<div class="col-md-4 col-xs-4">
		<div class="row">
			<div id="div_personas" class="col-md-12 col-xs-12">
				<!-- En esta div se cargan las personas de la comanda -->
			</div>
		</div>
		<div class="row">
			<div id="div_listar_pedidos_persona" class="col-md-12 col-xs-12" style="height: 240px; overflow: scroll;">
				<!-- En esta div se cargan los pedidos las personas -->
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<button
					type="button"
					class="btn btn-lg btn-block"
					onclick="comandera.pedir({
						id_comanda: comandera['datos_mesa_comanda']['id_comanda']
					})"
					style="background-color: #209775; color:white; margin-top: 1%; <?php echo $style ?>">
					<i class="fa fa-paper-plane-o"></i> Ask
				</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<button 
					onclick="comandera.validar_cuenta({id_comanda: comandera['datos_mesa_comanda']['id_comanda']})"
					type="button" 
					class="btn btn-lg btn-block btnEnd" 
					idcomanda="comandera['datos_mesa_comanda']['id_comanda']" 
					style="background-color: #CEA42F; color: #714789; margin-top: 1%;"
					tipo="<?php echo $tipo ?>" 
					repa="<?php echo $repa ?>">
					<i class="fa fa-credit-card"></i> Account
				</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<div class="panel-group" id="accordion_funciones_comandera" role="tablist" aria-multiselectable="true" style="padding-top: 2%;">
					<div class="panel">
						<div
							class="panel-heading"
							id="heading_funciones_comandera"
							role="tab"
							role="button"
							style="cursor: pointer; background-color: #714789; color: #CEA42F;"
							data-toggle="collapse"
							data-parent="#accordion_funciones_comandera"
							href="#tab_funciones_comandera"
							aria-controls="collapse"
							aria-expanded="true"
							align="center">
							<h4><strong><i class="fa fa-wrench"></i> Functions</strong></h4>
						</div>
						<div
							id="tab_funciones_comandera"
							class="panel-collapse collapse"
							role="tabpanel"
							aria-labelledby="heading_funciones_comandera">
							<div class="panel-body">
								<button 
									type="button" 
									class="btn" 
									style="background-color: #714789; color: #CEA42F; width: 130px; margin-top: 1%; <?php echo $style ?>" 
									data-toggle="modal" 
									data-target="#modal_autorizar">
									<i class="fa fa-pencil"></i> To assign
								</button>
								<button 
									type="button" 
									class="btn"
									onclick="comandera.vista_mudar_mesa({
														div: 'div_mudar_mesa',
														id_mesa: comandera['datos_mesa_comanda']['id_mesa']
													})"
									style="background-color: #209775; color:white; width: 130px; margin-top: 1%; <?php echo $style ?>" 
									data-toggle="modal" 
									data-target="#div_mudar" 
									idcomanda="comandera['datos_mesa_comanda']['id_comanda']">
									<i class="fa fa-exchange"></i> Move
								</button>
								<button 
									type="button" 
									class="btn" 
									style="background-color: #CEA42F; color: #714789; width: 130px; margin-top: 1%"
									onclick="comandera.cerrar_comanda({
										bandera: 0,
										reimprime: 1,
										nombre: comandera['datos_mesa_comanda']['nombre'],
										idComanda: comandera['datos_mesa_comanda']['id_comanda'],
										tel: comandera['datos_mesa_comanda']['tel'],
										idmesa: comandera['datos_mesa_comanda']['id_mesa'],
										ids_mesas: comandera['datos_mesa_comanda']['ids_mesas'],
										tipo: comandera['datos_mesa_comanda']['tipo'],
										id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
										num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
										tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion'],
										personas: comandera.datos_mesa_comanda['num_personas'],
										mesero: comandera.datos_mesa_comanda['mesero'],
										f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp']
									})">
									<i class="fa fa-search"></i> See
								</button><?php 
							
							// Valida que exista la reservacion
								$id_reservacion = (empty($id_reservacion)) ? 0 : $id_reservacion ; ?>
								<button 
									type="button" 
									class="btn" 
									style="background-color: #D6872D; color:white; width: 130px; margin-top: 1%; <?php echo $style ?>" 
									data-toggle="modal" 
									data-target="#modal_eliminar_comanda">
									<i class="fa fa-trash"></i> Remove
								</button><?php
									
								if (!empty($mesas_juntas)) { ?>
									<button 
										type="button" 
										class="btn btn-default " 
										style="width: 130px; margin-top: 1%" 
										data-toggle="modal" 
										data-target="#div_separar" 
										mesas_juntas="<?php echo $mesas_juntas ?>">
										<i class="fa fa-arrows-h"></i> Pull apart
									</button><?php
								} ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-8 col-xs-8">
		<div id="div_productos" style="overflow: scroll; height: 500px"><?php
			foreach($products['rows'] as $value){
			// Comprueba si es platillo especial
				$clase = (!empty($value['especial'])) ? 'info' : 'default' ; ?>
		
				<button
					onclick="comandera.detalles_producto({
						div : 'div_productos',
						id_producto : <?php echo $value['idProducto'] ?>,
						id_comanda : comandera['datos_mesa_comanda']['id_comanda'],
						materiales : <?php echo $value['materiales'] ?>,
						tipo : '<?php echo $value['tipo'] ?>',
						departamento : '<?php echo $value['idDep'] ?>',
						persona : comandera.datos_mesa_comanda['persona_seleccionada']
					})"
					class="btn btn-<?php echo $clase ?>" >    
					<div class="row">       
						<div style="width:100px;" class="wrapPro">          
							<label><?php echo substr($value['nombre'], 0, 10)  ?></label>       
						</div>    
					</div>    
					<div class="row">      
						<div>          
							<img 
								type="image" 
								alt=" " 
								style="width:80px; height:80px" 
								src="<?php echo $value['imagen'] ?>">      
						</div>    
					</div>    
					<div class="row">      
						<label>$ <?php echo $value['precioventa'] ?></label>    
					</div>  
				</button><?php
			} ?>
		</div>
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<button 
					id="btn_cargar_productos"
					class="btn btn-lg btn-departamento" 
					style="width: 95%" 
					data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
					onclick="comandas.buscar_productos({
						div: 'div_productos',
						btn: 'btn_cargar_productos',
						limite: $('#limite').val(), 
						vista: 'cargar_productos', 
						comanda: comandera['datos_mesa_comanda']['id_comanda']
					})">
					<i class="fa fa-undo"></i> Load more products
				</button>
			</div>
			<input type="number" id="limite" value="100" style="display: none" />
		</div>
	</div>
</div><!-- Fin Abajo -->

<!-- =====================================				Modales				============================================== -->

<!-- Modal Autorizar asignacion -->
<div id="modal_autorizar" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" onclick="$('#modal_autorizar').click()">
					&times;
				</button>
				<h4 class="modal-title">Allow assignment</h4>
			</div>
			<div class="modal-body">
				<h3><small>Enter password:</small></h3>
				<div class="input-group input-group-lg">
					<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
					<input id="pass_asignacion" type="password" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) autoriza_asignacion({pass:$('#pass_asignacion').val()})" class="form-control">
					<span class="input-group-btn">
						<button 
							onclick="comandera.autoriza_asignacion({
									pass: $('#pass_asignacion').val()
							})" 
							class="btn" 
							style="background-color: #209775; color:white;"
							type="button">
							<i class="fa fa-check"></i> Authorize
						</button> </span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIN Modal Autorizar asignacion -->

<!-- Modal asignar -->
<div class="modal fade" id="modal_asignar" tabindex="-1" role="dialog" aria-labelledby="titulo_asignar">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" onclick="$('#modal_asignar').click()"  type="button" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="titulo_asignar" align="left">Assign table</h4>
			</div>
		<!-- Mensaje -->
			<div class="modal-body">
				<div align="left">
					<blockquote style="font-size: 14px">
						<p>
							Select the <strong> waiter </strong> to <strong> Assign </strong> the table.

						</p>
					</blockquote>
				</div>
				<div align="center" id="mesas_libres" style="overflow: scroll;height: 55%"><?php
					foreach ($empleados as $key => $value) { ?>
						<div class="pull-left" style="padding:5px">
							<button 
								type="button" 
								class="btn btn-default btn-lg"  
								onclick="comandera.asignar_mesa({
										empleado:<?php echo $value['id'] ?>,
										mesa: comandera['datos_mesa_comanda']['id_mesa']
								})" 
								style="width: 110px;">
								<i class="fa fa-user"></i> <br>
								<?php echo substr($value['usuario'], 0, 9); ?>
							</button>
						</div><?php
					} ?>
				</div>
			</div>
		<!-- Cancelar -->
			<div class="modal-footer">
				<button type="button" class="btn" style="background-color: #D6872D; color:white;" onclick="$('#modal_asignar').click()">
					Cancel
				</button>
			</div>
		</div>
	</div>
</div>
<!-- FIN Modal asignar -->

<!-- Ventana modal mudar comanda -->	
<div class="modal fade" id="div_mudar" tabindex="-1" role="dialog" aria-labelledby="titulo_mudar">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close"  onclick="('#div_mudar').modal('toggle');" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="titulo_separar" align="left">Change command</h4>
			</div>
		<!-- Mensaje -->
			<div class="modal-body">
				<div align="left">
					<blockquote style="font-size: 14px">
						<p>
							Select the <strong> Table </strong> .This <strong> action will separate </strong> The tables and move the orders to the table you select.
						</p>
					</blockquote>
				</div>
				<div class="row">
	      			<div class="col-md-12 col-xs-12" id="div_mudar_mesa">
	     				<!-- En esta div se cargan las mesas -->
	      			</div>
	      		</div>
			</div>
		<!-- Cancelar -->
		<div class="modal-footer">
				<button type="button" class="btn btn-danger" onclick="$('#div_mudar').modal('toggle');">
					Cancel
				</button>
			</div>
		</div>
	</div>
</div>
<!-- FIN Ventana modal mudar comanda -->

<!-- Modal Autorizar asignacion -->
<div id="modal_autorizar" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" onclick="$('#modal_autorizar').click()">
					&times;
				</button>
				<h4 class="modal-title">Allow assignment</h4>
			</div>
			<div class="modal-body">
				<h3><small>Enter password:</small></h3>
				<div class="input-group input-group-lg">
					<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
					<input id="pass_asignacion" type="password" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) autoriza_asignacion({pass:$('#pass_asignacion').val()})" class="form-control">
					<span class="input-group-btn">
						<button 
							onclick="comandera.autoriza_asignacion({
									pass: $('#pass_asignacion').val()
							})" 
							class="btn btn-success" 
							style="background-color: #209775; color:white;"
							type="button">
							<i class="fa fa-check"></i> Authorize
						</button> 
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIN Modal Autorizar asignacion -->

<!-- Modal eliminar comanda -->
<div id="modal_eliminar_comanda" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" >
					&times;
				</button>
				<h4 class="modal-title">Remove comands</h4>
			</div>
			<div class="modal-body">
				<h3><small>Enter password:</small></h3>
				<div class="input-group">
					<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
					<input
					onkeypress="(((document.all) ? event.keyCode : event.which)==13) eliminar_comanda({pass: $(this).val(), comanda: comandera['datos_mesa_comanda']['id_comanda'], reservacion: comandera['datos_mesa_comanda']['id_reservacion'], tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']})"
					id="pass_eliminar_comanda"
					type="password"
					class="form-control">
					<span class="input-group-btn">
						<button
							onclick="comandera.eliminar_comanda({
								pass: $('#pass_eliminar_comanda').val(),
								idcomanda: comandera['datos_mesa_comanda']['id_comanda'],
								tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
								idmesa: comandera['datos_mesa_comanda']['id_mesa'],
								ids_mesas: comandera['datos_mesa_comanda']['ids_mesas'],
								tipo: comandera['datos_mesa_comanda']['tipo'],
								id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
								tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']
							})"
							class="btn"
							type="button"
							style="background-color: #D6872D; color:white;">
							<i class="fa fa-trash"></i> Remove
						</button> 
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIN Modal eliminar comanda -->
	
<!-- Ventana modal pagar personalizado -->
<div class="modal fade" id="div_personalizar" tabindex="-1" role="dialog" aria-labelledby="titulo_personalizar">
	<div class="modal-dialog modal-lg" style="width: 100%" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button id="cerrar_modal_personalizar" type="button" class="close" onclick="$('#div_personalizar').click()">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="titulo_personalizar" align="left">Close comand</h4>
			</div>
		<!-- Contenedor -->
			<div class="modal-body" id="contenedor_personalizar">
				<!-- Esta div se llena con la interfaz de cerrar comanda personalizado -->
			</div>
		<!-- Botones pagar personalizado-->
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-7" id="div_extras">
						<!-- En esta div aaparecen los productos extra -->
					</div>
					<div class="col-md-5" align="right">
						<button 
							id="btn_personalizado_ok" 
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
							autocomplete="off" 
							type="button" 
							class="btn btn-lg" 
							style="background-color: #209775; color:white;"
							onclick="comandas.guardar_comanda_parcial({
									persona: comandera['datos_mesa_comanda']['persona_seleccionada'], 
									idpadre: comandera['datos_mesa_comanda']['id_comanda'],
									mesa: comandera['datos_mesa_comanda']['id_mesa'],
									tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
							})">
							<i class="fa fa-check"></i> Ok
						</button>
						<button type="button" class="btn btn-lg" onclick="$('#div_personalizar').click()" style="background-color: #D6872D; color:white;">
							<i class="fa fa-ban"></i> Cancel
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIN Ventana modal pagar personalizado -->

<!-- Modal eliminar persona -->
<div id="modal_eliminar_persona" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick="$('#modal_eliminar_persona').click()">
					&times;
				</button>
				<h4 class="modal-title">Remove people</h4>
			</div>
			<div class="modal-body">
				<h3><small>Enter password:</small></h3>
				<div class="input-group">
					<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
					<input
					onkeypress="(((document.all) ? event.keyCode : event.which)==13) comandera.borrar_persona({pass: $('#pass_eliminar_persona').val(), id_comanda: comandera['datos_mesa_comanda']['id_comanda'], persona: $('#borrar_persona').val(), btn: 'btn_borrar_persona'})"
					id="pass_eliminar_persona"
					type="password"
					class="form-control">
					<span class="input-group-btn">
						<button
							onclick="comandera.borrar_persona({
								pass: $('#pass_eliminar_persona').val(),
								id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
								persona: $('#borrar_persona').val(), 
								btn: 'btn_borrar_persona' 
							})"
							id="btn_borrar_persona"
							class="btn"
							style="background-color: #D6872D; color:white;"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							type="button">
							<i class="fa fa-trash"></i> Remove
						</button> 
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIN Modal eliminar persona -->

<!-- Modal Autorizar_pedido -->
<div id="modal_autorizar_pedido" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button id="btn_cerrar_pedido" type="button" class="close" onclick="$('#modal_autorizar_pedido').click()">
					&times;
				</button>
				<h4 class="modal-title">Authorize modification</h4>
			</div>
			<div class="modal-body">
				<input type="number" id="id_pedido_modificar" style="display: none" />
				<h3><small>Enter password:</small></h3>
				<div class="input-group input-group-lg">
					<span class="input-group-addon"> <i class="fa fa-unlock-alt"></i> </span>
					<input id="pass_pedido" type="password" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) autorizar_pedido({pass:$('#pass_pedido').val(), pedido: $('#id_pedido_modificar').val(), json: 1})" class="form-control">
					<span class="input-group-btn">
						<button onclick="comandera.autorizar_pedido({
								pass: $('#pass_pedido').val(),
								pedido: $('#id_pedido_modificar').val(), 
								json: 1
						})" 
						class="btn"
						style="background-color: #209775; color:white;" 
						type="button">
							<i class="fa fa-check"></i> Authorize
						</button> 
					</span>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIN Modal Autorizar_pedido -->

<!-- Modal combos -->
<div class="modal fade" id="modal_combo" tabindex="-1" role="dialog" aria-labelledby="titulo_combo">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick="$('#modal_combo').click()" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="titulo_combo" align="left">combo</h4>
			</div>
		<!-- Contenedor -->
			<div class="modal-body" id="div_productos_combo">
				<!-- En esta div se cargan los productos del combo -->
			</div>
		<!-- Botones-->
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-12 col-xs-12" align="right">
						<button 
							id="btn_combos" 
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							type="button" 
							class="btn btn-lg" 
							style="background-color: #209775; color:white;"
							onclick="comandera.guardar_combo({
									btn: 'btn_combos', 
									pedidos: comandera.pedidos_seleccionados,
									datos_combo: comandera.datos_combo,
									persona: comandera['datos_mesa_comanda']['persona_seleccionada']
							})">
							<i class="fa fa-check"></i> Ok
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<!-- FIN modal combos -->
	
<!-- Modal merma -->
<div id="modal_merma" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" id="btn_cerrar_merma" class="close" onclick="$('#modal_merma').click()">
					&times;
				</button>
				<h4 class="modal-title">To save as diminishes shrink?</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-5 col-xs-5">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-trash-o"></i> Decrease </span>
							<select id="merma" class="selectpicker" data-width="30%">
								<option value="1">Si</option>
								<option selected value="2">No</option>
							</select>
						</div>
					</div>
					<div class="col-md-7 col-xs-7">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-pencil"></i> </span>
							<input
								onkeypress="(((document.all) ? event.keyCode : event.which)==13) comandera.guardar_merma({
										pedido: comandera.pedido_merma, 
										comentario: $('#comentario_merma').val()
										btn: 'btn_merma'
								})"
								id="comentario_merma"
								placeholder="Coments"
								type="text"
							class="form-control">
							<span class="input-group-btn">
								<button
									onclick="comandera.restar_pedido({
										persona: comandera.datos_mesa_comanda.persona_seleccionada,
										id_comanda: comandera.datos_mesa_comanda.id_comanda,
										id_mesa: comandera.datos_mesa_comanda.id_mesa,
										comentario: $('#comentario_merma').val(),
										pedido: comandera.pedido_merma, 
										id: comandera.pedido_merma.id,
										merma: $('#merma').val(),
										btn: 'btn_merma'
									})"
									id="btn_merma"
									class="btn btn-danger"
									data-loading-text="<i class='fa fa-refresh fa-spin'></i>">
									<i class="fa fa-trash"></i> Remove
								</button> 
							</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- FIN Modal merma -->

<!-- =====================================				FIN Modales			============================================== -->

<div class="col-md-6 col-xs-6 div_scroll_x" id="div_departamentos"><?php
	foreach($deparmentos['rows'] as $value){ ?>
		<button
			class="btn btn-lg btn-departamento"
			onclick="comandera.listar_familias({
				departamento: <?php echo $value['idDep'] ?>,
				div: 'div_departamentos',
				div_productos: 'div_productos'
			})">
			<div class="row">       
				<div style="width:200px;">          
					<?php echo substr($value['nombre'], 0, 20)  ?>  
				</div>    
			</div> 
		</button><?php			
	} ?>
</div>
<div class="col-md-1 col-xs-1" id="div_mover_scroll">
    <i
	    class="fa fa-caret-right fa-4x"
	    style="color: #DCB435"
	    onclick="comandera.mover_scroll({
		    direccion: 'derecha',
		    div: 'div_departamentos',
		    cantidad: 600
	    })">
	</i>
</div>
<script>
	$(".selectpicker").selectpicker("refresh");
	$('#div_departamentos').appendTo('#modal_cabecera');
	$('#div_mover_scroll').appendTo('#modal_cabecera');
</script>