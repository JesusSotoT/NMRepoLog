
<html>
<head>
	<title>Auxiliar Impuestos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php
	if($toexcel==0){//se muestra reporte en navegador ?>
		<script language='javascript' src='js/pdfmail.js'></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
		<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<!--LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />	
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<?php //include('../../netwarelog/design/css.php');?>
<!--LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

	<?php
	}
	?>
	<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
	<style type="text/css">
		.titulo_aux{text-align: center; font: 20px arial; border: 0px solid; }
		.totales{text-align: right; font: 11px arial; font-weight: bolder; border-top: 1px solid;}
		.cabecera{ font: 11px arial; font-weight: bolder;  vertical-align: bottom; border: 0px solid; text-align: center;}
		.total_texto{font-weight: bolder; vertical-align: top;}
		.espacio{height: 20px;}
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
		.table tr, .table td{
			border: none !important;
		}
	</style>
</head>
<script>
	$(document).ready(function() {
		$('#nmloader_div',window.parent.document).hide();

	});
	function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<body>
<input type='hidden' value='Anexos IVA causado y acreditable.' id='titulo'>
<div id='imprimible'>
<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Anexos IVA causado y acreditable<br>
				<section id="botones">
					<?php
					if($toexcel==0){//se muestra reporte en navegador
					?>
						<a href="javascript:window.print();"><img  border="0" src="../../netwarelog/design/default/impresora.png" width="20px"></a>
						<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a> 
						<a href="javascript:mail();"><img border="0" title="Enviar reporte por correo electrónico" src="../../../webapp/netwarelog/repolog/img/email.png"></a>
						<img border="0" src="../../netwarelog/repolog/img/filtros.png" title="Haga click aquí para cambiar los filtros..." onclick="regreso()">
						<img src="images/images.jpg" title="Exportar a Excel" onclick="generaexcel()" width="20px" height="20px"> 
					<?php
					}
					?>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<section id='imprimible'>
						<div class="row">
							<?php
							$logo=$organizacion->logoempresa;
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
								<b style='font-size:18px;color:black;'><?php echo $organizacion->nombreorganizacion; ?></b><br>
								<b style='font-size:15px;'>Anexo IVA causado acreditable</b><br>
								<b style='font-size:12px;'>Periodo de Acreditamiento <?php echo $meses[$ini].' - '.$meses[$fin].'  '.$ejercicio->NombreEjercicio; ?></b>
							</div>
						</div>
						<div class="row" id='divcon'>
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table border="0" cellpadding="3" style="width:100%;font-size:11px;" class="table">
										
										<tbody>
											
											<tr style="background-color:#edeff1;font-weight:bold;text-align:left;">
												<td width="50%"><a href="index.php?c=auxiliar_impuestos&f=reporte&considera_per=<?php echo $per ?>&sel_ejercicio=<?php echo $ejer ?>&<?php echo "$per_fech_ini=".$ini ?>&<?php echo "$per_fech_fin=".$fin ?>&radio_mov=0&tasa_sel=-" target='_blank'>-- IVA CAUSADO --</a></td><td width="25%">&nbsp;</td><td width="25%">&nbsp;</td>
											</tr>
											<tr class="">
												<td class="" >Actos o Actividades Gravados al 16%</td><td class="" >&nbsp;</td><td class="" align="right"><?php  echo number_format($arr["tasa16"]->baset,2,'.',','); ?></td>
											</tr>
											<tr class="">
												<td class="" >Actos o Actividades Gravados al 11%</td><td class="" >&nbsp;</td><td class="" align="right"><?php  echo number_format(@$arr["tasa11"]->baset,2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >Actos o Actividades Gravados al 0%</td><td class="" >&nbsp;</td><td class="" align="right"><?php  echo number_format(@$arr["tasa0"]->baset,2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >Actos o Actividades Exentos</td><td class="" >&nbsp;</td><td class="" align="right"><?php  echo number_format(@$arr["tasaExenta"]->baset,2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >Actos o Actividades en Otras Tasas</td><td class="" >&nbsp;</td><td class="" align="right"><?php  echo number_format(@$arr["otrasTasas"]->baset,2,'.',','); ?></td>
												
											</tr>
											<tr style="background-color:#edeff1;">
												<td class="total_texto" >Suma Actos o Actividades Gravados</td><td class="" >&nbsp;</td><td class="totales" align="right"><?php echo number_format(@$arr["tasa16"]->baset+@$arr["tasa11"]->baset+@$arr["tasa0"]->baset+@$arr["tasaExenta"]->baset+@$arr["otrasTasas"]->baset,2,'.',','); ?></td>
											</tr>


											<tr>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
											</tr>


											<tr>
												<td class="" >IVA Causado al 16%</td><td class="" >&nbsp;</td><td class="" align="right"><?php  echo number_format(@$arr["tasa16"]->ivat,2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >IVA Causado al 11%</td><td class="" >&nbsp;</td><td class="" align="right"><?php  echo number_format(@$arr["tasa11"]->ivat,2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >IVA Retenido</td><td class="" >&nbsp;</td><td class="" align="right"><?php  echo number_format(@$arr["ivaRetenido"],2,'.',','); ?></td>
												
											</tr>
											<tr style="background-color:#edeff1;">
												<td class="total_texto" >Total IVA Causado</td><td class="" >&nbsp;</td><td class="totales" align="right"><?php  $a=$arr["tasa16"]->ivat+$arr["tasa11"]->ivat-$arr["ivaRetenido"]; echo number_format($a,2,'.',',');?></td>
											</tr>

											<tr>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
											</tr>

											<tr style="background-color:#edeff1;font-weight:bold;">
												<td >-- COMPRAS Y GASTOS --</td><td class="" >&nbsp;</td><td >&nbsp;</td>
											</tr>
											<tr>
												<td class="" >Compras y Gastos al 16%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$baseComprado["16%"],2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >Compras y Gastos al 11%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$baseComprado["11%"],2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >Compras y Gastos al 0%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$baseComprado["0%"],2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >Compras y Gastos Exentos</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$baseComprado["Exenta"],2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >Compras y Gastos en Otras Tasas</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$baseComprado["Otra Tasa 1"]+@$baseComprado["Otra Tasa 2"],2,'.',','); ?></td>
												
											</tr>
											
											<tr style="background-color:#edeff1;">
												<td class="total_texto" >Total Compras y Gastos Gravados</td><td class="" >&nbsp;</td><td class="totales" align="right"><?php echo number_format(@$totalBaseComprado,2,'.',','); ?></td>
											</tr>

											<tr>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
											</tr>
												
											<tr>
												<td class="" >IVA de Compras y Gastos al 16%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$ivaComprado["16%"],2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >IVA de Compras y Gastos al 11%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$ivaComprado["11%"],2,'.',','); ?></td>
											</tr>
											<tr>
												<td class="" >IVA de Compras y Gastos en Otras Tasas</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$ivaComprado["Otra Tasa 1"]+@$ivaComprado["Otra Tasa 2"],2,'.',','); ?></td>
											</tr>
											<?php if($acredita==0){ ?>
												
											
											<tr>
												<td class="" >IVA Retenido</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$baseComprado["ivaretenido"],2,'.',','); ?></td>
												
											</tr>
											<?php }else{ $baseComprado["ivaretenido"]=0;} ?>
											<tr style="background-color:#edeff1;">
												<td class="total_texto" >Total IVA de Compras y Gastos </td><td class="" >&nbsp;</td><td class="totales" align="right"><?php echo number_format(@$ivaComprado["16%"]+@$ivaComprado["11%"]+@$ivaComprado["Otra Tasa 1"]+@$ivaComprado["Otra Tasa 2"]+$baseComprado["ivaretenido"],2,'.',','); ?></td>
											</tr>

											<tr>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
											</tr>


											<tr style="background-color:#edeff1;font-weight:bold;">
												<td ><a href="index.php?c=auxiliar_impuestos&f=reporte&considera_per=<?php echo $per ?>&sel_ejercicio=<?php echo $ejer ?>&<?php echo "$per_fech_ini=".$ini ?>&<?php echo "$per_fech_fin=".$fin ?>&radio_mov=1&tasa_sel=-" target='_blank'>-- IVA PAGADO ACREDITABLE --</a></td><td class="" >&nbsp;</td><td >&nbsp;</td>
											</tr>
											<tr>
												<td class="" >Actos y Actividades Pagados al 16%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$arr['16%']->baseacr,2,".",","); ?></td>
											</tr>
											<tr>
												<td class="" >Actos y Actividades Pagados al 11%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$arr['11%']->baseacr,2,".",","); ?></td>
											</tr>
											<tr>
												<td class="" >Actos y Actividades Pagados al 0%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$arr['0%']->baseacr,2,".",","); ?></td>
											</tr>
											<tr>
												<td class="" >Actos y Actividades Exentos</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$arr['Exenta']->baseacr,2,".",","); ?></td>
											</tr>
											<tr style="background-color:#edeff1;">
												<td class="total_texto" >Total Actos y Actividades Pagados</td><td class="" >&nbsp;</td><td class="totales" align="right"><?php echo @number_format(@$arr['16%']->baseacr+@$arr['11%']->baseacr+@$arr['0%']->baseacr+@$arr['Exenta']->baseacr,2,'.',',');?></td>
											</tr>

											<tr>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
											</tr>
												
											<tr>
												<td class="" >IVA de Actos y Actividades Pagados al 16%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$arr['16%']->ivacredit,2,".",","); ?></td>
											</tr>
											<tr>
												<td class="" >IVA de Actos y Actividades Pagados al 11%</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$arr['11%']->ivacredit,2,".",","); ?></td>
											</tr>
											<?php if($acredita==0){ ?>
											<tr>
												<td class="" >IVA Retenido</td><td class="" >&nbsp;</td><td class=""align="right" ><?php echo number_format(@$arr['retenido'],2,'.',',');?></td>
											</tr>
											<tr>
												<td class="" >IVA Acreditable Retenido de Meses Anteriores</td><td class="" >&nbsp;</td><td class="" align="right"><?php echo number_format(@$arr['mes'],2,'.',','); ?></td>
											</tr>
											<?php } else {$arr['retenido']=0; $arr['mes']=0;} ?>
											<tr style="background-color:#edeff1;">
												<td class="total_texto" >Total IVA Acreditable</td><td class="" >&nbsp;</td><td class="totales" align="right"><?php  $b=@$arr['16%']->ivacredit+@$arr['11%']->ivacredit-@$arr['retenido']+@$arr['mes']; echo  number_format($b,2,'.',','); ?></td>
											</tr>

											<tr>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
											</tr>
											<tr>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
												<td class="espacio" ></td>
											</tr>

											<tr style="background-color:#edeff1;font-weight:bold;">
												<td >-- DETERMINACION DEL IVA --</td><td class="" >&nbsp;</td><td >&nbsp;</td>
											</tr>
											<tr>
												<?php $r=$a-$b;  ?>
												<td class="total_texto" >IVA a Cargo</td><td class="" >&nbsp;</td><td class="total_texto numero" align="right"><?php if($r>=0){ echo number_format($r,2,'.',',');}else{ echo "0.0000"; }?></td>
											</tr>
											<tr>
												<td class="total_texto" >IVA a Favor</td><td class="" >&nbsp;</td><td class="total_texto numero" align="right"><?php if($r<0){ echo number_format($r,2,'.',',');}else{ echo "0.0000"; }?></td>
											</tr>

										</tbody>
										<tfoot>
										</tfoot>
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
</div>


<?php if($toexcel==0){	?>
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
					<input type='hidden' name='nombreDocu' value='Resumen General R21'>
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
<?php }?>
</body>
</html>