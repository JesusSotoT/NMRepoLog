<!DOCTYPE html>
<head></head>
<body><?php
if ($saldo_banco==0.0000&&$existe==0) {?>
	<div align="center">
		<h3><span class="label label-default">* No se detectaron conciliaciones *</span></h3>
	</div><?php
}else{ ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-6">
						<h3 class="panel-title">Conciliacion Completa</h3>
					</div>
					
					<div class="col-md-6" align="right">
						<button type="button" class="btn btn-info" id="btn_guardar_canciliacion" onclick="guardar({registros:<?php echo $registros_guardar ?>, saldo_inicial:'<?php echo $saldo_inicial ?>', saldo_final:'<?php echo $saldo_final_empresa ?>', periodo:'<?php echo $periodo ?>', ejercicio:'<?php echo $ejercicio ?>', cuenta:'<?php echo $cuenta ?>'})">
							Ajustar
						</button>
						
						<button type="button" class="btn btn-success" id="btn_guardar_canciliacion" onclick="guardar({registros:<?php echo $registros_guardar ?>, saldo_inicial:'<?php echo $saldo_inicial ?>', saldo_final:'<?php echo $saldo_final_empresa ?>', periodo:'<?php echo $periodo ?>', ejercicio:'<?php echo $ejercicio ?>', cuenta:'<?php echo $cuenta ?>'})">
							Guardar
						</button>					
					</div>
				</div>
			</div>

			<div class="panel-body">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">Resumen</h5>
					</div>

					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="row">
									<div class="col-md-12"><?php
										echo $info_empresa; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12"><?php
										echo $nombre_banco; ?>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12" id="info_cuenta">
										
									</div>
								</div>

								<div class="row">
									<div class="col-md-12" style="height: 50px">
										Cortada al <?php echo $corte; ?>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<!-- Div vacia -->
							</div>
						</div>
						
						<div class="row">
							<div class="col-md-12">
								<!-- Div vacia -->
							</div>
						</div>
					<!-- Fila Uno	 -->
						<div class="row">
							<div class="col-md-3">
								Saldo banco
							</div>

							<div class="col-md-2">
						<!-- Variable que viene desde el controlador -->
								<?php echo '$ '.$saldo_banco ?>
							</div>

							<div class="col-md-2">
								<!-- Div vacia -->
							</div>

							<div class="col-md-3">
								Saldo contable
							</div>

							<div class="col-md-2">
						<!-- Variable que viene desde el controlador -->
								<?php echo '$ '.$saldo_empresa ?>
							</div>
						</div>
					<!-- FIN Fila Uno	 -->

					<!-- Fila Dos	 -->
						<div class="row">
							<div class="col-md-3">
								(-) Cheques en circulacion
							</div>

							<div class="col-md-2">
							<!-- $total_cheques es una variable que viene desde el controlador -->
								$ <?php echo $total_cheques ?>
							</div>

							<div class="col-md-2">
								<!-- Div vacia -->
							</div>

							<div class="col-md-3">
								(-) Cargos de Bancos
							</div>

							<div class="col-md-2">
							<!-- $total_cargos_banco es una variable que viene desde el controlador -->
								$ <?php echo $total_cargos_banco ?>
							</div>
						</div>
					<!-- FIN Fila Dos	 -->

					<!-- Fila Tres	 -->
						<div class="row">
							<div class="col-md-3">
								(+) Nuestros Depositos
							</div>

							<div class="col-md-2">
							<!-- $total_nuestros_depositos es una variable que viene desde el controlador -->
								$ <?php echo $total_nuestros_depositos ?>
							</div>

							<div class="col-md-2">
								<!-- Div vacia -->
							</div>

							<div class="col-md-3">
								(+) Depositos de Bancos
							</div>

							<div class="col-md-2">
							<!-- $total_nuestros_depositos es una variable que viene desde el controlador -->
								$ <?php echo $total_depositos_banco ?>
							</div>
						</div>
					<!-- FIN Fila Tres	 -->

					<!-- Fila Cuatro	 -->
						<div class="row">
							<div class="col-md-3">
								Saldos Iguales
							</div>

							<div class="col-md-2">
							<!-- $saldo_final_banco es una variable que viene desde el controlador -->
							<strong>$ <?php echo $saldo_final_banco ?></strong>
							</div>

							<div class="col-md-2">
								<!-- Div vacia -->
							</div>

							<div class="col-md-3">
								Saldos Iguales
							</div>

							<div class="col-md-2">
							<!-- $saldo_final_empresa es una variable que viene desde el controlador -->
								<strong>$ <?php echo $saldo_final_empresa ?></strong>
							</div>
						</div>
					<!-- FIN Fila Cuatro	 -->

					<!-- Diferencia entre cantidades -->
						<div class="row">
							<div class="col-md-5">
								<!-- Div vacia -->
							</div>
							<div class="col-md-4">
								Diferencia:
								<p id="diferencia">
								<!-- $diferencia_totales es una variable que viene desde el controlador -->
									<strong>$ <?php echo $diferencia_totales ?></strong>
								</p>
							</div>
							<div class="col-md-3">
								<!-- Div vacia -->
							</div>
						</div>
					<!-- FIN Diferencia entre cantidades -->
					</div>
				</div>
				
			<!-- Cheques el circulacion -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">Cheques el circulacion</h5>
					</div>
					
					<div class="panel-body"><?php
						if (!empty($cheques)) { ?>
							<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
								<tr>
									<td><strong>Fecha</strong></td>
									<td><strong>Folio</strong></td>
									<td><strong>Concepto</strong></td>
									<td><strong>Referencia</strong></td>
									<td align="center"><strong>Importe</strong></td>
								</tr><?php
		
								foreach ($cheques as $c => $cc) { ?>
									<tr>
										<td><?php echo $cc['fecha'] ?></td>
										<td><?php echo $cc['folio'] ?></td>
										<td><?php echo $cc['concepto'] ?></td>
										<td><?php echo $cc['referencia'] ?></td>
										<td align="center"><?php echo $cc['cargo'] ?></td>
									</tr><?php
								}  ?>
								
								<tr>
									<td colspan="4" class="default" align="right" style="font-size: 14px"><strong>Total: </strong></td>
													<!-- $total_cheques es una variable que viene desde el controlador -->
									<td align="center" style="font-size: 14px"><strong>$ <?php echo $total_cheques ?></strong></td>
								</tr>
							</table><?php
						}else{ ?>
							<div align="center">
								<h3><span class="label label-default">* No se detectaron cheques en circulacion *</span></h3>
							</div><?php
						} ?>
					</div>
				</div>
			<!-- FIN Cheques el circulacion -->
			
			<!-- Cargos de banco -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">Cargos de banco</h5>
					</div>
					
					<div class="panel-body"><?php
						if (!empty($cargos_banco)) { ?>
							<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
								<tr>
									<td><strong>Fecha</strong></td>
									<td><strong>Folio</strong></td>
									<td><strong>Concepto</strong></td>
									<td><strong>Referencia</strong></td>
									<td align="center"><strong>Importe</strong></td>
								</tr><?php
								
								foreach ($cargos_banco as $c => $cc) { ?>
									<tr>
										<td><?php echo $cc['fecha'] ?></td>
										<td><?php echo $cc['folio'] ?></td>
										<td><?php echo $cc['concepto'] ?></td>
										<td><?php echo $cc['referencia'] ?></td>
										<td align="center"><?php echo $cc['abono'] ?></td>
									</tr><?php
								} ?>
								
								<tr>
									<td colspan="4" class="default" align="right" style="font-size: 14px"><strong>Total: </strong></td>
													<!-- $total_cargos_banco es una variable que viene desde el controlador -->
									<td align="center" style="font-size: 14px"><strong>$ <?php echo $total_cargos_banco ?></strong></td>
								</tr>
							</table><?php
						}else{ ?>
							<div align="center">
								<h3><span class="label label-default">* No se detectaron cargos bancarios *</span></h3>
							</div><?php
						} ?>
					</div>
				</div>
			<!-- FIN Cargos de banco -->
			
			<!-- Nuestros Depositos -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">Nuestros Depositos</h5>
					</div>
					
					<div class="panel-body"><?php
						if (!empty($nuestros_depositos)) { ?>
							<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
								<tr>
									<td><strong>Fecha</strong></td>
									<td><strong>Folio</strong></td>
									<td><strong>Concepto</strong></td>
									<td><strong>Referencia</strong></td>
									<td align="center"><strong>Importe</strong></td>
								</tr><?php
								
								foreach ($nuestros_depositos as $c => $cc) { ?>
									<tr>
										<td><?php echo $cc['fecha'] ?></td>
										<td><?php echo $cc['folio'] ?></td>
										<td><?php echo $cc['concepto'] ?></td>
										<td><?php echo $cc['referencia'] ?></td>
										<td align="center"><?php echo $cc['abono'] ?></td>
									</tr><?php
								} ?>
								
								<tr>
									<td colspan="4" class="default" align="right" style="font-size: 14px"><strong>Total: </strong></td>
													<!-- $total_cargos_banco es una variable que viene desde el controlador -->
									<td align="center" style="font-size: 14px"><strong>$ <?php echo $total_nuestros_depositos ?></strong></td>
								</tr>
							</table><?php
						}else{ ?>
							<div align="center">
								<h3><span class="label label-default">* No se detectaron depositos *</span></h3>
							</div><?php
						} ?>
					</div>
				</div>
			<!-- FIN Nuestros Depositos -->
			
			<!-- Depositos del banco -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title">Depositos del banco</h5>
					</div>
					
					<div class="panel-body"><?php
						if (!empty($depositos_banco)) { ?>
							<table width="100%" border="0" class="table table-striped table-bordered table-condensed" cellspacing="0">
								<tr>
									<td><strong>Fecha</strong></td>
									<td><strong>Folio</strong></td>
									<td><strong>Concepto</strong></td>
									<td><strong>Referencia</strong></td>
									<td align="center"><strong>Importe</strong></td>
								</tr><?php
								
								// $depositos_banco es una variable que viene desde el controlador
								foreach ($depositos_banco as $c => $cc) { ?>
									<tr>
										<td><?php echo $cc['fecha'] ?></td>
										<td><?php echo $cc['folio'] ?></td>
										<td><?php echo $cc['concepto'] ?></td>
										<td><?php echo $cc['referencia'] ?></td>
										<td align="center"><?php echo $cc['cargo'] ?></td>
									</tr><?php
								} ?>
								
								<tr>
									<td colspan="4" class="default" align="right" style="font-size: 14px"><strong>Total: </strong></td>
													<!-- $total_depositos_banco es una variable que viene desde el controlador -->
									<td align="center" style="font-size: 14px"><strong>$ <?php echo $total_depositos_banco ?></strong></td>
								</tr>
							</table><?php
						}else{ ?>
							<div align="center">
								<h3><span class="label label-default">* No se detectaron depositos bancarios *</span></h3>
							</div><?php
						} ?>
					</div>
				</div>
			<!-- FIN Nuestros Depositos -->
			</div>
	</div><?php
} ?>
</body>
</html>