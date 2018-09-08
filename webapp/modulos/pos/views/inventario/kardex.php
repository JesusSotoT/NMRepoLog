<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kardex</title>
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
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>


    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<!--    <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
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
        //$('#tableKardex').DataTable();
        $('#tableKardex').DataTable({
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
        $('#producto').select2();
        $('#almacen').select2();
        $('#tipo').select2();
        $('#almacenOrigen').select2();
        $('#almacenDestino').select2();
        $('#desde').datepicker({
            format: "yyyy-mm-dd",
        });
        $('#hasta').datepicker({
            format: "yyyy-mm-dd",
        });

   });
   </script>
<body>  
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Kardex</h3>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Producto</label>
                        <select  class="form-control" id="producto">
                            <option value="0">-Seleccion un producto-</option>
                            <?php 
                                foreach ($inventarioActual['productos'] as $key1 => $value1) {
                                    echo '<option value="'.$value1['id'].'">'.$value1['codigo'].'/'.$value1['nombre'].'</option>';
                                }
                            ?>
                        </select>
                        <label>Tipo</label>
                        <select id="tipo" class="form-control">
                            <option value="">-Selecciona tipo-</option>
                            <option value="0">Salida</option>
                            <option value="1">Entradas</option>
                            <option value="2">Traspaso</option>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Almacen Origen</label>
                        <select id="almacenOrigen" class="form-control">
                            <option value="0">-Seleccion un Almacen-</option>
                            <?php 
                                foreach ($inventarioActual['almacenes'] as $key2 => $value2) {
                                    echo '<option value="'.$value2['id'].'">'.$value2['codigo_sistema'].'/'.$value2['nombre'].'</option>';
                                }
                            ?>                            

                        </select>
                        <label>Almacen Destino</label>
                        <select id="almacenDestino" class="form-control">
                            <option value="0">-Seleccion un Almacen-</option>
                            <?php 
                                foreach ($inventarioActual['almacenes'] as $key2 => $value2) {
                                    echo '<option value="'.$value2['id'].'">'.$value2['codigo_sistema'].'/'.$value2['nombre'].'</option>';
                                }
                            ?>                            

                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Desde</label>
                        <div id="datetimepicker1" class="input-group date">
                            <input id="desde" class="form-control" type="text" placeholder="Fecha de Entrega">
                            <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                            </span> 
                        </div>
                        <label>Hasta</label>
                        <div id="datetimepicker2" class="input-group date">
                            <input id="hasta" class="form-control" type="text" placeholder="Fecha de Entrega">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>    
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="row"></div><br>
                        <br>
                        <br>
                       
                        <div class="row"><button class="btn btn-default" onclick="buscar();">Buscar</button></div>
                        <div class="row"></div>
                    </div>
    
                </div>
            </div>
            <div class="panel-body">
                <div class="col-sm-12" style="overflow:auto;">
                <table class="table table-hover" id="tableKardex">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Nombre/codigo</th>
                            <th>Cantidad</th>
                            <th>importe</th>
                            <th>Almacen Origen</th>
                            <th>Almacen Destino</th>
                            <th>Tipo</th>
                            <th>Costo</th>
                            <th>Referencia</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                            foreach ($inventarioActual['grid'] as $key => $value) {
                                if($value['tipo_traspaso']==0){
                                    $tipoTraspaso = '<span class="label label-warning">Salida</span>';
                                }elseif($value['tipo_traspaso']==1){
                                    $tipoTraspaso = '<span class="label label-success">Entrada</span>';
                                }else{
                                    $tipoTraspaso = '<span class="label label-primary">Traspaso</span>';
                                }
                                echo '<tr>';
                                echo '<td>'.$value['id'].'</td>';
                                echo '<td>'.$value['nombre'].'/'.$value['codigo'].'</td>';
                                echo '<td>'.$value['cantidad'].'</td>';
                                echo '<td>'.$value['importe'].'</td>';
                                echo '<td>'.$value['origen'].'</td>';
                                echo '<td>'.$value['destino'].'</td>';
                                echo '<td>'.$tipoTraspaso.'</td>';
                                echo '<td>'.$value['costo'].'</td>';
                                echo '<td>'.$value['referencia'].'</td>';
                                echo '<td>'.$value['usuario'].'</td>';
                                echo '<td>'.$value['fecha'].'</td>';
                                echo '</tr>';
                            }   


                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>    
    </div>
   
</div>
    
</body>
</html>