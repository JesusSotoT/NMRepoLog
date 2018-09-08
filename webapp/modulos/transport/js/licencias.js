function resetcampos(){
    $('#nombC').val('');
    $('#estatusC').val('');
    $('#codiC').val('');
    $('#chofer').select2({placeholder: 'Selecciona un cliente', allowClear: true});
    $('#tipoLic').val('');
    $('#numLic').val('');
    $('#vigLic').val('');
}

function reload_table(){
    $.ajax({
    url: 'ajax.php?c=ordenservicio&f=reload_lic',
    type: 'POST',
    dataType: 'json',
    })
                    .done(function(data) {
                    var table = $('#table_listado_lic').DataTable();
                    table.clear().draw();
                    var x ='';    
                    $.each(data, function(index, val) {

                    x ='<tr idEmpelado="'+val.idEmpleado+'">'+
                    '<td>'+val.idEmpleado+'</td>'+
                    '<td>'+val.nombre+'</td>'+
                    '<td>'+val.codigo+'</td>'+
                    '<td>'+val.estatus_tran+'</td>'+
                    '<td>'+val.numerolicencia+'</td>'+
                    '<td>'+val.tipolicencia+'</td>'+
                    '<td>'+val.vigencia+'</td>'+
                    +'</tr>';
                    table.row.add($(x)).draw();
              });
       })
}

function addlic(){
$('#modal_form_addlic').modal('show');

                    $.ajax({
                    url: 'ajax.php?c=ordenservicio&f=listaConductores',
                    type: 'POST',
                    dataType: 'json',
                    })
                    .done(function(data) {
                    idchofer=0;
                    $.each(data, function(index, val) {
                    $('#chofer').append('<option value="'+val.idEmpleado+'">'+val.nombre+'</option>');

                    $('#chofer').change(function(){
                    idchofer = $('#chofer').val();
                    if(idchofer > 0){
                    //alert(idest);
                    $.ajax({ /// LISTAR CIUDADES
                    data : {idchofer:idchofer},
                    url: 'ajax.php?c=ordenservicio&f=listaConductores1',
                    type: 'POST',
                    dataType: 'json',
                    })
                    .done(function(data) {
                    $.each(data, function(index, val) {
                    $('#nombC').val(val.nombre);
                    $('#estatusC').val(val.estatus_tran);
                    $('#codiC').val(val.codigo);
                    });
                    })
                    
                    resetcampos();
                   

                   }
                });
              });
            })

          }

function save_lic(){
chofer  =  $('#chofer').val();
if(chofer == 0){
  alert("Debes seleccionar a un Conductor para poder registrar su licencia");
  return false;
}
licencia = $('#numLic').val();
if(licencia == 0){
  alert("Debes ingresar un numero de licencia");
  return false;
}
tipoLic = $('#tipoLic').val();
if (tipoLic == 0) {
  alert("Debes elegir el tipo de licencia");
  return false;
}
vigencia = $('#vigLic').val();
if (vigencia == 0) {
  alert("Debes de ingresar la vigencia de la licencia");
  return false;
}

        $.ajax({ /// EVIANDO PARAMETROS POST
            data : {chofer:chofer,licencia:licencia,tipoLic:tipoLic,vigencia:vigencia},
            url: 'ajax.php?c=ordenservicio&f=saveLic',
            type: 'POST',
            dataType: 'text',
          })
          .done(function(data) {
          $('#modal_form_addlic').modal('hide');
            if(data == 1){
             echo ("Registro Exitoso!!");
            }else{
             //alert("Registro Fallido");
            }
          })
 $('#modal_form_addlic').modal('hide');
 resetcampos();
 reload_table();

}



