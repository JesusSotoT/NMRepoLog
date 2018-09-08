<?php
// Valida que existan reservaciones
	if (empty($_SESSION['productos_agregados']['productos'])&&empty($_SESSION['productos_agregados']['productos_preparados'])) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				Selecciona <strong>"productos"</strong>
				o <strong>"productos preparados"</strong> para agregarlos.
			</p>
		</blockquote><?php
		
		return 0;
	} ?>

<br /><?php 
// productos normales
if (!empty($_SESSION['productos_agregados']['productos'])) { ?>
	<table id="tabla_productos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th><strong>producto</strong></th>
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
					<td align="center"> $ <?php echo number_format($v['precio'], 2, '.', ''); ?></td>
				</tr><?php
			} ?>
		</tbody>
	</table><?php
} ?>