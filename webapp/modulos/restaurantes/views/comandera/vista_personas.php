<script>
	comandera.datos_mesa_comanda['num_personas']  = 0;
</script><?php

	foreach ($objeto['personas'] as $key => $value) { ?>
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
				style="font-size: 20px; background-color: #482E69; color: white">
				<?php echo  $value['npersona'] ?>
			</button>
		</div>
		<script>
			comandera.datos_mesa_comanda['num_personas'] ++;
			comandera.datos_mesa_comanda['posicion_color'] ++; 
		</script><?php
	} ?>
