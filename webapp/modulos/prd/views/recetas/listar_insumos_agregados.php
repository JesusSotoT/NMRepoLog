<?php
// Valida que existan reservaciones
	if (empty($_SESSION['insumos_agregados']['insumos'])&&empty($_SESSION['insumos_agregados']['insumos_preparados'])) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				Seleccione un <strong>"producto"</strong>
				y	asígnele <strong>"procesos de producción"</strong> para agregarlos.
			</p>
		</blockquote><?php

		return 0;
	} ?>

<br /><?php
// Insumos normales
if (!empty($_SESSION['insumos_agregados']['insumos'])) { ?>
	<table id="tabla_insumos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<!--<th align="center"><strong>Cantidad</strong></th>-->
				<th align="center"><strong>Orden</strong></th>
				<th align="center"><strong>Insumo</strong></th>
				<th><strong>Tiempo horas</strong></th>
				<!--<th align="center"><strong>Costo Proveedor</strong></th>
				<th align="center"><strong>Costo Preparacion</strong></th>-->
			</tr>
		</thead>
		<tbody><?php
			foreach ($_SESSION['insumos_agregados']['insumos'] as $k => $v) {
			// Opciones del select
				$opcional = (in_array(3, $v['select'])) ? 'selected' : '' ;
				$sin = (in_array(1, $v['select'])) ? 'selected' : '' ;
				$extra = (in_array(2, $v['select'])) ? 'selected' : '' ;
				$normal = (in_array(0, $v['select']) || (empty($opcional) && empty($extra))) ? 'selected' : '' ;

				if ($v['sub_total']!='undefined') {
					$total+=$v['sub_total'];
				} ?>
				<tr>

				<!-- Guarda los opcionales al cargar -->
					<td align="center"><?php echo $v['id'] ?></td>
					<td><?php echo $v['nombre'] ?></td>
					<td align="center"><?php echo number_format($v['costo'], 2, '.', ''); ?></td>
					<td align="center" id="sub_total_<?php echo $v['id'] ?>"><?php echo $v['sub_total']; ?></td>
				</tr><?php
			} ?>

		</tbody>
	</table><?php
}

// Insumos preparados
if (!empty($_SESSION['insumos_agregados']['insumos_preparados'])) { ?>
	<table id="tabla_insumos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th align="center" class="info"><strong>Cantidad</strong></th>
				<th align="center" class="info"><strong>Tipo</strong></th>
				<th align="center" class="info"><strong>Insumo</strong></th>
				<th class="info"><strong>Unidad</strong></th>
				<th align="center" class="info"><strong>Costo Proveedor</strong></th>
				<th align="center" class="info"><strong>Costo Preparacion</strong></th>
			</tr>
		</thead>
		<tbody><?php
			foreach ($_SESSION['insumos_agregados']['insumos_preparados'] as $k => $v) {
			// Opciones del select
				$opcional = (in_array(3, $v['select'])) ? 'selected' : '' ;
				$sin = (in_array(1, $v['select'])) ? 'selected' : '' ;
				$extra = (in_array(2, $v['select'])) ? 'selected' : '' ;
				$normal = (in_array(0, $v['select'])||(empty($opcional)&&empty($extra))) ? 'selected' : '' ;

				if ($v['sub_total']!='undefined') {
					$total_preparado+=$v['sub_total'];
				} ?>
				<tr>

				<!-- Guarda los opcionales al cargar --><?php
					if (!empty($v['id'])) { ?>
						<script>
							recetas.guardar_opcionales({preparado:1,id:<?php echo $v['id'] ?>, opcionales:$('#opcional_preparado_<?php echo $v['id'] ?>').val()});
						</script><?php
					} ?>
					<td align="center"><?php echo $v['id'] ?></td>
					<td><?php echo $v['nombre'] ?></td>
					<td align="center"><?php echo number_format($v['tiempo_hrs'], 2, ':', ''); ?></td>
					<td align="center" id="sub_total_preparado_<?php echo $v['id'] ?>"><?php echo $v['sub_total']; ?></td>
				</tr><?php
			} ?>
			<tr>
				<td colspan="4"></td>
				<td align="right">Costo total:</td>
				<td align="center">
					<strong style="font-size: 18px">
						<p id="total_preparados"><?php echo number_format($total_preparado, 2, '.', ''); ?></p>
					</strong>
				</td>
			</tr>
		</tbody>
	</table><?php
} ?>
<script>
// Actualiza el precio de venta
	$('#precio_venta').val(<?php echo $total+$total_preparado ?>);

// calcula la ganancia
	recetas.calcular_ganancia({porcentaje:$('#margen_ganancia').val()});
</script>
