<script>
// Consulta los pedidos de la comanda al cargar la vista
	comandas.listar_pedidos({div:'div_pedidos_personalizado'});
// Consulta las personas de la comanda al cargar la vista
	comandas.listar_personas({div:'div_personas_personalizado'});
// Consulta las sub comandas y la informacion de la comanda
	comandas.listar_sub_comandas({
		div:'div_sub_comandas', 
		status:'*', 
		empleado:'*', 
		mesa:'*', 
		id:<?php echo $objeto['idComanda'] ?>
	});
</script>
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
<div class="row" style="overflow: scroll; height:10%">
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