<?php 
 //echo 'hola';
?>
<!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Retiro de Caja</title>
  
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/typeahead/typeahead.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>

    <script src="../../libraries/numeric.js"></script>
    <script src="js/retiro.js"></script>
    <script src="../../libraries/typeahead/typeahead.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RCA -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

    <script>
    $(document).ready(function() {
        //pintatabla();
        $('#tablita').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
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
       /* $('#tablita').DataTable({
            'aaSorting' : [[0,'desc' ]]
        }); */
        usuarios();
        $('.numeros').numeric();
    });
    </script>
    </head>
    <body>
<div class="container well">
        <div class="row">
            <h3>Retiro de Caja</h3>
        </div>
        <div class="row">
            <div class="col-sm-2">
                <button class="btn btn-primary btn-block" onclick="formRetiro();"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Retiro</button>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-sm-12" style="overflow:auto;">
            <table class="table table-bordered table-hover" id="tablita">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cantidad</th>
                        <th>Concepto</th>
                        <th>Usuario</th>
                        <th>Fecha</th>
                        <th>Imprimir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach ($retiros as $key => $value) {
                            echo '<tr>';
                            echo '<td>'.$value['id'].'</td>';
                            echo '<td align="center">$'.$value['cantidad'].'</td>';
                            echo '<td>'.$value['concepto'].'</td>';
                            echo '<td>'.$value['usuario'].'</td>';
                            echo '<td>'.$value['fecha'].'</td>';
                            echo '<td><button class="btn btn-default" onclick="reimprime('.$value['id'].');"><i class="fa fa-print" aria-hidden="true"></i></button></td>';
                            echo '</tr>';
                        }
                    ?>
                </tbody>
            </table>
            </div>
        </div>
<!-- Modal de Loading -->
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
  <!-- Modal del Form -->
  <div class="modal fade" id="modalformRetiro" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header modal-header-primary">
          <h4 class="modal-title"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Retiro</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <label>Disponible $</label>
                    <input type="text" class="form-control numeros" id="saldo_disponibleR" readonly>
                </div>
                <div class="col-sm-12">
                    <label>Cantidad a Retirar</label>
                    <input type="text" class="form-control numeros" id="cantidad">
                </div>
                <div class="col-sm-12">
                    <label>Concepto</label>
                    <textarea  cols="30" rows="5" id="concepto" class="form-control"></textarea>
                </div>
            </div>
        </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                             <button class="btn btn-primary btn-block" onclick="retira();"> <i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar</button> 
                        </div>
                    </div>
                </div>


      </div>
    </div>
  </div>
    <!--          Molda Success           -->
  <div id="modalSuccess" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-success">
            <div class="modal-header panel-heading">
                <h4 id="modal-label">Exito!</h4>
            </div>
            <div class="modal-body">
                <p>Tu retiro se guardo existosamente</p>
            </div>
            <div class="modal-footer">
                <button id="modal-btnconf2-uno" type="button" class="btn btn-default" onclick="javascript:$('#modalSuccess').modal('hide');">Continuar</button> 
            </div>
        </div>
    </div> 
  </div>

</div>
    </body>
    </html>    