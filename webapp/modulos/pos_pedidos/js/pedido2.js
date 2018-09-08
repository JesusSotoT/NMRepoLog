
function createnewP(){
   var pathname = window.location.pathname;
    $("#tb2174-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=caja&f=indexPedido2');
    
}
function backbuttonP(){
  
    var pathname = window.location.pathname;
    $("#tb2174-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=indexGridPedidosCliente');
}
function buscaP(){
  var cliente = $('#cotiClienteP').val();
  var empleado = $('#cotiEmpleadoP').val();
  var desde = $('#desde').val();
  var hasta = $('#hasta').val();

  $.ajax({
    url: 'ajax.php?c=pedido&f=buscaP2',
    type: 'POST',
    dataType: 'json',
    data: {cliente: cliente,desde:desde,hasta:hasta},
  })
  .done(function(data) {
    console.log(data);
               
                var cotizacion='';
                var perfilBo = '';


                var table = $('#tabliGriP').DataTable();
    
            //$('.rows').remove();
            
                 
                 table.clear().draw();
                 var x ='';
                $.each(data.pedidos, function(index, val) {
                    if(val.idCotizacion==null){
                      cotizacion='';
                    }else{
                      cotizacion=val.idCotizacion;
                    }



                       switch(val.status) {
                        case '0':
                            estado = '<a class="btn btn-danger">Cancelado</a>';
                            break;
                        case '1':
                            estado = '<a class="btn btn-default">Activo</a>';
                            break;
                        case '2':
                            estado = '<a class="btn btn-warning">Proceso</a>';
                            break;
                        case '3':
                            estado = '<a  class="btn btn-primary">Terminado</a><a class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                            break;
                        case '4':
                            estado = '<a  class="btn btn-info">En Venta</a><a class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                            break;  
                        case '5':
                            estado = '<a class="btn btn-success">Vendido</a>';
                            break;

                    }
                    x = '<tr class="trtablitaGridP">'+
                        '<td><a href="index.php?c=pedido&f=pedidoView2&pe='+val.id+'">'+val.id+'</a></td>'+
                        '<td><a href="index.php?c=pedido&f=pedidoView2&pe='+val.id+'">$'+parseFloat(val.total).toFixed(2)+'</a></td>'+
                        '<td><a href="index.php?c=pedido&f=pedidoView2&pe='+val.id+'">'+val.fecha+'</a></td>'+
                        '<td><a onclick="FunPdf('+val.id+');" class="btn btn-default"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>'+
                        '<td>'+estado+perfilBo+
                        '</td>'+
                        '</tr>'; 
                        perfilBo = '';
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

function loadPedido(idPedido){
        $.ajax({
          url: 'ajax.php?c=pedido&f=pedidoView',
          type: 'POST',
          dataType: 'json',
          data: {idPedido: idPedido},
        })
        .done(function(data) {
        /*  var pathname = window.location.pathname;
          $("#tb2175-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=pedidoView1');
           */
           $('#cantidad').val(12);
          ////Select Productos
              $.each(data , function(index, value) {
                var optionProduct = $(document.createElement('option')).attr({'value': value.idProducto}).html(value.nombre).appendTo($('#selectProductP'));
              });

        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
    
}
function FunPdf(id){
  window.open("../../modulos/cotizaciones/cotizacionesPdf/pedido_"+id+".pdf");
}





