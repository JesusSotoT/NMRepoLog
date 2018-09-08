function gridFacturado(bandera){

	$('#modalMensajes').modal();
	                $.ajax({
                    url: 'ajax.php?c=cliente&f=gridCliente',
                    type: 'post',
                    dataType: 'json',
                    //data: {param1: 'value1'},
                })
                .done(function(resp) {
                    console.log(resp);

            var table = $('#tableGrid').DataTable();
    
            //$('.rows').remove();
            var arch = '';
            table.clear().draw();

            if(bandera==2){
            	        $.each(resp.grid, function(index, val) {
        					if(val.archivo!=null){
        						arch = val.archivo;
        					}else{
        						arch = '';
        					}
	                        if(val.factura!=null && val.factura!='Error'){
	                        x ='<tr>'+
	                                '<td>'+val.id+'</td>'+
	                                '<td>'+val.nombre+'</td>'+
	                                '<td>'+val.folio_inadem+'</td>'+
	                                '<td>'+val.cupon+'</td>'+
	                                '<td>'+val.vitrina+'</td>'+
	                                '<td>'+val.resp_nwm+'</td>'+
	                                '<td>'+val.factura+'</td>'+
	                                '<td>'+arch+'</td>'+
	                                '<td></td>'+
	                               // '<td>$'++'</td>'+
	                                //'<td>$'+parseFloat(val.impuestos).toFixed(2)+'</td>'+
	                                //'<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
	                                '</tr>';
	                                 table.row.add($(x)).draw(); 
	                        }
	  
	                            //totalLabel += parseFloat(val.value); 
	                            //$('#tableFP tr:last').after(x);    
	                   

        				});
            }else if(bandera==3){
            	        $.each(resp.grid, function(index, val) {
        		        	if(val.archivo!=null){
        						arch = val.archivo;
        					}else{
        						arch = '';
        					}
	                        if(val.factura=='Error'){
	                        x ='<tr>'+
	                                '<td>'+val.id+'</td>'+
	                                '<td>'+val.nombre+'</td>'+
	                                '<td>'+val.folio_inadem+'</td>'+
	                                '<td>'+val.cupon+'</td>'+
	                                '<td>'+val.vitrina+'</td>'+
	                                '<td>'+val.resp_nwm+'</td>'+
	                                '<td>'+val.factura+'</td>'+
	                                '<td>'+arch+'</td>'+
	                                '<td><input type="checkbox" value="'+val.id+'" class="checkPro"></td>'+
	                               // '<td>$'++'</td>'+
	                                //'<td>$'+parseFloat(val.impuestos).toFixed(2)+'</td>'+
	                                //'<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
	                                '</tr>';
	                                 table.row.add($(x)).draw(); 
	                        }
	  
	                            //totalLabel += parseFloat(val.value); 
	                            //$('#tableFP tr:last').after(x);    
	                   

        				});
            }else{
            	        $.each(resp.grid, function(index, val) {
        		        	if(val.archivo!=null){
        						arch = val.archivo;
        					}else{
        						arch = '';
        					}
	                        if(val.factura==null){
	                        x ='<tr>'+
	                                '<td>'+val.id+'</td>'+
	                                '<td>'+val.nombre+'</td>'+
	                                '<td>'+val.folio_inadem+'</td>'+
	                                '<td>'+val.cupon+'</td>'+
	                                '<td>'+val.vitrina+'</td>'+
	                                '<td>'+val.resp_nwm+'</td>'+
	                                '<td></td>'+
	                                '<td>'+arch+'</td>'+
	                                '<td><input type="checkbox" value="'+val.id+'" class="checkPro"></td>'+
	                               // '<td>$'++'</td>'+
	                                //'<td>$'+parseFloat(val.impuestos).toFixed(2)+'</td>'+
	                                //'<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
	                                '</tr>';
	                                 table.row.add($(x)).draw(); 
	                        }
	  
	                            //totalLabel += parseFloat(val.value); 
	                            //$('#tableFP tr:last').after(x);    
	                   

        				});
            }

            $('#modalMensajes').modal('hide');


                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
}
function facturaIn(idandFac){
	var res = idandFac.split("-");
	var id = res[0];
	var idFacres = res[1];
	

	$.ajax({
		//url: 'ajax.php?c=cliente&f=facturaIndem',
		url: 'ajax.php?c=cliente&f=notaInadem',
		async:false,    
        cache:false,
		type: 'POST',
		dataType: 'json',
		data: {idReg: id},
	})
	.done(function(resp) {
		console.log(resp);
			idRegNota = id;
			var idFac = idFacres;
			x = JSON.parse(resp.azurian);
			console.log(x);
		
			if(resp.success==0){

				$.ajax({
					url: 'ajax.php?c=cliente&f=guardaError',
					async:false,    
        			cache:false,
					type: 'post',
					dataType: 'json',
					data: {error: resp.error,
							id: id,
						},
				})
				.done(function(eser) {
					console.log(eser);
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
				$('#contResp').append('<div class="row danger"><div class="col-sm-12"><label>'+x.Receptor.nombre+' - Error :'+resp.error+' - '+resp.mensaje+'</label><i class="fa fa-times" aria-hidden="true"></i></div></div>');
			}
				if(resp.success==1){
					$('#contResp').append('<div class="row success"><div class="col-sm-12"><label>'+x.Receptor.nombre+' - UUID : '+resp.datos.UUID+'</label><i class="fa fa-check" aria-hidden="true"></i></div></div>');
					azu=resp.azurian;
					uid=resp.datos.UUID;
					correo=resp.correo;
					idFacRela = idFac;
					uuuuid = resp.datos.UUID;
					$.ajax({
						type: 'POST',
						url:'ajax.php?c=cliente&f=guardaNota',
						async:false, 
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
							total:resp.monto,
							idFacRela: idFacRela
							//idfac:idfac,
						},
						success: function(resp){
							alert(idRegNota);
							$.ajax({
								//async: false,
								url: 'ajax.php?c=cliente&f=GuardaNotaInadem',
								type: 'POST',
								async:false, 
								dataType: 'json',
								data: {
										UUID2 : uid,
										idRegNota : idRegNota
									},
							})
							.done(function(dataResp) {
								console.log(dataResp);
							})
							.fail(function() {
								console.log("error");
							})
							.always(function() {
								console.log("complete");
							});
							
							$.ajax({
                                async: false,
                                type: 'POST',
                                url: 'ajax.php?c=cliente&f=envioFactura',
                                dataType: 'json',
                                data: {
                                    uid: uid,
                                    correo: correo,
                                    azurian: azu,
                                    doc: 2
                                },
                                beforeSend: function() {
                                    //caja.mensaje("Enviando Factura");
                                },
                                success: function(resp) {
                                    //$('#modalFacturacion').modal('hide');
                                   /* $('#modalCodigoVenta').modal('hide');

                                    caja.eliminaMensaje();
                                    if(resp.cupon==false){
                                        caja.modalComprobante('../../modulos/facturas/'+uid+'.pdf', false, uid);
                                    }else{
                                        caja.modalComprobante('../../modulos/facturas/'+uid+'__'+resp.receptor+'__'+resp.cupon+'.pdf', false, uid);
                                    }
                                    caja.eliminaMensaje(); */
                                    //window.open('../../modulos/facturas/' + uid + '.pdf');
                                    //window.location.reload();
                                },
                                error: function() {
                                   
                                }
                            });
							/*$.ajax({
								async: false,
								type: 'POST',
								url:'../../modulos/punto_venta/funcionesPv.php',
								data:{funcion:"envioFactura2",uid:uid,correo:correo,azurian:azu},
								success: function(resp){  
									
								}
							});  */

							
							//alert('Se ha facturado correctamente');
							
							//window.location.reload();
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
function allNotes(){
		alert('okeokdoekdoekdokeodkeodkeo');

		$('#modalLoading').modal();
		
		$('#btnaf').hide();
		$('#loadingDiv').show();
		x = '';
		var oTable = $('#tableGrid').dataTable();
    	var allPages = oTable.fnGetNodes();

		cadena='';
		var time = 5000;
		$('input:checked', allPages).each(function(){
            cadena+=$(this,allPages).val()+',';
            x = $(this,allPages).val();
            facturaIn(x);
        });
		//var str = "How are you doing today?";
		var res = cadena.split(",");
        alert('Termino el proceso de Facturacion');
        $('#btnCerrar78').show();

}
function selAll(){

	var oTable = $('#tableGrid').dataTable();
    var allPages = oTable.fnGetNodes();

    /*if ($('.checkPro',allPages).is(":checked")) {
    	$('.checkPro',allPages).prop('checked', false);
    	aaa();
    }else{
    	$('.checkPro',allPages).prop('checked', true);
    	aaa();
    } */
    if ($('.checkPro').is(":checked")) {
    	$('.checkPro').prop('checked', false);
    	aaa();
    }else{
    	$('.checkPro').prop('checked', true);
    	aaa();
    }
    
}