<?php
// Valida que existan reservaciones
	if (empty($_SESSION['insumos_agregados']['insumos'])&&empty($_SESSION['insumos_agregados']['insumos_preparados'])) { ?>
		<br /><br />
		<blockquote style="font-size: 16px">
			<p>
				Selecciona <strong>"Insumos"</strong>
				o <strong>"Insumos preparados"</strong> para agregarlos.
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
				<th align="center"><strong>Cantidad</strong></th>
				<th align="center"><strong>Modificador</strong></th>
				<th align="center"><strong>Insumo</strong></th>
				<th><strong>Unidad</strong></th>
				<th align="center"><strong>Costo Proveedor</strong></th>
				<th align="center"><strong>Costear</strong></th>
				<th align="center"><strong>Costo Preparacion</strong></th>
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
					<td align="center" style="width: 120px">
						<div class="input-group">
							<span class="input-group-addon"  id="loader_<?php echo $v['id'] ?>"><i class="fa fa-slack"></i></span>
							<input 
								onchange="recetas.calcular_precio({
									id:<?php echo $v['id'] ?>,
									cantidad:$(this).val()
								})" 
								type="number" 
								style="width: 90px"
								value="<?php echo $v['cantidad'] ?>" 
								id="cantidad_<?php echo $v['id'] ?>" 
								class="form-control"/>
						</div>
					</td>
					<td style="width: 15%">
						<div class="input-group">
							<span id="loader_select_<?php echo $v['id'] ?>" class="input-group-addon"><i class="fa fa-list-ul"></i></span>
							<select 
								onchange="recetas.guardar_opcionales({
									id:<?php echo $v['id'] ?>, 
									opcionales:$('#opcional_<?php echo $v['id'] ?>').val()
								});" 
								data-selected-text-format="count" 
								id="opcional_<?php echo $v['id'] ?>" 
								class="selectpicker" 
								multiple data-width="50px">
								<option <?php echo $normal ?> value="0">Normal</option>
								<option <?php echo $sin ?> value="1">Sin</option>
								<option <?php echo $extra ?> value="2">Extra</option>
								<option <?php echo $opcional ?> value="3">Opcional</option>
							</select>
						</div>
					</td>
				<!-- Guarda los opcionales al cargar --><?php
					if (!empty($v['id'])) { ?>
						<script>
							recetas.guardar_opcionales({id:<?php echo $v['id'] ?>, opcionales:$('#opcional_<?php echo $v['id'] ?>').val()});
						</script><?php
					} ?>
					<td align="center"><?php echo $v['nombre'] ?></td>
					<td><?php echo $v['unidad'] ?></td>
					<td align="center">
						<select onchange="recetas.calcular_precio({
									id:<?php echo $v['id'] ?>,
									cantidad:$('#cantidad_<?php echo $v['id'] ?>').val()
								})" class="form-control" id="select_costo_proveedor_<?php echo $v['id'] ?>">
							<?php if(empty($v['ids_proveedor'])){ ?>
								<option value="-1">$ <?php echo $v['costo'] ?></option>
							<?php } else { 
								$ids_proveedor = explode( ',', $v['ids_proveedor']);
								$costos = explode( ',', $v['costos']);
								?>
								<?php foreach ($ids_proveedor as $k5 => $v5) { ?>
									<option <?php if($v['proveedor_select'] == $v5) { ?> selected <?php } ?> value="<?php echo $v5?>">$ <?php echo $costos[$k5] ?></option>
								<?php }?>
							<?php } ?>
						</select>
					</td>
					<td align="center">
						<input 
							style="cursor: pointer"
							onclick="recetas.costear({
								id: <?php echo $v['id'] ?>,
								check: $('#check_costeo_<?php echo $v['idProducto'] ?>').prop('checked')
							})"
							type="checkbox" <?php
							if ($v['costear'] != 2) { ?>
								checked="<?php echo $v['costear'] ?>"<?php
							} ?>
							id="check_costeo_<?php echo $v['idProducto'] ?>" />
					</td>
					<td align="center" id="sub_total_<?php echo $v['id'] ?>"><?php
					
							 echo $v['costear']."---<-<";
						if ($v['costear'] != 2) {
							 echo $v['sub_total'];
						} ?>
					</td>
				</tr>
				<script type="text/javascript">recetas.calcular_precio({
									id:<?php echo $v['id'] ?>,
									cantidad:$('#cantidad_<?php echo $v['id'] ?>').val()
								});</script>
				<?php
			} ?>
			<tr>
				<td colspan="5"></td>
				<td align="right">Costo total:</td>
				<td align="center">
					<strong style="font-size: 18px">
						<p id="total"><?php echo number_format($total, 2, '.', ''); ?></p>
					</strong>
				</td>
			</tr>
		</tbody>
	</table><?php
}

// Insumos preparados
if (!empty($_SESSION['insumos_agregados']['insumos_preparados'])) { ?>
	<table id="tabla_insumos_agregados" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th align="center" class="info"><strong>Cantidad</strong></th>
				<th align="center" class="info"><strong>Modificador</strong></th>
				<th align="center" class="info"><strong>Insumo</strong></th>
				<th class="info"><strong>Unidad</strong></th>
				<th align="center" class="info"><strong>Costo Proveedor</strong></th>
				<th align="center" class="info"><strong>Costear</strong></th>
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
					<td align="center" style="width: 30%">
						<div class="input-group">
							<span class="input-group-addon"  id="loader_<?php echo $v['id'] ?>"><i class="fa fa-slack"></i></span>
							<input onchange="recetas.calcular_precio({preparado:1,id:<?php echo $v['id'] ?>,cantidad:$(this).val()})" type="number" value="<?php echo $v['cantidad'] ?>" id="cantidad_preparado_<?php echo $v['id'] ?>" class="form-control"/>
						</div>
					</td>
					<td style="width: 15%">
						<div class="input-group">
							<span id="loader_select_preparado_<?php echo $v['id'] ?>" class="input-group-addon"><i class="fa fa-list-ul"></i></span>
							<select onchange="recetas.guardar_opcionales({preparado:1,id:<?php echo $v['id'] ?>, opcionales:$('#opcional_preparado_<?php echo $v['id'] ?>').val()})" data-selected-text-format="count" id="opcional_preparado_<?php echo $v['id'] ?>" class="selectpicker" multiple data-width="50px">
								<option <?php echo $normal ?> value="0">Normal</option>
								<option <?php echo $sin ?> value="1">Sin</option>
								<option <?php echo $extra ?> value="2">Extra</option>
								<option <?php echo $opcional ?> value="3">Opcional</option>
							</select>
						</div>
					</td>
				<!-- Guarda los opcionales al cargar --><?php
					if (!empty($v['id'])) { ?>
						<script>
							recetas.guardar_opcionales({preparado:1,id:<?php echo $v['id'] ?>, opcionales:$('#opcional_preparado_<?php echo $v['id'] ?>').val()});
						</script><?php
					} ?>
					<td align="center"><?php echo $v['nombre'] ?></td>
					<td><?php echo $v['unidad'] ?></td>
					<td align="center"> $ <?php echo number_format($v['costo'], 2, '.', ''); ?></td>
					<td align="center">
						<input 
							style="cursor: pointer"
							onclick="recetas.costear({
								preparado: 1,
								id: <?php echo $v['id'] ?>,
								check: $('#check_costeo_<?php echo $v['id'] ?>').prop('checked')
							})"
							type="checkbox" <?php
							if ($v['costear'] != 2) { ?>
								checked="<?php echo $v['costear'] ?>"<?php
							} ?>
							id="check_costeo_<?php echo $v['id'] ?>" />
					</td>
					<td align="center" id="sub_total_preparado_<?php echo $v['id'] ?>"><?php
						if ($v['costear'] != 2) {
							 echo $v['sub_total'];
						} ?>
					</td>
				</tr>
				<script type="text/javascript">recetas.calcular_precio({preparado:1,id:<?php echo $v['id'] ?>,cantidad:$("#cantidad_preparado_<?php echo $v['id'] ?>").val()});</script>
				<?php
			} ?>
			<tr>
				<td colspan="5"></td>
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