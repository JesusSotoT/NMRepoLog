/**
 * @author Fer De La Cruz
 */
var id_reservacion=0;
var mesa=0;
var reservaciones = {

///////////////// ******** ---- 		listar		------ ************ //////////////////
	//////// Consulta las reservaciones y las agrega a un div
		// Como parametros recibe:
			// f_ini -> fecha y hora de inicio
			// F_fin -> fecha y hora final
			// div -> div donde se cargara el contenido html
			// vista -> bandera que señala si se debe de cargar la vista o no
			// btn -> boton del loader
					
			 listar:function ($objeto) {
			 	console.log('----> Objeto listar');
			 	console.log($objeto);
			 	
			// Loader en el boton OK
				var $btn = $('#'+$objeto['btn']);
			    $btn.button('loading');
			    
			 	$.ajax({
					data:$objeto,
				    url:'ajax.php?c=reservaciones&f=listar',
				    type: 'GET',
				    dataType:'html',
				    success: function(resp){
					 	console.log('----> response listar');
					 	console.log(resp);
					 
					 // Quita loader
			    		$btn.button('reset');
				    	
					// Error: Manda un mensaje con el error
					    if(!resp){
							$mensaje='Error al consultar las reservaciones';
							$.notify(
								$mensaje,
								{
									position:"top center",
							  		autoHide: true,
									autoHideDelay: 5000, 
									className: 'error',
									arrowSize: 15
								}
							);
							
					    	return 0;
					    }
					    
					// Todo bien :D carga el contenido en la Div
						$('#'+$objeto['div']).html(resp);
						
					// Crea el datatable
						reservaciones.convertir_dataTable({id:'tabla_reservaciones_listar'});
				   	}
				});
			},

///////////////// ******** ---- 		FIN listar		------ ************ //////////////////

///////////////// ******** ---- 		convertir_draggable		------ ************ //////////////////
	//////// Convierte los id con draggable en divs ue se pueden arrastrar
		// Como parametros recibe:

			convertir_draggable:function($objeto) {
				console.log('--------> Objet dragables');
				console.log($objeto);
				
			// Convierte los id con draggable en divs ue se pueden arrastrar
				$(function(){
			  		var options = {
				    	float: true,
				        disableResize:true,
				        cell_height: 77,
				        vertical_margin: 5,
				        scroll: false,
				        resizable:{
				        	autoHide: true, 
				        	handles: 'null'
				        },
				        alwaysShowResizeHandle: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
					};
				           
					$('.grid-stack').gridstack(options);
					
				// Agrega el dragable al droppable
					grid = $('.grid-stack').data('gridstack');
				
				// Acomoda los dragables en sus posiciones si no tienen
					$.each($objeto, function(index, value){
					// si la mesa no iene cordenadas le asigna unas y la guarda
						if (value['y'] ==-1 && value['x'] ==-1) {
						// Si X es mayor al numero de columnas(5 equivale a 4 columnas) la regresa al 1 e incrementa Y
							if ($x >10) {
								$x = 0;
								$y+=2;
							}
							
							var result=grid.move($('#'+value['mesa']),$x,$y);
							
							$x+=2;
						}
					});
			  	});
			},

///////////////// ******** ---- 		FIN convertir_draggable		------ ************ //////////////////

///////////////// ******** ---- 		areas		------ ************ //////////////////
	//////// Obtiene el listado de las areas en las que estan las mesas
		// Como parametros recibe:
			// id -> id del area
			
			areas:function($objeto) {
				console.log('---------> objeto Areas');
				console.log($objeto);
		
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=reservaciones&f=areas',
					type : 'POST',
					dataType : 'json',
				}).done(function(resp) {
					console.log('---------> response Areas');
					console.log(resp);
				
				// Refresca la pagina
					$("#tb2158-u .frurl", window.parent.document).attr("src", $("#tb2158-u .frurl", window.parent.document).attr("src"));	
				}).fail(function(resp) {
					console.log('---------> Fail areas');
					console.log(resp);
		
					var $mensaje = 'Error al obtener las mesas';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
						arrowSize : 15
					});
				});
			},

///////////////// ******** ---- 		FIN areas		------ ************ //////////////////

///////////////// ******** ---- 		abrir_reservacion		------ ************ //////////////////
	//////// Abre la ventana modal y escoge la mesa del select
		// Como parametros recibe:
			// mesa -> id de la mesa
			// id -> ID de la reservacion
			
			abrir_reservacion:function($objeto) {
				console.log('---------> objeto abrir_reservacion');
				console.log($objeto);
				
			// Nueva reservacion
				if($objeto['funcion']=='guardar'){
					mesa=$objeto['mesa'];
					
				// Muestra el boton de agregar y oculta los demas
					$('#btn_actualizar_reservacion').hide();
					$('#btn_agregar_reservacion').show();
					$('#btn_terminar_reservacion').hide();
					
				// Deselecciona el cliente
					$('#cliente > option[value="-1"]').attr('selected', 'selected');
					$('.selectpicker').selectpicker('refresh');
					
				// Actualiza la fecha
					var $fecha = new Date();
					var m = $fecha.getMonth() + 1;
					var $mes = (m < 10) ? '0' + m : m;
					var d = $fecha.getDate();
					var $dia = (d < 10) ? '0' + d : d;
					var h = $fecha.getHours();
					var $hora = (h < 10) ? '0' + h : h;
					var mi = $fecha.getMinutes();
					var $minutos = (mi < 10) ? '0' + mi : mi;
					
					$fecha = $fecha.getFullYear() + '-' + $mes + '-' + $dia + 'T' + ($hora + 1) + ':' + $minutos;
					$('#fecha').val($fecha);
							
				// Limpia la descripcion
					$("#des").val('');
    
					return 0;
				}
				
				if($objeto['funcion']=='editar'){
					id_reservacion=$objeto['id'];
			
				// Muestra el boton de actualizar y oculta los demas
					$('#btn_actualizar_reservacion').show();
					$('#btn_agregar_reservacion').hide();
					$('#btn_terminar_reservacion').hide();
					
				// Selecciona el cliente del select
					$("#cliente option").filter(function() {
						return $(this).val() == $objeto['cliente']; 
					}).prop('selected', true);
					$('.selectpicker').selectpicker('refresh');
				
				// Formatea la fecha
					var $fecha=''+$objeto['fecha'];
					$fecha=$fecha.replace(' ', 'T');
					$objeto['fecha']=$fecha.substring(0, 16);
					$("#fecha").val($objeto['fecha']);
					
				// Agrega la descripcion
					$("#des").val($objeto['des']);
				}
				
				if($objeto['funcion']=='terminar'){
					id_reservacion=$objeto['id'];
					
				// Muestra el boton de terminar y oculta los demas
					$('#btn_actualizar_reservacion').hide();
					$('#btn_agregar_reservacion').hide();
					$('#btn_terminar_reservacion').show();
				
				// Selecciona la mesa del select
					$("#mesa option").filter(function() {
						return $(this).val() == $objeto['mesa']; 
					}).prop('selected', true);
				
				// Se utiliza cuando se pretende terminar la reservacion
					mesa=$objeto['mesa'];
					
				// Selecciona el cliente del select
					$("#cliente option").filter(function() {
						return $(this).val() == $objeto['cliente']; 
					}).prop('selected', true);
					$('.selectpicker').selectpicker('refresh');
				
				// Formatea la fecha
					var $fecha=''+$objeto['fecha'];
					$fecha=$fecha.replace(' ', 'T');
					$objeto['fecha']=$fecha.substring(0, 16);
					$("#fecha").val($objeto['fecha']);
					
				// Agrega la descripcion
					$("#des").val($objeto['des']);
				}
			},

///////////////// ******** ---- 		FIN abrir_reservacion		------ ************ //////////////////

///////////////// ******** ---- 		calendario		------ ************ //////////////////
	//////// Abre el modulo de agenda para progrmar una reservacion
		// Como parametros recibe:
			// desde_foodware -> entero como 0,1 para indicar si se llama el modulo desde restaurantes
		
			calendario:function($objeto) {
				console.log('----------> Objeto reservaciones');
				console.log($objeto);
			
			// Abre la pestaña del calendario				
				window.parent.agregatab('../../modulos/restaurantes/reservaciones/index.php', 'Calendario', '', 112);
			},


///////////////// ******** ---- 		FIN calendario		------ ************ //////////////////

///////////////// ******** ---- 		guardar		------ ************ //////////////////
	//////// Guarda la reservacion
		// Como parametros recibe:
			// cliente -> ID del cliente
			// fecha -> fecha y hora de la reservacion
			// btn -> boton del loader
		
			guardar:function($objeto) {
				console.log('----------> Objeto guardar');
				$objeto['mesa']=mesa;
				console.log($objeto);
				
			 // ** Validaciones
			 	if($objeto['cliente']<1){
			 		var $mensaje='Selecciona un cliente';
					$('#'+$objeto['btn']).notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 5000, 
							className: 'warn',
						}
					);
					
					return 0;
			 	}
			 	
			// Loader en el boton OK
				var $btn = $('#'+$objeto['btn']);
			    $btn.button('loading');
						
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=reservaciones&f=guardar',
					type : 'POST',
					dataType : 'json'
				}).done(function(resp) {
					console.log('---------> Done guardar');
					console.log(resp);
		
				// Quita el loader del boton
					$btn.button('reset');
		
				// Todo bien :D
					if (resp['status'] == 1) {
					// Limpia los campos
						$('#cliente').val('');
						$('.selectpicker').selectpicker('refresh');
						$('#des').val('');
						$('#des_espera').val('');
		
					// Actualiza la fecha
						var $fecha = new Date();
						var m = $fecha.getMonth() + 1;
						var $mes = (m < 10) ? '0' + m : m;
						var d = $fecha.getDate();
						var $dia = (d < 10) ? '0' + d : d;
						var h = $fecha.getHours();
						var $hora = (h < 10) ? '0' + h : h;
						var mi = $fecha.getMinutes();
						var $minutos = (mi < 10) ? '0' + mi : mi;
						
						var $fecha_listar_espera = $fecha.getFullYear() + '-' + $mes + '-' + $dia;
						$fecha = $fecha.getFullYear() + '-' + $mes + '-' + $dia + 'T' + ($hora + 1) + ':' + $minutos;
						$('#fecha').val($fecha);
						
					// Asigna la mesa al cliente y cierra la ventana modal
						if($objeto['mesa']>0){
							reservaciones.asignar({id:resp['result'], mesa:$objeto['mesa']});
							$('#btn_cerrar_agregar_reservacion').click();
						}
					
					// Mensaje
						var $mensaje = 'Reservacion guardada';
						$.notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'success',
						});
					
					// Limpia el campo de nombre
						$('#nombre_espera').val('');
						
					// Lista los pendientes en la lista de espera
						reservaciones.listar_pendientes({lista_espera:1, orden:' inicio ASC',status:-1, div:'div_lista_espera', f_ini:$fecha_listar_espera});
						return 0;
					} else {
						var $mensaje = 'Algo salio mal';
		
						$.notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
					}
				}).fail(function(resp) {
					console.log('---------> Fail guardar');
					console.log(resp);
					
				// Quita el loader
					$btn.button('reset');
		
					$mensaje = 'Error al guardar la reservacion';					
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
						arrowSize : 15
					});
				});
			},


///////////////// ******** ---- 		FIN guardar		------ ************ //////////////////

///////////////// ******** ---- 		listar_pendientes		------ ************ //////////////////
	//////// Consulta las reservaciones y las agrega a un div
		// Como parametros recibe:
			// fecha -> fecha y hora del dia
			// div -> div donde se cargara el contenido html
			// lista_espera -> bandera que indica si se debe de carga la lista de espera
					
			listar_pendientes:function ($objeto) {
			 	console.log('----> Objeto listar_pendientes');
			 	console.log($objeto);
			 	
			// Loader
				$("#"+$objeto['div']).html('<div align="center"><i class="fa fa-refresh fa-5x fa-spin"></i></div>');
			    
			 	$.ajax({
					data:$objeto,
				    url:'ajax.php?c=reservaciones&f=listar_pendientes',
				    type: 'GET',
				    dataType:'html',
				    success: function(resp){
					 	console.log('----> response listar_pendientes');
					 	console.log(resp);
				    	
					// Error: Manda un mensaje con el error
					    if(!resp){
							$mensaje='Error al consultar las reservaciones';
							$.notify(
								$mensaje,
								{
									position:"top center",
							  		autoHide: true,
									autoHideDelay: 5000, 
									className: 'error',
									arrowSize: 15
								}
							);
							
					    	return 0;
					    }
					    
					// Actualiza la fecha
						var $fecha = new Date();
						var m = $fecha.getMonth() + 1;
						var $mes = (m < 10) ? '0' + m : m;
						var d = $fecha.getDate();
						var $dia = (d < 10) ? '0' + d : d;
						
						$fecha = $fecha.getFullYear() + '-' + $mes + '-' + $dia + 'T';
					
						reservaciones.status_reservaciones({status:1, f_ini:$fecha+'00:01', f_fin:$fecha+'23:59'});
						
					// Todo bien :D carga el contenido en la Div
						$('#'+$objeto['div']).html(resp);
					
					// La funcion es cargada desde el boton ver
						if(!$objeto['lista_espera']){
						// Crea el datatable
							reservaciones.convertir_dataTable({id:'tabla_reservaciones'});
							
							$('.selectpicker').selectpicker('refresh');
						}
				   	}
				});
			},

///////////////// ******** ---- 		FIN listar_pendientes		------ ************ //////////////////

///////////////// ******** ---- 		listar_reservaciones		------ ************ //////////////////
	//////// Consulta las reservaciones y las agrega a un div
		// Como parametros recibe:
			// fecha -> fecha y hora del dia
			// div -> div donde se cargara el contenido html
			// lista_espera -> bandera que indica si se debe de carga la lista de espera
					
			listar_reservaciones:function ($objeto) {				
			 	console.log('----> Objeto listar_reservaciones');
			 	console.log($objeto);

			// Loader
			    $.ajax({ /// TODOS LOS MOVIMIENTOS ENTRE EL RANGO DE FECHAS 'movs'
		                data:$objeto,
				    	url:'ajax.php?c=reservaciones&f=listar_reservaciones',
		                type: 'GET',
		                dataType: 'json'	
		        })
		        .done(function(data) { 
		        	$.each(data, function(index, val) {
		        			// notificacion
		        			var $mensaje = 'Llego el Cliente '+val.cliente+'? su reservacion fue a las '+val.inicio;
		        			$.notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 9000,
								className : 'info',
								arrowSize : 15
							});	        		
		        	});
		        	console.log(data);
		        });
			},

///////////////// ******** ---- 		FIN listar_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 		asignar		------ ************ //////////////////
	//////// Asigna una mesa a la reservacion
		// Como parametros recibe:
			// mesa -> ID de la mesa
			// id -> ID de la reservacion
			
			asignar:function ($objeto) {
				console.log('----> Objeto asignar');
				console.log($objeto);
				
				if(!$objeto['mesa']){
			 		var $mensaje='Selecciona una mesa';
					$('#tabla_reservaciones').notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 5000, 
							className: 'warn',
						}
					);
					
					return 0;
			 	}
			 
			 // Loader
				$('#loader_'+$objeto['id']).html("<i class='fa fa-refresh fa-spin'></i>");
							
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=reservaciones&f=asignar',
					type : 'GET',
					dataType : 'json',
					success : function(resp) {
						console.log('----> response asignar');
						console.log(resp);
					
					// Quita loader
						$('#loader_'+$objeto['id']).html("<i class='fa fa-cutlery'></i>");
				
					// Todo bien :D
						if(resp['status']==1){
						// Muestra el boton de terminar y oculta los demas
							$('#tr_'+$objeto['id']).addClass('warning');
							$('#btn_terminar_'+$objeto['id']).show();
							$('#btn_editar_'+$objeto['id']).hide();
							$('#btn_eliminar_'+$objeto['id']).hide();
							$('#td_select_'+$objeto['id']).hide();
							
						// Agrega el atributo de la mesa al boton de terminar
							$('#btn_terminar_'+$objeto['id']).attr('mesa', $objeto['mesa']);
							
						// Bloquea la mesa
							$(".opcion_"+$objeto['mesa']).prop( "disabled", true );
							$('.selectpicker').selectpicker('refresh');
							
						// Cambia la mesa a reservada
							$('#panel_'+$objeto['mesa']).removeClass("panel-default").addClass("panel-warning");
						
						// Agrega atributos a la mesa para indicar la reservacion
							$('#mesa_'+$objeto['mesa']).attr({
								id_reservacion : $objeto['id'],
								cliente : $objeto['cliente'],
								des : $objeto['des'],
								fecha : $objeto['fecha'],
								funcion : 'editar'
							});
							
							id_reservacion=0;
							mesa=0;
							
						// Actualiza la fecha
							var $fecha = new Date();
							var m = $fecha.getMonth() + 1;
							var $mes = (m < 10) ? '0' + m : m;
							var d = $fecha.getDate();
							var $dia = (d < 10) ? '0' + d : d;
							
							var $fecha_listar_espera = $fecha.getFullYear() + '-' + $mes + '-' + $dia;
							$fecha = $fecha.getFullYear() + '-' + $mes + '-' + $dia + 'T';
						
							reservaciones.status_reservaciones({status:1, f_ini:$fecha+'00:01', f_fin:$fecha+'23:59'});
							
						// Lista los pendientes en la lista de espera
							reservaciones.listar_pendientes({lista_espera:1, orden:' inicio ASC', status:-1, div:'div_lista_espera', f_ini:$fecha_listar_espera});
							return 0;
						}
						
					// La mesa ya esta asignada
						if (resp['status'] == 2) {
							$mensaje = 'Este mesa ya esta asignada a un cliente';
							$.notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'warn',
								arrowSize : 15
							});
		
							return 0;
						}
						
					// Error: Manda un mensaje con el error
						if (!resp) {
							$mensaje = 'Error al asignar la reservaciones';
							$.notify($mensaje, {
								position : "top center",
								autoHide : true,
								autoHideDelay : 5000,
								className : 'error',
								arrowSize : 15
							});
		
							return 0;
						}
					}
				});
			},

///////////////// ******** ---- 		FIN asignar		------ ************ //////////////////

///////////////// ******** ---- 		status_mesas		------ ************ //////////////////
	//////// Consulta el status de las mesas
		// Como parametros recibe:
			
			status_mesas:function ($objeto) {
				console.log('----> Objeto status_mesas');
				console.log($objeto);
				
				$.ajax({
					url : 'ajax.php?c=comandas&f=checkMesas',
					type : 'POST',
					dataType : 'json',
					async : false,
				}).done(function(resp) {
					console.log('----> Done status_mesas');
					console.log(resp);
					
					if (resp["status"]) {
						$.each(resp["comandas"], function(index, val) {
							$('#mesa_' + resp["comandas"][index]["mesa"]).css('background-color', '#FF6961');
						});
					}
				}).fail(function() {
					console.log("Error en status de mesa");
				});
			},


///////////////// ******** ---- 		FIN status_mesas		------ ************ //////////////////

///////////////// ******** ---- 		status_reservaciones		------ ************ //////////////////
	//////// Consulta el status de las reservaciones
		// Como parametros recibe:
			
			status_reservaciones:function ($objeto) {
				console.log('----> Objeto status_reservaciones');
				console.log($objeto);
				
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=reservaciones&f=status_reservaciones',
					type : 'POST',
					dataType : 'json',
					async : false,
				}).done(function(resp) {
					console.log('----> Done status_reservaciones');
					console.log(resp);
					
					if(resp['status']==1){
						$.each(resp['result'], function(index, value) {
						// Cambia el color de la mesa a reservada
							$('#panel_'+value['mesa']).removeClass("panel-default").addClass("panel-warning");
							
						// Bloquea la mesa del listado
							$('.opcion_'+value['mesa']).prop("disabled", true);
							$('.selectpicker').selectpicker('refresh');
						
						// Agrega atributos a la mesa para indicar la reservacion
							$('#mesa_'+value['mesa']).attr({
								id_reservacion : value['id'],
								cliente : value['idCliente'],
								des : value['descripcion'],
								fecha : value['inicio'],
								funcion: 'terminar'
							});
						
						// Agrega el popup sobre la mesa
							reservaciones.tooltips(value);
						});
					}
				}).fail(function(resp) {
					console.log("Error al consultar el status de las reservaciones");
					console.log(resp);
				});
			},


///////////////// ******** ---- 		FIN status_reservaciones		------ ************ //////////////////

///////////////// ******** ---- 				eliminar				------ ************ //////////////////
	//////// Elimina la reservacion
		// Como parametros recibe:
			// id -> ID de la reservacion
					
			eliminar:function ($objeto) {
				console.log('----> Objeto eliminar');
				console.log($objeto);
				if(confirm("¿Estas seguro de cancelar la reservacion?")){
				// Loader
					var $btn = $('#' + $objeto['btn']);
					$btn.button('loading');
			
					$.ajax({
						data : $objeto,
						url : 'ajax.php?c=reservaciones&f=eliminar',
						type : 'POST',
						dataType : 'json'
					}).done(function(resp) {
						console.log('----> Done eliminar');
						console.log(resp);
					
					// Quita el loader
						$btn.button('reset');
					
					// Todo bien :D
						if (resp['status'] == 1) {
					 		var $mensaje='Reservacion cancelada';
							$('#tabla_reservaciones').notify(
								$mensaje,
								{
									position:"top center",
							  		autoHide: true,
									autoHideDelay: 5000, 
									className: 'success',
								}
							);
						
						// indica que se elimino la reservacion y oculta los botones
							$('#tr_'+$objeto['id']).addClass('danger');
							$('#btn_terminar_'+$objeto['id']).hide();
							$('#btn_editar_'+$objeto['id']).hide();
							$('#btn_eliminar_'+$objeto['id']).hide();
							$('#td_select_'+$objeto['id']).hide();
						}
					}).fail(function(resp) {
						console.log('----> fail eliminar');
						console.log(resp);
					// Quita el loader
						$btn.button('reset');
						
						$mensaje='Error al eliminar la reservacion';
						$.notify(
							$mensaje,
							{
								position:"top center",
						  		autoHide: true,
								autoHideDelay: 5000, 
								className: 'error',
								arrowSize: 15
							}
						);
					});
				}
			},
			
///////////////// ******** ---- 				FIN eliminar			------ ************ //////////////////

///////////////// ******** ---- 				tooltips				------ ************ //////////////////
	//////// Muestra un popup sobre la mesa con reservacion
		// Como parametros recibe:
			// id -> ID de la reservacion
			// mesa -> ID de la mesa
			
			tooltips : function($objeto) {
				console.log('----> Objeto tooltips');
				console.log($objeto);
		
				$contenido = "	<div align='center'>";
				$contenido += "		<button id='tooltips_"+$objeto['id']+"' onclick='reservaciones.terminar({btn:\"tooltips_"+$objeto['id']+"\",id:"+$objeto['id']+", mesa:"+$objeto['mesa']+"})' type='button' data-loading-text='<i class=\"fa fa-refresh fa-spin\"></i>' class='btn btn-success btn-lg'>";
				$contenido += "			<i class='fa fa-check'></i> Terminar";
				$contenido += "		</button>";
				$contenido += "	</div>";
				
				$('#mesa_'+$objeto['mesa']).tooltipster({
					contentAsHTML : true,
					interactive : true,
					animation : 'fall',
					autoClose : false,
					theme : 'tooltipster-Shadow',
					zIndex : 0,
					content : $contenido,
				}).mouseenter();
			},


///////////////// ******** ---- 				FIN tooltips			------ ************ //////////////////

///////////////// ******** ---- 				terminar				------ ************ //////////////////
	//////// Termina la reservacion
		// Como parametros recibe:
			// id -> ID de la reservacion
			// btn -> Nombre del boton del loader
			// mesa -> ID de la mesa
					
			terminar:function ($objeto) {
			// Se utilizan cuando es llamada desde el boton "terminar" de la ventana modal
				if(!$objeto['id']){
					$objeto['id']=id_reservacion;
				}
				
				if(!$objeto['mesa']){
					$objeto['mesa']=mesa;
				}
				
				console.log('----> Objeto terminar');
				console.log($objeto);
				
			// Loader
				var $btn = $('#' + $objeto['btn']);
				$btn.button('loading');
		
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=reservaciones&f=terminar',
					type : 'POST',
					dataType : 'json'
				}).done(function(resp) {
					console.log('----> Done terminar');
					console.log(resp);
				
				// Quita el loader
					$btn.button('reset');
				
				// Todo bien :D
					if (resp['status'] == 1) {
				 		var $mensaje='Reservacion terminada';
						$('#' + $objeto['btn']).notify(
							$mensaje,
							{
								position:"top center",
						  		autoHide: true,
								autoHideDelay: 5000, 
								className: 'success',
							}
						);
					
					// Elimina el tooltipster
						$('#mesa_'+$objeto['mesa']).tooltipster("destroy");
							
					// Cambia el color de la mesa y el tr
						$('#panel_'+$objeto['mesa']).removeClass("panel-warning").addClass("panel-default");
						$('#tr_'+$objeto['id']).removeClass("warning").addClass("success");
						$('#td_select_'+$objeto['id']).hide();
						$('#btn_terminar_'+$objeto['id']).hide();
					
					// Devuelve la mesa a su estado original
						$('#mesa_'+$objeto['mesa']).removeAttr('id_reservacion cliente des fecha');
						$('#mesa_'+$objeto['mesa']).attr({funcion : 'guardar'});
						
					// Desbloquea la mesa
						$('.opcion_'+$objeto['mesa']).prop("disabled", false);
						$('.selectpicker').selectpicker('refresh');
							
					// Cierra la ventana modal
						$('#btn_cerrar_agregar_reservacion').click();
					}
				}).fail(function(resp) {
					console.log('----> fail terminar');
					console.log(resp);
					
				// Quita el loader
					$btn.button('reset');
					
					$mensaje='Error al terminar la reservacion';
					$('#' + $objeto['btn']).notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 5000, 
							className: 'error',
							arrowSize: 15
						}
					);
				});
			},
			
///////////////// ******** ---- 				FIN terminar			------ ************ //////////////////

///////////////// ******** ---- 				actualizar				------ ************ //////////////////
	//////// Actualizar la reservacion
		// Como parametros recibe:
			// cliente -> ID del cliente
			// fecha -> fecha y hora de la reservacion
			// btn -> boton del loader
		
			actualizar:function($objeto) {
				$objeto['id']=id_reservacion;
				console.log('----------> Objeto actualizar');
				console.log($objeto);
				
			 // ** Validaciones
			 	if(!$objeto['fecha']){
			 		var $mensaje='Fecha invalida: \n Ejem. 13/01/2015 13:21';
					$('#'+$objeto['btn']).notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 5000, 
							className: 'warn',
						}
					);
					
					return 0;
			 	}
			 	
			 	if(!$objeto['cliente']){
			 		var $mensaje='Selecciona un cliente';
					$('#'+$objeto['btn']).notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 5000, 
							className: 'warn',
						}
					);
					
					return 0;
			 	}
			 	
			// Loader en el boton modificar
				var $btn = $('#'+$objeto['btn']);
			    $btn.button('loading');
						
				$.ajax({
					data : $objeto,
					url : 'ajax.php?c=reservaciones&f=actualizar',
					type : 'POST',
					dataType : 'json'
				}).done(function(resp) {
					console.log('---------> Done actualizar');
					console.log(resp);
		
				// Quita el loader del boton
					$btn.button('reset');
		
				// Todo bien :D
					if (resp['status'] == 1) {
					// Limpia los campos
						$('#cliente').val('');
						$('.selectpicker').selectpicker('refresh');
						$('#des').val('');
		
					// Actualiza la fecha
						var $fecha = new Date();
						var m = $fecha.getMonth() + 1;
						var $mes = (m < 10) ? '0' + m : m;
						var d = $fecha.getDate();
						var $dia = (d < 10) ? '0' + d : d;
						var h = $fecha.getHours();
						var $hora = (h < 10) ? '0' + h : h;
						var mi = $fecha.getMinutes();
						var $minutos = (mi < 10) ? '0' + mi : mi;
						
						$fecha = $fecha.getFullYear() + '-' + $mes + '-' + $dia + 'T' + ($hora + 1) + ':' + $minutos;
						$('#fecha').val($fecha);
					
					// Mensaje
						var $mensaje = 'Reservacion actualizada';
						$.notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'success',
						});
						
					// Lista las reservaciones pendientes
						var $fecha = new Date();
						$fecha = $fecha.getFullYear() + '-' + $mes + '-' + $dia;
						reservaciones.listar_pendientes({orden:' inicio DESC',status:-1, div: 'div_listar_pendientes', f_ini:$fecha});
						
					// Cierra la ventana modal
						$('#btn_cerrar_agregar_reservacion').click();
					
						return 0;
					} else {
						var $mensaje = 'Algo salio mal';
						$('#' + $objeto['btn']).notify($mensaje, {
							position : "top center",
							autoHide : true,
							autoHideDelay : 5000,
							className : 'error',
						});
					}
				}).fail(function(resp) {
					console.log('---------> Fail actualizar');
					console.log(resp);
					
				// Quita el loader
					$btn.button('reset');
		
					$mensaje = 'Error al actualizar la reservacion';
					$.notify($mensaje, {
						position : "top center",
						autoHide : true,
						autoHideDelay : 5000,
						className : 'error',
						arrowSize : 15
					});
				});
			},


///////////////// ******** ---- 				FIN actualizar			------ ************ //////////////////

///////////////// ******** ---- 		graficar		------ ************ //////////////////
	//////// Genera una grafica y la carga en la div
		// Como parametros recibe:
			// div -> Div donde se cargara la grafica
			// datos -> Array con los datos a graficar
			// tipo -> tipo de grafica(dona, barras, linea, linea y area)
			
			 graficar:function ($objeto) {
			 	console.log('------------> $objeto graficar');
			 	console.log($objeto);
			 
			 // Grafica de dona
			 	if($objeto['dona']){
					Morris.Donut({
						element: $objeto['div']+'_dona',
						data: $objeto['dona']
					});
			 	}
			 	
			 // Grafica de barras
			 	if($objeto['barras']){
					Morris.Bar({
						element: $objeto['div']+'_barras',
						data: $objeto['barras'],
						xkey: $objeto['x'],
						ykeys: [$objeto['y']],
						labels: [$objeto['label']]
					});
			 	}
			 	
			 // Grafica lineal
			 	if($objeto['lineal']){
					Morris.Line({
						element: $objeto['div']+'_lineal',
						data: $objeto['lineal'],
						xkey: $objeto['x'],
						ykeys: [$objeto['y']],
						labels: [$objeto['label']]
					});
			 	}
			 	
			},

///////////////// ******** ---- 		FIN graficar		------ ************ //////////////////

///////////////// ******** ---- 	convertir_dataTable		------ ************ //////////////////
//////// Conviertela tabla en dataTable
	// Como parametros recibe:
		// id -> ID de la tabla a convertir
	
	convertir_dataTable : function($objeto) {
		console.log('objeto convertir dataTable');
		console.log($objeto);
		
		var $orden = ($objeto['orden']) ? $objeto['orden'] : 'desc';
		
	// Validacion para evitar error al crear el dataTable
		if (!$.fn.dataTable.isDataTable('#' + $objeto['id'])) {
			$('#' + $objeto['id']).DataTable({
				language : {
					search : "<i class=\"fa fa-search\"></i>",
					lengthMenu : "_MENU_ por pagina",
					zeroRecords : "No hay datos.",
					infoEmpty : "No hay datos que mostrar.",
					info : " ",
					infoFiltered : " -> <strong> _TOTAL_ </strong> resultados encontrados",
					paginate : {
						first : "Primero",
						previous : "<<",
						next : ">>",
						last : "Último"
					}
				},
				order: [[0, $orden]]
			});
		}
	},
	
///////////////// ******** ---- 	FIN convertir_dataTable		------ ************ //////////////////

///////////////// ******** ---- 		guardar_cliente		------ ************ //////////////////
//////// Agrega un cliente a la base de datos en la tabla comun_cliente
	// Como parametros puede recibir:
		// id-> id del formulario
		
	guardar_cliente: function($objeto) {
		console.log('------> Objeto guardar_cliente');
		console.log($objeto);

		var $datos = {};
		var $requeridos = [];
		var error = 0;
		var $mensaje = 'Campos incorrectos: \n';
		var filtro_mail = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		var filtro_tel = /^[0-9]{10}$/;
		var filtro_nombre = /^[A-Za-z\_\-\.\s]+$/;

	// ** Validaciones
	// Obtiene todos los input del formulario
		var $inputs = $('#' + $objeto.formulario + ' :input');
		// console.log($inputs);

	// Recorre los input para asignarlos a un arreglo
		$inputs.each(function() {
			var required = $(this).attr('required');
			var valor = $(this).val();
			var id = this.id;

		// Valida que el campo no este vacio si es requerido
			if (required == 'required' && valor.length <= 0) {
				error = 1;

				$requeridos.push(id);
			}

		// Valida el E-mail
			if (id == 'mail' && valor.length > 0) {
				if (!filtro_mail.test(valor)) {
					error = 1;
					$mensaje += '\n * Direccion de E-mail invalida * \n Ejem: fer@netwar.com \n';
				}
			}

		// Valida el nombre
			if (id == 'nombre' && valor.length > 0) {
				if (!filtro_nombre.test(valor)) {
					error = 1;
					$mensaje += '\n * Nombre invalido * \n Ejem: Fer De La Cruz \n';
				}
			}

		// Valida el Telefono
			if (id == 'tel' && valor.length > 0) {
				if (!filtro_tel.test(valor)) {
					error = 1;
					$mensaje += '\n * Telefono invalido * \n Ejem: 0123456789 \n';
				}
			}

		// Valida el Codigo postal
			if (id == 'cp' && valor > 99999) {
				if (!filtro_tel.test(valor)) {
					error = 1;
					$mensaje += '\n * Codigo postal invalido * \n Ejem: 01234 \n';
				}
			}

			$datos[this.id] = $(this).val();
		});

	// Valida que los campos requeridos estes llenos
		if ($requeridos.length > 0) {
			$mensaje += '\n Debes llenar los siguientes campos: \n';
			// Recorre el array con los campos requeridos para crear el mensaje
			$.each($requeridos, function(index, value) {
				$mensaje += '* ' + this + ' * \n';
			});
		}

	// Si hay algun error no realiza el ajax y muestra el mensaje con los errores
		if (error == 1) {
			$('#headingOne').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 10000,
				className : 'warn',
			});

			return 0;
		}

		$datos['funcion'] = 'guardar_cliente';

	// Loader en el boton OK
		var $btn = $('#' + $objeto['btn']);
		$btn.button('loading');

		console.log('------> datos guardar_cliente');
		console.log($datos);

	// Inserta el registro en la base de datos, devuelve un mensaje si es exitoso o no
		$.ajax({
			url : 'ajax.php?c=reservaciones&f=guardar_cliente',
			type : 'POST',
			data : $datos,
			dataType : 'json',
		}).done(function(response) {
			console.log(response);

		// Quita el loader
			$btn.button('reset');

			if (response['status'] == 1) {
			// Cierra la modal
				$('#close_agregar_cliente').click();

				$mensaje = 'Cliente agregado con exito';
				$('#notificaciones_clientes').notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'success',
					arrowSize : 15
				});

			// Agrega el cliente al select y lo selecciona
				var $opcion = '	<option value="' + response['result'] + '" selected="selected">';
				$opcion += $datos['nombre'];
				$opcion += '		</option>';
				$('#cliente').append($opcion);
				$('#cliente').selectpicker('refresh');
			} else {
				$mensaje = 'Error al agregar cliente';
				$('#headingOne').notify($mensaje, {
					position : "top center",
					autoHide : true,
					autoHideDelay : 5000,
					className : 'error',
				});
			}
		}).fail(function(resp) {
			console.log('---------> Fail guardar cliente');
			console.log(resp);

		// Quita el loader
			$btn.button('reset');

			$mensaje = 'Error al guardar el cliente';
			$('#headingOne').notify($mensaje, {
				position : "top center",
				autoHide : true,
				autoHideDelay : 5000,
				className : 'error',
				arrowSize : 15
			});
		});
	},

			
///////////////// ******** ---- 			FIN	guardar_cliente					------ ************ //////////////////

///////////////// ******** ---- 			convertir_calendario				------ ************ //////////////////
//////// Convierte el input en calendario
	// Como parametros recibe:
		// id -> ID del input

	convertir_calendario : function($objeto) {
		console.log('objeto convertir_calendario');
		console.log($objeto);
		
		$.fn.datepicker.dates['es'] = 
		{
            days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
            daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
            daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", 
            	"Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
            ],
            monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        };
        
		$("#"+$objeto['id']).datepicker({
			isRTL: false,
		 	format: 'yyyy-mm-dd',
		 	language: 'es',
	        autoclose :true
	    });
	},

///////////////// ******** ---- 			FIN convertir_calendario			------ ************ //////////////////

};