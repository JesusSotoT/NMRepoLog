<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="../../libraries/dataTable/js/datatables.min.js" type="text/javascript"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<script language='javascript' src='../cont/js/pdfmail.js'></script>
	<script src="../cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script type="text/javascript" src='js/calendario.js'></script>

	
	
</head>
<script>
	$(document).ready(function() {
				//$('#global').DataTable();
			} );
	function generaexcel()
	{
		$("#padre").css("background","#D8D8D8");
		$("#ico").hide();
		$().redirect('../cont/views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
		$("#ico").show();
		$("#padre").css("background","#848484");
	}
	function antesmail(){
		$("#ico").hide();
		$("#padre").css("background","#D8D8D8");
		mail();
		$("#ico").show();
		$("#padre").css("background","#848484");
	}
	function antespdf(){
		$("#padre").css("background","#D8D8D8");
		$("#ico").hide();
		pdf();
		$("#ico").show();
		$("#padre").css("background","#848484");
	}
</script>
<style>
	

	@media print
	{
		#global,#global2
		{
			
		}
		#ico,#filtros,#excel
		{
			display:none;
		}
		#padre{
			background:#D8D8D8;
		}
		
	}

</style>
<body>
	<div class="iconos" id="ico">
		<table class="bh" align="right" border="0" >
			<tr>            
				<td width=16 align=right>
					<a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
				</td>
				<td width=16  align=right>
					<a href="index.php?c=Flujo&f=verauxiliar"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" 
						title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
					</td>                        
					<td width=16 align=right>
						<a href="javascript:antesmail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png"  
							title ="Enviar reporte por correo electrónico" border="0"> 
						</a>
					</td>
					<td width=16 align=right>
						<a href="javascript:antespdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif"  
							title ="Generar reporte en PDF" border="0"> 
						</a>
					</td>
					<td width=16 align=right>
						<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
					</td>																				
				</tr>
			</table>
		</div><br><br>
		<div id='imprimible'>
			<?php
			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo == 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
			?>
			<div style="width:90%;background: #848484;" align="center" class="container well" id="padre">	
				<div class="panel panel-default" >
					<div class="panel-heading"  style="height: 135px">
						<h3>Auxiliar por Beneficiario / Pagador</h3>
						
						<?php
						if(	$_REQUEST['cuenta']	!=	0 ){
							echo "<b>".$banco."</b> Num.Cuenta(".$cuenta.")";
						}else{
							echo "<b>Todas las cuentas</b>";
						}?><br>
						<b style="font-family: Courier">Del <?php
							echo $_REQUEST['fechainicio'];
							?>
							Al <?php
							echo $_REQUEST['fechafin'];
							?></b><br>
							<?php
							if($_REQUEST['proyectados']==1){?>
							/<b style="font-family: cursive;font-size: 11px;">Incluidos No Depositados del Periodo</b> /
							<?php	}if($_REQUEST['cobrados']==0){?>
							/<b style="font-family: cursive;font-size: 11px;">Incluidos Cheques No Cobrados</b> /
							<?php	}
							
							?>
							
						</div> 
						<div class="panel-body" >		
							<table class="table table-striped table-bordered"  id="global"  width="100%">
								<thead>
									<tr>
										<th align="center">Fecha</th>
										<th align="center">Tipo Documento</th>
										<th align="center">No.Documento</th>
										<th align="center">Banco(Cuenta)</th>
										<th align="center">Concepto</th>
										<th align="center">Importe</th>
									</tr>
								</thead>
								<tbody>	
									<?php 
									$global = $globalnodep = $cont = 0;
									foreach($infobene as $key=>$datos){
										?>
										<tr><td colspan="6" align="left"><b style="font-size: 15px"><?php echo strtoupper($datos['nombrebene']);?></b></td></tr>
										<?php
										$ingresos = 0;
										if($DocIngresos[$key] ){
											foreach($DocIngresos[$key] as $key2=>$val){ $ingresos+=$val['importe']; ?>
											<tr>
												<td align="center"><?php echo $val['fecha'] ?></td>
												<td align="center">Ingreso</td>
												<td align="center"><a title="Ir a Documento" href="javascript:edicion('Ingresos',<?php echo $key2;?>)"><?php echo $val['numdoc']; ?></a></td>
												<td align="center"><?php echo $val['banco']."(".$val['cuenta'].")"; ?></td>
												<td align="center"><?php echo $val['concepto']; ?></td>
												<td align="right"><?php echo number_format($val['importe'],2,'.',','); ?></td>
											</tr>
											
											<?php }?>
											<tr><td colspan="5" align="right"><b>Total Ingresos</b></td><td align="right"><b><?php echo number_format($ingresos,2,'.',',');?></b></td></tr>
											<?php
										}
										$ingresosno = 0;
										if($DocIngresosNoDep[$key] ){
											foreach($DocIngresosNoDep[$key] as $key2=>$val){ $ingresosno+=$val['importe']; ?>
											<tr>
												<td align="center"><?php echo $val['fecha'] ?></td>
												<td align="center">Ingreso</td>
												<td align="center"><a title="Ir a Documento" href="javascript:edicion('IngresosP',<?php echo $key2;?>)"><?php echo $val['numdoc']; ?></a></td>
												<td align="center"><?php echo $val['banco']."(".$val['cuenta'].")"; ?></td>
												<td align="center"><?php echo $val['concepto']; ?></td>
												<td align="right"><?php echo number_format($val['importe'],2,'.',','); ?></td>
											</tr>
											
											<?php } $globalnodep+= $ingresosno;?>
											<tr><td colspan="5" align="right"><b>Total Ingresos No Dep.</b></td><td align="right"><b><?php echo number_format($ingresosno,2,'.',',');?></b></td></tr>
											<?php
										}
										$depositos = 0;
										if($DocDep[$key]){
											foreach($DocDep[$key] as $key2=>$val){ $depositos+=$val['importe']; ?>
											<tr>
												<td align="center"><?php echo $val['fecha'] ?></td>
												<td align="center">Depositos</td>
												<td align="center"><a title="Ir a Documento" href="javascript:edicion('depositos',<?php echo $key2;?>)"><?php echo $val['numdoc']; ?></a></td>
												<td align="center"><?php echo $val['banco']."(".$val['cuenta'].")"; ?></td>
												<td align="center"><?php echo $val['concepto']; ?></td>
												<td align="right"><?php echo number_format($val['importe'],2,'.',','); ?></td>
											</tr>
											
											<?php }?>
											<tr><td colspan="5" align="right"><b>Total Depositos</b></td><td align="right"><b><?php echo number_format($depositos,2,'.',',');?></b></td></tr>
											<?php
										}
										$cheques = 0;
										if($DocCheques[$key]){
											foreach($DocCheques[$key] as $key2=>$val){ $cheques+=$val['importe']; ?>
											<tr>
												<td align="center"><?php echo $val['fecha'] ?></td>
												<td align="center">Cheque</td>
												<td align="center"><a title="Ir a Documento" href="javascript:edicion('Cheques',<?php echo $key2;?>)"><?php echo $val['numdoc']; ?></a></td>
												<td align="center"><?php echo $val['banco']."(".$val['cuenta'].")"; ?></td>
												<td align="center"><?php echo $val['concepto']; ?></td>
												<td align="right"><?php echo number_format($val['importe'],2,'.',','); ?></td>
											</tr>
											
											<?php }?>
											<tr><td colspan="5" align="right"><b>Total Cheques</b></td><td align="right"><b><?php echo number_format($cheques,2,'.',',');?></b></td></tr>
											<?php
										}
										$egresos = 0;
										if($DocEgresos[$key]){
											foreach($DocEgresos[$key] as $key2=>$val){ $egresos+=$val['importe']; ?>
											<tr>
												<td align="center"><?php echo $val['fecha'] ?></td>
												<td align="center">Egreso</td>
												<td align="center"><a title="Ir a Documento" href="javascript:edicion('Egresos',<?php echo $key2;?>)"><?php echo $val['numdoc']; ?></a></td>
												<td align="center"><?php echo $val['banco']."(".$val['cuenta'].")"; ?></td>
												<td align="center"><?php echo $val['concepto']; ?></td>
												<td align="right"><?php echo number_format($val['importe'],2,'.',','); ?></td>
											</tr>
											
											<?php }?>
											<tr><td colspan="5" align="right"><b>Total Egresos</b></td><td align="right"><b><?php echo number_format($egresos,2,'.',',');?></b></td></tr>
											<?php
										} $global+=($ingresos + $depositos)-($egresos + $cheques);  $total=($ingresos + $depositos)-($egresos + $cheques); if($total<0){ $cont = "style='color:red'";}else{$cont="";}?>
										<tr class="text-info"><td colspan="5" align="right"><b style="font-size: 14px">Total Beneficiario / Pagador</b></td><td align="right" <?php echo $cont;?>><b style="font-size: 16px"><?php echo number_format(($ingresos + $depositos)-($egresos + $cheques),2,'.',',');?></b></td></tr>

										<?php $cont++;
									}	 
									if($global<0){ $color = "style='color:red'";}else{$color="";}	 ?>
									<tr style="background: #808080;color:white"><td colspan="5" align="right" style="font-size: 20px"><b >TOTAL GLOBAL</b></td><td align="right" style="font-size: 20px;<?php echo $color;?>" ><b><?php echo number_format($global,2,'.',',');?></b></td></tr>
									<?php if($_REQUEST['proyectados']==1){?>
									<tr style="background: #808080;color:white"><td colspan="5" align="right" style="font-size: 20px"><b>TOTAL (NO DEPOSITADOS)</b></td><td align="right" style="font-size: 20px"><b><?php echo number_format($globalnodep,2,'.',',');?></b></td></tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div><!-- imprimible -->
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
			<!-- MAIL -->
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
				top:-30%
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