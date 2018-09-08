<div class="col-md-12 col-xs-12"><?php
// Valida que existan combos
	if (empty($datos)) { ?>
		<div align="center">
			<h3><span class="label label-default"><?php if($idioma == 1) { ?> * Sin promociones * <?php } else { ?> * No promotions * <?php }?></span></h3>
		</div><?php
	} 
	
	foreach ($datos as $key => $value) {
		$promocion = json_encode($value);
		$promocion = str_replace('"', "'", $promocion); ?>
		
		<div id="promocion_<?php echo $value['id_promocion'] ?>" class="pull-left" style="padding:5px">
			<button type="button" 
				onclick="comandera.listar_productos_promociones({
				promocion: <?php echo $promocion ?>,
				id: <?php echo $value['id_promocion'] ?>,
				div: 'div_productos_promocion',
				boton: 'promocion_<?php echo $value['id_promocion'] ?>'})"
				class="btn btn-default" 
				data-toggle="modal" 
				data-target="#modal_promocion"
				style="width: 120px;height: 60px;white-space: normal;">
				<div class="row">
					<div style="font-weight: bold;" class="">
						<?php echo $value['nombre'] ?>
					</div>
				</div>
			</button>
			
		</div><?php
	} ?>
</div>
<script type="text/javascript">
</script>