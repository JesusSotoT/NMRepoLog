
$(document).ready(function(){

});



$(function() {
  $('#nombre').on('change', function(){
    var v=$("#nombre>option:selected").val();
    $("#period").val(v);
  });


  $('#nominas').on('change', function(){

    m=$("#nominas option:selected").attr("value");
    $("#nomi").val(m);


    var v=$("#nominas>option:selected").text();

    if(v != "Todos"){
      var i = v.split(")"), j = i[3], k = i[1]; 
      var cadena =k;  
      $("#extraord").val(k);

      fechainic = cadena.substring(11,0);
      fechafina = cadena.substring(22,12);
      $("#fechainic").val(fechainic);
      $("#fechafina").val(fechafina);
    }

    valip = $('#nombre').val(); 
    $.ajax({
      url:"ajax.php?c=reportes&f=periodo",
      type: 'POST',
      dataType:'json',
      data:{
        idtipop:  $('#nombre').val(), 
        idnomp: $('#nominas').val()
      },
      success: function(r){
        if(valip=='*'){
          option='<option value="*">Todos</option>';
        }else{
          option='';
        }
        if(r.success==1 ){
          option='<option value="*">Todos</option>';
          $.each(r.data, function( k, v ) {  
            option+='<option value="'+v.idnomp+'">('+v.numnomina+') '+v.fechainicio+' '+v.fechafin+'</option>';

          });
        }
        else{
          option+='<option value="">No hay nominas</option>';         
        }
        $('#nominasdos').html(option);
        $('#nominasdos').selectpicker('refresh');
      }
    });
  });


  $('#nombre').on('change', function(){

    valip = $(this).val(); 

    $.ajax({
      url:"ajax.php?c=reportes&f=periodo",
      type: 'POST',
      dataType:'json',
      data:{idtipop: $(this).val() },
      success: function(r){
        if(valip=='*'){
          option='<option value="*">Todos</option>';
        }else{
          option='';
        }
        if(r.success==1 ){

          option='<option value="*">Todos</option>';
          $.each(r.data, function( k, v ) {  
            option+='<option value="'+v.idnomp+'">('+v.numnomina+') '+v.fechainicio+' '+v.fechafin+'</option>';

          });

        }else{
          option+='<option value="">No hay nominas</option>';         
        }

        $('#nominas').html(option);
        $('#nominas').selectpicker('refresh');

      }
    });
  });


  $('#load').on('click', function(evt) {

    $(this).button('loading');
    $("#formDetallado").submit();


  });

});



// INICIA GENERA PDF
function pdf(){

  //$('.tdsize').css({'font-size': '8px '});
  var contenido_html = $("#imprimible").html();

  $("#contenido").text(contenido_html);

  $("#divpanelpdf").modal('show');

}
function generar_pdf(){
   $('.saltopagina').css({'display':'block'});
  $('.saltopagina').css({'page-break-before':'always'});
  // $('.tdsize').removeAttr('style');
  // $(".tdsize").removeAttr("font-size");
  $("#divpanelpdf").modal('hide');
}
function cancelar_pdf(){
  $("#divpanelpdf").modal('hide');
}

function pdf_generado(){
  alert("OK");
}
// TERMINA GENERA PDF

