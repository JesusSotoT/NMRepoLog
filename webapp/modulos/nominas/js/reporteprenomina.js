
$(document).ready(function(){
  $('.firma').addClass('mostrardiv');

    $(".firma").css("display", "none");
    $(".firma").hide();


  $('#impresion').css({'display':'none'});
  $('#listaRaya').css({'display':'none'});
  
  $('.mostrarrangos').hide(); 
  $('.rangoempleado').hide(); 
  $('.extracheck').hide(); 
  cargaNominas('*');
});

function validacionesDeSelect(){

  $("#empleado").val('*').selectpicker('refresh');
  $("#idnomp").val('*').selectpicker('refresh');
  $("#idtipop").val('*').selectpicker('refresh');
  $("#codigouno").val('').selectpicker('refresh');
  $("#codigodos").val('').selectpicker('refresh');
  $("#origen").val('').selectpicker('refresh');
}



function activarChecked(){
  if ($("#mostrarvisual").prop("checked")){
   // alert("aa");  
   $('select[name*="idtipop"] option[value="3"]').show();
   validacionesDeSelect();
   $('#mostrarimprimir').prop('checked', true);
   $('#mostrarimprimir').show(); 
   $('#imprimir').show(); 
   $('#impresion').css({'display':'inline'}); 
   $('#listaRaya').css({'display':'inline'}); 
   
   $('.mostrarrangos').show(); 
   $('#divVisual').hide(); 
   $('#pdf').hide(); 


 }
 else{
    //alert("bb");
    $('select[name*="idtipop"] option[value="3"]').hide();
    validacionesDeSelect();
    $('#mostrarimprimir').prop('checked', false);   
    $('#impresion').css({'display':'none'}); 
    $('#listaRaya').css({'display':'none'}); 
    $('#imprimir').css({'display':'none'}); 
    $('#divVisual').show(); 
    $('.mostrarrangos').hide(); 
    $('.rangoempleado').hide(); 
    $('.empleadocheck').show();
    $('#pdf').show();  
  }
}

function activarCheckeddos(){
  if ($("#mostrarimprimir").prop("checked")){
    //alert("cc"); 
    $('select[name*="idtipop"] option[value="3"]').hide();
    validacionesDeSelect();
    $('#mostrarvisual').prop('checked', true);     
    $('.mostrarrangos').css({'display':'none'});
    $('.rangoempleado').hide(); 
    $('.empleadocheck').show(); 
    $('#divVisual').show(); 
    $('#pdf').show(); 
    $('#impresion').css({'display':'none'});
    $('#listaRaya').css({'display':'none'});
    $('#imprimir').hide(); 
    
    
    
  }else{
   // alert("dd");
   $('select[name*="idtipop"] option[value="3"]').show();
   validacionesDeSelect();
   $('.mostrarrangos').show();  
   $('#imprimir').show(); 
   $('#mostrarvisual').prop('checked', false);     
   $('#mostrarimprimir').show(); 
   $('#divVisual').hide(); 
   $('#impresion').css({'display':'inline'});
   $('#listaRaya').css({'display':'inline'});
   $('#pdf').css({'display':'none'});
 }
}


function activarCheckedtres(){
  if ($("#mostrarrangos").prop("checked")){
    //alert("p");
    validacionesDeSelect();          
    $('.empleadocheck').show();
    $('.rangoempleado').hide();
    $('#pdf').hide(); 
    
    
    
  }else{
    //alert("q"); 
    validacionesDeSelect();  
    $('.empleadocheck').hide(); 
    $('.rangoempleado').show();
    $('.extracheck').hide(); 
    $('#pdf').hide(); 
  }
}

function ContPercepciones(idtipop,idnomp,idEmpleado,codigouno,codigodos,origen){
  var idtipop    = $("#idtipop").val();
  var idnomp     = $("#idnomp").val();
  var idEmpleado = $("#empleado").val();
  var codigouno  = $("#codigouno").val();
  var codigodos  = $("#codigodos").val();
  var origen     = $("#origen").val();
  
  $.post("ajax.php?c=Reportes&f=cargaPerceFiltros",{
    idtipop:idtipop,
    idnomp:idnomp,
    idEmpleado:idEmpleado,
    codigouno:codigouno,
    codigodos:codigodos,
    origen:origen
    
  },function (request){
    //alert(request);
    $("#contPerce").html(request);   
  });
}



cargaNominas  = function(idTipop){
  $.ajax({
    url:"ajax.php?c=reportes&f=periodo",
    type: 'POST',
    dataType:'json',
    data:{
      idtipop: idTipop
    },
    success: function(r){
      //alert(r);
      if(idTipop=='*'){
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
}

$(function() {
  
 $('#idnomp').on('change', function(){ 
  var v=$("#idnomp>option:selected").text();
  if(v != "Todos"){
    var i = v.split(")"), j = i[3], k = i[1]; 
    var cadena =k;  
    uno=cadena.substring(11,0);
      // alert(uno);
      dos = cadena.substring(22,12);
      // alert(dos);  
      $("#nomi").val(uno);
      $("#nomidos").val(dos);
    }
  });


 $('#idtipop').on('change', function(){ 
  var v=$("#idtipop>option:selected").text(); 
  $("#peri").val(v);  
  valip = $(this).val(); 
  cargaNominas(valip);
  if($("#idtipop>option:selected").val() == 3){
    $('.extracheck').show(); 
      //alert("aa");
    }else{
      $("#origen").val('').selectpicker('refresh');
      $('.extracheck').hide(); 
    }
  });

 $('#load').on('click', function() { 
    //alert("boto");  
    $("#loade").show();
    var  idEmpleado = $("#empleado").val();
    var  idnomp     = $("#idnomp").val();
    var  idtipop    = $("#idtipop").val();
    var  origen     = $("#origen").val();
    
    if ($("#mostrarvisual").prop("checked")){
      $.post("ajax.php?c=Reportes&f=tablaReporteSobrerecibo",{
        idtipop:idtipop,
        idnomp:idnomp,
        idEmpleado: idEmpleado,
        nomi: $('#nomi').val(),
        nomidos: $("#nomidos").val()
        
        
      },function(resp){   
        //  alert(resp); 
        $("#divVisual").html(resp); 
        
        var extensions = {
          "sLength": "custom_length_class text-left", 
          "sInfo": 'text-left'
        }
        
        $.extend($.fn.dataTableExt.oStdClasses, extensions);
        $.extend($.fn.dataTableExt.oJUIClasses, extensions);
        
        $('#divVisualx').DataTable( {
          "scrollX": true,
          "language": {
            "url": "js/Spanish.json",
            "info": "No existen registros."
            
          },
          "lengthMenu": [ 5,10, 25, 50, 75, 100 ]
        } );            
      });
      $("#divVisual").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>');           
      $('#divVisual').show();
      $('#divVisualv').show();  
      $('#imprimir').hide(); 
      $('#impresion').css({'display':'none'}); 
      $('.mostrarrangos').hide(); 
      $('.rangoempleado').hide(); 
      $('.empleadocheck').show(); 
    }
    
    if ($("#mostrarimprimir").prop("checked")){

      $('.mostrarrangos').show();     
      $('#mostrarimprimir').show();   
      $('#divVisual').hide(); 
      $('#impresion').css({'display':'inline'});
      
      if(($("#codigouno").val()!="" && $("#codigodos").val()=="") || ($("#codigodos").val()!="" && $("#codigouno").val()=="")){
        alert("seleccione un rango valido.");
        
      }else{ 

        $("#contPerce").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>');     
        $('#imprimir').show(); 
        $('#pdf').css({'display':'none'}); 
        ContPercepciones();
      }
    }
  });
});

//INICIA GENERA PDF
function pdf(){

  /*E M P I E Z A  T A B L A  V I S U A L *REPORTE PRENOMINA**/
  var table = $('#divVisualx').DataTable();
  table.destroy();
  
  $(".coluno").removeAttr("width"); 
  $(".colemple").removeAttr("width");        
  $('.siz').css({'fontWeight':'bold','fontSize':'11px'}); 
  
  
  /*T E R M I N A  R E P O R T E  P R E N O M I N A*/
  $('#tabladucio').removeClass('border').removeClass('table');
  $(".mostrar").show();
  $("#tabl").removeAttr("width"); 
  $("#img").removeAttr("width"); 
  $(".linpdf").removeAttr("width");
  $('.brmail').css({'display':'none'});
  $('.estilopdf').css({'color':'#FFFFFF','fontSize':'10px'});
  $('#neto').css({'fontSize':'9px'});
  $('#emple').css({'fontWeight':'bold'});
  $('.estinegrit').css({'fontWeight':'bold','fontSize':'11px'});  
  $('.parrafopdf').css({'fontSize':'10px'});
  $('#linpdf').css({'width': '100% '});
  $('#tabl').css({'width':'185%'});
  $('.paddinpdf').css({'padding-right':'330px'});
  
  
  /*E M P I E Z A  T A B L A  V I S U A L *REPORTE PRENOMINA**/
  
  $('.coluno').css({'width': '55px '});
  $('.colemple').css({'width': '85px '});
  $('.taman').css({'fontSize':'8px'});
  
  /*T E R M I N A  R E P O R T E  P R E N O M I N A*/
  
  
  var contenido_html = $("#imprimible").html();
  /*PRENOMINA*/
  
  var extensions = {
    "sLength": "custom_length_class text-left", 
    "sInfo": 'text-left'
  }
  
  $.extend($.fn.dataTableExt.oStdClasses, extensions);
  $.extend($.fn.dataTableExt.oJUIClasses, extensions);
  
  $('#divVisualx').DataTable( {
    "scrollX": true,
    "language": {
      "url": "js/Spanish.json"
    },
    "lengthMenu": [ 5,10, 25, 50, 75, 100 ]
  } ); 
  $('.taman').css({'fontSize':'13px'});
  /*TERMINA PRENOMNA*/
  
  $("#contenido").text(contenido_html);
  $("#divpanelpdf").modal('show');
  $('.estilopdf').css({'color':'white','fontSize':'16px'});
  $('#neto').css({'fontSize':'14px'});
  $('.estinegrit').css({'fontWeight':'bold','fontSize':'14px'});
  $('.encabpdf').css({'color':'black'});
  $('.parrafopdf').css({'fontSize':'13px'});
  $('#tabl').css({'width':'20%'});
  $(".mostrar").hide();
  $('.siz').css({'fontWeight':'bold','fontSize':'14px'}); 
  
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


// EMPIEZA GENERAR IMPRIMIR
function printl(){

  $('.firma').addClass('mostrardiv');

  setTimeout(function () { 
   
    window.close();
    $('.firma').removeClass('mostrardiv');  
  }, 500);
  
}


function listaRaya(){
  $('.firma').removeClass('mostrar');

  setTimeout(function () { 
    window.close();

    
  }, 1000);
  






}

