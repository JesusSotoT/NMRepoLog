<?php
if($toexcel==1){
 header("Content-Type: application/vnd.ms-excel");
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 header("content-disposition: attachment;filename=DeclaracionR21.xls");
 }
?>
<html>
<head>
	<script language='javascript' src='js/pdfmail.js'></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
	<title>Declaración R21 IVA </title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<?php 
	$concepto_r21="text-align:left;width:35%;";
	$valor_r21="text-align:right;width:12%;";
	$esp_medio="width:6%;";
	$titulo_v="background-color:#edeff1;font-weight:bold;height:30px;"
	?>
	<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
	<style type="text/css">
		.titulo_r21{text-align: center; font: 23px arial; border: 0px}
		.sub{text-align: center; font: 16px arial; font-weight: bold; border-top: 2px solid; border-bottom: 2px solid;}
		
		.valor_r21{width: 75px; text-align: right; font: 13px arial; vertical-align: top;}
		.esp_medio{width: 30px;}
		.titulo_v{background-color: #4c4c4c;color: white;}
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
	
<?php
	if($toexcel==0){//se muestra reporte en navegador
?>	
	<!--LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />	
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" /-->
	<link rel="stylesheet" href="css/style.css" type="text/css"> 		
	
<?php }?>
</head>	
<body >

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Declaración R21 de Impuesto al Valor Agregado<br>
				<section id="botones">
					<a href="javascript:window.print();"><img class="nmwaicons" border="0" src="../../netwarelog/design/default/impresora.png"></a>
					<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a> 
					<a href="javascript:mail();"><img border="0" title="Enviar reporte por correo electrónico" src="../../../webapp/netwarelog/repolog/img/email.png"></a>
					<a id="filtros" onclick="" href="index.php?c=declaracionR21&f=filtro"><img border="0" title="Haga click aquí para cambiar los filtros..." src="../../netwarelog/repolog/img/filtros.png"></a>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
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
								<b style="font-size:18px;color:black;"><?php echo $organizacion->nombreorganizacion; ?></b><br>
								<b style="font-size:15px;">Declaración R21</b><br>
								Ejercicio <b><?php echo $ejercicio->NombreEjercicio ?></b> Periodo <b> <?php echo $meses[$per_ini]; ?></b>
							</div>
						</div>
						<div class="row" id='divcon'>
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table border="0" cellpadding="3" align="center" class="table_r21 busqueda table" style="width:100%;max-width:900px;font-size:10px;">
										<tr style="text-align:left; <?php echo $titulo_v; ?>"><td colspan="5" ><a href="index.php?c=auxiliar_impuestos&f=reporte&considera_per=1&sel_ejercicio=<?php echo $ejercicio->id ?>&per_ini=<?php echo $per_ini ?>&per_fin=<?php echo $per_ini ?>&radio_mov=1&tasa_sel=-" target='_blank'>Montos pagados</a></td></tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>" >Total de actos o actividades pagados a la tasa del 16% de IVA</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIva16->base,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Total de actos o actividades pagados a la tasa del 11% de IVA</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIva11->base,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Total de actos o actividades pagados en la importacion de bienes o servicios a la tasa del 16% de IVA</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalbaseimport16->base,2,'.',','); ?></td>  
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Total de los demas actos o actividades pagados a la tasa del 0% de IVA</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIva0->base,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Total de actos o actividades pagados en la importacion de bienes o servicios a la tasa del 11% de IVA</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalbaseimport11->base,2,'.',','); ?></td>  
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Total de los demas actos o actividades pagados por lo que no se pagara el IVA (Excentos) </td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIvaExcento->base,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr style="text-align:left; <?php echo $titulo_v; ?>"><td colspan="5" ><a href="index.php?c=auxiliar_impuestos&f=reporte&considera_per=1&sel_ejercicio=<?php echo $ejercicio->id ?>&per_ini=<?php echo $per_ini ?>&per_fin=<?php echo $per_ini ?>&radio_mov=1&tasa_sel=-" target='_blank'>Determinación del impuesto al valor agregado acreditable</a></td></tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Total de IVA de actos o actividades pagados a la tasa del 16%</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalTasaIvaAcr16->IVA,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Total de IVA correspondiente a actos o actividades gravados</td>
											<td style="<?php echo $valor_r21; ?>"><?php  echo number_format($sumagravados,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Total de IVA de actos o actividades pagados a la tasa del 11%</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalTasaIvaAcr11->IVA,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">IVA trasladado o pagado en la importación por adquisición de bienes distintos de las inversiones, adquisición de servicios o por el uso o goce temporal de bienes destinados exclusivamente para realizar actos o actividades por los que no se se está obligado al pago del impuesto</td>
							 				<td style="<?php echo $valor_r21; ?>" id="gocetemporal"><?php echo number_format($arr['GastosExentos'],2,'.',','); ?></td> 
											</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Total de IVA de actos o actividades pagados en la importación de bienes y servicios a la tasa del 16%</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($ivaimport16->IVA,2,'.',','); ?></td>  
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">IVA trasladado o pagado en la importación de inversiones destinadas exclusivamente para realizar actos o actividades por los que que no se está obligado al pago del impuesto</td>
											<td style="<?php echo $valor_r21; ?>" id="importacioninversiones" ><?php echo number_format($arr['InvExentos'],2,'.',','); ?></td>  
											</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Total de IVA de actos o actividades pagados en la importación de bienes y servicios a la tasa del 11%</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($ivaimport11->IVA,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">IVA de bienes utilizados indistintamente para realizar actos o actividades gravados y actos o actividades por los que no se está obligado al pago del impuesto</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($ivabienesutilizados,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Proporción utilizada conforme al artículo 5-B de la LIVA</td>
											<td style="<?php echo $valor_r21; ?>"><?php if($prop_select==2){echo number_format($prop,4,'.',',');} else{echo number_format(0,4,'.',',');} ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Proporción utilizada conforme al artículo 5 de la LIVA</td>
											<td style="<?php echo $valor_r21; ?>"><?php if($prop_select==1){echo number_format($prop,4,'.',',');} else{echo number_format(0,4,'.',',');} ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Total de IVA trasladado al contribuyente (Efectivamente pagado)</td>
											
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($efectivamentepagado,2,'.',',');  ?></td>				
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">IVA acreditable de bienes utilizados indistintamente para realizar actos o actividades gravados y actos o actividades por los que no se está obligado al pago del impuesto</td>
											<td style="<?php echo $valor_r21; ?>" id="multipliart5"><?php echo number_format($multipliart5,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">IVA trasladado por adquisición de bienes distintos de las inversiones, adquisición de servicios o por el uso o goce temporal de bienes que se utilizan exclusivamente para realizar actos o actividades gravados</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($arr['GastosGravadosNacional'],2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">IVA acreditable</td>
											<td style="<?php echo $valor_r21; ?>" id="ivaacreditable"><?php echo number_format($ivaacreditable,2,'.',','); ?></td>

										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">IVA pagado en la importación por adquisición de bienes distintos de las inversiones, adquisición de servicios o por el uso o goce temporal de bienes que se utilizan exclusivamente para realizar actos o actividades gravados</td>
											<td style="<?php echo $valor_r21; ?>" id="ivaimporgosetemp"><?php echo number_format($arr['GastosGravadosExtrangeros'],2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Monto acreditable actualizado a incrementar derivado del ajuste</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($montoAjuste,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">IVA trasladado por la adquisición de inversiones destinadas exclusivamente para realizar actos o actividades gravados</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($arr['InvGravadosNacional'],2,'.',','); ?></td> <!-- en este se debe verificar q si tiene iva pagado no acreditable se reste con importe iva y no el importe base  y ese sera el new import bas -->
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Total IVA acreditable de periodo</td>
											<td style="<?php echo $valor_r21; ?>" id="totalacreditable" ><?php echo number_format($totalacreditable,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila"><td style="<?php echo $concepto_r21; ?>">IVA pagado por la importación de inversiones destinadas exclusivamente para realizar actos o actividades gravados</td>
											<td style="<?php echo $valor_r21; ?>" id="tipoiva"><?php echo number_format($arr['InvGravadosExtrangeros'],2,'.',','); ?></td> 
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>"></td>
											<td style="<?php echo $valor_r21; ?>"></td></tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>

										<tr style="text-align:left; <?php echo $titulo_v; ?>"><td colspan="5" ><a href="index.php?c=auxiliar_impuestos&f=reporte&considera_per=1&sel_ejercicio=<?php echo $ejercicio->id ?>&per_ini=<?php echo $per_ini ?>&per_fin=<?php echo $per_ini ?>&radio_mov=0&tasa_sel=-" target='_blank'>Determinación del Impuesto al Valor Agregado</a></td></tr>
										
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Valor de los actos o actividades gravados a la tasa del 16%</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIvaImp16,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Otras cantidades a cargo del contribuyente</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($otrasCargo,2,'.',',') ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Valor de los actos o actividades gravados a la tasa del 11%</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIvaImp11,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Otras cantidades a favor del contribuyente</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($otrasFavor,2,'.',',') ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Valor de los actos o actividades gravados a la tasa del 0% exportación</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIvacausa0,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Cantidad a cargo</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($cargo,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Valor de los actos o actividades gravados a la tasa del 0% otros</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIvacausaotros,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Saldo a favor</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($favor,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Suma de los actos o actividades gravados</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($sumaactosgravados,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Devolución inmediata obtenida</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($devolucionObtenida,2,'.',',') ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Acreditamientos del saldo a favor de periodos anteriores (Sin exceder de la cantidad a cargo)</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($acredAnteriores,2,'.',',') ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Saldo a favor del periodo</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($saldofavorperiodo,2,'.',',') ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Valor de los actos o actividades por los que no se deba pagar del impuesto (Exentos)</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($totalBaseIvacausaExenta,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Diferencia a cargo</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($diferenciacargo,2,'.',',') ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Impuesto causado</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($impuestocausado,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">IEPS acreditable de alcohol, alcohol desnaturalizado y mieles incristalizable de productos distintos de bebidas alcoholicas</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($iepsAcred,2,'.',',') ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Cantidad actualizada a reintegrarse derivada del ajuste</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($cantidadReintegrarse,2,'.',',') ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Impuesto a cargo</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($impuestocargoresult,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">IVA retenido al contribuyente</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($retenidocontri,2,'.',','); ?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>">Remanente de saldo a favor IEPS acreditable de alcohol, alcohol desnaturalizado y mieles incristalizable de productos distintos de bebidas alcohólicas</td>
											<td style="<?php echo $valor_r21; ?>"><?php echo number_format($remateieps,2,'.',','); ?></td>
										</tr>
										<tr class="busqueda_fila">
											<td colspan="5">&nbsp;</td>
										</tr>
										<tr class="busqueda_fila">
											<td style="<?php echo $concepto_r21; ?>">Total de IVA acreditable</td>
											<td style="<?php echo $valor_r21; ?>" ><?php echo number_format($totalacreditable,2,'.',',');?></td>
											<td style="<?php echo $esp_medio; ?>"></td>
											<td style="<?php echo $concepto_r21; ?>"></td>
											<td style="<?php echo $valor_r21; ?>"></td>
										</tr>

										<tfoot><tr><th colspan=5> Reporte R21</th></tr></tfoot>
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


<?php if($toexcel==0){?>	
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
<?php }?>
</body>
</html>