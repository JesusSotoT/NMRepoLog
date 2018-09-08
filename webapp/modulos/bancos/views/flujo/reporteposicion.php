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
					<a href="index.php?c=Flujo&f=verposicion"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" 
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
					<div class="panel-heading"  style="height: 130px">
						<h3>Posición Bancaria Concentrada</h3>
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
							?></b>

						</div> 
						<div class="panel-body" >


							<table class="table table-striped table-bordered"  id="global"  width="100%">
								<thead>
									<tr>
										<th>Cuenta</th>
										<th>Nombre</th>
										<th>Saldo Inicial</th>
										<th>Ingresos(+)</th>
										<th>Egresos(-)</th>
										<th>Saldo Final</th>
										<th>Ingresos en Transito(+)</th>
										<th>Egresos en Transito(-)</th>
										<th>Saldo Contable</th>
										<th>Importe en Transito</th>
										<th>Ingresos No depositados del Periodo</th>
									</tr>

								</thead>
								<tbody>	
									<?php 

									foreach($infocuenta as $val){ ?>
									<tr>
										<td><?php echo $val['numcuenta']; ?></td>
										<td><?php echo $val['nombre']; ?></td>
										<td align="right"><?php echo number_format($val['saldoinicial'],2,'.',','); ?></td>
										<td align="right"><?php echo number_format($val['ingresos'],2,'.',','); ?></td>
										<td align="right"><?php echo number_format($val['egresos'],2,'.',','); ?></td>
										<td align="right"><?php echo number_format($val['saldoinicial']+$val['ingresos']-$val['egresos'],2,'.',','); ?></td>
										<td align="right"><?php echo number_format($val['ingresostransito'],2,'.',','); ?></td>
										<td align="right"><?php echo number_format($val['egresostransitoactual'],2,'.',','); ?></td>
										<td align="right"><?php echo number_format($val['saldofinal'],2,'.',','); ?></td>
										<td align="right"><?php echo number_format(($val['ingresostransito']-$val['egresostransitoactual']),2,'.',','); ?></td>
										<td align="right"><?php echo number_format($val['ingresosproyectados'],2,'.',','); ?></td>

									</tr>

									<?php }

									?>

								</tbody>
							</table>	
						</div>
					</div>
				</div>
			</div>
			<!-- imprimible -->
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