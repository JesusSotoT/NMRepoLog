<?php
include("../../../netwarelog/webconfig.php");

$cont=0;
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$opc=$_REQUEST['opc' ];
	switch ($opc) {
		case 1:
					echo
'
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th class="nmcatalogbusquedatit" align="center">ID</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Cargo</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Vencimiento</th>
			<th class="nmcatalogbusquedatit" align="center">Nombre</th>
			<th class="nmcatalogbusquedatit" align="center">Concepto</th>
			<th class="nmcatalogbusquedatit" align="center">Folio de venta</th>
			<th class="nmcatalogbusquedatit" align="center">Monto</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Abonado</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Actual</th>
			<th class="nmcatalogbusquedatit" align="center">Estado</th>
			</tr>';
$idcliente=$_REQUEST['cliente'];
$consul=$conection->query("select c.idCxc ID,cc.nombre Nombre,c.concepto,
c.idVenta,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,
c.estatus,c.fechacargo,c.fechavencimiento,c.estatus
from cxc c,comun_cliente cc
where cc.id=c.idCliente  and cc.id=".$idcliente);
//$paginas=($consul->num_rows/$paginacion);if($consul->num_rows%$paginacion!=0){$paginas++;}

$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;
while($lista=$consul->fetch_array(MYSQLI_ASSOC)){
	 if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='nmcatalogbusquedacont_1';
		}
		else//Si es impar pinta esto
		{
    		$color='nmcatalogbusquedacont_2';
		}
$cont++;
	if($lista['estatus']==1){
		;
	echo '	<tr class="'.$color.'" style=" color:#0101DF; font-size: 10pt;">
	<td align="center"> '.$lista['ID'].' </td >
	<td align="center"> '.$lista['fechacargo'].' </td>
	<td align="center"> '.$lista['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($lista['Nombre']).' </td>
	<td align="center"> '.utf8_encode($lista['concepto']).' </td>
	<td align="center"> '.$lista['idVenta'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['monto'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['saldoabonado'].' </td>
	<td align="center" style="background:#FF0000;color: #FFFFFF"> '.$lista['SaldoActual'].' </td>
    <td align="center">SALDADA </td>
</tr>';
	}else{ if($lista['fechavencimiento']<=date('Y-m-d')){
echo'	<tr class="'.$color.'" style=" color:#FF0040; font-size: 10pt;">
	<td align="center"> '.$lista['ID'].' </td >
	<td align="center"> '.$lista['fechacargo'].' </td>
	<td align="center"> '.$lista['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($lista['Nombre']).' </td>
	<td align="center"> '.utf8_encode($lista['concepto']).' </td>
	<td align="center"> '.$lista['idVenta'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['monto'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['saldoabonado'].' </td>
	<td align="center" style="background:#FF0000;color: #FFFFFF"> '.$lista['SaldoActual'].' </td>
    <td align="center">VENCIDA </td>
</tr>';
	}else{
		
echo '		<tr class="'.$color.'" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$lista['ID'].' </td >
	<td align="center"> '.$lista['fechacargo'].' </td>
	<td align="center"> '.$lista['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($lista['Nombre']).' </td>
	<td align="center"> '.utf8_encode($lista['concepto']).' </td>
	<td align="center"> '.$lista['idVenta'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['monto'].' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.$lista['saldoabonado'].' </td>
	<td align="center" style="background:#FF0000;color: #FFFFFF"> '.$lista['SaldoActual'].' </td>
    <td align="center">ACTIVA </td>
</tr>';
	}
}
$monto=$monto+$lista['monto'];
	 $Saldoabonado=$Saldoabonado+$lista['saldoabonado']; 
     $SaldoActual=$SaldoActual+$lista['SaldoActual'];	 

}//while

	echo '<tr class="nmsubtitle"><td></td><td></td>
	<td></td><td></td>
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.$monto.'</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.$Saldoabonado.'</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.$SaldoActual.'</td>
	<td style="font-size: 14px;font-weight:bold"></td></tr>
';

	break;
	case 2:
   echo '
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th class="nmcatalogbusquedatit" align="center">ID</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Cargo</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Vencimiento</th>
			<th class="nmcatalogbusquedatit" align="center">Nombre</th>
			<th class="nmcatalogbusquedatit" align="center">Concepto</th>
			<th class="nmcatalogbusquedatit" align="center">Folio de venta</th>
			<th class="nmcatalogbusquedatit" align="center">Monto</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Abonado</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Actual</th>
			<th class="nmcatalogbusquedatit" align="center">Estado</th>
</tr>
';

	$inicio=$_REQUEST['inicio'];
	$fin=$_REQUEST['fin'];
	
	$buscaf=$conection->query("
select c.idCxc ID,cc.nombre Nombre,c.concepto,
c.idVenta,c.monto,c.saldoabonado,format(c.saldoactual,2)
 SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento
from cxc c,comun_cliente cc
where cc.id=c.idCliente and 
 c.fechavencimiento BETWEEN '".$inicio."' and '".$fin."' 

	");
	$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;
				
	 while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
	 	 if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='nmcatalogbusquedacont_1';
		}
		else//Si es impar pinta esto
		{
    		$color='nmcatalogbusquedacont_2';
		}
$cont++;
	 	if($listaf['estatus']==1){//saldada
		echo '
		<tr class="'.$color.'" style=" color:#0101DF; font-size: 10pt;">
	<td align="center" style=""> '.$listaf['ID'].' </td >
	<td align="center" style=""> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($listaf['Nombre']).' </td>
	<td align="center"> '.utf8_encode($listaf['concepto']).' </td>
	<td align="center"> '.$listaf['idVenta'].' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>
	<td align="center"> SALDADA </td>
	</tr>
		';
	}//if	
		else{
			if($listaf['fechavencimiento']<=date('Y-m-d ')){
		 echo '	
<tr class="'.$color.'" style=" color:#FF0040; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($listaf['Nombre']).' </td>
	<td align="center"> '.utf8_encode($listaf['concepto']).' </td>
	<td align="center"> '.$listaf['idVenta'].' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>
	<td align="center"> VENCIDA </td>

</tr>
	 ';		
				
			}else{
	 echo '	
<tr class="'.$color.'" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($listaf['Nombre']).' </td>
	<td align="center"> '.utf8_encode($listaf['concepto']).' </td>
	<td align="center"> '.$listaf['idVenta'].' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>
	<td align="center"> ACTIVA </td>

</tr>
	 ';
			}//else
			}//primer else
		
	 $monto=$monto+$listaf['monto'];
	 $Saldoabonado=$Saldoabonado+$listaf['saldoabonado']; 
     $SaldoActual=$SaldoActual+$listaf['SaldoActual'];	 }

	echo '<tr class="nmsubtitle"><td></td><td></td>
	<td></td><td></td>
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.$monto.'</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.$Saldoabonado.'</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.$SaldoActual.'</td>
	<td align="center" style="font-size: 14px;font-weight:bold"></td></tr>
';
	 
   break;
   case 3://cxp
					echo
'
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th class="nmcatalogbusquedatit" align="center">ID</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Cargo</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Vencimiento</th>
			<th class="nmcatalogbusquedatit" align="center">Concepto</th>
			<th class="nmcatalogbusquedatit" align="center">Proveedor</th>
			<th class="nmcatalogbusquedatit" align="center">Monto</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Abonado</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Actual</th>
			<th class="nmcatalogbusquedatit" align="center">Estado</th>
			</tr>';
$concepto=$_REQUEST['concepto'];
$consul=$conection->query("select c.idCxp ID,c.concepto,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,c.fechacargo,c.fechavencimiento,
c.estatus,(select  case mp.razon_social when mp.razon_social THEN mp.razon_social ELSE null end from mrp_proveedor mp
WHERE   mp.idPrv=c.idProveedor) prove
from cxp c
WHERE   c.concepto='".$concepto."'");

$estatus="";
$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;

while($lista=$consul->fetch_array(MYSQLI_ASSOC)){
	 if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='nmcatalogbusquedacont_1';
		}
		else//Si es impar pinta esto
		{
    		$color='nmcatalogbusquedacont_2';
		}
$cont++;
	if($lista['estatus']==1){
		echo '
<tr class="'.$color.'" style="color:#0101DF; font-size: 10pt;">
	<td align="center"> '.$lista['ID'].' </td >
	<td align="center"> '.$lista['fechacargo'].' </td>
	<td align="center"> '.$lista['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($lista['concepto']).' </td>
		<td align="center"> '.utf8_encode($lista['prove']).' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.number_format($lista['monto'], 2, '.', ',').' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.number_format($lista['saldoabonado'], 2, '.', ',').' </td>
	<td align="center" style="background:#FF0000;color: #FFFFFF"> '.number_format($lista['SaldoActual'], 2, '.', ',').' </td>
    <td align="center"> SALDADA </td>
</tr>
';
	}else { if($lista['fechavencimiento']<=date('Y-m-d')){
		echo '
<tr class="'.$color.'" style=" color:#FF0040; font-size: 10pt;">
	<td align="center"> '.$lista['ID'].' </td >
	<td align="center"> '.$lista['fechacargo'].' </td>
	<td align="center"> '.$lista['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($lista['concepto']).' </td>
		<td align="center"> '.utf8_encode($lista['prove']).' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.number_format($lista['monto'], 2, '.', ',').' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.number_format($lista['saldoabonado'], 2, '.', ',').' </td>
	<td align="center" style="background:#FF0000;color: #FFFFFF"> '.number_format($lista['SaldoActual'], 2, '.', ',').' </td>
    <td align="center">VENCIDA </td>
</tr>
';
	}else{
		echo '
<tr class="'.$color.'" style="color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$lista['ID'].' </td >
	<td align="center"> '.$lista['fechacargo'].' </td>
	<td align="center"> '.$lista['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($lista['concepto']).' </td>
		<td align="center"> '.utf8_encode($lista['prove']).' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.number_format($lista['monto'], 2, '.', ',').' </td>
	<td align="center" style="background:#333333;color: #FFFFFF"> '.number_format($lista['saldoabonado'], 2, '.', ',').' </td>
	<td align="center" style="background:#FF0000;color: #FFFFFF"> '.number_format($lista['SaldoActual'], 2, '.', ',').' </td>
    <td align="center">ACTIVA </td>
</tr>
';
	}
		
	}

$monto=$monto+$lista['monto'];
	 $Saldoabonado=$Saldoabonado+$lista['saldoabonado']; 
     $SaldoActual=$SaldoActual+$lista['SaldoActual'];	 
 }
echo '<tr class="nmsubtitle"><td></td>
	<td></td><td></td>
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.number_format($monto, 2, '.', ',').'</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.number_format($Saldoabonado, 2, '.', ',').'</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.number_format($SaldoActual, 2, '.', ',').'</td>
	<td style="font-size: 14px;font-weight:bold"></td></tr>
';
	break;////
	case 4:
   echo '
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th class="nmcatalogbusquedatit" align="center">ID</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Cargo</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Vencimiento</th>
			<th class="nmcatalogbusquedatit" align="center">Concepto</th>
			<th class="nmcatalogbusquedatit" align="center">Proveedor</th>
			<th class="nmcatalogbusquedatit" align="center">Monto</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Abonado</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Actual</th>
			<th class="nmcatalogbusquedatit" align="center">Estado</th>
			</tr>
';

	$inicio=$_REQUEST['inicio'];
	$fin=$_REQUEST['fin'];
	
	$buscaf=$conection->query("select c.idCxp ID,c.concepto,
	c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,
	c.fechacargo,c.fechavencimiento,c.estatus,
(select  case mp.razon_social when mp.razon_social THEN mp.razon_social ELSE null end from mrp_proveedor mp
WHERE   mp.idPrv=c.idProveedor) prove
    from cxp c
    WHERE 
    c.fechavencimiento BETWEEN '".$inicio."' and '".$fin."' ");
	$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;
				
	 while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
	 	 if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='nmcatalogbusquedacont_1';
		}
		else//Si es impar pinta esto
		{
    		$color='nmcatalogbusquedacont_2';
		}
$cont++;
	 		if($listaf['estatus']==1){//saldada
	 echo '	
<tr class="'.$color.'" style=" color:#0101DF; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($listaf['concepto']).' </td>
		<td align="center"> '.utf8_encode($listaf['prove']).' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>
	<td align="center"> SALDADA </td>

</tr>
	 ';
	 }else{
	 	if($listaf['fechavencimiento']<=date('Y-m-d ')){
	echo '	
<tr class="'.$color.'" style=" color:#FF0040; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($listaf['concepto']).' </td>
		<td align="center"> '.utf8_encode($listaf['prove']).' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>
	<td align="center"> VENCIDA </td>

</tr>
	 ';
	 }else{
	 echo '	
<tr class="'.$color.'" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($listaf['concepto']).' </td>
		<td align="center"> '.utf8_encode($listaf['prove']).' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>
	<td align="center"> ACTIVA </td>

</tr>
	 ';	
	 }
}//primer else	
	$monto=$monto+$listaf['monto'];
	 $Saldoabonado=$Saldoabonado+$listaf['saldoabonado']; 
     $SaldoActual=$SaldoActual+$listaf['SaldoActual'];	
	 }
	echo '<tr class="nmsubtitle"><td></td><td></td><td></td>
	
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td style="font-size: 14px;font-weight:bold">$'.number_format($monto, 2, '.', ',').'</td>
	<td style="font-size: 14px;font-weight:bold">$'. number_format($Saldoabonado, 2, '.', ',').'</td>
	<td style="font-size: 14px;font-weight:bold">$'.number_format($SaldoActual, 2, '.', ',').'</td>
	<td style="font-size: 14px;font-weight:bold"></td>
	</tr>
';
	 
   break;
   
case 5:
	$estado=$_REQUEST['estado'];
	$escrito="";
	echo '
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th class="nmcatalogbusquedatit" align="center">ID</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Cargo</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Vencimiento</th>
			<th class="nmcatalogbusquedatit" align="center">Nombre</th>
			<th class="nmcatalogbusquedatit" align="center">Concepto</th>
			<th class="nmcatalogbusquedatit" align="center">Folio de venta</th>
			<th class="nmcatalogbusquedatit" align="center">Monto</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Abonado</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Actual</th>
			<th class="nmcatalogbusquedatit" align="center">Estado</th>
</tr>
';
	if($estado==1){
	$buscaf=$conection->query("
select c.idCxc ID,cc.nombre Nombre,c.concepto,
c.idVenta,c.monto,c.saldoabonado,format(c.saldoactual,2)
 SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento
from cxc c,comun_cliente cc
where cc.id=c.idCliente and c.estatus=1");	
$escrito="SALDADA";
	}
	elseif($estado==0){
		$buscaf=$conection->query("
select c.idCxc ID,cc.nombre Nombre,c.concepto,
c.idVenta,c.monto,c.saldoabonado,format(c.saldoactual,2)
 SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento
from cxc c,comun_cliente cc
where cc.id=c.idCliente and c.estatus=0");
$escrito="ACTIVA";
	}
	elseif($estado!=1 && $estado!=0 && $estado!="todo"){
		$buscaf=$conection->query("
select c.idCxc ID,cc.nombre Nombre,c.concepto,
c.idVenta,c.monto,c.saldoabonado,format(c.saldoactual,2)
 SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento
from cxc c,comun_cliente cc
where cc.id=c.idCliente and c.estatus=0 and
 c.fechavencimiento<= '".$estado."' ");
 $escrito="VENCIDA";
	}
	
	$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;
// 						
	 
	 if($buscaf->num_rows>0){
	 while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
	 	 if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='nmcatalogbusquedacont_1';
		}
		else//Si es impar pinta esto
		{
    		$color='nmcatalogbusquedacont_2';
		}
$cont++;
		 echo '	
<tr class="'.$color.'" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($listaf['Nombre']).' </td>
	<td align="center"> '.utf8_encode($listaf['concepto']).' </td>
	<td align="center"> '.$listaf['idVenta'].' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>
	<td align="center"> '.$escrito.' </td>

</tr>
	 ';		
	 $monto=$monto+$listaf['monto'];
	 $Saldoabonado=$Saldoabonado+$listaf['saldoabonado']; 
     $SaldoActual=$SaldoActual+$listaf['SaldoActual'];	 }

	echo '<tr class="nmsubtitle"><td></td><td></td>
	<td></td><td></td>
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td style="font-size: 14px;font-weight:bold">$'.$monto.'</td>
	<td style="font-size: 14px;font-weight:bold">$'.$Saldoabonado.'</td>
	<td style="font-size: 14px;font-weight:bold">$'.$SaldoActual.'</td>
	<td style="font-size: 14px;font-weight:bold"></td></tr>
';}else{
	echo '	
<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	<td style="background:#333333;color: #FFFFFF" height="30%" colspan="10"  style="font-size:14px; text-align: center; height: 34px; font-weight:bold"> 
	No existen datos que coincidieran con su búsqueda. </td >

</tr>
	 ';
}
	break;
case 6:
	$estado=$_REQUEST['estado'];
	$escrito="";
	echo '
<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th class="nmcatalogbusquedatit" align="center">ID</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Cargo</th>
			<th class="nmcatalogbusquedatit" align="center">Fecha Vencimiento</th>
			<th class="nmcatalogbusquedatit" align="center">Concepto</th>
			<th class="nmcatalogbusquedatit" align="center">Proveedor</th>
			<th class="nmcatalogbusquedatit" align="center">Monto</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Abonado</th>
			<th class="nmcatalogbusquedatit" align="center">Saldo Actual</th>
			<th class="nmcatalogbusquedatit" align="center">Estado</th>
</tr>
';
	if($estado==1){
	$buscaf=$conection->query("
select c.idCxp ID,c.concepto,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,c.fechacargo,c.fechavencimiento,
c.estatus,(select  case mp.razon_social when mp.razon_social THEN mp.razon_social ELSE null end from mrp_proveedor mp
WHERE   mp.idPrv=c.idProveedor) prove
from cxp c
WHERE  c.estatus=1");	
$escrito="SALDADA";
	}
	elseif($estado==0){
		$buscaf=$conection->query("
select c.idCxp ID,c.concepto,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,c.fechacargo,c.fechavencimiento,
c.estatus,(select  case mp.razon_social when mp.razon_social THEN mp.razon_social ELSE null end from mrp_proveedor mp
WHERE   mp.idPrv=c.idProveedor) prove
from cxp c
WHERE  c.estatus=0");
$escrito="ACTIVA";
	}
	elseif($estado!=1 && $estado!=0){
		$buscaf=$conection->query("select c.idCxp ID,c.concepto,c.monto,c.saldoabonado,format(c.saldoactual,2) SaldoActual,c.fechacargo,c.fechavencimiento,
c.estatus,(select  case mp.razon_social when mp.razon_social THEN mp.razon_social ELSE null end from mrp_proveedor mp
WHERE   mp.idPrv=c.idProveedor) prove
from cxp c
WHERE  c.estatus=0 and
 c.fechavencimiento<= '".$estado."' ");
 $escrito="VENCIDA";
	}
	
	$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;
// 						
	 
	 if($buscaf->num_rows>0){
	 while($listaf=$buscaf->fetch_array(MYSQLI_ASSOC)){
	 	 if ($cont%2==0)//Si el contador es par pinta esto en la fila del grid
		{
    		$color='nmcatalogbusquedacont_1';
		}
		else//Si es impar pinta esto
		{
    		$color='nmcatalogbusquedacont_2';
		}
$cont++;
		 echo '	
<tr class="'.$color.'" style=" color:#6E6E6E; font-size: 10pt;">
	<td align="center"> '.$listaf['ID'].' </td >
	<td align="center"> '.$listaf['fechacargo'].' </td>
	<td align="center"> '.$listaf['fechavencimiento'].' </td>
	<td align="center"> '.utf8_encode($listaf['concepto']).' </td>
		<td align="center"> '.utf8_encode($listaf['prove']).' </td>
	<td align="center"> '.$listaf['monto'].' </td>
	<td align="center"> '.$listaf['saldoabonado'].' </td>
	<td align="center"> '.$listaf['SaldoActual'].'</td>
	<td align="center"> '.$escrito.' </td>

</tr>
	 ';		
	 $monto=$monto+$listaf['monto'];
	 $Saldoabonado=$Saldoabonado+$listaf['saldoabonado']; 
     $SaldoActual=$SaldoActual+$listaf['SaldoActual'];	 }

	echo '<tr class="nmsubtitle"><td></td>
	<td></td><td></td>
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.number_format($monto, 2, '.', ',').'</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.number_format($Saldoabonado, 2, '.', ',').'</td>
	<td align="center" style="font-size: 14px;font-weight:bold">$'.number_format($SaldoActual, 2, '.', ',').'</td>
	<td align="center" style="font-size: 14px;font-weight:bold"></td></tr>
';}else{
	echo '	
<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
	<td style="background:#333333;color: #FFFFFF" height="30%" colspan="10" align="center" style="font-size:14px; text-align: center; height: 34px; font-weight:bold"> 
	No existen datos que coincidieran con su búsqueda. </td >

</tr>
	 ';
}
	break;
} 
?>