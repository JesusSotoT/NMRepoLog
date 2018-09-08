
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
		<script src="../../libraries/datepicker/js/bootstrap-datepicker.es.js"></script>



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
						language: "es"
				});
				$('#hasta').datepicker({
						format: "yyyy-mm-dd",
						language: "es"
				});

				verCorte();
				 
	 });
	 </script>
	 <style>
			 #gridPagosCut_filter { display: none;}
	 </style>
<body>  
<div class="container well col-sm-12">
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
				<div class="col-md-2">
					 <h3>Corte <span id="idCorte"><?php echo $idCorte; ?></span></h3>
					 <input type="hidden" id="idCorte" value="<?php echo $idCorte; ?>">
					 <input type="hidden" id="empleado" value="<?php echo $corteInfo[0]['idEmpleado']; ?>">
				</div>
				<div class="col-sm-5">
						<label>Desde</label>
						<input type="text" id="desdeCut" class="form-control" value="<?php echo $corteInfo[0]['fechainicio']; ?>" readonly>
				</div>
				<div class="col-sm-5">
						<label>Hasta</label>
						<input type="text" id="hastaCut" class="form-control" value="<?php echo $corteInfo[0]['fechafin']; ?>" readonly>
				</div>
		</div>
								
										<!-- <div class="row">
												<div class="col-sm-2">
														<button class="btn btn-primary" data-toggle="modal" data-target="#modalArqueo" onclick="verArqueo();">
																Arqueo
														</button>
												</div>
										</div> -->
		<div class="row">
				<div class="col-sm-12">
						<div style="align:center;">
								<label>Usuario: </label><strong><?php echo ' '.$corteInfo[0]['usuario']; ?></strong>
						</div>
				</div>
		</div>
										
										


				<!-- <div class="panel panel-default" 
						<?php if ( count($cortes['cortes']) == 0 ) {  ?>
								hidden
						<?php } ?>
						>
						<div class="panel-heading" role="tab" id="headingZero">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseZero" aria-expanded="false" aria-controls="collapseZero">
												Cortes parciales
										</a>
								</h4>
						</div>
						<div id="collapseZero" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo" 
						>
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridCortesParciales">
																<thead>
																		<tr>
																				<th>ID</th>
																				<th>Fecha Inicio</th>
																				<th>Fecha Fin</th>
																				<th>Saldo Inicial</th>
																				<th>Moto de Ventas</th>
																				<th>Retiro de Caja</th>
																				<th>Abono de Caja</th>
																				<th>Saldo Final</th>
																				<th>Usuario</th>
																				<th>Ver</th>
																				<th></th>
																		</tr>
																</thead>
																<tbody>
																		<?php 
																				foreach ($cortes['cortes'] as $key => $value) {
																						echo '<tr class="rows">';
																						echo '<td>'.$value['idCortecaja'].'</td>';
																						echo '<td>'.$value['fechainicio'].'</td>';
																						echo '<td>'.$value['fechafin'].'</td>';
																						echo '<td>$'.number_format($value['saldoinicialcaja'],2).'</td>';
																						echo '<td>$'.number_format($value['montoventa'],2).'</td>';
																						echo '<td>$'.number_format($value['retirocaja'],2).'</td>';
																						echo '<td>$'.number_format($value['abonocaja'],2).'</td>';
																						echo '<td>$'.number_format($value['saldofinalcaja'],2).'</td>';
																						echo '<td>'.$value['usuario'].'</td>';
																						echo '<td><a class="btn btn-primary active" href="index.php?c=caja&f=verCorte&idCorte='.$value['idCortecaja'].'"><i class="fa fa-list-ul"></i> Ver</a></td>';
																						echo '<td> <a class="btn btn-primary active" onclick="imprimeCorteTicket('.$value['idCortecaja'].');"><i class="fa fa-print"></i></a></td>';
																						echo '</tr>';
																						$final += $value['saldofinalcaja'];
																				}
																		?>
																</tbody>
														</table>
												</div>
										</div>
								</div>
						</div>
				</div> -->


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
																						<div class="col-sm-6">
																								<div class="row">
																										<div class="col-sm-6">
																												<label>Saldo inicial Caja </label>
																												<input type="text" class="form-control" id="saldo_inicial" value="<?php echo $corteInfo[0]['saldoinicialcaja']; ?>" readonly style="text-align: right;">
																										</div>
																										<div class="col-sm-6">
																												<label>Monto de Ventas en el Periodo  </label>
																												<input type="text" class="form-control" id="monto_ventas" value="<?php  echo $corteInfo[0]['montoventa'];?>" readonly style="text-align: right;">
																										</div>
																								</div>
																						</div>
																						<div class="col-sm-6">
																								<div class="row">
																										<div class="col-sm-6">
																												<label>Saldo Final </label>
																												<input type="text" class="form-control numeros" id="saldo_final" readonly style="text-align: right;">
																										</div>                                                    
																										<div class="col-sm-6">
																												<label>Saldo disponible en Caja </label>
																												<input type="text" class="form-control" id="saldo_disponible" value="<?php echo ($corteInfo[0]['saldoinicialcaja']+$corteInfo[0]['montoventa']); ?>" readonly style="text-align: right;">
																										</div>
																								</div>
																						</div>
																						<!--    <div class="col-sm-4">
																										<label>Saldo inicial Caja $</label>
																										<input type="text" class="form-control" id="saldo_inicial" value="<?php echo $corteInfo[0]['saldoinicialcaja']; ?>" readonly>
																								</div>
																								<div class="col-sm-4">
																										<label>Monto de Ventas en el Periodo $ </label>
																										<input type="text" class="form-control" id="monto_ventas" value="<?php  echo $corteInfo[0]['montoventa'];?>" readonly>
																								</div>
																								<div class="col-sm-6">
																										<label>Saldo disponible en Caja $</label>
																										<input type="text" class="form-control" id="saldo_disponible" value="<?php echo ($corteInfo[0]['saldoinicialcaja']+$corteInfo[0]['montoventa']); ?>" readonly>
																								</div>
																								<div class="col-sm-6">
																										<label>Saldo Retiros de Caja $</label>
																										<input type="text" class="form-control" id="saldoRetirosCaja" readonly>
																								</div> -->
																						</div>
																						<div class="row">
																								<div class="col-sm-12">
																										<h4>ABONOS / RETIROS</h4>
																								</div>
																						</div>
																						<div class="row">
																								<div class="col-sm-6">
																										<div class="row">
																												<div class="col-sm-6">
																														<label>Retiros de Caja $</label>
																														<input type="text" class="form-control" id="saldoRetirosCaja" readonly style="text-align: right;">
																												</div>                                                        
																												<div class="col-sm-6">
																														<label>Deposito de Corte Caja </label>
																														<input type="text" class="form-control numeros" id="deposito_caja" style="background-color: #A9F5A9;text-align: right;" value="<?php echo $corteInfo[0]['abonocaja']; ?>" readonly >
																												</div>
																										</div>
																								</div>
																								<div class="col-sm-6">
																										<div class="row">
																												<div class="col-sm-6">
																														<label>Retiro de Corte Caja </label>
																														<input type="text" class="form-control numeros" id="retiro_caja" style="background-color: #FFCCDD;text-align: right;" value="<?php echo $corteInfo[0]['retirocaja']; ?>" readonly >
																												</div>
																												<div class="col-sm-6">
																														<label>Saldo Otras Formas de Pago $</label>
																														<input type="text" class="form-control numeros" id="totalof"   readonly style="text-align: right;">
																												</div>
																										</div>
																								</div>
																							 <!-- <div class="col-sm-4">
																										<label>Retiro de Corte Caja $</label>
																										<input type="text" class="form-control numeros" id="retiro_caja" style="background-color: #FFCCDD;" value="<?php echo $corteInfo[0]['retirocaja']; ?>" readonly>
																								</div>
																								<div class="col-sm-4">
																										<label>Deposito de Caja $</label>
																										<input type="text" class="form-control numeros" id="deposito_caja" style="background-color: #A9F5A9;" value="<?php echo $corteInfo[0]['abonocaja']; ?>" readonly>
																								</div>
																								 <div class="col-sm-4">
																										<label>Deposito de Caja $</label>
																										<input type="text" class="form-control numeros" id="deposito_caja" style="background-color: #A9F5A9;" value="<?php echo $corteInfo[0]['abonocaja']; ?>" readonly>
																								</div>
																								<div class="col-sm-4">
																										<div style="align:center;padding-top:9%">
																												<label>Usuario: </label><strong><?php echo ' '.$corteInfo[0]['usuario']; ?></strong>
																										</div>
																								</div> -->
																						</div>
																				</div>
																		</div>
																</div>
														</div>
												</div>
										</div>
<div class="row">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingOne">
								<h4 class="panel-title">
										<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
												Pagos
										</a>
								</h4>
						</div>
						<div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne" >
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12" style="overflow-x: scroll">
														<table class="table table-bordered table-hover" id="gridPagosCut" style="width: 100% !important;">
																<thead>
																		<tr>
																				<th>ID Venta</th>
																				<th>Cliente</th>
																				<th>Fecha</th>
																				<th>EF</th>
																				<th>TC</th>
																				<th>TD</th>
																				<th>CR</th>
																				<th>CH</th>
																				<th>TRA</th>
																				<th>SPEI</th>
																				<th>TR</th>
																				<th>NI</th>
																				<th>TVales</th>
																				<th>Cortesía</th>
																				<th>Otros</th>
																				<th>Cambio</th>
																				<th>Impuestos</th>
																				<th>Monto</th>
																				<th>Des.</th>
																				<th>Importe</th>
																				<th>Ingreso (EF-Cambio)</th>
																		</tr>
																</thead>
																<tbody>
																</tbody>
														</table>
												</div>
										</div>
										<div class="row">
												<div class="col-sm-12" style="overflow-x: scroll">
														<table class="table table-bordered table-hover" id="gridPagosCutTotales">
																<tr> </tr>
														</table>
												</div>
										</div>
								</div>
						</div>
				</div>
				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingTwo">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
												Tarjetas
										</a>
								</h4>
						</div>
						<div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridTarjetas">
																<thead>
																		<tr>
																				<th>Tarjeta</th>
																				<th>Monto</th>
																		</tr>
																</thead>
																<tbody>
																</tbody>
														</table>
												</div>
										</div>
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridTarjetasCutTotales"><tr></tr></table>
												</div>
										</div>
								</div>
								</div>
						</div>
				</div>
				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingThree">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
												Productos Vendidos
										</a>
								</h4>
						</div>
						<div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridProductosCut">
																<thead>
																		<tr>
																				<th>Código</th>
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
								</div>
						</div>
				</div>
				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingFour">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
												Retiros de caja
										</a>
								</h4>
						</div>
						<div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
								<div class="panel-body">
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
				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingFive">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
												Abonos de Caja
										</a>
								</h4>
						</div>
						<div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridAbonosCut">
																<thead>
																		<tr>
																				<th>ID Abono</th>
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
														<table class="table table-bordered table-hover" id="gridAbonosCutTotales"><tr></tr></table>
												</div>
										</div>
								</div>
						</div>
				</div>

				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingSix2">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix2" aria-expanded="false" aria-controls="collapseSix2">
												Cortesías
										</a>
								</h4>
						</div>
						<div id="collapseSix2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix2">
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridCortesiasCut">
																<thead>
																		<tr>
																				<th>ID Venta</th>
																				<th>Monto</th>
																		</tr>
																</thead>
																<tbody>
																</tbody>
														</table>
												</div>
										</div>
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridCortesiasCutTotales"><tr></tr></table>
												</div>
										</div>
								</div>
						</div>
				</div>

				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingSix">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
												Propinas
										</a>
								</h4>
						</div>
						<div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" style="width: 100%" id="gridPropinasCut">
																<thead>
																		<tr>
																			<th>Venta</th>
						                                                    <th>Mesero</th>
						                                                    <th>Fecha y hora</th>
						                                                    <th>Efectivo</th>
						                                                    <th>Visa</th>
						                                                    <th>MC</th>
						                                                    <th>AMEX</th>
						                                                    <th>Total</th>
																		</tr>
																</thead>
																<tbody>
																</tbody>
														</table>
												</div>
										</div>
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridPropinasCutTotales"><tr></tr></table>
												</div>
										</div>
								</div>
						</div>
				</div>

				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingSeven">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
												Devoluciones
										</a>
								</h4>
						</div>
						<div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridDevolucionesCut">
																<thead>
																		<tr>
																				<th>ID Venta</th>
																				<th>Monto</th>
																		</tr>
																</thead>
																<tbody>
																</tbody>
														</table>
												</div>
										</div>
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridDevolucionesCutTotales"><tr></tr></table>
												</div>
										</div>
								</div>
						</div>
				</div>

				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingEight">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
												Cancelaciones
										</a>
								</h4>
						</div>
						<div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridCancelacionesCut">
																<thead>
																		<tr>
																				<th>ID Venta</th>
																				<th>Monto</th>
																		</tr>
																</thead>
																<tbody>
																</tbody>
														</table>
												</div>
										</div>
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridCancelacionesCutTotales"><tr></tr></table>
												</div>
										</div>
								</div>
						</div>
				</div>

				<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingEight">
								<h4 class="panel-title">
										<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
												Facturas
										</a>
								</h4>
						</div>
						<div id="collapseNine" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
								<div class="panel-body">
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridFacturasCut">
																<thead>
																		<tr>
																				<th>ID Venta</th>
																				<th>Monto</th>
																		</tr>
																</thead>
																<tbody>
																</tbody>
														</table>
												</div>
										</div>
										<div class="row">
												<div class="col-sm-12">
														<table class="table table-bordered table-hover" id="gridFacturasCutTotales"><tr></tr></table>
												</div>
										</div>
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

			<!-- Modal arqueo de caja -->
<div id="modalArqueo" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Arqueo de caja</h4>
			</div>
			<div class="modal-body">

			<div class="row">
					<i class="fa fa-money fa-4x" aria-hidden="true"></i>
			</div>
			<div class="row">
				<div class="col-sm-4">
						<div class="input-group">
								<span class="input-group-addon">$ 20</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso20" disabled>
						</div>
				</div>
				<div class="col-sm-4">
						<div class="input-group">
								<span class="input-group-addon">$ 50</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso50" disabled>
						</div>
				</div>
				<div class="col-sm-4">
						<div class="input-group">
								<span class="input-group-addon">$ 100</span>
								<input type="number" class="form-control"  value="0" min="0" style="text-align: right;"  id="peso100" disabled>
						</div>
				</div>
			</div>
			<div class="row">
					<div class="col-sm-4">
							<div class="input-group">
								<span class="input-group-addon">$ 200</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso200" disabled>
						</div>
					</div>
					<div class="col-sm-4">
							<div class="input-group">
								<span class="input-group-addon">$ 500</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso500" disabled>
						</div>
					</div>
					<div class="col-sm-4">
							<div class="input-group">
								<span class="input-group-addon">$ 1000</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso1000" disabled>
						</div>
					</div>
			</div>
			<br>
			<div class="row">
					<i class="fa fa-usd fa-4x" aria-hidden="true"></i>
			</div>
			<div class="row">
				<div class="col-sm-3">
						<div class="input-group">
								<span class="input-group-addon">$ 1</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso1" disabled>
						</div>
				</div>
				<div class="col-sm-3">
						<div class="input-group">
								<span class="input-group-addon">$ 2</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso2" disabled>
						</div>
				</div>
				<div class="col-sm-3">
						<div class="input-group">
								<span class="input-group-addon">$ 5</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso5" disabled>
						</div>
				</div>
				<div class="col-sm-3">
						<div class="input-group">
								<span class="input-group-addon">$ 10</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="peso10" disabled>
						</div>
				</div>
			</div>
			<div class="row">
					<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">5¢</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="centavo5" disabled>
						</div>
					</div>
					<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">10¢</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="centavo10" disabled>
						</div>
					</div>
					<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">20¢</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="centavo20" disabled>
						</div>
					</div>
					<div class="col-sm-3">
							<div class="input-group">
								<span class="input-group-addon">50¢</span>
								<input type="number" class="form-control" value="0" min="0" style="text-align: right;"  id="centavo50" disabled>
						</div>
					</div>
			</div>
			<br><br>
				<div class="row">
						<div class="col-sm-12">
								<div class="input-group">
										<span class="input-group-addon"> Total </span>
										<input type="number" class="form-control" value="0" style="text-align: right;" id="totalArqueo" disabled >
								</div>
						</div>
				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
		
</body>
</html>