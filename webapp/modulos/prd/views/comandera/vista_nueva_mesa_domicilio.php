<div class="grid-stack-item" id="<?php echo $id ?>">
	<div class="grid-stack-item-content panel panel-danger">
		<div class="panel-heading" style="cursor: move">
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-home"></i>
						</div>
						<input type="text" disabled="disabled" class="form-control" value="<?php echo $objeto['nombre']; ?>">
					</div>
				</div>
			</div>
		</div>
		<div>
			<a href="javascript:void(0)" style="color: #000000">
				<div class="panel-body">
					<div class="GtableTableIcon" align="center">
						<div class="row">
							<div 
								class="col-xs-6"
								onclick="comandera.mandar_mesa_comandera({
										id_mesa: <?php echo $id ?>,
										tipo: 2,
										id_comanda: $(this).attr('id_comanda'),
										nombre: '<?php echo  $objeto['nombre'] ?>',
										domicilio: '<?php echo $objeto['domicilio'] ?>',
										tel: '<?php echo $objeto['tel'] ?>',
										tipo_operacion: <?php echo $objeto['tipo_operacion'] ?>
								})"
								data-toggle="modal" 
								id="mesa_<?php echo $id ?>"
								id_comanda = ""
								data-target="#modal_comandera">
								<i class="fa fa-motorcycle fa-3x"></i>
								<div id="div_total_<?php echo $id ?>">
										<!-- En esta div se carga el total de la comanda -->
								</div>
							</div>
							<div class="col-xs-6" style="padding-top: 10%">
								<i 
									onclick="asignar_repartidor()"
									class="fa fa-male fa-3x fa-lg text-primary" 
									id="mesaR_<?php echo $id ?>" 
									idmesaR="<?php echo $id ?>" 
									idcomandaR=""></i> 
									<?php echo $objeto['nombre']; ?>
							</div>
						</div>
					</div>
				</div> 
			</a>
		</div>
	</div>
</div>


<script>
// Carga la comandera
	comandera.mandar_mesa_comandera({
		id_mesa: <?php echo $id ?>,
		tipo: 2,
		id_comanda: '',
		nombre: '<?php echo  $objeto['nombre'] ?>',
		domicilio: '<?php echo $objeto['domicilio'] ?>',
		tel: '<?php echo $objeto['tel'] ?>',
		tipo_operacion: <?php echo $objeto['tipo_operacion'] ?>
	});
	
// Crea la mesa y la agrega a la cuadricula
	grid.addWidget = function(el, x, y, width, height, auto_position, min_width, max_width, min_height, max_height, id) {
		el = $(el);
		if ( typeof id != 'undefined')
			el.attr('id', id);
		if ( typeof x != 'undefined')
			el.attr('data-gs-x', x);
		if ( typeof y != 'undefined')
			el.attr('data-gs-y', y);
		if ( typeof width != 'undefined')
			el.attr('data-gs-width', width);
		if ( typeof height != 'undefined')
			el.attr('data-gs-height', height);
		if ( typeof min_width != 'undefined')
			el.attr('data-gs-min-width', min_width);
		if ( typeof max_width != 'undefined')
			el.attr('data-gs-max-width', max_width);
		if ( typeof min_height != 'undefined')
			el.attr('data-gs-min-height', min_height);
		if ( typeof max_height != 'undefined')
			el.attr('data-gs-max-height', max_height);
		if ( typeof auto_position != 'undefined')
			el.attr('data-gs-auto-position', auto_position ? 'yes' : null);
		this.container.append(el);
		this._prepare_element(el);
		this._update_container_height();

		return el;
	};
	grid.addWidget($('#<?php echo $id ?>'), 0, 0, 2, 2, true, null, null, null, null, '<?php echo $id ?>');
</script>