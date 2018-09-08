<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ordenes de Compra</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/facturas.js"></script>

    <!-- Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!-- <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

        <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js" type="text/javascript"></script>
   <script>

   $(document).ready(function() {

        $('#tableGrid').DataTable({
            autowidth: 'false',
            dom: 'Bfrtip',
            buttons: [ 'excel' ],
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ facturas",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });
   });
   </script>
<body>
<div class="container well">
<div class="row">
    <div class="col-xs-12">
        <h3 id="titulo">Cuentas por cobrar</h3>
    </div>
</div>
<div class="row">
    <div class="col-xs-3"></div>
    <div class="col-xs-3"></div>
    <div class="col-xs-3">
        <label>Reporte</label>
        <select  id="reporte">
            <option value="1">Operadores</option>
            <option value="1">Tarjetas Corporativas</option>
        </select>
    </div>
    <div class="col-xs-3"><button class="btn btn-default">Ejecutar</button></div>
</div>
</div>
</body>
</html>
