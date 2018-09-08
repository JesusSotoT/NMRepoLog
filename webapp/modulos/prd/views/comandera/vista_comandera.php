<!-- **	/////////////////////////- -				CSS 				--///////////////////// **-->
<style>
    .wrapPro {
        word-wrap: break-word;
        position: justify;
        font-size: 11px;
        width: 80%;
        padding: 10px 10px 10px 10px;
        height: auto;
        overflow-x: auto;
        color: #000;
    }
</style>
	
<!-- **	/////////////////////////- -				FIN CSS 			--///////////////////// **-->
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
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']
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
							tipo: comandera['datos_mesa_comanda']['tipo'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']
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
								tipo: comandera['datos_mesa_comanda']['tipo'],
								id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
								num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
								tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']
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
							idmesa: comandera['datos_mesa_comanda']['id_mesa'],
							tipo: comandera['datos_mesa_comanda']['tipo'],
							id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
							num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
							tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']
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
						<div style="padding-top:10px">Dividir</div>
					</div>
				</a>
			</div>
		</div>
	</div>
</div>
<!-- Fin div pagar -->

<div id="div_funciones_comandera">
		<button 
			type="button" 
			class="btn btn-success btn-lg" 
			onclick="comandera.pedir({
				id_comanda: comandera['datos_mesa_comanda']['id_comanda']
			})"
			style="width: 130px; margin-top: 1%; <?php echo $style ?>">
			<i class="fa fa-check"></i> Pedido
		</button>
		<button 
			onclick="comandera.validar_cuenta({id_comanda: comandera['datos_mesa_comanda']['id_comanda']})"
			type="button" 
			class="btn btn-warning btn-lg btnEnd" 
			style="width: 130px; margin-top: 1%" 
			idcomanda="comandera['datos_mesa_comanda']['id_comanda']" 
			tipo="<?php echo $tipo ?>" 
			repa="<?php echo $repa ?>">
			<i class="fa fa-credit-card"></i> Cuenta
		</button>
		<button 
			type="button" 
			class="btn btn-default btn-lg" 
			style="width: 130px; margin-top: 1%; <?php echo $style ?>" 
			data-toggle="modal" 
			data-target="#modal_autorizar">
			<i class="fa fa-pencil"></i> Asignar
		</button>
		<button 
			type="button" 
			class="btn btn-primary btn-lg" 
			style="width: 130px; margin-top: 1%; <?php echo $style ?>" 
			data-toggle="modal" 
			data-target="#div_mudar" 
			idcomanda="comandera['datos_mesa_comanda']['id_comanda']">
			<i class="fa fa-exchange"></i> Mudar
		</button>
		<button 
			type="button" 
			class="btn btn-warning btn-lg" 
			style="width: 130px; margin-top: 1%"
			onclick="comandera.cerrar_comanda({
				bandera: 0,
				reimprime: 1,
				nombre: comandera['datos_mesa_comanda']['nombre'],
				idComanda: comandera['datos_mesa_comanda']['id_comanda'],
				tel: comandera['datos_mesa_comanda']['tel'],
				idmesa: comandera['datos_mesa_comanda']['id_mesa'],
				tipo: comandera['datos_mesa_comanda']['tipo'],
				id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
				num_comensales: comandera['datos_mesa_comanda']['info_mesa']['comensales'],
				tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']
			})">
			<i class="fa fa-search"></i> Ver
		</button><?php 
	
	// Valida que exista la reservacion
		$id_reservacion = (empty($id_reservacion)) ? 0 : $id_reservacion ; ?>
		<button 
			type="button" 
			class="btn btn-danger btn-lg" 
			style="width: 130px; margin-top: 1%; <?php echo $style ?>" 
			data-toggle="modal" 
			data-target="#modal_eliminar_comanda">
			<i class="fa fa-trash"></i> Eliminar
		</button><?php
			
		if (!empty($mesas_juntas)) { ?>
			<button 
				type="button" 
				class="btn btn-default btn-lg" 
				style="width: 130px; margin-top: 1%" 
				data-toggle="modal" 
				data-target="#div_separar" 
				mesas_juntas="<?php echo $mesas_juntas ?>">
				<i class="fa fa-arrows-h"></i> Separar
			</button><?php
		} ?>
</div>
<!-- Departamentos, familias y lineas -->
<div class="row">
	<div class="col-md-5">
		<button 
			onclick="comandera.area_inicio()"
			class="btn btn-default btn-lg">
			<i class="fa fa-home"></i> Area
		</button>
		<button class="btn btn-default btn-lg btnFamily" iddeparment="0" style="display:none">
			<i class="fa fa-angle-right"></i> Cat
		</button>
		<button class="btn btn-default btn-lg btnLine" idFamily="0" style="display:none">
			<i class="fa fa-angle-right"></i> Sub
		</button>
		<div class="input-group input-group-lg has-success" style="width: 150px; padding-top: 15px">
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
					class="btn btn-success"
					id="btn_tiempo">
					<i class="fa fa-plus"></i> <i class="fa fa-clock-o"></i>
				</button> 
			</span>
		</div>
	</div>
	<div class="col-md-7" id="div_departamentos" align="left" style="overflow: scroll; height: 95px;"><?php
		foreach($deparmentos['rows'] as $value){ ?>
			<button 
				type="button" 
				class="btn btn-default btn-lg" 
				style="font-size:13px; width:130px; margin-top:1%"
				onclick="comandera.listar_familias({
					departamento: <?php echo $value['idDep'] ?>,
					div: 'div_departamentos',
					div_productos: 'div_productos'
				})">
				<?php echo substr(utf8_decode($value['nombre']), 0, 11); ?>
			</button><?php
		} ?>
	</div>
</div><br />
<!-- FIN Departamentos, familias y lineas -->

<!-- Abajo -->
<div class="row">
	<div class="col-md-5">
		<div class="row">
			<div class="col-md-5">
				<button type="button" class="btn btn-default btn-lg" onclick="$('#modal_comandera').click();">
					<i class="fa fa-angle-double-left"></i>&nbsp;
				</button>
				<button 
					data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
					id="btn_cerrar_persona" 
					type="button" 
					class="btn btn-warning btn-lg"
					onclick="comandera.cerrar_comanda_persona({
						btn: 'btn_cerrar_persona',
						id_comanda: comandera['datos_mesa_comanda']['id_comanda'],
						id_mesa: comandera['datos_mesa_comanda']['id_mesa'],
						persona: comandera['datos_mesa_comanda']['persona_seleccionada']
					})">
					<i class="fa fa-credit-card"></i>
					<i class="fa fa-pencil-square-o "></i> 
					<kbd id="text_cerrar_persona">0</kbd>	
				</button>
			</div>
			<div class="col-md-3">
				<button 
					onclick="comandera.agregar_persona_comandera({
						num_personas: comandera['datos_mesa_comanda']['num_personas'],
						id_comanda: comandera['datos_mesa_comanda']['id_comanda']
					})" 
					type="button" 
					class="btn btn-default btn-lg">
					<i class="fa fa-plus"></i> <i class="fa fa-pencil-square-o"></i>
				</button>
			</div>
			<div class="col-md-4">
				<div class="input-group input-group-lg has-error" style="width: 150px">
					<input type="number" class="form-control" id="borrar_persona">
					<span class="input-group-btn">
						<button 
							type="button" 
							class="btn btn-danger" 
							data-toggle="modal" 
							data-target="#modal_eliminar_persona">
							<i class="fa fa-minus"></i> <i class="fa fa-pencil-square-o"></i>
						</button>
					</span>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="div_personas" class="col-md-12">
				<!-- En esta div se cargan las personas de la comanda -->
			</div>
		</div>
		<div class="row">
			<div id="div_listar_pedidos_persona" class="col-md-12">
				<!-- En esta div se cargan los pedidos las personas -->
			</div>
		</div>
	</div>
	<div class="col-md-7">
		<div class="row">
		<!-- Kits -->
			<div class="col-md-3" style="display: none"> <!-- No mostrar -->
				<button
				onclick="comandas.listar_kits({
							div: 'div_productos',
							tipo: 6,
							persona: person,
							comanda: <?php echo $idcomanda ?>
						})"
				class="btn btn-warning btn-lg">
					<i class="fa fa-dropbox fa-lg"></i> Kits
				</button>
			</div>
		<!-- FIN Kits -->
		
		<!-- Buscador  -->
			<div class="col-md-8">
				<div class="input-group input-group-lg">
					<input onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.buscar_productos({texto: $('#texto').val(), comanda: comandera['datos_mesa_comanda']['id_comanda'], div:'div_productos'})" type="search" id="texto" class="form-control" placeholder="pasta, corte, desayuno, omelet, etc.">
					<span class="input-group-btn">
						<button 
							onclick="comandas.buscar_productos({
									texto: $('#texto').val(), 
									comanda: comandera['datos_mesa_comanda']['id_comanda'], 
									div: 'div_productos'
							})"
							class="btn btn-default" 
							type="button">
							&nbsp;<i class="fa fa-search"></i>
						</button> 
					</span>
				</div>
			</div>
		<!-- FIN Buscador -->
		</div><br />
		<div id="div_productos"><?php
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
			<div class="col-md-12">
				<button 
					id="btn_cargar_productos"
					class="btn btn-default btn-lg" 
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
</div> <!-- Fin Abajo -->

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
							class="btn btn-success" 
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
				<button type="button" class="btn btn-danger" onclick="$('#modal_asignar').click()">
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
				<button type="button" class="close"  onclick="$('#div_mudar').click()" aria-label="Cerrar">
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
				<div align="center" id="mesas_libres"><?php
					if ($mesas_libres['total']>0) {
						foreach ($mesas_libres['rows'] as $key => $value) { ?>
							<button 
								id="btn_mesa_<?php echo $value['id_mesa'] ?>" 
								onclick="comandera.mudar_comanda({
										mesa_origen: comandera['datos_mesa_comanda']['id_mesa'],
										mesa: <?php echo $value['id_mesa'] ?>,
										comanda: comandera['datos_mesa_comanda']['id_comanda']
								})" 
								type="button" 
								class="btn btn-default btn-lg">
								<?php echo  $value['nombre_mesa'] ?>
							</button><?php
						}
					} else { ?>
						<div align="center">
							<h3><span class="label label-default">* No hay mesas disponibles *</span></h3>
						</div><?php
					} ?>
				</div>
			</div>
		<!-- Cancelar -->
		<div class="modal-footer">
				<button type="button" class="btn btn-danger" onclick="$('#div_mudar').click()">
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
								idmesa: comandera['datos_mesa_comanda']['id_mesa'],
								id_reservacion: comandera['datos_mesa_comanda']['id_reservacion'],
								tipo_operacion: comandera['datos_mesa_comanda']['tipo_operacion']
							})"
							class="btn btn-danger"
							type="button">
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
							data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
							autocomplete="off" 
							type="button" 
							class="btn btn-success btn-lg" 
							onclick="comandas.guardar_comanda_parcial({
									persona: comandera['datos_mesa_comanda']['persona_seleccionada'], 
									idpadre: comandera['datos_mesa_comanda']['id_comanda'],
									mesa: comandera['datos_mesa_comanda']['id_mesa']
							})">
							<i class="fa fa-check"></i> Ok
						</button>
						<button type="button" class="btn btn-danger btn-lg" onclick="$('#div_personalizar').click()">
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
							class="btn btn-danger"
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
						class="btn btn-success" 
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

<!-- =====================================				FIN Modales			============================================== -->
<script>
	$('#div_funciones_comandera').appendTo('#contenedor_funciones_comandera');
</script>