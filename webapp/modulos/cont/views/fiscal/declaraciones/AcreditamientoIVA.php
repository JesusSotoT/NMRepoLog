<?
//header("Content-Type: application/vnd.ms-excel");
//header("Expires: 0");
//header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//header("content-disposition: attachment;filename=pruebas_excel.xls");
?>
<html>
<head>
	<title>Auxiliar Impuestos</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<style type="text/css">
		.titulo_aux{text-align: center; font: 20px arial; border: 0px solid; }
		.totales_aux{text-align: center; font: 11px arial; font-weight: bold; border: 1px solid;}
		.cabecera{ font: 11px arial; vertical-align: bottom; border: 2px solid; text-align: center;}
		.cuerpo{ font: 11px arial; vertical-align: top; }
		.numero{text-align: right;}
		
	</style>
</head>
<body>
	<table border="0" cellpadding="1" cellspacing="1" border="1" class="table_aux" width="3160px">
		<thead>
			<tr><td colspan="16" class="titulo_aux">Nombre de la empresa</td><td colspan="13" class="titulo_aux">&nbsp;</td></tr>
			<tr><td colspan="16" class="titulo_aux">Auxiliar de Impuestos Ingresos</td><td colspan="13" class="titulo_aux">&nbsp;</td></tr> 	
			<tr><td colspan="16" class="titulo_aux">Periodo de Acreditamiento Fecha</td><td colspan="13" class="titulo_aux">&nbsp;</td></tr>
		</thead>
		<tbody>
			<tr>
				<td class="cabecera" width="70px">Fecha Póliza</td>
				<td class="cabecera" width="70px">Tipo Póliza</td>
				<td class="cabecera" width="70px">Número Póliza</td>
				<td class="cabecera" width="150px">Concepto Póliza</td>
				<td class="cabecera" width="70px">Ejercicio acreditamiento</td>
				<td class="cabecera" width="70px">Periodo acreditamiento</td>
				<td class="cabecera" width="70px">Tasa IVA</td>
				<td class="cabecera" width="100px">Compras y Gastos (Base del IVA)</td>
				<td class="cabecera" width="130px">Actos y Actividades Pagados (Base del IVA Acreditable)</td>
				<td class="cabecera" width="100px">IVA de Compras y Gastos (Importe de IVA)</td>
				<td class="cabecera" width="70px">IVA Pagado no Acreditable</td>
				<td class="cabecera" width="70px">IVA Retenido</td>
				<td class="cabecera" width="100px">IVA Acreditable Retenido de Meses Anteriores</td>
				<td class="cabecera" width="120px">IVA de Actos y Actividades Pagados (IVA Acreditable Neto)</td>
				<td class="cabecera" width="100px">Deducciones Autorizadas para IETU</td>
				<td class="cabecera" width="120px">Acredimientos para IETU</td>
				<td class="cabecera" width="110px">Concepto IETU</td>
				<td class="cabecera" width="110px">Para IVA</td>
				<td class="cabecera" width="110px">Para IETU</td>
				<td class="cabecera" width="110px">ISR Retenido</td>
				<td class="cabecera" width="110px">Otros</td>
				<td class="cabecera" width="110px">Código Proveedor</td>
				<td class="cabecera" width="250px">Nombre Proveedor</td>
				<td class="cabecera" width="70px">RFC Proveedor</td>
				<td class="cabecera" width="70px">Serie Movimiento Proveedor</td>
				<td class="cabecera" width="70px">Folio Movimiento Proveedor</td>
				<td class="cabecera" width="120px">Referencia Movimiento Proveedor</td>
				<td class="cabecera" width="180px">Tipo de tercero</td>
				<td class="cabecera" width="250px">Tipo de operación</td>

				
				
			</tr>
			<tr>
				<td colspan="7" class="totales_aux">&nbsp;</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td colspan="3" class="totales_aux">&nbsp;</td>
				<td class="totales_aux">0.00</td>
				<td class="totales_aux">0.00</td>
				<td colspan="8" class="totales_aux">&nbsp;</td>
				
			</tr>
			<?php
			for($i=0;$i<100;$i++){?>
			<tr>
				<td class="cuerpo" align="center">21-ene-2014</td>
				<td class="cuerpo">Egresos</td>
				<td class="cuerpo numero" >1</td>
				<td class="cuerpo" >PAGO DE FACTURA 2345</td>
				<td class="cuerpo" align="center">2013</td>
				<td class="cuerpo">Diciembre</td>
				<td class="cuerpo numero">16%</td>
				<td class="cuerpo numero" >1,000.00</td>
				<td class="cuerpo numero" >1,000.00</td>
				<td class="cuerpo numero" >160.00</td>
				<td class="cuerpo numero" >0.00</td>
				<td class="cuerpo numero" >0.00</td>
				<td class="cuerpo numero" >160.00</td>
				<td class="cuerpo numero" >160.00</td>
				<td class="cuerpo numero" >1,000.00</td>
				<td class="cuerpo numero" >0.00</td>
				<td class="cuerpo numero" >0</td>
				<td class="cuerpo numero" >cosa</td>
				<td class="cuerpo numero" >cosa</td>
				<td class="cuerpo numero" >0.00</td>
				<td class="cuerpo numero" >0.00</td>
				<td class="cuerpo numero" >1</td>
				<td class="cuerpo" >PROVEEDOR DE LA INDUSTRIA SA DE CV</td>
				<td class="cuerpo" >PIN020203I00</td>
				<td class="cuerpo numero" >cosa</td>
				<td class="cuerpo numero" >2,345</td>
				<td class="cuerpo" >cosa</td>
				<td class="cuerpo" align="right">04 - Proveedor Nacional</td>
				<td class="cuerpo" align="right">03 - Prestación de Servicios Profesionales</td>
				
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</body>
</html>

