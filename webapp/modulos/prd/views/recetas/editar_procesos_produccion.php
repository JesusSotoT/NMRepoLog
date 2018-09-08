<?php
// Valida que existan recetas o insumos preparados
	if (empty($datos)) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				No se encontraron <strong> Recetas</strong>
				o <strong>Insumos preparados</strong>
			</p>
		</blockquote><?php

		return 0;
	} ?>

<div class="row">
	<div class="col-xs-12">
		<table id="tabla_recetas_editar" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><strong>Código</strong></th>
					<th><strong>Nombre</strong></th>

					<th align="center"><strong><i class="fa fa-pencil"></i></strong></th>
				</tr>
			</thead>
			<tbody><?php
				foreach ($procesos_productos as $k => $v) {
					?>
					<tr class="<?php echo $clase ?>">
						<td><?php echo $v['codigo'] ?></td>
						<td><?php echo $v['nombre'] ?></td>
						<td align="center"><?php
							$v['div']='div_insumos_producto_agregados';
							$proceso=json_encode($v);
							
							$proceso=str_replace('"', "'", $proceso); ?>
							<button
								id="btn_editar_<?php echo $v['idProducto'] ?>"
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
								class="btn btn-primary btn-lg"
								title="Editar"
								onclick="$(this).attr('disabled', true); recetas.editar_productos_proceso(<?php echo $proceso  ?>)">
								<i class="fa fa-pencil"></i>
							</button>
						</td>
					</tr><?php
				} ?>
			</tbody>
		</table>
	</div>
	<script>
		recetas.convertir_dataTable({id: 'tabla_recetas_editar'});
	</script>
</div>