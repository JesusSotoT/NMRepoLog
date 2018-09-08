<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/style.css">
<script language='javascript'>
	$(document).ready(function() {
		$('#nmloader_div',window.parent.document).hide();
	});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>
	.tit_tabla_buscar td
	{
		font-size:12px;
	}

	.razon_social
	{
		background-color:#f6f7f8;
	}

	.derecha
	{
		text-align: center;
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

	@media print
	{
		#imprimir,#filtros,#excel, #botones
		{
			display:none;
		}
		.table-responsive{
			overflow-x: unset;
		}
	}
</style>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Conciliación de IVA fiscal y contable<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
					<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=conciliacion_IVA_contable_fiscal&f=filtro' id='filtros' onclick='$("#nmloader_div",window.parent.document).show();'><img src="../../netwarelog/repolog/img/filtros.png" border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
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
								<?php
								echo $fecha;
								?>
							</div>
						</div>
						<div class="row" id='divcon'>
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table class="table" border='0' style='width:100%;max-width:900px;font-size:10px;' align="center" cellpadding=3 id='trasladado'>
										<tr class='' style="background-color:#edeff1;text-align:center;font-size:10px;font-weight:bold;height:30px">
											<td width='9%'>Fecha Poliza</td>
											<td width='7%'>Tipo Poliza</td>
											<td width='8%'>Periodo / Poliza</td>
											<td width='9%'>Ejercicio</td>
											<td width='9%'> Periodo</td>
											<td width='18%'>Concepto</td>
											<td width='10%'>IVA Contable</td>
											<td width='10%'>IVA Fiscal</td>
											<td width='10%'>IVA Pagado No Acreditable</td>
											<td width='10%'>Diferencia</td>
										</tr>
										<?php
										if($trasladado)
										{
											$totalImporteContable = 0;
											$totalTotalFiscal = 0;
											echo "<tr><td colspan='10' style='text-align:left;background-color:#f6f7f8;height:30px;'><b>IVA Trasladado</b></td></tr>";
											while($dt = $datosTrasladado->fetch_object())
											{
												if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
												{
									    			$color='#ffffff';
												}
												else//Si es impar pinta esto
												{
									    			$color='#ffffff';
												}


												echo "<tr id='dt' style='background-color:$color;'>
														<td>$dt->fecha</td>
														<td>$dt->TipoPoliza</td>
														<td><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$dt->id&idProveedor=".intval($dt->idProveedor)."&fecha=$dt->fecha' target='_blank'>$dt->idperiodo/$dt->numpol</a></td>
														<td>$dt->Ejercicio</td>
														<td>$dt->idperiodo</td>
														<td>$dt->concepto</td>
														<td style='text-align:right;'>".number_format($dt->ImporteContable,2)."</td>
														<td style='text-align:right;'>".number_format($dt->TotalFiscal,2)."</td>
														<td style='text-align:right;'>".number_format($dt->IvaNoAcreditable,2)."</td>";

														$dif = $dt->ImporteContable-$dt->TotalFiscal;
														if($dif<-0.01)
														{
															$color="color:red";
														}
													echo "<td style='$color;text-align:right;'>".number_format($dif,2)."</td>
														</tr>";

														$totalImporteContable += $dt->ImporteContable;
														$totalTotalFiscal += $dt->TotalFiscal;
												$cont++;//Incrementa contador


											}
											echo "<tr style='text-align:left;font-weight:bold;background-color:#f6f7f8;height:30px;' tipo='subtotalDT'><td colspan='6'></td><td style='text-align:right;'>$".number_format($totalImporteContable,2)."</td><td style='text-align:right;'>$".number_format($totalTotalFiscal,2)."</td><td colspan='2'></td></tr>";
										}

										if($acreditable)
										{
											$totalImporteContable = 0;
											$totalImporteFiscal = 0;
											echo "<tr style='background-color:#f6f7f8;height:30px;text-align:left;'><td colspan='10'><b>IVA Acreditable</b></td></tr>";
											$ant = '0';
											while($da = $datosAcreditado->fetch_object())
											{
												if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
												{
									    			$color='#ffffff';
												}
												else//Si es impar pinta esto
												{
									    			$color='#ffffff';
												}
												$act = "$da->TipoPoliza/$da->idperiodo/$da->numpol";
												if($ant != $act)
												{
													echo "<tr id='da' style='background-color:$color'>
															<td class='text-center'>$da->fecha</td>
															<td class='text-center'>$da->TipoPoliza</td>
															<td></td>
															<td class='text-center'>$da->Ejercicio</td>
															<td class='text-center'>$da->idperiodo</td>
															<td class='text-center'>$da->concepto</td>
															<td style='text-align:right;'>".number_format($da->ImporteContable,2)."</td>
															<td></td><td></td><td></td></tr>";
												}

												echo "<tr><td></td><td></td><td class='text-center'><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$da->id&idProveedor=".intval($da->idProveedor)."&fecha=$da->fecha' target='_blank'>$da->idperiodo/$da->numpol</a></td><td></td><td></td><td></td><td></td>
												<td style='text-align:right;'>".number_format($da->ImporteFiscal,2)."</td>
														<td style='text-align:right;'>".number_format($da->IvaNoAcreditable,2)."</td>";
												if($ant != $act)
													$Importes = $da->ImporteContable;
												else
												{
													$Importes = $dif;
												}
													


														$dif = $Importes - $da->ImporteFiscal;
														if($dif<-0.01)
														{
															$color="color:red";
														}
													echo "<td style='$color;text-align:right;'>".number_format($dif,2)."</td>
														</tr>";
												if($ant != $act)
													$totalImporteContable += $da->ImporteContable;
												$totalImporteFiscal += $da->ImporteFiscal;
												$cont++;//Incrementa contador
												$ant = "$da->TipoPoliza/$da->idperiodo/$da->numpol";


											}
											echo "<tr style='text-align:left;font-weight:bold;background-color:#f6f7f8;' tipo='subtotalDA'><td colspan='6'></td><td style='text-align:right;'>$".number_format($totalImporteContable,2)."</td><td style='text-align:right;'>$".number_format($totalImporteFiscal,2)."</td><td colspan='2'></td></tr>";
										}
										if($trasladado)
										{
											$totalImporteContable = 0;
											$totalTotalFiscal = 0;
											echo "<tr style='color:white;background-color#f6f7f8;height:30px;text-align:left;'><td colspan='10'><b>IVA Trasladado Sin IVA</b></td></tr>";
											while($dtn = $datosTrasladadoNo->fetch_object())
											{
												if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
												{
									    			$color='#ffffff';
												}
												else//Si es impar pinta esto
												{
									    			$color='#ffffff';
												}


												echo "<tr id='dtn' style='background-color:$color'>
														<td>$dtn->fecha</td>
														<td>$dtn->TipoPoliza</td>
														<td><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$dtn->id&idProveedor=".intval($dtn->idProveedor)."&fecha=$dtn->fecha' target='_blank'>$dtn->idperiodo/$dtn->numpol</a></td>
														<td>$dtn->Ejercicio</td>
														<td>$dtn->idperiodo</td>
														<td>$dtn->concepto</td>
														<td style='text-align:right;'>".number_format($dtn->ImporteContable,2)."</td>
														<td style='text-align:right;'>".number_format($dtn->TotalFiscal,2)."</td>
														<td style='text-align:right;'>".number_format($dtn->IvaNoAcreditable,2)."</td>";

														$dif = $dtn->ImporteContable-$dtn->TotalFiscal;
														if($dif<-0.01)
														{
															$color="color:red";
														}
													echo "<td style='$color;text-align:right;'>".number_format($dif,2)."</td>
														</tr>";

												$totalImporteContable += $dtn->ImporteContable;
												$totalTotalFiscal +=  $dtn->TotalFiscal;
												$cont++;//Incrementa contador


											}
											echo "<tr style='text-align:left;font-weight:bold;background-color:#edeff1;' tipo='subtotalDTN'><td colspan='6'></td><td style='text-align:right;'>$".number_format($totalImporteContable,2)."</td><td style='text-align:right;'>$".number_format($totalTotalFiscal,2)."</td><td colspan='2'></td></tr>";
										}
										if($acreditable)
										{
											$totalImporteContable = 0;
											$totalImporteFiscal = 0;
											echo "<tr style='background-color:#f6f7f8;height:30px;text-align:left;'><td colspan='10'><b>IVA Acreditable Sin IVA</b></td></tr>";
											while($dan = $datosAcreditadoNo->fetch_object())
											{
												if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
												{
									    			$color='#ffffff';
												}
												else//Si es impar pinta esto
												{
									    			$color='#ffffff';
												}


												echo "<tr id='dan' style='background-color:$color'>
														<td>$dan->fecha</td>
														<td>$dan->TipoPoliza</td>
														<td><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$dan->id&idProveedor=".intval($dan->idProveedor)."&fecha=$dan->fecha' target='_blank'>$dan->idperiodo/$dan->numpol</a></td>
														<td>$dan->Ejercicio</td>
														<td>$dan->idperiodo</td>
														<td>$dan->concepto</td>
														<td style='text-align:right;'>".number_format($dan->ImporteContable,2)."</td>
														<td style='text-align:right;'>".number_format($dan->ImporteFiscal,2)."</td>
														<td style='text-align:right;'>".number_format($dan->IvaNoAcreditable,2)."</td>";

														$dif = $dan->ImporteFiscal-$dan->ImporteContable;
														if($dif<-0.01)
														{
															$color="color:red";
														}
													echo "<td style='background-color:$color;text-align:right;'>".number_format($dif,2)."</td>
														</tr>";

												$totalImporteContable += $dan->ImporteContable;
												$totalImporteFiscal += $dan->ImporteFiscal;
												$cont++;//Incrementa contador


											}
											echo "<tr style='text-align:left;font-weight:bold;background-color:#f6f7f8;' tipo='subtotalDAN'><td colspan='6'></td><td style='text-align:right;'>$".number_format($totalImporteContable,2)."</td><td style='text-align:right;'>$".number_format($totalImporteFiscal,2)."</td><td colspan='2'></td></tr>";
										}
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
			<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;">
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