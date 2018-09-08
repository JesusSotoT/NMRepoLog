<?php
// Valida que existan reservaciones
	if (empty($reservaciones)) {?>
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
					<div id="grafica_reservaciones_dona" class="col-xs-4" style="height: 40%">
						<!-- En esta div se carga la grafica de dona -->
					</div>
					<div id="grafica_reservaciones_lineal" class="col-xs-8" style="height: 40%">
						<!-- En esta div se carga la grafica de barras -->
					</div>
				</div>
            </div>
        </div>
    </div>
</div>
<table id="tabla_reservaciones_listar" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th><strong>Reservacion</strong></th>
			<th><strong>Sucursal</strong></th>
			<th><strong>Cliente</strong></th>
			<th><strong>Inicio</strong></th>
			<th><strong>Fin</strong></th>
			<th><strong>Monto</strong></th>
			<th><strong>Estatus</strong></th>
		</tr>
	</thead>
	<tbody><?php
	// $promedios es un array con los promedios por comensal que viene desde el controlador
		foreach ($reservaciones as $key => $value) {
			if ($value['activo']==1) {
				$estatus='Activa';
				$class='warning';
			}
			
			if($value['activo']==0){
				$estatus='Cerrada';
				$class='success';
			}
			
			if($value['activo']==2){
				$estatus='Cancelada';
				$class='danger';
			} ?>
			
			<tr class="<?php echo $class ?>">
				<td><?php echo $value['id'] ?></td>
				<td><?php echo $value['sucursal'] ?></td>
				<td>
					<a onclick="window.parent.agregatab('../../modulos/appministra/index.php?c=cuentas&f=cuentasxcobrar&;id=<?php echo $value['id_cliente'] ?>','Detalle del cliente','',1675);" href="#" class="active"><?php echo $value['cliente'] ?></a>
					
				</td>
				<td><?php echo $value['inicio'] ?></td>
				<td><?php echo $value['fin'] ?></td>
				<td><?php
					if ($value['total']) { ?>
					<a onclick="window.parent.agregatab('../../modulos/pos/index.php?c=caja&f=ventasGrid&id=<?php echo $value['id_venta'] ?>','Ventas','',2106);" href="#" class="active">$ <?php echo $value['total'] ?></a>
						<?php
					} ?>
				</td>
				<td><?php echo $estatus ?></td>
			</tr> <?php
		} ?>
	</tbody>
</table>
<script>
	reservaciones.graficar({
		div:'grafica_reservaciones',
		x:'inicio',
		y:'reservaciones',
		label:'Reservaciones',
		dona:<?php echo json_encode($dona) ?>,
		lineal:<?php echo json_encode($lineal) ?>
	});
</script>