<div class="grid-stack-item" id="<?php echo $id ?>" data-gs-no-resize="1"
										data-gs-width="3" 
										data-gs-height="3">
	<div style="width:100%; height:100%;text-align: center;" class="grid-stack-item-content panel panel-foodware">
		<div class="panel-heading" style="cursor: move">
			<div class="row">
				<div class="col-xs-12">
					<div class="input-group">
						<div class="input-group-addon">
							<i class="fa fa-home"></i>
						</div>
						<input type="text" disabled="disabled" class="form-control" value="<?php echo $objeto['domicilio']; ?>">
					</div>
				</div>
			</div>
		</div>
		<div>
			<div
			onclick="comandera.mandar_mesa_comandera({
			id_mesa:  <?php echo $id ?>,
			tipo: 1,
			nombre_mesa_2: '<?php echo $objeto['nombre'] ?>',
			id_comanda: $(this).attr('id_comanda'),
			nombre: '<?php echo  $objeto['nombre'] ?>',
			domicilio: '<?php echo $objeto['domicilio'] ?>',
			tel: '<?php echo $objeto['tel'] ?>',
			tipo_operacion: <?php echo $objeto['tipo_operacion'] ?>
			
			})"
												id="mesa_<?php echo $id ?>"
												id_comanda=""
												style="cursor:pointer">
				<div class="panel-body">
					<div class="GtableTableIcon" style="color: #36254f" align="center">
						<div class="row">
							<div class="col-md-7 col-xs-7  ">
								<div class="row">
									<i class="fa fa-shopping-basket fa-3x"></i>
								</div>
								<div class="row" style="padding-top: 5px">
									<p id="mesero_<?php echo $id ?>">
										<?php echo $objeto['mesero']; ?>	
									</p>
								</div>
							</div>
							<div class="col-md-5 col-xs-5" style="font-size: 16px; padding:0; padding-top: 20px">
								<div id="div_total_<?php echo $id ?>" class="price" style="" >
									<!-- En esta div se carga el total de la comanda -->
								</div>
							</div>
						</div>
					</div>
				</div> 
			</div>
		</div>
	</div>
</div>


<script>
// Carga la comandera
	comandera.mandar_mesa_comandera({
		id_mesa: <?php echo $id ?>,
		tipo: 1,
		id_comanda: '',
		nombre: '<?php echo  $objeto['nombre'] ?>',
		nombre_mesa_2: '<?php echo  $objeto['nombre'] ?>',
		domicilio: '<?php echo $objeto['domicilio'] ?>',
		tel: '<?php echo $objeto['tel'] ?>',
		tipo_operacion: <?php echo $objeto['tipo_operacion'] ?>
	});
	
// Crea la mesa y la agrega a la cuadricula
	gridLlD.addWidget = function(el, x, y, width, height, auto_position, min_width, max_width, min_height, max_height, id) {
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
	gridLlD.addWidget($('#<?php echo $id ?>'), 0, 0, 3, 3, true, null, null, null, null, '<?php echo $id ?>');
</script>

	
