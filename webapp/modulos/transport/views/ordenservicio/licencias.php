<style type="text/css">
  #overflow {
  border: 1px solid blue;
  width:198px;
  overflow-y: auto;
  height: 100%;
}
.table th, .table td {
     

 }
 .table2 td{
  font-size: 8px;
 }
.row tr {
width: 20px; /*Aquí va el ancho de la Celda*/
height: 20px; /*Aquí el Alto*/
background-color: gray;
}
table.table.table-condensed {
    border: 1px solid gray;
}


.tabla th {
padding: 10px;
font-size: 13px;
background-color:  #960017;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
.tabla2 th {
padding: 10px;
font-size: 8px;
background-color: #a4c3d1;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-top-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-top-style: solid;
border-right-color: #558FA6;
border-bottom-color: #558FA6;
border-top-color: #558FA6;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
.tabla3 td {
padding: 10px;
font-size: 8px;
color: black;
border-right-width: 1px;
border-bottom-width: 1px;
border-top-width:solid;
border-right-style: solid;
border-bottom-style: solid;
border-top-style: solid;
border-right-color: #060606;
border-bottom-color: #060606;
border-top-color: #060606;
font-family: “Trebuchet MS”, Arial;
text-transform: uppercase;
}
.texto-vertical-1 {
    writing-mode: vertical-lr;
     -ms-transform:rotate(180deg); /* IE 9 */
  -moz-transform:rotate(180deg); /* Firefox */
  -webkit-transform:rotate(180deg); /* Safari and Chrome */
  -o-transform:rotate(180deg); /* Opera */
   
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
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/funciones_evireq.js"></script>
  <script src="js/licencias.js"></script>


  <div class="container">
    <h1>Licencias y Conductores</h1>
     <hr style="

    margin-right:1px;
    border-width:3px;
    border-color: #960017;
           "   >
           <br>
           <br>
           
    <button class="btn btn-success" onclick="addlic()"><i class="glyphicon glyphicon-plus"></i>Agregar Licencia</button>

    <br>
    <br>

       <table id="table_listado_lic" class="table tabla table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Conductor</th>
            <th>Codigo</th>
            <th>Estatus</th>
            <th>Licencia</th>
            <th>Tipo de licencia</th>
            <th>Fecha de Expiracion</th>
          </tr>
        </thead>
        <tbody>
        </tbody>

      </table>
  </div>

   <!-- Modal para Agregar-->
  <div class="modal fade" id="modal_form_addlic" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title_conv col-xs-1"> Agregar Nueva Licencia </h3>
      </div>
      <div class="modal-body form">
        <form action="#" id="form_convenio" class="form-horizontal">
          <div class="form-body">
            <div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Selecciona un Conductor</h3>
  </div>
  <div class="panel-body">
  <div class="form-group">
  <label class="control-label col-md-2">Conductor:</label>
              <div class="col-md-9">
                <select id="chofer" class="form-control " style="width: 100%"></select>
              </div>
            </div>
            <div>
            <label class="col-md-1">Nombre  </label>
            <input class="col-md-4" id="nombC">
            <label class="col-md-1">Estatus  </label>
            <input class="col-md-4" id="estatusC">
            <label class="col-md-1">Codigo  </label>
            <input class="col-md-1" id="codiC">
            </div>
  </div>
  </div>

    <div class="form-body">
            <div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">Ingresa Datos de Licencia</h3>
  </div>
  <div class="panel-body">
            <div class="form-group">
              <label class="control-label col-md-3">Numero de licencia :</label>
              <div class="col-md-9">
                <input type="text" id="numLic" class="form-control" ></input>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Tipo de licencia :</label>
              <div class="col-md-3">
               <select id="tipoLic" class="form-control " style="width: 50%">
                 <option value="A">A</option>
                 <option value="B">B</option>
                 <option value="C">C</option>
                 <option value="D">D</option>
                 <option value="E">E</option>
               </select>

              </div>
            
             
              <label class="control-label col-md-3">Vigencia :</label>
              <div class="col-md-3">
                <input type="date" id="vigLic" value="<?php echo date("Y-m-d"); ?>" >
              </div>
            </div>
          </div>
        </div>
      </div>
        </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="btnSave" onclick="save_lic()" class="btn btn-primary">Guardar</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="resetcampos()">Cancelar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
  
<script type="text/javascript">
  $(document).ready(function(){

          reload_table();
          resetcampos();

      })

  $('#chofer').select2();
  
</script>

