<?php
// Valida que existan pedidos eliminados
if (empty($terminados)) {?>
	<div align="center">
		<h3><span class="label label-default">* Aqui aparecen los pedidos terminados *</span></h3>
	</div><?php
	
	return 0;
} ?>
<table id="tabla_listado_terminados" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
			<th><strong># <i class="fa fa-pencil"></i></strong></th>
			<th><strong><i class="fa fa-hashtag"></i></strong></th>
			<th><strong><i class="fa fa-pencil-square-o"></i></strong></th>
			<th><strong><i class="fa fa-object-group"></i></strong></th>
			<th><strong><i class="fa fa-user"></i></strong></th>
			<th><strong><i class="fa fa-clock-o"></i></strong></th>
			<th><strong><i class="fa fa-arrow-left"></i></strong></th>
		</tr>
	</thead>
	<tbody><?php
	// $eliminados es un array con los promedios por comensal que viene desde el controlador
		foreach ($terminados as $key => $value) {
			$pedido = $value;
			$pedido = json_encode($pedido);
			$pedido = str_replace('"', "'", $pedido);

			$preparacion = (!empty($value['opcionalesDesc'])) ? 
								'<footer style="font-size: 15px">'.$value['opcionalesDesc'].'</footer>' : '' ; 
			$preparacion .= (!empty($value['adicionalesDesc'])) ?
								'<footer style="font-size: 15px">'.$value['adicionalesDesc'].'</footer>' : '' ; 
			$preparacion .= (!empty($value['sin_desc'])) ? 
								'<footer style="font-size: 15px">'.$value['sin_desc'].$value['nota_sin'].'</footer>' : '' ; ?>
			
			<tr>
				<td><?php echo $value['producto'] ?></td>
				<td><?php echo $value['comanda'] ?></td>
				<td>
					<blockquote>
						<p style="font-size: 18px"><?php echo $value['descripcion'] ?></p>
						<?php echo $preparacion ?>
					</blockquote>
				</td>
				<td><?php echo $value['nombre_mesa'] ?></td>
				<td><?php echo $value['persona'] ?></td>
				<td><?php echo $value['tiempo'] ?></td>
				<td>
					<button id="react_<?php echo $value['comanda'] ?>" onclick="pedidos.reactivar(<?php echo $pedido ?>)" data-loading-text="<i class='fa fa-refresh fa-spin'></i>" class="btn btn-warning btn-lg btn-loader">
						<i class="fa fa-arrow-left"></i>
					</button>
				</td>
			</tr> <?php
		} ?>
	</tbody>	
</table>
<div class="col-md-6">			
	<div class="panel-body" id="clasif">
		<table class="table table-striped table-bordered">
			<tr >
				<td class="danger" >Para llevar</td>
				<td class="info" > A domicilio</td>
				<td >Normal</td>
			</tr>
		</table>
	</div>
</div>