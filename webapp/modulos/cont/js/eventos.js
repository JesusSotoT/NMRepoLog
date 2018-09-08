function agregar_editar_evento(){
	var titulo, fecha_antes, fecha_despues, estatus, id, error_correo = false, email, asunto, mensaje;
	id = $('.title').attr('data-id');
	//Si la id no esta definida la asignamos a 0
	if (id == undefined || id == null) {
		id = 0;
	}
	//Obtenemos los valores del formulario
	email = $('#email').val();
	asunto = $('#asunto').val();
	titulo = $('#titulo').val();
	mensaje = $('#mensaje').val();
	estatus = $('#estatus').val();
	instancia = $('#instancia').val();
	lista_usuarios = $('#clientes').val();
	fecha_antes = $('#fecha_antes').val();
	fecha_despues = $('#fecha_despues').val();

	//Almacenamos el valor de las fechas en otro formato para poder validarlas
	fa = moment(fecha_antes, 'YYYY-MM-DD');
	fd = moment(fecha_despues, 'YYYY-MM-DD');

	//Validamos los campos de correo
  if ($('#mostrar_correo').val() == 1) {
  	//Validamos que se encuentre al menos un email
  	if (($('#email').val() != '' || $('#email').val() != undefined)) {
  		//Validamos que se formule correctamente el correo
  		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
			email = email.split(",");
			if ($.isArray(email)) {
				$.each(email, function(index, value){
					if (!regex.test(value)) {
						alert("El correo "+value+" esta mal formulado.");
						error_correo = true;
					}
				});
			} else {
				if (!regex.test(email)) {
					alert("El correo "+email+" esta mal formulado.");
					error_correo = true;
				}
			}
  		//Validamos que llene al menos uno de los campos de asunto o mensaje
  		if (($('#asunto').val() == '' && $('#mensaje').val() == '')) {
  			alert("Debe llenar al menos uno de los campos de asunto o mensaje.");
  			error_correo = true;
  		}
  	}
  }

	//Validamos que se seleccione un usuario al menos.
	if(lista_usuarios == null || lista_usuarios == undefined){
		alert("Debe seleccionar al menos un usuario.");
	// -------
	//Validamos que el titulo no este vacío
	} else if((titulo == '') || (titulo == undefined)){
		alert("El campo titulo esta vacío.");
	//Validamos las fechas
	} else if(!validar_fecha(fa, fd)){
		/* Si hay error no hara nada.
		 * El metodo validar_fecha ya menciona que salio mal por medio de alerts
		 * --------- */
	} else if(error_correo){
		//Si hay errores en el formulario del correo se detiene la funcion.
		alert("Hubo errores al generar el correo, intente de nuevo.");
	} else {
		$.post("ajax.php?c=edu&f=agregar_evento",
		{
			id: id,
			email: email,
			asunto: asunto,
			titulo: titulo,
			mensaje: mensaje,
			estatus: estatus,
			instancia: instancia,
			fecha_antes:fecha_antes,
			correos: $('#email').val(),
			fecha_despues:fecha_despues,
			lista_usuarios: lista_usuarios,
			validar_correo: $('#mostrar_correo').val()
		},
		function(data){
			console.log(data);
			//Validamos que se haya agregado correctamente
			if (data.resultado == 1) {
				alert("El evento se agrego exitosamente.");
				//Validamos que se haya seleccionado la opcion de enviar correo
				if ($('#mostrar_correo').val() == 1) {
					//Validamos que se haya enviado correctamente el correo
					if (data.correo == 1 && $('#mostrar_correo').val() == 1) {
						alert("El correo se envio exitosamente.");
					} else {
						//Si no, mostrara un mensaje de error
						alert("Fallo el envio del correo.\n"+data.correo);
					}
					if (data.correo == 1 && $('#mostrar_correo').val() == 1) {
						alert("El correo se envio exitosamente.");
					} else {
						//Si no, mostrara un mensaje de error
						alert("Fallo el envio del correo.\n"+data.correo);
					}
				}
				window.location.replace("index.php?c=edu&f=ver_eventos");
			}
			//Validamos que se haya editado correctamente
			else if(data.resultado == 2){
				alert("El evento se guardo exitosamente.");
				//Validamos que se haya seleccionado la opcion de enviar correo
				if ($('#mostrar_correo').val() == 1) {
					//Validamos que se haya enviado correctamente el correo
					if (data.correo == 1 && $('#mostrar_correo').val() == 1) {
						alert("El correo se envio exitosamente.");
					} else {
						//Si no, mostrara un mensaje de error
						alert("Fallo el envio del correo.\n"+data.correo);
					}
					if (data.correo == 1 && $('#mostrar_correo').val() == 1) {
						alert("El correo se envio exitosamente.");
					} else {
						//Si no, mostrara un mensaje de error
						alert("Fallo el envio del correo.\n"+data.correo);
					}
				} 
				window.location.replace("index.php?c=edu&f=ver_eventos");
			} else {
				alert("No se pudo realizar la operación.");
			}
		}, "JSON");
	}
}

//Obtenemos los eventos para el listado
function obtener_eventos(tipo, instancia){
	$('#loading').show();
	$.post("ajax.php?c=edu&f=obtener_eventos_tabla",
		{
			tipo: tipo,
			instancia: instancia
		},
		function(data){
			console.log(data);
			$('#loading').hide();
			$('#tabla-eventos tbody').html(data);
		});
}

//Validamos la fecha
function validar_fecha(fecha_antes, fecha_despues){
	if (fecha_antes > fecha_despues) {
		alert("Debe ingresar una fecha inicial anterior a la final.");
		return false;
	} else {
		//Validamos que los campos no esten vacios
		if (fecha_antes == '') {
			alert("Debe seleccionar un valor para la fecha inicial");
			return false;
		} else if (fecha_despues == ''){
			alert("Debe seleccionar un valor para la fecha final");
			return false;
		} else {
			return true;
		}
	} 
}

function obtener_lista_usuarios(){
	$.ajax({
    url:'ajax.php?c=edu&f=alumnos_select',
    type:'POST',
    data:{idprofesor:$("#instancia").val()},
    success: function(data) {
	    $('#clientes').html(data).multiselect({
	      maxHeight: 400,
	      numberDisplayed: 2,
	      inheritClass: true,
	      selectAllValue: '0',
	      buttonWidth: '100%',
	      disableIfEmpty: true,
	      enableFiltering: true,
	      allSelectedText: 'Todos',
	      filterPlaceholder: 'Buscar',
	      selectAllJustVisible: false,
	      includeSelectAllOption: true,
	      enableFullValueFiltering: false,
	      selectAllText: 'Seleccionar todos.',
	      enableCaseInsensitiveFiltering: true,
	      nonSelectedText: 'Seleccione los clientes.'
	    });
	    $('#clientes').show();
    }
	});
}

//Hacemos que la cadena de string tenga Title Case.
function toTitleCase(str) {
  return str.replace(/\w\S*/g, function(txt){
  	return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
  });
}

function obtener_evento(id){
	$.post("ajax.php?c=edu&f=obtener_evento",
	{
		id: id
	},
	function(data){
		//console.log(data);
		$('#titulo').val(data.titulo);
		$('#fecha_antes').val(data.fecha_antes);
		$('#fecha_despues').val(data.fecha_despues);
		$('#estatus option[value='+data.estatus+']').prop('selected', true);
		if (data.estatus == 2) {
			$('#estatus option[value=1]').prop('selected', true);
		}
		$.each(data.lista_usuarios, function(i, registro){
			$('.multiselect-container li a label input[value='+registro+']').trigger('click');
		});
		$('#email').val(data.emails);
		$('#asunto').val(data.asunto);
		$('#mensaje').val(data.mensaje);
	}, "JSON");
}

function iniciar_calendario(){
	var my_calendar = {}, eventArray = '', id;
	instancia = $('#titulo_lista').attr('data-inst');
	$('.clndr').on('click', function(){
		id = $(this).attr('id');
	  $.post('ajax.php?c=edu&f=obtener_eventos', 
	  {
	  	id: id,
	  	instancia: instancia
	  },
	  function(data){
	  	console.log(data);
	  	if (!$.isEmptyObject(my_calendar)) {
	  		//my_calendar.clndr1.destroy();
	  		my_calendar.clndr1.setEvents(data);
	  	} else {
	  		eventArray = data;
	  		my_calendar.clndr1 = $('.cal1').clndr({
					daysOfTheWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
					events: eventArray,
					clickEvents: {
						click: function(target){
							mostrarEventosCal(1);
							$('#lista-evento').html('');
							$.each(target.events, function(i, evento){
								$.each(evento, function(campo, valor){
									if (campo == 'title') {
										$('#lista-evento').append("<h3>"+valor+"</h3>");	
									} else {
										//No imprimir nada
									}
								}); // // Campos.
							}); // // Each evento.
							//console.log(target.events);
						} // // Click Function
					}, // // Click Events
					multiDayEvents: {
						singleDay: 'date',
						endDate: 'endDate',
						startDate: 'startDate'
					},
					showAdjacentMonths: true,
					adjacentDaysChangeMonth: false
				});
  		}
	  }, "json");
		$('#clndr-container').modal({show:true});
	});
}

function mostrarEventosCal(tipo){
	$('#eventos').animate({
		opacity: "toggle"
	}, function(){
		//Validamos que sea el boton de mostrar notificaciones para que haga la consulta.
		//De lo contrario, no consultara por nuevas notificaciones.
		if(tipo == 1){
			$('#evento-loading').show();
			var display;
			display = $('#eventos').css('display');
			if(display == "block"){
				$('#evento-loading').hide();
			}	
		}
	});
}