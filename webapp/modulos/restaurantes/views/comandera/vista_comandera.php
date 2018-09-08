<?php
// Valida si se deben de ocultar los botones
//echo json_encode($configuraciones['tipo_operacion']);

//echo json_encode($_SESSION['tables']);
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
			Seleccione el tipo de cuenta que se va a realizar
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
							sillas: comandera['datos_mesa_comanda']['sillas'],
							tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion'],
							personas: comandera.datos_mesa_comanda['num_personas'],
							mesero: comandera.datos_mesa_comanda['mesero'],
							f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp'],
							mesaTipo: comandera['datos_mesa_comanda']['mesaTipo']
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
							sillas: comandera['datos_mesa_comanda']['sillas'],
							tipo: comandera['datos_mesa_comanda']['tipo'],
							tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion'],
							personas: comandera.datos_mesa_comanda['num_personas'],
							mesero: comandera.datos_mesa_comanda['mesero'],
							f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp'],
							mesaTipo: comandera['datos_mesa_comanda']['mesaTipo']
						})">
						<div style="padding-top:10px">Todo Junto</div>
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
								sillas: comandera['datos_mesa_comanda']['sillas'],
								tipo: comandera['datos_mesa_comanda']['tipo'],
								tipo_mesa: comandera['datos_mesa_comanda']['tipo_mesa'],
								id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
								num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
								tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion'],
								mesero: comandera.datos_mesa_comanda['mesero'],
								f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp'],
								mesaTipo: comandera['datos_mesa_comanda']['mesaTipo']
							})">
							<div style="padding-top:10px">Pagar</div>
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
							sillas: comandera['datos_mesa_comanda']['sillas'],
							tipo: comandera['datos_mesa_comanda']['tipo'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion'],
							mesaTipo: comandera['datos_mesa_comanda']['mesaTipo']
						})">
						<div style="padding-top:10px">Mandar a caja</div>
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
							sillas: comandera['datos_mesa_comanda']['sillas'],
							tipo: comandera['datos_mesa_comanda']['tipo'],
							mesaTipo: comandera['datos_mesa_comanda']['mesaTipo'],
							div:'contenedor_personalizar'
						})"
				data-toggle="modal" 
				data-target="#div_personalizar">
				<a href="javascript:void(0)" style="text-decoration:none;color:#ffffff">
					<div 
						style="background:#00BFFF;width:130px;height:40px;margin:2px;font-family:verdana;color:#ffffff;font-size:14px;border-radius: 6px;margin-top:5px" 
						align="center">
						<div style="padding-top:10px">Dividir</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</div><!-- Fin div pagar -->
<div class="row"><!-- Departamentos, familias y lineas -->
	<div class="colo-md-4 col-xs-4">
		<div class="row"> <!-- se omite en fastfoow ch@-->
			<div class="col-md-3 col-xs-3" style="<?php echo $style; ?>">
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
			<!--    ////////////////////////////.  Cambiar de lougar para fastfood fin. -->
			<?php 
			if($configuraciones['tipo_operacion'] != "3"){
			?>
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
			
			<?php 
			}else{
			?>							
					<i class="fa fa-cutlery" style="color: #763F8B"></i> <i id="comanda_text"> xxxx</i> / 
					<i class="fa fa-th-large" style="color: #763F8B"></i> <i id="mesa_text"> Nombre de mesa</i>
					<div style="width: 100px; float: left">
						<i class="fa fa-user" style="color: #763F8B"></i> 
						<input 
							type="number" 
							min="1"
							id="num_comensales_comandera"
							onchange="comandera.guardar_comensales({
								comensales: $(this).val(),
								comanda: comandera['datos_mesa_comanda']['id_comanda']
							})"
							style="width: 50px" 
							align="center" />
					</div>				
			<?php
			}
			?>

			
			<!--   ////////////////////////////.  Cambiar de lougar para fastfood fin. -->
			<div class="col-md-3 col-xs-3">
				<button 
					onclick="comandera.agregar_persona_comandera({
						num_personas: comandera['datos_mesa_comanda']['num_personas'],
						id_comanda: comandera['datos_mesa_comanda']['id_comanda']
					})" 
					type="button" 
					class="btn btn-default act">
					<i class="fa fa-plus" style="color: #2C2146"></i> <i class="fa fa-pencil-square-o" style="color: #763F8B"></i>
				</button>
			</div>
			<div class="col-md-3 col-xs-3">
				<div class="input-group  has-error" style="width: 110px">
					<input type="number" class="form-control" id="borrar_persona">
					<span class="input-group-btn">
						<button 
							type="button" 
							class="btn act" 
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
	<div class="col-md-5 col-xs-5">
		<div class="btn-group" role="group" aria-label="...">
			<button
				onclick="comandera.productos = ''; comandera.area_inicio()"
				class="btn"
				style="background-color: #209775; color: white;">
				<i class="fa fa-cubes fa-lg"></i> Productos
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
				<i class="fa fa-plus-circle fa-lg" style="color: #DCB435"></i> Complementos
			</button>
			<button
				onclick="comandera.listar_promociones({
					div: 'div_productos',
					tipo: 7,
					persona: comandera.datos_mesa_comanda['persona_seleccionada'],
					comanda: comandera['datos_mesa_comanda']['id_comanda']
				})"
				class="btn"
				style="background-color: #e27f24; ">
				<i class="fa fa-tags fa-lg" style=""></i> Promociones
			</button>
		</div>
	</div>
	<div class="col-md-3 col-xs-3"><!-- Buscador  -->
		<div class="input-group">
			<input onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.buscar_productos({texto: $('#texto').val(), comanda: comandera['datos_mesa_comanda']['id_comanda'], div:'div_productos'})" type="search" id="texto" class="form-control" placeholder="pasta, corte, desayuno, omelet, etc.">
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
		<!--////////////////////////////////////////////////////////////////////////// ch@-->
		<?php if($configuraciones['tipo_operacion'] == "3"){ ?>
			
			<div class="row">
				<div class="col-md-5 col-xs-5">				
						<button
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							id="btn_cerrar_persona"
							type="button"
							class="btn btn-lg btn-block"
							onclick="comandera.cerrar_comanda_persona({
								btn: 'btn_cerrar_persona',
								id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
								id_mesa: comandera['datos_mesa_comanda']['id_mesa'],
								persona: comandera['datos_mesa_comanda']['persona_seleccionada'],
								personas: comandera.datos_mesa_comanda['num_personas'],
								mesero: comandera.datos_mesa_comanda['mesero'],
								f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp']
							})"
							style="background-color: #CEA42F; color: #714789 margin-top: 1%;" >
							<i class="fa fa-credit-card"></i> <kbd id="text_cerrar_persona" style="background-color: #CEA42F; color: #714789">0</kbd> Individual
						</button>				
				</div>

				<div class="col-md-7 col-xs-7">
					<button
						type="button"
						class="btn btn-lg btn-block btn-success"
						onclick="comandera.pagarT({
							id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
							idmesa: comandera['datos_mesa_comanda']['id_mesa'],
						})"
						style="background-color: #209775; color:white; margin-top: 1%;">
						<i class="fa fa-money"></i> Pagar
					</button>
				</div>
			</div>

		<?php }?>
		
		<!--////////////////////////////////////////////////////////////////////////// ch@ fin -->
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<button
					type="button"
					class="btn btn-lg btn-block"
					onclick="comandera.pedir({
						id_comanda: comandera['datos_mesa_comanda']['id_comanda']
					})"
					style="background-color: #209775; color:white; margin-top: 1%; <?php echo $style ?>">
					<i class="fa fa-paper-plane-o"></i> Pedir
				</button>
			</div>
		</div>
		<!-- CH@ se oculta para fastfood-->
		<?php 
		if($configuraciones['tipo_operacion'] != "3"){
		?>
		<div class="row">
			<div class="col-md-12 col-xs-12">
				<button 
					onclick="comandera.validar_cuenta({id_comanda: comandera['datos_mesa_comanda']['id_comanda']})"
					type="button" 
					class="btn btn-lg btn-block btnEnd" 
					idcomanda="comandera['datos_mesa_comanda']['id_comanda']" 
					style="background-color: #CEA42F; color: #714789; margin-top: 1%; <?php //echo $style ?>"
					tipo="<?php echo $tipo ?>" 
					repa="<?php echo $repa ?>">
					<i class="fa fa-credit-card"></i> Cuenta
				</button>
			</div>
		</div>
		<?php 
			}
		?>
		<!-- CH@ se oculta para fastfood fin -->
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
							<h4><strong><i class="fa fa-wrench"></i> Funciones</strong></h4>
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
									<i class="fa fa-pencil"></i> Asignar
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
									<i class="fa fa-exchange"></i> Mudar
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
										f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp'],
										ver: 1
									})">
									<i class="fa fa-search"></i> Ver
								</button><?php 
							
							// Valida que exista la reservacion
								/// se habilitara para fastfood $style
								$id_reservacion = (empty($id_reservacion)) ? 0 : $id_reservacion ; ?>
								<button 
									type="button" 
									class="btn" 
									style="background-color: #D6872D; color:white; width: 130px; margin-top: 1%; <?php //echo $style ?>" 
									data-toggle="modal" 
									data-target="#modal_eliminar_comanda">
									<i class="fa fa-trash"></i> Eliminar
								</button><?php
									
								if (!empty($mesas_juntas)) { ?>
									<button 
										type="button" 
										class="btn btn-default " 
										style="width: 130px; margin-top: 1%" 
										data-toggle="modal" 
										data-target="#div_separar" 
										mesas_juntas="<?php echo $mesas_juntas ?>">
										<i class="fa fa-arrows-h"></i> Separar
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
					<i class="fa fa-undo"></i> Cargar mas productos
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
				<h4 class="modal-title">Autorizar asignacion</h4>
			</div>
			<div class="modal-body">
				<h3><small>Introduce la contraseña:</small></h3>
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
							<i class="fa fa-check"></i> Autorizar
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
				<h4 class="modal-title" id="titulo_asignar" align="left">Asignar mesa</h4>
			</div>
		<!-- Mensaje -->
			<div class="modal-body">
				<div align="left">
					<blockquote style="font-size: 14px">
						<p>
							Selecciona el <strong> Mesero </strong> al que desea <strong> Asignar </strong> la mesa.
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
					Cancelar
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
				<h4 class="modal-title" id="titulo_separar" align="left">Mudar comanda</h4>
			</div>
		<!-- Mensaje -->
			<div class="modal-body">
				<div align="left">
					<blockquote style="font-size: 14px">
						<p>
							Selecciona la <strong> Mesa </strong>.Esta acción <strong> separara </strong>
							las mesas y mudara los pedidos a la mesa que selecciones.
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
					Cancelar
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
				<h4 class="modal-title">Autorizar asignacion</h4>
			</div>
			<div class="modal-body">
				<h3><small>Introduce la contraseña:</small></h3>
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
							<i class="fa fa-check"></i> Autorizar
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
				<h4 class="modal-title">Eliminar comanda</h4>
			</div>
			<div class="modal-body">
				<h3><small>Introduce la contraseña:</small></h3>
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
							<i class="fa fa-trash"></i> Eliminar
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
				<h4 class="modal-title" id="titulo_personalizar" align="left">Cerrar comanda</h4>
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
							tipodiv="0" 
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
									tipodiv: $(this).attr('tipodiv'),
							})">
							<i class="fa fa-check"></i> Ok
						</button>
						<button type="button" class="btn btn-lg" onclick="$('#div_personalizar').click()" style="background-color: #D6872D; color:white;">
							<i class="fa fa-ban"></i> Cancelar
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
				<h4 class="modal-title">Eliminar persona</h4>
			</div>
			<div class="modal-body">
				<h3><small>Introduce la contraseña:</small></h3>
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
							class="btn act"
							style="background-color: #D6872D; color:white;"
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							type="button">
							<i class="fa fa-trash"></i> Eliminar
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
				<h4 class="modal-title">Autorizar modificacion</h4>
			</div>
			<div class="modal-body">
				<input type="number" id="id_pedido_modificar" style="display: none" />
				<h3><small>Introduce la contraseña:</small></h3>
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
							<i class="fa fa-check"></i> Autorizar
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

<!-- Modal promociones -->
<div class="modal fade" id="modal_promocion" tabindex="-1" role="dialog" aria-labelledby="titulo_promocion">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" onclick="$('#modal_promocion').click()" aria-label="Cerrar">
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="col-md-6 col-xs-6">
					<h4 class="modal-title" id="titulo_promocion" align="left">Promocion</h4>
				</div>
				<div class="col-md-5 col-xs-6">
					<h4 id="title-promo" style="float:right; display: none;">Productos seleccionados: 1</h4>
				</div>
			</div>
		<!-- Contenedor -->
			<div class="modal-body" id="div_productos_promocion">
				<!-- En esta div se cargan los productos del promocion -->
			</div>
		<!-- Botones-->
			<div class="modal-footer">
				<div class="row">
					<div class="col-md-12 col-xs-12" align="right">
						<button 
							id="btn_promociones" 
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
							type="button" 
							class="btn btn-lg" 
							style="background-color: #209775; color:white;"
							onclick="comandera.guardar_promocion({
									btn: 'btn_promociones', 
									pedidos: comandera.pedidos_seleccionados,
									datos_promocion: comandera.datos_promocion,
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
<!-- FIN modal promociones -->
	
<!-- Modal merma -->
<div id="modal_merma" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" id="btn_cerrar_merma" class="close" onclick="$('#modal_merma').click()">
					&times;
				</button>
				<h4 class="modal-title">¿Guardar como merma merma?</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-5 col-xs-5">
						<div class="input-group">
							<span class="input-group-addon"> <i class="fa fa-trash-o"></i> Merma </span>
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
								placeholder="Comentarios"
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
									<i class="fa fa-trash"></i> Eliminar
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
    <div class="modal fade" id="modalPagar" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header modal-header-success">
         <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
          <h4 class="modal-title"><i class="fa fa-money fa-lg"></i> Pagos</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-4">
                    <select id="cboMetodoPago" class="form-control" onchange="comandera.changeMetodoPago();">
                    <?php 
                        foreach ($formasDePago['formas'] as $key => $value) {
                            echo '<option value="'.$value['idFormapago'].'">('.$value['claveSat'].') '.$value['nombre'].'</option>';
                        }
                    ?>                     
                    </select>
                </div>
                <div class="col-sm-4">
                    <input type="text" id="txtCantidadPago" class="form-control numeros">
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-default btn-block" id="btnAgregarPago">Agrega Pago</button>
                </div>
            <!--    <div class="col-sm-1">
                    <button id="btnDenominacionesPago" class="form-control btn btn-primary" data-toggle="modal" data-target="#modalPagoDenominacion" onclick="caja.pagoDenominacionButtonAction();">
                                <i class="fa fa-money" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="col-sm-3" id="option-vendedor">
                                    <div class="row">
                                        <div class="col-sm-12" >
                                            <div class="input-group " id="vendedorSelect" style="margin-left:-7%; ">
                                                <span class="input-group-addon" ><i class="fa fa-user-circle-o" aria-hidden="true"></i></span> 
                                                <input type="text" class="typeahead form-control " id="vendedor-caja" placeholder="Vendedor comisionista" data-provide="typeahead" style="width: 105%;">

                                                <input type="hidden" id="hidenvendedor-caja" value="">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                </div> -->
            </div><br>
                <div class="row" id="divReferenciaPago" style="display:none;">
                    <div class="col-md-8">
                            <label id="lblReferencia">Referencia transferencia:</label>
                            <input type="text" id="txtReferencia" class="form-control pull-left" value="">
                    </div>
                    <div class="col-sm-4" id="tarjetasRadios" style="display:none;">
                        <label id="er">Tipo de Tarjeta</label>
                      <!--  <select class="form-control" name="" id="">
                            <option value="1">VISA/<i class="fa fa-cc-visa" aria-hidden="true"></i></option>
                            <option value="2">MASTER CARD/<i class="fa fa-cc-mastercard" aria-hidden="true"></i></option>
                            <option value="3">AMERICAN EXPRESS/<i class="fa fa-cc-amex" aria-hidden="true"></i></option>
                        </select>-->
                       <div style="margin-top:1%;">
                        <label class="radio-inline"><input type="radio" name="tarRadio" value="1"><i class="fa fa-cc-visa" aria-hidden="true"></i></label>
                        <label class="radio-inline"><input type="radio" name="tarRadio" value="2"><i class="fa fa-cc-mastercard" aria-hidden="true"></i></label>
                        <label class="radio-inline"><input type="radio" name="tarRadio" value="3"><i class="fa fa-cc-amex" aria-hidden="true"></i></label>
                        </div> 
                    </div>
                </div>
            <div class="row">
                <div class="col-sm-5">
                    <label>Total a Pagar:</label>
                    <label id="lblTotalxPagar"></label>
                </div>
                <div class="col-sm-2"></div>
                <div class="col-sm-5">
                    <label>Entregado:</label>
                    <label id="lblAbonoPago"></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Metodo</th>
                                <th>Cantidad</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody id="divDesglosePagoTablaCuerpo">

                        </tbody>
                    </table>
                </div>
            </div><?php
                    
        // Valida si se debe de mostrar la propina
            if($ajustes_foodware['switch_propina'] == 1){
                $ajustes_json = json_encode($ajustes_foodware);
                $ajustes_json = str_replace('"', "'", $ajustes_json); ?>
                
                <script>caja.info_venta['ajustes'] = <?php echo $ajustes_json ?></script>
                <div class="row">
                    <div class="col-sm-4">
                        <select id="metodo_pago_propina" class="form-control">
                            <option value="1">Efectivo</option>
                            <option value="2">Cheque</option>
                            <option value="3">Tarjeta de regalo</option>
                            <option value="4">Tarjeta de crédito</option>
                            <option value="5">Tarjeta de debito</option>
                            <option value="6">Crédito</option>
                            <option value="7">Transferencia</option>
                            <option value="8">Spei</option>
                            <option value="9">-No Identificado-</option>
                            <option value="21">Otros</option>
                            <option value="24">NA</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                            <input 
                                id="porcentaje_propina" 
                                onchange="caja.calcular_propina({porcentaje: $(this).val()})"
                                type="number" 
                                min="0"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                            <input 
                                id="monto_propina"
                                type="number" 
                                min="0"
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <button 
                            class="btn btn-default" 
                            onclick="caja.agregar_propina({
                                    metodo_pago: $('#metodo_pago_propina').val(),
                                    monto: $('#monto_propina').val()
                            })">
                            Agrega propina
                        </button>
                    </div>
                    <div class="col-sm-2" style="padding-top: 10px">
                        <label id="txt_total_propina">$ 0</label>
                    </div>
                </div><br /><?php
            } ?>
            <div class="row">
                <div class="col-sm-5">
                    <label>Aun por Pagar:</label>
                    <label id="lblPorPagar"></label>
                </div>
                <div class="col-sm-2"></div>
                <div class="col-sm-5">
                    <label>Cambio:</label>
                    <label id="lblCambio"></label>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <button class="btn btn-success btn-block" onclick="javascript:comandera.pagar();" id="pagarPagar">Pagar    </button>
                </div>
              <!--  <div class="col-sm-3">
                    <button class="btn btn-warning btn-block" onclick="javascript:$('#modalPagar').modal('toggle'); caja.suspender();">Suspender</button>
                </div> -->
                <div class="col-sm-4">
                    <button class="btn btn-primary btn-block" onclick="javascript:$('#modalPagar').modal('toggle');">Salir    </button>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-danger btn-block" onclick="javascript:$('#modalPagar').modal('toggle');">Cancelar  </button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-default">
            <div align="center"><label id="lblMensajeEstado"></label></div>
            <div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
                 <span class="sr-only">Loading...</span>
             </div>
        </div>
        </div>
      </div>
    </div>
  </div>
      <div id='modalComprobante' class="modal fade" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="labelTF">Ticket</h4>
                    <input type="hidden" id="idVentaTicket">
                </div>
                <div class="modal-body">
                    <div class="row rTouch">
                        <div class="col-md-12">
                            <iframe id="frameComprobante" src="" frameborder="0" style="float:left;height:300px;width:100%;"></iframe>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                    <!--   <div class="col-md-6 col-md-offset-6">
                            <input type="text" id="emailTicket" class="form-control">
                            <button class="btn btn-primary" onclick="caja.enviarTicket();">Enviar</button>
                            <button class="btn btn-danger btnMenu" onclick="javascript:window.location.reload();">Salir</button>
                        </div> -->
                        <div id="emailTicketHide">
                        <div class="col-sm-2">
                            <label>Email</label>
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="emailTicket">
                        </div>
                        <div class="col-sm-3"> 
                            <input type="hidden" id="inputRecibo">
                            <button onclick="comandera.enviarTicket();" class="btn btn-primary btn-block"><i class="fa fa-paper-plane" aria-hidden="true"></i> Enviar</button>
                        </div>
                        </div>
                        <div class="col-sm-3">
                        
						<button id="botonSalirModal" onclick="comandera.kk({
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
								tipo_operacion: 3,
								mesero: comandera.datos_mesa_comanda['mesero'],
								f_ini: comandera.datos_mesa_comanda.info_mesa['timestamp']
						})" class="btn btn-danger btnMenu btn-block">Salir</button>
                            <!--<button id="botonSalirModal" onclick="comandera.kk(comandera['datos_mesa_comanda']['id_mesa'],comandera['datos_mesa_comanda']['id_comanda']);" class="btn btn-danger btnMenu btn-block">Salir</button> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Modal Descuento-->
  <div class="modal fade" id="modalDescParcial" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title">Descuento por producto</h3>
        </div>
        <div class="modal-body" style="padding-top: 0px;">
            <div class="row" style="background-color: rgba(0,0,0,0.05)">
                <div class="col-sm-5">
                    <h4>Producto</h4>
                </div>
                <!--
                <div class="col-sm-3">
                    <h4>Lista de precios</h4>
                </div>
                -->
                <div class="col-sm-2">
                    <h4>Precio</h4>
                </div>
                <!--
                <div class="col-sm-2">
                    <h4>Subtotal</h4>
                </div>
                -->
            </div>

            <div class="row">
            <input type="hidden" id="xProParc">
                <div class="col-sm-5">
                    <h5 id="encabezadoNombre" style="margin-top: 20px;"></h5>
                </div>
                <!--
                <div class="col-sm-3">
                    <h5><select id="selectListaPrecios" class="form-control" onclick="caja.changeListaPrecio();" onchange="caja.changeListaPrecio();">
                    </select></h5>
                </div>
                -->
                <div class="col-sm-2">
                    <h5 id="encabezadoPrecio" style="margin-top: 20px;"></h5>
                    <input type="hidden" id="encabezadoPrecioInput">
                    <input type="hidden" id="limite_porcentaje">
                    <input type="hidden" id="limite_cantidad">
                </div>
				<!--
                <div class="col-sm-2">
                    <h5 id="encabezadoImporte" style="margin-top: 20px;"></h5>
                    <input type="hidden" id="encabezadoImporteInput">
                </div>
                -->
            </div>
            <br>
            <div class="row" id="edicionProd">
            <!--
                <div class="col-xs-12">
                    <label>Descripcion:</label>
                    <input type="text" id="descProdUpdate" class="form-control"> 
                </div>
            -->
            </div>
            <br>
            <div class="row">
                <div class="col-sm-8">
                    <div class="row">   
                        <div class="col-sm-3">
                            <label style="margin: 10px;">Descuento:</label>
                        </div>
                        <div class="col-sm-5">
                            <select id="tipoDescu" class="form-control">                                
                                <option value="%" >Porcentaje</option>                                
                                <!--
                                <option value="N" selected="selected">Precio de lista</option>
                                <option value="C">Cortesía</option>
                                <option value="$">Monto</option>
                                -->
                            </select>
                        </div>                        
                        <div class="col-sm-4">
                            <input type="number" class="form-control" id="desCantidad">
                        </div>                        
                    </div>
                </div>
                <div class="col-sm-2">
                    <button id="btndesc" class="btn btn-primary btn-block">Aplicar</button>
                <!--    <button class="btn btn-primary btn-block" onclick="caja.aplicaDesParcial();">Aplicar</button> -->
                </div>
                <div class="col-sm-2">
                   <!-- <button class="btn btn-danger btn-block" data-dismiss="modal" onclick="comandera.modalDescHide();">Cancelar</button>-->
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
<!-- Modal Descuento fin-->
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
<div class="col-md-1 col-xs-1" id="div_mover_scroll" style="padding-left: 10px; width: 15px;">
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