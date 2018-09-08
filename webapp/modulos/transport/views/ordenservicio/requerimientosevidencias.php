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
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/funciones_evireq.js"></script>
  <script src="js/funciones_gen.js"></script>

<div class="container">
    <h1>Requerimientos y Evidencias</h1>
     <hr style="

    margin-right:1px;
    border-width:3px;
    border-color: black;
           "   >
           <br>
           <br>
           
    <button class="btn btn-success" onclick="addER()"><i class="glyphicon glyphicon-plus"></i>Agregar nuevo</button>

    <br>
    <br>

       <table id="table_listado_evireq" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Cliente</th>
            <th>Estado</th>
            <th>Municipio</th>
            <th>Requerimientos</th>
            <th>Evidencia(s)</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        </tbody>

      </table>
  </div>
  


  <!-- Modal para Agregar-->
  <div class="modal fade" id="modal_form_ER" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title_conv col-xs-1"> Nuevos Requerimientos y evidencias </h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form_convenio" class="form-horizontal">
          <input type="hidden" value="" id="id0" name="id"/>
          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Cliente:</label>
              <div class="col-md-9">
                <select id="cli" class="form-control " style="width: 100%"></select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Contacto:</label>
              <div class="col-md-9">
                <input type="text" id="contacto" class="form-control" style="width: 100%"></input>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Evidencias:</label>
              <div class="col-md-9">
                <textarea id="evi" class="form-control" rows="5" cols="125" placeholder="Escriba aqui las evidencias"></textarea>
              </div>
            </div>
             <div class="form-group">
              <label class="control-label col-md-3">Requerimientos:</label>
              <div class="col-md-9">
                <textarea id="req" class="form-control" rows="5" cols="125" placeholder="Escriba aqui los requerimientos"></textarea>
              </div>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save_evireq()" class="btn btn-primary">Guardar</button>
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
        <h3 class="modal-title_conv col-xs-1"> Editar Requerimientos y evidencias </h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form_convenio" class="form-horizontal">
          <input type="hidden" value="" id="id0" name="id"/>
          <div class="form-body">
          <input type="hidden" id="idevireqE">
            <div class="form-group">
              <label class="control-label col-md-3">Cliente:</label>
              <div class="col-md-9">
                <input id="cliE" class="form-control " style="width: 100%"></input>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Evidencias:</label>
              <div class="col-md-9">
                <textarea id="eviE" class="form-control" rows="5" cols="125"></textarea>
              </div>
            </div>
             <div class="form-group">
              <label class="control-label col-md-3">Requerimientos:</label>
              <div class="col-md-9">
                <textarea id="reqE" class="form-control" rows="5" cols="125"></textarea>
              </div>
            </div>
          </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button"  class="btn btn-success" onclick="send_edit_evireq()">Editar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetcamposEdit()">Cancelar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  

     


    <script> 
      $('#cli').select2();

      $(document).ready(function(){

          reload_table_evireq();

      })


     

    </script>