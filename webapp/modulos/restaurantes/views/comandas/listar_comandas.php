<?php
// Valida que existan comandas en los parametros seleccionadas
	if (empty($comandas)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>
	
	<div class="panel-group" id="accordion_graficas" role="tablist" aria-multiselectable="true">
        <div class="panel panel-default">
            <div 
            	class="panel-heading" 
            	role="tab"
            	data-toggle="collapse" 
            	data-parent="#accordion_graficas" 
            	href="#tab_graficas" 
            	aria-controls="collapse_graficas"
            	style="cursor: pointer">
                <h4 class="panel-title"><strong><i class="fa fa-pie-chart"></i> Graficas</strong></h4>
            </div>
            <div id="tab_graficas" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading_tab_graficas">
                <div class="panel-body">
					<div class="row">
						<div id="grafica_comandas_barras" class="col-xs-8" style="height: 40%">
							<!-- En esta div se carga la grafica de barras -->
						</div>
						<div id="grafica_comandas_dona" class="col-xs-4" style="height: 40%">
							<!-- En esta div se carga la grafica de dona -->
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>

    <div>	
    	<h2 style ="padding-left: 80%;" id="consumo"></h2>
    </div><br><br><br>
    <?php 
    	//echo json_encode($comandas);
     ?>
	<table id="tabla_comandas" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th align="center"><strong><i class="fa fa-barcode"></i></strong></th> <!-- Codigo -->
				<th align="center"><strong><i class="fa fa-home"></i></strong></th> <!-- Sucursal -->
				<th align="center"><strong><i class="fa fa-object-group"></i></strong></th> <!-- Nombre Mesa -->
				<th align="center"><strong><i class="fa fa-check-square-o"></i></strong></th> <!-- Estatus -->
				<th align="center"><strong><i class="fa fa-hand-o-up"></i></strong></th> <!-- Mesero -->
				<th align="center"><strong><i class="fa fa-clock-o"></i></strong></th> <!-- Fecha / Hora -->
				<th align="center"><strong><i class="fa fa-usd"></i></strong></th> <!-- Total -->
				<th align="center"><strong><i class="fa fa-language"></i></strong></th> <!-- via origen -->
				<th align="center"><strong><i class="fa fa-credit-card"></i></strong></th> <!-- ID venta -->
				<th align="center"><strong><i class="fa fa-ticket"></i></strong></th> <!-- Ticket -->
				<th align="center"><strong><i class="fa fa-pencil-square-o"></i></strong></th> <!-- Pedidos -->
				<th align="center"><strong><i class="fa fa-list"></i></strong></th> <!-- Sub comandas -->
			</tr>
		</thead>
		<tbody><?php
		// $comandas es un array con las comandas viene desde el controlador
		$consumo = 0;
			foreach ($comandas as $key => $value) { 

				$consumo += $value['total']*1;

				$promedio_general += $value['promedioComensal'];
				$num_comandas ++; ?>
				
				<tr>
					<td><?php 
					// Si ya se pago no se puede reabrir
						if (!empty($value['venta'])) {
							echo $value['codigo'];
						} else { ?>
							<a 	
								href="#"
								data-toggle="modal" 
								data-target="#modal_autorizar"
								title="Abrir comanda"
								onclick="$('#id_comanda').val(<?php echo $value['id'] ?>)">
								<?php echo $value['codigo'] ?>
							</a><?php
						} ?>
					</td>
					<td><?php echo $value['sucursal'] ?></td>
					<td align="center"><?php echo $value['nombre_mesa'] ?></td>
					<td><?php 
					// Si la comanda no se ha pagado crea un link para hacerlo
						if ($value['status'] == 'Cerrada / Sin pago') { ?>
							<a
								href="#" 
								title="Mandar a caja"
								onclick="comandas.mandar_comanda_caja({codigo: '<?php echo $value['codigo'] ?>'})">
								<?php echo $value['status'] ?>
							</a><?php
						} else {
							echo $value['status'];
						} ?>
					</td>
					<td><?php echo $value['usuario'] ?></td>
					<td align="center"><?php echo $value['timestamp'] ?></td>
					<td align="center">$ <?php echo $value['total'] ?></td>
					<td align="center"><?php echo $value['via_contacto_text'] ?></td>
					<td align="center"><?php echo $value['venta'] ?></td>
					<td align='center'>
					<!-- CloseComanda es una funcion que viene desde js/comandas/reimprime.js -->
						<img 
							src='../../modulos/restaurantes/images/impresora.jpeg' 
							title='Cuenta' 
							style='cursor:pointer;'
							onclick="comandera.cerrar_comanda({
								bandera: 0,
								reimprime: 1,
								mesero: '<?php echo $value['usuario'] ?>',
								personas: '<?php echo $value['personas'] ?>',
								f_ini: '<?php echo $value['timestamp'] ?>',
								nombre: comandera['datos_mesa_comanda']['nombre'],
								idComanda: '<?php echo $value['id'] ?>',
								tel: '<?php echo $value['tel'] ?>',
								idmesa: '<?php echo $value['idmesa'] ?>'
							})" />
					</td>
					<td align='center'>
					<!-- imprimePedido es una funcion que viene desde js/comandas/reimprime.js -->
						<img 
							src='../../modulos/restaurantes/images/impresora2.jpeg' 
							title='Pedidos' 
							style='cursor:pointer;' 
							onclick="imprimePedido({id_comanda:'<?php echo $value['id'] ?>'})">
					</td>
					<td align="center"><?php
						if (!empty($value['sub_comandas'])) { ?>
							<button 
								id="btn_<?php echo $value['id'] ?>" 
								class="btn btn-primary" 
								data-toggle="modal" 
								data-target="#modal_sub_comandas"  
								onclick="comandas.listar_comandas_hijas({id_padre:<?php echo $value['id'] ?>, div:'div_sub_comandas'})">
								<i class="fa fa-list"></i>
							</button><?php
						} ?>
					</td>
				</tr> <?php
			} ?>
		</tbody>
	</table>

	<script> 
				$("#consumo").text('Consumo: $'+<?php echo $consumo; ?>); 
	</script>

	
	
<!-- Modal sub comandas -->
	<div id="modal_sub_comandas" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						&times;
					</button>
					<h4 class="modal-title">Sub comandas</h4>
				</div>
				<div class="modal-body" id="div_sub_comandas">
					<!-- Aqui se cargan las sub comandas -->
				</div>
			</div>
		</div>
	</div>
<!-- FIN Modal sub comandas -->

<!-- Modal autorizar -->
	<div id="modal_autorizar" class="modal fade" role="dialog">
 		<div class="modal-dialog">
    		<div class="modal-content">
	      		<div class="modal-header">
	      			<button type="button" class="close" data-dismiss="modal">&times;</button>
	       			<h4 class="modal-title">Autorizar</h4>
	      		</div>
	      		<div class="modal-body">
					<blockquote style="font-size: 14px">
				    	<p>
				      		Esta funcion <strong>abre</strong> nueva mente la comanda
				      		seleccionada
				    	</p>
				    </blockquote>
	     			<h3><small>Introduce la contraseña:</small></h3>
	     			<div class="row">
	     				<div class="col-md-4">
			       			<div class="input-group input-group-lg">
								<span class="input-group-addon"><i class="fa fa-hashtag"></i></span>
								<input id="id_comanda" type="number" class="form-control" disabled="1">
							</div>
	     				</div>
	     				<div class="col-md-8">
			       			<div class="input-group input-group-lg">
								<span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
								<input id="pass" onkeypress="if(((document.all) ? event.keyCode : event.which)==13) comandas.cambiar_status({pass:$('#pass').val(), id_comanda: $('#id_comanda').val(), btn: 'btn_autorizar'})" type="password" class="form-control">
								<span class="input-group-btn">
					        		<button 
					        			id="btn_autorizar" 
					        			data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
					        			onclick="comandas.cambiar_status({
					        						pass: $('#pass').val(), 
					        						id_comanda: $('#id_comanda').val(), 
					        						btn: 'btn_autorizar'
					        					})" 
					        			class="btn btn-primary" 
					        			type="button">
					        			<i class="fa fa-external-link"></i> Abrir
					        		</button>
					      		</span>
							</div>
						</div>
	     			</div>
	      		</div>
			</div>
	  	</div>
	</div>
<!-- FIN Modal reiniciar mesas -->
	
	<script>
		comandas.graficar({
			div:'grafica_comandas', 
			x:'timestamp', 
			y:'comandas', 
			label:'Comandas', 
			dona:<?php echo json_encode($dona) ?>, 
			barras:<?php echo json_encode($lineal) ?>
		});
	</script>