<?php include_once("../../netwarelog/catalog/conexionbd.php"); 
	$qry= strtolower($_GET["term"]);
	$return = array();

	$strSql = "SELECT au.idSuc,mp.nombre FROM administracion_usuarios au,mrp_sucursal mp WHERE mp.idSuc=au.idSuc AND au.idempleado=" . $_SESSION['accelog_idempleado'];

	$q=mysql_query($strSql);		

	if(mysql_num_rows($q)>0)
	{
		while($r=mysql_fetch_object($q))
		{
			$sucursal=$r->idSuc;
		}
	}	

	$strSql = "SELECT s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen FROM mrp_sucursal s, almacen a WHERE s.idAlmacen=a.idAlmacen AND s.idSuc=".$sucursal;

	$qsuc=mysql_query($strSql);
	$objsuc=mysql_fetch_object($qsuc);
	
	$strSql = "select  mrp_producto.idProducto id,mrp_producto.nombre,
		CASE WHEN mrp_stock.cantidad  IS NOT NULL 
		THEN mrp_stock.cantidad-if(SUM(mrp_devoluciones_reporte.nDevoluciones) is null,0,SUM(mrp_devoluciones_reporte.nDevoluciones))
		ELSE 0 END AS cantidad from  mrp_producto left join mrp_stock  on mrp_producto.idProducto=mrp_stock.idProducto 
		left join mrp_devoluciones_reporte on mrp_devoluciones_reporte.idProducto=mrp_stock.idProducto and mrp_devoluciones_reporte.idProveedor=mrp_producto.idProveedor and mrp_devoluciones_reporte.idAlmacen=mrp_stock.idAlmacen and mrp_devoluciones_reporte.estatus=0
		where (nombre like '%$qry%' or mrp_producto.idProducto like '%$qry%') and vendible=1 and mrp_stock.idAlmacen=".$objsuc->idAlmacen." group by mrp_producto.idProducto order by nombre";
	
	$query = mysql_query($strSql);

	while ($row = mysql_fetch_array($query)) {
		array_push($return,array('id'=>$row["id"],'label'=>utf8_encode($row['id']." / ".$row['nombre'].":".number_format($row['cantidad'],2,".",","))));
	}
	mysql_close($conexion);
	echo(json_encode($return));
?> 