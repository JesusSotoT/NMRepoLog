<?php 
	/*==================================================================
	=            boxCut.php - Miguel Angel Velazco Martinez            =
	==================================================================*/
	
	/**
	
		TODO:
		- Esta clase extiende a la clase de conexion del modulo de reportes graficos
		- GetBoxCuts
			- Este metodo permite traer un listado de cortes de caja con una paginacion
		- GetCut
			- Este metodo permite obtener 
				Seccion 1.- Los Pagos activos del usuario en el rango de fechas definido.
				Seccion 2.- Los productos vendidos relacionados con los pagos arriba descritos.
				Seccion 3.- El saldo final del ultimo corte de caja.
				Seccion 4.- Deberia cargar los pagos a cuentas por cobrar. 
				Seccion 5.- Las fechas ingresadas por los metodos
				Seccion 6.- (Solamente para los reportes) Los abonos y retiros de ese corte.
		- getSales
			- Permite realizar una ataptacion a getCut para que obtenga las ventas del dia.
		- getSucursal
			- Permite obtener la sucursal asignada al usuario. La base de la obtencion de dicha 
			  informacion es el id de usuario de la sesion.
		- newCut
			- Genera un corte de caja y actualiza el inicio de caja asignando su id de corte.
		- sales
			- Revisa si existen registros de ventas. Retorna el numero de ventas realizadas
		- returnData
			- Realiza un encoding a JSON de los datos generados por la consulta y los retorna 
			  al metodo que ordeno su ejecucion.
	
	**/
	
	
	/*-----  End of boxCut.php - Miguel Angel Velazco Martinez  ------*/
	
	require '../../graphicReports/models/connection_sqli.php';

	class BoxCut extends Connection
	{
		function __construct()
		{
			@session_start();
			//$this->sucursal = $this->getSucursal($_SESSION['accelog_idempleado']);
			$this->user = $_SESSION['accelog_idempleado'];
		}

		public function getBoxCuts( $jump = 0, $init, $end, $user )
		{
			$perfil = $_SESSION['accelog_idperfil'];
			//echo 'X'.$perfil.'X';
			$sql  = "SELECT ";
			$sql .= "	c.idCortecaja, ";
			$sql .= "	c.fechainicio, ";
			$sql .= "	c.fechafin, ";
			$sql .= "	c.retirocaja, ";
			$sql .= "	c.abonocaja, ";
			$sql .= "	c.saldoinicialcaja, ";
			$sql .= "	c.saldofinalcaja, ";
			$sql .= "	c.montoventa, ";
			$sql .= "	u.usuario, ";
			$sql .= "	c.idEmpleado ";
			$sql .= "FROM ";
			$sql .= "	corte_caja c, accelog_usuarios u ";
			$sql .= "WHERE ";

			$perfil = str_replace('(', '', $perfil);
			$perfil = str_replace(')','',$perfil);	
			//echo '['.$perfil.']';
			if($perfil != 2 ){
				//if($perfil == 5){
				//	$sql .="  c.idEmpleado=u.idempleado ";
				//}else{
					$sql .= "	c.idEmpleado = " . $this->user . " and c.idEmpleado=u.idempleado ";
				//}
			}else{
				$sql .="  c.idEmpleado=u.idempleado ";
			}
			
			//$sql .= "   c.idEmpleado = u.idempleado ";
			 //$sql .= "	AND " . $this->user . " = (SELECT idempleado FROM  venta v WHERE v.idempleado = " . $this->user . " AND v.fecha BETWEEN c.fechainicio AND c.fechafin LIMIT 1) ";
			if ($init != 0 && $end != 0)
			{
				$sql .= "AND fechainicio BETWEEN '" . $init . " 00:00:01' AND '" . $end . " 23:59:59' ";
			}
			
		// Si se manda el idempleado($user) crea un filtro por usuario
			$sql .=($user!='*'&&!empty($user))?'AND u.idempleado='.$user.' ':'';
			
			$sql .= "ORDER BY ";
			$sql .= " fechafin DESC ";
			$sql .= "LIMIT " . $jump . ", 15;";
			//echo $sql;
			$data =  $this->returnData( $sql );

			return $data;
			//return $sql;
		}

		public function getCut($init, $end, $onlyShow = false, $iduser=0)
		{		//echo '(((((((((((((((((((((((((((((((((((((((((((((((((((((((((((('.$iduser.')))))))))))))))))))))))))';
			// Inicia Seccion 1 - Pagos - 
			if($iduser==0 || $iduser==''){
				$iduser = $this->user;
			}
				$sql  = 'SELECT ';
				$sql .= '	"Ventas" AS Flag, ';
				$sql .= '	v.idVenta, ';
				$sql .= '	v.fecha, ';
				$sql .= '	c.nombre, ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 1 AND v.idVenta = vp.idVenta )  AS Efectivo , ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 4 AND v.idVenta = vp.idVenta ) AS TCredito, ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 5 AND v.idVenta = vp.idVenta ) AS TDebito, ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 6 AND v.idVenta = vp.idVenta ) AS CxC, ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 2 AND v.idVenta = vp.idVenta ) AS Cheque, ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 7 AND v.idVenta = vp.idVenta ) AS Trans, ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 8 AND v.idVenta = vp.idVenta ) AS SPEI, ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 3 AND v.idVenta = vp.idVenta ) AS TRegalo, ';
				$sql .= '	( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM venta_pagos vp WHERE vp.idFormapago = 9 AND v.idVenta = vp.idVenta ) AS Ni, ';
				//$sql .= '  	';
				$sql .= '	REPLACE(FORMAT(v.cambio, 2),",","") as cambio, ';
				$sql .= '	REPLACE(FORMAT(v.montoimpuestos, 2), ",", "") AS Impuestos, ';
				$sql .= '	REPLACE(FORMAT((v.monto - v.montoimpuestos), 2 ), ",", "") AS Monto, ';
				$sql .= '	REPLACE(FORMAT(v.monto, 2), ",", "") AS Importe ';
				$sql .= 'FROM ';
				$sql .= '	venta v ';
				$sql .= '	LEFT JOIN venta_pagos p ON p.idVenta = v.idVenta ';
				$sql .= '	LEFT JOIN comun_cliente c ON v.idCliente = c.id ';
				$sql .= 'WHERE ';
				$sql .= '	v.estatus = 1 ';
				$sql .= '	AND ';
				$sql .= '	v.idEmpleado = ' . $iduser . ' ';
				$sql .= '	AND ';
				$sql .= '	v.fecha BETWEEN ';
				$sql .= '	"' . $init . '" ';
				$sql .= '	AND ';
				$sql .= '	"' . $end . '" ';
				$sql .= 'GROUP BY ';
				$sql .= '	v.idVenta ';
				$sql .= 'UNION ';
			// Termina Seccion 1
			// Inicia Seccion 2 - Productos Vendidos
				$sql .= 'SELECT ';
				$sql .= '	"Productos" AS Flag, ';
				$sql .= '	p.codigo, ';
				$sql .= '	p.nombre, ';
				$sql .= '	sum(vp.cantidad) AS Cantidad, ';
				$sql .= '	REPLACE(FORMAT(vp.preciounitario,2), ",", "") AS preciounitario, ';
				$sql .= '	REPLACE(FORMAT(sum(vp.montodescuento), 2), ",", "") AS Descuento, ';
				$sql .= '	REPLACE(FORMAT(sum(vp.impuestosproductoventa), 2), ",", "") AS Impuestos, ';
				//$sql .= '	REPLACE(FORMAT(sum( (vp.subtotal + vp.impuestosproductoventa) - vp.descuento ), 2), ",", "") AS Subtotal, ';
				$sql .= '	REPLACE(FORMAT(sum(vp.total), 2), ",", "") AS Subtot, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00 ';
				$sql .= 'FROM ';
				$sql .= '	venta_producto vp ';
				$sql .= '	INNER JOIN mrp_producto p ON vp.idProducto = p.idProducto ';
				$sql .= 'WHERE ';
				$sql .= '	vp.idVenta IN(SELECT idVenta from venta v WHERE v.idEmpleado = ' . $iduser . ' AND v.estatus = 1 AND v.fecha BETWEEN "' . $init . '" AND "' . $end . '") ';
				$sql .= 'GROUP BY ';
				$sql .= '	p.idProducto ';
				$sql .= 'UNION ';
			// Termina Seccion 2
			// Inicia Seccion 3 - Saldo del ultimo Corte - 
				if( !$onlyShow )
				{
					$sql .= 'SELECT ';
					$sql .= '	"SaldoIni" AS Flag, ';
					$sql .= '	IF(';
					$sql .= '		( SELECT MAX(idCortecaja) FROM corte_caja WHERE idEmpleado = ' . $iduser . ' ) IS NULL,';/* SI NO TIENE CORTES DE CAJA PREVIOS */
					$sql .= '		( SELECT FORMAT(monto,2) FROM inicio_caja i WHERE i.idUsuario = ' . $iduser . ' AND id = ( SELECT MAX(id) FROM inicio_caja WHERE idUsuario = ' . $iduser . ' ) ),';/* SELECCIONE SU ULTIMO INICIO DE CAJA, O SI NO */
					$sql .= '		(SELECT FORMAT((SELECT saldofinalcaja FROM corte_caja WHERE idCortecaja = (SELECT MAX(idCortecaja) FROM corte_caja WHERE idEmpleado = ' . $iduser . '))+(SELECT monto FROM inicio_caja i WHERE i.idUsuario = ' . $iduser . ' AND i.id IN (IF((SELECT MAX(id) FROM inicio_caja WHERE idUsuario = ' . $iduser . ' AND idCorteCaja IS NULL) IS NULL ,0,(SELECT MAX(id) FROM inicio_caja WHERE idUsuario = ' . $iduser . ' AND idCortecaja IS NULL)))),2)) ';/* SELECCIONE SU ULTIMO CORTE DE CAJA + SU ULTIMO INICIO DE CAJA ( SI LO HAY ) */
					$sql .= '	) AS "SaldoIni", ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00 ';
					$sql .= 'UNION ';
				}				
			// Termina Seccion 3
			// Inicia Seccion 4 - Pagos a CxC Recibidos por el usuario - 
				$sql .= 'SELECT ';
				$sql .= '	"CxC" AS Flag, ';
				$sql .= '	c.idCxcpagos AS IDPago, ';
				$sql .= '	x.fechacargo AS "Fecha_de_Registro", ';
				$sql .= '	x.fechavencimiento AS "Fecha_de_Vencimiento", ';
				$sql .= '	cl.nombre, ';
				$sql .= '	c.fecha AS "FechaPago", ';
				$sql .= '	c.monto AS monto, ';
				$sql .= '	f.nombre AS "Forma de Pago", ';
				$sql .= '	e.nombre AS Atendio, ';
				$sql .= '	c.idFormapago, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00 ';
				$sql .= 'FROM ';
				$sql .= '	cxc_pagos c ';
				$sql .= '	RIGHT JOIN cxc x ON x.idCxc = c.idCxc  ';
				$sql .= '	INNER JOIN vista_empleados e ON e.idEmpleado = c.idEmpleado ';
				$sql .= '	INNER JOIN forma_pago f ON f.idFormapago = c.idFormapago ';
				$sql .= '	INNER JOIN comun_cliente cl ON cl.id = x.idCliente ';
				$sql .= 'WHERE ';
				$sql .= '	c.idEmpleado = ' . $iduser . ' ';
				$sql .= '	AND c.fecha BETWEEN "' . $init . '" AND "' . $end . '" ';
				$sql .= 'UNION ';
			// Termina Seccion 4
			// Inicia Seccion 5 - Rango de fechas -
				$sql .= 'SELECT ';
				$sql .= '	"LastData" AS Flag, ';
				$sql .= '	"' . $init . '" AS Init, ';
				$sql .= '	"' . $end . '" AS End, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00, ';
				$sql .= '	0.00 ';
			// Termina Seccion 5 - Rango de fechas -
			// Inicia Seccion 6 - Datos Exclusivos de la muestra -
				if ( $onlyShow )
				{
					$sql .= 'UNION ';
					$sql .= 'SELECT ';
					$sql .= '	"cutInfo" AS Flag, ';
					$sql .= '	IF( retirocaja IS NULL, 0, retirocaja) AS retirocaja, ';
					$sql .= '	IF( abonocaja IS NULL, 0, abonocaja) AS abonocaja, ';
					$sql .= '	IF( saldoinicialcaja IS NULL, 0, saldoinicialcaja ) AS saldoInicial, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00, ';
					$sql .= '	0.00 ';
					$sql .= 'FROM ';
					$sql .= '	corte_caja ';
					$sql .= 'WHERE ';
					$sql .= '	fechainicio = "' . $init . '" ';
					$sql .= '	AND fechafin = "' . $end . '" ';
					$sql .= '	AND idEmpleado = "' . $iduser . '" ';
				}
				//echo $sql.'XXXXXXXXXXXXX';
			// Termina Seccion 6 
			$data =  $this->returnData( $sql, true );
			return $data;
		}
		public function getRetiros($desde,$hasta,$idcorte){
			if($idcorte=='A'){
				$sql = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from venta_retiro_caja r, accelog_usuarios u where r.idempleado=u.idempleado and fecha between  '".$desde."' and '".$hasta."' and r.idempleado=".$_SESSION['accelog_idempleado'];
			}else{
				$sql = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from venta_retiro_caja r, accelog_usuarios u where r.idempleado=u.idempleado and fecha between  '".$desde."' and '".$hasta."' and r.idcorte=".$idcorte;
			}

			$data =  $this->returnData( $sql, true );
			return $data;

		} 

		public function getSales()
		{
			$init = null;
			$end  = null;
			date_default_timezone_set('America/Mexico_City');
			$date = date("Y-m-d G:i:s");
			$sql  = "SELECT ";
			$sql .= "	IF( NOT ISNULL(cc.fechafin), cc.fechafin, 'ALL' ) AS Init, ";
			$sql .= "	'" . $date . "' AS 'End' ";
			$sql .= "FROM ";
			$sql .= "	corte_caja cc ";
			$sql .= "WHERE ";
			$sql .= "	cc.idCortecaja = (SELECT MAX(idCortecaja) FROM corte_caja WHERE idEmpleado =  " . $this->user . ");";

			$result = $this->query( $sql );

			if ( $result->num_rows != 0 )
			{
				$data = $result->fetch_array( MYSQLI_ASSOC );
				if( $data['Init'] !== "ALL" )
				{
					$init = $data['Init'];
					$end  = $data['End'];
				}
			}

			if (is_null($init) || is_null($end))
			{
				$sql  = "SELECT ";
				$sql .= "	fecha ";
				$sql .= "FROM ";
				$sql .= "	inicio_caja i ";
				$sql .= "WHERE";
				$sql .= "	id = ( SELECT MIN(id) FROM inicio_caja WHERE idUsuario = '" . $this->user . "' );";
				
				$result = $this->query($sql);
				
				if ( $result->num_rows > 0 )
					$data2 = $result->fetch_array( MYSQLI_ASSOC );

				$init = ( isset($data2) ) ? $data2['fecha'] : "0000-01-01 00:00:01";
				date_default_timezone_set("Mexico/General");
				$end  = date('Y-m-d H:i:s');
			}
			

			return $this->getCut( $init, $end );
		}

		public function getSucursal($userId)
		{
			$sql  = "SELECT ";	
			$sql .= "	idSuc ";
			$sql .= "FROM ";	
			$sql .= "	administracion_usuarios ";	
			$sql .= "WHERE ";	
			$sql .= "	idempleado = " . $userId . ";";	
			$result = $this->query( $sql );
			$result = $result->fetch_array( MYSQLI_ASSOC );
			return $result['idSuc'];
		}

		public function newCut()
		{
			@session_start();
			$fecha_inicio     = $_POST['fecha_inicio'];
			$fecha_fin        = $_POST['fecha_fin'];
			$saldo_inicial    = $_POST['saldo_inicial'];
			$monto_venta      = $_POST['monto_ventas'];
			$saldo_disponible = $_POST['saldo_disponible'];
			$retiro_caja      = $_POST['retiro_caja'];
			$deposito_caja    = $_POST['deposito_caja'];
			$retiros          = $_POST['retiros'];

			$saldo_final = ( $saldo_disponible - $retiro_caja ) + $deposito_caja;
			
			try
			{
				$qry  = "INSERT INTO corte_caja ";
				$qry .= "(fechainicio, ";
				$qry .= "fechafin, ";
				$qry .= "retirocaja, ";
				$qry .= "abonocaja, ";
				$qry .= "saldoinicialcaja, ";
				$qry .= "saldofinalcaja, ";
				$qry .= "montoventa, ";
				$qry .= "idEmpleado) ";
				$qry .= "VALUES ";
				$qry .= "('" . $fecha_inicio . "', ";
				$qry .= "'" . $fecha_fin . "', ";
				$qry .= "" . $retiro_caja . ", ";
				$qry .= "" . $deposito_caja . ", ";
				$qry .= "" . $saldo_inicial . ", ";
				$qry .= "" . $saldo_final . ", ";
				$qry .= "" . $monto_venta . ", ";
				$qry .= "" . $_SESSION['accelog_idempleado'] . ");";
				
				
				$id = $this->indexQuery( $qry );
	///////Retiros de caja
				foreach ($retiros as $key => $value) {
					$updt = "UPDATE venta_retiro_caja set idcorte=".$id." where id=".$value['idre'];
					$this->query($updt);
				}
	////////// 			
				
				$qry  = "SELECT ";
				$qry .= "au.idSuc ";
				// $qry .= "mp.nombre ";
				$qry .= "FROM ";
				$qry .= "administracion_usuarios au ";
				// $qry .= "mrp_sucursal mp ";
				$qry .= "WHERE ";
				//$qry .= "mp.idSuc=au.idSuc ";
				$qry .= "au.idempleado = " . $_SESSION['accelog_idempleado'] . ";";

				$q = $this->query( $qry );		
				if( $q->num_rows > 0 )
				{
					$r = $q->fetch_assoc();
					// $sucursal_operando=$r['nombre'];
					$sucursal_id=$r['idSuc'];	
				}
				
				$qry  = "UPDATE ";
				$qry .= "inicio_caja ";
				$qry .= "SET ";
				$qry .= "idCortecaja = " . $id . " ";
				$qry .= "WHERE ";
				$qry .= "idCortecaja IS NULL ";
				$qry .= "AND idSucursal = " . $sucursal_id . " ";
				$qry .= "AND idUsuario = " . $_SESSION['accelog_idempleado'] . ";";

				$this->query( $qry );

				//Inicia generacion de polizas contables---------------------------------------------------------

				$polizas_por = $enlace = $this->polizas_por();
				if(is_null($enlace))
				{
					$enlace=0;
				}

				if($enlace)//Si regresa true entonces esta activado el enlace.
				{
					$ventas = $this->getVentas($fecha_inicio,$fecha_fin);
					if(intval($ventas->num_rows)>0)//Hay ventas
					{
						$fecha_fin = explode('-',$fecha_fin);
						if($polizas_por == 1)// si es 1 se genera polizas por corte de caja
						{
							$numpol = $this->ultimaNumpol($fecha_fin[0],$fecha_fin[1]);
							if(is_null($numpol))
							{
								$numpol=0;
							}
							$numpol++;
							$Poliza = $this->poliza($fecha_fin[0],$fecha_fin[1],$fecha_fin[2],$numpol);
							$consulta='';
							$NumMovto = 1;
							while($v = $ventas->fetch_assoc())
							{
								$factura = "-";
								$archivo = "../../../facturas/".$v['Factura'].".xml";
								if(file_exists($archivo))
								{
									global $xp;
									$newdir = "../../../cont/xmls/facturas/$Poliza";
									mkdir($newdir, 0777);

									$file 	= $archivo;
									$texto 	= file_get_contents($file);
									$texto 	= preg_replace('{<Addenda.*/Addenda>}is', '<Addenda/>', $texto);
									$texto 	= preg_replace('{<cfdi:Addenda.*/cfdi:Addenda>}is', '<cfdi:Addenda/>', $texto);
									$xml 	= new DOMDocument();
									$xml->loadXML($texto);
									
									$xp = new DOMXpath($xml);
									$data['uuid'] 	= $this->getpath("//@UUID");
									$data['folio'] 	= $this->getpath("//@folio");
									$data['emisor'] = $this->getpath("//@nombre");
									$factura = $data['folio']."_".$data['emisor'][0]."_".$data['uuid'].".xml";
									copy($archivo,$newdir."/".$factura);
								}
								if(floatval($v['montoMenosImpuestos'])>0)
								{
									$prodventas = $this->ventaProductos($v['idVenta']);
									while($pv = $prodventas->fetch_assoc())
									{
										if(intval($pv['idCuenta']) != 0)
										{
											$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",".$pv['idCuenta'].",'Abono',".number_format($pv['SubTotal'],2,'.','').",'".$data['uuid']."','Ventas Linea ".$pv['nombre']." PDV(".$v['idVenta'].")',1,NOW(),'$factura'); ";//Guarda Ventas
										}
										else
										{
											$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT ventas FROM cont_config_pdv WHERE id=1),'Abono',".number_format($pv['SubTotal'],2,'.','').",'".$data['uuid']."','Ventas PDV(".$v['idVenta'].")',1,NOW(),'$factura'); ";//Guarda Ventas
										}
									}
									$NumMovto++;
								}
								
								/*if(floatval($v['montoMenosImpuestos'])>0)
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT ventas FROM cont_config_pdv WHERE id=1),'Abono',".number_format($v['montoMenosImpuestos'],2,'.','').",'Ventas','Venta PDV(".$v['idVenta'].")',0,NOW(),'-'); ";//Guarda Ventas
									$NumMovto++;
								}*/
								if(floatval($v['montoimpuestos'])>0)
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT iva FROM cont_config_pdv WHERE id=1),'Abono',".number_format($v['montoimpuestos'],2,'.','').",'".$data['uuid']."','IVA PDV(".$v['idVenta'].")',1,NOW(),'$factura'); ";//Guarda IVA
									$NumMovto++;
								}
								
								//INICIA Medios de pago----------------------------------------
								
								/*if(floatval($v['Efectivo'])>0)
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT bancos FROM cont_config_pdv WHERE id=1),'Cargo',".number_format($v['Efectivo'],2,'.','').",'Bancos pago en Efectivo','Venta PDV(".$v['idVenta'].")',0,NOW(),'-'); ";//Guarda Bancos
									$NumMovto++;
								}
								
								if(floatval($v['Credito'])>0)
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT clientes FROM cont_config_pdv WHERE id=1),'Cargo',".number_format($v['Credito'],2,'.','').",'Cliente Pago a Credito','Venta PDV(".$v['idVenta'].")',0,NOW(),'-','1-".$v['idCliente']."'); ";//Guarda Cliente
									$NumMovto++;
								}*/

								if(intval($v['Cuenta'])>0)
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",".$v['Cuenta'].",'Cargo',".number_format($v['monto'],2,'.','').",'".$data['uuid']."','Cliente PDV(".$v['idVenta'].")',1,NOW(),'$factura','1-".$v['idCliente']."'); ";//Guarda Cliente
									$NumMovto++;
								}
								else
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT clientes FROM cont_config_pdv WHERE id=1),'Cargo',".number_format($v['monto'],2,'.','').",'".$data['uuid']."','Cliente PDV(".$v['idVenta'].")',1,NOW(),'$factura','1-".$v['idCliente']."'); ";//Guarda Cliente
									$NumMovto++;	
								}
								
								//TERMINA Medios de pago----------------------------------------

								/*if(floatval($v['Caja'])>0)
								{
									$consulta .= "INSERT INTO cont_movimientos(IdPoliza, NumMovto,IdSegmento,IdSucursal,Cuenta,TipoMovto,Importe,Referencia,Concepto,Activo,FechaCreacion,Factura,Persona) 
													VALUES($Poliza,$NumMovto,1,".$v['idSucursal'].",(SELECT caja FROM cont_config_pdv WHERE id=1),'Abono',".number_format($v['Credito'],2,'.','').",'Caja','Venta PDV(".$v['idVenta'].")',0,NOW(),'-','1-".$v['idCliente']."'); ";//Guarda Caja en caso de haber cambio que supere al efectivo.
									$NumMovto++;
								}*/
								
							}
							$this->dataTransact($consulta);
						}
					}
				}

				//Termina generacion de polizas contables---------------------------------------------------------

				echo 1;
			}
			catch(Exception $e)
			{
				echo 0;
			}
		}

		function getpath($qry) 
		{
			global $xp;
			$prm = array();
			$nodelist = $xp->query($qry);
			foreach ($nodelist as $tmpnode)  
			{
	    		$prm[] = trim($tmpnode->nodeValue);
	    	}
			$ret = (sizeof($prm)<=1) ? $prm[0] : $prm;
			return($ret);
		}

		public function returnData($sql, $reverse = false)
		{
			$data = array();
			$result = $this->query( $sql );
			for ($i=0; $i < $result->num_rows; $i++)
			{ 
				array_push( $data, $result->fetch_array( MYSQLI_ASSOC ) );
			}
			if($reverse)
				$data = array_reverse($data);
			return json_encode($data);
		}

		public function sales( $date )
		{
			$qry  = "SELECT ";
			$qry .= "	COUNT(*) AS ventas ";
			$qry .= "FROM ";
			$qry .= "	venta ";
			$qry .= "WHERE ";
			$qry .= "	fecha > '" . $date . "' ";
			$qry .= "AND idEmpleado = " . $this->user . ";";
			
			$data = $this->returnData( $qry );
			return $data;
		}

		public function getVentas($fecha_inicio,$fecha_fin)
		{
			$qry = "SELECT 
v.idVenta,
v.idCliente, 
(SELECT cuenta FROM comun_cliente WHERE id=v.idCliente) AS Cuenta,
@Efectivo := (SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 1) AS EfectivoAntes,
@Cambio := v.cambio AS Cambio,
@Resultado:=@Efectivo - @Cambio AS EfectivoMenosCambio,
(v.monto-v.montoimpuestos) AS montoMenosImpuestos, 
v.monto,
v.montoimpuestos,
IF (@Resultado<=0,0,@Resultado)  AS Efectivo,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 2) AS Cheques,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 3) AS TarjetaRegalo,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 4) AS TarjetaCredito,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 5) AS TarjetaDebito,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 6) AS Credito,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 7) AS Transferencia,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 8) AS Spei,
(SELECT SUM(monto) FROM venta_pagos WHERE idVenta = v.idVenta AND idFormaPago = 9) AS Otro,
IF (@Resultado<0,@Resultado*-1,0)  AS Caja,
(SELECT folio FROM pvt_respuestaFacturacion WHERE idSale = v.idVenta LIMIT 1) AS Factura,
v.idSucursal,
v.fecha
FROM 	venta v 
WHERE 	fecha BETWEEN '$fecha_inicio' AND '$fecha_fin' 
AND v.estatus=1";
			$data = $this->query( $qry );
			return $data;
		}

		public function ventaProductos($idVenta)
		{
			$qry = "SELECT 
SUM(vp.subtotal) AS SubTotal,
l.idCuenta, 
l.nombre
FROM venta_producto vp
INNER JOIN mrp_producto p ON p.idProducto = vp.idProducto
INNER JOIN mrp_linea l ON l.idLin = p.idLinea
WHERE idVenta = $idVenta
GROUP BY idLin";
			$data = $this->query( $qry );
			return $data;
		}

		public function polizas_por()
		{

			$qry = "SELECT polizas_por FROM cont_config_pdv WHERE id=1 AND conectar != 0";
			$data = $this->query( $qry );
			$data = $data->fetch_assoc();
			return $data['polizas_por'];
		}	

		public function ultimaNumpol($ejercicio,$periodo)
				{
					$myQuery = "SELECT numpol FROM cont_polizas WHERE idperiodo=".intval($periodo)." AND idejercicio = (SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = '$ejercicio') ORDER BY numpol DESC LIMIT 1";
					$numpol = $this->query($myQuery);
					$numpol = $numpol->fetch_assoc();
					return $numpol['numpol'];
				}
		public function poliza($ejercicio,$periodo,$dia,$numpol)
			{
				$dia = explode(' ',$dia);
				$myQuery = "INSERT INTO cont_polizas(idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, referencia, concepto, cargos, abonos, ajuste, fecha, fecha_creacion, activo, eliminado, pdv_aut)
								 			VALUES(1,(SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = $ejercicio),".intval($periodo).",".$numpol.",3,'','Provision Ventas PDV(".$dia[0]."/$periodo/$ejercicio)',0,0,0,'$ejercicio-$periodo-".$dia[0]."',NOW(),0,0,1)";
				$idPoliza = $this->insert_id($myQuery);
				return $idPoliza;
			}
		
	// Regresa un array con el listado de usuarios
		public function listar_usuarios($objet){
			
			$condicion.=(!empty($objet['id']))?' AND idempleado="'.$objet['id'].'"':'';
			$condicion.=(!empty($objet['usuario']))?' AND usuario LIKE "%'.$objet['usuario'].'%"':'';
				
			$sql = "SELECT idempleado, usuario
					FROM accelog_usuarios
					WHERE 1=1".
					$condicion;
					
			$usuarios= $this->returnData($sql);
			
			return $usuarios;
		}
	} ?>