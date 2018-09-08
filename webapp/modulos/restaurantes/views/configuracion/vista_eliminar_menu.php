<?php
// Valida que existan menus
	if (empty($menus)) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				No se encontraron <strong> Menus</strong>, puedes agregarlos en el boton 
				<button class="btn btn-success btn-lg">
					<i class="fa fa-plus"></i> Agregar
				</button>
			</p>
		</blockquote><?php
		
		return 0;
	} ?>

<div class="row">
	<div class="col-xs-12">
		<table id="tabla_eliminar_menus" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><strong>Nombre</strong></th>
					<th><strong>Nombre restaurante</strong></th>
					<th><strong>Estilo</strong></th>
					<th align="center"><strong><i class="fa fa-trash"></i></strong></th>
				</tr>
			</thead>
			<tbody><?php
				foreach ($menus as $k => $v) { ?>
					<tr id="tr_eliminar_menu_<?php echo $v['id_menu'] ?>">
						<td><?php echo $v['nombre'] ?></td>
						<td><?php echo $v['nombre_restaurante'] ?></td>
						<td><?php echo $v['texto_estilo'] ?></td>
						<td align="center">
							<button 
								id="btn_eliminar_<?php echo $v['id_menu'] ?>" 
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								class="btn btn-danger btn-lg" 
								title="Editar" 
								onclick="configuracion.eliminar_menu({
												id_menu:<?php echo $v['id_menu']  ?> , 
												btn: 'btn_eliminar_<?php echo $v['id_menu'] ?>'
										})">
								<i class="fa fa-trash"></i>
							</button>
						</td>
					</tr><?php
				} ?>
			</tbody>
		</table>
	</div>
	<script>
		configuracion.convertir_dataTable({id: 'tabla_eliminar_menus'});
	</script>
</div> 