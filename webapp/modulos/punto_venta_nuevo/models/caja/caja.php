<?php
//ini_set('display_errors', 1);
require("models/connection_sqli.php"); // funciones mySQLi

class cajaModel extends Connection {

	public function __construct() {
		session_start();
		cajaModel::simple();
		cajaModel::propina();
		cajaModel::sessiooon();
		//unset($_SESSION["sucursal"]);
		//unset($_SESSION["caja"]);
		//unset($_SESSION["simple"]);
		//$_SESSION["simple"] = true;
	}

	function sessiooon() {
		 if (!isset($_SESSION["sesionid"])) {
			$_SESSION["sesionid"] = session_id();
		 }
	}

	function claseVenta($idProducto,$cantidad){
		include "models/venta.php";
		$venta = new Venta();
	   // $y = $venta->ventaPrueba();
	   $result =  $venta->ventaSencilla($idProducto,$cantidad);
		//echo '['.$y.']';
	   return $result;
	}

	function formasDePago() {
		$selct = "SELECT * from forma_pago where claveSat='01' or claveSat='02' or claveSat='03'  or claveSat='04'  or claveSat='05'  or claveSat='06' or claveSat='08' or claveSat='28' or claveSat='29' or claveSat='99' or claveSat='NA'";
		$res = $this->queryArray($selct);
		return $res['rows'];
	}

	function verificainicioCaja() {
		$empleado = "Select nombre from empleados where idEmpleado = " . $_SESSION['accelog_idempleado'];
		$empleadoResult = $this->queryArray($empleado);
		$_SESSION["nombreEmpleado"] = $empleadoResult["rows"][0]["nombre"];

		if (!isset($_SESSION["sucursal"])) {
			$qry = "SELECT ";
			$qry .= "   au.idSuc ";
			$qry .= " ,mp.nombre ";
			$qry .= "FROM ";
			$qry .= "   administracion_usuarios au ";
			$qry .= "   INNER JOIN mrp_sucursal mp ON mp.idSuc = au.idSuc ";
			$qry .= "WHERE ";
			$qry .= "   au.idempleado = " . $_SESSION['accelog_idempleado'] . " ";
			$qry .= "LIMIT 1;";

			$q = $this->queryArray($qry);

			if ($q["total"] > 0) {

				foreach ($q["rows"] as $key => $value) {
					$_SESSION["sucursal"] = $value["idSuc"];
					$_SESSION["sucursalNombre"] = $value["nombre"];
				}
			} else {
				$_SESSION["sucursal"] = 1;
				$_SESSION["sucursalNombre"] = "Sucursal";
			}
		}

		$qry2 = "SELECT ";
		$qry2 .= "id ";
		$qry2 .= "FROM ";
		$qry2 .= "inicio_caja ";
		$qry2 .= "WHERE ";
		$qry2 .= "idSucursal = " . $_SESSION['sucursal'] . " ";
		$qry2 .= "AND idUsuario = " . $_SESSION['accelog_idempleado'] . " and idcorteCaja is null ";
		$qry2 .= "ORDER BY ";
		$qry2 .= "id desc ";
		$qry2 .= "LIMIT 1;";

		$q = $this->queryArray($qry2);

		if ($q["total"] > 0) {
			foreach ($q["rows"] as $key => $value) {
				if (is_numeric($value["idCortecaja"])) {//Selecciona el corte y si ese dato no es nulo ese inicio esta cerrado
					return "false";
				} else {
					return "true"; // si el registro traido es nulo envia 0
				}
			}
		} else {// Si ni siquiera tiene registro de inicio de caja
			return "false";
		}
	}

	function iniciocaja() {

		$queryUsuarios = "select au.idSuc,mp.nombre from administracion_usuarios au,mrp_sucursal mp where mp.idSuc=au.idSuc and au.idempleado=" . $_SESSION['accelog_idempleado'];

		$queryUsuarios = $this->queryArray($queryUsuarios);

		if ($queryUsuarios["total"] > 0) {

			$sucursal_operando = $queryUsuarios["rows"][0]["nombre"];
			$sucursal_id = $queryUsuarios["rows"][0]["idSuc"];

			//var_dump("select  cc.saldofinalcaja from inicio_caja i inner join corte_caja cc on i.idCortecaja=cc.idCortecaja where i.idSucursal=".$sucursal_id." order by i.fecha desc limit 1");

			$queryInicioCaja = "select  cc.saldofinalcaja from inicio_caja i inner join corte_caja cc on i.idCortecaja=cc.idCortecaja where i.idSucursal=" . $sucursal_id . " order by i.fecha desc limit 1";

			$queryInicioCaja = $this->queryArray($queryInicioCaja);

			if ($queryInicioCaja["total"] > 0) {
				$saldoencaja = "$" . number_format($queryInicioCaja["rows"][0]["saldofinalcaja"], 2, ".", ",");
			} else {
				$saldoencaja = "$0.00";
			}

			return array("status" => 1, "sucursalNombre" => $sucursal_operando, "sucursalId" => $sucursal_id, "saldo" => $saldoencaja);
		} else {

			$cbo = '<select id="sucursal" name="sucursal" onchange="cargasaldocaja(this.value);" >';
			$query = "select idSuc id,nombre  from mrp_sucursal";

			$query = $this->queryArray($query);

			return array("status" => 2, "rows" => $query["rows"]);
		}
	}
	////Cambia Estatus de un pedido a vendido
	function estatusPedido($idPedido,$idVenta){
		$query1 = 'UPDATE cotpe_pedido set status=5, idVenta="'.$idVenta.'" where id='.$idPedido; 
		$res1 = $this->queryArray($query1);

		return array('resp' => true );
	  
	}
	////////////////////////////////////////
	function Iniciarcaja($sucursal, $monto) {
		date_default_timezone_set("Mexico/General");
		$fechaactual = date("Y-m-d H:i:s");
		$_SESSION['sucursal'] = $sucursal;

		$insertInicioCaja = "Insert into inicio_caja(id,fecha,monto,idUsuario,idCortecaja,idSucursal) values('','" . $fechaactual . "','" . $monto . "'," . $_SESSION['accelog_idempleado'] . ",NULL," . $sucursal . ")";

		$resultInsert = $this->queryArray($insertInicioCaja);

		$query = "select  s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen from mrp_sucursal s, almacen a where s.idAlmacen=a.idAlmacen and s.idSuc=" . $sucursal;

		$resultQuery = $this->queryArray($query);

		$id = $resultQuery["rows"][0]["idSuc"];
		$sucursal = $resultQuery["rows"][0]["sucursal"];
		$idAlmacen = $resultQuery["rows"][0]["idAlmacen"];

		return '<input type="hidden" id="caja-sucursal" value="' . $idSuc . '"><input type="hidden" id="caja-almacen" value="' . $idAlmacen . '">Sucursal:' . $sucursal;
	}

	function pintaRegistros() {
		//unset($_SESSION['caja']);
		//unset($_SESSION['pagos-caja']);
		//Consultamos si hay ventas suspendidas
		$suspendidas = '';
		$dataInicio = false;
		$selectVentasSuspendidas = "Select id,identi from venta_suspendida ";
		$selectVentasSuspendidas .= " where borrado = 0";

		$resultVentasSuspendidas = $this->queryArray($selectVentasSuspendidas);
		if ($resultVentasSuspendidas["total"] > 0) {
			$suspendidas = $resultVentasSuspendidas["rows"];
		}

		$verificaInicio = $this->verificainicioCaja();
		if ($verificaInicio == 'false') {
			$dataInicio = $this->iniciocaja();
		}

		if (isset($_SESSION['caja'])) {
			return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "simple" => $_SESSION["simple"], "suspendidas" => $suspendidas, "inicio" => $dataInicio, "sucursal" => $_SESSION["sucursalNombre"], "empleado" => $_SESSION["nombreEmpleado"]);
		} else {
			return array('status' => false, "suspendidas" => $suspendidas, "inicio" => $dataInicio);
		}
	}

	function simple() {
		/*
		  Verificamos si la caja debe descontar o no de inventario por medio de una consulta.
		  el resultado se sube a sesion..
		 */
		  if (!isset($_SESSION["simple"])) {
			$simple = "select a.idperfil from accelog_perfiles_me a where idmenu=1259";

			$resultSimple = $this->queryArray($simple);

			if ($resultSimple["total"] == 0) {
				$_SESSION["simple"] = false;
			} else {
				$_SESSION["simple"] = true;
			}
		}
	}

	//Validamos que este activo el menu propina
	function propina() {

		if (!isset($_SESSION["propina"])) {
			$q = "select a.idperfil from accelog_perfiles_me a where idmenu=1601";
			$resultPropina = $this->queryArray($q);

			if ($resultPropina["total"] > 0) {
				$_SESSION["propina"] = true;
			} else {
				$_SESSION["propina"] = false;
			}
		}
	}

	function buscaClientes($term) {
		/*obtiene los clientes*/
		$queryClientes = "SELECT  id,nombre ";
		$queryClientes .= " FROM comun_cliente ";
		$queryClientes .= " WHERE nombre like '%" . $term . "%' order by nombre desc ";

		$result = $this->queryArray($queryClientes);

		return $result["rows"];

	}

	function checatimbres() {
		/*  $contadorfacturas = "SELECT total FROM pvt_contadorFacturas";
		$facturasTotales = $this->queryArray($contadorfacturas);*/
//      $licfac = "SELECT valor from comun_parametros_licencias where parametro='Facturas'";
//      $facslic = $this->queryArray($licfac);

//		if($facturasTotales["rows"][0]["total"] > $facslic["rows"][0]["valor"]){
//      	if($facslic["rows"][0]["valor"]==0){
//          	return 1;
//          }else{
				return 0;
//          }
		/*  $sqlTimbres = "select total from pvt_contadorFacturas";
		$result = $this->queryArray($sqlTimbres);

		if ($result["total"] > 99) {
			return 1;
		} else {
			return 0;
		} */
	}

	function cargaRfcs($idCliente) {

		$queryRfc = "select id , rfc from comun_facturacion where nombre=" . $idCliente;

		$result = $this->queryArray($queryRfc);
		//inadem
		/*$queryInadem = "SELECT *  from comun_cliente_inadem where idCliente=".$idCliente;
		$result1= $this->queryArray($queryInadem); 
		//print_r($result1);
		$inademFlag = '0';
		$vitrinasnum = 0;
		if($result1['total']>0){
			$inademFlag = '1';
			unset($_SESSION['caja']);
			if($result1['rows'][0]['vitrina']=='1100' || $result1['rows'][0]['vitrina']==1100){
				$productos = array("CONV51", "511", "5121100", "5131100","5141100");
				//$productos = array("CONV51", "511");
				$vitrinasnum = 1;
			}
			if($result1['rows'][0]['vitrina']=='1101' || $result1['rows'][0]['vitrina']==1101){
				$productos = array("CONV51", "511","5121101","5131101","5141101");
				//$productos = array("CONV51", "511");
				$vitrinasnum = 2;
			}
			if($result1['rows'][0]['vitrina']=='1102' || $result1['rows'][0]['vitrina']==1102){
				$productos = array("CONV51", "511","5121102","5131102","5141102");
				//$productos = array("CONV51", "511");
				$vitrinasnum = 3;
			}
		  /*  if($result1['rows'][0]['vitrina']=='1101' || $result1['rows'][0]['vitrina']==1101){
				 $productos = array("CONV51", "511","5121101","5131101","5141101");
			} */

		 //  $productos = array("CONV51", "511", "512", "513","514");
			  /*  $this->query('UPDATE mrp_producto set nombre="SOLUCION:'.$result1['rows'][0]['vitrina'].';VALE:'.$result1['rows'][0]['cupon'].';FOLIO:'.$result1['rows'][0]['folio_inadem'].'" where idProducto=87');

				foreach ($productos as $value) {
					$x = $this->agregaProducto($value, 1);
				}
				switch ($vitrinasnum) {
					case 1:
						$_SESSION['caja']['cargos']['subtotal'] = $_SESSION['caja']['cargos']['subtotal'] - 0.01;
						$_SESSION['caja']['cargos']['total'] = $_SESSION['caja']['cargos']['total'] - 0.01;
						break;
					case 2:
						$_SESSION['caja']['cargos']['subtotal'] = $_SESSION['caja']['cargos']['subtotal'] - 0.01;
						$_SESSION['caja']['cargos']['total'] = $_SESSION['caja']['cargos']['total'] - 0.01;
					break;
					case 3:
						$_SESSION['caja']['cargos']['subtotal'] = $_SESSION['caja']['cargos']['subtotal'] - 0.01;
						$_SESSION['caja']['cargos']['total'] = $_SESSION['caja']['cargos']['total'] - 0.01;
					break;
				}
				$y = $this->agregaPago(9,'-No Identificado-',$_SESSION["caja"]["cargos"]["total"],'');
			   // var_dump($x);
			   // print_r($_SESSION['caja']);
		}   
		//exit(); */

		if ($result["total"] > 0) {
			//return array("status" => true, "rfc" => $result["rows"], "inadem" => $inademFlag);
			return array("status" => true, "rfc" => $result["rows"]);
		} else {
			return array("status" => false);
		}
	}

	function buscaProductos($term) {
		$return = array();

		$verificaInicio = $this->verificainicioCaja();
		if ($verificaInicio == "false") {
			return array("status" => false, "inicio" => $this->iniciocaja());
		}

		if (!isset($_SESSION['almacen'])) {
			$strSql = " SELECT au.idSuc,mp.nombre ";
			$strSql .= " FROM administracion_usuarios au,mrp_sucursal mp ";
			$strSql .= " WHERE mp.idSuc=au.idSuc AND au.idempleado=" . $_SESSION['accelog_idempleado'];

			$q = $this->queryArray($strSql);

			if ($q["total"] > 0) {
				$_SESSION["sucursal"] = $q["rows"][0]["idSuc"];
			} else {
				$_SESSION["sucursal"] = 1;
			}



			$strSql = "SELECT s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen ";
			$strSql .= " FROM mrp_sucursal s, almacen a ";
			$strSql .= " WHERE s.idAlmacen=a.idAlmacen AND s.idSuc=" . $_SESSION["sucursal"];

			$qsuc = $this->queryArray($strSql);

			if ($q["total"] > 0) {
				$_SESSION["almacen"] = $qsuc["rows"][0]['idAlmacen'];
			} else {
				$_SESSION["almacen"] = 1;
			}
		}


		if ($_SESSION["simple"] == true) {
			$strSql = "select  mrp_producto.tipo_producto,mrp_producto.idProducto id,mrp_producto.nombre,mrp_producto.idunidad,mrp_producto.codigo,mrp_producto.idunidadCompra,mrp_producto.idunidadCompra,mrp_unidades.idUni,mrp_unidades.conversion,mrp_stock.ocupados,
			CASE WHEN mrp_stock.cantidad  IS NOT NULL
			THEN mrp_stock.cantidad-if(SUM(mrp_devoluciones_reporte.nDevoluciones) is null,0,SUM(mrp_devoluciones_reporte.nDevoluciones))
			ELSE 0 END AS cantidad";
			$strSql .= " from  mrp_producto left join mrp_stock  on mrp_producto.idProducto=mrp_stock.idProducto
			left join mrp_devoluciones_reporte on mrp_devoluciones_reporte.idProducto=mrp_stock.idProducto and mrp_devoluciones_reporte.idProveedor=mrp_producto.idProveedor and mrp_devoluciones_reporte.idAlmacen=mrp_stock.idAlmacen and mrp_devoluciones_reporte.estatus=0
			left join mrp_unidades on mrp_producto.idunidad=mrp_unidades.idUni ";
			$strSql .= "where (nombre like '%$term%' or mrp_producto.idProducto like '%$term%' or mrp_producto.codigo like '%$term%') and vendible=1 and tipo_producto != 5 and mrp_stock.idAlmacen=" . $_SESSION["almacen"] . " AND mrp_producto.estatus=1 
			group by mrp_producto.idProducto order by nombre limit 10";
		} else {
			$strSql = "select  mrp_producto.tipo_producto,mrp_producto.idProducto id,mrp_producto.nombre,mrp_producto.codigo"
			. " from mrp_producto "
			. "where (nombre like '%$term%' or mrp_producto.idProducto like '%$term%' or mrp_producto.codigo like '%$term%') "
			. "and vendible=1 and tipo_producto != 5 and mrp_producto.estatus=1 "
					//. "and mrp_stock.idAlmacen=" . $_SESSION["almacen"] . " "
			. "group by mrp_producto.idProducto order by nombre limit 10";
		}
		//echo $strSql;
		$query = $this->queryArray($strSql);

		foreach ($query["rows"] as $key => $value) {
			$cantidad = 0;
			if ($_SESSION["simple"] == true && $value['tipo_producto'] != 6) {
				if (isset($_SESSION["caja"][$value["id"]])) {
					$cantidad = $_SESSION["caja"][$value["id"]]->cantidad;
				}

				if ($value['conversion'] == "") {
					$value['conversion'] = 1;
				}

				//echo "(".$value['conversion'].")";
			/*    echo "<pre>";
				echo $value['cantidad']."</br>";
				echo $value['conversion']."</br>";
				echo $cantidad."</br>";
				echo $value["ocupados"]."</br>";
				echo "------------------------------";
				echo "</pre>"; */
			  //  array_push($return, array('id' => $value["codigo"], 'label' => $value['codigo'] . " / " . $value['nombre'] . ":" . number_format(($value['cantidad'] - ($value['conversion'] * $cantidad) - $value["ocupados"]), 2)));
				array_push($return, array('id' => $value["codigo"], 'label' => $value['codigo'] . " / " . $value['nombre'] . ":" . number_format(($value['cantidad'] - $cantidad - $value["ocupados"]), 2)));
			} else {
				array_push($return, array('id' => $value["codigo"], 'label' => $value['codigo'] . " / " . $value['nombre']));
			}
		}

		return $return;
	}

	function agregaProducto($idArticulo, $cantidadInicial) {
		/*print_r($_SESSION['caja']);
		exit(); */
		$_SESSION['mesa'] = 0;
		try {
			$selectId = "Select idProducto,codigo ";
			$selectId .= " from mrp_producto ";
			//$selectId .= " where strcmp(idProducto,'" . $idArticulo . "')=0 or strcmp(codigo,'" . $idArticulo . "')=0";
			$selectId .= " where codigo='".$idArticulo."'";
			$resultselectId = $this->queryArray($selectId);
			$idArticulo = $resultselectId["rows"][0]["idProducto"];
			$codigo = $resultselectId["rows"][0]["codigo"];

			/*
			En esta funcion buscamos toda la informacion del producto que se selecciona en el autocomplete,y despues
			hacemos los calculos de impuestos para despues subir el producto a la caja que se encuentra a session y devolverlo a la vista.
			*/
			$comanda = false;
			$options = array();

			if (!isset($_SESSION['almacen'])) {
				$strSql = " SELECT au.idSuc,mp.nombre ";
				$strSql .= " FROM administracion_usuarios au,mrp_sucursal mp ";
				$strSql .= " WHERE mp.idSuc=au.idSuc AND au.idempleado=" . $_SESSION['accelog_idempleado'];
				$q = $this->queryArray($strSql);

				if ($q["total"] > 0) {
					$_SESSION["sucursal"] = $q["rows"][0]["idSuc"];
				} else {
					$_SESSION["sucursal"] = 1;
				}

				$strSql = "SELECT s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen ";
				$strSql .= " FROM mrp_sucursal s, almacen a ";
				$strSql .= " WHERE s.idAlmacen=a.idAlmacen AND s.idSuc=" . $_SESSION["sucursal"];
				$qsuc = $this->queryArray($strSql);

				if ($q["total"] > 0) {
					$_SESSION["almacen"] = $qsuc["rows"][0]['idAlmacen'];
				} else {
					$_SESSION["almacen"] = 1;
				}
			}

			/* $selectUnidadesPeso = "Select identificadores from unid_generica where tipo = 'Peso'";
			$resultUnidades = $this->queryArray($selectUnidadesPeso); */

			$tipo = "Select nombre from mrp_producto where strcmp(nombre,'" . $idArticulo . "')=0 OR  strcmp(codigo,'" . $idArticulo . "')=0 OR  strcmp(idProducto,'" . $idArticulo . "')=0 ";
			$tipo = $this->queryArray($tipo);
			$comanda = false;
			$pos = strpos($tipo["rows"][0]["nombre"], "comanda");

			if ($pos !== false) {
				$comanda = true;
			}

			if ($comanda == true || !is_numeric($idArticulo)) {
				/*
				Comprobamos que el articulo sea o no  una comanda si lo es consultamos los productos de la comanda
				y los agregamos a la caja.
				*/
				$rows = array();
				$idArticulo = strtoupper($idArticulo);
				$idArticulo2=$idArticulo;
				$pos = strpos($idArticulo, "COM");
				if ($pos !== false || $comanda == true) {
					$_SESSION['mesa'] = 0;
					//Obtenermos el codigo de la comanda porque es $idArticulo es el id no el codigo
					$sqlCodigoComanda = "select codigo from mrp_producto where idProducto=".$idArticulo;
					$CodigoComanda = $this->queryArray($sqlCodigoComanda);
					$idArticulo = $CodigoComanda["rows"][0]["codigo"];
					$comanda = true;
					$individual = strpos($idArticulo, "P");
					
					$sqlMesa = "SELECT id,idmesa from com_comandas where codigo='".$CodigoComanda["rows"][0]["codigo"]."'";
					$idMesa = $this->queryArray($sqlMesa);

					$_SESSION['mesa'] = $idMesa["rows"][0]["idmesa"];
					// var_dump($_SESSION['mesa']);
					$idComanda = $idMesa["rows"][0]["id"];

					$queryadicionales ='SELECT
						b.idProducto,a.npersona, SUM(a.cantidad) cantidad, b.nombre, 
						(select ROUND(if(SUM(e.valor) is null,b.precioventa,
						((SUM(e.valor)/100)*b.precioventa)+b.precioventa),2) precioventa FROM 
						producto_impuesto e where e.idProducto=b.idProducto) precioventa, 
						a.opcionales, a.adicionales, a.normales, c.tipo, c.nombre nombreu, c.domicilio, d.codigo 
					FROM 
						com_pedidos a 
					INNER JOIN 
							mrp_producto b 
						ON 
							b.idProducto=a.idproducto 
					LEFT JOIN 
							com_comandas d 
						ON 
							d.id='.$idComanda.' 
					LEFT JOIN 
							com_mesas c 
						ON 
							c.id_mesa=d.idmesa 
					WHERE 
						idcomanda='.$idComanda.'
					GROUP BY 
						a.npersona, a.idProducto, a.opcionales, a.adicionales order by a.npersona asc';
					//
					//echo $queryadicionales;

					$queryadicionales = "SELECT * from com_pedidos where idcomanda=".$idComanda. " order by adicionales asc";
					$adicionales = $this->queryArray($queryadicionales);
					//print_r($adicionales['rows']);
					
					$repetido = 0;
					$repetido2 = array();
					$adicionales2=$adicionales['rows'];
					foreach ($adicionales['rows'] as $key => $value) {
						$repetido = 0;
						if($value['idproducto']!=0){
							foreach ($adicionales2 as $key2 => $value2) {
								if($value['idproducto']==$value2['idproducto']){
									$repetido++;
									if($repetido>1){
										// $repetido2 .=$value['idproducto'].'-'; 
										$repetido2[]=$value['idproducto'];
										//array_push($repetido2, $value['idproducto']);
									}
								}
							}
						}
					}

					/*print_r($repetido2);
					foreach ($repetido2 as $keyx => $valuex) {
						if($valuex==4956){
							unset($repetido2[$keyx]);
						}
					}
					print_r($repetido2); */
					$prodCom = array();
					$prodAdi = array();
					//print_r($adicionales['rows']);
					//echo '<br>';
					//exit();
					$anterior = '';

					foreach ($adicionales['rows'] as $key => $value) {
						/*if($value['idproducto']==4886){
							unset($prodAdi);
						} */

						//echo '------------------------------------------ <br>';
						//print_r($prodAdi);
						//echo 'Producto comanda ['.$value['idproducto'].'] <br>';
						if (in_array($value['idproducto'], $repetido2)) {
							//       echo 'SI esta en repetido '.$value['idproducto'].'<br>';
							//unset($prodAdi);
							if($value['idproducto']!=$anterior){
								unset($prodAdi);
							}
						}else{
							unset($prodAdi);
						}
						//echo 'prod='.$value['idProducto'].'('.$value['adicionales'].')';
						if($value['idproducto']!=0){
							//echo $value['idproducto'].'X';
							// echo $value['adicionales'].'//';
							$extra = explode(",", $value['adicionales']);
							foreach ($extra as $key2 => $value2) {
								if($value2!=''){
									$nombreExtraQuery = "SELECT * from mrp_producto where idProducto=".$value2;
									$ResultnombreExtra = $this->queryArray($nombreExtraQuery);
									$nombreExtra = $ResultnombreExtra['rows'][0]['nombre'];
									$costoExtra += $ResultnombreExtra['rows'][0]['precioventa'];
									$prodAdi[]=$ResultnombreExtra['rows'][0]['idProducto'];
								}
							}
							//print_r($prodAdi);
							//echo '<br>';
							$prodCom[$value['idproducto']] =$prodAdi;
							//unset($prodAdi); 
							if (in_array($value['idproducto'], $prodCom)) {
								//   echo 'si'.$value['idproducto'];
								unset($prodAdi);
							}else{
								//unset($prodAdi);
							}

							$anterior = $value['idproducto'];
							//echo '..................................... <br>';
							/*if($repetido2!=''){
							}else{
								unset($prodAdi);
							} */
						}
						//unset($prodAdi);
					}
					/*echo 'fin';
					print_r($prodAdi);
					print_r($prodCom);
					echo 'Extra='.$costoExtra; 
					//exit(); */
					/*
					Validamos que la comanda sea pago completo o por persona, si el primer caracter es '#', la comanda se paga por persona.
					*/

					if ($individual !== false) {
						$arrayCodigo = explode("P", $idArticulo);
						$idArticulo = $idArticulo2;
						$codigo = "COM" . $arrayCodigo[0];

						$comandastr = 'COM' . substr($codigo, 0, 5);
						$persona = round(substr($codigo, -2));

						$wherePersona = " AND npersona = " . $arrayCodigo[1] . " ";
						$wherePersona2 = " npersona = " . $arrayCodigo[1] . " ";
						$person = $arrayCodigo[1];
					} else {
						$wherePersona = " ";
					}

					$sqlExistencia = "select idUnidad,idProducto id,nombre,codigo,precioventa,imagen,mrp_producto.tipo_producto  from mrp_producto where strcmp(nombre,'" . $idArticulo . "')=0 OR  strcmp(codigo,'" . $idArticulo . "')=0 OR  strcmp(idProducto,'" . $idArticulo . "')=0  and mrp_producto.estatus = 1 ";
					$resultExistencia = $this->queryArray($sqlExistencia);

					if ($resultExistencia["total"] < 1) {
						throw new Exception("No existe una comanda con ese codigo.", 1);
					}

					$idProductos = "Select mp.imagen,p.idProducto,sum(cantidad) cantidad,mp.idUnidad,mp.nombre,mp.precioventa,mp.tipo_producto,mp.esreceta,mp.eskit,(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM mrp_unidades u where  u.idUni=mp.idunidad)unidad";
					//$idProductos = "Select mp.imagen,p.idProducto,(select sum(cantidad) from com_pedidos where ".$wherePersona2." )cantidad,mp.idUnidad,mp.nombre,mp.precioventa,mp.tipo_producto,mp.esreceta,mp.eskit,(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM mrp_unidades u where  u.idUni=mp.idunidad)unidad";

					$idProductos .= " FROM com_pedidos p,com_comandas c ,mrp_producto mp ";
					$idProductos .= " WHERE c.codigo = '".$codigo."'";
					$idProductos .= " AND p.idProducto != 0 ";
					$idProductos .= " AND c.id=p.idcomanda ";
					$idProductos .= " AND p.idProducto=mp.idProducto ";
					$idProductos .= $wherePersona . " and mp.estatus = 1 ";
					$idProductos .= " group by mp.idProducto ";
					//echo $idProductos;
					$result = $this->queryArray($idProductos);

					$copyArray=$result['rows'];
					$flag = 1;
					foreach ($copyArray as $keyx => $valuex) {
						foreach ($prodCom as $keyf => $valuef) {
							//echo 'key='.$keyf;
							if($keyf==$valuex['idProducto']){
								foreach ($valuef as $key3 => $value3) {
									//echo 'adiciona='.$value3;
									$queryAd = "SELECT p.imagen,p.idProducto,1 as cantidad,p.idunidad as idUnidad,p.nombre,p.precioventa,p.tipo_producto,p.esreceta,p.eskit,u.compuesto as unidad";
									$queryAd .=" from mrp_producto p, mrp_unidades u ";
									$queryAd .="where p.idunidad=u.idUni and p.idProducto=".$value3;
									$resultAd = $this->queryArray($queryAd);
									$resultAd['rows'][0]['cantidad']=1;
									//array_push($result['rows'], $resultAd['rows'][0]);
									array_splice($result['rows'], $keyx+$flag, 0, $resultAd['rows']);
								   // print_r($result['rows']);
								   //echo 'XXXXXX';
								}
							}
							$flag++;
						}
					}
					//array_splice($result['rows'], 1, 0, $resultAd['rows'][0]);
					/*print_r($result['rows']);
					exit(); */
					if ($result["total"] == 0) {
						throw new Exception("No se encontraron productos en la comanda.", 1);
					}
					$options[3] = $codigo;
					
					/*
					Buscamos si esta configurado algun producto para funcionar como propina
					*/
					$queryProductoPropina = " Select idproducto from com_productos_propina";
					$propina = $this->queryArray($queryProductoPropina);
					
					if ($propina["total"] > 0) {
						$options[4] = $propina["rows"][0]["idproducto"];
					}

					// Guarda el ID de la comanda
					session_start();
					$_SESSION['id_comanda']=$idComanda;
				} else {
					$queryn = "SELECT mp.idunidad,mp.deslarga,mp.tipo_producto,mp.imagen,mp.idProducto id,mp.codigo,mp.nombre,mp.precioventa,mp.idUnidad,mp.esreceta,mp.eskit,(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM
						mrp_unidades u where  u.idUni=mp.idunidad)unidad ";
					$queryn .= " FROM mrp_producto mp ";
					$queryn .= " where strcmp(mp.codigo,'" . $idArticulo . "')=0 OR  strcmp(mp.idProducto,'" . $idArticulo . "')=0";
					$queryn .= " and vendible=1 and mp.estatus = 1";

					// echo "aqui";
					$result = $this->queryArray($queryn);
					if ($result["total"] == 0) {
						throw new Exception("No existe un articulo con esa descripción o codigo", 1);
					}

					$result["rows"][0]["idProducto"] = $idArticulo;
					//$result["rows"][0]["codigo"] = $idArticulo;
				}
			} else {
				if ($idArticulo != 0) {
					$queryn = "SELECT mp.estatus,mp.idunidad,mp.deslarga,mp.tipo_producto,mp.imagen,mp.idProducto id,mp.codigo,mp.nombre,mp.precioventa,mp.idUnidad,mp.esreceta,mp.eskit,mp.tipo_producto,(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM
						mrp_unidades u where  u.idUni=mp.idunidad)unidad ";
					$queryn .= " FROM mrp_producto mp ";
					$queryn .= " where (strcmp(mp.codigo,'" . $idArticulo . "')=0 OR  strcmp(mp.idProducto,'" . $idArticulo . "')=0)";
					$queryn .= " and vendible=1  and mp.estatus = 1";

					//echo 'aca';
					$result = $this->queryArray($queryn);
					if ($result["total"] == 0) {
						throw new Exception("No existe un articulo con esa descripción o codigo", 1);
					}
					$result["rows"][0]["idProducto"] = $idArticulo;
				}
			}

			/*
			Subimos la informacion a sesion por cada producto ya sea indivudual o todos los de la comanda
			*/
			//unset($_SESSION['caja']);
			$cantidad = 0;

			if (isset($_SESSION["caja"][$idArticulo])) {
				$cantidad = $_SESSION["caja"][$idArticulo]->cantidad;
			}
			/*print_r($result["rows"]);
			echo 'perooo';
			exit();*/ 
			//echo 'juan';
			foreach ($result["rows"] as $key => $value) {
				$selectProduccion = " select cantidad from mrp_detalle_orden_produccion where idProducto=" .$value["id"];
				$resultProduccion = $this->queryArray($selectProduccion);
				//echo $resultProduccion["rows"][0]["cantidad"].'de';
				//exit();
				if ($value["tipo_producto"] == 4 || ($value["tipo_producto"] == 2 && $resultProduccion["rows"][0]["cantidad"]=='')) {
					//echo 'si entroe al if ihdeffh';
					/*
					Si el producto es kit verficamos que tengamos existencia de los materiales que lo componen
					*/

					$queryMateriales = "select mp.imagen,mpm.idMaterial,mpm.cantidad,mp.deslarga,mp.tipo_producto,";
					$queryMateriales .= "(select (case mp.idunidad when 0 then 0 else u.compuesto  end) FROM ";
					$queryMateriales .= "mrp_unidades u where  u.idUni=mp.idunidad)unidad ";
					$queryMateriales .= " from mrp_producto_material mpm,mrp_producto mp ";
					$queryMateriales .= " where mp.idProducto=mpm.idProducto and (mp.tipo_producto=2 or mp.tipo_producto=4) and mpm.idProducto=" . $value["id"] . " and mp.estatus = 1	";
					//echo $queryMateriales;
					$materilaes = $this->queryArray($queryMateriales);
					//echo $producto->id.'x';
					$selectProduccion = " select cantidad from mrp_detalle_orden_produccion where idProducto=" .$value["id"];
					//echo $selectMaterial;
					//echo $selectProduccion;

					$resultProduccion = $this->queryArray($selectProduccion);
					// echo $resultProduccion["rows"][0]["cantidad"].'dededede';
					//exit();
					if ($materilaes["total"] > 0 && $resultProduccion["rows"][0]["cantidad"]=='') {
						//Recorremos los materiles del kit
						foreach ($materilaes["rows"] as $key => $materialValue) {
							if ($_SESSION["simple"] == true && $result["rows"][0]["tipo_producto"] != 6) {
								$queryExistencia = "select s.cantidad,s.ocupados ";
								$queryExistencia .= "from mrp_stock s ";
								$queryExistencia .= "where idAlmacen=" . $_SESSION["almacen"] . " and idProducto=" . $materialValue["idMaterial"];
								$resultqueryExistencia = $this->queryArray($queryExistencia);

								//if (((float) $materialValue["cantidad"] > (float) (($resultqueryExistencia["rows"][0]["cantidad"] - $cantidad) - $resultqueryExistencia["rows"][0]["ocupados"])) && ($materialValue["cantidad"] <= $cantidadInicial) || ((float) $resultqueryExistencia["rows"][0]["cantidad"] < (float) $cantidadInicial)) {
								if (((float) ($materialValue["cantidad"]*$cantidadInicial) > (float) (($resultqueryExistencia["rows"][0]["cantidad"] - $cantidad) - $resultqueryExistencia["rows"][0]["ocupados"])) || ((float) $resultqueryExistencia["rows"][0]["cantidad"] < (float) $cantidadInicial)) {
									if ($comanda == FALSE) {
										throw new Exception("No hay suficientes materiales para ese producto.", 1);
									}
								}
							}
							$result["rows"][0]["cantidad"] = 1;
							$arrImpMateriales .= json_encode(array('idMaterial' => $materialValue["idMaterial"], "cantidad" => $materialValue["cantidad"]));
						}
					}
				} else {
					/*
					Si no es kit verificamos existencias tomando en cuenta las devoluciones.
					*/

					if ($value["tipo_producto"] != 6) {
						if ($value["id"] != '') {
							$idP = $value["id"];
						}

						if ($value["idProducto"] != '') {
							$idP = $value["idProducto"];
						}

						$existencias = "select s.cantidad-if(SUM(o.nDevoluciones) is null,0,SUM(o.nDevoluciones)) cantidad,ocupados ";
						$existencias .= " from mrp_stock s left join mrp_devoluciones_reporte o on o.idProducto=s.idProducto and o.idAlmacen=s.idAlmacen and o.estatus=0  ";
						$existencias .= " where s.idAlmacen=" . $_SESSION["almacen"] . " and s.idProducto=" . $idP;

						$resultExistencias = $this->queryArray($existencias);

						/*  echo "(" . $resultExistencias["rows"][0]["cantidad"] . ")";
						echo "Cantidad -> (" . $cantidad . ")";
						echo "Ocupados -> (" . $resultExistencias["rows"][0]["ocupados"] . ")";
						echo "Cantidad Inicial -> (" . $cantidadInicial . ")"; */

						if ((float) (($resultExistencias["rows"][0]["cantidad"] - $cantidad) - $resultExistencias["rows"][0]["ocupados"]) > 0 && (float) $resultExistencias["rows"][0]["cantidad"] < 1 && (float) $resultExistencias["rows"][0]["cantidad"] >= $cantidadInicial) {
							$result["rows"][0]["cantidad"] = (float) $resultExistencias["rows"][0]["cantidad"];
						} else if ((float) (($resultExistencias["rows"][0]["cantidad"] - $cantidad) - $resultExistencias["rows"][0]["ocupados"]) >= 1 && (float) $resultExistencias["rows"][0]["cantidad"] >= $cantidadInicial) {
							$result["rows"][0]["cantidad"] = 1;
						} else {
							if ($_SESSION["simple"] == true) {
								throw new Exception("No hay suficientes materiales para ese producto..", 1);
							} else {
								$result["rows"][0]["cantidad"] = 1;
							}
						}
					} else {
						$result["rows"][0]["cantidad"] = 1;
					}
				}

				/*
				Si es una comanda necesitamos traer la informacion de cada producto, de uno por uno..
				y despues subirlo a sesion de lo contrario solo subimos la informacion a session por que ya
				traemos la informacion del producto.
				*/

				if ($comanda) {
					if($person != ''){
						$condicion = ' and npersona='.$person;
					}

					$datosProducto = 'Select mrp.idunidad,mrp.tipo_producto,mrp.deslarga,mrp.imagen,mrp.codigo,mrp.nombre,mrp.precioventa,mrp.esreceta,mrp.eskit,mrp.idunidad,if(sum(p.cantidad)>0,sum(p.cantidad),"1") cantidad ';
					$datosProducto .= ' from mrp_producto mrp,com_pedidos p,com_comandas c';
					$datosProducto .= ' where mrp.idProducto = ' . $value["idProducto"] . ' and c.codigo = \'' . $options[3] . '\' ';
					$datosProducto .= ' and p.idProducto=mrp.idProducto  and p.idcomanda = c.id and estatus = 1'.$condicion;
					//  echo '['.$datosProducto.']';
					$datosProductoResult = $this->queryArray($datosProducto);
					$value["precioventa"] = $datosProductoResult["rows"][0]["precioventa"];
				} else {
					$datosProductoResult = $result;
				}

				//print_r($datosProductoResult);
				//Agregamos los impuestos a un array para despues subirlos a sesion
				if (isset($resultImpuestos["rows"][0]["precioventa"])) {
					$arrImpProducto = json_encode(array('idProducto' => $value["idProducto"], 'precioVenta' => $resultImpuestos["rows"][0]["precioventa"]));
				}

				//cantidad
				//echo "(Producto -> ".$value['idProducto'].")";
				$cantidadAnterior = (isset($_SESSION["caja"][$value["idProducto"]])) ? $_SESSION["caja"][$value["idProducto"]]->cantidad : 0;
				if (isset($_SESSION["caja"][$value["idProducto"]])) {
					$cantidad = $cantidadAnterior + $datosProductoResult["rows"][0]["cantidad"];
				} else {
					$cantidad = $datosProductoResult["rows"][0]["cantidad"];
				}

				//asignamos a $subtotalGeneral lo que hay en sesion para que se sumen.
				if (isset($_SESSION["caja"]["cargos"]["subtotal"])) {
					//echo $_SESSION["caja"]["cargos"]["subtotal"];
					$subtotalGeneral = $_SESSION["caja"]["cargos"]["subtotal"];
				} else {
					$subtotalGeneral = 0;
				}

				if (isset($_SESSION["caja"][$value["idProducto"]]->subtotal)) {
					$subtotal = $_SESSION["caja"][$value["idProducto"]]->subtotal;
				}

				//asignamos a $total lo que hay en sesion para que se sumen.
				$total = '';
				if (isset($_SESSION["caja"]["cargos"]["total"])) {
					$total = $_SESSION["caja"]["cargos"]["total"];
				}

				$total = str_replace(",", "", $total);
				$subtotalGeneral = str_replace(",", "", $subtotalGeneral);

				//Calculamos el subtotal               
				$subtotal = $value["precioventa"] * $cantidad;

				//echo 'precio='.$value["precioventa"];
				$subtotalGeneral += $value["precioventa"] * $cantidad;

				//calculamos el total de la venta
				if (isset($_SESSION["caja"]["cargos"]["impuestos"]["suma"])) {
				   // echo 'subtotal='.$subtotalGeneral;
					$total = ($subtotalGeneral + $_SESSION["caja"]["cargos"]["impuestos"]["suma"]);
				} else {
				   // echo 'subtotal='.$subtotalGeneral;
					$total = $subtotalGeneral;
				}

				//Se suben los impuestos a sesion
				$_SESSION["caja"]["cargos"]["subtotal"] = $subtotalGeneral;
				$_SESSION["caja"]["cargos"]["total"] = $total;

				/* Validamos si el producto ya esta en sesion
				if(!isset($_SESSION['caja'][$value["idProducto"]]))
				{ */
				if (!$comanda) {
					$selectIdProducto = "Select idProducto, codigo from mrp_producto where strcmp(idProducto,'" . $idArticulo . "')=0  or codigo = '" . $idArticulo . "' and estatus = 1 ";
					$resultidProducto = $this->queryArray($selectIdProducto);

					$value["idProducto"] = $resultidProducto["rows"][0]["idProducto"];
					$datosProductoResult["rows"][0]["codigo"] = $resultidProducto["rows"][0]["codigo"];
				}

				$arraySession = new stdClass();
				$producto_impuesto = (isset($_SESSION['caja'][$value["idProducto"]])) ? $_SESSION['caja'][$value["idProducto"]]->impuesto : 0;
				$suma_impuestos = (isset($_SESSION['caja'][$value["idProducto"]])) ? $_SESSION['caja'][$value["idProducto"]]->suma_impuestos : 0;

				$arraySession->id = $value["idProducto"];
				$arraySession->nombre = $datosProductoResult["rows"][0]["nombre"];
				$arraySession->descripcion = $datosProductoResult["rows"][0]["deslarga"];
				$arraySession->imagen = $datosProductoResult["rows"][0]["imagen"];
				$arraySession->codigo = $datosProductoResult["rows"][0]["codigo"];
				/* if($_SESSION['caja'][$idArticulo]->precioventa=''){
					$str_precioventa = number_format($datosProductoResult["rows"][0]["precioventa"], 2);

				}else{
					$str_precioventa = number_format($datosProductoResult["rows"][0]["precioventa"], 2);
				} */
				$str_precioventa = $datosProductoResult["rows"][0]["precioventa"];
				$str_precioventa = str_replace(",", "", $str_precioventa);
				$arraySession->precioventa = $str_precioventa;
				$arraySession->esreceta = $datosProductoResult["rows"][0]["esreceta"];
				$arraySession->eskit = $datosProductoResult["rows"][0]["eskit"];
				$arraySession->cantidad = $cantidad;
				$arraySession->unidad = $datosProductoResult["rows"][0]["unidad"];
				$arraySession->idunidad = $datosProductoResult["rows"][0]["idunidad"];
				if (isset($arrImpProducto) && $arrImpProducto != '') {
					$array_kit .= json_encode($arrImpProducto);
				} else if (isset($arrImpMateriales)) {
					$array_kit .= json_encode($arrImpMateriales);
				}

				if (isset($array_kit)) {
					$arraySession->arr_kit = $array_kit;
				} else {
					$arraySession->arr_kit = '';
				}

				if (isset($descuento)) {
					$arraySession->descuento = $descuento;
				} else {
					$arraySession->descuento = 0.00;
				}

				$arraySession->tipodescuento = '$';
				$arraySession->impuesto = $producto_impuesto;
				$arraySession->suma_impuestos = $suma_impuestos;
				$arraySession->subtotal = $subtotal;
				$arraySession->tipo_producto = $datosProductoResult["rows"][0]["tipo_producto"];

				//$this->iniTrans();
				/*   $selectImpuestos = "SELECT id_impuesto,valor from pvt_producto_impuestos where idProducto=".$value["idProducto"];
				$pvt_impues = $this->queryArray($selectImpuestos);
				$impuestosString = $pvt_impues["rows"][0]["id_impuesto"].'|'.$pvt_impues["rows"][1]["id_impuesto"].'|'.$pvt_impues["rows"][2]["id_impuesto"].'|'.$pvt_impues["rows"][3]["id_impuesto"];
				$selectProd = "SELECT nombre_producto from pvt_ventas_test where id_sesion='".$_SESSION["sesionid"]."' and idProducto=".$value["idProducto"];
				$rslt = $this->queryArray($selectProd);

				$lengthArray = count($rslt["rows"]);
				if ($lengthArray < 1) {
					$insertProducto = 'insert into pvt_ventas_test (id_sesion,idProducto,nombre_producto,cantidad,precio,subtotal,impuestos,descripcion,unidad,arr_kit,tipo_producto)'
					.' values ("'.$_SESSION["sesionid"].'","'.$value["idProducto"].'","'.$datosProductoResult["rows"][0]["nombre"].'","'.$cantidadInicial.'","'.$str_precioventa.'","'.($str_precioventa*$cantidadInicial).'","'.$impuestosString.'","'.$datosProductoResult["rows"][0]["deslarga"].'","'.$datosProductoResult["rows"][0]["unidad"].'","'.$array_kit.'","'.$datosProductoResult["rows"][0]["tipo_producto"].'")';
					$insertProducto = $this->query($insertProducto);
				}else{
					$updateinsertProducto = 'UPDATE pvt_ventas_test set cantidad=cantidad+'.$cantidadInicial.' , subtotal=subtotal+'.($str_precioventa*$cantidadInicial).' where id_sesion="'.$_SESSION["sesionid"].'" and idProducto='.$value["idProducto"];
					$updateinsertProducto = $this->query($updateinsertProducto);
				}

				// $this->commit();
				$this->calculaImpuestosMysql($value["idProducto"],$cantidadInicial,$impuestosString); */
				$_SESSION['caja'][$value["idProducto"]] = (object) $arraySession;

				//Datos de conversion
				$arraySession->idUnidad = $datosProductoResult["rows"][0]["idUnidad"];

				/*
				*  Calculamos los impuestos del producto
				*/
				$this->calculaImpuestos($value["idProducto"], $comanda);
			}
			/*
			Despues de subir los datos a sesion hay que pintarlos en la caja, y para eso se regresa un array a la vista y los productos se pintan con javascript...
			*/
			$this->propina();

			/*
			Valor de la propina en caso de que sea comanda
			*/
			/* echo "(".$cantidadInicial.")";
			echo "(".$_SESSION["caja"][$value["idProducto"]]->cantidad.")";
			echo (float)$cantidadInicial+(float)$_SESSION["caja"][$value["idProducto"]]->cantidad; */
			//echo "(".$cantidadAnterior." - ".$cantidadInicial.")";
			/* echo "(Anterior => ".$cantidadAnterior.")";
			echo "(Inicial => ".$cantidadInicial.")"; */

			if ($comanda && $_SESSION["propina"]) {
				$options[5] = ($_SESSION["caja"]["cargos"]["total"]) * 10 / 100;
				if ($_SESSION["propina"] == true) {
					$options[6] = true;
				} else {
					$options[6] = false;
				}
			} else if ($cantidadAnterior != $cantidadInicial) {

				/* $patron = "/[".$value["idunidad"]."]?/";
				  $cambiarCantidad = 0;
				  preg_match($patron, $resultUnidades["rows"][0]["identificadores"],$encontrados);
				  if($encontrados[0] == ''){
				  $cambiaCantidad = round((float)$cantidadInicial+(float)$cantidadAnterior);
				  }else
				  {
				  $cambiaCantidad = (float)$cantidadInicial+(float)$cantidadAnterior;
			  	} */

			  	if ($_SESSION["caja"][$idProducto]->idunidad == 1 || $_SESSION["caja"][$idProducto]->unidad == 'unidad' || $_SESSION["caja"][$idProducto]->unidad == 'Unidad') {
			  		$cambiaCantidad = round((float) $cantidadInicial + (float) $cantidadAnterior);
			  	} else {
			  		$cambiaCantidad = (float) $cantidadInicial + (float) $cantidadAnterior;
			  	}

			  	//echo "(nueva -> ".$cambiaCantidad.")";
			  	if (!$comanda) {
			  		cajaModel::cambiaCantidad($value["idProducto"], $cambiaCantidad, "$", "0.00", '');
			  	}
			}
			//var_dump($nombreExtra);
			// $this->agregaProducto($nombreExtra,1);
			return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "opciones" => $options, "simple" => $_SESSION["simple"], "sucursal" => $_SESSION["sucursalNombre"], "empleado" => $_SESSION["nombreEmpleado"]);
		} catch (Exception $e) {
			return array('status' => false, 'msg' => $e->getMessage());
		}
	}

	function calculaImpuestosMysql($id,$cantidad,$impuestos,$elimina=0){
		//echo 'id='.$id.' $cantidad='.$cantidad.' impuestos'.$impuestos;
		// include "../../modulos/punto_venta_nuevo/conexionomar.php";
		// $conexion = new DBconetor;
		//$this->iniTrans();
		$fomula = 1;
		$impues = explode("|", $impuestos);

		$resultPrecioV = $this->queryArray('SELECT precio, descuento from pvt_ventas_test where idProducto='.$id.' and id_sesion="'.$_SESSION["sesionid"].'"');
		$precio = $resultPrecioV["rows"][0]["precio"];
		$discount = $resultPrecioV["rows"][0]["descuento"];

		$subtotalPr = $precio * $cantidad;
		$IVA = $impues[0];
		$IEPS =$impues[1];
		$ISR = $impues[2];
		$ISH = $impues[3];

		$formula=0;
		///Caluclo IEPS
		if($IEPS!=0){
			$ieps = explode(":", $IEPS);
			$valorSel = $this->queryArray('SELECT * from pvt_impuestos where id='.$ieps[0]);
			$valorImpuesto = $valorSel['rows'][0]['valor'];
			if($ieps[1]==1){
				$IepsProducto = ($subtotalPr - $discount)*$valorImpuesto/100;
				//echo 'Ieps '.$valorImpuesto.'%='.$IepsProducto.'<br>';
			}else{
				$IepsProducto = ($subtotalPr - $discount)*$valorImpuesto/100;
				//echo 'Ieps '.$valorImpuesto.'%='.$IepsProducto.'<br>';
				$formula=1;
			}

			if($elimina!=0){
				if($elimina==1){ //elimina
					$delupdIeps ='valor-'.$IepsProducto;
				}else{ // si es dos, modifica cantidad
					$delupdIeps = $IepsProducto;
				}
				$this->query('UPDATE pvt_venta_impuestos set valor='.$delupdIeps.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
			}else{
				$rev = $this->queryArray('SELECT * from  pvt_venta_impuestos where idsesion="'.$_SESSION["sesionid"].'" and impuesto ="'.$valorSel['rows'][0]['nombre'].'"');
				if($rev['total']==0){
				  $this->query('INSERT into pvt_venta_impuestos (idsesion,impuesto,valor) values ("'.$_SESSION["sesionid"].'","'.$valorSel['rows'][0]['nombre'].'","'.$IepsProducto.'")');
				}else{
				  $this->query('UPDATE pvt_venta_impuestos set valor=valor+'.$IepsProducto.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
				}
			}
		} ///fin calculo IEPS
		//Calculo ISH
		if($ISH!=0){
			$valorSel = $this->queryArray('SELECT * from pvt_impuestos where id='.$ISH);
			$valorImpuesto = $valorSel['rows'][0]['valor'];
			$ishProducto = ($subtotalPr - $discount)*$valorImpuesto/100;
			if($elimina!=0){
				if($elimina==1){ //elimina
					$delupdIsh = 'valor-'.$ishProducto;
				}else{ // si es dos, modifica cantidad
					$delupdIsh = $ishProducto;
				}
				$this->query('UPDATE pvt_venta_impuestos set valor='.$delupdIsh.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
			}else{
				$rev = $this->queryArray('SELECT * from  pvt_venta_impuestos where idsesion="'.$_SESSION["sesionid"].'" and impuesto ="'.$valorSel['rows'][0]['nombre'].'"');
				if($rev['total']==0){
					$this->query('INSERT into pvt_venta_impuestos (idsesion,impuesto,valor) values ("'.$_SESSION["sesionid"].'","'.$valorSel['rows'][0]['nombre'].'","'.$ishProducto.'")');
				}else{
					$this->query('UPDATE pvt_venta_impuestos set valor=valor+'.$ishProducto.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
				}
			}
		}
		//Fin del calculo ISH
		//CAlculo ISR
		if($ISR!=0){
			$valorSel = $this->queryArray('SELECT * from pvt_impuestos where id='.$ISR);
			$valorImpuesto = $valorSel['rows'][0]['valor'];
			$isrProducto = ($subtotalPr - $discount)*$valorImpuesto/100;
			if($elimina!=0){
				if($elimina==1){
					$delupdIsr = 'valor-'.$isrProducto;
				}else{
					$delupdIsr = $isrProducto;
				}
				$this->query('UPDATE pvt_venta_impuestos set valor='.$delupdIsr.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
			}else{
				$rev = $this->queryArray('SELECT * from  pvt_venta_impuestos where idsesion="'.$_SESSION["sesionid"].'" and impuesto ="'.$valorSel['rows'][0]['nombre'].'"');
				if($rev['total']==0){
					$this->query('INSERT into pvt_venta_impuestos (idsesion,impuesto,valor) values ("'.$_SESSION["sesionid"].'","'.$valorSel['rows'][0]['nombre'].'","'.$isrProducto.'")');
				}else{
					$this->query('UPDATE pvt_venta_impuestos set valor=valor+'.$isrProducto.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
				}
			}
		} //Fin CAlculo ISR
		/* STIMP = 6666; */
		$valorSel = $this->queryArray('SELECT * from pvt_impuestos where id='.$IVA);
		$valorImpuesto = $valorSel['rows'][0]['valor'];
		$sub = ($precio*$cantidad) - $discount;
		if($formula==1){
			$ivaProducto = ($sub + $IepsProducto) * $valorImpuesto/100;
		}else{
			$ivaProducto = $sub * $valorImpuesto/100;
		}
		$toalImpuestoProducto = $ivaProducto+$IepsProducto+$ishProducto-$isrProducto;
		if($elimina!=0){
			if($elimina==1){
				$delupdIva = 'valor-'.$ivaProducto;
			}else{
				$delupdIva = $ivaProducto;
				$this->query('UPDATE pvt_ventas_test set suma_impuestos='.$toalImpuestoProducto.' WHERE id_sesion="'.$_SESSION["sesionid"].'" and idProducto='.$id);
			}
			$this->query('UPDATE pvt_venta_impuestos set valor='.$delupdIva.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
		}else{
			$reviva = $this->queryArray('SELECT * from  pvt_venta_impuestos where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
			if($reviva['total']==0){
				$this->query('INSERT into pvt_venta_impuestos (idsesion,impuesto,valor) values ("'.$_SESSION["sesionid"].'","'.$valorSel['rows'][0]['nombre'].'","'.$ivaProducto.'")');
			}else{
				//echo 'UPDATE pvt_venta_impuestos set valor = valor + '.$ivaProducto.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"';
				$this->query('UPDATE pvt_venta_impuestos set valor = valor + '.$ivaProducto.' where idsesion="'.$_SESSION["sesionid"].'" and impuesto="'.$valorSel['rows'][0]['nombre'].'"');
			}
			$this->query('UPDATE pvt_ventas_test set suma_impuestos=suma_impuestos+'.$toalImpuestoProducto.' WHERE id_sesion="'.$_SESSION["sesionid"].'" and idProducto='.$id);
		}
		//$this->commit();
	}

	function eliminaProductoMysql($idProducto){

		$selectIdProductoPvt = "SELECT idProducto,cantidad,impuestos from pvt_ventas_test where id_sesion='".$_SESSION["sesionid"]."' and idProducto=".$idProducto;
		$resultidPvt = $this->queryArray($selectIdProductoPvt);
		//print_r($resultidPvt);
		// echo $resultidPvt["rows"][0]["idProducto"].'-'.$resultidPvt["rows"][0]["cantidad"].'-'.$resultidPvt["rows"][0]["impuestos"];
		$elimina=1;

		$this->calculaImpuestosMysql($resultidPvt["rows"][0]["idProducto"],$resultidPvt["rows"][0]["cantidad"],$resultidPvt["rows"][0]["impuestos"],$elimina);
		$this->iniTrans();
		$eliminarSuspendida = "Delete from pvt_ventas_test where idProducto =".$idProducto." and id_sesion='".$_SESSION["sesionid"]."'";
		$resutEliminaSuspendida = $this->queryTrans($eliminarSuspendida);
		$this->commit();
	}

	function calculaImpuestos_nuevaaa($idProducto,$comanda){
		foreach ($_SESSION['caja'] as $key => $value) {
			if($key!='cargos'){
				$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
				$queryImpuestos .= " from impuesto i, mrp_producto p ";
				$queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
				$queryImpuestos .= " where p.idProducto=" . $key . " and i.id=pi.idImpuesto ";
				$queryImpuestos .= " Order by pi.idImpuesto DESC ";
				echo $queryImpuestos;
				//exit();
				$resultImpuestos = $this->queryArray($queryImpuestos);

				foreach ($resultImpuestos['rows'] as $key => $value) {
				}
			}
		}
	}

	function calculaImpuestos($idProducto, $comanda, $descuento = 0.00, $precionuevo = 0.00) {
		//echo $precionuevo.'X';
		$flag = 0;
		if($precionuevo == 0){
			$flag = 1;
			$selectPrecioVenta = "Select precioventa from mrp_producto where idProducto = " . $idProducto . " or codigo = '" . $idProducto . "' ";
			$resultPrecioV = $this->queryArray($selectPrecioVenta);

			$precionuevo = $resultPrecioV["rows"][0]["precioventa"];
		}
		/*
		Consultamos los impuestos del producto si los tiene.
		*/

		$suma_impuestos = 0;
		$producto_impuesto = 0;
		$total = 0;
		$ieps = 0;
		//echo 'primer='.$ieps;
		$selectIdProducto = "Select idProducto, codigo from mrp_producto where idProducto = " . $idProducto . " or codigo = '" . $idProducto . "' ";
		$resultid = $this->queryArray($selectIdProducto);
		$idProducto = $resultid["rows"][0]["idProducto"];

		$subtotal = $_SESSION['caja'][$idProducto]->subtotal;
		$cantidad = ($comanda) ? $_SESSION['caja'][$idProducto]->cantidad : 1;

		$_SESSION['caja'][$idProducto]->id = $resultid["rows"][0]["idProducto"];
		$_SESSION['caja'][$idProducto]->codigo = $resultid["rows"][0]["codigo"];

		//print_r($_SESSION["caja"]);
		$_SESSION["caja"]["cargos"]["impuestos"]['IVA'] = 0.0;
		$_SESSION["caja"]["cargos"]["impuestos"]['IEPS'] = 0.0;
		$_SESSION["caja"]["cargos"]["impuestos"]['test'] = 0.0;
		//echo 'sgundo='.$ieps;

		foreach ($_SESSION["caja"] as $key => $value) {
			if ($key != 'cargos') {
				if ($key == $idProducto) {
					//echo 'terccer='.$faieps;
					//echo 'X';
					$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre, i.retenido";
					$queryImpuestos .= " from impuesto i, mrp_producto p ";
					$queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
					$queryImpuestos .= " where p.idProducto=" . $idProducto . " and i.id=pi.idImpuesto ";
					$queryImpuestos .= " Order by pi.idImpuesto DESC ";
					//echo $queryImpuestos;
					//exit();
					$resultImpuestos = $this->queryArray($queryImpuestos);

					$_SESSION['caja'][$idProducto]->impuesto = 0.0;
					//print_r($resultImpuestos["rows"]);
					//exit();
					//echo 'cuarto='.$ieps;

					$max = sizeof($resultImpuestos["rows"]);
					if($max==1){
						$ieps=0;
					}

					foreach ($resultImpuestos["rows"] as $key => $valueImpuestos) {
						//echo 'DDDD'.$key;
						//exit();
						if($precionuevo!=0){
							$precio = $precionuevo;
						}else{
							//echo 'ecntro';
							if($flag==0){
								//  echo '*'.$_SESSION['caja'][$idProducto]->precioventa.'*';
								$precio = $_SESSION['caja'][$idProducto]->precioventa;
							}else{
								$precio = $valueImpuestos["precioventa"];
							}
						}
						// echo 'cinco='.$ieps;

						$descuento = str_replace(",", "", $descuento);
						if($_SESSION['caja'][$idProducto]->cantidad!=''){
							$cantidad=$_SESSION['caja'][$idProducto]->cantidad;
						}
						//$subtotal = $_SESSION['caja'][$idProducto]->subtotal = str_replace(",", "", number_format(($valueImpuestos["precioventa"] * $cantidad ) - $descuento, 2));
						$subtotal = $_SESSION['caja'][$idProducto]->subtotal = str_replace(",", "", ($precio * $cantidad ) - $descuento);
						//echo '/'.$precio.'-'.$cantidad.'/';
						$suma_impuestos = $valueImpuestos["valor"];

						//echo 'seis='.$ieps;
						/*   if(ieps=0)
							ipes=0
						else
							ieps=precio*(ieps/100)

						if(iva=0)
							iva=0
						else
							iva=precio*(iva/100)

						importetotal=precio+ieps+iva */

						if ($valueImpuestos["nombre"] == 'IEPS') {
							//echo 'siete='.$ieps;
							//$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
							$producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
							//echo '?'.$producto_impuesto;
							// exit();
						} else {
							if ($ieps != 0) {
								/*if($ke==0){
									$ieps=0;
								} */
								//   echo 'F'.$valueImpuestos;
								// echo 'ocho='.$ieps;
								//echo 'iepssi';
								//$producto_impuesto = ((($subtotal)) * $valueImpuestos["valor"] / 100);
								//echo $key;
								//echo "(".$subtotal.'+'.$ieps.')*'.$valueImpuestos["valor"].'/ 100';
								$producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);
								//echo 'X'.$producto_impuesto;
								//exit();
								if($valueImpuestos["retenido"]==1){
									$nombreret=$valueImpuestos["nombre"];
									$producto_impuesto_ret =  (($subtotal) * $valueImpuestos["retenido"] / 100);//sacco el retenido
								}
							} else {
								//echo 'nohayieps';
								//$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
								// echo 'Y'.$producto_impuesto;
								//exit();
								if($valueImpuestos["retenido"]==1){
									$nombreret=$valueImpuestos["nombre"];
									$producto_impuesto_ret =  (($subtotal) * $valueImpuestos["valor"] / 100);//sacco el retenido 
								}else{
									$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
								}                                 
							}
						}

						//echo "(".$subtotal ."-". $descuento ."*". $valueImpuestos["valor"].")";
						//echo $producto_impuesto.'X';
						//echo 'D'.$producto_impuesto_ret;
						$xx2 += $producto_impuesto_ret;
				
						if($valueImpuestos["retenido"]!=1){
							$_SESSION['caja'][$idProducto]->impuesto = str_replace(",", "", $_SESSION['caja'][$idProducto]->impuesto) + $producto_impuesto ;
							$_SESSION['caja'][$idProducto]->suma_impuestos += $suma_impuestos;
							$_SESSION['caja'][$idProducto]->cargos->$valueImpuestos["nombre"] = $producto_impuesto;
						}
						
						if($producto_impuesto_ret!=0){
							//echo $producto_impuesto_ret.'?';
							$_SESSION["caja"]["cargos"]["impuestos"][$nombreret] = $producto_impuesto_ret;
						}
						if($valueImpuestos["retenido"]!=1){
							$_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]] = str_replace(",", "", $_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]]) + $producto_impuesto;
						}
						$producto_impuesto_ret = 0;
					}
				} else {
					//echo 'y'.$key.'y';
					$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre, i.retenido";
					$queryImpuestos .= " from impuesto i, mrp_producto p ";
					$queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
					$queryImpuestos .= " where p.idProducto=" . $key . " and i.id=pi.idImpuesto ";
					$queryImpuestos .= " Order by pi.idImpuesto DESC ";

					$resultImpuestos = $this->queryArray($queryImpuestos);

					$_SESSION['caja'][$key]->impuesto = 0.0;

					foreach ($resultImpuestos["rows"] as $key2 => $valueImpuestos) {

						if (!isset($_SESSION['caja'][$key]->descuento_neto) && $_SESSION['caja'][$key]->descuento_neto == '') {
							$_SESSION['caja'][$key]->descuento_neto = 0.0;
						}
						/*echo '$precionuevo'.$precionuevo;
						echo 'sesion'.$_SESSION['caja'][$key]->precioventa;*/
						$precio=$_SESSION['caja'][$key]->precioventa;
						/*  if($precionuevo!=0){
							$precio = $precionuevo;
						}else{
							if($flag==0){
								echo '*'.$_SESSION['caja'][$idProducto]->precioventa.'*';
								$precio = $_SESSION['caja'][$idProducto]->precioventa;
							}else{
								$precio = $valueImpuestos["precioventa"];
							}
							//$precio = $valueImpuestos["precioventa"];
						}  */

						//echo $_SESSION['caja'][$key]->descuento_neto;

						$subtotal = $_SESSION['caja'][$key]->subtotal = str_replace(",", "", ($precio * $_SESSION['caja'][$key]->cantidad ) - str_replace(",", "", $_SESSION['caja'][$key]->descuento_neto));
						//echo "(".$_SESSION['caja'][$key]->descuento_neto.")";
						$suma_impuestos = $valueImpuestos["valor"];

						if ($valueImpuestos["nombre"] == 'IEPS') {
							$producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
							//$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
						
						} else {
							if ($ieps != 0) {
								$producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);
								//$producto_impuesto = ((($subtotal)) * $valueImpuestos["valor"] / 100);
								if($valueImpuestos["retenido"]==1){
									$nombreret=$valueImpuestos["nombre"];
									$producto_impuesto_ret =  (($subtotal) * $valueImpuestos["valor"] / 100);//sacco el retenido
								}
							} else {
								$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
								if($valueImpuestos["retenido"]==1){
									$nombreret=$valueImpuestos["nombre"];
						   
									$producto_impuesto_ret =  (($subtotal) * $valueImpuestos["valor"] / 100);//sacco el retenido 
								}                              
							}
						}
						//echo 'D'.$producto_impuesto_ret;
						//echo "(".$subtotal ."-". $descuento ."*". $valueImpuestos["valor"].")";

						$_SESSION['caja'][$key]->impuesto = str_replace(",", "", $_SESSION['caja'][$key]->impuesto) + $producto_impuesto - $producto_impuesto_ret;
						$_SESSION['caja'][$key]->suma_impuestos += $suma_impuestos;
						$_SESSION['caja'][$key]->cargos->$valueImpuestos["nombre"] = $producto_impuesto;

						if($producto_impuesto_ret!=0){
							$_SESSION["caja"]["cargos"]["impuestos"][$nombreret] = $producto_impuesto_ret;
						}
						if($valueImpuestos["retenido"]!=1){
							$_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]] = str_replace(",", "", $_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]]) + $producto_impuesto;
						}
					}
				}
			}
		}
		//echo $xx2;
		foreach ($_SESSION["caja"] as $key => $value) {
			if ($key != 'cargos') {
				$strimpuesto = str_replace(",", "", $_SESSION["caja"][$key]->impuesto);
				$total += $strimpuesto;
				$subtotalGeneral += str_replace(",", "", $_SESSION["caja"][$key]->subtotal);
			}
		}

		$str_subtotal = str_replace(",", "", $subtotalGeneral);
		$_SESSION["caja"]["cargos"]["subtotal"] = $str_subtotal;

		$str_total = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
		$_SESSION["caja"]["cargos"]["total"] = ($str_subtotal + $total) - $xx2;
		//print_r($_SESSION["caja"]);
	}

	function eliminaProducto($idProducto) {

	   // $this->eliminaProductoMysql($idProducto);

		$cantidadAntes = $_SESSION['caja'][$idProducto]->cantidad;
		$precioventa = $_SESSION['caja'][$idProducto]->precioventa;
		$Subtotal = 0;
		$sumaImpuestos = 0;
		$cantidad = 0;

		$_SESSION['caja'][$idProducto]->cantidad = number_format($cantidad, 2);
		$_SESSION['caja'][$idProducto]->subtotal = $cantidad * $precioventa;
		//print_r($_SESSION['caja']);
	   
		unset($_SESSION['caja'][$idProducto]);

		$_SESSION["caja"]["cargos"]["subtotal"] = 0;
		$_SESSION["caja"]["cargos"]["total"] = 0;
		$_SESSION["caja"]["cargos"]["impuestos"]["IEPS"] = 0;
		$_SESSION["caja"]["cargos"]["impuestos"]["IVA"] = 0;
		$_SESSION["caja"]["cargos"]["impuestos"]["test"] = 0;
 
		foreach ($_SESSION["caja"] as $key => $value) {
			if ($key != 'cargos') {
				//$key es e id del producto
				$impuestos = 0;
				$Subtotal += $_SESSION['caja'][$key]->subtotal;

				foreach ($value->cargos as $key2 => $value2) {

					$sumaImpuestos += $value2;
					$impuestos += $value2;

					//$_SESSION["caja"]["cargos"]["impuestos"][$key2] += ($value2 * $_SESSION['caja'][$key]->cantidad);
					$_SESSION["caja"]["cargos"]["impuestos"][$key2] += ($value2);
					//$_SESSION["caja"]["cargos"]["impuestos"][$key2] = ($value2 * $_SESSION['caja'][$key]->cantidad);
				}
				//echo "impuestos1 -> ".$sumaImpuestos;
				//echo "Producto -> ".$key."  cantidad -> ".$_SESSION['caja'][$key]->cantidad;
				//$sumaImpuestos*=$_SESSION['caja'][$key]->cantidad;
	
				//echo "impuestos2 -> ".$sumaImpuestos;
				//echo "impuestos -> ".$impuestos;
				//$_SESSION['caja'][$key]->impuesto = number_format($impuestos * $_SESSION['caja'][$key]->cantidad, 2);
				$_SESSION['caja'][$key]->impuesto = number_format($impuestos, 2);
				//echo "impuestoprod -> ".$_SESSION['caja'][$key]->impuesto;
			}
		}

		$_SESSION["caja"]["cargos"]["subtotal"] = number_format($Subtotal, 2);

		$str_subtotal = str_replace(",", "", $_SESSION["caja"]["cargos"]["subtotal"]);
		$_SESSION["caja"]["cargos"]["total"] = number_format($str_subtotal + $sumaImpuestos, 2);

		//$this->calculaImpuestos($idProducto, true, $_SESSION['caja'][$idProducto]->descuento);
		return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "count" => count($_SESSION['caja']), "simple" => $_SESSION["simple"], "sucursal" => $_SESSION["sucursalNombre"], "empleado" => $_SESSION["nombreEmpleado"]);
	}

	function _eliminaProducto($idProducto) {

		$ieps = 0;
		$subtotal = $_SESSION['caja'][$idProducto]->subtotal;
		$impuesto = $_SESSION['caja'][$idProducto]->impuesto;
		$cantidad = $_SESSION['caja'][$idProducto]->cantidad;
		$subSess = $_SESSION['caja']["cargos"]["subtotal"];

		$_SESSION['caja']["cargos"]["subtotal"] = number_format($subSess - $subtotal, 2);
		//echo "sesion cargos subtotal=".$subSess.'-'.$subtotal;
		$_SESSION['caja']["cargos"]["total"] -= ($_SESSION['caja']["cargos"]["subtotal"] + $impuesto);

		//echo 'sesion cargos total-=sesion subtotal'.$_SESSION['caja']["cargos"]["subtotal"].'+'.$impuesto;
		exit();
		$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
		$queryImpuestos .= " from impuesto i, mrp_producto p ";
		$queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
		$queryImpuestos .= " where p.idProducto=" . $idProducto . " and i.id=pi.idImpuesto ";
		$queryImpuestos .= " Order by pi.idImpuesto DESC ";

		$resultImpuestos = $this->queryArray($queryImpuestos);

		foreach ($resultImpuestos["rows"] as $key => $valueImpuestos) {

			$valueImpuestos["precioventa"] = $_SESSION['caja'][$idProducto]->precioventa;

			$producto_impuesto = 0;
			if ($valueImpuestos["nombre"] == 'IEPS') {
				//$producto_impuesto = $ieps = $valueImpuestos["precioventa"] * $valueImpuestos["valor"] / 100;
				$producto_impuesto = $valueImpuestos["precioventa"] * $valueImpuestos["valor"] / 100;
			} else {
				//$producto_impuesto = ($valueImpuestos["precioventa"] + $ieps) * $valueImpuestos["valor"] / 100;
				$producto_impuesto = ($valueImpuestos["precioventa"]) * $valueImpuestos["valor"] / 100;
			}

			$_SESSION["caja"]["cargos"]["impuestos"][$valueImpuestos["nombre"]] -= number_format(number_format($producto_impuesto, 2) * $cantidad, 2);
		}

		unset($_SESSION['caja'][$idProducto]);
		return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "count" => count($_SESSION['caja']));
	}

	function agregarPropina($idArticulo, $cantidad) {
		session_start();
		$arrayPropina = new stdClass();

		$datosPropina = 'Select codigo,nombre from mrp_producto where idProducto = ' . $idArticulo . '';

		$result = $this->queryArray($datosPropina);

		$arrayPropina->id = $idArticulo;
		$arrayPropina->nombre = $result["rows"][0]["nombre"];
		$arrayPropina->codigo = $result["rows"][0]["codigo"];
		$arrayPropina->precioventa = $cantidad;
		$arrayPropina->esreceta = 0;
		$arrayPropina->eskit = 0;
		$arrayPropina->unidad = '';
		$arrayPropina->cantidad = '1.00';
		$arrayPropina->descuento = 0;
		$arrayPropina->descuento_neto = 0;
		$arrayPropina->tipodescuento = 0;
		$arrayPropina->arr_kit = '';
		$arrayPropina->impuesto = 0;
		$arrayPropina->suma_impuestos = 0;
		$arrayPropina->subtotal = $cantidad;


		$_SESSION['caja'][$idArticulo] = $arrayPropina;
		$subtotal = $_SESSION["caja"]["cargos"]["subtotal"];
		$_SESSION["caja"]["cargos"]["subtotal"] = number_format($subtotal + $cantidad, 2);
		$_SESSION["caja"]["cargos"]["total"] += number_format($cantidad, 2);

		return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "simple" => $_SESSION["simple"]);
	}
	function configuraPropina(){
		$configuraPropina = "SELECT propina from com_configuracion where id=1";
		$result = $this->queryArray($configuraPropina);

		return array('status' => $result['rows'][0]['propina']);

	}

	function cancelarCaja() {

		/* $_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);
		  $almacen = $_SESSION["almacen"];

		  foreach ($_SESSION["caja"] as $key => $value) {

		  if ($key != 'cargos') {

		  $updateStock = "UPDATE mrp_stock "
		  . "SET cantidad=cantidad+" . $value->cantidad . " "
		  . "WHERE idProducto='$key' AND idAlmacen='$almacen'";


		  $resultUpdateStock = $this->queryTrans($updateStock);

		  if (!$resultUpdateStock["status"]) {
		  throw new Exception("Error al actualizar el stock.");
		  }
		  }
		} */
		unset($_SESSION['caja']);
		unset($_SESSION['pagos-caja']);
		return true;
	}

	function checaExistencias($idProducto) {
		$sqlExistencias = "select  mrp_producto.idProducto id,mrp_producto.nombre,mrp_stock.ocupados,"
		. "CASE WHEN mrp_stock.cantidad  IS NOT NULL THEN mrp_stock.cantidad-if(SUM(mrp_devoluciones_reporte.nDevoluciones) is null"
		. ",0,SUM(mrp_devoluciones_reporte.nDevoluciones))ELSE 0 END AS cantidad "
		. "from  mrp_producto left join mrp_stock  on mrp_producto.idProducto=mrp_stock.idProducto left "
		. "join mrp_devoluciones_reporte on mrp_devoluciones_reporte.idProducto=mrp_stock.idProducto and "
		. "mrp_devoluciones_reporte.idProveedor=mrp_producto.idProveedor and mrp_devoluciones_reporte.idAlmacen=mrp_stock.idAlmacen "
		. "and mrp_devoluciones_reporte.estatus=0 where mrp_producto.idProducto  = " . $idProducto . " and "
		. "vendible=1 and mrp_stock.idAlmacen=" . $_SESSION['almacen'] . " group by mrp_producto.idProducto order by nombre";
		$result = $this->queryArray($sqlExistencias);
		if ($result["total"] > 0) {
			$original = $result["rows"][0]["cantidad"] - $result["rows"][0]["ocupados"];
			$str_cantidad = str_replace(",", ".", $_SESSION['caja'][$idProducto]->cantidad);
			$result["rows"][0]["cantidad"] -= ($str_cantidad + $result["rows"][0]["ocupados"]);
			return array("status" => true, "rows" => $result["rows"], "Original" => $original);
		} else {
			return array("status" => false);
		}
	}

	function checaPrecios($idProducto){
		$sqlPrecios="SELECT id,descripcion,precio,orden from mrp_lista_precios where idProducto=".$idProducto;
		$result = $this->queryArray($sqlPrecios);
		return array("status" => true, "rows" => $result["rows"]);
	}

	function checaPrecioVenta($idProducto){
		$sqlPrecios="SELECT precioventa,descu from mrp_producto where idProducto=".$idProducto;
		$result = $this->queryArray($sqlPrecios);
		return array("status" => true, "rows" => $result["rows"]);
	}

	function ajustaTotal($ajuste){
		if($ajuste==0 || $ajuste==''){
			$_SESSION["caja"]["cargos"]["total"] = $_SESSION["caja"]["cargos"]["total"];
		}else{
			$_SESSION["caja"]["cargos"]["total"] = $ajuste;
		}
		return array("status" => true);
	}

	function cambiaCantidadMysql($idProducto, $cantidad, $tipo, $descuento, $comentario, $precionuevo){
		$selectIdProductoPvt = "SELECT idProducto,cantidad,impuestos,precio from pvt_ventas_test where id_sesion='".$_SESSION["sesionid"]."' and idProducto=".$idProducto;
		$resultidPvt = $this->queryArray($selectIdProductoPvt);
		$precioventa = $resultidPvt["rows"][0]["precio"];
		
		if($precionuevo==''){
			$precioventa = $resultidPvt["rows"][0]["precio"];  
		}else{
			$precioventa = $precionuevo;  
		}

		if($tipo=='%'){
		   $descuento = ($precioventa * $cantidad) * $descuento / 100;
		}

		$subtotal = $precioventa * $cantidad;
		$elimina = 2;
		$updateProducto = "UPDATE pvt_ventas_test set cantidad=".$cantidad.", precio=".$precioventa.", subtotal=".$subtotal.", descuento=".$descuento.", tipodescuento='".$tipo."' where idProducto=".$idProducto." and id_sesion='".$_SESSION["sesionid"]."'";
		// echo $updateProducto;
		$resutEliminaSuspendida = $this->query($updateProducto);
		$this->calculaImpuestosMysql($idProducto,$cantidad,$resultidPvt["rows"][0]["impuestos"],$elimina);
	}

	function cambiaCantidad($idProducto, $cantidad, $tipo, $descuento, $comentario, $precionuevo) {
		if($precionuevo!=''){
			$precioventa = $_SESSION['caja'][$idProducto]->precioventa = $precionuevo;
		}

		$cantidad = $_SESSION['caja'][$idProducto]->cantidad = $cantidad;
		$cantidad = str_replace(",", "", $cantidad);
		$_SESSION['caja'][$idProducto]->subtotal = $cantidad * $precioventa;
		$_SESSION['caja'][$idProducto]->descuento = 0.0;
		$_SESSION['caja'][$idProducto]->tipodescuento = $tipo;
		$_SESSION['caja'][$idProducto]->descuento_cantidad = $descuento;

		if ($tipo != '' && $descuento != 0.0) {
			if ($tipo == "%") {
				$_SESSION['caja'][$idProducto]->descuento = ($_SESSION['caja'][$idProducto]->precioventa * $cantidad) * $descuento / 100;
				$_SESSION['caja'][$idProducto]->descuento_neto = $_SESSION['caja'][$idProducto]->descuento;
			} else if ($tipo == "$") {
				$_SESSION['caja'][$idProducto]->descuento = number_format($descuento, 2);
				$_SESSION['caja'][$idProducto]->descuento_neto = $_SESSION['caja'][$idProducto]->descuento;
			}
		} else {
			$_SESSION['caja'][$idProducto]->descuento_neto = 0.0;
		}
		$_SESSION['caja'][$idProducto]->subtotal = ($_SESSION['caja'][$idProducto]->precioventa * $cantidad) - str_replace(",", "", $_SESSION['caja'][$idProducto]->descuento);

		foreach ($_SESSION["caja"] as $key => $value) {
			foreach ($value->cargos as $key2 => $value2) {
				$data = (array) $_SESSION["caja"][$key]->cargos;
				$impuestoxCant = $value2;
				$sumaImpuestos += $impuestoxCant;

				if ($descuento != '') {
					if ($tipo == "%") {
						$impuestos = str_replace(",", "", ($sumaImpuestos * $_SESSION['caja'][$idProducto]->cantidad) * $descuento / 100);
						//$_SESSION['caja'][$key]->impuesto
					} else if ($tipo == "$") {
						$impuestos = str_replace(",", "", $sumaImpuestos);
					}
				} else {
					$impuestos = str_replace(",", "", $sumaImpuestos * $_SESSION['caja'][$idProducto]->cantidad);
				}
			}
			//$_SESSION['caja'][$key]->impuesto = number_format(str_replace(",","", $impuestos),2);
		}
		$this->calculaImpuestos($idProducto, true, $_SESSION['caja'][$idProducto]->descuento, $precioventa);
		return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "count" => count($_SESSION['caja']), "simple" => $_SESSION["simple"], "sucursal" => $_SESSION["sucursalNombre"], "empleado" => $_SESSION["nombreEmpleado"]);
	}

	/* function cambiaCantidadX($idProducto, $cantidad, $tipo, $descuento, $comentario) {
		$precioventa = $_SESSION['caja'][$idProducto]->precioventa;
		$Subtotal = 0;
		$sumaImpuestos = 0;
		$porcentaje = 1 - ($descuento / 100);
		$nombre = $_SESSION['caja'][$idProducto]->nombre;
		if (!isset($_SESSION['caja'][$idProducto]->original) || $_SESSION['caja'][$idProducto]->original == '') {
			$_SESSION['caja'][$idProducto]->original = $nombre;
		}
		$_SESSION['caja'][$idProducto]->nombre = $_SESSION['caja'][$idProducto]->original . " " . $comentario;
		$_SESSION['caja'][$idProducto]->tipodescuento = $tipo;
		if($descuento != '' && $descuento != 0.0) {
			$_SESSION['caja'][$idProducto]->descuento_neto = $descuento;
		}else {
			$_SESSION['caja'][$idProducto]->descuento_neto = 0.0;
		}

		if ($_SESSION["caja"][$idProducto]->idunidad == 1 || $_SESSION["caja"][$idProducto]->unidad == 'unidad' || $_SESSION["caja"][$idProducto]->unidad == 'Unidad') {
			$cantidad = round($cantidad);
		}

		$cantidad = $_SESSION['caja'][$idProducto]->cantidad = $cantidad;
		$cantidad = str_replace(",", "", $cantidad);
		$_SESSION['caja'][$idProducto]->subtotal = $cantidad * $precioventa;

		if ($tipo != '' && $descuento != '') {
			if ($tipo == '$') {
				$_SESSION['caja'][$idProducto]->descuento = number_format($descuento, 2);
				$_SESSION['caja'][$idProducto]->descuento = number_format($descuento, 2);
			}

			if ($tipo == '%') {
				$_SESSION['caja'][$idProducto]->descuento = number_format(($precioventa * $cantidad) / 100 * $descuento, 2);
			}
		} else {
			$porcentaje = 1;
			$_SESSION['caja'][$idProducto]->descuento = number_format(0, 2);
		}

		$strSubtotal = str_replace(",", "", $_SESSION['caja'][$idProducto]->subtotal);
		$_SESSION['caja'][$idProducto]->subtotal = number_format($strSubtotal, 2);

		$_SESSION["caja"]["cargos"]["subtotal"] = 0;
		$_SESSION["caja"]["cargos"]["total"] = 0;
		$_SESSION["caja"]["cargos"]["impuestos"]["IEPS"] = 0;
		$_SESSION["caja"]["cargos"]["impuestos"]["IVA"] = 0;
		$_SESSION["caja"]["cargos"]["impuestos"]["test"] = 0;

		foreach ($_SESSION["caja"] as $key => $value) {
			if ($key != 'cargos') {
				$impuestos = 0;

				//$Subtotal += (float)str_replace(",", "", $_SESSION['caja'][$key]->subtotal);
				//echo "(".$Subtotal.")";
				if ($key == $idProducto) {
					//SI hay descuento
					if ($tipo != '' && $descuento != '') {
						if ($tipo == "%") {
							$_SESSION['caja'][$idProducto]->descuento = number_format(((str_replace(",", "", $_SESSION['caja'][$key]->subtotal) + $impuestos) / 100 )* $descuento,2);
						} else if ($tipo == "$") {
							$_SESSION['caja'][$idProducto]->descuento = $descuento;
						}
						//echo "1-(".$Subtotal.")";
						//echo "2-(".$_SESSION['caja'][$key]->descuento.")";
						//$Subtotal -= $_SESSION['caja'][$key]->descuento;
						$Subtotal += $_SESSION['caja'][$key]->subtotal = $_SESSION['caja'][$key]->subtotal - $_SESSION['caja'][$key]->descuento;
						// $_SESSION['caja'][$idProducto]->subtotal = $Subtotal;
						//echo "3-(".$Subtotal.")";
					}

					foreach ($value->cargos as $key2 => $value2) {
						$impuestoxCant = str_replace(",", "", $value2) * $value->cantidad;
						$sumaImpuestos += $impuestoxCant;
						if($descuento != '' && $_SESSION['caja'][$key]->descuento_neto != 0.0) {
							if ($tipo == "%") {
								$impuestos += str_replace(",", "", $value2 * $descuento / 100 );
							} else if ($tipo == "$") {
								$impuestos += str_replace(",", "", $value2);
							}
						}else {
							$impuestos += str_replace(",", "", $value2);
						}
						$_SESSION["caja"]["cargos"]["impuestos"][$key2] = number_format(str_replace(",", "", number_format($_SESSION["caja"]["cargos"]["impuestos"][$key2] + (str_replace(",", "", $value2) * $value->cantidad), 2)),2);
					}
				} else {
					if (isset($value->cargos)) {
						//print_r($_SESSION['caja'][$key]);
						foreach ($value->cargos as $key2 => $value2) {
							$impuestoxCant = str_replace(",", "", $value2) * $value->cantidad;
							$sumaImpuestos += $impuestoxCant;
							if($_SESSION['caja'][$key]->descuento != 0.0 && $_SESSION['caja'][$key]->descuento_neto != 0.0) {
								if ($tipo == "%") {
									$impuestos += str_replace(",", "", $value2 * $_SESSION['caja'][$key]->descuento_neto / 100 );
								} else if ($tipo == "$") {
									$impuestos += str_replace(",", "", $value2);
								}
							}else {
								$impuestos += str_replace(",", "", $value2);
							}
							$_SESSION["caja"]["cargos"]["impuestos"][$key2] = number_format(str_replace(",", "", number_format($_SESSION["caja"]["cargos"]["impuestos"][$key2] + (str_replace(",", "", $value2) * $value->cantidad), 2)),2);
						}
					}
					//echo "1-(".$Subtotal.")";
					//echo "2-(".$_SESSION['caja'][$key]->descuento.")";
					$Subtotal += $_SESSION['caja'][$key]->subtotal;
					//$_SESSION['caja'][$key]->subtotal -= $_SESSION['caja'][$key]->descuento;
					//echo "3-(".$Subtotal.")";
				}
				$_SESSION['caja'][$key]->impuesto = number_format($impuestos * $_SESSION['caja'][$key]->cantidad, 2);
			}
		}
		//echo "(".$Subtotal.")";
		foreach ($variable as $key => $value) {
			# code...
		}

		$_SESSION["caja"]["cargos"]["subtotal"] =  number_format(str_replace(",", "",  $_SESSION["caja"]["cargos"]["subtotal"]) + $Subtotal, 2);
		$_SESSION["caja"]["cargos"]["total"] = number_format((str_replace(",", "",  $_SESSION["caja"]["cargos"]["subtotal"]) + $sumaImpuestos), 2);
		
		//$this->calculaImpuestos($idProducto, true, $_SESSION['caja'][$idProducto]->descuento);
		//$this->calculaImpuestos($idProducto, false);
		//print_r($_SESSION["caja"]);
		//exit();
		return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "count" => count($_SESSION['caja']), "simple" => $_SESSION["simple"]);
	} */

	function truncateFloat($number, $digitos, $a, $b) {
		$resultado = str_replace(",", ".", $number);
		$resultado = number_format($resultado, 2);
		return $resultado;
	}

	function findAddenda($rfc){
		$selectid = "SELECT nombre from comun_facturacion where rfc='".$rfc."'";
		$result1 = $this->queryArray($selectid);
		$idFact = $rfc;
		$idCliente = (object) $this->datosFacturacion($idFact);
		$selectAddenda = "SELECT *  from pvt_addenda_cliente where idCliente=".$idCliente->nombre;
		$result2 = $this->queryArray($selectAddenda);
	
		if($result2['rows'][0]['xml']!=''){
			return array("status" => true, "adenda" => $result2['rows']);
		}else{
			return array("status" => true);
		}
	}

	function agregaPago($tipo, $tipostr, $cantidad, $referencia) {
		$_SESSION['pagos-caja']["pagos"][$tipo]['tipostr'] = $tipostr;
		$str_cantidad = str_replace(",", "", $_SESSION['pagos-caja']["pagos"][$tipo]['cantidad']);
		$_SESSION['pagos-caja']["pagos"][$tipo]['cantidad'] = $str_cantidad + $cantidad;
		$_SESSION['pagos-caja']["pagos"][$tipo]['referencia'] = $referencia;
		$_SESSION['pagos-caja']["pagos"][$tipo]['tipo'] = $tipo;


		$abonado = 0;
		foreach ($_SESSION['pagos-caja']["pagos"] as $key => $value) {
			$str_cantidad2 = str_replace(",", "", $value["cantidad"]);
			$abonado += $str_cantidad2;
		}

			//Abonado es el dinero que entrega el cliente no importa como pague
		$abonado = str_replace(",", "", $abonado);
		$_SESSION['pagos-caja']["Abonado"] = $abonado;


			//Aun por pagar en los casos que pagan con diferentes metodos.
		$str_total = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
		$porPagar = $str_total - $_SESSION['pagos-caja']["Abonado"];
		if ($_SESSION['pagos-caja']["Abonado"] >= $str_total) {
			$_SESSION['pagos-caja']["porPagar"] = number_format(0, 2);
		} else {
			$_SESSION['pagos-caja']["porPagar"] = $this->redondeo($porPagar,2);
		}

			//Cambio en caso de que sea necesario
		$cambio = $_SESSION['pagos-caja']["Abonado"] - $str_total;
		if ($cambio > 0) {
			$_SESSION['pagos-caja']["cambio"] = $this->redondeo($cambio,2);
		} else {
			$_SESSION['pagos-caja']["cambio"] = number_format(0, 2);
		}

		$_SESSION['pagos-caja']["Abonado"] = $this->redondeo($abonado,2);

		return array("status" => true, "tipo" => $tipo, "tipostr" => $tipostr, "cantidad" => $_SESSION['pagos-caja']["pagos"][$tipo]['cantidad'], "abonado" => $_SESSION['pagos-caja']["Abonado"], "porPagar" => $_SESSION['pagos-caja']["porPagar"], "cambio" => $_SESSION['pagos-caja']["cambio"]);
	}

	function redondeo($numero, $decimales){
		$raiz = 10;
		$multiplicador = pow ($raiz,$decimales);
		$resultado = ((int)($numero * $multiplicador)) / $multiplicador;
		return str_replace(',', '', number_format($resultado, $decimales));
	}

	function buscaFoodwear(){
		$query = "SELECT count(*) as menu from accelog_perfiles_me where idmenu=1594";
		$resu = $this->queryArray($query);

		if($resu['rows'][0]['menu']>0){
			return array("food" => true);
		}else{
			 return array("food" => false);
		}
	}

	function checarPagos() {
		$verificaInicio = $this->verificainicioCaja();
		if ($verificaInicio == 'false') {
			return array("statusInicio" => false, "inicio" => $this->iniciocaja());
		}

		if (isset($_SESSION['pagos-caja']["pagos"])) {
			return array("status" => true, "pagos" => $_SESSION['pagos-caja']["pagos"], "abonado" => $_SESSION['pagos-caja']["Abonado"], "porPagar" => $_SESSION['pagos-caja']["porPagar"], "cambio" => $_SESSION['pagos-caja']["cambio"]);
		} else {
			return array("status" => false);
		}
	}

	function eliminarPago($pago) {

		$pagado = $_SESSION['pagos-caja']["pagos"][$pago]['cantidad'];
		unset($_SESSION['pagos-caja']["pagos"][$pago]);

		if (count($_SESSION['pagos-caja']["pagos"]) < 1) {
			unset($_SESSION['pagos-caja']["pagos"]);

			$_SESSION['pagos-caja']["Abonado"] = number_format(0, 2);
			$_SESSION['pagos-caja']["porPagar"] = number_format(0, 2);
			$_SESSION['pagos-caja']["cambio"] = number_format(0, 2);

			return array("status" => false);
		} else {

			$_SESSION['pagos-caja']["Abonado"] -= number_format($pagado, 2);
				//Aun por pagar en los casos que pagan con diferentes metodos.
			$porPagar = $_SESSION["caja"]["cargos"]["total"] - $_SESSION['pagos-caja']["Abonado"];
			if ($_SESSION['pagos-caja']["Abonado"] >= $_SESSION["caja"]["cargos"]["total"]) {
				$_SESSION['pagos-caja']["porPagar"] = number_format(0, 2);
			} else {
				$_SESSION['pagos-caja']["porPagar"] = number_format($porPagar, 2);
			}
			$_SESSION['pagos-caja']["cambio"] -= number_format($pagado, 2);
		}

		return array("status" => true, "abonado" => $_SESSION['pagos-caja']["Abonado"], "porPagar" => $_SESSION['pagos-caja']["porPagar"], "cambio" => $_SESSION['pagos-caja']["cambio"]);
	}

	function checatarjetaregalo($numero, $monto) {
		$tarjetaQuery = "select valor,usada,montousado from tarjeta_regalo where numero='" . $numero . "'";

		$resultTarjeta = $this->queryArray($tarjetaQuery);

		if ($resultTarjeta["total"] > 0) {
			$disponible = (float) $resultTarjeta["rows"][0]["valor"] - (float) $resultTarjeta["rows"][0]["montousado"];

			if ($disponible < $monto) {
				return array("status" => false, "msg" => "Saldo disponible en tarjeta de regalo:$" . number_format($disponible, 2));
			}

			if ($resultTarjeta["rows"][0]["usada"] == 1) {
				return array("status" => false, "msg" => "Se ha agotado el saldo de la tarjeta de regalo.");
			} else {
				return array("status" => true, "data" => $monto);
			}
		} else {
			return array("status" => false, "msg" => "No esta registrado este numero de tarjeta.");
		}
	}

	function checalimitecredito($cliente, $monto) {

		$querySaldo = "select sum(saldoactual) as debe from cxc where idCliente=" . $cliente;

		$result = $this->queryArray($querySaldo);

		if ($result["total"] > 0) {
			$queryCredito = "select limite_credito credito from comun_cliente where id=" . $cliente;

			$resultCredito = $this->queryArray($queryCredito);

			if ($resultCredito["total"] < 1) {
				return array("status" => false, "msg" => "Ocurrio un error al obtener los datos del cliente..");
			}
			$debe = $result["rows"][0]["debe"];
			$credito = $resultCredito["rows"][0]["credito"];

			$cargo = (float) ($debe + (float) ($monto));

			if ($cargo > $credito) {
				return array("status" => false, "msg" => "El limite de credito del cliente se ha excedido, su limite de credito es de $" . number_format($credito, 2) . " y actualmente tiene un monto por liquidar de $" . number_format($debe, 2));
			} else {
				return array("status" => true);
			}
		} else {
			return array("status" => false, "msg" => "Ocurrio un error al obtener los datos del cliente.");
		}
	}

	function guardarVenta($cliente, $idFact, $documento, $suspendida, $propina, $comentario) {
		try {
			if ($suspendida != 0) {
				$this->eliminarSuspendida($suspendida);
			}

			date_default_timezone_set("Mexico/General");
			$fechaactual = date("Y-m-d H:i:s");

			$_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);
			$_SESSION['pagos-caja'] = $this->object_to_array($_SESSION['pagos-caja']);

			$monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
			$cambio = str_replace(",", "", $_SESSION['pagos-caja']["cambio"]);

			foreach ($_SESSION["caja"]["cargos"]["impuestos"] as $key => $value) {
				$impuestos+= (float) str_replace(",", "", $value);
			}

			if (!is_numeric($cliente)) {
				$cliente = 'NULL';
			}
			$this->iniTrans();

			if ($_SESSION['pagos-caja']["porPagar"] != 0) {
				throw new Exception("No has cubierto el total de la compra.");
			}

			$selectid = "Select max(idVenta) as idVenta from venta for Update;";
			$result = $this->queryTrans($selectid);

			if ($result["rows"] < 1) {
				throw new Exception($result["msg"]);
			}

			$idVenta = $result["rows"][0]["idVenta"] + 1;
			$envioFac = 0;
			if($documento ==2){
				$envioFac = 1;
			}

			//Insert into venta
			$insertVenta = "	INSERT INTO
									venta (idVenta,idCliente,monto,estatus,idEmpleado,rfc,documento,fecha,
										cambio,montoimpuestos,idSucursal,envio) 
								VALUES (" . $idVenta . "," . $cliente . "," . $monto . ",1,
									" . $_SESSION['accelog_idempleado'] . ",''," . $documento . ",
									'" . $fechaactual . "'," . $cambio . ",'" . $impuestos . "',
									" . $_SESSION["sucursal"] . ",'".$envioFac."');";
			$result = $this->queryTrans($insertVenta);

			if ($result["total"] < 0) {
				throw new Exception("Error al registrar la venta 1. ".$insertVenta);
			}

			foreach (@$_SESSION['caja'] as $key => $producto) {
				if ($key != 'cargos') {
					$impuestos = 0;
					$producto = (object) $producto;

					foreach ($producto->cargos as $key2 => $value2) {
						$impuestos += (float) str_replace(",", "", $value2);
					}

					$descuentogeneral = ((1 - ((str_replace(",", "", $producto->descuento) * 100 / (str_replace(",", "", $producto->descuento) * $producto->precioventa)) / 100)) * $impuestos);
					//$producto->subtotal = ($producto->cantidad * $producto->precioventa - $producto->descuento);
					$total = ($producto->precioventa * $producto->cantidad + $impuestos) - str_replace(",", "", $producto->descuento);

					$selectid = "Select max(idventa_producto) as idVentap from venta_producto for Update;";
					$result = $this->queryTrans($selectid);

					if ($result["rows"] < 1) {
						throw new Exception($result["msg"]);
					}

					$idVentap = $result["rows"][0]["idVentap"] + 1;
					$subtotalSTR = str_replace(",", "", $producto->subtotal);
					$insertVenta_Pro = "INSERT INTO venta_producto (idventa_producto,idProducto,cantidad,preciounitario,tipodescuento,descuento,subtotal,idVenta,impuestosproductoventa,montodescuento,total,arr_kit,comentario) "
						. "VALUES (" . $idVentap . "," . $producto->id . "," . $producto->cantidad . ",'" . $producto->precioventa . "','" . $producto->tipodescuento . "','" . $producto->descuento_cantidad . "'," . $producto->cantidad * $producto->precioventa . "," . $idVenta . ",'" . $impuestos . "','" . str_replace(",", "", $producto->descuento) . "','" . $total . "','NULL','" . $comentario . "')";
					//throw new Exception($insertVenta_Pro);

					$resultVenta_Pro = $this->queryTrans($insertVenta_Pro);
					if ($resultVenta_Pro["total"] < 0) {
						throw new Exception("Error al registrar la venta del producto 2. " . $resultVenta_Pro["msg"]);
					}

					if ($producto->id == '' || $producto->id == null) {
						throw new Exception("Error al registrar la venta del producto 3. ");
					}

					$selectProductoImpuesto = "select i.id idImpuesto,i.nombre as impuesto, pi.valor from producto_impuesto pi inner join impuesto i on i.id=pi.idImpuesto where idProducto=" . $producto->id;
					$resultProductoImpuesto = $this->queryTrans($selectProductoImpuesto);
					if ($resultProductoImpuesto["total"] < 0) {
						throw new Exception("Error al consultar los impuestos " . $resultProductoImpuesto["msg"]);
					}

					foreach ($resultProductoImpuesto["rows"] as $keyImpuesto => $valueImpuesto) {
						$insertventaproductoimpuesto = "insert into venta_producto_impuesto values(0," . $resultProductoImpuesto['insertId'] . "," . $valueImpuesto["idImpuesto"] . "," . $valueImpuesto["valor"] . ");";
						$resultventaproductoimpuesto = $this->queryTrans($insertventaproductoimpuesto);

						if ($resultventaproductoimpuesto["status"] < 0) {
							throw new Exception("Error al guardar los impuestos... " . $resultventaproductoimpuesto["msg"]);
						}
					}

					if ($_SESSION["simple"] && $producto->tipo_producto != 6) {
						if ($producto->tipo_producto == 4 || $producto->tipo_producto == 2) {
							$selectMaterial = "select s.cantidad,mm.idMaterial,mm.cantidad materia "
							. "from mrp_stock s,mrp_producto_material mm "
							. "where mm.idProducto=" . $producto->id . " "
							. "and s.idProducto=mm.idMaterial  "
							. "and s.idAlmacen=" . $_SESSION["almacen"] . " GROUP BY mm.idMaterial";

							$resultMaterial = $this->queryTrans($selectMaterial);

							$selectProduccion = " select cantidad "
							. "from mrp_detalle_orden_produccion "
							. "where idProducto=" . $producto->id;
							//echo $selectMaterial;
							//echo   $selectProduccion;             

							$resultProduccion = $this->queryTrans($selectProduccion);
							if ($resultMaterial["rows"][0] == '' || $resultProduccion["rows"][0] > 0) {
								//echo "entro al if";
								$updatestock = "Update mrp_stock "
								. "set cantidad=cantidad-" . $producto->cantidad . " "
								. "where  idAlmacen=" . $_SESSION["almacen"] . " "
								. "and idProducto=" . $producto->id;
								$resultStock = $this->queryTrans($updatestock);
							} else {

								foreach ($resultMaterial["rows"] as $key => $valAlmacen) {
									$updatestock = "Update mrp_stock "
									. "set cantidad=" . ($valAlmacen['cantidad'] - ($valAlmacen['materia'] * $producto->cantidad) ) . " "
									. "where  idAlmacen=" . $_SESSION["almacen"] . " "
									. "and idProducto=" . $valAlmacen['idMaterial'];
									$resultStock = $this->queryTrans($updatestock);

									if ($resultStock["total"] < 0) {
										throw new Exception("Error al actualizar el stock. " . $resultStock["msg"]);
									}
								}
							} //fin del else

							/*      $updatestock = "Update mrp_stock "
							. "set cantidad=" . ((float) $resultMaterial['rows'][0]['cantidad'] - $producto->cantidad) . " "
							. "where  idAlmacen=" . $_SESSION["almacen"] . " "
							. "and idProducto=" . $producto->id;
							echo 'Primero->'.$resultMaterial['rows'][0]['cantidad'];
							echo 'Segundo'.$producto->cantidad;
							echo '*'.$updatestock.'*';
							$resultStock = $this->queryTrans($updatestock); */
						} else {

							$selectMaterial = "select s.cantidad "
							. "from mrp_stock s "
							. "where  s.idProducto=" . $producto->id . ""
							. " and  s.idAlmacen=" . $_SESSION["almacen"];

							$resultMaterial = $this->queryTrans($selectMaterial);
							if ($resultMaterial["total"] < 0 || $resultMaterial["rows"][0]["cantidad"] <= 0 && $propina != $producto->id) {
								throw new Exception("No hay sufuciente existencia del producto -> " . $producto->nombre);
							}

							//Consultamos los datos para la conversion
							$queryConversion = $this->queryTrans("Select conversion from mrp_unidades where idUni = " . $producto->idunidad);
							if ($queryConversion["rows"]["conversion"][0] == '') {
								$queryConversion["rows"]["conversion"][0] = 1;
							}

							//echo ((float) $resultMaterial['rows'][0]['cantidad']  - $producto->cantidad);
							$updatestock = "Update mrp_stock "
							. "set cantidad=" . ((float) $resultMaterial['rows'][0]['cantidad'] - $producto->cantidad) . " "
							. "where  idAlmacen=" . $_SESSION["almacen"] . " "
							. "and idProducto=" . $producto->id;
							//echo 'X'.$updatestock.'X';
							//echo "(".(float) $resultMaterial['rows'][0]['cantidad'] ."-". (float) $queryConversion["rows"]["conversion"][0] ."-". $producto->cantidad.")";
							//throw new Exception("Error Processing Request", 1);

							$resultStock = $this->queryTrans($updatestock);
							if ($resultStock["total"] < 0) {
								throw new Exception("No se pudo actualizar el stock.. " . $resultStock["msg"]);
							}
						}
					}
				}
			}
			/* Array
			[1] => Array
			(
				[tipostr] => Efectivo
				[cantidad] => 2.00
				[referencia] =>
			)
			) */

			foreach ($_SESSION['pagos-caja']["pagos"] as $idFormapago => $value) {
				if ($value["cantidad"] > 0) {
					$cantidad = $value["cantidad"];
					$referencia = $value["referencia"];
					$selectid = "Select max(idventa_pagos) as idVentaPagos from venta_pagos for Update;";
					$result = $this->queryTrans($selectid);

					if ($result["rows"] < 1) {
						throw new Exception($result["msg"]);
					}

					$idVentaPagos = $result["rows"][0]["idVentaPagos"] + 1;
					//venta_pagos
					$insertVenta_Pagos = "INSERT INTO venta_pagos(idventa_pagos,idVenta,idFormapago,monto,referencia) "
					. "VALUES(" . $idVentaPagos . "," . $idVenta . "," . $idFormapago . "," . str_replace(",", "", $cantidad) . ",'" . $referencia . "')";

					$resultinsertVenta_Pagos = $this->queryTrans($insertVenta_Pagos);
					if ($resultinsertVenta_Pagos["total"] < 0) {
						throw new Exception("No se pudo guardar el cargo del pago. " . $resultinsertVenta_Pagos["msg"]);
					}

					//pago a credito
					if ($idFormapago == 6) {
						$diasCredito = "SELECT dias_credito from comun_cliente where id=" . $cliente;
						$resultdiasCredito = $this->queryTrans($diasCredito);

						foreach ($resultdiasCredito["rows"] as $key => $value2) {
							$nuevafecha = strtotime('+' . $value2["dias_credito"] . ' day', strtotime($fechaactual));
							$nuevafecha = date('Y-m-d H:i:s', $nuevafecha);
							//print_r($_SESSION['pagos-caja']["pagos"]);
							$cc = "INSERT INTO cxc (fechacargo, fechavencimiento, idVenta, monto, saldoabonado, saldoactual, estatus, idCliente, concepto) VALUES "
							. "('" . $fechaactual . "','" . $nuevafecha . "'," . $idVenta . "," . str_replace(",", "", $value["cantidad"]) . ",'0'," . str_replace(",", "", $value["cantidad"]) . ",'0'," . $cliente . ",'Venta a credito');";

							$resultCC = $this->queryTrans($cc);
							if ($resultCC["total"] < 0) {
								throw new Exception("Error al guardar las cuentas por cobrar. " . $resultCC["msg"]);
							}
						}
					}

					//tarjeta de regalo
					if ($idFormapago == 3) {

						unset($diasCredito);
						unset($diasC);
						unset($nuevafecha);

						$cc = "select numero,valor,usada,montousado from tarjeta_regalo where numero='" . $referencia . "'";

						$resultCC = $this->queryTrans($cc);

						foreach ($resultCC["rows"] as $key => $valueCC) {
							$cantidad = str_replace(',', '', $cantidad);
							$extensionconsulta = "";
							if (((float) $cantidad + (float) $valueCC["montousado"]) >= (float) $valueCC["valor"]) {
								$extensionconsulta = ",usada=1";
							}

							$updateTarjeta = "Update tarjeta_regalo "
							. "set montousado=" . str_replace(',', '', ((float) $cantidad + (float) $valueCC["montousado"])) . $extensionconsulta . " "
							. "where numero='" . $referencia . "'";

							$resultupdateTarjeta = $this->queryTrans($updateTarjeta);

							if ($resultupdateTarjeta["total"] < 0) {
								throw new Exception("Error al actualizar la tarjeta de regalo " . $resultupdateTarjeta["msg"]);
							}
						}
					}
				}
			}

			//simple
			//pago automatico (efectivo)
			/* if ($_SESSION["simple"]) {
				$simple = "INSERT INTO venta_pagos(idventa_pagos,idVenta,idFormapago,monto,referencia) "
				. "VALUES(''," . $result['insertId'] . ",1," . $cantidad . ",'')";

				$returnSimple = $this->queryTrans($simple);
				if ($returnSimple["total"] < 0) {
					throw new Exception("Error al guardar los pagos...");
				}
			} */

			//$this->rollback();
			//exit($idFact);
			//$this->facturar($idFact, ($monto - $impuestos), $impuestos, $result['insertId'], 1);
			//throw new Exception("stop");
			//print_r($_SESSION["caja"]);
			//throw new Exception("Fin", 1);

			$this->commit();

			// Si es comanda cambia el status y guarda el ID de la venta
			session_start();
			if (!empty($_SESSION['id_comanda'])) {
				$sql="	UPDATE
						com_comandas
					SET
						status=1, 
						id_venta=".$idVenta."
					WHERE
						id=".$_SESSION['id_comanda'];
				$result_comanda = $this -> query($sql);
				$_SESSION['id_comanda']='';
			}

			return array("status" => true, "idVenta" => $idVenta);
		} catch (Exception $e) {
			$this->rollback();
			return array("status" => false, "msg" => $e->getMessage());
		}
	}

	function suspenderVenta($idFact, $doc, $cliente, $nombre, $suspendida) {
		try {
			//Varificamos si existe una venta suspendida con el mismo id
			/* if ($suspendida != '') {
				$selectSuspendida = "select id,s_almacen from venta_suspendida where id='$suspendida'";
				$resultselectSuspendida = $this->queryArray($selectSuspendida);
				if ($resultselectSuspendida["total"] > 0) {
					$this->eliminarSuspendida($suspendida);
				}
			} */

			$this->iniTrans();
			date_default_timezone_set("Mexico/General");
			$fechaactual = date("Y-m-d H:i:s");
			$monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
			$cambio = str_replace(",", "", $_SESSION['pagos-caja']["cambio"]);

			foreach ($_SESSION["caja"]["cargos"]["impuestos"] as $key => $value) {
				$impuestos+=$value;
			}
			$almacen = $_SESSION["almacen"];
			$sucursal = $_SESSION["sucursal"];
			$empleado = $_SESSION['accelog_idempleado'];

			$arr = json_encode((object) $_SESSION['caja']);
			if (isset($_SESSION['pagos-caja'])) {
				$arr2 = json_encode((object) $_SESSION['pagos-caja']);
			} else {
				$arr2 = '{"pagos":{},"Abonado":"0.00","porPagar":"0.00","cambio":"0.00"}';
			}

			if ($idFact == '') {
				$idFact = 0;
			}

			//Guardamos la venta suspendida
			$insertVentaSuspendida = "INSERT INTO venta_suspendida (s_almacen,s_cambio,s_cliente,s_documento,s_empleado,s_funcion,s_idFact,s_impuestos,s_monto,s_pagoautomatico,s_sucursal,s_impuestost,arreglo1,arreglo2,identi,fecha) VALUES "
			. "('" . $almacen . "','" . $cambio . "'," . $cliente . ",'" . $doc . "'," . $empleado . ",'suspenderVenta'," . $idFact . ",'" . $impuestos . "','" . $monto . "',0,'" . $sucursal . "','" . $impuestos . "','" . $arr . "','" . $arr2 . "','" . $nombre . " - " . $fechaactual . " - $" . $monto . "','" . $fechaactual . "');";
			$resultinsertVentaSuspendida = $this->queryTrans($insertVentaSuspendida);

			if (!$resultinsertVentaSuspendida["status"]) {
				throw new Exception("Error al suspender la venta.");
			}

			foreach ($_SESSION['caja'] as $key => $value) {
				if ($key != 'cargos') {
					$value = (object) $value;
					$updateStock = "UPDATE mrp_stock "
					. "SET cantidad=cantidad-" . $value->cantidad . " "
					. "WHERE idProducto='$key' AND idAlmacen='$almacen'";

					$resultUpdateStock = $this->queryTrans($updateStock);

					if (!$resultUpdateStock["status"]) {
						throw new Exception("Error al actualizar el stock.");
					}
				}
			}

			unset($_SESSION['caja']);
			unset($_SESSION['pagos-caja']);
			$this->commit();
			$this->eliminarSuspendida($suspendida);
			return array("status" => true);
		} catch (Exception $e) {
			$this->rollback();
			return array("status" => false, "msg" => $e->getMessage());
		}
	}

	function cargarSuspendida($id_susp) {
		try {
			//Consultamos la informacion de la venta suspendida
			$datosSuspendida = "Select id, s_almacen, s_cambio, s_cliente, s_documento, s_empleado, s_funcion, s_idFact, s_impuestos, s_monto, s_pagoautomatico, s_sucursal, s_impuestost, arreglo1, arreglo2, identi, fecha, borrado";
			$datosSuspendida .= " from venta_suspendida ";
			$datosSuspendida .= " where id = " . $id_susp . " ";
			$resultSuspendida = $this->queryArray($datosSuspendida);

			if ($resultSuspendida["total"] > 0) {
				$pos = strpos($resultSuspendida["rows"][0]["arreglo1"], "\"\"");
				$json = json_decode($resultSuspendida["rows"][0]["arreglo1"], true);
				$error = json_last_error();

				if ($error === JSON_ERROR_NONE) {
					$_SESSION['caja'] = $json;
				} else {
					if ($pos === FALSE) {
						$json = $resultSuspendida["rows"][0]["arreglo1"];
					} else {
						$json = str_replace("\"\"", null, $resultSuspendida["rows"][0]["arreglo1"]);
					}

					$pos2 = strpos($json, "\\");
					if (!$pos2 === FALSE) {
						$json = str_replace("\\", null, $json);
					}
					$_SESSION['caja'] = json_decode($json, true);
				}

				if ($resultSuspendida["rows"][0]["arreglo2"] != null) {
					$_SESSION['pagos-caja'] = json_decode($resultSuspendida["rows"][0]["arreglo2"], true);
				}
			} else {
				throw new Exception("Ocurrio un error al consultar los datos de la caja.");
			}
			
			//Facturar(0,$monto,$impuestos,$idVenta,$bloqueo,1);
			return array('status' => true, "rows" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "simple" => $_SESSION["simple"], "cliente" => $resultSuspendida["rows"][0]["s_cliente"]);
		} catch (Exception $e) {
			return array("status" => false, "msg" => $e->getMessage());
		}
	}

	function eliminarSuspendida($id) {
		try {
			$this->iniTrans();
			$datosSuspendida = "SELECT arreglo1, s_almacen from venta_suspendida where id='$id' ";
			$resultDatos = $this->queryTrans($datosSuspendida);

			if ($resultDatos["rows"][0] != '') {
				$json = str_replace("\"\"", null, $resultDatos["rows"]["arreglo1"][0]);
				$json = str_replace("\\", null, $json);
				$json = json_decode($json, true);

				$almacen = $resultDatos["rows"]["s_almacen"][0];
				foreach ($json as $key => $value) {
					if ($key != 'cargos') {
						$updateStock = "UPDATE mrp_stock "
						. "SET cantidad=cantidad+" . $value['cantidad'] . " "
						. "WHERE idProducto='$key' AND idAlmacen='$almacen'";

						$resultUpdateStock = $this->queryTrans($updateStock);
						if (!$resultUpdateStock["status"]) {
							throw new Exception("Error al actualizar el stock.");
						}
					}
				}
			} else {
				throw new Exception("Ocurrio un error al consultar los datos de la venta.");
			}

			$eliminarSuspendida = "Delete from venta_suspendida where id =" . $id;
			$resutEliminaSuspendida = $this->queryTrans($eliminarSuspendida);
			if (!$resutEliminaSuspendida["status"]) {
				throw new Exception("No se pudo eliminar la venta suspendida.");
			}

			$this->commit();
			return array("status" => true);
		} catch (Exception $e) {
			$this->rollback();
			return array("status" => false, "msg" => $e->getMessage());
		}
	}

	function arrayToObject($d) {
		if (is_array($d)) {
			/*
			 * Return array converted to object
			 * Using __FUNCTION__ (Magic constant)
			 * for recursive call
			 */
			return (object) array_map(__FUNCTION__, $d);
		} else {
			// Return object
			return $d;
		}
	}

	function facturar($idFact, $idVenta, $bloqueo, $mensaje,$consumo) {
		$_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);
		$monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
		$impuestos = 0;
		$arraytmp = (object) $_SESSION['caja'];

		foreach ($arraytmp as $key => $producto) {
			if ($key != 'cargos') {
				$impuestos = 0;
				foreach ($producto->cargos as $key2 => $value2) {
					$impuestos += $value2;
				}
			}
		}

		if ($memsaje != false || $mensaje != '') {
			$updateVenta = $this->queryArray("Update venta set observacion = '" . $mensaje . "' where idVenta =" . $idVenta);
		}

		$folios = "SELECT serie,folio FROM pvt_serie_folio LIMIT 1";
		$data = $this->queryArray($folios);
		if ($data["total"] > 0) {
			$data = $data["rows"][0];
		}
	   /* $queryUpdateFolo = "UPDATE pvt_serie_folio SET folio=folio+1 where id=1";
		$this->query($queryUpdateFolo); */
		// Receptor
		//===============================================================

		$parametros['Receptor'] = array();
		if ($idFact == 0) {

			$parametros['Receptor']['RFC'] = "XAXX010101000";
		} else {
			$df = (object) $this->datosFacturacion($idFact);
			$parametros['Receptor']['RFC'] = $df->rfc;
			$parametros['Receptor']['RazonSocial'] = utf8_decode($df->razon_social);
			$parametros['Receptor']['Pais'] = utf8_decode($df->pais);
			$parametros['Receptor']['Calle'] = utf8_decode($df->domicilio);
			$parametros['Receptor']['NumExt'] = $df->num_ext;
			$parametros['Receptor']['Colonia'] = utf8_decode($df->colonia);
			$parametros['Receptor']['Municipio'] = utf8_decode($df->municipio);
			$parametros['Receptor']['Ciudad'] = utf8_decode($df->ciudad);
			$parametros['Receptor']['CP'] = $df->cp;
			$parametros['Receptor']['Estado'] = utf8_decode($df->estado);
			$parametros['Receptor']['Email1'] = $df->correo;
		}
		/*-------------Saber si es inadem -----------------------*/
		/*$cuponInadem = '';
		$queryIdReceptor = "SELECT nombre from comun_facturacion where rfc='".$parametros['Receptor']['RFC']."' order by nombre desc";
		$resultOne = $this->queryArray($queryIdReceptor);

		$queryCupon = "SELECT cupon from comun_cliente_inadem where idCliente=".$resultOne['rows'][0]['nombre'];
		$resultTwo = $this->queryArray($queryCupon);
		if($resultTwo['rows'][0] > 0){
			$cuponInadem = $resultTwo['rows'][0]['cupon'];
		}else{
			$cuponInadem = '';
		}   /*
		//echo 'eeeeee'.$cuponInadem;
		/*-------------------------------------------------------*/

		/*-----SAber si Tiene Foodwear-------*/
		$queryFood = "SELECT count(*) as food from accelog_perfiles_me where idmenu=1594";
		$resFood = $this->queryArray($queryFood);
		$hasFood = $resFood['rows'][0]['food'];

		//Obteniendo la descripcion de la forma de pago
		$formapago = "";
		$queryFormaPago = " SELECT nombre,referencia,claveSat from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;
		$resultqueryFormaPago = $this->queryArray($queryFormaPago);

		foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
			if (strlen($pagosValue["referencia"]) > 0) {
				//$formapago .= $pagosValue['claveSat'] . " Ref:" . $pagosValue['referencia'] . ",";
				//$formapago .= $pagosValue['claveSat'] . ",";
				$formapago .= $pagosValue['claveSat'] . ",";
				$refFormaPago = $pagosValue['referencia'];
			} else {
				$formapago .= $pagosValue['claveSat'] . ",";
				$refFormaPago = '';
			}
		}

		$formapago = substr($formapago, 0, strlen($formapago) - 1);
		if ($formapago == "") {
			$formapago = ".";
		}

		$Email = $df->correo;

		$parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
		$parametros['DatosCFD']['MetododePago'] = utf8_decode($formapago);
		$parametros['DatosCFD']['NumCtaPago'] = $refFormaPago;
		$parametros['DatosCFD']['Moneda'] = "MXP";
		$parametros['DatosCFD']['Subtotal'] = str_replace(",", "", number_format($_SESSION["caja"]["cargos"]["subtotal"],2));
		// $parametros['DatosCFD']['Subtotal'] = $parametros['DatosCFD']['Subtotal'] - 0.01;
		$parametros['DatosCFD']['Total'] = str_replace(",", "", number_format($_SESSION["caja"]["cargos"]["total"],2));
		// $parametros['DatosCFD']['Total'] = $parametros['DatosCFD']['Total'] - 0.01;
		$parametros['DatosCFD']['Serie'] = $data['serie'];
		$parametros['DatosCFD']['Folio'] = $data['folio'];
		$parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
		$parametros['DatosCFD']['MensajePDF'] = "";
		$parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";

		$x = 0;
		$textodescuento = "";

		foreach ($_SESSION['caja'] as $key => $producto) {
			if ($key != 'cargos') {
				$producto = (object) $producto;
				$descuentogeneral = 0;
				//echo "( descuento -> ".$producto->descuento_cantidad.")";
				if ($producto->tipodescuento == "%") {
					$descuentogeneral = (($producto->precioventa * str_replace(",", "", $producto->descuento)) / 100) * $producto->cantidad;
					if ($producto->descuento > 0) {
						$textodescuento.=" - " . cajaModel::cortadec(str_replace(",", "", $producto->descuento_cantidad)) . " %";
					}
				}
				if ($producto->tipodescuento == "$") {
					$descuentogeneral = $producto->descuento;
					if ($producto->descuento > 0) {
						$textodescuento.=" - $" . cajaModel::cortadec(str_replace(",", "", $producto->descuento_cantidad)) . "";
					}
				}

				$conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
				$conceptosDatos[$x]["Unidad"] = $producto->unidad;
				$conceptosDatos[$x]["Precio"] = $producto->precioventa;
				if ($producto->descripcion != '') {
				   /* if(($key==87 || $key=='87') && $cuponInadem!=''){
						$queryidcliente = "SELECT nombre from comun_facturacion where rfc='".$parametros['Receptor']['RFC']."' order by nombre desc";
						$resultidCliente = $this->queryArray($queryidcliente);
						$resultidCliente['rows'][0]['nombre'];
						
						$queryInadem = "SELECT *  from comun_cliente_inadem where idCliente=".$resultidCliente['rows'][0]['nombre'];
						$resultDatosInadem= $this->queryArray($queryInadem);
						$conceptosDatos[$x]["Descripcion"] = "SOLUCION:".$resultDatosInadem['rows'][0]['vitrina'].";VALE:".$resultDatosInadem['rows'][0]['cupon'].";FOLIO:".$resultDatosInadem['rows'][0]['folio_inadem'];
					}else{   */
						$conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion . " " . $textodescuento);
					//} 
				} else {
				   /* if(($key==87 || $key=='87') && $cuponInadem!=''){
						$queryidcliente = "SELECT nombre from comun_facturacion where rfc='".$parametros['Receptor']['RFC']."' order by nombre desc";
						$resultidCliente = $this->queryArray($queryidcliente);
						$resultidCliente['rows'][0]['nombre'];
						
						$queryInadem = "SELECT *  from comun_cliente_inadem where idCliente=".$resultidCliente['rows'][0]['nombre'];
						$resultDatosInadem= $this->queryArray($queryInadem);
						$conceptosDatos[$x]["Descripcion"] = "SOLUCION:".$resultDatosInadem['rows'][0]['vitrina'].";VALE:".$resultDatosInadem['rows'][0]['cupon'].";FOLIO:".$resultDatosInadem['rows'][0]['folio_inadem'];
					}else{  */
						$conceptosDatos[$x]["Descripcion"] = trim($producto->nombre . " " . $textodescuento);
					//} 
				}
				$textodescuento = '';
				$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precioventa - str_replace(",", "", $producto->descuento) );
				$consumoTotal +=  $conceptosDatos[$x]['Importe']*1;
				$x++;

				//print_r($conceptosDatos);

				$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
				$queryImpuestos .= " from impuesto i, mrp_producto p ";
				$queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
				$queryImpuestos .= " where p.idProducto=" . $producto->id . " and i.id=pi.idImpuesto ";
				$queryImpuestos .= " Order by pi.idImpuesto DESC ";
				//echo $queryImpuestos;
				$resultImpuestos = $this->queryArray($queryImpuestos);

				foreach ($resultImpuestos["rows"] as $key => $value) {

					if ($value["nombre"] == 'IEPS') {
						$calculos = str_replace(",", "", ((($producto->precioventa * $producto->cantidad - str_replace(",", "", $producto->descuento_neto) ) * $value["valor"])) / 100);
						$nn2[$value["nombre"]][$value["valor"]]["Valor"] += $calculos;
						$ieps = $calculos;
					} else {
						if ($ieps != 0) {
							$nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", (((($producto->precioventa * $producto->cantidad) + $ieps - str_replace(",", "", $producto->descuento_neto)) * $value["valor"]) ) / 100);
							//$nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", number_format((((($producto->precioventa * $producto->cantidad) - str_replace(",", "", $producto->descuento_neto)) * $value["valor"]) ) / 100, 2));
						} else {
							$nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", ((($producto->precioventa * $producto->cantidad - str_replace(",", "", $producto->descuento_neto)) * $value["valor"])) / 100);

							//echo "(".$producto->precioventa  ."*". $producto->cantidad ."-". str_replace(",", "", $producto->descuento_neto) ."*". $value["valor"].")";
						}
					}

					//$nn2[$value["nombre"]][$value["valor"]]["Valor"] = $_SESSION['caja']["cargos"]["impuestos"][$value["nombre"]];
				}
			}
		}

		//        unset($_SESSION['pagos-caja']);
		//        unset($_SESSION['caja']);
		/*$selectAdenda = "SELECT xml from pvt_addenda where id=1";
		$adenda = $this->queryArray($selectAdenda); */

		/* FACTURACION AZURIAN
		============================================================== */
		require_once('../../modulos/SAT/config.php');

		date_default_timezone_set("Mexico/General");
		$fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-7 minute"));

		$logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
		$logo = $this->queryArray($logo);
		$r3 = $logo["rows"][0];

		$qrpac = "SELECT pac FROM pvt_configura_facturacion WHERE id=1;";
		$respac = $this->queryArray($qrpac);
		$pac = $respac["rows"][0]["pac"]; 

		$azurian = array();
		if ($bloqueo == 0) {
			$queryConfiguracion = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";
			$returnConfiguracion = $this->queryArray($queryConfiguracion);
			if ($returnConfiguracion["total"] > 0) {
				$r = (object) $returnConfiguracion["rows"][0];

				/* DATOS OBLIGATORIOS DEL EMISOR
				================================================================== */
				$rfc_cliente = $r->rfc;

				$parametros['EmisorTimbre'] = array();
				$parametros['EmisorTimbre']['RFC'] = $r->rfc;
				$parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
				$parametros['EmisorTimbre']['Pais'] = $r->pais;
				$parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
				$parametros['EmisorTimbre']['Calle'] = $r->calle;
				$parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
				$parametros['EmisorTimbre']['Colonia'] = $r->colonia;
				$parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
				$parametros['EmisorTimbre']['Municipio'] = $r->municipio;
				$parametros['EmisorTimbre']['Estado'] = $r->estado;
				$parametros['EmisorTimbre']['CP'] = $r->cp;
				$cer_cliente = $pathdc . '/' . $r->cer;
				$key_cliente = $pathdc . '/' . $r->llave;
				$pwd_cliente = $r->clave;
			} else {

				$JSON = array('success' => 0,
					'error' => 1001,
					'mensaje' => 'No existen datos de emisor.');
				echo json_encode($JSON);
				exit();
			}
		}

		/* Observaciones pdf */
		$azurian['Observacion']['Observacion'] = $mensaje;
		
		if($consumo == 1 || $consumo=='1'){
			//echo $consumoTotal;
			$precioSiniva = $parametros['DatosCFD']['Subtotal'] / 1.16;
			//echo $ivaCon;
			$elIva = $precioSiniva * 0.16;
			// $subTotalCon = $parametros['DatosCFD']['Total'] - $ivaCon;
			$parametros['DatosCFD']['Subtotal'] = $precioSiniva;
			$nn2["IVA"]["16.00"]["Valor"] = $elIva;
			//echo 'sub'.$parametros['DatosCFD']['Subtotal'];
		} 
		/* CORREO RECEPTOR
		============================================================== */
		if ($nn2 == '') {
			$nn2["IVA"]["0.0"]["Valor"] = 0.00;
		}
		$nn = $nn2;
		$azurian['nn']['nn'] = $nn;
		$azurian['org']['logo'] = $r3["logoempresa"];

		/* CORREO RECEPTOR
		============================================================== */
		$azurian['Correo']['Correo'] = $Email;

		/* Datos Basicos
		============================================================== */
		$azurian['Basicos']['Moneda'] = $parametros['DatosCFD']['Moneda'];
		$azurian['Basicos']['metodoDePago'] = $parametros['DatosCFD']['MetododePago'];
		$azurian['Basicos']['NumCtaPago'] = $parametros['DatosCFD']['NumCtaPago'];
		$azurian['Basicos']['LugarExpedicion'] = $parametros['DatosCFD']['LugarDeExpedicion'];
		$azurian['Basicos']['version'] = '3.2';
		$azurian['Basicos']['serie'] = $parametros['DatosCFD']['Serie']; //No obligatorio
		$azurian['Basicos']['folio'] = $parametros['DatosCFD']['Folio']; //No obligatorio
		$azurian['Basicos']['fecha'] = $fecha;
		$azurian['Basicos']['sello'] = '';
		$azurian['Basicos']['formaDePago'] = $parametros['DatosCFD']['FormadePago'];
		$azurian['Basicos']['tipoDeComprobante'] = 'ingreso';
		$azurian['tipoFactura'] = 'factura';
		$azurian['Basicos']['noCertificado'] = '';
		$azurian['Basicos']['certificado'] = '';
		$str_subtotal = number_format($parametros['DatosCFD']['Subtotal'], 2);
		$azurian['Basicos']['subTotal'] = str_replace(",", "", $str_subtotal);
		$str_total = number_format($parametros['DatosCFD']['Total'], 2);
		$str_total = str_replace(',', '',$str_total);
		//$str_total = $str_total - 0.01;
		//$str_total = number_format($str_total,0).'.00';  //Comente para que Salgan Decimales Normalmente
		$str_total = number_format($str_total,2);
		$azurian['Basicos']['total'] = str_replace(",", "", $str_total);    

		/* Datos Emisor
		============================================================== */

		$azurian['Emisor']['rfc'] = strtoupper($parametros['EmisorTimbre']['RFC']);
		$azurian['Emisor']['nombre'] = strtoupper($parametros['EmisorTimbre']['RazonSocial']);

		/* Datos Fiscales Emisor
		============================================================== */

		$azurian['FiscalesEmisor']['calle'] = $parametros['EmisorTimbre']['Calle'];
		$azurian['FiscalesEmisor']['noExterior'] = $parametros['EmisorTimbre']['NumExt'];
		$azurian['FiscalesEmisor']['colonia'] = $parametros['EmisorTimbre']['Colonia'];
		$azurian['FiscalesEmisor']['localidad'] = $parametros['EmisorTimbre']['Ciudad'];
		$azurian['FiscalesEmisor']['municipio'] = $parametros['EmisorTimbre']['Municipio'];
		$azurian['FiscalesEmisor']['estado'] = $parametros['EmisorTimbre']['Estado'];
		$azurian['FiscalesEmisor']['pais'] = $parametros['EmisorTimbre']['Pais'];
		$azurian['FiscalesEmisor']['codigoPostal'] = $parametros['EmisorTimbre']['CP'];

		/* Datos Regimen
		============================================================== */

		$azurian['Regimen']['Regimen'] = $parametros['EmisorTimbre']['RegimenFiscal'];

		/* Datos Receptor
		============================================================== */

		$azurian['Receptor']['rfc'] = strtoupper($parametros['Receptor']['RFC']);
		$azurian['Receptor']['nombre'] = strtoupper($parametros['Receptor']['RazonSocial']);

		/* Datos Domicilio Receptor
		============================================================== */
		$azurian['DomicilioReceptor']['calle'] = $parametros['Receptor']['Calle'];
		$azurian['DomicilioReceptor']['noExterior'] = $parametros['Receptor']['NumExt'];
		$azurian['DomicilioReceptor']['colonia'] = $parametros['Receptor']['Colonia'];
		$azurian['DomicilioReceptor']['localidad'] = $parametros['Receptor']['Ciudad'];
		$azurian['DomicilioReceptor']['municipio'] = $parametros['Receptor']['Municipio'];
		$azurian['DomicilioReceptor']['estado'] = $parametros['Receptor']['Estado'];
		$azurian['DomicilioReceptor']['pais'] = $parametros['Receptor']['Pais'];
		$azurian['DomicilioReceptor']['codigoPostal'] = $parametros['Receptor']['CP'];

		$conceptosOri = '';
		$conceptos = '';
		if($consumo == 1 || $consumo=='1'){
			unset($conceptosDatos);
			$conceptosDatos[0]["Cantidad"] = 1;
			$conceptosDatos[0]["Unidad"] = "No Aplica";
			$conceptosDatos[0]["Precio"] = $precioSiniva;
			$conceptosDatos[0]["Descripcion"] = "Consumo de Alimentos y bebidas";
			$conceptosDatos[0]["Importe"] = $precioSiniva;
		}
		
		foreach ($conceptosDatos as $key => $value) {
			$value['Descripcion'] = preg_replace("/'/", "&apos;", $value['Descripcion']);
			$value['Descripcion'] = preg_replace('/"/', "&quot;", $value['Descripcion']); 
		   // $value['Descripcion'] = preg_replace('("|\')', "&apos;", $value['Descripcion']);
			$value['Descripcion'] = eregi_replace("[\n|\r|\n\r]", " ", $value['Descripcion']);
			$value['Descripcion'] = trim($value['Descripcion']); 

			$conceptosOri.='|' . $value['Cantidad'] . '|';
			$conceptosOri.=$value['Unidad'] . '|';
			$conceptosOri.=$value['Descripcion'] . '|';
			$conceptosOri.=str_replace(",", "", number_format($value['Precio'],2)) . '|';
			$conceptosOri.=str_replace(",", "", number_format($value['Importe'],2));
			$conceptos.="<cfdi:Concepto cantidad='" . $value['Cantidad'] . "' unidad='" . $value['Unidad'] . "' descripcion='" . $value['Descripcion'] . "' valorUnitario='" . str_replace(",", "", number_format($value['Precio'],2)) . "' importe='" . str_replace(",", "", number_format($value['Importe'],2)) . "'/>";
			$subTotImportes += (float) str_replace(",", "", number_format($value['Importe'],2));
		}
	


		$ivas = '';
		$tisr = 0.00;
		$tiva = 0.00;
		$tieps = 0.00;

		$oriisr = '';
		$oriiva = '';

		$isr = '';
		$iva = '';
		$azurian['Conceptos']['conceptos'] = $conceptos;
		$azurian['Conceptos']['conceptosOri'] = $conceptosOri;

		$traslads = '';
		$retenids = '';
		$haytras = 0;
		$hayret = 0;
		$trasladsimp = 0.00;
		$retenciones = 0.00;
		$trasxml = '';
		$retexml = '';
  
		foreach ($nn as $clave => $imm) {
			if ($clave == 'IEPS' || $clave == 'IVA') {
				$haytras = 1;
				foreach ($nn[$clave] as $clavetasa => $val) {
					if ($clave == 'IEPS') {
						$tieps+=number_format($val['Valor'], 2, '.', '');
					}
					if ($clave == 'IVA') {
						$tiva+=number_format($val['Valor'], 2, '.', '');
					}
					$traslads.='|' . $clave . '|';
				   // $traslads.='' . $clavetasa . '|';
					$traslads.='' . number_format($clavetasa,2) . '|';
					$traslads.=number_format($val['Valor'], 2, '.', '');
					$trasladsimp+=number_format($val['Valor'], 2, '.', '');
					$trasxml.="<cfdi:Traslado impuesto='" . $clave . "' tasa='" . number_format($clavetasa,2) . "' importe='" . number_format($val['Valor'], 2, '.', '') . "' />";
				}
			} elseif ($clave == 'ISR' || $clave == 'IVAR') {
				$hayret = 1;

				foreach ($nn[$clave] as $clavetasa => $val) {
				if($clave == 'IVAR'){
					$clave = substr($clave, 0, -1);
					$king = 1;
				} 
					$tisr+=number_format($val['Valor'], 2, '.', '');
					$retenids.='|' . $clave . '|';
					$retenidsT.='' . number_format($val['Valor'], 2, '.', '') . '|';
					$retenids.=number_format($val['Valor'], 2, '.', '');
					$retenciones+=number_format($val['Valor'], 2, '.', '');
					$retexml.="<cfdi:Retencion impuesto='" . $clave . "' importe='" . number_format($val['Valor'], 2, '.', '') . "' />";
					/*if($king ==1){
						$clave = 'IVAR';
						$king = 0;
					} */
				}
			}
		}
		$azurian['Impuestos']['totalImpuestosIeps'] = $tieps;
		if ($haytras == 1) {
			$iva.='<cfdi:Traslados>' . $trasxml . '</cfdi:Traslados>';
		} else {
			$traslads.='|IVA|';
			$traslads.='0.00|';
			$traslads.='0.00';
			$trasladsimp = '0.00';
			$iva.="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='0.00' importe='0.00' /></cfdi:Traslados>";
		}
		if ($hayret == 1) {
			$isr.='<cfdi:Retenciones>' . $retexml . '</cfdi:Retenciones>';
		}

		//echo $iva.'  '.$isr; exit();
		/*  foreach ($impuestosDatos as $key => $value) {
			if($value['TipoImpuesto']=='ISR' || $value['TipoImpuesto']=='isr' || $value['TipoImpuesto']=='Isr'){
				$isr="<cfdi:Retenciones><cfdi:Retencion impuesto='ISR' importe='";
				$tisr=($value['Importe']*1)+($tisr*1);
				$oriisr='|ISR|';
				$oriisr.=number_format($tisr,2,'.','').'|';
				$oriisr.=number_format($tisr,2,'.','');
			}

			if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
				$iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
				$tiva=($value['Importe']*1)+($tiva*1);
				$oriiva='|IVA|';
				$oriiva.='16|';
				$oriiva.=number_format($tiva,2,'.','').'|';
				$oriiva.=number_format($tiva,2,'.','');
			}

			if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
				$iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
				$tiva=($value['Importe']*1)+($tiva*1);
				$oriiva='|IVA|';
				$oriiva.='16|';
				$oriiva.=number_format($tiva,2,'.','').'|';
				$oriiva.=number_format($tiva,2,'.','');
			}
		}
		*/
		if($hayret == 1){
			$cadRet = '|'.str_replace(',', '', number_format($tisr,2));
		}else{
			$cadRet = '';
		}

		/*echo 'SubImportes='.$subTotImportes.'<br>';
		echo 'SubAzurian='.$azurian['Basicos']['subTotal'].'<br>';
		echo 'totimpuestos='.$trasladsimp.'<br>';
		echo 'TotalAzurian='.$azurian['Basicos']['total'].'<br>';  */

		$xsubT =  number_format($subTotImportes, 2, '.', '');
        $xsubA =  number_format($azurian['Basicos']['subTotal'], 2, '.', '');
       
        if($xsubT < $xsubA){
            $azurian['Basicos']['subTotal'] = $azurian['Basicos']['subTotal'] - 0.01;
            $trasladsimp = $trasladsimp + 0.01;
        }elseif($xsubT > $xsubA){
            if($trasladsimp > 0){
                $azurian['Basicos']['subTotal'] = $azurian['Basicos']['subTotal'] + 0.01;
                $trasladsimp = $trasladsimp - 0.01;
            }else{
                $azurian['Basicos']['subTotal'] = $azurian['Basicos']['subTotal'] + 0.01;
                $azurian['Basicos']['total'] = $azurian['Basicos']['total'] + 0.01;
            }
            
        } 

		$azurian['Impuestos']['isr'] = $retenids.$cadRet;
		$azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');
		$azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
		$azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');


	
		/*echo 'SubImportes='.$subTotImportes.'<br>';
		echo 'SubAzurian='.$azurian['Basicos']['subTotal'].'<br>';
		echo 'totimpuestos='.$azurian['Impuestos']['totalImpuestosTrasladados'].'<br>';
		echo 'TotalAzurian='.$azurian['Basicos']['total'];
		exit();   */


		


		/* if($iva!=''){
			$iva.=number_format($tiva,2,'.','')."'"." /></cfdi:Traslados>";
		}
		if($isr!=''){
			$isr.=number_format($tisr,2,'.','')."'"." /></cfdi:Retenciones>";
		} */
		$ivas.=$isr . $iva;

		$azurian['Impuestos']['ivas'] = $ivas;


		unset($_SESSION['pagos-caja']);
		unset($_SESSION['caja']);


		if($pac==2){
			require_once('../../modulos/SAT/funcionesSAT2.php');
		}else if($pac==1){
			require_once('../../modulos/lib/nusoap.php');
			require_once('../../modulos/SAT/funcionesSAT.php');  
		}
		//require_once('../../modulos/WS_facturacion.php');
	}

	function facturarRecibo($idFact, $idVenta, $bloqueo, $mensaje) {
		$_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);
		$monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
		$impuestos = 0;
		$arraytmp = (object) $_SESSION['caja'];

		foreach ($arraytmp as $key => $producto) {
			if ($key != 'cargos') {
				$impuestos = 0;
				foreach ($producto->cargos as $key2 => $value2) {
					$impuestos += $value2;
				}
			}
		}

		if ($memsaje != false || $mensaje != '') {
			$updateVenta = $this->queryArray("Update venta set observacion = '" . $mensaje . "' where idVenta =" . $idVenta);
		}

		$folios = "SELECT serie_r,folio_r FROM pvt_serie_folio LIMIT 1";
		$data = $this->queryArray($folios);
		if ($data["total"] > 0) {
			$data = $data["rows"][0];
		}

		$df = (object) $this->datosFacturacion($idFact);
		// Receptor
		//===============================================================
		$parametros['Receptor'] = array();
		$parametros['Receptor']['RFC'] = $df->rfc;
		$parametros['Receptor']['RazonSocial'] = utf8_decode($df->razon_social);
		$parametros['Receptor']['Pais'] = utf8_decode($df->pais);
		$parametros['Receptor']['Calle'] = utf8_decode($df->domicilio);
		$parametros['Receptor']['NumExt'] = $df->num_ext;
		$parametros['Receptor']['Colonia'] = utf8_decode($df->colonia);
		$parametros['Receptor']['Municipio'] = utf8_decode($df->municipio);
		$parametros['Receptor']['Ciudad'] = utf8_decode($df->ciudad);
		$parametros['Receptor']['CP'] = $df->cp;
		$parametros['Receptor']['Estado'] = utf8_decode($df->estado);
		$parametros['Receptor']['Email1'] = $df->correo;

		//Obteniendo la descripcion de la forma de pago
		$formapago = "";
		$queryFormaPago = " select nombre,referencia from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;
		$resultqueryFormaPago = $this->queryArray($queryFormaPago);

		foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
			if (strlen($pagosValue["referencia"]) > 0) {
				$formapago .= $pagosValue['nombre'] . " Ref:" . $pagosValue['referencia'] . ",";
			} else {
				$formapago .= $pagosValue['nombre'] . ",";
			}
		}

		$formapago = substr($formapago, 0, strlen($formapago) - 1);
		if ($formapago == "") {
			$formapago = ".";
		}

		$Email = $df->correo;

		$parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
		$parametros['DatosCFD']['MetododePago'] = utf8_decode($formapago);
		$parametros['DatosCFD']['Moneda'] = "MXP";
		$parametros['DatosCFD']['Subtotal'] = str_replace(",", "", $_SESSION["caja"]["cargos"]["subtotal"]);
		$parametros['DatosCFD']['Total'] = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
		$parametros['DatosCFD']['Serie'] = $data['serie'];
		$parametros['DatosCFD']['Folio'] = $data['folio'];
		$parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
		$parametros['DatosCFD']['MensajePDF'] = "";
		$parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";

		$x = 0;
		$textodescuento = "";
		foreach ($_SESSION['caja'] as $key => $producto) {
			if ($key != 'cargos') {
				$producto = (object) $producto;
				$descuentogeneral = 0;
				//echo "( descuento -> ".$producto->descuento_cantidad.")";
				if ($producto->tipodescuento == "%") {
					$descuentogeneral = (($producto->precioventa * str_replace(",", "", $producto->descuento)) / 100) * $producto->cantidad;
					if ($producto->descuento > 0) {
						$textodescuento.=" - " . cajaModel::cortadec(str_replace(",", "", $producto->descuento_cantidad)) . " %";
					}
				}
				if ($producto->tipodescuento == "$") {
					$descuentogeneral = $producto->descuento;
					if ($producto->descuento > 0) {
						$textodescuento.=" - $" . cajaModel::cortadec(str_replace(",", "", $producto->descuento_cantidad)) . "";
					}
				}

				$conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
				$conceptosDatos[$x]["Unidad"] = $producto->unidad;
				$conceptosDatos[$x]["Precio"] = $producto->precioventa;
				if ($producto->descripcion != '') {
					$conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion . " " . $textodescuento);
				} else {
					$conceptosDatos[$x]["Descripcion"] = trim($producto->nombre . " " . $textodescuento);
				}
				$textodescuento = '';
				$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precioventa - str_replace(",", "", $producto->descuento) );
				$x++;

				//print_r($conceptosDatos);
				$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
				$queryImpuestos .= " from impuesto i, mrp_producto p ";
				$queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
				$queryImpuestos .= " where p.idProducto=" . $producto->id . " and i.id=pi.idImpuesto ";
				$queryImpuestos .= " Order by pi.idImpuesto DESC ";
				$resultImpuestos = $this->queryArray($queryImpuestos);

				foreach ($resultImpuestos["rows"] as $key => $value) {
					if ($value["nombre"] == 'IEPS') {
						$calculos = str_replace(",", "", number_format(((($producto->precioventa * $producto->cantidad - $producto->descuento_neto ) * $value["valor"])) / 100, 2));
						$nn2[$value["nombre"]][$value["valor"]]["Valor"] = $calculos;
						$ieps = $calculos;
					} else {
						if ($ieps != 0) {
							$nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", number_format((((($producto->precioventa * $producto->cantidad) + $ieps - $producto->descuento_neto) * $value["valor"]) ) / 100, 2));
						} else {
							$nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", number_format(((($producto->precioventa * $producto->cantidad - $producto->descuento_neto) * $value["valor"])) / 100, 2));
						}
					}
					//$nn2[$value["nombre"]][$value["valor"]]["Valor"] = $_SESSION['caja']["cargos"]["impuestos"][$value["nombre"]];
				}
			}
		}

		//        unset($_SESSION['pagos-caja']);
		//        unset($_SESSION['caja']);

		/* FACTURACION AZURIAN
		============================================================== */
		require_once('../../modulos/SAT/config.php');

		date_default_timezone_set("Mexico/General");
		$fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-7 minute"));

		$logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
		$logo = $this->queryArray($logo);
		$r3 = $logo["rows"][0];

		$qrpac = "SELECT pac FROM pvt_configura_facturacion WHERE id=1;";
		$respac = $this->queryArray($qrpac);
		$pac = $respac["rows"][0]["pac"]; 

		$azurian = array();
		if ($bloqueo == 0) {
			$queryConfiguracion = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";
			$returnConfiguracion = $this->queryArray($queryConfiguracion);
			if ($returnConfiguracion["total"] > 0) {
				$r = (object) $returnConfiguracion["rows"][0];

				/* DATOS OBLIGATORIOS DEL EMISOR
				================================================================== */
				$rfc_cliente = $r->rfc;

				$parametros['EmisorTimbre'] = array();
				$parametros['EmisorTimbre']['RFC'] = $r->rfc;
				$parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
				$parametros['EmisorTimbre']['Pais'] = $r->pais;
				$parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
				$parametros['EmisorTimbre']['Calle'] = $r->calle;
				$parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
				$parametros['EmisorTimbre']['Colonia'] = $r->colonia;
				$parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
				$parametros['EmisorTimbre']['Municipio'] = $r->municipio;
				$parametros['EmisorTimbre']['Estado'] = $r->estado;
				$parametros['EmisorTimbre']['CP'] = $r->cp;
				$cer_cliente = $pathdc . '/' . $r->cer;
				$key_cliente = $pathdc . '/' . $r->llave;
				$pwd_cliente = $r->clave;
			} else {

				$JSON = array('success' => 0,
					'error' => 1001,
					'mensaje' => 'No existen datos de emisor.');
				echo json_encode($JSON);
				exit();
			}
		}

		/* Observaciones pdf */
		$azurian['Observacion']['Observacion'] = $mensaje;

		/* CORREO RECEPTOR
		============================================================== */
		if ($nn2 == '') {
			$nn2["IVA"]["0.0"]["Valor"] = 0.00;
		}
		$nn = $nn2;
		$azurian['nn']['nn'] = $nn;
		$azurian['org']['logo'] = $r3["logoempresa"];

		/* CORREO RECEPTOR
		============================================================== */
		$azurian['Correo']['Correo'] = $Email;

		/* Datos Basicos
		============================================================== */
		$azurian['Basicos']['Moneda'] = $parametros['DatosCFD']['Moneda'];
		$azurian['Basicos']['metodoDePago'] = $parametros['DatosCFD']['MetododePago'];
		$azurian['Basicos']['LugarExpedicion'] = $parametros['DatosCFD']['LugarDeExpedicion'];
		$azurian['Basicos']['version'] = '3.2';
		$azurian['Basicos']['serie'] = $parametros['DatosCFD']['Serie']; //No obligatorio
		$azurian['Basicos']['folio'] = $parametros['DatosCFD']['Folio']; //No obligatorio
		$azurian['Basicos']['fecha'] = $fecha;
		$azurian['Basicos']['sello'] = '';
		$azurian['Basicos']['formaDePago'] = $parametros['DatosCFD']['FormadePago'];
		$azurian['Basicos']['tipoDeComprobante'] = 'ingreso';
		$azurian['tipoFactura'] = 'recibo';
		$azurian['Basicos']['noCertificado'] = '';
		$azurian['Basicos']['certificado'] = '';
		$str_subtotal = number_format($parametros['DatosCFD']['Subtotal'], 2);
		$azurian['Basicos']['subTotal'] = str_replace(",", "", $str_subtotal);
		$str_total = number_format($parametros['DatosCFD']['Total'], 2);
		$azurian['Basicos']['total'] = str_replace(",", "", $str_total);

		/* Datos Emisor
		============================================================== */

		$azurian['Emisor']['rfc'] = strtoupper($parametros['EmisorTimbre']['RFC']);
		$azurian['Emisor']['nombre'] = strtoupper($parametros['EmisorTimbre']['RazonSocial']);

		/* Datos Fiscales Emisor
		============================================================== */

		$azurian['FiscalesEmisor']['calle'] = $parametros['EmisorTimbre']['Calle'];
		$azurian['FiscalesEmisor']['noExterior'] = $parametros['EmisorTimbre']['NumExt'];
		$azurian['FiscalesEmisor']['colonia'] = $parametros['EmisorTimbre']['Colonia'];
		$azurian['FiscalesEmisor']['localidad'] = $parametros['EmisorTimbre']['Ciudad'];
		$azurian['FiscalesEmisor']['municipio'] = $parametros['EmisorTimbre']['Municipio'];
		$azurian['FiscalesEmisor']['estado'] = $parametros['EmisorTimbre']['Estado'];
		$azurian['FiscalesEmisor']['pais'] = $parametros['EmisorTimbre']['Pais'];
		$azurian['FiscalesEmisor']['codigoPostal'] = $parametros['EmisorTimbre']['CP'];

		/* Datos Regimen
		============================================================== */

		$azurian['Regimen']['Regimen'] = $parametros['EmisorTimbre']['RegimenFiscal'];

		/* Datos Receptor
		============================================================== */

		$azurian['Receptor']['rfc'] = strtoupper($parametros['Receptor']['RFC']);
		$azurian['Receptor']['nombre'] = strtoupper($parametros['Receptor']['RazonSocial']);

		/* Datos Domicilio Receptor
		============================================================== */

		$azurian['DomicilioReceptor']['calle'] = $parametros['Receptor']['Calle'];
		$azurian['DomicilioReceptor']['noExterior'] = $parametros['Receptor']['NumExt'];
		$azurian['DomicilioReceptor']['colonia'] = $parametros['Receptor']['Colonia'];
		$azurian['DomicilioReceptor']['localidad'] = $parametros['Receptor']['Ciudad'];
		$azurian['DomicilioReceptor']['municipio'] = $parametros['Receptor']['Municipio'];
		$azurian['DomicilioReceptor']['estado'] = $parametros['Receptor']['Estado'];
		$azurian['DomicilioReceptor']['pais'] = $parametros['Receptor']['Pais'];
		$azurian['DomicilioReceptor']['codigoPostal'] = $parametros['Receptor']['CP'];

		$conceptosOri = '';
		$conceptos = '';

		foreach ($conceptosDatos as $key => $value) {

			$value['Descripcion'] = preg_replace('("|\')', "", $value['Descripcion']);
			$value['Descripcion'] = eregi_replace("[\n|\r|\n\r]", " ", $value['Descripcion']);
			$value['Descripcion'] = trim($value['Descripcion']);

			$conceptosOri.='|' . $value['Cantidad'] . '|';
			$conceptosOri.=$value['Unidad'] . '|';
			$conceptosOri.=$value['Descripcion'] . '|';
			$conceptosOri.=str_replace(",", "", $value['Precio']) . '|';
			$conceptosOri.=str_replace(",", "", $value['Importe']);
			$conceptos.="<cfdi:Concepto cantidad='" . $value['Cantidad'] . "' unidad='" . $value['Unidad'] . "' descripcion='" . $value['Descripcion'] . "' valorUnitario='" . str_replace(",", "", $value['Precio']) . "' importe='" . str_replace(",", "", $value['Importe']) . "'/>";
		}

		$ivas = '';
		$tisr = 0.00;
		$tiva = 0.00;
		$tieps = 0.00;

		$oriisr = '';
		$oriiva = '';

		$isr = '';
		$iva = '';
		$azurian['Conceptos']['conceptos'] = $conceptos;
		$azurian['Conceptos']['conceptosOri'] = $conceptosOri;

		$traslads = '';
		$retenids = '';
		$haytras = 0;
		$hayret = 0;
		$trasladsimp = 0.00;
		$retenciones = 0.00;
		$trasxml = '';
		$retexml = '';

		foreach ($nn as $clave => $imm) {
			if ($clave == 'IEPS' || $clave == 'IVA') {
				$haytras = 1;
				foreach ($nn[$clave] as $clavetasa => $val) {
					if ($clave == 'IEPS') {
						$tieps+=number_format($val['Valor'], 2, '.', '');
					}
					if ($clave == 'IVA') {
						$tiva+=number_format($val['Valor'], 2, '.', '');
					}
					$traslads.='|' . $clave . '|';
					$traslads.='' . $clavetasa . '|';
					$traslads.=number_format($val['Valor'], 2, '.', '');
					$trasladsimp+=number_format($val['Valor'], 2, '.', '');
					$trasxml.="<cfdi:Traslado impuesto='" . $clave . "' tasa='" . $clavetasa . "' importe='" . number_format($val['Valor'], 2, '.', '') . "' />";
				}
			} elseif ($clave == 'ISR') {
				$hayret = 1;
				foreach ($nn[$clave] as $clavetasa => $val) {
					$tisr+=number_format($val['Valor'], 2, '.', '');
					$retenids.='|' . $clave . '|';
					$retenids.='' . number_format($val['Valor'], 2, '.', '') . '|';
					$retenids.=number_format($val['Valor'], 2, '.', '');
					$retenciones+=number_format($val['Valor'], 2, '.', '');
					$retexml.="<cfdi:Retencion impuesto='" . $clave . "' importe='" . number_format($val['Valor'], 2, '.', '') . "' />";
				}
			}
		}
		$azurian['Impuestos']['totalImpuestosIeps'] = $tieps;
		if ($haytras == 1) {
			$iva.='<cfdi:Traslados>' . $trasxml . '</cfdi:Traslados>';
		} else {
			$traslads.='|IVA|';
			$traslads.='0.00|';
			$traslads.='0.00';
			$trasladsimp = '0.00';
			$iva.="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='0.00' importe='0.00' /></cfdi:Traslados>";
		}
		if ($hayret == 1) {
			$isr.='<cfdi:Retenciones>' . $retexml . '</cfdi:Retenciones>';
		}
		//echo $iva.'  '.$isr; exit();
		/*  foreach ($impuestosDatos as $key => $value) {
			if($value['TipoImpuesto']=='ISR' || $value['TipoImpuesto']=='isr' || $value['TipoImpuesto']=='Isr'){
				$isr="<cfdi:Retenciones><cfdi:Retencion impuesto='ISR' importe='";
				$tisr=($value['Importe']*1)+($tisr*1);
				$oriisr='|ISR|';
				$oriisr.=number_format($tisr,2,'.','').'|';
				$oriisr.=number_format($tisr,2,'.','');
			}

			if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
				$iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
				$tiva=($value['Importe']*1)+($tiva*1);
				$oriiva='|IVA|';
				$oriiva.='16|';
				$oriiva.=number_format($tiva,2,'.','').'|';
				$oriiva.=number_format($tiva,2,'.','');
			}

				if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
					$iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
					$tiva=($value['Importe']*1)+($tiva*1);
					$oriiva='|IVA|';
					$oriiva.='16|';
					$oriiva.=number_format($tiva,2,'.','').'|';
					$oriiva.=number_format($tiva,2,'.','');
				}
			}
		*/

		$azurian['Impuestos']['isr'] = $retenids;
		$azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');
		$azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
		$azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');

		/* if($iva!=''){
		$iva.=number_format($tiva,2,'.','')."'"." /></cfdi:Traslados>";
		}
		if($isr!=''){
		$isr.=number_format($tisr,2,'.','')."'"." /></cfdi:Retenciones>";
		} */
		$ivas.=$isr . $iva;
		$azurian['Impuestos']['ivas'] = $ivas;

		unset($_SESSION['pagos-caja']);
		unset($_SESSION['caja']);

		if($pac==2){
			require_once('../../modulos/SAT/funcionesSAT2.php');
		}else if($pac==1){
			require_once('../../modulos/lib/nusoap.php');
			require_once('../../modulos/SAT/funcionesSAT.php');  
		}
		//require_once('../../modulos/WS_facturacion.php');
  	}

  	function cortadec($numero) {
  		if (preg_match('/\./', $numero)) {
			if (preg_match('/E/', $numero)) {
				return $numero;
			} else {
				$de = explode('.', $numero);
				$de[1] = substr($de[1], 0, 9);
				return $de[0] . '.' . $de[1];
			}
		} else {
			return $numero;
		}
	}

	function datosFacturacion($id) {
		if ($id != '') {
			$datosFacturacion = "Select nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
			e.estado estado,ciudad,municipio,regimen_fiscal
			from comun_facturacion cf left join estados e on  e.idestado=cf.estado
			where  id=" . $id;

			$result = $this->queryArray($datosFacturacion);

			if ($result["total"] > 0) {
				return $result["rows"][0];
			}
		} else {
			return false;
		}
	}

	function guardarFacturacion($UUID, $noCertificadoSAT, $selloCFD, $selloSAT, $FechaTimbrado, $idComprobante, $idFact, $idVenta, $noCertificado, $tipoComp, $monto, $cliente, $trackId, $idRefact, $azurian, $estatus) {
		$azurian = base64_encode($azurian);
		$fechaactual = preg_replace('/T/', ' ', $FechaTimbrado);
		if ($idRefact == 'c') {
			$tipoComp = 'C';
			$queryRespuesta = "UPDATE pvt_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta'";
			$this->queryArray($queryRespuesta);
		}

		$insertRespuestaFacturacion = "INSERT INTO pvt_respuestaFacturacion "
		. "(idSale,idFact,folio,factNum,serieCsdSat,serieCsdEmisor,selloDigitalSat,selloDigitalEmisor,fecha,borrado,tipoComp,idComprobante,cadenaOriginal) VALUES "
		. "('" . $idVenta . "','" . $idFact . "','" . $UUID . "','" . $trackId . "','" . $noCertificadoSAT . "','" . $noCertificado . "','" . $selloSAT . "','" . $selloCFD . "','" . $fechaactual . "',0,'" . $tipoComp . "','" . $idComprobante . "','" . $azurian . "');";

		$resultInsert = $this->queryArray($insertRespuestaFacturacion);
		$insertedId = $resultInsert["insertId"];


		if (is_numeric($insertedId)) {
			$queryUpdateContador = "UPDATE pvt_contadorFacturas set total=total+1 where id=1";
			$this->queryArray($queryUpdateContador);

			$ContadorLicencias = "UPDATE comun_parametros_licencias set valor=valor-1 where parametro='Facturas'";
			$this->queryArray($ContadorLicencias);

			if ($tipoComp == "R") {
				$queryUpdateFolo = "UPDATE pvt_serie_folio SET folio_r=folio_r+1 where id=1";
			} else {
				$queryUpdateFolo = "UPDATE pvt_serie_folio SET folio=folio+1 where id=1";
			}
			$this->queryArray($queryUpdateFolo); 
		}

		if (preg_match('/all/', $idRefact)) {
			$idRefact = preg_replace('/all/', '', $idRefact);
			$updatePendienteFactura = "UPDATE pvt_pendienteFactura SET facturado=1 WHERE id_sale in (" . $idRefact . ")";
			$this->queryArray($updatePendienteFactura);
		}

		if ($idRefact > 0 && $idRefact != 'c') {
			$updatePendienteFactura = "UPDATE pvt_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'";
			$this->queryArray($updatePendienteFactura);
		}
		$queryEnvio = "UPDATE venta set envio=2 where idVenta=".$idVenta;
		$this->queryArray($queryEnvio);
		
		return $insertedId;
	}

	function envioFactura($uid, $Email, $azurian, $doc) {

			//$azurian=json_decode($azurian);
		$azurian = cajaModel::object_to_array($azurian);
		$datosTimbrado = $azurian['datosTimbrado'];

		if ($azurian['FiscalesEmisor']['noExterior'] == '') {
			$nemi = '';
		} else {
			$nemi = ' #' . $azurian['FiscalesEmisor']['noExterior'];
		}

		if ($azurian['DomicilioReceptor']['noExterior'] == '') {
			$nrec = '';
		} else {
			$nrec = ' #' . $azurian['DomicilioReceptor']['noExterior'];
		}
			//print_r($azurian);


			//Obteniendo la descripcion de la forma de pago

			$idVenta = $azurian['datosTimbrado']['idVenta'];
			$formapago = "";

			$queryFormaPago = " SELECT nombre,referencia,claveSat from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;

			$resultqueryFormaPago = $this->queryArray($queryFormaPago);

			foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
				if (strlen($pagosValue["referencia"]) > 0) {
					//$formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'." Ref:" . $pagosValue['referencia'] . ",";
					$formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'.",";
					$refFormaPago = $pagosValue['referencia'];
					//$formapago .= $pagosValue['nombre'] . ",";
				} else {
					$formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'.",";
					$refFormaPago = '';
				}
			}

			$formapago = substr($formapago, 0, strlen($formapago) - 1);

			if ($formapago == "") {
				$formapago = ".";
			}

		include "../../modulos/SAT/PDF/CFDIPDF.php";

		$obj = new CFDIPDF( );

		if ($doc == 3) {
			$doc = "recibo";
		} else {
			$doc = "";
		}
		$azurian['Conceptos']['conceptosOri'] = preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptosOri']);
		$azurian['Conceptos']['conceptosOri'] = preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptosOri']);
			//$obj->ponerColor('#333333');
		$obj->datosCFD($datosTimbrado['UUID'], $azurian['Basicos']['serie'] . ' ' . $azurian['Basicos']['folio'], $datosTimbrado['noCertificado'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['noCertificadoSAT'], $azurian['Basicos']['formaDePago'], $azurian['Basicos']['tipoDeComprobante'], $doc);
		$obj->lugarE($azurian['Basicos']['LugarExpedicion']);
		$obj->datosEmisor($azurian['Emisor']['nombre'], $azurian['Emisor']['rfc'], $azurian['FiscalesEmisor']['calle'] . $nemi, $azurian['FiscalesEmisor']['localidad'], $azurian['FiscalesEmisor']['colonia'], $azurian['FiscalesEmisor']['municipio'], $azurian['FiscalesEmisor']['estado'], $azurian['FiscalesEmisor']['codigoPostal'], $azurian['FiscalesEmisor']['pais'], $azurian['Regimen']['Regimen']);
		$obj->datosReceptor($azurian['Receptor']['nombre'], $azurian['Receptor']['rfc'], $azurian['DomicilioReceptor']['calle'] . $nrec, $azurian['DomicilioReceptor']['localidad'], $azurian['DomicilioReceptor']['colonia'], $azurian['DomicilioReceptor']['municipio'], $azurian['DomicilioReceptor']['estado'], $azurian['DomicilioReceptor']['codigoPostal'], $azurian['DomicilioReceptor']['pais']);
		$obj->agregarConceptos($azurian['Conceptos']['conceptosOri']);
		$obj->agregarTotal($azurian['Basicos']['subTotal'], $azurian['Basicos']['total'], $azurian['nn']['nn']);
		$obj->agregarMetodo($formapago, $refFormaPago, 'MXN');
		$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
		$obj->agregarObservaciones($azurian['Observacion']['Observacion']);
		$obj->generar("../../netwarelog/archivos/1/organizaciones/" . $azurian['org']['logo'] . "", 0);
		$obj->borrarConcepto();

		$queryIdReceptor = "SELECT nombre from comun_facturacion where rfc='".$azurian['Receptor']['rfc']."' order by nombre desc";
		$resultOne = $this->queryArray($queryIdReceptor);

		/*$queryCupon = "SELECT cupon from comun_cliente_inadem where idCliente=".$resultOne['rows'][0]['nombre'];
		if($this->queryArray($queryCupon)){
			$resultTwo = $this->queryArray($queryCupon);
			$cuponInadem = $resultTwo['rows'][0]['cupon'];
		}else{
		   $resultTwo = '';
		   $cuponInadem = '';
		}  */

		$cuponInadem = '';
		if ($Email != '') {
			require_once('../../modulos/phpmailer/sendMail.php');

			$mail->From = "mailer@netwarmonitor.com";
			$mail->FromName = "NetwareMonitor";
			$mail->Subject = "Factura Generada";
			$mail->AltBody = "NetwarMonitor";
			$mail->MsgHTML('Factura Generada');
			if($cuponInadem==null || $cuponInadem==''){
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'.xml');
			$mail->AddAttachment('../../modulos/cont/xmls/facturas/temporales/'. $uid .'.xml');
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'.pdf');
			}else{
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cuponInadem.'.xml');
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cuponInadem.'.pdf');
			} 

			$mail->AddAddress($Email, $Email);


			@$mail->Send();
		}
		//$cuponInadem='';
	   if($cuponInadem ==null || $cuponInadem==''){
		return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => false);
	   }else{
		return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => $cuponInadem);
	   } 
	}

	function pendienteFacturacion($idFacturacion, $monto, $cliente, $idventa, $trackId, $azurian, $documento) {
	 
		$azurian = base64_encode($azurian);
		$fechaactual = date('Y-m-d H:i:s');
		$tipo = ($documento = 2 ? 'F' : 'R');

		if (is_numeric($cliente)) {
			$query = "insert into pvt_pendienteFactura values(''," . $idventa . ",'" . $fechaactual . "'," . $cliente . ",'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "');";
			$resultquery = $this->queryArray($query);

				//echo $query;
			return array("status" => true, "type" => 1);
		} else {
			$query = "insert into pvt_pendienteFactura values(''," . $idventa . ",'" . $fechaactual . "',NULL,'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "');";
				//echo $query;
			$resultquery = $this->queryArray($query);
			return array("status" => true, "type" => 2);
		}
	}

	function object_to_array($data) {
		if (is_array($data) || is_object($data)) {
			$result = array();
			foreach ($data as $key => $value) {
				$result[$key] = $this->object_to_array($value);
			}
			return $result;
		}
		return $data;
	}

	function variablesFlash() {
		$string = $this->query("select CONCAT(puerto, ',', baudios, ',', paridad, ',', bstop, ',', bits) str, bascula  from datos_bascula");
		$vars = $string->fetch_array();
		return $vars['str'] . "-" . $vars['bascula'];
	}

	function alter_() {
		$tablas = "";
		$bd = "_dbmlog0000001039";
		echo $bd . "<br/>";
		$query = "select TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = '$bd' and COLUMN_NAME like '%unid%';";

		$result = $this->queryArray($query);

		foreach ($result["rows"] as $key => $value) {
			$Select = "select COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS where TABLE_SCHEMA = '$bd' and TABLE_NAME = '" . $value["TABLE_NAME"] . "' and COLUMN_NAME like '%uni%';";
			$result2 = $this->queryArray($Select);
			$ftabla = strpos($tablas, $value["TABLE_NAME"]);
			$tablas .= $value["TABLE_NAME"] . "-";
			if ($ftabla === false) {
				foreach ($result2["rows"] as $key => $value2) {

					$pos = strpos($value["TABLE_NAME"], "vista");
						//if($value["TABLE_NAME"] != 'mrp_unidades' && $pos===false)
					if ($value["TABLE_NAME"] != 'mrp_unidades' && $pos === false) {
						echo "UPDATE IGNORE  " . $value["TABLE_NAME"] . " set " . $value2["COLUMN_NAME"] . " = 3 WHERE " . $value2["COLUMN_NAME"] . " = 2; <br/>";

						echo "UPDATE IGNORE  " . $value["TABLE_NAME"] . " set " . $value2["COLUMN_NAME"] . " = " . $value2["COLUMN_NAME"] . "+1 WHERE " . $value2["COLUMN_NAME"] . " > 1; <br/>";
					}
				}
			}
		}
	}

	function alter() {

		$query = "SELECT DISTINCT TABLE_SCHEMA ";
		$query .= "FROM INFORMATION_SCHEMA.COLUMNS ";
		$query .= "WHERE TABLE_NAME = 'mrp_producto' AND COLUMN_NAME = ' estatus' ";

		$result = $this->queryArray($query);

		foreach ($result["rows"] as $key => $value) {

			$query1 = "ALTER TABLE `" . $value["TABLE_SCHEMA"] . "`.`mrp_producto` DROP COLUMN ` estatus`;";

			$result = $this->queryArray($query1);
				//echo $query1."<br/>";

			echo "<script>console.log( 'Base : " . $value["TABLE_SCHEMA"] . " actualizada' );</script>";
		}
	}

	function generaCaja($idsVentas) {
		//cajaModel::simple();
		//cajaModel::propina();
		unset($_SESSION['pagos-caja']);
		unset($_SESSION['caja']);

		$idsVentas = explode('-', $idsVentas);
		$ventas = "Select * from venta where idVenta between " . $idsVentas[0] . " and " . $idsVentas[1];
		$ventas = $this->queryArray($ventas);

		foreach ($ventas["rows"] as $key => $value) {
			$productos = "Select vp.*,p.* from venta_producto vp, mrp_producto p ";
			$productos .= " where vp.idVenta = " . $value["idVenta"];
			$productos .= " and p.idProducto = vp.idProducto ";
			$productos = $this->queryArray($productos);

			foreach ($productos["rows"] as $keyP => $val) {
				cajaModel::agregaProducto($val["idProducto"], $val["cantidad"]);
			}

			$queryRfc = "select id , rfc from comun_facturacion where nombre=" . $value["idCliente"];
			$queryRfc = $this->queryArray($queryRfc);

			if ($queryRfc["rows"][0]["rfc"] == '') {
				$rfcCliente = 0;
			} else {
				$rfcCliente = $queryRfc["rows"][0]["id"];
			}
			cajaModel::facturarSimulacion($rfcCliente, $value["idVenta"], 0, $value["observacion"], $value["idCliente"], 0, 2);
		}
		return true;
	}

	function readFile($url){
		// echo '../.'.$url;
		$url = $url;
		//print_r($_SESSION['caja']);
		// exit();
		$codigoProducto = '';
		$cantidad = 0.00;
		$read = fopen($url, 'r') or die ('erro al leer');
		// var_dump($read);

		while (!feof($read)) {
			$linea=fgets($read);
			$jump=nl2br($linea);
			$x=substr($jump,1,31);
			$codigoProducto=substr($x,0,20);
			$cantidad=substr($x,20);

			// echo '('.$codigoProducto.'-'.$cantidad.')';
			$xy = $this->agregaProducto($codigoProducto, $cantidad);
		}
		fclose($read); 
		//print_r($_SESSION['caja']);
		return array("status" => true);
	}

	function facturarSimulacion($idFact, $idVenta, $bloqueo = 0, $mensaje, $cliente, $trackId, $documento) {
		//$_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);
		$impuestos = 0;
		$queryImpuestos = "select montoimpuestos,monto from venta where idVenta = ".$idVenta;
		$resultImpuestos = $this->queryArray($queryImpuestos);
		$monto = $resultImpuestos["rows"][0]["monto"];
		$arraytmp = (object) $_SESSION['caja'];

		foreach ($arraytmp as $key => $producto) {
			if ($key != 'cargos') {
				$impuestos = 0;
				foreach ($producto->cargos as $key2 => $value2) {
					$impuestos += $value2;
				}
			}
		}

		if ($memsaje != false || $mensaje != '') {
			$updateVenta = $this->queryArray("Update venta set observacion = '" . $mensaje . "' where idVenta =" . $idVenta);
		}

		$folios = "SELECT serie,folio FROM pvt_serie_folio LIMIT 1";

		$data = $this->queryArray($folios);
		if ($data["total"] > 0) {
			$data = $data["rows"][0];
		}

		// Receptor
		//===============================================================
		$parametros['Receptor'] = array();
		if ($idFact == 0) {

			$parametros['Receptor']['RFC'] = "XAXX010101000";
		} else {
			$df = (object) $this->datosFacturacion($idFact);
			$parametros['Receptor']['RFC'] = $df->rfc;
			$parametros['Receptor']['RazonSocial'] = utf8_decode($df->razon_social);
			$parametros['Receptor']['Pais'] = utf8_decode($df->pais);
			$parametros['Receptor']['Calle'] = utf8_decode($df->domicilio);
			$parametros['Receptor']['NumExt'] = $df->num_ext;
			$parametros['Receptor']['Colonia'] = utf8_decode($df->colonia);
			$parametros['Receptor']['Municipio'] = utf8_decode($df->municipio);
			$parametros['Receptor']['Ciudad'] = utf8_decode($df->ciudad);
			$parametros['Receptor']['CP'] = $df->cp;
			$parametros['Receptor']['Estado'] = utf8_decode($df->estado);
			$parametros['Receptor']['Email1'] = $df->correo;
		}

		//Obteniendo la descripcion de la forma de pago
		$formapago = "";
		$queryFormaPago = " select nombre,referencia from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;
		$resultqueryFormaPago = $this->queryArray($queryFormaPago);

		foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
			if (strlen($pagosValue["referencia"]) > 0) {
				$formapago .= $pagosValue['nombre'] . " Ref:" . $pagosValue['referencia'] . ",";
			} else {
				$formapago .= $pagosValue['nombre'] . ",";
			}
		}

		$formapago = substr($formapago, 0, strlen($formapago) - 1);

		if ($formapago == "") {
			$formapago = ".";
		}

		$Email = $df->correo;

		$parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
		$parametros['DatosCFD']['MetododePago'] = utf8_decode($formapago);
		$parametros['DatosCFD']['Moneda'] = "MXP";
		$parametros['DatosCFD']['Subtotal'] = $monto-$resultImpuestos["rows"][0]["montoimpuestos"];
		$parametros['DatosCFD']['Total'] = $monto;
		$parametros['DatosCFD']['Serie'] = $data['serie'];
		$parametros['DatosCFD']['Folio'] = $data['folio'];
		$parametros['DatosCFD']['TipodeComprobante'] = "F"; //F o C
		$parametros['DatosCFD']['MensajePDF'] = "";
		$parametros['DatosCFD']['LugarDeExpedicion'] = "Mexico";

		$x = 0;
		$textodescuento = "";

		foreach ($_SESSION['caja'] as $key => $producto) {
			if ($key != 'cargos') {
				$producto = (object) $producto;
				$descuentogeneral = 0;
				//echo "( descuento -> ".$producto->descuento_cantidad.")";
				if ($producto->tipodescuento == "%") {
					$descuentogeneral = (($producto->precioventa * str_replace(",", "", $producto->descuento)) / 100) * $producto->cantidad;
					if ($producto->descuento > 0) {
						$textodescuento.=" - " . cajaModel::cortadec(str_replace(",", "", $producto->descuento_cantidad)) . " %";
					}
				}
				if ($producto->tipodescuento == "$") {
					$descuentogeneral = $producto->descuento;
					if ($producto->descuento > 0) {
						$textodescuento.=" - $" . cajaModel::cortadec(str_replace(",", "", $producto->descuento_cantidad)) . "";
					}
				}

				$conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
				$conceptosDatos[$x]["Unidad"] = $producto->unidad;
				$conceptosDatos[$x]["Precio"] = $producto->precioventa;
				if ($producto->descripcion != '') {
					$conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion . " " . $textodescuento);
				} else {
					$conceptosDatos[$x]["Descripcion"] = trim($producto->nombre . " " . $textodescuento);
				}
				$textodescuento = '';
				$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precioventa - str_replace(",", "", $producto->descuento) );
				$x++;

				//print_r($conceptosDatos);
				/*$queryImpuestos = "select p.idProducto,p.precioventa, pi.valor, i.nombre";
				$queryImpuestos .= " from impuesto i, mrp_producto p ";
				$queryImpuestos .= " left join producto_impuesto pi on p.idProducto=pi.idProducto ";
				$queryImpuestos .= " where p.idProducto=" . $producto->id . " and i.id=pi.idImpuesto ";
				$queryImpuestos .= " Order by pi.idImpuesto DESC ";
				$resultImpuestos = $this->queryArray($queryImpuestos);
				foreach ($resultImpuestos["rows"] as $key => $value) {

					if ($value["nombre"] == 'IEPS') {
						$calculos = str_replace(",", "", number_format(((($producto->precioventa * $producto->cantidad - str_replace(",", "", $producto->descuento_neto) ) * $value["valor"])) / 100, 2));
						$nn2[$value["nombre"]][$value["valor"]]["Valor"] = $calculos;
						$ieps = $calculos;
					} else {
						if ($ieps != 0) {
							$nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", number_format((((($producto->precioventa * $producto->cantidad) + $ieps - str_replace(",", "", $producto->descuento_neto)) * $value["valor"]) ) / 100, 2));
						} else {
							$nn2[$value["nombre"]][$value["valor"]]["Valor"] += str_replace(",", "", number_format(((($producto->precioventa * $producto->cantidad - str_replace(",", "", $producto->descuento_neto)) * $value["valor"])) / 100, 2));

							//echo "(".$producto->precioventa  ."*". $producto->cantidad ."-". str_replace(",", "", $producto->descuento_neto) ."*". $value["valor"].")";
						}
					}

					//$nn2[$value["nombre"]][$value["valor"]]["Valor"] = $_SESSION['caja']["cargos"]["impuestos"][$value["nombre"]];
				}*/
			}
		}

		//        unset($_SESSION['pagos-caja']);
		//        unset($_SESSION['caja']);

		/* FACTURACION AZURIAN
		============================================================== */
		require_once('../../modulos/SAT/config.php');

		date_default_timezone_set("Mexico/General");
		$fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-7 minute"));
		$logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
		$logo = $this->queryArray($logo);
		$r3 = $logo["rows"][0];
		$azurian = array();

		if ($bloqueo == 0) {
			$queryConfiguracion = "SELECT a.*, b.regimen as regimenf FROM pvt_configura_facturacion a INNER JOIN pvt_catalogo_regimen b WHERE a.id=1 AND b.id=a.regimen;";
			$returnConfiguracion = $this->queryArray($queryConfiguracion);
			if ($returnConfiguracion["total"] > 0) {
				$r = (object) $returnConfiguracion["rows"][0];

				/* DATOS OBLIGATORIOS DEL EMISOR
				================================================================== */
				$rfc_cliente = $r->rfc;

				$parametros['EmisorTimbre'] = array();
				$parametros['EmisorTimbre']['RFC'] = $r->rfc;
				$parametros['EmisorTimbre']['RegimenFiscal'] = $r->regimenf;
				$parametros['EmisorTimbre']['Pais'] = $r->pais;
				$parametros['EmisorTimbre']['RazonSocial'] = $r->razon_social;
				$parametros['EmisorTimbre']['Calle'] = $r->calle;
				$parametros['EmisorTimbre']['NumExt'] = $r->num_ext;
				$parametros['EmisorTimbre']['Colonia'] = $r->colonia;
				$parametros['EmisorTimbre']['Ciudad'] = $r->ciudad; //Ciudad o Localidad
				$parametros['EmisorTimbre']['Municipio'] = $r->municipio;
				$parametros['EmisorTimbre']['Estado'] = $r->estado;
				$parametros['EmisorTimbre']['CP'] = $r->cp;
				$cer_cliente = $pathdc . '/' . $r->cer;
				$key_cliente = $pathdc . '/' . $r->llave;
				$pwd_cliente = $r->clave;
			} else {

				$JSON = array('success' => 0,
					'error' => 1001,
					'mensaje' => 'No existen datos de emisor.');
				echo json_encode($JSON);
				exit();
			}
		}
		/* Observaciones pdf */
		$azurian['Observacion']['Observacion'] = $mensaje;

		/* CORREO RECEPTOR
		============================================================== */
		$nn2["IVA"]["0.0"]["Valor"] = $resultImpuestos["rows"][0]["montoimpuestos"];
		$nn = $nn2;
		$azurian['nn']['nn'] = $nn;
		$azurian['org']['logo'] = $r3["logoempresa"];

		/* CORREO RECEPTOR
		============================================================== */
		$azurian['Correo']['Correo'] = $Email;

		/* Datos Basicos
		============================================================== */
		$azurian['Basicos']['Moneda'] = $parametros['DatosCFD']['Moneda'];
		$azurian['Basicos']['metodoDePago'] = $parametros['DatosCFD']['MetododePago'];
		$azurian['Basicos']['LugarExpedicion'] = $parametros['DatosCFD']['LugarDeExpedicion'];
		$azurian['Basicos']['version'] = '3.2';
		$azurian['Basicos']['serie'] = $parametros['DatosCFD']['Serie']; //No obligatorio
		$azurian['Basicos']['folio'] = $parametros['DatosCFD']['Folio']; //No obligatorio
		$azurian['Basicos']['fecha'] = $fecha;
		$azurian['Basicos']['sello'] = '';
		$azurian['Basicos']['formaDePago'] = $parametros['DatosCFD']['FormadePago'];
		$azurian['Basicos']['tipoDeComprobante'] = 'ingreso';
		$azurian['tipoFactura'] = 'factura';
		$azurian['Basicos']['noCertificado'] = '';
		$azurian['Basicos']['certificado'] = '';
		$str_subtotal = number_format($parametros['DatosCFD']['Subtotal'], 2);
		$azurian['Basicos']['subTotal'] = str_replace(",", "", $str_subtotal);
		$str_total = number_format($parametros['DatosCFD']['Total'], 2);
		$azurian['Basicos']['total'] = str_replace(",", "", $str_total);

		/* Datos Emisor
		============================================================== */

		$azurian['Emisor']['rfc'] = strtoupper($parametros['EmisorTimbre']['RFC']);
		$azurian['Emisor']['nombre'] = strtoupper($parametros['EmisorTimbre']['RazonSocial']);

		/* Datos Fiscales Emisor
		============================================================== */

		$azurian['FiscalesEmisor']['calle'] = $parametros['EmisorTimbre']['Calle'];
		$azurian['FiscalesEmisor']['noExterior'] = $parametros['EmisorTimbre']['NumExt'];
		$azurian['FiscalesEmisor']['colonia'] = $parametros['EmisorTimbre']['Colonia'];
		$azurian['FiscalesEmisor']['localidad'] = $parametros['EmisorTimbre']['Ciudad'];
		$azurian['FiscalesEmisor']['municipio'] = $parametros['EmisorTimbre']['Municipio'];
		$azurian['FiscalesEmisor']['estado'] = $parametros['EmisorTimbre']['Estado'];
		$azurian['FiscalesEmisor']['pais'] = $parametros['EmisorTimbre']['Pais'];
		$azurian['FiscalesEmisor']['codigoPostal'] = $parametros['EmisorTimbre']['CP'];

		/* Datos Regimen
		============================================================== */

		$azurian['Regimen']['Regimen'] = $parametros['EmisorTimbre']['RegimenFiscal'];

		/* Datos Receptor
		============================================================== */

		$azurian['Receptor']['rfc'] = strtoupper($parametros['Receptor']['RFC']);
		$azurian['Receptor']['nombre'] = strtoupper($parametros['Receptor']['RazonSocial']);

		/* Datos Domicilio Receptor
		============================================================== */

		$azurian['DomicilioReceptor']['calle'] = $parametros['Receptor']['Calle'];
		$azurian['DomicilioReceptor']['noExterior'] = $parametros['Receptor']['NumExt'];
		$azurian['DomicilioReceptor']['colonia'] = $parametros['Receptor']['Colonia'];
		$azurian['DomicilioReceptor']['localidad'] = $parametros['Receptor']['Ciudad'];
		$azurian['DomicilioReceptor']['municipio'] = $parametros['Receptor']['Municipio'];
		$azurian['DomicilioReceptor']['estado'] = $parametros['Receptor']['Estado'];
		$azurian['DomicilioReceptor']['pais'] = $parametros['Receptor']['Pais'];
		$azurian['DomicilioReceptor']['codigoPostal'] = $parametros['Receptor']['CP'];

		$conceptosOri = '';
		$conceptos = '';

		foreach ($conceptosDatos as $key => $value) {

			$conceptosOri.='|' . $value['Cantidad'] . '|';
			$conceptosOri.=$value['Unidad'] . '|';
			$conceptosOri.=$value['Descripcion'] . '|';
			$conceptosOri.=str_replace(",", "", $value['Precio']) . '|';
			$conceptosOri.=str_replace(",", "", $value['Importe']);
			$conceptos.="<cfdi:Concepto cantidad='" . $value['Cantidad'] . "' unidad='" . $value['Unidad'] . "' descripcion='" . $value['Descripcion'] . "' valorUnitario='" . str_replace(",", "", $value['Precio']) . "' importe='" . str_replace(",", "", $value['Importe']) . "'/>";
		}

		$ivas = '';
		$tisr = 0.00;
		$tiva = 0.00;
		$tieps = 0.00;

		$oriisr = '';
		$oriiva = '';

		$isr = '';
		$iva = '';
		$azurian['Conceptos']['conceptos'] = $conceptos;
		$azurian['Conceptos']['conceptosOri'] = $conceptosOri;

		$traslads = '';
		$retenids = '';
		$haytras = 0;
		$hayret = 0;
		$trasladsimp = 0.00;
		$retenciones = 0.00;
		$trasxml = '';
		$retexml = '';

		foreach ($nn as $clave => $imm) {
			if ($clave == 'IEPS' || $clave == 'IVA') {

				$haytras = 1;
				foreach ($nn[$clave] as $clavetasa => $val) {
					if ($clave == 'IEPS') {
						$tieps+=number_format($val['Valor'], 2, '.', '');
					}
					if ($clave == 'IVA') {
						$tiva+=number_format($val['Valor'], 2, '.', '');
					}
					$traslads.='|' . $clave . '|';
					$traslads.='' . $clavetasa . '|';
					$traslads.=number_format($val['Valor'], 2, '.', '');
					$trasladsimp+=number_format($val['Valor'], 2, '.', '');
					$trasxml.="<cfdi:Traslado impuesto='" . $clave . "' tasa='" . $clavetasa . "' importe='" . number_format($val['Valor'], 2, '.', '') . "' />";
				}
			} elseif ($clave == 'ISR') {
				$hayret = 1;
				foreach ($nn[$clave] as $clavetasa => $val) {
					$tisr+=number_format($val['Valor'], 2, '.', '');
					$retenids.='|' . $clave . '|';
					$retenids.='' . number_format($val['Valor'], 2, '.', '') . '|';
					$retenids.=number_format($val['Valor'], 2, '.', '');
					$retenciones+=number_format($val['Valor'], 2, '.', '');
					$retexml.="<cfdi:Retencion impuesto='" . $clave . "' importe='" . number_format($val['Valor'], 2, '.', '') . "' />";
				}
			}
		}
		$azurian['Impuestos']['totalImpuestosIeps'] = $tieps;
		if ($haytras == 1) {
			$iva.='<cfdi:Traslados>' . $trasxml . '</cfdi:Traslados>';
		} else {
			$traslads.='|IVA|';
			$traslads.='0.00|';
			$traslads.='0.00';
			$trasladsimp = '0.00';
			$iva.="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='0.00' importe='0.00' /></cfdi:Traslados>";
		}
		if ($hayret == 1) {
			$isr.='<cfdi:Retenciones>' . $retexml . '</cfdi:Retenciones>';
		}
		//echo $iva.'  '.$isr; exit();
		/*  foreach ($impuestosDatos as $key => $value) {


		  if($value['TipoImpuesto']=='ISR' || $value['TipoImpuesto']=='isr' || $value['TipoImpuesto']=='Isr'){
		  $isr="<cfdi:Retenciones><cfdi:Retencion impuesto='ISR' importe='";
		  $tisr=($value['Importe']*1)+($tisr*1);
		  $oriisr='|ISR|';
		  $oriisr.=number_format($tisr,2,'.','').'|';
		  $oriisr.=number_format($tisr,2,'.','');
		  }

		  if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
		  $iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
		  $tiva=($value['Importe']*1)+($tiva*1);
		  $oriiva='|IVA|';
		  $oriiva.='16|';
		  $oriiva.=number_format($tiva,2,'.','').'|';
		  $oriiva.=number_format($tiva,2,'.','');
		  }

		  if($value['TipoImpuesto']=='IVA' || $value['TipoImpuesto']=='iva' || $value['TipoImpuesto']=='Iva'){
		  $iva="<cfdi:Traslados><cfdi:Traslado impuesto='IVA' tasa='16' importe='";
		  $tiva=($value['Importe']*1)+($tiva*1);
		  $oriiva='|IVA|';
		  $oriiva.='16|';
		  $oriiva.=number_format($tiva,2,'.','').'|';
		  $oriiva.=number_format($tiva,2,'.','');
		  }
		  }
		 */

		$azurian['Impuestos']['isr'] = $retenids;
		$azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');

		$azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
		$azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');

		/* if($iva!=''){
		  $iva.=number_format($tiva,2,'.','')."'"." /></cfdi:Traslados>";
		  }
		  if($isr!=''){
		  $isr.=number_format($tisr,2,'.','')."'"." /></cfdi:Retenciones>";
	  } */
		$ivas.=$isr . $iva;

		$azurian['Impuestos']['ivas'] = $ivas;

		$azurian = base64_encode(json_encode($azurian));

		date_default_timezone_set("Mexico/General");
		$fechaactual = date("Y-m-d H:i:s");
		if (is_numeric($cliente)) {
			$query = "insert into pvt_pendienteFactura values(''," . $idVenta . ",'" . $fechaactual . "'," . $cliente . ",'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','F');";
			$resultquery = $this->queryArray($query);

				//(echo $query;
			unset($_SESSION['pagos-caja']);
			unset($_SESSION['caja']);
			return array("status" => true, "type" => 1);
		} else {
			$query = "insert into pvt_pendienteFactura values(''," . $idVenta . ",'" . $fechaactual . "',NULL,'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','F');";
				//echo $query;
			unset($_SESSION['pagos-caja']);
			unset($_SESSION['caja']);
			$resultquery = $this->queryArray($query);
				//return array("status" => true, "type" => 2);
		}
		//require_once('../../modulos/WS_facturacion.php');
	}



	function envioRecibo($uid, $Email, $azurian, $doc) {
		alert('Generando recibo, por favor espere...');

/*
			//$azurian=json_decode($azurian);
		$azurian = cajaModel::object_to_array($azurian);
		$datosTimbrado = $azurian['datosTimbrado'];

		if ($azurian['FiscalesEmisor']['noExterior'] == '') {
			$nemi = '';
		} else {
			$nemi = ' #' . $azurian['FiscalesEmisor']['noExterior'];
		}

		if ($azurian['DomicilioReceptor']['noExterior'] == '') {
			$nrec = '';
		} else {
			$nrec = ' #' . $azurian['DomicilioReceptor']['noExterior'];
		}
			//print_r($azurian);


			//Obteniendo la descripcion de la forma de pago

			$idVenta = $azurian['datosTimbrado']['idVenta'];
			$formapago = "";

			$queryFormaPago = " SELECT nombre,referencia,claveSat from venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;

			$resultqueryFormaPago = $this->queryArray($queryFormaPago);

			foreach ($resultqueryFormaPago["rows"] as $key => $pagosValue) {
				if (strlen($pagosValue["referencia"]) > 0) {
					$formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'." Ref:" . $pagosValue['referencia'] . ",";
					//$formapago .= $pagosValue['nombre'] . ",";
				} else {
					$formapago .= $pagosValue['nombre'] .'('.$pagosValue['claveSat'].')'.",";
				}
			}

			$formapago = substr($formapago, 0, strlen($formapago) - 1);

			if ($formapago == "") {
				$formapago = ".";
			}

		include "../../modulos/SAT/PDF/CFDIPDF.php";

		$obj = new CFDIPDF( );

		if ($doc == 3) {
			$doc = "recibo";
		} else {
			$doc = "";
		}
		$azurian['Conceptos']['conceptosOri'] = preg_replace('/&apos;/', "'", $azurian['Conceptos']['conceptosOri']);
		$azurian['Conceptos']['conceptosOri'] = preg_replace('/&quot;/', '"', $azurian['Conceptos']['conceptosOri']);
			//$obj->ponerColor('#333333');
		$obj->datosCFD($datosTimbrado['UUID'], $azurian['Basicos']['serie'] . ' ' . $azurian['Basicos']['folio'], $datosTimbrado['noCertificado'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['FechaTimbrado'], $datosTimbrado['noCertificadoSAT'], $azurian['Basicos']['formaDePago'], $azurian['Basicos']['tipoDeComprobante'], $doc);
		$obj->lugarE($azurian['Basicos']['LugarExpedicion']);
		$obj->datosEmisor($azurian['Emisor']['nombre'], $azurian['Emisor']['rfc'], $azurian['FiscalesEmisor']['calle'] . $nemi, $azurian['FiscalesEmisor']['localidad'], $azurian['FiscalesEmisor']['colonia'], $azurian['FiscalesEmisor']['municipio'], $azurian['FiscalesEmisor']['estado'], $azurian['FiscalesEmisor']['codigoPostal'], $azurian['FiscalesEmisor']['pais'], $azurian['Regimen']['Regimen']);
		$obj->datosReceptor($azurian['Receptor']['nombre'], $azurian['Receptor']['rfc'], $azurian['DomicilioReceptor']['calle'] . $nrec, $azurian['DomicilioReceptor']['localidad'], $azurian['DomicilioReceptor']['colonia'], $azurian['DomicilioReceptor']['municipio'], $azurian['DomicilioReceptor']['estado'], $azurian['DomicilioReceptor']['codigoPostal'], $azurian['DomicilioReceptor']['pais']);
		$obj->agregarConceptos($azurian['Conceptos']['conceptosOri']);
		$obj->agregarTotal($azurian['Basicos']['subTotal'], $azurian['Basicos']['total'], $azurian['nn']['nn']);
		$obj->agregarMetodo($formapago, '', 'MXN');
		$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
		$obj->agregarObservaciones($azurian['Observacion']['Observacion']);
		$obj->generar("../../netwarelog/archivos/1/organizaciones/" . $azurian['org']['logo'] . "", 0);
		$obj->borrarConcepto();

		$queryIdReceptor = "SELECT nombre from comun_facturacion where rfc='".$azurian['Receptor']['rfc']."' order by nombre desc";
		$resultOne = $this->queryArray($queryIdReceptor);

		/*$queryCupon = "SELECT cupon from comun_cliente_inadem where idCliente=".$resultOne['rows'][0]['nombre'];
		if($this->queryArray($queryCupon)){
			$resultTwo = $this->queryArray($queryCupon);
			$cuponInadem = $resultTwo['rows'][0]['cupon'];
		}else{
		   $resultTwo = '';
		   $cuponInadem = '';
		}  */

/*
		$cuponInadem = '';
		if ($Email != '') {
			require_once('../../modulos/phpmailer/sendMail.php');

			$mail->From = "mailer@netwarmonitor.com";
			$mail->FromName = "NetwareMonitor";
			$mail->Subject = "Factura Generada";
			$mail->AltBody = "NetwarMonitor";
			$mail->MsgHTML('Factura Generada');
			if($cuponInadem==null || $cuponInadem==''){
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'.xml');
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'.pdf');
			}else{
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cuponInadem.'.xml');
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'__'.str_replace(' ','_',$azurian['Receptor']['nombre']).'__'.$cuponInadem.'.pdf');
			} 

			$mail->AddAddress($Email, $Email);


			@$mail->Send();
		}
		//$cuponInadem='';
	   if($cuponInadem ==null || $cuponInadem==''){
		return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => false);
	   }else{
		return array("status" => true, "receptor" => str_replace(' ','_',$azurian['Receptor']['nombre']), "cupon" => $cuponInadem);
	   } 
*/
	}



}





?>
