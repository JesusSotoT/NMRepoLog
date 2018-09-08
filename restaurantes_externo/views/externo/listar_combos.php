<div class="col-md-12 col-xs-12"><?php
// Valida que existan combos
	if (empty($datos)) { ?>
		<div align="center">
			<h3><span class="label label-default">* Sin combos * </span></h3>
		</div><?php
	} 
	
	foreach ($datos as $key => $value) {
		$combo = json_encode($value);
		$combo = str_replace('"', "'", $combo); ?>
		
		<div id="combo_<?php echo $value['id_combo'] ?>" class="pull-left" style="padding:5px">
			<button 
				class="btn btn-default" 
				title="<?php echo($value['extras']) ?>" 
				onclick="externo.listar_productos_combo({
							combo: <?php echo $combo ?>,
							id: <?php echo $value['id_combo'] ?>,
							div: 'div_productos_combo',
							boton: 'combo_<?php echo $value['id_combo'] ?>'
						})"
						style="width: 110px"
						data-toggle="modal" 
						data-target="#modal_combo" >
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<?php echo substr($value['nombre'], 0, 9); ?>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<input type="image" style="width:80px;height:80px" src="../webapp/modulos/pos/<?php echo $value['imagen'] ?>"/>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						$ <?php echo $value['precio'] ?>
					</div>
				</div>
			</button>
		</div><?php
	} ?>
</div>
<script type="text/javascript">
</script>