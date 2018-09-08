<?php
// Valida que existan reservaciones
	if (empty($reservaciones)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
		
<table id="tabla_reservaciones" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th align="center"><strong>#</strong></th>
			<th><strong>Cliente</strong></th>
			<th align="center"><strong>Fecha / Hora</strong></th>
			<th><strong>Descripcion</strong></th>
			<th><strong>Mesa</strong></th>
			<th align="center"><strong><i class="fa fa-check"></i></strong></th>
			<th align="center"><strong><i class="fa fa-pencil"></i></strong></th>
			<th align="center"><strong><i class="fa fa-trash"></i></strong></th>
		</tr>
	</thead>
	<tbody><?php
		// $reservaciones es un array con las reservaciones que viene desde el controlador
			foreach ($reservaciones as $key => $value) {
				if ($value['activo']==1) {
					$estatus='Activa';
				}
				
				if($value['activo']==0){
					$estatus='Cerrada';
				}
				
				if($value['activo']==2){
					$estatus='Cancelada';
				} ?>
				
				<tr id="tr_<?php echo $value['id'] ?>">
					<td align="center"><?php echo $value['id'] ?></td>
					<td><?php echo $value['cliente'] ?></td>
					<td align="center"><?php echo $value['inicio'] ?></td>
					<td><?php echo $value['descripcion'] ?></td>
					<td>
			        	<div class="input-group" id="td_select_<?php echo $value['id'] ?>">
							<span class="input-group-addon" id="loader_<?php echo $value['id'] ?>"><i class="fa fa-cutlery"></i></span>
							<select id="select_<?php echo $value['id'] ?>" onchange="reservaciones.asignar({cliente:<?php echo $value['idCliente'] ?>,des:'<?php echo $value['descripcion'] ?>',fecha:'<?php echo $value['inicio'] ?>', mesa:$('#select_<?php echo $value['id'] ?>').val(),id:<?php echo $value['id'] ?>})" class="selectpicker" data-width="80%" data-live-search="true">
								<option selected value="">- Asignar</option><?php
								
								session_start();
								foreach ($_SESSION['mesas'] as $k => $v) { ?>
									<option class="opcion_<?php echo $v['mesa'] ?>" value="<?php echo $v['mesa'] ?>">
										<?php echo $v['nombre'] ?>
									</option> <?php
								} ?>
							</select>
						</div>
					</td>
					<td align="center">
						<button id="btn_terminar_<?php echo $value['id'] ?>" style="display: none" class="btn btn-success btn-lg" title="Terminar reservacion" onclick="reservaciones.terminar({id:<?php echo $value['id'] ?>, mesa:$(this).attr('mesa')})">
							<i class="fa fa-check"></i>
						</button>
					</td>
					<td align="center">
						<button id="btn_editar_<?php echo $value['id'] ?>" class="btn btn-primary btn-lg" title="Editar reservacion"  data-toggle="modal" data-target="#modal_agregar_reservacion" onclick="reservaciones.abrir_reservacion({cliente:<?php echo $value['idCliente'] ?>,des:'<?php echo $value['descripcion'] ?>',fecha:'<?php echo $value['inicio'] ?>',funcion:'editar', id:<?php echo $value['id'] ?>,mesa:<?php echo $v['mesa'] ?>})">
							<i class="fa fa-pencil"></i>
						</button>
					</td>
					<td align="center">
						<button id="btn_eliminar_<?php echo $value['id'] ?>" class="btn btn-danger btn-lg" title="Cancelar reservacion" onclick="reservaciones.eliminar({id:<?php echo $value['id'] ?>,mesa:<?php echo $v['mesa'] ?>})">
							<i class="fa fa-trash"></i>
						</button>
					</td>
				</tr> <?php
			} ?>
	</tbody
</table>