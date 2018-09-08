<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Ventas</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/prodsuc.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js" type="text/javascript"></script>


    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<!--    <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RCA -->
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>
<!--    <script src="../../libraries/export_print/jquery-1.12.3.js"></script> -->

    <!-- morris -->
    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>

   <script>
   $(document).ready(function() {
        //$('#tableSales').DataTable()
        //graficar('','','','','');
        $('#table1').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar",
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


        $('#cliente').select2();

        $('#desde').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
        });
        $('#hasta').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
        });
         
   });
   </script>
<body>  
<div class="container well">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Productos por Sucursal</h3>
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-3">
                        <label>Sucursal</label>
                        <select id="sucursal" class="form-control" onchange="listaSucu();">
                            <option value="0">-Selecciona Sucursal-</option>
                            <?php 
                                foreach ($sucursal as $key => $value) {
                                    echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3"><br>
                        <button onclick="allPro();" class="btn btn-primary">Vincular</button>
                    </div>
                    <div class="col-sm-3">
                        <label>Tipo Monedero</label>
                        <select id="monedero" class="form-control">
                            <?php 
                                foreach ($politicas['politicas'] as $key => $value) {
                                    $ploit = '';
                                    if($value['tipo']==1){
                                        $ploit ='EL '.$value['porcentaje'].'% se va puntos y pesos';
                                    }else{
                                        $ploit = 'Por cada '.$value['dinero'].' se dan '.$value['puntos'].' puntos';
                                    }
                                    echo '<option value="'.$value['id'].'">'.$value['nombre'].'('.$ploit.')</option>';
                                }   

                            ?>
                        </select>
                    </div> 
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-hover" id="table1">
                            <thead>
                                <tr>
                                    <th><button class="btn btn-prymary" onclick="sellAll();">Slecciona</button></th>
                                    <th>ID</th>
                                    <th>Codigo</th>
                                    <th>Producto</th>
                                    <th>Sucursales</th>
                                    <th>Departamento</th>
                                    <th>Familia</th>
                                    <th>Linea</th>
                                    <th>Edita</th>
                                </tr>
                            </thead>
                            <tbody id="table1body">
                                <?php 

                                    echo $productos;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    

    <!--- Modal sucursales -->
    <div id="modalSuc" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content panel-default">
                <div class="modal-header panel-heading">
                    <h4 id="modal-labelPr"></h4>
                    <input type="hidden" id="idProModal">
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-9">
                            <select id="sucAdd" class="form-control">
                                <?php 
                                    foreach ($sucursal as $key => $value) {
                                        echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-success" onclick="vinculaSucursal();">Agregar Sucursal</button><br>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                        <table class="table table-bordered table-hover" id="tableSuc">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Sucursal</th>
                                    <th>.</th>
                                </tr>
                            </thead>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
               <!-- <div class="modal-footer">
                    <button type="button" id="btnAgregarProductoCaracteristicas" class="btn btn-primary" onclick="caja.agregaCarac();">Agregar</button> 
                    <button type="button" class="btn btn-danger"  data-dismiss="modal">Cancelar</button>
                </div> -->
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
    
</body>
</html>