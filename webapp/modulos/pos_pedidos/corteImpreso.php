<?php  
//ini_set('display_errors', 1);
session_start();
error_reporting(0);
$idCorte=$_REQUEST["corte"];
//$print = $_REQUEST["print"];
include("controllers/caja.php"); 
$cajaController = new Caja;
$saldos = $cajaController->saldosCorte($idCorte);
$resumenCorte = $cajaController->imprimeCorte($idCorte);
//print_r($resumenCorte['retiros']);

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Corte de Caja</title>
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
			window.print();
		});
	</script>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-md-12">
           <h3>Corte <?php echo $idCorte; ?></h3>
        </div>
    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <label>Desde</label>
                            <h4><?php echo $saldos[0]['fechainicio']; ?></h4>
                        </div>
                        <div class="col-sm-3">
                            <label>Hasta</label>
                           	<h4><?php echo $saldos[0]['fechafin']; ?></h4>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">Saldos</h4>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>Saldo inicial Caja </label>
                                                    <h4>$<?php echo number_format($saldos[0]['saldoinicialcaja'],2); ?></h4>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Monto de Ventas en el Periodo </label>
                                                    <h4>$<?php  echo number_format($saldos[0]['montoventa'],2);?></h4>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Saldo disponible en Caja $</label>
                                                    <h4>$<?php echo number_format(($saldos[0]['saldoinicialcaja']+$saldos[0]['montoventa']),2); ?></h4>	
                                                  
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h4>Depositos/Retiros</h4>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <label>Retiro de Caja </label>
                                              		<h4>$<?php echo number_format($saldos[0]['retirocaja'],2); ?></h4>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Deposito de Caja</label>
                                                    <h4>$<?php echo number_format($saldos[0]['abonocaja'],2); ?></h4>
                                                </div>
                                                
                                                <div class="col-sm-4">
                                                    
                                                        <label>Usuario: </label>
                                                        <h4><?php echo ' '.$saldos[0]['usuario']; ?></h4>
                                                    
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
                                        <table class="table table-bordered table-hover" id="gridPagosCut" style="font-size:7px;">
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
                                            <?php 
                                            	foreach ($resumenCorte['ventas'] as $key => $value) {
										            if($value['nombre']==null){
										                $cliente = 'Publico General';
										            }else{
										                $cliente = $value['nombre'];           
										            }
										            $efectivoCambio = ($value['Efectivo'] - $value['cambio']);
										            echo '<tr>';
                                            		echo '<td>'.$value['idVenta'].'</td>';
                                            		echo '<td>'.$cliente.'</td>';
                                            		echo '<td>'.$value['fecha'].'</td>';
                                            		echo '<td align="center">$'.$value['Efectivo'].'</td>';
                                            		$efectivo += $value['Efectivo'];
                                            		echo '<td align="center">$'.$value['TCredito'].'</td>';
                                            		$TCredito +=$value['TCredito'];
                                            		echo '<td align="center">$'.$value['TDebito'].'</td>';
                                            		$TDebito += $value['TDebito'];
                                            		echo '<td align="center">$'.$value['CxC'].'</td>';
                                            		$CxC += $value['CxC'];
                                            		echo '<td align="center">$'.$value['Cheque'].'</td>';
                                            		$Cheque += $value['Cheque'];
                                            		echo '<td align="center">$'.$value['Trans'].'</td>';
                                            		$Trans += $value['Trans'];
                                            		echo '<td align="center">$'.$value['SPEI'].'</td>';
                                            		$SPEI += $value['SPEI'];
                                            		echo '<td align="center">$'.$value['TRegalo'].'</td>';
                                            		$TRegalo += $value['TRegalo'];
                                            		echo '<td align="center">$'.$value['Ni'].'</td>';
                                            		$Ni += $value['Ni'];
                                            		echo '<td align="center">$'.$value['cambio'].'</td>';
                                            		$cambio += $value['cambio'];
                                            		echo '<td align="center">$'.$value['Impuestos'].'</td>';
                                            		$Impuestos += $value['Impuestos'];
                                            		echo '<td align="center">$'.$value['Monto'].'</td>';
                                            		$Monto += $value['Monto'];
                                            		echo '<td align="center">$'.$value['Importe'].'</td>';
                                            		$Importe += $value['Importe'];
                                            		echo '<td align="center">$'.$efectivoCambio.'</td>';
                                            		$efectivoCambioSum += $efectivoCambio;
                                            		echo '</tr>';

                                            	} 
                                            	echo '<tr style="background:white;">';
                                            	echo '<td colspan="3">Totales</td>';
                                            	echo '<td>$'.number_format($efectivo,2).'</td>';
                                            	echo '<td>$'.number_format($TCredito,2).'</td>';
                                            	echo '<td>$'.number_format($TDebito,2).'</td>';
                                            	echo '<td>$'.number_format($CxC,2).'</td>';
                                            	echo '<td>$'.number_format($Cheque,2).'</td>';
                                            	echo '<td>$'.number_format($Trans,2).'</td>';
                                            	echo '<td>$'.number_format($SPEI,2).'</td>';
                                            	echo '<td>$'.number_format($TRegalo,2).'</td>';
                                            	echo '<td>$'.number_format($Ni,2).'</td>';
                                            	echo '<td>$'.number_format($cambio,2).'</td>';
                                            	echo '<td>$'.number_format($Impuestos,2).'</td>';
                                            	echo '<td>$'.number_format($Monto,2).'</td>';
                                            	echo '<td style="background:#FFCCDD;">$'.number_format($Importe,2).'</td>';
                                            	echo '<td style="background:#A9F5A9;">$'.number_format($efectivoCambioSum,2).'</td>';
                                            	echo '</tr>';
                                            ?>
                                            </tbody>
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
                                            <?php 
                                            foreach ($resumenCorte['productos'] as $key => $value) {
                                            	echo '<tr>';
                                            	echo '<td>'.$value['codigo'].'</td>';
                                            	echo '<td>'.$value['nombre'].'</td>';
                                            	echo '<td align="center">'.$value['Cantidad'].'</td>';
                                            	echo '<td align="center">$'.$value['preciounitario'].'</td>';
                                            	echo '<td align="center">$'.$value['Descuento'].'</td>';
                                            	$Descuento += $value['Descuento'];
                                            	echo '<td align="center">$'.$value['Impuestos'].'</td>';
                                            	$Impuestos2 += $value['Impuestos'];
                                            	echo '<td align="center">$'.$value['Subtot'].'</td>';
                                            	$Subtot += $value['Subtot'];
                                            	echo '</tr>';
                                            }
                                            echo '<tr style="background:white;">';
                                            echo '<td colspan="4">Totales</td>';
                                            echo '<td>$'.number_format($Descuento,2).'</td>';
                                            echo '<td>$'.number_format($Impuestos2,2).'</td>';
                                            echo '<td style="background:#FFCCDD;">$'.number_format($Subtot,2).'</td>';
                                            echo '</tr>';

                                            ?>
                                            </tbody>
                                        </table>
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
                                            <?php 
                                            	foreach ($resumenCorte['retiros'] as $key => $value) {
                                            		echo '<tr>';
                                            		echo '<td>'.$value['id'].'</td>';
                                            		echo '<td>'.$value['fecha'].'</td>';
                                            		echo '<td>'.$value['concepto'].'</td>';
                                            		echo '<td>'.$value['usuario'].'</td>';
                                            		echo '<td align="center">$'.number_format($value['cantidad'],2).'</td>';
                                            		$cantidad3 += $value['cantidad'];
                                            		echo '</tr>';
                                            	}
                                            	echo '<tr style="background:white;">';
                                            	echo '<td colspan="4">Totales</td>';
                                            	echo '<td align="center" style="background:#FFCCDD;">$'.number_format($cantidad3,2).'</td>';
                                            	echo '</tr>';

                                            ?>
                                            </tbody>
                                        </table>
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