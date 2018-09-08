<style>
	.wrapPro {
	    word-wrap: break-word;
	    position:justify;
	    font-size: 11px;
	    width: 80%;
	    padding: 10px;
	    height: auto;
	    overflow-x: hidden;
	}
</style><?php
foreach($objeto['combo']['grupos'] as $key => $value){ ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-4" style="padding-top: 5px">
					<h4 class="panel-title"><?php if($idioma == 1) { ?>Grupo<?php } else { ?>Group<?php } echo $key ?></h4>
				</div>
				<div class="col-md-4" style="padding-top: 5px" align="center">
					<h4 class="panel-title">Seleccionados <i id="cantidad_grupo_<?php echo $key ?>">0</i> / <?php echo $value['cantidad_grupo'] ?></h4>
				</div>
				<div class="col-md-4" align="right">
					<button 
						class="btn" 
						style="background-color: #D6872D; color:white;"
						onclick="externo.reiniciar_grupo({
							grupo: <?php echo $key ?>, 
							div: 'div_combo_grupo_<?php echo $key ?>'
						})">
						<i class="fa fa-undo"></i> <?php if($idioma == 1) { ?>Reiniciar<?php } else { ?>Restart<?php }?>
					</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12" id="div_combo_grupo_<?php echo $key ?>"><?php
					foreach($value['productos'] as $v){
					// Comprueba si es platillo especial
						$clase = (!empty($v['materiales'])) ? 'warning' : 'default' ; ?>
						
						<div
							class = "pull-left" 
							style = "padding:5px" 
							idproducto = "<?php echo $v['idProducto'] ?>" 
							idcomanda = "<?php echo $v['comanda'] ?>" 
							materiales = "<?php echo $v['materiales'] ?>" 
							tipo = "<?php echo $v['tipo'] ?>" 
							iddep = "<?php echo $v['id_departamento'] ?>">
							<button
								 onclick="externo.detalles_producto({
									combo: 1,
									cantidad_grupo: <?php echo $value['cantidad_grupo'] ?>,
									grupo: '<?php echo $key ?>',
									div : 'div_combo_grupo_<?php echo $key ?>',
									nombre : '<?php echo $v['nombre'] ?>',
									id_producto : <?php echo $v['idProducto'] ?>,
									materiales : <?php echo $v['materiales'] ?>,
									tipo : '<?php echo $v['tipo'] ?>',
									departamento : '<?php echo $v['id_departamento'] ?>',
									persona : externo.datos_mesa_comanda.persona_seleccionada,
									id_comanda : externo.datos_mesa_comanda.id_comanda
								})" 
								type="button" 
								class="btn btn-<?php echo $clase ?>" 
								style="width: 103px;">
								<div class="row">
									<div style="width:85px;" class="wrapPro">
										<?php echo substr($v['nombre'], 0, 25)  ?>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										<input type="image" alt=" " style="width:80px; height:80px" src="../pos/<?php echo $v['imagen'] ?>"/>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12">
										$ <?php echo $v['precio'] ?>
									</div>
								</div>
							</button>
						</div><?php
					} ?>
				</div>
			</div>
		</div>
	</div>
	<script>
		var array = [];
		array.grupo = <?php echo $key ?>;
		array.html = $('#div_combo_grupo_<?php echo $key ?>').html();
		externo.combos.push(array);
	</script><?php
}