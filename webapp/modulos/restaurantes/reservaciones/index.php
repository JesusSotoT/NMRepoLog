<!DOCTYPE html>
<html>
<head>
<!-- ////////// **					CSS					**///////////////// -->

<!--  bootstrap  -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

<!-- form -->
	<link href='form.css' rel='stylesheet' />

<!-- fullcalendar -->
	<link href='fullcalendar/fullcalendar.css' rel='stylesheet' />
	<link href='fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
	
<!-- jquery-ui -->
	<link href="jquery-ui/css/smoothness/jquery-ui-1.10.3.custom.css" rel="stylesheet">

<!--NETWARLOG CSS-->
	<?php include('../../../netwarelog/design/css.php'); ?>
	<LINK href="../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />
		
<!-- datetimepicker -->
	<link href='datetimepicker/datetimepicker.css' rel='stylesheet' />
	
<!-- bootstrap-select -->
		<link rel="stylesheet" href="../../../libraries/bootstrap-select-1.9.3/dist/css/bootstrap-select.min.css">
	
<!-- ////////// **					FIN CSS					**///////////////// -->


<!-- ////////// **					JS					**///////////////// -->

<!-- jquery -->
	<script src="jquery-ui/js/jquery-1.9.1.js"></script>
	<script src="jquery-ui/js/jquery-ui-1.10.3.custom.js"></script>

<!-- fullcalendar -->
	<script src='fullcalendar/fullcalendar.js'></script>

<!-- datetimepicker -->
	<script src='datetimepicker/datetimepicker.js'></script>
	<script src='datetimepicker/jquery-ui-timepicker-es.js'></script>
	<script src='datetimepicker/ui.datepicker-es.js'></script>

<!-- bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<!-- bootstrap-select  -->
		<script src="bootstrap-select-1.9.3/dist/js/bootstrap-select.min.js"></script>
	
<!--  Notify  -->
	<script src="../js/notify.js"></script>
	
<!-- ////////// **					FIN JS					**///////////////// -->


<script>
$(document).ready(function() {
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
		
	var agregando=true;

	var calendar = $('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		
		disableDragging: true,
		disableResizing:true,
		eventClick: function(calEvent, jsEvent, view) {
			$.ajax({
				type: 'POST',
				url:'form.php',
				data: {
					funcion:"form",
					id:calEvent.id,
					titulo:calEvent.title,
					descripcion:calEvent.description
				},
				success: function(resp){
					$('.opciones-evento').dialog({
						modal: true,
						draggable: true,
						resizable: true,
						width:600,
						height:600,
						open: function(){
							var closeBtn = $('.ui-dialog-titlebar-close');
           					closeBtn.html('x');
           					closeBtn.addClass('btn btn-danger');
							$(this).empty().append(resp);
						},
				
						buttons:{
						/*INICIO ACTUALIZAR EVENTO*/
							"Actualizar": function(){
			  					var filtro_tel=/^[0-9]{10}$/;
			    				var valor=$('#tel').val();
			  					
						// ** Validaciones
									
							// Valida quese seleccione un cliente
								if ($("#cliente").val() == "") {
							    	$('#cliente').notify(
										'Debes seleccionar el cliente',
										{
											position:"left",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
								}

						    	// valida que tenga correo
			    				if ($("#op-"+ed_id).attr("ed-ema") == "") {
							    	$('#cliente').notify(
										'El cliente debe tener registrado un email.',
										{
											position:"left",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
								}
			    
						    // Valida que el numero de personas sea mayor que cero
						    	if($('#num_personas').val()<1){
						    		$('#num_personas').notify(
										'Ingresa el numero de personas',
										{
											position:"top center",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
						    	}
					
							// Valida si la reservacion es para todo el dia
								if ($("#todoeldia").is(':checked')) {
									allDay = true;
								} else {
									allDay = false;
								}
								$('#modalMensajes').modal();
								$.ajax({
									url:'form.php',
									type: 'POST',
									data: {
										funcion:'agregarevento',
										fecha:$("#fecha").val(),
										correo : $("#op-"+$("#cliente").val()).attr('ed-ema'),
										id:$("#id").val(),
										cliente:$("#cliente").val(),
										nombre : $("#op-"+$("#cliente").val()).attr('ed-nom'),
										descripcion:$("#descripcion").val(),
										num_personas : $("#num_personas").val()
									},success: function(resp){
										console.log(resp);
										$('#modalMensajes').modal('hide');

										if(resp==3){
									    	$('.opciones-evento').notify(
												'Estas tratando de ingresar una cita \n que se transpapela con otra , \n checa tu disponibilidad',
												{
													position:"top center",
											  		autoHide: true,
													autoHideDelay: 7000, 
													className: 'error',
												}
											);
											
											return false;
										}
										
										if(resp==4){
									    	$('.opciones-evento').notify(
												'La fecha y hora inicial debe ser mayor a la actual',
												{
													position:"top center",
											  		autoHide: true,
													autoHideDelay: 7000, 
													className: 'warn',
												}
											);
											
											return false;
										}
										
										if(resp==5){
									    	$('.opciones-evento').notify(
												'La fecha y hora inicial debe ser mayor a la Final',
												{
													position:"top center",
											  		autoHide: true,
													autoHideDelay: 7000, 
													className: 'warn',
												}
											);
											
											return false;
										}
										
										if(resp==2){ 
									    	$('.opciones-evento').notify(
												'Error al actualizar, intente mas tarde',
												{
													position:"top center",
											  		autoHide: true,
													autoHideDelay: 7000, 
													className: 'error',
												}
											);
											
											return false;
										}
										$('#calendar').fullCalendar('refetchEvents');
										$('.opciones-evento').dialog('close');
										
									}
								});	
								
								// $(this).dialog('close');
							},
						/*END ACTUALIZAR UN EVENTO*/
						
						/*INICIO ELIMINAR EVENTO*/	
							"Cancelar": function(){
								//alert(calEvent.id);
								$('.dialogoConfirmarEliminar').dialog({
									modal: true,
									minWidth: 390,
									draggable: true,
									resizable: false,
									title:"Cancelar Reservacion",
									open: function(){
										var closeBtn = $('.ui-dialog-titlebar-close');
			           					closeBtn.html('x');
			           					closeBtn.addClass('btn btn-danger');
										$(this).empty().append('¿Estas seguro que deseas cancelar la reservacion?');

									},
									
									buttons:[{
											text:'Cancelar',
											click: function(){ 
												$.ajax({
													url:'form.php',
													type: 'POST',
													data: {funcion:'eliminarevento',id:calEvent.id},
													success: function(resp){
														if(resp==1){
															$('#calendar').fullCalendar( 'refetchEvents' );
															$('.dialogoConfirmarEliminar').dialog('close');
															$('.opciones-evento').dialog('close');
														}
													}
												});	
											}
										},{
											text: 'Salir',
											click: function(){
												$(this).dialog('close');
											}
										}
									]
								}).height('auto');
							},
						//*END ELIMINAR EVENTO*/
					
						//*INICIO Salir EVENTO*/
							"Salir": function(){
								$(this).dialog('close');
							}
						//*END Salir EVENTO*/
						}
					}).height('auto');
				//* FIN  $('.opciones-evento').dialog */
				}
			//* FIN  succsses Ajax */
			});	
		//* FIN Ajax */
		},
	// * FIN eventClick: function */
	
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {
			var now = new Date();
				
			if(allDay){
				now.setDate(now.getDate()-1);
			}
		
			if( (new Date(start).getTime() < now)){
	   			alert("No puedes hacer una cita de una fecha y hora anterior a la actual"); return false;
			}
			
			var fecha = new Date(start);
			var dd = start.getDate();
			var mm = start.getMonth()+1; //January is 0!
			var yyyy = start.getFullYear();
			var diaSel = yyyy+'-'+mm+'-'+dd;

		// Abre el formulario
			$.ajax({
				type: 'POST',
				url:'form.php',
				data: {
					funcion:"form",
					todoeldia:allDay,
					inicio:diaSel,
				},success: function(resp){
					$('.opciones-evento').dialog({
						modal: true,
						draggable: true,
						resizable: true,
						width:600,
						height:600,
						open: function(){
							var closeBtn = $('.ui-dialog-titlebar-close');
           					closeBtn.html('x');
           					closeBtn.addClass('btn btn-danger');
							$(this).empty().append(resp);
						},
						
						buttons: {
						/*INICIO AGREGAR UN EVENTO*/
							"Agregar": function() {

			  					var filtro_tel=/^[0-9]{10}$/;
			    				var valor=$('#tel').val();
			  					
						// ** Validaciones
									
							// Valida quese seleccione un cliente
								if ($("#cliente").val() == "") {
							    	$('#cliente').notify(
										'Debes seleccionar el cliente',
										{
											position:"left",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
								}

			    			// valida que tenga correo
			    				if ($("#op-"+ed_id).attr("ed-ema") == "") {
							    	$('#cliente').notify(
										'El cliente debe tener registrado un email.',
										{
											position:"left",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
								}

						    // Valida que el numero de personas sea mayor que cero
						    	if($('#num_personas').val()<1){
						    		$('#num_personas').notify(
										'Ingresa el numero de personas',
										{
											position:"top center",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
						    	}
					
							// Valida si la reservacion es para todo el dia
								if ($("#todoeldia").is(':checked')) {
									allDay = true;
								} else {
									allDay = false;
								}
								$('#modalMensajes').modal();
								$.ajax({
									url : 'form.php',
									type : 'POST',
									data : {
										funcion : 'agregarevento',
										cliente : $("#cliente").val(),
										correo : $("#op-"+$("#cliente").val()).attr('ed-ema'),
										nombre : $("#op-"+$("#cliente").val()).attr('ed-nom'),
										descripcion : $("#descripcion").val(),
										fecha : $("#fecha").val(),
										num_personas : $("#num_personas").val()
									},success : function(resp) {
										console.log(resp);
										$('#modalMensajes').modal('hide');
										if(resp==3){
									    	$('.opciones-evento').notify(
												'Estas tratando de ingresar una cita \n que se transpapela con otra , \n checa tu disponibilidad',
												{
													position:"top center",
											  		autoHide: true,
													autoHideDelay: 7000, 
													className: 'error',
												}
											);
											
											return false;
										}
										
										if(resp==4){
									    	$('#fecha').notify(
												'La fecha y hora debe ser mayor \n a la actual',
												{
													position:"top center",
											  		autoHide: true,
													autoHideDelay: 7000, 
													className: 'warn',
												}
											);
											
											return false;
										}
										
										if(resp==5){
									    	$('#inicio').notify(
												'La fecha y hora inicial debe ser menor \n a la Final',
												{
													position:"top center",
											  		autoHide: true,
													autoHideDelay: 7000, 
													className: 'warn',
												}
											);
											
											return false;
										}
										
										if(resp==2){ 
									    	$('.opciones-evento').notify(
												'Error al actualizar, \n intente mas tarde',
												{
													position:"top center",
											  		autoHide: true,
													autoHideDelay: 7000, 
													className: 'error',
												}
											);
											
											return false;
										}
				
										$('#calendar').fullCalendar('refetchEvents');
										$('.opciones-evento').dialog('close');
									}
								});
							},
						/*FIN AGREGAR UN EVENTO*/
						}
					}).height('auto');
				}
			});
		// * FIN Ajax */
		},
	// * FIN SELECT function */
		editable: true,
	
	// Carga el archivo de eventos que Consulta las reservaciones
		events:{
			url:'eventos.php',cache:false
		}
	});
// FIN fullCalendar
});
//	
function addgrup(){
	if($("#cliente").val()==""){
		alert("Debes seleccionar el cliente primero");
		
		return false;
	}
	
	$('.dialogoConfirmarEliminar').dialog({
		modal: true,
		minWidth: 390,
		draggable: true,
		resizable: false,
		title:"Agregar grupo",
		open: function(){
		$(this).empty().append('Nombre<input type="text" id="nombregrupo">');},
		buttons:[{text:'Guardar',click: function(){ 
					
					 $.ajax({
					url:'form.php',
					type: 'POST',
					data: {
						funcion:'guardagrupo',nombre:$("#nombregrupo").val(),cliente:$("#cliente").val()},
						success: function(cbores){
							$("#loadgrupos").html(cbores);
							$('.dialogoConfirmarEliminar').dialog('close');

				}});
				
			}},{text: 'Salir',click: function(){$(this).dialog('close');}}]}).height('auto');			
}

///	
function deletegrup(){
	if( $("#grupo").val()!="" ){
		$('.dialogoConfirmarEliminar').dialog({
			modal: true,
			minWidth: 390,
			draggable: true,
			resizable: false,
			title:"Eliminar grupo",
			open: function(){
				$(this).empty().append('¿Estas seguro que deseas eliminar el grupo?');
			},
			
			buttons:[{text:'Eliminar',click: function(){
				$.ajax({
					url:'form.php',
					type: 'POST',
					data: {funcion:'eliminargrupo',id:$("#grupo").val(),cliente:$("#cliente").val()},
					success: function(cbores){
						$("#loadgrupos").html(cbores);
						$('.dialogoConfirmarEliminar').dialog('close');
					}
				});
			}},{text: 'Salir',click: function(){$(this).dialog('close');}}]
		}).height('auto');			
	}
}
var ed_id = 0;
function ReloadSubcliente(element){
	if(element != ''){
		ed_id = element;
		$('.edit').show();
	} else {
		ed_id = 0;
		$('.edit').hide();
	}
}

function guardaGrupo(){
	$("#intercambio").html('<select id="grupo" name="grupo"><option>-Seleccione-</option></select><input type="button" id="addgrupo" value="+" class="add">');	
	agregando=true;
}

///////////////// ******** ---- 		select_buscador		------ ************ //////////////////

	//////// Cambia los select por select con buscador.
		// Como parametros puede recibir:
			// Array con los id de los select
		
			function select_buscador ($objeto) {
			// Recorre el arreglo y establece las propiedades del buscador
				$('.selectpicker').selectpicker('refresh');
			}

///////////////// ******** ---- 		FIN select_buscador		------ ************ //////////////////

///////////////// ******** ---- 		add_cliente		------ ************ //////////////////
	// Abre un formulario con los campos necesarios para registrar un nuevo cliente
	
	function add_cliente($objet) {
		$.ajax({
			type : 'POST',
			url : 'form.php',
			data : {
				funcion : "modal",
			},
			success : function(resp) {
			// Carga el formulario para agregar un cliente
				$('.add_cliente').dialog({
					modal: true,
					minWidth: 390,
					draggable: true,
					resizable: false,
					title:"Agregar cliente",
					open: function(){
						var close = $('.ui-dialog-titlebar-close');
           				close.addClass('btn btn-danger');
           				close.html('x');
						$(this).empty().append(resp);
					},
				// Crea los botones al final del formulario
					buttons:[
						{text:'Guardar',
							click: function(){
								guardar_cliente({formulario: 'modal_cliente_reservaciones'});
							}
						}
					]
				}).height('auto');
			
			// Funcion que cambia el icono en la cabecera
				function cambiar_icono(e) {
				    $(e.target)
				        .prev('.panel-heading')
				        .find("i.indicator")
				        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
				}
			
			// Cuando se oculta el body del accordion cambia el icono(flecha hacia abajo)
				$('#accordion').on('hidden.bs.collapse', cambiar_icono);
				
			// Cuando se oculta el body del accordion cambia el icono(flecha hacia arriba)
				$('#accordion').on('shown.bs.collapse', cambiar_icono);
			}
		});
	}
///////////////// ******** ---- 		add_cliente		------ ************ //////////////////

///////////////// ******** ---- 		edit_cliente		------ ************ //////////////////
	// Abre un formulario con los campos necesarios para registrar un nuevo cliente
	
	function edit_cliente($objet) {
		console.log('edit_cliente');
		console.log($objet);
		console.log($("#op-"+$objet).attr('ed-nom'));
		$.ajax({
			type : 'POST',
			url : 'form.php',
			data : {
				funcion : "modal2",
			},
			success : function(resp) {
			// Carga el formulario para editar un cliente
				$('.edit_cliente').dialog({
					modal: true,
					minWidth: 390,
					draggable: true,
					resizable: false,
					title:"Editar cliente",
					open: function(){
						var close = $('.ui-dialog-titlebar-close');
           				close.addClass('btn btn-danger');
           				close.html('x');
						$(this).empty().append(resp);
						$('#Nombre_edi').val($("#op-"+$objet).attr('ed-nom'));
						$('#Telefono_edi').val($("#op-"+$objet).attr('ed-tel'));
						$('#E-mail_edi').val($("#op-"+$objet).attr('ed-ema'));
					},
				// Crea los botones al final del formulario
					buttons:[
						{text:'Editar',
							click: function(){
								guardar_cliente({formulario: 'modal_cliente_reservaciones2', tipo: 2});
							}
						}
					]
				}).height('auto');
			
			// Funcion que cambia el icono en la cabecera
				function cambiar_icono(e) {
				    $(e.target)
				        .prev('.panel-heading')
				        .find("i.indicator")
				        .toggleClass('glyphicon-chevron-down glyphicon-chevron-up');
				}
			
			// Cuando se oculta el body del accordion cambia el icono(flecha hacia abajo)
				$('#accordion').on('hidden.bs.collapse', cambiar_icono);
				
			// Cuando se oculta el body del accordion cambia el icono(flecha hacia arriba)
				$('#accordion').on('shown.bs.collapse', cambiar_icono);
			}
		});
	}
///////////////// ******** ---- 		edit_cliente		------ ************ //////////////////

///////////////// ******** ---- 		agregar_cliente		------ ************ //////////////////
	//////// Agrega un cliente a la base de datos en la tabla comun_cliente
		// Como parametros puede recibir:
			// id-> id del formulario
		
			function guardar_cliente($objeto){
			  	var $datos = {};
			  	var $requeridos = [];
			  	var error=0;
			    var $mensaje='Campos incorrectos: \n';
			  	var filtro_mail= /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			  	var filtro_tel=/^[0-9]{10}$/;
			  	var filtro_nombre=/^[A-Za-z\_\-\.\s]+$/;
			  	
		// ** Validaciones
			// Obtiene todos los input del formulario
			    var $inputs = $('#'+$objeto.formulario+' :input');
				// console.log($inputs);
				
			// Recorre los input para asignarlos a un arreglo
			    $inputs.each(function() {
			    	var required=$(this).attr('required');
			    	var valor=$(this).val();
			    	var id=this.id;
			    	
			    // Valida que el campo no este vacio si es requerido
			    	if(required=='required'&&valor.length<=0){
			    		error=1;
			    		
			        	$requeridos.push(id);
			    	}
			    
			    // Valida el E-mail
			    	if(id=='E-mail'&&valor.length>0){
			    		if (!filtro_mail.test(valor)){
			    			error=1;
			    			$mensaje+='\n * Direccion de E-mail invalida * \n Ejem: fer@netwar.com \n';	
						}
			    	}
			    
			    // Valida el nombre
			    	if(id=='Nombre'&&valor.length>0){
			    		if (!filtro_nombre.test(valor)){
			    			error=1;
			    			$mensaje+='\n * Nombre invalido * \n Ejem: Fer De La Cruz \n';	
						}
			    	}
			    
			    // Valida el Telefono
			    	if(id=='Telefono'&&valor.length>0){
			    		if (!filtro_tel.test(valor)){
			    			error=1;
			    			$mensaje+='\n * Telefono invalido * \n Ejem: 0123456789 \n';
						}
			    	}
			    	if($objeto.tipo != 2) {
				    // Valida el Codigo postal
				    	if(id=='cp'&&valor>99999){
				    		if (!filtro_tel.test(valor)){
				    			error=1;
				    			$mensaje+='\n * Codigo postal invalido * \n Ejem: 01234 \n';	
							}
				    	}
			    	}	
			        $datos[this.id] = $(this).val();
			    });
			    
			// Valida que los campos requeridos estes llenos
			    if($requeridos.length>0){
			    	$mensaje+='\n Debes llenar los siguientes campos: \n';
				// Recorre el array con los campos requeridos para crear el mensaje
			    	$.each($requeridos, function( index, value ) {
			    		$mensaje+='* '+this+' * \n';
					});
			    }

			// Si hay algun error no realiza el ajax y muestra el mensaje con los errores
			    if(error==1){
			    	if ($objeto.tipo == 2) {
			    		$('.edit_cliente').notify(
							$mensaje,
							{
								position:"top center",
						  		autoHide: true,
								autoHideDelay: 10000, 
								className: 'warn',
							}
						);
			    	} else {
						$('.add_cliente').notify(
							$mensaje,
							{
								position:"top center",
						  		autoHide: true,
								autoHideDelay: 10000, 
								className: 'warn',
							}
						);
					}
					
			   		return 0;
			    }
			
				$datos['funcion']='guardar_cliente';

				$datos['tipo'] = $objeto.tipo;

				$datos['id_cli'] = ed_id;
			
			// Inserta el registro en la base de datos, devuelve un mensaje si es exitoso o no
				$.ajax({
					url: 'form.php',
					type: 'POST',
					data:$datos,
					dataType: 'json'
				}).done(function(response) {
					console.log(response.result);
					
					if(response['result']==1){
						if($objeto.tipo == 2){
							$mensaje='Cliente editado con exito';
							$.notify(
								$mensaje,
								{
									position:"top center",
							  		autoHide: true,
									autoHideDelay: 5000, 
									className: 'success',
									arrowSize: 15
								}
							);
							
							$('.edit_cliente').dialog('close');
							// Lista los clientes y modifica el select con los nuevos clientes
							listar_cliente({id_cli: ed_id});
						} else {
							$mensaje='Cliente agregado con exito';
							$.notify(
								$mensaje,
								{
									position:"top center",
							  		autoHide: true,
									autoHideDelay: 5000, 
									className: 'success',
									arrowSize: 15
								}
							);
								
							$('.add_cliente').dialog('close');
							listar_cliente({id_cli: response['id_cli']});
						}
						
					
					
					}else{
						if($objeto.tipo == 2){
							$mensaje='Error al editar cliente, intente mas tarde';
						
							$('.edit_cliente').notify(
								$mensaje,
								{
									position:"top center",
							  		autoHide: true,
									autoHideDelay: 5000, 
									className: 'error',
								}
							);
						} else {
							$mensaje='Error al agregar cliente, intente mas tarde';
						
							$('.add_cliente').notify(
								$mensaje,
								{
									position:"top center",
							  		autoHide: true,
									autoHideDelay: 5000, 
									className: 'error',
								}
							);
						}
					}
				});
			}
			
///////////////// ******** ---- 	FIN	agregar_cliente		------ ************ //////////////////

///////////////// ******** ---- 		listar_cliente		------ ************ //////////////////
	// Obtiene el cilente final y lo agrega en el select
		// Como parametro puede recibir:
			// id-> ID del cliente
	
	function listar_cliente($objet) {
		$.ajax({
			type : 'POST',
			url : 'form.php',
			dataType: 'json',
			data : {
				funcion : "listar_cliente",
			},
			success : function(resp) {
				console.log(resp);
				var val=1;
				$('#cliente').html('');
				
			// Recorre el arreglo y establece las propiedades del select
				$opcion='<option value="">- Seleccione -</option>';
				
				$.each(resp, function( key, value ) {
					
					if ($objet.id_cli==value['id']) {
						console.log($objet.id_cli+' - '+value['id']);
						$opcion+='<option id="op-'+value['id']+'" ed-nom="'+value['nombre']+'" ed-tel="'+value['celular']+'" ed-ema="'+value['email']+'" selected value="'+value["id"]+'">'+value["nombre"]+'</option>';
						//$tel=value["celular"];
						$()
					} else { 
						$opcion+='<option id="op-'+value['id']+'" ed-nom="'+value['nombre']+'" ed-tel="'+value['celular']+'" ed-ema="'+value['email']+'" value="'+value["id"]+'">'+value["nombre"]+'</option>';
					}
				});
				
				$('#cliente').html($opcion);
				
				$objeto[0]="cliente";
			// Mandamos llamar la funcion que crea el buscador
				select_buscador($objeto);
				ed_id = $objet.id_cli;
				$(".edit").show();
			}
		});
	}
///////////////// ******** ---- 		add_cliente		------ ************ //////////////////

</script>
<style>
	body {
		margin-top: 40px;
		text-align: center;
		font-size: 12px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
	}
	
	#calendar{
		width: 70%;
		margin: 0 auto;
	}
</style>
</head>
<body>
	<div id='calendar'>
		 <!-- Div donde se carga el calendario -->
	</div>
	<div class="opciones-evento" style="overflow: hidden" title="Agregar una reservación">
	</div>
</body>
</html>

<div class="dialogoConfirmarEliminar">
	<!-- Div donde se carga el cuadro que confirma el eliminar -->
</div>
<div class="add_cliente">
	<!-- Div donde se carga el formulario para agregar un nuevo cliente -->
</div>
<div class="edit_cliente">
	<!-- Div donde se carga el formulario para agregar un nuevo cliente -->
</div>
	<div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1100;" data-backdrop="static">
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