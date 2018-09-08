<?php 

$contenido = '<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Corte de Caja</title>
</head>';

$contenido.="<style>
	#pagosGrid > td {
		border: 1px solid black;
	}
</style>";    
$contenido .='<body><div style="width:100%; align:center;"><h3>Corte '.$idCorte.'</h3></div>';

$contenido .='<div style="width:100%; align:center;">
				<table>
					<tr>
						<th>    
						
					        <label>Desde</label>
                            <h4>'.$saldos[0]["fechainicio"].'</h4>

                    	</th>
						<th>
							<div style="margin-left:12px">
								<label>Hasta</label>
                           		<h4>'.$saldos[0]["fechafin"].'</h4>
							</div>
						</th>
					</tr>
				</table>
			</div><br />'; 
$contenido .='<div style="width:100%">
				<table style="border-collapse: collapse; border: 1px solid black;">
				   <tr>
				      <th colspan="4"><h4>Saldos</h4></th>
				   </tr>
				   <tr>
						<td style="width:25%; border: 1px solid black;">
							<div style="margin-left:8%">
						  <label>Saldo inicial Caja </label>
                          <h4>$'.number_format($saldos[0]['saldoinicialcaja'],2).'</h4>
                          </div>
						</td>
						<td style="width:25%; border: 1px solid black;">
							<div style="margin-left:8%">
							<label>Monto de Ventas en el Periodo</label>
                            <h4>$'.number_format($saldos[0]['montoventa'],2).'</h4>
                            </div>
						</td>
						<td style="width:25%;  border: 1px solid black;">
							<div style="margin-left:8%">
							<label>Saldo Retiros de Caja</label>
                            <h4>$'.number_format($cantidadREt,2).'</h4>
                            </div> 
						</td>
						<td style="width:25%;  border: 1px solid black;">
							<div style="margin-left:8%">
							<label>Saldo disponible en Caja</label>
                            <h4>$'.number_format($dispoMeRe,2).'</h4>
                            </div>
						</td>
				   </tr>
					<tr>
				      <th colspan="4"><h4>Depositos/Retiros</h4></th>
				   </tr>
				   <tr>
						<td style="width:25%; border: 1px solid black;">
						<div style="margin-left:8%">
								<label>Retiro de Corte Caja </label>
                                <h4>$'.number_format($saldos[0]['retirocaja'],2).'</h4>
						</div>
						</td>
						<td style="width:25%; border: 1px solid black;">
						<div style="margin-left:8%">
								<label>Deposito de Caja</label>
                                <h4>$'.number_format($saldos[0]['abonocaja'],2).'</h4>
						</div>
						</td>
						<td style="width:25%; border: 1px solid black;">
						<div style="margin-left:8%">
								<label>Saldo Final</label>
                                <h4>$'.number_format($saldoFinal,2).'</h4>
						</div>
						</td>
						<td style="width:25%; border: 1px solid black;">
						<div style="margin-left:8%">
								<label>Usuario: </label>
                                <h4> '.$saldos[0]['usuario'].'</h4>
						</div>
						</td>	   
				   </tr>

				</table>
	
</div>'; 

///esta si
$contenido .='<div style="width:100%; align:center;"><h3>Pagos</h3></div>';
$contenido .='<div style="width:100%; align:center;">
				<table   id="pagosGrid" style="font-size:9px; border: 1px solid black;">
         		 <tr style="background-color:#dcdcdc;">
                                                    <th>ID Venta</th>
                                                    <th>Cliente</th>
                                                    <th>Fecha</th>
                                                    <th>EF</th>
                                                    <th>TC</th>
                                                    <th>TD</th>
                                                    <th>CR</th>
                                                    <th>CH</th>
                                                    <th>TRA</th>
                                                    <th>SPEI</th>
                                                    <th>TR</th>
                                                    <th>NI</th>
                                                    <th>Cambio</th>
                                                    <th>Impuestos</th>
                                                    <th>Monto</th>
                                                    <th>Des.</th>
                                                    <th>Importe</th>
                                                    <th>Ingreso(EF-Cambio)</th>
                                                </tr>';
                                            	foreach ($resumenCorte['ventas'] as $key => $value) {
										            if($value['nombre']==null){
										                $cliente = 'Publico General';
										            }else{
										                $cliente = $value['nombre'];           
										            }
										            $efectivoCambio = ($value['Efectivo'] - $value['cambio']);
										            $contenido.= '<tr>';
                                            		$contenido.= '<td>'.$value['idVenta'].'</td>';
                                            		$contenido.= '<td>'.$cliente.'</td>';
                                            		$contenido.= '<td>'.$value['fecha'].'</td>';
                                            		$contenido.= '<td align="center">$'.$value['Efectivo'].'</td>';
                                            		$efectivo += $value['Efectivo'];
                                            		$contenido.= '<td align="center">$'.$value['TCredito'].'</td>';
                                            		$TCredito +=$value['TCredito'];
                                            		$contenido.= '<td align="center">$'.$value['TDebito'].'</td>';
                                            		$TDebito += $value['TDebito'];
                                            		$contenido.= '<td align="center">$'.$value['CxC'].'</td>';
                                            		$CxC += $value['CxC'];
                                            		$contenido.= '<td align="center">$'.$value['Cheque'].'</td>';
                                            		$Cheque += $value['Cheque'];
                                            		$contenido.= '<td align="center">$'.$value['Trans'].'</td>';
                                            		$Trans += $value['Trans'];
                                            		$contenido.= '<td align="center">$'.$value['SPEI'].'</td>';
                                            		$SPEI += $value['SPEI'];
                                            		$contenido.= '<td align="center">$'.$value['TRegalo'].'</td>';
                                            		$TRegalo += $value['TRegalo'];
                                            		$contenido.= '<td align="center">$'.$value['Ni'].'</td>';
                                            		$Ni += $value['Ni'];
                                            		$contenido.= '<td align="center">$'.$value['cambio'].'</td>';
                                            		$cambio += $value['cambio'];
                                            		$contenido.= '<td align="center">$'.$value['Impuestos'].'</td>';
                                            		$Impuestos += $value['Impuestos'];
                                            		$contenido.= '<td align="center">$'.$value['Monto'].'</td>';
                                            		$Monto += $value['Monto'];
                                                    $contenido.= '<td align="center">$'.number_format($value['descuentoGeneral'],2).'</td>';
                                                    $dess += $value['descuentoGeneral'];
                                            		$contenido.= '<td align="center">$'.$value['Importe'].'</td>';
                                            		$Importe += $value['Importe'];
                                            		$contenido.= '<td align="center">$'.$efectivoCambio.'</td>';
                                            		$efectivoCambioSum += $efectivoCambio;
                                            		$contenido.= '</tr>';

                                            	} 
                                            	$contenido.= '<tr style="background:white;">';
                                            	$contenido.= '<td colspan="3">Totales</td>';
                                            	$contenido.= '<td>$'.number_format($efectivo,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($TCredito,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($TDebito,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($CxC,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($Cheque,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($Trans,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($SPEI,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($TRegalo,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($Ni,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($cambio,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($Impuestos,2).'</td>';
                                            	$contenido.= '<td>$'.number_format($Monto,2).'</td>';
                                                $contenido.= '<td>$'.number_format($dess,2).'</td>';
                                            	$contenido.= '<td style="background:#FFCCDD;">$'.number_format($Importe,2).'</td>';
                                            	$contenido.= '<td style="background:#A9F5A9;">$'.number_format($efectivoCambioSum,2).'</td>';
                                            	$contenido.= '</tr>';
                                            	$contenido .='</table></div>';                                             
///esta si
$contenido .='<div style="width:100%; align:center;"><h4>Productos Vendidos</h4></div>';
$contenido .='<div style="width:100%; align:center;"><table style="border: 1px solid black;">  
												<tr style="background-color:#dcdcdc;">
                                                    <th>Codigo</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unitario</th>
                                                    <th>Descuento</th>
                                                    <th>Impuestos</th>
                                                    <th>Subtotal</th>
                                                </tr>';
                                            foreach ($resumenCorte['productos'] as $key => $value) {
                                            	$contenido.= '<tr>';
                                            	$contenido.= '<td>'.$value['codigo'].'</td>';
                                            	$contenido.= '<td>'.$value['nombre'].'</td>';
                                            	$contenido.= '<td align="center">'.$value['Cantidad'].'</td>';
                                            	$contenido.= '<td align="center">$'.$value['preciounitario'].'</td>';
                                            	$contenido.= '<td align="center">$'.$value['Descuento'].'</td>';
                                            	$Descuento += $value['Descuento'];
                                            	$contenido.= '<td align="center">$'.$value['Impuestos'].'</td>';
                                            	$Impuestos2 += $value['Impuestos'];
                                            	$contenido.= '<td align="center">$'.$value['Subtot'].'</td>';
                                            	$Subtot += $value['Subtot'];
                                            	$contenido.= '</tr>';
                                            }
                                            $contenido.= '<tr style="background:white;">';
                                            $contenido.= '<td colspan="4">Totales</td>';
                                            $contenido.= '<td>$'.number_format($Descuento,2).'</td>';
                                            $contenido.= '<td>$'.number_format($Impuestos2,2).'</td>';
                                            $contenido.= '<td style="background:#FFCCDD;">$'.number_format($Subtot,2).'</td>';
                                            $contenido.= '</tr></table></div>'; 

$contenido .='<div style="width:100%; align:center;"><h4>Retiros de Caja</h4></div>';
$contenido .='<div style="width:100%; align:center;"><table style="border: 1px solid black;">
												<tr style="background-color:#dcdcdc;">
                                                    <th>ID Retiro</th>
                                                    <th>Fecha</th>
                                                    <th>Concepto</th>
                                                    <th>Usuario</th>
                                                    <th>Cantidad</th>
                                                </tr> ';
                                                foreach ($resumenCorte['retiros'] as $key => $value) {
                                            		$contenido.= '<tr>';
                                            		$contenido.= '<td>'.$value['id'].'</td>';
                                            		$contenido.= '<td>'.$value['fecha'].'</td>';
                                            		$contenido.= '<td>'.$value['concepto'].'</td>';
                                            		$contenido.= '<td>'.$value['usuario'].'</td>';
                                            		$contenido.= '<td align="center">$'.number_format($value['cantidad'],2).'</td>';
                                            		$cantidad3 += $value['cantidad'];
                                            		$contenido.= '</tr>';
                                            	}
                                            	$contenido.= '<tr style="background:white;">';
                                            	$contenido.= '<td colspan="4">Totales</td>';
                                            	$contenido.= '<td align="center" style="background:#FFCCDD;">$'.number_format($cantidad3,2).'</td>';
                                            	$contenido.= '</tr></table></div>'; 
$contenido .='</body></html>';

$pdf->WriteHTML($contenido);
$pdf->Output('cortes/corte_'.$idCorte.'.pdf', 'F'); 





?>