<script>
// Consulta los pedidos de la comanda al cargar la vista
	comandas.listar_pedidos({div:'div_pedidos_personalizado'});
// Consulta las personas de la comanda al cargar la vista
	comandas.listar_personas({div:'div_personas_personalizado'});
// Consulta las personas de la comanda al cargar la vista
	comandas.listar_personas_2({div:'div_personas_personalizado_2'});
// Consulta las sub comandas y la informacion de la comanda
	comandas.listar_sub_comandas({
		div:'div_sub_comandas',  
		status:'*', 
		empleado:'*', 
		mesa:'*', 
		id:<?php echo $objeto['idComanda'] ?>
	});
// Consulta las sub comandas y la informacion de la comanda
	comandas.listar_sub_comandas_2({
		div:'div_sub_comandas_2',  
		status:'*', 
		empleado:'*', 
		mesa:'*', 
		id:<?php echo $objeto['idComanda'] ?>
	});
	// function para 
	$( "#divPorPro" ).click(function() {
	  $('#btn_personalizado_ok').attr('tipodiv', 0);

	});
	$( "#divPorPorCant" ).click(function() {
	  $('#btn_personalizado_ok').attr('tipodiv', 1);
	});
</script>
<div class="row" style="margin: 0;">
	<div id="exTab2">	
		<ul class="nav nav-tabs" style="    padding: 0px 5px;">
			<li id="divPorPro" class="active"><a  href="#tab_por_pro" data-toggle="tab">Por productos</a></li>
			<li id="divPorPorCant" ><a href="#tab_por_por_cant" data-toggle="tab">Por porcentaje o cantidad</a></li>
		</ul>
		<div class="tab-content ">
			<div class="tab-pane active" id="tab_por_pro">
	          	<div class="row">
					<div class="col-xs-7" id="div_personas_personalizado">
						<!-- En esta div se cargan las personas de la comanda -->
					</div>
					<div class="col-xs-5" align="right">
						<div class="row">
							<div class="col-xs-6">
								<div class="input-group input-group-lg">
								<span class="input-group-btn">
									<button 
										class="btn btn-success" 
										type="button" 
										onclick="comandas.agregar_persona({
												div: 'div_personas_personalizado', 
												persona: $('#add_persona').val()
										})">
										<i class="fa fa-plus"></i>
									</button>
								</span>
								<input type="number" id="add_persona" class="form-control" min="1" value="1">
							</div>
							</div>
							<div class="col-xs-6" align="right">
								<div class="input-group input-group-lg">
									<span class="input-group-btn">
										<button 
											class="btn btn-danger" 
											type="button" 
											onclick="comandas.quitar_persona({
													div: 'div_personas_personalizado', 
													persona:$('#borrar_persona_personalizado').val()
											})">
											<i class="fa fa-minus"></i>
										</button>
									</span>
									<input 
										readonly="1" 
										type="number" 
										id="borrar_persona_personalizado"  
										class="form-control" 
										value="<?php echo $_SESSION['cerrar_personalizado']['num_personas'] ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4" id="div_agregados_personalizado">
						<!-- En esta div se cargan los pedidos que ha seleccionadado cada cliente -->
					</div>
					<div class="col-xs-4" id="div_pedidos_personalizado">
						<!-- En esta div se cargan los pedidos de la comanda -->
					</div>
					<div class="col-xs-4" id="div_sub_comandas">
						<!-- En esta div se cargan las sub comandas -->
					</div>
				</div> 
			</div>
			<div class="tab-pane" id="tab_por_por_cant">
				<div class="row">
					<div class="col-xs-7" id="div_personas_personalizado_2">
						<!-- En esta div se cargan las personas de la comanda -->
					</div>
					<div class="col-xs-5" style="float:right;" align="right">
						<div class="row">
							<div class="col-xs-6">
								<div class="input-group input-group-lg">
								<span class="input-group-btn">
									<button 
										class="btn btn-success" 
										type="button" 
										onclick="comandas.agregar_persona({
												div: 'div_personas_personalizado', 
												persona: $('#add_persona').val()
										})">
										<i class="fa fa-plus"></i>
									</button>
								</span>
								<input type="number" id="add_persona" class="form-control" min="1" value="1">
							</div>
							</div>
							<div class="col-xs-6" align="right">
								<div class="input-group input-group-lg">
									<span class="input-group-btn">
										<button 
											class="btn btn-danger" 
											type="button" 
											onclick="comandas.quitar_persona({
													div: 'div_personas_personalizado', 
													persona:$('#borrar_persona_personalizado').val()
											})">
											<i class="fa fa-minus"></i>
										</button>
									</span>
									<input 
										readonly="1" 
										type="number" 
										id="borrar_persona_personalizado"  
										class="form-control" 
										value="<?php echo $_SESSION['cerrar_personalizado']['num_personas'] ?>">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-4" id="div_agregados_personalizado_2">
						<!-- En esta div se cargan los pedidos que ha seleccionadado cada cliente -->
					</div>
					<div class="col-xs-4" id="div_pedidos_personalizado_2">
						<!-- En esta div se cargan los pedidos de la comanda -->
					</div>
					<div class="col-xs-4" id="div_sub_comandas_2">
						<!-- En esta div se cargan las sub comandas -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>