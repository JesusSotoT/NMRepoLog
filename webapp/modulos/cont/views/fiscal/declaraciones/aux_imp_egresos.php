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
	var ancho;
	for(var i=1;i<=22;i++)
	{
		if(parseInt($("#t"+i).width()) > parseInt($(".c"+i).width()))
		{
			$("#t"+i).removeAttr('width')	
			ancho = $("#t"+i).width()
			$(".c"+i).attr('style','width:'+ancho+'px !important;').attr('tamano',ancho)	
			$("#t"+i).attr('style','width:'+ancho+'px !important;').attr('tamano',ancho)	
			//alert(ancho)
		}

		if(parseInt($(".c"+i).width()) > parseInt($("#t"+i).width()))
		{
			$("#t"+i).removeAttr('width')	
			ancho = $(".c"+i).width()
			$("#t"+i).attr('style','width:'+ancho+'px !important;').attr('tamano',ancho)	
			$(".c"+i).attr('style','width:'+ancho+'px !important;').attr('tamano',ancho)	
			//alert(ancho)
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

	/*INICIA Mejora al titulo estatico*************/
	/*
	Paso 1: Agregar este CSS
	Paso 2: quitar clases del netwar a las tablas
	Paso 3: Agregar el jquery y ajustar el numero en el for dependiendo de la cantidad de columnas
	Paso 4: agregar tbody y thead tambien agregar los ids y las clases al tbody y tds
	Paso 5: Quitar width a la tabla
	Paso 6: agregar los styles
	*/
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
	

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Auxiliar de Impuestos (Egresos)<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
					<!--<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>  --> 
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=auxiliar_impuestos&f=filtro' id='filtros' onclick='$("#nmloader_div",window.parent.document).show();'><img src="../../netwarelog/repolog/img/filtros.png" border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-1">
				</div>
				<div class="col-md-10" id="imp_cont">
					<input type='hidden' value='Auxiliar de Impuestos (Egresos)' id='titulo'>
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
									<table border='0' width=2000 class="table" cellspacing=0 cellpadding=0 id='egresos'>
		
										<tr style='font-size:10px;background-color:#e4e7ea;font-weight:bold'>
											<td width='60' id='t1'>Fecha Poliza</td>
											<td width='30' id='t2'>Tipo Poliza</td>
											<td width='70' id='t3'>Poliza</td>
											<td width='30' id='t4'>Ejercicio</td>
											<td width='30' id='t5'> Periodo</td>
											<td width='150' id='t6'>Concepto</td>
											<td width='120' id='t7'>Tasa IVA</td>
											<td width='120' id='t8'>Actos y Actividades Pagados (Base del IVA Acreditable)</td>
											<td width='120' id='t9'>IVA de Compras y Gastos (Importe de IVA)</td>
											<td width='120' id='t10'>IVA Pagado no Acreditable</td>
											<td width='120' id='t11'>IVA Retenido</td>
											<td width='120' id='t12'>IVA Acreditable Retenido de Meses Anteriores</td>
											<td width='120' id='t13'>IVA de Actos y Actividades Pagados (IVA Acreditable Neto)</td>
											<td width='120' id='t14'>Para IVA</td>
											<td width='120' id='t15'>ISR Retenido</td>
											<td width='120' id='t16'>Otros</td>
											<td width='50' id='t17'>Codigo Proveedor</td>
											<td width='80' id='t18'>Nombre Proveedor</td>
											<td width='80' id='t19'>RFC Proveedor</td>
											<td width='80' id='t20'>Referencia Movimiento Proveedor</td>
											<td width='80' id='t21'>Tipo de Tercero</td>
											<td width='80' id='t22'>Tipo de Operacion</td>

										</tr>

										<?php
											while($e = $egresos->fetch_object())
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

												if(floatval($e->ivaRetenido) != 0)
												{
													$ivaRetenido = floatval($e->ivaRetenido)*(-1);
												}
												if(floatval($e->isrRetenido) != 0)
												{
													$isrRetenido = floatval($e->isrRetenido)*(-1);
												}
												echo "<tr id='e' style='background-color:$color'>
														<td class=''>$e->fecha</td>
														<td style='text-align:center;' class=''>$e->titulo</td>
														<td style='text-align:center;' class=''><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$e->id&idProveedor=".intval($e->idProveedor)."&fecha=$e->fecha' target='_blank'>$e->idperiodo/$e->numpol</a></td>
														<td style='text-align:center;' class=''>$e->ejercicio</td>
														<td style='text-align:center;' class=''>$e->periodo</td>
														<td style='text-align:center;' class=''>$e->concepto</td>
														<td style='text-align:center;' class=''>$e->tasa</td>
														<td style='text-align:right;' class=''>".number_format($e->ImporteBase,2)."</td>
														<td style='text-align:right;' class=''>".number_format($e->impiva,2)."</td>
														<td style='text-align:right;' class=''>".number_format($e->iva_pag_no_acr,2)."</td>
														<td style='text-align:right;' class=''>".number_format($ivaRetenido,2)."</td>
														<td style='text-align:right;' class=''>".number_format($e->retenidoMesAnterior,2)."</td>
														<td style='text-align:right;' class=''>".number_format($e->causado,2)."</td>
														<td style='text-align:center;' class=''>$e->tipoiva</td>
														<td style='text-align:right;' class=''>".number_format($isrRetenido,2)."</td>
														<td style='text-align:right;' class=''>".number_format($e->otrasErogaciones,2)."</td>
														<td style='text-align:center;' class=''>$e->idProveedor</td>
														<td style='text-align:center;' class=''>$e->razon_social</td>
														<td style='text-align:center;' class=''>$e->rfc</td>
														<td style='text-align:center;' class=''>$e->referencia</td>
														<td style='text-align:center;' class=''>$e->tipotercero</td>
														<td style='text-align:center;' class=''>$e->tipooperacion</td></tr>";

														
														//<td>".number_format($dt->ivaNoAcreditable,2)."</td>";

														
														$ImporteBase += $e->ImporteBase;
														$ImporteIva += $e->impiva;
														$iva_pag_no_acr += $e->iva_pag_no_acr;
														$ivaRetenidoT += $ivaRetenido;
														$retenidoMesAnteriorT += $e->retenidoMesAnterior;
														$causado += $e->causado;
														$isrRetenidoT += $isrRetenido;
														$otrasErogaciones += $e->otrasErogaciones;
														
												$cont++;//Incrementa contador


											}
											echo "<tr style='text-align:left;font-weight:bold;background-color:#f6f7f8;' tipo='subtotalDT'><td colspan='7'>Totales: </td><td style='text-align:right;'>$".number_format($ImporteBase,2)."</td><td style='text-align:right;'>$".number_format($ImporteIva,2)."</td><td style='text-align:right;'>$".number_format($iva_pag_no_acr,2)."</td><td style='text-align:right;'>$".number_format($ivaRetenidoT,2)."</td><td style='text-align:right;'>$".number_format($retenidoMesAnteriorT,2)."</td><td style='text-align:right;'>$".number_format($causado,2)."</td><td></td><td style='text-align:right;'>$".number_format($isrRetenidoT,2)."</td><td style='text-align:right;'>$".number_format($otrasErogaciones,2)."</td><td colspan='6'></td></tr>";
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