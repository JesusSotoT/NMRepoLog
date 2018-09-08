<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
<script language='javascript'>
$(document).ready(function()
{
$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};

	var total = 0;
	var subtotal = 0;
	var convANum = 0;
	for(var f = 7; f <= 15; f++)//Recorre cada columna
	{
		$('#xx').append("<td id='"+f+"'></td>");//Anexa un elemento a la tabla
		//if(f!=9)
		//{
			$("#resultados tr").each(function(index)//Recorre cada fila
			{
				if($(this).attr('tipo') == 'numero')//Si es un elemento de tipo numero hace la suma de los resulados
				{
					convANum = $("td:nth-child("+f+")",this).text();
					convANum = convANum.replace(',','');
					total += parseFloat(convANum)//Sumatoria total
					subtotal += parseFloat(convANum)//Sumatoria del proveedor
				}
				if($(this).next().attr('tipo') == 'subtotal' && $(this).next().next().attr('tipo') != 'numero')//Si es un campo del tipo subtotal y el siguiente elemento no es de tipo numero entonces agrega elementos
				{
					if(f==7)//si se trata del primer barrido agraga td`s de relleno a la tabla
					{
						$(this).next().append("<td>Subtotal:</td><td></td><td></td><td></td><td></td><td></td>");
					}
					
					if(isNaN(subtotal)){subtotal=0;}

					if(f==9)
					{
						$(this).next().append("<td></td>");//Agrega la suma del subtotal
					}
					else
					{
						$(this).next().append("<td style='text-align:right;'>$"+subtotal.format()+"</td>");//Agrega la suma del subtotal
					}
					subtotal=0;	//se reinicia la suma
				}
			});	
		if(isNaN(total)){total=0;}
		
		if(f!=9){$("#"+f).text("$"+total.format()).css('text-align','right');}//Agrega el total al elemento
		total = 0;
		//}
	}

	//Agregar este proceso para igualar los width de las columnas de la tabla
	//COMIENZA
	//Primer paso: pegar este bloque de jquery
	//Segundo paso: crear el titulo estatico
	//Tercer paso: crear el div que contendra el contenido
	//Cuarto paso: quitar anchos en tablas
	//Quinto paso: modificar el css
	/*var ancho,texto;
	for(i=1;i<=16;i++)
	{
		ancho = $(".tit_tabla_buscari td:nth-child("+i+")").width();
		texto = $(".tit_tabla_buscari td:nth-child("+i+")").text();
		$(".estatico td:nth-child("+i+")").text(texto).width(ancho)
	}

	$("#resultados").attr('style','margin-top:-'+$(".tit_tabla_buscari").height()+'px;')*/
	//TERMINA

});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html()});
			}
</script>
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>
	.tit_tabla_buscar td
	{
		font-size:12px;
	}

	.razon_social
	{
		text-align:left;
	}

	.derecha,#resultados
	{
		text-align: center;
	}

	#divcon
	{
	width:auto;
	height:400px;
	overflow:scroll;
	}

	@media print
	{
		#imprimir,#filtros,#excel,#estatico,#pdf,#email
		{
			display:none;
		}

		#divcon
		{
			width:auto;
			height:auto;
		}

		#resultados
		{
			margin-top:40px !important;
		}

		.table-responsive{
			overflow-x: unset;
		}

		input{
			display: none;
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
    .table thead, .table tbody tr {
	    display:table;
	    width:100%;
	   	table-layout: fixed;/* even columns width , fix width of table too*/
	}
	.table tr, .table td{
		border: none !important;
	}
</style>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Auxiliar de movimientos de control de IVA</br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img src="../../netwarelog/repolog/img/impresora.png" border="0" title='Imprimir'></a>
					<!--<a href='javascript:generaexcel()' id='excel' ><img src='images/images.jpg' width='35px'></a>-->
					<a href='index.php?c=auxiliar_controlIva&f=Inicial' id='filtros'><img src="../../netwarelog/repolog/img/filtros.png" border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>			
				</section>
			</h3>
			<div class="row">
				<div class="col-md-12">
					<input type='hidden' value='Auxiliar de movimientos de control de IVA.' id='titulo'>
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
							<div class="col-md-12 col-sm-12" style='text-align:center;font-size:18px;'>
								<?php
								echo $fecha;
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table class="" border='0' cellspacing=0 cellpadding=0 id='resultados'>
										<tr class='tit_tabla_buscari' style='height:0px;'>
											<td class='nmcatalogbusquedatit'># Poliza</td>
											<td class='nmcatalogbusquedatit'>Fecha</td>
											<td class='nmcatalogbusquedatit'>Tipo Poliza</td>
											<td class='nmcatalogbusquedatit'>Referencia</td>
											<td class='nmcatalogbusquedatit'>Ejercicio</td>
											<td class='nmcatalogbusquedatit'>Periodo Acreditamiento</td>
											<td class='nmcatalogbusquedatit'>Importe Base</td>
											<td class='nmcatalogbusquedatit'>Otras Erogaciones</td>
											<td class='nmcatalogbusquedatit'>Tasa</td>
											<td class='nmcatalogbusquedatit'>Importe IVA</td>
											<td class='nmcatalogbusquedatit'>Importe Antes de Retenciones</td>
											<td class='nmcatalogbusquedatit'>IVA Retenido</td>
											<td class='nmcatalogbusquedatit'>ISR Retenido</td>
											<td class='nmcatalogbusquedatit'>Total Erogaci&oacute;n</td>
											<td class='nmcatalogbusquedatit'>Iva Pagado No Acreditable</td>
										</tr>
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
									    		$color='#fafafa';
											}

											if($anterior != $d->proveedor)
											{
												echo "<tr class='razon_social' style='#background-color:#f6f7f8;'><td colspan=5>$d->proveedor</td><td colspan=3>RFC: $d->rfc</td><td colspan=3></td><td colspan=4></td></tr>";
											}

											$importeIVA = $d->importeBase * ($d->tasa / 100);
											$importeAntes = $d->importeBase + $d->otrasErogaciones + $importeIVA;
											echo "<tr tipo='numero' style='height: 30px !important;background-color:$color;'>
													<td title='# Poliza'><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$d->id&idProveedor=$d->idProveedor&fecha=$d->fecha' target='_blank'>$d->idperiodo/$d->numpol</a></td>
													<td title='Fecha'>$d->fecha</td>
													<td class='derecha'  title='Tipo Poliza' >$d->tipoPoliza</td>
													<td title='Referencia'>$d->referencia</td>
													<td title='Ejercicio'>$d->ejercicio</td>
													<td title='Periodo Acreditamiento'>$d->periodoAcreditamiento</td>
													<td title='Importe Base' style='text-align:right;'>".number_format($d->importeBase,2)."</td>
													<td title='Otras Erogaciones' style='text-align:right;'>".number_format($d->otrasErogaciones,2)."</td>
													<td title='Tasa'>$d->tasaValor</td>
													<td title='Importe IVA' style='text-align:right;'>".number_format($importeIVA,2)."</td>
													<td title='Importe antes de Retenciones' style='text-align:right;'>".number_format($importeAntes,2)."</td>
													<td title='IVA Retenido' style='text-align:right;'>".number_format($d->ivaRetenido,2)."</td>
													<td title='ISR Retenido' style='text-align:right;'>".number_format($d->isrRetenido,2)."</td>
													<td title='Total Erogacion' style='text-align:right;'>".number_format($importeAntes - $d->ivaRetenido - $d->isrRetenido,2)."</td>
													<td title='IVA Pagado no acreditable' style='text-align:right;'>".number_format($d->ivaPagadoNoAcreditable,2)."</td>";
													echo "</tr>";
											echo "<tr id='$d->proveedor' style='text-align:center;font-weight:bold;background-color:#f6f7f8;' tipo='subtotal'></tr>";

											$cont++;//Incrementa contador
											$anterior = $d->proveedor;

										}
										?>
										<tr  id='xx' style='text-align:center;font-weight:bold;background-color:#edeff1;' tipo='total'><td>Totales:</td><td></td><td></td><td></td><td></td><td></td></tr>
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
