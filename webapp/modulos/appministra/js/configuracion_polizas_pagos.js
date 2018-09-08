$(function()
 {
	if(parseInt($("#pestania").val()))
	{
		$('#myTabs a').eq(($("#pestania").val())-1).click();
	}
	tipoGastos();
	procesos();
	$.fn.modal.Constructor.prototype.enforceFocus = function () {};
 });

function procesos()
{
	$("#cuentas_lista,#vinculacion,#impuestos,.gastos").select2({width : '230'})
	for(i=1;i<=8;i++)
	{
			getInfoPoliza(i);
			aut_man(i);
			getCuentasAsoc(i);
	}
	//Carga la lista de polizas de compras, el numero representa el id del cual comenzara a buscar igual a ese o mayor.

}

function getInfoPoliza(n)
{
	$.post('ajax.php?c=configuracion&f=getInfoPolizaPagos', 
		{
			tipo 	 : n
		},
		function(data) 
		{
			//alert(data)
			var datos = data.split("**/**");
			if(parseInt(datos[4]) == 1)
				$("#aut_man_"+n+"_1").click().trigger('click');
			if(!parseInt(datos[4]))
				$("#aut_man_"+n+"_0").click().trigger('click');

			$("#guardar_poliza_"+n).attr('onclick','guardar_poliza('+n+')')
			$("#por_mov_"+n).val(datos[5]);
			$("#dias_"+n).val(datos[6])
			$("#tipo_poliza_"+n).val(datos[1]);
			$("#gasto_"+n).val(datos[2]).trigger('change');
			$("#concepto_"+n).val(datos[3]);
		});
}

function aut_man(n)
{
	if($("#aut_man_"+n+"_1").prop('checked'))
		$("#mensaje_"+n).html("Generar poliza, Activará la opcion de generar polizas por tipo de pago.");
	if($("#aut_man_"+n+"_0").prop('checked'))
		$("#mensaje_"+n).html("No Generar poliza, Desactivará la opcion de generar polizas por tipo de pago.");
}

function tipoGastos()
{
	$.post('ajax.php?c=configuracion&f=tipoGastos', 
		function(data) 
		{
			//alert(data)
			$(".gastos").html(data);
			$(".gastos").val(0).trigger("change");
		});
}

function getCuentasAsoc(n)
{
	$.post('ajax.php?c=configuracion&f=getCuentasAsoc', 
		{
			tipo : n,
			pagos : 1
		},
		function(data) 
		{
			var datos = jQuery.parseJSON(data);
                $('#cuentas_'+n).DataTable( {
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
			getCuentas(n);
			$("#cuentas_lista").val($("#cuentas_lista_ option:first").val()).trigger("change");
		});
}
function getCuentas(n)
{
	$.post('ajax.php?c=configuracion&f=getCuentas', 
		function(data) 
		{
			$("#cuentas_lista").html(data);
			$("#cuentas_lista").val($("#cuentas_lista option:first").val()).trigger("change")
		});

	$.post('ajax.php?c=configuracion&f=getDatosVinc', 
		function(data) 
		{
			$("#vinculacion").html(data);
			$("#vinculacion").val($("#vinculacion option:first").val()).trigger("change")
		});

	$.post('ajax.php?c=configuracion&f=getImpuestos', 
		function(data) 
		{
			$("#impuestos").html(data);
			$("#impuestos").val($("#impuestos option:first").val()).trigger("change")
		});
}

function abrir_cuenta(n,p)
{
	$("#tipo_hid").val(p)
	$('.bs-cuentas-modal-md').modal('show');

	if(parseInt(n))
	{

		$("#cuenta_hid").val(0)
		$("#am").text("Agregar Cuenta")
		$("#cuentas_lista").val($("#cuentas_lista option:first").val()).trigger("change")
		$("#vinculacion").val($("#vinculacion option:first").val()).trigger("change")
		$("#impuestos").val($("#impuestos option:first").val()).trigger("change")
		$("#cargo").click()
	}
	else
	{
		$("#am").text("Modificar Cuenta")
	}
}

function cerrar_cuenta(n)
{
	$('.bs-cuentas-modal-md').modal('hide');
}

function agregar_cuenta()
{
	$.post('ajax.php?c=configuracion&f=agregar_cuenta', 
		{
			tipo 	 : $("#tipo_hid").val(),
			existe 	 : $("#cuenta_hid").val(),
			cuenta 	 : $("#cuentas_lista").val(),
			abono 	 : $("#abono").prop('checked') ? 1 : 0,
			cargo    : $("#cargo").prop('checked') ? 1 : 0,
			vincular : $("#vinculacion").val(),
			impuesto : $("#impuestos").val(),
			pagos    : 1
		},
		function(data) 
		{
			if(data)
			{
				var tip = $("#tipo_hid").val()
				if(parseInt($("#tipo_hid").val()) > 17)
					tip = 2;
				$('#cuentas_'+tip).DataTable().destroy();
				getCuentasAsoc($("#tipo_hid").val())
				//window.location = 'index.php?c=configuracion&f=polizas&p='+$("#tipo_hid").val();
				if(parseInt($("#cuenta_hid").val()))
					$('.bs-cuentas-modal-md').modal('hide');
			}
			else
				alert("Sucedio un error intente de nuevo.");
		});
}

function guardar_poliza(n)
{
	var tipo = n;
	var aut = $("#aut_man_"+tipo+"_1").prop('checked') ? 1 : 0;


	$.post('ajax.php?c=configuracion&f=guardar_poliza', 
		{
			tipo 	 : n,
			aut 	 : aut,
			man    	 : $("#aut_man_"+tipo+"_0").prop('checked') ? 1 : 0,
			por_mov  : $("#por_mov_"+tipo).val(),
			dias  	 : $("#dias_"+tipo).val(),
			tipo_pol : $("#tipo_poliza_"+tipo).val(),
			gasto 	 : $("#gasto_"+tipo).val(),
			concepto : $("#concepto_"+tipo).val(),
			pagos    : 1
		},
		function(data) 
		{
			if(data)
			{
				if(tipo == 4)
					tipo = 1;
				if(tipo == 5)
					tipo = 2;
				if(tipo == 6)
					tipo = 3;
				if(tipo == 7 || tipo == 8)
					tipo = 4;
				window.location = 'index.php?c=configuracion&f=polizas_pagos&p='+tipo;
			}
			else
				alert("Sucedio un error intente de nuevo.");
		});
}

function eliminar(c,p)
{
	var preg = confirm("Esta seguro que desea borrar la cuenta?");
	if(preg)
	{
		$.post('ajax.php?c=configuracion&f=eliminar_cuenta', 
		{
			id 		: c,
			pagos   : 1
		},
		function(data) 
		{
			if(data)
			{
				$('#cuentas_'+p).DataTable().clear().draw();
				$('#cuentas_'+p).DataTable().destroy();
				getCuentasAsoc(p)
			}
			else
				alert("Sucedio un error intente de nuevo.");
		});
	}
}

function modificar(c,p)
{
	$.post('ajax.php?c=configuracion&f=datos_cuenta', 
		{
			id 		: c,
			pagos    : 1
		},
		function(data) 
		{
			//alert(data)
			if(data)
			{
				var datos = data.split("**/**")
				$("#cuentas_lista").val(datos[0]).trigger('change');
				if(datos[1] == 1)
					$("#abono").click();
				if(datos[1] == 2)
					$("#cargo").click();
				$("#vinculacion").val(datos[2]).trigger('change');
				if(datos[3])
					$("#impuestos").val(datos[3]).trigger('change');
				$("#cuenta_hid").val(c)
				abrir_cuenta(0,p);
			}
			else
				alert("Sucedio un error intente de nuevo.");
		});
}

