var caja = {
	currentRequest: null,
	currentRequestP: null,
	meses: new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"),
	diasSemana: new Array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado"),
	data: new Array(),
	init: function() {
		$('#search-producto').trigger('click');
		//caja.printTime();
		$('#frameComprobante').attr({'src': ''});

		caja.autocomplete();

		$.ajax({
			url: 'ajax.php?c=caja&f=pintaRegistros',
			type: 'GET',
			dataType: 'json',
			success: function(data) {
				$('#search-producto').focus();
				if (data.estatus) {
					caja.pintaResultados(data, false);
				}

				if (data.suspendidas != '') {
					$('#divSuspendidas').css({'display': 'block'});
					$.each(data.suspendidas, function(key, value) {
						var option = $(document.createElement('option')).attr({'value': value.id}).html(value.identi).appendTo($('#s_cliente'));
					});
				}
				//caja.inicioCaja(data);
			}
		});
	},

	autocomplete: function() {
		var clientes = $('#cliente-caja').typeahead({
			hint: true,
			highlight: true,
			minLength: 1
		},
		{
			name: 'id',
			displayKey: 'nombre',
			source: function(query, process) {
				if ($('#cliente-caja').val() != '')
				{
					caja.currentRequest = $.ajax({
						url: 'ajax.php?c=caja&f=buscaClientes',
						type: 'GET',
						dataType: 'json',
						data: {term: query},
						beforeSend: function() {
							if (caja.currentRequest != null) {
								caja.currentRequest.abort();
							}
							$('#cliente-caja').addClass('loader');
						},
						success: function(data) {
							$('#cliente-caja').removeClass('loader');
							return process(data);
						},
						error: function(data)
						{
							$('#cliente-caja').removeClass('loader');
						}
					});
				} else
				{
					$('#cliente-caja').removeClass('loader');
					if (caja.currentRequest != null) {
						caja.currentRequest.abort();
					}
				}
			}
		}).on('typeahead:selected', function(event, data) {
			$('#hidencliente-caja').val(data.id);
			caja.checatimbres(data.id);
		});
		var productos = $('#search-producto').typeahead({
			hint: true,
			highlight: true,
			minLength: 1
		},
		{
			name: 'id',
			displayKey: 'label',
			source: function(query, process) {

				if ($('#search-producto').val() != '')
				{
					caja.currentRequestP = $.ajax({
						url: 'ajax.php?c=caja&f=buscaProductos',
						type: 'GET',
						dataType: 'json',
						data: {term: query},
						beforeSend: function() {
							if (caja.currentRequestP != null) {
								caja.currentRequestP.abort();
							}
							$('#search-producto').addClass('loader');
						},
						success: function(data) {

							var result = false;

							$('#search-producto').removeClass('loader');
							if (result == false)
							{
								return process(data);
							}
						},
						error: function(data)
						{
							$('#search-producto').removeClass('loader');
						}
					});
} else
{
	$('#search-producto').removeClass('loader');
	if (caja.currentRequestP != null) {
		caja.currentRequestP.abort();
	}
}
}
}).on('typeahead:selected', function(event, data) {

	$('#search-producto').val('').typeahead('clearHint');
	caja.buscaCaracteristicas(data.id);
	//caja.agregaProducto(data.id);
});
},
busquedaXcodigo: function(e)
	{
		if (window.event)
			keyCode = window.event.keyCode;
		else if (e)
			keyCode = e.which;
		var producto = $('#search-producto').val();
		if (keyCode == 13 && producto != '')
		{
			//caja.agregaProducto(producto);
			caja.buscaCaracteristicas(producto);
		}
	},
	agregaProducto : function(id,caracteristicas){
		console.log('------> objeto agregar producto');
		console.log(id);
		
		caja.mensaje('Procesando...');
		 var cantidad = $('#cantidad-producto').val();
		$.ajax({
			url: 'ajax.php?c=caja&f=agregaProducto',
			type: 'POST',
			dataType: 'json',
			data: {
				id: id,
				cantidad: cantidad,
				caracter :caracteristicas,
				cliente : $('#hidencliente-caja').val(),
				xyz : 1,
			},
			beforeSend: function() {
				if (caja.currentRequestP != null) {
					caja.currentRequestP.abort();
				}
				$('#search-producto').addClass('loader');
			},
			success: function(data){
				console.log('------> success agregar producto');
				console.log(data);
				$('#listaDePreciosClient').val(data.listaDePrecios);
			// La comanda ya fue pagada
				if(data['status']==3){
					alert("La comanda "+data['comanda']+" ya fue pagada. ID de la venta: "+data['id_venta']);
					$('#search-producto').val('').typeahead('clearHint').focus();
				}
				
			// Comanda sin productos
				if(data['status']==4){
					alert("Esta comanda no tiene productos para cobrar");
					$('#search-producto').val('').typeahead('clearHint').focus();
				}
				if(data.estatus==false){
                    //$('#codigoGS1, #nombreGS1, #precioGS1, #descGS1, #statusGS1').val('');
                    alert("El producto no existe");
                    $('#cantidad-producto').val('1');
                    $('#search-producto').val('').typeahead('clearHint').focus();
                    caja.eliminaMensaje();
                    return false;
                }
				
				caja.eliminaMensaje();
				
				//caja.eliminaMensaje();
				caja.pintaResultados(data);
				$('#cantidad-producto').val('1');
				$('#search-producto').val('').typeahead('clearHint').focus();
			},
			error: function(data){
				console.log('------> error agregar producto');
				console.log(data);
				
				$('#cantidad-producto').val('1');
				caja.eliminaMensaje();
				$('#search-producto').removeClass('loader');
			}
		});
		
	},
	pintaResultados: function(data){
		console.log(data);
		
		if(data.estatus==true){
			caja.data = data;
			caja.eliminaMensaje();
			var subtotal = 0.00;
			var impuestosVal = 0.00;
			var total = 0.00;
			$('.filas').empty();
			console.log(data.productos);
			$.each(data.productos, function(index, val) {
				 index = index.replace("+", "");
				if(index != 'cargos' && val.idProducto !='null' && index!='descGeneral' && index!='descGeneralCant'){
					if(val.caracteristicas!=''){
						idProdCar = val.idProducto+'_'+val.caracteristicas;
					}else{
						idProdCar = val.idProducto;
					}
				   
					$('#productsTable1 tr:last').after('<tr class="filas" id="filaPro_'+idProdCar+'" prodCar="filaPro_'+idProdCar+'">'+
									'<td><input type="text" id="cant_'+val.idProducto+'" value="'+val.cantidad+'" onblur="caja.recalcula(\''+idProdCar+'\',\'cantidad\');" style="width:100%" class="form-control numeros" cant="'+idProdCar+'"></td>'+
									'<td onclick="caja.descuentoParcial('+idProdCar+')">'+val.nombre+'</td>'+
									' <td><input type="text" id="precio_'+val.idProducto+'" value="'+parseFloat(val.precio).toFixed(2)+'" class="form-control span1 numeros" onblur="caja.recalcula(\''+idProdCar+'\',\'precio\');" style="width:100%" precio="'+idProdCar+'"></td>'+
									'<td>$'+parseFloat(val.importe).toFixed(2)+'</td>'+
									'<td align="left"><span class="glyphicon glyphicon-trash" onclick="caja.eliminarProducto(\''+idProdCar+'\');"></span></td>'+
									'</tr>');
								subtotal += val.importe;
				}

			}); 
			
			$('#subtotalLabel').text('$'+data['cargos']['subtotal']);
			$('#totalLabel').text('$'+data['cargos']['total']);
			  ///////descuento general
			$('#desDiven').empty();
			if (typeof data.descGeneral!== 'undefined') {
				 $('#desDiven').append('<div class="row">'+
								   '<div class="col-sm-6" style="font-size:15px;">Descuento</div>'+
								'<div class="col-sm-6" style="font-size:15px;">'+
									'<label>$'+data.descGeneral.toFixed(2)+'</label>'+
								'</div>'+
							'</div>');  
			}          
			//alert(data.subtotal);
			$('#impestosDiv').empty();
			$.each(data.cargos.impuestosPorcentajes, function(index, val) {
				 $('#impestosDiv').append('<div class="row">'+
							   '<div class="col-sm-6" style="font-size:15px;">'+index+'</div>'+
							'<div class="col-sm-6" style="font-size:15px;">'+
								'<label>$'+val.toFixed(2)+'</label>'+
							'</div>'+
						'</div>');
					impuestosVal += val; 
			});
			total = subtotal + impuestosVal;
			$('#subtotalLabel').text('$'+data['cargos']['subtotal'].toFixed(2));
			$('#totalLabel').text('$'+data['cargos']['total'].toFixed(2));
			$('#totalDeProductos').text(data.totalProductos);
			$('#totalDeProductosInput').val(data.totalProductos);
			
			$('.numeros').numeric();
		}else{
			//alert('Error - 1500');
		}

	},
	mensaje: function(mensaje) {

		$('#lblMensajeEstado').text(mensaje);
		$('#modalMensajes').modal({
						show:true,
						keyboard: false,
					});
	},    
	eliminaMensaje: function() {

		$('#modalMensajes').modal('hide');
	},
	checatimbres: function(idCliente) {
	//$('#cliente-caja').addClass('loader');
		$.ajax({
			url: 'ajax.php?c=caja&f=cargaRfcs',
			type: 'POST',
			dataType: 'json',
			data: {idCliente: idCliente},
			success: function(data)
			{   
				if (data.status)
				{
					$.each(data.rfc, function(index, value) {
						var option = $(document.createElement('option')).attr({'value': value.id}).html(value.rfc).appendTo($('#rfc'));
					});
				   
						$("#labelrfc").show();
						$("#selectrfc").show('slow');
						$('#cliente-caja').removeClass('loader');                   
					 
	 
				}
			}
		});
	},
	modalPagar: function (){
			//caja.checaPagos();
			console.log(caja.data);
			$('#lblTotalxPagar').text(caja.data["cargos"]["total"]);
			$('#btnAgregarPago').unbind('click').bind('click', function() {

				var tipostr = $('#cboMetodoPago option:selected').text();
				var tipo = $('#cboMetodoPago').val();
				var pago = ($('#txtCantidadPago').val()).replace(",",'');
				var txtReferencia = $('#txtReferencia').val();

				caja.metodoPago(tipo, tipostr, pago, txtReferencia);
			});
			$('#cboMetodoPago').unbind('change').bind('change', function() {
				caja.muestraReferenciaPago($(this).val());
			});
			$('#modalPagar').modal({
				show:true,
			});
	},
	checaPagos: function() {

	$.ajax({
		type: 'POST',
		url: 'ajax.php?c=caja&f=checarPagos',
		dataType: 'json',
		success: function(data) {
			if (data.status)
			{
				$('#abonosPagos').empty();

				var abonosPagos = $('#abonosPagos');

				$.each(data.pagos, function(index, value) {
					var registroCaja = $(document.createElement('div')).attr({'id': 'Pago' + index}).addClass('form-control registroCaja').appendTo(abonosPagos);
					var regTipo = $(document.createElement('div')).addClass('col-xs-5').html(value.tipostr).appendTo(registroCaja);
					var regCantidad = $(document.createElement('div')).attr({'id': 'cantidad' + index}).addClass('col-xs-5').html(value.cantidad).appendTo(registroCaja);
					var regAccion = $(document.createElement('div')).addClass('col-xs-2').appendTo(registroCaja);
					var accion = $(document.createElement('img')).addClass('imgDelete').attr({'src': 'img/bor.png'}).appendTo(regAccion);

					accion.bind('click', function() {
						caja.eliminarPago(index);
					});
				});

				$('#lblAbonoPago').text(data.abonado);
				$('#lblPorPagar').text(data.porPagar);
				$('#lblCambio').text(data.cambio);
			} else if (data.statusInicio == false)
			{
				$('#modalPago').dialog("close");
				//caja.inicioCaja(data);
			}
		}});

	},
	metodoPago: function(tipo, tipostr, cantidad, txtReferencia) {

	if ($('#cboMetodoPago').val() == '')
	{
		return;
	}

	if ($("#txtCantidadPago").val() == "") {
		alert("Ingresa la cantidad para agregar el pago");
		$("#txtCantidadPago").focus();
		return false;
	}

	if ($("#cboMetodoPago").val() == 2 && $("#txtReferencia").val() == "")
	{
		alert("Debes ingresar el número de cheque para registrar el pago");
		return false;
	}

	if ($("#cboMetodoPago").val() == 7 && $("#txtReferencia").val() == "")
	{
		alert("Debes ingresar la txtReferencia de la transferencia para registrar el pago");
		return false;
	}


	if ($("#cboMetodoPago").val() == 8 && $("#txtReferencia").val() == "")
	{
		alert("Debes ingresar la txtReferencia SPEI para registrar el pago");
		return false;
	}

	if ($("#cboMetodoPago").val() == 3 && $("#txtReferencia").val() == "")
	{
		alert("Debes ingresar el número de la tarjeta de regalo para registrar el pago");
		return false;
	}

	if ($("#cboMetodoPago").val() == 4 && $("#txtReferencia").val() == "")
	{
		alert("Debes ingresar el número de baucher para registrar el pago");
		return false;
	}

	if ($("#cboMetodoPago").val() == 5 && $("#txtReferencia").val() == "")
	{
		alert("Debes ingresar el número de baucher para registrar el pago");
		return false;
	}


	if ($("#cboMetodoPago").val() == 6 && $("#hidencliente-caja").val() == "")
	{
		alert("Debes seleccionar el cliente para poder registrar un pago a credito");
		return false;
	}

		//Tejeta de regalo
		if ($("#cboMetodoPago").val() == 3)
		{
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'ajax.php?c=caja&f=checatarjetaregalo',
				data: {
					numero: txtReferencia,
					monto: cantidad
				},
				success: function(response) {
					if (response.status)
					{
						caja.agregarPago(tipo, tipostr, cantidad, txtReferencia);
					} else
					{
						alert(response.msg);
					}
				}});//end ajax
		} else if ($("#cboMetodoPago").val() == 6)//pago a credito
		{
			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: 'ajax.php?c=caja&f=checalimitecredito',
				data: {
					cliente: $("#hidencliente-caja").val(),
					monto: cantidad
				},
				success: function(resp) {
					if (resp.status)
					{
						caja.agregarPago(tipo, tipostr, cantidad, txtReferencia);
					} else
					{
						alert(resp.msg);
					}

				}});//end ajax
		} else
		{
			caja.agregarPago(tipo, tipostr, cantidad, txtReferencia);
		}


	},
	agregarPago: function(tipo, tipostr, cantidad, txtReferencia)
	{
		/*var cambio = $("#pagar-cambio").html().replace("Cambio:$", "");
		 var cambio = cambio.replace("$", "");
		 if (cambio > 0)
		 {
		 alert("Con los pagos efectuados se puede completar la venta");
		 return false;
	 }*/

		/*if ($("#cboMetodoPago").val() > 3 && $("#cantidadpago").val() > $('#cantidad-recibida').val().replace('$', '').replace(',', ''))
		 {
		 alert('El pago no debe ser superior al total');
		 return false;
		 //alert($("#cboMetodoPago").val()+" / "+$("#cantidadpago").val()+" / "+$('#cantidad-recibida').val().replace('$','')); return false;

	 }*/

	 if ($('#Pago' + tipo).length)
	 {
		cantidad = parseFloat(cantidad.replace(",", ""));
			//cantidad += parseFloat($('#cantidad' + tipo).html());
		}

		$.ajax({
			type: 'POST',
			url: 'ajax.php?c=caja&f=agregaPago',
			dataType: 'json',
			data: {
				tipo: tipo,
				tipostr: tipostr,
				cantidad: cantidad,
				txtReferencia: txtReferencia
			},
			success: function(data) {

				if (data.status)
				{
					if ($('.nopagos').length)
					{
						$('.nopagos').parent().empty();
					}

					if ($('#Pago' + data.tipo).length)
					{
						$('#cantidad' + data.tipo).html(data.cantidad);
					} else
					{
						var abonosPagos = $('#divDesglosePagoTablaCuerpo');

						var registroCaja = $(document.createElement('tr')).attr({'id': 'Pago' + data.tipo}).appendTo(abonosPagos);
						var regTipo = $(document.createElement('td')).html(data.tipostr).appendTo(registroCaja);
						var regCantidad = $(document.createElement('td')).attr({'id': 'cantidad' + data.tipo}).html(data.cantidad).appendTo(registroCaja);
						var regAccion = $(document.createElement('td')).css({'text-align' : 'center'}).appendTo(registroCaja);
						var accion = $(document.createElement('span')).addClass('glyphicon glyphicon-remove').appendTo(regAccion);

						accion.bind('click', function() {
							caja.eliminarPago(data.tipo);
						});
					}

					$('#lblAbonoPago').text("$ " + data.abonado);
					$('#lblPorPagar').text("$ " + data.porPagar);
					$('#lblCambio').text("$ " + data.cambio);

					$('#txtCantidadPago').val('');
					$('#txtReferencia').val('');
				}
			}});
},
eliminarPago: function(pago) {

	$.ajax({
		type: 'POST',
		url: 'ajax.php?c=caja&f=eliminarPago',
		dataType: 'json',
		data: {
			pago: pago
		},
		success: function(data) {

			$('#Pago' + pago).hide('slow', function() {
				$(this).remove();

				if ($('#abonosPagos').html() == '')
				{
					$('#abonosPagos').html('<div class="form-control registroCaja nopagos"><div class="col-xs-12 text-center">No hay pagos</div></div>');
				}
			});

			if (data.status)
			{
				$('#lblAbonoPago').text(data.abonado);
				$('#lblPorPagar').text(data.porPagar);
				$('#lblCambio').text(data.cambio);
			} else
			{
				$('#lblAbonoPago').text('0.00');
				$('#lblPorPagar').text('0.00');
				$('#lblCambio').text('0.00');
			}
		}});

},
pagar: function() {

	var codigo = $('#codigo').val();
	var propina = $('#propina').val();

	if ($('.nopagos').length)
	{
		alert('Debes saldar la deuda.');
		$('#txtCantidadPago').focus();
		return;
	}

	if ($('#lblPorPagar').text() != '0.00' && $('#lblPorPagar').text() != '$ 0.00')
	{
		alert('No has cubierto el total de la deuda.');
		return;
	}



	if (codigo != '')
	{
		$.ajax({
			url: '../restaurantes/ajax.php?c=productocomanda&f=borrarProductoTemporal',
			type: 'POST',
			dataType: 'json',
			async: true,
			data: {'codigo': codigo},
			error: function(data) {
				alert('No se pudo borrar la comanda-p');
				return;
			}
		});
	}


	$.ajax({
		url: 'ajax.php?c=caja&f=guardarVenta',
		type: 'POST',
		dataType: 'json',
		async: true,
		data: {
			idFact: $("#rfc").val(),
			documento: $("#documento").val(),
			cliente: $("#hidencliente-caja").val(),
			suspendida: $("#s_cliente").val(),
			propina: propina,
			comentario: $('#txtareacomentariosProducto').val(),
				//pagoautomatico: 1,
				//impuestos: $totalimpuestos,
				//sucursal: $("#caja-sucursal").val(),
				//almacen: $("#caja-almacen").val(),
				//cambio: 0,
				//monto: $total,
				//cliente: $("#hidencliente-caja").val(),
				//empleado: $("#idvendedor").val()
			},
			beforeSend: function() {
				caja.mensaje("Guardando Venta");
			},
			success: function(resp) {
				console.log('----> success venta');
				console.log(resp);
				
				if (resp.status)
				{   
				 if($('#documento').val() == 2)
				 {
					$('#lblComentarioE').html('la Factura.');
				}else{
					$('#lblComentarioE').html('el Recibo de Ingresos.');
				}
				caja.observacionesFactura(resp);
			} else
			{
				caja.eliminaMensaje();
				alert(resp.msg);
			}
		},
		error: function(data) {
			console.log('----> error venta');
			console.log(data);
				
			caja.eliminaMensaje();
			alert(data.msg);
		}
	});
},
observacionesFactura: function(resp) {
	obsResp = resp;

	if ($('#documento').val() == 1)
	{
		caja.comprobante(resp, false);
		caja.mensaje("Generando Ticket");
	} else {
		caja.eliminaMensaje();
		$('#modal_Observaciones').modal({
										backdrop: 'static',
										keyboard: false, 
									});
	}
},
observacionesEnviar: function(){
	$('#modal_Observaciones').modal("hide");
	if($('#documento').val() == 2)
	{
		caja.mensaje("Generando Factura");
	}else{
		caja.mensaje("Generando Recibo de Ingresos");
	}
	caja.comprobante(obsResp, $('#txtareaObservaciones').val());
},
comprobante: function(resp, mensaje) {
 console.log(resp);
 console.log(mensaje);
 
	$.ajax({
		type: 'POST',
		url: 'ajax.php?c=caja&f=facturar',
		dataType: 'json',
		data: {
			idFact: $("#rfc").val(),
			idVenta: resp.idVenta,
			doc: $('#documento').val(),
			mensaje: mensaje,
			consumo:$('#consumo').val(),
		},
		beforeSend: function() {



		},
		success: function(resp) {
			//alert('entro al success');
			//return false;
			caja.eliminaMensaje();
			if (resp.success == '500') {
				alert(resp.mensaje);
				window.location.reload();
				return false;
			}
			if (resp.success == '-1') {
				alert('Ha ocurrido un error durante el proceso de venta y facturacion.');
				window.location.reload();
				return false;
			}
			if (resp.success == '3') {
				alert('Venta realizada con exito.');
				
				caja.modalComprobante("../../modulos/pos/ticket.php?idventa=" + resp.idVenta, true, resp.idVenta);

					//window.location.reload();
					return false;
				}
				/* NUEVA FACTURACION Y RESPUESTA DE VENTA
				================================================ */
				if (resp.success == 0 || resp.success == 5) {
					if (resp.success == 0) {
						alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
					}
					//alert('esto es una prueba');
					caja.modalComprobante("../../modulos/pos/ticket.php?idventa=" + resp.idVenta, true, resp.idVenta);
					// caja.modalComprobante("ajax.php?c=caja&f=ticket&idVenta=" + resp.idVenta, true);
					
					$.ajax({
						type: 'POST',
						url:'ajax.php?c=caja&f=pendienteFacturacion',
						data:{
							azurian:resp.azurian,
							idFact:$("#rfc").val(),
							monto:(resp.monto),
							cliente:$("#hidencliente-caja").val(),
							trackId:resp.trackId,
							idVenta:resp.idVenta,
							doc: $('#documento').val()

						},
						beforeSend: function() {
							caja.mensaje("Guardando Factura 2");
						},
						success: function(resp){  
							caja.eliminaMensaje();
						}

					});

				}

				if (resp.success == 1)
				{
					azu = JSON.parse(resp.azurian);
					uid = resp.datos.UUID;
					correo = resp.correo;

					$.ajax({
						type: 'POST',
						url: 'ajax.php?c=caja&f=guardarFacturacion',
						dataType: 'json',
						data: {
							UUID: uid,
							noCertificadoSAT: resp.datos.noCertificadoSAT,
							selloCFD: resp.datos.selloCFD,
							selloSAT: resp.datos.selloSAT,
							FechaTimbrado: resp.datos.FechaTimbrado,
							idComprobante: resp.datos.idComprobante,
							idFact: resp.datos.idFact,
							idVenta: resp.datos.idVenta,
							noCertificado: resp.datos.noCertificado,
							tipoComp: resp.datos.tipoComp,
							trackId: resp.datos.trackId,
							monto: (resp.monto),
							cliente: $("#hidencliente-caja").val(),
							idRefact: 0,
							azurian: resp.azurian,
							doc: $('#documento').val()
						},
						beforeSend: function() {
							if($('#documento').val() == 2)
							{
								//caja.mensaje("Guardando Factura");
							}else if($('#documento').val() == 3)
							{
								caja.mensaje("Guardando Recibo de Ingresos");
							}
						},
						success: function(resp) {
						   
							caja.eliminaMensaje();
							//window.open('../../modulos/facturas/'+uid+'.pdf');
							$.ajax({
								async: false,
								type: 'POST',
								url: 'ajax.php?c=caja&f=envioFactura',
								dataType: 'json',
								data: {
									uid: uid,
									correo: correo,
									azurian: azu,
									doc: $('#documento').val()
								},
								beforeSend: function() {
									caja.mensaje("Enviando Factura");
								},
								success: function(resp) {
									caja.eliminaMensaje();
									if(resp.cupon==false){
										caja.modalComprobante('../../modulos/facturas/'+uid+'.pdf', false, uid);
									}else{
										caja.modalComprobante('../../modulos/facturas/'+uid+'__'+resp.receptor+'__'+resp.cupon+'.pdf', false, uid);
									}
									//window.open('../../modulos/facturas/' + uid + '.pdf');
									//window.location.reload();
								},
								error: function() {
									caja.eliminaMensaje();
								}
							});

$("#loaderventa").hide();
$('#caja-dialog').modal('hide');
$("#boton-pagar").removeAttr("disabled");
alert('Has registrado la venta con exito');
							//window.location.reload();
						},
						error: function() {
							caja.eliminaMensaje();
						}
					});
}
				//window.location.reload();
			}
		});
},
modalComprobante: function(src, ticket, idVenta) {

		$('#idVentaTicket').val(idVenta);
		var sizeWidth = 0;
		var sizeheight = 0;

		if (ticket)
		{
			sizeWidth = 325;
			sizeheight = 450;
		} else {

			sizeWidth = $('#tb1238-u', window.parent.document).width() - 100;
			sizeheight = $('#tb1238-u', window.parent.document).height() - 50;
		}

		$('#modalPago').modal('hide');

		$('#frameComprobante').attr({'src': src});

		$('#modalComprobante').modal({backdrop: 'static'});
},
eliminarProducto: function(idProducto)
	{
		$.ajax({
			url: 'ajax.php?c=caja&f=eliminaProducto',
			type: 'POST',
			dataType: 'json',
			data: {'id': idProducto},
			beforeSend: function() {
			   /* if (caja.currentRequestP != null) {
					caja.currentRequestP.abort();
				}
				if (caja.currentRequest != null) {
					caja.currentRequest.abort();
				} */
			},
			success: function(data) {
				///Elimina por atributo con sus caracteristcas
				var x = 'tr[prodCar="filaPro_'+idProducto+'"]';
				//var x = 'filaPro_'+idProducto;
			  
				if (data)
				{   
					$(x).hide('slow', function() {
						$(x).empty();
						//$('#filaPro_' + idProducto).empty();
						if (data.count < 2){   
							caja.cancelarCaja();
						} else{   
							caja.pintaResultados(data, false);
						}
					});
				}
			}
		});
	},
	cancelarCaja: function(msg)
	{

		if (msg)
		{
			if (!confirm('¿Deseas cancelar la la venta?'))
			{
				return;
			}
		}


		$.ajax({
			url: 'ajax.php?c=caja&f=cancelarCaja',
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {
				if (caja.currentRequestP != null) {
					caja.currentRequestP.abort();
				}
				if (caja.currentRequest != null) {
					caja.currentRequest.abort();
				}
			},
			success: function(data) {

				if (data)
				{
					caja.limpiaPago();
					$('.contenidoForm').empty().html('<div class="registroCaja noProducts"><label class="col-xs-12" style="text-align:center">No hay productos en la caja.</label></div>');
					$('.impuestosCaja').empty().css({'display': 'none'});
					$('#codigo').val('');

					$('#documento').val(1).trigger('change');
					$('#search-producto').val('').typeahead('clearHint');
					//$('#cliente-caja').val('').typeahead('clearHint');
					$('#totalDeProductos').text('0');
					$('#totalDeProductosInput').val('');
				}
			}
		});
	},
	limpiaPago: function(){

		$('#cboMetodoPago').val(1);
		$('#txtCantidadPago').val('');
		$('#lblTotalxPagar').text('');
		$('#lblAbonoPago').text('0.00');
		$('#lblReferencia').text('');
		$('#txtReferencia').text('');

		$('#lblAbonoPago').text('0.00');
		$('#lblPorPagar').text('0.00');
		$('#lblCambio').text('0.00');
		
			$('#subtotalLabel').text('$0.00');
			$('#totalLabel').text('$0.00');
			//alert(data.subtotal);
			$('#impestosDiv').empty();
			$('.filas').empty();
	},
	suspender: function() {

	   /* if ($("#hidencliente-caja").val() == "") {
			alert("Nesesita seleccionar un cliente para suspender la venta!");
			return false;
		} */


		$.ajax({
			type: 'POST',
			url: 'ajax.php?c=caja&f=suspenderVenta',
			dataType: 'json',
			data: {
				idFact: $("#rfc").val(),
				documento: $("#documento").val(),
				cliente: $("#hidencliente-caja").val(),
				nombre: $("#cliente-caja").val(),
				suspendida: $('#s_cliente').val()
			},
			success: function(resp) {

				if (resp.status) {
					window.location.reload();
				} else
				{
					alert(resp.msg);
				}

			}});

	},
	cargarSuspendida: function() {
	$.ajax({
		type: 'POST',
		url: 'ajax.php?c=caja&f=cargarSuspendida',
		dataType: 'json',
		data: {
			id_susp: $('#s_cliente').val()
		},
		success: function(data) {

			if (data.estatus)
			{ 
				caja.pintaResultados(data, false);
					//$('#hidencliente-caja').val(data.cliente);
				}
				else
				{   
					
					alert(data.msg);
				}
			}});
	},
	recalcula: function(idProducto, field){
		//var cantidad = $('#cant_'+idProducto).val();
		//var precio = $('#precio_'+idProducto).val();
		var x = 'input[precio="'+idProducto+'"]';
		var y = 'input[cant="'+idProducto+'"]';
		var precio = $(x).val();
		var cantidad = $(y).val();
			$.ajax({
				url: 'ajax.php?c=caja&f=recalcula',
				type: 'POST',
				dataType: 'json',
				data: {cantidad: cantidad,
						precio : precio,
						idProducto : idProducto,
						xyz : field,
					},
			})
			.done(function(data) {
				console.log(data);
				if(data.estatus==true){
					 caja.data = data;
					 caja.pintaResultados(data, false);
				}
				
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		
	},
	eliminarSuspendida: function() {

		//caja.eliminaMensaje();
		$.ajax({
			type: 'POST',
			url: 'ajax.php?c=caja&f=eliminarSuspendida',
			dataType: 'json',
			data: {
				suspendida: $('#s_cliente').val()
			},
			success: function(data) {
				if (data.status)
				{
					alert('Se elimino correctamente');
					window.location.reload();
				}
				else
				{
					alert(data.msg);
				}
			}});
	},
	agregaProTouch: function(id){
		var codigo = $(id).attr('codigoProTouch');
		//caja.agregaProducto(codigo);
		caja.buscaCaracteristicas(codigo);

	},
	facturarButton: function(){
		$('#gridHidden').hide();
		$('#rfcMoldal').val('');
		$('#modalFacturacion').modal({
				show:true,
			});
	},
	clienteAddButton: function(){
		$('#modalCliente').modal({
				show:true,
			});
	},
	municipiosF: function(){
		var estado = $('#estado').val();

			$.ajax({
				url: 'ajax.php?c=cliente&f=municipios',
				type: 'POST',
				dataType: 'json',
				data: {estado: estado},
			})
			.done(function(data) {
				console.log(data);
				$('#municipios').empty();
				$.each(data, function(index, val) {
					$('#municipios').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
				});
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
	},
	guardaCliente: function(){
		var idCliente =  $('#idCliente').val();
		var codigo =  $('#codigo').val();
		var nombre =  $('#nombre').val();
		var tienda =  $('#tienda').val();
		var mumint =  $('#mumint').val();
		var numext =  $('#numext').val();
		var direccion =  $('#direccion').val();
		var colonia =  $('#colonia').val(); 
		var cp =  $('#cp').val();
		var estado =  $('#estado').val();  
		var municipio =  $('#municipios').val();
		var email =  $('#email').val();
		var celular =  $('#celular').val();
		var tel1 =  $('#tel1').val();
		var tel2 =  $('#tel2').val();
		var rfc =  $('#rfc2').val();
		var curp =  $('#curp').val();
		var diasCredito =  $('#diasCredito').val();
		var limiteCredito =  $('#limiteCredito').val();
		var moneda =  $('#moneda').val();
		var listaPrecio =  $('#listaPrecio').val();

		caja.mensaje("Guardando Cliente");

		$.ajax({
			url: 'ajax.php?c=cliente&f=guardaCliente',
			type: 'POST',
			dataType: 'json',
			data: {idCliente: idCliente,
					codigo : codigo,
					nombre : nombre,
					tienda : tienda,
					mumint : mumint,
					numext : numext,
					direccion: direccion,
					colonia : colonia,
					cp : cp,
					estado : estado,
					municipio: municipio,
					email : email,
					celular : celular,
					tel1 : tel1,
					tel2 : tel2,
					rfc : rfc,
					curp : curp,
					diasCredito : diasCredito,
					limiteCredito: limiteCredito,
					moneda : moneda,
					listaPrecio : listaPrecio,
					},
		})
		.done(function(data) {
			console.log(data);
			if(data.idClienteInser!=''){
				caja.eliminaMensaje();
				$('#modalSuccess').modal({
					show:true,
				});
			}else{
				alert('Algo Paso');
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	},
	cierramodales: function (){
		$('#modalSuccess').modal('hide');
		$('#modalCliente').modal('hide');
		caja.init();
	},
	ventasButtonAccion: function(){
		caja.mensaje("Procesando...");
		$.ajax({
			url: 'ajax.php?c=caja&f=ventasCaja',
			type: 'post',
			dataType: 'json',
			//data: {param1: 'value1'},
		})
		.done(function(data) {
			console.log(data);


			var table = $('#tableSales').DataTable();
	
			//$('.filas').empty();
			table.clear().draw();
			var x ='';
			var estatus = '';
			$.each(data, function(index, val) {
				if(val.estatus=='Activa'){
					estatus = '<span class="label label-success">Activa</span>';
				}else{
					estatus = '<span class="label label-danger">Cancelada</span>';
				}
				x ='<tr class="filas">'+
								'<td>'+val.folio+'</td>'+
								'<td>'+val.fecha+'</td>'+
								'<td>'+val.cliente+'</td>'+
								'<td>'+val.empleado+'</td>'+
								'<td>'+val.sucursal+'</td>'+
								'<td>'+estatus+'</td>'+
								'<td>$'+val.iva+'</td>'+
								'<td>$'+val.monto+'</td>'+
								'<td><button class="btn btn-primary btn-block" onclick="caja.ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
								'</tr>';  
					table.row.add($(x)).draw();                          
			}); 
			caja.eliminaMensaje();
			$('#modalVentasList').modal({
				show:true,
			});
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	},
	ventaDetalle: function (id){
		
		$.ajax({
			url: 'ajax.php?c=caja&f=detalleVenta',
			type: 'post',
			dataType: 'json',
			data: {idVenta: id},
		})
		.done(function(data) {
			console.log(data);
			$('#idFacPanel').text('Venta '+id);
			$('#idVentaHidden').val(id);
			$('.rowsSale').remove();
			$.each(data.products, function(index, val) {
					$('#tableSale tr:last').after('<tr class="rowsSale" id="detalle_'+val.id+'">'+
									'<td>'+val.codigo+'</td>'+
									'<td>'+val.nombre+'</td>'+
									'<td align="center">'+val.cantidad+'</td>'+
									'<td>$'+val.preciounitario+'</td>'+
									/*'<td>$'+val.montodescuto+'</td>'+ */
									'<td>$'+val.impuestosproductoventa+'</td>'+
									'<td>$'+val.total+'</td>'+
									'</tr>');
			}); 

			$('#impuestosDiv').empty();
			$('.totalesDiv').empty();
			$('#pay').empty();
			$.each(data.taxes, function(index, val) {
				$('#impuestosDiv').append('<div class="row">'+
							'<div class="col-sm-6"><label>'+index+':</label></div>'+
							'<div class="col-sm-6"><label>$'+val+'</label></div>'+
							'</div>');   
			});
			$('#subtotalDiv').append('<div class="row">'+
							'<div class="col-sm-6"><h4>Subtotal:$'+data.total+'</h4></div>'+
							'</div>');
			$('#totalDiv').append('<div class="row">'+
							'<div class="col-sm-6"><h4>Total:$'+data.total+'</h4></div>'+
							'</div>');

			/*$('#inputSubTotal').val(data.cargos.subtotal);
			$('#inputTotal').val(data.cargos.total); */
			$.each(data.pay, function(index, val) {
				$('#pay').append('<div class="row">'+
							'<div class="col-sm-6"><label>'+val.nombre+':</label></div>'+
							'<div class="col-sm-6"><label>$'+val.monto+'</label></div>'+
							'</div>'); 
			});
			



			 $('#modalVentasDetalle').modal({
					show:true,
			});
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	},
	cancelaVenta : function(){
		var idVenta = $('#idVentaHidden').val();
		

		var r = confirm("Deseas cancelar la venta?");
		if (r == true) {
			caja.mensaje('Procesando...');
			$.ajax({
				url: 'ajax.php?c=caja&f=cancelarVenta',
				type: 'POST',
				dataType: 'json',
				data: {idVenta: idVenta},
			})
			.done(function(resca) {
				console.log(resca);
				caja.eliminaMensaje();
				if(resca.estatus==true){
					alert('Se canelo la Venta');
					
					$('#modalVentasDetalle').modal('hide');
					caja.ventasButtonAccion();
				}
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
		} 
		
	},
	reImprimeticket: function (){
		var idVenta = $('#idVentaHidden').val();
		window.open("ticket.php?idventa="+idVenta);
	},
	aplicaDescuento: function (){
		var descuento = $('#descuentoGeneral').val();
		if(descuento=='' || descuento < 1){
			alert('El descuento tiene que ser mayor a cero');
			return false;
		}
		$.ajax({
			url: 'ajax.php?c=caja&f=descuentoGeneral',
			type: 'post',
			dataType: 'json',
			data: {descuento: descuento},
		})
		.done(function(data) {
			console.log(data);
			caja.pintaResultados(data);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	},
	revisaRfc: function(){
		var rfc = $('#rfcMoldal').val();

		if(rfc==''){
			alert('Introduce un RFC.');
			return false;
		}
		caja.mensaje('Procesando...');
		$.ajax({
			url: 'ajax.php?c=caja&f=verificaRfcmodal',
			type: 'post',
			dataType: 'json',
			data: {rfc: rfc},
		})
		.done(function(data) {
			console.log(data);
			caja.eliminaMensaje();
			
			if(data.estatus==true){
			$('#gridHidden').show('slow');    
			$('.filasFormF').empty();
			 $.each(data.datosFac, function(index, val) {
				$('#datosFactGrid tr:last').after('<tr class="filasFormF" id="filaId_'+val.id+'" >'+
						'<td>'+val.rfc+'</td>'+
						'<td>'+val.razon_social+'</td>'+
						'<td>'+val.correo+'</td>'+
						'<td>'+val.pais+'</td>'+
						'<td>'+val.regimen_fiscal+'</td>'+
						'<td>'+val.domicilio+'</td>'+
						'<td>'+val.num_ext+'</td>'+
						'<td>'+val.cp+'</td>'+
						'<td>'+val.colonia+'</td>'+
						'<td>'+val.estado+'</td>'+
						'<td>'+val.municipio+'</td>'+
						'<td>'+val.ciudad+'</td>'+
						'<td><div style="float:left;"><button class="btn btn-success" type="button" onclick="caja.factButton('+val.id+');"><i class="fa fa-check" aria-hidden="true"></i></button></div></td>'+
						'<td><div style="float:left;"><button class="btn btn-default" type="button" onclick="caja.edit('+val.id+');"><i class="fa fa-pencil" aria-hidden="true"></i></button></div></td>'+
						'</tr>');
									
			});                 
			}else{
	  
				$('#modalCuestion').modal({
					show:true,
				});
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	},
	despliegaForm: function(){

		$('.formF').val('');

		$('#estadoFormF > option[value="0"]').attr('selected', 'selected');
		$('#municipioFormF > option[value="0"]').attr('selected', 'selected');
			
		$('#modalCuestion').modal('hide');
		$('#newOrUpd').empty();
		$('#newOrUpd').append('<span class="label label-default">Nuevo Registro</span>');


				var rfc2 = $('#rfcMoldal').val();
				$('#rfcFormF').val(rfc2);
				$('#rfcFormF').prop('readonly', true);
		$('#modalFormFact').modal({
			show:true,
		});
	},
	guardaFormF: function(){
		var idFac = $('#comFacId').val();
		var rfc = $('#rfcFormF').val();
		var razSoc = $('#razonSFormF').val();
		var email = $('#emailFormF').val();
		var pais = $('#paisFormF').val();
		var regimen = $('#regimenFormF').val();
		var domicilio = $('#domicilioFormF').val();
		var numero = $('#numeroFormF').val();
		var cp = $('#cpFormF').val();
		var col = $('#coloniaFormF').val();
		var estado = $('#estadoFormF').val();
		var municipio = $('#municipioFormF').val();
		var ciudad = $('#ciudadFormF').val();

		$('#but').hide();
		$('#butlo').show();
	   
		$.ajax({
			url: 'ajax.php?c=caja&f=guardaClientFact',
			type: 'post',
			dataType: 'json',
			data: {idFac: idFac,
					rfc: rfc,
					razSoc: razSoc,
					email : email,
					pais : pais,
					regimen : regimen,
					domicilio : domicilio,
					numero : numero,
					cp : cp,
					col : col,
					estado : estado,
					municipio : municipio,
					ciudad : ciudad
				},
		})
		.done(function(datox) {
			console.log(datox);
			if(datox.estatus==true){
				caja.eliminaMensaje();
				$('#but').show();
				$('#butlo').hide();
				caja.revisaRfc();
				$('#modalFormFact').modal('hide');
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
		

	},
	edit: function(id){
		$.ajax({
			url: 'ajax.php?c=caja&f=datosFacturacionCliente',
			type: 'POST',
			dataType: 'json',
			data: {id: id},
		})
		.done(function(data) {
			console.log(data);
			$('#newOrUpd').empty();
			$('#newOrUpd').append('<span class="label label-warning">Editando</span>');
			$('#comFacId').val(data.Datafact[0].idFac);
			$('#rfcFormF').val(data.Datafact[0].rfc);
			$('#razonSFormF').val(data.Datafact[0].razon_social);
			$('#emailFormF').val(data.Datafact[0].correo);
			$('#paisFormF').val(data.Datafact[0].pais);
			$('#regimenFormF').val(data.Datafact[0].regimen_fiscal);
			$('#domicilioFormF').val(data.Datafact[0].domicilio);
			$('#numeroFormF').val(data.Datafact[0].num_ext);
			$('#cpFormF').val(data.Datafact[0].cp);
			$('#coloniaFormF').val(data.Datafact[0].colonia);
			$('#ciudadFormF').val(data.Datafact[0].ciudad);

			$('#estadoFormF > option[value="'+data.Datafact[0].idEstado+'"]').attr('selected', 'selected');
			$('#municipioFormF > option[value="'+data.Datafact[0].idMunicipio+'"]').attr('selected', 'selected');
			
			$('#modalFormFact').modal({
				show:true,
			});

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	},    
	municipiosFact: function(){
		var estado = $('#estadoFormF').val();

			$.ajax({
				url: 'ajax.php?c=cliente&f=municipios',
				type: 'POST',
				dataType: 'json',
				data: {estado: estado},
			})
			.done(function(data) {
				console.log(data);
				$('#municipioFormF').empty();
				$.each(data, function(index, val) {
					$('#municipioFormF').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
				});
			})
			.fail(function() {
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
	},
	factButton: function(id){
		$('#codigoTicket').val('');
		$('#ticketDiv').attr({'src': ''});
		$('#facB').hide(); 
		 $('#ticketHideDiv').hide();

		$('#idComunFactu').val(id);
		$('#modalCodigoVenta').modal({
			show:true,
		});
	},
	buscaTicket: function(){
		$('#ticketHideDiv').hide();
		var codigoTicket = $('#codigoTicket').val();
		if(codigoTicket==''){
			alert('Ingresa un codigo');
			return false;
		}
		src = "../../modulos/pos/ticket.php?idventa=" + codigoTicket + "&print=false";
		$('#ticketDiv').attr({'src': src});
		$('#ticketHideDiv').show('slow');
		$('#facB').show('slow');

	},
	factSale: function(){
		idComunFactu = $('#idComunFactu').val();
		venta = $('#codigoTicket').val();
		documento = 2;
		mensaje = '';
		consumo = '';
		caja.mensaje('Procesando...');
		$.ajax({
			url: 'ajax.php?c=caja&f=oneFact',
			type: 'POST',
			dataType: 'json',
			data: {idComunFactu: idComunFactu,
					venta : venta
				},
		})
		.done(function(resp) {
			console.log(resp);
			caja.eliminaMensaje();
			if (resp.success == '500') {
				alert(resp.mensaje);
				window.location.reload();
				return false;
			}
			if (resp.success == '-1') {
				alert('Ha ocurrido un error durante el proceso de venta y facturacion.');
				window.location.reload();
				return false;
			}
				/* NUEVA FACTURACION Y RESPUESTA DE VENTA
				================================================ */
				if (resp.success == 0 || resp.success == 5) {
					if (resp.success == 0) {
						alert('Ha ocurrido un error durante el proceso de facturación. Error ' + resp.error + ' - ' + resp.mensaje);
					}
				}
				if (resp.success == 1){
					azu = JSON.parse(resp.azurian);
					uid = resp.datos.UUID;
					correo = resp.correo;
					$.ajax({
						type: 'POST',
						url: 'ajax.php?c=caja&f=guardarFacturacion',
						dataType: 'json',
						data: {
							UUID: uid,
							noCertificadoSAT: resp.datos.noCertificadoSAT,
							selloCFD: resp.datos.selloCFD,
							selloSAT: resp.datos.selloSAT,
							FechaTimbrado: resp.datos.FechaTimbrado,
							idComprobante: resp.datos.idComprobante,
							idFact: resp.datos.idFact,
							idVenta: resp.datos.idVenta,
							noCertificado: resp.datos.noCertificado,
							tipoComp: resp.datos.tipoComp,
							trackId: resp.datos.trackId,
							monto: (resp.monto),
							cliente: 1268,
							idRefact: 0,
							azurian: resp.azurian,
							doc: 2
						},
						beforeSend: function() {
							if($('#documento').val() == 2)
							{
								caja.mensaje("Guardando Factura");
							}else if($('#documento').val() == 3)
							{
								caja.mensaje("Guardando Recibo de Ingresos");
							}
						},
						success: function(resp) {
						   
							caja.eliminaMensaje();
							//window.open('../../modulos/facturas/'+uid+'.pdf');
							$.ajax({
								async: false,
								type: 'POST',
								url: 'ajax.php?c=caja&f=envioFactura',
								dataType: 'json',
								data: {
									uid: uid,
									correo: correo,
									azurian: azu,
									doc: $('#documento').val()
								},
								beforeSend: function() {
									caja.mensaje("Enviando Factura");
								},
								success: function(resp) {
									///Cierra los modales de facturacion , ticket y datos
									$('#modalFacturacion').modal('hide');
									$('#modalCodigoVenta').modal('hide');

									caja.eliminaMensaje();
									if(resp.cupon==false){
										caja.modalComprobante('../../modulos/facturas/'+uid+'.pdf', false, uid);
									}else{
										caja.modalComprobante('../../modulos/facturas/'+uid+'__'+resp.receptor+'__'+resp.cupon+'.pdf', false, uid);
									}
									//window.open('../../modulos/facturas/' + uid + '.pdf');
									//window.location.reload();
								},
								error: function() {
									caja.eliminaMensaje();
								}
							});

							$("#loaderventa").hide();
							$('#caja-dialog').modal('hide');
							$("#boton-pagar").removeAttr("disabled");
							alert('Has registrado la venta con exito');
							//window.location.reload();
						},
						error: function() {
							caja.eliminaMensaje();
						}
					});
				}///fin del resp-success 1
				

		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	},
	inicioCaja: function(data)
	{
		if (data.inicio !== undefined && data.inicio != false)
		{
			var contenedor = $('#divContSucursal');
			contenedor.empty();
			switch (data.inicio.status)
			{
				case 1:

				var sucursalOperando = $(document.createElement('label')).addClass('text-left control-label col-xs-7 pull-left').text('Sucursal que esta operando').appendTo(contenedor);
				var sucursalNombre = $(document.createElement('label')).addClass('text-left control-label col-xs-5').text(data.inicio.sucursalNombre).appendTo(contenedor);
				var sucursalid = $(document.createElement('hidden')).attr({'id': 'sucursalId'}).val(data.inicio.sucursalId).appendTo(contenedor);

				$('#lblSaldo').text('Saldo actual en caja');
				$('#saldocaja').text(data.inicio.saldo);

				break;

				case 2:

				var sucursalOperando = $(document.createElement('label')).addClass('text-left control-label col-xs-7 pull-left').text('Selecciona la sucursal que esta operando').appendTo(contenedor);
				var sucursales = $(document.createElement('select')).addClass('form-control').attr({'id': 'sucursalId'}).css({'width': '39%', 'margin-top': '2%'}).appendTo(contenedor);

				$.each(data.inicio.rows, function(index, val) {
					var registrosSucursales = $(document.createElement('option')).attr({'value': val.id}).html(val.nombre).appendTo(sucursales);
				});


					//$('#sucursalNombre').text(data.inicio.sucursalNombre);
					$('#lblSaldo').text('Saldo actualmente en caja');
					$('#saldocaja').text('$0.00');
					break;
				}

				$('#inicio_caja').modal({backdrop: 'static'});
} else
{
	return false;
}
},  
cajaIniciar: function(){
		if ($("#iniciocaja").val() == "") {
			alert("Debes indicar con cuanto inicia caja, puede ser 0");
			return false;
		}
		if ($("#sucursalId").val() == "") {
			alert("Debes seleccionar que sucursal estas operando");
			return false;
		}

		$.ajax({
			type: 'POST',
			url: 'ajax.php?c=caja&f=Iniciarcaja',
			data: {
				sucursal: $("#sucursalId").val(),
				monto: $("#iniciocaja").val()
			},
			success: function(resp) {
				$('#inicio_caja').modal("hide");
			}
		});//end ajax
	},
	isNumberKey: function(evt)
	{
		var charCode = (evt.which) ? evt.which : event.keyCode;
		return (charCode <= 13 || (charCode >= 48 && charCode <= 57) || charCode == 46);
	},
	muestraReferenciaPago: function(valor){
	
	var elemento = $('#divReferenciaPago');
	var elTexto = $('#lblReferencia');
	$('#txtReferencia').val('');

	elemento.css({'display': 'block'});

	switch (parseInt(valor))
	{
		case 2 :
		$('#txtCantidadPago').val(caja.data["cargos"]["total"]);
		elTexto.text('Numero de cheque:');
		break;
		case 3:
		case 4:
		case 5:
		$('#txtCantidadPago').val(caja.data["cargos"]["total"]);
		elTexto.text('Numero de tarjeta:');
		break;
		case 6:
		$('#txtCantidadPago').val(caja.data["cargos"]["total"]);
		elTexto.text('Comentario:');
		break;
		case 7 :
		$('#txtCantidadPago').val(caja.data["cargos"]["total"]);
		elTexto.text('Referencia transferencia:');
		break;
		case 8 :
		$('#txtCantidadPago').val(caja.data["cargos"]["total"]);
		elTexto.text('Referencia spei:');
		break;
		case 26:
		alert(valor);
		$('#txtCantidadPago').val(caja.data["cargos"]["total"]);
		break;
		default :
		elemento.css({'display': 'none'});
		break;
	}
},
corteButtonAccion: function(){
	caja.mensaje('Procesando...');
	$.ajax({
		url: 'ajax.php?c=caja&f=obtenCorte',
		type: 'post',
		dataType: 'json',
		data: {show: 0},
	})
	.done(function(resCor) {
		console.log(resCor);
		$('#desdeCut').val(resCor.desde);
		$('#hastaCut').val(resCor.hasta);
		$('#desdeCutText').text(resCor.desde);
		$('#hastaCutText').text(resCor.hasta);
		///Llena la tabla de los pagos
		var cliente = '';
		var Efectivo =0; 
		var TCredito =0;
		var TDebito =0;
		var CxC =0;
		var Cheque  =0;
		var Trans =0; 
		var SPEI  =0;
		var TRegalo  =0;
		var Ni  =0;
		var cambio =0;
		var Impuestos =0;
		var Monto =0;
		var Importe = 0;
		var efectivoCambio2 = 0;
		$('.cutRows').empty();
		$.each(resCor.ventas, function(index, val) {
			if(val.nombre==null){
				cliente = 'Publico General';
			}else{
				cliente = val.nombre;           
			}
			efectivoCambio = (val.Efectivo - val.cambio);
					$('#gridPagosCut tr:last').after('<tr class="cutRows">'+
									'<td>'+val.idVenta+'</td>'+
									'<td>'+cliente+'</td>'+
									'<td>'+val.fecha+'</td>'+
									'<td>$'+val.Efectivo+'</td>'+
									'<td>$'+val.TCredito+'</td>'+
									'<td>$'+val.TDebito+'</td>'+
									'<td>$'+val.CxC+'</td>'+
									'<td>$'+val.Cheque+'</td>'+
									'<td>$'+val.Trans+'</td>'+
									'<td>$'+val.SPEI+'</td>'+
									'<td>$'+val.TRegalo+'</td>'+
									'<td>$'+val.Ni+'</td>'+
									'<td>$'+val.cambio+'</td>'+
									'<td>$'+val.Impuestos+'</td>'+
									'<td>$'+val.Monto+'</td>'+
									'<td>$'+val.Importe+'</td>'+
									'<td>$'+efectivoCambio+'</td>'+
									'</tr>');
					Efectivo +=  parseFloat(val.Efectivo);
					TCredito += parseFloat(val.TCredito);
					TDebito += parseFloat(val.TDebito);
					CxC += parseFloat(val.CxC);
					Cheque += parseFloat(val.Cheque);
					Trans += parseFloat(val.Trans);
					SPEI += parseFloat(val.SPEI);
					TRegalo +=parseFloat(val.TRegalo);
					Ni += parseFloat(val.Ni);
					cambio += parseFloat(val.cambio);
					Impuestos += parseFloat(val.Impuestos);
					Monto += parseFloat(val.Monto);
					Importe += parseFloat(val.Importe);
					efectivoCambio2 += parseFloat(efectivoCambio); 
		}); 
				$('#gridPagosCut tr:last').after('<tr class="cutRows">'+
									'<td colspan="3">Totales</td>'+
								   
									'<td>$'+Efectivo.toFixed(2)+'</td>'+
									'<td>$'+TCredito.toFixed(2)+'</td>'+
									'<td>$'+TDebito.toFixed(2)+'</td>'+
									'<td>$'+CxC.toFixed(2)+'</td>'+
									'<td>$'+Cheque.toFixed(2)+'</td>'+
									'<td>$'+Trans.toFixed(2)+'</td>'+
									'<td>$'+SPEI.toFixed(2)+'</td>'+
									'<td>$'+TRegalo.toFixed(2)+'</td>'+
									'<td>$'+Ni.toFixed(2)+'</td>'+
									'<td>$'+cambio.toFixed(2)+'</td>'+
									'<td>$'+Impuestos.toFixed(2)+'</td>'+
									'<td>$'+Monto.toFixed(2)+'</td>'+
									'<td style="background-color: #FFCCDD;">$'+Importe.toFixed(2)+'</td>'+
									'<td style="background-color: #a9f5a9;">$'+efectivoCambio2.toFixed(2)+'</td>'+
									'</tr>');
		///Llena la tabla de los productos
		var Cantidad = 0;
		var Descuento = 0;
		var Impuestos2 = 0;
		var Subtot = 0;
		$.each(resCor.productos, function(index, val) {
					$('#gridProductosCut tr:last').after('<tr class="cutRows">'+
									'<td>'+val.codigo+'</td>'+
									'<td>'+val.nombre+'</td>'+
									'<td align="center">'+val.Cantidad+'</td>'+
									'<td>$'+val.preciounitario+'</td>'+
									'<td>$'+val.Descuento+'</td>'+
									'<td>$'+val.Impuestos+'</td>'+
									'<td>$'+val.Subtot+'</td>'+
									'</tr>');
					//alert('antes='+val.Subtot);
					Cantidad += parseFloat(val.Cantidad);
					Descuento += parseFloat(val.Descuento);
					Impuestos2 += parseFloat(val.Impuestos2);
					Subtot += parseFloat(val.Subtot); 
					//alert('sumado='+Subtot);
		}); 
							$('#gridProductosCut tr:last').after('<tr class="cutRows">'+
									'<td colspan="4">Totales</td>'+                  
									'<td>$'+Descuento.toFixed(2)+'</td>'+
									'<td>$'+Impuestos.toFixed(2)+'</td>'+
									'<td style="background-color: #FFCCDD;">$'+Subtot.toFixed(2)+'</td>'+
									'</tr>'); 
		///Llena la Tabla de retiros
		var cantidad3 = 0;
		$.each(resCor.retiros, function(index, val) {
					$('#gridRetirosCut tr:last').after('<tr idRetiro="'+val.id+'" class="cutRows">'+
									'<td>'+val.id+'</td>'+
									'<td>'+val.fecha+'</td>'+
									'<td>'+val.concepto+'</td>'+
									'<td>'+val.usuario+'</td>'+
									'<td>$'+val.cantidad+'</td>'+
									
									'</tr>');
					cantidad3 += parseFloat(val.cantidad) 
		}); 
		   $('#gridRetirosCut tr:last').after('<tr class="cutRows">'+
									'<td colspan="4">Totales</td>'+
									'<td style="background-color: #FFCCDD;">'+cantidad3.toFixed(2)+'</td>'+
									'</tr>');


		$('#saldo_inicial').val(resCor.montoInical);
		$('#monto_ventas').val(resCor.monto_ventas);
		$('#saldo_disponible').val(resCor.saldoDisponible);
		
		caja.eliminaMensaje();
		$('#modalCorteDeCaja').modal({
			show:true,
		});
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	

},
newCut: function(){
   var fecha_inicio = $('#desdeCut').val();
   var fecha_final = $('#hastaCut').val();
   var inicial =  $('#saldo_inicial').val();
   var montoVentas = $('#monto_ventas').val();
   var disponible = $('#saldo_disponible').val();
   var retiroCaja = $('#retiro_caja').val();
   var deposito = $('#deposito_caja').val();
   var retiros = '';

	$("#gridRetirosCut tr").each(function (index) 
	{   //console.log($("#tablita input:hidden"));
		idRetiro = $(this).attr('idRetiro');
		retiros += idRetiro+'-';
	});
	caja.mensaje('Procesando...');
	$.ajax({
		url: 'ajax.php?c=caja&f=crearCorte',
		type: 'POST',
		dataType: 'json',
		data: {fecha_inicio: fecha_inicio,
				fecha_fin : fecha_final,
				saldo_inicial : inicial,
				monto_ventas : montoVentas,
				saldo_disponible : disponible,
				retiro_caja : retiroCaja,
				deposito_caja : deposito,
				retiros : retiros
		},
	})
	.done(function(resCorte) {
		console.log(resCorte);
		if(resCorte.idCorte!=''){
			alert('Se Realizo el corte con Exito');
			caja.eliminaMensaje();
			$('#modalCorteDeCaja').modal('hide');
			caja.init();
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	


}, 
buscarVenta: function(){
	var idVenta = $('#inputidVenta').val();
	if(idVenta==''){
		alert('Ingresa un id de Venta');
		return false;
	}
		caja.mensaje('Procesando...');
		$.ajax({
			url: 'ajax.php?c=caja&f=buscaVentaCaja',
			type: 'post',
			dataType: 'json',
			data: {idVenta: idVenta},
		})
		.done(function(resVen) {
			console.log(resVen);
			var table = $('#tableSales').DataTable();
	
			//$('.filas').empty();
			table.clear().draw();
			var x ='';
			var estatus = '';
			$.each(resVen.venta, function(index, val) {
				if(val.estatus=='Activa'){
					estatus = '<span class="label label-success">Activa</span>';
				}else{
					estatus = '<span class="label label-danger">Cancelada</span>';
				}
				x ='<tr class="filas">'+
								'<td>'+val.folio+'</td>'+
								'<td>'+val.fecha+'</td>'+
								'<td>'+val.cliente+'</td>'+
								'<td>'+val.empleado+'</td>'+
								'<td>'+val.sucursal+'</td>'+
								'<td>'+estatus+'</td>'+
								'<td>$'+val.iva+'</td>'+
								'<td>$'+val.monto+'</td>'+
								'<td><button class="btn btn-default btn-block" onclick="caja.ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
								'</tr>';  
					table.row.add($(x)).draw();                          
			}); 
			caja.eliminaMensaje();
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	
},
enviarTicket: function(){
	var emailTicket = $('#emailTicket').val();
	var idVenta = $('#idVentaTicket').val();


	// Expresion regular para validar el correo
	var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

	// Se utiliza la funcion test() nativa de JavaScript
	if (regex.test(emailTicket.trim())) {
		caja.mensaje('Enviando...');
		$.ajax({
			url: 'ajax.php?c=caja&f=enviarTicket',
			type: 'POST',
			dataType: 'json',
			data: {idVenta : idVenta,
					correo : emailTicket},
		})
		.done(function(result) {
			console.log(result);
			if(result.estatus==true){
				caja.eliminaMensaje();
				alert('Se envio al correo Electronico');
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});

	} else {
		alert('La direccón de correo no es valida');
		return false;
	}
	
},
pedir: function(){
	var cliente = $("#hidencliente-caja").val();
	var productos = $('#totalDeProductosInput').val();

	if(cliente=='' && cliente==0){
		alert('Necesitas agregar un cliente.');
		return false;
	}
	if(productos=='' || productos < 1){
		alert('Necesitas agregar productos al pedido');
		return false;
	}
	$('#modalConfirm').modal();


   /* var r = confirm("Desear reliazar el pedido?");
	if (r != true) {
		txt = "You pressed Cancel!";
	} else {
		txt = "You pressed ok!";
			  $.ajax({
				url: 'ajax.php?c=caja&f=guardarPedido',
				type: 'POST',
				dataType: 'json',
				async: true,
				data: {
					idFact: $("#rfc").val(),
					documento: $("#documento").val(),
					cliente: $("#hidencliente-caja").val(),
					suspendida: $("#s_cliente").val(),
					comentario: $('#txtareacomentariosProducto').val(),
						//pagoautomatico: 1,
						//impuestos: $totalimpuestos,
						//sucursal: $("#caja-sucursal").val(),
						//almacen: $("#caja-almacen").val(),
						//cambio: 0,
						//monto: $total,
						//cliente: $("#hidencliente-caja").val(),
						//empleado: $("#idvendedor").val()
					},
					beforeSend: function() {
						caja.mensaje("Guardando Pedido");
					},
					success: function(resp) {
						console.log('----> success venta');
						console.log(resp);

						caja.eliminaMensaje();
						if(resp.idPedido > 0){
							alert('Pedido realizado con exito.')
							window.location.reload();
						}
					
				},
				error: function(data) {
					console.log('----> error venta');
					console.log(data);
						
					caja.eliminaMensaje();
					alert(data.msg);
				}
			});
	} */
},
pedirConfirm: function(){
	$('#modalConfirm').modal('hide');
	var obs = $('#txtareaObservaciones').val();
			$.ajax({
				url: 'ajax.php?c=caja&f=guardarPedido',
				type: 'POST',
				dataType: 'json',
				async: true,
				data: {
					idFact: $("#rfc").val(),
					documento: $("#documento").val(),
					cliente: $("#hidencliente-caja").val(),
					suspendida: $("#s_cliente").val(),
					comentario: $('#txtareacomentariosProducto').val(),
					moneda: $('#monedaVenta').val(),
					obs : obs,
						//pagoautomatico: 1,
						//impuestos: $totalimpuestos,
						//sucursal: $("#caja-sucursal").val(),
						//almacen: $("#caja-almacen").val(),
						//cambio: 0,
						//monto: $total,
						//cliente: $("#hidencliente-caja").val(),
						//empleado: $("#idvendedor").val()
					},
					beforeSend: function() {
						caja.mensaje("Guardando Pedido");
					},
					success: function(resp) {
						console.log('----> success pedido');
						console.log(resp);

						caja.eliminaMensaje();
						if(resp.idPedido > 0){
							alert('Pedido realizado con exito.')
							window.location.reload();
						}
					
				},
				error: function(data) {
					console.log('----> error venta');
					console.log(data);
						
					caja.eliminaMensaje();
					alert(data.msg);
				}
			});
},
backGrid: function(){
	var pathname = window.location.pathname;
	window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=imprimeGridP';
},agregaCarac: function(){
	//alert('entro');
	var a = '';
	var idProducto = $('#carIdProddiv').val();
	$(".recr").each(function() {
		a += $(this).val()+'*';
	});
	$('#modalCarac').modal('hide');
	caja.agregaProducto(idProducto,a);
},
buscaCaracteristicas: function (id){
	caja.mensaje('Procesando...');
	$.ajax({
		url: 'ajax.php?c=caja&f=obtenCaracteristicas',
		type: 'POST',
		dataType: 'json',
		data: {id: id,
			cantidad: $('#cantidad-producto').val()},
	})
	.done(function(result) {
		console.log(result);
				if(result.tieneCar > 0){
						$('#prodCarcDiv').empty();
						var contenido = '';
						$.each(result.cararc, function(index, val) {
							 //alert(index);
							 contenido += '<div class="row">'
							/* contenido += '<div class="col-sm-6">';
							 contenido += ' <img src="../pos/'+result.imagen+'" height="150" width="180"> ';
							 contenido +='</div>'; */
							 contenido +='<div class="col-sm-12">';
							 contenido += '<label>'+index+'</label>';
							 contenido += '<select class="form-control recr"  onchange="caja.getExisCara();">';
							 $.each(val, function(index2, val2) {
								  contenido +='<option value="'+val2.id_caracteristica_padre+'=>'+val2.id+'">'+val2.nombre+'</option>';
							 });
							 contenido +='</select>';
							 contenido +='</div>';
							 contenido +='</div>';
							
						}); 
						contenido += '<div class="row"><div class="col-sm-12">';
						contenido +='<label>Existencia:</label>';
						contenido +='<label id="exiCaracText"></label>';
						contenido +='<input type="hidden" id="exiCaracInput">';
						contenido +='</div></div>';
						
						$('#carIdProddiv').val(id);
						$('#prodCarcDiv').append(contenido);
						$('#modalCarac').modal({
							show:true,
						}); 

						$('#divImagenPro').attr("src", '../pos/'+result.imagen);
						$('#modal-labelCr').text(result.nombreProd);
						
						caja.eliminaMensaje();
						caja.getExisCara();
						//alert('prueba');

						salir = 1;
						//alert('salir1='+salir);
						////lotes
					   /*     var contenido2 = '';
							var options='';
							$.each(result.lotes, function( k, v ) {
								alert(v.idLote);
								options+='<option value="'+v.idLote+'">'+v.numero+' ('+v.cantidad+')</option>';
							});

							contenido2 += '<div class="row"><div class="col-sm-6">';
							contenido2 +='<label>Prosucto lote</label></div>';
							contenido2 += '<div class="col-sm-6">';
							contenido2 +='<select id="lotes" multiple="" class="selectpicker">';
							contenido2 +=options+'</select>';
							contenido2 +='</div></div>';
						$('#prodCarcDiv').append(contenido2);
						$('#modalCarac').modal({
							show:true,
						});
						$('#lotes').select2({width : '100%'}); */
				}else{
					caja.agregaProducto(id,'');
				}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
},
buscaProdCoin: function(){
	var moneda = $('#monedaVenta').val();
	caja.mensaje('Procesando...');
	$.ajax({
		url: 'ajax.php?c=caja&f=productosMoneda',
		type: 'POST',
		dataType: 'json',
		data: {coin: moneda},
	})
	.done(function(resmon) {
		console.log(resmon);
		caja.eliminaMensaje();
		//alert(resmon.respuesta);
		if(resmon.respuesta > 0){
			$('#contProducts').empty();
			var nombre = '';
			var btnContent = '';
			var contador = 1;
			$.each(resmon.productos, function(index, val) {
			   
				if(val.descripcion_corta!=''){
					nombre = val.descripcion_corta;
				}else{
					nombre = val.nombre;
				} 

					btnContent += '<div class="pull-left" style="padding:2px;">';
					btnContent += '  <button class="btn btn-default" codigoProTouch="'+val.codigo+'" onclick="caja.agregaProTouch(this)">';
					btnContent += '    <div class="row">';
					btnContent += '       <div style="width:90px;" class="wrapPro">';
					btnContent += '          <label>'+nombre.substr(0,10)+'</label>';
					btnContent += '       </div>';
					btnContent += '    </div>';
					btnContent += '    <div class="row">';
					btnContent += '      <div style="height:70px; width:100px;">';
					btnContent += '          <img src="'+val.ruta_imagen+'" alt="" style="height:70px; width:90px;">';
					btnContent += '      </div>';
					btnContent += '    </div>';
					btnContent += '    <div class="row">';
					btnContent += '      <label>$'+val.precio.toFixed(2)+'</label>';
					btnContent += '    </div>';
					btnContent += '  </button>';
					btnContent += '</div>'; 
					
					contador++; 
			}); 
				$('#contProducts').append(btnContent);
		}else{
			alert('No se encontraron productos asosciados a esa moneda.');
		}

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
},
getExisCara: function(){
	var a = '';
	var idProducto = $('#carIdProddiv').val();
	$(".recr").each(function() {
		a += $(this).val()+',';
	});
 
	$.ajax({
		url: 'ajax.php?c=caja&f=getExisCara',
		type: 'post',
		dataType: 'json',
		data: { a : a,
				producto : idProducto},
	})
	.done(function(respExisCar) {
	   $('#exiCaracInput').val(respExisCar.cantidadExis);
	   $('#exiCaracText').text(respExisCar.cantidadExis);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	}); 
	
},
descuentoParcial: function (id){

	$.ajax({
		url: 'ajax.php?c=caja&f=getInfoProducto',
		type: 'post',
		dataType: 'json',
		data: {id: id},
	})
	.done(function(respDesC) {
		console.log(respDesC);
		$('#xProParc').val(id);
		$('#encabezadoNombre').text(respDesC.nombre);
		$('#encabezadoPrecio').text('$'+parseFloat(respDesC.precio).toFixed(2));
		$('#encabezadoPrecioInput').val(parseFloat(respDesC.precio).toFixed(2));
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
	$('#desCantidad').val('');
	$('#modalDescParcial').modal();
	
},
aplicaDesParcial: function(){
	var id = $('#xProParc').val();
	var cantidad = $('#desCantidad').val();
	var tipoDes = $('#tipoDescu').val();
	var pre = $('#encabezadoPrecioInput').val();

	if(parseFloat(cantidad) < 0){
		alert('La cantidad debe ser mayor a cero');
		return false;
	}

	if(tipoDes=='%'){
		if(parseFloat(cantidad) > 100 ){
			alert('El descuento no puede ser mayor al 100%');
			return false;
		}
	}
	if(tipoDes=='$'){
		if(parseFloat(cantidad) > parseFloat(pre)){
			alert('El descuento no puede ser mayor al precio del producto');
			return false;
		}
	} 

	caja.mensaje('Procesando...');
	$.ajax({
		url: 'ajax.php?c=caja&f=cambiaCantidad',
		type: 'POST',
		dataType: 'json',
		data: {id: id,
			   cantidad : cantidad,
			   tipo : tipoDes,
			},
	})
	.done(function(data) {
		console.log(data);
		caja.data = data;
		caja.pintaResultados(data, false);
		$('#modalDescParcial').modal('hide');
		caja.eliminaMensaje();
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	

},
pintarProductos() {
    $('#rango').val(0);
    $('#containerTouch').empty();
    caja.cargarMas();

},

resetFilters(){
    $("#selectDepartamento").empty().trigger('change');
},

cargarMas: function(){
    var rango = $('#rango').val(),
        departamento = $('#selectDepartamento').val(),
        familia = $('#selectFamilia').val(),
        linea = $('#selectLinea').val();

   caja.mensaje('Procesando...' + rango);
    $.ajax({
        url: 'ajax.php?c=caja&f=cargarMas',
        type: 'post',
        dataType: 'json',
        data: { departamento: departamento,
                familia : familia,
                linea : linea,
                rango: rango 
            }
    })
    .done(function(resp) {
        console.log(resp);
        var y = parseFloat(rango);
        var x = y + 100;
        $('#rango').val(x);
        var nombre = '';
        $('#botonCarga').remove();
        $.each(resp, function(index, val) {
            if (val.tipo_producto!=3) {
                if(val.descripcion_corta!=''){
       
                    nombre = val.descripcion_corta.substr(0, 10);  
                }else{
                    nombre = val.nombre.substr(0, 10);
                }

                $('#containerTouch').append('<div class="pull-left" style="padding:2px;">'+
                '  <button class="btn btn-default" codigoProTouch="'+val.codigo+'" onclick="caja.agregaProTouch(this)">'+
                '    <div class="row">'+
                '       <div style="width:90px;" class="wrapPro">'+
                '          <label>'+nombre+'</label>'+
                '       </div>'+
                '    </div>'+
                '    <div class="row">'+
                '      <div style="height:70px; width:100px;">'+
                '          <img src="../pos/'+val.ruta_imagen+'" alt="" style="height:70px; width:90px;">'+
                '      </div>'+
                '    </div>'+
                '    <div class="row">'+
                '      <label>$'+parseFloat(val.precio).toFixed(2)+'</label>'+
                '    </div>'+
                '  </button>'+
                '</div>');
                
            } 
        });
        $('#containerTouch').append('<div class="row" id="botonCarga"><div class="col-sm-12"><button class="btn btn-default" onclick="caja.cargarMas();">Cargar mas</button></div></div>');
        caja.eliminaMensaje();

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
},

toggleFilters(){
	if( $( '#idCategorias' ).is( ':visible' ) )
        $( '#idCategorias' ).hide();
	else
		$( '#idCategorias' ).show();
}

};//fin de caja var

window.onload = function() {
    
    $("#selectDepartamento").select2({
        placeholder: "Departamento",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 1,
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectDepartamento").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    })
    .on("change", function(e) {
        $("#selectFamilia").empty().trigger('change');
    });
    $("#selectFamilia").select2({
        placeholder: "Familia",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 2,
                    departamento : $('#selectDepartamento').val(),
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectFamilia").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    })
    .on("change", function(e) {
        $("#selectLinea").empty().trigger('change');
    });
    $("#selectLinea").select2({
        placeholder: "Linea",
        delay: 250,
        width:'100%',
        ajax: {
            url: 'ajax.php?c=caja&f=buscarClasificadores',
            type: 'GET',
            dataType: 'json',

            data: function(params) {
                return { clasificador : 3,
                    familia : $('#selectFamilia').val(),
                    patron: params.term };
            },

            processResults: function (data) {
                $("#selectLinea").empty();
                return { results: data.rows };
            },
            cache: true
        },
        templateResult: function format(state) {
            return  state.text;
        },
        templateSelection: function format(state) {
            return  state.text;
        }
    })
    .on("change", function(e) {
        caja.pintarProductos();
    });;
};