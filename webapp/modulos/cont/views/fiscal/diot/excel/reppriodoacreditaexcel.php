<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=reporteacreditamiento.xls");
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
	
	</head>
	<body>
		<b style="font-size:16px; color:#6E6E6E; text-align: center;">&nbsp;Movimiento con Proveedores.</b><br>
	</br>
		<h2 align="center"  style="font-size:16px; color:#6E6E6E; text-align: center;"><div id="empresa"><?php echo $empresa; ?></div></h2>
		<h3 align="center" style="font-size:16px; color:#6E6E6E; text-align: center;">Movimientos con Proveedores</h3>
		<h4 id="periodo" align="center" style="font-size:16px; color:#6E6E6E; text-align: center;"><?php echo $periodo; ?></h4>
<p align="right" style="font-size:16px; color:#6E6E6E; text-align: right;margin-right: 55px;">Fecha : <label id="fech"><?php echo $fecha; ?></label></p>


		<table align='center' class="busqueda" id="datos" width='90%'cellpadding="1" cellspacing="2" >
			<thead>
				<tr class="tit_tabla_buscar">
				<th align="center">Fecha</th>
				<th align="center">Tipo</th>
				<th align="center">Número</th>
				<th align="center">Concepto</th>
				<th align="center" >Ejercicio</th>
				<th align="center">Periodo</th>
				<th align="center" width='30px'>Importe Base</th>
				<th align="center" width='70px'>Importe IVA Acreditable</th>
				<th align="center" width='30px' >Total Erogación</th>
				</tr>
			</thead>
			<tbody><?php echo $bodyfi; ?></tbody>
			<tfoot></tfoot>
		</table>
		<br/><br/>
		<input type="button" value="Regresar" onclick="javascript:regreso();"/>
	</body>
	</html>