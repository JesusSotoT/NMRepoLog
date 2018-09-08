function addSerie(){
    var idLote = $('#idloteinput').val();
    var idProducto = $('#idProductoInput').val();
    var serie = $('#serie').val();
    var cantidad = $('#cantidadInput').val();

    $.ajax({
        url: 'ajax.php?c=lote&f=addSerie',
        type: 'POST',
        dataType: 'json',
        data: {idLote:idLote, idProducto:idProducto, serie:serie, cantidad:cantidad},
    })
    .done(function(data) {
        if(data.status){
            $('#serie').val('');
            $('#serialTable tr:last').after('<tr class="rowsSeries"><td>'+data.serie+'</td></tr>');
        }else{
            alert('Ya no tienes mas Series para Registrar.');
            $('#serie').val('');
            return;
        }
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}

function goBack(){
    var pathname = window.location.pathname;
    $("#tb1909-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=lote&f=imprimeGrid');
    
}
function cargaSelects(){
    $.ajax({
        url: 'ajax.php?c=lote&f=cargaSelects',
        type: 'POST',
        dataType: 'json',
        
    })
    .done(function(data) {

        $.each(data.lote, function(index, val) {
            $('#lote').append('<option value="'+val.idLote+'">'+val.idLote+'</option>');
        });

        $.each(data.producto, function(index, val) {
            $('#producto').append('<option value="'+val.idProducto+'">'+val.nombre+'</option>');
        });

    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
    
}
function busca(){
    var lote = $('#lote').val();
    var producto = $('#producto').val();
    var desde = $('#desde').val();
    var hasta  = $('#hasta').val();

    $.ajax({
        url: 'ajax.php?c=lote&f=busca',
        type: 'POST',
        dataType: 'json',
        data: {lote:lote, producto:producto, desde:desde, hasta:hasta,},
    })
    .done(function(data) {
        console.log(data);
        $('.rowsTable').remove();
      $.each(data.grid, function(index, val) {
          $('#Gridinadem tr:last').after('<tr class="rowsTable">'+
              '<td><a href="ajax.php?c=lote&f=loteForm&pe='+val.idLote+'">'+val.idLote+'</a></td>'+
              '<td><a href="ajax.php?c=lote&f=loteForm&pe='+val.idLote+'">'+val.idOrdeCom+'</td>'+
              '<td><a href="ajax.php?c=lote&f=loteForm&pe='+val.idLote+'">'+val.nombre+'</a></td>'+
              '<td><a href="ajax.php?c=lote&f=loteForm&pe='+val.idLote+'">'+val.cantidad+'</a></td>'+
              '<td><a href="ajax.php?c=lote&f=loteForm&pe='+val.idLote+'">'+val.fecha_recibido+'</a></td>'+
              '<td><a href="ajax.php?c=lote&f=loteForm&pe='+val.idLote+'">'+val.fecha_caducidad+'</a></td>'+
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








