function editSolicitud(idsolicitud, espesifico){

        var idcliente = 0;
       // restCampos();

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
          ///////////////////////////////

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
                //alert("Flete");
            }else if(servi=="Renta"){
                $('#Rflete').prop('checked', false);
                $('#Rrenta').prop('checked', true);
                //alert("Renta");
            }
          viaje = val.viaje;
            if(viaje=="Local"){
                $('#viajeRForaneo').prop('checked', false);
                $('#viajeRLocal').prop('checked', true);
                //alert("local");
            }else if(viaje=="Foraneo"){
                $('#viajeRLocal').prop('checked', false);
                $('#viajeRForaneo').prop('checked', true);
                //alert("Foraneo");
            }
          temp = val.temperatura;
           if(temp=="Frio"){
                $('#Rseco').prop('checked', false);
                $('#Rfrio').prop('checked', true);
                $("#gradosl").show();
                $("#grados").show();
                $("#grados2").show();
                $("#gradosl2").show();
                $("#temp1").show(); 
            }else if(temp=="Seco"){
                $('#Rfrio').prop('checked', false);
                $('#Rseco').prop('checked', true);
                $("#gradosl").hide();
                $("#grados").hide();
                $("#grados2").hide();
                $("#gradosl2").hide();
                $("#temp1").hide();
            }

        $(document).ready(function () {
          $.fn.modal.Constructor.prototype.enforceFocus = function () {};
            $("input[name=temperaturaR]:radio").change(function () {
                if ($("#Rseco").attr("checked")) {
                    $('#Rsecoedit:input').removeAttr('disabled');
                }
                else {
                   $('#Rsecoedit:input').attr('disabled', 'disabled');
                }
                temp = $(this).val();
                if (temp == "Frio"){
                        $("#gradosl").show();
                        $("#grados").show();
                        $("#grados2").show();
                        $("#gradosl2").show();
                        $("#temp1").show();

                }
                if (temp == "Seco"){
                        $("#gradosl").hide();
                        $("#grados").hide();
                         $("#grados2").hide();
                        $("#gradosl2").hide();
                        $("#temp1").hide();
                }
            })
        });
        $("#grados").val(val.grados);
        //grados = val.grados;

      //id de carga... oculto
      $("#idcarga").val(val.iddatoscarga).attr('readonly','readonly');
      //
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
          //alert(idcliente);
          $.each(data, function(index, val) {
           if(idcliente == val.id){
              $('#cliente').append('<option selected="selected" value="'+val.id+'">'+val.nombretienda+'*/*'+val.direccion+'</option>');
            }
           else{
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
          
                    /// LISTAR CIUDADES
      $.ajax({
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

      /// LISTAR CAPACIDADES ///
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
      /// LISTAR TIPOS DE CARGA ///
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


      /////LISTAR TIPOS DE EMBALAJE////
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
     })
}

 function restCampos2(){
  //LIMPIAR CAMPOS
  $('#cliente').select2('val','');
  $('#cliente').html('<option selected="selected" value="0">Selecciona un Cliente</option>');


  $('#destinatario').select2('val','');
  $('#destinatario').html('<option selected="selected" value="0">Selecciona un Destinatario</option>');

  $('#capacidad').select2('val','');
  $('#capacidad').html('<option selected="selected" value="0">Capacidad</option>');


  $('#tipocarga').select2('val','');
  $('#tipocarga').val('<option selected="selected" value="0">Carga</option>');

  $("#contacto").val("");
  $("#celular").val("");

  $("#embalaje").select2('val','');
  $("#embalaje").html('<option selected="selected" value="0"></option>');

  $("#fechaD").val("");

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
  $("#estadoE1").select2('val','');
  $("#estadoE1").html('<option selected="selected" value="0"></option>');

  $('#ciudadE1').select2();
  $("#ciudadE1").select2('val','');
  $("#ciudadE1").html('<option selected="selected" value="0"></option>');

  $('#estadoC1').select2();
  $("#estadoC1").select2('val','');
  $("#estadoC1").html('<option selected="selected" value="0"></option>');

  $('#ciudadC1').select2();
  $("#ciudadC1").select2('val','');
  $("#ciudadC1").html('<option selected="selected" value="0"></option>');

  $('#tipocarga').select2();
  $('#capacidad').select2();

  $('#desccorta').select2();
  $("#desccorta").select2('val','');
  $("#desccorta").html('<option selected="selected" value="0"></option>');

  
  $('#idope').select2();
  $('#iduni').select2();
  $('#idcaja').select2();

  $('#modal_form_sol').modal('hide');
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

function reload_table_con(folio,id,idsolicitud,incluir){
            $.ajax({
              data: {id:id,folio:folio,idsolicitud:idsolicitud,incluir:incluir},
              url: 'ajax.php?c=ordenservicio&f=lastIdCon',
              type: 'POST',
              dataType: 'json',
            })
            .done(function(data) {
                  var table1 = $('#table_listado_con').DataTable();
                      table1.clear().draw();
                  var x ='';
                               $.each(data, function(index, val) {
                  var importe = val.precio_cliente * val.cantidad;
                  var incluir = val.incluir;
                     
                      x ='<tr idConvenio="'+val.idconvenio+'" class="filas">'+
                                     '<td>'+val.idconvenio+'</td>'+
                                     '<td>'+val.nombretienda+'</td>'+
                                     '<td><input id="input'+val.idconvenio+'" type="text" onblur="inn('+folio+','+val.idconvenio+')" value='+val.cantidad+'></td>'+
                                     '<td>'+val.descripcion+'</td>'+
                                     '<td>'+val.concepto+'</td>'+
                                     '<td><label id="lbprecio'+val.idconvenio+'">'+val.precio_cliente+'</label></td>'+
                                     '<td><label id="lbimporte'+val.idconvenio+'">'+importe+'</label></td>'+
                                     '<td><button id="ch'+val.idconvenio+'" class="btn btn-info" onclick="ch('+folio+','+val.idconvenio+','+val.id+','+idsolicitud+','+val.cantidad+')"'+incluir+'"><i class="glyphicon glyphicon-ok-sign"></i> Incluir </button></td>'+
                         '</tr>';
                      table1.row.add($(x)).draw();
                    });
                               
                    console.log(table1);
                    lastid = $("#lastid").val(folio);
                             $("#btnnewCon").attr('onclick','newConvenio('+folio+','+id+')');
            })
              $.ajax({
              data: {id:id,folio:folio,idsolicitud:idsolicitud,incluir:incluir},
              url: 'ajax.php?c=ordenservicio&f=convagree',
              type: 'POST',
              dataType: 'json',
            })
            .done(function(data) {
                  var table2 = $('#table_listado_conAgree').DataTable();
                      table2.clear().draw();
                  var x ='';
                               $.each(data, function(index, val) {
                  var importe = val.precio_cliente * val.cantidad;
                  var incluir = val.incluir;
                     
                      x ='<tr idConvenio="'+val.idconvenio+'" class="filas">'+
                                     '<td>'+val.idconvenio+'</td>'+
                                     '<td>'+val.nombretienda+'</td>'+
                                     '<td><input id="input'+val.idconvenio+'" type="text" onblur="inn('+folio+','+val.idconvenio+')" value='+val.cantidad+'></td>'+
                                     '<td>'+val.descripcion+'</td>'+
                                      '<td>'+val.concepto+'</td>'+
                                     '<td><label id="lbprecio'+val.idconvenio+'">'+val.precio_cliente+'</label></td>'+
                                     '<td><label id="lbimporte'+val.idconvenio+'">'+importe+'</label></td>'+
                                     '<td><button id="ch'+val.idconvenio+'" class="btn btn-danger" onclick="chNo('+folio+','+val.idconvenio+','+val.id+','+idsolicitud+','+val.cantidad+')"'+incluir+'"><i class="glyphicon glyphicon-remove-sign"></i> Eliminar </button></td>'+
                         '</tr>';
                      table2.row.add($(x)).draw();
                    });
                    
            })
                      
    }


  function ch(folio,idconvenio,id,cantidad,idsolicitud,cantidad,incluir){
   $.ajax({ /// EVIANDO PARAMETROS POST
    data : {folio:folio,idconvenio:idconvenio,id:id,cantidad:cantidad},
    url: 'ajax.php?c=ordenservicio&f=editConvenioInc',
    type: 'POST',
    dataType: 'text',
   })
   
       $.ajax({
              data: {id:id,folio:folio,idsolicitud:idsolicitud,incluir:incluir},
              url: 'ajax.php?c=ordenservicio&f=convagree',
              type: 'POST',
              dataType: 'json',
            })
            .done(function(data) {
                  var table2 = $('#table_listado_conAgree').DataTable();
                      table2.clear().draw();
                  var x ='';
                               $.each(data, function(index, val) {
                  var importe = val.precio_cliente * val.cantidad;
                  var incluir = val.incluir;
                     
                      x ='<tr idConvenio="'+val.idconvenio+'" class="filas">'+
                                     '<td>'+val.idconvenio+'</td>'+
                                     '<td>'+val.nombretienda+'</td>'+
                                     '<td><input id="input'+val.idconvenio+'" type="text" onblur="inn('+folio+','+val.idconvenio+')" value='+val.cantidad+'></td>'+
                                     '<td>'+val.descripcion+'</td>'+
                                      '<td>'+val.concepto+'</td>'+
                                     '<td><label id="lbprecio'+val.idconvenio+'">'+val.precio_cliente+'</label></td>'+
                                     '<td><label id="lbimporte'+val.idconvenio+'">'+importe+'</label></td>'+
                                     '<td><button id="ch'+val.idconvenio+'" class="btn btn-danger" onclick="chNo('+folio+','+val.idconvenio+','+val.id+','+idsolicitud+','+val.cantidad+')"'+incluir+'"><i class="glyphicon glyphicon-remove-sign"></i> Eliminar </button></td>'+
                         '</tr>';
                      table2.row.add($(x)).draw();
                    });
                    table2.reload();
            })
  }

   function chNo(folio,idconvenio,id,idsolicitud,cantidad,incluir){

   $.ajax({ /// EVIANDO PARAMETROS POST
    data : {folio:folio,idconvenio:idconvenio,id:id,idsolicitud:idsolicitud,cantidad:cantidad,incluir:incluir},
    url: 'ajax.php?c=ordenservicio&f=deleteRelSolConv',
    type: 'POST',
    dataType: 'text',
   })

      $.ajax({
              data: {id:id,folio:folio,idsolicitud:idsolicitud,incluir:incluir},
              url: 'ajax.php?c=ordenservicio&f=convagree',
              type: 'POST',
              dataType: 'json',
            })
            .done(function(data) {
                  var table2 = $('#table_listado_conAgree').DataTable();
                      table2.clear().draw();
                  var x ='';
                               $.each(data, function(index, val) {
                  var importe = val.precio_cliente * val.cantidad;
                  var incluir = val.incluir;
                     
                      x ='<tr idConvenio="'+val.idconvenio+'" class="filas">'+
                                     '<td>'+val.idconvenio+'</td>'+
                                     '<td>'+val.nombretienda+'</td>'+
                                     '<td><input style="width: 5px;" id="input'+val.idconvenio+'" type="text" onblur="inn('+folio+','+val.idconvenio+')" value='+val.cantidad+'></td>'+
                                     '<td>'+val.descripcion+'</td>'+
                                      '<td>'+val.concepto+'</td>'+
                                     '<td><label id="lbprecio'+val.idconvenio+'">'+val.precio_cliente+'</label></td>'+
                                     '<td><label id="lbimporte'+val.idconvenio+'">'+importe+'</label></td>'+
                                     '<td><button id="ch'+val.idconvenio+'" class="btn btn-danger" onclick="chNo('+folio+','+val.idconvenio+','+val.id+','+idsolicitud+','+val.cantidad+')"'+incluir+'"><i class="glyphicon glyphicon-remove-sign"></i> Eliminar </button></td>'+
                         '</tr>';
                      table2.row.add($(x)).draw();
                    });
                    table2.reload();
            })

   
  }


    function inn(idsolicitud,idconvenio){
      importe = 0;
      precio = $("#lbprecio"+idconvenio).text();
      cantidad = $("#input"+idconvenio).val();
      importe = precio * cantidad;
      $("#lbimporte"+idconvenio).text(importe);
          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idsolicitud:idsolicitud,idconvenio:idconvenio,cantidad:cantidad},
            url: 'ajax.php?c=ordenservicio&f=editConvenioCant',
            type: 'POST',
            dataType: 'text',
          })
    }

 function asignar_solicitud(folio, idEmpleado, idunidad, idcajatractor, idordenservicio, espesifico){
        espesifico = espesifico;
        idsolicitud = folio;
        incluir = 0;

      ///LISTAR solicitudes con uno o mas convenios incluidos
          $.ajax({
            data : {idsolicitud:idsolicitud},
            url: 'ajax.php?c=ordenservicio&f=listaConInc',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
                $.each(data, function(index, val) {
                  incluir = val.incluir;
                });
                       if(incluir == 0){
                        alert("Debe incluir un convenio");
                        }else{
                       $("#idsol_inc").val(folio); // id de solicitud
                       $('#fechaA').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
                       $('#idope').html('<option selected="selected" value="0">Selecciona un Operador</option>');
                       $('#iduni').html('<option selected="selected" value="0">Selecciona una Unidad</option>');
                       $('#idcaja').html('<option selected="selected" value="0">Selecciona una Caja</option>');
      ///LISTAR OPERADORES
          $.ajax({
            data:{idordenservicio:idordenservicio,espesifico:espesifico,idEmpleado:idEmpleado},
            url: 'ajax.php?c=ordenservicio&f=listaOperadores',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                if(idEmpleado == val.idEmpleado){
                $('#idope').append('<option value="'+val.idEmpleado+'">'+val.operador+'</option>');
                }else{
                $('#idope').append('<option value="'+val.idEmpleado+'">'+val.operador+'</option>');
                }
                });
            })
     ///LISTAR UNIDADES
          $.ajax({
            data:{idordenservicio:idordenservicio,espesifico:espesifico,idunidad:idunidad},
            url: 'ajax.php?c=ordenservicio&f=listaUnidades',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  if(idunidad == val.idunidad){
                    $('#iduni').append('<option selected="selected" value="'+val.idunidad+'">'+val.unidad+'</option>');
                  }else{
                    $('#iduni').append('<option value="'+val.idunidad+'">'+val.unidad+'</option>');
                  }
                });
            })
    ///LISTAR CAJAS
          $.ajax({
            data:{idordenservicio:idordenservicio,espesifico:espesifico,idcajatractor:idcajatractor},
            url: 'ajax.php?c=ordenservicio&f=listaCajas',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  if(idcajatractor == val.idcajatractor){
                    $('#idcaja').append('<option selected="selected" value="'+val.idcajatractor+'">'+val.unidad+'</option>');
                  }else{
                    $('#idcaja').append('<option value="'+val.idcajatractor+'">'+val.unidad+'</option>');
                  }
                });
                $('#modal_form_sol2').modal({show:true});
            })
        } /// esle
    }) // done principal
}

function reload_table_con2(folio){
         $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaConvenios',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
                //console.log(data); //devuelve los obj...
              var table = $('#table_listadoConv').DataTable();
                  //$('.filas').empty();
                  table.clear().draw();
                  var x ='';
                  $.each(data, function(index, val) {
                      x ='<tr idConvenio="'+val.idconvenio+'" class="filas">'+
                          '<td>'+val.idconvenio+'</td>'+
                          '<td>'+val.nombre+'</td>'+
                          '<td>'+val.estado+'</td>'+
                          '<td>'+val.municipio+'</td>'+
                          '<td>'+val.capacidad+'</td>'+
                          '<td>'+val.temperatura+'</td>'+
                          '<td>'+val.descripcion+'</td>'+
                          '<td>'+val.concepto+'</td>'+
                          '<td>'+val.precio_cliente+'</td>'+
                          '<td><a class="btn btn-sm btn-primary" title="Agregar" onclick="agregarConvenio('+val.idconvenio+','+folio+')"><i class="glyphicon glyphicon-chevron-down"></i></a>'+
                         '</tr>';
                          table.row.add($(x)).draw();
                  });
          })

    }

  function agregarConvenio(idconvenio,idsolicitud){
    $.ajax({ /// EVIANDO PARAMETROS POST
    data : {idconvenio:idconvenio,idsolicitud:idsolicitud,},
    url: 'ajax.php?c=ordenservicio&f=ligarConSol',
    type: 'POST',
    dataType: 'text',
    })

    .done(function(data) {
    $('#modal_form_convList').modal('hide');
    $('#modal_form_sol').modal({show:true});
    $('#modal_form_sol').getModalContent();
    reload_table_con(idsolicitud);
    if(data == 1){
      //alert("Registro Exitoso");
    }else{
      alert("Registro Fallido");
    }
    })
    }


  function newConvenio(idsolicitud,lastid){
      $('#modal_form_conv').modal({show:true});
      $('#lastid').val(lastid);
      $('#folio').val(idsolicitud);
      $('.modal-title_conv').text('Nuevo Convenio'); // Set Title to Bootstrap modal title
      $('#desccorta').select2('val', '0');

          ///LISTAR CLIENTES
          $.ajax({
            data: {lastid:lastid},
            url: 'ajax.php?c=ordenservicio&f=listaClientesconv',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  $('#clienteconv').val(val.nombretienda);
                });
            })
          /// LISTAR DESC CORTA
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaDesccorta',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
          $.each(data, function(index, val) {
          $('#desccorta').append('<option value="'+val.iddesccorta+'">'+val.concepto+'</option>');
           if(val.iddesccorta == 1){
            $('#retencion').val('4');
          }
          if(val.iddesccorta == 2){
             $('#retencion').val('4');  
          }

                });
         
       
        })
}

function save_convenio(){
      lastid = $('#lastid').val();
      idsolicitud = $('#folio').val();
      desc = $("#desc").val();
      desccorta = $("#desccorta").val();
      precioclie = $("#precioclie").val();
      retencion = $("#retencion").val();
      comisionporc = $("#comisionporc").val();

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {lastid:lastid,idsolicitud:idsolicitud,cli:cli,desc:desc,desccorta:desccorta,precioclie:precioclie,retencion:retencion,comisionporc:comisionporc},
            url: 'ajax.php?c=ordenservicio&f=saveConvenio',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
                $('#modal_form_conv').modal('hide');
                  $('#modal_tamano').modal('hide');
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
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

    function restCampos(){
      $('#modal_form_sol').modal('show');
      $('#fechaD').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).append('<input value="<?php echo date("Y-m-d")?>">').attr('readonly','readonly');
      $('#fechaC').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).append('<input value="<?php echo date("Y-m-d")?>">').attr('readonly','readonly');
      $('#fechaE').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).append('<input value="<?php echo date("Y-m-d")?>">').attr('readonly','readonly');
      $("#gradosl").hide(); //laber
      $("#grados").hide();  //input
      $("#divcarga").hide();
      hora();
      $("#horaC").val(hora);
      $("#horaE").val(hora);
      //LIMPIAR CAMPOS
      $('#cliente').select2('val','');
      $('#cliente').html('');


      $('#destinatario').select2('val','');
      $('#destinatario').html('');


      $('#capacidad').select2('val','');
      $('#capacidad').html('');

      $('#tipocarga').select2('val','');
      $('#tipocarga').val('');

      $("#contacto").val("");
      $("#celular").val("");

      $("#embalaje").select2('val','');
      $("#embalaje").html('');


      $("#peso").val("");
      $("#celular_des").val("");
      $("#contacto_des").val("");
      $("#cargaC").val("");
      $("#calleC").val("");
      $("#referenciaC").val("");
      $("#coloniaC").val("");
      $("#estadoC").val("");
      $("#ciudadC").val("");
      $("#preguntarC").val("");
      $("#telefonoC").val("");
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
      $("#atencion").val("");
      $("#evidencias").val("");
      $("#requerimientos").val("");
      $("#recomendaciones").val("");
}
reload_table();
    /// FUNCION PARA AGREGAR MASK INPUT PARA LA HORA // INICIO
    var patron = new Array(2,2)
    function mascara(d,sep,pat,nums){
    if(d.valant != d.value){
    val = d.value
    largo = val.length
    val = val.split(sep)
    val2 = ''
    for(r=0;r<val.length;r++){
    val2 += val[r]
    }
    if(nums){
    for(z=0;z<val2.length;z++){
    if(isNaN(val2.charAt(z))){
    letra = new RegExp(val2.charAt(z),"g")
    val2 = val2.replace(letra,"")
    }
    }
    }
    val = ''
    val3 = new Array()
    for(s=0; s<pat.length; s++){
    val3[s] = val2.substring(0,pat[s])
    val2 = val2.substr(pat[s])
    }
    for(q=0;q<val3.length; q++){
    if(q ==0){
    val = val3[q]
    }
    else{
    if(val3[q] != ""){
    val += sep + val3[q]
    }
    }
    }
    d.value = val
    d.valant = val
    }
    }
    /// FUNCION PARA AGREGAR MASK INPUT PARA LA HORA // FIN

    //// HORA ACTUAL INICIO
    function hora(){
    var date = new Date;
    //var seconds = date.getSeconds();
    var minutes = date.getMinutes();
    if (minutes<10){ // para agregar el 0
    minutes = "0"+minutes;
    }
    var hour = date.getHours();
    var hora = (hour+":"+minutes);
    return hora;
    }
    //// HORA ACTUAL FIN
    function reload_table()
    {
      $.ajax({
      url: 'ajax.php?c=ordenservicio&f=listaSolicitudes',
      type: 'POST',
      dataType: 'json',
      })
        .done(function(data) {

              //console.log(data); //devuelve los obj...
            var table = $('#table_listado').DataTable();
                //$('.filas').empty();
                table.clear().draw();
                var x ='';
                var inc='';
                $.each(data, function(index, val) {

                      inc ='<a class="btn btn-sm btn-primary" title="Asignar" onclick="asignar_solicitud('+val.folio+')"><i class="glyphicon glyphicon-road"></i></a></td>';



                    x ='<tr idConvenio="'+val.folio+'" class="filas">'+
                                    '<td>'+val.folio+'</td>'+
                                    '<td>'+val.fecha+'\n'+val.hora+'</td>'+
                                    '<td>'+val.nombretienda+'</td>'+
                                    '<td>'+val.poblacion+'</td>'+
                                    '<td>'+val.estado+'</td>'+
                                    '<td>'+val.fechac+'\n'+val.horac+'</td>'+
                                    '<td>'+val.capacidad+'</td>'+
                                    '<td>'+val.temperatura+'</td>'+
                                    '<td>'+val.nombre_tien_des+'</td>'+
                                    '<td>'+val.fechae+'\n'+val.horae+'</td>'+
                                    '<td>'+val.estatus_solicitud+'</td>'+
                                    '<td><a class="btn btn-sm btn-primary" title="Editar" onclick="edit_solicitud('+val.idsolicitud+')"><i class="glyphicon glyphicon-pencil"></i></a></td>'+
                                    '<td><a class="btn btn-sm btn-primary" title="Convenio" onclick="convenio_solicitud('+val.folio+','+val.id+','+val.incluir+','+val.idsolicitud+')"><i class="glyphicon glyphicon-copy"></i></a>'+''+inc+''+
                                    '<td><a class="btn btn-sm btn-danger" title="Eliminar" onclick="delete_solicitud('+val.folio+')"><i class="glyphicon glyphicon-trash"></i></a></td>'
                                +'</tr>';
                        table.row.add($(x)).draw();
          });
     })
}

    function inn(idsolicitud,idconvenio){
      importe = 0;
      precio = $("#lbprecio"+idconvenio).text();
      cantidad = $("#input"+idconvenio).val();
      importe = precio * cantidad;
      $("#lbimporte"+idconvenio).text(importe);

      //alert(idconvenio+"-"+cantidad);

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idsolicitud:idsolicitud,idconvenio:idconvenio,cantidad:cantidad},
            url: 'ajax.php?c=ordenservicio&f=editConvenioCant',
            type: 'POST',
            dataType: 'text',
          })

    }
    function newConvenio1(folio,lastid,id){
      $('#modal_form_con2sol').modal('hide');
      $('#modal_form_convList').modal('hide');
      $('#modal_form_conv').modal({show:true});
      $('#lastid').val(lastid);
      $('#folio').val(folio);
      $('#cli').val(id);
      $('.modal-title_conv').text('Nuevo Convenio'); // Set Title to Bootstrap modal title
      $('#desccorta').empty();
      $('#cli').append('<input>'+id+'</input>').attr('readonly','readonly');
      $('#est').html('<option selected="selected" value="0">Selecciona un Estado</option>');
      $('#cap').html('<option selected="selected" value="0">Selecciona la Capacidad</option>');
      $("#desc").val(""); 
      $("#precioclie").val("");
      $("#preciopro").val("");
      $("#retencion").val("");
      $("#comisionfija").val("");
      $("#comisionporc").val("");
      $('#ciudades').hide();
        
          ///LISTAR CLIENTES
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaClientes',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  $('#cli').append('<option value="'+val.id+'">'+val.nombre+'/'+val.direccion+'</option>');
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
                  $('#cap').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');
                });
            })
          /// LISTAR ESTADOS
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaEstados',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
              var idest = 0;
                $.each(data, function(index, val) {
                $('#est').append('<option value="'+val.idestado+'">'+val.estado+'</option>');
                });
                $('#est').change(function()
                {
                $('#ciudades').show(700);
                $('#ciu').html('<option selected="selected" value="0">Selecciona una Ciudad</option>');
                idest = $('#est').val();
                if(idest > 0){
                //alert(idest);
                $.ajax({ /// LISTAR CIUDADES
                data : {idest:idest},
                url: 'ajax.php?c=ordenservicio&f=listaCiudades',
                type: 'POST',
                dataType: 'json',
                })
                .done(function(data) {
                $.each(data, function(index, val) {
                $('#ciu').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                });
                })
                }else{
                alert("Seleccione un Estado");
                return false;
                    }
                });
            })
          /// LISTAR DESC CORTA
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaDesccorta',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  $('#desccorta').append('<option value="'+val.iddesccorta+'">'+val.concepto+'</option>');
                });
            })

            $('#modal_form_convList').modal('hide');
    }

    function addConvenio1(idsolicitud){
      $('#modal_form_convList').modal({show:true});
      reload_table_con2(idsolicitud);
    }

    function agregarConvenio(idconvenio,idsolicitud){

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idconvenio:idconvenio,idsolicitud:idsolicitud,},
            url: 'ajax.php?c=ordenservicio&f=ligarConSol',
            type: 'POST',
            dataType: 'text',
          })

            .done(function(data) {
                $('#modal_form_convList').modal('hide');
                reload_table_con(idsolicitud);
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })

    }

    function save_convenio(){
      lastid = $('#lastid').val();
      idsolicitud = $('#folio').val();
      cli = $("#cli").val();
      est = $("#est").val();
      if (est == 0) {
        alert("No has seleccionado un estado");
        return false;
      }
      ciu = $("#ciu").val();
      if (ciu == 0) {
        alert("No has seleccionado una ciudad");
        return false;
      }
      cap = $("#cap").val();
      if (cap == 0 ) {
        alert("No has seleccionado una capacidad");
        return false;
      }
      temp = $("#temp").val();
      if (temp == 0) {
        alert("No has seleccionado una opcion de temperatura");
        return false;
      }
      desc = $("#desc").val();
      if (desc == 0 ) {
        alert("no has ingresado una descripcion");
        return false;
      }
      desccorta = $("#desccorta").val();
      precioclie = $("#precioclie").val();
      preciopro = $("#preciopro").val();
      retencion = $("#retencion").val();
      comisionfija = $("#comisionfija").val();
      comisionporc = $("#comisionporc").val();
      coor = $("#coor").val();

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {lastid:lastid,idsolicitud:idsolicitud,cli:cli,est:est,ciu:ciu,cap:cap,temp:temp,desc:desc,desccorta:desccorta,precioclie:precioclie,
                    preciopro:preciopro,retencion:retencion,comisionfija:comisionfija,comisionporc:comisionporc,coor:coor},
            url: 'ajax.php?c=ordenservicio&f=saveConvenio',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
                $('#modal_form_conv').modal('hide');
                $('#modal_form_con2sol').modal('hide');
                alert('Convenio Agregado exitosamente , Vuelva a ingresar a convenios');
             
                   reload_table_con(lastid,idsolicitud,id);
                
            })

            

    }


    function add_solicitud(){
      //$('#form_solicitud')[0].reset(); // reset form on modals
     restCampos();
     $('.modal-title_sol').text('');
      $("#btnUpdate").hide();
      $('#btnSave').show();
      $('#cliente').select2('val','');
      $('#cliente').html('');


      $('#destinatario').select2('val','');
      $('#destinatario').html('');


      $('#capacidad').select2('val','');
      $('#capacidad').html('');


      $('#tipocarga').select2('val','');
      $('#tipocarga').val('');


      $("#contacto").val("");
      $("#celular").val("");

      $("#embalaje").select2('val','');
      $("#embalaje").html('');

      $("#peso").val("");
      $("#celular_des").val("");
      $("#contacto_des").val("");
      $("#cargaC").val("");
      $("#calleC").val("");
      $("#referenciaC").val("");
      $("#coloniaC").val("");
      $("#estadoC").val("");
      $("#ciudadC").val("");
      $("#preguntarC").val("");
      $("#telefonoC").val("");
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
      $("#atencion").val("");
      $("#evidencias").val("");
      $("#requerimientos").val("");
      $("#recomendaciones").val("");
     
                        $("#gradosl").hide();
                        $("#grados").hide();
                        $("#grados2").hide();
                        $("#gradosl2").hide();
                        $("#temp1").hide();


  /// PARA RADIO BUTTON Y CAMPO DEPENNDINETE ///
        $(document).ready(function () {
          $.fn.modal.Constructor.prototype.enforceFocus = function () {};
            $("input[name=temperaturaR]:radio").change(function () {
                if ($("#Rseco").attr("checked")) {
                    $('#Rsecoedit:input').removeAttr('disabled');
                      $("#gradosl").hide();
                        $("#grados").hide();
                        $("#grados2").hide();
                        $("#gradosl2").hide();
                        $("#temp1").hide();
                }
                else {
                   $('#Rsecoedit:input').attr('disabled', 'disabled');
                }
                temp = $(this).val();
                if (temp == "Frio"){
                        $("#gradosl").show();
                        $("#grados").show();
                        $("#grados2").show();
                        $("#gradosl2").show();
                        $("#temp1").show();
                }
                if (temp == "Seco"){
                        $("#gradosl").hide();
                        $("#grados").hide();
                        $("#grados2").hide();
                        $("#gradosl2").hide();
                        $("#temp1").hide();
                }
                
            })
        });
      //// HORA ACTUAL
      hora();
      $('#horaD').val(hora);
      ///LISTAR CLIENTES
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaClientes',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
              
                $.each(data, function(index, val) {

                  $('#cliente').append('<option value="'+val.id+'">'+val.nombretienda+'</option>');
                  
                });
                $('#cliente').change(function(){
                    idcliente = $('#cliente').val();
                    if(idcliente > 0){
                      //alert(idest);
                        $.ajax({ /// LISTAR CIUDADES
                        data : {idcliente:idcliente},
                        url: 'ajax.php?c=ordenservicio&f=listaClientes1',
                        type: 'POST',
                        dataType: 'json',
                      })
                      .done(function(data) {
                          $.each(data, function(index, val) {
                            $('#contacto').val(val.nombre);
                            $('#celular').val(val.celular);
                            $("#evidencias").val(val.evidencia);
                            $("#requerimientos").val(val.requerimientos);
                          });
                          $('#contacto').attr('readonly','readonly');
                          $('#celular').attr('readonly','readonly');
                           $("#evidencias").attr('readonly','readonly');
                            $("#requerimientos").attr('readonly','readonly');
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
                  $('#capacidad').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');
                });
            })

        $.ajax({   
            url: 'ajax.php?c=ordenservicio&f=listaEmbalaje',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
          $.each(data, function(index, val) {
          $('#embalaje').append('<option value="'+val.idembalaje+'">'+val.tipoembalaje+'</option>');
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
                  $('#tipocarga').append('<option value="'+val.idtipocarga+'">'+val.tipocarga+'</option>');
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
                      //alert(idest);
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
                      //alert("Selecciona un destinatario");
                      return false;
                    }
                });
            })

    }
    function save_solicitud(){
      if($('#cliente').val() > 0){
      estatus = "1";

      /// CAMPOS COMUNES DE TABLA//
      fechaD = $("#fechaD").val();

      horaD = $("#horaD").val();
      //contacto = $("#contacto").val();
      //celular = $("#celular").val();
      

      embalaje = $("#embalaje").val();
      if (embalaje == 0) {
        alert("Ingresa un tipo de embalaje");
        return false;
      }
      peso = $("#peso").val();
      grados = $("#grados").val();
      fechaC = $("#fechaC").val();
      if (fechaC == 0) {
        alert("Selecciona una fecha de carga");
        return false;
      }
      horaC = $("#horaC").val();
      fechaE = $("#fechaE").val();
      if (fechaE == 0) {
        alert("Selecciona una fecha de entrega");
        return false;
      }
      horaE = $("#horaE").val();
      atencion = $("#atencion").val();
      if (atencion == 0) {
        alert("Ingresa quien brindo la atencion");
        return false;
      }
      requerimientos = $("#requerimientos").val();
      if (requerimientos == 0) {
        alert("Ingresa los requerimientos");
        return false;
      }
      evidencias = $("#evidencias").val();
      if (evidencias == 0) {
        alert("Ingresa evidencias");
        return false;
      }
      recomendaciones = $("#recomendaciones").val();
      if (recomendaciones == 0) {
        alert("Ingresa las recomendaciones");
        return false;
      }
      /// CAMPOS DE SELECT
      idcliente = $("#cliente").val();
      idcapacidad = $("#capacidad").val();
      if (idcapacidad == 0) {
        alert("Selecciona una capacidad");
        return false;
      }
      idtipocarga = $("#tipocarga").val();
      if (idtipocarga == 0) {
        alert("Selecciona el tipo de carga");
        return false;
      }
      iddatoscarga = $("#idcarga").val();
      if (iddatoscarga == 0) {
        alert("Debes ingresar datos de carga");
        return false;
      }
      iddatosentrega = $("#identrega").val();
      if (iddatosentrega == 0) {
        alert("Debes ingresar datos de entrega");
        return false;
      }
      iddestinatario = $("#destinatario").val();
      if (iddestinatario == 0) {
        alert("Debes seleccionar un destinatario");
        return false;
      }

      /// RADIOBUTTON /// INICIO
          /// TEMPERATURA ///
      var temp = "";
      if ($('#Rseco').prop('checked')) {
        temp = $('#Rseco').val();
      }
      if ($('#Rfrio').prop('checked')) {
        temp = $('#Rfrio').val();
      }
      if ($('#Rcaliente').prop('checked')) {
        temp = $('#Rcaliente').val();
      }
      if ($('#Rhumedo').prop('checked')) {
        temp = $('#Rhumedo').val();
      }
      //alert(temp);
            /// SERVICIO ///
      var servi = "";
      if ($('#Rflete').prop('checked')) {
        servi = $('#Rflete').val();
      }
      if ($('#Rrenta').prop('checked')) {
        servi = $('#Rrenta').val();
      }
      //alert(servi);
          /// VIAJE ///
      var viaje = "";
      if ($('#viajeRLocal').prop('checked')) {
        viaje = $('#viajeRLocal').val();
      }
      if ($('#viajeRForaneo').prop('checked')) {
        viaje = $('#viajeRForaneo').val();
      }
      //alert(viaje);
      /// RADIOBUTTON /// FIN

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {fechaD:fechaD,horaD:horaD,embalaje:embalaje,
                    peso:peso,grados:grados,fechaC:fechaC,horaC:horaC,
                    fechaE:fechaE,horaE:horaE,atencion:atencion,recomendaciones:recomendaciones,
                    requerimientos:requerimientos,evidencias:evidencias,
                    idcliente:idcliente,idcapacidad:idcapacidad,idtipocarga:idtipocarga,
                    iddatoscarga:iddatoscarga,iddatosentrega:iddatosentrega,temp:temp,servi:servi,viaje:viaje,estatus:estatus,iddestinatario:iddestinatario},
            url: 'ajax.php?c=ordenservicio&f=saveSolicitud',
            type: 'POST',
            dataType: 'text',
          })
            .done(function(data) {
                $('#modal_form_sol').modal('hide');
                reload_table();
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })
           
      } else {
          alert("Alguno de los datos no fue capturado , porfavor ingresalos todos");
      }
    }
    function hide_datoscarga2(){
      $('#modal_form_datoscarga').modal('hide');
    }
    function hide_datoscarga3(){
      $('#myModal').modal('hide');
    }
    function hide_datoscarga4(){
      $('#myModal1').modal('hide');
    }
    function datos_carga(){
      $('#modal_form_datoscarga').modal('show');

      $('#iddatoscarga').html(' ');
      $('#iddatoscarga').append('<option value="0">Selecciona un Lugar</option>');

      $('#diventrega').hide();
      $('#divcarga').show();
      idcliente = $("#cliente").val();

          /// LISTAR DATOS DE CARGA
          $.ajax({
            data: {idcliente:idcliente},
            url: 'ajax.php?c=ordenservicio&f=listaDatoscarga',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {

                $.each(data, function(index, val) {
                  $('#iddatoscarga').append('<option id="'+val.id_cliente+'" value="'+val.iddatoscarga+'">'+val.carga_en+'/'+val.municipio+'</option>');
                });
            })
    }
    function add_datoscarga(){
      iddatoscarga = $("#iddatoscarga").val(); /// id de lugar donde cargan

        $.ajax({ /// LISTAR CAMOS DE DATOS CARGA //// al seleccionar alguno
                  data : {iddatoscarga:iddatoscarga},
                  url: 'ajax.php?c=ordenservicio&f=listaDatoscarga1',
                  type: 'POST',
                  dataType: 'json',
              })
              .done(function(data) {

                  $.each(data, function(index, val) {


                    //// campos comunes //////////
                    //$("#iddatoscarga").val(val.iddatoscarga).prop('readonly', true);
                    $("#idcarga").val(val.iddatoscarga).prop('readonly', true);
                    $("#cargaC").val(val.carga_en).prop('readonly', true);
                    $("#calleC").val(val.calle).prop('readonly', true);
                    $("#referenciaC").val(val.referencia).prop('readonly', true);
                    $("#coloniaC").val(val.colonia).prop('readonly', true);
                    $("#preguntarC").val(val.preguntar_por).prop('readonly', true);
                    $("#telefonoC").val(val.telefono).prop('readonly', true);
                    $("#estadoC").val(val.estado).prop('readonly', true);
                    $("#ciudadC").val(val.municipio).prop('readonly', true);


                  });
                  $('#modal_form_datoscarga').modal('hide');
                  $('#modal_form_sol').modal('show');
                  $('#modal_form_sol').modal('handleUpdate');
                  $("#divcarga").show();
                })
    }
    function datos_entrega(){
      $('#modal_form_datoscarga').modal('show');

      $('#iddatosentrega').html(' ');
      $('#iddatosentrega').append('<option value="0">Selecciona un Lugar</option>');

      $('#divcarga').hide();
      $('#diventrega').show();

      iddestinatario = $("#destinatario").val();
      //alert (iddestinatario);
          $.ajax({
            data: {iddestinatario:iddestinatario},
            url: 'ajax.php?c=ordenservicio&f=listaDatosentrega',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
                $.each(data, function(index, val) {
                  $('#iddatosentrega').append('<option id="'+val.id_destinatario+'" value="'+val.iddatosentrega+'">'+val.entrega_en+'/'+val.municipio+'</option>');
                });
            })
    }
    function add_datosentrega(){
      iddatosentrega = $("#iddatosentrega").val(); /// id de lugar donde cargan

        $.ajax({ /// LISTAR CAMOS DE DATOS CARGA //// al seleccionar alguno
                  data : {iddatosentrega:iddatosentrega},
                  url: 'ajax.php?c=ordenservicio&f=listaDatosentrega1',
                  type: 'POST',
                  dataType: 'json',
              })
              .done(function(data) {

                  $.each(data, function(index, val) {
                    //alert(val.entrega_en);

                    //// campos comunes //////////
                    $("#identrega").val(val.iddatosentrega).prop('readonly', true);
                    $("#cargaE").val(val.entrega_en).prop('readonly', true);
                    $("#calleE").val(val.calle).prop('readonly', true);
                    $("#referenciaE").val(val.referencia).prop('readonly', true);
                    $("#coloniaE").val(val.colonia).prop('readonly', true);
                    $("#preguntarE").val(val.preguntar_por).prop('readonly', true);
                    $("#telefonoE").val(val.telefono).prop('readonly', true);
                    $("#estadoE").val(val.estado).prop('readonly', true);
                    $("#ciudadE").val(val.municipio).prop('readonly', true);


                  });
                  $('#modal_form_datoscarga').modal('hide');
                  $('#modal_form_sol').modal('show');
                  $('#modal_form_sol').modal('handleUpdate');
                  $("#diventrega").show();
                })
    }
    function add_lugarcarga(){

      $('#cargaC1').val("");
      $('#calleC1').val("");
      $('#estadoC1').html(' ');
      $('#estadoC1').append('<option value="0">Selecciona un Estado</option>');
      $('#ciudadC1').html(' ');
      $('#ciudadC1').append('<option value="0">Selecciona una Ciudad</option>');
      $('#referenciaC1').val("");
      $('#coloniaC1').val("");
      $('#preguntarC1').val("");
      $('#telefonoC1').val("");
      $('#horaC1').val("");
      $('#fechaC1').val("");


      /// LISTAR ESTADOS
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaEstados',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
              var idest = 0;
                $.each(data, function(index, val) {
                  $('#estadoC1').append('<option value="'+val.idestado+'">'+val.estado+'</option>');
                });
                $('#estadoC1').change(function()
                {
                  $('#ciudadC1').html('<option selected="selected" value="0">Selecciona una Ciudad</option>');
                    idest = $('#estadoC1').val();
                    if(idest > 0){
                      //alert(idest);
                        $.ajax({ /// LISTAR CIUDADES
                        data : {idest:idest},
                        url: 'ajax.php?c=ordenservicio&f=listaCiudades',
                        type: 'POST',
                        dataType: 'json',
                      })
                      .done(function(data) {
                          $.each(data, function(index, val) {
                            $('#ciudadC1').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                          });
                      })
                    }else{
                      alert("Seleccione un Estado");
                    }
                });
            })

        $('#fechaC1').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
        hora();
        $('#horaC1').val(hora);
        $('#myModal').modal({show:true});
    }
    function guardar_lugarcarga(){
      carga_en = $('#cargaC1').val();
      calleC1 = $('#calleC1').val();
      estadoC1 = $('#estadoC1').val();
      ciudadC1 = $('#ciudadC1').val();
      referenciaC1 = $('#referenciaC1').val();
      coloniaC1 = $('#coloniaC1').val();
      preguntarC1 = $('#preguntarC1').val();
      telefonoC1 = $('#telefonoC1').val();
      horaC1 = $('#horaC1').val();
      fechaC1 = $('#fechaC1').val();
      idcliente = $('#cliente').val();




               $.ajax({ /// EVIANDO PARAMETROS POST
            data : {carga_en:carga_en,calleC1:calleC1,estadoC1:estadoC1,
                    ciudadC1:ciudadC1,referenciaC1:referenciaC1,coloniaC1:coloniaC1,preguntarC1:preguntarC1,
                    telefonoC1:telefonoC1,horaC1:horaC1,fechaC1:fechaC1,idcliente:idcliente},
            url: 'ajax.php?c=ordenservicio&f=saveLugarcarga',
            type: 'POST',
            dataType: 'text',
          })
            .done(function(data) {
                $('#myModal').modal('hide');
                datos_carga();
                reload_table();
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })
    }
    function add_lugarentrega(){

      $('#cargaE1').val("");
      $('#calleE1').val("");
      $('#estadoE1').html(' ');
      $('#estadoE1').append('<option value="0">Selecciona un Estado</option>');
      $('#ciudadE1').html(' ');
      $('#ciudadE1').append('<option value="0">Selecciona una Ciudad</option>');
      $('#referenciaE1').val("");
      $('#coloniaE1').val("");
      $('#preguntarE1').val("");
      $('#telefonoE1').val("");
      $('#horaE1').val("");
      $('#fechaE1').val("");

      /// LISTAR ESTADOS
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaEstados',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
              var idest = 0;
                $.each(data, function(index, val) {
                  $('#estadoE1').append('<option value="'+val.idestado+'">'+val.estado+'</option>');
                });
                $('#estadoE1').change(function()
                {
                  $('#ciudadE1').html('<option selected="selected" value="0">Selecciona una Ciudad</option>');
                    idest = $('#estadoE1').val();
                    if(idest > 0){
                      //alert(idest);
                        $.ajax({ /// LISTAR CIUDADES
                        data : {idest:idest},
                        url: 'ajax.php?c=ordenservicio&f=listaCiudades',
                        type: 'POST',
                        dataType: 'json',
                      })
                      .done(function(data) {
                          $.each(data, function(index, val) {
                            $('#ciudadE1').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                          });
                      })
                    }else{
                      alert("Seleccione un Estado");
                    }
                });
            })

        $('#fechaE1').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
        hora();
        $('#horaE1').val(hora);

        $('#myModal1').modal({show:true});
    }
    function guardar_lugarentrega(){
      entrega_en = $('#cargaE1').val();
      calleE1 = $('#calleE1').val();
      estadoE1 = $('#estadoE1').val();
      ciudadE1 = $('#ciudadE1').val();
      referenciaE1 = $('#referenciaE1').val();
      coloniaE1 = $('#coloniaE1').val();
      preguntarE1 = $('#preguntarE1').val();
      telefonoE1 = $('#telefonoE1').val();
      horaE1 = $('#horaE1').val();
      fechaE1 = $('#fechaE1').val();
      iddestinatario = $('#destinatario').val();


        $.ajax({ /// EVIANDO PARAMETROS POST
            data : {entrega_en:entrega_en,calleE1:calleE1,estadoE1:estadoE1,
                    ciudadE1:ciudadE1,referenciaE1:referenciaE1,coloniaE1:coloniaE1,preguntarE1:preguntarE1,
                    telefonoE1:telefonoE1,horaE1:horaE1,fechaE1:fechaE1,iddestinatario:iddestinatario},
            url: 'ajax.php?c=ordenservicio&f=saveLugarentrega',
            type: 'POST',
            dataType: 'text',
          })

            .done(function(data) {

                $('#myModal1').modal('hide');
                datos_entrega();
                reload_table();
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })
    }
    function edit_solicitud(idsolicitud){

      $('.modal-title_sol').text('Editar Solicitud'); // Set Title to Bootstrap modal title
      $('#btnSave').hide();

      $("#btnUpdate").hide();
      var espesifico = "NO";
      //editSolicitud(idsolicitud,espesifico);

      var idcliente = 0;
      var iddestinatario = 0;

       // restCampos();
        //alert(idsolicitud);

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
          iddestinatario = val.id_destinatario;
          iddatosentrega = val.iddatosentrega;
          idestadoe = val.idestadoe;
          idmunicipioe = val.idmunicipioe;
          idembalaje = 
          ///////////////////////////////

          $('#fechaD').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
          $('#fechaC').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
          $('#fechaE').datepicker({ format: 'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');

          $("#idsolicitud").val(val.idsolicitud);
          $("#fechaD").val(val.fecha);
          $("#horaD").val(val.hora);
          $("#contacto").val(val.nombre).attr('readonly','readonly');
          $("#celular").val(val.celular).attr('readonly','readonly');
          $("#peso").val(val.peso);

          //radios
          servi = val.tipo_servicio;
            if(servi=="Flete"){
                $('#Rrenta').prop('checked', false);
                $('#Rflete').prop('checked', true);
                //alert("Flete");
            }else if(servi=="Renta"){
                $('#Rflete').prop('checked', false);
                $('#Rrenta').prop('checked', true);
                //alert("Renta");
            }
          viaje = val.viaje;
            if(viaje=="Local"){
                $('#viajeRForaneo').prop('checked', false);
                $('#viajeRLocal').prop('checked', true);
                //alert("local");
            }else if(viaje=="Foraneo"){
                $('#viajeRLocal').prop('checked', false);
                $('#viajeRForaneo').prop('checked', true);
                //alert("Foraneo");
            }
          temp = val.temperatura;
            if(temp=="Seco"){
                $('#Rfrio').prop('checked', true);
                $('#Rseco').prop('checked', false);
                $("#gradosl").hide();
                $("#grados").hide();
                $("#grados2").hide();
                $("#gradosl2").hide();
                $("#temp1").hide();
                //alert("Seco");
            }else if(temp=="Frio"){
                $('#Rseco').prop('checked', true);
                $('#Rfrio').prop('checked', false);
                $("#gradosl").show();
                $("#grados").show();
                $("#grados2").show();
                $("#gradosl2").show();
                $("#temp1").show();
            }

        $(document).ready(function () {
          $.fn.modal.Constructor.prototype.enforceFocus = function () {};
            $("input[name=temperaturaR]:radio").change(function () {
                if ($("#Rseco").attr("checked")) {
                    $('#Rsecoedit:input').removeAttr('disabled');
                }
                else {
                   $('#Rsecoedit:input').attr('disabled', 'disabled');
                }
                temp = $(this).val();
                if (temp == "Frio"){
                        $("#gradosl").show();
                        $("#grados").show();
                        $("#grados2").show();
                        $("#gradosl2").show();
                        $("#temp1").show();
                }
                if (temp == "Seco"){
                         $("#gradosl").hide();
                        $("#grados").hide();
                        $("#grados2").hide();
                        $("#gradosl2").hide();
                        $("#temp1").hide();
                }
            })
        });
        $("#grados").val(val.grados);
        //grados = val.grados;

      //id de carga... oculto
      $("#idcarga").val(val.iddatoscarga).attr('readonly','readonly');
      //
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

      $("#contacto_des").val(val.nombre_des).attr('readonly','readonly');
      $("#celular_des").val(val.celular_des).attr('readonly','readonly');
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
                          //alert(idcliente);
                            $.each(data, function(index, val) {
                              if(idcliente == val.id){
                                 $('#cliente').append('<option selected="selected" value="'+val.id+'">'+val.nombretienda+'</option>');
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
                      //alert(idest);
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
                      //alert("Selecciona un destinatario");
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
                    })

                            });

                     $.ajax({   
            url: 'ajax.php?c=ordenservicio&f=listaEmbalaje',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
          $.each(data, function(index, val) {
          $('#embalaje').append('<option value="'+val.idembalaje+'">'+val.tipoembalaje+'</option>');
                          });

                      })

                      

    $('#modal_form_sol').modal({show:true});
                       })



    }
    function send_edit_solicitud(){

      idsolicitud = $("#idsolicitud").val();
      idcliente = $("#cliente").val();
      fechaD = $("#fechaD").val();
      horaD = $("#horaD").val();
      horaE = $("#horaE").val();
      fechaE = $("#fechaE").val();
      horaC = $("#horaC").val();
      fechaC = $("#fechaC").val();

      idcapacidad = $("#capacidad").val();
      idtipocarga = $("#tipocarga").val();
      embalaje = $("#embalaje").val();
      peso = $("#peso").val();
      grados = $("#grados").val();
      iddatoscarga = $("#idcarga").val();
      nombre_des = $("#contacto_des").val();
      celular_des = $("#celular_des").val();
      iddatosentrega = $("#identrega").val();
      atencion = $("#atencion").val();
      evidencias = $("#evidencias").val();
      recomendaciones = $("#recomendaciones").val();
      requerimientos = $("#requerimientos").val();

      /// RADIOBUTTON /// INICIO
          /// TEMPERATURA ///
      var temp = "";
      if ($('#Rseco').prop('checked')) {
        temp = $('#Rseco').val();
      }
      if ($('#Rfrio').prop('checked')) {
        temp = $('#Rfrio').val();
      }
            /// SERVICIO ///
      var servi = "";
      if ($('#Rflete').prop('checked')) {
        servi = $('#Rflete').val();
      }
      if ($('#Rrenta').prop('checked')) {
        servi = $('#Rrenta').val();
      }
          /// VIAJE ///
      var viaje = "";
      if ($('#viajeRLocal').prop('checked')) {
        viaje = $('#viajeRLocal').val();
      }
      if ($('#viajeRForaneo').prop('checked')) {
        viaje = $('#viajeRForaneo').val();
      }
      /// RADIOBUTTON /// FIN

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idsolicitud:idsolicitud,fechaD:fechaD,horaD:horaD,embalaje:embalaje,
                    peso:peso,grados:grados,fechaC:fechaC,horaC:horaC,
                    fechaE:fechaE,horaE:horaE,atencion:atencion,recomendaciones:recomendaciones,
                    requerimientos:requerimientos,evidencias:evidencias,
                    idcliente:idcliente,idcapacidad:idcapacidad,idtipocarga:idtipocarga,
                    iddatoscarga:iddatoscarga,nombre_des:nombre_des,celular_des:celular_des,iddatosentrega:iddatosentrega,temp:temp,servi:servi,viaje:viaje},
            url: 'ajax.php?c=ordenservicio&f=editSolicitud',
            type: 'POST',
            dataType: 'text',
          })
            .done(function(data) {
                $('#modal_form_sol').modal('hide');
                reload_table();
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })


    }

  
    function saveNewope(){
    nom_operador = $("#nombre_opeNew").val();
    ape_operador = $("#ape_opeNew").val();
    tel_operador = $("#tel_opeNew").val();
    rfc_operador = $("#rfc_opeNew").val();
    age_operador = $("#age_opeNew").val();
    est_operador = $("#est_opeNew").val();
    ciu_operador = $("#ciu_opeNew").val();
    calle_operador = $("#calle_opeNew").val();
    num_exterior = $("#noext_opeNew").val();
    num_interior = $("#noint_opeNew").val();
    col_operador = $("#col_opeNew").val();
    cp_operador = $("#cp_opeNew").val();
    num_lic = $("#nolic_opeNew").val();
    fecha_ingreso = $("#fechaAgree").val();
    tel1 = $('#tel1_opeNew').val();
    tel2 = $('#tel2_opeNew').val();
    nom_emer = $('#nomeme_opeNew').val();

          $.ajax({ /// EVIANDO PARAMETROS POST
            data :{nom_operador:nom_operador,ape_operador:ape_operador,tel_operador:tel_operador,
                    rfc_operador:rfc_operador,age_operador:age_operador,est_operador:est_operador,
                    ciu_operador:ciu_operador,calle_operador:calle_operador,num_exterior:num_exterior,
                    num_interior:num_interior,col_operador:col_operador,cp_operador:cp_operador,num_lic:num_lic,
                    fecha_ingreso:fecha_ingreso,tel1:tel1,tel2:tel2,nom_emer:nom_emer},
            url: 'ajax.php?c=ordenservicio&f=SaveNewOpe',
            type: 'POST',
            dataType: 'text',
          })
            .done(function(data) {
               $('#modal_agree_ope').modal('hide');
    
                if(data == 1){
                  echo(1);
                }else{
                  alert("Registro Fallido");
                }
            }) 

                               resetagree();
                                $('#modal_form_sol2').modal('show');

    }



    function delete_solicitud(folio){
      //alert(folio);
      if(confirm('Seguro que desea eliminar la solicitud?'))
      {
            $.ajax({ /// EVIANDO PARAMETROS POST
            data : {folio:folio},
            url: 'ajax.php?c=ordenservicio&f=deleteSolicitud',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
                reload_table();
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Fallo la elimindacion");
                }
            })
      }
    }

    function saveIncluir(){
      idsolicitud = $("#idsol_inc").val();
      fechaA = $("#fechaA").val();
      idope = $("#idope").val();
      iduni = $("#iduni").val();
      idcaja = $("#idcaja").val();
      

          $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idsolicitud:idsolicitud,fechaA:fechaA,idope:idope,iduni:iduni,idcaja:idcaja},
            url: 'ajax.php?c=ordenservicio&f=saveAsignacion',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
              reload_table();
              $('#modal_form_sol2').modal('hide');
                reload_table();
          })
         
    }
    function convenio_solicitud(folio,id,idsolicitud,incluir){
        reload_table_con(folio,id,idsolicitud,incluir);
        //alert(incluir);
      $('#modal_form_con2sol').modal({show:true});
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

    function isNumber(evt) {
      evt = (evt) ? evt : window.event;
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      }
      return true;
    }

    function fin_conv_select(){

    $('#modal_form_con2sol').modal('hide');

    }


    function agregar_ope(){

    $('#modal_agree_ope').modal('show');
    
    $('#modal_form_sol2').modal('hide');

    }


function resetagree(){

    $('#nombre_opeNew').val("");
    $('#ape_opeNew').val("");
    $('#tel_opeNew').val("");
    $('#rfc_opeNew').val("");
    $('#age_opeNew').val("");
    $('#est_opeNew').val("");
    $('#ciu_opeNew').val("");
    $('#calle_opeNew').val("");
    $('#noext_opeNew').val("");
    $('#noint_opeNew').val("");
    $('#col_opeNew').val("");
    $('#cp_opeNew').val("");
    $('#nolic_opeNew').val("");
    $('#fechaAgree').datepicker({ format:'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');

    $('#modal_form_sol2').modal('show');
    }






  

 function add_newOperador(){

   
    $('#est_opeNew').html('<option value="0">Selecciona un Estado</option>');
    $('#calle_opeNew').val("");
    $('#noext_opeNew').val("");
    $('#noint_opeNew').val("");
    $('#col_opeNew').val("");
    $('#cp_opeNew').val("");
    $('#nolic_opeNew').val("");
    $('#fechaAgree').datepicker({ format:'yyyy-mm-dd', "autoclose": true }).attr('readonly','readonly');
   // $('#ciu_openNew').html('<option selected="selected" value="0">Selecciona una Ciudad</option>');
    $('#modal_agree_ope').modal('show');
    $('#modal_form_sol2').modal('hide');

     /// LISTAR ESTADOS
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaEstados',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
              var idest = 0;
                $.each(data, function(index, val) {
                  $('#est_opeNew').append('<option value="'+val.idestado+'">'+val.estado+'</option>');
                });
                $('#est_opeNew').change(function()
                {
                  $('#ciu_opeNew').html('<option>Selecciona una Ciudad</option>');
                    idest = $('#est_opeNew').val();
                    if(idest > 0){
                     // alert(idest);
                        $.ajax({
                        data : {idest:idest},
                        url: 'ajax.php?c=ordenservicio&f=listaCiudades',
                        type: 'POST',
                        dataType: 'json',
                      })
                      .done(function(data) {
                          $.each(data, function(index, val) {
                          $('#ciu_opeNew').append('<option value="'+val.idmunicipio+'">'+val.municipio+'</option>');
                          });
                      })
                    }else{
                      alert("Seleccione un Estado");
                    }
                });
            })


    }


    function add_newUnidad(){
     resetcamposUni();
  $('#modal_form_addUni').modal('show'); // show bootstrap modal
    $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listarselect_marca',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
         $('#marcaUni').append('<option value="'+val.idmarca+'">'+val.marca+'</option>');
                            });
                       })
     $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listarselect_tipoUni',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
         $('#tipoUni').append('<option value="'+val.idtipounidad+'">'+val.tipo+'</option>');
                            });
                       })
     $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listarselect_capUni',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
         $('#capUni').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>');
                            });
                       })
     $.ajax({
    url: 'ajax.php?c=ordenservicio&f=listarselect_tipocomb',
    type: 'POST',
    dataType: 'json',
    })
    .done(function(data) {
    $.each(data, function(index, val) {
         $('#tipocombustibleUni').append('<option value="'+val.idtipocombustible+'">'+val.combustible+'</option>');
                            });
                       })
$(document).ready(function () {
  $.fn.modal.Constructor.prototype.enforceFocus = function () {};
            $("input[name=refrigeradoR]:radio").change(function () {
                if ($("#refriUniNo").attr("checked")) {
                    $('#refriUniNoedit:input').removeAttr('disabled');
                      $("#tamtanqthermo").hide();
                        $("#rendthermfor").hide();
                        $("#rendthermloc").hide();
                        $("#thermotable1").hide();
                }
                else {
                   $('#refriUniNoedit:input').attr('disabled', 'disabled');
                }
                temp = $(this).val();
                if (temp == "1"){
                        $("#tamtanqthermo").show();
                        $("#rendthermfor").show();
                        $("#rendthermloc").show();
                        $("#thermotable1").show();
                }
                if (temp == "0"){
                        $("#tamtanqthermo").hide();
                        $("#rendthermfor").hide();
                        $("#rendthermloc").hide();
                        $("#thermotable1").hide();
                }
                
            })
        });


    } 


function resetcamposUni(){
      $('#noEconomico').val('');
      $('#marcaUni').val('');
      $('#anioUni').val('');
      $('#placasUni').val('');
      $('#colorUni').val('');
      $('#tipoUni').val('');
      $('#capUni').val('');
      $('#tipocombustibleUni').val('');
      $('#tamanotanqUni').val('');
      $('#rendforUni').val('');
      $('#rendlocUni').val('');
      $('#tamtanqthermo').val('');
      $('#rendthermfor').val('');
      $('#rendthermloc').val('');
      $('#fechaaddUni').val('');
      $('#kmadquisicion').val('');
      $('#kmtotal').val('');
      $('#observaciones').val('');
      $('#modeloUni').val('');
}



    function add_newCaja(){
      $('#modal_agree_caja').modal('show');
      $('#modal_form_sol2').modal('hide');

  //$("#fecha_newuni").val();
  $("#numeco_new_caja").val("");
  $("#placas_new_caja").val("");
  $("#ejes_new").val("");
  $("#color_new_caja").val("");
  $("#observaciones_caja").val("");
  $("#fecha_new_caja").val();


  
  $("#tipocaja_new").html('<option value="0">Tipo de caja</option>');
            $.ajax({   
            url: 'ajax.php?c=ordenservicio&f=listaTipocaja',
            type: 'POST',
            dataType: 'json',
          })
          .done(function(data) {
          $.each(data, function(index, val) {
          $('#tipocaja_new').append('<option value="'+val.id+'">'+val.tipocaja+'</option>');
                          });

                      })

    } 

function saveNewunidad(){
  numero_ec = $("#numeco_new").val();
  placas = $("#placas_new").val();
  modelo = $("#model_new").val();
  anio = $("#anio_new").val();
  color =  $("#color_new").val();
  tanque = $("#tam_tanq").val();
  observaciones = $("#observaciones").val();
  fecha =  $("#fecha_newuni").val();
  tipo = $("#tipounidad").val();
  capacidad =  $("#capacidad_new").val();
  marca = $("#marca_new").val();
  tipogas = $("#tipogas").val();
   

          $.ajax({ /// EVIANDO PARAMETROS POST
            data :{numero_ec:numero_ec,placas:placas,modelo:modelo,anio:anio,color:color,tanque:tanque,
          observaciones:observaciones,fecha:fecha,tipo:tipo,capacidad:capacidad,marca:marca,tipogas:tipogas},
            url: 'ajax.php?c=ordenservicio&f=SaveNewunidad',
            type: 'POST',
            dataType: 'text',
          })
            .done(function(data) {
               $('#modal_agree_uni').modal('hide');
    
                if(data == 1){
                  echo(1);
                }else{
                  alert("Registro Fallido");
                }
            }) 

                               resetagree();
                                $('#modal_form_sol2').modal('show');

    }

function saveNewope(){
    nom_operador = $("#nombre_opeNew").val();
    ape_operador = $("#ape_opeNew").val();
    tel_operador = $("#tel_opeNew").val();
    rfc_operador = $("#rfc_opeNew").val();
    age_operador = $("#age_opeNew").val();
    est_operador = $("#est_opeNew").val();
    ciu_operador = $("#ciu_opeNew").val();
    calle_operador = $("#calle_opeNew").val();
    num_exterior = $("#noext_opeNew").val();
    num_interior = $("#noint_opeNew").val();
    col_operador = $("#col_opeNew").val();
    cp_operador = $("#cp_opeNew").val();
    num_lic = $("#nolic_opeNew").val();
    fecha_ingreso = $("#fechaAgree").val();
    tel1 = $('#tel1_opeNew').val();
    tel2 = $('#tel2_opeNew').val();
    nom_emer = $('#nomeme_opeNew').val();

          $.ajax({ /// EVIANDO PARAMETROS POST
            data :{nom_operador:nom_operador,ape_operador:ape_operador,tel_operador:tel_operador,
                    rfc_operador:rfc_operador,age_operador:age_operador,est_operador:est_operador,
                    ciu_operador:ciu_operador,calle_operador:calle_operador,num_exterior:num_exterior,
                    num_interior:num_interior,col_operador:col_operador,cp_operador:cp_operador,num_lic:num_lic,
                    fecha_ingreso:fecha_ingreso,tel1:tel1,tel2:tel2,nom_emer:nom_emer},
            url: 'ajax.php?c=ordenservicio&f=SaveNewOpe',
            type: 'POST',
            dataType: 'text',
          })
            .done(function(data) {
               $('#modal_agree_ope').modal('hide');
    
                if(data == 1){
                  echo(1);
                }else{
                  alert("Registro Fallido");
                }
            }) 

                               resetagree();
                                $('#modal_form_sol2').modal('show');
}
    

function saveNewcaja(){
  numero = $("#numeco_new_caja").val();
  placas =  $("#placas_new_caja").val();
  ejes = $("#ejes_new").val();
  color = $("#color_new_caja").val();
  obs = $("#observaciones_caja").val();
  fecha = $("#fecha_new_caja").val();
  tipo = $("#tipocaja_new").val();

   $.ajax({ /// EVIANDO PARAMETROS POST
            data :{numero:numero,placas:placas,ejes:ejes,color:color,obs:obs,fecha:fecha,tipo:tipo},
            url: 'ajax.php?c=ordenservicio&f=SaveNewcaja',
            type: 'POST',
            dataType: 'text',
          })
            .done(function(data) {
               $('#modal_agree_caja').modal('hide');
    
                if(data == 1){
                  echo(1);
                }else{
                  alert("Registro Fallido");
                }
            }) 

                               resetagree();
                              $('#modal_form_sol2').modal('show');
                               



}



function cierra(){
window.close();
}













