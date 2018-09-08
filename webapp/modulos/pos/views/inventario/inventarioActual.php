<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Inventario Actual</title>
    <link rel="stylesheet" href="">
</head>
    
        <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/typeahead/typeahead.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/numeric.js"></script>
    <script src="js/inventario.js"></script>
    <script src="../../libraries/numeric/jquery.numeric.js"></script>
    <script src="../../libraries/typeahead/typeahead.js"></script>

    <!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <!-- <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
        <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>

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
        $('#tableMovs').DataTable({
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
        inicial()

   });
   </script>
<body>  
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Inventario Actual</h3>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Producto</label>
                        <select  class="form-control" id="producto">
                            <option value="">-Seleccion un producto-</option>
                            <?php 
                                foreach ($inventarioActual['productos'] as $key1 => $value1) {
                                    echo '<option value="'.$value1['codigo'].'">'.$value1['codigo'].'/'.$value1['nombre'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Almacen</label>
                        <select id="almacen" class="form-control">
                            <option value="">-Seleccion un Almacen-</option>
                            <?php 
                                foreach ($inventarioActual['almacenes'] as $key2 => $value2) {
                                    echo '<option value="'.$value2['id'].'">'.$value2['codigo_sistema'].'/'.$value2['nombre'].'</option>';
                                }
                            ?>                            

                        </select>
                    </div>
                    <div class="col-sm-3">
                        <div style="padding-top:7%;">
                            <button class="btn btn-primary" onclick="inicial();">Buscar</button>
                        </div>
                    </div>
    
                </div>
            </div>
            <div class="panel-body">
                <div class="col-sm-12" style="overflow:auto;">
                <table class="table table-hover" id="tableKardex">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nombre</th>
                            <th>Codigo</th>
                            <th>Unidad</th>
                            <th>Cantidad</th>
                            <th>Almacen</th>
                           <!--  <th>.</th>
                           <th>Almacen Origen</th>
                            <th>Almacen Destino</th>
                            <th>Tipo</th>
                            <th>Costo</th>
                            <th>Referencia</th>
                            <th>Usuario</th>
                            <th>Fecha</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                           /* foreach ($inventarioActual['grid'] as $key => $value) {
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
                            }    */


                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>    
    </div>
   
</div>

  <div class="modal fade" id="modalMensajes" role="dialog" style="z-index:1051;" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Espere un momento...</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-default">
            <div align="center"><label id="lblMensajeEstado"></label></div>
            <div align="center"><i class="fa fa-refresh fa-spin fa-5x fa-fw margin-bottom"></i>
                 <span class="sr-only">Loading...</span>
             </div>
        </div>
        </div>
      </div>
    </div>
  </div>


    <div id='modalMovimientosDetalle' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="prodNombre"></h4>
                </div>
                <div class="modal-body">
                    <div style="height:400px;overflow:auto;">
                        <div class="row">
                            <div class="col-sm-12">
                                    <input id="idProdIn" type="hidden">
                                <table class="table table-bordered" id="tableMovs">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Tipo</th>
                                            <th>Cantidad</th>
                                            <th>Almacen Origen</th>
                                            <th>Almacen DEstino</th>
                                            <th>Costo</th>
                                            <th>Referencia</th>
                                            <th>Usuario</th>
                                            <th>Fecha</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                             
                            </div>
                        </div>  
                    </div>                  
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <button class="btn btn-primary" onclick="ajustarInveModal();"><i class="fa fa-cogs" aria-hidden="true"></i> Ajustar</button> 
                            <button class="btn btn-danger" onclick="javascript: $('#modalMovimientosDetalle').modal('hide');"><i class="fa fa-times" aria-hidden="true"></i> Salir</button> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalAjuste" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header modal-header-warning">
          <h4 class="modal-title"><i class="fa fa-cogs" aria-hidden="true"></i> Ajuste</h4>
        </div>
        <div class="modal-body">
            <input type="hidden" id="prodAjustar" value="">
            <div class="row">
                <div class="col-sm-12">
                    <label>Tipo de Traspaso</label>
                    <select id="tipoMov" class="form-control">
                        <option value="0">Salida</option>
                        <option value="1">Entrada</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>Almacen</label>
                    <select id="almacen2" class="form-control">
                            <option value="">-Seleccion un Almacen-</option>
                            <?php 
                                foreach ($inventarioActual['almacenes'] as $key2 => $value2) {
                                    echo '<option value="'.$value2['id'].'">'.$value2['codigo_sistema'].'/'.$value2['nombre'].'</option>';
                                }
                            ?>                            

                    </select>
                </div>
                <div class="col-sm-12">
                    <label>Costo por Unidad</label>
                    <input type="text" class="form-control numeros" id="costo">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <label>Cantidad </label>
                    <input type="text" class="form-control numeros" id="cantidad">
                </div>
                <div class="col-sm-12">
                    <label>Concepto</label>
                    <textarea  cols="30" rows="5" id="obser" class="form-control"></textarea>
                </div>
            </div>
        </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                             <button class="btn btn-primary " onclick="ajustarInve();"> <i class="fa fa-cogs" aria-hidden="true"></i> Ajustar</button> 
                        <button class="btn btn-danger" onclick="javascript: $('#modalAjuste').modal('hide');"><i class="fa fa-times" aria-hidden="true"></i> Cancelar</button> 
                        </div>
                    </div>
                </div>


      </div>
    </div>
  </div>  
    
</body>
</html>