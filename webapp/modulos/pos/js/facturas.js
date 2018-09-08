function verPdf(id){
	window.open("../../modulos/facturas/"+id+".pdf");

}

function verXml(id){
	$.ajax({
		url: 'ajax.php?c=caja&f=origenPac',
		type: 'POST',
		dataType: 'json',
		data: {id: id},
	})
	.done(function(resp) {
		if(resp.pac=='formas'){
			window.open("../../modulos/cont/xmls/facturas/temporales/"+id+".xml");
		}else{
			window.open("../../modulos/facturas/"+id+".xml");
		}
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});


}


function crearNota(){
	var monto = $('#montoNota').val();
	var montFact = $('#inputMonto').val();
	var idFac = $('#idFactN').val();

	if(parseFloat(monto) < 1){
		alert('EL monto de la nota de credito tiene que ser mayor a cero.');
		return false;
	}
	if(parseFloat(montFact) < monto){
		alert('El monto no puede ser mayor al de la factura.');
		return false;
	}
	monto = parseFloat(monto);
	montosiniva = (monto/1.16) //subtotal o importe
	iva = (montosiniva*0.16) // puro iva
	total = montosiniva+iva;
	    $('#lblMensajeEstado').text('Creando Nota de Credito...');
        $('#modalMensajes').modal({
                        show:true,
                        keyboard: false,
                    });
	$.ajax({
		url: 'ajax.php?c=caja&f=creaNota',
		type: 'POST',
		dataType: 'json',
		data: { monto: monto,
				montosiniva: montosiniva,
				iva : iva,
				total: total,
				idFac: idFac

		},
	})
	.done(function(resp) {
		console.log(resp);
	    if(resp.success==0){
			alert('Ha ocurrido un error al crear la nota de crédito. Error '+resp.error+' - '+resp.mensaje);
			window.location.reload();
			return false;
		}
		if(resp.success==1){
			$('#lblMensajeEstado').text('Guardando Nota de Credito...');
			azu=resp.azurian;
			uid=resp.datos.UUID;
			correo=resp.correo;
			idFacRela = idFac;
			uuuuid = resp.datos.UUID;
			$.ajax({
				type: 'POST',
				url:'ajax.php?c=caja&f=guardaNota',
				data:{
					UUID:resp.datos.UUID,
					noCertificadoSAT:resp.datos.noCertificadoSAT,
					selloCFD:resp.datos.selloCFD,
					selloSAT:resp.datos.selloSAT,
					FechaTimbrado:resp.datos.FechaTimbrado,
					idComprobante:resp.datos.idComprobante,
					idFact:resp.datos.idFact,
					idVenta:resp.datos.idVenta,
					noCertificado:resp.datos.noCertificado,
					tipoComp:resp.datos.tipoComp,
					trackId:resp.datos.trackId,
					monto:resp.monto,
					cliente:'',
					idRefact:'c',
					azurian:resp.azurian,
					total:total,
					idFacRela: idFacRela,
					//idfac:idfac,
				},
				success: function(resp){

					totalX = total;
					$('#gridNotas').append('<tr class="cutRows">'+
                                    '<td>'+uuuuid+'</td>'+
                                    '<td>'+totalX+'</td>'+
                                    '</tr>');

					$.ajax({
						url: 'ajax.php?c=caja&f=envioFactura',
						type: 'POST',
						dataType: 'json',
						data: {  uid: uid,
                                    correo: correo,
                                    azurian: azu,
                                    doc: 10,
                                },
					})
					.done(function() {
						$('#modalMensajes').modal('hide');
						alert('Se creo la nota satisfactoriamente.');
						window.location.reload();
					})
					.fail(function() {
						console.log("error");
					})
					.always(function() {
						console.log("complete");
					});

				}
			});
		}






	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});


}

function notaCredito(id){


	$.ajax({
		url: 'ajax.php?c=caja&f=infoFact',
		type: 'post',
		dataType: 'json',
		data: {id: id},
	})
	.done(function(res) {
		console.log(res);
		$('#idFactN').val(id);
		$('#labelMonto').text('$'+res.monto);
		$('#inputMonto').val(res.disponible);
		$('#modalNotaCredito').modal();
		$('#dispoPnota').text('$'+res.disponible)

		$('#tablaNotas').empty();
		$('#tablaNotas').append('<table class="table table-bordered table-hover" id="gridNotas">'+
                               '<thead>'+
                                   '<tr>'+
                                       '<th>Folio</th>'+
                                       '<th>Monto</th>'+
                                   '</tr>'+
                               '</thead>'+
                               '<tbody></tbody></table>');
		$.each(res.notas, function(index, val) {
			$('#gridNotas tr:last').after('<tr class="cutRows">'+
                                    '<td>'+val.folio+'</td>'+
                                    '<td>'+parseFloat(val.monto).toFixed(2)+'</td>'+
                                    '</tr>');
		});


	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
	function aaa(){
			var checados = $( "input:checked" ).length;

		var oTable = $('#tableGrid').dataTable();
    	var allPages = oTable.fnGetNodes();
    	var contador = 0;
		cadena='';
		$('input:checked', allPages).each(function(){
            contador ++;
        });
        
			if(contador>=2){
				$('#btnaf').css('display','block');
			}else{
				$('#btnaf').css('display','none');
			}

		}
function cancelar(id){
	var r = confirm("Deseas cancelar la Factura?");
	if (r == true) {
		$('#lblMensajeEstado').text('Cancelando Factura...');
        $('#modalMensajes').modal({
                        show:true,
                        keyboard: false,
                    });
	    $.ajax({
	    	url: 'ajax.php?c=caja&f=cancelaFactura',
	    	type: 'POST',
	    	dataType: 'json',
	    	data: {id: id},
	    })
	    .done(function(resp) {
	    	$('#modalMensajes').modal('hide');
	    	if(resp.success==1){
	    		//alert(resp.mensaje);
	    		//window.location.reload();
	    		$.ajax({
	    			url: 'ajax.php?c=caja&f=cancelaFacturaEstatus',
	    			type: 'POST',
	    			dataType: 'json',
	    			data: {id: id},
	    		})
	    		.done(function(dataresp) {
	    			console.log(dataresp);
	    			if(dataresp.respuesta==1){
	    				alert('Se cancelo La Factura.');
	    				window.location.reload();
	    			}
	    		})
	    		.fail(function() {
	    			console.log("error");
	    		})
	    		.always(function() {
	    			console.log("complete");
	    		});
	    		



	    		/*$.ajax({
	    			url: 'ajax.php?c=caja&f=cancelaFacturaEstatus',
	    			type: 'post',
	    			dataType: 'json',
	    			data: {id: id},
	    		})
	    		.done(function(respuesta) {
	    			console.log(respuesta);
	    			alert(respuesta.respuesta);
	    			if(respuesta.respuesta==1){
	    				alert('Se cancelo La Factura.');
	    				window.location.reload();
	    			}
	    		})
	    		.fail(function(err) {
	    			var perro = JSON.stringify(err);
	    			alert(perro);
	    			console.log(perro);
	    		})
	    		.always(function() {
	    			console.log("complete");
	    		}); */




	    		//window.location.reload();

	    	}else{
	    		alert('Error '+resp.error+' :'+resp.mensaje);
	    	}
	    })
	    .fail(function() {
	    	console.log("error");
	    })
	    .always(function() {
	    	console.log("complete");
	    });

	} else {
	   return false;
	}
}


function buscar(){
	$('#btnMostrarMasFacturas').hide();
	var cliente = $('#clienteFc').val();
	var desde = $('#desde').val();
	var hasta = $('#hasta').val();
	var tipo = $('#tipoDoc').val();
	var empleado = $('#empleado').val();
	var sucursal = $('#sucursal').val();
	$.ajax({
		url: 'ajax.php?c=caja&f=buscarFacturas',
		type: 'GET',
		dataType: 'html',
		data: {cliente: cliente,
				desde : desde,
				hasta : hasta,
				tipo : tipo,
				empleado : empleado,
				sucursal : sucursal
			},
	})
	.done(function(result) {
		console.log( String(result) );

		var table = $('#tableGrid').DataTable();
		table.clear().draw();
        $( result ).find('tbody>tr').each(function(index, el) {
        	table.rows.add( $(el.outerHTML) );
        });
		table.draw();

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}

function fact(id){

	$('#modalFact').modal();
	$('#idPendienteFact').val(id);
}
function facturale(){



	var id = $('#idPendienteFact').val();
	var cliente = $('#rfc').val();
	var obser = $('#obser').val();
	var serie = $('#seriesCfdi').val();

	$.ajax({
		url: 'ajax.php?c=caja&f=validaClienteFact',
		type: 'post',
		dataType: 'json',
		data: {cliente: cliente,
				venta: id},
	})
	.done(function(respEx) {
		console.log(respEx);

		if(respEx.resx==0){
		var txt;
		var r = confirm("El rfc no coincide con el cliente de la venta ¿Deseas continuar?");
		if (r == true) {
				$('#modalFact').modal('hide');
				$('#modalMensajes').modal();
				$.ajax({
					url: 'ajax.php?c=caja&f=factPendiente',
					type: 'POST',
					dataType: 'json',
					data: {id: id,
						cliente: cliente,
						obser: obser,
						serie : serie
					},
				})
				.done(function(resp) {
					console.log(resp);
						if(resp.success==600){
							alert('Esta venta no es facturable.');
							window.location.reload();
							return false;
						}
						if(resp.success==0){

							alert('Ha ocurrido un error durante el proceso de facturación. Error '+resp.error+' - '+resp.mensaje);

							//$(".divSelector").html('<div style="margin-top:40px;">Recargando pagina...</div>');
							//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');
							window.location.reload();
							$.ajax({
								url: 'ajax.php?c=caja&f=guardaTIDPe',
								type: 'POST',
								dataType: 'json',
								data: {trackId: resp.trackId,id:id},
							})
							.done(function(tr) {
								console.log("success");
							})
							.fail(function() {
								console.log("error");
							})
							.always(function() {
								console.log("complete");
							});
							$('#modalMensajes').modal('hide');

						}
							if(resp.success==1){
								azu=resp.azurian;
								azu2=resp.azurian;
								azu = JSON.parse(resp.azurian);
								uid=resp.datos.UUID;
								correo=resp.correo;
								logo =  azu.org.logo;
								console.log(resp.azurian);
								$.ajax({
									type: 'POST',
									url:'ajax.php?c=caja&f=guardarFacturacion',
									data:{
										UUID:resp.datos.UUID,
										noCertificadoSAT:resp.datos.noCertificadoSAT,
										selloCFD:resp.datos.selloCFD,
										selloSAT:resp.datos.selloSAT,
										FechaTimbrado:resp.datos.FechaTimbrado,
										idComprobante:resp.datos.idComprobante,
										idFact:cliente,
										idVenta:id,
										noCertificado:resp.datos.noCertificado,
										tipoComp:resp.datos.tipoComp,
										trackId:resp.datos.trackId,
										monto:resp.datos.monto,
										cliente:resp.datos.idCliente,
										idRefact:id,
										azurian:resp.azurian},
									success: function(resp){
										console.log(azu);
										if (typeof azu.Basicos.version !== 'undefined') {
			                                //alert('3.2');

			                                version = '3.2';
				                            $.ajax({
												async: false,
												type: 'POST',
												url:'../../modulos/punto_venta/funcionesPv.php',
												data:{funcion:"envioFactura2",uid:uid,correo:correo,azurian:azu2, idfp:0},
												success: function(resp){

												}
											});
			                            }else{
			                                //alert('3.3');
			                                ///////Creacion del PDF
						                   	$.ajax({
			                                    url: 'ajax.php?c=caja&f=pdf33',
			                                    type: 'POST',
			                                    dataType: 'json',
			                                    data: {uid: uid,
			                                            logo: logo},
			                                })
			                                .done(function(respPdf) {
			                                    
			                                    console.log(respPdf);
			                                })
			                                .fail(function() {
			                                    console.log("error");
			                                })
			                                .always(function() {
			                                    console.log("complete");
			                                });

			                                version = '3.3';
			                                openedWindow = window.open('../cont/controllers/visorpdf.php?name='+uid+'.xml&logo='+logo+'&id=temporales&caja=1&nominas=1');
			                                //openedWindow.close();
				                            $.ajax({
												async: false,
												type: 'POST',
												url:'../../modulos/punto_venta/funcionesPv.php',
												data:{funcion:"envioFactura3",uid:uid,correo:correo,azurian:azu, idfp:0},
												success: function(resp){

												}
											});
			                            }


										/*$.ajax({
											async: false,
											type: 'POST',
											url:'../../modulos/punto_venta/funcionesPv.php',
											data:{funcion:"envioFactura2",uid:uid,correo:correo,azurian:azu, idfp:0},
											success: function(resp){

											}
										}); */


										alert('Se ha facturado correctamente');
										$('#modalMensajes').modal('hide');
										window.location.reload();
										//$(".divSelector").html('<div style="margin-top:40px;">Recargando pagina...</div>');
										//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');

									}
								});
							}
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
		} else {
		   return false;
		}

		}else{
			$('#modalFact').modal('hide');
				$('#modalMensajes').modal();
				$.ajax({
					url: 'ajax.php?c=caja&f=factPendiente',
					type: 'POST',
					dataType: 'json',
					data: {id: id,
						cliente: cliente,
						obser: obser,
						serie: serie
					},
				})
				.done(function(resp) {
					console.log(resp);
						if(resp.success==600){
							alert('Esta venta no es facturable.');
							window.location.reload();
							return false;
						}
						if(resp.success==0){

							alert('Ha ocurrido un error durante el proceso de facturación. Error '+resp.error+' - '+resp.mensaje);

							//$(".divSelector").html('<div style="margin-top:40px;">Recargando pagina...</div>');
							//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');
							window.location.reload();
							$.ajax({
								url: 'ajax.php?c=caja&f=guardaTIDPe',
								type: 'POST',
								dataType: 'json',
								data: {trackId: resp.trackId,id:id},
							})
							.done(function(tr) {
								console.log("success");
							})
							.fail(function() {
								console.log("error");
							})
							.always(function() {
								console.log("complete");
							});
							$('#modalMensajes').modal('hide');

						}
							if(resp.success==1){
								azu=resp.azurian;
								azu2 = resp.azurian;
								azu = JSON.parse(resp.azurian);
								uid=resp.datos.UUID;
								correo=resp.correo;
								$.ajax({
									type: 'POST',
									url:'ajax.php?c=caja&f=guardarFacturacion',
									data:{
										UUID:resp.datos.UUID,
										noCertificadoSAT:resp.datos.noCertificadoSAT,
										selloCFD:resp.datos.selloCFD,
										selloSAT:resp.datos.selloSAT,
										FechaTimbrado:resp.datos.FechaTimbrado,
										idComprobante:resp.datos.idComprobante,
										idFact:cliente,
										idVenta: id,
										noCertificado:resp.datos.noCertificado,
										tipoComp:resp.datos.tipoComp,
										trackId:resp.datos.trackId,
										monto:resp.datos.monto,
										cliente:resp.datos.idCliente,
										idRefact:id,
										azurian:resp.azurian},
									success: function(resp){
										console.log(azu);
										if (typeof azu.Basicos.version !== 'undefined') {
			                                //alert('3.2');
			                                version = '3.2';
				                            $.ajax({
												async: false,
												type: 'POST',
												url:'../../modulos/punto_venta/funcionesPv.php',
												data:{funcion:"envioFactura2",uid:uid,correo:correo,azurian:azu2, idfp:0},
												success: function(resp){

												}
											});
			                            }else{
			                                //alert('3.3');
			                                version = '3.3';
			                                openedWindow = window.open('../cont/controllers/visorpdf.php?name='+uid+'.xml&logo=f_de_foodware.png&id=temporales&caja=10&nominas=1');
			                                openedWindow.close();
				                            $.ajax({
												async: false,
												type: 'POST',
												url:'../../modulos/punto_venta/funcionesPv.php',
												data:{funcion:"envioFactura3",uid:uid,correo:correo,azurian:azu, idfp:0},
												success: function(resp){

												}
											});
			                            }


									/*	$.ajax({
											async: false,
											type: 'POST',
											url:'../../modulos/punto_venta/funcionesPv.php',
											data:{funcion:"envioFactura2",uid:uid,correo:correo,azurian:azu, idfp:0},
											success: function(resp){

											}
										}); */


										alert('Se ha facturado correctamente');
										$('#modalMensajes').modal('hide');
										window.location.reload();
										//$(".divSelector").html('<div style="margin-top:40px;">Recargando pagina...</div>');
										//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');

									}
								});
							}
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
		}










	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}
function buscarPendientes(){
	//var cliente = $('#clienteFc').val();
	var desde = $('#desde').val();
	var hasta = $('#hasta').val();
	var sucursal = $('#sucursal').val();
	var empleado = $('#empleado').val();
	$('#modalMensajes').modal();
	$.ajax({
		url: 'ajax.php?c=caja&f=buscarVentasPendientes',
		type: 'POST',
		dataType: 'json',
		data: {
				desde : desde,
				hasta : hasta,
				empleado : empleado,
				sucursal : sucursal
			},
	})
	.done(function(result) {
		console.log(result);
		var table = $('#tableGrid').DataTable();

            //$('.rows').remove();

            table.clear().draw();

            var x ='';
            var estatus = '';
            var monto = 0;
            var iva = 0;
            var total = 0;
            var y = '';
            var origen = '';
            var ttt = '';
            $.each(result, function(index, val) {
            	if(val.facturado=='0'){
	            		            	//alert(val.cadenaOriginal);
	            	//x = JSON.parse(val.cadenaOriginal)
	            	console.log(x);
	            	/*alert(x.datosTimbrado.UUID);
	            	alert(val.borrado);
	            	alert(val.idSale);
	            	alert(x.datosTimbrado.UUID);
	            	alert(x.Basicos.total);
	            	alert(x.Receptor.nombre);
	            	alert(val.tipoComp); */
	                if(val.borrado=='0'){
	                    estatus = '<span class="label label-success">Activa</span>';
	                }else{
	                    estatus = '<span class="label label-danger">Cancelada</span>';
	                }
	                if(val.origen == 1){ // comercial
	                	origen = 'Envios';
	                }else{
	                	origen = 'Caja';
	                }
	                /*if (typeof x.Basicos.total!== 'undefined') {
	                	ttt = x.Basicos.total;
	                }else{
	                	ttt = x.Basicos.Total;
	                } */
	                y ='<tr class="filas">'+
	                                '<td>'+val.id_sale+'</td>'+
	                                '<td>'+val.fecha+'</td>'+
	                                '<td>$'+parseFloat(val.monto).toFixed(2)+'</td>'+
	                                '<td>'+val.cliente+'</td>'+
	                                '<td>'+val.empleado+'</td>'+
	                                '<td>'+val.sucursal+'</td>'+
	                                '<td>'+origen+'</td>'+
	                                //'<td>'+val.factNum+'</td>'+
	                                '<td><a class="btn btn-default" onclick="fact('+val.id_sale+')"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>'+
	                                '<td><input class="checkPro" type="checkbox" name="prods" value="'+val.id_sale+'" id="check_'+val.id+'" onclick="aaa();"></td>'+
	                                '</tr>';

	                    table.row.add($(y)).draw();
            	}


            });
            //alert(total);
         $('#modalMensajes').modal('hide');


	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function allfs(){
		//alert('okeokdoekdoekdokeodkeodkeo');
		$('#btnaf').hide();
		$('#loadingDiv').show();

		var oTable = $('#tableGrid').dataTable();
    	var allPages = oTable.fnGetNodes();

		cadena='';
		$('input:checked', allPages).each(function(){
            cadena+=$(this,allPages).val()+',';
        });

		$('#modalMensajes').modal();

		$.ajax({
			data:{id:cadena},
       		url:'ajax.php?c=caja&f=allfs',
       		type: 'POST',
       		dataType:'json',
       		success: function(resp){
       			console.log(resp);
       			if(resp.success==0){
					alert('Ha ocurrido un error durante el proceso de facturación. Error '+resp.error+' - '+resp.mensaje);
					$('#modalMensajes').modal('hide');
					return false;
				}
				if(resp.success==1){

					azu=resp.azurian;
					uid=resp.datos.UUID;
					correo='';


					$.ajax({
						type: 'POST',
						url:'ajax.php?c=caja&f=guardarFacturacion',
						data:{
							UUID:resp.datos.UUID,
							noCertificadoSAT:resp.datos.noCertificadoSAT,
							selloCFD:resp.datos.selloCFD,
							selloSAT:resp.datos.selloSAT,
							FechaTimbrado:resp.datos.FechaTimbrado,
							idComprobante:resp.datos.idComprobante,
							idFact:resp.datos.idFact,
							idVenta:resp.datos.idVenta,
							noCertificado:resp.datos.noCertificado,
							tipoComp:resp.datos.tipoComp,
							trackId:resp.datos.trackId,
							monto:resp.datos.monto,
							cliente:resp.datos.idCliente,
							azurian: resp.azurian,
							idRefact:'all'+cadena},
						success: function(resp){
							//alert('98989898');
							$('#btnaf').show();
							$('#loadingDiv').hide();
							$('#modalMensajes').modal('hide');
							alert('Se ha facturado correctamente');
 							$.ajax({
								async: false,
								type: 'POST',
								url:'ajax.php?c=caja&f=envioFactura',
								data:{uid:uid,correo:'muchasFac@gmail.com',azurian:azu,doc:''},
								success: function(resp){

								}
							});
							console.log(resp);

							window.location.reload();
							//$('.frurl', window.parent.document).attr('src','../repolog/repolog.php?i=43');

						}
					});
				}
       		}
		});
}

function enviaFact(id,correo){
	$('#correoDestina').val(correo);
	$('#cuerpoCorreo').val('');
	$('#uuidEscondido').val(id);
	$('#modalEnvio').modal();
}
function reenviale(){
	var uuid = $('#uuidEscondido').val();
	var email = $('#correoDestina').val();
	var msg = $('#cuerpoCorreo').val();
	if (msg == "") { msg="Factura generada"; }

	console.log("email"+email);
	email = email.trim();

	if (email == '') {
		alert('Escriba un correo por favor.');
		return 0;
	}else{
		$('#modalMensajes').modal();
		$.ajax({
			url: 'ajax.php?c=caja&f=rFac',
			type: 'POST',
			dataType: 'json',
			data: {uuid: uuid,
					email: email,
					msg: msg
				},
		})
		.done(function(resp) {
			$('#modalMensajes').modal('hide');
			$('#modalEnvio').modal('hide');
			if(resp.estatus==true){
				alert('Se envio correctamente');
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	}
}
function selAll(){

	var oTable = $('#tableGrid').dataTable();
    var allPages = oTable.fnGetNodes();

    if ($('.checkPro',allPages).is(":checked")) {
    	$('.checkPro',allPages).prop('checked', false);
    	aaa();
    }else{
    	$('.checkPro',allPages).prop('checked', true);
    	aaa();
    }

}
function verAcuse(id){
	$.ajax({
		url: 'ajax.php?c=caja&f=verAcuse',
		type: 'post',
		dataType: 'json',
		data: {id: id},
	})
	.done(function(dataresp) {
		console.log(dataresp);

		$('.acusefecha').text(dataresp.fecha);
		$('#acuserfc').text(dataresp.rfc);
		$('#acusefolio').text(dataresp.folio);
		$('#modal-acuse').modal('show')
		$('#idFact').val(id);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function enviarAcuse(){
	var correo = $('#correoEnvio').val();
    var idFact = $('#idFact').val();
    var imprime = 0;

    // Expresion regular para validar el correo
    var regex = /[\w-\.]{2,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/;

    // Se utiliza la funcion test() nativa de JavaScript
    if (regex.test(correo.trim())) {
        $('#modalMensajes').modal();
        $.ajax({
            url: 'ajax.php?c=caja&f=enviarAcuse',
            type: 'POST',
            dataType: 'json',
            data: {idFact : idFact,
                    correo : correo,
                    imprime : imprime
                },
        })
        .done(function(result) {
            console.log(result);
            if(result.enviado==1){
				$('#modalMensajes').modal('hide');
				$('#modalEnvio').modal('hide');
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
}
function imprimeAcuse(){
	var correo = $('#correoEnvio').val();
    var idFact = $('#idFact').val();
    var imprime = 1;

	$.ajax({
		url: 'ajax.php?c=caja&f=enviarAcuse',
		type: 'POST',
		dataType: 'json',
		data: {idFact : idFact,
                correo : correo,
                imprime : imprime
		},
	})
	.done(function(resp) {
		console.log(resp);
		var caracteristicas = "height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0";
      	nueva=window.open(this.href, 'Popup', caracteristicas);
      	nueva.document.write(resp.contenido);
      	nueva.document.write('<script type="text/javascript"> window.print(); </script>');

      	return false;
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
function muestraMas(){
	var rango = $('#rango').val();
   //caja.mensaje('Procesando...')
   //$('#modalMensajes').modal();
    $.ajax({
        url: 'ajax.php?c=caja&f=muestraMasFact',
        type: 'post',
        data: {rango: rango},
    })
    .done(function(result) {

        var y1 = parseFloat(rango);
        var x1 = y1 + 100;
        $('#rango').val(x1);
        //console.log(result);

        var table = $('#tableGrid').DataTable();
        $( result ).find('tbody>tr').each(function(index, el) {
        	table.rows.add( $(el.outerHTML) );
        });
        table.draw();
		

            
           




    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}
function dowloadZip(){

		var oTable = $('#tableGrid').dataTable();
    	var allPages = oTable.fnGetNodes();

		cadena='';
		$('input:checked', allPages).each(function(){
            cadena+=$(this,allPages).val()+'*';
        });

       $.ajax({
       	url: 'ajax.php?c=caja&f=dowloadZip',
       	type: 'post',
       	dataType: 'json',
       	data: {cadena: cadena},
       })
       .done(function(resp) {
       	console.log(resp);
       	if(resp.estatus==1){
       		window.open("../facturas/notas/facturas.zip",'_blank');
       	}
       })
       .fail(function() {
       	console.log("error");
       })
       .always(function() {
       	console.log("complete");
       });


}

	function relacion_facturas_pendientes(tipo, uuid, $importes)
	{
		$('#mensaje p').html("");
		$('#container_tbl_facturas').hide();
		$('#tbl_facturas_pendientes tbody').html("");
		$('#facturas_pendientes').modal('show');
		if(parseFloat($importes))
		{
			if(tipo == 1) 
			{
				$.post("ajax.php?c=caja&f=relacion_polizas_facturas",
				{
					uuid:uuid
				},
				function(data)
				{
					$('#container_tbl_facturas').show();
					$.each(data, function(poliza, array)
					{
						//Abrimos la fila
						$('#tbl_facturas_pendientes tbody').append("<tr></tr>");
						//Recorremos el array de los registros obtenidos de la base de datos
						$.each(array, function(campo, valor)
						{
							if (campo == 'numpol') 
							{
								$('#tbl_facturas_pendientes tbody tr:last').append("<td>Poliza de Ingreso "+valor+"</td>");
							} else if(campo == 'SumImportes')
							{
								$('#tbl_facturas_pendientes tbody tr:last').append("<td>$"+valor+"</td>");
							} else 
							{
								$('#tbl_facturas_pendientes tbody tr:last').append("<td>"+valor+"</td>");
							}
						});
						//Añadimos el saldo.
						//$('#tbl_facturas_pendientes tbody tr:last').append(
						//	"<td>$"+(totalFactura-data[poliza].SumImportes)+"</td>"
						//);
					});
				}, "JSON");
			}
			else if(tipo == 2)
			{
				$('#mensaje p').html("La factura tiene cuentas por cobrar relacionadas.");
			}
		}
		else
			$('#mensaje p').html("<b style='color:red;'>La factura no tiene cuentas por cobrar ni polizas relacionadas.</b>");

	} 


function muestraMasComPago(){
	var rango = $('#rango').val();
   //caja.mensaje('Procesando...')
   //$('#modalMensajes').modal();
    $.ajax({
        url: 'ajax.php?c=caja&f=muestraMasFactComplementosPago',
        type: 'post',
        data: {rango: rango},
    })
    .done(function(result) {

        var y1 = parseFloat(rango);
        var x1 = y1 + 100;
        $('#rango').val(x1);
        //console.log(result);

        var table = $('#tableGrid').DataTable();
        $( result ).find('tbody>tr').each(function(index, el) {
        	table.rows.add( $(el.outerHTML) );
        });
        table.draw();
		

            
           




    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}

function buscarComPago(){
	$('#btnMostrarMasFacturas').hide();
	var cliente = $('#clienteFc').val();
	var desde = $('#desde').val();
	var hasta = $('#hasta').val();
	var empleado = $('#empleado').val();
	var sucursal = $('#sucursal').val();
	$.ajax({
		url: 'ajax.php?c=caja&f=buscarFacturasComPag',
		type: 'GET',
		dataType: 'html',
		data: {cliente: cliente,
				desde : desde,
				hasta : hasta,
				empleado : empleado,
				sucursal : sucursal
			},
	})
	.done(function(result) {
		console.log( String(result) );

		var table = $('#tableGrid').DataTable();
		table.clear().draw();
        $( result ).find('tbody>tr').each(function(index, el) {
        	table.rows.add( $(el.outerHTML) );
        });
		table.draw();

	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});

}
