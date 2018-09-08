<?php
// Valida que existan reservaciones
	if (empty($_SESSION['pasos'][$paso])) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				Seleccione un <strong>"producto"</strong>
				y	asígnele <strong>"procesos de producción"</strong> para agregarlos.
			</p>
		</blockquote><?php

		return 0;
	}
	 ?>

<br /><?php
// Insumos normales
if (!empty($_SESSION['pasos'])) { ?>
	<table id="tabla_insumos_agregados2" class="table table-striped table-bordered" cellspacing="0" width="100%" style="font-size: 11px;">
		<thead>
			<tr>
				<!--<th align="center"><strong>Cantidad</strong></th>-->
				<th align="center"><strong>Orden</strong></th>
				<th align="center"><strong>Estatus</strong></th>
				<th align="center"><strong>Tipo</strong></th>
				<th align="center"><strong>Acción</strong></th>

				<th><strong>Actividad</strong></th>
				<th><strong>Tiempo / Piezas</strong></th>
				<th><strong>Acciones</strong></th>
				<!--<th align="center"><strong>Costo Proveedor</strong></th>
				<th align="center"><strong>Costo Preparacion</strong></th>-->
			</tr>
		</thead>
		<tbody><?php
			foreach ($_SESSION['pasos'][$paso] as $k => $v) {
				?>
				<tr id="acc_<?php echo $v['idAccion'] ?>">

				<!-- Guarda los opcionales al cargar -->
					<td align="center"><?php echo $v['idAccion'] ?></td>
					<?php if($v['estatus']==1){ ?>
					<td align="center">Activo</td>
					<?php }else{ ?>
					<td align="center">Inactivo</td>
					<?php } ?>
					<?php if($v['tipo']==1){ ?>
					<td align="center">Secuencial</td>
					<?php }else{ ?>
					<td align="center">No secuencial</td>
					<?php } ?>
					<td><?php echo $v['nombreAccion'] ?> (<?php echo $v['alias'] ?>)</td>
					<?php if($v['actividad']==1){ ?>
					<td align="center">Duracion</td>
					<?php }else{ ?>
					<td align="center">Piezas</td>
					<?php } ?>
					<td align="center"><?php echo $v['alias_hr']; ?></td>
					<td><button onclick="recetas.removerAccion('<?php echo $paso ?>',<?php echo $v['idAccion'] ?>);" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> Remover</button></td>
				</tr><?php
			} ?>

		</tbody>
	</table><?php
}

// Insumos preparados
?>

