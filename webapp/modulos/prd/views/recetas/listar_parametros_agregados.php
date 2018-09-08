<?php
// Valida que existan reservaciones
	if (empty($_SESSION['parametros_lab'])) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
					Seleccione un <strong>producto</strong>
			y establezca sus respectivos <strong>parámetros</strong>.
			</p>
		</blockquote><?php

		return 0;
	} ?>

<br /><?php
// Insumos normales
if (!empty($_SESSION['parametros_lab'])) { ?>
	<table id="tabla_insumos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<!--<th align="center"><strong>Cantidad</strong></th>-->
				<th><strong>Parametro</strong></th>
				<th><strong>Unidad</strong></th>
				<th style="width:20%"><strong>Lim. Inf.</strong></th>
				<th style="width:20%"><strong>Lim. Sup.</strong></th>
				<th><strong>Referencia</strong></th>
				<!--<th align="center"><strong>Costo Proveedor</strong></th>
				<th align="center"><strong>Costo Preparacion</strong></th>-->
			</tr>
		</thead>
		<tbody><?php
			foreach ($_SESSION['parametros_lab'] as $k => $v) {
			// Opciones del select
				$disabled_num = "";
				$disabled_ref = "";
				if ($v['is_numeric'] == 1)
					$disabled_ref = "disabled";
				else
					$disabled_num = "disabled";
				$opcional = (in_array(3, $v['select'])) ? 'selected' : '' ;
				$sin = (in_array(1, $v['select'])) ? 'selected' : '' ;
				$extra = (in_array(2, $v['select'])) ? 'selected' : '' ;
				$normal = (in_array(0, $v['select']) || (empty($opcional) && empty($extra))) ? 'selected' : '' ;

				if ($v['sub_total']!='undefined') {
					$total+=$v['sub_total'];
				} ?>
				<tr>

				<!-- Guarda los opcionales al cargar -->
					<td><?php echo $v['parametro'] ?></td>
					<td><?php echo $v['unidad'] ?></td>
					<td>
						<?php
							if ($v['is_numeric'] == 1){
								$lim_inf = $v['lim_inf'];
								$lim_sup = $v['lim_sup'];
								$referencia = '';
							} else {
								$lim_inf = "N/A";
								$lim_sup = "N/A";
								$referencia = $v['referencia'];
							}
						?>
						<div class="input-group">
							<span class="input-group-addon"  id="loader_inf_<?php echo $v['id'] ?>"><i class="fa fa-slack"></i></span>
							<input type="text" id="cantidad_inf_<?php echo $v['id']?>" value="<?php echo $lim_inf ?>" class="form-control" <?php echo $disabled_num ?> onkeyup="recetas.asignar_referencias({id:<?php echo $v['id'] ?>,tipo_parametro: 'lim_inf', cantidad:$(this).val()})"/>
						</div>
					</td>
					<td>
						<div class="input-group">
							<span class="input-group-addon"  id="loader_sup_<?php echo $v['id'] ?>"><i class="fa fa-slack"></i></span>
							<input type="text" id="cantidad_sup_<?php echo $v['id']?>" value="<?php echo $lim_sup ?>" class="form-control" <?php echo $disabled_num ?> onkeyup="recetas.asignar_referencias({id:<?php echo $v['id'] ?>,tipo_parametro: 'lim_sup', cantidad:$(this).val()})"/>
						</div>
					</td>
					<td>
						<div class="input-group">
							<span class="input-group-addon"  id="loader_ref_<?php echo $v['id'] ?>"><i class="fa fa-slack"></i></span>
							<input type="text" id="referencia_<?php echo $v['id']?>" value="<?php echo $referencia ?>" class="form-control" <?php echo $disabled_ref ?> onkeyup="recetas.asignar_referencias({id:<?php echo $v['id'] ?>,tipo_parametro: 'referencia', cantidad:$(this).val()})"/>
						</div>
					</td>
				</tr><?php
			} ?>

		</tbody>
	</table><?php
}
?>
