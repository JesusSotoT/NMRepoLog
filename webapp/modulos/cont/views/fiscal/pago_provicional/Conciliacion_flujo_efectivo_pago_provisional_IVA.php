<?
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Conciliacion flujo efectivo pago provisional.xls");
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
		.totales{text-align: right; font: 11px arial; font-weight: bolder; border-top: 1px solid; vertical-align: top;}
		.cabecera{ font: 11px arial; vertical-align: bottom; border: 0px solid; text-align: center;}
		.cuerpo{ font: 11px arial; vertical-align: top; }
		.numero{text-align: right; font: 11px arial;}
		.tasa{font: 11px arial; font-weight: bolder; vertical-align: top; text-align: center;}
		.espacio{height: 15px;}
		
		
	</style>
</head>
<body>
	<table border="0" cellpadding="4" cellspacing="0" class="table_aux busqueda" width="995px">
		<thead>
			<?php
			if($toexcel==1){?> 
			<tr><td colspan="10" class="titulo_aux"><?php echo $organizacion->nombreorganizacion; ?></td></tr>
			<tr><td colspan="10" class="titulo_aux">Conciliacion de flujo de efectivo y pago provisional de IVA</td></tr> 	
			<tr><td colspan="10" class="titulo_aux">Periodo de <?php echo $fecha_ini.' a '.$fecha_fin; ?></td></tr>
			<tr class="fondo_gris">
				<td colspan="10" class="espacio">&nbsp;</td>
			</tr>
			<?php
			}
			?>
		
			<tr class="tit_tabla_buscar">
				<th class="cabecera" width="70px">Fecha</th>
				<th class="cabecera" width="70px">Tipo</th>
				<th class="cabecera" width="50px">Número</th>
				<th class="cabecera" width="200px">Concepto</th>
				<th class="cabecera" width="100px">Total Flujo Efectivo</th>
				<th class="cabecera" width="100px">Total</th>
				<th class="cabecera" width="100px">Base</th>
				<th class="cabecera" width="100px">IVA</th>
				<th class="cabecera" width="100px">IVA Pagado No Acred.</th>
				<th class="cabecera" width="100px">Otras Erogaciones</th>
				
			</tr>
		</thead>
		<tbody>
			<tr class="fondo_gris">
				<td colspan="10" class="espacio">&nbsp;</td>
			</tr>
		<?php
		foreach ($cuentasPolizas as $key => $cuentaFlujo) {//$cuentasPolizas
			if($key>0){
				//print_r($cuentaFlujo[0]->manual_code);
				
		?>
			<tr class="busqueda_fila">
				<td colspan="10" style="font: 14px arial; font-weight: bolder; vertical-align: top;"><?php echo $cuentaFlujo[0]->manual_code." ".$cuentaFlujo[0]->description; ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="10" class="espacio">&nbsp;</td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="2" class="tasa">I N G R E S O S</td><td colspan="8" class="cuerpo">&nbsp;</td>
			</tr>
		<?php
			$totalTotal=0;
			$totalBase=0;
			$totalIva=0;
			$totalNoAcred=0;
			foreach ($cuentaFlujo as $key2 => $cuentaPol) {
				//foreach($cuentaPol as $key3=>$d){
				if($key2>0 && $cuentaPol->tipo=="Ingresos"){
					$totalTotal+=$cuentaPol->total;
					$totalBase+=$cuentaPol->base;
					$totalIva+=$cuentaPol->ivafiscal;
					$totalNoAcred+=$cuentaPol->no_acreditable;
		?>
			<tr class="busqueda_fila">
				<td class="cuerpo" ><?php echo $cuentaPol->fecha; ?></td>
				<td class="cuerpo" ><?php echo $cuentaPol->tipo; ?></td>
				<td class="cuerpo" style="text-align: center;" ><?php echo $cuentaPol->num; ?></td>
				<td class="cuerpo" ><?php echo $cuentaPol->concepto; ?></td>
				<td class="numero" ><?php echo number_format(0.00,4,'.',','); ?></td>
				<td class="numero" ><?php echo number_format($cuentaPol->total,4,'.',','); ?></td>
				<td class="numero" ><?php echo number_format($cuentaPol->base,4,'.',','); ?></td>
				<td class="numero" ><?php echo number_format($cuentaPol->ivafiscal,4,'.',','); ?></td>
				<td class="numero" ></td>
				<td class="numero" ><?php echo number_format($cuentaPol->no_acreditable,4,'.',','); ?></td>
			</tr>
		<?php
				//}
			}
			}
					//print_r($cuentaFlujo[0]->description);
			
		?>
			<tr class="busqueda_fila">
				<td class="cuerpo" ></td>
				<td class="cuerpo" ></td>
				<td class="cuerpo" ></td>
				<td style="font: 11px arial; font-weight: bolder;" >Ingresos <?php echo $cuentaFlujo[0]->description; ?></td>
				<td class="totales" ><?php echo number_format("0.00",4,'.',',') ?></td>
				<td class="totales" ><?php echo number_format($totalTotal,4,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalBase,4,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIva,4,'.',','); ?></td>
				<td class="totales" ></td>
				<td class="totales" ><?php echo number_format($totalNoAcred,4,'.',','); ?></td>
			</tr>


			<tr class="fondo_gris">
				<td colspan="10" class="espacio"></td>
			</tr>
			<tr class="busqueda_fila">
				<td colspan="2" class="tasa">E G R E S O S</td><td colspan="8" class="cuerpo">&nbsp;</td>
			</tr>
		<?php
			$totalTotal=0;
			$totalBase=0;
			$totalIva=0;
			$totalNoAcred=0;
			$totalOtras = 0;
			foreach ($cuentaFlujo as $key2 => $cuentaPol) {
				if($key2>0 && $cuentaPol->tipo=="Egresos"){
					$totalTotal+=$cuentaPol->total;
					$totalBase+=$cuentaPol->base;
					$totalIva+=$cuentaPol->ivafiscal;
					$totalNoAcred+=$cuentaPol->no_acreditable;
					$totalOtras+=$cuentaPol->otrasErogaciones;
		?>
			<tr class="busqueda_fila">
				<td class="cuerpo" ><?php echo $cuentaPol->fecha; ?></td>
				<td class="cuerpo" ><?php echo $cuentaPol->tipo; ?></td>
				<td class="cuerpo" style="text-align: center;" ><?php echo $cuentaPol->num; ?></td>
				<td class="cuerpo" ><?php echo $cuentaPol->description; ?></td>
				<td class="numero" ><?php echo number_format("0.00",4,'.',','); ?></td>
				<td class="numero" ><?php echo number_format($cuentaPol->total,4,'.',','); ?></td>
				<td class="numero" ><?php echo number_format($cuentaPol->base,4,'.',','); ?></td>
				<td class="numero" ><?php echo number_format($cuentaPol->ivafiscal,4,'.',','); ?></td>
				<td class="numero" ><?php echo number_format($cuentaPol->no_acreditable,4,'.',','); ?></td>
				<td class="numero" ><?php echo number_format($cuentaPol->otrasErogaciones,4,'.',','); ?></td>
			</tr>
		<?php
			}
			}
		?>
			<tr class="busqueda_fila">
				<td class="cuerpo" ></td>
				<td class="cuerpo" ></td>
				<td class="cuerpo" ></td>
				<td style="font: 11px arial; font-weight: bolder;" >Egresos <?php echo $cuentaFlujo[0]->description; ?></td>
				<td class="totales" ><?php echo number_format("0.00",4,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalTotal,4,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalBase,4,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalIva,4,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalOtras,4,'.',','); ?></td>
				<td class="totales" ><?php echo number_format($totalNoAcred,4,'.',',');  ?></td>
			</tr>
			<tr class="fondo_gris">
				<td colspan="10" class="espacio"></td>
			</tr>
		<?php
		}

		}
		?>

			<tr class="fondo_gris">
				<td colspan="10" class="espacio"></td>
			</tr>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</body>
</html>

