<?php
include("../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$opc=$_REQUEST['opc' ];
	switch ($opc) {
		case 1:
					echo
'
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th align="center">ID</th>
			<th align="center">Fecha Cargo</th>
			<th align="center">Fecha Vencimiento</th>
			<th align="center">Nombre</th>
			<th align="center">Concepto</th>
			<th align="center">Folio de venta</th>
			<th align="center">Monto</th>
			<th align="center">Saldo Abonado</th>
			<th align="center">Saldo Actual</th></tr>';
$idcliente=$_REQUEST['cliente'];
$consul=$conection->query("select c.idCxc ID,cc.nombre Nombre,c.concepto,c.idVenta,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento
from cxc c,comun_cliente cc
where cc.id=c.idCliente and   c.estatus= 0 and cc.id=".$idcliente." GROUP BY cc.nombre");
//$paginas=($consul->num_rows/$paginacion);if($consul->num_rows%$paginacion!=0){$paginas++;}

while($lista=$consul->fetch_array(MYSQLI_ASSOC)){
echo '
<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$lista['ID'].' </td >
	<td align="center"> '.$lista['fechacargo'].' </td>
	<td align="center"> '.$lista['fechavencimiento'].' </td>
	<td align="center"> '.$lista['Nombre'].' </td>
	<td align="center"> '.$lista['concepto'].' </td>
	<td align="center"> '.$lista['idVenta'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['monto'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['saldoabonado'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['SaldoActual'].' </td>

</tr>
'; }
	break;
	case 2:
   echo '
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th align="center">ID</th>
			<th align="center">Fecha Cargo</th>
			<th align="center">Fecha Vencimiento</th>
			<th align="center">Nombre</th>
			<th align="center">Concepto</th>
			<th align="center">Folio de venta</th>
			<th align="center">Monto</th>
			<th align="center">Saldo Abonado</th>
			<th align="center">Saldo Actual</th>
</tr>
';
	$inicio=$_REQUEST['inicio'];
	$fin=$_REQUEST['fin'];
	
	$buscaf=$conection->query("
select c.idCxc ID,cc.nombre Nombre,c.concepto,
c.idVenta,c.monto,c.saldoabonado,format(c.saldoactual,2)
 SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento
from cxc c,comun_cliente cc
where cc.id=c.idCliente and   c.estatus= 0 
and c.fechacargo BETWEEN '".$inicio."' and '".$fin."' and
 c.fechavencimiento BETWEEN '".$inicio."' and '".$fin."' 
GROUP BY cc.nombre
	");
	$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;
	 while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
	 echo '	
<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.$listaf['Nombre'].' </td>
	<td align="center"> '.$listaf['concepto'].' </td>
	<td align="center"> '.$listaf['idVenta'].' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>

</tr>
	 ';	
	 $monto=$monto+$listaf['monto'];
	 $Saldoabonado=$Saldoabonado+$listaf['saldoabonado']; 
     $SaldoActual=$SaldoActual+$listaf['SaldoActual'];	 }

	echo '<tr style="background:#333333;color: #FFFFFF"><td></td><td></td>
	<td></td><td></td>
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td style="font-size: 14px;font-weight:bold">$'.$monto.'</td>
	<td style="font-size: 14px;font-weight:bold">$'.$Saldoabonado.'</td>
	<td style="font-size: 14px;font-weight:bold">$'.$SaldoActual.'</td></tr>
';
	 
   break;
   case 3:
					echo
'
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th align="center">ID</th>
			<th align="center">Fecha Cargo</th>
			<th align="center">Fecha Vencimiento</th>
			<th align="center">Concepto</th>
			<th align="center">Monto</th>
			<th align="center">Saldo Abonado</th>
			<th align="center">Saldo Actual</th>
			</tr>';
$concepto=$_REQUEST['concepto'];
$consul=$conection->query("select c.idCxp ID,c.concepto,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,c.fechacargo,c.fechavencimiento
from cxp c
WHERE c.estatus= 0 and c.concepto='".$concepto."'");

while($lista=$consul->fetch_array(MYSQLI_ASSOC)){
echo '
<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$lista['ID'].' </td >
	<td align="center"> '.$lista['fechacargo'].' </td>
	<td align="center"> '.$lista['fechavencimiento'].' </td>
	<td align="center"> '.$lista['concepto'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['monto'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['saldoabonado'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['SaldoActual'].' </td>

</tr>
'; }
	break;
	case 4:
   echo '
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th align="center">ID</th>
			<th align="center">Fecha Cargo</th>
			<th align="center">Fecha Vencimiento</th>
			<th align="center">Concepto</th>
			<th align="center">Monto</th>
			<th align="center">Saldo Abonado</th>
			<th align="center">Saldo Actual</th>
			</tr>
';
	$inicio=$_REQUEST['inicio'];
	$fin=$_REQUEST['fin'];
	
	$buscaf=$conection->query("select c.idCxp ID,c.concepto,
	c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,
	c.fechacargo,c.fechavencimiento
    from cxp c
    WHERE (c.fechacargo BETWEEN '".$inicio."' and '".$fin."' ||
    c.fechavencimiento BETWEEN '".$inicio."' and '".$fin."')and 
	c.estatus=0");
	$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;
	 while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
	 echo '	
<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.$listaf['concepto'].' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>

</tr>
	 ';	
	$monto=$monto+$listaf['monto'];
	 $Saldoabonado=$Saldoabonado+$listaf['saldoabonado']; 
     $SaldoActual=$SaldoActual+$listaf['SaldoActual'];	
	 }
	echo '<tr style="background:#333333;color: #FFFFFF"><td></td><td></td>
	
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td style="font-size: 14px;font-weight:bold">$'.$monto.'</td>
	<td style="font-size: 14px;font-weight:bold">$'. $Saldoabonado.'</td>
	<td style="font-size: 14px;font-weight:bold">$'.$SaldoActual.'</td></tr>
';
	 
   break;
} 
?>