//////////////////////////////INICIO EVIDENCIAS Y REQUERIMIENTOS///////////////////////////////////

  function reload_table_evireq()
    {
                  $.ajax({
                  url: 'ajax.php?c=ordenservicio&f=listaevireq',
                  type: 'POST',
                  dataType: 'json',
                  })
                    .done(function(data) {
                          //console.log(data); //devuelve los obj...
                        var table = $('#table_listado_evireq').DataTable();
                            //$('.filas').empty();
                            table.clear().draw();
                            var x ='';
                            $.each(data, function(index, val) {
                                x ='<tr idevireq="'+val.idevireq+'" class="filas">'+
                                                '<td>'+val.idevireq+'</td>'+
                                                '<td>'+val.nombretienda+'</td>'+
                                                '<td>'+val.estado+'</td>'+
                                                '<td>'+val.municipio+'</td>'+
                                                '<td>'+val.requerimientos+'</td>'+
                                                '<td>'+val.evidencia+'</td>'+
                                                '<td><a class="btn btn-sm btn-primary" href="javascript:void()" title="Editar" onclick="edit_evireq('+val.idevireq+','+val.idcliente+')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>'+
                                                '<a class="btn btn-sm btn-danger" href="javascript:void()" title="Eliminar" onclick="delete_evireq('+val.idevireq+')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a> </td>'
                                                +'</tr>';
                                    table.row.add($(x)).draw();
                            });
                    })
    }



    function delete_evireq(idevireq){

        if(confirm('Seguro que desea eliminar el convenio?'))
      {
            $.ajax({ /// EVIANDO PARAMETROS POST
            data : {idevireq},
            url: 'ajax.php?c=ordenservicio&f=deleteEvireq',
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

      reload_table_evireq();
    }

 function addER(){

        $('#modal_form_ER').modal('show'); // show bootstrap modal
          $('#cli').empty(''); // se limpian opciones anteriomente cargadas
          $('#contacto').val('');
          $('#cli').html('<option selected="selected" value="0">Selecciona un Cliente</option>');
          $('#evi').html('').val('');
          $('#req').html('').val('');
         
          ///LISTAR CLIENTES
          $.ajax({
            url: 'ajax.php?c=ordenservicio&f=listaClientes',
            type: 'POST',
            dataType: 'json',
          })
            .done(function(data) {
              idcliente =0;
                $.each(data, function(index, val) {
                  $('#cli').append('<option value="'+val.id+'">'+val.nombretienda+'</option>');
                  
                });
                $('#cli').change(function(){
                    idcliente = $('#cli').val();
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
                          });
                      })
                    }else{
                      alert("Seleccione un Cliente");
                      return false;

                    }
                });
            })
          
      }




function save_evireq(){

      cliente  =  $('#cli').val();
     
      requerimientos  =  $('#req').val();
        evidencias  =  $('#evi').val();
        $.ajax({ /// EVIANDO PARAMETROS POST
            data : {cliente:cliente,requerimientos:requerimientos,evidencias:evidencias},
            url: 'ajax.php?c=ordenservicio&f=saveEvireq',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
                $('#modal_form_conv').modal('hide');
                reload_table_con(idsolicitud);
                if(data == 1){
                  //alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })
 $('#modal_form_ER').modal('hide');
 reload_table_evireq();
 resetcampos();
}


function resetcampos(){
          $('#cli').empty(); // se limpian opciones anteriomente cargadas
          $('#contacto').val('');
          $('#evi').html('').val('');
          $('#req').html('').val('');
          $('#cli').select2({placeholder: 'Selecciona un cliente', allowClear: true});
         
}

function resetcamposEdit(){
          $('#cliE').empty(); // se limpian opciones anteriomente cargadas
          $('#eviE').html('').val('');
          $('#reqE').html('').val('');
     
         
}



function edit_evireq(idevireq,idcliente){
   $('#cliE').empty();

      $.ajax({
        data : {idevireq:idevireq,idcliente:idcliente},
        url: 'ajax.php?c=ordenservicio&f=edit_evireq',
        type: 'POST',
        dataType: 'JSON',
      })
      .done(function(data){
       
        $.each(data, function(index, val) {
          
        
          $('#idevireqE').val(val.idevireq).attr('readonly','readonly');
          $('#eviE').val(val.evidencia);
          $('#reqE').val(val.requerimientos);
          $('#cliE').val(val.nombretienda).attr('readonly','readonly');

        });
      })
       $('#modal_form_edit_ER').modal('show');
       resetcamposEdit();
    }// FIN FUNCTION



 function send_edit_evireq(){
         evidencias = $("#eviE").val();
         requerimientos = $("#reqE").val();
         idevireqE = $("#idevireqE").val();

         $.ajax({
        data: {evidencias:evidencias,requerimientos:requerimientos,idevireqE:idevireqE},
               url: 'ajax.php?c=ordenservicio&f=send_edit_evireq',
               type : 'POST',
               dataType : 'text',
               })
  .done(function(data){      
  if(data == 1){
   }
  else{
  alert("Registro Fallido");
      }
  })    
    $('#modal_form_edit_ER').modal('hide');
     reload_table_evireq(); 
}


























