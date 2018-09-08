<?php
// Valida que existan reservaciones
	if (empty($datos['pasos_procesos_produccion'])) { ?>
		<!--<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				Seleccione un <strong>"producto"</strong>
				y	asígnele <strong>"procesos de producción"</strong> para agregarlos.
			</p>
		</blockquote>--><?php

		//return 0;
	} ?>

<br /><?php
// Insumos normales
if (!empty($datos['pasos_procesos_produccion'])) { echo "Hay info";?>
	<div class="col-md-12 col-sm-12">
			<div class="panel-group" id="accordion_insumos" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default">
						<div hrefer class="panel-heading" id="heading_insumos" role="tab" role="button" style="cursor: pointer" data-toggle="collapse" data-parent="#accordion_insumos" href="#tab_insumos" aria-controls="collapse_insumos" aria-expanded="true">
							<h4 class="panel-title">
								<strong>Pasos</strong>
							</h4>
						</div>
						<div id="tab_pasos" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_insumos">
							<div class="panel-body">
								<table id="tabla_pasos" class="table table-striped table-bordered" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th align="center"><strong>Orden</strong></th>
											<th><strong>Nombre</strong></th>
											<th align="center"><strong><i class="fa fa-check fa-lg"></i></strong></th>
										</tr>
									</thead>
									<tbody><?php
										foreach ($datos['pasos_procesos_produccion'] as $k => $v) { ?>
											<tr id="tr_<?php echo $v['id'] ?>" onclick="recetas.agregar_proceso({id:<?php echo $v['id'] ?>,nombre:'<?php echo $v['nombre'] ?>', tiempo_hrs: <?php echo $v['tiempo_hrs'] ?>, div:'div_insumos_agregados',check:$('#check_p_<?php echo $v['id'] ?>').prop('checked')})" style="cursor: pointer">
												<td align="center">
													<?php echo $v['id'] ?>
												</td>
												<td>
													<?php echo $v['nombre'] ?>
												</td>
												<td align="center">
													<input style="cursor: pointer" disabled="1" type="checkbox" id="check_p_<?php echo $v['id'] ?>" />
												</td>
											</tr><?php
										} ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
	</div>
	<?php
} else {
	echo "No hay info";
}
?>
