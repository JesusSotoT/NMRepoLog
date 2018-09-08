function reload_table_ant()
{
  $.ajax({
  url: 'ajax.php?c=ordenservicio&f=reload_table_ant',
  type: 'POST',
  dataType: 'json',
  })
  .done(function(data) {  
      var table = $('#table_listado_ant').DataTable(); 
          table.clear().draw();
          var x ='';
              $.each(data, function(index, val) {
            x ='<tr idevireq="'+val.idanticipo+'" class="filas">'+
                  '<td>'+val.idanticipo+'</td>'+
                  '<td>'+val.idordenservicio+'</td>'+
                  '<td>'+val.fecha_captura+'</td>'+
                  '<td>'+val.nombre+'</td>'+
                  '<td>'+val.referencia+'</td>'+
                  '<td>'+val.importe+'</td>'+
                  '<td>'+val.estatus2+'</td>'+
                  '<td>'+val.nombrepago+'</td>'+
                  '<td><a class="btn btn-sm btn-success" href="javascript:void()" title="APROBAR" onclick="aprobar_ant('+val.idanticipo+','+val.idordenservicio+')"><i class="glyphicon glyphicon glyphicon-ok"></i>APROBAR</a><br> <br>'+
                  '<a class="btn btn-sm btn-danger" href="javascript:void()" title="RECHAZAR" onclick="rechazar_ant('+val.idanticipo+','+val.idordenservicio+')"><i class="glyphicon glyphicon-remove"></i>RECHAZAR</a> </td>'
              +'</tr>';
                  table.row.add($(x)).draw();
        });
    })
}

function aprobar_ant(idanticipo,idordenservicio)
{
 if(confirm("¿ Deseas aprobar este anticipo ?")){
  $.ajax({
  data: {idanticipo:idanticipo,idordenservicio:idordenservicio},
  url: 'ajax.php?c=ordenservicio&f=aprobar_ant',
  type: 'POST',
  dataType: 'text',
  })
  .done(function(data) {
  echo (1);
  $.each(data, function(index, val) {

  alert("Se ha aprobado el anticipo exitosamente");

  });
  })
  }
  reload_table_ant();
  alert("Se ha aprobado el anticipo exitosamente");
  
}

function rechazar_ant(idanticipo,idordenservicio)
{
 if(confirm("¿ Deseas rechazar este anticipo ?")){
  $.ajax({
  data: {idanticipo:idanticipo,idordenservicio:idordenservicio},
  url: 'ajax.php?c=ordenservicio&f=rechazar_ant',
  type: 'POST',
  dataType: 'text',
  })
  .done(function(data) {
  echo (1);
  $.each(data, function(index, val) {

  alert("Se ha rechazado el anticipo exitosamente");

  });
  })
  }
  reload_table_ant();
  alert("Se ha rechazado el anticipo exitosamente");

}








