<script>
	comandera.datos_mesa_comanda['num_personas']  = 0;
</script><?php

	$posicion_color = 1;
	
	foreach ($objeto['personas'] as $key => $value) {
		if($posicion_color > 6){
			$posicion_color = 1;
		}
		
		$colarray[1] = 'background-color: #4a72b2';
		$colarray[2] = 'background-color: #e6b54a';
		$colarray[3] = 'background-color: #87868a';
		$colarray[4] = 'background-color: #6eaa6f';
		$colarray[5] = 'background-color: #76aadb';
		$colarray[6] = 'background-color: #f4e16a';
		
		$col = $colarray[$posicion_color];
		
		$posicion_color ++; ?>
		
		<div class="pull-left" style="padding:5px">
			<button 
				id="persona_<?php echo  $value['npersona'] ?>" 
				type="button" 
				class="btn btn-lg"
				data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
				onclick="comandera.listar_pedidos_persona({
						persona: <?php echo $value['npersona'] ?>, 
						id_comanda: <?php echo $objeto['id_comanda'] ?>,
						div: 'div_listar_pedidos_persona'
				})"
				style="font-size: 25px;<?php echo $col ?>">
				<i class="fa fa-pencil-square-o"></i> <?php echo  $value['npersona'] ?>
			</button>
		</div>
		<script>
			comandera.datos_mesa_comanda['num_personas'] ++;
			comandera.datos_mesa_comanda['posicion_color'] ++; 
		</script><?php
	} ?>
