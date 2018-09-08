
$(document).ready(function(){

  $('select[name*="idtipop"] option[value="3"]').hide();

  $('.tablaentradas').DataTable( {
    responsive: true,
    "language": {
      "url": "js/Spanish.json",

    }
    
  } );

  $.datepicker.setDefaults($.datepicker.regional['es-MX']);
  $("#fechainicio").datepicker({
    maxDate: 365,
    dateFormat: 'yy-mm-dd',
    numberOfMonths: 1,
    onSelect: function(selected) {
      $("#final").datepicker("option","minDate", selected);
      $("#nombreEmpleado").prop('disabled', false);
      $("#nominas").prop('disabled', true);
      $("#nombre").prop('disabled', true);
    }
  });

  $("#fechafin").datepicker({ 
   dateFormat: 'yy-mm-dd',
   maxDate:365,
   numberOfMonths: 1,
   onSelect: function(selected) {
    $("#inicial").datepicker("option","maxDate", selected);
    $("#nombreEmpleado").prop('disabled', false);
    $("#nominas").prop('disabled', true);
    $("#nombre").prop('disabled', true);     
  }
});  
});


function activarChecked(){

  if ($("#mostrarfechas").prop("checked")){
      // alert("aa");  
      $('#mostrarfechas').prop('checked', true);
      $('#mostrarperiodos').prop('checked', true);
      $('#mostrarfecha').hide(); 
      $('#mostrarperiodo').show(); 

    }
    else{
       // alert("bb");
       $('#mostrarperiodos').prop('checked', false);
       $('#mostrarperiodo').hide();
       $('#mostrarfecha').show();  
     }
   }

   function activarCheckeddos(){
    if ($("#mostrarperiodos").prop("checked")){
       // alert("cc");  

       $('#mostrarfechas').prop('checked', true);
       $('#mostrarperiodos').prop('checked', true);
       $('#mostrarfecha').show(); 
       $('#mostrarperiodo').hide(); 
     }
     else{

       // alert("dd");
       $('#mostrarfechas').prop('checked', false);
       $('#mostrarperiodo').show(); 
       $('#mostrarfecha').hide();  
     }
   }

   function envioCorreos(){
    x=0;
    cadena='';
    $("#table tr").each(function(index) {
      if(x>0){
        cadena+=$(this).attr('idemp')+'#.#'+$(this).attr('xml')+'##.##';
      }

      x++;
    });
//alert(cadena);
$("#divmsg").load("mail.php", {cadena:cadena,m:1});
}

function guardarinput(e,input){
  vali= $('#i_'+input).val();

  if(e.keyCode === 13){
      e.preventDefault(); 
     // alert(vali);

     $.ajax({
      url:"ajax.php?c=reportes&f=actHoras",
      type: 'POST',
      data:{vali:vali,input:input},
      success: function(r){
        $('#'+input).html('<td id="'+input+'" onclick="editar(\''+input+'\');">'+vali+'</td>');
      }
    });
   }else if(e.keyCode === 27 || e.code === 'Escape' ){
		$('#'+input).html('<td id="'+input+'" onclick="editar(\''+input+'\');">'+vali+'</td>');
	}
 }

 function editar(iddiv){
  valortd= $.trim($('#'+iddiv).text());
  $('#'+iddiv).html('<input id="i_'+iddiv+'" onkeydown="guardarinput(event,\''+iddiv+'\');"  style="width:100%;" type="text" value="'+valortd+'">');
  $('#i_'+iddiv).focus().val("").val(valortd);
  $('#'+iddiv).prop('onclick',null).off('click');
}

$(function() {
  $('#idnomp').on('change', function(){
    var disabled = $(this).val() == 'true' ? false : true;
    $("#fechafin").prop('disabled', true);
    $("#fechainicio").prop('disabled', true);
    $("#nombreEmpleado").prop('disabled', true);
  });


  $('#idtipop').on('change', function(){

   valip = $(this).val(); 
     //alert(valip);

     $.ajax({
      url:"ajax.php?c=reportes&f=periodo",
      type: 'POST',
      dataType:'json',
      data:{
        idtipop: $(this).val() 
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

       }else{
        option+='<option value="">No hay nominas</option>';         
      }

      $('#idnomp').html(option);
      $('#idnomp').selectpicker('refresh');

    }
  });
   });

  $('#load').on('click', function() { 

   $(this).button('loading');
   if ($("#mostrarfechas").prop("checked")){
    if(!$("#fechafin").val() || !$("#fechainicio").val()){
     alert("Seleccione una fecha.");
     $(this).button('reset'); 

   }else{
    $("#formentradas").submit();

  }    
}
else{
  $("#formentradas").submit();
}

});
});




  //INICIA GENERA PDF
  function pdf(){

    $(".collwidt").removeAttr("border");
    $('.mostrartabla').css({'display':'none'});
    $('.saltopagina').css({'display':'block'});
    $('.saltopagina').css({'page-break-before':'always'});
    $(".tablaentradas").removeAttr("fontSize");
    $('.tablaentradas').css({'fontSize':'9px'}); 

    var table = $('.tablaentradas').DataTable();
    table.destroy();
    $(".unoents").removeAttr("width");
    $(".dosents").removeAttr("width");
    $(".collwidt").removeAttr("width");
    $(".collwidts").removeAttr("width");
     
    $('.unoents').css({'width':'40px'});
    $('.dosents').css({'width':'100px'});
    $('.collwidt').css({'width':'60px'});
    $('.collwidts').css({'width':'20px'});
  


    var contenido_html = $("#imprimible").html();


    $("#contenido").text(contenido_html);

    $('.tablaentradas').DataTable( {
      responsive: true,
      "language": {
        "url": "js/Spanish.json"
      }
    } );

    $("#divpanelpdf").modal('show');
    $('.mostrartabla').css({'display':'inline'});

    $('.mostrar').css({'display':'inline'});
    $('.tablaentradas').css({'fontSize':'12.5px'}); 

  }
  function generar_pdf(){
    $("#divpanelpdf").modal('hide');
    $('.mostrartabla').css({'display':'inline'});
  }
  function cancelar_pdf(){
    $("#divpanelpdf").modal('hide');
  }

  function pdf_generado(){
    alert("OK");
  }
// TERMINA GENERA PDF


 //I M P R I M I R   P D F
 function printl(){
  $('.tablaentradas').css({'fontSize':'11px'}); 
  $('.muestra').show();

  var table = $('.tablaentradas').DataTable();
  table.destroy();
  
  setTimeout(function () { 
    window.close();
    $('.muestra').hide();
    $('.tablaentradas').css({'fontSize':'12.5px'}); 

    $('.tablaentradas').DataTable( {
      responsive: true,

      "language": {
        "url": "js/Spanish.json"
      }
    } );
    
  }, 3000);

}




