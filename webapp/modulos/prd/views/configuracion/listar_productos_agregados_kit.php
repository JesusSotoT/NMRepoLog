<?php
// Valida que existan productos
	if (empty($_SESSION['productos_agregados']['productos'])) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				Selecciona <strong>"productos"</strong>
				o <strong>"productos preparados"</strong> para agregarlos.
			</p>
		</blockquote><?php
		
		return 0;
	} ?>
	
<table id="tabla_productos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th><strong>producto</strong></th>
			<th><strong>Cantidad</strong></th>
			<th align="center"><strong>Precio</strong></th>
		</tr>
	</thead>
	<tbody><?php
		foreach ($_SESSION['productos_agregados']['productos'] as $k => $v) {
			if ($v['sub_total'] != 'undefined') {
				$total += $v['sub_total'];
			} ?>
			<tr>
				<td><?php echo $v['nombre'] ?></td>
				<td>
	        		<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
						<input
							value="<?php echo $v['cantidad'] ?>" 
							onchange="configuracion.actualizar_cantidad({
											id: <?php echo $v['id'] ?>,
											cantidad: $(this).val()
									})"
							style="min-width: 100px"
							type="number" 
							id="cantidad_<?php echo $v['id'] ?>" 
							class="form-control"/>
					</div>
				</td>
				<td align="center"> $ <?php echo number_format($v['precio'], 2, '.', ''); ?></td>
			</tr><?php
		} ?>
	</tbody>
	<footer>
			<tr>
				<td colspan="2" align="right">Costo total:</td>
				<td align="center">
					<strong style="font-size: 18px">
						<p id="costo_total"><?php echo number_format($total, 2, '.', ''); ?></p>
					</strong>
				</td>
			</tr>
	</footer>
</table>