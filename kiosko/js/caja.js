var caja = {
    currentRequest: null,
    currentRequestP: null,
    meses: new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"),
    diasSemana: new Array("Domingo", "Lunes", "Martes", "Mi&eacute;rcoles", "Jueves", "Viernes", "S&aacute;bado"),
    data: new Array(),
    
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
    
    modalComprobante: function(src, ticket) {

        $('#modalPago').modal('hide');

        $('#frameComprobante').attr({'src': src});

        $('#modalComprobante').modal({backdrop: 'static'});
    },

    facturarButton: function(){
        $('#gridHidden').hide();
        $('#rfcMoldal').val('');
        $('#modalFacturacion').modal({
                show:true,
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
            }else if(data.rfc_no_valido !== undefined){
                alert("Rfc no valido");
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
                $('#rfcFormF').prop('readonly', true)
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
            }else if(datox.rfc_no_valido !== undefined){
                alert("Rfc no valido");
                $('#but').show();
                $('#butlo').hide();
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
                url: 'ajax.php?c=caja&f=municipios',
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
        $.ajax({
            url: 'ajax.php?c=caja&f=verificaFacturacionValida',
            type: 'POST',
            data: {codigoTicket: codigoTicket},
        })
        .done(function(data) {
            console.log(data);
            if(data == "ok"){
                src = "../kiosko/ticket.php?idventa=" + codigoTicket + "&print=false"
                $('#ticketDiv').attr({'src': src});
                $('#ticketHideDiv').show('slow');
                $('#facB').show('slow');
            }else if(data == "pasada"){
                $('#facB').hide('slow');
                alert("Esta venta no se puede facturar debido a que no se encuentra entre los dias habiles de facturacion.");
            }else{
                 $('#facB').hide('slow');
                alert("Esta venta ha sido facturada anteriormente");
            }
        })
        .fail(function(error) {
            console.log(error);
            $('#facB').hide('slow');
            alert("No se ha podido encontrar la venta, intentalo nuevamente");
        })
        .always(function() {
            console.log("complete");
        });
        

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

                            $.ajax({
                                url: 'ajax.php?c=caja&f=guardaTIDPe',
                                type: 'POST',
                                dataType: 'json',
                                data: {trackId: resp.trackId,id:venta},
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

                    }
                }
                if (resp.success == 1){
                    azu = JSON.parse(resp.azurian);
                    uid = resp.datos.UUID;
                    correo = resp.correo;
                    logo = azu.org.logo;
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
                            cliente: idComunFactu,
                            idRefact: venta,
                            azurian: resp.azurian,
                            doc: 2
                        },
                        beforeSend: function() {
                            caja.mensaje("Guardando Factura");
                        },
                        success: function(resp) {

                            
                            if (typeof azu.Basicos.version !== 'undefined') {
                                //alert('3.2');
                                version = '3.2';
                            }else{
                                //alert('3.3');
                                version = '3.3';
                                openedWindow = window.open('../webapp/modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo='+logo+'&id=temporales&caja=2&nominas=1');
                                //openedWindow = window.open('../webapp/modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo=f_de_foodware.png&id=temporales&caja=10&nominas=1');

                                //openedWindow.close();
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
                                
                            }
                            caja.eliminaMensaje();
                           
                            //window.open('../../modulos/facturas/'+uid+'.pdf');
                           /* if($('#versionFacturacionHide').val() == '3.3'){
                                openedWindow = window.open('../../modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo=f_de_foodware.png&id=temporales&caja=1&nominas=1');
                                openedWindow.close();
                            }  */
                            setTimeout(
                              function() 
                              {
                            $.ajax({
                                async: false,
                                type: 'POST',
                                url: 'ajax.php?c=caja&f=envioFactura',
                                dataType: 'json',
                                data: {
                                    uid: uid,
                                    correo: correo,
                                    azurian: azu,
                                    doc: 2
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

                                        if (version == '3.2') {
                                            //alert('3.2A');
                                            caja.modalComprobante('../webapp/modulos/facturas/'+uid+'.pdf', false);

                                        }else{
                                            //alert('3.3A');
                                            caja.modalComprobante('../webapp/modulos/facturas/'+uid+'.pdf', false);
                                            //caja.modalComprobante('../webapp/modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo='+logo+'&id=temporales&caja=10&nominas=1', false);

                                            //caja.modalComprobante('../webapp/modulos/cont/controllers/visorpdf.php?name='+uid+'.xml&logo=f_de_foodware.png&id=temporales&caja=10&nominas=1', false);

                                        }
                                        
                                    }else{
                                        caja.modalComprobante('../webapp/modulos/facturas/'+uid+'__'+resp.receptor+'__'+resp.cupon+'.pdf', false);
                                    }
                                },
                                error: function() {
                                    caja.eliminaMensaje();
                                }
                            });
                              }, 999);


                            $("#loaderventa").hide();
                            $('#caja-dialog').modal('hide');
                            $("#boton-pagar").removeAttr("disabled");
                            $('#modalCodigoVenta').modal('hide');
                            alert('Venta facturada correctamente');
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
        
    }
    
}//fin de caja var