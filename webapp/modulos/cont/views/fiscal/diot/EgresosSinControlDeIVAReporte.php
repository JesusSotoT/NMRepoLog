<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript'>
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
$(document).ready(function(){
	$('#nmloader_div',window.parent.document).hide();
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};
	var sumatoria=0;
	var numero=0;
	$("tr").each(function(index)
	{
		if($('td:nth-child(6)',this).text() != '' && $('td:nth-child(6)',this).text() != 'Abono')
		{
			numero = $('td:nth-child(6)',this).text().replace(/\,/g,'').replace('$','');
			sumatoria += parseFloat(numero)
		}
	});
	$('#totales').text("$" + sumatoria.format());
	//Agregar este proceso para igualar los width de las columnas de la tabla
	//COMIENZA
	//Primer paso: pegar este bloque de jquery
	//Segundo paso: crear el titulo estatico
	//Tercer paso: crear el div que contendra el contenido
	//Cuarto paso: quitar anchos en tablas
	//Quinto paso: modificar el css
	//Sexto paso: height 0 en titulo real
	var ancho,texto;
	for(i=1;i<=6;i++)
	{
		ancho = $(".tit_tabla_buscari td:nth-child("+i+")").width();
		texto = $(".tit_tabla_buscari td:nth-child("+i+")").text();
		$(".estatico td:nth-child("+i+")").text(texto).width(ancho)
	}

	$("#resultados").attr('style','margin-top:-'+$(".tit_tabla_buscari").height()+'px;')
	//TERMINA
});			
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/style.css" type="text/css">
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style>
	.tit_tabla_buscar td
	{
		font-size:medium;
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

		#resultados
		{
			margin-top:40px !important;
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
    .table thead, .table tbody tr {
	    display:table;
	    width:100%;
	   	table-layout: fixed;/* even columns width , fix width of table too*/
	}
</style>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Egresos sin control de IVA<br>
				<section id="botones">
					<a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
                    <a href="index.php?c=EgresosSinIva&f=Inicial"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
					<a href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"> </a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
					<a href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"> </a>		
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<input type='hidden' value='Egresos sin control de IVA.' id='titulo'>
					<section id='imprimible'>
						<div class="row">
							<?php
							$url = explode('/modulos',$_SERVER['REQUEST_URI']);
							if($logo == 'logo.png') $logo = 'x.png';
							$logo = str_replace(' ', '%20', $logo);
							?>
							<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
								<b> <?php echo $impresion;?></b>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12" style='text-align:center;font-size:18px;'>
								<b style='text-align:center;'><?php echo $empresa;?></b>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
								<b style="font-size:15px;">Egresos sin control de IVA </b><br>
								Del <b><?php echo $inicio;?></b> Al <b><?php echo $fin;?></b> 
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
										<div class="table-responsive">
											<table border='0' align="center" cellpadding="3" style:"background-color:#ffffff;min-width:500px;max-width:900px;font-size:10px;">
												<thead>
												<tr style='height:22px;background-color:#edeff1;font-size:10px;font-weight:bold;'>
													<td style="min-width:100px;width:12%;">Periodo/Poliza</td>
													<td style="min-width:100px;width:12%;">Fecha</td>
													<td style="min-width:100px;width:15%;">Tipo Poliza</td>
													<td style="min-width:100px;width:25%;">Concepto</td>
													<td style="min-width:100px;width:15%;">Referencia</td>
													<td style="min-width:100px;width:21%;">Abono</td>
												</tr>
												</thead>
												<?php
												while($d = $datos->fetch_object())
												{
													if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
													{
											    		$color='#ffffff';
													}
													else//Si es impar pinta esto
													{
											    		$color='#f6f7f8';
													}
													echo "<tr style='height:22px;background-color:$color;text-align:center;font-size:10px;' >
															<td><a href='ajax.php?c=RepPeriodoAcreditamiento&f=verrepdetallado&idpoliza=$d->id&idProveedor=0&fecha=$d->fecha' target='_blank'>$d->idperiodo/$d->numpol</a></td>
															<td>$d->fecha</td>
															<td>$d->tipoPoliza</td>
															<td>$d->concepto</td>
															<td>$d->referencia</td>
															<td style='text-align:right;font-weight:bold;'>$".number_format($d->Erogacion,2)."</td></tr>";
													$cont++;//Incrementa contador
												}
												?>
												<tr style='height:22px;font-size:12px;font-weight:bold;text-align:right;background-color:#edeff1;'><td></td>
												<td></td>
												<td></td>
												<td></td><td>Total: </td><td id='totales' style:"padding:10px;"></td></tr>
											</table>
										</div>
									</div>
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
					<input type='hidden' name='nombreDocu' value='Egresos sin control de IVA'>
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