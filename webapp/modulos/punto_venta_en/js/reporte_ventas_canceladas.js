	
	$(document).ready (function(){
		cargaReporte();
		//Filtros
		compruebaSimple();
		buscaProductos();
		buscaSucursales();
		buscaEmpleados();
		buscaClientes();
		
	});
	
	function buscaProductos()
	{ $('body').css('overflow','hidden');
		               $('#carga').css('display','block');//show();
		$.ajax(
		{
			url:'../../../punto_venta/funcionesBD/reporte_ventas_canceladas.php',
			type: 'POST',
			data: {funcion: "buscaProductos"},
			success: function(callback)
			{	
	     		$("#filtro_producto_div").html(callback);
	     		$('body').css('overflow','auto');
		                            $('#carga').hide();	   
			}
		});
	};
	
	function buscaSucursales()
	{ $('body').css('overflow','hidden');
		               $('#carga').css('display','block');//show();
		$.ajax(
		{
			url:'../../../punto_venta/funcionesBD/reporte_ventas_canceladas.php',
			type: 'POST',
			data: {funcion: "buscaSucursales"},
			success: function(callback)
			{	
	     		$("#filtro_sucursal_div").html(callback);
	     		$('body').css('overflow','auto');
		                            $('#carga').hide();	
			}
		});
	};
	
	function buscaEmpleados()
	{ $('body').css('overflow','hidden');
		               $('#carga').css('display','block');//show();
		$.ajax(
		{
			url:'../../../punto_venta/funcionesBD/reporte_ventas_canceladas.php',
			type: 'POST',
			data: {funcion: "buscaEmpleados"},
			success: function(callback)
			{	
	     		$("#filtro_empleado_div").html(callback);
	     		$('body').css('overflow','auto');
		                            $('#carga').hide();	
			}
		});
	};
	
	function buscaClientes()
	{ $('body').css('overflow','hidden');
		               $('#carga').css('display','block');//show();
		$.ajax(
		{
			url:'../../../punto_venta/funcionesBD/reporte_ventas_canceladas.php',
			type: 'POST',
			data: {funcion: "buscaClientes"},
			success: function(callback)
			{	
	     		$("#filtro_cliente_div").html(callback);
	     		$('body').css('overflow','auto');
		                            $('#carga').hide();	
			}
		});
	};
	
	function cargaReporte()
	{ $('body').css('overflow','hidden');
		               $('#carga').css('display','block');//show();
		$.ajax(
			{
				async: false,
				url:'../../../punto_venta/funcionesBD/reporte_ventas_canceladas.php',
				type: 'POST',
				data: {funcion: "cargaReporte"},
				success: function(callback)
				{	
		     		$("#reporte_div").html(callback);
		     		                $('body').css('overflow','auto');
		                            $('#carga').hide();	
				}
			});
	};
	
	function filtraReporte()
	{ $('body').css('overflow','hidden');
		               $('#carga').css('display','block');//show();
		//$('#btn_filtrar').prop('disabled', true);
		//$('#btn_limpiar').prop('disabled', true);
		
		var filtro_fecha_inicio = $("#filtro_fecha_inicio").val();
		var filtro_fecha_fin 	= $("#filtro_fecha_fin").val();
		var filtro_cliente 		= $("#filtro_cliente").val();
		var filtro_vendedor = $("#filtro_empleado").val();
		var filtro_sucursal = $("#filsucursal").val();
		var filtro_producto = $("#filtro_producto").val();
		
		if(filtro_fecha_inicio == "" && filtro_fecha_fin == "" && filtro_cliente == "" && filtro_vendedor == "" && filtro_sucursal == "" && filtro_producto == "")
		{
			alert("Debe introducir al menos un filtro");
			$('body').css('overflow','auto');
		                            $('#carga').hide();	
		}
		else
		{
			$.ajax(
			{
				url:'../../../punto_venta/funcionesBD/reporte_ventas_canceladas.php',
				type: 'POST',
				data: {funcion: "cargaReporte", filtro_fecha_inicio: filtro_fecha_inicio, filtro_fecha_fin: filtro_fecha_fin, filtro_cliente: filtro_cliente,
							filtro_vendedor: filtro_vendedor, filtro_sucursal: filtro_sucursal, filtro_producto: filtro_producto},
				success: function(callback)
				{	
		     		$("#reporte_div").html(callback);
		     		$('body').css('overflow','auto');
		                            $('#carga').hide();	
				}
			});
		}
	}
	
	function limpiaFiltros()
	{
		$("#filtro_fecha_inicio").val("");
		$("#filtro_fecha_fin").val("");
		$("#filtro_cliente").val("");
		$("#filtro_empleado").val("");
		$("#filtro_sucursal").val("");
		$("#filtro_producto").val("");
	}

	function agrupa()
	{
		var ordenamiento;
		var opc;
		if ($('#rad_cliente').is(':checked'))
		{
			opc = 2;
			ordenamiento = "ORDER BY v.idCliente";
		}
		else if($('#rad_vendedor').is(':checked'))
		{
			opc = 1;
			ordenamiento = "ORDER BY v.idEmpleado";
		}
		else if($('#rad_sucursal').is(':checked'))
		{
			opc = 3;
			ordenamiento = "ORDER BY v.idSucursal";
		}
		else if($('#rad_producto').is(':checked'))
		{
			opc = 4;
			ordenamiento = "ORDER BY p.idProducto";
		}
		
		$.ajax(
			{
				url:'../../../punto_venta/funcionesBD/reporte_ventas_canceladas.php',
				type: 'POST',
				data: {funcion: "cargaReporteAgrupado", opc: opc, ordenamiento: ordenamiento},
				success: function(callback)
				{	
		     		$("#reporte_div").html(callback);
				}
			});
	}

	function compruebaSimple()
	{
		$.ajax(
		{
			url:'../../../punto_venta/funcionesBD/reporte_ventas_canceladas.php',
			type: 'POST',
			data: {funcion: "compruebaSimple"},
			success: function(callback)
			{	
	     		if(callback != 1)
	     		{
	     			//$("#filtro_sucursal").css("display", "none");
	     			//$("#agrupamiento_sucursal").css("display", "none");
	     		}
			}
		});
	}
