<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="css/style.css" type="text/css">
<script language='javascript'>
$(document).ready(function()
{
	$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};

	//Proceso que recorre los valores de los elementos y los suma. 
	//Cuando termina agrega los elementos con los resultados
	
	var total = 0;
	var subtotal = 0;
	var num = 0;
	for(var f = 6; f <= 14; f++)//Recorre cada columna
	{
		$('#xx').append("<td id='"+f+"'></td>");//Anexa un elemento a la tabla
		$("#resultados tr").each(function(index)//Recorre cada fila
		{
			if($(this).attr('tipo') == 'numero')//Si es un elemento de tipo numero hace la suma de los resulados
			{
				num = $("td:nth-child("+f+")",this).text();
				num = num.replace(',','');
				total += parseFloat(num)//Sumatoria total
				subtotal += parseFloat(num)//Sumatoria del proveedor
				
			}

			if($(this).next().attr('tipo') == 'subtotal' && $(this).next().next().attr('tipo') != 'numero')//Si es un campo del tipo subtotal y el siguiente elemento no es de tipo numero entonces agrega elementos
				{
					if(f==6)//si se trata del primer barrido agraga td`s de relleno a la tabla
					{
						$(this).next().append("<td>Subtotal:</td><td></td><td></td><td></td><td></td>");
					}
					
					if(isNaN(subtotal)){subtotal=0;}

					$(this).next().append("<td>$"+subtotal.format()+"</td>");//Agrega la suma del subtotal
					subtotal=0;	//se reinicia la suma
				}

		});	
		
		if(isNaN(total)){total=0;}
		
		$("#"+f).text("$"+total.format());//Agrega el total al elemento
		total = 0;
	}

	//Agregar este proceso para igualar los width de las columnas de la tabla
	//COMIENZA
	//Primer paso: pegar este bloque de jquery
	//Segundo paso: crear el titulo estatico
	//Tercer paso: crear el div que contendra el contenido
	//Cuarto paso: quitar anchos en tablas
	//Quinto paso: modificar el css
	var ancho,texto;
	for(i=1;i<=14;i++)
	{
		ancho = $(".tit_tabla_buscari td:nth-child("+i+")").width();
		texto = $(".tit_tabla_buscari td:nth-child("+i+")").text();
		$(".estatico td:nth-child("+i+")").text(texto).width(ancho)
	}

	$("#resultados").attr('style','margin-top:-'+$(".tit_tabla_buscari").height()+'px;')
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
		background-color:#edeff1;
		
	}

	.derecha
	{
		text-align: right;
	}

	#divcon
	{
	width:auto;
	height:400px;
	overflow:scroll;
	}

	@media print
	{
		#imprimir,#filtros,#excel,#estatico,#pdf,#email, #botones
		{
			display:none;
		}

		#divcon
		{
			width:auto;
			height:auto;
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
</style>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Auxiliar del Formato A29<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img src="../../netwarelog/repolog/img/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
					<a href="javascript:pdf();" id='pdf'><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   
					<a href="javascript:mail();" id='email'><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=auxiliar_a29&f=Inicial' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros'><img src="../../netwarelog/repolog/img/filtros.png" border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<input type='hidden' value='Auxiliar del Formato A29.' id='titulo'>
					<section id='imprimible'>
						<div class="row">
							<?php
							$url = explode('/modulos',$_SERVER['REQUEST_URI']);
							if($logo == 'logo.png') $logo = 'x.png';
							$logo = str_replace(' ', '%20', $logo);
							?>
							<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
								<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b><br>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
								<?php
								echo $fecha;
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table border='0' cellpadding=3 id='resultados' style='width:100%;max-width:1000px;font-size:8px;' class="table">
										<thead>
										<tr style='height:30px;background-color:#edeff1;font-weight:bold;font-size:9px;'>
											<td style="width:7%;">Fecha Poliza</td>
											<td style="width:7%;">Tipo Poliza</td>
											<td style="width:7%;">Periodo / Poliza</td>
											<td style="width:7%;">Ejercicio</td>
											<td style="width:7%;">Periodo</td>
											<td style="width:7%;">Actos Pagados 15% o 16%</td>
											<td style="width:7%;">Actos Pagados 15%</td>
											<td style="width:7%;">IVA No Acreditable pagado al 15% o 16%</td>
											<td style="width:8%;">Actos Pagados 10% u 11%</td>
											<td style="width:8%;">Actos Pagados 10%</td>
											<td style="width:7%;">IVA No Acreditable pagado al 10% u 11%</td>
											<td style="width:7%;">Actos Pagados Tasa 0%</td>
											<td style="width:7%;">Actos Pagados Excentos</td>
											<td style="width:7%;">IVA Retenido</td>
										</tr>
										</thead>

										<?php
										$anterior ='';
										$pagoIva = 0;
										while($d = $datos->fetch_object())
										{
											if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
											{
									    		$color='#ffffff';
											}
											else//Si es impar pinta esto
											{
									    		$color='#ffffff';
											}
											if($anterior != $d->razon_social)
											{
												echo "<tr class='razon_social' style='background-color:#edeff1;font-size:8px;font-weight:bold;'><td colspan=4>$d->razon_social</td><td colspan=3>$d->rfc</td><td colspan=3>$d->tipo_tercero</td><td colspan=4>$d->tipo_operacion</td></tr>";
											}

											echo "<tr style='background-color:$color;font-size:8px;' tipo='numero'>
													<td title='Fecha'>$d->fecha</td>
													<td title='Tipo de Poliza'>$d->tipo_poliza</td>
													<td title='Periodo/Poliza' style='text-align:center;'><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$d->id&idProveedor=$d->idProveedor&fecha=$d->fecha' target='_blank'>$d->idperiodo/$d->numpol</a></td>
													<td title='Ejercicio'>$d->ejercicio</td>
													<td title='Periodo Acreditamiento'>$d->periodoAcreditamiento</td>";
													if($d->Tasa == 15 || $d->Tasa == 16)
													{
														echo "<td  title='15% o 16%' class='derecha'>".number_format($d->importeBase,2)."</td>";
														$pagoIva += $Pago;
													}
													else
													{echo "<td title='15% o 16%' class='derecha'>0.00</td>";}

													if($d->Tasa == 15)
													{
														//$Pago = $d->importeBase * (($d->Tasa /100)+1);
														//$Pago -= $d->importeBase;
														echo "<td class='derecha' title='15%' >".number_format($d->importeBase,2)."</td>";
													}
													else
													{echo "<td class='derecha' title='15%'>0.00</td>";}

													if($d->Tasa == 16 || $d->Tasa == 15)
													{echo "<td class='derecha' title='IVA Pagado No Creditable 15% o 16%'>".number_format($d->ivaPagadoNoAcreditable,2)."</td>";}
													else
													{echo "<td class='derecha' title='IVA Pagado No Creditable 15% o 16%'>0.00</td>";}

													if($d->Tasa == 11 || $d->Tasa == 10)
													{
														echo "<td class='derecha' title='11% o 10%'>".number_format($d->importeBase,2)."</td>";
													}
													else
													{echo "<td class='derecha' title='11% o 10%'>0.00</td>";}

													if($d->Tasa == 10)
													{
														echo "<td class='derecha' title='10%'>".number_format($d->importeBase,2)."</td>";
													}
													else
													{echo "<td class='derecha' title='10%'>0.00</td>";}

													if($d->Tasa == 11 || $d->Tasa == 10)
													{echo "<td class='derecha' title='IVA Pagado no Acreditable 11% o 10%'>".number_format($d->ivaPagadoNoAcreditable,2)."</td>";}
													else
													{echo "<td class='derecha' title='IVA Pagado no acreditable 11% o 10%'>0.00</td>";}

													if($d->Tasa == 0 && $d->TasaNom == '0%')
													{
														$Pago = $d->importeBase;
														echo "<td title='0%' class='derecha'>".number_format($Pago,2)."</td>";
													}
													else
													{echo "<td class='derecha' title='0%'>0.00</td>";}

													if($d->Tasa == 0 && $d->TasaNom == 'Exenta')
													{
														$Pago = $d->importeBase;
														echo "<td class='derecha' title='Exenta'>".number_format($Pago,2)."</td>";
													}
													else
													{echo "<td class='derecha' title='Exenta'>0.00</td>";}

													echo "<td class='derecha' title='IVA Retenido'>".number_format($d->ivaRetenido,2)."</td>";

													echo "</tr>";

													echo "<tr id='$d->razon_social' style='text-align:right;font-weight:bold;background-color:#f6f7f8;font-size:8px;' tipo='subtotal'></tr>";


											$cont++;//Incrementa contador
											$anterior = $d->razon_social;

										}
										?>
										<tr  id='xx' style='text-align:center;font-weight:bold;background-color:#edeff1;font-size:9px;' tipo='total'><td>Totales:</td><td></td><td></td><td></td><td></td></tr>
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
					<input type='hidden' name='nombreDocu' value='Auxiliar formato a29'>
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