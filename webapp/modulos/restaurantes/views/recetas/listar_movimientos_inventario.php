<?php
// Valida que existan reservaciones
	if (empty($datos)) { ?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} 
?>
<div class="panel-group" id="accordion_graficas" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div 
        	class="panel-heading" 
        	role="tab"
        	data-toggle="collapse" 
        	data-parent="#accordion_graficas" 
        	href="#tab_graficas" 
        	aria-controls="collapse_graficas"
        	style="cursor: pointer">
            <h4 class="panel-title"><strong><i class="fa fa-pie-chart"></i> Graficas</strong></h4>
        </div>
        <div id="tab_graficas" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_tab_graficas">
            <div class="panel-body">
				<div class="row">
					<div id="grafica_actividades_barras" class="col-xs-8 col-xs-offset-2" style="height: 40%">
						<!-- En esta div se carga la grafica de barras -->
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<table class="table table-striped table-bordered" id="tabla_control_insumos">
    <thead>
        <tr>
            <th>Codigo</th>
            <th>Nombre</th>
            <th>Unidad</th>
            <th align="center">Entradas</th>
            <th align="center">Salidas</th>
            <th align="center">Existencias</th>
        </tr>
    </thead>
	<tbody>	<?php
		foreach ($datos as $key => $value) { ?>

			<tr data-toggle="collapse" style="cursor: pointer" data-target="#accordion_<?php echo $key ?>" class="clickable">
	            <td><?php echo $value['codigo'] ?></td>
	            <?php $insumo=json_encode($value);
				$insumo=str_replace('"', "'", $insumo);?>		
	            <td><a onclick="recetas.verReceta(<?php echo $insumo  ?>);"><?php echo $value['nombre'] ?></a></td>
	            <td><?php echo $value['unidad'] ?></td>
	            <td align="center"><?php echo $value['total_entradas'] ?></td>
	            <td align="center"><?php echo $value['total_salidas'] ?></td>
	            <td align="center"><?php echo $value['total_entradas'] - $value['total_salidas'] ?></td>
	        </tr>
	        <tr id="accordion_<?php echo $key ?>" class="collapse">
	        	<td colspan="6">
		        	<table class="table table-striped table-bordered">
		        		<tr>
		        			<th>Producto</th>
		        			<th align="center">Entradas</th>
		        			<th align="center">Salidas</th>
		        		</tr><?php
		        		
				        foreach ($value['origenes'] as $k => $v) { ?>
							<tr>
								<td><?php echo $k ?></td>
								<td align="center"><?php echo $v['entradas'] ?></td>
								<td align="center"><?php echo $v['salidas'] ?></td>
							</tr><?php
						} ?>
		        	</table>
	        	</td>
	        </tr><?php
		} ?>
	</tbody>
</table>
<script>
	comandas.graficar({
		div:'grafica_actividades', 
		x:'productos', 
		y:'cantidad', 
		label:'Insumos mas consumidos', 
		barras:<?php echo json_encode($barras) ?>
	});
</script>