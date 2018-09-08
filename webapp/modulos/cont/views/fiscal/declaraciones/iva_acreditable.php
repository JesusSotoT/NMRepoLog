<?
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=generacion_iva_acreditable.xls");
?>
<html>
<head>
	<title>Declaración R21 IVA </title>
	<?php
	if($toexcel==0){ ?>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />	
	<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<style type="text/css">
	.fondo_gris{background:#f5f5f5;}
	.fondo_verde{background:#91C313;}
	.fondo_gris{background:#f5f5f5;}
	</style>
	<?php
	}
	?>
	<style type="text/css">
		.titulo_acred{text-align: center; font: 23px arial; }
		.subtitulo_acred{text-align: left; font: 16px arial; font-weight: bold; border-top: 0px solid; border-bottom: 1px solid;}
		.subtitulo_acred_2{text-align: left; font: 18px arial; font-weight: bold; border-top: 0px solid; border-bottom: 0px solid;}
		.encab_acred{text-align: right; font-weight: bold; font: 13px arial; }
		.consepto_acred{width: 380px; font: 13px arial; vertical-align: top;}
		.valor_acred{width: 80px; text-align: right; font: 13px arial; vertical-align: top;}
		.valor_total_acred{border-top: 2px solid; width: 70px; text-align: right; font: 13px arial; vertical-align: top;}
		
	</style>
</head>
<body>
	<table border="0" cellpadding="5" cellspacing="0" width="630px" class="table_r21 busqueda">
		<thead>
			<?php
			if($toexcel==1){ ?>
			<tr><th colspan="4" class="titulo_acred"><?php echo $organizacion->nombreorganizacion; ?></th></tr>
			<tr><th colspan="4" class="titulo_acred">Generacion del IVA acreditable</th></tr> 	
			<tr><th colspan="4" class="titulo_acred">Periodo de acreditamiento <?php echo $meses[$per_ini].'/'.$ejercicio->NombreEjercicio.' a '.$meses[$per_fin].'/'.$ejercicio->NombreEjercicio; ?></th></tr>
			<?php
			}
			?>
		</thead>
		<tbody>
			<tr class="fondo_verde">
				<td colspan="4" class="subtitulo_acred">IVA acreditable para generar actos gravados:</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"></td>
				<td class="encab_acred">Base</td>
				<td class="encab_acred">IVA 16%</td>
				<td class="encab_acred">IVA 11%</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">Gastos para generar ingresos gravados para IVA</td>
				<td class="valor_acred"><?php echo number_format($gastos[1]["base"],4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[1]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[1]["11%"]->iva,4,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">Inversiones para generar ingresos gravados para IVA</td>
				<td class="valor_acred"><?php echo number_format($gastos[2]["base"],4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[2]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[2]["11%"]->iva,4,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"><b>Total</b></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[1]["base"]+$gastos[2]["base"],4,'.',','); ?></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[1]["16%"]->iva+$gastos[2]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[1]["11%"]->iva+$gastos[2]["11%"]->iva,4,'.',','); ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="4" class="consepto_acred">&nbsp;</td>
			</tr>

			<tr class="fondo_verde">
				<td colspan="4" class="subtitulo_acred">IVA Acreditable para generar actos gravados y exentos (No identificado):</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"></td>
				<td class="encab_acred">Base</td>
				<td class="encab_acred">IVA 16%</td>
				<td class="encab_acred">IVA 11%</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">Gastos para generar ingresos NO identificados para IVA</td>
				<td class="valor_acred"><?php echo number_format($gastos[6]["base"],4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[6]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[6]["11%"]->iva,4,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">Inversiones para generar ingresos NO identificados para IVA</td>
				<td class="valor_acred"><?php echo number_format($gastos[8]["base"],4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[8]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[8]["11%"]->iva,4,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"><b>Total</b></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[6]["base"]+$gastos[8]["base"],4,'.',','); ?></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[6]["16%"]->iva+$gastos[8]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[6]["11%"]->iva+$gastos[8]["11%"]->iva,4,'.',','); ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="4" class="consepto_acred">&nbsp;</td>
			</tr>

			<tr class="fondo_verde">
				<td colspan="4" class="subtitulo_acred">IVA Acreditable para generar actos exentos:</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"></td>
				<td class="encab_acred">Base</td>
				<td class="encab_acred">IVA 16%</td>
				<td class="encab_acred">IVA 11%</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">Gastos para generar ingresos exentos de IVA</td>
				<td class="valor_acred"><?php echo number_format($gastos[7]["base"],4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[7]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[7]["11%"]->iva,4,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">Inversiones para generar ingresos exentos de IVA</td>
				<td class="valor_acred"><?php echo number_format($gastos[9]["base"],4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[9]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[9]["11%"]->iva,4,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"><b>Total</b></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[7]["base"]+$gastos[9]["base"],4,'.',','); ?></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[7]["16%"]->iva+$gastos[9]["16%"]->iva,4,'.',','); ?></td>
				<td class="valor_total_acred"><?php echo number_format($gastos[7]["11%"]->iva+$gastos[9]["11%"]->iva,4,'.',','); ?></td>
			</tr>

			<tr class="fondo_gris">
				<td colspan="4" class="consepto_acred">&nbsp;</td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="4" class="consepto_acred">&nbsp;</td>
			</tr>

			<tr class="fondo_gris">
				<td colspan="4" class="subtitulo_acred_2">Factor de acreditamiento: <?php echo $fac_acr; if($fac_acr==0){$fac_acr=1;}?></td>
			</tr>

			<tr class="fondo_verde">
				<td colspan="4" class="subtitulo_acred" style="border-top: 0px solid;">IVA Acreditable:</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"></td>
				<td class="encab_acred"></td>
				<td class="encab_acred">IVA 16%</td>
				<td class="encab_acred">IVA 11%</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">IVA para generar ingresos gravados</td>
				<td class="valor_acred"></td>
				<td class="valor_acred"><?php $ivaTotal16=0; echo number_format($gastos[1]["16%"]->iva*$fac_acr,4,'.',','); $ivaTotal16+=($gastos[1]["16%"]->iva*$fac_acr); ?></td>
				<td class="valor_acred"><?php $ivaTotal11=0; echo number_format($gastos[1]["11%"]->iva*$fac_acr,4,'.',','); $ivaTotal11+=($gastos[1]["11%"]->iva*$fac_acr); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">IVA para generar ingresos NO identificados</td>
				<td class="valor_acred"></td>
				<td class="valor_acred"><?php echo number_format($gastos[6]["16%"]->iva*$fac_acr,4,'.',','); $ivaTotal16+=($gastos[6]["16%"]->iva*$fac_acr); ?></td>
				<td class="valor_acred"><?php echo number_format($gastos[6]["11%"]->iva*$fac_acr,4,'.',','); $ivaTotal11+=($gastos[6]["11%"]->iva*$fac_acr); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"><b>Total</b></td>
				<td class="valor_total_acred" style="border-top: 0px solid;"></td>
				<td class="valor_total_acred"><?php echo number_format($ivaTotal16,4,'.',','); ?></td>
				<td class="valor_total_acred"><?php echo number_format($ivaTotal11,4,'.',','); ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="4" class="consepto_acred">&nbsp;</td>
			</tr>

			<tr class="fondo_verde">
				<td colspan="4" class="subtitulo_acred">IVA Acreditable para aplicar como gastos</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"></td>
				<td class="encab_acred"></td>
				<td class="encab_acred">IVA 16%</td>
				<td class="encab_acred">IVA 11%</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">IVA no acreditable no identificado</td>
				<td class="valor_acred"></td><?php $noAcred16=0; $noAcred11=0; ?>
				<td class="valor_acred"><?php echo number_format(($gastos[6]["16%"]->noAcred+$gastos[8]["16%"]->noAcred)*$fac_acr,4,'.',','); $noAcred16+=(($gastos[6]["16%"]->noAcred+$gastos[8]["16%"]->noAcred)*$fac_acr); ?></td>
				<td class="valor_acred"><?php echo number_format(($gastos[6]["11%"]->noAcred+$gastos[8]["11%"]->noAcred)*$fac_acr,4,'.',','); $noAcred11+=(($gastos[6]["11%"]->noAcred+$gastos[8]["11%"]->noAcred)*$fac_acr); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred">IVA para generar actos excentos</td>
				<td class="valor_acred"></td>
				<td class="valor_acred"><?php echo number_format(($gastos[7]["16%"]->iva+$gastos[9]["16%"]->iva)*$fac_acr,4,'.',','); $noAcred16+=(($gastos[7]["16%"]->iva+$gastos[9]["16%"]->iva)*$fac_acr); ?></td>
				<td class="valor_acred"><?php echo number_format(($gastos[7]["11%"]->iva+$gastos[9]["11%"]->iva)*$fac_acr,4,'.',','); $noAcred11+=(($gastos[7]["11%"]->iva+$gastos[9]["11%"]->iva)*$fac_acr); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_acred"><b>Total</b></td>
				<td class="valor_total_acred" style="border-top: 0px solid;"></td>
				<td class="valor_total_acred"><?php echo number_format($noAcred16,4,'.',','); ?></td>
				<td class="valor_total_acred"><?php echo number_format($noAcred11,4,'.',','); ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="4" class="consepto_acred">&nbsp;</td>
			</tr>

			
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</body>
</html>