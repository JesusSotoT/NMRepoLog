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
                                '<td>$'+val.iva+'</td>'+
                                '<td>$'+val.monto+'</td>'+
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
        window.open("../../modulos/pos/ticket.php?idventa=" +idVenta);
    }
    function buscar(){
        var cliente = $('#cliente').val();
        var empleado = $('#empleado').val();
        var desde = $('#desde').val();
        var hasta = $('#hasta').val();
        mensaje('Procesando...')
        graficar();
        $.ajax({
            url: 'ajax.php?c=caja&f=buscarVentas',
            type: 'POST',
            dataType: 'json',
            data: {cliente: cliente,
                    empleado : empleado,
                    desde: desde,
                    hasta: hasta
                },
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
                                '<td>$'+val.iva+'</td>'+
                                '<td>$'+val.monto+'</td>'+
                                '<td><button class="btn btn-primary btn-block" onclick="ventaDetalle('+val.folio+');" type="button"><i class="fa fa-list-ul"></i> Detalle</button></td>';
                                '</tr>';  
                    table.row.add($(x)).draw();                          
            });         
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
        $('#gDonut').html('');
        $('#gLine').html('');
        $('#tab_graficas').addClass('in');
    
        //$('#graficasDiv').toggle();
        $.ajax({
            url: 'ajax.php?c=caja&f=graficar',
            type: 'POST',
            dataType: 'json',
            data: {desde: desde,
                    hasta : hasta
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

