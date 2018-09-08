<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script src="js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
		<script src="../../libraries/dataTable/js/datatables.min.js" type="text/javascript"></script>
		<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
		<link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/datatablesboot.min.css">
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
		<script type="text/javascript" src='js/calendario.js'></script>
		<script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<script language='javascript' src='../cont/js/pdfmail.js'></script>

		
	</head>
	<style>   
	a:link   
	{   
	 text-decoration:none; 
	 color:#000000;  
	}   
</style>

	<script>
	
			function generaexcel()
			{
				$().redirect('../cont/views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
				
			}
		$(document).ready(function(){
			<?php
			if(isset($_REQUEST['moneda'])){?>
				$("#moneda").val(<?php echo $_REQUEST['moneda'];?>).select2({width : "150px"});
				cuentasPorMoneda(<?php echo $_REQUEST['cuenta'];?>);
			<?php } ?>
			
			
		});
	</script>
	<body>
		<div class="iconos" id="ico">
                <table class="bh" align="right" border="0" >
                    <tr>            
                        <td width=16 align=right>
                            <a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
                        </td>
						<td width=16 align=right>
							<a href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png"  
							   title ="Enviar reporte por correo electrónico" border="0"> 
							</a>
						</td>
						<td width=16 align=right>
							<a href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif"  
							   title ="Generar reporte en PDF" border="0"> 
							</a>
						</td>
						<td width=16 align=right>
							<a href='javascript:generaexcel();' id='excel'><img src='images/images.jpg' width='35px'></a>
						</td>																				
                    </tr>
                </table>
            </div>	
		<div style="width:98%;background: #F2F2F2;" align="" class="container well" >
			
			<div class="panel panel-default" >
				<div class="panel-heading"  style="height: 46px;font-family: Courier;" align="center"><b style="font-size:25px;">Calendario Financiero</b></div> 
			</div>	
			<div class="panel-body" >
				<section>
					<form method="post" action="index.php?c=Calendario&f=vercalendario" id="form">
					<div class="row" style="" >
						<div class="col-md-5" style="background:#848484;color: #FAFAFA" align="center">
							<h4 align="center" style="">Cuenta(s)</h4><br>
							Moneda:
					
							<select id="moneda" name="moneda" style="width: 150px;" onchange="cuentasPorMoneda()">
								<option value="0" selected="">--Seleccione--</option>
								<?php while($moni = $moneda->fetch_array()){?>
								<option value="<?php echo $moni['coin_id']; ?>" ><?php echo $moni['description']?></option>
								<?php } ?>
							</select>
							<i class='fa fa-refresh fa-spin ' id="progres" style="display: none"></i>
						
							Cuenta:
						
							<select id="cuenta" name="cuenta"  style="150px"></select>
							<br><br>
						</div>
						<div class="col-md-2"></div> 
						<div class="col-md-5" style="background:#848484;color: #FAFAFA" align="center">
							<h4 align="center" style="">Fechas</h4><br>
							Del
							<input type="date" id="fechainicio" name="fechainicio" class="" style="width:150px;color: black " value="<?php echo @$_REQUEST['fechainicio'];?>"> 
							Al
							<input type="date" id="fechafin" name="fechafin" class="" style="width:150px;color: black " value="<?php echo @$_REQUEST['fechafin'];?>">
							<br><br>
						</div>
				</div>
				<div align="right">
					<button type="button" class="btn btn-primary" id="load" style="center"   data-loading-text="Consultando<i class='fa fa-refresh fa-spin '></i>">Generar Reporte</button>
				</div>
				</form>
			</section>
			</div>
<div id='imprimible'>
	<?php
			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo == 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
	?>					
			<div class="panel panel-default" >
				<div class="panel-heading"  style="height: 39px;" align="center"><b style="font-size: 16px;">Saldos</b></div> 
					<div class="panel-body" >
					<table width="100%" cellpadding="2" cellspacing="2">
						<thead>
							<tr style="background:#848484;font-family: Courier;font-weight: bold;color: #FAFAFA">
								<th>Saldos</th>
								<th>Inicial</th>
								<th>Ingresos</th>
								<th>Egresos</th>
								<th>Final</th>
								<th>No Depositados del Periodo</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="font-weight: bold;">Contable</td>
								<td><?php echo number_format($saldoInicialContable,2,'.',',');?></td>
								<td><?php echo number_format($ingresosBancarios+$ingresosTransito-$ingresosAntes,2,'.',',');?></td>
								<td><?php echo number_format($egresosBancarios+$egresosTransitoMes-$egresosAntes,2,'.',',');?></td>
								<td><?php echo number_format($saldoInicialContable+($ingresosBancarios+$ingresosTransito-$ingresosAntes)-($egresosBancarios+$egresosTransitoMes-$egresosAntes),2,'.',',');?></td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Bancario</td>
								<td><?php echo number_format($saldoInicialBancario,2,'.',',');?></td>
								<td><?php echo number_format($ingresosBancarios,2,'.',',');?></td>
								<td><?php echo number_format($egresosBancarios,2,'.',',');?></td>
								<td><?php echo number_format($saldoInicialBancario+$ingresosBancarios-$egresosBancarios,2,'.',',');?></td>
							</tr>
							<tr>
								<td style="font-weight: bold;">Transito Periodo + Cob/Dep P.Ant </td>
								<?php 
								//se le restan los que ya quedaron aplicados
								//porque sino los tomara como que aun estas sin depositar
								//pero ya estan depositados en el mes
								$saldoTransito = ($saldoInicialTransito)-($ingresosAntes-$egresosAntes);
								
								?>
								<td><?php echo number_format($saldoInicialTransito,2,'.',',');?></td>
								<td><?php echo number_format($ingresosAntes+$ingresosTransito,2,'.',',');?></td>
								<td><?php echo number_format($egresosAntes+$egresosTransitoMes,2,'.',',');?></td>
								<td><?php echo number_format($saldoInicialTransito+($ingresosAntes+$ingresosTransito)-($egresosAntes+$egresosTransitoMes),2,'.',',');?></td>
								<td></td>

								
							</tr>
							<tr>
								<td style="font-weight: bold;">En Transito del Periodo</td>
								<?php 
								//se le restan los que ya quedaron aplicados
								//porque sino los tomara como que aun estas sin depositar
								//pero ya estan depositados en el mes
								$saldoTransito = ($saldoInicialTransito)-($ingresosAntes-$egresosAntes);
								
								?>
								<td><?php echo number_format($saldoTransito,2,'.',',');?></td>
								<td><?php echo number_format($ingresosTransito,2,'.',',');?></td>
								<td><?php echo number_format($egresosTransitoMes,2,'.',',');?></td>
								<td><?php echo number_format($saldoTransito+$ingresosTransito-$egresosTransitoMes,2,'.',',');?></td>
								<td><?php echo number_format($Nodepositados,2,'.',',');?></td>

								
							</tr>
							<tr><td colspan="6"><hr></td></tr>
							<tr>
								<td style="font-weight: bold;">Proyectado(Appministra)</td>
								<td></td>
								<td><?php echo number_format( $totalIngresoscxc,2,'.',',');?></td>
								<td><?php echo number_format( $totalEgresoscxp ,2,'.',',');?></td>
								<td><?php echo number_format( $totalIngresoscxc - $totalEgresoscxp ,2,'.',',');?></td>
							</tr>
						</tbody>
					</table>
				
				</div>
			</div>	
			<div class="panel panel-default" >
				<div class="panel-heading"  style="height: 39px;background:#848484 " align="center"><b style="font-size: 16px;color: #FAFAFA">Documentos Bancarios</b></div> 
			</div>
			<div class="row">
				<div class="col-md-6">	
					<div class="panel panel-default" >
						<div class="panel-heading"  style="height:35px" align="center"><b style="font-size: 16px;">Ingresos</b></div> 
							<div class="panel-body" >
								<table class="table table-striped table-bordered">
									<thead style="background: #848484;color: #FAFAFA ">
										<th>Fecha</th>
										<th>Cuenta</th>
										<th>Banco</th>
										<th>Concepto</th>
										<th>Importe</th>
									</thead>
									<tbody>
										<?php $saldoIngresos = 0;
										foreach ($DocIngresos as $key=>$val) {$saldoIngresos+=$val['importe']; ?>
											<tr>
												<td><a  href="javascript:edicion('Ingresos',<?php echo $key;?>)"><?php echo $val['fecha'] ?></a></td>
												<td><a  href="javascript:edicion('Ingresos',<?php echo $key;?>)"><?php echo $val['cuenta'] ?></a></td>
												<td><a  href="javascript:edicion('Ingresos',<?php echo $key;?>)"><?php echo $val['banco'] ?></a></td>
												<td><a  href="javascript:edicion('Ingresos',<?php echo $key;?>)"><?php echo $val['concepto'] ?></a></td>
												<td align="right"><a  href="javascript:edicion('Ingresos',<?php echo $key;?>)"><?php echo number_format($val['importe'],2,'.',',') ?></a></td>
											</tr>
										<?php
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="3" align="right">Total Ingresos</td>
											<td colspan="2" align="right"><?php echo number_format($saldoIngresos,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
													

							</div>
							<div align="right">
								<button title="Nuevo Ingreso"  onclick="javascript:nuevo('Ingresos')">
								<span class="glyphicon-plus" ></span>
								</button>
							</div>
					</div>
				</div>
				<div class="col-md-6">	
					<div class="panel panel-default" >
					
						<div class="panel-heading"  style="height: 35px" align="center"><b style="font-size: 16px;">Egresos</b></div> 
						<div class="panel-body" style="overflow:scroll; height:300px;">
							<table class="table table-striped table-bordered">
									<thead style="background: #848484 ;color: #FAFAFA">
										<th>Fecha</th>
										<th>Cuenta</th>
										<th>Banco</th>
										<th>Concepto</th>
										<th>Importe</th>
									</thead>
									<tbody>
										<?php $totalEgresos = 0;
										foreach ($DocEgresos as $key=>$val) { $totalEgresos +=  $val['importe']; ?>
											<tr>
												<td><a  href="javascript:edicion('Egresos',<?php echo $key;?>)"><?php echo $val['fecha'] ?></a></td>
												<td><a  href="javascript:edicion('Egresos',<?php echo $key;?>)"><?php echo $val['cuenta'] ?></a></td>
												<td><a  href="javascript:edicion('Egresos',<?php echo $key;?>)"><?php echo $val['banco'] ?></a></td>
												<td><a  href="javascript:edicion('Egresos',<?php echo $key;?>)"><?php echo $val['concepto'] ?></a></td>
												<td align="right"><a  href="javascript:edicion('Egresos',<?php echo $key;?>)"><?php echo number_format($val['importe'],2,'.',',') ?></a></td>
											</tr>
										<?php
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="3" align="right">Total Egresos</td>
											<td colspan="2" align="right"><?php echo number_format($totalEgresos,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
						</div>
						<div align="right">
							<button title="Nuevo Egreso" onclick="javascript:nuevo('Egresos')">
							<span class="glyphicon-plus" ></span>
							</button>
						</div>
					</div>
					
				</div>
				
		</div>
		<div class="row">
				<div class="col-md-6">	
					<div class="panel panel-default" >
						<div class="panel-heading"  style="height: 35px" align="center"><b style="font-size: 16px;">Depositos</b></div> 
						<div class="panel-body" style="overflow:scroll; height:300px;">
							<table class="table table-striped table-bordered">
									<thead style="background: #848484 ;color: #FAFAFA">
										<th>Fecha</th>
										<th>Cuenta</th>
										<th>Banco</th>
										<th>Concepto</th>
										<th>Status</th>
										<th>Importe</th>
									</thead>
									<tbody>
										<?php $totalDepositos = 0;
										foreach ($DocDep as $key=>$val) { if($val['status']!="Sin Depositar"){$totalDepositos += $val['importe'];} ?>
											<tr>
												<td><a  href="javascript:edicion('depositos',<?php echo $key;?>)"><?php echo $val['fecha'] ?></a></td>
												<td><a  href="javascript:edicion('depositos',<?php echo $key;?>)"><?php echo $val['cuenta'] ?></a></td>
												<td><a  href="javascript:edicion('depositos',<?php echo $key;?>)"><?php echo $val['banco'] ?></a></td>
												<td><a  href="javascript:edicion('depositos',<?php echo $key;?>)"><?php echo $val['concepto'] ?></a></td>
												<td><a  href="javascript:edicion('depositos',<?php echo $key;?>)"><?php echo $val['status'] ?></a></td>
												<td align="right"><a  href="javascript:edicion('depositos',<?php echo $key;?>)"><?php echo number_format($val['importe'],2,'.',',') ?></a></td>
											</tr>
										<?php
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="4" align="right">Total Depositos</td>
											<td colspan="2" align="right"><?php echo number_format($totalDepositos,2,'.',',');?></td>
										</tr>
										<tr style="color: black;font-weight: bold;">
											<td colspan="4" align="right">Total Sin Depositar</td>
											<td colspan="2" align="right"><?php echo number_format($ingresosTransito,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
						</div>
						<div align="right">
							<button title="Nuevo Deposito" onclick="javascript:nuevo('depositos')">
							<span class="glyphicon-plus"  ></span>
							</button>
						</div>
					</div>
				</div>
				<div class="col-md-6">	
					<div class="panel panel-default" >
					
						<div class="panel-heading"  style="height: 35px" align="center"><b style="font-size: 16px;">Cheques</b></div> 
						<div class="panel-body" style="overflow:scroll; height:300px;">
							<table class="table table-striped table-bordered">
									<thead style="background: #848484;color: #FAFAFA ">
										<th>Fecha</th>
										<th>Cuenta</th>
										<th>Banco</th>
										<th>Concepto</th>
										<th>Status</th>
										<th>Proceso</th>
										<th>Importe</th>
										
									</thead>
									<tbody>
										<?php $totalCheques = $totalChequesSin =  0;
										foreach ($DocCheques as $key=>$val) {
											if($val['cobrado']=="Sin Cobrar"){
												$totalChequesSin += $val['importe'];
											}else{
												$totalCheques += $val['importe'];
											} ?>
											<tr title="Editar Documento">
												
												<td><a  href="javascript:edicion('Cheques',<?php echo $key;?>)"><?php echo $val['fecha'] ?></a></td>
												<td><a  href="javascript:edicion('Cheques',<?php echo $key;?>)"><?php echo $val['cuenta'] ?></a></td>
												<td><a  href="javascript:edicion('Cheques',<?php echo $key;?>)"><?php echo $val['banco'] ?></a></td>
												<td><a  href="javascript:edicion('Cheques',<?php echo $key;?>)"><?php echo $val['concepto'] ?></a></td>
												<td><a  href="javascript:edicion('Cheques',<?php echo $key;?>)"><?php echo $val['status'] ?></a></td>
												<td><a  href="javascript:edicion('Cheques',<?php echo $key;?>)"><?php echo $val['cobrado'] ?></a></td>
												<td align="right"><a  href="javascript:edicion('Cheques',<?php echo $key;?>)"><?php echo number_format($val['importe'],2,'.',','); ?></a></td>
												
											</tr>
										<?php
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="5" align="right">Total Cheques Cobrados</td>
											<td colspan="2" align="right"><?php echo number_format($totalCheques,2,'.',',');?></td>
										</tr>
										<tr style="color: black;font-weight: bold;">
											<td colspan="5" align="right">Total Cheques Sin Cobrar</td>
											<td colspan="2" align="right"><?php echo number_format($totalChequesSin,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
						</div>
						<div align="right">
							<button title="Nuevo Cheque" onclick="javascript:nuevo('Cheques')">
							<span class="glyphicon-plus"  ></span>
							</button>
						</div>
					</div>
				</div>
		</div>
		<div class="row">
				<div class="col-md-6">	
					<div class="panel panel-default" >
					
						<div class="panel-heading"  style="height: 35px" align="center"><b style="font-size: 16px;">Ingresos No Depositados</b></div> 
						<div class="panel-body" style="overflow:scroll; height:300px;">
							<table class="table table-striped table-bordered">
									<thead style="background: #848484;color: #FAFAFA ">
										<th>Fecha</th>
										<th>Cuenta</th>
										<th>Banco</th>
										<th>Concepto</th>
										<th>Importe</th>
									</thead>
									<tbody>
										<?php $totalIngresosNo = 0;
										foreach ($DocIngresosNoDep as $key=>$val) { $totalIngresosNo += $val['importe']; ?>
											<tr>
												<td> <a  href="javascript:edicion('IngresosP',<?php echo $key;?>)"><?php echo $val['fecha'] ?></a></td>
												<td><a  href="javascript:edicion('IngresosP',<?php echo $key;?>)"><?php echo $val['cuenta'] ?></a></td>
												<td><a  href="javascript:edicion('IngresosP',<?php echo $key;?>)"><?php echo $val['banco'] ?></a></td>
												<td><a  href="javascript:edicion('IngresosP',<?php echo $key;?>)"><?php echo $val['concepto'] ?></a></td>
												<td align="right"><a  href="javascript:edicion('IngresosP',<?php echo $key;?>)"><?php echo number_format($val['importe'],2,'.',',') ?></a></td>
											</tr>
										<?php
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="3" align="right">Total Ingresos No Depositados</td>
											<td colspan="2" align="right"><?php echo number_format($totalIngresosNo,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
						</div>
						<div align="right">
							<button title="Nuevo Ingreso No Depositado" onclick="javascript:nuevo('IngresosP')">
							<span class="glyphicon-plus" ></span>
							</button>
						</div>
						
					</div>
				</div>
				<div class="col-md-6">	
					<div class="panel panel-default" >
					
						<div class="panel-heading"  style="height: 35px" align="center"><b style="font-size: 16px;">Proyectados (Appministra)</b></div> 
							<b style="font-size: 16px;background: #E6E6E6">INGRESOS</b>

							<div class="panel-body" style="overflow:scroll; height:300px;" >
								<table class="table table-striped table-bordered">
									<thead >
										<tr><th style="text-align: center" colspan="3">Cargos por Cobrar</th></tr>
										<tr style="background: #848484;color: #FAFAFA ">
											<th>Fecha</th>
											<th>Concepto</th>
											<th>Saldo</th>
										</tr>
									</thead>
									<tbody>
										<?php $totalcI = 0;
										if($cargosIn){
											foreach ($cargosIngresos as $cI) {
												 $totalcI += $cI['saldo']; ?>
												<tr>
													<td><?php echo $cI['fecha_pago'];?></td>
													<td><?php echo $cI['concepto'];?></td>
													<td style="width: 50px" align="right"><?php echo number_format(($cI['saldo']),2,'.',',');?></td>
												</tr>
										<?php	
											}
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="2" align="right">Total Cargos por Cobrar</td>
											<td colspan="3" align="right"><?php echo number_format($totalcI,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
								<table class="table table-striped table-bordered">
									<thead >
										<tr><th style="text-align: center" colspan="4">Facturas por Cobrar</th></tr>
										<tr style="background: #848484;color: #FAFAFA ">
											<th>Fecha</th>
											<th>Folio</th>
											<th>Concepto</th>
											<th>Saldo</th>
										</tr>
									</thead>
									<tbody>
										<?php $totalfI = 0;
										if($facturasIn){
											foreach ($facturasIngresos as $fI) {
												
												 $totalfI += $fI['saldo']; ?>
													<tr>
														<td><?php echo $fI['fecha_factura'];?></td>
														<td><?php echo $fI['folio'];?></td>
														<td><?php echo $fI['desc_concepto'];?></td>
														<td style="width: 50px" align="right"><?php echo number_format(($fI['saldo']),2,'.',',');?></td>
													</tr>
										<?php 	
											}
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="3" align="right">Total Facturas por Cobrar</td>
											<td colspan="4" align="right"><?php echo number_format($totalfI,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
							</div>
							<!-- EGRESOS -->
							<b style="font-size: 16px;background: #E6E6E6">EGRESOS</b>

							<div class="panel-body" style="overflow:scroll; height:300px;" >

								<table class="table table-striped table-bordered">
									<thead >
										<tr><th style="text-align: center" colspan="3">Cargos por Pagar</th></tr>
										<tr style="background: #848484;color: #FAFAFA ">
											<th>Fecha</th>
											<th>Concepto</th>
											<th>Saldo</th>
										</tr>
									</thead>
									<tbody>
										<?php $totalcE = 0;
										if($cargosIn){
											foreach ($cargosEgresos as $cI ) {
												$totalcE += $cI['saldo']; ?>
												<tr>
													<td><?php echo $cI['fecha_pago'];?></td>
													<td><?php echo $cI['concepto'];?></td>
													<td style="width: 50px" align="right"><?php echo number_format(($cI['saldo']),2,'.',',');?></td>
												</tr>
										<?php	
											}
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="2" align="right">Total Cargos por Pagar</td>
											<td colspan="3" align="right"><?php echo number_format($totalcE,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
								<table class="table table-striped table-bordered">
									<thead >
										<tr><th style="text-align: center" colspan="4">Facturas por Pagar</th></tr>
										<tr style="background: #848484;color: #FAFAFA ">
											<th>Fecha</th>
											<th>Folio</th>
											<th>Concepto</th>
											<th>Saldo</th>
										</tr>
									</thead>
									<tbody>
										<?php $totalfE = 0;
										if($facturasIn){
											foreach ($facturasEgresos as $fE ) {
												
												 $totalfE += $fE['saldo']; ?>
													<tr>
														<td><?php echo $fE['fecha_factura'];?></td>
														<td><?php echo $fE['no_factura'];?></td>
														<td><?php echo $fE['desc_concepto'];?></td>
														<td style="width: 50px" align="right"><?php echo number_format(($fE['saldo']),2,'.',',');?></td>
													</tr>
										<?php 	
											}
										} ?>
									</tbody>
									<tfoot>
										<tr style="color: black;font-weight: bold;">
											<td colspan="3" align="right">Total Facturas por Pagar</td>
											<td colspan="4" align="right"><?php echo number_format($totalfE,2,'.',',');?></td>
										</tr>
									</tfoot>
								</table>
							</div>
					</div>
				</div>
		</div>	
		
			
			
			
			
</div>

		</div><!-- div principal -->
		
<!--GENERA PDF*************************************************-->
<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generar PDF</h4>
            </div>
            <form id="formpdf" action="../cont/libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
	            <div class="modal-body">
	                <div class="row">
	                	<div class="col-md-6">
	                		<label>Escala (%):</label>
							<select id="cmbescala" name="cmbescala" class="form-control">
								<?php
									for($i=100; $i > 0; $i--){
										echo '<option value='. $i .'>' . $i . '</option>';
									}
								?>
							</select>
	                	</div>
	                	<div class="col-md-6">
	                		<label>Orientación:</label>
	                		<select id="cmborientacion" name="cmborientacion" class="form-control">
								<option value='P'>Vertical</option>
								<option value='L'>Horizontal</option>
							</select>
	                	</div>
	                </div>
	                <textarea id="contenido" name="contenido" style="display:none"></textarea>
					<input type='hidden' name='tipoDocu' value='hg'>
					<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
					<input type='hidden' name='nombreDocu' value='Detalle Saldo Conciliacion'>
	            </div>
	            <div class="modal-footer">
	            	<div class="row">
	                    <div class="col-md-6">
	                    	<input type="submit" value="Crear PDF" autofocus class="btn btn-primary btnMenu">
	                    </div>
	                    <div class="col-md-6">
	                    	<input type="button" value="Cancelar" onclick="cancelar_pdf()" class="btn btn-danger btnMenu">
	                    </div>
	                </div>
	            </div>
	        </form>
        </div>
    </div>
</div>


<!--GENERA PDF*************************************************-->


			<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:2;">
			<div 
				id="divmsg"
				style="
					opacity:0.8;
					position:relative;
					background-color:#000;
					color:white;
					padding: 20px;
					-webkit-border-radius: 20px;
    				border-radius: 10px;
					left:-50%;
					top:-200px
				">
				<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...
				</center>
			</div>
			</div>
			<script>
				function cerrarloading(){
					$("#loading").fadeOut(0);
					var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
					$("#divmsg").html(divloading);
				}
			</script>
	</body>
	

</html>
