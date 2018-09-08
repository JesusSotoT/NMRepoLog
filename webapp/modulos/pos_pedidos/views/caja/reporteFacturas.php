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
    <script src="js/cliente.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
   <script>
   $(document).ready(function() {

        $('#tableGrid').DataTable({
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
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
 <!--   <div class="row">
        <div class="col-sm-1">
             <button class="btn btn-primary" onclick="newClient();"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Cliente</button>
        </div>
      
    </div> -->
    <div class="row">
        <div class="col-sm-12">
            <label>Total: <?php //echo $facturas['total']; ?></label>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12" style="overflow:auto;">
            <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Folio</th>
                        <th>Venta</th>
                        <th>UUID</th>
                        <th>Pago</th>
                        <th>Cliente</th>
                        <th>RFC</th>
                        <th>Estatus</th>
                        <th>Tipo</th> 
                      <!--  <th>Autorizo</th>
                        <th>Estatus</th>
                        <th>Modificar</th> -->
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $status="";

                      /*  foreach ($facturas['clientes'] as $key => $value) {

                            if($value['borrado']==0){
                                $status = '<span class="label label-success">Activo</span>';
                            }else{
                                $status = '<span class="label label-danger">Inactivo</span>';
                            }
                            echo '<tr>';
                            echo '<td>'.$value['id'].'</td>';
                            echo '<td>'.$value['nombre'].'</td>';
                            echo '<td>'.$value['nombretienda'].'</td>';
                            echo '<td>'.$value['rfc'].'</td>';
                            echo '<td>'.$value['curp'].'</td>';
                            echo '<td>'.$value['direccion'].'</td>';
                            echo '<td>'.$status.'</td>';


                            echo '</tr>';
                        } */
                        ?>
                    </tbody>
                </table>
        </div>        
    </div>
</div>
    
</body>
</html>