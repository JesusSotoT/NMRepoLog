<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="js/bootstrap/bootstrap.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
</head>
<body>
	<div id="conciliacion"><?php
		if ($vista==2) { 
			if (empty($conciliacion2['conciliados'])) {?>
				<div align="center">
					<h3><span class="label label-default">* No se detectaron documentos conciliados *</span></h3>
				</div><?php
			}else{ ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Documentos conciliados</h3>
					</div>
					<div class="panel-body">
						<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
							<tr>
								<td>Fecha</td>
								<td>Fecha de conciliacion</td>
								<td>Folio</td>
								<td>Concepto</td>
								<td>Referencia</td>
								<td>Cuenta</td>
								<td>Cargo</td>
								<td>Abono</td>
							</tr><?php
							
						//$conciliacion2 es una varia ble que viene desde el controlador
						//con los registros de las conciliados y no conciliados de Cuentas bancarias
								foreach ($conciliacion2['conciliados'] as $c => $cc) {
									if ($cc['cuenta']!=$cuenta) { ?>
										<tr>
											<td colspan="9" class="success"><strong>Cuenta: <?php echo $cc['cuenta'] ?></strong></td>
										</tr><?php
									} 
								
									$cuenta=$cc['cuenta']; ?>
									
									<tr>
										<td><?php echo $cc['fecha'] ?></td>
										<td><?php echo $cc['fecha_conciliacion'] ?></td>
										<td><?php echo $cc['folio'] ?></td>
										<td><?php echo $cc['concepto'] ?></td>
										<td><?php echo $cc['referencia'] ?></td>
										<td><?php echo $cc['cuenta'] ?></td>
										<td><?php echo $cc['cargo'] ?></td>
										<td><?php echo $cc['abono'] ?></td>
									</tr><?php
									
								} ?>
						</table>
					</div>
				</div><?php
			}
		} 
		
		if ($vista==3) {
			if (empty($conciliacion['no_conciliados'])) {?>
				<div align="center">
					<h3><span class="label label-default">* No se detectaron documentos no conciderados por el banco *</span></h3>
				</div><?php
			}else{ ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-8">
								<h3 class="panel-title">Documentos no conciderados por el banco</h3>
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
										<td colspan="7" class="active"><strong>Cuenta: <?php echo $cc['cuenta'] ?></strong></td>
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
										
							<tr class="active">
								<td colspan="5" class="default" align="right"><strong>Totales: </strong></td>
								<td align="center"><strong>$ <?php echo $total_cargo ?></strong></td>
								<td align="center"><strong>$ <?php echo $total_abono ?></strong></td>
							</tr>
						</table>
					</div>
				</div><?php
			}
		}
		
		if ($vista==4) {
			if (empty($conciliacion2['no_conciliados'])) {?>
				<div align="center">
					<h3><span class="label label-default">* No se detectaron documentos no conciderados por el banco *</span></h3>
				</div><?php
			}else{ ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-md-8">
								<h3 class="panel-title">Documentos no conciderados por la empresa</h3>
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
										
							<tr class="active">
								<td colspan="5" class="default" align="right"><strong>Totales: </strong></td>
								<td align="center"><strong>$ <?php echo $total_cargo ?></strong></td>
								<td align="center"><strong>$ <?php echo $total_abono ?></strong></td>
							</tr>
						</table>
					</div>
				</div><?php
			}
		} ?>
	</div>
</body>