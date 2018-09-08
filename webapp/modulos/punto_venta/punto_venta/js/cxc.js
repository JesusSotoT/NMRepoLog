	$(document).ready (function(){
		
		if($("#id_cxc").val() != "0")
		{
			cargaDatosCuenta();
			cargaFormasDePago();
			consultaPagos();
			if($("#carga_pre_pagos").val() == 1)
			{
				cargaPrePagos();
			}
		}
	});
	
	function cargaDatosCuenta()
	{
		var id = $("#id_cxc").val();
		$.ajax(
			{
				async:false,
				url:'../../../punto_venta/funcionesBD/cuentasC.php',
				type: 'POST',
				data: {funcion: "cargaDatosCuenta", id: id},
				success: function(callback)
				{
					var arregloDatos = callback.split("$$$^^^###///");
					//fechacargo, fechavencimiento, concepto, monto, saldoabonado, saldoactual, estatus
					$("#fecha_cargo").val(arregloDatos[0]);
					$("#fecha_vencimiento").val(arregloDatos[1]);
					$("#concepto").val(arregloDatos[2]);
					$("#monto").val(arregloDatos[3]);
					$("#saldo_abonado").val(arregloDatos[4]);
					$("#saldo_actual").val(arregloDatos[5]);
					$("#folio").val(arregloDatos[7]);
					$("#id_cliente").val(arregloDatos[8]);
					$("#cliente").val(arregloDatos[9] + " (" + arregloDatos[10] + ")");
					
					if($("#saldo_actual").val() <= 0){
						$("#formulario").disable();
						$("#estado_formulario").html("<b>La cuenta ya está saldada.</b><p>");
						$("#estado_formulario").css("color","green");
					}
				}
			});
	}
	
	function cargaFormasDePago()
	{
		$.ajax(
			{
				url:'../../../punto_venta/funcionesBD/cuentasC.php',
				type: 'POST',
				data: {funcion: "cargaFormasDePago"},
				success: function(callback)
				{
					$("#formas_pago_div").html(callback);
				}
			});
	}
	
	function consultaPagos()
	{
		var id = $("#id_cxc").val();
		$.ajax(
			{
				url:'../../../punto_venta/funcionesBD/cuentasC.php',
				type: 'POST',
				data: {funcion: "consultaPagos", id:id},
				success: function(callback)
				{
					$("#pagos_div").html(callback);
				}
			});
	}
	
	function agregaPago()
	{
		var fecha_abono = $("#fecha_abono").val();
		var abono = $("#abono").val();
		var id_forma_pago = $("#forma_pago").val();
		var forma_pago = $("#forma_pago option:selected").text();
		var id = $("#id_cxc").val();
		var saldo_actual = $("#saldo_actual").val();
		var fven = $("#fven").val();
		var referencia = 0;
		
		var alerta="";
		
		if(fecha_abono == "")
			alerta += "- No se eligió una fecha de abono. \n";
		if( abono == "" || parseFloat(abono,10) === 0 || parseFloat(abono,10) < 0.1 )
			alerta += "- No hay un abono qué agregar o el abono es insuficiente. \n";
		if(id_forma_pago == "")
			alerta += "- No seleccionaste una forma de pago. \n";
			
		if(id_forma_pago == 2)
		{
			referencia = $("#referencia").val();
			if(referencia == "")
			{
				alerta += "- El numero de cheque no es válido. \n";
			}
			
			$.ajax(
			{
				async: false,
				url:'../../../punto_venta/funcionesBD/cuentasC.php',
				type: 'POST',
				data: {funcion: "compruebaChequeOTarjetaDuplicados", referencia: referencia, id_forma_pago: id_forma_pago},
				success: function(callback)
				{
					if (callback != 1)
					{
						if(callback == 2)
							alerta += "- No puedes agregar el mismo cheque dos veces. \n";
						if(callback == 3)
							alerta += "- No puedes usar la misma tarjeta de regalo dos veces. \n";
					}
				}
			});
				
		}
		if(id_forma_pago == 3)
		{
			referencia = $("#referencia").val();
			if(referencia == "")
			{
				alerta += "- El numero de tarjeta no puede ir vacío. \n";
			}
			else
			{
				$.ajax(
				{
					async: false,
					url:'../../../punto_venta/funcionesBD/cuentasC.php',
					type: 'POST',
					data: {funcion: "compruebaChequeOTarjetaDuplicados", referencia: referencia, id_forma_pago: id_forma_pago},
					success: function(callback)
					{
						if (callback != 1)
						{
							if(callback == 2)
								alerta += "- No puedes agregar el mismo cheque dos veces. \n";
							if(callback == 3)
								alerta += "- No puedes usar la misma tarjeta de regalo dos veces. \n";
						}
					}
				});
				
				$.ajax(
				{
					async: false,
					url:'../../../punto_venta/funcionesBD/cuentasC.php',
					type: 'POST',
					data: {funcion: "compruebaTarjetaRegalo", referencia: referencia},
					success: function(callback)
					{
						if(callback == "Agotada")
						{
							alerta += "- La tarjeta ya está agotada. \n";
						}
						else
						{
							if(callback == "No existe")
							{
								alerta += "- El numero de tarjeta no existe. \n";
							}
							else
							{
								if(callback == "Usada")
								{
									alerta += "- La tarjeta ya fue usada. \n";
								}
								else
								{
									abono = callback;
								}
							}
						}
					}
				});
			}
		}
		if(id_forma_pago == 4 || id_forma_pago == 5)
		{
			referencia = $("#referencia").val();
		}
		
		if (alerta != "")
		{
			alert(alerta);
		}
		else
		{
			$.ajax(
			{
				url:'../../../punto_venta/funcionesBD/cuentasC.php',
				type: 'POST',
				data: {funcion: "agregaPago", id:id, fecha_abono: fecha_abono, abono: abono, fven: fven,
								id_forma_pago: id_forma_pago, forma_pago: forma_pago, saldo_actual: saldo_actual, referencia: referencia},
				success: function(callback)
				{
					$("#pre_pagos_div").html(callback);
				}
			});
		}
		
	}
	
	function cargaPrePagos()
	{
		var id = $("#id_cxc").val();
		var saldo_actual = $("#saldo_actual").val();
		var fven = $("#fven").val();
		
		$.ajax(
			{
				url:'../../../punto_venta/funcionesBD/cuentasC.php',
				type: 'POST',
				data: {funcion: "cargaPrePagos", id:id, fven: fven, saldo_actual: saldo_actual},
				success: function(callback)
				{
					$("#pre_pagos_div").html(callback);
				}
			});
	}
	
	function crearCuenta()
	{
		var fecha_cargo = $("#fecha_cargo").val();
		var fecha_vencimiento = $("#fecha_vencimiento").val();
		var monto = $("#monto").val();
		var saldo_abonado = $("#saldo_abonado").val();
		var saldo_actual = $("#saldo_actual").val();
		var concepto = $("#concepto").val();
		
		var alerta = "";
		
		if(fecha_cargo == "")
			alerta += "- No se eligió una fecha de cargo. \n";
		if(fecha_vencimiento == "")
			alerta += "- No se eligió una fecha de vencimiento. \n";
		if(monto == "")
			alerta += "- No se escribió un monto. \n";
		if(saldo_actual == "")
			alerta += "- No se escribió un saldo actual. \n";
		if(concepto == "")
			alerta += "- No escribiste un concepto. \n";
			
		if (alerta != "")
		{
			alert(alerta);
		}
		else
		{
			$.ajax(
			{
				url:'../../../punto_venta/funcionesBD/cuentasC.php',
				type: 'POST',
				data: {		funcion: "crearCuenta", fecha_cargo: fecha_cargo, fecha_vencimiento: fecha_vencimiento,
							monto: monto, saldo_abonado: saldo_abonado, saldo_actual: saldo_actual, concepto: concepto},
				success: function(callback)
				{
					if (callback == 1)
					{
						alert("La cuenta por pagar se generó con éxito");
						window.location="../cxp/listado_cxp.php";
					}
					else
					{
						alert("Hubo un error al generar la cuenta.");
					}
				}
			});
		}
	}
	
	function registraPagos()
	{
		var id = $("#id_cxc").val();
		var fven = $("#fven").val();
		var saldo_actual = $("#saldo_actual").val();
		var saldo_abonado = $("#saldo_abonado").val();
		
		$.ajax(
			{
				url:'../../../punto_venta/funcionesBD/cuentasC.php',
				type: 'POST',
				data: {funcion: "registraPagos", id:id, saldo_actual: saldo_actual, saldo_abonado: saldo_abonado},
				success: function(callback)
				{
					//alert(callback);
					console.log(callback);
					data = $.parseJSON( callback );
					$.post("../../../cont/ajax.php?c=CaptPolizas&f=getCXCValues",{ data : data },function(data){
						console.log(data);
						window.location = "../cxc/cuenta.php?id="+id+"&fven="+fven+"&clrssn=1";
					});
				}
			});
	}
	
	function compruebaChequeOTarjetaRegalo()
	{
		var id_forma_pago = $("#forma_pago").val();
		if (id_forma_pago == 2)
		{
			$("#abono").enable();
			$("#abono").val("");
			$("#referencia_div").html("<label>No. de cheque:</label> <input type='text' id='referencia' style='width: 50%;'>");
		}
		else if (id_forma_pago == 3)
		{
			$("#referencia_div").html("<label>No. de tarjeta:</label> <input type='text' id='referencia' style='width: 50%;'>");
			$("#abono").empty();
			$("#abono").disable();
			$("#abono").val("De acuerdo a tarjeta");
		}
		else if (id_forma_pago == 4 || id_forma_pago == 5 || id_forma_pago == 7 || id_forma_pago == 8)
		{
			$("#referencia_div").html("<label>No. de baucher:</label> <input type='text' id='referencia' style='width: 50%;'>");
			$("#abono").val("");
			$("#abono").enable();
		}
		else
		{
			$("#abono").val("");
			$("#abono").enable();
			$("#referencia_div").empty();
		}
	}
