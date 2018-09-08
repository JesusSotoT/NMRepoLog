<?php
// Valida que existan kits
	if (empty($datos)) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				No se encontraron <strong> Kits</strong>
			</p>
		</blockquote><?php
		
		return 0;
	} ?>

<div class="row">
	<div class="col-xs-12">
		<table id="tabla_kits_eliminar" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><strong>Nombre</strong></th>
					<th><strong><i class="fa fa-barcode"></i> Codigo</strong></th>
					<th><strong><i class="fa fa-cubes"></i> Productos</strong></th>
					<th><strong><i class="fa fa-clock-o"></i> Horario</strong></th>
					<th><strong><i class="fa fa-usd"></i> Precio</strong></th>
					<th align="center"><strong><i class="fa fa-trash"></i></strong></th>
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
					
					<tr id="tr_eliminar_<?php echo $v['id_kit'] ?>">
						<td><?php echo $v['nombre'] ?></td>
						<td><?php echo $v['codigo'] ?></td>
						<td><?php echo $productos ?></td>
						<td><?php echo $v['horario'] ?></td>
						<td align="center">$ <?php echo $v['precio'] ?></td>
						<td align="center"><?php
						
							$v['div'] = 'div_productos_agregados';
							$producto = json_encode($v);
							$producto = str_replace('"', "'", $producto); ?>
							
							<button 
								id="btn_eliminar_<?php echo $v['id_kit'] ?>" 
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
								class="btn btn-danger btn-lg" 
								title="eliminar" 
								onclick="configuracion.eliminar_kit({
											id_kit:<?php echo $v['id_kit'] ?>, 
											btn:'btn_eliminar_<?php echo $v['id_kit'] ?>'
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
		configuracion.convertir_dataTable({id: 'tabla_kits_eliminar'});
	</script>
</div> 