<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Orden de Compra</title>
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
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js"></script>



    <script>
    $(document).ready(function() {
            $('#proTable').DataTable({
                language: {
                        search: "Buscar:",
                        lengthMenu:"",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del START al END de TOTAL elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     }
            });
            $("#proveedor").select2({
                width : "300px"
            });
            
            $('#fecha_entrega').datepicker({
                format: "yyyy-mm-dd",
                language: "es"
            });

        $("#codigo").keyup(function (e) {
            if (e.keyCode == 13) {
                var codigo = $('#codigo').val();
                $('#loadingPro').show();
                $.ajax({
                    url: 'ajax.php?c=compra&f=buscaIdpro',
                    type: 'POST',
                    dataType: 'json',
                    data: {codigo: codigo},
                })
                .done(function(data) {
                    //alert(data.idProducto);
                    console.log(data.idProducto);
                   // data.idProducto = 3291;
                    $('#cant_'+data.idProducto).focus();
                    $('#codigo').val('');
                    $('#loadingPro').hide();
                })
                .fail(function() {
                    console.log("error");
                })
                .always(function() {
                    console.log("complete");
                });
                
            }
        });

    });
    </script>
</head>
<body>
<div class="container well">
    <div class="row">
        <div class="col-sm-1">
            <button class="btn btn-default" onclick="back();"><i class="fa fa-arrow-left" aria-hidden="true"></i> Regresar</button>
        </div>
        <div class="col-sm-1">
            <span class="label label-success">Nuevo</span>
        </div>
    </div>
    <div class="panel panel-default">
        <div class='panel-body'>
            <input type="hidden" id="ordenCompra">
            <div class="row">
                <div class="col-sm-3">
                        <div class="input-group">
                            <label>Proveedor</label>
                            <select class="form-control" id="proveedor" onchange="buscaProductos();">
                                <option value="0">--Selecciona Proveedor--</option>
                               <?php 
                                foreach ($proveedores as $key => $value) {
                                         echo '<option value="'.$value['idPrv'].'">'.$value['razon_social'].'</option>';
                                        } 
                               ?>
                            </select>
                        </div>
                </div>
            </div>
            <br>
            <div class="row">
               <!-- <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-addon"><label>Usuario</label></span>
                        <input class="form-control" placeholder="Cantidad" type="text">
                    </div>
                </div> -->
                <div class="col-sm-4">
                    <div class="input-group">
                        <span class="input-group-addon"><label>Almacen</label></span>
                        <select id="almacen" class="form-control" onchange="buscaProductos();">
                            <?php 
                            foreach ($almacenes as $key => $value) {
                                echo '<option value="'.$value['id'].'">'.$value['nombre'].'</option>';
                            }

                            ?>
                        </select>
                    </div>            
                </div>
                <div class="col-sm-2">
                    <div class='input-group date' id='datetimepicker1'>
                        <input type='text' class="form-control" id="fecha_entrega" placeholder="Fecha de Entrega"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
                <div class="col-sm-2">
                    
                </div>
            </div>
        </div>
    </div>

   <!-- <div class="panel panel-default">
        <div class="panel-heading">Lista Productos</div>
        <div class="panel-body">
            <div class="col-sm-7">
                <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="listaPedidos">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Costo</th>
                  </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td>ORbit de 123</td>
                        <td>4</td>
                        <td>$5</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>
    </div>  -->

    <div class="panel panel-default">
        <div class="panel-heading">Productos</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-3">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-barcode"></span></span>
                            <input class="form-control" placeholder="Codigo" type="text" id='codigo'>            
                        </div>
                </div>
                <div class="col-sm-1" style="display:none;" id="loadingPro">
                    <i class="fa fa-refresh fa-spin fa-3x"></i>
                </div>
            </div>
            <br>
            <div class="container" style="width: 100%; height: 400px; overflow: auto; background-color:#fbfbf2;">
               <!-- <i class="fa fa-refresh fa-spin fa-5x"></i> -->
             <table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="proTable">
                    <thead>
                      <tr>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Unidad</th>
                        <th>Pedido</th>
                        <th>Costo</th>
                      </tr>
                    </thead>
                    <div id="xxxxx">
                    <tbody id="tableBody">
                    </tbody>
                    </div>
                </table>
            </div> <!--fin del contenedor overflow-->
        <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-3" id="impuestosDiv"></div>
        <div class="col-sm-3">
            <div id="subtotalDiv" class="totalesDiv"></div>
            <div id="totalDiv" class="totalesDiv"></div>
            <!-- inputs donde se guarda el total y subtotal -->
            <input type="hidden" id="inputSubTotal">
            <input type="hidden" id="inputTotal">
        </div>
        </div>    
        <div class="row">
            <div class="col-sm-4">
               <!-- <button type="button" class="btn btn-primary"><span class="glyphicon glyphicon-send"></span> Enviar</button> -->
            </div>
            <div class="col-sm-5"></div>
            <div class="col-sm-3 btn-group">
                 <button type="button" class="btn btn-warning" onclick="limpiar();">Limpiar</button>
                <div id="guardaDiv"><button type="button" class="btn btn-success" onclick="guardar(1);"><span class="glyphicon glyphicon-floppy-disk"></span>Guardar</button></div>
                <div id="sded" style="display:none;"><i class="fa fa-refresh fa-spin fa-3x"></i></div>
            </div>
        </div>
        </div>
    </div>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Exito</h4>
        </div>
        <div class="modal-body">
          <div class="alert alert-success">
            <strong>Exito!</strong> Se guardo correctamente la orde de compra.
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="redireccion();">Continuar</button>
        </div>
      </div>
    </div>
  </div>
</div>
    
   <div class="modal fade" id="modalMensajes" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
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

</body>
</html>
