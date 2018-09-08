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
    <script src="js/facturas.js"></script>

    <!-- Modificaciones RC -->
    <script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../libraries/export_print/jszip.min.js"></script>

<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
    <!-- <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

        <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js" type="text/javascript"></script>
   <script>

   $(document).ready(function() {

        $('#tableGrid').DataTable({
            autowidth: 'false',
            dom: 'Bfrtip',
            buttons: [ 'excel' ],
                            language: {
                            search: "Buscar:",
                            lengthMenu:"",
                            zeroRecords: "No hay datos.",
                            infoEmpty: "No hay datos que mostrar.",
                            info:"Mostrando del _START_ al _END_ de _TOTAL_ facturas",
                            paginate: {
                                first:      "Primero",
                                previous:   "Anterior",
                                next:       "Siguiente",
                                last:       "Último"
                            },
                         },
                          aaSorting : [[0,'desc' ]]
        });

        $('#desde').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
        });
        $('#hasta').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
        });
          $('#sucursal').select2({ width: '100%' });
        $('#empleado').select2({ width: '100%' });
   });
   </script>
<body>
<style>
    #tbl_facturas_pendientes>thead>tr>th, #tbl_facturas_pendientes>tbody>tr>td{
        text-align: center;
        vertical-align: middle;
    }
</style>
<div class="container col-sm-12 well">
    <div class="row">
        <div class="col-sm-12">
        </div>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                                <div class="row">
                    <div class="col-sm-3">
                        <label>Desde</label>
                        <div id="datetimepicker1" class="input-group date">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input id="desde" class="form-control" type="text" placeholder="Fecha de inicio">
                        </div>

                    </div>
                    <div class="col-sm-3">
                        <label>Hasta</label>
                        <div id="datetimepicker2" class="input-group date">
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                            <input id="hasta" class="form-control" type="text" placeholder="Fecha final">
                        </div>


                        <div class="row"></div>
                    </div>

                    <div class="col-sm-3">
                        <label>Empleado</label>
                        <select id="empleado" class="form-control">
                            <option value="0">-Selecciona-</option>
                            <?php 
                                foreach ($sucUsus['usu'] as $key => $value) {
                                    echo '<option value="'.$value['idempleado'].'">'.$value['usuario'].'</option>';
                                }

                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Sucursal</label>
                        <select id="sucursal" class="form-control">
                            <option value="0">-Selecciona-</option>
                            <?php 
                                foreach ($sucUsus['suc'] as $key => $value) {
                                    echo '<option value="'.$value['idSuc'].'">'.$value['nombre'].'</option>';
                                }

                            ?>
                        </select>
                    </div>


                </div>
                <div class="row">
                    <div class="col-sm-9"></div>
                    <div class="col-sm-1">
                        
                            <button class="btn btn-default" onclick="buscarComPago();">Buscar</button>
                        
                    </div>
                    <div class="col-sm-1">
                       
                            <button id="btnMostrarMasFacturas" class="btn btn-default" onclick="muestraMasComPago();">Mostrar más</button>
                            <input type="hidden" value="100" id="rango">
              
                    </div>
                    <div class="col-sm-1">
                        
                            <button class="btn btn-default" alt="Selecciona facturas o haz clic en el botón 'Todas'" title="Selecciona facturas o haz clic en el botón &quot;Todas&quot;" onclick="dowloadZip();">Descargar</button>
                        
                    </div>
                    <div class="col-sm-1">
                        <div style="display:none;"><a href='../facturas/notas/facturas.zip' id="zipRef">Comprimido</a></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
    <?php
     function object_to_array($data) {
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value) {
                $result[$key] = $this->object_to_array($value);
            }
            return $result;
        }
        return $data;
        }

    foreach ($facturas as $key => $value) {
       $azurian=base64_decode($value['cadenaOriginal']);

        $azurian = str_replace("\\", "", $azurian);
        if($azurian!=''){
            $azurian=json_decode($azurian);
        }
        $azurian = $this->object_to_array($azurian);
        //print_r($azurian);
    }
    ?>
        <div class="col-sm-12" id="tablecontainer" style="overflow:auto;">
            <?php require 'listaComplementosDePago.php'; ?>
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



    <div class="modal fade" id="modalEnvio" role="dialog">
    <div class="modal-dialog modal-sm" style="width: 400px;">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Reenviar</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-2">
                            <label>Correo:</label>
                            <input type="hidden" id="uuidEscondido">
                        </div>
                        <div class="col-sm-10">
                            <input type="text" id="correoDestina" class="form-control">
                        </div>
                    </div><br>

                    <div class="row">
                        <div class="col-sm-2">
                            <label>Mensaje:</label> <br>
                        </div>
                        <div class="col-sm-12">
                            <textarea type="text" id="cuerpoCorreo" class="form-control"> </textarea>
                        </div>

                    </div><br>

                    
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-warning btn-block" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-block" onclick="reenviale();">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
<!-- 
<div class="modal fade" id="modalNotaCredito" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Nota de Crédito</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>La Nota de Crédito no puede sobrepasar el total de la factura.</label>
                            <input type="hidden" id="idFactN">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <h4>Monto:</h4><label id="labelMonto"></label>
                            <input type="hidden" id="inputMonto">
                        </div>
                        <div class="col-sm-6">
                            <h4>Disponible</h4><label id="dispoPnota"></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <label>Monto:</label>
                        </div>
                        <div class="col-sm-10">
                            <input type="text" id="montoNota" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12" id="tablaNotas">

                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-warning btn-block" data-dismiss="modal">Cancelar</button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-primary btn-block" onclick="crearNota();">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>





<div id="modal-acuse" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content panel-default">
            <div class="modal-header panel-heading">
            <button class="close" type="button" data-dismiss="modal">×</button>
                <h4 id="modal-label">Acuse de cancelacion</h4>
                <input type="hidden" id="idFact">
            </div>
            <div class="modal-body">
                <table>
                <tr>
                    <td width="250px;"><b>Fecha y hora de solicitud:</b></td>
                    <td class="acusefecha">xxx</td>
                </tr>
                <tr>
                    <td><b>Fecha y hora de cancelacion:</b></td>
                    <td class="acusefecha">xxx</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><b>RFC emisor:</b></td>
                    <td id="acuserfc">xxx</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><b>Folio fiscal</b></td>
                    <td id="acusefolio">xxx</td>
                </tr>
                <tr>
                    <td><b>Estado CFDI</b></td>
                    <td id="estadofolio">Cancelado</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                </table>
            </div>
            <div class="modal-footer">
                <div class="col-sm-8">
                    <input type="text" class="form-control col-sm-8" id="correoEnvio">
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-primary" onclick="enviarAcuse();">Enviar</button>
                    <button class="btn btn-primary" onclick="imprimeAcuse();">Imprimir</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="facturas_pendientes" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="display:inline-block;">Relación de pagos y facturas</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="mensaje">
            <p></p>
        </div>
        <div class="table-responsive" id="container_tbl_facturas" style="display:none;">
            <table id="tbl_facturas_pendientes" class="table table-striped">
                <thead>
                    <tr>
                        <th>Referencia de pago</th>
                        <th>Fecha</th>
                        <th>Importe</th>
                        <th>Concepto</th>
                        
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div> 
-->

</body>
</html>
