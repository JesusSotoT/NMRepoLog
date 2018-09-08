
$(document).ready(function(){

 $('.tablacumulado').DataTable({
  "language": {
    "url": "js/Spanish.json"
  },

  "lengthMenu": [ 5,10, 25, 50, 75, 100 ],

  "footerCallback": function ( row, data, start, end, display ) {
    var api = this.api(), data;

            // converting to interger to find total
            var intVal = function ( i ) {
              return typeof i === 'string' ?
              i.replace(/[\$,]/g, '')*1 :
              typeof i === 'number' ?
              i : 0;
            };

            // computing column Total of the complete result 
            var monTotal = api
            .column(2)
            .data()
            .reduce( function (a, b) {
              return intVal(a) + intVal(b);
            }, 0 );

            var wedTotal = api
            .column(3)
            .data()
            .reduce( function (a, b) {
              return intVal(a) + intVal(b);
            }, 0 );

            // Update footer by showing the total with the reference of the column index 
            $( api.column(2).footer()).html(numeral(monTotal).format('$0,0.00'));
            $( api.column(3).footer()).html(numeral(wedTotal).format('$0,0.00'));
            uno=numeral(monTotal).format('00.00');
            dos=numeral(wedTotal).format('00.00')
            resta=uno-dos;

            if (dos>uno) {

             $(api.column(7).footer()).html(numeral(wedTotal).format('$0,0.00'));



           }else{

            $(api.column(7).footer()).html(numeral(resta).format('$0,0.00'));
          }
        },

      } );

} );


function sumaPercep() {
  var sum = 0;
  $(".importePercepciones").each(function(){
    
    c = parseFloat($(this).html().replace(",",""));
    if (!isNaN(c)) {
      sum += parseFloat($(this).html().replace(",",""));
    }
    // alert(sum);
  });
  
  $("#tdSumaPercepciones").html(numeral(sum).format('$0,0.00'));

};


function sumaDeduccion() {
  var sum = 0;
  $('.importeDeducciones').each(function() {
    c= parseFloat($(this).html().replace(",",""));

    if (!isNaN(c)) {
      sum += parseFloat($(this).html().replace(",",""));    
    }
    // alert(sum);
  });

  $("#tdSumaDeducciones").html(numeral(sum).format('$0,0.00'));
  resta();

};

function resta(){

  var perce = $("#tdSumaPercepciones").html().replace(',', '');
  var x = perce.replace('$', '');
  // alert(x);

  var deduccion = $("#tdSumaDeducciones").html().replace(',', '');
  var y = deduccion.replace('$', '');
  
  if (y>x) {
    //alert("mayor dedu");
    var total =(numeral(deduccion).format('00.00')-numeral(perce).format('00.00'));
    $("#resta").html(numeral(total).format('$0,0.00'));
  }else{
    //alert("mayor perce");
    var total =(numeral(perce).format('00.00') - numeral(deduccion).format('00.00'));
    $("#resta").html(numeral(total).format('$0,0.00'));
  }
}

$(function() {
  $('#nombre').on('change', function(){
   var v=$("#nombre>option:selected").val();
   $("#period").val(v);
 });


  $('#nominasdos').on('change', function(){

    var v=$("#nominasdos>option:selected").text();

    

    if(v != "Todos"){
      var i = v.split(")"), j = i[3], k = i[1]; 
      var cadena =k; 
      uno = cadena.substring(11,0);
      dos = cadena.substring(22,12);
      $("#nomidos").val(dos);
    }

  });


  $('#nominas').on('change', function(){
    var v=$("#nominas>option:selected").text();
    
    if(v != "Todos"){
      var i = v.split(")"), j = i[3], k = i[1]; 
      var cadena =k;  
      $("#extraord").val(k);
      
      uno = cadena.substring(11,0);
      dos = cadena.substring(22,12);
      $("#nomi").val(uno);
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
    if($("#period").val() !='3' && $("#nominas").val() !='*' && $("#nominasdos").val() =='*' ){

      alert("Campo obligatorio.");
      evt.preventDefault();
      $(this).button('reset'); 

    }
    else{
     $("#formAcumulado").submit();

   }

 });

  sumaPercep();
  sumaDeduccion();
  resta();
});


function mailNominas(xml,correo){
  if (correo=="") {
    correo="@netwarmonitor.com";
  }else{
    correo=correo;
  }
  var msg = "Registre el correo electrónico a quién desea enviarle el XML:";
  var a = prompt(msg,correo);
  if(a!=null){
    $("#loading").fadeIn(500);
    $("#divmsg").load("mail.php?a="+a, {xml:xml});
  }
}


  //INICIA GENERA PDF
  function pdf(){

    $('.siz').css({'fontWeight':'bold','fontSize':'11px'}); 
    var table = $('.tablacumulado').DataTable();
    table.destroy();

    $('.saltopagina').css({'display':'block'});
    $('.saltopagina').css({'page-break-before':'always'});
    $('.tama').css({'fontWeight':'bold','fontSize':'10px','height':'20px'}); 
    $('.gene').css({'fontWeight':'bold','height':'20px','padding-left':'90px'});  

    $(".clav").removeAttr("width");
    $(".conc").removeAttr("width");
    $(".perce").removeAttr("width"); 
    $(".dedu").removeAttr("width"); 
    $(".grav").removeAttr("width"); 
    $(".exen").removeAttr("width"); 
    $(".peri").removeAttr("width"); 
    $(".nomi").removeAttr("width");
    $(".orig").removeAttr("width");


    $('.clav').css({'width':'35px'});
    $('.conc').css({'width':'75px'});
    $('.perce').css({'width':'60px'});
    $('.dedu').css({'width':'60px'});
    $('.grav').css({'width':'60px'});
    $('.exen').css({'width':'50px'});
    $('.peri').css({'width':'60px'});
    $('.nomi').css({'width':'60px'});
    $('.orig').css({'width':'50px'});

    var contenido_html = $("#imprimible").html();

    $("#contenido").text(contenido_html);
    $('.tablacumulado').DataTable( {
      "language": {
        "url": "js/Spanish.json"
      }
    });
    $('.tama').css({'fontWeight':'bold','fontSize':'14px'}); 
    $('.siz').css({'fontWeight':'bold','fontSize':'14px'}); 
    $('#totales').removeClass('tama');
    $("#divpanelpdf").modal('show');
    $('#totales').removeClass('tama');
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
  
  // COMIENZA GENERAR MAIL
  function mail(){

    var msg = "Registre el correo electrónico a quién desea enviarle el reporte:";
    var a = prompt(msg,"@netwarmonitor.com");
    if(a!=null){
      var table = $('.tablacumulado').DataTable();
      table.destroy();

      var html_contenido_reporte;
      html_contenido_reporte = $("#imprimible").html();
      
      $("#loading").fadeIn(500);
      $("#divmsg").load("../../../webapp/netwarelog/repolog/mail.php?a="+a, {reporte:html_contenido_reporte});
    }
  } 