<!DOCTYPE html>
<head></head>
<body>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Resumen</h3>
		</div>

		<div class="panel-body">
			<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
				<tr>
					<td><strong>Fecha</strong></td>
					<td><strong>Cuenta</strong></td>
					<td><strong>Saldo Inicial</strong></td>
					<td><strong>Saldo Final</strong></td>
				</tr>
				
				<tr>
					<td><?php echo $corte ?></td>
					<td><?php echo $cuenta ?></td>
					<td><?php echo $conciliacion_existente['saldo_inicial'] ?></td>
					<td><?php echo $conciliacion_existente['saldo_final'] ?></td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>