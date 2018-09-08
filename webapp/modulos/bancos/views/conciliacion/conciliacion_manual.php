<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script src="js/bootstrap/bootstrap.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap-theme.css" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap/bootstrap-theme.min.css" />
</head>
<body>
	<div class="row">
	<!-- Documentos -->
		<div class="col-md-6">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-md-6">
							<h3 class="panel-title">Listado de Documentos Bancarios</h3>
						</div>
						
						<div class="col-md-6" align="right">
							<button type="button" class="btn btn-info btn-sm" id="btn_guardar_manual" onclick="guardar_manual({})">
								<span class="glyphicon glyphicon-floppy-disk"></span> Guardar
							</button>
							
							<button type="button" class="btn btn-success btn-sm" id="btn_guardar_canciliacion" onclick="guardar({registros:<?php echo $registros_guardar ?>, saldo_inicial:'<?php echo $saldo_inicial ?>', saldo_final:'<?php echo $saldo_final_empresa ?>', periodo:'<?php echo $periodo ?>', ejercicio:'<?php echo $ejercicio ?>', cuenta:'<?php echo $cuenta ?>'})">
								<span class="glyphicon glyphicon-ok"></span> Finalizar
							</button>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<table width="100%" border="0" class="table table-bordered table-condensed" cellspacing="0">
						<tr class="active">
							<td><strong><span class="glyphicon glyphicon-check"></span></strong></td>
							<td style="width: 80px"><strong>Fecha</strong></td>
							<td><strong>Folio</strong></td>
							<td><strong>Referencia</strong></td>
							<td><strong>Concepto</strong></td>
							<td><strong>Cargo</strong></td>
							<td><strong>Abono</strong></td>
						</tr><?php
						$registros=array();
					//$documentos es una variable que viene desde el controlador
					//con todos los registros de las documentos bancarios
						while($rr=$documentos->fetch_array()){
								
							// echo "<pre>";
							// print_r($rr);
							
								if ($rr['idbancaria']!=$cuenta) { ?>
									<tr>
										<td colspan="9" class="success"><strong>Cuenta: <?php echo $rr['idbancaria'] ?></strong></td>
									</tr><?php
								}
								
								if ($rr['conciliado']==1) {
									$checado='checked=true';
									$tr='success';
									$rr['checado']=1;
								} else{
									$checado='';
									$tr='';
									$rr['checado']=0;
								}
								
								$registros[$rr[0]]=$rr;
								
								$rr['importe']=number_format($rr['importe'], 4, '.' ,'');
								$cuenta=$rr['idbancaria']; ?>
								
								<tr id="tr_<?php echo $rr[0] ?>" class="<?php echo $tr ?>" >
									<td><input type="checkbox" <?php echo $checado ?> onchange="check({id:<?php echo $rr[0] ?>})" id="check_<?php echo $rr[0] ?>" /> </td>
									<td><?php echo $rr['fecha'] ?></td>
									<td><?php echo $rr['folio'] ?></td>
									<td><?php echo $rr['referencia'] ?></td>
									<td><?php echo $rr['concepto'] ?></td><?php
									
									if ($rr['idDocumento']=='2' OR $rr['idDocumento']=='4') { ?>
										<td><?php echo $rr['importe'] ?></td>
										<td></td><?php
									}
									
									if ($rr['idDocumento']=='1' OR $rr['idDocumento']=='5') { ?>
										<td></td>
										<td><?php echo $rr['importe'] ?></td><?php
									} ?>
								</tr><?php
						} ?>
					</table>
				</div>
			</div>
		</div>
		
	<!-- Cuenta bancaria -->
		<div class="col-md-6">
			<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Cuenta Bancaria</h3>
			</div>
			<div class="panel-body">
				<table width="100%" border="0" class="table table-bordered table-condensed" cellspacing="0">
					<tr class="active">
						<td><strong>Fecha</strong></td>
						<td><strong>Folio</strong></td>
						<td><strong>Concepto</strong></td>
						<td><strong>Cargo</strong></td>
						<td><strong>Abono</strong></td>
					</tr>
					<tr><?php
				//$listar_cuentas_bancarias es una varia ble que viene desde el controlador
				//con todos los registros de las cuentas bancarias
					while($rr=$cuentas_bancarias->fetch_array()){
						if ($rr['idbancaria']!=$cuenta) { ?>
							<tr>
								<td colspan="9" class="active"><strong>Cuenta: <?php echo $rr['idbancaria'] ?></strong></td>
							</tr><?php
						}
						
						$cuenta=$rr['idbancaria']; ?>
						
							<tr>
								<td><?php echo $rr['fecha'] ?></td>
								<td><?php echo $rr['folio'] ?></td>
								<td><?php echo $rr['concepto'] ?></td>
								<td><?php echo $rr['cargos'] ?></td>
								<td><?php echo $rr['abonos'] ?></td>
							</tr><?php
					} ?>
				</table>
			</div>
		</div>
		</div>
	</div>
</body>
<script>datos=<?php echo json_encode($registros) ?></script>