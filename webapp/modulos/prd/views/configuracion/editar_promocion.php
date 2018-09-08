<?php
// Valida que existan recetas o productos preparados
	if (empty($datos)) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				No se encontraron <strong> Promociones</strong>
			</p>
		</blockquote><?php
		
		return 0;
	} ?>

<div class="row">
	<div class="col-xs-12">
		<table id="tabla_promociones_editar" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><strong>Nombre</strong></th>
					<th><strong>Tipo</strong></th>
					<th><strong>Por cada</strong></th>
					<th><strong>Descontar</strong></th>
					<th><strong>Descuento</strong></th>
					<th><strong>Horario</strong></th>
					<th align="center"><strong><i class="fa fa-pencil"></i></strong></th>
				</tr>
			</thead>
			<tbody><?php
				foreach ($datos as $k => $v) {
				// Genera una cadena con los nombres de lo productos
 					$productos = '';
					foreach ($v['productos'] as $key => $value) {
						$productos .= $value['nombre'] . ', ';
					}
					$productos = substr($productos, 0, -2); ?>
					
					<tr>
						<td><?php echo $v['nombre'] ?></td>
						<td><?php echo $v['tipo_texto'] ?></td>
						<td align="center"><?php echo $v['cantidad'] ?></td>
						<td align="center"><?php echo $v['cantidad_descuento'] ?></td>
						<td align="center"><?php echo $v['descuento'] ?></td>
						<td><?php echo $v['horario'] ?></td>
						<td align="center"><?php
						
							$v['div'] = 'div_productos_agregados';
							$producto = json_encode($v);
							$producto = str_replace('"', "'", $producto); ?>
							
							<button 
								id="btn_editar_<?php echo $v['idProducto'] ?>" 
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								class="btn btn-primary btn-lg" 
								title="Editar" 
								onclick="$(this).attr('disabled', true); configuracion.editar_promocion(<?php echo $producto  ?>)">
								<i class="fa fa-pencil"></i>
							</button>
						</td>
					</tr><?php
				} ?>
			</tbody>
		</table>
	</div>
	<script>
		configuracion.convertir_dataTable({id: 'tabla_promociones_editar'});
	</script>
</div> 