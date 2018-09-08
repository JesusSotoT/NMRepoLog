
$(function()
 {
	if(parseInt($("#pestania").val()))
	{
		$('#myTabs a').eq($("#pestania").val()).click();

	}
	existencia_combo()
	if(parseInt($("#costeo_hidden").val()))
	{
		$("#costeo").val($("#costeo_hidden").val());
		$("#costeo").attr("disabled",true);
		//$("#existencia").attr("disabled",true);
		//$("#costeo_existencia").attr("disabled",true);
		//$("#btn-1").removeAttr("onclick").hide();
	}
	$("#costeo_existencia").val($("#id_costeo_salida_hidden").val())

	$("#iva").val($("#iva_hidden").val())
	$("#ret_iva").val($("#ret_iva_hidden").val())
	if(parseInt($("#pestania_prod").val()))
	{
		$('#myTabs a').eq($("#pestania_prod").val()).click();
	}
	$("#blanco").hide();
	$(".numeric").numericInput({ allowFloat: true });
 });


$('#myTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

//INICIAN FUNCIONES DE MODULO CONFIGURACION GENERAL
function actual(a)
{
	$.post('ajax.php?c=configuracion&f=cambiaActual', 
		{
			idejercicio: a
		}, 
		function() 
		{
			window.location = 'index.php?c=configuracion&f=general&p=0';
		});
}

function actualPeriodo(a)
{
	$.post('ajax.php?c=configuracion&f=cambiaActualPeriodo', 
		{
			idperiodo: a
		}, 
		function() 
		{
			window.location = 'index.php?c=configuracion&f=general&p=1';
		});
}

function cerrar(e,n)
{
	$('#modal-generico-container').attr("uso","ejercicio").attr("idejer",e).attr("ejerNombre",n)
	$("#modal-generico-mensaje").text("Esta seguro que desea cerrar el Ejercicio "+n+"?");
	$('#modal-generico-container').modal('show');
}
function cerrarPeriodo(i,n)
{
	$('#modal-generico-container').attr("uso","periodo").attr("idper",i);
	$("#modal-generico-mensaje").text("Esta seguro que desea cerrar el Periodo "+n+"?");
	$('#modal-generico-container').modal('show');
}

function abiertos()
{
	var i = $("#abiertos").prop('checked') ? 1 : 0;
	$.post('ajax.php?c=configuracion&f=periodosAbiertos', 
		{
			abiertos: i
		});
}

function continuar()
{

   if($('#modal-generico-container').attr('uso') == 'ejercicio')
	{
		var e = $('#modal-generico-container').attr('idejer');
		var n = $('#modal-generico-container').attr('ejerNombre');
		$('#modal-generico-container').removeAttr('idejer');
		$('#modal-generico-container').removeAttr('ejerNombre');
		$('#modal-generico-container').removeAttr('uso');
		$.post('ajax.php?c=configuracion&f=cerrarEjercicio', 
		{
			idejercicio: e,
			ejerNombre: n
		}, 
		function(data) 
		{
			if(parseInt(data))
				window.location = 'index.php?c=configuracion&f=general&p=0';
			else
			{
				$('#modal-alert-mensaje').text("No se puede cerrar, antes debe cerrar el ejercicio anterior.");
				$('#alert-type').attr("class","modal-content panel-danger");
				$('#modal-alert-container').modal('show');
			}
		});
	}

	if($('#modal-generico-container').attr('uso') == 'periodo')
	{
		var p = $('#modal-generico-container').attr('idper');
		$('#modal-generico-container').removeAttr('idper');
		$('#modal-generico-container').removeAttr('uso');
		$.post('ajax.php?c=configuracion&f=cerrarPeriodo', 
		{
			idperiodo: p
		}, 
		function(data) 
		{
			if(parseInt(data))
				window.location = 'index.php?c=configuracion&f=general&p=1';
			else
			{
				$('#modal-alert-mensaje').text("No se puede cerrar, antes debe cerrar el periodo anterior.");
				$('#alert-type').attr("class","modal-content panel-danger");
				$('#modal-alert-container').modal('show');
			}
		});
	}


	if($('#modal-generico-container').attr('guardar_tipo') == '1')
	{
		var existencia = $("#existencia").prop('checked') ? 1 : 0;
		var mod_costo_compras = $("#mod_costo_compras").prop('checked') ? 1 : 0;
		var idexis;
		if(existencia)
			idexis = $("#costeo_existencia").val()
		else
			idexis = 0;

			$.post('ajax.php?c=configuracion&f=guardar&t=1', 
			{
				idcosteo		  : $("#costeo").val(),
				boolexis		  : existencia,
				idexistencia	  : idexis,
				mod_costo_compras : mod_costo_compras
			}, 
			function(data) 
			{
				window.location = 'index.php?c=configuracion&f=general&p=2';
			});
		
		
	}

	if($('#modal-generico-container').attr('guardar_tipo') == '2')
	{
		

			$.post('ajax.php?c=configuracion&f=guardar&t=2', 
			{
				iva: $("#iva").val(),
				ieps: $("#ieps").prop('checked') ? 1 : 0,
				ish: $("#ish").prop('checked') ? 1 : 0,
				ret_iva: $("#ret_iva").val(),
				ret_isr: $("#ret_isr").prop('checked') ? 1 : 0
				
			}, 
			function(data) 
			{
				window.location = 'index.php?c=configuracion&f=general&p=3';
			});
		
		
	}

	if($('#modal-generico-container').attr('guardar_tipo') == '3')
	{
		

			$.post('ajax.php?c=configuracion&f=guardar&t=3', 
			{
				compras : $("#not_compras").val(),
				ventas  : $("#not_ventas").val(),
				cortes  : $("#not_cortes").val()
				
			}, 
			function(data) 
			{
				window.location = 'index.php?c=configuracion&f=general&p=4';
			});
		
		
	}

	if($('#modal-generico-container').attr('guardar_tipo') == '4')
	{
		

			$.post('ajax.php?c=configuracion&f=guardar&t=4', 
			{
				dias_canc : $("#dias_canc").val(),
				dias_emit  : $("#dias_emit").val()
				
			}, 
			function(data) 
			{
				window.location = 'index.php?c=configuracion&f=general&p=5';
			});
		
		
	}

	if($('#modal-generico-container').attr('reiniciar') == '1') {
		var pass = prompt('Es necesario la contraseña del administrador para continuar con esta operacion.');
		if(pass) {
			$.post("../cont/ajax.php?c=Config&f=passAdmin",
			{
				Pass: pass
			},
			function(data) {
				var conservar = "No";
				if(data == 'OK') {
					if (confirm("Esta seguro que desea eliminar todos los Clientes, Proveedores y Empleados?") == true) {
						conservar = "Si";
					}
					$.post("ajax.php?c=configuracion&f=reiniciar", 
						{
							conservar: conservar
						},
						function() {
							alert('Se ha eliminado todo el registro del modulo Appministra.')
							window.location.replace("index.php?c=configuracion&f=general");
						});
				} else {
					alert('***[Contraseña Incorrecta]***');
				}
			});
		}
		$('#generico-type').attr("class","modal-content panel-warning");
		$('#modal-generico-container').removeAttr('reiniciar');
	}

	$('#modal-generico-container').modal('hide');
}

function existencia_combo()
{
	var i = $("#existencia").prop('checked') ? 1 : 0;
	if(i)
		$("#costeo_existencia").show()
	else
		$("#costeo_existencia").hide()
}

function guardar(n)
{
	if(n == 1)
	{
		if($("#costeo").val() != "0")
		{
			$('#modal-generico-container').attr("guardar_tipo","1");
			$("#modal-generico-mensaje").text("Esta seguro que desea guardar?");
			$('#modal-generico-container').modal('show');
		}
		else
		{
			$('#modal-alert-mensaje').text("Seleccione un metodo de costeo.");
			$('#alert-type').attr("class","modal-content panel-warning");
			$('#modal-alert-container').modal('show');
		}
	}

	if(n == 2)
	{
			$('#modal-generico-container').attr("guardar_tipo","2");
			$("#modal-generico-mensaje").text("Esta seguro que desea guardar?");
			$('#modal-generico-container').modal('show');   
	}

	if(n == 3)
	{
		if(validarEmail($("#not_compras").val()) && validarEmail($("#not_ventas").val()) && validarEmail($("#not_cortes").val()))
		{
			$('#modal-generico-container').attr("guardar_tipo","3");
			$("#modal-generico-mensaje").text("Esta seguro que desea guardar?");
			$('#modal-generico-container').modal('show');   
		}
	}

	if(n == 4)
	{
			if($("#dias_canc").val() == '')
				$("#dias_canc").val(0)
			if($("#dias_emit").val() == '')
				$("#dias_emit").val(0)
			$('#modal-generico-container').attr("guardar_tipo","4");
			$("#modal-generico-mensaje").text("Esta seguro que desea guardar?");
			$('#modal-generico-container').modal('show');   
	}
}

function quitar_vacio(a)
{
	if($(a).val() == '')
		$(a).val('0')
}

function pol_aut()
{
	var i = $("#pol_aut").prop('checked') ? 1 : 0;
	$.post('ajax.php?c=configuracion&f=pol_aut', 
		{
			pol_aut: i
		});
}

function ej_cer()
{
	var i = $("#ej_cer").prop('checked') ? 1 : 0;
	$.post('ajax.php?c=configuracion&f=ej_cerrados', 
		{
			ej_cer: i
		});
}

function reiniciar()
{
	$('#modal-generico-container').attr("reiniciar","1");
	$("#modal-generico-mensaje").text("Esta seguro que desea eliminar todos los registros y reiniciar el sistema?");
	$('#generico-type').attr("class","modal-content panel-danger");
	$('#modal-generico-container').modal('show');
}
//TERMINAN FUNCIONES DE MODULO CONFIGURACION GENERAL

//INICIAN FUNCIONES DEL MODULO CLASIFICACIONES
function inicializa_listaclas()
{
	$.post('ajax.php?c=configuracion&f=listaClas', 
			function(data) 
			{
				var datos = jQuery.parseJSON(data);
				$('#tabla-data').DataTable( {
					dom: 'Bfrtip',
					buttons: [ 
						'pageLength', 'excel',
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
						{ data: 'nombre' },
						{ data: 'clave' },
						{ data: 'npadre' },
						{ data: 'tipo' },
						{ data: 'mod' },
						{ data: 'elim' }
					]
				});
				 $('#tabla-data_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
				$("#tabla-data").before($("#boton_virtual").html());
				$("#boton_virtual").hide();
			});
}

function modificar_clas(id)
{
	$("#label-warning").hide();
	$("h4").text("Modificar Clasificador")
	$.post('ajax.php?c=configuracion&f=datos_clas', 
		{
			id: id
		}, 
		function(data)
		{
			$("#padreclas option").removeAttr('disabled')
			var datos = data.split('Ω');
			$("#idclas").val(id)
			$("#nombreclas").val(datos[0])
			$("#claveclas").val(datos[1])
			$("#padreclas").val(datos[2])
			if(!parseInt(datos[2]))
			{
				$("#padreclas option[value='"+id+"']").attr('disabled',true)
			}
			
			$("#tipoclas").val(datos[3])
			$("#status").val(datos[4])
			padreclas($("#padreclas"));

		});
}

function nuevo_clas()
{
	$("h4").text("Nuevo Clasificador")
	$("#idclas").val(0)
	$("#nombreclas").val('')
	$("#claveclas").val('')
	$("#padreclas").val(0)
	$("#tipoclas").val(0).show()
	$("#status").val(1).show()
	$("#tipoclaslabel").val('').hide()
	$("#label-warning").hide();
}

function padreclas(a)
{
	console.log($("option:selected",a).attr('tipo'))
	//alert($("option:selected",a).attr('tipo'))
	if(parseInt($("option:selected",a).attr('tipo')))
	{
		$("#tipoclas").val($("option:selected",a).attr('tipo')).hide()
		$("#tipoclaslabel").attr('valor',$("option:selected",a).attr('tipo')).text($("#tipoclas option:selected").text()).show()
	}
	else
	{
		if(!parseInt($("#idclas").val()))
			$("#tipoclas").val('0')
		$("#tipoclas").show()
		$("#tipoclaslabel").val('').hide()
	}
}

function guardar_clas()
{
	var tipoclas;
	var validar=0;
	if($("#tipoclaslabel").text() == '')
		tipoclas = $("#tipoclas").val();
	else
		tipoclas = $("#tipoclaslabel").attr('valor');

	if(parseInt(tipoclas))
		validar++;

	if($("#nombreclas").val() != '')
		validar++;

	if($("#claveclas").val() != '')
		validar++;

	//alert(validar)
	if(parseInt(validar) >= 3)
	{
		if(!parseInt($("#padreclas").val()))
		{
			if(!parseInt($("#status").val()))
			{
				$.post('ajax.php?c=configuracion&f=busca_hijos_clas', 
						{
							idclas: $("#idclas").val(),
						}, 
						function(data)
						{
							if(parseInt(data))
							{
								alert("No se puede completar la accion debido a que tiene clasificadores dependientes activos.")
							}
							else
							{
								guardar_clas2(tipoclas)
							}
						});
			}
			else
				guardar_clas2(tipoclas)
		}
		else
		{
			if(parseInt($("#status").val()))
			{
				$.post('ajax.php?c=configuracion&f=busca_padre_clas', 
					{
						id_padre_clas: $("#padreclas").val()
					}, 
					function(data)
					{
						if(parseInt(data))
						{
							alert("No se puede completar la accion debido a que tiene clasificadores padre inactivos.")
						}
						else
						{
							guardar_clas2(tipoclas)
						}
					});
			}
			else
				guardar_clas2(tipoclas)
			
		}
	}
	else
	{
		$("#label-warning").fadeIn(500);
	}
}

function guardar_clas2(tipoclas)
{
	$.post('ajax.php?c=configuracion&f=guardar_clas', 
		{
			idclas: $("#idclas").val(),
			nombreclas: $("#nombreclas").val(),
			claveclas: $("#claveclas").val(),
			padreclas: $("#padreclas").val(),
			tipoclas: tipoclas,
			status:$("#status").val()
		}, 
		function(data)
		{
			//alert(data)
			$('.bs-example-modal-sm').modal('hide');
			//nuevo_clas();
			location.reload();
		});
}

function cancelar_clas()
{
	$('.bs-example-modal-sm').modal('hide');
	nuevo_clas();
}
//TERMINAN FUNCIONES DEL MODULO CLASIFICACIONES
//INICIAN FUNCIONES DEL MODULO CLASIFICACIONES DE PRODUCTO
function inicializa_lista_clas_prod(tipo)
{
	$.post('ajax.php?c=configuracion&f=lista_clas_prod&tipo='+tipo, 
			function(data) 
			{
				var datos = jQuery.parseJSON(data);
				if(tipo == 'dep')
				{
					$('#tabla-dep').DataTable( {
						dom: 'Bfrtip',
						buttons: [ 
							'pageLength', 'excel',
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
							{ data: 'mod' }
						]
					});
					$('#tabla-dep_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
					$("#tabla-dep").before($("#boton_virtual1").html());
					$("#boton_virtual1").hide();
				}

				 if(tipo == 'fam')
					{
						$('#tabla-fam').DataTable( {
							dom: 'Bfrtip',
							buttons: [ 
								'pageLength', 'excel',
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
								{ data: 'departamento' },
								{ data: 'mod' }
							]
						});
						$('#tabla-fam_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
						$("#tabla-fam").before($("#boton_virtual2").html());
						$("#boton_virtual2").hide();
					}

					 if(tipo == 'lin')
					{
						$('#tabla-lin').DataTable( {
							dom: 'Bfrtip',
							buttons: [ 
								'pageLength', 'excel',
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
								{ data: 'familia' },
								{ data: 'mod' },
								{ data: 'status' }
							]
						});
						$('#tabla-lin_wrapper div:nth-child(2) div:nth-child(1)').css('overflow-y','auto');
						$("#tabla-lin").before($("#boton_virtual3").html());
						$("#boton_virtual3").hide();
					}
			});
}

function nuevo_clas_dep(tipo)
{
	$("#depende").html("<option value='0'>Ninguno</option>");
	$("#nombre").val('')
	if(tipo == 'dep')
	{
		$("h4").text('Nuevo Departamento')
		$("#id").val('0');
		$("#id_label").hide();
		$("#depende").val('0').hide();
		$("#depende_label").hide();
		$("#status").hide();
		$("#status_label").hide();
		$("#guardar").attr('onclick',"guardar_clas_prod('dep')");
	}

	if(tipo == 'fam')
	{
		$.post('ajax.php?c=configuracion&f=lista_departamentos',  
			function(data)
			{
				$("h4").text('Nueva Familia')
				$("#id").val('0');
				$("#id_label").hide();
				$("#depende").append(data);
				$("#depende_label").show();
				$("#depende").show().val('0');
				$("#status").hide();
				$("#status_label").hide();
				$("#guardar").attr('onclick',"guardar_clas_prod('fam')"); 
			});
	}

	if(tipo == 'lin')
	{
		$.post('ajax.php?c=configuracion&f=lista_familias',  
			function(data)
			{
				$("h4").text('Nueva Línea')
				$("#id").val('0');
				$("#id_label").hide();
				$("#depende").append(data);
				$("#depende_label").show();
				$("#depende").show().val('0');
				$("#status").show();
				$("#status_label").show();
				$("#guardar").attr('onclick',"guardar_clas_prod('lin')");
			});
	}
	$("#label-warning").hide();
}

function cancelar_clas_prod()
{
	$('.bs-example-modal-sm').modal('hide');
	nuevo_clas_dep('dep');
}

function guardar_clas_prod(tipo)
{
	if(tipo == 'dep')
	{
		if($("#nombre").val() != '')
		{
			$.post('ajax.php?c=configuracion&f=guardar_clas_prod&tipo=dep', 
			{
				id: $("#id").val(),
				nombre: $("#nombre").val()
			}, 
			function(data)
			{
				if(parseInt(data))
				{
					cancelar_clas_prod();  
					window.location = 'index.php?c=configuracion&f=clasificadoresProd&p=0';
				}
				else
				{
					alert("Ese departamento ya existe, intente con otro nombre.")
				}
				
			});
		}
		else
		{
			$("#label-warning").fadeIn(500);
		}
	}

	if(tipo == 'fam')
	{
		if($("#nombre").val() != '' && $("#depende").val() != '0')
		{
			$.post('ajax.php?c=configuracion&f=guardar_clas_prod&tipo=fam', 
			{
				id: $("#id").val(),
				nombre: $("#nombre").val(),
				depende: $("#depende").val()
			}, 
			function(data)
			{
				if(parseInt(data))
				{
					cancelar_clas_prod();  
					window.location = 'index.php?c=configuracion&f=clasificadoresProd&p=1';
				}
				else
				{
					alert("Esa familia ya existe, intente con otro nombre.")
				}
			});
		}
		else
		{
			$("#label-warning").fadeIn(500);
		}
	}

	if(tipo == 'lin')
	{
		if($("#nombre").val() != '' && $("#depende").val() != '0')
		{
			$.post('ajax.php?c=configuracion&f=guardar_clas_prod&tipo=lin', 
			{
				id: $("#id").val(),
				nombre: $("#nombre").val(),
				depende: $("#depende").val(),
				status: $("#status").val()
			}, 
			function(data)
			{
				if(parseInt(data))
				{
					cancelar_clas_prod();  
					window.location = 'index.php?c=configuracion&f=clasificadoresProd&p=2';
				}
				else
				{
					alert("Esa linea ya existe, intente con otro nombre.")
				}
			});
		}
		else
		{
			$("#label-warning").fadeIn(500);
		}
	}
}

function modificar_clas_prod(id,tipo)
{
	$("#label-warning").hide();
	$("#blanco").show();
	//alert(tipo)
	
		nuevo_clas_dep(tipo);
		
		$.post('ajax.php?c=configuracion&f=datos_clas_prod', 
		{
			id: id,
			tipo:tipo
		}, 
		function(data)
		{
			if(tipo == 'dep')
				$("h4").text("Modificar Departamento")
			if(tipo == 'fam')
				$("h4").text("Modificar Familia")
			if(tipo == 'lin')
				$("h4").text("Modificar Línea")
			var datos = data.split('Ω');
			$("#id").val(datos[0])
			$("#nombre").val(datos[1])
			if(tipo != "dep")
				$("#depende").val(datos[2])
			if(tipo == "lin")
				$("#status").val(datos[3])
			$("#guardar").attr("onclick","guardar_clas_prod('"+tipo+"')")
			$("#blanco").hide();
		});
}

function validarEmail(email)
{
	var vreturn = true;
	if(email != '')
	{
		expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

		if(email.indexOf(',') !== -1)
		{
			var arr = email.split(",");
			for(i=0;i<=arr.length-1;i++)
			{
				if (!expr.test(arr[i]))
			    {
			        alert("Error: La dirección de correo " + arr[i] + " es incorrecta.");
			        vreturn = false;
			    }
			}
		}
		else
		{
		    if (!expr.test(email))
		    {
		        alert("Error: La dirección de correo " + email + " es incorrecta.");
		        vreturn = false;
		    }
		}
	}
	
	return vreturn;
}

//TERMINAN FUNCIONES DEL MODULO CLASIFICACIONES DE PRODUCTO