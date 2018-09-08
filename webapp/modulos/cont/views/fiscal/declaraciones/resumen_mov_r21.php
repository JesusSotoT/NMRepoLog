<?php
if($toexcel==1){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=resumenR21.xls");
}
?>
<html>
<head>
	<title>Resumen de movimientos general para R21 IVA </title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<script language='javascript' src='js/pdfmail.js'></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
		<!-- 
		<script type="text/javascript" src="js/resumenGeneralR21.js"></script> -->

		<?php
		if($toexcel==0){//se muestra reporte en navegador
		?>
			<link rel="stylesheet" href="css/style.css" type="text/css">
		<?php } ?>
	
	<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
	<style type="text/css">
		.titulo_r21{text-align: center; font: 20px arial; border: 0px solid;}
		
		.fondoVerde{background-color: #4c4c4c;color: white;}
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
	</style>
</head>
<body >

<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Resumen de Movimientos General para R21<br>
				<section id="botones">
					<?php
					if($toexcel==0){//se muestra reporte en navegador
					?>
						<link rel="stylesheet" href="css/style.css" type="text/css">
					 	<a href="javascript:window.print();"><img class="nmwaicons" border="0" src="../../netwarelog/design/default/impresora.png"></a>
						<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a> 
						<a href="javascript:mail();"><img border="0" title="Enviar reporte por correo electrónico" src="../../../webapp/netwarelog/repolog/img/email.png"></a>
						<a id="filtros" href="index.php?c=resumenGeneralR21&f=filtro" onclick=""><img border="0" src="../../netwarelog/repolog/img/filtros.png" title="Haga click aquí para cambiar los filtros..."></a>
					<?php
					}
					?>
				</section>
			</h3>
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<section id='imprimible'>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
								<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3 text-center">
								<?php
								$logo=$organizacion->logoempresa;
								$url = explode('/modulos',$_SERVER['REQUEST_URI']);
								if($logo == 'logo.png') $logo = 'x.png';
								$logo = str_replace(' ', '%20', $logo);
								?>
								<img id='logo_empresa' src='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' height='55'>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
								<b style="color:black;font-size:18px;"><?php echo $organizacion->nombreorganizacion; ?></b><br>
								<b style="font-size:15px;">Resumen de Movimientos General para R21 IVA</b><br>
								RFC: <b><?php echo $organizacion->RFC; ?></b><br>
								Ejercicio <b><?php echo $ejercicio->NombreEjercicio;?></b> Periodo De <b><?php echo $meses[$per_ini]; ?></b> A <b><?php echo $meses[$per_fin]; ?></b>
							</div>
						</div>
						<div class="row" id='divcon'>
							<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
								<div class="table-responsive">
									<table class="table"  cellpadding="4" cellspacing="0" style="font-size:9px;">
										<tr style="background-color:#edeff1;font-weight:bold;"><td width="21%">Monto de los actos o actividades pagados</td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=1&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Enero</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=2&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Febrero</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=3&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Marzo</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=4&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Abril</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=5&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Mayo</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=6&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Junio</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=7&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Julio</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=8&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Agosto</a></td>
											<td width="7%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=9&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Septiembre</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=10&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Octubre</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=11&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Noviembre</a></td>
											<td width="6%" class="text-right"><a href="ajax.php?c=declaracionR21&f=reporte&ejercicio=<?php echo $ejercicio->id ?>&per_ini=12&acr_iva=<?php echo $_REQUEST['acr_iva'] ?>&inc_iva=0&prop_select=<?php echo $_REQUEST['use_prop'] ?>&prop=<?php echo $_REQUEST['prop'] ?>" target='_blank'>Diciembre</a></td>
											<td width="6%" class="text-right">Total</td>
										</tr>

										<tr class="busqueda_fila"><td class="consepto_r21">Total de los actos o actividades pagados a la tasa del 16% de IVA</td>
											<?php $suma=0; 

											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($valorBase16[$i]['16%'],2,'.',','); $suma+=$valorBase16[$i]['16%']; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
												<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila"><td class="consepto_r21">Total de los actos o actividades pagados a la tasa del 11% de IVA</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($valorBase11[$i]['11%'],2,'.',','); $suma+=$valorBase11[$i]['11%']; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
												<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila"><td class="consepto_r21">Total de actos o actividades pagados en la importación de bienes y servicios a la tasa del 16%</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($valorbaseimport16[$i]["16%"],2,'.',','); $suma+=$valorbaseimport16[$i]["16%"]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
												<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila"><td class="consepto_r21">Total de actos o actividades pagados en la importación de bienes y servicios a la tasa del 11%</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($valorbaseimport11[$i]["11%"],2,'.',','); $suma+=$valorbaseimport11[$i]["11%"]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
												<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila"><td class="consepto_r21">Total de los demas actos o actividades pagados a la tasa del 0%</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($valorbase0[$i]["0%"],2,'.',','); $suma+=$valorbase0[$i]["0%"]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila"><td class="consepto_r21">Total de los actos o actividades pagados por los que no se pagará el IVA (Excentos)</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($totalBaseIvaExcento[$i]["Exenta"],2,'.',','); $suma+=$totalBaseIvaExcento[$i]["Exenta"]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr style="background-color:#edeff1;font-weight:bold;" >
											<td  colspan="14" align="center">Determinacion del impuesto al valor agregado</td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Total del IVA de actos o actividades pagados a la tasa del 16%</td>
											<?php $suma=0; 
											$arrIva = array();
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($totalTasaIvaAcr16[$i]["16%"],2,'.',','); $suma+=$totalTasaIvaAcr16[$i]["16%"]; $arrIva[$i]=$totalTasaIvaAcr16[$i]["16%"]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Total del IVA de actos o actividades pagados a la tasa del 11%</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($totalTasaIvaAcr11[$i]["11%"],2,'.',','); $suma+=$totalTasaIvaAcr11[$i]["11%"]; $arrIva[$i]+=$totalTasaIvaAcr11[$i]["11%"]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Total del IVA de actos o actividades pagados en la importación de bienes o servicios a la tasa del 16%</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($ivaimport16[$i]["16%"],2,'.',','); $suma+=$ivaimport16[$i]["16%"]; $arrIva[$i]+=$ivaimport16[$i]["16%"]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Total del IVA de actos o actividades pagados en la importación de bienes o servicios a la tasa del 11%</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												<td style="text-align: right;"><?php echo number_format($ivaimport11[$i]["11%"],2,'.',','); $suma+=$ivaimport11[$i]["11%"]; $arrIva[$i]+=$ivaimport11[$i]["11%"]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr style="background-color:#f6f7f8;font-weight:bold;">
											<td class="consepto_r21"><b>Total de IVA trasladado al contribuyente (Efectivamente pagado)</b></td>
											<?php $suma=0; 
											$ivaPrevio = array();
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><b><?php echo number_format($efectivamentepagado[$i],2,'.',','); $suma+=$efectivamentepagado[$i];  ?></b></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><b><?php echo number_format($suma,2,'.',','); ?></b></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">IVA de compras y gastos nacionales para gravados</td>
											<?php $suma=0; 
											$arrIva = array();
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($arr[$i]['GastosGravadosNacional'],2,'.',','); $suma += $arr[$i]['GastosGravadosNacional'];  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">IVA de compras y gastos importación para gravados</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($arr[$i]['GastosGravadosExtrangeros'],2,'.',','); $suma += $arr[$i]['GastosGravadosExtrangeros']; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">IVA de inversiones nacionales para gravados</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($arr[$i]['InvGravadosNacional'],2,'.',','); $suma += $arr[$i]['InvGravadosNacional'];  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">IVA de inversiones importación para gravados</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($arr[$i]['InvGravadosExtrangeros'],2,'.',','); $suma += $arr[$i]['InvGravadosExtrangeros'];  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class=" busqueda_fila" style="background-color:#f6f7f8;font-weight:bold;">
											<td class="consepto_r21"><b>IVA de actos gravados</b></td>
											<?php $suma=0; 
											$totalIvaPeriodo = array();
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><b><?php echo number_format($sumagravados[$i],2,'.',','); $suma += $sumagravados[$i];?></b></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><b><?php echo number_format($suma,2,'.',','); ?></b></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">IVA de compras y gastos nacionales y de importacion para actos Excentos de IVA</td>
											<?php $suma=0; 
											$arrIva = array();
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($arr[$i]['GastosExentos']+$arr[$i]['GastosExentosnacional'],2,'.',','); $suma+=$arr[$i]['GastosExentos']+$arr[$i]['GastosExentosnacional'];  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">IVA de inversiones nacionales y de importacion para actos Excentos de IVA</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($arr[$i]['InvExentos']+$arr[$i]['InvExentosnacional'],2,'.',','); $suma+=$arr[$i]['InvExentos']+$arr[$i]['InvExentosnacional'];  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>				
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class=" busqueda_fila">
											<td class="consepto_r21">IVA de actos Excentos</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($IVAExcentos[$i],2,'.',','); $suma+=$IVAExcentos[$i]; ; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class=" busqueda_fila">
											<td class="consepto_r21">IVA de bienes para generar actos Excentos y gravados (No identificados) (previo)</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($ivabienesutilizados[$i],2,'.',','); $suma+=$ivabienesutilizados[$i]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">% Art 5 IVA</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php if($use_prop==1){ echo number_format($prop,2,'.',','); } else echo "0.00";  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php if($use_prop==1){ echo number_format($prop,2,'.',','); } else echo "0.00"; ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">% Art 5-B IVA</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php if($use_prop==2){ echo number_format($prop,2,'.',','); } else echo "0.00"; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php if($use_prop==2){ echo number_format($prop,2,'.',','); } else echo "0.00"; ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">IVA de bienes para generar actos Excentos y gravados (No identificados) (definitivo)</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($multipliart5[$i],2,'.',','); $suma+=$multipliart5[$i];  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class=" " style="font-weight:bold;background-color:#edeff1;">
											<td class="consepto_r21">Total IVA acreditable del periodo</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($totalacreditable[$i],2,'.',','); $suma+=$totalacreditable[$i]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="text-align: right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Valor de actos o actividades gravados al 16%</td>
											<?php $suma=0; 
											$sumaActosGravados = array();
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($totalBaseIvaImp16[$i]['tasa16'],2,'.',','); $suma+=$totalBaseIvaImp16[$i]['tasa16']; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Valor de actos o actividades gravados al 11%</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($totalBaseIvaImp11[$i]['tasa11'],2,'.',','); $suma+=$totalBaseIvaImp11[$i]['tasa11']; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Valor de actos o actividades gravados al 0% exportación</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($totalBaseIvacausa0[$i]['tasa0'],2,'.',','); $suma += $totalBaseIvacausa0[$i]['tasa0'];  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Valor de actos o actividades gravados al 0% otros</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($totalBaseIvacausaotros[$i]['otrasTasas'],2,'.',','); $suma += $totalBaseIvacausaotros[$i]['otrasTasas']; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class=" busqueda_fila" style="background-color:#f6f7f8;font-weight:bold;">
											<td class="consepto_r21"><b>Suma de actos o actividades gravados</b></td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><b><?php echo number_format($sumaactosgravados[$i],2,'.',','); $suma += $sumaactosgravados[$i]; ?></b></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><b><?php echo number_format($suma,2,'.',','); ?></b></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Valor de actos o actividades por las que no se debe pagar el impuesto (Excentos)</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php 
											echo number_format($totalBaseIvacausaExenta[$i]['tasaExenta'],2,'.',','); $suma+=$totalBaseIvacausaExenta[$i]['tasaExenta']; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class=" " style="background-color:#f6f7f8;font-weight:bold;">
											<td class="consepto_r21">IVA Causado</td>
											<?php 
											$suma = 0;
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($impuestocausado[$i],2,'.',','); $suma += $impuestocausado[$i]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="text-align: right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Otros cantidades a cargo </td>
											<?php $suma=0; 
											$ivaFavorEnContra = array();
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($cargo[$i],2,'.',','); $suma += $cargo[$i];  ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class="busqueda_fila">
											<td class="consepto_r21">Otras cantidades a favor</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
											<td style="text-align: right;"><?php echo number_format($favor[$i],2,'.',','); $suma += $favor[$i]; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="background-color:#e4e7ea;font-weight:bold;text-align:right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>

										<tr class=" " style="background-color:#edeff1;font-weight:bold;">
											<td class="consepto_r21">IVA a favor o en contra</td>
											<?php $suma=0; 
											for($i=$per_ini;$i<=$per_fin;$i++){ ?>
												
											<td style="text-align: right;"><?php echo number_format($ivafavorcargo[$i],2,'.',','); $suma +=$ivafavorcargo[$i] ; ?></td>
											<?php } 
											for($i=$per_fin+1;$i<=12;$i++){?>
												<td style="text-align: right;"></td>
											<?php }?>
											<td style="text-align: right;"><?php echo number_format($suma,2,'.',','); ?></td>
										</tr>
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