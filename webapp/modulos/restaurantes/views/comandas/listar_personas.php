<div class="row"><?php
	$clases[0]='info';
	$clases[1]='success';
	$clases[2]='warning';
	$clases[3]='primary';
	$clases[4]='danger';
						
	$posi=0;
	
// $_SESSION['cerrar_personalizado']['num_personas'] es una variable del controlador
	for ($i=0; $i < $_SESSION['cerrar_personalizado']['num_personas']; $i++) { ?>
		<div class="pull-left" style="padding:5px" id="persona_<?php echo $i+1 ?>">
			<button type="button" 
				onclick="comandas.listar_agregados({
						persona: <?php echo $i+1 ?>, 
						div: 'div_agregados_personalizado', 
						clase: '<?php echo $clases[$posi] ?>'
				})" 
				class="btn btn-<?php echo $clases[$posi] ?> btn-lg">
				<i class="fa fa-user"></i> <?php echo $i+1 ?>
			</button>
		</div><?php
		
		$posi ++;
		$posi = ($posi > 4) ? 0 : $posi;
							
	} ?>
	
	<input type="text" id="persona" style="visibility: hidden" value="0" />
	<input type="text" id="clase" style="visibility: hidden" value="default" />
</div>