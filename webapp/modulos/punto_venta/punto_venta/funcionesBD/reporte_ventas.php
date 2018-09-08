<?php
	include("../../../netwarelog/webconfig.php");
	
	$funcion = $_POST['funcion'];
	
	$connection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
	$funcion($connection);

	
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------
//-------------------------------------------------------------------------------------------------------------------------------------------

	function buscaProductos($connection)
	{
		$select_producto = "<div><select id='filtro_producto' style='width: 100%; min-width: 100%;' >";
		$select_producto .= "<option value=''>Todos los productos</option>";
		
		//if ($result = $connection->query("SELECT idProducto, nombre FROM mrp_producto WHERE vendible=1 order by nombre "))
		if ($result = $connection->query("SELECT idProducto, nombre FROM mrp_producto order by nombre "))
		
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_producto .= '<option value="'.$row["idProducto"].'">'.utf8_decode($row["nombre"]).'</option>';
				
			}
			echo $select_producto;
		}
		$select_producto .= "</select></div>";	
	}

//---------------------------------------------------------------------------------

	function buscaSucursales($connection)
	{	
		$select_producto = "<div><select id='filtro_sucursal' style='width: 100%; min-width: 100%;' >";
		$select_producto .= "<option value=''>Todas las sucursales</option>";
		if ($result = $connection->query("SELECT idSuc, nombre FROM mrp_sucursal"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_producto .= '<option value="'.$row["idSuc"].'">'.utf8_decode($row["nombre"]).'</option>';
				
			}
			$select_producto .= "</select></div>";
			echo $select_producto;
		}
	}

//---------------------------------------------------------------------------------

	function buscaEmpleados($connection)
	{	
		$select_empleado = "<div><select id='filtro_empleado' style='width: 100%; min-width: 100%;' >";
		$select_empleado .= "<option value=''>Todos los empleados</option>";
		if ($result = $connection->query("SELECT idEmpleado, nombre FROM empleados"))
		{
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_empleado .= '<option value="'.$row["idEmpleado"].'">'.utf8_decode($row["nombre"]).'</option>';
				
			}
			$select_empleado .= "</select></div>";
			echo $select_empleado;
		}
	}
	
//---------------------------------------------------------------------------------

	function buscaClientes($connection)
	{	
		$select_cliente = "<div><select id='filtro_cliente' style='width: 100%; min-width: 100%;' >";
		$select_cliente .= "<option value=''>Todos los clientes</option>";
		if ($result = $connection->query("SELECT c.id, c.nombre, e.estado, m.municipio FROM comun_cliente c
											INNER JOIN estados e ON e.idEstado = c.idEstado
											INNER JOIN municipios m ON m.idMunicipio = c.idMunicipio
											ORDER BY c.nombre;"))
		{
			if($result->num_rows > 0)
			{
					$select_cliente .= '<option value=NULL>Público en general</option>';
				while($row = $result->fetch_array())
					$rows[] = $row;
				foreach($rows as $row)
					$select_cliente .= '<option value="'.$row["id"].'">'.utf8_encode($row["nombre"].' ('.$row["estado"].', '.$row["municipio"].')').'</option>';
				
			}
			$select_cliente .= "</select></div>";
			echo $select_cliente;
		}
	}
	
//---------------------------------------------------------------------------------

	function cargaReporte($connection)
	{
		session_start();
		$simple=0;
		
		$result = $connection->query("select a.idperfil from accelog_perfiles_me a where idmenu=1259");
		if($result->num_rows > 0)
			$simple = 1;
			
		$filtro = "";
		$primerquery = "	SELECT v.idVenta, 
										v.idSucursal, s.nombre AS nombresucursal,
										v.idEmpleado, e.nombre AS nombreempleado,
										v.idCliente,  c.nombre AS nombrecliente, 
										v.monto, v.rfc, v.documento, v.fecha, v.cambio, v.montoimpuestos, v.estatus 
										FROM venta v
										LEFT JOIN comun_cliente c ON c.id = v.idCliente
										INNER JOIN mrp_sucursal s ON s.idSuc = v.idSucursal
										INNER JOIN empleados e ON e.idempleado = v.idEmpleado 
										WHERE v.estatus = 1 ";
										
		if(isset($_POST['filtro_fecha_inicio']))
		{
			
			$filtro_fecha_inicio = $_POST["filtro_fecha_inicio"]; 
		    $filtro_fecha_fin = $_POST["filtro_fecha_fin"]; 
		    $filtro_cliente = $_POST["filtro_cliente"]; 
			$filtro_vendedor = $_POST["filtro_vendedor"]; 
			$filtro_sucursal = $_POST["filtro_sucursal"]; 
			$filtro_producto = $_POST["filtro_producto"]; 
			$filtro_fechas = "";
			
			//echo "inicio: ".$filtro_fecha_inicio." fin: ".$filtro_fecha_fin." cliente: ".$filtro_cliente." vendedor:".$filtro_vendedor." sucursal: ".$filtro_sucursal." producto: ".$filtro_producto;
			
			if ($filtro_cliente != "")
			{
				if($filtro_cliente == "NULL")
				{
					$filtro_cliente = "AND v.idCliente IS NULL ";
				}
				else
				{
					$filtro_cliente = "AND v.idCliente = ".$filtro_cliente." ";
				}
			}
			//-------------------------------
			if($filtro_vendedor != "")
			{
				$filtro_vendedor = " AND v.idEmpleado = ".$filtro_vendedor." ";
			}
			//-------------------------------
			if($filtro_sucursal != "")
			{
				$filtro_sucursal = " AND v.idSucursal= ".$filtro_sucursal." ";
			}
			//--------------------------------
			if($filtro_producto != "")
			{
				$primerquery = "	SELECT v.idVenta, 
										p.idProducto, mp.nombre AS nombreproducto, 
										v.idSucursal, s.nombre AS nombresucursal,
										v.idEmpleado, e.nombre AS nombreempleado,
										v.idCliente,  c.nombre AS nombrecliente, 
										v.monto, v.rfc, v.documento, v.fecha, v.cambio, v.montoimpuestos, v.estatus 
										FROM venta v
										LEFT JOIN comun_cliente c ON c.id = v.idCliente
										INNER JOIN mrp_sucursal s ON s.idSuc = v.idSucursal
										INNER JOIN empleados e ON e.idempleado = v.idEmpleado
										INNER JOIN venta_producto p ON p.idVenta = v.idVenta 
										INNER JOIN mrp_producto mp ON mp.idProducto = p.idProducto  
										WHERE v.estatus = 1 ";
										
				$filtro_producto = " AND p.idProducto = ".$filtro_producto." ";
			}
			//--------------------------------
			if($filtro_fecha_inicio != "" && $filtro_fecha_fin != "")
			{
				$filtro_fechas = " AND DATE(v.fecha) BETWEEN '".$filtro_fecha_inicio."' AND '".$filtro_fecha_fin."' ";
			}
			//--------------------------------
			$filtro = $filtro_cliente.$filtro_vendedor.$filtro_sucursal.$filtro_fechas.$filtro_producto;
			
		}
		
		$totaliva = 0;
		$totalcambio = 0;
		$totalmonto = 0;
		
		$tabla = "<center><div id='reporte_ventas' style=' width: 100%; '>";
		
		$result = $connection->query($primerquery.$filtro.";");
		if($result->num_rows > 0)
		{
			$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
				<tr>
					<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
					<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>
					<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Cliente</th>";
			if($simple == 1)		
				$tabla .=	"<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Sucursal</th>";
					
			$tabla .="<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Vendedor</th>
					<th width=20% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
					<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
					<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
					<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>IVA</th>
				</tr>";
			
			while($rows = $result->fetch_array())
					$row[] = $rows;
			for($i=0; $i<count($row); $i++)
			{
				$ecoformaspago = "";
				
				$tabla .= "  
						<tr>
							<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
        					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
        					
        					
				if($row[$i]['idCliente'] == NULL)
				{
					$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";
				}			
				else 
				{
					$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";
				}			
				if($simple == 1)
							$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
        		$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
        					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
        					
        		$obtieneFormasPago = $connection->query("
										SELECT p.idVenta, p.idFormapago, f.nombre,
										p.monto, p.referencia
										FROM venta_pagos p
										INNER JOIN venta v ON v.idVenta = p.idVenta
										INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
										WHERE p.idVenta = ".$row[$i]['idVenta']." 
										ORDER BY idVenta;");
										
				while($rows2 = $obtieneFormasPago->fetch_array())
					$row2[] = $rows2;
				
				for($j=0; $j<count($row2); $j++)
				{
					if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
					{
						$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
					}
				}
				$tabla .= $ecoformaspago;
											
        		$tabla .= "	</center></td>
							<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
							<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto']-$row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
        					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
						</tr>";
						
				$totalcambio += $row[$i]['cambio'];
				$totalmonto += $row[$i]['monto'] - $row[$i]['montoimpuestos'];
				$totaliva += $row[$i]['montoimpuestos'];
			}
			
			$tabla .= "	<tr>";
			if($simple == 1)
				$tabla .= "		<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=6> <b>Subtotal </b></td>";
			else
				$tabla .= "		<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>";
			$tabla .= "		<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($totalcambio, 2, '.', ',')."</center></td>
							<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($totalmonto, 2, '.', ',')."</center></td>
        					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($totaliva, 2, '.', ',')."</center></td>
						</tr>";
			
			if($simple == 1)
				$tabla .= "	<tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=7> <b>Total </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($totaliva+$totalmonto), 2, '.', ',')."</center></td>
						</tr>";
			else
				$tabla .= "	<tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($totaliva+$totalmonto), 2, '.', ',')."</center></td>
						</tr>";
			
			
			$tabla .= "</table>";
			if($filtro != "")
			{
				$tabla .= "<br><input type='button' value='Mostrar la lista completa' onclick='cargaReporte();'><br>";
			}
			$tabla .= "</div></center>";
			echo $tabla;
		}
		else
		{
			if($filtro != "")
			{
				$tabla .= "<br><br><br><div style='color: #B40431;'><b>No se encontraron ventas que coincidieran con su búsqueda.</b></div><br>
							<input type='button' value='Mostrar la lista completa' onclick='cargaReporte();'><br><br><br><br>";
				$tabla .= "</div></center>";
			}
			else
			{
				$tabla .= "<br><br><br><div style='color: #006efe;'>No se encontraron ventas recientes</div><br><br><br><br>";
				$tabla .= "</div></center>";
			}
			echo $tabla;
		}
	}
	//-------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------
	//-------------------------------------------------------------------------------------------
	function cargaReporteAgrupado($connection)
	{
		session_start();
		$simple=0;
		
		$result = $connection->query("select a.idperfil from accelog_perfiles_me a where idmenu=1259");
		if($result->num_rows > 0)
			$simple = 1;
		
		$ordenamiento = $_POST["ordenamiento"]; 
		$opc = $_POST['opc'];
		$tabla = "<center><div id='reporte_ventas' style=' width: 100%; '>";
		
		if($opc == 1 || $opc == 2 || $opc == 3)
		{
			$primerquery = "SELECT v.idVenta, 
										v.idSucursal, s.nombre AS nombresucursal,
										v.idEmpleado, e.nombre AS nombreempleado,
										v.idCliente,  c.nombre AS nombrecliente, 
										v.monto, v.rfc, v.documento, v.fecha, v.cambio, v.montoimpuestos, v.estatus 
										FROM venta v
										LEFT JOIN comun_cliente c ON c.id = v.idCliente
										INNER JOIN mrp_sucursal s ON s.idSuc = v.idSucursal
										INNER JOIN empleados e ON e.idempleado = v.idEmpleado 
										WHERE v.estatus = 1 ";
		}
		else if ($opc == 4)
		{
			$primerquery = "	SELECT v.idVenta, 
										p.idProducto, mp.nombre AS nombreproducto, 
										v.idSucursal, s.nombre AS nombresucursal,
										v.idEmpleado, e.nombre AS nombreempleado,
										v.idCliente,  c.nombre AS nombrecliente, 
										v.monto, v.rfc, v.documento, v.fecha, v.cambio, v.montoimpuestos, v.estatus,
										p.cantidad  
										FROM venta v
										LEFT JOIN comun_cliente c ON c.id = v.idCliente
										INNER JOIN mrp_sucursal s ON s.idSuc = v.idSucursal
										INNER JOIN empleados e ON e.idempleado = v.idEmpleado
										INNER JOIN venta_producto p ON p.idVenta = v.idVenta 
										INNER JOIN mrp_producto mp ON mp.idProducto = p.idProducto  
										WHERE v.estatus = 1 ";
		}
		
		$result = $connection->query($primerquery.$ordenamiento.";");
		
		if($result->num_rows > 0)
		{
			while($rows = $result->fetch_array())
					$row[] = $rows;
			$sumacambio = 0;
			$sumaiva = 0;
			$sumamonto = 0;
			$total=0;
	
	
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			
			if($opc == 1)
			{
				for($i=0; $i<count($row); $i++)
				{
					$ecoformaspago = "";
					if($i==0)
					{
						$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>".$row[$i]['nombreempleado']."</div><br>"; 
						$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>
								<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>				Cliente</th>";
						if($simple == 1)
							$tabla .= "	<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>				Sucursal</th>";
						$tabla .= "	<th width=20% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>IVA</th>
							</tr>";
							
							
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
								if($row[$i]['idCliente'] == NULL)
									$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";	
								else
									$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";				
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
					    	$tabla .= " <td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto'] - $row[$i]['montoimpuestos'];
					}
					else if($row[$i]['idEmpleado'] == $row[$i-1]['idEmpleado'])
					{
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
								if($row[$i]['idCliente'] == NULL)
									$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";	
								else
									$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";				
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
					    	$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto']-$row[$i]['montoimpuestos'];
					}
					else
					{
						if ($simple == 1)
						{	$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
								$tabla .= "	<tr>
								<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total </b></td>
								<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
								</tr>";
						}
						else
						{
							$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=4> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";	
							$tabla .= "	<tr>
								<td style='									 								 text-align: right;			height: 34px;' colspan=5> <b>Total </b></td>
								<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
							</tr>";
						}
						$tabla .= "</table><br><br>";
						
						$total += $sumaiva+$sumamonto;
						$sumacambio = 0;
						$sumaiva = 0;
						$sumamonto = 0;
						
						$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>".$row[$i]['nombreempleado']."</div><br>"; 
						$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>
								<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>				Cliente</th>";
						if($simple == 1)
							$tabla .= "		<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>				Sucursal</th>";
						$tabla .= "		<th width=20% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>IVA</th>
							</tr>";
							
							
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
								if($row[$i]['idCliente'] == NULL)
									$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";	
								else
									$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";				
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
					    $tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto'] - $row[$i]['montoimpuestos'];
					}
				}
		
				if($simple == 1)
				{	$tabla .= "	<tr>
								<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>
								<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
								<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
		    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
							</tr>";
							
					$tabla .= "	<tr>
								<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total </b></td>
								<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
							</tr></table>";
							
					$total += $sumaiva+$sumamonto;
					$tabla .= "	<br><br><br><table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'><tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total de ventas </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #DA81F5; height: 34px;' colspan=2>	<center>$".number_format(($total), 2, '.', ',')."</center></td>
						</tr>";
					
					$tabla .= "</table><br><br>";
				}
				else
				{
					$tabla .= "	<tr>
								<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=4> <b>Subtotal </b></td>
								<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
								<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
		    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
							</tr>";
					$tabla .= "	<tr>
								<td style='									 								 text-align: right;			height: 34px;' colspan=5> <b>Total </b></td>
								<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
							</tr></table>";
					$total += $sumaiva+$sumamonto;
					$tabla .= "	<br><br><br><table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'><tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=5> <b>Total de ventas </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #DA81F5; height: 34px;' colspan=2>	<center>$".number_format(($total), 2, '.', ',')."</center></td>
						</tr>";
					
					$tabla .= "</table><br><br>";
				}			
			}	
			
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			
			else if($opc == 2)
			{
				for($i=0; $i<count($row); $i++)
				{
					$ecoformaspago = "";
					if($i==0)
					{
						if($row[$i]['idCliente'] == NULL)
							$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>Público en general</div><br>"; 
						else 
							$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>".$row[$i]['nombrecliente']."</div><br>"; 
						
						$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>";
						if($simple == 1)
							$tabla .= "<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>				Sucursal</th>";
						$tabla .= "<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Vendedor</th>
								<th width=20% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>IVA</th>
							</tr>";
							
							
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
						$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto'] - $row[$i]['montoimpuestos'];
					}
					else if($row[$i]['idCliente'] == $row[$i-1]['idCliente'])
					{
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
					    $tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto'] - $row[$i]['montoimpuestos'];
					}
					else
					{
						if($simple == 1)
						{
							$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
							$tabla .= "	<tr>
									<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total </b></td>
									<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
								</tr>";
							$tabla .= "</table><br><br>";
						}
						else 
						{
							$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=4> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
							$tabla .= "	<tr>
									<td style='									 								 text-align: right;			height: 34px;' colspan=5> <b>Total </b></td>
									<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
								</tr>";
							$tabla .= "</table><br><br>";
						}
						
						$total += $sumaiva + $sumamonto;
						$sumacambio = 0;
						$sumaiva = 0;
						$sumamonto = 0;
						
						if($row[$i]['idCliente'] == NULL)
							$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>Público en general</div><br>"; 
						else 
							$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>".$row[$i]['nombrecliente']."</div><br>";
						
						$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>";
						if($simple == 1)
							$tabla .= "<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Sucursal</th>";
						$tabla .= "	<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Vendedor</th>
								<th width=20% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>IVA</th>
							</tr>";
							
							
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
						$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto']-$row[$i]['montoimpuestos'];
					}
				}
		
				if($simple == 1)
				{
					$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
					$tabla .= "	<tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
						</tr></table>";
						
					$total += $sumaiva+$sumamonto;
					$tabla .= "	<br><br><br><table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'><tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total de ventas </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #DA81F5; height: 34px;' colspan=2>	<center>$".number_format(($total), 2, '.', ',')."</center></td>
						</tr>";
					$tabla .= "</table><br><br>";
				}
				else 
				{
					$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=4> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
					$tabla .= "	<tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=5> <b>Total </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
						</tr></table>";
						
					$total += $sumaiva+$sumamonto;
					$tabla .= "	<br><br><br><table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'><tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=5> <b>Total de ventas </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #DA81F5; height: 34px;' colspan=2>	<center>$".number_format(($total), 2, '.', ',')."</center></td>
						</tr>";
					$tabla .= "</table><br><br>";
				}
			}	
			
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			
			else if($opc == 3)
			{
				for($i=0; $i<count($row); $i++)
				{
					$ecoformaspago = "";
					if($i==0)
					{
						$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>".$row[$i]['nombresucursal']."</div><br>"; 
						
						$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>
								<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Cliente</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Vendedor</th>
								<th width=20% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>Impuestos</th>
							</tr>";
							
							
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
		    						if($row[$i]['idCliente'] == NULL)
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";
									}			
									else 
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";
									}		
						$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto'] - $row[$i]['montoimpuestos'];
					}
					else if($row[$i]['idSucursal'] == $row[$i-1]['idSucursal'])
					{
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
									if($row[$i]['idCliente'] == NULL)
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";
									}			
									else 
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";
									}	
						$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto']-$row[$i]['montoimpuestos'];
					}
					else
					{
						$tabla .= "	<tr>
								<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>
								<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
								<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
		    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
							</tr>";
						$tabla .= "	<tr>
								<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total </b></td>
								<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
							</tr>";
						$tabla .= "</table><br><br>";
						$total += $sumaiva + $sumamonto;
						
						$sumacambio = 0;
						$sumaiva = 0;
						$sumamonto = 0;
						
						$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>".$row[$i]['nombresucursal']."</div><br>";
						
						$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>
								<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Cliente</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Vendedor</th>
								<th width=20% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>Impuestos</th>
							</tr>";
							
							
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
									if($row[$i]['idCliente'] == NULL)
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";
									}			
									else 
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";
									}	
						$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto']-$row[$i]['montoimpuestos'];
					}
				}
		
				$tabla .= "	<tr>
								<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>
								<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
								<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
		    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
							</tr>";
				$tabla .= "	<tr>
						<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total </b></td>
						<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
					</tr></table>";
					
				$total += $sumaiva+$sumamonto;
				$tabla .= "	<br><br><br><table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'><tr>
						<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total de ventas </b></td>
						<td style='border: 1px solid #006efe; 		 background-color: #DA81F5; height: 34px;' colspan=2>	<center>$".number_format(($total), 2, '.', ',')."</center></td>
					</tr>";
				$tabla .= "</table><br><br>";
			}	
			
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			//============================================================
			
			else if($opc == 4)
			{
				$sumacantidad = 0;
				for($i=0; $i<count($row); $i++)
				{
					$ecoformaspago = "";
					if($i==0)
					{
						$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>".$row[$i]['nombreproducto']."</div><br>"; 
						
						$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Cliente</th>";
						if($simple == 1)
							$tabla .= "<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Sucursal</th>";
						$tabla .= "<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Vendedor</th>
								<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Cantidad</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>Impuestos</th>
							</tr>";
							
							
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
									if($row[$i]['idCliente'] == NULL)
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";
									}			
									else 
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";
									}	
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
						$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
					  						<td style='border-bottom: 1px solid #CCCCCC;'>	<center>".$row[$i]['cantidad']."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumacantidad += $row[$i]['cantidad'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto']-$row[$i]['montoimpuestos'];
					}
					else if($row[$i]['idProducto'] == $row[$i-1]['idProducto'])
					{
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
									if($row[$i]['idCliente'] == NULL)
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";
									}			
									else 
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";
									}	
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
					    $tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; '>	<center>".$row[$i]['cantidad']."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacambio += $row[$i]['cambio'];
						$sumacantidad += $row[$i]['cantidad'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto']-$row[$i]['montoimpuestos'];
					}
					else
					{
						if($simple == 1)	
						{
							$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=6> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  height: 34px;'>	<center>".$sumacantidad."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
									
								$tabla .= "	<tr>
									<td style='									 								 text-align: right;			height: 34px;' colspan=8> <b>Total </b></td>
									<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
								</tr>";
							$tabla .= "</table><br><br>";
						}
						else
						{
							$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  height: 34px;'>	<center>".$sumacantidad."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
									
								$tabla .= "	<tr>
									<td style='									 								 text-align: right;			height: 34px;' colspan=7> <b>Total </b></td>
									<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
								</tr>";
							$tabla .= "</table><br><br>";
						}
						
						$total += $sumamonto + $sumaiva;
						$sumacantidad = 0;
						$sumacambio = 0;
						$sumaiva = 0;
						$sumamonto = 0;
						
						$tabla .= "<div style='text-align: left; font-size: 14px; color: #006efe;'>".$row[$i]['nombreproducto']."</div><br>";
						
						$tabla .= "<table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'>
							<tr>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;'>	Fecha de venta</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Folio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Cliente</th>";
						if($simple == 1)
							$tabla .= "<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Sucursal</th>";
						$tabla .= "<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Vendedor</th>
								<th width=15% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Formas de pago</th>
								<th width=5%  style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe;'>									Cantidad</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F781BE;'>		Cambio</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #58FA82;'>		Total venta</th>
								<th width=10% style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; background-color: #F3F781; border-right: 1px solid #006efe;'>Impuestos</th>
							</tr>";
							
							
						$tabla .= "  
							<tr>
									<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC; border-left: 1px solid #006efe;'>	<center> ".$row[$i]['fecha']."</center></td>
		    						<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".$row[$i]['idVenta']."</center></td>";
									if($row[$i]['idCliente'] == NULL)
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>Público en general</td>";
									}			
									else 
									{
										$tabla .= "<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombrecliente'])."</td>";
									}	
						if($simple == 1)
							$tabla .= "	<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center> ".utf8_encode($row[$i]['nombresucursal'])."</center></td>";
						$tabla .= " <td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	".utf8_encode($row[$i]['nombreempleado'])."</td>
					    			<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #CCCCCC;'>	<center>";
					    					
							    		$obtieneFormasPago = $connection->query("
																SELECT p.idVenta, p.idFormapago, f.nombre,
																p.monto, p.referencia
																FROM venta_pagos p
																INNER JOIN venta v ON v.idVenta = p.idVenta
																INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago
																WHERE p.idVenta = ".$row[$i]['idVenta']." 
																ORDER BY idVenta;");
																
										while($rows2 = $obtieneFormasPago->fetch_array())
											$row2[] = $rows2;
										
										for($j=0; $j<count($row2); $j++)
										{
											if($row2[$j]['idVenta'] == $row[$i]['idVenta'])
											{
												$ecoformaspago .= $row2[$j]['nombre']."($".number_format($row2[$j]['monto'], 2, '.', ',').") <br>";
											}
										}
										$tabla .= $ecoformaspago;
															
					    $tabla .= "	</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; '>	<center>".$row[$i]['cantidad']."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #F781BE;'>	<center>$".number_format($row[$i]['cambio'], 2, '.', ',')."</center></td>
											<td style='border-bottom: 1px solid #CCCCCC; background-color: #58FA82;'>	<center>$".number_format(($row[$i]['monto'] - $row[$i]['montoimpuestos']), 2, '.', ',')."</center></td>
					    					<td style='border-bottom: 1px solid #CCCCCC; border-right: 1px solid #006efe; background-color: #F3F781;'>	<center>$".number_format($row[$i]['montoimpuestos'], 2, '.', ',')."</center></td>
										</tr>";
										
						$sumacantidad += $row[$i]['cantidad'];
						$sumacambio += $row[$i]['cambio'];
						$sumaiva += $row[$i]['montoimpuestos'];
						$sumamonto += $row[$i]['monto']-$row[$i]['montoimpuestos'];
					}
				}
		
				if($simple == 1)
				{
					$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=6> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  height: 34px;'>	<center>".$sumacantidad."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
					$tabla .= "	<tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=8> <b>Total </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
						</tr></table>";
						
					$total += $sumaiva+$sumamonto;
					$tabla .= "	<br><br><br><table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'><tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=6> <b>Total de ventas </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #DA81F5; height: 34px;' colspan=2>	<center>$".number_format(($total), 2, '.', ',')."</center></td>
						</tr>";
					$tabla .= "</table><br><br>";
				}
				else
				{
					$tabla .= "	<tr>
									<td style='									 border-top: 1px solid #006efe; 								 text-align: right;			height: 34px;' colspan=5> <b>Subtotal </b></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  height: 34px;'>	<center>".$sumacantidad."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-left: 1px solid #006efe;  background-color: #F781BE; height: 34px;'>	<center>$".number_format($sumacambio, 2, '.', ',')."</center></td>
									<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; 								 background-color: #58FA82; height: 34px;'>	<center>$".number_format($sumamonto, 2, '.', ',')."</center></td>
			    					<td style='border-bottom: 1px solid #006efe; border-top: 1px solid #006efe; border-right: 1px solid #006efe; background-color: #F3F781; height: 34px;'>	<center>$".number_format($sumaiva, 2, '.', ',')."</center></td>
								</tr>";
					$tabla .= "	<tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=7> <b>Total </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #00BFFF; height: 34px;' colspan=2>	<center>$".number_format(($sumaiva+$sumamonto), 2, '.', ',')."</center></td>
						</tr></table>";
						
					$total += $sumaiva+$sumamonto;
					$tabla .= "	<br><br><br><table cellpadding='0' cellspacing='0' width='100%' style='min-width: 100%; font-size: 12px;'><tr>
							<td style='									 								 text-align: right;			height: 34px;' colspan=5> <b>Total de ventas </b></td>
							<td style='border: 1px solid #006efe; 		 background-color: #DA81F5; height: 34px;' colspan=2>	<center>$".number_format(($total), 2, '.', ',')."</center></td>
						</tr>";
					$tabla .= "</table><br><br>";
				}
			}	
			
			
			
			
			
			
			
			
			
			$tabla .= "</div><br><br><input type='button' value='Mostrar la lista completa' onclick='cargaReporte();'><br><br><br><br></center>";
			echo $tabla;
		}
		else
		{
			$tabla .= "<br><br><br><div style='color: #006efe;'>No se encontraron ventas recientes</div><br><br><br><br>";
			$tabla .= "</div></center>";
			echo $tabla;
		}
	}
	
	function compruebaSimple($connection)
	{
		session_start();
		$simple=0;
		
		$result = $connection->query("select a.idperfil from accelog_perfiles_me a where idmenu=1259");
		if($result->num_rows > 0)
		{
			$simple = 1;
		}
		echo $simple;
	}
?>