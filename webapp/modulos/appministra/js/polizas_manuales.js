$(function()
 {
 	window.ids = '';
 	$(".id_cliente,.id_proveedor").select2();
	fechasPredefinidas(0);
	fechasPredefinidas(1);
	fechasPredefinidas(2);
	$("#generar_vtas").click()
	$("#generar_cmps").click()
	$("#generar_cxp").click()
	$("#generar_cxc").click()
	$("#generar_entradas").click()
	$("#generar_salidas").click()
	$("#generar_traspasos").click()
	getTodosDemas(8)
	getTodosDemas(9)
	
 });

function getFacturasVentasCompras(t)
{
	var vt;
	var gasto;
	var clienteProv;
	var tipo_venta;
	var rango;
	if(t == 1)
	{
		vt = "ventas";
		gasto = 1;
		clienteProv = $("#id_cliente").val();
		tipo_venta = $("#tipo_venta").val();
		rango = $("#fechas_vtas").val();
	}

	if(t == 2)
	{
		vt = "compras";
		gasto = $("#tipo_gasto").val();
		clienteProv = $("#id_proveedor").val();;
		tipo_venta = 0;
		rango = $("#fechas_cmps").val();;
	}
	if(!parseInt(gasto))
		gasto = 1;
	$.post('ajax.php?c=configuracion&f=getFacturasVentasCompras', 
		{
			tipo 		: t,
			gasto 		: gasto,
			clienteProv	: clienteProv,
			tipo_venta 	: tipo_venta,
			rango		: rango
		},
		function(data) 
		{
			//alert(data)
			var datos = jQuery.parseJSON(data);
                $('#tabla_'+vt).DataTable( {
                    dom: 'Bfrtip',
                    paging:false,
                    searching:false,
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
                     "order": [[ 0, "desc" ]],
                     data:datos,
                     columns: [
                        { data: 'nombre' },
                        { data: 'folio' },
                        { data: 'fecha' },
                        { data: 'ventas' },
                        { data: 'seleccionar' }
                    ]
                });
		});
}

function carga_compras()
{
	$('#tabla_compras').DataTable().rows().remove();
	$('#tabla_compras').DataTable().destroy();
	getFacturasVentasCompras(2);
}

function getTodosDemas(n)
{
	var nombre;
	var clienteProv;
	var rango;
	switch(parseInt(n))
	{
		case 3: nombre = 'cxp';			clienteProv = $("#id_proveedor_cxp").val();break;
		case 4: nombre = 'cxc';			clienteProv = $("#id_cliente_cxc").val();break;
		case 5: nombre = 'entradas';	clienteProv = 0;break;
		case 6: nombre = 'salidas';		clienteProv = 0;break;
		case 7: nombre = 'traspasos';	clienteProv = 0;break;
		case 8: nombre = 'cancelacion';	clienteProv = 0;break;
		case 9: nombre = 'devolucion';	clienteProv = 0;break;
	}

	$.post('ajax.php?c=configuracion&f=getTodosDemas', 
	{
		tipo 		: n,
		clienteProv	:clienteProv,
		rango 		:$("#fechas_"+nombre).val()
	},
		function(data) 
		{
			//alert(data)
			var datos = jQuery.parseJSON(data);
                $('#tabla_'+nombre).DataTable( {
                    dom: 'Bfrtip',
                    paging:false,
                    searching:false,
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
                     "order": [[ 0, "desc" ]],
                     data:datos,
                     columns: [
                        { data: 'uno' },
                        { data: 'dos' },
                        { data: 'tres' },
                        { data: 'cuatro' },
                        { data: 'seleccionar' }
                    ]
                });
		});
}

function generar_poliza(num)
{
	var tipochk;

	window.ids = '';
	if(num == 1)
	{
		tipochk = 'ventas';
		/*if(parseInt($("#tipo_venta").val()) == 2)
			num = 10;*/
	}
	if(num == 2)
		tipochk = 'compras';
	if(num == 3)
		tipochk = 'cxp';
	if(num == 4)
		tipochk = 'cxc';
	if(num == 5)
		tipochk = 'entrada';
	if(num == 6)
		tipochk = 'salida';
	if(num == 7)
		tipochk = 'traspaso';
	if(num == 8)
		tipochk = 'cancelacion';
	if(num == 9)
		tipochk = 'devolucion';

	$("input[class='check_"+tipochk+"']:checked").each(function()
     {
     	split = $(this).attr('id');
     	split = split.split('-');
     	window.ids += split[1]+",";
     });
     
     ventana_poliza(1,num);
}

function ventana_poliza(abrir,num)
{
	$("#tipo").val(num)
	$("#concepto").val('')
	$("#fecha").val('')
	$("#segmento").val(1).trigger('change')
	if(parseInt(abrir))
		$('.bs-polizas-modal-md').modal('show');
	else
		$('.bs-polizas-modal-md').modal('hide');
}

function guardar()
{
	var gasto = 0;
	if(parseInt($("#tipo").val()) == 2)
		gasto = $("#tipo_gasto").val()
	console.log(window.ids);
	if($("#concepto").val() != '' && $("#fecha").val())
	{
		$.post('ajax.php?c=configuracion&f=guardar_poliza_manual', 
     	{
     		tipo: $("#tipo").val(),
     		gasto : gasto,
     		concepto: $("#concepto").val(),
     		fecha: $("#fecha").val(),
     		segmento: $("#segmento").val(),
     		ids : window.ids
     	},
		function(data) 
		{
			console.log(data)
			if(parseInt(data))
			{
				alert('Se genero la poliza correctamente.')
				//location.reload();
				if(parseInt($("#tipo").val()) == 1)
				{
					$('#tabla_ventas').DataTable().destroy();
					getFacturasVentasCompras(1)
					$("#tipo_venta").val(2)
				}

				if(parseInt($("#tipo").val()) == 2)
				{
					$('#tabla_compras').DataTable().destroy();
					getFacturasVentasCompras(2)
				}

				if(parseInt($("#tipo").val()) == 3)
				{
					$('#tabla_cxp').DataTable().destroy();
					getTodosDemas(3)
				}

				if(parseInt($("#tipo").val()) == 4)
				{
					$('#tabla_cxc').DataTable().destroy();
					getTodosDemas(4)
				}
				if(parseInt($("#tipo").val()) == 5)
				{
					$('#tabla_entradas').DataTable().destroy();
					getTodosDemas(5)
				}
				if(parseInt($("#tipo").val()) == 6)
				{
					$('#tabla_salidas').DataTable().destroy();
					getTodosDemas(6)
				}
				if(parseInt($("#tipo").val()) == 7)
				{
					$('#tabla_traspasos').DataTable().destroy();
					getTodosDemas(7)
				}
				if(parseInt($("#tipo").val()) == 8)
				{
					$('#tabla_cancelacion').DataTable().destroy();
					getTodosDemas(8)
				}
				if(parseInt($("#tipo").val()) == 9)
				{
					$('#tabla_devolucion').DataTable().destroy();
					getTodosDemas(9)
				}

				/*if(parseInt($("#tipo").val()) == 10)
				{
					$('#tabla_ventas').DataTable().destroy();
					getFacturasVentasCompras(1)
					$("#tipo_venta").val(2)
				}*/
				ventana_poliza(0,0)
			}
			else
				alert("Hubo un error y no se genero la poliza.\nPosibles Problemas:\n-No existe el ejercicio\n La poliza no se formo adecuadamente.")
			
		});
	}
	else
		alert('Faltan campos que llenar.')
}

function buscaVentas()
{
	$('#tabla_ventas').DataTable().rows().remove();
	$('#tabla_ventas').DataTable().destroy();
	getFacturasVentasCompras(1)
}

function sel_todos_check(tipo)
{
	var checked = $("#todos_check_"+tipo).prop('checked') ? 1 : 0;
	if(checked)
		$(".check_"+tipo).prop('checked',true);
	else
		$(".check_"+tipo).prop('checked',false);
	
}

function fechasPredefinidas(num)
{
	var start = moment();
    var end = moment();
    var fechas;
    var lado;
    if(!num)
    {
    	fechas = ".fechas_izq";
    	lado = "left";
    }
    if(num == 1)
    {
    	fechas = ".fechas_der";
    	lado = "right";
    }

    if(num < 2)
    {
    	$(fechas).daterangepicker({
	    	locale: {
	      				format: 'YYYY-MM-DD',
	      				separator: ' / ',
	      				"applyLabel": "OK",
				        "cancelLabel": "Cancelar",
				        "fromLabel": "Desde",
				        "toLabel": "Hasta",
				        "customRangeLabel": "Rango de fechas",
				        "weekLabel": "S",
				        "daysOfWeek": [
				            "Do",
				            "Lu",
				            "Ma",
				            "Mi",
				            "Ju",
				            "Vi",
				            "Sa"
				        ],
				        "monthNames": [
				            "Enero",
				            "Febrero",
				            "Marzo",
				            "Abril",
				            "Mayo",
				            "Junio",
				            "Julio",
				            "Agosto",
				            "Septiembre",
				            "Octubre",
				            "Noviembre",
				            "Deciembre"
				        ],
	    			},
	        startDate: start,
	        endDate: end,
	        "opens": lado,
	        ranges: {
	           'Hoy': [moment(), moment()],
	           'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	           'Esta semana': [moment().startOf('week'), moment().endOf('week')],
	           '1 Semana atrás': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
	           '2 Semanas atrás': [moment().subtract(2, 'week').startOf('week'), moment().subtract(2, 'week').endOf('week')],
	           '3 Semanas atrás': [moment().subtract(3, 'week').startOf('week'), moment().subtract(3, 'week').endOf('week')],
	           'Este mes': [moment().startOf('month'), moment().endOf('month')],
	           'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
	           'Dos meses atrás': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')]
	        }
	    });
    }
    else
    {
    	$('#fecha').daterangepicker({
	    	locale: {
	      				format: 'YYYY-MM-DD',
				        "weekLabel": "S",
				        "daysOfWeek": [
				            "Do",
				            "Lu",
				            "Ma",
				            "Mi",
				            "Ju",
				            "Vi",
				            "Sa"
				        ],
				        "monthNames": [
				            "Enero",
				            "Febrero",
				            "Marzo",
				            "Abril",
				            "Mayo",
				            "Junio",
				            "Julio",
				            "Agosto",
				            "Septiembre",
				            "Octubre",
				            "Noviembre",
				            "Deciembre"
				        ],
	    			},
			singleDatePicker: true,
			showDropdowns: true,	    			
	        startDate: start,
	        "opens": lado
	    });
    }
    

    
}

function buscaDatos(num)
{
	var tabla;
	switch(parseInt(num))
	{
		case 3: tabla='cxp';break;
		case 4: tabla='cxc';break;
		case 5: tabla='entradas';break;
		case 6: tabla='salidas';break;
		case 7: tabla='traspasos';break;
		case 8: tabla='cancelacion';break;
		case 9: tabla='devolucion';break;
	}

	$('#tabla_'+tabla).DataTable().rows().remove();
	$('#tabla_'+tabla).DataTable().destroy();
	getTodosDemas(num)
}

