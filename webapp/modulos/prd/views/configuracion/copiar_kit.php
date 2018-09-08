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
		<table id="tabla_recetas_copiar" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><strong>Nombre</strong></th>
					<th><strong>Insumos</strong></th>
					<th><strong>Insu. Preparados</strong></th>
					<th><strong>Costo</strong></th>
					<th><strong>Preparacion</strong></th>
					<th align="center"><strong><i class="fa fa-copy"></i></strong></th>
				</tr>
			</thead>
			<tbody><?php
				foreach ($datos as $k => $v) {
				// Genera una cadena con los nombres de lo insumos
					$insumos='';
					foreach ($v['insumos'] as $key => $value) {
						$insumos.=$value['nombre'].', ';
					}
					$insumos=substr($insumos, 0,-2); 
					
				// Genera una cadena con los nombres de lo insumos preparados
					$insumos_preparados='';
					foreach ($v['insumos_preparados'] as $key => $value) {
						$insumos_preparados.=$value['nombre'].', ';
					}
					$insumos_preparados=substr($insumos_preparados, 0,-2);
				
				// Si es insumo preparado lo pinta de azul
					$clase = ($v['tipo_producto']==8) ? 'info' : '' ; ?>
					
					<tr class="<?php echo $clase ?>">
						<td><?php echo $v['nombre'] ?></td>
						<td><?php echo $insumos ?></td>
						<td><?php echo $insumos_preparados ?></td>
						<td><?php echo $v['costo'] ?></td>
						<td><?php echo $v['preparacion'] ?></td>
						<td align="center"><?php
						
							$v['div']='div_insumos_agregados';
							$insumo=json_encode($v);
							$insumo=str_replace('"', "'", $insumo); ?>
							
							<button class="btn btn-warning" title="Copiar" onclick="recetas.copiar(<?php echo $insumo  ?>)">
								<i class="fa fa-copy"></i>
							</button>
						</td>
					</tr><?php
				} ?>
			</tbody>
		</table>
	</div>
	<script>
		recetas.convertir_dataTable({id: 'tabla_recetas_copiar'});
	</script>
</div> 