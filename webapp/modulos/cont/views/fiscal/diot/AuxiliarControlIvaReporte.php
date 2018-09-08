<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<?php // include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php //echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
<link rel="stylesheet" href="css/style.css" type="text/css">	
<script language='javascript'>
$(document).ready(function()
{
	$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};

	var total = 0;
	for(var f = 8; f <= 16; f++)//Recorre cada columna
	{
		$('#xx').append("<td id='"+f+"'></td>");//Anexa un elemento a la tabla
		if(f!=10)
		{
			$("#resultados tr").each(function(index)//Recorre cada fila
			{
				if($(this).attr('tipo') == 'numero')//Si es un elemento de tipo numero hace la suma de los resulados
				{
					convANum = $("td:nth-child("+f+")",this).text();
					convANum = convANum.replace(',','');
					total += parseFloat(convANum)//Sumatoria total
				}
			});	
		if(isNaN(total)){total=0;}
		
		$("#"+f).text("$"+total.format()).css({"text-align":"right"});//Agrega el total al elemento
		total = 0;
		}
	}
	//Agregar este proceso para igualar los width de las columnas de la tabla
	//COMIENZA
	//Primer paso: pegar este bloque de jquery
	//Segundo paso: crear el titulo estatico
	//Tercer paso: crear el div que contendra el contenido
	//Cuarto paso: quitar anchos en tablas
	//Quinto paso: modificar el css
	//Sexto paso: height 0 en titulo real
	var ancho,texto;
	for(i=1;i<=16;i++)
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
		
		 border-left: 1px solid;
	}

	.razon_social
	{
		background-color:#f6f7f8;
	}

	.derecha
	{
		text-align: center;
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

		#imp_rep{
			width: 100%;
		}

		table{
			zoom: 0.8 !important;
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
				&nbsp;Auxiliar de movimientos de control de IVA<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>  
					<a href="javascript:pdf();" id='pdf'><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>  
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a> 
					<!--  <a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a> -->  
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=auxiliar_controlIva&f=Inicial' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros'><img src="../../netwarelog/repolog/img/filtros.png" border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-1">
				</div>
				<div class="col-md-10" id="imp_rep">
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
							<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
								<?php
								echo $fecha;
								?>
							</div>
						</div>
						<div class="row" id='divcon'>
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table class="table" border='0' style="width:100%;font-size:8px;" cellpadding=3 id='resultados'>
										<thead>
										<tr style='height:30px;background-color:#edeff1;font-size:9px;'>
											<td width='6%'># Poliza</td>
											<td width='6%'>Fecha</td>
											<td width='6%'>Tipo Poliza</td>
											<td width='6%'>Referencia</td>
											<td width='6%'>Ejercicio</td>
											<td width='6%'>Periodo Acreditamiento</td>
											<td width='6%'>Proveedor</td>
											<td width='7%'>Importe Base</td>
											<td width='6%'>Otras Erogaciones</td>
											<td width='6%'>Tasa</td>
											<td width='6%'>Importe IVA</td>
											<td width='7%'>Importe Antes de Retenciones</td>
											<td width='7%'>IVA Retenido</td>
											<td width='7%'>ISR Retenido</td>
											<td width='6%'>Total Erogaci&oacute;n</td>
											<td width='6%'>Iva Pagado No Acreditable</td>
										</tr>
									   </thead>
										<?php
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

											$importeIVA = $d->importeBase * ($d->tasa / 100);
											$importeAntes = $d->importeBase + $d->otrasErogaciones + $importeIVA;
											echo "<tr style='background-color:$color;font-size:8px;' tipo='numero'  style='height: 30px !important;font-size:8px;'>
													<td class=''  ><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$d->id&idProveedor=$d->idProveedor&fecha=$d->fecha' target='_blank'>$d->idperiodo/$d->numpol</a></td>
													<td class='' >$d->fecha</td>
													<td class='derecha '>$d->tipoPoliza</td>
													<td class='' >$d->referencia</td>
													<td class='' >$d->ejercicio</td>
													<td class='' >$d->periodoAcreditamiento</td>
													<td class='' >$d->proveedor</td>
													<td class='' style='text-align:right;'>".number_format($d->importeBase,2)."</td>
													<td class='' style='text-align:right;'>".number_format($d->otrasErogaciones,2)."</td>
													<td class='' style='text-align:right;'>$d->tasaValor</td>
													<td class='' style='text-align:right;' >".number_format($importeIVA,2)."</td>
													<td class='' style='text-align:right;' >".number_format($importeAntes,2)."</td>
													<td class='' style='text-align:right;' >".number_format($d->ivaRetenido,2)."</td>
													<td class='' style='text-align:right;' >".number_format($d->isrRetenido,2)."</td>
													<td class='' style='text-align:right;' >".number_format($importeAntes - $d->ivaRetenido - $d->isrRetenido,2)."</td>
													<td class='' style='text-align:right;' >".number_format($d->ivaPagadoNoAcreditable,2)."</td>";
													echo "</tr>";

											$cont++;//Incrementa contador

										}
										?>
										<tr  id='xx' style='text-align:center;font-size:8px;font-weight:bold;background-color:#edeff1;' tipo='total'><td>Totales:</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
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
					<input type='hidden' name='nombreDocu' value='Auxiliar de control de iva'>
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