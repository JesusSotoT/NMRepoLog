<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="css/style.css" type="text/css">
<script language='javascript'>
	$(document).ready(function() {
		$('#nmloader_div',window.parent.document).hide();

	//Agregar este proceso para igualar los width de las columnas de la tabla
	//INICIA
	for(var i=1;i<=13;i++)
	{
		if(parseInt($("#t"+i).width()) > parseInt($(".c"+i).width()))
		{
			$(".c"+i).width($("#t"+i).width()).attr('tamano',$("#t"+i).width())	
		}

		if(parseInt($(".c"+i).width()) > parseInt($("#t"+i).width()))
		{
			$("#t"+i).width($(".c"+i).width()).attr('tamano',$(".c"+i).width())	
		}
	}
	//TERMINA


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
		background-color:gray;
		color:white;
	}

	.derecha
	{
		text-align: center;
	}

	.right
	{
		text-align: right;
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
/*INICIA Mejora al titulo estatico*************/
/*
Paso 1: Agregar este CSS
Paso 2: quitar clases del netwar a las tablas
Paso 3: Agregar el jquery
Paso 4: agregar los ids y las clases al tbody y tds
Paso 5: Quitar width a la tabla
Paso 6: agregar los styles
.tb {
    height: 400px;
    overflow: auto;
    
}

td
{
	padding: 4px;
}



thead, tbody{
    display:block;}
/*TERMINA Mejora al titulo estatico*************/
	@media print
	{
		#imprimir,#filtros,#excel, #botones
		{
			display:none;
		}
		.table-responsive{
			overflow-x: unset;
		}
		#imp_cont{
			width: 100%;
		}
		table{
			zoom: 0.5 !important;
		}
	}
</style>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Auxiliar de Impuestos (Ingresos)<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a><a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a> 
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=auxiliar_impuestos&f=filtro' id='filtros' onclick='$("#nmloader_div",window.parent.document).show();'><img src="../../netwarelog/repolog/img/filtros.png" border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-1">
				</div>
				<div class="col-md-10" id="imp_cont">
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
								<?php
								echo $fecha_reporte;
								?>
							</div>
						</div>
						<div class="row" id='divcon'>
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table class="table" border='0' style='width:100%;max-width:1000px;font-size:9px;' align="center" cellpadding=3 id='ingresos'>
		
										<tr style='background-color:#edeff1;font-weight:bold;font-size:9px;'>
											<td width='5%' id='t1'>Fecha Poliza</td>
											<td width='5%' id='t2'>Tipo Poliza</td>
											<td width='5%' id='t3'>Poliza</td>
											<td width='10%' id='t4'>Concepto</td>
											<td width='5%' id='t5'>Ejercicio</td>
											<td width='5%' id='t6'> Periodo</td>
											<td width='5%' id='t7'>Tasa IVA</td>
											<td width='10%' id='t8' style='text-align:right;'>Actos o Actividades Gravados (Base del IVA)</td>
											<td width='10%' id='t9' style='text-align:right;'>Importe de IVA</td>
											<td width='10%' id='t10' style='text-align:right;'>IVA Retenido</td>
											<td width='10%' id='t11' style='text-align:right;'>IVA Causado</td>
											<td width='10%' id='t12' style='text-align:right;'>ISR Retenido</td>
											<td width='10%' id='t13' style='text-align:right;'>Otros</td>
										</tr>
										
										
										<?php
											while($i = $ingresos->fetch_object())
											{
												$ivaRetenido=0;
												$isrRetenido=0;
												if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
												{
									    			$color='#ffffff';
												}
												else//Si es impar pinta esto
												{
									    			$color='#fafafa';
												}

												if(floatval($i->ivaRetenido) != 0)
												{
													$ivaRetenido = floatval($i->ivaRetenido)*(-1);
												}
												if(floatval($i->isrRetenido) != 0)
												{
													$isrRetenido = floatval($i->isrRetenido)*(-1);
												}
												echo "<tr id='e' style='background-color:$color'>
														<td class='c1'>$i->fecha</td>
														<td class='c2'>$i->TipoPoliza</td>
														<td class='c3'><center><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$i->id&idProveedor=".intval($i->idProveedor)."&fecha=$i->fecha' target='_blank'>$i->idperiodo/$i->numpol</a></center></td>
														<td class='c4'>$i->concepto</td>
														<td class='c5'>$i->Ejercicio</td>
														<td class='c6'>$i->Periodo</td>
														<td class='c7'>$i->Tasa</td>
														<td class='c8' style='text-align:right;'>".number_format($i->ImporteBase,2)."</td>
														<td class='c9' style='text-align:right;'>".number_format($i->ImporteIVA,2)."</td>
														<td class='c10' style='text-align:right;'>".number_format($ivaRetenido,2)."</td>
														<td class='c11' style='text-align:right;'>".number_format($i->causado,2)."</td>
														<td class='c12' style='text-align:right;'>".number_format($isrRetenido,2)."</td>
														<td class='c13' style='text-align:right;'>".number_format($e->otros,2)."</td>";


														
														$ImporteBase += $i->ImporteBase;
														$ImporteIVA += $i->ImporteIVA;
														$ivaRetenidoT += $ivaRetenido;
														$causado += $i->causado;
														$isrRetenidoT += $isrRetenido;
														$otros += $i->otros;
														
												$cont++;//Incrementa contador


											}
											echo "<tr style='text-align:right;font-weight:bold;background-color:#edeff1;' tipo='subtotalDT'><td colspan='7' style='text-align:left;'>Totales: </td><td>$".number_format($ImporteBase,2)."</td><td>$".number_format($ImporteIVA,2)."</td><td>$".number_format($ivaRetenidoT,2)."</td><td>$".number_format($causado,2)."</td><td>$".number_format($isrRetenidoT,2)."</td><td>$".number_format($otros,2)."</td></tr>";
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
			<script language='javascript'>
				function cerrarloading(){
					$("#loading").fadeOut(0);
					var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
					$("#divmsg").html(divloading);
				}
			</script>