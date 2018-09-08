
function createnew(){
   var pathname = window.location.pathname;
    $("#tb1887-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=cotizacion&f=loadProducts');
    
}
function busca(){
  var cliente = $('#cotiCliente').val();
  var empleado = $('#cotiEmpleado').val();
  var desde = $('#desde').val();
  var hasta = $('#hasta').val();
  
  $.ajax({
    url: 'ajax.php?c=cotizacion&f=buscar',
    type: 'POST',
    dataType: 'json',
    data: {cliente: cliente,empleado:empleado,desde:desde,hasta:hasta },
  })
  .done(function(data) {
                $('.trtablitaGrid').remove();
                var status='';
                $.each(data, function(index, val) {
                  if(val.status=='2'){
                    status='<a class="btn btn-success">Pedido</a>';
                  }else{
                    status='<a onclick="pedido('+val.id+');" class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a><a onclick="eliminaCoti('+val.id+');" class="btn btn-default"><i class="fa fa-times" aria-hidden="true"></i></a>';
                  }
                    $('#tabliGri tr:last').after('<tr class="trtablitaGrid">'+
                        '<td>'+val.id+'</td>'+
                        '<td>'+val.nombre+'</td>'+
                        '<td>$'+val.total+'</td>'+
                        '<td>'+val.usuario+'</td>'+
                        '<td>'+val.fecha+'</td>'+
                        '<td><a onclick="FunPdf('+val.id+');" class="btn btn-default"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>'+
                        '<a onclick="reenvia('+val.id+');" class="btn btn-default"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>'+status+'</td>'+
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

function backbutton(){
        $.ajax({
          url: 'ajax.php?c=cotizacion&f=deleteSession',
          type: 'GET',
        })
        .done(function(data) {
          if(data=='1'){
            var pathname = window.location.pathname;
           $("#tb1887-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=cotizacion&f=imprimeGrid');
          }
        
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
       
        
}
function pintaCoti(data){

        $('#tableContainer').show();
        $(".xxx").remove();
        var precio = 0;
        var importe = 0;
        var aimagen = "";

          $.each(data.rows, function(index, val) {
            if(index!='charges'){
              var cantidad = val.cantidad;
                    precio = val.precio*1;
                    importe = val.importe*1;
                    cantidad = cantidad * 1;
                    
                    if (val.imagen!=""){
                        if (val.imagen=="images/noimage.jpeg") {
                          aimagen = "";
                        } else {
                          aimagen = '<img src="../mrp/'+val.imagen+'" width="30" height="30" );">';
                        }
                    } else {
                        aimagen = "";
                    }

                    $('#cotTable tr:last').after('<tr class="cotTable xxx" id="prodFila_'+val.idProducto+'">'+
                        '<td>'+cantidad+'</td>'+
                        '<td>'+val.nombre+'</td>'+
                        '<td>'+val.unidad+'</td>'+
                        '<td>'+precio.toFixed(2)+'</td>'+
                        '<td>'+importe.toFixed(2)+'</td>'+
                        '<td>'+aimagen+'</td>'+
                        '<td><span class="glyphicon glyphicon-minus-sign" onclick="deletePro('+val.idProducto+');"></span></td>'+
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
  function agrega(){
    	
      var idProducto =  $('#selectProduct').val();
      var cantidad = $('#cantidad').val();
      var qprecio = $('#selectPrecio').val();
       
       if(idProducto==0 || idProducto ==''){
            alert('Tienes que agregar un producto');
            return;
       }
       if(cantidad <= 0 || cantidad==''){
            alert('La canidad no puede ser negativa o estar vacia.');
            return;
       }

      $.ajax({
          url: 'ajax.php?c=cotizacion&f=addProduct',
          type: 'POST',
          dataType: 'json',
          data: {idProducto: idProducto,cantidad:cantidad,precio:qprecio},
      })
      .done(function(data) {
        
        $('#tableContainer').show();
        $(".xxx").remove();
        var precio = 0;
        var importe = 0;
        var aimagen = "";

          $.each(data.rows, function(index, val) {
            if(index!='charges'){
              var cantidad = val.cantidad;
                    cantidad = cantidad * 1;
                    precio = val.precio * 1;
                    importe = val.importe * 1;
                    
                    if (val.imagen!=""){
                        if (val.imagen=="images/noimage.jpeg") {
                          aimagen = "";
                        } else {
                          aimagen = '<img src="../mrp/'+val.imagen+'" width="30" height="30" );">';
                        }
                    } else {
                        aimagen = "";
                    }
                    $('#cotTable tr:last').after('<tr class="cotTable xxx" id="prodFila_'+val.idProducto+'">'+
                        '<td>'+cantidad+'</td>'+
                        '<td>'+val.nombre+'</td>'+
                        '<td>'+val.unidad+'</td>'+
                        '<td>'+precio.toFixed(2)+'</td>'+
                        '<td>'+importe.toFixed(2)+'</td>'+
                        '<td>'+aimagen+'</td>'+
                        '<td><span class="glyphicon glyphicon-minus-sign" onclick="deletePro('+val.idProducto+');"></span></td>'+
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
          $('#selectProduct > option[value="0"]').prop('selected',true);
          $("#selectProduct").select2({
              width : "150px"
          });

          $('#selectPrecio option').remove();
      })
      .fail(function() {
          console.log("error");
      })
      .always(function() {
          console.log("complete");
      });
      
}
function send(){

    var idCliente = $('#selectcliente').val();
    var observacion = $('#observ').val();
    if(idCliente==0 || idCliente==''){
        alert('Debes seleccionar un Cliente para enviar la cotzacion');
        return;
    } 
      $('#sendBtn').hide();
      

      $('#modalMensajes').modal({
                        show:true,
                        keyboard: false,
                    });
      $.ajax({
        url: 'ajax.php?c=cotizacion&f=send',
        type: 'POST',
        dataType: 'json',
        data: {idCliente: idCliente,observacion:observacion},
      })
      .done(function(data) {
        console.log(data);
        if(data.status==true || data.status==1){
          $('#modalMensajes').modal('hide');
          alert('Se registro tu cotizacion');
          var pathname = window.location.pathname;
          $("#tb1887-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=cotizacion&f=imprimeGrid');
        }else{
          alert('El cliente no tiene correo electronico registrado');
          var pathname = window.location.pathname;
          $("#tb1887-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=cotizacion&f=imprimeGrid');
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      

}
function deletePro(idProducto){
  
  var id = idProducto;
        $.ajax({
          url: 'ajax.php?c=cotizacion&f=deletePro',
          type: 'POST',
          dataType: 'json',
          data: {id: id},
        })
        .done(function(data) {
         $('#prodFila_'+idProducto).remove();
         pintaCoti(data);
        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
  
}
function FunPdf(id){
  window.open("../../modulos/cotizaciones/cotizacionesPdf/cotizacion_"+id+".pdf");
}
function reenvia(id){
  
  var r = confirm("Deseas enviar la cotizacion al cliente?");
    if (r == true) {
              $('#modalMensajes').modal({
                    show:true,
                    keyboard: false,
              });

            $.ajax({
            url: 'ajax.php?c=cotizacion&f=resubmit',
            type: 'POST',
            dataType: 'json',
            data: {id: id},
          })
          .done(function(data) {
                if(data.status==true || data.status==1){
                  $('#modalMensajes').modal('hide');
                  alert('Se envio tu cotizacion, al cliente');

                }else{
                  $('#modalMensajes').modal('hide');
                  alert('El cliente no tiene correo electronico registrado');
                }
          })
          .fail(function() {
            console.log("error");
          })
          .always(function() {
            console.log("complete");
          });
    } else {
        return;
    }

 
} 
function pedido(idCotizacion){
  
  var r = confirm("Deseas convertir esta cotizacion en un Pedido?");
    if (r == true) {

         $('#modalMensajes').modal({
              show:true,
              keyboard: false,
          });
        $.ajax({
          url: 'ajax.php?c=cotizacion&f=createPedido',
          type: 'POST',
          dataType: 'json',
          data: {idCotizacion: idCotizacion},
        })
        .done(function(data) {
          $('#modalMensajes').modal('hide');
          alert('Se mando a pedidos');
            $.ajax({
                url: 'ajax.php?c=cotizacion&f=printGrid',
                type: 'GET',
                dataType: 'json',
            })
            .done(function(data) {
              $('.trtablitaGrid').remove();
                var status='';
                $.each(data, function(index, val) {
                  if(val.status=='2'){
                    status='<a class="btn btn-success">Pedido</a>';
                  }else{
                     status = '<a onclick="pedido('+val.id+');" class="btn btn-default"><i class="fa fa-shopping-cart" aria-hidden="true"></i></a><a onclick="eliminaCoti('+val.id+');" class="btn btn-default"><i class="fa fa-times" aria-hidden="true"></i></a>'
                  }
                    $('#tabliGri tr:last').after('<tr class="trtablitaGrid">'+
                        '<td>'+val.id+'</td>'+
                        '<td>'+val.nombre+'</td>'+
                        '<td>$'+val.total+'</td>'+
                        '<td>'+val.usuario+'</td>'+
                        '<td>'+val.fecha+'</td>'+
                        '<td><a onclick="FunPdf('+val.id+');" class="btn btn-default"><i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>'+
                        '<a onclick="reenvia('+val.id+');" class="btn btn-default"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>'+status+'</td>'+
                        '</td>'+
                        '</tr>'); 
                }); 
            })
            .fail(function() {
                console.log("error");
            });

        })
        .fail(function() {
          console.log("error");
        })
        .always(function() {
          console.log("complete");
        });
        
    }else{
      return;
    }
}
function eliminaCoti(id){

    var r = confirm("Deseas eliminar la cotizacion");
    if (r == true) {
          $('#modalMensajes').modal({
              show:true,
              keyboard: false,
          });
        $.ajax({
          url: 'ajax.php?c=cotizacion&f=eliminaCoti',
          type: 'POST',
          dataType: 'json',
          data: {id: id},
        })
        .done(function(resp) {
          console.log(resp);
          $('#modalMensajes').modal('hide');
          if(resp.status==true){
            alert('Se elimino la cotizacion');
            window.location.reload();
          }else{
            alert('Ocurrio un error en el proceso de eliminacion, vuelve a intentar ');
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
