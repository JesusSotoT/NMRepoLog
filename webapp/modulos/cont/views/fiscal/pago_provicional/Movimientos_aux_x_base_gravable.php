<?
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=mov_aux_base_gravable.xls");
?>
<html>
<head>
	<title>Auxiliar Impuestos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php
if($toexcel==0){//se muestra reporte en navegador
?>
			<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />	
			<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
			<style type="text/css">
			.fondo_gris{background-color: #f5f5f5;}
			</style>
<?php
}
?>
	<style type="text/css">
		.titulo_aux{text-align: center; font: 20px arial; border: 0px solid; }
		.totales{text-align: right; font: 11px arial; font-weight: bolder; border-top: 1px solid;}
		.cabecera{ font: 11px arial; font-weight: bolder; vertical-align: middle; border: 0px solid; text-align: center;}
		.cuerpo{ font: 11px arial; vertical-align: top; }
		.numero{text-align: right; font: 11px arial;}
		.tasa{font: 11px arial; font-weight: bolder; vertical-align: top;}
		.espacio{height: 15px;}
	</style>
</head>
<body>
	<table border="0" cellpadding="4" cellspacing="0" class="table_aux busqueda" width="890px">
		<thead>
			<?php
			if($toexcel==1){?> 
			<tr><td colspan="10" class="titulo_aux"><?php echo $organizacion->nombreorganizacion; ?></td></tr>
			<tr><td colspan="10" class="titulo_aux">Movimientos Auxiliares por base gravable</td></tr> 	
			<tr><td colspan="10" class="titulo_aux">Periodo de Acreditamiento <?php echo $meses[$per_ini].'/'.$ejercicio->NombreEjercicio.' a '.$meses[$per_fin].'/'.$ejercicio->NombreEjercicio; ?></td></tr>
			<?php
			}
			?>
		
			<tr class="tit_tabla_buscar">
				<th class="cabecera" width="70px">Fecha</th>
				<th class="cabecera" width="65px">Tipo</th>
				<th class="cabecera" width="65px">Número</th>
				<th class="cabecera" width="200px">Concepto</th>
				<th class="cabecera" width="65px">Ejer.</th>
				<th class="cabecera" width="65px">Periodo</th>
				<th class="cabecera" width="90px">Importe base</th>
				<th class="cabecera" width="90px">Importe IVA</th>
				<th class="cabecera" width="90px">IVA pagado no acreditable</th>
				<th class="cabecera" width="90px">Total</th>
			</tr>
		</thead>
		<tbody>
			<tr class="fondo_gris">
				<td colspan="10" style="font: 14px arial; font-weight: bolder; vertical-align: top;">IVA CAUSADO</td>
	<!-- Taza 16% -->
			<tr class="fondo_gris">
				<td colspan="10" class="espacio"></td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Tasa 16%</td>
			</tr>
			<?php 
				$totalCausado=0;
				$baseCausado=0;
				$ivaCausado=0;
				$totalBase=0;
				$totalIvaFiscal=0;
				
				$totalTotal=0;
				foreach ($polCausado['16%'] as $key => $value) {
					if($value->base>0){
						$totalBase+=$value->base;
						$totalIvaFiscal+=$value->iva;
						$totalTotal+=$value->total;

						$baseCausado+=$value->base;
						$ivaCausado+=$value->iva;
						$totalCausado+=$value->total;
			?>
						<tr class="busqueda_fila">
							<td class="cuerpo" ><?php echo $value->fecha; ?></td>
							<td class="cuerpo" style="text-align:center;"><?php echo $value->poliza; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $value->num; ?></td>
							<td class="cuerpo" ><?php echo $value->concepto; ?></td>
							<td class="numero" style="text-align:center;" ><?php echo $value->NombreEjercicio; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$value->idperiodo]; ?></td>
							<td class="numero" ><?php echo number_format($value->base,2,'.',','); ?></td>
							<td class="numero" ><?php echo number_format($value->iva,2,'.',','); ?></td>
							<td class="numero" ></td>
							<td class="numero" ><?php echo number_format($value->total,2,'.',','); ?></td>
						</tr>
				<?php
					}
				}
			?>
			
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Taza 11% -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Tasa 11%</td>
			</tr>
			<?php 
				
				$totalBase=0;
				$totalIvaFiscal=0;
				
				$totalTotal=0;
				foreach ($polCausado['11%'] as $key => $value) {
					if($value->base>0){
						$totalBase+=$value->base;
						$totalIvaFiscal+=$value->iva;
						$totalTotal+=$value->total;

						$totalCausado+=$value->total;
						$baseCausado+=$value->base;
						$ivaCausado+=$value->iva;
			?>
						<tr class="busqueda_fila">
							<td class="cuerpo" ><?php echo $value->fecha; ?></td>
							<td class="cuerpo" style="text-align:center;"><?php echo $value->poliza; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $value->num; ?></td>
							<td class="cuerpo" ><?php echo $value->concepto; ?></td>
							<td class="numero" style="text-align:center;" ><?php echo $value->NombreEjercicio; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$value->idperiodo]; ?></td>
							<td class="numero" ><?php echo number_format($value->base,2,'.',','); ?></td>
							<td class="numero" ><?php echo number_format($value->iva,2,'.',','); ?></td>
							<td class="numero" ></td>
							<td class="numero" ><?php echo number_format($value->total,2,'.',','); ?></td>
						</tr>
				<?php
					}
				}
			?>
			
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Taza 0% -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Tasa 0%</td>
			</tr>
			<?php 
				
				$totalBase=0;
				$totalIvaFiscal=0;
				
				$totalTotal=0;
				foreach ($polCausado['0%'] as $key => $value) {
					if($value->base>0){
						$totalBase+=$value->base;
						$totalIvaFiscal+=$value->iva;
						$totalTotal+=$value->total;

						$totalCausado+=$value->total;
						$baseCausado+=$value->base;
						$ivaCausado+=$value->iva;
			?>
						<tr class="busqueda_fila">
							<td class="cuerpo" ><?php echo $value->fecha; ?></td>
							<td class="cuerpo" style="text-align:center;"><?php echo $value->poliza; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $value->num; ?></td>
							<td class="cuerpo" ><?php echo $value->concepto; ?></td>
							<td class="numero" style="text-align:center;" ><?php echo $value->NombreEjercicio; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$value->idperiodo]; ?></td>
							<td class="numero" ><?php echo number_format($value->base,2,'.',','); ?></td>
							<td class="numero" ><?php echo number_format($value->iva,2,'.',','); ?></td>
							<td class="numero" ></td>
							<td class="numero" ><?php echo number_format($value->total,2,'.',','); ?></td>
						</tr>
				<?php
					}
				}
			?>
			
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Tasa Exenta -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Tasa Exenta</td>
			</tr>
			<?php 
				
				$totalBase=0;
				$totalIvaFiscal=0;
				$totalNoAcred=0;
				$totalTotal=0;
				foreach ($polCausado['Exenta'] as $key => $value) {
					if($value->base>0){
						$totalBase+=$value->base;
						$totalIvaFiscal+=$value->iva;
						$totalTotal+=$value->total;

						$baseCausado+=$value->base;
						$ivaCausado+=$value->iva;
						$totalCausado+=$value->total;
			?>
						<tr class="busqueda_fila">
							<td class="cuerpo" ><?php echo $value->fecha; ?></td>
							<td class="cuerpo" style="text-align:center;"><?php echo $value->poliza; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $value->num; ?></td>
							<td class="cuerpo" ><?php echo $value->concepto; ?></td>
							<td class="numero" style="text-align:center;" ><?php echo $value->NombreEjercicio; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$value->idperiodo]; ?></td>
							<td class="numero" ><?php echo number_format($value->base,2,'.',','); ?></td>
							<td class="numero" ><?php echo number_format($value->iva,2,'.',','); ?></td>
							<td class="numero" ></td>
							<td class="numero" ><?php echo number_format($value->total,2,'.',','); ?></td>
						</tr>
				<?php
					}
				}
			?>
			
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Otras Tasas -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Otras Tasas</td>
			</tr>
			<?php 
				
				$totalBase=0;
				$totalIvaFiscal=0;
				$totalNoAcred=0;
				$totalTotal=0;
				foreach ($polCausado['otras'] as $key => $value) {
					if($value->base>0){
						$totalBase+=$value->base;
						$totalIvaFiscal+=$value->iva;
						$totalTotal+=$value->total;

						$totalCausado+=$value->total;
						$baseCausado+=$value->base;
						$ivaCausado+=$value->iva;
			?>
						<tr class="busqueda_fila">
							<td class="cuerpo" ><?php echo $value->fecha; ?></td>
							<td class="cuerpo" style="text-align:center;"><?php echo $value->poliza; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $value->num; ?></td>
							<td class="cuerpo" ><?php echo $value->concepto; ?></td>
							<td class="numero" style="text-align:center;" ><?php echo $value->NombreEjercicio; ?></td>
							<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$value->idperiodo]; ?></td>
							<td class="numero" ><?php echo number_format($value->base,2,'.',','); ?></td>
							<td class="numero" ><?php echo number_format($value->iva,2,'.',','); ?></td>
							<td class="numero" ></td>
							<td class="numero" ><?php echo number_format($value->total,2,'.',','); ?></td>
						</tr>
				<?php
					}
				}
			?>
			
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Otros -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Otros</td>
			</tr>
			<?php
				$totalOtros=0;
				foreach ($polCausado['otros'] as $key => $otros) {
					if($key>0){
						$totalOtros+=$otros->otros;
						if($otros->otros>0){
			?>
							<tr class="busqueda_fila">
								<td class="cuerpo" ><?php echo $otros->fecha; ?></td>
								<td class="cuerpo" style="text-align:center;"><?php echo $otros->poliza; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $otros->num; ?></td>
								<td class="cuerpo" ><?php echo $otros->concepto; ?></td>
								<td class="numero" style="text-align:center;" ><?php echo $otros->NombreEjercicio; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$otros->idperiodo]; ?></td>
								<td class="numero" >0.00</td>
								<td class="numero" >0.00</td>
								<td class="numero" >0.00</td>
								<td class="numero" ><?php echo number_format($otros->otros,2,'.',','); ?></td>
							</tr>
			<?php
						}
					}
				}
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" >0.00</td>
				<td class="totales" >0.00</td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalOtros,2,'.',','); ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="10" class="espacio"></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td style="font: 11px arial; font-weight: bolder;" >Total actos causados</td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($baseCausado,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($ivaCausado,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalCausado,2,'.',','); ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="10" class="espacio" ></td>
			</tr>

	<!-- IVA Retenido -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">IVA Retenido</td>
			</tr>
			<?php
				$totalivaRetenido=0;
				foreach ($polCausado['ivaretenido'] as $key => $ivaRetenido) {
					if($key>0){
						
						if($ivaRetenido->ivaRetenido>0){
							$totalivaRetenido+=$ivaRetenido->ivaRetenido;
			?>
							<tr class="busqueda_fila">
								<td class="cuerpo" ><?php echo $ivaRetenido->fecha; ?></td>
								<td class="cuerpo" style="text-align:center;"><?php echo $ivaRetenido->poliza; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $ivaRetenido->num; ?></td>
								<td class="cuerpo" ><?php echo $ivaRetenido->concepto; ?></td>
								<td class="numero" style="text-align:center;" ><?php echo $ivaRetenido->NombreEjercicio; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$ivaRetenido->idperiodo]; ?></td>
								<td class="numero" >0.00</td>
								<td class="numero" ><?php echo number_format($ivaRetenido->ivaRetenido*(-1),2,'.',','); ?></td>
								<td class="numero" ></td>
								<td class="numero" ><?php echo number_format($ivaRetenido->ivaRetenido*(-1),2,'.',','); ?></td>
							</tr>
			<?php
						}
					}
				}
				$totalivaRetenido*=(-1);
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" >0.00</td>
				<td class="totales" ><?php echo number_format($totalivaRetenido,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalivaRetenido,2,'.',','); ?></td>
			</tr>
	<!-- ISR Retenido -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">ISR Retenido</td>
			</tr>
			<?php
				$totalIsrRetenido=0;
				foreach ($polCausado['isrretenido'] as $key => $isrRetenido) {
					if($key>0){
						
						if($isrRetenido->isrRetenido>0){
							$totalIsrRetenido+=$isrRetenido->isrRetenido;
			?>
							<tr class="busqueda_fila">
								<td class="cuerpo" ><?php echo $isrRetenido->fecha; ?></td>
								<td class="cuerpo" style="text-align:center;"><?php echo $isrRetenido->poliza; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $isrRetenido->num; ?></td>
								<td class="cuerpo" ><?php echo $isrRetenido->concepto; ?></td>
								<td class="numero" style="text-align:center;" ><?php echo $isrRetenido->NombreEjercicio; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$isrRetenido->idperiodo]; ?></td>
								<td class="numero" >0.00</td>
								<td class="numero" >0.00</td>
								<td class="numero" >0.00</td>
								<td class="numero" ><?php echo number_format($isrRetenido->isrRetenido*(-1),2,'.',','); ?></td>
							</tr>
			<?php
						}
					}
				}
				$totalIsrRetenido*=(-1);
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" >0.00</td>
				<td class="totales" >0.00</td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalIsrRetenido,2,'.',','); ?></td>
			</tr>

			<tr class="fondo_gris">
				<td colspan="10" class="espacio"></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="10" class="espacio"></td>
			</tr>

			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td style="font: 13px arial; font-weight: bolder;" >TOTAL</td>
				<td style="font: 13px arial; font-weight: bolder;" > IVA CAUSADO</td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($baseCausado,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($ivaCausado+$totalivaRetenido,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalCausado+$totalivaRetenido+$totalIsrRetenido,2,'.',','); ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="10" class="espacio"></td>
			</tr>

			<tr class="busqueda_fila">
				<td colspan="10" style="font: 14px arial; font-weight: bolder; vertical-align: top;">IVA ACREDITABLE</td>
	<!-- Taza 16% -->
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class=" " ></td>
				<td class=" " ></td>
				<td class=" " ></td>
				<td class=" " ></td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Tasa 16%</td>
			</tr>
			<?php 
			$totalGravable=0;
			$baseGravable=0;
			$ivaGravable=0;
			$noAcrGravable=0;

			$totalBase=0;
			$totalIvaFiscal=0;
			$totalNoAcred=0;
			$totalTotal=0;
			if (array_key_exists('16%', $polAcred)){
				for($i=0;$i<count($polAcred['16%']);$i++){ 
					$totalBase+=$polAcred['16%'][$i]->base;
					$totalIvaFiscal+=$polAcred['16%'][$i]->ivafiscal;
					$totalNoAcred+=$polAcred['16%'][$i]->no_acreditable;
					$totalTotal+=$polAcred['16%'][$i]->total;

					$totalGravable+=$polAcred['16%'][$i]->total;
					$baseGravable+=$polAcred['16%'][$i]->base;
					$ivaGravable+=$polAcred['16%'][$i]->ivafiscal;
					$noAcrGravable+=$polAcred['16%'][$i]->no_acreditable;
					?>
					<tr class="busqueda_fila">
						<td class="cuerpo" ><?php echo $polAcred['16%'][$i]->fecha; ?></td>
						<td class="cuerpo" style="text-align:center;"><?php echo $polAcred['16%'][$i]->poliza; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $polAcred['16%'][$i]->num; ?></td>
						<td class="cuerpo" ><?php echo $polAcred['16%'][$i]->concepto; ?></td>
						<td class="numero" style="text-align:center;" ><?php echo $polAcred['16%'][$i]->NombreEjercicio; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$polAcred['16%'][$i]->idperiodo]; ?></td>
						<td class="numero" ><?php echo number_format($polAcred['16%'][$i]->base,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['16%'][$i]->ivafiscal,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['16%'][$i]->no_acreditable,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['16%'][$i]->total,2,'.',','); ?></td>
					</tr>
				<?php
				}
			}
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalNoAcred,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Taza 11% -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Tasa 11%</td>
			</tr>
			<?php 
			$totalBase=0;
			$totalIvaFiscal=0;
			$totalNoAcred=0;
			$totalTotal=0;
			if (array_key_exists('11%', $polAcred)){
				for($i=0;$i<count($polAcred['11%']);$i++){ 

					$totalBase+=$polAcred['11%'][$i]->base;
					$totalIvaFiscal+=$polAcred['11%'][$i]->ivafiscal;
					$totalNoAcred+=$polAcred['11%'][$i]->no_acreditable;
					$totalTotal+=$polAcred['11%'][$i]->total;

					$totalGravable+=$polAcred['11%'][$i]->total;
					$baseGravable+=$polAcred['11%'][$i]->base;
					$ivaGravable+=$polAcred['11%'][$i]->ivafiscal;
					$noAcrGravable+=$polAcred['11%'][$i]->no_acreditable;

					?>
					<tr class="busqueda_fila">
						<td class="cuerpo" ><?php echo $polAcred['11%'][$i]->fecha; ?></td>
						<td class="cuerpo" style="text-align:center;"><?php echo $polAcred['11%'][$i]->poliza; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $polAcred['11%'][$i]->num; ?></td>
						<td class="cuerpo" ><?php echo $polAcred['11%'][$i]->concepto; ?></td>
						<td class="numero" style="text-align:center;" ><?php echo $polAcred['11%'][$i]->NombreEjercicio; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$polAcred['11%'][$i]->idperiodo]; ?></td>
						<td class="numero" ><?php echo number_format($polAcred['11%'][$i]->base,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['11%'][$i]->ivafiscal,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['11%'][$i]->no_acreditable,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['11%'][$i]->total,2,'.',','); ?></td>
					</tr>
				<?php
				}
			}
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalNoAcred,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Taza 0% -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Tasa 0%</td>
			</tr>
			<?php 
			$totalBase=0;
			$totalIvaFiscal=0;
			$totalNoAcred=0;
			$totalTotal=0;
			if (array_key_exists('0%', $polAcred)){
				for($i=0;$i<count($polAcred['0%']);$i++){ 

					$totalBase+=$polAcred['0%'][$i]->base;
					$totalIvaFiscal+=$polAcred['0%'][$i]->ivafiscal;
					$totalNoAcred+=$polAcred['0%'][$i]->no_acreditable;
					$totalTotal+=$polAcred['0%'][$i]->total;

					$totalGravable+=$polAcred['0%'][$i]->total;
					$baseGravable+=$polAcred['0%'][$i]->base;
					$ivaGravable+=$polAcred['0%'][$i]->ivafiscal;
					$noAcrGravable+=$polAcred['0%'][$i]->no_acreditable;


					?>
					<tr class="busqueda_fila">
						<td class="cuerpo" ><?php echo $polAcred['0%'][$i]->fecha; ?></td>
						<td class="cuerpo" style="text-align:center;"><?php echo $polAcred['0%'][$i]->poliza; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $polAcred['0%'][$i]->num; ?></td>
						<td class="cuerpo" ><?php echo $polAcred['0%'][$i]->concepto; ?></td>
						<td class="numero" style="text-align:center;" ><?php echo $polAcred['0%'][$i]->NombreEjercicio; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$polAcred['0%'][$i]->idperiodo]; ?></td>
						<td class="numero" ><?php echo number_format($polAcred['0%'][$i]->base,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['0%'][$i]->ivafiscal,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['0%'][$i]->no_acreditable,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['0%'][$i]->total,2,'.',','); ?></td>
					</tr>
				<?php
				}
			}
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalNoAcred,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Tasa Exenta -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Tasa Exenta</td>
			</tr>
			<?php 
			$totalBase=0;
			$totalIvaFiscal=0;
			$totalNoAcred=0;
			$totalTotal=0;
			if (array_key_exists('Exenta', $polAcred)){
				for($i=0;$i<count($polAcred['Exenta']);$i++){ 

					$totalBase+=$polAcred['Exenta'][$i]->base;
					$totalIvaFiscal+=$polAcred['Exenta'][$i]->ivafiscal;
					$totalNoAcred+=$polAcred['Exenta'][$i]->no_acreditable;
					$totalTotal+=$polAcred['Exenta'][$i]->total;

					$totalGravable+=$polAcred['Exenta'][$i]->total;
					$baseGravable+=$polAcred['Exenta'][$i]->base;
					$ivaGravable+=$polAcred['Exenta'][$i]->ivafiscal;
					$noAcrGravable+=$polAcred['Exenta'][$i]->no_acreditable;

					?>
					<tr class="busqueda_fila">
						<td class="cuerpo" ><?php echo $polAcred['Exenta'][$i]->fecha; ?></td>
						<td class="cuerpo" style="text-align:center;"><?php echo $polAcred['Exenta'][$i]->poliza; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $polAcred['Exenta'][$i]->num; ?></td>
						<td class="cuerpo" ><?php echo $polAcred['Exenta'][$i]->concepto; ?></td>
						<td class="numero" style="text-align:center;" ><?php echo $polAcred['Exenta'][$i]->NombreEjercicio; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$polAcred['Exenta'][$i]->idperiodo]; ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Exenta'][$i]->base,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Exenta'][$i]->ivafiscal,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Exenta'][$i]->no_acreditable,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Exenta'][$i]->total,2,'.',','); ?></td>
					</tr>
				<?php
				}
			}
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalNoAcred,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Otras Tasas -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Otras Tasas</td>
			</tr>
			<?php 
			$totalBase=0;
			$totalIvaFiscal=0;
			$totalNoAcred=0;
			$totalTotal=0;
			if (array_key_exists('Otra Tasa 1', $polAcred)){
				for($i=0;$i<count($polAcred['Otra Tasa 1']);$i++){ 

					$totalBase+=$polAcred['Otra Tasa 1'][$i]->base;
					$totalIvaFiscal+=$polAcred['Otra Tasa 1'][$i]->ivafiscal;
					$totalNoAcred+=$polAcred['Otra Tasa 1'][$i]->no_acreditable;
					$totalTotal+=$polAcred['Otra Tasa 1'][$i]->total;

					$totalGravable+=$polAcred['Otra Tasa 1'][$i]->total;
					$baseGravable+=$polAcred['Otra Tasa 1'][$i]->base;
					$ivaGravable+=$polAcred['Otra Tasa 1'][$i]->ivafiscal;
					$noAcrGravable+=$polAcred['Otra Tasa 1'][$i]->no_acreditable;

					?>
					<tr class="busqueda_fila">
						<td class="cuerpo" ><?php echo $polAcred['Otra Tasa 1'][$i]->fecha; ?></td>
						<td class="cuerpo" style="text-align:center;"><?php echo $polAcred['Otra Tasa 1'][$i]->poliza; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $polAcred['Otra Tasa 1'][$i]->num; ?></td>
						<td class="cuerpo" ><?php echo $polAcred['Otra Tasa 1'][$i]->concepto; ?></td>
						<td class="numero" style="text-align:center;" ><?php echo $polAcred['Otra Tasa 1'][$i]->NombreEjercicio; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$polAcred['Otra Tasa 1'][$i]->idperiodo]; ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Otra Tasa 1'][$i]->base,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Otra Tasa 1'][$i]->ivafiscal,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Otra Tasa 1'][$i]->no_acreditable,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Otra Tasa 1'][$i]->total,2,'.',','); ?></td>
					</tr>
				<?php
				}
			}
			if (array_key_exists('Otra Tasa 2', $polAcred)){
				for($i=0;$i<count($polAcred['Otra Tasa 2']);$i++){ 

					$totalBase+=$polAcred['Otra Tasa 2'][$i]->base;
					$totalIvaFiscal+=$polAcred['Otra Tasa 2'][$i]->ivafiscal;
					$totalNoAcred+=$polAcred['Otra Tasa 2'][$i]->no_acreditable;
					$totalTotal+=$polAcred['Otra Tasa 2'][$i]->total;

					$totalGravable+=$polAcred['Otra Tasa 2'][$i]->total;
					$baseGravable+=$polAcred['Otra Tasa 2'][$i]->base;
					$ivaGravable+=$polAcred['Otra Tasa 2'][$i]->ivafiscal;
					$noAcrGravable+=$polAcred['Otra Tasa 2'][$i]->no_acreditable;

					?>
					<tr class="busqueda_fila">
						<td class="cuerpo" ><?php echo $polAcred['Otra Tasa 2'][$i]->fecha; ?></td>
						<td class="cuerpo" style="text-align:center;"><?php echo $polAcred['Otra Tasa 2'][$i]->poliza; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $polAcred['Otra Tasa 2'][$i]->num; ?></td>
						<td class="cuerpo" ><?php echo $polAcred['Otra Tasa 2'][$i]->concepto; ?></td>
						<td class="numero" style="text-align:center;" ><?php echo $polAcred['Otra Tasa 2'][$i]->NombreEjercicio; ?></td>
						<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$polAcred['Otra Tasa 2'][$i]->idperiodo]; ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Otra Tasa 2'][$i]->base,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Otra Tasa 2'][$i]->ivafiscal,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Otra Tasa 2'][$i]->no_acreditable,2,'.',','); ?></td>
						<td class="numero" ><?php echo number_format($polAcred['Otra Tasa 2'][$i]->total,2,'.',','); ?></td>
					</tr>
				<?php
				}
			}
			?>
			
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($totalBase,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIvaFiscal,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalNoAcred,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalTotal,2,'.',','); ?></td>
			</tr>
	<!-- Otros -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">Otros</td>
			</tr>
			<?php
				$totalOtros=0;
				foreach ($polAcred['otros'] as $key => $otros) {
					if($key>0){
						$totalOtros+=$otros->otros;
						if($otros->otros>0){
			?>
							<tr class="busqueda_fila">
								<td class="cuerpo" ><?php echo $otros->fecha; ?></td>
								<td class="cuerpo" style="text-align:center;"><?php echo $otros->poliza; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $otros->num; ?></td>
								<td class="cuerpo" ><?php echo $otros->concepto; ?></td>
								<td class="numero" style="text-align:center;" ><?php echo $otros->NombreEjercicio; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$otros->idperiodo]; ?></td>
								<td class="numero" >0.00</td>
								<td class="numero" >0.00</td>
								<td class="numero" ></td>
								<td class="numero" ><?php echo number_format($otros->otros,2,'.',','); ?></td>
							</tr>
			<?php
						}
					}
				}
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" >0.00</td>
				<td class="totales" >0.00</td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalOtros,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="10" class="espacio" ></td>
			</tr>

			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td style="font: 11px arial; font-weight: bolder;" >Total actos gravados</td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($baseGravable,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($ivaGravable,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($noAcrGravable,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalGravable,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="10" class="espacio"></td>
			</tr>

	<!-- IVA Retenido -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">IVA Retenido</td>
			</tr>
			<?php
				$totalivaRetenido=0;
				foreach ($polAcred['ivaretenido'] as $key => $ivaRetenido) {
					if($key>0){
						$totalivaRetenido+=$ivaRetenido->ivaRetenido;
						if($ivaRetenido->ivaRetenido>0){
			?>
							<tr class="busqueda_fila">
								<td class="cuerpo" ><?php echo $ivaRetenido->fecha; ?></td>
								<td class="cuerpo" style="text-align:center;"><?php echo $ivaRetenido->poliza; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $ivaRetenido->num; ?></td>
								<td class="cuerpo" ><?php echo $ivaRetenido->concepto; ?></td>
								<td class="numero" style="text-align:center;" ><?php echo $ivaRetenido->NombreEjercicio; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$ivaRetenido->idperiodo]; ?></td>
								<td class="numero" >0.00</td>
								<td class="numero" ><?php echo number_format($ivaRetenido->ivaRetenido*(-1),2,'.',','); ?></td>
								<td class="numero" ></td>
								<td class="numero" ><?php echo number_format($ivaRetenido->ivaRetenido*(-1),2,'.',','); ?></td>
							</tr>
			<?php
						}
					}
				}
				$totalivaRetenido*=(-1);
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" >0.00</td>
				<td class="totales" ><?php echo number_format($totalivaRetenido,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalivaRetenido,2,'.',','); ?></td>
			</tr>
	<!-- ISR Retenido -->
			<tr class="busqueda_fila">
				<td colspan="10" class="tasa">ISR Retenido</td>
			</tr>
			<?php
				$totalIsrRetenido=0;
				foreach ($polAcred['isrretenido'] as $key => $isrRetenido) {
					if($key>0){
						$totalIsrRetenido+=$isrRetenido->isrRetenido;
						if($isrRetenido->isrRetenido>0){
			?>
							<tr class="busqueda_fila">
								<td class="cuerpo" ><?php echo $isrRetenido->fecha; ?></td>
								<td class="cuerpo" style="text-align:center;"><?php echo $isrRetenido->poliza; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $isrRetenido->num; ?></td>
								<td class="cuerpo" ><?php echo $isrRetenido->concepto; ?></td>
								<td class="numero" style="text-align:center;" ><?php echo $isrRetenido->NombreEjercicio; ?></td>
								<td class="cuerpo" style="text-align:center;" ><?php echo $meses[$isrRetenido->idperiodo]; ?></td>
								<td class="numero" >0.00</td>
								<td class="numero" >0.00</td>
								<td class="numero" ></td>
								<td class="numero" ><?php echo number_format($isrRetenido->isrRetenido*(-1),2,'.',','); ?></td>
							</tr>
			<?php
						}
					}
				}
				$totalIsrRetenido*=(-1);
			?>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" >0.00</td>
				<td class="totales" >0.00</td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalIsrRetenido,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="10" class="espacio"></td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="10" class="espacio"></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="" ></td>
				<td class="" ></td>
				<td style="font: 13px arial; font-weight: bolder;" >TOTAL</td>
				<td style="font: 13px arial; font-weight: bolder;" >IVA ACREDITABLE</td>
				<td class="" ></td>
				<td class="" ></td>
				<td class="totales" ><?php echo number_format($baseGravable,2,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($ivaGravable+$totalivaRetenido,2,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalGravable+$totalivaRetenido+$totalIsrRetenido,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="10" class="espacio"></td>
			</tr>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</body>
</html>