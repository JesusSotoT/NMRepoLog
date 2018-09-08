<div class="row">
	<table id="tabla_dividir" style="text-align:center; width: 100%;" cellspacing="0">
		<thead>
			<tr>
				<th align="center"></th>
				<th align="center"></th>
				<th align="center"></th>
				<th style="text-align:center;"><strong>%</strong></th>
				<th style="text-align:center;"><strong>$</strong></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$clases[0]='info';
			$clases[1]='success';
			$clases[2]='warning';
			$clases[3]='primary';
			$clases[4]='danger';
								
			$posi=0;
			
		// $_SESSION['cerrar_personalizado']['num_personas'] es una variable del controlador
			for ($i=0; $i < $_SESSION['cerrar_personalizado']['num_personas']; $i++) { ?>
				<tr style="border: 5px solid; border-color: transparent;" id="tr_servicio_domicilio_<?php echo $value['id'] ?>">
					<td style="text-align:center;">
						<button type="button"
							class="btn btn-<?php echo $clases[$posi] ?> btn-lg">
							<i class="fa fa-user"></i> <?php echo $i+1 ?>
						</button>
					</td>
					<td style="text-align:center;" id="pago_<?php echo $i+1?>">$ 0.00</td>
					<td style="text-align:center;" id="per_<?php echo $i+1?>">$ 0.00</td>
					<td  style="text-align:center;"><input onkeyup="comandas.porcentaje_pre(<?php echo $i+1?>)" style="width: 90%; display:inline;"type="number" max="100" id="porcentaje_<?php echo $i+1 ?>" class="form-control"></td>
					<td style="text-align:center;"><input onkeyup="comandas.porcentaje_pre(<?php echo $i+1?>)" style="width: 90%; display:inline;" type="number" class="form-control" id="peso_<?php echo $i+1 ?>"</td>
				</tr><?php
				
				$posi ++;
				$posi = ($posi > 4) ? 0 : $posi;
									
			} ?>
			
		</tbody>
	</table>

	
</div>
<script type="text/javascript">
	comandas.llenar_campos_dividir();
</script>