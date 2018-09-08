<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Recepcion Ordenes de Compra</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/compra.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    
    <!-- Modificaciones RCA -->
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

   <script>
   $(document).ready(function() {
            $('#tableGrid').DataTable({
                            dom: 'Bfrtip',
                            buttons: ['excel'],
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
    <div class="row">
        <div class="col-sm-12">
            <h3>Recepcion Orden de Compra</h3>
        </div>
      <!--  <div class="col-sm-1">
             <button class="btn btn-default" onclick="newOrder();">Nueva Orden</button>
        </div> -->
      
    </div>
    <div class="row">
        <div class="col-sm-12" style="overflow:auto;">
                     <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Proveedor</th>
                        <th>Fecha Pedido</th>
                        <th>Fecha Entrega</th>
                        <th>Elaboro</th>
                        <th>Total</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                      <!--  <th>Autorizo</th>
                        <th>Estatus</th>
                        <th>Modificar</th> -->
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $status="";
                        foreach ($ordenesCompra as $key => $value) {

                            if($value['activo']==1){
                                $status = '<span class="label label-primary">Activo</span>';
                            }elseif($value['activo']==3){
                                $status = '<span class="label label-succeess">Recibida</span>';
                            }elseif($value['activo']==4){
                                $status = '<span class="label label-danger">Cerrada</span>';
                            }elseif($value['activo']==5){
                                $status = '<span class="label label-warning">Parcial</span>';
                            }else{
                                 $status = '<span class="label label-warning">0</span>';
                            }
                            echo '<tr>';
                            echo '<td>'.$value['id'].'</td>';
                            echo '<td>'.$value['razon_social'].'</td>';
                            echo '<td>'.$value['fecha'].'</td>';
                            echo '<td>'.$value['fecha_entrega'].'</td>';
                            echo '<td>'.$value['usuario'].'</td>';
                            echo '<td>$'.number_format($value['total'],2).'</td>';
                            echo '<td>'.$status.'</td>';
                            //echo '<td>'.$value['Autorizacion'].'</td>';
                            //echo '<td>'.$value['Estatus'].'</td>';
                            //Columna de los botones
                            echo '<td>';
                            //echo '<a href="index.php?c=compra&f=ordenCompra&idorden='.$value['id'].'" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a> ';
                            if($value['activo']==4){
                                echo '<a href="index.php?c=compra&f=recepcionOrdenRecibida&idorden='.$value['id'].'" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-th-list"></span> Ver    </a> ';
                            }else{
                                echo '<a href="index.php?c=compra&f=recepcionOrdenRecibida&idorden='.$value['id'].'" class="btn btn-success btn-xs active"><span class="glyphicon glyphicon-log-in"></span> Recibir</a> ';
                            }
                            //echo '<a href="index.php?c=compra&f=ordenCompra&idorden=100000" class="btn btn-danger btn-xs active"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
                            echo '</td>';

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
        </div>        
    </div>
</div>
    
</body>
</html>