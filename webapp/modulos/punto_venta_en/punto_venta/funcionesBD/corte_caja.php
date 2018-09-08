<?php
	include("../../../netwarelog/webconfig.php");
	
	$funcion = $_POST['funcion'];
	
	$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	$funcion($connection);
	mysqli_close($connection);
	
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------

	function compruebaAnteriores($connection)
	{
		$compruebaCortesAnteriores = $connection->query("SELECT idCortecaja, fechainicio, fechafin, retirocaja, abonocaja, saldoinicialcaja, saldofinalcaja, montoventa FROM corte_caja ORDER BY idCortecaja desc LIMIT 1");
		if($compruebaCortesAnteriores->num_rows < 1)
		{
			//No hay cortes anteriores	
			$compruebaVentasAnteriores = $connection->query("SELECT fecha FROM venta WHERE estatus = 1 ORDER BY fecha LIMIT 1");
			if($compruebaVentasAnteriores->num_rows < 1)
			{
				//No hay ventas anteriores
				echo 0;
			}
			else
			{
				//Hay ventas pero no hay cortes	
				$obtieneInicioCaja = $connection->query("SELECT monto FROM inicio_caja WHERE idCortecaja IS NULL AND id > 0 LIMIT 1");
				$row2 = $obtieneInicioCaja->fetch_array();
			
				$row = $compruebaVentasAnteriores->fetch_array();
				echo "2$$$+++###AAABBB".$row['fecha']."$$$+++###AAABBB".$row2['monto'];
			}
		}
		else
		{
			//Hay cortes anteriores
			$obtieneInicioCaja = $connection->query("SELECT monto FROM inicio_caja WHERE idCortecaja IS NULL AND id > 0 LIMIT 1");
			$row2 = $obtieneInicioCaja->fetch_array();
				
			$row = $compruebaCortesAnteriores->fetch_array();
			echo "1$$$+++###AAABBB".$row['fechafin']."$$$+++###AAABBB".$row['saldofinalcaja']."$$$+++###AAABBB".$row2['monto'];
		}
	}
	
	function cargaPagos($connection)
	{
		$fecha_inicio = $_POST['fecha_inicio'];
		$fecha_fin = $_POST['fecha_fin'];
		$id = $_POST['id'];
		$hayCanceladas = false;
		
		if($id == 'NULL')
		{
			//Es un nuevo corte
			$filtro_canceladas = "AND v.estatus = 1";
		}
		else
		{
			$filtro_canceladas = "";
		}
		
		$monto_venta = 0;
		$iva = 0;
		$total_importe = 0;
		$importe = 0;
		$cambio = 0;
		$total_cambio = 0;
		$total_iva = 0;
		$total_monto = 0;
		
		$acumulado_spei = 0;
		$total_spei = 0;
		
		$acumulado_transferencia = 0;
		$total_transferencia = 0;
		
		$acumulado_tarjeta_credito = 0;
		$total_tarjeta_credito = 0;
		
		$acumulado_tarjeta_regalo = 0;
		$total_tarjeta_regalo = 0;
		
		$acumulado_tarjeta_debito = 0;
		$total_tarjeta_debito = 0;
		
		$acumulado_efectivo = 0;
		$total_efectivo = 0;
		
		$acumulado_credito = 0;
		$total_credito = 0;
		
		$acumulado_cheque = 0;
		$total_cheque = 0;
		$tabla = "<center><div id='pagos' style=' width: 100%; max-height: 400px; overflow: auto;'>";

		//Variable que almacena la consulta //////////////////////////////////////////
		$consulta = "	SELECT p.idventa_pagos, p.idVenta, p.idFormapago, p.monto, p.referencia, v.idCliente, c.nombre, v.estatus, 
										v.fecha, v.cambio, v.monto AS montoventa, v.montoimpuestos AS montoimpuestos   
									  	FROM venta_pagos p
									  	INNER JOIN venta v ON v.idVenta = p.idVenta 
									  	LEFT JOIN comun_cliente c ON c.id = v.idCliente 
									  	WHERE (v.fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."') ".$filtro_canceladas." ORDER BY p.idVenta";		
		
		//Se ejecuta la consulta con la variable ///////////////////////////////////
		$result = $connection->query($consulta);

		//Guarda la consulta en una variable de sesion para llevarlo al sistema contable ////////////////////////////////////////
		$_SESSION['cont_query'] = $consulta;
									
		if($result->num_rows > 0)
		{
			$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
				<tr>
					<th width=5% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>Folio de venta</th>
					<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>Cliente</th>
					<th width=14% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>Fecha y hora</th>
					
					<th width=5% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; border-top: 1px solid #006efe;'>EF</th>
					<th width=5% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; border-top: 1px solid #006efe;'>TC</th>
					<th width=5% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; border-top: 1px solid #006efe;'>TD</th>
					<th width=5% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; border-top: 1px solid #006efe;'>CR</th>
					<th width=5% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; border-top: 1px solid #006efe;'>CH</th>
					
					<th width=5% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; border-top: 1px solid #006efe;'>TRA</th>
					<th width=5% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; border-top: 1px solid #006efe;'>SPEI</th>
					
					<th width=5% style='border-bottom: 1px solid #006efe; background-color: #DDDDDD; border-top: 1px solid #006efe; border-right: 1px solid #EEEEEE;'>TR</th>
				
					<th width=7% style='border-bottom: 1px solid #006efe; background-color: #81DAF5; border-top: 1px solid #006efe;'>Cambio</th>
					<th width=7% style='border-bottom: 1px solid #006efe; background-color: #F2F5A9; border-top: 1px solid #006efe;'>Impuestos</th>
					<th width=10% style='border-bottom: 1px solid #006efe; background-color: #DD66DD; border-top: 1px solid #006efe;'>Monto</th>
					<th width=12% style='border-bottom: 1px solid #006efe; background-color: #A9F5A9; border-top: 1px solid #006efe; border-right: 1px solid #006efe;'>Importe</th>
				</tr>";
			
			
			while($rows = $result->fetch_array())
					$row[] = $rows;
			//var_dump($row);
			for($i=0; $i<count($row); $i++)
			{
				if($i==0)
				{
					//El primer registro
					$importe = $row[$i]['montoventa'];
					$iva = $row[$i]['montoimpuestos'];
					$cambio = $row[$i]['cambio'];
					$monto_venta = $row[$i]['montoventa'] - $iva;
					
					if($row[$i]['idFormapago'] == 1)
					{	$acumulado_efectivo += 			$row[$i]['monto'];  $total_efectivo += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 2)
					{	$acumulado_cheque += 			$row[$i]['monto']; $total_cheque += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 3)
					{	$acumulado_tarjeta_regalo += 	$row[$i]['monto']; $total_tarjeta_regalo += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 4)
					{	$acumulado_tarjeta_credito += 	$row[$i]['monto']; $total_tarjeta_credito += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 5)
					{	$acumulado_tarjeta_debito += 	$row[$i]['monto']; $total_tarjeta_debito += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 6)
					{	$acumulado_credito += 			$row[$i]['monto']; $total_credito += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 7)
					{	$acumulado_transferencia += 	$row[$i]['monto']; $total_transferencia += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 8)
					{	$acumulado_spei += 			    $row[$i]['monto']; $total_spei += $row[$i]['monto']; }
				}
				else if($row[$i]['idVenta'] == $row[$i-1]['idVenta'])
				{
					//Si el registro corresponde a la misma venta
					$importe = $row[$i]['montoventa'];
					$iva = $row[$i]['montoimpuestos'];
					$cambio = $row[$i]['cambio'];
					$monto_venta = $row[$i]['montoventa'] - $iva;
					
					if($row[$i]['idFormapago'] == 1)
					{	$acumulado_efectivo += 			$row[$i]['monto'];  $total_efectivo += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 2)
					{	$acumulado_cheque += 			$row[$i]['monto']; $total_cheque += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 3)
					{	$acumulado_tarjeta_regalo += 	$row[$i]['monto']; $total_tarjeta_regalo += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 4)
					{	$acumulado_tarjeta_credito += 	$row[$i]['monto']; $total_tarjeta_credito += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 5)
					{	$acumulado_tarjeta_debito += 	$row[$i]['monto']; $total_tarjeta_debito += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 6)
					{	$acumulado_credito += 			$row[$i]['monto']; $total_credito += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 7)
					{	$acumulado_transferencia += 	$row[$i]['monto']; $total_transferencia += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 8)
					{	$acumulado_spei += 			    $row[$i]['monto']; $total_spei += $row[$i]['monto']; }
				}
				else
				{
					$total_importe += $importe;
					$total_cambio += $cambio;
					$total_iva += $iva;
					$total_monto += $monto_venta;
					
					//Si el registro NO corresponde a la misma venta
					if($row[$i-1]['estatus'] == 0)
					{
						$hayCanceladas = true;
						$tabla .= "<tr>
							 	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-left: 1px solid #006efe; color: #FF0000;'><center>".$row[$i-1]['idVenta']."</center></td>";
							  	
								 if($row[$i-1]['idCliente'] == NULL)
								 {
								 	$tabla .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; color: #FF0000;'>"."Público en general"."</td>";
								 }
								 else
								 {
								 	$tabla .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; color: #FF0000;'>".$row[$i-1]['nombre']."</td>";
								 }
					
						$tabla .= "  <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; color: #FF0000;'>".$row[$i-1]['fecha']."</td>";
						
						$tabla .="	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_efectivo."</center></td>
							  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_tarjeta_credito."</center></td>
							  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_tarjeta_debito."</center></td>
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_credito."</center></td>
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_cheque."</center></td>
        					  	 
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_transferencia."</center></td>
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_spei."</center></td>
							  	 
        					  	 
							  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_tarjeta_regalo."</center></td>
        					  
							  	 <td style='border-bottom: 1px solid #EEEEEE; background-color: #81DAF5; color: #FF0000;'><center>$".$cambio."</center></td>
							  	  <td style='border-bottom: 1px solid #EEEEEE; background-color: #F2F5A9; color: #FF0000;'><center>$".$iva."</center></td>
							  	  <td style='border-bottom: 1px solid #EEEEEE; background-color: #DD66DD; color: #FF0000;'><center>$".$monto_venta."</center></td>
							  	 <td style='border-bottom: 1px solid #EEEEEE; background-color: #A9F5A9;  color: #FF0000; border-right: 1px solid #006efe;'><center>$".$importe."</center></td>
        					  </tr>";
					}
					else 
					{
						$tabla .= "<tr>
							 	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-left: 1px solid #006efe;'><center>".$row[$i-1]['idVenta']."</center></td>";
							  	
								 if($row[$i-1]['idCliente'] == NULL)
								 {
								 	$tabla .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'>"."Público en general"."</td>";
								 }
								 else
								 {
								 	$tabla .= "<td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'>".$row[$i-1]['nombre']."</td>";
								 }
					
						$tabla .= "  <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'>".$row[$i-1]['fecha']."</td>";
						
						$tabla .="	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_efectivo."</center></td>
							  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_tarjeta_credito."</center></td>
							  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_tarjeta_debito."</center></td>
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_credito."</center></td>
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_cheque."</center></td>
        					  	 
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_transferencia."</center></td>
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_spei."</center></td>
							  	 
								 
							  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_tarjeta_regalo."</center></td>
        					  
							  	 <td style='border-bottom: 1px solid #EEEEEE; background-color: #81DAF5;'><center>$".$cambio."</center></td>
							  	  <td style='border-bottom: 1px solid #EEEEEE; background-color: #F2F5A9;'><center>$".$iva."</center></td>
							  	  <td style='border-bottom: 1px solid #EEEEEE; background-color: #DD66DD;'><center>$".$monto_venta."</center></td>
							  	 <td style='border-bottom: 1px solid #EEEEEE; background-color: #A9F5A9; border-right: 1px solid #006efe;'><center>$".$importe."</center></td>
        					  </tr>";
					}	  
					$importe = 0;
					$cambio = 0;
					$iva = 0;
					$monto_venta = 0;
					
					$acumulado_tarjeta_credito = 0;
					$acumulado_tarjeta_regalo = 0;
					$acumulado_tarjeta_debito = 0;
					$acumulado_efectivo = 0;
					$acumulado_credito = 0;
					$acumulado_cheque = 0;
					
					$importe = $row[$i]['montoventa'];
					$cambio = $row[$i]['cambio'];
					$iva = $row[$i]['montoimpuestos'];
					$monto_venta = $row[$i]['montoventa'] - $iva;
					
					if($row[$i]['idFormapago'] == 1)
					{	$acumulado_efectivo += 			$row[$i]['monto']; $total_efectivo += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 2)
					{	$acumulado_cheque += 			$row[$i]['monto']; $total_cheque += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 3)
					{	$acumulado_tarjeta_regalo += 	$row[$i]['monto']; $total_tarjeta_regalo += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 4)
					{	$acumulado_tarjeta_credito += 	$row[$i]['monto']; $total_tarjeta_credito += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 5)
					{	$acumulado_tarjeta_debito += 	$row[$i]['monto']; $total_tarjeta_debito += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 6)
					{	$acumulado_credito += 			$row[$i]['monto']; $total_credito += $row[$i]['monto']; }
					
					
					if($row[$i]['idFormapago'] == 7)
					{	$acumulado_transferencia += 	$row[$i]['monto']; $total_transferencia += $row[$i]['monto']; }
					if($row[$i]['idFormapago'] == 8)
					{	$acumulado_spei += 			$row[$i]['monto']; $total_spei += $row[$i]['monto']; }
					
				}
			}

			if($row[$i-1]['estatus'] == 0)
			{
				$hayCanceladas = true;
				$tabla .= "<tr>
				 	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; border-left: 1px solid #006efe; color: #FF0000;'><center>".$row[$i-1]['idVenta']."</center></td>";
				  	 
				  	if($row[$i-1]['idCliente'] == NULL)
					{
						$tabla.= "<td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; color: #FF0000;'>"."Público en general"."</td>";
				  	}
					else 
					{
						$tabla.= "<td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; color: #FF0000;'>".$row[$i-1]['nombre']."</td>";
					}
					
				$tabla .= "  <td style='border-bottom: 1px solid #006EFE; border-right: 1px solid #EEEEEE; color: #FF0000;'>".$row[$i-1]['fecha']."</td>";
				
				$tabla .=  	" <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_efectivo."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_tarjeta_credito."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_tarjeta_debito."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_credito."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_cheque."</center></td>
				  	 
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_transferencia."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_spei."</center></td>
				  	 
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD; color: #FF0000;'><center>$".$acumulado_tarjeta_regalo."</center></td>
				  
				  	  <td style='border-bottom: 1px solid #006efe; background-color: #81DAF5; color: #FF0000;'><center>$".$cambio."</center></td>
				  	  <td style='border-bottom: 1px solid #006efe; background-color: #F2F5A9; color: #FF0000;'><center>$".$iva."</center></td>
				  	  <td style='border-bottom: 1px solid #006efe; background-color: #DD66DD; color: #FF0000;'><center>$".$monto_venta."</center></td>
					  <td style='border-bottom: 1px solid #006efe; background-color: #A9F5A9;  color: #FF0000;border-right: 1px solid #006efe;'><center>$".$importe."</center></td>
				  </tr>";
			}		  	 
			else 
			{
				$tabla .= "<tr>
				 	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; border-left: 1px solid #006efe;'><center>".$row[$i-1]['idVenta']."</center></td>";
				  	 
				  	if($row[$i-1]['idCliente'] == NULL)
					{
						$tabla.= "<td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE;'>"."Público en general"."</td>";
				  	}
					else 
					{
						$tabla.= "<td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE;'>".$row[$i-1]['nombre']."</td>";
					}
					
				$tabla .= "  <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE;'>".$row[$i-1]['fecha']."</td>";
				
				$tabla .=  	" <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_efectivo."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_tarjeta_credito."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_tarjeta_debito."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_credito."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_cheque."</center></td>
				  	 
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_transferencia."</center></td>
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_spei."</center></td>
				  	
				  	 
				  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #DDDDDD;'><center>$".$acumulado_tarjeta_regalo."</center></td>
				  
				  	  <td style='border-bottom: 1px solid #006efe; background-color: #81DAF5;'><center>$".$cambio."</center></td>
				  	  <td style='border-bottom: 1px solid #006efe; background-color: #F2F5A9;'><center>$".$iva."</center></td>
				  	  <td style='border-bottom: 1px solid #006efe; background-color: #DD66DD;'><center>$".$monto_venta."</center></td>
					  <td style='border-bottom: 1px solid #006efe; background-color: #A9F5A9; border-right: 1px solid #006efe;'><center>$".$importe."</center></td>
				  </tr>";
			}
			$importe = $row[$i-1]['montoventa'];
		
			$total_importe += $importe;
			$total_cambio += $cambio;
			$total_iva += $iva;
			$total_monto += $monto_venta;
			
			if($hayCanceladas == true)
			{
				$tabla .= "<tr>
						 <td style='text-align: left; height: 30px; color: #FF0000;' colspan=2>*Las ventas canceladas se muestran en rojo*</td>
						  <td style='border-right: 1px solid #EEEEEE; text-align: right; height: 30px;'><B>Subtotal</B>:</td>";
			}
			else 
			{
				$tabla .= "<tr>
						 <td style='border-right: 1px solid #EEEEEE; text-align: right; height: 30px;' colspan=3><B>Subtotal</B>:</td>";
			}
						
					  	 
				$tabla .= " <td style='border-right: 1px solid #EEEEEE; background-color: #DDDDDD; height: 30px;'><center>$".$total_efectivo."</center></td>
					  	 <td style='border-right: 1px solid #EEEEEE; background-color: #DDDDDD; height: 30px;'><center>$".$total_tarjeta_credito."</center></td>
					  	 <td style='border-right: 1px solid #EEEEEE; background-color: #DDDDDD; height: 30px;'><center>$".$total_tarjeta_debito."</center></td>
					  	 <td style='border-right: 1px solid #EEEEEE; background-color: #DDDDDD; height: 30px;'><center>$".$total_credito."</center></td>
					  	 <td style='border-right: 1px solid #EEEEEE; background-color: #DDDDDD; height: 30px;'><center>$".$total_cheque."</center></td>
					  	 
						 <td style='border-right: 1px solid #EEEEEE; background-color: #DDDDDD; height: 30px;'><center>$".$total_transferencia."</center></td>
					  	 <td style='border-right: 1px solid #EEEEEE; background-color: #DDDDDD; height: 30px;'><center>$".$total_spei."</center></td>
						 
					  	 <td style='border-right: 1px solid #EEEEEE; background-color: #DDDDDD; height: 30px;'><center>$".$total_tarjeta_regalo."</center></td>
					  	 
					  	 <td style='background-color: #81DAF5; height: 30px;'><center>$".$total_cambio."</center></td>
					  	 <td style='background-color: #F2F5A9; height: 30px;'><center>$".$total_iva."</center></td>
					  	 <td style='background-color: #DD66DD; height: 30px;'><center>$".$total_monto."</center></td>
					  	 <td style='background-color: #A9F5A9; height: 30px;'><center>$".$total_importe."</center></td>
					  	 <input type='hidden' id='monto_ventas_pg' value=".$total_importe.">
						</tr>";
					  
			$importe = 0;
			$acumulado_tarjeta_credito = 0;
			$acumulado_tarjeta_regalo = 0;
			$acumulado_tarjeta_debito = 0;
			$acumulado_efectivo = 0;
			$acumulado_credito = 0;
			$acumulado_cheque = 0;
			$iva = 0;
			$monto_venta = 0;
			
			$tabla .= "</table>";
			$tabla .= "</div></center><br><br>";
			if($hayCanceladas == true)
			{
				echo "1$$$+++###AAABBB".$tabla."$$$+++###AAABBB2";
			}
			else
			{
				echo "1$$$+++###AAABBB".$tabla;
			}
		}
		else
		{
			$tabla .= "<br><br><br><b>No hay pagos recientes de ventas</b><br><br><br>";
			$tabla .= "</div></center><br><br>";
			echo "0$$$+++###AAABBB".$tabla;
		}

	}
	
	function cargaProductos($connection)
	{
		$fecha_inicio = $_POST['fecha_inicio'];
		$fecha_fin = $_POST['fecha_fin'];
		$id = $_POST['id'];
		
		if($id == 'NULL')
		{
			//Es un nuevo corte
			$filtro_canceladas = "AND v.estatus = 1";
		}
		else
		{
			$filtro_canceladas = "";
		}
		
		$i=0;
		
		$suma_subtotal = 0;
		$total_subtotal = 0;
		$suma_cantidad = 0;
		$suma_descuento = 0;
		$total_descuento = 0;		
		$iva = 0;
		
		$tabla = "<center><div id='pagos' style=' width: 100%; max-height: 400px; overflow: auto;'>";
		
		$result = $connection->query("	SELECT p.idventa_producto, p.idVenta, p.idProducto, p.cantidad, p.descuento, p.subtotal, p.tipodescuento, v.estatus, 
										v.idCliente, mp.nombre, mp.codigo, v.fecha, v.montoimpuestos, p.preciounitario  
									  	FROM venta_producto p
									  	INNER JOIN venta v ON v.idVenta = p.idVenta 
									  	INNER JOIN mrp_producto mp ON mp.idProducto = p.idProducto 
									  	WHERE (v.fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."') ".$filtro_canceladas." ORDER BY p.idProducto");
									  	
		if($result->num_rows > 0)
		{
			$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='width: 100%; font-size: 12px;'>
				<tr>
					<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>		Codigo			</th>
					<th width=40% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>										Producto		</th>
					<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>										Cantidad		</th>
					<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>										Precio unitario	</th>
					<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #FFCCDD;'>			Descuento		</th>
					
					<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #DD66DD;'>	Subtotal		</th>
				</tr>";
			//$i=0;
			while($rows = $result->fetch_array())
					$row[] = $rows;
			for($i=0; $i<count($row); $i++)
			{
				if($i==0)
				{
					//El primer registro
					$suma_cantidad += $row[$i]['cantidad'];
					if($row[$i]['tipodescuento']=="%")
					{
						$descuento = (($row[$i]['descuento'])*($row[$i]['preciounitario']*$row[$i]['cantidad']))/100;
						$suma_descuento += $descuento;
					}
					else if ($row[$i]['tipodescuento']=="$")
					{
						$descuento = $row[$i]['descuento'];
						$suma_descuento += $descuento;
					}
					
					$suma_subtotal += ($row[$i]['preciounitario']*$row[$i]['cantidad'])-$descuento;
					//$total_subtotal += $suma_subtotal;
				}
				else if($row[$i]['idProducto'] == $row[$i-1]['idProducto'])
				{
					//Si el registro corresponde al mismo producto
					$suma_cantidad += $row[$i]['cantidad'];
					if($row[$i]['tipodescuento']=="%")
					{
						$descuento = (($row[$i]['descuento'])*($row[$i]['preciounitario']*$row[$i]['cantidad']))/100;
						$suma_descuento += $descuento;
					}
					else if ($row[$i]['tipodescuento']=="$")
					{
						$descuento = $row[$i]['descuento'];
						$suma_descuento += $descuento;
					}
					
					$suma_subtotal += ($row[$i]['preciounitario']*$row[$i]['cantidad'])-$descuento;
					//$total_subtotal += $suma_subtotal;
				}
				else
				{
					$total_subtotal += $suma_subtotal;
					$total_descuento += $suma_descuento;
					//Si el registro NO corresponde al mismo producto
					$tabla .= "<tr>
							 	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE; border-left: 1px solid #006efe;'><center>".utf8_decode($row[$i-1]['codigo'])."</center></td>
							  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'>".utf8_decode($row[$i-1]['nombre'])."</td>
							  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><center>".$suma_cantidad."</center></td>
        					  	 <td style='border-bottom: 1px solid #EEEEEE; border-right: 1px solid #EEEEEE;'><center>$".$row[$i-1]['preciounitario']."</center></td>
								 <td style='border-bottom: 1px solid #EEEEEE; background-color: #FFCCDD'><center>$".$suma_descuento."</center></td>
							  	 
							  	 <td style='border-bottom: 1px solid #EEEEEE; background-color: #DD66DD; border-right: 1px solid #006efe;'><center>$".$suma_subtotal."</center></td>
        					  </tr>";
							  
					$suma_subtotal = 0;
					$suma_cantidad = 0;
					$suma_descuento = 0;
					$descuento = 0;
					
					$suma_cantidad += $row[$i]['cantidad'];
					if($row[$i]['tipodescuento']=="%")
					{
						$descuento = (($row[$i]['descuento'])*($row[$i]['preciounitario']*$row[$i]['cantidad']))/100;
						$suma_descuento += $descuento;
					}
					else if ($row[$i]['tipodescuento']=="$")
					{
						$descuento = $row[$i]['descuento'];
						$suma_descuento += $descuento;
					}
					
					$suma_subtotal += ($row[$i]['preciounitario']*$row[$i]['cantidad'])-$descuento;
				}
			}
			
			$obtieneIVA = $connection->query("	SELECT p.idventa_producto, p.idVenta, p.idProducto, p.cantidad, p.descuento, p.subtotal, p.tipodescuento, 
										v.idCliente, mp.nombre, mp.codigo, v.fecha, v.montoimpuestos, mp.precioventa AS preciounitario  
									  	FROM venta_producto p
									  	INNER JOIN venta v ON v.idVenta = p.idVenta 
									  	INNER JOIN mrp_producto mp ON mp.idProducto = p.idProducto 
									  	WHERE (v.fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."') ".$filtro_canceladas." ORDER BY p.idVenta");
									  	
			while($rowz = $obtieneIVA->fetch_array())
					$row2[] = $rowz;
									  	
			for($j=0; $j<count($row2); $j++)
			{
				if($j==0)
				{
					//El primer registro
					$iva += $row2[$j]['montoimpuestos'];
				}
				else if($row2[$j]['idVenta'] != $row2[$j-1]['idVenta'])
				{
					//Si el registro corresponde a la misma venta
					$iva += $row2[$j]['montoimpuestos'];
				}
			}
			
			$total_subtotal += $suma_subtotal;
			$total_descuento += $suma_descuento;
			
			$tabla .= "<tr>
					 	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; border-left: 1px solid #006efe;'><center>".utf8_decode($row[$i-1]['codigo'])."</center></td>
					  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE;'>".$row[$i-1]['nombre']."</td>
					  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE;'><center>".$suma_cantidad."</center></td>
					  	 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE;'><center>$".$row[$i-1]['preciounitario']."</center></td>
						 <td style='border-bottom: 1px solid #006efe; border-right: 1px solid #EEEEEE; background-color: #FFCCDD'><center>$".$suma_descuento."</center></td>
					  	 
					  	 <td style='border-bottom: 1px solid #006efe; background-color: #DD66DD; border-right: 1px solid #006efe;'><center>$".$suma_subtotal."</center></td>
					  </tr>";
					  
			$tabla .= "<tr>
						 <td style='text-align: right; height: 30px;' colspan=4><B>Subtotal</B>:</td>
					  	 <td style='background-color: #FFCCDD; height: 30px;'><center>$".$total_descuento."</center></td>
					  	 <td style='background-color: #DD66DD; height: 30px;'><center>$".$total_subtotal."</center></td>
					   </tr>";
						
			$tabla .= "<tr>
						 <td style='text-align: right; height: 30px;' colspan=5><B>Impuestos</B>:</td>
					  	 <td style='background-color: #F2F5A9; height: 30px;'><center>$".$iva."</center></td>
					   </tr>";
						
			$tabla .= "<tr>
						 <td style='text-align: right; height: 30px;' colspan=5><B>Total</B>:</td>
					  	 <td style='background-color: #A9F5A9; height: 30px;'><center>$".($iva+$total_subtotal)."</center></td>
					  	 <input type='hidden' id='monto_ventas_pd' value=".($iva+$total_subtotal).">
					   </tr>";
					  
			$suma_subtotal = 0;
			$suma_cantidad = 0;
			$suma_descuento = 0;
			
			$tabla .= "</table>";
			$tabla .= "</div></center>";
			echo "1$$$+++###AAABBB".$tabla;
		}
		else
		{
			$tabla .= "<br><br><br><b>No hay ventas recientes de productos</b><br><br><br>";
			$tabla .= "</div></center>";
			echo "0$$$+++###AAABBB".$tabla;
		}
		
	}
	
	function guardaCorte($connection)
	{
		session_start();
		$fecha_inicio = $_POST['fecha_inicio'];
		$fecha_fin = $_POST['fecha_fin'];
		$saldo_inicial = $_POST['saldo_inicial'];
		$monto_venta = $_POST['monto_ventas'];
		$saldo_disponible = $_POST['saldo_disponible'];
		$retiro_caja = $_POST['retiro_caja'];
		$deposito_caja = $_POST['deposito_caja'];
		
		$saldo_final = ($saldo_disponible - $retiro_caja) + $deposito_caja;
		
		if($result = $connection->query("INSERT INTO corte_caja (fechainicio, fechafin, retirocaja, abonocaja, saldoinicialcaja, saldofinalcaja, montoventa) VALUES ('".$fecha_inicio."', '".$fecha_fin."', ".$retiro_caja.", ".$deposito_caja.", ".$saldo_inicial.", ".$saldo_final.", ".$monto_venta.");"))	
		{
			$id = $connection->insert_id;
			
			$q = $connection->query("SELECT au.idSuc,mp.nombre FROM administracion_usuarios au, mrp_sucursal mp WHERE mp.idSuc=au.idSuc AND au.idempleado=".$_SESSION['accelog_idempleado']);		
			if($q->num_rows > 0)
			{
				$r = $q->fetch_assoc();
				{
					$sucursal_operando=$r['nombre'];
					$sucursal_id=$r['idSuc'];
				}	
			}
			
			$connection->query("UPDATE inicio_caja SET idCortecaja = ".$id." WHERE idCortecaja IS NULL AND idSucursal = ".$sucursal_id);
			echo 1;
		}
		else 
		{
			echo 0;
		}
		
	}
	
	function cargaSaldos($connection)
	{
		$id = $_POST['id'];
		$fecha_inicio = $_POST['fecha_inicio'];
		$fecha_fin = $_POST['fecha_fin'];
		
		$result = $connection->query("SELECT idCortecaja, fechainicio, fechafin, retirocaja, abonocaja, saldoinicialcaja, saldofinalcaja, montoventa FROM corte_caja WHERE idCortecaja = ".$id." AND fechainicio = '".$fecha_inicio."' AND fechafin = '".$fecha_fin."';");
		if($result->num_rows > 0)
		{
			//Se cargaron los datos	
			if($row = $result->fetch_array())
			{
				$saldo_disponible = ($row['saldofinalcaja'] - $row['abonocaja']) + $row['retirocaja'];
				echo $row['retirocaja']."$$$+++###AAABBB".$row['abonocaja']."$$$+++###AAABBB".$row['saldoinicialcaja']."$$$+++###AAABBB".$saldo_disponible."$$$+++###AAABBB".$row['montoventa'];
			}
		}
		else
		{
			echo "NULL$$$+++###AAABBB";
		}
	}
		
?>



























