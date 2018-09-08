<style>
.tabla-porcentaje th {
padding: 10px;
font-size: 13px;
background-color: #60677c;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #60677c;
border-bottom-color: #60677c;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}

</style>


<head>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/datatablesboot.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css"> 
</head>

   <!-- ch@isystem - Librerias genericas -->
  <script src="../../libraries/jquery.min.js" type="text/javascript"></script>
  <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
  <script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
  <!-- ch@isystem- Librerias raiz transport -->
  <!-- <script src="js/dataTables.bootstrap.min.js" type="text/javascript"></script>-->
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/porcentaje_ope.js"></script>

  <div class="container">
    <h2>PORCENTAJE PARA OPERADORES</h2>
    

   <hr style="

    margin-right:1px;
    border-width:3px;
    border-color: #60677c;
           "   >           
    <button class="btn btn-success" onclick="addope()"><i class="glyphicon glyphicon-plus"></i>Agregar nuevo</button>

    <br>
    <br>

       <table id="table_listado_porcentaje" class="table table-striped table-bordered tabla-porcentaje" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Operador</th>
            <th>Porcentaje</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
  </div>
    </div>


  <!-- Modal para Agregar-->
  <div class="modal fade" id="modal_form_ope" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title_conv col-xs-1"> Asignar Porcentaje a Operador </h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form_convenio" class="form-horizontal">
          <input type="hidden" value="" id="id0" name="id"/>
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Operador:</label>
              <div class="col-md-9">
                <select id="ope" class="form-control " style="width: 100%"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Porcentaje:</label>
              <div class="col-md-9">
                <select id="porcentaje" class="form-control" style="width: 100%">
                <option value="13">13%</option>
                <option value="15">15%</option>
                </select>
              </div>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save_porcentajeope()" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetcampos()">Cancelar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  

   <!-- Modal para Editar -->
  <div class="modal fade" id="modal_form_edit_ER" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title_conv col-xs-1"> Editar Porcentaje de Operadores </h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form_convenio" class="form-horizontal">
          <input type="hidden" value="" id="idope"/>
          <div class="form-body">
          <input type="hidden" id="idevireqE">
            <div class="form-group">
              <label class="control-label col-md-3">Operador:</label>
              <div class="col-md-9">
                <input id="ope2" class="form-control"></input>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Porcentaje:</label>
             <select id="porcentaje" class="form-control">
                <option value="13">13%</option>
                <option value="15">15%</option>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button"  class="btn btn-success" onclick="send_edit_porcentajeope()">Editar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetcampos()">Cancelar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  

     



    <script> 
      $('#ope').select2();

      $(document).ready(function(){

          reload_table_porcentajeope();

      })

function reload_table_porcentajeope()
    {
                  $.ajax({
                  url: 'ajax.php?c=liquidaciones&f=listaporcentajeope',
                  type: 'POST',
                  dataType: 'json',
                  })
          .done(function(data) {
          //  console.log(data); //devuelve los obj...
          var table = $('#table_listado_porcentaje').DataTable();
          table.clear().draw();
          var x ='';
          $.each(data, function(index, val) {
          x ='<tr idEmpleado="'+val.idempleado+'" class="filas">'+
          '<td>'+val.idempleado+'</td>'+
          '<td>'+val.nombre+'</td>'+
          '<td>'+val.porcentaje+'</td>'+
          '<td><a class="btn  btn-primary" href="javascript:void()" title="Editar"><i class="glyphicon glyphicon-pencil" onclick="editporcentaje('+val.idempleado+')"></i>Editar</a></td>'
          +'</tr>';
          table.row.add($(x)).draw();
                            });
                    })
    }


function addope(){
  $('#modal_form_ope').modal('show');
  $('#ope').val('');
  $('#porcentaje').val('');

           $.ajax({
                    url: 'ajax.php?c=liquidaciones&f=listaConductores',
                    type: 'POST',
                    dataType: 'json',
                    })
                    .done(function(data) {
    
                    $.each(data, function(index, val) {
                    $('#ope').append('<option value="'+val.idempleado+'">'+val.nombre+'</option>');

                })
              });
            }

     function save_porcentajeope(){
      operador = $('#ope').val();
      porcentaje = $('#porcentaje').val();
       $.ajax({ /// EVIANDO PARAMETROS POST
            data : {operador:operador,porcentaje:porcentaje},
            url: 'ajax.php?c=liquidaciones&f=saveporcentaje',
            type: 'POST',
            dataType: 'text',
          })
       .done(function(data) {
                $('#modal_form_ope').modal('hide');
                reload_table_porcentajeope();
               
            })

}

function editporcentaje(idempleado){
 alert(idempleado);
  $.ajax({
        data : {idempleado:idempleado},
        url: 'ajax.php?c=liquidaciones&f=edit_ope',
        type: 'POST',
        dataType: 'JSON',
      })
      .done(function(data){
        $('#modal_form_edit_ER').modal('show');
        $.each(data, function(index, val) {
          $('#modal_form_edit_ER').modal('show');
          $('#idope').val(val.idEmpleado).attr('readonly','readonly');
          $('#ope2').val(val.nombre).attr('readonly','readonly');
          $('#porcentaje').val(val.porcentaje);

        });
      })
       //$('#modal_form_edit_ER').modal('show');
}


    </script>