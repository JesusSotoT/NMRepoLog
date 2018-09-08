<?php
// Valida que existan actividades
	if (empty($comandas)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
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
					<div id="grafica_comensales_dona" class="col-xs-4" style="height: 40%">
						<!-- En esta div se carga la grafica de dona -->
					</div>
					<div id="grafica_comensales_lineal" class="col-xs-8" style="height: 40%">
						<!-- En esta div se carga la grafica de barras -->
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<table id="tabla_comensalesXmesa" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<td align="center"><strong>Duracion</strong></td>
			<td align="center"><strong>Sucursal</strong></td>
			<td align="center"><strong>Mesa</strong></td>
			<td ><strong>Empleado</strong></td>
			<td align="center"><strong>Comensales</strong></td>
			<td align="center"><strong>Fecha / Hora</strong></td>
		</tr>
	</thead>
	<tbody><?php
	// $comandas es un array con las comandas viene desde el controlador
		foreach ($comandas as $key => $value) { ?>
			<tr>
				<td align="center"><?php echo $value['duracion'] ?></td>
				<td align="center"><?php echo $value['sucursal'] ?></td>
				<td align="center"><?php echo $value['nombre_mesa'] ?></td>
				<td><?php echo $value['usuario'] ?></td>
				<td align="center"><?php echo $value['personas'] ?></td>
				<td align="center"><?php echo $value['timestamp'] ?></td>
			</tr> <?php
		} ?>
	</tbody>
</table>
<script>
	comandas.graficar({
		div:'grafica_comensales', 
		x:'timestamp', y:'personas', 
		label:'Comensales', 
		dona:<?php echo json_encode($dona) ?>, 
		lineal:<?php echo json_encode($lineal) ?>
	});
</script>