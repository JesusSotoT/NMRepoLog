<?php
// Valida que existan promedios en las fechas seleccionadas
	if (empty($promedios)) { ?>
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
					<div id="grafica_promedios_lineal" class="col-xs-12" style="height: 40%">
						<!-- En esta div se carga la grafica de barras -->
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
	<div class="col-md-6"></div>
	<div class="col-md-6" id="total" align="right">
		<!-- En esta div se carga el total de utilidad -->
	</div>
</div><br />
<table id="tabla_promedios" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th><strong>Comanda</strong></th>
			<th><strong>Sucursal</strong></th>
			<th><strong>Comensales</strong></th>
			<th><strong>Fecha / Hora</strong></th>
			<th><strong>Empleado</strong></th>
			<th><strong>Promedio Comanda</strong></th>
		</tr>
	</thead>
	<tbody><?php
	// $promedios es un array con los promedios por comensal que viene desde el controlador
		foreach ($promedios as $key => $value) { 
			$promedio_general += $value['promedioComensal'];
			$num_comandas++; ?>
			<tr>
				<td><?php echo $value['id'] ?></td>
				<td><?php echo $value['sucursal'] ?></td>
				<td><?php echo $value['comensales'] ?></td>
				<td><?php echo $value['fecha'] ?></td>
				<td><?php echo $value['empleado'] ?></td>
				<td>$ <?php echo $value['promedioComensal'] ?></td>
			</tr> <?php
		} 
				
		$promedio_general /= $num_comandas; 
		$promedio_general = round($promedio_general, 2); ?>
	</tbody>	
</table>
<script>
	$("#total").html('<h4>Promedio general: <strong>$'+<?php echo $promedio_general ?>+'</strong><h4>');
	
	comandas.graficar({
		div:'grafica_promedios',
		x:'timestamp',
		y:'promedioComensal',
		label:'Promedio',
		lineal:<?php echo json_encode($lineal) ?>
	});
</script>