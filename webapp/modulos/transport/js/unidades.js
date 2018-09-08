function reloadtable(){
	 $.ajax({
        url: 'ajax.php?c=ordenservicio&f=listaunidadesalta',
        type: 'POST',
        dataType: 'json',
      })
      .done(function(data) {
        var table = $('#table_listado_unidades').DataTable({responsive: true});
        table.clear().draw();
        var x ='';
        $.each(data, function(index, val) {
          x ='<tr idunidad="'+val.idunidad+'" class="filas">'+
          '<td>'+val.idunidad+'</td>'+
          '<td width="50%" style="font-size: 15px" align="center">'+val.marca+' - '+val.modelo+'</td>'+
          '<td width="5%" align="center">'+val.no_economico+'</td>'+
          '<td align="center">'+val.placas+'</td>'+
          '<td align="center">'+val.anio+'</td>'+
          '<td align="center">'+val.color+'</td>'+
          '<td align="center">'+val.combustible+'</td>'+
          '<td align="right" width="15%"><a class="btn btn-sm btn-info"  title="Informacion de Unidad" onclick="showUniInfo('+val.idunidad+')"><i style="color: white;" class="glyphicon glyphicon-circle-arrow-up"></i></a> - <a class="btn btn-sm" style="background-color: #f37735; color: white;"  title="Editar Datos de Unidad" onclick=""><i style="color: white;" class="glyphicon glyphicon-pencil"> </i></a> - <a class="btn btn-sm btn-danger"  title="Eliminar Unidad" onclick=""><i style="color: white;" class="glyphicon glyphicon-remove-sign"></i></a></td>'+            
          '</tr>';  
          table.row.add($(x)).draw();                           
        });
      }) 

}

function addUni(){
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


/////////////////////////////// RADIOS FUCTION////////////////////////////////////////////////////////////////////////////////////
$(document).ready(function () {
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

function save_addUni(){

   if(  $('#refriUni').prop('checked')){
      refrigerado = $('#refriUni').val();

  }else{
      refrigerado =  $('#refriUniNo').val();
  }

      no_economico =  $('#noEconomico').val();
      marca =  $('#marcaUni').val();
      anio = $('#anioUni').val();
      placas =  $('#placasUni').val();
      color = $('#colorUni').val();
      tipo =  $('#tipoUni').val();
      capacidad = $('#capUni').val();
      tipocomb =  $('#tipocombustibleUni').val();
      tamanotanUni =  $('#tamanotanqUni').val();
      rendforUni =  $('#rendforUni').val();
      rendlocUni =  $('#rendlocUni').val();
      tamtanqthem =  $('#tamtanqthermo').val();
      rendthermfor = $('#rendthermfor').val();
      rendthermloc = $('#rendthermloc').val();
      fechaaddUni = $('#fechaaddUni').val();
      kmadquisicion = $('#kmadquisicion').val();
      kmtotal = $('#kmtotal').val();
      observaciones = $('#observaciones').val();
      modelo = $('#modeloUni').val()

    $.ajax({ /// EVIANDO PARAMETROS POST
    data :{refrigerado:refrigerado,no_economico:no_economico,marca:marca,anio:anio,placas:placas,color:color,
            tipo:tipo,capacidad:capacidad,tipocomb:tipocomb,tamanotanUni:tamanotanUni,rendforUni:rendforUni,rendlocUni:rendlocUni,
            tamtanqthem:tamtanqthem,rendthermfor:rendthermfor,rendthermloc:rendthermloc,fechaaddUni:fechaaddUni,kmadquisicion:kmadquisicion,
            kmtotal:kmtotal,observaciones:observaciones,modelo:modelo},
    url: 'ajax.php?c=ordenservicio&f=save_addUni',
    type: 'POST',
    dataType: 'text',
    })
    .done(function(data) {
                $('#modal_form_addUni').modal('hide');
                reloadtable();
                if(data == 1){
                  alert("Registro Exitoso");
                }else{
                  alert("Registro Fallido");
                }
            })

    resetcamposUni();
}


function showUniInfo(idunidad){
  resetcampos();
  $.ajax({ /// EVIANDO PARAMETROS POST
    data : {idunidad:idunidad},
    url: 'ajax.php?c=ordenservicio&f=infoUni',
    type: 'POST',
    dataType: 'json',
    })

.done(function(data) {
    $('#modal_form_infoUni').modal('show'); //show boostrap modal
            $('#idunidadInfo').val(idunidad);
$.each(data, function(index, val) {
         
         var refrigerado = val.refrigerado;

         if (refrigerado == 1) {
            $('#refriUniInfo').prop('checked', true).attr('disabled');
            $('#refriUniNoInfo').prop('checked', false).attr('disabled');

         $('#noEconomicoInfo').val(val.no_economico).attr('readonly','readonly');
         $('#marcaUniInfo').append('<option value="'+val.idmarca+'">'+val.marca+'</option>').attr('readonly','readonly');
         $('#modeloUniInfo').val(val.modelo).attr('readonly','readonly');
         $('#anioUniInfo').val(val.anio).attr('readonly','readonly');
         $('#placasUniInfo').val(val.placas).attr('readonly','readonly');
         $('#colorUniInfo').val(val.color).attr('readonly','readonly');
         $('#tipoUniInfo').append('<option value="'+val.tipo_unidad+'">'+val.tipo+'</option>').attr('readonly','readonly');
         $('#capUniInfo').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>').attr('readonly','readonly');
         $('#tipocombustibleUniInfo').append('<option value="'+val.idtipocombustible+'">'+val.combustible+'</option>').attr('readonly','readonly');
         $('#tamanotanqUniInfo').val(val.tanque_tamano).attr('readonly','readonly');
         $('#rendforUniInfo').val(val.tanque_rendimiento_foraneo).attr('readonly','readonly');
         $('#rendlocUniInfo').val(val.tanque_rendimiento_local).attr('readonly','readonly');
         $('#tamtanqthermoInfo').val(val.tanque_tamano_thermo).attr('readonly','readonly');
         $('#rendthermforInfo').val(val.tanque_rendimiento_foraneo_thermo).attr('readonly','readonly');
         $('#rendthermlocInfo').val(val.tanque_rendimiento_local_thermo).attr('readonly','readonly');
         $('#fechaaddUniInfo').val(val.fecha_adquisicion).attr('readonly','readonly');
         $('#kmadquisicionInfo').val(val.kmadquisicion).attr('readonly','readonly');
         $('#kmtotalInfo').val(val.kmtotal).attr('readonly','readonly');
         $('#observacionesInfo').val(val.observaciones).attr('readonly','readonly');
         }
         else{
          $('#refriUniNoInfo').prop('checked', true).attr('disabled');
          $('#refriUniInfo').prop('checked', false).attr('disabled');
         $('#noEconomicoInfo').val(val.no_economico).attr('readonly','readonly');
         $('#marcaUniInfo').append('<option value="'+val.idmarca+'">'+val.marca+'</option>').attr('readonly','readonly');
         $('#modeloUniInfo').val(val.modelo).attr('readonly','readonly');
         $('#anioUniInfo').val(val.anio).attr('readonly','readonly');
         $('#placasUniInfo').val(val.placas).attr('readonly','readonly');
         $('#colorUniInfo').val(val.color).attr('readonly','readonly');
         $('#tipoUniInfo').append('<option value="'+val.tipo_unidad+'">'+val.tipo+'</option>').attr('readonly','readonly');
         $('#capUniInfo').append('<option value="'+val.idcapacidadunidad+'">'+val.capacidad+'</option>').attr('readonly','readonly');
         $('#tipocombustibleUniInfo').append('<option value="'+val.idtipocombustible+'">'+val.combustible+'</option>').attr('readonly','readonly');
         $('#tamanotanqUniInfo').val(val.tanque_tamano).attr('readonly','readonly');
         $('#rendforUniInfo').val(val.tanque_rendimiento_foraneo).attr('readonly','readonly');
         $('#rendlocUniInfo').val(val.tanque_rendimiento_local).attr('readonly','readonly');
         $('#thermotable').hide();
         $('#tamtanqthermoInfo').hide();
         $('#rendthermforInfo').hide();
         $('#rendthermlocInfo').hide();
         $('#fechaaddUniInfo').val(val.fecha_adquisicion).attr('readonly','readonly');
         $('#kmadquisicionInfo').val(val.kmadquisicion).attr('readonly','readonly');
         $('#kmtotalInfo').val(val.kmtotal).attr('readonly','readonly');
         $('#observacionesInfo').val(val.observaciones).attr('readonly','readonly');
         }
        
        });



    })

}





function resetcamposUni(){
         $('#noEconomicoInfo').val('');
         $('#modeloUniInfo').val('');
         $('#anioUniInfo').val('');
         $('#placasUniInfo').val('');
         $('#colorUniInfo').val('');
         $('#tamanotanqUniInfo').val('');
         $('#rendforUniInfo').val('');
         $('#rendlocUniInfo').val('');
         $('#tamtanqthermoInfo').val('');
         $('#rendthermforInfo').val('');
         $('#rendthermlocInfo').val('');
         $('#fechaaddUniInfo').val('');
         $('#kmadquisicionInfo').val('');
         $('#kmtotalInfo').val('');
         $('#observacionesInfo').val('');

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

















