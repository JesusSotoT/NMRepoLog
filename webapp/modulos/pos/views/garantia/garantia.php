<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Garantía</title>
        <link rel="stylesheet" href="">

        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
        <script src="../../libraries/jquery.min.js"></script>
        <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
        <!--Select 2 -->
        <script src="../../libraries/select2/dist/js/select2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

        <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
        <!-- Modificaciones RC -->
        <script src="../../libraries/dataTable/js/datatables.min.js"></script>
        <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
        <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
        <script src="../../libraries/export_print/buttons.html5.min.js"></script>
        <script src="../../libraries/export_print/jszip.min.js"></script>
        <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
        <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
        
        <script src="js/garantia.js"></script>
    </head>
<body>
    <br>
    <div class="container well col-sm-12">
       
        <h3>Garantía</h3>
        <div class="row">
            <div class='col-sm-12 col-md-2'>
                <button id="btn-nueva-garantia" class="btn btn-primary" data-toggle="modal" data-tajet="modalEditar"><i class="fa fa-plus" aria-hidden="true" ></i> Nueva garantía </button>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-12" style="overflow:auto;">
                <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Duración (días)</th>
                        <th>Modificar</th>
                      </tr>
                    </thead>
                    <tbody id="tabla-principal">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>