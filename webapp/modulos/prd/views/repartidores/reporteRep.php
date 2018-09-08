<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reporte de Repartidores</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="js/repartidores/repartidores.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <!--Data Tables -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

    <!--Button Print css -->
    <link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!--Button Print js -->
    <script src="../../libraries/dataTable/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/dataTable/js/buttons.print.min.js"></script>
    <body> 
        <div class="container panel col-xs-12 col-md-12" >
            <div class="row">
                <div class="col-xs-12 col-md-12">
                   <h3>Repartidores</h3>
                </div>
            </div>
            <div class="container panel">                
                <div class="container"  style="overflow:auto">
                    <div class="panel-heading ">
                        <div aling="center">
                            <label>Repartidor:</label>
                            <select id="repartidor" class="form-control" style="width: 80%;" multiple="multiple">
                                <option value="0" selected>-Todos-</option>                        
                            </select>
                            <button class="btn btn-default" onclick="reloadRep();">Procesar</button><br> 
                        </div>
                        <br>
                    </div>
                    <div class="col-sm-12">
                        <table id="table_ambos" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                          <tr>
                            <th td width="30">Comanda</th>
                            <th td width="30">Repartidor</th>
                            <th td width="30">Pedido Asignado</th>
                            <th td width="30">Pedido Entregado</th>
                            <th td width="30">Pedido Pagado</th>
                            <th td width="30">Estatus</th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<script>
    $(document).ready(function() {
        $("#repartidor").select2();
        autoload2();
        $.ajax({
            url: 'ajax.php?c=repartidores&f=listarRepartidor',
            type: 'post',
            dataType: 'json',
        })
        .done(function(data) {
            $('#repartidor').html('');
            $('#repartidor').append('<option value="0">-Todos-</option>'); 
            $.each(data, function(index, val) {
              $('#repartidor').append('<option value="'+val.id+'">'+val.nombre+'</option>');  
            });
        })

    });
</script>