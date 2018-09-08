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
if($objeto['promocion']['tipo_promocion'] == 1 || $objeto['promocion']['tipo_promocion'] == 2 || $objeto['promocion']['tipo_promocion'] == 4){
	foreach($objeto['promocion']['grupos'] as $key => $value){ ?> 
		<div class="row">
					<div class="col-xs-12" id="div_promocion"><?php
						foreach($value as $key => $v){
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
										promocion: 1,
										div : 'div_promocion',
										tipo_promocion : '<?php echo $objeto['promocion']['tipo_promocion']?>',
										nombre : '<?php echo $v['nombre'] ?>',
										id_producto : <?php echo $v['idProducto'] ?>,
										materiales : <?php echo $v['materiales'] ?>,
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
		<script>
			externo.htmlPromo = $("#div_promocion").html();
			var array = [];
			array.grupo = <?php echo $key ?>;
			array.html = $('#div_combo_grupo_<?php echo $key ?>').html();
			externo.promociones.push(array);
			$("#title-promo").html('Productos seleccionados: 0');
			$("#title-promo").show();
		</script><?php
	}
} else if($objeto['promocion']['tipo_promocion'] == 3){ 

	foreach($objeto['promocion']['grupos']['mayor_price'] as $key => $value){ ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-4" style="padding-top: 5px">
					<h4 class="panel-title"></h4>
				</div>
				<div class="col-md-4" style="padding-top: 5px" align="center">
					<h4 class="panel-title"><?php if($idioma == 1) { ?>Seleccionados<?php } else { ?>Selects<?php }?> <i id="cantidad_grupo_mayor_price">0</i> / <?php echo intval($objeto['promocion']['cantidad']) ?></h4>
				</div>
				<div class="col-md-4" align="right">
					<button 
						class="btn" 
						style="background-color: #D6872D; color:white;"
						onclick="externo.reiniciar_grupo_promociones({
							grupo: 'mayor_price', 
							div: 'div_combo_grupo_mayor_price'
						})">
						<i class="fa fa-undo"></i> <?php if($idioma == 1) { ?>Reiniciar<?php } else { ?>Restart<?php }?>
					</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12" id="div_combo_grupo_mayor_price"><?php
						foreach($value as $key => $v){
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
										promocion: 1,
										cantidad_grupo: <?php echo intval($objeto['promocion']['cantidad']) ?>,
										grupo: 'mayor_price',
										div : 'div_combo_grupo_mayor_price',
										tipo_promocion : '<?php echo $objeto['promocion']['tipo_promocion']?>',
										nombre : '<?php echo $v['nombre'] ?>',
										id_producto : <?php echo $v['idProducto'] ?>,
										materiales : <?php echo $v['materiales'] ?>,
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
		array.grupo = 'mayor_price';
		array.html = $('#div_combo_grupo_mayor_price').html();
		externo.promociones.push(array);
		$("#title-promo").hide();
	</script>
<?php } 
} else if($objeto['promocion']['tipo_promocion'] == 5){ 
	foreach($objeto['promocion']['grupos']['comprar'] as $key => $value){ ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-4" style="padding-top: 5px">
					<h4 class="panel-title">Comprar</h4>
				</div>
				<div class="col-md-4" style="padding-top: 5px" align="center">
					<h4 class="panel-title"><?php if($idioma == 1) { ?>Seleccionados<?php } else { ?>Selects<?php }?> <i id="cantidad_grupo_comprar">0</i> / <?php echo intval($objeto['promocion']['cantidad']) ?></h4>
				</div>
				<div class="col-md-4" align="right">
					<button 
						class="btn" 
						style="background-color: #D6872D; color:white;"
						onclick="externo.reiniciar_grupo_promociones({
							grupo: 'comprar', 
							div: 'div_combo_grupo_comprar'
						})">
						<i class="fa fa-undo"></i> <?php if($idioma == 1) { ?>Reiniciar<?php } else { ?>Restart<?php }?>
					</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12" id="div_combo_grupo_comprar"><?php
						foreach($value as $key => $v){
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
										promocion: 1,
										cantidad_grupo: <?php echo intval($objeto['promocion']['cantidad']) ?>,
										grupo: 'comprar',
										div : 'div_combo_grupo_comprar',
										tipo_promocion : '<?php echo $objeto['promocion']['tipo_promocion']?>',
										nombre : '<?php echo $v['nombre'] ?>',
										id_producto : <?php echo $v['idProducto'] ?>,
										materiales : <?php echo $v['materiales'] ?>,
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
		array.grupo = 'comprar';
		array.html = $('#div_combo_grupo_comprar').html();
		externo.promociones.push(array);
		$("#title-promo").hide();
	</script>
<?php } foreach($objeto['promocion']['grupos']['recibir'] as $key => $value){ ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="row">
				<div class="col-md-4" style="padding-top: 5px">
					<h4 class="panel-title">Recibir</h4>
				</div>
				<div class="col-md-4" style="padding-top: 5px" align="center">
					<h4 class="panel-title"><?php if($idioma == 1) { ?>Seleccionados<?php } else { ?>Selects<?php }?> <i id="cantidad_grupo_recibir">0</i> / <?php echo intval($objeto['promocion']['cantidad_descuento']) ?></h4>
				</div>
				<div class="col-md-4" align="right">
					<button 
						class="btn" 
						style="background-color: #D6872D; color:white;"
						onclick="externo.reiniciar_grupo_promociones({
							grupo: 'recibir', 
							div: 'div_combo_grupo_recibir'
						})">
						<i class="fa fa-undo"></i> <?php if($idioma == 1) { ?>Reiniciar<?php } else { ?>Restart<?php }?>
					</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-xs-12" id="div_combo_grupo_recibir"><?php
						foreach($value as $key => $v){
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
										promocion: 1,
										cantidad_grupo: <?php echo intval($objeto['promocion']['cantidad_descuento']) ?>,
										grupo: 'recibir',
										div : 'div_combo_grupo_recibir',
										tipo_promocion : '<?php echo $objeto['promocion']['tipo_promocion']?>',
										nombre : '<?php echo $v['nombre'] ?>',
										id_producto : <?php echo $v['idProducto'] ?>,
										materiales : <?php echo $v['materiales'] ?>,
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
		array.grupo = 'recibir';
		array.html = $('#div_combo_grupo_recibir').html();
		externo.promociones.push(array);
		$("#title-promo").hide();
	</script>
<?php }
}?>
