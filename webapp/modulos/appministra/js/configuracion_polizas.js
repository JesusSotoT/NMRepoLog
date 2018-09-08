$(function()
 {
	if(parseInt($("#conectar_hid").val()))
	{
		$("#conectar").attr('checked',true).attr("disabled",true)
	}
	else
	{
		$("#conectar").attr("disabled",false)
	}
	ver();

	if(parseInt($("#conectar_bco_hid").val()))
	{
		$("#conectar_bancos").attr('checked',true)
	}
	else
	{
		$("#conectar_bancos").attr("disabled",false)
	}

	if(parseInt($("#pestania").val()))
	{
		$('#myTabs a').eq($("#pestania").val()).click();
	}
	tipoGastos();
	procesos();
	//getInfoPolizaComprasLista();
	$.fn.modal.Constructor.prototype.enforceFocus = function () {};
 });

function procesos()
{
	$("#cuentas_lista,#vinculacion,#impuestos,.gastos").select2({width : '230'})
	for(i=1;i<=9;i++)
	{
		if(i != 2)
		{
			getInfoPoliza(i);
			aut_man(i);
			getCuentasAsoc(i);
		}

		if(i == 1)
		{
			getInfoPoliza(10);
			aut_man(10);
			getCuentasAsoc(10);
		}

		if(i == 4)
		{
			getInfoPoliza(11);
			aut_man(11);
			getCuentasAsoc(11);
		}

		if(i == 3)
		{
			getInfoPoliza(12);
			aut_man(12);
			getCuentasAsoc(12);
		}
	}
	//Carga la lista de polizas de compras, el numero representa el id del cual comenzara a buscar igual a ese o mayor.
	getPolizasComprasLista(18);
}

function ver()
{
	var conectar = $("#conectar").prop('checked') ? 1 : 0;
	if(parseInt(conectar))
	{
		$(".ver").show();
	}
	else
	{
		$(".ver").hide();
	}
}

function guardar_gral()
{
	$.post('ajax.php?c=configuracion&f=guardar_gral_pol', 
		{
			conectar 	: $("#conectar").prop('checked') ? 1 : 0,
			conectar_bco 	: $("#conectar_bancos").prop('checked') ? 1 : 0,
			autorizacion: $("#autorizacion").prop('checked') ? 1 : 0
		}, 
		function() 
		{
			window.location = 'index.php?c=configuracion&f=polizas&p=0';
		});
}

function aut_man(n)
{
	if($("#aut_man_"+n+"_1").prop('checked'))
		$("#mensaje_"+n).html("Generacion Automatica, creará una poliza por cada documento.");
	if($("#aut_man_"+n+"_0").prop('checked'))
		$("#mensaje_"+n).html("Generacion Manual, podrás crear la polizas seleccionando manualmente los documentos desde el menú POLIZAS MANUALES.");
	if($("#aut_man_"+n+"_2").prop('checked'))
		$("#mensaje_"+n).html("Generacion Automatica, creará una poliza por cada venta.");
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

	/*$.post('ajax.php?c=configuracion&f=getImpuestos', 
		function(data) 
		{
			$("#impuestos").html(data);
			$("#impuestos").val($("#impuestos option:first").val()).trigger("change")
		});*/
}

function getPolizasComprasLista(nu)
{
	$.post('ajax.php?c=configuracion&f=getPolizasComprasLista', 
		{
			n : nu
		},
		function(data) 
		{
			var datos = jQuery.parseJSON(data);
                $('#polizas_compras').DataTable( {
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
                        { data: 'nombre_poliza' },
                        { data: 'gasto' },
                        { data: 'tipo_poliza' },
                        { data: 'automatica' },
                        { data: 'por_mov' },
                        { data: 'dias' },
                        { data: 'modificar' },
                        { data: 'eliminar' }
                    ]
                });
		});
}
function getCuentasAsoc(n)
{
	$.post('ajax.php?c=configuracion&f=getCuentasAsoc', 
		{
			tipo : n
		},
		function(data) 
		{
			if(parseInt(n) > 17)
				n = 2;
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

function abrir_cuenta(n,p)
{
	if(parseInt(p) == 2)
		p = $("#id_poliza").val();
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
			impuesto : $("#impuestos").val()
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

function imp()
{
	if(parseInt($("#vinculacion").val()) == 3)
		$("#imps").show();
	else
		$("#imps").hide();
}

function eliminar(c,p)
{
	var preg = confirm("Esta seguro que desea borrar la cuenta?");
	if(preg)
	{
		$.post('ajax.php?c=configuracion&f=eliminar_cuenta', 
		{
			id 		: c
		},
		function(data) 
		{
			if(data)
			{
				if(parseInt(p) > 17)
				{
					$('#cuentas_2').DataTable().destroy();
					getCuentasAsoc(p)
				}
				else
				{
					$('#cuentas_'+p).DataTable().clear().draw();
					$('#cuentas_'+p).DataTable().destroy();
					getCuentasAsoc(p)
				}
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
			id 		: c
		},
		function(data) 
		{
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

function guardar_poliza(n)
{
	var tipo = n;
	var aut = $("#aut_man_"+tipo+"_1").prop('checked') ? 1 : 0;
	
	if(!aut && (parseInt(n) == 10 || parseInt(n) == 1))
	{
		aut = $("#aut_man_"+tipo+"_2").prop('checked') ? 1 : 0;
		if(aut)
		{
			aut = 2;
		}
	}

	if(parseInt(n) > 17)
		tipo = 2;

	$.post('ajax.php?c=configuracion&f=guardar_poliza', 
		{
			tipo 	 : n,
			aut 	 : aut,
			man    	 : $("#aut_man_"+tipo+"_0").prop('checked') ? 1 : 0,
			por_mov  : $("#por_mov_"+tipo).val(),
			dias  	 : $("#dias_"+tipo).val(),
			tipo_pol : $("#tipo_poliza_"+tipo).val(),
			gasto 	 : $("#gasto_"+tipo).val(),
			concepto : $("#concepto_"+tipo).val()
		},
		function(data) 
		{
			if(data)
			{
				if(parseInt(n) == 10)
					tipo = 1;
				if(parseInt(n) == 11)
					tipo = 4;
				if(parseInt(n) == 12)
					tipo = 3;
				window.location = 'index.php?c=configuracion&f=polizas&p='+tipo;
			}
			else
				alert("Sucedio un error intente de nuevo.");
		});
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

function getInfoPoliza(n)
{
	var na = n;
	if(parseInt(n) > 17)
		n = 2;

	$.post('ajax.php?c=configuracion&f=getInfoPoliza', 
		{
			tipo 	 : na
		},
		function(data) 
		{
			//alert(data)
			var datos = data.split("**/**");
			if(parseInt(datos[4]) == 1)
				$("#aut_man_"+n+"_1").click().trigger('click');
			if(parseInt(datos[4]) == 2)
				$("#aut_man_"+n+"_2").click().trigger('click');
			if(!parseInt(datos[4]))
				$("#aut_man_"+n+"_0").click().trigger('click');

			$("#guardar_poliza_2").attr('onclick','guardar_poliza('+na+')')
			$("#por_mov_"+n).val(datos[5]);
			$("#dias_"+n).val(datos[6])
			$("#tipo_poliza_"+n).val(datos[1]);
			$("#gasto_"+n).val(datos[2]).trigger('change');
			$("#concepto_"+n).val(datos[3]);
		});
}

function abrir_polizas_compras(n)
{
	$('#cuentas_2').DataTable().destroy();
	if(parseInt(n))
	{
		$("#id_poliza").val(n)
		$("#am_c").text("Modificar")
		//alert(n)
		getInfoPoliza(n);
		aut_man(n);
		getCuentasAsoc(n);
	}
	else
	{
		$.post('ajax.php?c=configuracion&f=nuevaPoliza', 
		function(data) 
		{
			if(parseInt(data))
			{
				$("#id_poliza").val(data)
				$("#am_c").text("Crear")
				getInfoPoliza(data);
				aut_man(data);
				getCuentasAsoc(data);

			}
		});
	}

	$('.bs-compras-modal-md').modal('show');
}
function cerrar_poliza()
{
	$('.bs-compras-modal-md').modal('hide');	
}

function eliminar_pc(n)
{
	if(confirm("Esta seguro que quiere eliminar esta poliza?"))
	{
		$.post('ajax.php?c=configuracion&f=eliminar_poliza', 
		{
			id 	 : n
		},
		function(data) 
		{
			//alert(data)
			if(data)
				window.location = 'index.php?c=configuracion&f=polizas&p=2';
			else
				alert("Sucedio un error intente de nuevo.");
		});
	}
}