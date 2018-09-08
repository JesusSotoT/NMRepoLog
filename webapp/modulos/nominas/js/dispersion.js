$(document).ready(function(){
  $(".check").attr('checked', false);

  $(".check").on('click',function(i, o) { 
    $(this).parents("tr").toggleClass("selected");
  });

  var tippago='';
  $('#tipopago').on('change', function(){ 

    tippago=$("#tipopago>option:selected").val();



    if (tippago==1) {

      $("#txtfechafin").prop('disabled', false);
    }else{
      $("#txtfechafin").prop('disabled', true);
    }
  });


  function replaceAll( text, busca, reemplaza ){
    while (text.toString().indexOf(busca) != -1)
      text = text.toString().replace(busca,reemplaza);
    return text;
  }
  replaceAll("123.345.567", ".", "" );

  var suma=0;

  $(".numbersOnly").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        //$("#emisora").html("Digits Only").show().fadeOut("slow");
        return false;
      }
    });



  $('.check').on('change', function(){

   var table =  $("#tablanueva").DataTable();
   var data1 = table.rows('.selected').data().toArray();
   var cant = data1.length;
   var suma=0;
   for (var i = 0 ; i< data1.length; i++){
    suma += Number(data1[i][4]);
  }


  x=(suma).toFixed(2);
  y=replaceAll(x, ".", "" ); 

  $("#numchecks").val(cant);

  $("#total").val(x);
  $("#totalformt").val(y);
  $("#cantEmpleSeleccion").val(cant);

})


  returnFormat  = function(text, maxlength){ 
    var a = text;
    return  "0".repeat( maxlength - a.length) + a; 
  }



  $('.tablainicio').DataTable( {
    "language": {
      "url": "js/Spanish.json"
    },"bLengthChange" : false,
    "scrollX": true
  } );


  /*TXT*/
  function descargarArchivo(contenidoEnBlob, nombreArchivo) {
    emisora= returnFormat($('#emisora').val(), $('#emisora').attr('maxlength'));
    consec= returnFormat($('#consecutivo').val(), $('#consecutivo').attr('maxlength'));

    nombre ='NI'+emisora+consec;
   // alert(nombre);
   
   if ($("#emisora").val()!='' && $("#consecutivo").val()!='' && $("#tipoRegistro").val()!='' 
    && $("#claveServicio").val()!='' && $("#txtfecha").val()!='' && $("#cuentacargo").val()!='' && $("#tipopago").val()!=''  && $("#numchecks").val()!=0 ) {


    var reader = new FileReader();
  reader.onload = function (event) {
    var save = document.createElement('a');
    save.href = event.target.result;
    save.target = '_blank';
      // save.download = nombreArchivo || 'archivo.dat';
      save.download = nombre+'.pag';
      var clicEvent = new MouseEvent('click', {
        'view': window,
        'bubbles': true,
        'cancelable': true
      });
      save.dispatchEvent(clicEvent);
      (window.URL || window.webkitURL).revokeObjectURL(save.href);

    };
    reader.readAsDataURL(contenidoEnBlob);
  
  }
alert("Registros guardados:"+$("#numchecks").text());
 // !=''  && $("#numchecks").val()
  
 window.location.reload(true);
  

}

//Función de ayuda: reúne los datos a exportar en un solo objeto
function obtenerDatos() {
  //alert(bancorecep);
  pago=tippago;


  var refeServi = " ".repeat(40);
  var refeLeyeOr = " ".repeat(40);
  var accion=" ".repeat(1);
  var fillerdos=" ".repeat(8);
  check=($("#numchecks").val()); 

  var str = $("#txtfecha").val();


  fecha=str.replace(/[^a-zA-Z 0-9.]+/g,'');

  if (pago==2) {
    var fechafin=$("#txtfechafin").val();
    fechafin=fechafin.replace(/[^a-zA-Z 0-9.]+/g,'');
  }else{
    var fechafin='00000000';

  }

  return {
    tipoRegistro: returnFormat($('#tipoRegistro').val(), $('#tipoRegistro').attr('maxlength')),
    claveServicio:returnFormat($('#claveServicio').val(), $('#claveServicio').attr('maxlength')),
    emisora:      returnFormat($('#emisora').val(), $('#emisora').attr('maxlength')),
    txtfecha:fecha,
    consecutivo:  returnFormat($('#consecutivo').val(), $('#consecutivo').attr('maxlength')),
    check:        returnFormat($('#numchecks').val(), $('#numchecks').attr('maxlength')),
    totalformt:   returnFormat($('#totalformt').val(), $('#totalformt').attr('maxlength')),
    naenviadas:   returnFormat($('#naenviadas').val(), $('#naenviadas').attr('maxlength')),
    iaenviadas:   returnFormat($('#iaenviadas').val(), $('#iaenviadas').attr('maxlength')),
    nbenviadas:   returnFormat($('#naenviadas').val(), $('#naenviadas').attr('maxlength')),
    ibenviadas:   returnFormat($('#iaenviadas').val(), $('#iaenviadas').attr('maxlength')),
    cuentaverif:  returnFormat($('#cuentaverif').val(), $('#cuentaverif').attr('maxlength')),
    tipopago:     returnFormat($('#tipopago').val(), $('#tipopago').attr('maxlength')),
    espacios:     returnFormat($('#espacios').val(), $('#espacios').attr('maxlength')),
    fechafin:fechafin,
    cuentacargo:     returnFormat($('#cuentacargo').val(), $('#cuentacargo').attr('maxlength')),
    filler:          returnFormat($('#filler').val(), $('#filler').attr('maxlength')),
    tiporegistro:    returnFormat($('#tiporegistro').val(), $('#tiporegistro').attr('maxlength')),
    txtfecha:fecha,
    refeServi:refeServi,
    refeLeyeOr:refeLeyeOr,
    tipMovim:returnFormat($('#tipMovim').val(), $('#tipMovim').attr('maxlength')),
    accion:accion,
    importIva:returnFormat($('#importIva').val(), $('#importIva').attr('maxlength')),
    fillerdos:fillerdos
  };
};


//Genera un objeto Blob con los datos en un archivo TXT
function generarTexto(datos) {


  var texto=[];
  // texto.push(datos.tipoRegistro);
  texto.push(datos.claveServicio);
  texto.push(datos.emisora); 
  texto.push(datos.txtfecha);
  texto.push(datos.consecutivo);
  texto.push(datos.check);
  texto.push(datos.totalformt);
  texto.push(datos.naenviadas);
  texto.push(datos.iaenviadas);
  texto.push(datos.nbenviadas);
  texto.push(datos.ibenviadas);
  texto.push(datos.cuentaverif);
  texto.push(datos.tipopago);
  texto.push(datos.espacios);
  texto.push(datos.fechafin);
  texto.push(datos.cuentacargo);
  texto.push(datos.filler); 

  var yx="";
  var table =  $("#tablanueva").DataTable();
  var data1 = table.rows('.selected').data().toArray();  
  for (var i = 0 ; i< data1.length; i++){

    texto.push('\n');
    texto.push(datos.tiporegistro);
    texto.push(datos.txtfecha); 
    texto.push( returnFormat(data1[i][1],10));
    texto.push(datos.refeServi);
    texto.push(datos.refeLeyeOr);
    yx=replaceAll( data1[i][4]  , ".", "" );
    texto.push(returnFormat(yx,15));  
    texto.push(data1[i][5]);
    texto.push(returnFormat(data1[i][6] ,2));
    texto.push(data1[i][7]);
    texto.push(datos.tipMovim);
    texto.push(datos.accion);
    texto.push(datos.importIva);
    texto.push(datos.fillerdos);

  }
      // Agregamos nuestro valor al arreglo
      return new Blob(texto, {
        type: 'text/plain'
      });
    }




    document.getElementById('guardar').addEventListener('click', function () {
      var datos = obtenerDatos();
      descargarArchivo(generarTexto(datos), 'archivo.pag');
    }, false);


    $('#tablanueva').DataTable( {

      "language": {
        "url": "js/Spanish.json"
      },
      "bLengthChange" : false,
      "scrollX": true,
      select : {
        style : "multi",
        className : "selected"
      } 
    });

    $('#fecha').datetimepicker({
      format: 'YYYY-MM-DD',
      ignoreReadonly: true,
      useCurrent: false ,
      locale: 'es'
    });

    $('#fechafin').datetimepicker({
      format: 'YYYY-MM-DD',
      ignoreReadonly: true,
      useCurrent: false ,
      locale: 'es'
    });


  });
function atraslistado(){

  window.location ="index.php?c=Dispersion&f=dispersion";
}
function newDispersion(){

  window.location="index.php?c=Dispersion&f=cargaDeDatos";
}


$(function() {

  $('#tipoperiodo').on('change', function(){
    var v=$("#tipoperiodo>option:selected").text();
    $("#descperiodo").val(v);
            //alert("no esta vacio");
            if ( $("#descperiodo").val()!='') {

              $("#formdispersion").submit();    
            }
          });


  $('#guardar').on('click',function(event) {
   if ($("#emisora").val()=='' || $("#consecutivo").val()==''  || $("#tipoRegistro").val()=='' 
     || $("#claveServicio").val()==''  || $("#txtfecha").val()==''  || $("#cuentacargo").val()==''  || $("#tipopago").val()==''  || $("#numchecks").val()==0 ) {
    alert("Llene todos los campos.");
}
if ($("#emisora").val()!='' && $("#consecutivo").val()!='' && $("#tipoRegistro").val()!='' 
  && $("#claveServicio").val()!='' && $("#txtfecha").val()!='' && $("#cuentacargo").val()!='' && $("#tipopago").val()!='' && $("#numchecks").val()!=0 ) {


var empleId = $("#empleId").val();
var nominadesc=$("#nominadesc").val();
var consecutivo=$("#consecutivo").val();
var fechainicio=$("#fechainicio").val();
var txtfecha=$("#txtfecha").val();
var tipopago=$("#tipopago").val();
var table =  $("#tablanueva").DataTable();
var data1 = table.rows('.selected').data().toArray();

$.post("ajax.php?c=Dispersion&f=actualizaStatus",{ 
  empleId:empleId,
  nominadesc:nominadesc,
  consecutivo:consecutivo,
  fechainicio:$("#fechainicio").val(),
  txtfecha:$("#txtfecha").val(),
  tipopago:tipopago,
  tableData : JSON.stringify(data1)


},function(resp){
 //  alert("Registros guardados:"+$("#numchecks").text());
 // // !=''  && $("#numchecks").val()
  
 // window.location.reload();
 //document.location.reload(true);

 if(parseInt(resp) != 1){
  alert("Error en el procceso intente de nuevo");

}

}); 
}

});



});


function accionEliminarDispersion(idEmpleado,idnomp){

 var confirma = confirm("¿Esta seguro que desea eliminar el concepto?");
 if (confirma == true) {

  $.post("ajax.php?c=Dispersion&f=accionEliminarDispersion",{
    idEmpleado:idEmpleado,       
    idnomp:idnomp

  },function(request){
    if(request == 1 ){
      alert("Datos eliminados.");
      window.location.reload();
    }
    else{
      alert("Error en el proceso.");
    } 
  });

  return true;
}else{

  window.close();
}
$("#"+load).hide();


}
