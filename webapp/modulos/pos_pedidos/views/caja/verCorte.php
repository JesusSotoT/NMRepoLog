<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Corte de Caja</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
    <script src="../../libraries/jquery.min.js"></script>
    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="js/corte.js"></script>
<!--Select 2 -->
    <script src="../../libraries/select2/dist/js/select2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
    <!-- Datepicker -->
    <link rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../libraries/datepicker/js/bootstrap-datepicker.min.js"></script>


    <link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
    <script src="../../libraries/dataTable/js/datatables.min.js"></script>
    <script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- morris -->
    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>

   <script>
   $(document).ready(function() {
        //$('#tableCuts').DataTable();
        //graficar('','');

        $('#cliente').select2();

        $('#desde').datepicker({
            format: "yyyy-mm-dd",
        });
        $('#hasta').datepicker({
            format: "yyyy-mm-dd",
        });

        verCorte();
         
   });
   </script>
<body>  
<div class="container well">
    <div class="row">
        <div class="col-sm-1">
            <button class="btn btn-default" type="button" onclick="regresar();"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Regresar</button>
        </div>

        <div class="col-sm-1">
            <div style="margin-left:25%;">
                <button class="btn btn-primary" type="button" onclick="imprimeCorte(<?php echo $idCorte; ?>);"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</button>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Corte <?php echo $idCorte; ?></h3>
           <input type="hidden" id="idCorte" value="<?php echo $idCorte; ?>">
           <input type="hidden" id="empleado" value="<?php echo $corteInfo[0]['idEmpleado']; ?>">
        </div>
    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Desde</label>
                            <input type="text" id="desdeCut" class="form-control" value="<?php echo $corteInfo[0]['fechainicio']; ?>" readonly>
                        </div>
                        <div class="col-sm-3">
                            <label>Hasta</label>
                            <input type="text" id="hastaCut" class="form-control" value="<?php echo $corteInfo[0]['fechafin']; ?>" readonly>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Saldos</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>Saldo inicial Caja $</label>
                                                    <input type="text" class="form-control" id="saldo_inicial" value="<?php echo $corteInfo[0]['saldoinicialcaja']; ?>" readonly>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Monto de Ventas en el Periodo $ </label>
                                                    <input type="text" class="form-control" id="monto_ventas" value="<?php  echo $corteInfo[0]['montoventa'];?>" readonly>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Saldo disponible en Caja $</label>
                                                    <input type="text" class="form-control" id="saldo_disponible" value="<?php echo ($corteInfo[0]['saldoinicialcaja']+$corteInfo[0]['montoventa']); ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h4>Depositos/Retiros</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>Retiro de Caja $</label>
                                                    <input type="text" class="form-control numeros" id="retiro_caja" style="background-color: #FFCCDD;" value="<?php echo $corteInfo[0]['retirocaja']; ?>" readonly>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Deposito de Caja $</label>
                                                    <input type="text" class="form-control numeros" id="deposito_caja" style="background-color: #A9F5A9;" value="<?php echo $corteInfo[0]['abonocaja']; ?>" readonly>
                                                </div>
                                                
                                                <div class="col-sm-4">
                                                    <div style="align:center;padding-top:9%">
                                                        <label>Usuario: </label><strong><?php echo ' '.$corteInfo[0]['usuario']; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" style="overflow:auto;">
                            <div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4>Pagos</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-hover" id="gridPagosCut">
                                            <thead>
                                                <tr>
                                                    <th>ID Venta</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha</th>
                                                    <th>EF</th>
                                                    <th>TD</th>
                                                    <th>TC</th>
                                                    <th>CR</th>
                                                    <th>CH</th>
                                                    <th>TRA</th>
                                                    <th>SPEI</th>
                                                    <th>TR</th>
                                                    <th>NI</th>
                                                    <th>Cambio</th>
                                                    <th>Impuestos</th>
                                                    <th>Monto</th>
                                                    <th>Importe</th>
                                                    <th>Ingreso(EF-Cambio)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-hover" id="gridPagosCutTotales">
                                            <tr> </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4>Productos Vendidos</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-hover" id="gridProductosCut">
                                            <thead>
                                                <tr>
                                                    <th>Codigo</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unitario</th>
                                                    <th>Descuento</th>
                                                    <th>Impuestos</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-hover" id="gridProductosCutTotales"><tr></tr></table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h4>Retiros de Caja</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-hover" id="gridRetirosCut">
                                            <thead>
                                                <tr>
                                                    <th>ID Retiro</th>
                                                    <th>Fecha</th>
                                                    <th>Concepto</th>
                                                    <th>Usuario</th>
                                                    <th>Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <table class="table table-bordered table-hover" id="gridRetirosCutTotales"><tr></tr></table>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>



<!-- Modal modalVentasDetalle -->
<!-- Modal de Ventas -->
    <div id='modalVentasDetalle' class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header modal-header-default">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="idFacPanel"></h4>
                </div>
                <div class="modal-body">
                    <div style="height:400px;overflow:auto;">
                        <div class="row">
                            <div class="col-sm-12">
                                    <input id="idVentaHidden" type="hidden">
                                <table class="table table-bordered" id="tableSale">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Descripcion</th>
                                            <th>Cantidad</th>
                                            <th>Precio U.</th>
                                           <!-- <th>Descuento</th> -->
                                            <th>Impuestos</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                             
                            </div>
                        </div>  
                    <div class="row">
                    <div class="col-sm-6">
                        <div id="pay">
                            
                        </div>
                    </div>
                    <div class="col-sm-3" id="impuestosDiv"></div>
                    <div class="col-sm-3">
                        <div id="subtotalDiv" class="totalesDiv"></div>
                        <div id="totalDiv" class="totalesDiv"></div>
                        <!-- inputs donde se guarda el total y subtotal -->
                        <input type="hidden" id="inputSubTotal">
                        <input type="hidden" id="inputTotal">
                    </div>
                    </div>
                    </div>                  
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-6">
                            <button class="btn btn-warning" onclick="cancelaVenta();"><i class="fa fa-ban" aria-hidden="true"></i> Cancelar</button> 
                            <button class="btn btn-primary" onclick="imprime();"><i class="fa fa-print" aria-hidden="true"></i> Imprimir</button> 
                            <button class="btn btn-danger" onclick="javascript:$('#modalVentasDetalle').modal('hide');"><i class="fa fa-times" aria-hidden="true"></i> Salir</button> 
                        </div>
                    </div>
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
    
</body>
</html>