<?
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=resumen por tipo R21.xls");
?>
<html>
<head>
	<title>Declaración R21 IVA </title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<?php
	if($toexcel==0){//se muestra reporte en navegador
	?>
		<LINK href="../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />	
		<LINK href="../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
	<?php
	}
	?>
	<style type="text/css">
		.titulo_r21{text-align: center; font: 20px arial; border: 0px solid;}
		.cabecera_r21{ text-align: center; font: 13px arial; font-weight: bold; border-top: 2px solid; border-bottom: 2px solid;}
		.consepto_r21{ width: 350px; font: 11px arial; vertical-align: top;}
		.valor_r21{width: 90px; text-align: right; font: 11px arial; vertical-align: top;}
		.negritas{font-weight: bolder;}
		.fondoAmarillo{background-color: yellow;}
		.fondoVerde{background-color:#91C313;}
		
	</style>
</head>
<body>
	<table border="1" cellpadding="4" cellspacing="0" class="table_r21 busqueda">
		<thead>
			<?php
			if($toexcel==1){?> 
			<tr><td colspan="9" class="titulo_r21"><?php echo $organizacion->nombreorganizacion; ?></td><td colspan="5" class="titulo_r21"></td></tr>
			<tr><td colspan="9" class="titulo_r21">Resumen de movimientos general para R21 IVA por tipo</td><td colspan="5" class="titulo_r21"></td></tr> 
			<tr><td colspan="9" class="titulo_r21">RFC: XXXXXXXXXXXX</td><td colspan="5" class="titulo_r21"></td></tr>	
			<tr><td colspan="9" class="titulo_r21">Rango de Fecha <?php echo $meses[$per_ini].'/'.$ejercicio->NombreEjercicio.' a '.$meses[$per_fin].'/'.$ejercicio->NombreEjercicio; ?></td><td colspan="5" class="titulo_r21"></td></tr>
			<?php
			}
			?>
		</thead>
		<tbody>
			<tr class="fondoVerde">
				<td class="cabecera_r21">Descripción</td>
				<td class="cabecera_r21">Enero</td><td class="cabecera_r21">Febrero</td><td class="cabecera_r21">Marzo</td><td class="cabecera_r21">Abril</td><td class="cabecera_r21">Mayo</td><td class="cabecera_r21">Junio</td><td class="cabecera_r21">Julio</td><td class="cabecera_r21">Agosto</td><td class="cabecera_r21">Septiembre</td><td class="cabecera_r21">Octubre</td><td class="cabecera_r21">Noviembre</td><td class="cabecera_r21">Diciembre</td><td class="cabecera_r21">Total</td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Base IVA efectivamente pagado</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
					<td class="valor_r21"><?php echo number_format($base[$i]->base,2,'.',','); $suma += $base[$i]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>

			<tr class="busqueda_fila">
				<td class="consepto_r21">Base IVA acreditable</td>
				<?php for($i=1;$i<=12;$i++){ ?>
					<td class="valor_r21"><?php echo number_format($base[$i]->base_acreditable,2,'.',','); $suma += $base[$i]->base_acreditable; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>

			<tr class="busqueda_fila">
				<td class="consepto_r21">Importe IVA</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
					<td class="valor_r21"><?php echo number_format($base[$i]->iva,2,'.',','); $suma += $base[$i]->iva; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>

			<tr class="busqueda_fila">
				<td class="consepto_r21">IVA no acreditable</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
					<td class="valor_r21"><?php echo number_format($base[$i]->ivaNoAcreditable*(-1),2,'.',','); $suma += $base[$i]->ivaNoAcreditable; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma*(-1),2,'.',','); ?></td>
			</tr>

			<tr class="fondoAmarillo">
				<td class="consepto_r21 negritas">IVA Acreditable NETO</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
					<td class="valor_r21"><?php echo number_format($base[$i]->iva-$base[$i]->ivaNoAcreditable,2,'.',','); $suma += ($base[$i]->iva-$base[$i]->ivaNoAcreditable); ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>

			<tr class="busqueda_fila">
				<td class="consepto_r21">Gastos para generar ingresos gravados</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][1]->base,2,'.',','); $suma += $GastosInversiones[$i][1]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			
			<tr class="busqueda_fila">
				<td class="consepto_r21">Inversiones para generar  ingresos gravados</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][2]->base,2,'.',','); $suma += $GastosInversiones[$i][2]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21 negritas">IVA para generar ingresos gravados</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i]['ivaTotalGravados'],2,'.',','); $suma += $GastosInversiones[$i]['ivaTotalGravados']; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Gastos para generar ingresos exentos</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][7]->base,2,'.',','); $suma += $GastosInversiones[$i][7]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Inversiones para generar  ingresos exentos</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][9]->base,2,'.',','); $suma += $GastosInversiones[$i][9]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21 negritas">IVA para generar ingresos exentos</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i]['ivaTotalExentos'],2,'.',','); $suma += $GastosInversiones[$i]['ivaTotalExentos']; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Gastos para generar ingresos NO identificados</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][6]->base,2,'.',','); $suma += $GastosInversiones[$i][6]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Inversiones para generar  ingresos NO identificados</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][8]->base,2,'.',','); $suma += $GastosInversiones[$i][8]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21 negritas">IVA para generar ingresos no identificados</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i]['ivaTotalNoIndentificados'],2,'.',','); $suma += $GastosInversiones[$i]['ivaTotalNoIndentificados']; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Otros egresos</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][3]->base,2,'.',','); $suma += $GastosInversiones[$i][3]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Factor de acreditamiento</td>
				<?php for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($prop,4,'.',','); ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">IVA acreditable de gastos o inversiones para ingresos no identificados</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i]['ivaTotalNoIndentificados']*$prop,2,'.',','); $suma += ($GastosInversiones[$i]['ivaTotalNoIndentificados']*$prop); ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">IVA no acreditable por ingresos no identificados</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i]['ivaTotalNoIndentificados']*(1-$prop),2,'.',','); $suma += ($GastosInversiones[$i]['ivaTotalNoIndentificados']*(1-$prop)); ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="fondoAmarillo">
				<td class="consepto_r21 negritas">IVA acreditable</td>
				<?php 
				$suma = 0;
				$ivaAcr = array();
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i]['ivaTotalGravados']+($GastosInversiones[$i]['ivaTotalNoIndentificados']*(1-$prop)),2,'.',','); $suma += $GastosInversiones[$i]['ivaTotalGravados']+($GastosInversiones[$i]['ivaTotalNoIndentificados']*(1-$prop)); $ivaAcr[$i]=$GastosInversiones[$i]['ivaTotalGravados']+($GastosInversiones[$i]['ivaTotalNoIndentificados']*(1-$prop)); ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="fondoAmarillo">
				<td class="consepto_r21 negritas">IVA no acreditable que se va al gasto</td>
				<?php 
				$suma = 0;
				$ivaNoAcre = array();
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i]['ivaTotalExentos']+($GastosInversiones[$i]['ivaTotalNoIndentificados']*(1-$prop)),2,'.',','); $suma += $GastosInversiones[$i]['ivaTotalExentos']+($GastosInversiones[$i]['ivaTotalNoIndentificados']*(1-$prop)); $ivaNoAcre[$i]=$GastosInversiones[$i]['ivaTotalExentos']+($GastosInversiones[$i]['ivaTotalNoIndentificados']*(1-$prop)); ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="fondoAmarillo">
				<td class="consepto_r21 negritas">Total IVA acreditable + no acreditable</td>
				<?php 
				$suma=0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($ivaAcr[$i]+$ivaNoAcre[$i],2,'.',','); $suma += $ivaAcr[$i]+$ivaNoAcre[$i]; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Base del IVA efectivamente cobrado</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($cobrado[$i]->baseCobrado,2,'.',','); $suma += $cobrado[$i]->baseCobrado; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="fondoAmarillo">
				<td class="consepto_r21 negritas">IVA Causado</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($cobrado[$i]->ivaCausado,2,'.',','); $suma += $cobrado[$i]->ivaCausado; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Ingresos nacionales</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][4]->base,2,'.',','); $suma += $GastosInversiones[$i][4]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">Ingresos por exportación</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($GastosInversiones[$i][5]->base,2,'.',','); $suma += $GastosInversiones[$i][5]->base; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="fondoAmarillo">
				<td class="consepto_r21 negritas">IVA a favor o en contra</td>
				<?php 
				$suma = 0;
				$aFavorEnContra = array();
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format(($cobrado[$i]->ivaCausado-$ivaAcr[$i]),2,'.',','); $suma += ($cobrado[$i]->ivaCausado-$ivaAcr[$i]); $aFavorEnContra[$i]=($cobrado[$i]->ivaCausado-$ivaAcr[$i]); ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">IVA retenido </td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($cobrado[$i]->ivaRetenido,2,'.',','); $suma += $cobrado[$i]->ivaRetenido; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="busqueda_fila">
				<td class="consepto_r21">IVA acreditable retenido de meses anteriores</td>
				<?php 
				$suma = 0;
				for($i=0;$i<=11;$i++){ ?>
				<td class="valor_r21"><?php echo number_format($cobrado[$i]->ivaRetenido,2,'.',','); $suma += $cobrado[$i]->ivaRetenido; ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			<tr class="fondoAmarillo">
				<td class="consepto_r21 negritas">Total IVA del periodo</td>
				<?php 
				$suma = 0;
				for($i=1;$i<=12;$i++){ ?>
				<td class="valor_r21"><?php echo number_format(($aFavorEnContra[$i]+$cobrado[$i]->ivaRetenido-$cobrado[($i-1)]->ivaRetenido),2,'.',','); $suma += ($aFavorEnContra[$i]+$cobrado[$i]->ivaRetenido-$cobrado[($i-1)]->ivaRetenido); ?></td>
				<?php } ?>
				<td class="valor_r21"><?php echo number_format($suma,2,'.',','); ?></td>
			</tr>
			
		</tbody>
		<tfoot>
		</tfoot>
	</table>
	
</body>
</html>