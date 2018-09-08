<?php
// Valida que existan menus
	if (empty($menus)) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				No se encontraron <strong> Menus</strong>, puedes agregarlos en el boton 
				<button class="btn btn-success btn-lg" onclick="$('#modal_editar').click(); $('#btn_nuevo').click();">
					<i class="fa fa-plus"></i> Agregar
				</button>
			</p>
		</blockquote><?php
		
		return 0;
	} ?>

<div class="row">
	<div class="col-xs-12">
		<table id="tabla_editar_menus" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><strong>Nombre</strong></th>
					<th><strong>Nombre restaurante</strong></th>
					<th><strong>Estilo</strong></th>
					<th align="center"><strong><i class="fa fa-pencil"></i></strong></th>
				</tr>
			</thead>
			<tbody><?php
				foreach ($menus as $k => $v) { ?>
					<tr>
						<td><?php echo $v['nombre'] ?></td>
						<td><?php echo $v['nombre_restaurante'] ?></td>
						<td><?php echo $v['texto_estilo'] ?></td>
						<td align="center"><?php
							$v['div'] = 'div_productos_agregados';
							$menu = json_encode($v);
							$menu = str_replace('"', "'", $menu);?>

							<button 
								id="btn_editar_<?php echo $v['idProducto'] ?>" 
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								class="btn btn-primary btn-lg" 
								title="Editar" 
								onclick="$(this).attr('disabled', true); configuracion.editar_menu(<?php echo $menu  ?>)">
								<i class="fa fa-pencil"></i>
							</button>
						</td>
					</tr><?php
				} ?>
			</tbody>
		</table>
	</div>
	<script>
		configuracion.convertir_dataTable({id: 'tabla_editar_menus'});
	</script>
</div> 