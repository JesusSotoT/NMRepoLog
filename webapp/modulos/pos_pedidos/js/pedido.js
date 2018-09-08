function createnewP(){
   var pathname = window.location.pathname;
    $("#tb2175-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=caja&f=indexPedido');
    
}
function backbuttonP(){
  
    var pathname = window.location.pathname;
    $("#tb2175-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=imprimeGridP');
}
function buscaP(){
  
  var cliente = $('#cotiClienteP').val();
  var empleado = $('#cotiEmpleadoP').val();
  var desde = $('#desde').val();
  var hasta = $('#hasta').val();
  
  $.ajax({
    url: 'ajax.php?c=pedido&f=buscaP',
    type: 'POST',
    dataType: 'json',
    data: {cliente: cliente, empleado:empleado,desde:desde,hasta:hasta},
  })
  .done(function(data) {
    console.log(data);
                $('.trtablitaGridP').remove();
                var cotizacion='';
                var perfilBo = '';
                var estado = '';
                var link = '';
                var table = $('#tabliGriP').DataTable();
                table.clear().draw();
                var x ='';
                data.pedidos = data.pedidos.reverse();
                $.each(data.pedidos, function(index, val) {
                  
                    if(val.idCotizacion==null){
                      cotizacion='';
                    }else{
                      cotizacion=val.idCotizacion;
                    }

                    switch(val.status) {
                        case '0':
                            estado = '<span class="label label-danger">Cancelado</span>';
                            link = '#';
                            break;
                        case '1':
                            estado = '<a onclick="aProceso('+val.id+');" class="btn btn-default">Activo</a><a onclick="cancelar('+val.id+');" class="btn btn-default">Cancelar</a>';
                            link = 'index.php?c=caja&f=indexPedido2&pe='+val.id+'';
                            break;
                        case '2':
                            estado = '<a onclick="aTerminado('+val.id+');" class="btn btn-warning">Proceso</a>';
                            link = 'index.php?c=caja&f=indexPedido2&pe='+val.id+'';
                            break;
                        case '3':
                            estado = '<span class="label label-primary">Terminado</span><a onclick="pedido('+val.id+');" class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                            link = '#';;
                            break;
                        case '4':
                            estado = '<span class="label label-info">En Venta</span><a onclick="pedido('+val.id+');" class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                            link = '#';
                            break;  
                        case '5':
                            estado = '<span class="label label-success">Vendido</span>';
                            link = '#';
                            break;

                    }

                   /* if(val.status>2){
                      if(val.status==3 || val.status==4){
                         perfilBo = '<a onclick="pedido('+val.id+');" class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                      }

                    } */

                    x = '<tr class="trtablitaGridP">'+
                        '<td><a href="'+link+'">'+val.id+'</a></td>'+
                        '<td><a href="'+link+'">'+val.nombre+'</a></td>'+
                        '<td><a href="'+link+'">'+cotizacion+'</a></td>'+
                        '<td><a href="'+link+'">$'+parseFloat(val.total).toFixed(2)+'</a></td>'+
                        '<td><a href="'+link+'">'+val.usuario+'</a></td>'+
                        '<td><a href="'+link+'">'+val.fecha+'</a></td>'+
                        '<td><a onclick="FunPdf('+val.id+');" class="btn btn-default"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>'+
                        '<td>'+estado+perfilBo+
                        '</td>'+
                        '</tr>'; 
                        perfilBo = '';
                        table.row.add($(x)).draw(); 
                }); 
                $('#tabliGriP').DataTable();
  })
  .fail(function() {
    console.log("error");
  })
  .always(function() {
    console.log("complete");
  });
  
}

function gridPedidos(){

            $.ajax({
                url: 'ajax.php?c=pedido&f=printGridP',
                type: 'GET',
                dataType: 'json',
            })
            .done(function(data) {
               // var status='';
               var cotizacion='';
               var total = 0;

                //var table = $('#tabliGriP').DataTable();
               var table =     $('#tabliGriP').DataTable({
                        dom: 'Bfrtip',
                        buttons: [ 'excel' ],
                        language: {
                        search: "Buscar:",
                        lengthMenu:"",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        },
                     },
                      aaSorting : [[0,'desc' ]]
    }); 
                //$('.filas').empty();
                table.clear().draw();
                var x ='';
                var estado = '';
                var perfilBo = '';
                var link = '';
                $.each(data.pedidos, function(index, val) {
                  total = val.total * 1;
                    if(val.idCotizacion==null){
                      cotizacion='';
                    }else{
                      cotizacion=val.idCotizacion;
                    }

                    switch(val.status) {
                        case '0':
                            estado = '<span class="label label-danger">Cancelado</span>';
                            link = '#';
                            break;
                        case '1':
                            estado = '<a onclick="aProceso('+val.id+');" class="btn btn-default">Activo</a><a onclick="cancelar('+val.id+');" class="btn btn-default">Cancelar</a>';
                            link = 'index.php?c=caja&f=indexPedido2&pe='+val.id+'';
                            break;
                        case '2':
                            estado = '<a onclick="aTerminado('+val.id+');" class="btn btn-warning">Proceso</a>';
                            link = 'index.php?c=caja&f=indexPedido2&pe='+val.id+'';
                            break;
                        case '3':
                            estado = '<span class="label label-primary">Terminado</span><a onclick="pedido('+val.id+');" class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                            link = '#';;
                            break;
                        case '4':
                            estado = '<span class="label label-info">En Venta</span><a onclick="pedido('+val.id+');" class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                            link = '#';
                            break;  
                        case '5':
                            estado = '<span class="label label-success">Vendido</span>';
                            link = '#';
                            break;

                    }
                    /*if(data.perfil=='(5)'){
                      if(val.status==3 || val.status==4){
                         perfilBo = '<a onclick="pedido('+val.id+');" class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a>';
                      }

                    } */
                    x ='<tr class="trtablitaGridP">'+
                        '<td><a href="'+link+'">'+val.id+'</a></td>'+
                        '<td><a href="'+link+'">'+val.nombre+'</a></td>'+
                        '<td><a href="'+link+'">'+cotizacion+'</a></td>'+
                        '<td><a href="'+link+'">$'+total.toFixed(2)+'</a></td>'+
                        '<td><a href="'+link+'">'+val.usuario+'</a></td>'+
                        '<td><a href="'+link+'">'+val.fecha+'</a></td>'+
                        '<td><a onclick="FunPdf('+val.id+');" class="btn btn-default"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a></td>'+
                        '<td>'+estado+perfilBo+
                        '</td>'+
                        '</tr>';
                        table.row.add($(x)).draw(); 
                        perfilBo = '';
                }); 
            })
            .fail(function() {
                console.log("error");
            });

}
function filtrosPedidos(){
            $.ajax({
              url: 'ajax.php?c=pedido&f=printFiltrosP',
              type: 'GET',
              dataType: 'json',
            })
            .done(function(data) {
            
                  $.each(data.cliente, function(index, val) {
                    $('#cotiClienteP').append('<option value="'+val.id+'">'+val.nombre+'</option>');
                  });

                  $.each(data.empleado, function(index, val) {
                    $('#cotiEmpleadoP').append('<option value="'+val.idempleado+'">'+val.usuario+'</option>');
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
function agregaP(){
      var idProducto =  $('#selectProductP').val();
      var cantidad = $('#cantidad').val();  
       
       if(idProducto==0 || idProducto ==''){
            alert('Tienes que agregar un producto');
            return;
       }
       if(cantidad <= 0 || cantidad==''){
            alert('la canidad no puede ser negativa o estar vacia');
            return;
       }

      $.ajax({
          url: 'ajax.php?c=pedido&f=addProductP',
          type: 'POST',
          dataType: 'json',
          data: {idProducto: idProducto,cantidad:cantidad},
      })
      .done(function(data) {
        
        $('#tableContainer').show();
        $(".xxx").remove();
        var precio = 0;
        var importe = 0;
          $.each(data.rows, function(index, val) {
            if(index!='charges'){
              var cantidad = val.cantidad;
                    cantidad = cantidad * 1;
                    precio = val.precio * 1;
                    importe = val.importe * 1;
                    $('#cotTable tr:last').after('<tr class="cotTable xxx" id="prodFila_'+val.idProducto+'">'+
                        '<td>'+cantidad+'</td>'+
                        '<td>'+val.nombre+'</td>'+
                        '<td>'+val.unidad+'</td>'+
                        '<td>'+precio.toFixed(2)+'</td>'+
                        '<td>'+importe.toFixed(2)+'</td>'+
                        '<td><span class="glyphicon glyphicon-minus-sign" onclick="deleteProP('+val.idProducto+');"></span></td>'+
                        '</tr>');
            }       
          });
          $(".tax").remove();
          $.each(data.rows["charges"]["taxes"], function(index, val) {
              if(val!=0 || val!='0'){
                 $('#divTaxes').append('<div class="tax"><label>'+index+':$</label>'+val.toFixed(2)+'</div>');
              }
            
          });
          data.rows["charges"]["sbtot"] = data.rows["charges"]["sbtot"].toFixed(2);
          data.rows["charges"]["Tot"] = data.rows["charges"]["Tot"].toFixed(2);
          $('#divTaxes').append('<div class="tax"><label>Subtotal:</label>'+data.rows["charges"]["sbtot"]+'</div>');
          $('#totalLab').html(data.rows["charges"]["Tot"]);
        /*  $.each(data.rows["charges"], function(index, val) {
             if(index='sbtot'){
                $('#divTaxes').append('<div class="tax"><label>Subtotal:</label>'+val+'</div>');
             }
             if(index='Tot'){
                $('#totalLab').html(val);
             }  
          }); */
          
          $('#cantidad').val(''); 
          $('#selectProductP > option[value="0"]').prop('selected',true);
          $("#selectProductP").select2({
              width : "150px"
          });  


      })
      .fail(function() {
          console.log("error");
      })
      .always(function() {
          console.log("complete");
      });
      
}
function sendP(){
    var idPedido = $('#idPedidoHide').val();
    var idCliente = $('#selectcliente').val();
    var observacion = $('#observ').val();
    if(idCliente==0 || idCliente==''){
        alert('Debes seleccionar un Cliente para enviar la cotzacion');
        return;
    }
      $('#sendBotton').hide();
      $('#modalMensajes').modal({
                        show:true,
                        keyboard: false,
                    });
      $.ajax({
        url: 'ajax.php?c=pedido&f=sendP',
        type: 'POST',
        dataType: 'json',
        data: {idCliente: idCliente,observacion:observacion,idPedido:idPedido},
      })
      .done(function(data) {

        if(data.status==true || data.status==1){
          alert('Se registro tu Pedido');
          var pathname = window.location.pathname;
          $("#tb2175-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=imprimeGridP');
        }else{
          alert('El cliente no tiene correo electronico registrado');
          var pathname = window.location.pathname;
          $("#tb2175-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=imprimeGridP');
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
}
function deleteProP(idProducto){
  var idPedido = $('#idPedidoHide').val();
  var id = idProducto;
        $.ajax({
          url: 'ajax.php?c=pedido&f=deleteProP',
          type: 'POST',
          dataType: 'json',
          data: {id: id,idPedido:idPedido},
        })
        .done(function(data) {
         $('#prodFila_'+idProducto).remove();
         pintaPedi(data);
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
  
}
function pintaPedi(data){

        $('#tableContainer').show();
        $(".xxx").remove();
        var precio = 0;
        var importe = 0;
          $.each(data.rows, function(index, val) {
            if(index!='charges'){
              var cantidad = val.cantidad;
                    cantidad = cantidad * 1;
                    precio = val.precio*1;
                    importe = val.importe*1;
                    $('#cotTable tr:last').after('<tr class="cotTable xxx" id="prodFila_'+val.idProducto+'">'+
                        '<td>'+cantidad+'</td>'+
                        '<td>'+val.nombre+'</td>'+
                        '<td>'+val.unidad+'</td>'+
                        '<td>'+precio.toFixed(2)+'</td>'+
                        '<td>'+importe.toFixed(2)+'</td>'+
                        '<td><span class="glyphicon glyphicon-minus-sign" onclick="deleteProP('+val.idProducto+');"></span></td>'+
                        '</tr>');
            }       
          });
          $(".tax").remove();
          $.each(data.rows["charges"]["taxes"], function(index, val) {
              if(val!=0 || val!='0'){
                 $('#divTaxes').append('<div class="tax"><label>'+index+':$</label>'+val.toFixed(2)+'</div>');
              }
            
          });
          data.rows["charges"]["sbtot"] = data.rows["charges"]["sbtot"].toFixed(2);
          data.rows["charges"]["Tot"] = data.rows["charges"]["Tot"].toFixed(2);
          $('#divTaxes').append('<div class="tax"><label>Subtotal:</label>'+data.rows["charges"]["sbtot"]+'</div>');
          $('#totalLab').html(data.rows["charges"]["Tot"]);
        /*  $.each(data.rows["charges"], function(index, val) {
             if(index='sbtot'){
                $('#divTaxes').append('<div class="tax"><label>Subtotal:</label>'+val+'</div>');
             }
             if(index='Tot'){
                $('#totalLab').html(val);
             }  
          }); */
          
          $('#cantidad').val(''); 
}
function pedido(idPedido){  
  
        $.ajax({
          url: 'ajax.php?c=pedido&f=sendCajaPedido',
          type: 'POST',
          dataType: 'json',
          data: {idPedido: idPedido},
        })
        .done(function(data) {
          console.log(data)
        if(data.venta==true){
          alert('Este pedido ya se hizo venta');
          return false;
        } 
        //window.parent.agregatab("../../modulos/appministra/index.php?c=configuracion&f=listas_precio","Listas de Precios","",1988);
          //$.each(data.codigo, function(index, val) {
                var outElement=$("#tb2175-u",window.parent.document).parent();
                var caja=outElement.find("#tb2051-u");
                var pestana=$("body",window.parent.document).find("#tb2051-1");
                var openCaja=$("body",window.parent.document).find("#mnu_2051");
                var pathname = window.location.pathname;
                var url=document.location.host+pathname;
                
                openCaja.trigger('click');
                
                pestana.trigger('click');
                //if(caja.length>0){
                  var campoBuscar=$(".frurl",caja).contents().find("#search-producto");
                  var campoCantidad=$(".frurl",caja).contents().find("#cantidad-producto");
                  var campoPedido=$(".frurl",caja).contents().find("#idPedido");
                  
                  ///PAra el pedido
                
                  campoPedido.trigger('focus');
                  campoPedido.val(idPedido);
                  
                  //campoCantidad.trigger('focus');
                  //campoCantidad.val(val.cantidad);
                  
                  campoBuscar.trigger("focus");
                  //campoBuscar.trigger("click");
                  campoBuscar.val('PMP'+idPedido);
                  campoBuscar.trigger({type: "keypress", which: 13});


                var outElement=$("#tb2175-u",window.parent.document).parent();
                var caja=outElement.find("#tb2357-u");
                var pestana=$("body",window.parent.document).find("#tb2357-1");
                var openCaja=$("body",window.parent.document).find("#mnu_2357");
                var pathname = window.location.pathname;
                var url=document.location.host+pathname;
                
                openCaja.trigger('click');
                
                pestana.trigger('click');
                //if(caja.length>0){
                  var campoBuscar=$(".frurl",caja).contents().find("#search-producto");
                  var campoCantidad=$(".frurl",caja).contents().find("#cantidad-producto");
                  var campoPedido=$(".frurl",caja).contents().find("#idPedido");
                  
                  ///PAra el pedido
                
                  campoPedido.trigger('focus');
                  campoPedido.val(idPedido);
                  
                  //campoCantidad.trigger('focus');
                  //campoCantidad.val(val.cantidad);
                  
                  campoBuscar.trigger("focus");
                  //campoBuscar.trigger("click");
                  campoBuscar.val('PMP'+idPedido);
                  campoBuscar.trigger({type: "keypress", which: 13}); 



         // });




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
function aProceso(id){
    var txt;
    var r = confirm("Deseas cambiar este pedido a proceso?");
    if (r == true) {

      $('#modalMensajes').modal({
          show:true,
          keyboard: false,
      });
        $.ajax({
          url: 'ajax.php?c=pedido&f=aProceso',
          type: 'POST',
          dataType: 'json',
          data: {id: id },
        })
        .done(function(resp) {
          console.log(resp);
          $('#modalMensajes').modal('hide');
          alert('Se Cambio de estado');
          var pathname = window.location.pathname;
          $("#tb2175-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=imprimeGridP');
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        
    } else {
        
    }

}
function aTerminado(id){
    var txt;
    var r = confirm("Deseas cambiar este pedido a Terminado?");
    if (r == true) {

      $('#modalMensajes').modal({
          show:true,
          keyboard: false,
      });
        $.ajax({
          url: 'ajax.php?c=pedido&f=aTerminado',
          type: 'POST',
          dataType: 'json',
          data: {id: id },
        })
        .done(function(resp) {
          console.log(resp);
          $('#modalMensajes').modal('hide');
          alert('Se Cambio de estado');
          var pathname = window.location.pathname;
          $("#tb2175-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=imprimeGridP');
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        
    } else {
        
    }
}
function cancelar(id){
      var txt;
    var r = confirm("Deseas cancelar este pedido?");
    if (r == true) {

      $('#modalMensajes').modal({
          show:true,
          keyboard: false,
      });
        $.ajax({
          url: 'ajax.php?c=pedido&f=cancelar',
          type: 'POST',
          dataType: 'json',
          data: {id: id },
        })
        .done(function(resp) {
          console.log(resp);
          $('#modalMensajes').modal('hide');
          alert('Se Cancelo el pedido');
          var pathname = window.location.pathname;
          $("#tb2175-u .frurl",window.parent.document).attr('src',window.location.protocol + '//'+document.location.host+pathname+'?c=pedido&f=imprimeGridP');
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        
    } else {
        
    }
}




