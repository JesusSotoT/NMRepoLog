  function reloadtable(){
    $('#modal_form_gasto').modal('hide');
    $('#modal_form_ayudante_add').modal('hide');
    $('#modal_form_anticipo_add').modal('hide');
    $('#modal_form_ayudante').modal('hide');
    $('#modal_form_carta').modal('hide');
      $.ajax({
        url: 'ajax.php?c=ordenservicio&f=listaOS',
        type: 'POST',
        dataType: 'json',
      })
                    .done(function(data) {
                          console.log(data);

                        var table = $('#table_list_OS').DataTable();
                            //$('.filas').empty();
                            table.clear().draw();
                            var x ='';
                            $.each(data, function(index, val) {
                                x ='<tr idConvenio="'+val.idordenservicio+'" class="filas">'+
                                                '<td>'+val.idordenservicio+'</td>'+
                                                '<td>'+val.fecha+'</td>'+
                                                '<td><a class="btn btn-info" onclick="editOS('+val.idsolicitud+','+val.idordenservicio+')"><i class="glyphicon glyphicon-file"></i>'+val.idsolicitud+'</a></td>'+
                                                '<td>'+val.operador+'</td>'+
                                                '<td>'+val.ne+'</td>'+
                                                '<td>'+val.cliente+'</td>'+
                                                '<td>'+val.entrega+'</td>'+
                                                '<td>'+val.viaje+'</td>'+
                                                '<td>'+val.municipio+'</td>'+
                                                '<td>'+val.estado+'</td>'+
                                                '<td>'+val.capacidad+'</td>'+
                                                '<td>'+val.temperatura+'</td>'+
                                                '<td><a class="btn btn-sm btn-success col" title="Gasto" onclick="gasto('+val.idordenservicio+','+val.idsolicitud+')"><i class="glyphicon glyphicon-usd"></i></a>'+
                                                    '<a class="btn btn-sm btn-warning" title="Bitacora" onclick="bitacora('+val.idordenservicio+','+val.idsolicitud+')"><i class="glyphicon glyphicon-pushpin"></i></a>'+
                                                    '<a class="btn btn-sm btn-info" title="Carta" onclick="cartaporte('+val.idordenservicio+','+val.idsolicitud+')"><i class="glyphicon glyphicon-paperclip"></i></a>'+
                                                    '<a class="btn btn-sm btn-success" title="Anticipo" onclick="anticipo('+val.idordenservicio+','+val.idsolicitud+')"><i class="glyphicon glyphicon-usd"></i></a>'+
                                                    '<a class="btn btn-sm btn-primary" title="Impresion" onclick="impresion('+val.idordenservicio+','+val.idsolicitud+')"><i class="glyphicon glyphicon-print"></i></a>'+
                                                    '<a class="btn btn-sm btn-danger" title="Borrar" onclick="borrarOS('+val.idordenservicio+','+val.idEmpleado+','+val.idcajatractor+','+val.idunidad+')"><i class="glyphicon glyphicon-trash"></i></a>'+
                                                '</td>'+
                                                '</tr>';
                                    table.row.add($(x)).draw();
                            });

                    })
  }





function editOS(idsolicitud,idordenservicio){
 $("#idsolicitud").val(idsolicitud);
 editSolicitud(idsolicitud);
 reload_table_con(idsolicitud);
 //asignar_solicitud(idsolicitud);
espesifico = "SI";
    $.ajax({
      data : {idordenservicio:idordenservicio},
      url: 'ajax.php?c=OrdenServicio&f=listaAsignacion',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
      $.each(data, function(index, val) {
        $("#fechaA").val(val.fecha);
        $("#logistica").val(val.no_logistica);
        idEmpleado = val.idEmpleado;
        idunidad = val.idunidad;
        idcajatractor = val.idcajatractor;

      });
      asignar_solicitud(idsolicitud, idEmpleado, idunidad, idcajatractor, idordenservicio, espesifico);
    })

}

  function hoy(){
    var hoy = new Date();
    var dd = hoy.getDate();
    var mm = hoy.getMonth()+1; //January is 0!

    var yyyy = hoy.getFullYear();
    if(dd<10){
        dd='0'+dd
    }
    if(mm<10){
        mm='0'+mm
    }
     var hoy = yyyy+'-'+mm+'-'+dd;
     return hoy;
    }

    function cartaporte(idordenservicio, idsolicitud){
    limpiarCarta();
    $('#modal_form_ayudante_add').modal('hide');
    $('#modal_form_anticipo_add').modal('hide');
    $('#modal_form_gasto_add').modal('hide');
    $('#modal_form_ayudante').modal('hide');
    $('#modal_form_anticipo').modal('hide');
    $('#modal_form_gasto').modal('hide');
    //$('#modal_form_carta').modal('show');
 /// Analiza si la orden de servicio ya tiene una carta porte
          $.ajax({
            data : {idordenservicio:idordenservicio},
            url: 'ajax.php?c=OrdenServicio&f=listaOS1',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
                $.each(data, function(index, val) {
                  carta = val.carta_porte; 
                });
                if(carta == "SI"){
                  //alert("Ya Tiene Carta Porte");
                  limpiar = "NO";
                  $('#btnPrint').show();
                  $('#btnSave').hide();
                  $("#valoruni").attr('readonly','readonly');
                  $("#valordec").attr('readonly','readonly');
                  $("#idcarta").attr('readonly','readonly');

                  $(".textarea").attr('readonly','readonly');
                  $(".noCarta").show();
                }else{
                  //alert("No Tiene Carta Porte");
                  limpiar = "SI";
                  $('#btnPrint').hide();
                  $('#btnSave').show();
                  $("#valoruni").attr('readonly', false).val('');
                  $("#valordec").attr('readonly',false).val('');
                  $(".noCarta").hide();
                } /// FIN ELSE
/////////                                                
                  $.ajax({
                  data : {folio:idsolicitud,espesifico:idordenservicio},
                  url: 'ajax.php?c=OrdenServicio&f=listaRelSolCon',
                  type: 'POST',
                  dataType: 'json',
                  })

                  .done(function(data) { // 2
                  var flete = 0, seguro = 0, maniobras = 0, autopistas = 0, otros = 0, subtotal = 0, iva = 0, suma = 0, retencion = 0, total = 0;
                  var table1 = $('#table_list_conv').DataTable({
                  "bPaginate": false,
                  "bLengthChange": false,
                  "bFilter": false,
                  "bInfo": false,
                  "bAutoWidth": false,
                  "bDestroy": true /// permite destruit al volver a recargar
                  });
                  table1.clear().draw();
                  var x ='';
                  $.each(data, function(index, val) {
                  if(val.iddesccorta == 1){ /// <- id de FLETE en tabla de descripcion corta
                  flete = flete + val.precio_cliente * val.cantidad;
                  }
                  if(val.iddesccorta == 3){ /// <- id de MANIOBRAS en tabla de descripcion corta
                  maniobras = maniobras + val.precio_cliente * val.cantidad;
                  }

                  if(val.retencion > 0){ /// <- id de MANIOBRAS en tabla de descripcion corta
                  retencion = retencion + val.precio_cliente * (val.retencion / 100);
                  }
                  //alert(limpiar);
                  observaciones = ""; 
                  if(limpiar == "SI"){

                  observaciones = "";
                  }
                  if(limpiar == "NO"){
                  observaciones = val.observaciones;
                  } 
                  x ='<tr class="filas">'+
                  '<td style="display:none;">'+val.idrel+'</td>'+
                  '<td>'+val.cantidad+'</td>'+
                  '<td>'+val.descripcion+'</td>'+
                  '<td>'+val.precio_cliente+'</td>'+
                  '<td><textarea id="obser_rel'+val.idrel+'" class="form-control textarea" rows="2">'+observaciones+'</textarea></td>'+
                  '</tr>';                
                  table1.row.add($(x)).draw();    

                  if(limpiar == "NO"){
                  $(".textarea").prop('readonly',true);
                  }                       
                  });
                  subtotal = flete + seguro + maniobras + autopistas + otros;
                  iva = subtotal * (16 / 100);
                  suma = subtotal + iva;
                  total = suma - retencion;
                  $("#flete").val(flete);
                  $("#seguro").val(seguro);
                  $("#maniobras").val(maniobras);
                  $("#autopistas").val(autopistas);
                  $("#otros").val(otros);
                  $("#subtotal").val(subtotal);
                  $("#iva").val(iva);
                  $("#suma").val(suma);
                  $("#retencion1").val(retencion);
                  $("#total").val(total);

                  /////////
                  $.ajax({
                  data:{idordenservicio:idordenservicio},
                  url: 'ajax.php?c=OrdenServicio&f=listaCartaPorte',
                  type: 'POST',
                  dataType: 'json',
                  })
                  .done(function(data){ //3
                  $.each(data, function(index, val){

                  if(limpiar == "SI"){
                  $("#valoruni").val('');
                  $("#valordec").val('');
                  }else{
                  $("#valoruni").val(val.valor_unitario);
                  $("#valordec").val(val.valor_declarado);
                  }
                  $("#idcarta").val(val.idcartaporte);
                  $("#Corigen").val(val.origen);
                  $("#COremitente").val(val.remitente);
                  $("#COrfc").val(val.rfc);
                  $("#COdomicilio").val(val.domicilio);
                  $("#COrecojer").val(val.recoger);

                  $("#Cdestino").val(val.destino);
                  $("#CDdestinatario").val(val.destinatario);
                  $("#CDdomicilio").val(val.domicilioe);
                  $("#CDentregar").val(val.entrega_en);



                  $("#operador").val(val.operador);
                  $("#unidad").val(val.no_economico );
                  $("#placas").val(val.placas);

                  $("#condiciones").val(val.condicionpago);
                  $("#observ").val("Orden de Servicio "+idordenservicio);
                  });

                  $('#modal_form_carta').modal('show'); 

                  }) // done 3





                  }) // done 2


                  }) // done principal
                
                  hoy();
                  $('#Cfecha').val(hoy);
                  $('#Cfecha').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
                  $('#idorden').val(idordenservicio);
                  $('#idsolicitud').val(idsolicitud);           
  
  }
  function save_carta(){

    idordenservicio = $('#idorden').val();
    idsolicitud = $('#idsolicitud').val();

    fecha = $("#Cfecha").val();
    valoruni = $("#valoruni").val();
    valordec = $("#valordec").val();
    condiciones = $("#condiciones").val();

    ////////////////////////////// CH  ///////////////////////////////////
    var string = "";
    $(function () { /// funcion para recorrer tabla y tomar los valores de
    var campo1, campo2, json="";
    $("#table_list_conv tbody tr").each(function (index)
    {
    $(this).children("td").each(function (index2)
    {
    switch (index2)
    {
    case 0: campo1 = $(this).text(); // obteniendo el valor de del id mediante this del primer elemento
    break;
    case 1: campo2 = $("#obser_rel"+campo1+"").val(); // obteniendo el valor del textarea mediante id
    break;
    }
    })
    json +='{"idrel":"'+campo1+'","obser":"'+campo2+'"}'+','; // formando una cadena con estilo json
    })
    $.ajax({
    data : {json:json},
    url: 'ajax.php?c=ordenservicio&f=editRelSolCon',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data){
    console.log(data);
    })
    })

    $.ajax({
    data : {idordenservicio:idordenservicio,fecha:fecha,valoruni:valoruni,valordec:valordec,condiciones:condiciones},
    url: 'ajax.php?c=ordenservicio&f=saveCartaPorte',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data){
    console.log(data);
    $('#modal_form_carta').modal('hide');
    limpiarCarta();
    })


    }
  function limpiarCarta(){
  $("#tbodyobs").html('').attr('readonly','readonly');
  $("#lugarexp").val('').attr('readonly','readonly');
  $("#Cfecha").val('').attr('readonly','readonly');
  $("#Corigen").val('').attr('readonly','readonly');
  $("#COremitente").val('').attr('readonly','readonly');
  $("#COrfc").val('').attr('readonly','readonly');
  $("#COdomicilio").val('').attr('readonly','readonly');
  $("#COrecojer").val('').attr('readonly','readonly');
  $("#Cdestino").val('').attr('readonly','readonly');
  $("#CDdestinatario").val('').attr('readonly','readonly');
  $("#CDrfc").val('').attr('readonly','readonly');
  $("#CDdomicilio").val('').attr('readonly','readonly');
  $("#CDentregar").val('').attr('readonly','readonly');

  $("#valoruni").val('');
  $("#valordec").val('');
  $("#condiciones").val('').attr('readonly','readonly');

  $("#flete").val('').attr('readonly','readonly');
  $("#seguro").val('').attr('readonly','readonly');
  $("#maniobras").val('').attr('readonly','readonly');
  $("#autopistas").val('').attr('readonly','readonly');
  $("#otros").val('').attr('readonly','readonly');
  $("#subtotal").val('').attr('readonly','readonly');
  $("#iva").val('').attr('readonly','readonly');
  $("#suma").val('').attr('readonly','readonly');
  $("#retencion1").val('').attr('readonly','readonly');
  $("#total").val('').attr('readonly','readonly');

  $("#operador").val('').attr('readonly','readonly');
  $("#unidad").val('').attr('readonly','readonly');
  $("#placas").val('').attr('readonly','readonly');
  $("#numletra").val('').attr('readonly','readonly');
  $("#observ").val('').attr('readonly','readonly');
  $('#modal_form_gasto').modal('hide');
  $('#modal_form_ayudante_add').modal('hide');
  $('#modal_form_anticipo_add').modal('hide');
  $('#modal_form_ayudante').modal('hide');
  $('#modal_form_carta').modal('show');
}
function gasto(idordenservicio){

  $('#AGidSO').val(idordenservicio);
  $('#idOSgasto').val(idordenservicio);
  $('#modal_form_gasto').modal('show');
  $('#modal_form_ayudante_add').modal('hide');
  $('#modal_form_anticipo_add').modal('hide');
  $('#modal_form_ayudante').modal('hide');
  $('#modal_form_carta').modal('hide');
  reloadtable_gastox(idordenservicio);
}
function anticipo(idordenservicio){
  reloadtable_anticipo(idordenservicio);
  $('#modal_form_ayudante_add').modal('hide');
  $('#modal_form_anticipo_add').modal('hide');
  $('#modal_form_ayudante').modal('hide');
  $('#modal_form_carta').modal('hide');
  $('#idOSanticipo').val(idordenservicio);

}
function bitacora(idordenservicio){
  $('#modal_form_addgasto').modal('hide');
  $('#modal_form_gasto').modal('hide');
  $('#modal_form_ayudante_add').modal('hide');
  $('#modal_form_anticipo_add').modal('hide');
  $('#modal_form_ayudante').modal('show');
  $('#modal_form_carta').modal('hide');
  reloadtable_ayudante(idordenservicio);
  $('#idOSayudante').val(idordenservicio);
 
}

function add_gasto(){
  $('#AGdesc').val("");
  $('#AGclave').val("");
  $('#AGmonto').val("");
  $('#AGobs').val("");
  $('#AGdesccorta').html(' ');
  $('#AGdesccorta').append('<option value="0">Selecciona una descripción</option>');
  $("input[name=RCobro][value=SI]").prop('checked', true);

  idOS = $('#idOSgasto').val();
  $('#AGidSO').val(idOS);

  $.ajax({
  url: 'ajax.php?c=OrdenServicio&f=listaDesccortaGastos',
  type: 'POST',
  dataType: 'json',
  })
  .done(function(data) {
  $.each(data, function(index, val) {
  $('#AGdesccorta').append('<option value="'+val.iddesccorta_gastos+'">'+val.concepto+'</option>');  
  });
  })

  $('#modal_form_ayudante_add').modal('hide');
  $('#modal_form_anticipo_add').modal('hide');
  $('#modal_form_ayudante').modal('hide');
  $('#modal_form_anticipo').modal('hide');
  $('#modal_form_carta').modal('hide');
  $('#modal_form_gasto').modal('show');
  $('#modal_form_gasto_add').modal('show');
  }
  function borrarOS(idordenservicio,idcajatractor,idEmpleado,idunidad){  
  if(confirm("Deseas eliminar la orden de servicio?")){
  $.ajax({
  data: {idordenservicio:idordenservicio,idcajatractor:idcajatractor,idEmpleado:idEmpleado,idunidad:idunidad},
  url: 'ajax.php?c=ordenservicio&f=borrarOS',
  type: 'POST',
  dataType: 'json',
  })
  .done(function(data) {
  echo (1);
  $.each(data, function(index, val) {

  alert("Se elimino exitosamente");

  });
  })
  }
  reloadtable();

  }



function add_anticipo(){
    hoy();
    $('#AnOS').val("");
    $('#Ancuenta').val("");
    $('#Anreferencia').val("");
    $('#Animporte').val("");
    $('#Anoperador').html(' ');
    $('#Anoperador').append('<option value="0">Selecciona un Operador</option>');
    $('#Anformapago').html(' ');
    $('#Anformapago').append('<option value="0">Selecciona la Forma de Pago</option>');
    idOS = $('#idOSanticipo').val();
    $('#AnOS').val(idOS).attr('readonly','readonly');
        
        $.ajax({
        data:{idOS:idOS},
        url: 'ajax.php?c=ordenservicio&f=listaOperadores1',
        type: 'POST',
        dataType: 'json',
    })
              .done(function(data) {
              $.each(data, function(index, val) {
              $('#Anoperador').append('<option value="'+val.idEmpleado+'">'+val.nombreEmpleado+'</option>').attr('readonly','readonly');
          });
    })

        $.ajax({
        url: 'ajax.php?c=ordenservicio&f=listaFormaspago',
        type: 'POST',
        dataType: 'json',
        })
              .done(function(data) {
              $.each(data, function(index, val) {
              $('#Anformapago').append('<option value="'+val.idFormapago+'">'+val.nombre+'</option>');
        });
    })

    $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listaCuenta',
    type: 'POST',
    dataType: 'json',
    })
              .done(function(data) {
              $.each(data, function(index, val) {
              $('#Ancuenta').append('<option value="'+val.idbancaria+'">'+val.cuenta+'</option>');
        });
    })

    $('#modal_form_ayudante_add').modal('hide');
    $('#modal_form_ayudante').modal('hide');
    $('#modal_form_anticipo').modal('show');
    $('#modal_form_carta').modal('hide');
    $('#modal_form_gasto').modal('hide');
    $('#modal_form_anticipo_add').modal('show');
  }

  function add_ayudante(){

    $('#AYidOS').val("");
    $('#AYmonto').val("");
    $('#AYobserv').val("");
    //$('#idOSayudante').val("");

    $('#AYoper').html(' ');
    $('#AYoper').append('<option value="0">Selecciona un Operador</option>');
    $('#AYconcep').html(' ');
    $('#AYconcep').append('<option value="0">Selecciona un Concepto</option>');

    idOS = $('#idOSayudante').val();
    $('#AYidOS').val(idOS);

    $.ajax({
      data:{idOS:idOS},
      url: 'ajax.php?c=ordenservicio&f=listaOperadores1',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
    $('#AYoper').append('<option value="'+val.idEmpleado+'">'+val.nombreEmpleado+'</option>').attr('readonly','readonly');
        });
              })

    $.ajax({
      url: 'ajax.php?c=ordenservicio&f=listaDesccortaGastos',
      type: 'POST',
      dataType: 'json',
        })
          .done(function(data) {
          $.each(data, function(index, val) {
          $('#AYconcep').append('<option value="'+val.iddesccorta_gastos+'">'+val.concepto+'</option>');
       });
    })

    $('#AnOS').val(idOS).attr('readonly','readonly');
    $('#modal_form_anticipo_add').modal('hide'); 
    $('#modal_form_ayudante').modal('show');
    $('#modal_form_anticipo').modal('hide');
    $('#modal_form_carta').modal('hide');
    $('#modal_form_gasto').modal('hide');
    $('#modal_form_ayudante_add').modal('show');
  }

  function save_gasto(idordenservicio){
    idOS = $('#AGidSO').val();
    desc = $('#AGdesc').val();
    desccorta = $('#AGdesccorta').val();
    clave = $('#AGclave').val();
    monto = $('#AGmonto').val();
    obser = $('#AGobs').val();

////////////////// SERVICIO /////////////////
  var cobro = "";
  if ($('#RCobrosi').prop('checked')) {
         cobro = $('#RCobrosi').val();
      }
  if ($('#RCobrono').prop('checked')) {
         cobro = $('#RCobrono').val();
      }

  $.ajax({
    data : {idOS:idOS,desc:desc,desccorta:desccorta,clave:clave,monto:monto,obser:obser,cobro:cobro},
    url: 'ajax.php?c=ordenservicio&f=saveGastoExtra',
    type: 'POST',
    dataType: 'json',
  })
    .done(function(data) {
    $('#modal_form_add_gastoX').modal('hide');
    reloadtable_gastox(idOS);
    
    if(data == 1){ 
    }
    else
    {
        alert("Registro Fallido");
    }
          })
                }

  function save_anticipo(idordenservicio){
    idOS = $('#AnOS').val();
    fecha = $('#Anfecha').val();
    operador = $('#Anoperador').val();
    formapago = $('#Anformapago').val();
    cuenta = $('#Ancuenta').val();
    referencia = $('#Anreferencia').val();
    importe = $('#Animporte').val();

    $.ajax({
      data : {idOS:idOS,fecha:fecha,operador:operador,formapago:formapago,cuenta:cuenta,referencia:referencia,importe:importe},
      url: 'ajax.php?c=ordenservicio&f=saveAnticipo',
      type: 'POST',
      dataType: 'json',
    })
               .done(function(data) {
                  $('#modal_form_anticipo_add').modal('hide');
                     reloadtable_anticipo(idOS);
  if(data == 1){
  }
      else
  {
  alert("Registro Fallido"); 
  }
      })
  }


  function save_ayudante(idordenservicio){
    idOS = $('#AYidOS').val();
    operador = $('#AYoper').val();
    concepto = $('#AYconcep').val();
    monto = $('#AYmonto').val();
    observ = $('#AYobserv').val();

    $.ajax({
      data : {idOS:idOS,operador:operador,concepto:concepto,monto:monto,observ:observ},
      url: 'ajax.php?c=ordenservicio&f=saveAyudante',
      type: 'POST',
      dataType: 'json',
    })
     .done(function(data) {
      $('#modal_form_ayudante_add').modal('hide');
      reloadtable_ayudante(idOS);
      if(data == 1){
        //alert("Registro Exitoso");
      }else{
        alert("Registro Fallido");
        }
      })
    }


  function reloadtable_gastox(idordenservicio){
    $.ajax({
      data : {idordenservicio:idordenservicio},
      url: 'ajax.php?c=OrdenServicio&f=listaGastoX',
      type: 'POST',
      dataType: 'json',
    })
    .done(function(data) {
    var table = $('#table_listado_gasto').DataTable();
    table.clear().draw();
    var x ='';
    $.each(data, function(index, val) {

    x ='<tr idConvenio="'+val.idgastoextra+'" class="filas">'+
    '<td>'+val.idgastoextra+'</td>'+
    '<td>'+val.clave+'</td>'+
    '<td>'+val.monto+'</td>'+
    '<td>'+val.observaciones+'</td>'+
    '<td>'+val.cobro_cliente+'</td>'+
    '<td><a class="btn btn-sm btn-primary" title="Editar" onclick="edit_gastox('+val.idgastoextra+')"><i class="glyphicon glyphicon-pencil"></i></a>'+
    '<a class="btn btn-sm btn-danger" title="Eliminar" onclick="delete_gastox('+val.idgastoextra+')"><i class="glyphicon glyphicon-trash"></i></a></td>' 
    +'</tr>';  
    table.row.add($(x)).draw();                           
    });

      $('#modal_form_gasto').modal('show');
    })  
  }

  function reloadtable_anticipo(idordenservicio){
      $.ajax({
        data : {idordenservicio:idordenservicio},
        url: 'ajax.php?c=ordenservicio&f=listaAnticipos',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data) {
      var table = $('#table_listado_anticipo').DataTable();
          table.clear().draw();
          var x ='';
          $.each(data, function(index, val) {

          x ='<tr idConvenio="'+val.idanticipo+'" class="filas">'+
          '<td>'+val.idanticipo+'</td>'+
          '<td>'+val.fecha_captura+'</td>'+
          '<td>'+val.operador+'</td>'+
          '<td>'+val.nombre+'</td>'+
          '<td>'+val.importe+'</td>'+
          '<td><a class="btn btn-sm btn-primary" title="Editar" onclick="edit_anticipo('+val.idanticipo+')"><i class="glyphicon glyphicon-pencil"></i></a>'+
          '<a class="btn btn-sm btn-danger" title="Eliminar" onclick="delete_anticipo('+val.idanticipo+')"><i class="glyphicon glyphicon-trash"></i></a></td>'
          +'</tr>';

      table.row.add($(x)).draw();
    });
      $('#modal_form_anticipo').modal('show');
    })
  }

function hora(){
      var date = new Date;
      var minutes = date.getMinutes();
      if (minutes<10){ // para agregar el 0
        minutes = "0"+minutes;
      }
      var hour = date.getHours();
      var hora = (hour+":"+minutes);
      return hora;
      }

function reloadtable_ayudante(idordenservicio){
      $.ajax({
        data : {idordenservicio:idordenservicio},
        url: 'ajax.php?c=ordenservicio&f=listaBitacora',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data) {
      $.each(data, function(index, val) {
      hora();
              $('#Bfecha').val(val.fecha);
              $('#Bhora').val(hora());
              $('#Bno_viaje').val(val.idordenservicio);
              $('#catB').val(val.marca);
              $('#modeloB').val(val.anio);
              $('#placasB').val(val.placas);
              $('#necB').val(val.no_economico);
              $('#nombCB').val(val.nombreEmpleadoB);
              $('#oriB').val(val.carga);
              $('#destB').val(val.entrega);
              $('#numLCB').val(val.numerolicencia);
              $('#tipoLB').val(val.tipolicencia);
              $('#vigB').val(val.vigencia);
      });
      $('#modal_form_ayudante').modal('show');
    })
  }

function makePDF() {

    var restorepage = $('body').html();
var printcontent =   $('#bitacora_div').clone();
$('body').empty().html(printcontent);
window.print();
window.location.reload();
    document.cookie ='variable='+cartaHTML+'; expires=Thu, 2 Aug 2021 20:47:11 UTC; path=/';
    console.log(bitacora);

    $.ajax({
      data : {cartaHTML:cartaHTML},
      url: 'ajax.php?c=ordenservicio&f=printCarta',
      type: 'POST',
      dataType: 'json',
      })
    var ventimp = window.open($('#bitacora_div'), 'popimpr');
    console.log(ventimp);
    ventimp.document.write( cartaHTML );
    ventimp.document.close();
    ventimp.print( );
    ventimp.close();
   }

  function print_carta(){
    window.print();
    var cartaHTML = $('#modal_form_carta').html();
    document.cookie ='variable='+cartaHTML+'; expires=Thu, 2 Aug 2021 20:47:11 UTC; path=/';
    console.log(cartaHTML);

    $.ajax({
      data : {cartaHTML:cartaHTML},
      url: 'ajax.php?c=ordenservicio&f=printCarta',
      type: 'POST',
      dataType: 'json',
    })

    var ventimp = window.open(' ', 'popimpr');
    ventimp.document.write( cartaHTML );
    ventimp.document.close();
    ventimp.print( );
    ventimp.close();
    }

  function delete_gastox(idgastoextra){
      if(confirm('Seguro que desea eliminar el gasto?'))
            {
            $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idgastoextra:idgastoextra},
            url: 'ajax.php?c=OrdenServicio&f=deletegastoX',
            type: 'POST',
            dataType: 'text',
            })
                .done(function(data) {
                if(data == 1){
                  
                }else{
                alert("Fallo la eliminacion");
             }
          })
                $('#modal_form_gasto').modal('hide');
          }        
             }

    function delete_anticipo(idanticipo){
      
      if(confirm('Seguro que desea eliminar el gasto?'))
            {
            $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idanticipo:idanticipo},
            url: 'ajax.php?c=OrdenServicio&f=delete_anticipo',
            type: 'POST',
            dataType: 'text',
            })
                .done(function(data) {
                if(data == 1){
                 
                }else{
                alert("Fallo la eliminacion");
             }
          })   
             $('#modal_form_anticipo').modal('hide');
          }        
             }




  function addConvenio(idsolicitud)
  {
    $('#modal_form_convList').modal({show:true});
    reload_table_con2(idsolicitud);
  }


/////////////////////////// prueba func_gen.js


function editSolicitud(idsolicitud, espesifico){
    var idcliente = 0;
    $.ajax({
    data : {idsolicitud:idsolicitud},
    url: 'ajax.php?c=ordenservicio&f=listaSolicitudEdit',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
    /////// ids para select ///////
    idcliente = val.id;
    idcapacidad = val.idcapacidadunidad;
    idtipocarga = val.idtipocarga;
    iddatoscarga= val.iddatoscarga;
    idestadoc = val.idestadoc;
    idmunicipioc = val.idmunicipioc;
    iddatosentrega = val.iddatosentrega;
    idestadoe = val.idestadoe;
    idmunicipioe = val.idmunicipioe;

    $('#fechaD').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
    $('#fechaC').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
    $('#fechaE').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');

    $("#idsolicitud").val(val.idsolicitud);
    $("#fechaD").val(val.fecha);
    $("#horaD").val(val.hora);
    $("#contacto").val(val.nombre).attr('readonly','readonly');
    $("#celular").val(val.celular).attr('readonly','readonly');
    $("#embalaje").val(val.embalaje);
    $("#peso").val(val.peso);

    //radios
    servi = val.tipo_servicio;
    if(servi=="Flete"){
    $('#Rrenta').prop('checked', false);
    $('#Rflete').prop('checked', true);
    }else if(servi=="Renta"){
    $('#Rflete').prop('checked', false);
    $('#Rrenta').prop('checked', true);
    }
    viaje = val.viaje;
    if(viaje=="Local"){
    $('#viajeRForaneo').prop('checked', false);
    $('#viajeRLocal').prop('checked', true);
    }
    else if(viaje=="Foraneo")
    {
    $('#viajeRLocal').prop('checked', false);
    $('#viajeRForaneo').prop('checked', true);
    }
    temp = val.temperatura;
    if(temp=="Seco"){
    $('#Rfrio').prop('checked', false);
    $('#Rseco').prop('checked', true);
    $("#gradosl").hide();
    $("#grados").hide();
    $("#grados2").hide();
    }
    else if(temp=="Frio")
    {
    $('#Rseco').prop('checked', false);
    $('#Rfrio').prop('checked', true);
    $("#gradosl").show();
    $("#grados").show();
    $("#grados2").show();
    }

    $(document).ready(function (){
    $("input[name=temperaturaR]:radio").change(function () {
    if ($("#Rseco").attr("checked")) {
    $('#Rsecoedit:input').removeAttr('disabled');
    }

    else 
    {
    $('#Rsecoedit:input').attr('disabled', 'disabled');
    }
    temp = $(this).val();
    if (temp == "Frio"){
    $("#gradosl").show();
    $("#grados").show();
    $("#grados2").show();

    }
    if (temp == "Seco"){
    $("#gradosl").hide();
    $("#grados").hide();
    $("#grados2").hide();
    $("#gradosl2").hide();
    }
    })
    });
    $("#grados").val(val.grados);
    $("#idcarga").val(val.iddatoscarga).attr('readonly','readonly');
    $("#cargaC").val(val.carga_en).attr('readonly','readonly');
    $("#calleC").val(val.callec).attr('readonly','readonly');
    $("#referenciaC").val(val.referenciac).attr('readonly','readonly');
    $("#coloniaC").val(val.coloniac).attr('readonly','readonly');
    $("#estadoC").val(val.estadoc).attr('readonly','readonly');
    $("#ciudadC").val(val.municipioc).attr('readonly','readonly');
    $("#preguntarC").val(val.preguntar_porc).attr('readonly','readonly');
    $("#telefonoC").val(val.telefonoc).attr('readonly','readonly');
    $("#fechaC").val(val.fechac);
    $("#horaC").val(val.horac);

    //id de entrega... oculto
    $("#identrega").val(val.iddatosentrega).attr('readonly','readonly');
    //
    $("#cargaE").val(val.entrega_en).attr('readonly','readonly');
    $("#calleE").val(val.callee).attr('readonly','readonly');
    $("#referenciaE").val(val.referenciae).attr('readonly','readonly');
    $("#coloniaE").val(val.coloniae).attr('readonly','readonly');
    $("#estadoE").val(val.estadoe).attr('readonly','readonly');
    $("#ciudadE").val(val.municipioe).attr('readonly','readonly');
    $("#preguntarE").val(val.preguntar_pore).attr('readonly','readonly');
    $("#telefonoE").val(val.telefonoe).attr('readonly','readonly');
    $("#fechaE").val(val.fechae);
    $("#horaE").val(val.horae);

    $("#atencion").val(val.atencion);
    $("#evidencias").val(val.evidencia);
    $("#requerimientos").val(val.req_cliente);
    $("#recomendaciones").val(val.recomendacion);

    $("#divcarga").hide();

    //////////////AJAX////////////////////////
    ///LISTAR CLIENTES//////
    $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listaClientes',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
    if(idcliente == val.id){
    $('#cliente').append('<option selected="selected" value="'+val.id+'">'+val.nombretienda+'*/*'+val.direccion+'</option>');
    }else{
    $('#cliente').append('<option value="'+val.id+'">'+val.nombretienda+'</option>');
    }
    });
    })
    ///LISTAR destinatarios
    

    $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listaDestinatarios',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    iddestinatario =0;
    $.each(data, function(index, val) {
    $('#destinatario').append('<option value="'+val.id+'">'+val.nombretienda+'</option>');
    });
    $('#destinatario').change(function(){
    iddestinatario = $('#destinatario').val();
    if(iddestinatario > 0){


    $.ajax({ /// LISTAR CIUDADES
    data : {iddestinatario:iddestinatario},
    url: 'ajax.php?c=ordenservicio&f=listaDestinatarios1',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
    $('#contacto_des').val(val.nombre);
    $('#celular_des').val(val.celular);
    });
    $('#contacto_des').attr('readonly','readonly');
    $('#celular_des').attr('readonly','readonly');
    })
    }else{
    return false;
    }
    });
    })

    /// LISTAR CAPACIDADES
    $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listaCapacidad',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
    if(idcapacidad == val.idcapacidadunidad){
    $('#capacidad').append('<option selected="selected" value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');
    }else{
    $('#capacidad').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');
    }
    });
    })

    /// LISTAR TIPOS DE CARGA
    $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listaTipocarga',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
    if(idtipocarga == val.idtipocarga){
    $('#tipocarga').append('<option selected="selected" value="'+val.idtipocarga+'">'+val.tipocarga+'</option>');
    }else{
    $('#tipocarga').append('<option value="'+val.idtipocarga+'">'+val.tipocarga+'</option>');
    }
    });
    $('#modal_form_sol').modal({show:true});
    })


    ///    LISTAR TIPOS DE EMBALAJE
    $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listaEmbalaje',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {

    $('#embalaje').append('<option selected="selected" value="'+val.idembalaje+'">'+val.tipoembalaje+'</option>');

    });
    $('#modal_form_sol').modal({show:true});
    })

    });
    //$('#modal_editOS').modal('show');
    })
    }

 function restCampos2(){
      //LIMPIAR CAMPOS
      $('#cliente').select2('val', null);
      $('#destinatario').select2('val',null);
      $('#capacidad').html(' ');
      $('#capacidad').append('<option value="0">Seleccione Capacidad</option>');
      $('#tipocarga').html(' ');
      $('#tipocarga').append('<option value="0">Seleccione Tipo Carga</option>');
      $("#contacto").val("");
      $("#celular").val("");
      $("#fechaD").val("");
      $("#embalaje").val("");
      $("#peso").val("");
      $("#cargaC").val("");
      $("#calleC").val("");
      $("#referenciaC").val("");
      $("#coloniaC").val("");
      $("#estadoC").val("");
      $("#ciudadC").val("");
      $("#preguntarC").val("");
      $("#telefonoC").val("");
      $("#fechaC").val("");
      $("#grados").val("");
      $("input[name=temperaturaR][value=Seco]").prop('checked', true);
      $("input[name=viajeR][value=Local]").prop('checked', true);
      $("input[name=tiposervicioR][value=Flete]").prop('checked', true);
      $("#cargaE").val("");
      $("#calleE").val("");
      $("#referenciaE").val("");
      $("#coloniaE").val("");
      $("#estadoE").val("");
      $("#ciudadE").val("");
      $("#preguntarE").val("");
      $("#telefonoE").val("");
      $("#fechaE").val("");
      $("#atencion").val("");
      $("#evidencias").val("");
      $("#requerimientos").val("");
      $("#recomendaciones").val("");
      $('#fecha_newuni').datepicker({ format:'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
      $('#fechaAgree').datepicker({ format:'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
      $('#fechaD').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
      $('#fechaC').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
      $('#fechaE').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
      $("#gradosl").hide(); //laber
      $("#grados").hide();  //input
      $("#divcarga").hide();
      hora();
      $("#horaC").val(hora);
      $("#horaE").val(hora);  
      $('#cliente').select2();
      $('#destinatario').select2();
      $('#estadoE1').select2();
      $('#ciudadE1').select2();
      $('#estadoC1').select2();
      $('#ciudadC1').select2();
      $('#tipocarga').select2();
      $('#capacidad').select2();
      $('#desccorta').select2();
      $('#cap').select2();
      $('#ciu').select2();
      $('#est').select2();
      $('#idope').select2();
      $('#iduni').select2();
      $('#idcaja').select2();
      $('#modal_form_sol').modal('hide');
      }