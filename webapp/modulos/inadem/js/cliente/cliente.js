
function busca(){
  var convocatoria = $('#convocatoria').val();
  var folio = $('#folio').val();
  var organismo = $('#organismo').val();

  $.ajax({
    url: 'ajax.php?c=cliente&f=buscar',
    type: 'POST',
    dataType: 'json',
    data: {convocatoria: convocatoria, folio:folio, organismo:organismo},
  })
  .done(function(data) {
      $('.rowsTable').remove();
      $.each(data.grid, function(index, val) {
          $('#Gridinadem tr:last').after('<tr class="rowsTable">'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.id+'</a></td>'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.nombre+'</td>'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.folio_inadem+'</a></td>'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.convocatoria+'</a></td>'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.vitrina+'</a></td>'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.cupon+'</a></td>'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.promotor+'</a></td>'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.organismo_inter+'</a></td>'+
              '<td><a href="ajax.php?c=cliente&f=clienteForm&pe='+val.id+'">'+val.resp_nwm+'</a></td>'+
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
function backToGrid(){
    var pathname = window.location.pathname;
    $("#tb1906-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=cliente&f=imprimeGrid');
}
function saveClient(){
  var folioInaCliente = $('#clienteIdInHidden').val();
  var folioIna = $('#folio').val();
  var convocatoria = $('#convocatoria').val();
  var vitrina = $('#vitrina').val();
  var cupon = $('#cupon').val();
  var beneficio = $('#beneficio').val();
  var aportacion = $('#aportacion').val();
  var organismo = $('#org_int').val();
  var promotor = $('#promotor').val();
  var respNwm = $('#resp_nwm').val();
  var fecha = $('#fecha').val();
  var instancia = $('#instancia').val();
  var respLegal = $('#resp_legal').val();

      $.ajax({
        url: 'ajax.php?c=cliente&f=saveClient',
        type: 'POST',
        dataType: 'json',
        data: {folioInaCliente:folioInaCliente,
              folioIna: folioIna,
              convocatoria:convocatoria,
              vitrina:vitrina,
              cupon:cupon,
              beneficio:beneficio,
              aportacion:aportacion,
              organismo:organismo,
              promotor:promotor,
              respNwm:respNwm,
              fecha:fecha,
              instancia:instancia,
              respLegal:respLegal
              },
      })
      .done(function(data) {
        if(data.status==true || data.status==1){
          alert('Se gurado Satisfactoriamente');
          var pathname = window.location.pathname;
          $("#tb1906-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=cliente&f=imprimeGrid');
        }else{
          alert('Error al Actualizar el Registro');
          var pathname = window.location.pathname;
          $("#tb1906-u .frurl",window.parent.document).attr('src','http://'+document.location.host+pathname+'?c=cliente&f=imprimeGrid');
        }
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
      


}


















