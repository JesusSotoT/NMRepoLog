<?php
// Valida que existan reservaciones
	if (empty($_SESSION['procesos_produccion'])) { ?>
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
if (!empty($_SESSION['procesos_produccion'])) { ?>
	<table id="tabla_insumos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<!--<th align="center"><strong>Cantidad</strong></th>-->
				<th align="center"><strong>Orden</strong></th>
				<th align="center"><strong>Acción</strong></th>
				<th><strong>Tiempo horas</strong></th>
				<!--<th align="center"><strong>Costo Proveedor</strong></th>
				<th align="center"><strong>Costo Preparacion</strong></th>-->
			</tr>
		</thead>
		<tbody><?php
			foreach ($_SESSION['procesos_produccion'] as $k => $v) {
				?>
				<tr>

				<!-- Guarda los opcionales al cargar -->
					<td align="center"><?php echo $v['id'] ?></td>
					<td><?php echo $v['nombre'] ?></td>
					<td align="center"><?php echo number_format($v['tiempo_hrs'], 2, ':', ''); ?></td>
				</tr><?php
			} ?>

		</tbody>
	</table><?php
}

// Insumos preparados
?>
<script>
// Actualiza el precio de venta
	$('#precio_venta').val(<?php echo $total+$total_preparado ?>);

// calcula la ganancia
	//recetas.calcular_ganancia({porcentaje:$('#margen_ganancia').val()});
</script>
