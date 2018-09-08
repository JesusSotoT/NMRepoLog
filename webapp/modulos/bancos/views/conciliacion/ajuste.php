<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="js/bootstrap/bootstrap.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
</head>
<body>
	<div id="conciliacion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Ajuste</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-default">
							<div class="panel-heading" align="center">
								<div class="row">
									<div class="col-md-6">
										<h3 class="panel-title" style="margin-top: 10px">Ajuste de conciliacion</h3>
									</div>
									
									<div class="col-md-6" align="right"><?php
										if ($diferencia>0) {?>
											<button class="btn btn-success">Ajustar</button><?php
										} ?>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-md-6" style="padding: 5px" align="right">
										<h3 class="panel-title">Saldo cuanta bancaria:</h3>
									</div>
									
									<div class="col-md-6" style="padding: 5px">
										<h3 class="panel-title">$ <?php echo $saldo_banco ?> </h3>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6" style="padding: 5px" align="right">
										<h3 class="panel-title">Saldo documentos:</h3>
									</div>
									
									<div class="col-md-6" style="padding: 5px">
										<h3 class="panel-title">$ <?php echo $saldo_empresa ?> </h3>
									</div>
								</div>
								
								<div class="row">
									<div class="col-md-6" style="padding: 5px" align="right">
										<h3 class="panel-title">Diferencia:</h3>
									</div>
									<div class="col-md-6" style="padding: 5px">
										<h3 class="panel-title">$ <?php echo $diferencia ?> </h3>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-8">
										<h3 class="panel-title">Movimientos no conciderados por nosotros</h3>
									</div>
									<div class="col-md-4" align="right">
										<h3 class="panel-title">Saldo final: $ <?php echo $saldo_banco ?> </h3>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
									<tr>
										<td align="center"><strong>Fecha</strong></td>
										<td><strong>Folio</strong></td>
										<td><strong>Concepto</strong></td>
										<td><strong>Referencia</strong></td>
										<td align="center"><strong>Cuenta</strong></td>
										<td align="center"><strong>Cargo</strong></td>
										<td align="center"><strong>Abono</strong></td>
									</tr><?php
									
									foreach ($conciliacion2['no_conciliados'] as $c => $cc) {
										if ($cc['cuenta']!=$cuenta) { ?>
											<tr>
												<td colspan="7" class="success"><strong>Cuenta: <?php echo $cc['cuenta'] ?></strong></td>
											</tr><?php
										}
										
										if (!empty($cc['cargo'])) {
											$total_cargo+=$cc['cargo'];
										}
										
										if (!empty($cc['abono'])) {
											$total_abono+=$cc['abono'];
										}
										
										$cuenta=$cc['cuenta']; ?>
											
										<tr>
											<td align="center"><?php echo $cc['fecha'] ?></td>
											<td><?php echo $cc['folio'] ?></td>
											<td><?php echo $cc['concepto'] ?></td>
											<td><?php echo $cc['referencia'] ?></td>
											<td align="center"><?php echo $cc['cuenta'] ?></td>
											<td align="center"><?php echo $cc['cargo'] ?></td>
											<td align="center"><?php echo $cc['abono'] ?></td>
										</tr><?php
									}
									
									$total_cargo=number_format($total_cargo, 4);
									$total_abono=number_format($total_abono, 4);  ?>
									
									<tr>
										<td colspan="5" class="default" align="right"><strong>Totales: </strong></td>
										<td align="center"><strong>$ <?php echo $total_cargo ?></strong></td>
										<td align="center"><strong>$ <?php echo $total_abono ?></strong></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<div class="row">
									<div class="col-md-8">
										<h3 class="panel-title">Movimientos no conciderados por el banco</h3>
									</div>
									<div class="col-md-4" align="right">
										<h3 class="panel-title">Saldo final: $ <?php echo $saldo_empresa ?> </h3>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
									<tr>
										<td align="center"><strong>Fecha</strong></td>
										<td><strong>Folio</strong></td>
										<td><strong>Concepto</strong></td>
										<td><strong>Referencia</strong></td>
										<td align="center"><strong>Cuenta</strong></td>
										<td align="center"><strong>Cargo</strong></td>
										<td align="center"><strong>Abono</strong></td>
									</tr><?php
									
									$total_cargo= 0.0000;
									$total_abono= 0.0000;
									
									foreach ($conciliacion['no_conciliados'] as $c => $cc) {
										if ($cc['cuenta']!=$cuenta) { ?>
											<tr>
												<td colspan="7" class="success"><strong>Cuenta: <?php echo $cc['cuenta'] ?></strong></td>
											</tr><?php
										} 
										
										if (!empty($cc['cargo'])) {
											$total_cargo+=$cc['cargo'];
										}
														
										if (!empty($cc['abono'])) {
											$total_abono+=$cc['abono'];
										}
										
										$cuenta=$cc['cuenta']; ?>
											
										<tr>
											<td align="center"><?php echo $cc['fecha'] ?></td>
											<td><?php echo $cc['folio'] ?></td>
											<td><?php echo $cc['concepto'] ?></td>
											<td><?php echo $cc['referencia'] ?></td>
											<td align="center"><?php echo $cc['cuenta'] ?></td>
											<td align="center"><?php echo $cc['cargo'] ?></td>
											<td align="center"><?php echo $cc['abono'] ?></td>
										</tr><?php
									} 
									
									$total_cargo=number_format($total_cargo, 4);
									$total_abono=number_format($total_abono, 4); ?>
									
									<tr>
										<td colspan="5" class="default" align="right"><strong>Totales: </strong></td>
										<td align="center"><strong>$ <?php echo $total_cargo ?></strong></td>
										<td align="center"><strong>$ <?php echo $total_abono ?></strong></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>