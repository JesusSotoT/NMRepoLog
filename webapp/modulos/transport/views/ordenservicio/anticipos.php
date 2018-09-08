<style>
  .tabla-anticipos th {
padding: 10px;
font-size: 13px;
background-color: #332a24;
color: #fff;
border-right-width: 1px;
border-bottom-width: 1px;
border-right-style: solid;
border-bottom-style: solid;
border-right-color: #332a24;
border-bottom-color: #332a24;
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
  <script src="js/datatables.min.js" type="text/javascript"></script>
  <script src="js/bootstrap-datepicker.min.js" type="text/javascript"></script>
  <script src="js/anticipos.js"></script>
<div class="container">
    <h1>Aprobacion de Anticipos</h1>
    <hr style=" margin-right:1px; border-width:3px; border-color: #332a24;">
    <table id="table_listado_ant" class="table table-striped table-bordered tabla-anticipos" cellspacing="0" width="100%">
      <thead>
        <tr>
            <th>ID</th>
            <th>Orden de Servicio</th>
            <th>Fecha de Captura</th>
            <th>Conductor</th>
            <th>Referencia</th>
            <th>Importe</th>
            <th>Estatus</th>
            <th>Forma de Pago</th>
            <th>Acciones</th>
        </tr>
      </thead>
    <tbody>
    </tbody>

      </table>
  </div>

    <script> 
       $(document).ready(function(){
        reload_table_ant(); })
    </script>