<?php
// Valida que existan reservaciones
	if (empty($_SESSION['formulario_lab_campo'])) { ?>
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
if (!empty($_SESSION['formulario_lab_campo'])) { ?>
	<table id="tabla_insumos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<!--<th align="center"><strong>Cantidad</strong></th>-->
				<th><strong>Parametro</strong></th>
				<th><strong>Unidad</strong></th>
				<th style="width:20%"><strong>Límite Inferior</strong></th>
				<th style="width:20%"><strong>Límite Superior</strong></th>
				<th><strong>Referencia</strong></th>
				<th><strong>Valor</strong></th>
				<!--<th align="center"><strong>Costo Proveedor</strong></th>
				<th align="center"><strong>Costo Preparacion</strong></th>-->
			</tr>
		</thead>
		<tbody><?php
			foreach ($_SESSION['formulario_lab_campo'] as $k => $v) {
			// Opciones del select
				$lim_inf = "N/A";
				$lim_sup = "N/A";
				$referencia = "N/A";
				$value_default = "";
				$input_type = "text";

				if ($v['is_numeric'] == 0)
					$referencia = $v['referencia'];
				else {
					$lim_inf = $v['lim_inf'];
					$lim_sup = $v['lim_sup'];
					$value_default = "0";
					$input_type="number";
				}

				if ($v['sub_total']!='undefined') {
					$total+=$v['sub_total'];
				}

				if ($v['is_numeric'] == 1){
					$bold_1 = "bold";
					$bold_2 = "bold";
					$bold_3 = "normal";
				} else {
					$bold_1 = "normal";
					$bold_2 = "normal";
					$bold_3 = "bold";
				}

				?>
				<tr>

					<td><?php echo $v['parametro'] ?></td>
					<td><?php echo $v['unidad'] ?></td>
					<td style="font-weight:<? echo $bold_1?>"><?php echo $lim_inf ?></td>
					<td style="font-weight:<? echo $bold_2?>"><?php echo $lim_sup ?></td>
					<td style="font-weight:<? echo $bold_3?>"><?php echo $referencia ?></td>
					<td>
						<div class="input-group">
							<span class="input-group-addon"  id="loader_inf_<?php echo $v['id'] ?>"><i class="fa fa-slack"></i></span>
							<input type="<?php echo $input_type?>" id="cantidad_inf_<?php echo $v['id']?>" value="<?php echo $value_default ?>" class="form-control" onkeyup="recetas.asignar_valor_lab({id:<?php echo $v['id'] ?>, valor:$(this).val()})"/>
						</div>
					</td>

				</tr><?php
			} ?>

		</tbody>
	</table><?php
}
?>
