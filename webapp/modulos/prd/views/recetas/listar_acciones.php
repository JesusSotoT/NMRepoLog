<?php
// Valida que existan reservaciones
	if (empty($_SESSION['produccion_acciones'])) { ?>
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
if (!empty($_SESSION['produccion_acciones'])) { ?>
	<table id="tabla_insumos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 12px;">
		<thead>
			<tr>
				<!--<th align="center"><strong>Cantidad</strong></th>-->
				<th align="center"><strong>Orden</strong></th>
				<th align="center"><strong>Estatus</strong></th>
				<th align="center"><strong>Acción</strong></th>
				<th align="center"><strong>Tipo</strong></th>
				<th align="center"><strong>Alias</strong></th>
				<th align="center"><strong>Actividad</strong></th>
				<th><strong>Tiempo / Piezas</strong></th>
				<!--<th align="center"><strong>Costo Proveedor</strong></th>
				<th align="center"><strong>Costo Preparacion</strong></th>-->
			</tr>
		</thead>
		<tbody><?php
			foreach ($_SESSION['produccion_acciones'] as $k => $v) {
				?>
				<tr id="<?php echo $v['id'] ?>">

				<!-- Guarda los opcionales al cargar -->
					<td align="center"><?php echo $v['id'] ?></td>
					<td><input id="sta" type="checkbox" name="sta" value="1" style="cursor:pointer;" checked>Activo</td>
					<td><?php echo $v['nombre'] ?></td>
					<td id="tdtipo">
						<select id="tipo" class="form-control">
						<option value="1" selected="selected">Secuencial</option>
						<option value="2">No secuencial</option>
						</select>
					</td>
					<td><input class="form-control" id="alias" style="width: 100%;" type="text" value="<?php echo $v['nombre'] ?>" /></td>
					<td id="tdactividad">
						<select id="actividad_<?php echo $v['id'] ?>" class="form-control" onchange="cambiaActividad(<?php echo $v['id'] ?>);">
						<option value="1" selected="selected">Duracion</option>
						<option value="2">Piezas</option>
						</select>
					</td>
					<td id="actinput_<?php echo $v['id'] ?>" align="center">
					<input id="alias_hr" class="alias_hrs" style="width: 100%;" type="text" value="" />
					
						
					</td>
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
