<!-- jquery-ui -->
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
	<!-- bootstrap min CSS -->
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap-3.3.7/css/bootstrap.min.css">
	<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	<!-- Iconos font-awesome -->
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<!-- Select con buscador  -->
		<link rel="stylesheet" href="../../libraries/select2/dist/css/select2.min.css" />
	<!-- DataTables  -->
	    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<!-- JQuery -->
		<script src="../../libraries/jquery.min.js"></script>
	<!-- JQuery-Ui -->
		<script src="../../libraries/jquery-ui-1.11.4/jquery-ui.min.js"></script>
	<!-- jquery.scrollTo.js -->
		<script type="text/javascript" src="js/jquery.scrollTo.js"></script>
	<!-- bootstrap -->
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- bootstrap-select  -->
		<script src="../../libraries/bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	<!-- Notify  -->
		<script src="../../libraries/notify.js"></script>
	<!-- DataTables  -->
		<script src="../../libraries/dataTable/js/datatables.min.js"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
	    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
	    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
	    <script src="../../libraries/export_print/jszip.min.js"></script>

	<!-- ** Sistema -->
		<script type="text/javascript" src="js/comandas/comandas.js"></script>
		<script type="text/javascript" src="js/comandas/comandera.js"></script>
		<script type="text/javascript" src="js/pedidos/pedidos.js"></script>

		<script type="text/javascript">
			$(document).ready(function() {
				pedidos.autocompleteProductos();

				pedidos.listar_ajustes();

				$('#btnAsignar').bind('click', function() {
					pedidos.asignaridPropina();
				});
			});
		</script>

<div class="row" style="margin: 0">
	<h3 style="margin-left: 15px;">&iquest;Qué información deseas envíar?</h3>
	<div class="row" style="margin-left: 10px">
		<div class="col-md-3">
			<div class="input-group">
				<div class="input-group-addon" style="text-align: left; font-weight: bold;">
					Promociones:
				</div>
				<select
				onchange="pedidos.actualizar_configuracion({enviar_promociones: $('#enviar_promociones').val()});"
				id="enviar_promociones"
				class="selectpicker">
					<option value="1">Si</option>
					<option value="2">No</option>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="input-group">
				<div class="input-group-addon" style="text-align: left; font-weight: bold;">
					Menú Digital:
				</div>
				<select
				onchange="pedidos.actualizar_configuracion({enviar_menu: $('#enviar_menu').val()});"
				id="enviar_menu"
				class="selectpicker">
					<option value="1">Si</option>
					<option value="2">No</option>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<div class="input-group">
				<div class="input-group-addon" style="text-align: left; font-weight: bold;">
					Felicitaciones:
				</div>
				<select
				onchange="pedidos.actualizar_configuracion({enviar_felicitaciones: $('#enviar_felicitaciones').val()});"
				id="enviar_felicitaciones"
				class="selectpicker">
					<option value="1">Si</option>
					<option value="2">No</option>
				</select>
			</div>
		</div>
		<div class="col-md-3">
			<button class="btn btn-default" onclick="send_emails();">Enviar correos</button>
		</div>
	</div>
	<div class="row" style="margin-top: 20px;">
		<div class ="col-sm-12">
			<div class ="col-sm-8">
				<div class="modal-footer">
			<table id="tabla_clientes" class="table table-striped table-bordered" cellspacing="0">
				<thead>
					<tr>
						<th align="center"><strong><i class="fa fa-hashtag"></i></strong></th>
						<th align="center"><strong><i class="fa fa-user"></i></strong></th>
						<th align="center"><strong><i class="fa fa-phone"></i></strong></th>
						<th align="center"><strong><i class="fa fa fa-envelope"></i></strong></th>
						<th align="center"><strong><i class="fa fa-pencil"></i></strong></th>
						<th align="center"><button class="btn btn-default" onclick="selAll();">Selecciona Todos</button></th>
					</tr>
				</thead>
				<tbody><?php
					foreach ($clientes as $key => $value) {
						$value['gestion_correo'] = 1; 
						$datos_cliente = json_encode($value);
						$datos_cliente = str_replace('"', "'", $datos_cliente); ?>
						
						<tr id="tr_cliente<?php echo $value['id'] ?>">
							<td id="id_<?php echo $value['id'];  ?>"><?php echo $value['id'] ?></td>
							<td id="nom_<?php echo $value['id'];  ?>"><?php echo $value['nombre'] ?></td>
							<td id="tel_<?php echo $value['id'];  ?>" align="center"><?php echo $value['tel'] ?></td>
							<td id="ema_<?php echo $value['id'];  ?>"><?php echo $value['email'] ?></td>
							<td align="center">
								<button 
					        		id="btn_edit_<?php echo $value['id'] ?>" 
					        		data-loading-text="<i class='fa fa-refresh fa-spin'></i>" 
					        		onclick="comandera.llenar_campos(<?php echo $datos_cliente ?>)"
									data-toggle="modal" 
									data-target="#modal_editar_cliente"
					        		class="btn btn-primary">
					        		<i class="fa fa-pencil-square-o"></i>
					        	</button>
							</td>
							<td align="center">
								<input class="checkPro" type="checkbox" name="checked" value="<?php echo $value['id']?>" id="check_<?php echo $value['id']?>"></td>
							</td>
						</tr><?php
					} ?>
				</tbody>
			</table>
			<script>comandas.convertir_dataTable({id:'tabla_clientes'})</script>
			</div>
			</div>
			<div class ="col-sm-4">
			<blockquote style="font-size: 14px">
		    	<p>
		      		Si el <strong>cliente</strong> ya existe, solo buscalo en la lista y seleccionalo. 
		      		Si no, captura sus datos y pulsa <button class="btn btn-success"><i class="fa fa-plus"></i> OK</button>
		    	</p>
		    </blockquote>
		    <div class="row">
		    	<div class="col-xs-11">
      				<h3><small>Cliente:</small></h3>
        			<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<input id="agregar_client" type="text" class="form-control">
					</div>
		    	</div>
		    	<div class="col-xs-11">
      				<h3><small>Telefono:</small></h3>
        			<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-phone"></i></span>
						<input 
							id="add_tel" 
							type="number" 
							class="form-control"
							placeholder="0123456789">
					</div>
		    	</div>
		    	<div class="col-xs-11">
      				<h3><small>Email:</small></h3>
        			<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
						<input 
							id="add_email" 
							type="email" 
							class="form-control"
							placeholder="correo@gmail.com">
					</div>
		    	</div>
		    	<div class="col-xs-11">
      				<button
					id="btn_add"
					style="float:right; margin-top: 5px;"
					data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
					onclick="add_client({
								btn: 'btn_add',
								nombre: $('#agregar_client').val(),
								email: $('#add_email').val(),
								tel: $('#add_tel').val(),
							})"
					class="btn btn-success btn-lg">
						<i class="fa fa-plus"></i> Ok
					</button>
		    	</div>

		    </div>
			</div>
		</div>	
	</div>
</div> 
<div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-default">
            <div align="center"><label id="lblMensajeEstado"></label></div>
            <div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
                 <span class="sr-only">Loading...</span>
             </div>
        </div>
        </div>
      </div>
    </div>
  </div>
	<!-- Modal editar cliente domicilio -->
		<div id="modal_editar_cliente" class="modal" role="dialog">
	 		<div class="modal-dialog" style="width: 95%">
	    		<div class="modal-content">
	      			<div class="modal-header">
	       				<button type="button" class="close" onclick="$('#modal_editar_cliente').click()">&times;</button>
	      				<div class="row">
	      					<div class="col-md-3" style="padding-top: 5px">
			        			<h4>
			        				Editar cliente: 
									<input type="number" min="1" id="id_cliente" style="width: 50px; display:none; " align="center" readonly="1"/>
			        			</h4>
	      					</div>
	      				</div>
	      			</div>
	      			<div class="modal-body">
					    <div class="row">
					    	<div class="col-xs-4">
			      				<h3><small>Cliente:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input id="editar_cliente" type="text" class="form-control">
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Telefono:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
									<input 
										id="editar_tel" 
										type="number" 
										class="form-control"
										placeholder="0123456789">
								</div>
					    	</div>
					    	<div class="col-xs-4">
					    		<h3><small>Correo:</small></h3>
			        			<div class="input-group input-group-lg">
									<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input 
										id="editar_email" 
										type="email" 
										class="form-control"
										placeholder="correo@gmail.com">
								</div>
					    	</div>
					    </div>
						<div class="row">
							<div class="col-md-6"></div>
							<div class="col-xs-6" style="padding-top: 45px" align="right">
								<button
								id="editar_btn"
								data-loading-text="<i class='fa fa-refresh fa-spin'></i>"
								onclick="edit_client({
											btn: 'editar_btn',
											nombre: $('#editar_cliente').val(),
											tel: $('#editar_tel').val(),
											id: $('#id_cliente').val(),
											email: $('#editar_email').val(),
									})"
								class="btn btn-primary btn-lg">
									<i class="fa fa-check"></i> Ok
								</button>
							</div>
					    </div>
	      			</div>
				</div>
	  		</div>
		</div>
	<!-- FIN Modal editar cliente domicilio -->
<script>
	///////////////// ******** ---- 	edit_client			------ ************ //////////////////
	// Como parametros puede recibir:
		// nombre -> nombre del cliente
		// direccion -> direccion
		// tel -> telefono
		// btn -> boton loader
		// nuevo -> 1 -> cliente nuevo

			function edit_client($objeto) {
				console.log('--------> objeto edit_client');
				console.log($objeto);				
				var $btn = $('#'+$objeto['btn']);
				$btn.button('loading');

				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=edit_client',
					type : 'GET',
					dataType: 'json'
				}).done(function(resp) {
					console.log('-----> done edit_client');
					console.log(resp);
					
					$btn.button('reset');
					$('#modal_editar_cliente').click();
					$('#nom_'+$objeto['id']).text($objeto['nombre']);
					$('#tel_'+$objeto['id']).text($objeto['tel']);
					$('#ema_'+$objeto['id']).text($objeto['email']);
				}).fail(function(resp) {
					console.log('---------> Fail edit_client');
					console.log(resp);
		
					var $mensaje = 'Error al editar cliente';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				});
			}

///////////////// ******** ---- 	FIN edit_client		------ ************ //////////////////

	///////////////// ******** ---- 	add_client			------ ************ //////////////////
	// Como parametros puede recibir:
		// nombre -> nombre del cliente
		// direccion -> direccion
		// tel -> telefono
		// btn -> boton loader
		// nuevo -> 1 -> cliente nuevo

			function add_client($objeto) {
				console.log('--------> objeto add_client');
				console.log($objeto);	
				var $btn = $('#'+$objeto['btn']);
				$btn.button('loading');	
				if($objeto['nombre'] < 1 || $.isNumeric($objeto['nombre'])){
					var $mensaje = 'Favor de escribir el nombre del cliente';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});
					$btn.button('reset');
					return 0;
				}
				var filtro_tel = /^[0-9]{10}$/;
				if ($objeto['tel'] > 0 && !filtro_tel.test($objeto['tel'])) {
					var $mensaje = 'Favor de escribir el celular del cliente';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'warn',
					});
					$btn.button('reset');
					return 0;
				}
				var filtro_mail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			 	if (!filtro_mail.test($objeto['email'])) {
					var $mensaje='Favor de ingresar el email del cliente correctamente';
					$.notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 5000, 
							className: 'warn',
							arrowSize : 15
						}
					);
					$btn.button('reset');
					return 0;
				}	
				

				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=comandas&f=add_client',
					type : 'GET',
					dataType: 'json'
				}).done(function(resp) {
					console.log('-----> done add_client');
					console.log(resp);
					
					var tabla = $('#tabla_clientes').DataTable();
					tabla.destroy();

					$btn.button('reset');
					$("#agregar_client").val("");
					$("#add_email").val("");
					$("#add_tel").val("");
					$("#tabla_clientes").prepend('<tr id="tr_cliente'+resp+'"><td id="id_'+resp+'">'+resp+'</td><td id="nom_'+resp+'">'+$objeto['nombre']+'</td><td id="tel_'+resp+'" align="center">'+$objeto['tel']+'</td><td id="ema_'+resp+'">'+$objeto['email']+'</td><td align="center"><button id="btn_edit_'+resp+'" data-loading-text="<i class='+"'"+'fa fa-refresh fa-spin'+"'"+'></i>" onclick="comandera.llenar_campos({'+"'"+'gestion_correo'+"'"+': 1, '+"'"+'nombre'+"'"+': '+"'"+$objeto['nombre']+"'"+', '+"'"+'email'+"'"+': '+"'"+$objeto['email']+"'"+', '+"'"+'tel'+"'"+': '+"'"+$objeto['tel']+"'"+', '+"'"+'id'+"'"+': '+resp+'})" data-toggle="modal" data-target="#modal_editar_cliente" class="btn btn-primary"><i class="fa fa-pencil-square-o"></i></button></td><td align="center"><input class="checkPro" type="checkbox" name="checked" value="'+resp+'" id="check_'+resp+'"></td></td></tr>');
					

					comandas.convertir_dataTable({id:'tabla_clientes'})
					//console.log('#nom_'+$objeto['id']);
					//$('#nom_'+$objeto['id']).text($objeto['nombre']);
					//$('#tel_'+$objeto['id']).text($objeto['tel']);
					//$('#ema_'+$objeto['id']).text($objeto['email']);
				}).fail(function(resp) {
					console.log('---------> Fail add_client');
					console.log(resp);
		
					var $mensaje = 'Error al agregar cliente';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
					});
				});
			}

///////////////// ******** ---- 	FIN add_client		------ ************ //////////////////
function selAll(){

	var oTable = $('#tabla_clientes').dataTable();
    var allPages = oTable.fnGetNodes();

    if ($('.checkPro',allPages).is(":checked")) {
    	$('.checkPro',allPages).prop('checked', false);
    }else{
    	$('.checkPro',allPages).prop('checked', true);
    }

}
function send_emails(){
		var checados = 0;
		var oTable = $('#tabla_clientes').dataTable();
	    var allPages = oTable.fnGetNodes();
		$('#modalMensajes').modal();
		

		$('input:checked', allPages).each(function(){
            checados ++;
        });

        setTimeout(function () {
        	if(checados<1){
				alert("Debe seleccionar al menos un cliente.");
				$('#modalMensajes').modal('hide');
				return 0;
			}

			cadena='';
			$('input:checked', allPages).each(function(){
	            cadena+=$(this,allPages).val()+',';
	        });
			console.log(cadena);
			$.ajax({
				data:{id:cadena},
	       		url:'ajax.php?c=comandas&f=all_correos',
	       		type: 'POST',
	       		dataType:'json',
	       		success: function(resp){
	       			console.log("all_correos resp: ");
	       			console.log(resp);
	       			$('#modalMensajes').modal('hide');
	       			if(resp){
	       				var $mensaje = 'Correos enviados con exito.';
						$.notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'success',
						});
	       			} else {
	       				var $mensaje = 'Error al enviar los correos.';
						$.notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
	       			}
	       		}
			});
        }, 1000);
		
}
</script>