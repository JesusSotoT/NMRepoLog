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
				<h3 class="panel-title">Listado de Documentos Bancarios</h3>
			</div>
			<div class="panel-body">
				<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
					<tr>
						<td>Fecha</td>
						<td>Folio</td>
						<td>Referencia</td>
						<td>Concepto</td>
						<td>Cargo</td>
						<td>Abono</td>
						<td>Cuenta Banco</td>
						<td>Saldo Inicial</td>
						<td>Saldo Final</td>
					</tr><?php
					
					$saldo_inicial=$saldo_empresa;
					
				//$documentos es una variable que viene desde el controlador
				//con todos los registros de las documentos bancarios
					while($rr=$documentos->fetch_array()){
							if ($rr['idbancaria']!=$cuenta) { ?>
								<tr>
									<td colspan="9" class="success"><strong>Cuenta: <?php echo $rr['idbancaria'] ?></strong></td>
								</tr><?php
							} 
							
							$rr['importe']=number_format($rr['importe'], 4, '.' ,'');
							$saldo_inicial=$saldo_empresa;
							$cuenta=$rr['idbancaria']; ?>
							
							<tr>
								<td><?php echo $rr['fecha'] ?></td>
								<td><?php echo $rr['folio'] ?></td>
								<td><?php echo $rr['referencia'] ?></td>
								<td><?php echo $rr['concepto'] ?></td><?php
								
								if ($rr['idDocumento']=='2' OR $rr['idDocumento']=='4') { ?>
									<td><?php echo $rr['importe'] ?></td>
									<td></td><?php
									
									$saldo_empresa+=$rr['importe'];
								}
								
								if ($rr['idDocumento']=='1' OR $rr['idDocumento']=='5') { ?>
									<td></td>
									<td><?php echo $rr['importe'] ?></td><?php
									
									$saldo_empresa-=$rr['importe'];
								} ?>
								
								<td><?php echo $rr['idbancaria'] ?></td>
								<td><?php echo $saldo_inicial ?></td>
								<td><?php echo $saldo_empresa ?></td>
							</tr><?php
					} 
					
					$saldo_empresa=number_format($saldo_empresa, 4, '.' ,''); ?>
					
					<tr>
						<td colspan="8" class="default" align="right" style="font-size: 14px"><strong>Total: </strong></td>
						<td align="center" style="font-size: 14px"><strong>$ <?php echo $saldo_empresa ?></strong></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</body>