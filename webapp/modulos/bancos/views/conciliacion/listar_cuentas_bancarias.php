<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="js/bootstrap/bootstrap.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>

<!-- Librerias de la herramienta -->
	<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<?php include('../../netwarelog/design/css.php');?>
	<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
</head>
<body>
	<div id="conciliacion">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Cuanta Bancaria</h3>
			</div>
			<div class="panel-body">
				<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
					<tr>
						<td><strong>Fecha</strong></td>
						<td><strong>Periodo<</strong></td>
						<td><strong>Folio</strong></td>
						<td><strong>Concepto</strong></td>
						<td><strong>Cargo</strong></td>
						<td><strong>Abono</strong></td>
						<td><strong>Cuenta</strong></td>
						<td><strong>Saldo Inicial</strong></td>
						<td><strong>Saldo Final</strong></td>
					</tr>
					<tr><?php
				//$listar_cuentas_bancarias es una varia ble que viene desde el controlador
				//con todos los registros de las cuentas bancarias
					while($rr=$listar_cuentas_bancarias->fetch_array()){
						if ($rr['idbancaria']!=$cuenta) { ?>
							<tr>
								<td colspan="9" class="active"><strong>Cuenta: <?php echo $rr['idbancaria'] ?></strong></td>
							</tr><?php
						} 
						
						$saldo_banco=$rr['saldofinal'];
						
						$cuenta=$rr['idbancaria']; ?>
						
							<tr>
								<td><?php echo $rr['fecha'] ?></td>
								<td><?php echo $rr['periodo'] ?></td>
								<td><?php echo $rr['folio'] ?></td>
								<td><?php echo $rr['concepto'] ?></td>
								<td><?php echo $rr['cargos'] ?></td>
								<td><?php echo $rr['abonos'] ?></td>
								<td><?php echo $rr['idbancaria'] ?></td>
								<td><?php echo $rr['saldoinicial'] ?></td>
								<td><?php echo $rr['saldofinal'] ?></td>
							</tr><?php
					} ?>
					
					<tr class="active">
						<td colspan="8" class="default" align="right" style="font-size: 14px"><strong>Total: </strong></td>
						<td align="center" style="font-size: 14px"><strong>$ <?php echo $saldo_banco ?></strong></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</body>