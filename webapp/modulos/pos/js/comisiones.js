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
            $('#idFacPanel').text('Venta '+id);
            $('#idVentaHidden').val(id);
            $('.rowsSale').remove();

            var descDesc = '';

            $.each(data.products, function(index, val) {
                    if(val.montodescuento > 0){
                        descDesc  = '[Precio:$'+parseFloat(val.precio).toFixed(2)+',Descuento:$'+parseFloat(val.montodescuento).toFixed(2)+'/'+val.tipodescuento+''+val.descuento+']';
                    }
                    $('#tableSale tr:last').after('<tr class="rowsSale" id="detalle_'+val.id+'">'+
                                    '<td>'+val.codigo+'</td>'+
                                    '<td>'+val.nombre+descDesc+'</td>'+
                                    '<td align="center">'+val.cantidad+'</td>'+
                                    '<td>$'+parseFloat(val.preciounitario).toFixed(2)+'</td>'+
                                    /*'<td>$'+val.montodescuto+'</td>'+ */
                                    '<td>$'+parseFloat(val.impuestosproductoventa).toFixed(2)+'</td>'+
                                    '<td>$'+parseFloat(val.total).toFixed(2)+'</td>'+
                                    '</tr>');
                    descDesc = '';
            }); 

            $('#impuestosDiv').empty();
            $('.totalesDiv').empty();
            $('#pay').empty();
            $.each(data.taxes, function(index, val) {
                $('#impuestosDiv').append('<div class="row">'+
                            '<div class="col-sm-6"><label>'+index+':</label></div>'+
                            '<div class="col-sm-6"><label>$'+parseFloat(val).toFixed(2)+'</label></div>'+
                            '</div>');   
            });
            $('#subtotalDiv').append('<div class="row">'+
                            '<div class="col-sm-6"><h4>Subtotal:$'+parseFloat(data.total).toFixed(2)+'</h4></div>'+
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
                    alert('Se canelo la Venta');
                    
                    $('#modalVentasDetalle').modal('hide');
                   
                    ventasGrid();

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
        var empleado = $('#empleado').val();
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var sucursal = $('#idSucursal').val();
        mensaje('Procesando...');
        graficar();
        $.ajax({
            url: 'ajax.php?c=caja&f=buscarComisiones',
            type: 'POST',
            dataType: 'json',
            data: {
                    empleado : empleado,
                    desde: desde,
                    hasta: hasta,
                    sucursal: sucursal,
                },
        })
        .done(function(data) {
            console.log(data);
            var table = $('#tableSales').DataTable();
    
            //$('.rows').remove();
            
            table.clear().draw();
         
            var x ='';
            var total = 0;
            $.each(data.ventas, function(index, val) {
                if ( val.tipo_comision == "1")
                    tipoComision = "Subtotal";
                else if ( val.tipo_comision == "2" )
                    tipoComision = "Utilidad";
                else 
                    tipoComision = "Ninguna";
                x = `<tr class="rows">
                        <td>${tipoComision}</td>
                        <td>${val.sucursal}</td>
                        <td>${val.empleado}</td>
                        <td>${val.producto}</td>
                        <td>${val.cantidad}</td>
                        <td style="text-align: right;">$ ${parseFloat(val.total_neto).toFixed(2)}</td>
                        <td>${val.porcentaje_comision ? val.porcentaje_comision : ""}</td>
                        <td style="text-align: right;">$ ${parseFloat(val.total_comision).toFixed(2)}</td>
                    </tr>`;
                    total+= parseFloat( val.total_comision );
                    table.row.add($(x)).draw();

                                         
            });    

            $('#montoTotalLabel').text('$'+parseFloat(total).toFixed(2));
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
        var empleado = $('#empleado').val();
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        var sucursal = $('#idSucursal').val();
        $('#gDonut').html('');
        $('#gLine').html('');
        $('#tab_graficas').addClass('in');
    
        //$('#graficasDiv').toggle();
        $.ajax({
            url: 'ajax.php?c=caja&f=graficarComision',
            type: 'POST',
            dataType: 'json',
            data: {desde: desde,
                    hasta : hasta,
                    sucursal: sucursal
                },
        })
        .done(function(resp) {
            console.log(resp);

        Morris.Donut({
          element: 'gDonut',
          resize: true,
          data: resp.dona
        });

        Morris.Line({
          element: 'gLine',
          resize: true,
          data: resp.linea,
          xkey: 'y',
          ykeys: ['a'],
          labels: ['Comisión $']
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

        $.ajax({
            url: 'ajax.php?c=caja&f=guardarTarjeta',
            type: 'POST',
            dataType: 'json',
            data: {idCard: idCard,
                    numero : numero,
                    monto : monto
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


