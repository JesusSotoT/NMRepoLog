<?php
// Valida que existan reservaciones
	if (empty($reservaciones)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
		
<table class="table table-striped table-bordered" cellspacing="0" width="100%">
	<tr>
		<td><strong>Cliente</strong></td>
		<td align="center"><strong>Hora llegada</strong></td>
		<td align="center"><strong>Tiempo de espera</strong></td>
		<td align="center"><strong>Asignar</strong></td>
		<!-- <td align="center"><strong><i class="fa fa-clock-o"></i></strong></td>
		<td align="center"><strong><i class="fa fa-trash"></i></strong></td> -->
	</tr><?php
// $reservaciones es un array con las reservaciones que viene desde el controlador
	date_default_timezone_set('America/Mexico_City');
	foreach ($reservaciones as $key => $value) { 
	// Calcula el tiempo
		// $tiempo = '';
		// $segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($value['inicio']);
		// $horas = floor($segundos / 3600);
		// $minutos = floor(($segundos - ($horas * 3600)) / 60);
// 		
	// // Formateamos el tiempo y lo agregamos al array
		// $horas = ($horas < 10) ? '0'.$horas : $horas ;
		// $minutos = ($minutos < 10) ? '0'.$minutos : $minutos ;
// 	
		// $tiempo = $horas.":".$minutos; 
		
		$tiempo = substr($value['inicio'], 11, 8);?>
		<tr>
			<td><?php echo $value['cliente'] ?></td>
			<td align="center"><?php echo $tiempo ?></td>
			<?php $segundos = strtotime(date('Y-m-d H:i:s')) - strtotime($tiempo);
			$tiempo_espera = floor($segundos / 60);?>
			<td align="center"><?php echo $tiempo_espera ?> min</td>
			<td align="center"><button class="btn btn-default" toggle="modal" data-target="#modal_asignar_mesa" onclick="reservaciones.vista_asignar_mesa({
														div: 'div_asignar_mesa',
														id: <?php echo $value['id'] ?>,
														cliente:<?php echo $value['idCliente'] ?>,
														des:'<?php echo $value['descripcion'] ?>',
														fecha:'<?php echo $value['inicio'] ?>',
														num_per: '<?php echo $value['num_personas']?>'
													})"><i class="fa fa-cutlery"></i></button></td>
			<!-- <td>
				<div class="input-group" id="td_select_<?php echo $value['id'] ?>">
					<span class="input-group-addon" id="loader_<?php echo $value['id'] ?>"><i class="fa fa-cutlery"></i></span>
					<select id="select_<?php echo $value['id'] ?>" onchange="reservaciones.asignar({cliente:<?php echo $value['idCliente'] ?>,des:'<?php echo $value['descripcion'] ?>',fecha:'<?php echo $value['inicio'] ?>', mesa:$('#select_<?php echo $value['id'] ?>').val(),id:<?php echo $value['id'] ?>})" class="selectpicker" data-width="80%" data-live-search="true">
						<option selected value="">- Asignar</option><?php
						session_start();
							foreach ($_SESSION['mesas'] as $k => $v) { ?>
								<option class="opcion_<?php echo $v['mesa'] ?>" value="<?php echo $v['mesa'] ?>">
									[<?php echo $v['mesa'] ?>] <?php echo $v['nombre'] ?>
								</option> <?php
							} ?>
					</select>
				</div>
			</td>
			<td align="center">
				<button id="btn_eliminar_<?php echo $value['id'] ?>" class="btn btn-danger btn-lg" title="Cancelar de la lista" onclick="reservaciones.eliminar({espera:1, id:<?php echo $value['id'] ?>, mesa:<?php echo $v['mesa'] ?>})">
					<i class="fa fa-trash"></i>
				</button>
			</td> -->
		</tr> <?php
	} ?>
</table>