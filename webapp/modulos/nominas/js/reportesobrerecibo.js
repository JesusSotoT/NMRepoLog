
$(document).ready(function(){

  $('#impresion').css({'display':'none'});
  $('.mostrarrangos').hide(); 
  $('.rangoempleado').hide(); 
  
});

function validacionesDeSelect(){

  $("#empleado").val('*').selectpicker('refresh');
  $("#idnomp").val('*').selectpicker('refresh');
  $("#idtipop").val('*').selectpicker('refresh');
  $("#codigouno").val('').selectpicker('refresh');
  $("#codigodos").val('').selectpicker('refresh');
   $('#imprimir').hide(); 
}

function activarChecked(){
  if ($("#mostrarvisual").prop("checked")){
        //alert("aa");  
        validacionesDeSelect();
        $('#mostrarvisual').prop('checked', true);
        $('#mostrarimprimir').prop('checked', true);
        $('#mostrarimprimir').show(); 
        $('.mostrarrangos').show(); 
       
      }
      else{
        //alert("bb");
        validacionesDeSelect();
        $('#mostrarimprimir').prop('checked', false);   
        $('#divVisual').hide();   
        $('#impresion').css({'display':'none'}); 
        $('.mostrarrangos').hide(); 
        $('.rangoempleado').hide(); 
        $('.empleadocheck').show(); 
      }
    }

    function activarCheckeddos(){
      if ($("#mostrarimprimir").prop("checked")){
        //alert("cc"); 
        validacionesDeSelect();
        $('#mostrarvisual').prop('checked', true);
        $('#mostrarimprimir').prop('checked', true);     
        $('#divVisual').hide(); 
        $('.mostrarrangos').css({'display':'none'});
        $('.rangoempleado').hide(); 
        $('.empleadocheck').show(); 

      }else{
          //alert("dd");
          validacionesDeSelect();
          $('.mostrarrangos').show();  
          $('#mostrarrangos').prop('checked', false);
          $('#mostrarvisual').prop('checked', false);     
          $('#mostrarimprimir').show(); 
          $('#divVisual').hide(); 
          $('#impresion').css({'display':'inline'});

        }
      }


      function activarCheckedtres(){
        if ($("#mostrarrangos").prop("checked")){
        //alert("p");
        validacionesDeSelect();          
        $('.empleadocheck').show();
        $('.rangoempleado').hide();
       


      }else{
        // alert("q"); 
        validacionesDeSelect();  
        $('.empleadocheck').hide(); 
        $('.rangoempleado').show();
      }
    }

    function ContPercepciones(idtipop,idnomp,idEmpleado,codigouno,codigodos){
     var idtipop    = $("#idtipop").val();
     var idnomp     = $("#idnomp").val();
     var idEmpleado = $("#empleado").val();
     var codigouno  = $("#codigouno").val();
     var codigodos  = $("#codigodos").val();

     $.post("ajax.php?c=Reportes&f=cargaPerceFiltros",{
      idtipop:idtipop,
      idnomp:idnomp,
      idEmpleado:idEmpleado,
      codigouno:codigouno,
      codigodos:codigodos

    },function (request){
      $("#contPerce").html(request);   
    });
   }


   $(function() {
    $('#idtipop').on('change', function(){
     valip = $(this).val(); 

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
    //alert("boto");  
    var  idEmpleado = $("#empleado").val();
    var  idnomp     = $("#idnomp").val();
    var  idtipop    = $("#idtipop").val();

    if ($("#mostrarvisual").prop("checked")){
      $.post("ajax.php?c=Reportes&f=tablaReporteSobrerecibo",{
        idtipop:idtipop,
        idnomp:idnomp,
        idEmpleado: idEmpleado
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
            "language": {
              "url": "js/Spanish.json"
            },
            "lengthMenu": [ 5,10, 25, 50, 75, 100 ]
          } );            
        });
      $("#divVisual").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>');           
      $('#divVisual').show();

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
       ContPercepciones();

     }
   }

 });

  });
