<?php
	if(!isset($_SESSION))
    	session_start();
?>
<!doctype html>
<html>
	<head>
		<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
		<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<script src="../../../punto_venta/js/ui.datepicker-es-MX.js"></script>
		<script type="text/javascript" src="../../../punto_venta/js/jquery.numeric.js"></script>
		<script src="../../js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<script type="text/javascript" src="../../../punto_venta/js/corte_caja.js"></script>
		<script type="text/javasctipt" src="../../../punto_venta/js/paginaciongrid.js"></script>
		<!--Script del autocompletado del campo de productos !-->
		<script>
			$.fn.disable = function() {
				return this.each(function() {          
					if (typeof this.disabled != "undefined")
					{
						$(this).data('jquery.disabled', this.disabled);
						this.disabled = true;
					}
				});
			};
			
			$.fn.enable = function() {
				return this.each(function() {
					if (typeof this.disabled != "undefined")
					{
						this.disabled = $(this).data('jquery.disabled');
					}
				});
			};
			$(document).ready(function() {
				$(".numeric").numeric({ precision: 12, scale: 2 });
				$(".throwback").click(function(){
					window.location="../caja/listado_cortes.php";
				});
			});
			function pdf(user)
			{
				//Plugg in que envia gran cantidad de datos en un post o get
				//Nota---Se modifico el plugg in (Ivan Cuenca) para que se habra en una pagina emergente _blank
				//Documentacion: http://www.avramovic.info/razno/jquery/redirect/
				$().redirect('pdf.php', {'cont': $('#topdf').html(), 'name': user});		
			}
		</script>
		<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
		<?php date_default_timezone_set("Mexico/General"); $hoy = getdate();?>
	</head>
	<body>
		<div id='formulario'>
			<input id='id_corte' 
			<?php echo (isset($_REQUEST['id'])) ? "value='".$_REQUEST['id']."' " : "value='NULL' ";?>
			type='hidden'>
			<div id='registro_nuevo'>
				<div class='tipo'>
				    <a href='javascript:window.print();'>
				    <img border='0' src='../../../../netwarelog/repolog/img/impresora.png'></a>
				    <a href='javascript:pdf("Corte de Caja")' style="display:none" id='pdflink'>
				    	<img src='../../../../netwarelog/repolog/img/pdf.gif' title='Generar PDF' id='dopdf'>
				    </a>
				    <b>Corte de caja</b>
				</div>
			    <br>
			</div>
			<div style='width: 90%; text-align: right'><input class='throwback' type='button' value='Regresar al listado'></div>
			<div class='campos' style='width: 90%;'>	
			
			<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

			<div class='listadofila' title='Filtros' style='padding: 10px; width: 90%; text-align: left;'>	
				<div style='width: 350px;'>
					<div id='notifica_fecha_div' style='height: 15px;'></div>
						<div>
							<?php
							if(isset($_REQUEST['f_ini']) && isset($_REQUEST['f_fin']))
							{
							?>
								<table border=0>
									<tr>
										<td>
											<b>
												<label>Desde: </label>
											</b>
											<br>
											<input id='fecha_inicio' type='text' style='width: 100%;' readonly value='<?php echo $_REQUEST['f_ini'];?>'>
										</td>
										<td>
											<b>
												<label>Hasta: </label>
											</b>
											<br>
											<input id='fecha_fin' type='text' style='width: 100%;' readonly value='<?php echo $_REQUEST['f_fin'];?>'>
										</td>
									</tr>
								</table>	
								</div>
							<?php
							}
							else
							{
							?>
								<div title='Inicio'  style=' width: 50%; padding: 10px; '>
									<b>
										<label>Inicio desde: </label>
									</b>
									<br>
									<input id='fecha_inicio' type='text' style='width: 100%;' readonly>
								</div>
								<div title='Fin'     style=' width: 50%; padding: 10px; '>
									<b>
										<label>Hasta: </label>
									</b>
									<br>
									<input id='fecha_fin' type='text' style='width: 100%;' readonly value='<?php echo $hoy['year']."-".$hoy['mon']."-".$hoy['mday']." ".$hoy['hours'].":".$hoy['minutes'].":".$hoy['seconds'];?>'>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>		
				<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
				<div id='topdf'><!--Inicia zona imprimible en pdf-->
					<div class='campos' style='width: 90%;'>	
					
					<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->

					<div class='listadofila' title='Filtros' id='print_pdf' style='padding: 10px; width: 90%; text-align: left; display:none'>	
						<div style='width: 350px;'>
							<div id='notifica_fecha_div' style='height: 15px;'></div>
						<div>
							<?php
							if(isset($_REQUEST['f_ini']) && isset($_REQUEST['f_fin']))
							{
							?>
								<table border=1>
									<tr>
										<td>
											<b>
												<label>Desde: </label>
											</b>
											<br>
											<label id='fecha_inicio'>
												<?php echo $_REQUEST['f_ini'];?>
											</label>
										</td>
										<td>
											<b>
												<label>Hasta: </label>
											</b>
											<br>
											<label id='fecha_fin'>
												<?php echo $_REQUEST['f_fin'];?>
											</label>
										</td>
									</tr>
								</table>
								</div>
							<?php
							}
							else
							{
							?>
								<div title='Inicio'  style=' width: 50%; padding: 10px; '>
									<b>
										<label>Filtrar desde: </label>
									</b>
									<br>
									<label id='fecha_inicio'>
								</div>
								<div title='Fin' style=' width: 50%; padding: 10px; '>
									<b>
										<label>Hasta: </label>
									</b>
									<br>
									<input id='fecha_fin'>
										<?php echo $hoy['year']."-".$hoy['mon']."-".$hoy['mday']." ".$hoy['hours'].":".$hoy['minutes'].":".$hoy['seconds'];?>
										</label>
								</div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
			 	<div class='listadofila' title='Corte de caja' 	style='padding: 10px; padding-bottom: 15px; width: 95%; text-align: left; border-top: 1px solid #006efe;'>
					<div id='aviso_canceladas'></div>
					<div title='Pagos'     	style='width: 100%; padding-right: 10px; '>
						<h3>Pagos: </h3>
						<table id='pagos_div' cellpadding='0' cellspacing='0' width='100%' style='width: 100%; font-size: 12px;'></table>
					</div>
					<div title='Productos'	style='width: 100%; padding-right: 10px; '>
						<h3>Productos: </h3>
						<table id='productos_div' cellpadding='0' cellspacing='0' width='100%' style='width: 100%; font-size: 12px;'></table>
					</div>
				</div>
				
			</div><!--Cierra el div de impresion pdf-->
			<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
			<div class='listadofila' title='Resumen'  style='padding: 10px; width: 80%; text-align: left;" id="saldos_div'>	
				<p>
					<div style='width:70%; display: table;'>
						<div style=' width: 50%'>
							<div title='Saldo inicial de caja'  		style='width: 80%;'>
								<label>Saldo inicial de caja: </label>
								<br>
								$
								<input id='saldo_inicial' type='text' class='numeric' style='width: 95%;' maxlength='10' readonly>
							</div>
							<p>
								<div title='Monto de ventas en el periodo'  style='width: 80%;'>
									<label>Monto de ventas en el periodo: </label>
									<br>
									$
									<input id='monto_ventas' type='text' class='numeric' style='width: 95%;' maxlength='10' readonly>
								</div>
							</p>
							<p>
								<div title='Saldo disponible en caja'  		style='width: 80%;'>
									<label>Saldo disponible en caja: </label>
									<br>
									$
									<input id='saldo_disponible' type='text' class='numeric' style='width: 95%;' maxlength='10' readonly>
								</div>
							</p>
						</div>
						<div style=' width: 50%'>
							<div title='Retiro de caja'  	style='width: 80%;'>
								<label>Retiro de caja: </label>
								<br>
								$
								<input id='retiro_caja' type='text' class='numeric' style='width: 95%; background-color: #FFCCDD;' maxlength='10'>
							</div>
							<p>
								<div title='Deposito de caja'	style='width: 80%;'>
									<label>Depósito de caja: </label>
									<br>
									$
									<input id='deposito_caja' type='text' class='numeric' style='width: 95%; background-color: #A9F5A9;' maxlength='10'>
								</div>
							</p>
						</div>
					</div>	
				</p>
			</div>	

			<!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////// !-->
			<div id='alerta_guardar' style='width: 80%;'></div>
			<div style='text-align: right; width: 80%' id='btn_guardar'>
				<input id='send' type='button' value='Guardar' onClick='guardarCorte()' />
			</div>
			<br>
			<br>
			<br>
			<br>
			<br>
		</div>
	</body>
</html>
