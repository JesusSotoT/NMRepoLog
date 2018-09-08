<?php 
//ini_set('display_errors', 1);
?>
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
    <script src="js/inventario.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <!-- Modificaciones RC -->
    <!-- <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
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
                        }
                     }
    });
   });
   </script>
<body>  
<div class="container well">
        <div class="row">
            <h3>Mermas</h3>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <button class="btn btn-default btn-block" onclick="cambia2();">Listado</button>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-default btn-block" onclick="cambia1();">Nueva Merma</button>
            </div>
        </div>
    <div class="row">
        <div class="col-sm-12" style="overflow:auto;">
                     <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Elaboro</th>
                        <th>Cantidad de Productos</th>
                        <th>Importe</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $status="";
                        foreach ($mermasList as $key => $value) {

                            if($value['activo']==1){
                                $status = '<span class="label label-success">Activo</span>';
                            }else{
                                $status = '<span class="label label-danger">Inactivo</span>';
                            }
                            echo '<tr>';
                            echo '<td>'.$value['id'].'</td>';
                            echo '<td>'.$value['fecha'].'</td>';
                            echo '<td>'.$value['usuario'].'</td>';
                            echo '<td align="center">'.$value['productos'].'</td>';
                            echo '<td>$'.number_format($value['importe'],2).'</td>';
                            echo '<td><button class="btn btn-primary btn-block" onclick="mermaDetalle('.$value['id'].');" type="button"><i class="fa fa-list-ul"></i> Detalle </button></td>';
                            //echo '<td>';
                            //echo '<a href="index.php?c=compra&f=ordenCompra&idorden='.$value['id'].'" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a> ';
                            //echo '<a href="index.php?c=compra&f=recepcionOrden&idorden='.$value['id'].'" class="btn btn-success btn-xs active"><span class="glyphicon glyphicon-edit"></span> Recibir</a> ';
                            //echo '<a href="index.php?c=compra&f=ordenCompra&idorden=100000" class="btn btn-danger btn-xs active"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
                            //echo '</td>';

                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
        </div>        
    </div>



                <!--- Modal detalle -->
    <div id="modalDetalle" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content panel-default">
                <div class="modal-header panel-heading">
                    <h4>Merma</h4><label id="modalIdMerma"></label>
                   <!-- <input type="hidden" id="carIdProddiv"> -->
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12" id="divDetalle"> 

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10"></div>
                        <div class="col-sm-2">
                            Total:
                            <label id="totalMerma"></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger"  data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div> 
    </div>


</div>
    
</body>
</html>