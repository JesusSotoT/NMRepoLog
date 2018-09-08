<table class="table table-bordered table-striped">
	<tr>
		<td align="center"><strong>Table / Home</strong></td>
		<td><strong>Waiter / Client</strong></td>
		<td align="center"><strong>People</strong></td>
		<td align="center"><strong>Status</strong></td>
		<td align="center"><strong>Total</strong></td>
		<td align="center"><strong><i class="fa fa-clock-o fa-lg"></i></strong></td>
		<td align="center"><strong><i class="fa fa-object-group"></i></strong></td>
	</tr><?php
	
// $comandas es un array con las comandas viene desde el controlador
	foreach ($_SESSION['tables'] as $key => $row) {
		switch($row['tipo']){
		//** Mesa normal(Individuales o juntas)
			case 0:
			// Mesa individual
				if($row['idmesas']==''){ ?>
					<tr
						onclick="comandera.mandar_mesa_comandera({
							id_mesa: <?php echo $row['mesa'] ?>,
							tipo: 0,
							nombre_mesa_2: '<?php echo $row['nombre_mesa'] ?>',
							id_comanda: $(this).attr('id_comanda'),
							tipo_operacion: comandera.datos_mesa_comanda.tipo_operacion
						})"
						id="mesa_<?php echo $row['mesa'] ?>"
						id_comanda="<?php echo $row['idcomanda'] ?>"
						data-toggle="modal" 
						data-target="#modal_comandera"
						style="cursor: pointer">
						<td align="center"><?php 
							echo $row['nombre'] ?>
						</td>
						<td ><i class="fa fa-hand-o-up text-primary"></i> <?php echo $row['mesero'] ?></td>
						<td align="center"><i class="fa fa-user text-primary"></i> <?php echo $row['personas'] ?></td>
						<td class="GtableTable0" id="mesa_<?php echo $row['mesa'] ?>">
							<div class="GtableTableIcon" align="center" style="width: 100%"></div>
						</td>
						<td align="center" id="div_total_<?php echo $row['mesa'] ?>"></td>
						<td align="center" id="div_tiempo_<?php echo $row['mesa'] ?>"></td>
						<td align="center">
							<i class="fa fa-object-group"></i>
						</td>
					</tr><?php
			// * Mesa compuesta
				}else{
					$ids=explode(',',$row['idmesas']);
					$personas=explode(',',$row['mpersonas']);
					$size=count($ids); 
					$total_personas=0;
			
				// Calcula el total de personas
					foreach ($personas as $key => $value) {
						$total_personas+=$value;
					} ?>
					
					<tr
						onclick="comandera.mandar_mesa_comandera({
							id_mesa: <?php echo $row['mesa'] ?>,
							tipo: 0,
							nombre_mesa_2: '<?php echo $row['nombre_mesa'] ?>',
							id_comanda: $(this).attr('id_comanda'),
							tipo_operacion: comandera.datos_mesa_comanda.tipo_operacion
						})"
						id="mesa_<?php echo $row['mesa'] ?>"
						id_comanda="<?php echo $row['idcomanda'] ?>"
						data-toggle="modal" 
						data-target="#modal_comandera" 
						class="info" 
						style="cursor: pointer">
						<td align="center"><?php 
							echo '['.$row['idmesas'].'] *Compuesta*'.$row['nombre'] ?>
						</td>
						<td><i class="fa fa-hand-o-up text-primary"></i> <?php echo $row['mesero'] ?></td>
						<td align="center"><?php echo $total_personas ?></td>
						<td class="GtableTable0" id="mesa_<?php echo $row['mesa'] ?>">
							<div class="GtableTableIcon" align="center" style="width: 100%"></div>
						</td>
						<td align="center" id="div_total_<?php echo $row['mesa'] ?>"></td>
						<td align="center" id="div_tiempo_<?php echo $row['mesa'] ?>"></td>
						<td align="center">
							<i class="fa fa-object-ungroup"></i>
						</td>
					</tr><?php
				} //Else
			break;//** FIN Mesa normal(Individuales o juntas)
							
		//** Para llevar
			case 1: ?>
				<tr 
					onclick="comandera.mandar_mesa_comandera({
						id_mesa: <?php echo $row['mesa'] ?>,
						tipo: 1,
						nombre: '<?php echo  $row['nombre'] ?>',
						domicilio: '<?php echo $row['domicilio'] ?>',
						nombre_mesa_2: '<?php echo $row['nombre_mesa'] ?>',
						tel: '<?php echo $row['tel'] ?>',
						id_comanda: $(this).attr('id_comanda'),
						tipo_operacion: comandera.datos_mesa_comanda.tipo_operacion
					})"
					id="mesa_<?php echo $row['mesa'] ?>"
					id_comanda="<?php echo $row['idcomanda'] ?>"
					data-toggle="modal" 
					data-target="#modal_comandera"
					class="danger" style="cursor: pointer">
					<td align="center"><?php 
						echo '<i class="fa fa-home"></i> '.$row['domicilio'] ?>
					</td>
					<td><i class="fa fa-user text-primary"></i> <?php echo $row['nombre'] ?></td>
					<td align="center"><i class="fa fa-user text-primary"></i> <?php echo $row['personas'] ?></td>
					<td class="GtableTable0" id="mesa_<?php echo $row['mesa'] ?>">
						<div class="GtableTableIcon" align="center" style="width: 100%"></div>
					</td>
					<td align="center" id="div_total_<?php echo $row['mesa'] ?>"></td>
					<td align="center" id="div_tiempo_<?php echo $row['mesa'] ?>"></td>
					<td align="center">
						<i class="fa fa-shopping-basket"></i>
					</td>
				</tr><?php
			break;//** FIN Para llevar
						
		//** Servicio a domicilio
			case 2: ?>
				<tr 
					onclick="comandera.mandar_mesa_comandera({
						id_mesa: <?php echo $row['mesa'] ?>,
						tipo: 2,
						nombre: '<?php echo  $row['nombre'] ?>',
						domicilio: '<?php echo $row['domicilio'] ?>',
						nombre_mesa_2: '<?php echo $row['nombre_mesa'] ?>',
						tel: '<?php echo $row['tel'] ?>',
						id_comanda: $(this).attr('id_comanda'),
						tipo_operacion: comandera.datos_mesa_comanda.tipo_operacion
					})"
					id="mesa_<?php echo $row['mesa'] ?>"
					id_comanda="<?php echo $row['idcomanda'] ?>"
					data-toggle="modal" 
					data-target="#modal_comandera" 
					class="info" 
					style="background-color: AEC6CF; cursor: pointer">
					<td align="center"><?php 
						echo '<i class="fa fa-home"></i> '.$row['domicilio'] ?>
					</td>
					<td ><i class="fa fa-user text-primary"></i> <?php echo $row['nombre'] ?></td>
					<td align="center"><i class="fa fa-user text-primary"></i> <?php echo $row['personas'] ?></td>
					<td class="GtableTable0" id="mesa_<?php echo $row['mesa'] ?>">
						<div class="GtableTableIcon" align="center" style="width: 100%"></div>
					</td>
					<td align="center" id="div_total_<?php echo $row['mesa'] ?>"></td>
					<td align="center" id="div_tiempo_<?php echo $row['mesa'] ?>"></td>
					<td align="center">
						<i class="fa fa-motorcycle"></i>
					</td>
				</tr><?php
			
			break;//** FIN Servicio a domicilio
		} // Switch
	}// Fin foreach

// Funciones iniciales	
	echo '	<script>
				reloadTableEvents();
				setTimeout(countTables,10000);
				info_comandas();
			</script>'; ?>
</table>