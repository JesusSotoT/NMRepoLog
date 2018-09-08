function modalFacturas(id){
  $.ajax(
   {
     type: "POST",
     url: 'ajax.php?c=ReportsMovPolizas&f=modal_facturas',
     data:{
       id: id
     },
     success:function(data) {
       $('#modalFacturas').modal('show');
       $('.modal-content').html("Cargando..");
       $('.modal-content').html(data);
     }
   });
}

function pdf(){
  var contenido_html = $("#idcontenido_reporte").html();
  //contenido_html = contenido_html.replace(/\"/g,"\\\"");
  $("#contenido").attr("value",contenido_html);

  $("#divpanelpdf").modal('show');
}

function generar_pdf(){
  $("#divpanelpdf").modal('hide');
  //$("#loading").fadeIn(500);
}

function cancelar_pdf(){
  $("#divpanelpdf").modal('hide');
}

function pdf_generado(){
  alert("OK");
}

function mail(){
  var msg = "Registre el correo electrónico a quién desea enviarle el reporte:";
  var a = prompt(msg,"@netwaremonitor.com");
  if(a!=null){
    var html_contenido_reporte;
    html_contenido_reporte = $("#idcontenido_reporte").html();
    $("#loading").fadeIn(500);
    $("#divmsg").load("../../../webapp/netwarelog/repolog/mail.php?a="+a, {reporte:html_contenido_reporte});
  }
}

function verpoliza(id){
  window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=ModificarPoliza&id='+id,'Informacion Poliza','',id);
}

function generaexcel(){
  $().redirect('views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte").html(), 'name': $("#titulo").val()});
}