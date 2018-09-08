<link rel="stylesheet" media="screen" href="">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script>
	
	function generaexcel()
			{
				//$("#1,#2,#3,#4,#5,#6").attr("border","1px");
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
				//$("#1,#2,#3,#4,#5,#6").attr("border","0px");
			}
</script>

	<script language='javascript' src='js/pdfmail.js'></script>
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<style>

	#logo_empresa /*Logo en pdf*/
	{
		display:none;
	}

	#titulo_impresion
	{
		visibility: hidden;
	}

	@media print
	{
		body {-webkit-print-color-adjust: exact;}
		#imprimir,#filtros,#excel,#titulo,#email_icon,#pdfre,#impri
		{
			display:none;
		}
		#titulo_impresion
		{
			visibility:visible;
		}
		#sc
		{
			overflow: visible;
		}

		#logo_empresa
		{
			display:block;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imprimible{
			width: 100% !important;
		}
	}
	.border
	{
		border-color: #aaaaaa;
	   	border-width: 1px;
	   	border-style: solid;
	}
	.btnMenu{
      	border-radius: 0; 
      	width: 100%;
      	margin-bottom: 0.3em;
      	margin-top: 0.3em;
  	}
  	.row
  	{
      	margin-top: 0.5em !important;
  	}
  	h4, h3{
      	background-color: #eee;
      	padding: 0.4em;
  	}
  	.modal-title{
  		background-color: unset !important;
  		padding: unset !important;
  	}
  	.nmwatitles, [id="title"] {
      	padding: 8px 0 3px !important;
     	background-color: unset !important;
  	}
  	.select2-container{
      	width: 100% !important;
  	}
  	.select2-container .select2-choice{
      	background-image: unset !important;
     	height: 31px !important;
  	}
  	.twitter-typeahead{
  		width: 100% !important;
  	}
  	.tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
</style>
<script>
	<?php 
	if($finconciliacion==1){?>
		alert("Aun no Finaliza la conciliacion\nSolo puede ver el reporte de conciliaciones terminadas");
		window.location="index.php?c=conciliacionAcontia&f=verReporteConciliacion";
<?php	}?>
</script>

<!---------------------------------------------------------- -->
<div id="imprimible" style="display:none;">
	<div id="paraimp">
	<table id="datosempresa" width='100%'>
		<tr>
			<td width="50%">
				<?php
				
				
			$url = explode('/modulos',$_SERVER['REQUEST_URI']);
			if($logo== 'logo.png') $logo = 'x.png';
			$logo = str_replace(' ', '%20', $logo);
			?>
			<!--img id='logo_empresa' src='<?php // echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' height='55'-->
			</td>
			<td valign="top" width='50%' style="font-size:7px;text-align:right;color:gray;">
			<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b><br>
			</td>
		</tr>
		<tr style="color:#576370;text-align:center;">
			<td colspan=2>
				<b style="font-size:18px;color:black;"><?php echo $empresa; ?></b><br>
				<b style="">Conciliacion Bancaria (Detalle de Saldos)</b><br>   
				Ejercicio <b><?php echo $nombreejercicio; ?></b> Periodo <b><?php echo $periodoreporte; ?></b> <br>
				Cuenta Bancaria: <span id='' style='font-weight:bold;'><?php echo  $infocuentas['nombre'];?></span> 
				Numero de Cuenta: <span id='' style='font-weight:bold;'><?php echo  $infocuentas['cuenta'];?></span> 
				<br><br>
			</td>
		</tr>
	</table><br><br>
	<!-- Cheques en Circulacion -->
	<fieldset>
	<legend>M O V I M I E N T O S&nbsp;&nbsp;&nbsp; B A N C O S</legend>
	<br>
	<table border=0 style='width:97%;font-size:12px;' cellpadding="3" id="1" class="border tbl1">
		<thead>
			<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
				<th colspan="3" align="center"><b>Cheques en Circulacion</b></th>
			</tr>
			<tr style="background-color:#e4e7ea;font-size:13px;font-weight:bold;height:30px;text-align:left;font-size: 15px">
				<td><b>Fecha</b></td>
				<td><b>Numero</b></td>
				<td align="right"><b>Importe</b></td>
			</tr>
		</thead>
	
		<tbody>
	<?php $sumcheques =0;
			 while ($c = $chequescircula->fetch_assoc()){
			 	if($c['formapago']==2 || $c['TipoMovto']=="Abono"){
			 		$sumcheques+=$c['Importe']; ?>
				
				<tr>
					<td ><?php echo $c['fecha'];?></td>
					<td ><?php echo $c['numero'];?></td>
					<td align="right"  class="number"><?php echo number_format($c['Importe'],2,'.',',');?></td>
				</tr>
		<?php  }
			}?>
		</tbody>
	
		<tfoot>
			<tr style="background-color:#e4e7ea;font-size:13px;font-weight:bold;height:30px;text-align:right;">
				<td></td><td><b>TOTAL</b></td>
				<td id="totalcheque2" align="right" class="number"><?php echo number_format($sumcheques,2,'.',','); ?></td>
				
			</tr>
		</tfoot>
	</table><br><br>
 	<!-- fin Cheques en Circulacion -->
	<!-- Nuestros Depositos -->
	<table border=0 style='width:97%;font-size:12px;' cellpadding="3" id="2" class="border tbl2">
	<thead>
		<tr  style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
			<th colspan="3" align="center"><b>Nuestros Depositos</b></th>
		</tr>
		<tr style="background-color:#e4e7ea;font-size:13px;font-weight:bold;height:30px;text-align:left;">
			<td><b>Fecha</b></td>
			<td><b>Concepto</b></td>
			<td align="right"><b>Importe</b></td>
		</tr>
	</thead>

		<tbody>
	<?php $depositossum = 0; 
	while($c = $depositos->fetch_assoc()){
			if($c['TipoMovto']=="Cargo"){ $depositossum += $c['Importe'];?>
				
				<tr>
					<td><?php echo $c['fecha'];?></td>
					<td><?php echo $c['concepto'];?></td>
					<td align="right" class="number"><?php echo number_format($c['Importe'],2,'.',',');?></td>
				</tr>
		<?php } 
			}?>
	
	<tfoot>
		<tr style="background-color:#e4e7ea;font-size:13px;font-weight:bold;height:30px;text-align:right;">
			<td></td><td><b>TOTAL</b></td>
			<td id="totalmideposito2" align="right" class="number"><?php echo number_format($depositossum,2,'.',',');?></td>
			
		</tr>
	</tfoot>
	</table><br><br>
	 <!-- fin Nuestros Depositos -->
	<table border=0 style='width:97%;font-size:12px;' cellpadding="3" id="3" class="border tbl3">
	<tbody>
		<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
			<td colspan="2"><b>Saldo Bancos</b></td>
			<td id="saldoEstadoCuenta" align="right"><?php echo number_format($saldoEstadoCuenta,2,'.',','); ?></td>
		</tr>
		<tr style="background-color:#e4e7ea;font-size:13px;font-weight:bold;height:30px;">
			<td colspan="2">
				(-)Cheques en Circulación
			</td>
			<td id="chequessaldo2" align="right" class="number"><?php echo number_format($sumcheques,2,'.',',');?></td>
		</tr>
		<tr style="background-color:#e4e7ea;font-weight:bold;height:30px;">
			<td colspan="2">
				(+)Nuestros Deposito 
			</td>
			<td id="depositossaldo2" align="right" class="number"><?php echo number_format($depositossum,2,'.',',');?></td>
		</tr>
	</tbody>
	<tfoot>
		<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
			<td colspan="2"><b>Saldos Iguales<b></td>
			<td id="saldobancototal2" align="right"><?php echo number_format($saldoEstadoCuenta-$sumcheques+$depositossum,2,'.',',');?></td>
		</tr>
	</tfoot>
	</table><br><br>
	</fieldset><br><br>
	<!-- Cargos del Banco -->
	<fieldset>
	<legend>M O V I M I E N T O S&nbsp;&nbsp;&nbsp; L I B R O S</legend>
	<br>
	<table border=0 style='width:97%;' cellpadding="3" id="4" class="border tbl4">
	<thead>
		<tr  style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
			<th colspan="3" align="center"><b>Cargos del Banco</b></th>
		</tr>
		<tr style="background-color:#e4e7ea;font-weight:bold;height:30px;text-align:left;">
			<td ><b>Fecha</b></td>
			<td ><b>Concepto</b></td>
			<td align="right" ><b>Importe</b></td>
		</tr>
	</thead>

			<tbody>
			
			</tbody>
		<tfoot>
		<tr style="background-color:#e4e7ea;font-weight:bold;height:30px;text-align:right;">
			<td ></td><td><b>TOTAL</b></td>
			<td id="totalcargosbanco2" align="right" class="number">0.00</td>
		</tr>
		</tfoot>
	</table><br><br>
	<!-- fin Cargos del Banco -->
	<!-- Depositos del Banco -->
	<table border=0 style='width:97%;' cellpadding="3" id="6" class="border tbl6">
		<thead>
			<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
				<th colspan="3" align="center"><b>Depositos del Banco</b></th>
			</tr>
			<tr style="background-color:#e4e7ea;font-weight:bold;height:30px;text-align:left;">
				<td><b>Fecha</b></td>
				<td><b>Concepto</b></td>
				<td align="right"><b>Importe</b></td>
			</tr>
		</thead>
	
			<tbody>
				
			</tbody>
		
		<tfoot>
			<tr style="background-color:#e4e7ea;font-weight:bold;height:30px;text-align:right;">
				<td ></td><td><b>TOTAL</b></td>
				<td id="bancodepositostotal2" align="right" class="number">0.00</td>
			</tr>
		</tfoot>
	</table><br><br>
	<!-- fin Depositos del Banco -->
	<table border=0 style='width:97%;font-size:13px;' cellpadding="3" id="5" class="border tbl5">
		<tr  style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
			<td colspan="2"><b>Nuestro Saldo</b></td>
			<td id="saldoEmpresa" align="right"><?php echo number_format($saldoEmpresa,2,'.',','); ?></td>
		</tr>
		<tr style="background-color:#e4e7ea;font-weight:bold;height:30px;">
			<td colspan="2">
				(-) Cargos Bancos
			</td>
			<td id="totalcargosbancosaldo2" align="right" class="number">0.00</td>
		</tr>
		<tr style="background-color:#e4e7ea;font-weight:bold;height:30px;">
			<td colspan="2">
				(+)Depositos Bancos
			</td>
			<td id="totalbancodepositosaldo2" align="right" class="number">0.00</td>
		</tr>
		<tr style="background-color:#6E6E6E;color:white;font-weight:bold;height:30px;">
			<td colspan="2"><b>Saldos Iguales</b></td>
			<td id="totalnuestrosaldo2" align="right" class="number"><?php echo number_format($saldoEmpresa,2,'.',',');?></td>
		</tr>
	</tr>
	
</table>
</fieldset>
</div>
</div>

<!------------------------------------------------------- -->

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Conciliacion Bancaria<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
					<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0" id="pdfre"></a>   
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" id="impri" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=conciliacionAcontia&f=verReporteConciliacion' id='filtros'><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>	
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<section >
						<section >
							<section >
								<div class="row">
									<?php
									$url = explode('/modulos',$_SERVER['REQUEST_URI']);
									if($logo== 'logo.png') $logo = 'x.png';
									$logo = str_replace(' ', '%20', $logo);
									?>
									<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
										<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 col-sm-12" style='text-align:center;font-size:18px;'>
										<b style='text-align:center;'><?php echo $empresa;?></b>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
										<b style="">Conciliacion Bancaria (Detalle de Saldos)</b><br>   
										Ejercicio <b><?php echo $nombreejercicio; ?></b> Periodo <b><?php echo $periodoreporte; ?></b> <br>
										Cuenta Bancaria: <span id='' style='font-weight:bold;'><?php echo  $infocuentas['nombre'];?></span> 
										Numero de Cuenta: <span id='' style='font-weight:bold;'><?php echo  $infocuentas['cuenta'];?></span> 
									</div>
								</div>
							</section>
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<h4>Movimientos Bancos</h4>
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<!-- Cheques en Circulacion -->
												<table border=0 style='font-size:12px;' cellpadding="3" class="table tbln1">
													<script type="text/javascript">
														$(".tbln1:first").html($(".tbl1:first").html());
													</script>
												</table><br>
											 	<!-- fin Cheques en Circulacion -->
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<h4>Movimientos Libros</h4>
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<!-- Cargos del Banco -->
												<table border=0 cellpadding="3"  class="table tbln4">
													<script type="text/javascript">
														$(".tbln4:first").html($(".tbl4:first").html());
													</script>
												</table><br>
												<!-- fin Cargos del Banco -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<!-- Nuestros Depositos -->
												<table border=0 style='font-size:12px;' cellpadding="3" class="table tbln2">
													<script type="text/javascript">
														$(".tbln2:first").html($(".tbl2:first").html());
													</script>
												</table><br>
												<!-- fin Nuestros Depositos -->
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<!-- Depositos del Banco -->
												<table border=0 cellpadding="3" class="table tbln6">
													<script type="text/javascript">
														$(".tbln6:first").html($(".tbl6:first").html());
													</script>
												</table><br>
												<!-- fin Depositos del Banco -->
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table border=0 style='font-size:12px;' cellpadding="3" class="table tbln3">
													<script type="text/javascript">
														$(".tbln3:first").html($(".tbl3:first").html());
													</script>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table border=0 style='font-size:13px;' cellpadding="3" class="table tbln5">
													<script type="text/javascript">
														$(".tbln5:first").html($(".tbl5:first").html());
													</script>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>

<!--GENERA PDF*************************************************-->
<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generar PDF</h4>
            </div>
            <form id="formpdf" action="libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
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