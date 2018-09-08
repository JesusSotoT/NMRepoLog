

  $(document).ready(function(){

$('select[name*="nombre"] option[value="3"]').hide();
   $('#tablaincidencias').DataTable( {
    
     // "scrollX": true,
    "language": {
      "url": "js/Spanish.json"
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
        //alert("aa");  
        $('#mostrarfechas').prop('checked', true);
        $('#mostrarperiodos').prop('checked', true);
        $('#mostrarfecha').hide(); 
        $('#mostrarperiodo').show(); 

      }
      else{
        //alert("bb");
        $('#mostrarperiodos').prop('checked', false);
        $('#mostrarperiodo').hide();
        $('#mostrarfecha').show();  
      }
    }



    function activarCheckeddos(){
      if ($("#mostrarperiodos").prop("checked")){
        //alert("cc");  
        $('#mostrarfechas').prop('checked', true);
        $('#mostrarperiodos').prop('checked', true);
        $('#mostrarfecha').show(); 
        $('#mostrarperiodo').hide(); 
      }
      else{

         //alert("dd");
         $('#mostrarfechas').prop('checked', false);     
         $('#mostrarperiodo').show(); 
         $('#mostrarfecha').hide();  
         $('#fechainicio').val('').datepicker("refresh");
         $('#fechafin').val('').datepicker("refresh");
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
        e.preventDefault(); // Ensure it is only this code that rusn
       // alert(vali);

       $.ajax({
        url:"ajax.php?c=reportes&f=actHoras",
        type: 'POST',
        data:{vali:vali,input:input},
        success: function(r){
          $('#'+input).html('<td id="'+input+'" onclick="editar(\''+input+'\');">'+vali+'</td>');
        }
      });
     }
   }

   function editar(iddiv){
    valortd= $('#'+iddiv).text();
    
    $('#'+iddiv).html('<input id="i_'+iddiv+'" onkeypress="guardarinput(event,\''+iddiv+'\');"  style="width:100%;" type="text" value="'+valortd+'">');
    $('#'+iddiv).prop('onclick',null).off('click');
  }

  $(function() {
    $('#idnomp').on('change', function(){
      var disabled = $(this).val() == 'true' ? false : true;
      $("#fechafin").prop('disabled', true);
      $("#fechainicio").prop('disabled', true);
      $("#nombreEmpleado").prop('disabled', true);
    });


    $('#nombre').on('change', function(){

     valip = $(this).val(); 

     // alert(valip);
    //$('#nominas').find('option').remove();
   //$('#nominas').selectpicker('refresh');

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


    $('#load').on('click', function() { 

     $(this).button('loading');
     if ($("#mostrarfechas").prop("checked")){
      if(!$("#fechafin").val() || !$("#fechainicio").val()){
       alert("Seleccione una fecha.");
       $(this).button('reset'); 

     }else{
      $("#formfecha").submit();

    }    
  }
  else{
    $("#formfecha").submit();
  }

  });
  });

  //INICIA GENERA PDF
function pdf(){
  
var table = $('#tablaincidencias').DataTable({"scrollX": false,"destroy": true});
 table.destroy();


$(".collwid1").removeAttr("width");
$(".collwid2").removeAttr("width");
$(".collwid3").removeAttr("width");
$(".campoaltura").attr('height','25');
$(".mostrar").show();

$('.estinegrit').css({'fontWeight':'bold','fontSize':'11px'});  
$('#tablaincidencias').removeClass('tablaincidencias_length').removeClass('table').removeClass('striped').removeClass('no-footer');
$('.collwid1').css({'width':'40px'});
$('.collwid2').css({'width':'100px'});
$('.collwid3').css({'width':'60px'});


var contenido_html = $("#imprimible").html();
var table = $('#tablaincidencias').DataTable();
table.destroy();
$('#tablaincidencias').addClass('table').addClass('bordered').addClass('striped').addClass('no-footer');
$(".campoaltura").removeAttr("height");  

$("#contenido").text(contenido_html);
$('#tablaincidencias').DataTable( {
 "scrollX": true,
    "language": {
      "url": "js/Spanish.json"
    }
  } );

$(".campoaltura").removeAttr("height"); 
$("#divpanelpdf").modal('show');
$('.estilopdf').css({'color':'white','fontSize':'16px'});
$('#neto').css({'fontSize':'14px'});
$('.estinegrit').css({'fontWeight':'bold','fontSize':'14px'});
$('.encabpdf').css({'color':'black'});
$('.parrafopdf').css({'fontSize':'13px'});
$('#tabl').css({'width':'30%'});
$(".mostrar").hide();
}
function generar_pdf(){
$("#divpanelpdf").modal('hide');
}
function cancelar_pdf(){
$("#divpanelpdf").modal('hide');
}

function pdf_generado(){
alert("OK");
}
// TERMINA GENERA PDF
// 
// 