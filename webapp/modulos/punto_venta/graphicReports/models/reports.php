<?php 
	/*==========================================================================
	=            models/reports.php - Miguel Angel Velazco Martinez            =
	===========================================================================*/
	
	/**
	
		TODO:
		- Resumen de ventas por rango de fechas (Grafico)(lineas) √ dateSales [Ventas, Fecha]
		- Ventas por categoría por rango de fechas (Grafico)(barras) √ dateFamilySales [Ventas, Familia]
		- Ventas por cliente por rango de fechas (Grafico)(barras) √ clientSales [Ventas, Nombre]
		- Ventas por producto (Grafico)(Barras) √ productSales [Ventas, Nombre]
		- Ventas porcentaje por empleado (Grafico) √ employeeSales [Ventas, Nombre]
		- Resumen porcentaje por tipo de pagos (Grafico) √ paymentSales [Ventas, Nombre]
		- Reporte resumen porcentaje por tipo de impuesto (Grafico) (Este no lo entiendo pero habrá que discutirse su utilidad)
		- Resumen de ventas por proveedores (Grafico)(Barras) ???
		- Conseguir sucursales para llenar select (getSucursals)
	
	**/
	
	/*-----  End of models/reports.php - Miguel Angel Velazco Martinez  ------*/
	
	require 'connection_sqli.php';

	class Report extends Connection
	{	
		public function clientSales( $initDate, $finalDate, $sucursal )
		{
			$sql = "SELECT ";
			$sql .= "	SUM(v.monto) AS Ventas, ";
			$sql .= "	if( ISNULL(c.nombre),'Cliente Generico', c.nombre) AS Nombre ";
			$sql .= "FROM ";
			$sql .= "	venta v ";
			$sql .= "	LEFT JOIN comun_cliente c ON v.idCliente = c.id ";
			$sql .= "WHERE ";
			$sql .= "	v.estatus = 1 ";
			$sql .= "	AND  v.fecha BETWEEN '" . $initDate . " 00:00:00' AND '" . $finalDate . " 23:59:59' ";
			$sql .= "	AND v.idSucursal IN(" .  implode(",", $sucursal) . ")";
			$sql .= "GROUP BY ";
			$sql .= "	c.nombre;";
			$data = $this->returnData( $sql );
			return $data;
		}

		public function dateFamilySales( $initDate, $finalDate, $sucursal )
		{
			$sql  = "SELECT ";
			$sql .= "	 SUM(vp.total) AS Ventas, ";
			$sql .= "	 f.nombre AS Familia ";
			$sql .= "FROM ";
			$sql .= "	venta_producto vp ";
			$sql .= "	INNER JOIN venta v ON v.idVenta = vp.idVenta AND v.estatus = 1 AND v.fecha BETWEEN '" . $initDate . " 00:00:00' AND '" . $finalDate . " 23:59:59' AND v.idSucursal IN(" .  implode(",", $sucursal) . ") ";
			$sql .= "	INNER JOIN mrp_producto p ON p.idProducto = vp.idProducto ";
			$sql .= "	INNER JOIN mrp_linea l ON l.idLin = p.idLinea ";
			$sql .= "	INNER JOIN mrp_familia f ON f.idFam = l.idFam ";
			$sql .= "GROUP BY 2;";
			$data = $this->returnData( $sql );
			return $data;
		}

		public function dateSales( $initDate, $finalDate, $sucursal )
		{
			$sql  = "SELECT";
			$sql .= "	SUM(v.monto) AS Ventas, ";
			$sql .= "	DATE(v.fecha) AS Fecha ";
			$sql .= "FROM ";
			$sql .= "	venta v ";
			$sql .= "WHERE ";
			$sql .= "	DATE(v.fecha) ";
			$sql .= "	BETWEEN";
			$sql .= "		'" . $initDate . " 00:00:00'";
			$sql .= "	AND";
			$sql .= "		'" . $finalDate . " 23:59:59'";
			$sql .= "	AND ";
			$sql .= "		v.estatus = 1 ";
			$sql .= "	AND v.idSucursal IN(" .  implode(",", $sucursal) . ") ";
			$sql .= "GROUP BY ";
			$sql .= "	2;";
			$data = $this->returnData( $sql );
			return $data;
			// return $sql;
		}	

		public function employeeSales( $initDate, $finalDate, $sucursal )
		{
			$sql  = "SELECT ";
			$sql .= "	SUM(v.monto) AS Ventas, ";
			$sql .= "	CONCAT (u.nombre,' ',u.apellidos) AS Nombre ";
			$sql .= "FROM ";
			$sql .= "	venta v ";
			$sql .= "	LEFT JOIN empleados e ON e.idEmpleado = v.idEmpleado ";
			$sql .= "	LEFT JOIN administracion_usuarios u ON u.idempleado = v.idEmpleado ";
			$sql .= "WHERE ";
			$sql .= "	v.estatus = 1 ";
			$sql .= "	AND v.fecha BETWEEN '" . $initDate . " 00:00:00' AND '" . $finalDate . " 23:59:59' ";
			$sql .= "	AND v.idSucursal IN(" .  implode(",", $sucursal) . ")";
			$sql .= "GROUP BY ";
			$sql .= "	2;";
			$data = $this->returnData( $sql );
			return $data;
		}

		public function getSucursals()
		{
			$sql  = "SELECT ";
			$sql .= "    idSuc AS id, ";
			$sql .= "    nombre AS name ";
			$sql .= "FROM ";
			$sql .= "    mrp_sucursal;";
			$data = $this->returnData( $sql );
			return $data;
		}

		public function paymentSales( $initDate, $finalDate, $sucursal )
		{
			$sql  = "SELECT ";
			$sql .= "	IF( p.idFormapago = 1, sum(p.monto - v.cambio ), sum(p.monto) ) AS Ventas, ";
			$sql .= "	f.nombre AS Nombre ";
			$sql .= "FROM ";
			$sql .= "	venta_pagos p ";
			$sql .= "	INNER JOIN venta v ON v.fecha BETWEEN '" . $initDate . " 00:00:00' AND '" . $finalDate . " 23:59:59' AND v.idVenta = p.idVenta AND v.estatus = 1 AND v.idSucursal IN(" .  implode(",", $sucursal) . ") ";
			$sql .= "	INNER JOIN forma_pago f ON f.idFormapago = p.idFormapago ";
			$sql .= "GROUP BY f.idFormapago; ";
			$data = $this->returnData( $sql );
			return $data;
		}

		public function productSales( $initDate, $finalDate, $sucursal )
		{
			$sql  = "SELECT ";
			$sql .= "	 SUM(vp.total) AS Ventas, ";
			$sql .= "	 p.nombre AS Nombre ";
			$sql .= "FROM ";
			$sql .= "	venta_producto vp ";
			$sql .= "	INNER JOIN venta v ON v.idVenta = vp.idVenta AND v.estatus = 1 AND v.fecha BETWEEN '" . $initDate . " 00:00:00' AND '" . $finalDate . " 23.59:59' AND v.idSucursal IN(" .  implode(",", $sucursal) . ") ";
			$sql .= "	INNER JOIN mrp_producto p ON p.idProducto = vp.idProducto ";
			$sql .= "GROUP BY 2 order by Ventas desc ;";
			 //var_dump($sql);
			$data =  $this->returnData( $sql );
			return $data;
		}

		public function returnData($sql)
		{
			$data = array();
			$result = $this->query( $sql );
			for ($i=0; $i < $result->num_rows; $i++)
			{ 
				array_push( $data, $result->fetch_array( MYSQLI_ASSOC ) );
			}
			return json_encode($data);
		}
	}
?>