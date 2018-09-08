window.onload = function(){
    $('#cliente').select2({ width: '100%' });
}
function ventasGrid(){

    $.ajax({
        url: 'ajax.php?c=caja&f=refresh',
        type: 'POST',
        dataType: 'json',
        //data: {param1: 'value1'},
    })
    .done(function(data) {
       
        console.log(data);
            var table = $('#tableSales').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var estatus = '';
            $.each(data.ventas, function(index, val) {
                if(val.estatus=='Activa'){
                    estatus = '<span class="label label-success">Activa</span>';
                }else{
                    estatus = '<span class="label label-danger">Cancelada</span>';
                }

                x ='<tr class="filas">'+
                                '<td>'+val.folio+'</td>'+
                                '<td>Ticket</td>'+
                                '<td>'+val.fecha+'</td>'+
                                '<td>'+val.cliente+'</td>'+
                                '<td>'+val.empleado+'</td>'+
                                '<td>'+val.sucursal+'</td>'+
                                '<td>'+estatus+'</td>'+
                                '<td>$'+parseFloat(val.iva).toFixed(2)+'</td>'+
                                '<td>$'+parseFloat(val.monto).toFixed(2)+'</td>'+
                                '<td><button class="btn btn-primary btn-block" onclick="ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
                                '</tr>';  
                    table.row.add($(x)).draw();                          
            });         
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}

 function ventaDetalle(id){
  
        $.ajax({
            url: 'ajax.php?c=caja&f=detalleVenta',
            type: 'post',
            dataType: 'json',
            data: {idVenta: id},
        })
        .done(function(data) {
            console.log(data);
            $('#idFacPanel').text(id);
            $('#idVentaHidden').val(id);
            $('.rowsSale').remove();
            if(data.estatusVenta==0){
                $('#cancelButton').hide();
                $('#idComentarioDevolucion').hide();
                $('#idAlmacenDevolucion').hide();
                $('#devButton').hide();
            }else{
                $('#cancelButton').show();
                $('#idComentarioDevolucion').show();
                $('#idAlmacenDevolucion').show();
                $('#devButton').show();
            }
            var descDesc = '';

            $.each(data.products, function(index, val) {

                if(val.id==0){
                    val.nombre = val.comentario;
                    val.codigo = 'promo';
                }
                if(val.montodescuento > 0){
                    descDesc  = '[Precio:$'+parseFloat(val.precio).toFixed(2)+',Descuento:$'+parseFloat(val.montodescuento).toFixed(2)+'/'+val.tipodescuento+''+val.descuento+']';
                }
                var devolver = `
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <input type="number" class="form-control inputCantidadDevolucion" value="0" min="0" max=${val.cantidad} />
                    </div>
                </div>
                `;
                    $('#tablaVenta').append('<tr class="rowsSale" id="detalle_'+val.id+'" ventaProducto="'+val.idventa_producto+'" json="" > '+
                                    '<td>'+val.codigo+'</td>'+
                                    '<td>'+val.nombre+descDesc+'</td>'+
                                    '<td align="center" class="cantidadProductos">'+val.cantidad+'</td>'+
                                    '<td>$'+parseFloat(val.preciounitario).toFixed(2)+'</td>'+
                                    '<td>$'+parseFloat(val.impuestosproductoventa).toFixed(2)+'</td>'+
                                    '<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
                                    '<td>' + devolver + '</td>'+
                                    '<th> <a class="btn"> <span class="label '+(val.devoluciones != 0 ? "label-warning" : "label-default" ) +'" '+(val.devoluciones != 0 ? 'onclick="detalleMovimientoDevolucion(' + val.idventa_producto + ');"' : "" ) + '> Ver devoluciones </span> </a> </th>'+
                                    '<th class="idVentaProductoDevolucion" style="display: none;">' + val.idventa_producto + '</th>'+
                                    '</tr>');
                    descDesc = '';
            }); 

            $('.inputCantidadDevolucion')
            .on('click', function(event) {
                event.preventDefault();
                $(this).trigger('focus');
            })
            .on('focus', function()  {
                var idVentaProducto = $(this).parent().parent().parent().parent().find('.idVentaProductoDevolucion').text() ;
                
                $.ajax({
                    type: "GET",                                            
                    url: "ajax.php?c=caja&f=obtenerSeriesYLotes",
                    data: {
                        idVentaProducto : idVentaProducto
                    },                                          
                    timeout: 2000,   
                    dataType: 'json',                                       
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(data) {

                        //console.log(data);
                        if(data.series.length > 0) {
                            //console.log(data.series.substring(0,data.series.length-1).split(","));

                            //arraySeries = data.series.substring(0,data.series.length-1).split(",");

                            domSeries = '';
                            data.series.forEach( (s) => {
                                domSeries += `
                                        <tr>
                                            <td> <input type="checkbox" name="serie_${s.id}" >  </td>
                                            <td> ${s.serie} </td>
                                        </tr>
                                `;
                            });
                            $('#modalSeriesDevolucion table').attr('ventaProducto', idVentaProducto);
                            $('#aceptarDevolucionSeries').attr('ventaProducto', idVentaProducto);
                            $('#modalSeriesDevolucion tbody').empty().append(domSeries);
                            $('#modalSeriesDevolucion').modal({
                                show:true,
                            });
                            $('#aceptarDevolucionSeries').on('click' , function() {
                                seriesDevolver = [];
                                $('#modalSeriesDevolucion tbody input').each( function() {
                                    if( $(this).is(":checked") ){
                                        seriesDevolver.push($(this).attr('name'));
                                    }
                                });
                                $(`tbody#tablaVenta tr[ventaProducto=${ $(this).attr('ventaProducto') }] .inputCantidadDevolucion`).val( seriesDevolver.length ) ;
                                $(`tbody#tablaVenta tr[ventaProducto=${ $(this).attr('ventaProducto') }]`).attr( 'json', JSON.stringify(seriesDevolver) ) ;

                                // $('#modalSeriesDevolucion tbody input').each( function() {
                                //     console.log( $(this).attr('name') , $(this).is(":checked") ) ;
                                // });
                            });

                            
                            

                            
                        }

                        if(data.lotes.length > 0) {
                            //console.log(data.lotes.substring(0,data.lotes.length-3).split(","));

                            //arrayLotes = data.lotes.lotes.substring(0,data.lotes.length-3).split(",");
                            domLotes = '';
                            data.lotes.forEach( (l) => {
                                //loteCantidad = l.split("-");
                                domLotes += `
                                        <tr>
                                            <td> <input type="number" max="${l[1]}" name="lote_${l[0]}" value=0>  </td>
                                            <td> ${l[1]} </td>
                                            <td> ${l[2]} </td>
                                        </tr>
                                `;
                            });

                            $('#modalLotesDevolucion table').attr('ventaProducto', idVentaProducto);
                            $('#aceptarDevolucionLotes').attr('ventaProducto', idVentaProducto);
                            $('#modalLotesDevolucion tbody').empty().append(domLotes);
                            $('#modalLotesDevolucion tbody input').
                            on('change', function(event) {
                                if( parseFloat($(this).val()) > parseFloat($(this).attr('max')) ) {
                                    $(this).val($(this).attr('max'));
                                    alert("No puedes devolver una cantidad mayor a la de la venta");
                                }
                            });
                            $('#modalLotesDevolucion').modal({
                                show:true,
                            });

                            $('#aceptarDevolucionLotes').on('click' , function() {
                                lotesDevolver = [];
                                cantidadProductos = 0;
                                $('#modalLotesDevolucion tbody input').each( function() {
                                    if( $(this).val() ){
                                        let objLote = {};
                                        objLote[$(this).attr('name')] = $(this).val();
                                        lotesDevolver.push( objLote );
                                            cantidadProductos +=  parseInt( $(this).val() );
                                    }else {
                                        cantidadProductos +=  0;
                                    }
                                });
                                $(`tbody#tablaVenta tr[ventaProducto=${ $(this).attr('ventaProducto') }] .inputCantidadDevolucion`).val( cantidadProductos ) ;
                                $(`tbody#tablaVenta tr[ventaProducto=${ $(this).attr('ventaProducto') }]`).attr( 'json', JSON.stringify(lotesDevolver) ) ;
                                // $('#modalLotesDevolucion tbody input').each( function()  {
                                //     console.log( $(this).attr('name')  , $(this).val() ) ;
                                // });
                            });

                            
                        }
                    },
                    error: function() {                                    
                    }
                });
            })
            .on('change', function() {
                var cantidad = $(this).parent().parent().parent().parent().find('.cantidadProductos').text() ;

                if ( parseInt( $(this).val() ) > parseInt( cantidad )) {
                    $(this).val("0");
                    alert( "Introduce una cantidad menor a la cantidad en la venta." );
                }
                else if(parseInt( $(this).val() ) < 0){
                    $(this).val("0");
                    alert( "Introduce una cantidad válida." );
                }
                var thisself = $(this);
                var idVentaProductoDevolucion = $(this).parent().parent().parent().parent().find('.idVentaProductoDevolucion').text() ;
                $.ajax({
                    type: "GET",                                            
                    url: "ajax.php?c=caja&f=productosDevueltos",
                    data: {"id" : idVentaProductoDevolucion },                                          
                    timeout: 2000,   
                    dataType: 'json',                                       
                    beforeSend: function() {
                    },
                    complete: function() {
                    },
                    success: function(data) {
                        if(data.status == true && data.rows[0].devueltos != null) {
                            var disponibles = cantidad - data.rows[0].devueltos;
                            if ( disponibles <  thisself.val()) {
                                alert("Introduce una cantidad menor a " + disponibles +  " (productos disponibles para devolución)");
                                thisself.val(disponibles);
                            }
                        }
                        
                    },
                    error: function() {  
                        alert("Error al procesar productos en garantía");                                   
                    }
                });

            });

            $('#impuestosDiv').empty();
            $('.totalesDiv').empty();
            $('#pay').empty();
            var impuestosTotal = 0;
            $.each(data.taxes, function(index, val) {
                $('#impuestosDiv').append('<div class="row">'+
                            '<div class="col-sm-6"><label>'+index+':</label></div>'+
                            '<div class="col-sm-6"><label>$'+parseFloat(val).toFixed(2)+'</label></div>'+
                            '</div>');   
                impuestosTotal += parseFloat(val);
            });
            $('#subtotalDiv').append('<div class="row">'+
                            '<div class="col-sm-6"><h4>Subtotal:$'+Math.abs(parseFloat(data.total - impuestosTotal) ).toFixed(2)+'</h4></div>'+
                            '</div>');
            if(parseFloat(data.descuentoGeneral) > 0 ){
                $('#ddiv').append('<div class="row">'+
                            '<div class="col-sm-6"><h4>Descuento:$'+parseFloat(data.descuentoGeneral).toFixed(2)+'</h4></div>'+
                            '</div>');
            }
            $('#totalDiv').append('<div class="row">'+
                            '<div class="col-sm-6"><h4>Total:$'+parseFloat(data.total).toFixed(2)+'</h4></div>'+
                            '</div>');

            /*$('#inputSubTotal').val(data.cargos.subtotal);
            $('#inputTotal').val(data.cargos.total); */
            $.each(data.pay, function(index, val) {
                $('#pay').append('<div class="row">'+
                            '<div class="col-sm-6"><label>'+val.nombre+':</label></div>'+
                            '<div class="col-sm-6"><label>$'+parseFloat(val.monto).toFixed(2)+'</label></div>'+
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
        
    }
     function cancelaVenta(){
        var idVenta = $('#idVentaHidden').val();
        

        var r = confirm("Deseas cancelar la venta?");
        if (r == true) {
            mensaje('Procesando...');
            $.ajax({
                url: 'ajax.php?c=caja&f=cancelarVenta',
                type: 'POST',
                dataType: 'json',
                data: {idVenta: idVenta},
            })
            .done(function(resca) {
                console.log(resca);
                eliminaMensaje();
                if(resca.estatus==true){
                    alert('Se Cancelo la Venta');
                    
                    $('#modalVentasDetalle').modal('hide');
                   
                    ventasGrid();

                }
                else {
                    alert("No se puede cancelar esta venta porque tiene factura activa");
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
    function mensaje(mensaje) {

        $('#lblMensajeEstado').text(mensaje);
        $('#modalMensajes').modal({
                        show:true,
                        keyboard: false,
                    });
    }    
    function eliminaMensaje() {

        $('#modalMensajes').modal('hide');
    }
    function imprime(){
        var idVenta = $('#idVentaHidden').val();
        var imprimeTicket = confirm("Presiona el botón Aceptar para imprimir el TICKET,  presiona el botón Cancelar para imprimir el RECIBO DE PAGO");
        if (imprimeTicket == true) {
            window.open("../../modulos/pos/ticket.php?idventa=" +idVenta);
        } else {
            window.open("../../modulos/pos/reciboPdf.php?idventa=" +idVenta);
        }
    }
    function buscar(){
        var cliente = $('#cliente').val();
        var empleado = $('#empleado').val();
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var sucursal = $('#idSucursal').val();
        var via_contacto = $('#via_contacto').val();
        mensaje('Procesando...');
        graficar();
        $.ajax({
            url: 'ajax.php?c=caja&f=buscarVentas',
            type: 'POST',
            dataType: 'json',
            async : false,
            data: {cliente: cliente,
                    empleado : empleado,
                    desde: desde,
                    hasta: hasta,
                    sucursal: sucursal,
                    via_contacto: via_contacto
                },
        })
        .done(function(data) {
            console.log(data);
            var table = $('#tableSales').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var estatus = '';
            var monto = 0;
            var iva = 0;
            var total = 0;
            var docu = '';
            var xlink = '';
            var cad = '';
            $.each(data.ventas, function(index, val) {
                monto = parseFloat(val.monto);
                if(val.estatus=='Activa'){
                    estatus = '<span class="label label-success">Activa</span>';
                    total += parseFloat(monto.toFixed(2));  
                }else{
                    estatus = '<span class="label label-danger">Cancelada</span>';
                }

                if(val.documento==1){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                        docu = 'Ticket Facturado('+xlink+')';
                    }else{
                        docu = 'Ticket';
                    }

                    /*$.ajax({
                        url: 'ajax.php?c=caja&f=ventasFact',
                        type: 'POST',
                        dataType: 'json',
                        async : false,
                        data: {id: val.folio},
                    })
                    .done(function(resppp) {
                        console.log(resppp);
                        if(resppp.estatus==true){
                            //alert('kwkwk');
                            
                            cadi = atob(resppp.cade);
                            //alert(cadi);
                            cadi  =  JSON.parse(cadi);
                            if (typeof cadi.Basicos.folio !== 'undefined') {
                                xlink = '<a href="../../modulos/facturas/'+cadi.datosTimbrado.UUID+'.pdf" target="_blank">'+cadi.Basicos.folio+'</a>';
                                docu = 'Ticket Facturado('+xlink+')';
                            }else{
                                xlink = '<a href="../../modulos/facturas/'+cadi.datosTimbrado.UUID+'.pdf" target="_blank">'+cadi.Basicos.Folio+'</a>';
                                docu = 'Ticket Facturado('+xlink+')';
                            }
                            
                        }
                    })
                    .fail(function() {
                        console.log("error");
                    })
                    .always(function() {
                        console.log("complete");
                    });*/
                    if(val.cadenaOriginal2){
                            //alert('kwkwk');
                            
                            cadi = atob(val.cadenaOriginal2);
                            //alert(cadi);
                            cadi  =  JSON.parse(cadi);
                            if (typeof cadi.Basicos.folio !== 'undefined') {
                                xlink = '<a href="../../modulos/facturas/'+cadi.datosTimbrado.UUID+'.pdf" target="_blank">'+cadi.Basicos.folio+'</a>';
                                docu = 'Ticket Facturado('+xlink+')';
                            }else{
                                xlink = '<a href="../../modulos/facturas/'+cadi.datosTimbrado.UUID+'.pdf" target="_blank">'+cadi.Basicos.Folio+'</a>';
                                docu = 'Ticket Facturado('+xlink+')';
                            }
                            
                        }
                    
                    
                }else if(val.documento==2){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);
                        if (typeof cad.Basicos.folio !== 'undefined') {
                            xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                        }else{
                            xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.Folio+'</a>';
                        }       
                        
                    }else{
                        xlink = 'Pendiente';
                    }
                    docu = 'Factura('+xlink+')';
                }else if(val.documento==4){
                    docu = 'Recibo de pago';
                }else if(val.documento==5){
                    if(val.cadenaOriginal!=null){
                        cad = atob(val.cadenaOriginal);
                        cad  =  JSON.parse(cad);

                        xlink = '<a href="../../modulos/facturas/'+cad.datosTimbrado.UUID+'.pdf" target="_blank">'+cad.Basicos.folio+'</a>';
                    }else{
                        xlink = 'Pendiente';
                    }
                    docu = 'Recibo de Honorarios('+xlink+')';
                } 

                if(val.devoluciones != 0)
                    estatus += '<br> <span class="label label-warning" > Con devoluciones </span>';
                iva = parseFloat(val.iva);
                x ='<tr class="filas">'+
                                '<td>'+val.folio+'</td>'+
                                '<td>'+docu+'</td>'+
                                '<td>'+val.fecha+'</td>'+
                                '<td>'+val.cliente+'</td>'+
                                '<td>'+val.empleado+'</td>'+
                                '<td>'+val.sucursal+'</td>'+
                                '<td>'+estatus+'</td>'+
                                '<td>$'+iva.toFixed(2)+'</td>'+
                                '<td>$'+monto.toFixed(2)+'</td>'+
                                '<td>'+val.formas_pago+'</td>'+
                                '<td><button class="btn btn-primary btn-block" onclick="ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
                                '</tr>';  
                    table.row.add($(x)).draw();

                                         
            });    
            //alert(total);    
            total = parseFloat(total).toFixed(2); 
            $('#montoTotalLabel').text('$'+total);
            var prom = parseFloat(total).toFixed(2) / parseFloat(data.numTrans).toFixed(2); 
            if(isNaN(prom)){
                prom = 0.00;
            }
            if(data.numTrans==0){
                $('#gDonut').html('<h3 align="center">No hay datos</h3>')
                $('#gLine').html('<h3 align="center">No hay datos</h3>')
                $('#gDonutMenos').html('<h3 align="center">No hay datos</h3>')
            }
            $('#ticketPromedio').text('$'+parseFloat(prom).toFixed(2));
            $('#transacciones').text(data.numTrans);
        eliminaMensaje();
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    
    }
    function graficar(){

        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var orderby = $('#orden').val();
        var sucursal = $('#idSucursal').val();
        var cliente = $('#cliente').val();
        var empleado = $('#empleado').val();
        $('#gDonut').html('');
        $('#gLine').html('');
        $('#gDonutMenos').html('');
        $('#tab_graficas').addClass('in');
    
        //$('#graficasDiv').toggle();
        $.ajax({
            url: 'ajax.php?c=caja&f=graficar',
            type: 'POST',
            dataType: 'json',
            data: {desde: desde,
                    hasta : hasta,
                    orderby:orderby,
                    sucursal: sucursal,
                    cliente : cliente,
                    empleado : empleado
                },
        })
        .done(function(resp) {
            console.log(resp);

        Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.dona
        });

        Morris.Donut({
          element: 'gDonutMenos',
          resize: true,
          data: resp.donaMenos
        });


        Morris.Line({
          element: 'gLine',
          resize: true,
          data: resp.linea,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Vendido $']
        });


        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
    function seeGraphics(){
        $('#graficasDiv').show('slow');
    }


    function newCard(){
        $('#modDiv').hide();
        $('#modal-giftCard').modal();

          $('#disponible').val('');
            $('#numeroTarjeta').val('');
            $('#montoTarjeta').val('');
            $('#idGiftCard').val('');
            $('#usado').val('');
    }
    function saveGiftCard(){
        var idCard = $('#idGiftCard').val();
        var numero = $('#numeroTarjeta').val();
        var monto = $('#montoTarjeta').val();
        var puntos = $('#puntos').val();
        var cliente = $('#cliente').val();

        $.ajax({
            url: 'ajax.php?c=caja&f=guardarTarjeta',
            type: 'POST',
            dataType: 'json',
            data: {idCard: idCard,
                    numero : numero,
                    monto : monto,
                    puntos: puntos,
                    cliente: cliente
                },
        })
        .done(function(resp) {
            console.log(resp);
            if(resp.idTarjeta > 0){
                alert('Se guardo la tarjeta exitosamente.');
                window.location.reload();
            }else{
                alert('Ya existe una tarjeta con ese numero.');
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }
    function desactivaGift(idGiftCard){

        var r = confirm("Deseas desactivar la tarjeta?");
        if (r == true) {
            mensaje('Procesando...');
            $.ajax({
                url: 'ajax.php?c=caja&f=desactivaGift',
                type: 'POST',
                dataType: 'json',
                data: {idGiftCard: idGiftCard},
            })
            .done(function(resca) {
                console.log(resca);
                eliminaMensaje();
                if(resca.estatus==true){
                    alert('Se desactivo la tarjeta.');
                    
                 window.location.reload();
                   
                    

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
       function activaGift(idGiftCard){

        var r = confirm("Deseas activar la tarjeta?");
        if (r == true) {
            mensaje('Procesando...');
            $.ajax({
                url: 'ajax.php?c=caja&f=activaGift',
                type: 'POST',
                dataType: 'json',
                data: {idGiftCard: idGiftCard},
            })
            .done(function(resca) {
                console.log(resca);
                eliminaMensaje();
                if(resca.estatus==true){
                    alert('Se activo la tarjeta.');
                    
       
                    window.location.reload();
                    //ventasGrid();

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
    function modificaGiftCard(idCard){

        $.ajax({
            url: 'ajax.php?c=caja&f=verTarjeta',
            type: 'POST',
            dataType: 'json',
            data: {idCard: idCard},
        })
        .done(function(resp) {
            console.log(resp);
            $('#disponible').val(resp.disponible);
            $('#numeroTarjeta').val(resp.numero);
            $('#montoTarjeta').val(resp.monto);
            $('#idGiftCard').val(resp.id);
            $('#usado').val(resp.montousado);

            $('#modal-giftCard').modal();
            $('#modDiv').show(); 
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
        
    }

     function detalleMovimientoDevolucion (idVentaProducto) {
    $('#modalDetalleMovimientoDevolucion').modal({
        show:true,
    });
    $('#idMovimientoDevolucionProducto').text( "Movimientos de venta, producto:" + idVentaProducto );

    $.ajax({
        type: "GET",                                            
        url: "ajax.php?c=caja&f=detalleMovimientoDevueltos", 
        data: { "idVentaProducto" : idVentaProducto }, 
        dataType : 'json',                                       
        timeout: 1500,                                          
        beforeSend: function(data) {                                 
        },
        complete: function(data) {                  
        },
        success: function(data) { 
            $('#tablaMovimientosDevolucion').empty();

            $.each( data['rows'], function(index, value) {

                var fila = `
                <tr>
                    <th>` + value.codigo + `</th>
                    <th>` + value.nombre + `</th>
                    <th class="cantidadProductos">` + value.cantidad +  `</th>
                    <th>` + value.almacen + `</th>
                    <th>` + value.comentario + `</th>
                    <th>` + value.fecha + `</th>
                </tr>
                `; 
                $('#tablaMovimientosDevolucion').append(fila);
            }); 

        },
        error: function() {                                     
            alert("Error al cargar tabla de devoluciones");
        }
    }); 
}


 function devolverVenta () {
    var datos = obtenerDatosDevolucion();
        console.log(datos);
        var continuar = true;
        if(datos.comentario == "") {
            alert("Inserta un comentario");
            continuar = false;
        }
        if(datos.tablaVentaProducto.length == 0) {
            alert("La cantidad de productos a reclamar debe ser mayor a cero");
            continuar = false;
        }

    if ( $('#idAlmacenDevolucion').val() == "0" ) {
        if (continuar) {
            console.log(datos);
        //saveMerma();
            $.ajax({
                url: 'ajax.php?c=caja&f=guardaMerma',
                type: 'POST',
                data: datos,                                          
                timeout: 2000,   
                dataType: 'json',
                beforeSend: function() {
                    mensaje("Procesando . . . ")
                },
                complete: function() {
                    eliminaMensaje();
                },
                success: function(data) {
                    if(data.estatus == true){
                        console.log(data);
                        if(data.idMerma!=0){
                            alert("Tu devolución a merma se ha procesado exitosamente");
                            $('#idFacPanel').text("");
                            $('#idAlmacenDevolucion').val("1");
                            $('#idComentarioDevolucion').val("");
                            $('#tablaVenta').empty();
                            $('#modalVentasDetalle').modal('hide');
                        }

                        
                    }
                    else
                        alert("Hubó un error al procesar tu devolución");
                },
                error: function() {  
                    alert("Error al procesar tu devolución");                                   
                }
            });
        }
        
    }
    else {
        

        if(continuar) {
            $.ajax({
                type: "POST",                                            
                url: "ajax.php?c=caja&f=devolucion",
                data: datos,                                          
                timeout: 2000,   
                dataType: 'json',                                       
                beforeSend: function() {
                    mensaje("Procesando . . . ")
                },
                complete: function() {
                    eliminaMensaje();
                },
                success: function(data) {

                    if(data.status == true){
                        alert("Tu devolución se ha procesado exitosamente");
                        $('#idFacPanel').text("");
                        $('#idAlmacenDevolucion').val("1");
                        $('#idComentarioDevolucion').val("");
                        $('#tablaVenta').empty();
                        $('#modalVentasDetalle').modal('hide');
                    }
                    else
                        alert("Hubó un error al procesar tu devolución");
                },
                error: function() {  
                    alert("Error al procesar tu devolución");                                   
                }
            });
        }
        else {
            //alert("Verifica que todos los campos esten correctamente");
        }
    }
    

}

 function obtenerDatosDevolucion (){
    var datos = { };

    datos.idVenta = $('#idFacPanel').text();
    datos.idAlmacen = $('#idAlmacenDevolucion').val();
    datos.comentario = $('#idComentarioDevolucion').val();
    datos.subtotal = 0.0;
    datos.total = 0.0;

    datos.tablaVentaProducto = [];
    $('tbody#tablaVenta tr').each( function() {
        let temp = {  };
        temp.idVentaProducto = $(this).children(':nth-child(9)').text();
        temp.codigo = $(this).children(':nth-child(1)').text();
        temp.nombre = $(this).children(':nth-child(2)').text();
        temp.cantidad = $(this).children(':nth-child(7)').find('input').val();
        temp.precio = parseFloat( $(this).children(':nth-child(4)').text().substring(1) );
        temp.idVentaProducto = parseFloat( $(this).children(':nth-child(9)').text() );

        datos.subtotal = parseFloat( $(this).children(':nth-child(4)').text().substring(1) ) * parseInt( $(this).children(':nth-child(7)').find('input').val() ) ;
        datos.total = parseFloat( $(this).children(':nth-child(5)').text().substring(1) ) / parseFloat( $(this).children(':nth-child(3)').text() ) * parseInt( $(this).children(':nth-child(7)').find('input').val() ) ;
        if ( temp.cantidad != "0" )
            datos.tablaVentaProducto.push( temp );
    });
    datos.total += datos.subtotal;

    return datos;
}

function newPolitic(){
    
    $('#giftCardPolitics').modal();
}
function savePolitic(){

    var tipo = $('#tipoCard').val();
    var dinero = $('#money').val();
    var porcentaje = $('#percent').val();
    var puntos = $('#points').val();
    var nombreP = $('#namePolitic').val();

    $.ajax({
        url: 'ajax.php?c=caja&f=guardarPolitica',
        type: 'POST',
        dataType: 'json',
        data: {tipo: tipo,
                dinero: dinero,
                porcentaje:porcentaje,
                puntos:puntos,
                nombreP : nombreP
            },
    })
    .done(function(al) {
        console.log(al);
        if(al.id>0){
            alert('Se guardo con exito');
            $('#giftCardPolitics').modal('hide');
            $('#namePolitic').val('');
            $('#money').val('');
            $('#points').val('');
            $('#percent').val('');
        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    

}
function clienteAddButton(){
    $('#modalCliente').modal({
                show:true,
            });
}
function guardaCliente(){
        var idCliente =  $('#idCliente').val();
        var codigo =  $('#codigo').val();
        var nombre =  $('#nombre').val();
        var tienda =  $('#tienda').val();
        var numint =  $('#numint').val();
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

        var razonSocial = $('#razonSocial').val();
        var regimen = $('#regimen').val();
        var ciudad = $('#cdF').val();

    
        $.ajax({
            url: 'ajax.php?c=cliente&f=guardaCliente',
            type: 'POST',
            dataType: 'json',
            data: {idCliente: idCliente,
                    codigo : codigo,
                    nombre : nombre,
                    tienda : tienda,
                    numint : numint,
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
                    regimenFact: regimen,
                    razonSocial : razonSocial,
                    ciudadFact : ciudad,
                    flag : 2

                    },
        })
        .done(function(data) {
            console.log(data);
            if(data.idClienteInser!=''){
                $('#cliente').append('<option value="'+data.idClienteInser+'">'+data.nombre+'</option>');
                $('#cliente > option[value="'+data.idClienteInser+'"]').attr('selected', 'selected');
                $('#cliente').select2({ width: '100%' });
                $('#modalSuccess').modal({
                    show:true,
                });
            }else{
                alert('Erro 1540 - Comunicate con el area de consultoria.');
            }
        })
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });

    }
     function municipiosF(){
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
    }
    function cierramodales(){
        $('#modalSuccess').modal('hide');
        $('#modalCliente').modal('hide');
        caja.init();
    }



