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
	
<!--  Select con buscador  -->
	<link rel="stylesheet" type="text/css" href="select2/select2.css" />
	
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

<!--  Select con buscador  -->
	<script src="select2/select2.min.js"></script>
	
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
							$(this).empty().append(resp);
						},
				
						buttons:{
						/*INICIO ACTUALIZAR EVENTO*/
							"Actualizar": function(){
			  					var filtro_tel=/^[0-9]{10}$/;
			    				var valor=$('#tel').val();
			  					
						// ** Validaciones
							// Valida que exista un titulo para la reservacion
								if ($("#titulo").val() == "") {
							    	$('#titulo').notify(
										'Debes ingresar el nombre del evento',
										{
											position:"top center",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
								}
									
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
							
							// Valida que se seleccione una mesa
								if ($("#mesa").val() == "") {
							    	$('#mesa').notify(
										'Debes seleccionar una mesa',
										{
											position:"top center",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
								}
			    
						    // Valida el Telefono
						    	if(valor.length>0){
						    		if (!filtro_tel.test(valor)){
							    		$('#tel').notify(
											'Telfono invalido, Ejem: \n 0123456789',
											{
												position:"top center",
										  		autoHide: true,
												autoHideDelay: 5000, 
												className: 'warn',
											}
										);
										
										return false;
									}
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
					
								$.ajax({
									url:'form.php',
									type: 'POST',
									data: {
										funcion:'agregarevento',
										inicio:$("#inicio").val(),
										fin:$("#fin").val(),
										id:$("#id").val(),
										tel:$("#tel").val(),
										cliente:$("#cliente").val(),
										titulo:$("#titulo").val(),
										descripcion:$("#descripcion").val(),
										todoeldia:allDay,
										mesa : $("#mesa").val(),
										num_personas : $("#num_personas").val()
									},success: function(resp){
										console.log(resp);
										
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
									    	$('#inicio').notify(
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
									    	$('#inicio').notify(
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
										
										$('#calendar').fullCalendar( 'refetchEvents' );
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
							$(this).empty().append(resp);
						},
						
						buttons: {
						/*INICIO AGREGAR UN EVENTO*/
							"Agregar": function() {
			  					var filtro_tel=/^[0-9]{10}$/;
			    				var valor=$('#tel').val();
			  					
						// ** Validaciones
							// Valida que exista un titulo para la reservacion
								if ($("#titulo").val() == "") {
							    	$('#titulo').notify(
										'Debes ingresar el nombre del evento',
										{
											position:"top center",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
								}
									
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
							
							// Valida que se seleccione una mesa
								if ($("#mesa").val() == "") {
							    	$('#mesa').notify(
										'Debes seleccionar una mesa',
										{
											position:"top center",
									  		autoHide: true,
											autoHideDelay: 5000, 
											className: 'warn',
										}
									);
									
									return false;
								}
			    
						    // Valida el Telefono
						    	if(valor.length>0){
						    		if (!filtro_tel.test(valor)){
							    		$('#tel').notify(
											'Telfono invalido, Ejem: \n 0123456789',
											{
												position:"top center",
										  		autoHide: true,
												autoHideDelay: 5000, 
												className: 'warn',
											}
										);
										
										return false;
									}
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
					
								$.ajax({
									url : 'form.php',
									type : 'POST',
									data : {
										funcion : 'agregarevento',
										cliente : $("#cliente").val(),
										tel : $("#tel").val(),
										titulo : $("#titulo").val(),
										descripcion : $("#descripcion").val(),
										inicio : $("#inicio").val(),
										fin : $("#fin").val(),
										todoeldia : allDay,
										mesa : $("#mesa").val(),
										num_personas : $("#num_personas").val()
									},success : function(resp) {
										console.log(resp);
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
									    	$('#inicio').notify(
												'La fecha y hora inicial debe ser mayor \n a la actual',
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
						
							"Salir": function() {
								$(this).dialog('close');
							}
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

function ReloadSubcliente(id){
	$.ajax({
		url:'form.php',
		type: 'POST',
		data: {
			funcion:'reloadgrupo',
			cliente:$("#cliente").val()
		},
		success: function(cbores){
			$("#loadgrupos").html(cbores);
		}
	});
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
				$.each( $objeto, function( key, value ) {
					$("#"+value).select2({
						width : "100px"
					});
				});
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
					title:"Agregar grupo",
					open: function(){
						$(this).empty().append(resp);
					},
				// Crea los botones al final del formulario
					buttons:[
						{text:'Guardar',
							click: function(){
								guardar_cliente({formulario: 'modal_cliente_reservaciones'});
							}
						},
						{text: 'Salir',
							click: function(){
								$(this).dialog('close');
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
			    	if(id=='mail'&&valor.length>0){
			    		if (!filtro_mail.test(valor)){
			    			error=1;
			    			$mensaje+='\n * Direccion de E-mail invalida * \n Ejem: fer@netwar.com \n';	
						}
			    	}
			    
			    // Valida el nombre
			    	if(id=='nombre'&&valor.length>0){
			    		if (!filtro_nombre.test(valor)){
			    			error=1;
			    			$mensaje+='\n * Nombre invalido * \n Ejem: Fer De La Cruz \n';	
						}
			    	}
			    
			    // Valida el Telefono
			    	if(id=='tel'&&valor.length>0){
			    		if (!filtro_tel.test(valor)){
			    			error=1;
			    			$mensaje+='\n * Telefono invalido * \n Ejem: 0123456789 \n';
						}
			    	}
			    
			    // Valida el Codigo postal
			    	if(id=='cp'&&valor>99999){
			    		if (!filtro_tel.test(valor)){
			    			error=1;
			    			$mensaje+='\n * Codigo postal invalido * \n Ejem: 01234 \n';	
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
					$('.add_cliente').notify(
						$mensaje,
						{
							position:"top center",
					  		autoHide: true,
							autoHideDelay: 10000, 
							className: 'warn',
						}
					);
					
			   		return 0;
			    }
			
				$datos['funcion']='guardar_cliente';
			
			// Inserta el registro en la base de datos, devuelve un mensaje si es exitoso o no
				$.ajax({
					url: 'form.php',
					type: 'POST',
					data:$datos,
				}).done(function(response) {
					console.log(response);
					
					if(response==1){
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
					
					// Lista los clientes y modifica el select con los nuevos clientes
						listar_cliente();
					}else{
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
					if (val==1) {
						val=0;
						$opcion+='<option selected value="'+value["id"]+'">'+value["nombre"]+'</option>';
						$tel=value["celular"];
					} else { 
						$opcion+='<option value="'+value["id"]+'">'+value["nombre"]+'</option>';
					}
				});
				
				$('#cliente').html($opcion);
				$('#tel').val($tel);
				
				$objeto[0]="cliente";
			// Mandamos llamar la funcion que crea el buscador
				select_buscador($objeto);
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
	<div class="opciones-evento" title="Agregar un evento a la agenda">
	  	<p>
	  		<span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>
		</p>
	</div>
</body>
</html>

<div class="dialogoConfirmarEliminar">
	<!-- Div donde se carga el cuadro que confirma el eliminar -->
</div>
<div class="add_cliente">
	<!-- Div donde se carga el formulario para agregar un nuevo cliente -->
</div>