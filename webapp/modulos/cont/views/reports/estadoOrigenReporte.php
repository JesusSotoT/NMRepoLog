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

	$('.clasif-Clasificacion').remove()
	$(".clasif-Activo:contains('TOTAL GRUPO')").remove()

	var activo, pasivo, capital, resultados, total;
	activo = $("#sum1-Activo").attr('cantidad')
	if(isNaN(activo))
	{
		activo = 0;
	}
	pasivo = $("#sum1-Pasivo").attr('cantidad')
	if(isNaN(pasivo))
	{
		pasivo = 0;
	}
	capital = $("#sum1-Capital").attr('cantidad')
	if(isNaN(capital))
	{
		capital = 0;
	}
	resultados = $(".sum1-Resultados").attr('cantidad')
	if(isNaN(resultados))
	{
		resultados = 0;
	}
	total = parseFloat(activo) + parseFloat(pasivo) + parseFloat(capital) + parseFloat(resultados)

	$("#sumaTotal1").html("$ "+total.format())

	activo = $("#sum2-Activo").attr('cantidad')
	if(isNaN(activo))
	{
		activo = 0;
	}
	pasivo = $("#sum2-Pasivo").attr('cantidad')
	if(isNaN(pasivo))
	{
		pasivo = 0;
	}
	capital = $("#sum2-Capital").attr('cantidad')
	if(isNaN(capital))
	{
		capital = 0;
	}
	resultados = $(".sum2-Resultados").attr('cantidad')
	if(isNaN(resultados))
	{
		resultados = 0;
	}
	total = parseFloat(activo) + parseFloat(pasivo) + parseFloat(capital) + parseFloat(resultados)

	$("#sumaTotal2").html("$ "+total.format())

	$('#total-resultados2').html($('#total-resultados').html())
	$('#total-resultados2').css({'font-weight':'bold'})

	$('#total-resultados').remove()

	$(".clasif-Resultados").remove()
	$(".quitar").remove()

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
$moneda=$_POST['moneda'];
if($_POST['valMon']){$valMon=$_POST['valMon'];}else{$valMon=1;}

$titulo1="font-size:10px;background-color:#f6f7f8;font-weight:bold;height:30px;";
$subtitulo="font-size:10px;font-weight:bold;height:30px;background-color:#fafafa;text-align:left;margin-left:10px;"

?>

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Estado de Origen y Aplicacion de Recursos<br>
				<section id="botones">
					<a href="javascript:window.print();" id='imprimir'><img class="nmwaicons" src="../../netwarelog/design/default/impresora.png" border="0" title='Imprimir'></a>
					<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>   
					<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a>   
					<a href="javascript:mail();"><img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"></a>
					<a href='index.php?c=reports&f=balanceGeneral&tipo=2' onclick="javascript:$('#nmloader_div',window.parent.document).show();" id='filtros' onclick="javascript:$('#nmloader_div',window.parent.document).hide();"><img src="../../netwarelog/repolog/img/filtros.png"  border="0" title='Haga click aqu&iacute; para cambiar los filtros...'></a>	
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<input type='hidden' value='Estado de Origen y aplicacion de producto.' id='titulo'>
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
								<b style='text-align:center;'><?php echo $empresa;?></b>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
								<b style="font-size:15px;">Estado de Origen y Aplicación</b><br>
								Ejercicio <b><?php echo $ej; ?></b> Periodo <b><?php echo $periodo; ?></b><br> 
							    Sucursal <b><?php echo $nomSucursal; ?></b> Segmento <b><?php echo $nomSegmento; ?></b> 
							    <?php if($valMon>1){echo "<br>Moneda <b>$moneda</b> Tipo de Cambio $ <b>$valMon</b>";}?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-1 col-sm-1">
									</div>
									<div class="col-md-10 col-sm-10">
										<div class="row">
											<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
												<div class="table-responsive">
													<table class="table" border=0 style='min-width:650px;font-size:10px;' cellpadding="3">
														<thead>
														<tr style='<?php echo "$titulo1"; ?>'>
															<td style='min-width:120px;width:15%;'>No. DE CUENTA</td>
															<td style='min-width:220px;width:45%;'>DESCRIPCIÓN</td>
															<td style='min-width:150px;width:20%;' class="text-right">ORIGEN</td>
															<td style='min-width:150px;width:20%;' class="text-right">APLICACIÓN</td>
														</tr>
														<tr id='total-resultados2' style='<?php echo"$titulo1"; ?>;text-align:left;'></tr>
														
														</thead>			
																	
														
														<?php
														//Carga los Pasivo, Capital y Resutados******************************************************************************
														$clasifAnterior='Clasificacion';//Almacena la clasificacion anterior
														//$grupoAnterior='Grupo';
														$sumaOrigen = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
														//$sumaOrigenGrupo = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
														$sumaApli = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
														//$sumaApliGrupo = 0;//Almacena la sumatoria de las cantidades de la cuenta de mayor
														while($d = $datos->fetch_object())
														{

															if(intval($_POST['idioma']))
															{
																$grupoNombre = $d->Grupo_Alt;
																$grupoAnteriorNombre = $grupoAnterior_Alt;
																$clasNombre = $d->Clasificacion_Alt;
																$clasAnteriorNombre = $clasifAnterior_Alt;
																$y = 'RESULTS';
															}
															else
															{
																$grupoNombre = $d->Grupo;
																$grupoAnteriorNombre = $grupoAnterior;
																$clasNombre = $d->Clasificacion;
																$clasAnteriorNombre = $clasifAnterior;
																$y = "RESULTADOS";
															}
															$CM = explode(' / ',$d->Cuenta_de_Mayor,2);
															if($clasifAnterior != $d->Clasificacion)
															{
																/*if($grupoAnterior != $d->Grupo)
																{
																	echo "<tr style='font-weight:bold;height:30px;' class='clasif-$clasifAnterior'><td colspan='2'>TOTAL ".strtoupper($grupoAnterior)."</td><td id='sumG-$grupoAnterior' style='text-align:right;'>$ ".number_format($sumaOrigenGrupo,2)."</td><td id='sumG-$grupoAnterior' style='text-align:right;$red'>$ ".number_format($sumaApliGrupo,2)."</td></tr>";
																	$sumaOrigenGrupo = 0;
																	$sumaApliGrupo = 0;
																}*/

																//comienza cuenta de clasificacion
																$red='';
																echo "<tr style='$subtitulo' class='clasif-$clasifAnterior'><td></td><td>TOTAL ".strtoupper($clasAnteriorNombre)."</td><td id='sum1-$clasifAnterior' style='text-align:right;' cantidad='".number_format($sumaOrigen,2,'.','')."'>$ ".number_format($sumaOrigen,2)."</td><td id='sum2-$clasifAnterior' style='text-align:right;' cantidad='".number_format($sumaApli,2,'.','')."'>$ ".number_format($sumaApli,2)."</td></tr>";
																$sumaOrigen = 0;
																$sumaApli = 0;
																echo "<tr class='clasif-$clasifAnterior'><td></td><td></td><td></td><td></td></tr>";	
																echo "<tr style='$subtitulo' class='clasif-$d->Clasificacion'><td></td><td>".strtoupper($clasNombre)."</td><td></td>
																<td></td></tr>";	
																//termina cuenta de clasificacion
																
																/*if($grupoAnterior != $d->Grupo)
																{
																	echo "<tr><td colspan='3' class='clasif-$d->Clasificacion'></td></tr>";	
																	echo "<tr style='font-weight:bold;height:30px;' class='clasif-$d->Clasificacion'><td colspan='3'>".strtoupper($d->Grupo)."</td></tr>";	
																}*/
															}
															/*else
															{
																if($grupoAnterior != $d->Grupo)
																{
																	$red='';
																	echo "<tr style='font-weight:bold;height:30px;' class='clasif-$d->Clasificacion'><td colspan='2'>TOTAL ".strtoupper($grupoAnterior)."</td><td id='sumG-$grupoAnterior' style='text-align:right;'>$ ".number_format($sumaOrigenGrupo,2)."</td><td id='sumG-$grupoAnterior' style='text-align:right;'>$ ".number_format($sumaApliGrupo,2)."</td></tr>";
																	$sumaOrigenGrupo = 0;
																	$sumaApliGrupo = 0;
																	echo "<tr><td colspan='3' class='clasif-$d->Clasificacion'></td></tr>";	
																	echo "<tr style='font-weight:bold;height:30px;' class='clasif-$d->Clasificacion'><td colspan='3'>".strtoupper($d->Grupo)."</td></tr>";	
																}
															}*/
															$red='';
															$ResultadosAntes = $d->CargosAbonosAnterior/$valMon;
															$Resultados = $d->CargosAbonos/$valMon;
															
																if($d->Clasificacion != 'Activo')
																{
																	$Resultados *=-1;
																	$ResultadosAntes *=-1;
																	$resultadoParcial = $ResultadosAntes - $Resultados;
																}else
																{
																	$resultadoParcial = $Resultados - $ResultadosAntes;
																}
															
															if($resultadoParcial<0)
															{
																$origen = $resultadoParcial*-1;
																$aplicacion = 0;
															}

															if($resultadoParcial>0)
															{
																$origen = 0;
																$aplicacion = $resultadoParcial;
															}
															
																if($resultadoParcial!=0)
																{
																	echo "<tr class='clasif-$d->Clasificacion'><td style='mso-number-format:\"@\";'>".$d->Codigo."</td><td style='text-align:left;'>".$CM[1]."</td><td class='quitar'>$ResultadosAntes</td><td class='quitar'>$Resultados</td><td style='text-align:right;'>$ ".number_format($origen,2)."</td><td style='text-align:right;'>$ ".number_format($aplicacion,2)."</td></tr>";
																}
																
																//$sumaOrigenGrupo += $origen;
																$sumaOrigen += $origen;
																//$sumaApliGrupo += $aplicacion;
																$sumaApli += $aplicacion;
																$origen=0; $aplicacion=0;
															
															
															$clasifAnterior = $d->Clasificacion;
															$clasifAnterior_Alt = $d->Clasificacion_Alt;
															//$grupoAnterior = $d->Grupo;
															$red='';
															if(floatval($sumaCantidad) < 0) $red = "color:red;";
														}
														$sumaRes=$sumaOrigen - $sumaApli;

														if(floatval($sumaRes)>0)
														{
															$origenRes = $sumaRes;
															$apliRes = 0;
														}

														if(floatval($sumaRes)<0)
														{
															$origenRes = 0;
															$apliRes = $sumaRes*-1;
														}
														?>
														
														<tr style='<?php echo "$subtitulo"; ?>'><td></td><td>TOTAL</td><td id='sumaTotal1' style='text-align:right;'></td><td id='sumaTotal2' style='text-align:right;'></td></tr>
														<tr style='<?php echo "$titulo1";?>' id='total-resultados'><td></td><td>TOTAL <?php echo $y; ?></td><td class='sum1-<?php echo $clasifAnterior."' style='text-align:right;";?>' cantidad='<?php echo number_format($origenRes,2,'.',''); ?>'>$ <?php echo number_format($origenRes,2); ?></td><td class='sum2-<?php echo $clasifAnterior."' style='text-align:right;";?>' cantidad='<?php echo number_format($apliRes,2,'.',''); ?>'>$ <?php echo number_format($apliRes,2); ?></td></tr>
													</table>
												</div>
											</div>
										</div>
										<div class="col-md-1 col-sm-1">
										</div>
									</div>
								</div>
							</div>
						</div>
						<input type='hidden' id='totalMayores' value='<?php echo $sumaCont; ?>'>
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