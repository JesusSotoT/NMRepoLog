var table;

function cargadatos()
{
	inicializa_lista_users('prof');
    inicializa_lista_users('alum');
    inicializa_lista_grupos();
    selects_rel()
}

function selects_rel()
{
	$.post('ajax.php?c=edu&f=lista_grupos_select', 
		{
			univ: $("#universidad").val()
		}, 
			function(data) 
			{
				$("#grupos_rel").html(data);
			});
}
function inicializa_lista_users(lista)
{
	$('#tabla-'+lista).DataTable().rows().remove();
	$('#tabla-'+lista).DataTable().destroy();
	if(parseInt($("#universidad").val()))
	{
		$.post('ajax.php?c=edu&f=lista_panel', 
			{
				lista: lista,
				univ: $("#universidad").val()
			}, 
			function(data) 
			{
				var datos = jQuery.parseJSON(data);
				
				table = $('#tabla-'+lista).DataTable( {
					dom: 'Bfrtip',
					buttons: [ 
					],
					language: {
						buttons: {
							pageLength: 'Mostrar %d filas'

						},
						search: "Buscar:",
						lengthMenu:"Mostrar _MENU_ elementos",
						zeroRecords: "No hay datos.",
						infoEmpty: "No hay datos que mostrar.",
						info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
						paginate: {
							first:      "Primero",
							previous:   "Anterior",
							next:       "Siguiente",
							last:       "Último"
						}
					 },
					 data:datos,
					 columns: [
						{ data: 'id' },
						{ data: 'razon' },
						{ data: 'nombre' },
						{ data: 'correo' },
						{ data: 'telefono' },
						{ data: 'giro' },
						{ data: 'instancia' },
						{ data: 'usuario_master' },
						{ data: 'pwd_master' },
						{ data: 'profesor' }
					]
				});
				 $('#tabla-'+lista+'_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
			});
	}
}

function inicializa_lista_grupos()
{
	$.post('ajax.php?c=edu&f=lista_todos_grupos',
		{
			univ: $("#universidad").val()
		}, 
			function(data) 
			{
				var datos = jQuery.parseJSON(data);
				
				$('#tabla-grup').DataTable( {
					dom: 'Bfrtip',
					buttons: [ 
					],
					language: {
						buttons: {
							pageLength: 'Mostrar %d filas'

						},
						search: "Buscar:",
						lengthMenu:"Mostrar _MENU_ elementos",
						zeroRecords: "No hay datos.",
						infoEmpty: "No hay datos que mostrar.",
						info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
						paginate: {
							first:      "Primero",
							previous:   "Anterior",
							next:       "Siguiente",
							last:       "Último"
						}
					 },
					 data:datos,
					 columns: [
						{ data: 'id' },
						{ data: 'nombre' },
						{ data: 'mod' },
						{ data: 'elim' }
					]
				});
				$('#tabla-grup_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
				$("#tabla-grup").before($("#boton_virtual").html());
				$("#boton_virtual").hide();
				 
				
			});
}

function inicializa_lista_rel() {	
	$.post('ajax.php?c=edu&f=lista_todos_relaciones', 
	{
		univ: $("#universidad").val(),
		grupo: $("#grupos_rel").val()
	}, 
	function(data) {
		var datos = jQuery.parseJSON(data);
		
		 	table = $('#tabla-rel').DataTable( {
			dom: 'Bfrtip',
				buttons: [ 
			],
			language: {
				buttons: {
					pageLength: 'Mostrar %d filas'
				},
				search: "Buscar:",
				lengthMenu:"Mostrar _MENU_ elementos",
				zeroRecords: "No hay datos.",
				infoEmpty: "No hay datos que mostrar.",
				info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
				paginate: {
					first:      "Primero",
					previous:   "Anterior",
					next:       "Siguiente",
					last:       "Último"
				}
			},
			columnDefs: [
				{ orderable: false, targets: [4] }
			],
			"order": [[ 0, "asc" ]],
			 data:datos,
			 columns: [
				{ data: 'id' },
				{ data: 'profesor' },
				{ data: 'alumno' },
				{ data: 'grupo' },
				{ data: 'elim' }
			]
		});
		$('#tabla-rel_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
		$("#tabla-rel").before($("#boton_virtual3").html());
		$("#boton_virtual3").hide();
	});
  $("#checkAll").on('click', function() {
  	var rows = table.rows({ 'search': 'applied' }).nodes();
		$('input[type="checkbox"]', rows).prop('checked', this.checked);
	});
}

function nuevo_grupo()
{
	$("#blanco").show();
	$("#id_grupo").val(0);
	$("#nombre_grupo").val('');
	$("#blanco").hide();

}

function nueva_relacion(t)
{
	$("#blanco2").show();
	$("#id_rel").val(0);
	$("#id_profe").val('');
	$("#id_alumno").val('');
	$("#blanco2").hide();
	$("#mensajes_alumno,#mensajes_profe").html('')
	if(!t)//Si es cero busca el profesor por default
	{
		$.post('ajax.php?c=edu&f=profe_default', 
		{
			grupo:$("#grupos_rel").val()
		},
			function(data) 
			{
				//alert(data)
				$("#id_profe").val(data).trigger('change');
			});
	}
	
}

function cancelar_rel()
{
	nueva_relacion(1);
	$('.bs-relaciones-modal-sm').modal('hide');
}

function modificar_grupo(id)
{
	nuevo_grupo()
	$.post('ajax.php?c=edu&f=datos_grupo', 
			{
				id: id
			}, 
			function(data) 
			{
				//alert(data);
				var datos = data.split("**//**");
				$("#id_grupo").val(datos[0]);
				$("#nombre_grupo").val(datos[1]);
			});
}
function eliminar_grupo(id)
{
	if(confirm("Esta completamente seguro de querer borrar el grupo con las relaciones que este tenga?"))
		if(confirm("Estas completamente seguro?"))
		{
			//alert(id)
			$.post('ajax.php?c=edu&f=eliminar_grupo', 
			{
				id: id
			}, 
			function() 
			{
				$('#tabla-grup').DataTable().rows().remove();
				$('#tabla-grup').DataTable().destroy();
				inicializa_lista_grupos();
			});
		}
	
}

function cancelar_grupo()
{
	$('.bs-grupo-modal-sm').modal('hide');
}

function busca_relaciones()
{
	$('#tabla-rel').DataTable().rows().remove();
	$('#tabla-rel').DataTable().destroy();
	inicializa_lista_rel();
}

function guarda_grupo()
{
	$.post('ajax.php?c=edu&f=guarda_grupo', 
			{
				nuevo: $("#id_grupo").val(),
				nombre: $("#nombre_grupo").val(),
				idcu: $("#universidad").val()
			}, 
			function(data) 
			{
				//alert(data)
				if(parseInt(data) == 1)
				{
					$('#tabla-grup').DataTable().rows().remove();
					$('#tabla-grup').DataTable().destroy();
					inicializa_lista_grupos();
					$("#nombre_grupo").val("");
				}
				if(!parseInt(data))
					alert("Ya existe ese grupo.")

				if(parseInt(data) == 2)
					alert("Ocurrio un error y no se guardo.")
				if(parseInt($("#id_grupo").val()))
					$('.bs-grupo-modal-sm').modal('hide');
				
			});
}

function guarda_rel()
{
	var errores = 0;
	if($("#id_profe").val() == '' || $("#id_profe").val() == ' ' || $("#mensajes_profe").children('i').attr('sigue') == 'no')
		errores++;

	if($("#id_alumno").val() == '' || $("#id_alumno").val() == ' ' || $("#mensajes_alumno").children('i').attr('sigue') == 'no')
		errores++;
	if(!errores)
	{
		//alert(id)
			$.post('ajax.php?c=edu&f=guarda_rel', 
			{
				id_rel: $("#id_rel").val(),
				profe:$("#id_profe").val(),
				alumno:$("#id_alumno").val(),
				grupo:$("#grupos_rel").val(),
				univ:$("#universidad").val()
			}, 
			function(data) 
			{
				//alert(data)
				if(parseInt(data))
				{
					$('#tabla-rel').DataTable().rows().remove();
					$('#tabla-rel').DataTable().destroy();
					inicializa_lista_rel();
				}
			});
	}
	else
		alert("Hay campos incorrectos.")
}

function datos_user(t)
{
	var mensaje,user;
	if(t)
	{
		user = $("#id_profe").val()
		mensaje = "#mensajes_profe";
	}
	else
	{
		user = $("#id_alumno").val()
		mensaje = "#mensajes_alumno";
	}

	$.post('ajax.php?c=edu&f=datos_user', 
			{
				instancia: user
			}, 
			function(data) 
			{
				$(mensaje).html(data);
			});
}

function enviar_email(){
	var email = $("#modal-label-correo").attr('email');
	var asunto = $("#asunto_correo").val();
	var mensaje = $("#mensaje_correo").val();

	if (asunto == null || asunto == undefined || asunto.length == 0) {
		alert("El campo Asunto esta vacío.");
	} else if(mensaje == null || mensaje == undefined || mensaje.length == 0){
		alert("El campo Mensaje esta vacío.");
	} else {
		var r = confirm("¿Esta seguro de enviar el correo?");
		if (r == true) {
			$.post('ajax.php?c=edu&f=enviar_email',
			{
				email: email,
				asunto: asunto,
				mensaje: mensaje
			}, function(data){
				if (data == 1) {
					alert("El correo se envio satisfactoriamente");
					$("#asunto_correo").val("");
					$("#mensaje_correo").val("");
					$('#enviar_mail_modal').modal('hide');
				} else {
					alert("Error: "+data);	
				}
			});
		}
	}
}

function abrir_modal_mail(id){
	var email = $("#link-"+id).text();
	$('#modal-label-correo').attr('email',email);
	$('#enviar_mail_modal').modal({show:true});
}

function eliminarSeleccionados() {
	var rows = table.rows({ 'search': 'applied' }).nodes();
	var arr_inputs = $('input:checked', rows);
	//Validamos que este seleccionada al menos un registro
	if (arr_inputs.length > 0) {
		if(confirm("Esta completamente seguro de querer borrar la relacion?")){
			if(confirm("Estas completamente seguro?")){
				var ids = new Array();
				//Obtenemos las instancias asociadas
				$.each(arr_inputs, function(index, input){
					ids.push($(input).attr('data-id'));
				});
				//console.log(ids);
				eliminar_rel(ids);
			}
		}
	} else {
		alert("Debe seleccionar por lo menos 1 un registro.");
	}
}

function eliminar_rel(ids){
	$.post('ajax.php?c=edu&f=eliminar_relacion', 
	{
		ids: ids
	}, 
	function(data) {
		//console.log(data);
		$('#tabla-rel').DataTable().rows().remove();
		$('#tabla-rel').DataTable().destroy();
		inicializa_lista_rel();
	});
}