	$(document).ready(function(){
		var compruebaPagosProdutctos = 1;
		if($("#id_corte").val() != "NULL")
		{
			cargaDatos();
		}
		else
		{
			inicializa();
		}
	});
	
	function cargaDatos()
	{
		var id = $("#id_corte").val();
		var fecha_inicio = $("#fecha_inicio").val();
		var fecha_fin = $("#fecha_fin").val();
		
		cargaPagos();
		cargaProductos();
		
		$.ajax(
			{
				async: false,
				url:'../../../punto_venta/funcionesBD/corte_caja.php',
				type: 'POST',
				data: {funcion: "cargaSaldos", id:id, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
				success: function(callback)
				{
					var arregloDatos = callback.split("$$$+++###AAABBB");
					if(arregloDatos[0] != "NULL")
					{
						$("#retiro_caja").val(arregloDatos[0]);
						$("#deposito_caja").val(arregloDatos[1]);
						$("#saldo_inicial").val(arregloDatos[2]);
						$("#saldo_disponible").val(arregloDatos[3]);
						$("#monto_ventas").val(arregloDatos[4]);
						
						$("#retiro_caja").disable();
						$("#deposito_caja").disable();
						$("#btn_guardar").empty();
					}
					else
					{
						alert("Hubo un error al cargar los datos");
					}
				}
			});
	};
	
	function inicializa()
	{
		$.ajax(
			{
				async: false,
				url:'../../../punto_venta/funcionesBD/corte_caja.php',
				type: 'POST',
				data: {funcion: "compruebaAnteriores"},
				success: function(callback)
				{	
		     		if(callback != 0)
		     		{
		     			var arregloDatos = callback.split("$$$+++###AAABBB");
		     			if(arregloDatos[0] == 1)
		     			{
		     				//Hay cortes anteriores
		     				$("#notifica_fecha_div").html("<div style='color:#0000FF' id='notifica_fecha'><b>Se cargó la fecha del último corte</b></div>");
		     				var saldo_inicial = parseFloat(arregloDatos[2])+parseFloat(arregloDatos[3]);
		     				saldo_inicial = saldo_inicial.toFixed(2);
		     				$("#saldo_inicial").val(saldo_inicial);
		     				$("#fecha_inicio").val(arregloDatos[1]);
		     				$("#notifica_fecha").fadeOut(10000);
		     			}	
		     			else if (arregloDatos[0] == 2)
		     			{
		     				//Hay ventas pero no hay cortes anteriores
		     				$("#notifica_fecha_div").html("<div style='color:#0000FF' id='notifica_fecha'><b>Se cargó la fecha de la primer venta</b></div>");
		     				$("#notifica_fecha").fadeOut(10000);
		     				$("#saldo_inicial").val(arregloDatos[2]);
		     			}
		     			$("#fecha_inicio").val(arregloDatos[1]);
		     			cargaProductos();
		     			cargaPagos();
		     			
		     			if(compruebaPagosProductos == 1)
		     			{
							$("#monto_ventas").val($("#monto_ventas_pg").val());
							var disponible = parseFloat($("#monto_ventas").val()) + parseFloat($("#saldo_inicial").val());
							disponible = disponible.toFixed(2);
							$("#saldo_disponible").val(disponible);
						}
						else
						{
							$("#send").disable();
							$("#saldos_div").empty();
						}
		     		}
		     		else
		     		{
		     			//No hay ventas ni cortes
		     			$("#formulario").empty();
		     			$("#formulario").html(   "<center><br><br><br><div style='width: 90%;'>"
												+"<div style='width: 80%; text-align: right'><input class='throwback' type='button' value='Regresar al listado'></div>"
												+"<br><br><br><br><div style='color:#FF0000'><b>"
		     									+"No se encontraron ventas en el sistema. No se pueden hacer cortes de caja."
		     									+"</b></div></center>");
		     		}
				}
			});
	};
	
	function cargaPagos()
	{
		var fecha_inicio = $("#fecha_inicio").val();
		var fecha_fin = $("#fecha_fin").val();
		var id = $("#id_corte").val();
		
		$.ajax(
			{
				async: false,
				url:'../../../punto_venta/funcionesBD/corte_caja.php',
				type: 'POST',
				data: {funcion: "cargaPagos", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, id: id},
				success: function(callback)
				{	
					var arregloDatos = callback.split("$$$+++###AAABBB");
					$("#pagos_div").html(arregloDatos[1]);
					if(arregloDatos[0] == 1)
					{
						if(arregloDatos.length == 3)
						{
							$("#aviso_canceladas").html("<br><center><div style='color: #FF0000;'>Este corte contiene ventas canceladas, no obstante, los totales y subtotales se muestran como estaban al momento de realizar el corte.</div></center><br>")
						}
						compruebaPagosProductos = 1;
					}
					else
					{
						compruebaPagosProductos = 0;
					}
				}
			});
	};
	
	function cargaProductos()
	{
		var fecha_inicio = $("#fecha_inicio").val();
		var fecha_fin = $("#fecha_fin").val();
		var id = $("#id_corte").val();
		
		$.ajax(
			{
				async: false,
				url:'../../../punto_venta/funcionesBD/corte_caja.php',
				type: 'POST',
				data: {funcion: "cargaProductos", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, id: id},
				success: function(callback)
				{	
					var arregloDatos = callback.split("$$$+++###AAABBB");
					$("#productos_div").html(arregloDatos[1]);
					if(arregloDatos[0] == 1)
					{
						compruebaPagosProductos = 1;
					}
					else
					{
						compruebaPagosProductos = 0;
					}
				}
			});
	};
	
	function guardarCorte()
	{
		var fecha_inicio = $("#fecha_inicio").val();
		var fecha_fin = $("#fecha_fin").val();
		var saldo_inicial = $("#saldo_inicial").val();
		var monto_ventas = $("#monto_ventas").val();
		var saldo_disponible = $("#saldo_disponible").val();
		var retiro_caja = $("#retiro_caja").val();
		var deposito_caja = $("#deposito_caja").val();

		var alerta = "";
		
		if(parseFloat(retiro_caja) > parseFloat(saldo_disponible))
		{
			alerta += "- No puede retirar más dinero del disponible en caja. \n";
		}
		if(alerta != "")
		{
			alert(alerta);
		}
		else
		{
			var r=confirm("Se generará el corte de caja. ¿Desea continuar?");
			if (r==true)
		  	{
		  		if (saldo_inicial == "")
		  			saldo_inicial = 0;
				if (monto_ventas == "")
					monto_ventas = 0;
				if (saldo_disponible == "")
					saldo_disponible = 0;
				if (retiro_caja == "")
					retiro_caja = 0;
 				if (deposito_caja == "")
 					deposito_caja = 0;

 				//Variable que determina si se realizo el callback bien
 				var doing = 0;
 					
		 		$.ajax(
				{
					async: false,
					url:'../../../punto_venta/funcionesBD/corte_caja.php',
					type: 'POST',
					data: {funcion: "guardaCorte", fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, saldo_inicial: saldo_inicial,
							 monto_ventas: monto_ventas, saldo_disponible: saldo_disponible, retiro_caja: retiro_caja, deposito_caja: deposito_caja},
					success: function(callback)
					{	
						if (callback == 1)
			     		{
			     			$("#send").disable();
			     			$("#alerta_guardar").html("<div style='color: #01a05f; text-align: right;'><b>Se está generando su corte. Por favor sea paciente. <img src='../../img/preloader.gif'></b></div>")
			     			doing = 1;

			     			//window.location="../caja/listado_cortes.php";
			     		}
			     		else
			     		{
			     			$("#send").enable();
			     			alert("Hubo un error al generar el corte.");
			     		}
					}
				});
				if(doing)
				{
					//////////////////////////////////////////////////
			    	//Al guardar el corte tomamos la consulta de la venta y la llevamos al sistema contable
					$.post("../../../cont/ajax.php?c=CaptPolizas&f=InsertPolMovPDV",
 					{
    					Fecha:$('#fecha_fin').val()
  				 	},
  				 	function(data)
  		 			{
  		 				$("#alerta_guardar").empty();
  		 				alert("El corte de caja se generó con éxito");
  		 				//alert(data)
  		 				window.location="../caja/listado_cortes.php";
  		 			});
					//Termina cambios para el sistema contable
					//////////////////////////////////////////////////
				}
			}
		}
	};