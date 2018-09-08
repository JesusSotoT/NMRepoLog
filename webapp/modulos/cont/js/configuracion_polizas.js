$(function()
{
	cargarPolizas();
	$.fn.modal.Constructor.prototype.enforceFocus = function () {};
	$("#cuentas_lista").select2({width : '230'})
});

function cargarPolizas()
{
	$.post('ajax.php?c=almacen&f=getPolizasLista', 
	function(data) 
	{
		//alert(data)
		var datos = jQuery.parseJSON(data);
        $('#polizas').DataTable( {
            dom: 'Bfrtip',
            language: 
            {
	            search: "Buscar:",
	            lengthMenu:"Mostrar _MENU_ elementos",
	            zeroRecords: "No hay datos.",
	            infoEmpty: "No hay datos que mostrar.",
	            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
            	paginate: 
            	{
                	first:      "Primero",
                	previous:   "Anterior",
                	next:       "Siguiente",
                	last:       "Último"
            	}
            },
            "order": [[ 0, "asc" ]],
            data:datos,
            columns: [
                { data: 'id' },
                { data: 'documento' },
                { data: 'tipo_poliza' },
                { data: 'nombre_poliza' },
                { data: 'provision' },
                { data: 'modificar' },
                { data: 'eliminar' }
            ]
        });
	});
}
function abrir_polizas(n)
{
	$("#tipo_poliza").val(1);
	$("#provision").val(1)
	$("#concepto").val('');
	$("#segmentos").val(1);
	$("#sucursales").val(1);
	$('#cuentas_2').DataTable().destroy();
	if(parseInt(n))
	{
		$("#id_poliza").val(n)
		$("#am_c").text("Modificar")
		//alert(n)
		getInfoPoliza(n);
		getCuentasAsoc(n);
	}
	else
	{
		$("#id_poliza").val(0)
		$("#am_c").text("Crear")
		$("#provision").val(1)
		getCuentasAsoc(0);
	}

	$('.bs-polizas-modal-lg').modal('show');
}

function getInfoPoliza(idpoliza)
{
	$.post('ajax.php?c=Almacen&f=getInfoPoliza', 
		{
			idpoliza : idpoliza
		},
		function(data) 
		{
			//alert(data)
			var datos = data.split("**/**");

			$("#tipo_poliza").val(datos[1]);
			$("#provision").val(datos[3])
			$("#concepto").val(datos[2]);
			$("#segmentos").val(datos[4]);
			$("#sucursales").val(datos[5]);
		});
}

function abrir_cuenta(id)
{
	$('.bs-cuentas-modal-md').modal('show');

	$("#cuenta_hid").val(0)
	$("#am").text("Agregar Cuenta")
	$("#cuentas_lista").val($("#cuentas_lista option:first").val()).trigger("change")
	$("#vinculacion").val($("#vinculacion option:first").val()).trigger("change")
	$("#impuestos").val($("#impuestos option:first").val()).trigger("change")
	$("#cargo").click()
	
	if(parseInt(id))
	{
		$("#am").text("Modificar Cuenta")
		$.post('ajax.php?c=Almacen&f=datos_cuenta', 
		{
			idmov 		: id
		},
		function(data) 
		{
			if(data)
			{
				//alert(data)
				var datos = data.split("**/**")
				$("#cuentas_lista").val(datos[0]).trigger('change');
				if(datos[1] == 1)
					$("#abono").click();
				if(datos[1] == 2)
					$("#cargo").click();
				$("#vinculacion").val(datos[2]).trigger('change');
				$("#cuenta_hid").val(id)
			}
			else
				alert("Sucedio un error intente de nuevo.");
		});
	}
}

function cerrar_cuenta(n)
{
	$('.bs-cuentas-modal-md').modal('hide');
}

function imp()
{
	if(parseInt($("#vinculacion").val()) == 3)
		$("#imp").show();
	else
		$("#imps").hide();
}

function getCuentasAsoc(n)
{
	$('#cuentas').DataTable().destroy();
	$.post('ajax.php?c=Almacen&f=getCuentasAsoc', 
		{
			idpoliza : n
		},
		function(data) 
		{
			//alert(data)
			var datos = jQuery.parseJSON(data);
                $('#cuentas').DataTable( {
                    dom: 'Bfrtip',
                    language: {
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
                     "order": [[ 0, "asc" ]],
                     data:datos,
                     columns: [
                        { data: 'manual_code' },
                        { data: 'description' },
                        { data: 'cargo' },
                        { data: 'abono' },
                        { data: 'vinculado' },
                        { data: 'modificar' },
                        { data: 'eliminar' }
                    ]
                });
			
			$("#cuentas_lista").val($("#cuentas_lista_ option:first").val()).trigger("change");
		});
}

function agregar_cuenta()
{
	$.post('ajax.php?c=almacen&f=agregar_cuenta', 
		{
			idpoliza : $("#id_poliza").val(),
			existe 	 : $("#cuenta_hid").val(),
			cuenta 	 : $("#cuentas_lista").val(),
			abono 	 : $("#abono").prop('checked') ? 1 : 0,
			cargo    : $("#cargo").prop('checked') ? 1 : 0,
			vincular : $("#vinculacion").val(),
			impuesto : $("#impuestos").val()
		},
		function(data) 
		{
			if(data)
			{
				$('#cuentas').DataTable().destroy();
				getCuentasAsoc($("#id_poliza").val())
				//window.location = 'index.php?c=configuracion&f=polizas&p='+$("#tipo_hid").val();
				if(parseInt($("#cuenta_hid").val()))
					$('.bs-cuentas-modal-md').modal('hide');
			}
			else
				alert("Sucedio un error intente de nuevo.");
		});
}

function guardar_poliza()
{
	$.post('ajax.php?c=Almacen&f=guardar_poliza', 
		{
			idpoliza : $("#id_poliza").val(),
			tipo_pol : $("#tipo_poliza").val(),
			concepto : $("#concepto").val(),
			provision : $("#provision").val(),
			segmento : $("#segmentos").val(),
			sucursal : $("#sucursales").val()
		},
		function(data) 
		{
			//alert(data)
			if(data)
				window.location = 'index.php?c=Almacen&f=polizas';
			else
				alert("Sucedio un error intente de nuevo.");
		});
}

function eliminar(id)
{
	if(confirm("Esta seguro que desea borrar esta poliza?"))
	{
		$.post('ajax.php?c=Almacen&f=eliminar_poliza', 
		{
			idpoliza : id
		},
		function(data) 
		{
			//alert(data)
			if(data)
				window.location = 'index.php?c=Almacen&f=polizas';
			else
				alert("Sucedio un error intente de nuevo.");
		});
	}
}

function eliminar_cuenta(id)
{
	if(confirm("Esta seguro que desea borrar este movimiento de la poliza?"))
	{
		$.post('ajax.php?c=Almacen&f=eliminar_cuenta', 
		{
			idmov : id
		},
		function(data) 
		{
			if(data)
			{
				$('#cuentas').DataTable().destroy();
				getCuentasAsoc($("#id_poliza").val())
				//window.location = 'index.php?c=configuracion&f=polizas&p='+$("#tipo_hid").val();
				if(parseInt($("#cuenta_hid").val()))
					$('.bs-cuentas-modal-md').modal('hide');
			}
			else
				alert("Sucedio un error intente de nuevo.");
		});
	}
}

