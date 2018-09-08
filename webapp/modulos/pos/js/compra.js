
function buscaProductos(){

    var idPrv = $('#proveedor').val();
    var idAlmacen = $('#almacen').val();
        $.ajax({
            url: 'ajax.php?c=compra&f=productosProveedor',
            type: 'POST',
            dataType: 'json',
            data: {idPrv:idPrv,
                   idAlmacen:idAlmacen,
                },
        })
        .done(function(data) {
            console.log(data);  
            var table = $('#proTable').DataTable();
    
            //$('.filas').empty();
            table.clear().draw();
            var x ='';
            $.each(data, function(index, val) {

                x ='<tr idProducto="'+val.id+'" class="filas">'+
                                '<td>'+val.codigo+'</td>'+
                                '<td>'+val.nombre+'</td>'+
                                '<td><input type="hidden" id="unidad_'+val.id+'" value="'+val.idUnidadC+'">'+val.unidad+'</td>'+
                                '<td><input class="cantidadPro" type="text" id="cant_'+val.id+'"/ onkeyup="calculaPrecios(1);"></td>'+
                                '<td>$<input class="costoPro" type="text" id="cost_'+val.id+'" value="'+val.costo+'" onkeyup="calculaPrecios(1);"/></td>'+
                                '</tr>';  
                    table.row.add($(x)).draw();                          
            }); 
          
            //var rowsTable = x;
            //table.row.add($(rowsTable)).draw();
        }) 
        .fail(function() {
            console.log("error");
        })
        .always(function() {
            console.log("complete");
        });
    
}
function limpiar(){
    $('.cantidadPro').val('');
}
function newOrder(){

        
}
function buscaProd(){
    alert('holliiiiii');
}
function guardar(tipo){
    $('#guardaDiv').hide();
    $('#sded').show();

    var idOrden = $('#ordenCompra').val();
    var idProvedor = $('#proveedor').val();
    //var idAlmacen = $('#almacen').val();
    var idAlmacen = $('#almacen').val();
    var fecha_entrega = $('#fecha_entrega').val();
    var subTotal = $('#inputSubTotal').val();
    var total = $('#inputTotal').val();
    var user = $('#autorizo').val();

    var productos = '';
    var contador = 0;
    var error = 0;


    if(tipo==1){
        var oTable = $('#proTable').dataTable();
        var allPages = oTable.fnGetNodes();

        $(allPages).each(function (index) 
        {   //console.log($("#tablita input:hidden"));
            
            contador++;
            idProducto = $(this,allPages).attr('idProducto');
            cantidad = $('#cant_'+idProducto, allPages).val();
            if(cantidad < 1 && cantidad!=''){
               
                error = 1;

            }
            precio = $('#cost_'+idProducto, allPages).val();
            if(cantidad!=''){
                productos +=idProducto+'-'+cantidad+'-'+precio+'/';
            }

        });
    }else{

        $('#proTable tr').each(function (index) 
        {   //console.log($("#tablita input:hidden"));
            
            contador++;
            idProducto = $(this).attr('idProducto');
            cantidad = $('#cant_'+idProducto).val();
            if(cantidad < 1 && cantidad!=''){
               
                error = 1;

            }
            precio = $('#cost_'+idProducto).val();
            if(cantidad!=''){
                productos +=idProducto+'-'+cantidad+'-'+precio+'/';
            }

        });
    }

    if(error==1){
        alert('La cantidad a pedir debe ser mayor a cero.');
        $('#guardaDiv').show();
        $('#sded').hide();
        return false;
    }
    if(productos==''){
        alert('No existen productos en la compra, agregalos.');
        $('#guardaDiv').show();
        $('#sded').hide();
        return false;
    }

    $.ajax({
        url: 'ajax.php?c=compra&f=guardaOrden',
        type: 'POST',
        dataType: 'json',
        data: {idProvedor: idProvedor, 
               idAlmacen: idAlmacen,
               productos: productos,
               fecha_entrega : fecha_entrega,
               idOrden : idOrden,
               subTotal : subTotal,
               total : total,
               user : user
           },
    })
    .done(function(data) {
        console.log(data);

        if(data.status==true){
            $('#myModal').modal({
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
    

}
function redireccion(){
    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=compra&f=indexGrid';   
}
/////producto 

function agregaProve(){
    var idProve = $('#proveedor').val();  
    var nombre = $('#proveedor').text();  
    var precio = 200;
    
    $('#provesList tr:last').after('<tr><td><span class="glyphicon glyphicon-remove-circle"></span></span></td><td>'+nombre+'</td><td>'+precio+'</td></tr>');



}
function adiciona(){
    $('#prodExtras').toggle('slow');
}
function agregaMasProd(){

    loadingModal();

    var idProducto = $('#productosExtras').val();
    var cantidad = $('#cant').val();
    var precio = $('#price').val();

    if(precio ==''){
        alert('Debes de Agregar un Precio.');
        return;
    }
    if(cantidad ==''){
        alert('Debes de Agregar una cantidad mayor a cero.');
        return;
    }

    $.ajax({
        url: 'ajax.php?c=compra&f=agregaMasProd',
        type: 'POST',
        dataType: 'json',
        data: {idProducto : idProducto,
                cantidad : cantidad,
                precio : precio,
            },
    })
    .done(function(data) {
        //alert('334');
        console.log(data);
        $('#proTable tr:last').after('<tr idProducto="'+data[0].id+'" id="x_'+data[0].id+'">'+
                                '<td><span class="glyphicon glyphicon-remove" onclick="elimina('+data[0].id+');"></span></td>'+
                                '<td>'+data[0].codigo+'</td>'+
                                '<td>'+data[0].nombre+'</td>'+
                                //'<td>'+cantidad+'</td>'+
                                //'<td><input id="ordenado_'+data[0].id+'" type="text" value="'+cantidad+'"></td>'+
                                '<td><input id="cant_'+data[0].id+'" type="text" value="'+cantidad+'" onkeyup="calculaPrecios(2)"></td>'+
                                '<td>$<input id="cost_'+data[0].id+'" type="text" value="'+precio+'" onkeyup="calculaPrecios(2)"></td>'+
                                //'<td>$'+precio+'</td>'+
                                '<td><label id="subto_'+data[0].id+'">$'+(precio * cantidad)+'</label></td>'+
                                '</tr>');
        calculaPrecios(2);
        $('#cant').val('');
        $('#price').val('');
        cierraModal();

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function elimina(id){
    //alert('i');
    $('#x_'+id).remove();
}
function recibir(){
    /*alert('Mercancia Recibida');

    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=compra&f=indexgrid'; */


    $('#guardaDiv').hide();
    $('#sded').show(); 
    var idOrden = $('#ordenCompra').val();
    var idProvedor = $('#proveedor').val();
    //var idAlmacen = $('#almacen').val();
    var idAlmacen = $('#idAlmacen').val();
    var fecha_entrega = $('#fecha_entrega').val();
    var fecha_factura = $('#fecha_factura').val();
    var factura = $('#factura').val();
    var observaciones = $('#observaciones').val();
    var facturaImporte = $('#facturaImporte').val();
    var productos = '';
    var contador = 0;
    var error  = 0;
    $("#proTable tr").each(function (index) 
    {   //console.log($("#tablita input:hidden"));
        recibido = $(this).attr('recib');
        if(recibido==0){
            contador++;
            idProducto = $(this).attr('idProducto');
            cantidad = $('#cant_'+idProducto).val();
            precio = $('#cost_'+idProducto).val();
            ordenado = $('#ordenado_'+idProducto).val();
            if(cantidad < 1 && cantidad!=''){
               
                error = 1;

            }
            if(cantidad!=''){
                productos +=idProducto+'-'+ordenado+'-'+cantidad+'-'+precio+'/';
            }
        }


    });
    
    if(error ==1){
        alert('No puedes recibir cantidades menores a cero');
        return false;
    }
    $.ajax({
        url: 'ajax.php?c=compra&f=recibeOrden',
        type: 'POST',
        dataType: 'json',
        data: {idProvedor: idProvedor, 
               idAlmacen: idAlmacen,
               productos: productos,
               fecha_entrega : fecha_entrega,
               idOrden : idOrden,
               factura : factura,
               observaciones : observaciones,
               facturaImporte : facturaImporte,
               fecha_factura : fecha_factura,
           },
    })
    .done(function(data) {
        console.log(data);

        if(data.status==true){
            $('#modalRecepcion').modal({
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
    
}


function newOrder(){

    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=compra&f=index';

}
function back(){
    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=compra&f=indexgrid';
}
function back2(){
    var pathname = window.location.pathname;
    window.location = window.location.protocol + '//'+document.location.host+pathname+'?c=compra&f=recepcionGrid';
}
function calculaPrecios(tipo){
var subtotal = 0;
var total = 0;
var productos = '';
var x = 0;


    if(tipo==1){
        var oTable = $('#proTable').dataTable();
        var allPages = oTable.fnGetNodes();
        console.log(allPages);
        $(allPages).each(function (index) 
        {   //console.log($("#tablita input:hidden"));
            //alert('alertoeoeoeoeoeoe');
            //contador++;
            idProducto = $(this, allPages).attr('idProducto');
            //alert(idProducto);
            cantidad = $('#cant_'+idProducto, allPages).val();
            precio = $('#cost_'+idProducto, allPages).val();
            x = precio * cantidad;
            $('#subto_'+idProducto, allPages).text('$'+parseFloat(x).toFixed(2));
            if(cantidad > 0){
               
                subtotal = parseFloat(precio) * parseFloat(cantidad);
                productos +=idProducto+'-'+cantidad+'-'+precio+'/';
            }

            total +=parseFloat(subtotal);
            subtotal = 0;
        });
    }else{
        //var oTable = $('#proTable').dataTable();
        //var allPages = oTable.fnGetNodes();
        //console.log(allPages);
        $('#proTable tr').each(function (index) 
        {   //console.log($("#tablita input:hidden"));
            //alert('alertoeoeoeoeoeoe');
            //contador++;
            idProducto = $(this).attr('idProducto');
            //alert(idProducto);
            cantidad = $('#cant_'+idProducto).val();
            precio = $('#cost_'+idProducto).val();
            x = precio * cantidad;
            $('#subto_'+idProducto).text('$'+parseFloat(x).toFixed(2));
            if(cantidad > 0){
               
                subtotal = parseFloat(precio) * parseFloat(cantidad);
                productos +=idProducto+'-'+cantidad+'-'+precio+'/';
            }

            total +=parseFloat(subtotal);
            subtotal = 0;
        });
    }
    //alert(productos);
    $.ajax({
        url: 'ajax.php?c=compra&f=calculaPrecios',
        type: 'POST',
        dataType: 'json',
        data: {productos: productos},
    })
    .done(function(data) {
        console.log(data);
        $('#impuestosDiv').empty();
        $('.totalesDiv').empty();
        $.each(data.cargos.impuestosPorcentajes, function(index, val) {
            $('#impuestosDiv').append('<div class="row">'+
                        '<div class="col-sm-6"><label>'+index+':</label></div>'+
                        '<div class="col-sm-6"><label>$'+parseFloat(val).toFixed(2)+'</label></div>'+
                        '</div>');   
        });
        $('#subtotalDiv').append('<div class="row">'+
                        '<div class="col-sm-6"><h4>Subtotal:$'+parseFloat(data.cargos.subtotal).toFixed(2)+'</h4></div>'+
                        '</div>');
        $('#totalDiv').append('<div class="row">'+
                        '<div class="col-sm-6"><h4>Total:$'+parseFloat(data.cargos.total).toFixed(2)+'</h4></div>'+
                        '</div>');

        $('#inputSubTotal').val(parseFloat(data.cargos.subtotal).toFixed(2));
        $('#inputTotal').val(parseFloat(data.cargos.total).toFixed(2));
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
    $('#totalOrden').val(parseFloat(total).toFixed(2));
    $('#totalOrdenLable').text(parseFloat(total).toFixed(2));
}
function modalRecep(lotes,series,pedimentos){
    //alert('lotes='+lotes+' Series='+series+' pedimentos='+pedimentos);
    /*    var ht = '<div>'+
      '<div class="row">'+
          '<div class="col-sm-6">'+
              '<label>Computadora hp All in One</label>'+
          '</div>'+
          '<div class="col-sm-6"></div>'+
      '</div>'+
      '<div class="row">'+
          '<div class="col-sm-6"></div>'+
      '</div>'+
      '<div class="row">'+
          '<div class="col-sm-6">'+
              '<label>Ordenadao:</label>'+
          '</div>'+
          '<div class="col-sm-6">'+
              '<label>10</label>'+
          '</div>'+
      '</div>'+
      '<div class="row">'+
          '<div class="col-sm-6">'+
              '<label>Recibido:</label>'+
          '</div>'+
         ' <div class="col-sm-6">'+
              '<input type="text" class="form-control" id="cantidadSeries">'+
          '</div>'+
      '</div>'+
      '<div class="row">'+
          '<div class="col-sm-6"></div>'+
          '<div class="col-sm-6">'+
              '<button class="btn btn-default" onclick="addSerie();">Agrega Serie</button>'+
          '</div>'+
      '</div>'+
      '<div class="row"><div id="seriesList"></div></div>'+
  '</div>';
    $('#recepcion').html(ht);
        $('#modalRecepcion').modal({
            show:true,
        });   */
}
function addSerie(){
    var idProducto = 10;
    var numero = $('#cantidadSeries').val();
    //numero =  numero;
    for (var i = 0; i < numero ; i++) {
        $('#seriesList').append('<div><div class="col-sm-6"><label>No. Serie '+i+'</label></div><div class="col-sm-6"><input type="text" class="form-control" id="s_'+idProducto+'_'+i+'"></div></div>');   
    };
}
 function cierraModal(){
    $('#modalMensajes').modal('toggle');
 }
 function loadingModal(){
    $('#modalMensajes').modal({
        show:true,
    });
 }
 function agregaMasProdRecepcion(){

    loadingModal();

    var idProducto = $('#productosExtras').val();
    var cantidad = $('#cant').val();
    var precio = $('#price').val();

    if(precio ==''){
        alert('Debes de Agregar un Precio.');
        return;
    }
    if(cantidad ==''){
        alert('Debes de Agregar una cantidad mayor a cero.');
        return;
    }

    $.ajax({
        url: 'ajax.php?c=compra&f=agregaMasProd',
        type: 'POST',
        dataType: 'json',
        data: {idProducto : idProducto,
                cantidad : cantidad,
                precio : precio,
            },
    })
    .done(function(data) {
        //alert('334');
        console.log(data);
        $('#proTable tr:last').after('<tr idProducto="'+data[0].id+'" id="x_'+data[0].id+'">'+
                                //'<td><span class="glyphicon glyphicon-remove" onclick="elimina('+data[0].id+');"></span></td>'+
                                '<td>'+data[0].codigo+'</td>'+
                                '<td>'+data[0].nombre+'</td>'+
                                //'<td>'+cantidad+'</td>'+
                                '<td><input id="ordenado_'+data[0].id+'" type="text" value="'+cantidad+'"></td>'+
                                '<td><input id="cant_'+data[0].id+'" type="text" value="'+cantidad+'" onkeyup="calculaPrecios(2)"></td>'+
                                '<td>$<input id="cost_'+data[0].id+'" type="text" value="'+precio+'" onkeyup="calculaPrecios(2)"></td>'+
                                //'<td>$'+precio+'</td>'+
                                '<td><label id="subto_'+data[0].id+'">$'+parseFloat((precio * cantidad)).toFixed(2)+'</label></td>'+
                                '</tr>');
        calculaPrecios(2);
        $('#cant').val('');
        $('#price').val('');
        cierraModal();

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}









