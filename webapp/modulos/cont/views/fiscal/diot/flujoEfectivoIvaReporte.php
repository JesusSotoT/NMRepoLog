<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<!--LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
<link rel="stylesheet" href="css/style.css" type="text/csss">
<script language='javascript'>
$(document).ready(function()
{

	$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};


	//var total = 0;
	var subtotal = cantidad = 0;
	for(var f = 5; f <= 10; f++)//Recorre cada columna
	{
		$('#xx').append("<td id='"+f+"'></td>");//Anexa un elemento a la tabla
		//if(f!=9)
		//{
			$("#resultados tr").each(function(index)//Recorre cada fila
			{
				if($(this).attr('tipo') == 'numero')//Si es un elemento de tipo numero hace la suma de los resulados
				{
					//total += parseFloat($("td:nth-child("+f+")",this).text())//Sumatoria total
					cantidad = parseFloat($("td:nth-child("+f+")",this).attr('cantidad'));
					if(!cantidad)
						cantidad = 0
					subtotal += cantidad//Sumatoria del proveedor
				}
				if($(this).next().attr('tipo') == 'subtotal' && $(this).next().next().attr('tipo') != 'numero')//Si es un campo del tipo subtotal y el siguiente elemento no es de tipo numero entonces agrega elementos
				{
					if(f==5)//si se trata del primer barrido agraga td`s de relleno a la tabla
					{
						$(this).next().append("<td colspan=2>Total Cuenta:</td><td></td><td></td>");
					}
					
					if(isNaN(subtotal)){subtotal=0;}

					
						$(this).next().append("<td style='text-align:right;'>$"+subtotal.format()+"</td>");//Agrega la suma del subtotal
					
					subtotal=0;	//se reinicia la suma
				}
			});	
		//if(isNaN(total)){total=0;}
		
		//$("#"+f).text(total.toFixed(2));//Agrega el total al elemento
		//total = 0;
		//}
	}

});
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>

	.proveedor
	{
	background-color:#FFFFFF;
	}
	.proveedor2
	{
	background-color:#f6f7f8;
	}

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
				&nbsp;Conciliaci&oacute;n de Flujo de efectivo e IVA<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
					<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=flujoEfectivoIva&f=Inicial' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros'><img src="../../netwarelog/repolog/img/filtros.png" border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
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
									<table class="table" style='width:100%;font-size:8px;max-width:900px;' cellpadding=3 id='resultados' align="center">
										<thead>
											<tr style="background-color:#edeff1;font-size:9px;font-weight:bold;height:30px;" >
											<td style='width:7%;'>Periodo / Poliza</td>
											<td style='width:10%;'>Fecha</td>
											<td style='width:10%;'>Tipo Poliza</td>
											<td style='width:15%;'>Concepto</td>
											<td style='width:12%;'>Abonos Flujo de Efectivo</td>
											<td style='width:8%;'>Total</td>
											<td style='width:8%;'>Base</td>
											<td style='width:8%;'>IVA</td>
											<td style='width:11%;'>Iva Pagado No Acreditable</td>
											<td style='width:11%;'>Otras Erogaciones</td>
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
									    		$color='#fafafa';
											}

											if($anterior != $d->account_code)
											{
												echo "<tr style='height:5px;'><td colspan='10' style='height:5px;'></td></tr>
												<tr class='razon_social' style='background-color:#edeff1;font-weight:bold;height:30px;'>
												<td colspan=2 title='Cuenta'>$d->manual_code</td>
												<td colspan=3>$d->description</td>
												<td colspan=3></td>
												<td colspan=4></td>
												</tr>";
											}

											echo "<tr tipo='numero' style='height:30px !important;background-color:$color;'>
													<td title='Periodo/Poliza'><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$d->id&idProveedor=".intval($d->idProveedor)."&fecha=$d->fecha' target='_blank'>$d->idperiodo/$d->numpol</a></td>
													<td title='Fecha'>$d->fecha</td>
													<td class='derecha' title='Tipo de poliza'>$d->TipoPoliza</td>
													<td title='Concepto'>$d->concepto</td>
													<td title='Abonos a Flujo de Efectivo' style='text-align:right;' cantidad='".$d->TotalAbonos."'>".number_format($d->TotalAbonos,2)."</td>
													<td title='Total' style='text-align:right;' cantidad='".$d->Total."'>".number_format($d->Total,2)."</td>
													<td title='Importe Base' style='text-align:right;' cantidad='".$d->ImporteBase."'>".number_format($d->ImporteBase,2)."</td>
													<td title='Importe IVA' style='text-align:right;' cantidad='".$d->ImporteIva."'>".number_format($d->ImporteIva,2)."</td>
													<td title='IVA Pagado No Acreditable' style='text-align:right;' cantidad='".$d->IvaPagadoNoAcreditable."'>".number_format($d->IvaPagadoNoAcreditable,2)."</td>
													<td title='Otras Erogaciones' style='text-align:right;' cantidad='".$d->Erogaciones."'>".number_format($d->Erogaciones,2)."</td>";

													echo "</tr>";
													if($_POST['impDetalleProv'])
													{
														if($_POST['soloAplican'])
														{
															$ProvsCuenta = $this->ProvsCuenta($d->idPoliza,'Aplican');	
														}
														else
														{
															$ProvsCuenta = $this->ProvsCuenta($d->idPoliza,'Todas');	
														}
														while($p = $ProvsCuenta->fetch_object())
														{
															if($cont2%2==0)//Si el contador es par pinta esto en la fila del grid
															{
									    						$color2='proveedor';
															}
															else//Si es impar pinta esto
															{
									    						$color2='proveedor2';
															}
															$aplica='';
															if($p->aplica == 0)
															{
																$aplica=" (<b>No Aplica</b>)";
															}
															echo "<tr class='$color2' tipo='proveedor'><td></td><td></td><td colspan='3' style='text-align:left;'>$p->Proveedor.$aplica</td><td>".number_format($p->Total,2,'.','')."</td><td>".number_format($p->importeBase,2,'.','')."</td><td>".number_format($p->ImporteIva,2,'.','')."</td><td>".number_format($p->ivaPagadoNoAcreditable,2,'.','')."</td><td>".number_format($p->otrasErogaciones,2,'.','')."</td></tr>";
															$cont2++;
														}
													}
											echo "<tr id='$d->Cuenta' style='text-align:center;font-weight:bold;background-color:#edeff1;' tipo='subtotal'></tr>
													";

											$cont++;//Incrementa contador
											$anterior = $d->account_code;

										}
										?>
										<!--<tr  id='xx' style='text-align:center;font-weight:bold;background-color:#91C313;color:white;' tipo='total'><td>Totales:</td><td></td><td></td><td></td></tr>-->
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
					<input type='hidden' name='nombreDocu' value='Flujo de efectivo e iva'>
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