<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript'>
$(document).ready(function()
{
	$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};

	$(".Resultados").remove();
	var cantidad = 0
	$("td[tipo='Pasivo'],td[tipo='CapitalContable']").each(function()
	{
		cantidad += parseFloat($(this).attr('cantidad'))
	});
	$("#tpcc").html("$ "+cantidad.format()).attr('cantidad',cantidad.toFixed(2))
	cantidad = 0
	$("td[tipo='PasivoAnterior'],td[tipo='CapitalContableAnterior']").each(function()
	{
		cantidad += parseFloat($(this).attr('cantidad'))
	});
	$("#tpccAnterior").html("$ "+cantidad.format()).attr('cantidad',cantidad.toFixed(2))
	$("td[tipo='Nada']").parent().remove()
	if(parseFloat($("#tpccAnterior").attr('cantidad'))!=parseFloat($("#activo_anterior").attr('cantidad')) && $("#activo_anterior").length>0)
		alert("Los saldos del mes pasado no cuadran, revise que las polizas esten cuadradas o que las cuentas tengan asignadas las NIF.")

	if(parseFloat($("#tpcc").attr('cantidad'))!=parseFloat($("#activo_actual").attr('cantidad')) && $("#activo_anterior").length>0)
		alert("Los saldos del mes no cuadran, revise que las polizas esten cuadradas o que las cuentas tengan asignadas las NIF.")

});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}		
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>
	.tit_tabla_buscar td
	{
		font-size:medium;
	}

	#logo_empresa /*Logo en pdf*/
	{
		display:none;
	}

	@media print
	{
		#imprimir,#filtros,#excel,#email_icon, #botones
		{
			display:none;
		}

		#logo_empresa
		{
			display:block;
		}

		.table-responsive{
			overflow-x: unset;
		}
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
    .table tr, .table td{
		border: none !important;
	}
</style>

<?php 
$titulo1="font-size:11px;background-color:#edeff1;font-weight:bold;height:30px;";
$subtitulo="font-size:10px;font-weight:bold;height:30px;background-color:#fafafa;text-align:left;margin-left:10px;"

?>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Estado de Situación Financiera (NIF)<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
					<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=reports&f=balanceGeneral&tipo=3' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros' onclick="javascript:$('#nmloader_div',window.parent.document).hide();"><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<input type='hidden' value='Auxiliar de Impuestos (Ingresos)' id='titulo'>
					<section id='imprimible'>
						<div class="row">
							<?php
							$url = explode('/modulos',$_SERVER['REQUEST_URI']);
							if($logo == 'logo.png') $logo = 'x.png';
							$logo = str_replace(' ', '%20', $logo);
							?>
							<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
								<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
								<b style="font-size:18px;color:black;"><?php echo $empresa; ?></b><br>
								<b style="font-size:15px;">Estado de Situación Financiera </b><br>
								Ejercicio<b> <?php echo $ej; ?> </b> Periodo <b> <?php echo $periodo; ?> </b><br>
								Sucursal <b><?php echo $nomSucursal; ?></b> Segmento <b><?php echo $nomSegmento; ?></b>
							</div>
						</div>
						<div class="row" id='divcon'>
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table class="table" align="center" style="max-width:800px;width:100%;font-size:9px;text-align:left" cellpadding="3">
										<thead>
										<tr style='<?php echo "$titulo1"; ?>;text-transform: uppercase;'>
											<td style='min-width:270px;width:50%;'>Clasificacion</td>
											<td style='text-align:right;min-width:170px;width:25%;'><?php echo $periodo;?></td>
											<td style='text-align:right;min-width:170px;width:25%;'><?php echo $periodoAnterior; ?></td>
										</tr>
										</thead>

										<tr><td style='text-align:left;min-width:270px;width:50%;'></td><td style='text-align:left;min-width:170px;width:25%;'></td><td style='min-width:170px;width:25%;'></td></tr>
										<?php
										$cont=0;
										$sumaCantidad = 0;
										$sumaCantidadAnterior = 0;
										$sumaCantidadRes = 0;
										$sumaCantidadAnteriorRes = 0;
										$sumaCantidadClas = 0;
										$sumaCantidadAnteriorClas = 0;
										$ClasificacionAnterior = 'Nada';
										while($d = $datos->fetch_object())
										{
											if($NivelAnterior != $d->Nivel AND $d->Clasificacion != "Resultados" AND $d->Clasificacion != "Capital") 
											{

												echo "<tr style='font-weight:bold;'><td height='30'>Total $NivelAnterior</td><td style='text-align:right;' tipo='$ClasificacionAnterior' cantidad='$sumaCantidad'>$ ".number_format($sumaCantidad,2)."</td><td style='text-align:right;' tipo='".$ClasificacionAnterior."Anterior' cantidad='$sumaCantidadAnterior'>$ ".number_format($sumaCantidadAnterior,2)."</td></tr>";
												if($ClasificacionAnterior != $d->Clasificacion)
												{
													if($d->Clasificacion == "Activo")
													{
														echo "<tr style='background-color:#f6f7f8;font-weight:bold;height:30px;'><td>ACTIVOS</td><td></td>
														<td></td></tr>";
													}
													if($d->Clasificacion == "Pasivo")
													{
														echo "<tr style='font-weight:bold;'><td height='30'>Total $ClasificacionAnterior</td><td style='text-align:right;' id='activo_actual' cantidad='".number_format($sumaCantidadClas,2,'.','')."'>$ ".number_format($sumaCantidadClas,2)."</td><td style='text-align:right;' id='activo_anterior' cantidad='".number_format($sumaCantidadAnteriorClas,2,'.','')."'>$ ".number_format($sumaCantidadAnteriorClas,2)."</td></tr>";
														echo "<tr style='background-color:#f6f7f8;font-weight:bold;height:30px;'><td>PASIVO Y CAPITAL CONTABLE</td><td></td>
														<td></td></tr>";
														$sumaCantidadClas = $sumaCantidadAnteriorClas = 0;
													}
												}
												echo "<tr><td height='30'><b>$d->Nivel</b></td><td></td>
												<td></td></tr>";
												$sumaCantidad = $sumaCantidadAnterior = 0;
											}

											if($d->Clasificacion == "Capital")
											{
												$cont++;
												if($cont == 1)
												{
													echo "<tr style='font-weight:bold;'><td height='30'>Total $NivelAnterior</td><td style='text-align:right;' tipo='$ClasificacionAnterior' cantidad='$sumaCantidad'>$ ".number_format($sumaCantidad,2)."</td><td style='text-align:right;' tipo='".$ClasificacionAnterior."Anterior' cantidad='$sumaCantidadAnterior'>$ ".number_format($sumaCantidadAnterior,2)."</td></tr>";
													echo "<tr style='font-weight:bold;'><td height='30'>Total $ClasificacionAnterior</td><td style='text-align:right;'>$ ".number_format($sumaCantidadClas,2)."</td><td style='text-align:right;'>$ ".number_format($sumaCantidadAnteriorClas,2)."</td></tr>";
													$sumaCantidadClas = $sumaCantidadAnteriorClas = 0;
													echo "<tr><td height='30'><b>Capital Contable</b></td><td></td><td></td></tr>";		
													$sumaCantidad = $sumaCantidadAnterior = 0;
												}

											}
										
											if($d->Clasificacion != 'Activo')
											{
												$d->CargosAbonos = $d->CargosAbonos * -1;
												$d->CargosAbonosAnterior = $d->CargosAbonosAnterior * -1;
											}
											else
											{
												$d->CargosAbonos = $d->CargosAbonos * 1;
												$d->CargosAbonosAnterior = $d->CargosAbonosAnterior * 1;
											}
											$NIF = explode('/', $d->NIF);
											echo "<tr class='$d->Clasificacion' numero='".$NIF[0]."'><td>".$NIF[1]."</td><td class='resCA' cantidad='$d->CargosAbonos' style='text-align:right;'>$ ".number_format($d->CargosAbonos,2)."</td><td class='resCAA' cantidad='$d->CargosAbonosAnterior' style='text-align:right;'>$ ".number_format($d->CargosAbonosAnterior,2)."</td></tr>";
											$NivelAnterior = $d->Nivel;
											$ClasificacionAnterior = $d->Clasificacion;
											$sumaCantidad += $d->CargosAbonos;
											$sumaCantidadAnterior += $d->CargosAbonosAnterior;
											$sumaCantidadClas += $d->CargosAbonos;
											$sumaCantidadAnteriorClas += $d->CargosAbonosAnterior;
											if($d->Clasificacion == "Resultados")
											{
												$sumaCantidadAnteriorRes += $d->CargosAbonosAnterior;
												$sumaCantidadRes += $d->CargosAbonos;
											}
										}
										echo "<tr id='resultados_total'><td style='text-align:left;'>Utilidad o perdida del Ejercicio</td><td style='text-align:right;'>$ ".number_format($sumaCantidadRes,2)."</td><td style='text-align:right;'>$ ".number_format($sumaCantidadAnteriorRes,2)."</td></tr>";
										echo "<tr style='font-weight:bold;$subtitulo'><td height='30'>Total Capital Contable</td><td style='text-align:right;' tipo='CapitalContable' cantidad='$sumaCantidad'>$ ".number_format($sumaCantidad,2)."</td><td style='text-align:right;' tipo='CapitalContableAnterior' cantidad='$sumaCantidadAnterior'>$ ".number_format($sumaCantidadAnterior,2)."</td></tr>";
										echo "<tr style='font-weight:bold;text-align:left;$titulo1'><td height='30'>Total Pasivo y Capital Contable</td><td style='text-align:right;' id='tpcc'></td><td style='text-align:right;' id='tpccAnterior'></td></tr>";
										?>

									</table>
								</div>
							</div>
						</div>
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
					<input type='hidden' name='nombreDocu' value='Estado de Resultados'>
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