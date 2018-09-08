<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL
require("models/connection_sqli_manual.php"); // funciones mySQLi

class CajaModel extends Connection {
	public function indexGridProductos(){
		$query = "SELECT * from app_productos where status=1 order by id asc";
		$rest = $this->queryArray($query);
		return $rest['rows'];
	}

	public function buscaClientes($term) {
		/*obtiene los clientes*/
		$queryClientes = "SELECT  id,nombre ";
		$queryClientes .= " FROM comun_cliente ";
		$queryClientes .= " WHERE nombre like '%" . $term . "%' order by nombre desc ";

		$result = $this->queryArray($queryClientes);
		//print_r($result["rows"]);
		return $result["rows"];
	}

	public function pintaRegistros() {
		//unset($_SESSION['caja']);
		//unset($_SESSION['pagos-caja']);
		//Consultamos si hay ventas suspendidas
		$suspendidas = '';
		$dataInicio = false;
		$selectVentasSuspendidas = "SELECT id,identi from app_pos_venta_suspendida ";
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
			return array('estatus' => true, "productos" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "simple" => $_SESSION["simple"], "suspendidas" => $suspendidas, "inicio" => $dataInicio, "sucursal" => $_SESSION["sucursalNombre"], "empleado" => $_SESSION["nombreEmpleado"]);
		} else {
			return array('status' => false, "suspendidas" => $suspendidas, "inicio" => $dataInicio);
		}
	}
	public function verificainicioCaja() {

		$empleado = "SELECT nombre from empleados where idEmpleado = " . $_SESSION['accelog_idempleado'];
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
		$qry2 .= "app_pos_inicio_caja ";
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
	public function iniciocaja() {

		$queryUsuarios = "SELECT au.idSuc,mp.nombre from administracion_usuarios au,mrp_sucursal mp where mp.idSuc=au.idSuc and au.idempleado=" . $_SESSION['accelog_idempleado'];

		$queryUsuarios = $this->queryArray($queryUsuarios);

		if ($queryUsuarios["total"] > 0) {

			$sucursal_operando = $queryUsuarios["rows"][0]["nombre"];
			$sucursal_id = $queryUsuarios["rows"][0]["idSuc"];

			//var_dump("select  cc.saldofinalcaja from inicio_caja i inner join corte_caja cc on i.idCortecaja=cc.idCortecaja where i.idSucursal=".$sucursal_id." order by i.fecha desc limit 1");

			$queryInicioCaja = "SELECT  cc.saldofinalcaja from app_pos_inicio_caja i inner join app_pos_corte_caja cc on i.idCortecaja=cc.idCortecaja where i.idSucursal=" . $sucursal_id . " order by i.fecha desc limit 1";

			$queryInicioCaja = $this->queryArray($queryInicioCaja);

			if ($queryInicioCaja["total"] > 0) {
				$saldoencaja = "$" . number_format($queryInicioCaja["rows"][0]["saldofinalcaja"], 2, ".", ",");
			} else {
				$saldoencaja = "$0.00";
			}

			return array("status" => 1, "sucursalNombre" => $sucursal_operando, "sucursalId" => $sucursal_id, "saldo" => $saldoencaja);
		} else {

			$cbo = '<select id="sucursal" name="sucursal" onchange="cargasaldocaja(this.value);" >';
			$query = "SELECT idSuc id,nombre  from mrp_sucursal";

			$query = $this->queryArray($query);

			return array("status" => 2, "rows" => $query["rows"]);
		}
	}
	public function buscaProductos($term){
		 $return = array();

		 $selctPro = "SELECT * from app_productos where nombre like '%".$term."%' and status=1";
		 $resutlSelec = $this->queryArray($selctPro);

		 foreach ($resutlSelec['rows'] as $key => $value) {
			  array_push($return, array('id' => $value["codigo"], 'label' => $value['codigo'] . " / " . $value['nombre']));
		 }
		 return $return;

	}
	public function crearOrden($datos){
		//echo $datos;
		
		date_default_timezone_set("Mexico/General");
		$fechaactual = date("Y-m-d H:i:s");
		
		$datos = explode('*', $datos);
	
		$provesdores = array();
		foreach ($datos as $key => $value) {
			$proveDatos = explode('_', $value);
			if( count($proveDatos) == 4) {
				if (array_key_exists($proveDatos[0], $provesdores)) {
					$provesdores[$proveDatos[0]][]=array('cantidad'=>$proveDatos[1],'producto'=>$proveDatos[2],'costo'=>$proveDatos[3]);
				}else{
					$provesdores[$proveDatos[0]][0]=array('cantidad'=>$proveDatos[1],'producto'=>$proveDatos[2],'costo'=>$proveDatos[3]);
				} 
			}
		}

		foreach ($provesdores as $key => $value) {
			if($key!=''){

				$insert1 = "INSERT INTO app_requisiciones (id_solicito,id_tipogasto,id_almacen,id_moneda,id_proveedor,observaciones,fecha,id_usuario,fecha_creacion, subtotal, total) 
							VALUES (".$_SESSION['accelog_idempleado'].", '7', '1', '1', $key, 'cotizacion requisicion', NOW(), ".$_SESSION['accelog_idempleado']." , NOW() , (subtotal), (total) )";
				$res1 = $this->queryArray($insert1);
				$idRequ = $res1['insertId'];

				$insert2 = "INSERT INTO app_ocompra (id_proveedor, id_usrcompra, observaciones, fecha, fecha_entrega, activo, id_requisicion, subtotal, total, id_almacen, autorizo, id_usuario, fecha_creacion ) 
							VALUES      ($key , " . $_SESSION['accelog_idempleado'] . " , 'cotizacion requisiciones', NOW(), NOW(), '1', $idRequ, (subtotal), (total), '1', '', " . $_SESSION['accelog_idempleado'] .", NOW() )";
				$res2 = $this->queryArray($insert2);
				$idOcompra = $res2['insertId'];

				foreach ($value as $key => $val) {
					$insert1 = "INSERT INTO app_requisiciones_datos ( id_requisicion, id_producto, almacen, estatus, activo, cantidad, caracteristica ) 
								VALUES      ($idRequ, ". $val['producto'] ." , '1', '1', '1', " . $val['cantidad'] . ", '0' )";
					$res1 = $this->queryArray($insert1);
					
					$sql = "SELECT  nombre, valor
							FROM    app_producto_impuesto ip, app_impuesto i
							WHERE   ip.id_impuesto = i.id AND ip.id_producto = ". $val['producto'] ." AND i.activo = '1'";
					$res = $this->queryArray($sql);
					$strImpuesto = "";
					$subtotal = 0;
					$impuestos = 0;
					$total = 0;
					$subtotal += floatval($val['costo']) * floatval($val['cantidad']);
					
					foreach ($res['rows'] as $key => $v) {
						if ($key != "0") $strImpuesto .= ",";

						$strImpuesto .= $v['nombre'] . "-" . $v['valor'] . "-" . ( floatval($val['costo']) * (floatval($v['valor']) / 100) * floatval($val['cantidad']) ) ;
						$impuestos += ( floatval($val['costo']) * (floatval($v['valor']) / 100) * floatval($val['cantidad']));
					}
					$total += $subtotal +  $impuestos;

					$sql ="UPDATE app_requisiciones
							SET subtotal='$subtotal', total='$total'
							WHERE id='$idRequ'"; 
					$this->queryArray($sql);
					$sql ="UPDATE app_ocompra
							SET subtotal='$subtotal', total='$total'
							WHERE id_requisicion='$idRequ'"; 
					$this->queryArray($sql);
					$insert2 = "INSERT INTO app_ocompra_datos (id_ocompra, id_producto, ses_tmp, estatus, activo, almacen, cantidad, costo, impuestos, caracteristica) 
								VALUES      ('$idOcompra', ". $val['producto'] .", '', '1', '1', '1', " . $val['cantidad'] . "," . $val['costo'] . ", '$strImpuesto' , '0')";
								
					$res2 = $this->queryArray($insert2);
				}
			}
		}
	}


	public function productosMoneda($moneda){
		$selectProd.="SELECT p.*, if(sum(vp.cantidad)!='', sum(vp.cantidad), 0) as cantidad";
		$selectProd.=" from app_productos p";
		$selectProd.=" left join app_pos_venta_producto vp on p.id=vp.idProducto";
		$selectProd.=" where p.status=1 and id_moneda=".$moneda;
		$selectProd.=" group by p.id";
		$selectProd.=" order by cantidad desc";

		$restSelec = $this->queryArray($selectProd);
		

		foreach ($restSelec['rows'] as $key => $value) {
			$imp = $this->calImpu($value['id'],$value['precio'],$value['formulaIeps']);
			$restSelec['rows'][$key]['precio']= $imp;
		} 
		
		return array('productos' => $restSelec['rows'], 'respuesta' => $restSelec['total']);
	}

	public function datosFacturacion($id) {
		if ($id != '') {
			$datosFacturacion = "SELECT nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
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
	public function agregaProducto($idProducto,$cantidadInicial,$caracteristicas,$cliente,$xyz){
		$xyz = 1;
		//$_SESSION['caja'] = (object) $_SESSION['caja'];
		$caras = '';
		$productosTotal = 0;
		//unset($_SESSION['caja']);
		//print_r($_SESSION['caja']);
		//print_r($_SESSION['pagos-caja']);
		//exit(); 
		//unset($_SESSION['caja']);
		$stringTaxes = '';
		//session_start();
		$select1 = "    SELECT 
							IF(f.descripcion!='', CONCAT(p.nombre, f.descripcion), p.nombre) AS nombre,
							p.id, p.codigo, p.descripcion_larga, p.id_unidad_venta, p.precio,
							p.ruta_imagen, p.formulaIeps, f.descripcion
						FROM 
							app_productos p
						LEFT JOIN
								app_campos_foodware f
							ON
								p.id=f.id_producto
						WHERE 
							p.codigo='".$idProducto."' and p.status=1";
						
		$resut1 = $this->queryArray($select1);
		if($resut1["total"] < 1){
			return array('estatus' =>false,'productos' =>$_SESSION['caja'], 'cargos' => $_SESSION['caja']['cargos'], 'totalProductos' => $productosTotal);

		}

		$select2 = "SELECT nombre from app_unidades_medida where id=".$resut1['rows'][0]['id_unidad_venta'];
		$result2 = $this->queryArray($select2);


		if($caracteristicas!=''){
			$idProdCar = $resut1['rows'][0]['id'].'_'.$caracteristicas;
		}else{
			$idProdCar = $resut1['rows'][0]['id'];
		}


		if(isset($_SESSION['caja'][$idProdCar])){

			if($xyz == 1){
				$_SESSION['caja'][$idProdCar]->cantidad= $_SESSION['caja'][$idProdCar]->cantidad + $cantidadInicial;
				$_SESSION['caja'][$idProdCar]->importe = $_SESSION['caja'][$idProdCar]->cantidad * $_SESSION['caja'][$idProdCar]->precio;
			}else{
				$_SESSION['caja'][$idProdCar]['cantidad']= $_SESSION['caja'][$idProdCar]['cantidad'] + $cantidadInicial;
				$_SESSION['caja'][$idProdCar]['importe'] = $_SESSION['caja'][$idProdCar]['cantidad'] * $_SESSION['caja'][$idProdCar]['precio'];
			}
			

			/*if($caracteristicas!=''){
				$caracteristicas2 =  explode("/", $caracteristicas);;
				foreach ($caracteristicas2 as $key => $value) {
					$expv=explode('=>', $value);
					$ip=$expv[0];
					$ih=$expv[1];
					$my = "SELECT concat('( ',a.nombre,': ',b.nombre,' )') as dcar FROM app_caracteristicas_padre a
					LEFT JOIN app_caracteristicas_hija b on b.id=".$ih."
					WHERE a.id=".$ip.";";
					$producto = $this->queryArray($my);
					$caras.= $producto['rows'][0]['dcar'];
				}
				$_SESSION['caja'][$resut1['rows'][0]['id'].'_'.$caracteristicas]->nombre = $_SESSION['caja'][$resut1['rows'][0]['id']]->nombre.' '.$caras;
			} */

		}else{

			$selecLis = "SELECT id_lista_precios from comun_cliente where id=".$cliente;
			$resLis = $this->queryArray($selecLis);

			if($resLis['total'] > 0){

				$selPrLis = "SELECT l.id, l.nombre, l.porcentaje, l.descuento, lp.id_producto";
				$selPrLis.=" from app_lista_precio l";
				$selPrLis.=" left join app_lista_precio_prods lp on lp.id_lista=l.id";
				$selPrLis.=" where l.id =".$resLis['rows'][0]['id_lista_precios']." and lp.id_producto=".$resut1['rows'][0]['id'];
				$resPrLis = $this->queryArray($selPrLis);
				//print_r($resPrLis);
				$idListaPrecios = $resLis['rows'][0]['id_lista_precios'];
				$descuento = 0;
				$precioFinal = 0;
				$descuento = $resut1['rows'][0]['precio'] * $resPrLis['rows'][0]['porcentaje']/ 100;
				if($resPrLis['rows'][0]['descuento'] == 1){
					$precioFinal = (float) $resut1['rows'][0]['precio'] - (float) $descuento;
				}else{
					$precioFinal = (float) $resut1['rows'][0]['precio'] + (float) $descuento;
				}

	
			}else{
				$precioFinal = $resut1['rows'][0]['precio'];
				$idListaPrecios = 0;
			} 

			session_start();

			$arraySession = new stdClass();
			if($caracteristicas!=''){
				$idProducto = $resut1['rows'][0]['id'].'_'.$caracteristicas;
			}else{
				$idProducto = $resut1['rows'][0]['id'];
			}
			$arraySession->idProducto = $resut1['rows'][0]['id'];
			$arraySession->codigo = $resut1['rows'][0]['codigo'];
			$arraySession->nombre = $resut1['rows'][0]['nombre'];
			$arraySession->descripcion = $resut1['rows'][0]['descripcion_larga'];
			$arraySession->unidad = $result2['rows'][0]['nombre'];
			$arraySession->idunidad = $resut1['rows'][0]['id_unidad_venta'];
			//$arraySession->precio = $resut1['rows'][0]['precio'];
			$arraySession->precio = $precioFinal;
			$arraySession->cantidad = $cantidadInicial;
			$arraySession->ruta_imagen = $resut1['rows'][0]['ruta_imagen'];
			$arraySession->importe = $precioFinal * $cantidadInicial;
			$arraySession->impuesto = '';
			$arraySession->suma_impuestos = '';
			$arraySession->cargos = '';
			$arraySession->formula = $resut1['rows'][0]['formulaIeps'];
			$arraySession->caracteristicas = $caracteristicas;

			$_SESSION['caja'][$idProducto] = $arraySession;

			///Caracteristicas
			if($caracteristicas!=''){
				$caracteristicas2 =  explode("*", $caracteristicas);
				foreach ($caracteristicas2 as $key => $value) {
					$expv=explode('=>', $value);
					$ip=$expv[0];
					$ih=$expv[1];
					$my = "SELECT concat('( ',a.nombre,': ',b.nombre,' )') as dcar FROM app_caracteristicas_padre a
					LEFT JOIN app_caracteristicas_hija b on b.id=".$ih."
					WHERE a.id=".$ip.";";
					$producto = $this->queryArray($my);
					$caras.= $producto['rows'][0]['dcar'];
				}
				$_SESSION['caja'][$resut1['rows'][0]['id'].'_'.$caracteristicas]->nombre = $_SESSION['caja'][$resut1['rows'][0]['id'].'_'.$caracteristicas]->nombre.' '.$caras;
			} 
		
		}
		

	   
		//$_SESSION['caja']['cargos']['subtotal'] = $subtotal;
		//$_SESSION['caja']['cargos']['total'] = $subtotal;

		$sessionArray = $this->object_to_array($_SESSION['caja']);

		foreach ($sessionArray as $key => $value) {
			if($key !='cargos' && $key!='descGeneral' && $key!='descGeneralCant'){
				$stringTaxes .=$value['idProducto'].'-'.$value['precio'].'-'.$value['cantidad'].'-'.$value['formula'].'-'.$value['caracteristicas'].'/';
				$productosTotal += $value['cantidad'];
			}
		}


		$this->calculaImpuestos($stringTaxes);

		////regresa los productos en orden de incersion
		$ar = $_SESSION['caja'];
		$nar=array();
		foreach ($ar as $key => $value) {
			$nar[$key.'+']=$ar[$key];
		}

		return array('estatus' =>true,'productos' =>$nar, 'cargos' => $_SESSION['caja']['cargos'], 'totalProductos' => $productosTotal,'listaDePrecios' => $idListaPrecios);
		


	}
	public function object_to_array($data) {
		if (is_array($data) || is_object($data)) {
			$result = array();
			foreach ($data as $key => $value) {
				$result[$key] = $this->object_to_array($value);
			}
			return $result;
		}
	return $data;
	}
	public function cargaRfcs($idCliente) {
		$queryRfc = "select id , rfc from comun_facturacion where nombre=" . $idCliente;
		$result = $this->queryArray($queryRfc);

		if ($result["total"] > 0) {
			return array("status" => true, "rfc" => $result["rows"]);
		} else {
			return array("status" => false);
		}
	}
	public function calculaImpuestos($stringTaxes){
		//echo '['.$stringTaxes.']';
		unset($_SESSION['caja']['cargos']);
		//echo $stringTaxes.'Z';
		//exit();
		//unset($_SESSION['prueba']);
		//idProdcuto-precio-cantidad-formula/idProducto2-precio2-cantidad2-formula2/
		//$productos = '41-100-1-0/42-50-1-2/44-100-1-1';
		//$productos = '44-100-1-1/66-100-1-1/';
		$productos = explode('/', $stringTaxes);

		foreach ($productos as $key => $value) {
			$producto_impuesto = 0;
			$prod = explode('-', $value);
			if($prod[0]!=''){
				$idProducto = $prod[0];
				$precio = $prod[1];
				$cantidad = $prod[2];
				$formula = $prod[3];//desc o asc 1 = ieps de los vinos , 2 = ieps de la gasolina
				$carac = $prod[4];
				$subtotal = $precio * $cantidad;
				$producto_impuesto2 = 0;
				$producto_impuestoR = 0;
				//echo 'Subtotal='.$subtotal;

				if($formula==2){
					$ordenform = 'ASC';
				}else{
					$ordenform = 'DESC';
				}
				
			   /* echo 'id='.$idProducto.'<br>';
				echo 'precio='.$precio.'<br>';
				echo 'cantidad='.$cantidad.'<br>';
				echo 'formula='.$formula; */
				$queryImpuestos = "select p.id,p.precio, i.valor, i.clave,pi.formula,i.nombre";
				$queryImpuestos .= " from app_impuesto i, app_productos p ";
				$queryImpuestos .= " left join app_producto_impuesto pi on p.id=pi.id_producto ";
				$queryImpuestos .= " where p.id=" . $idProducto . " and i.id=pi.id_impuesto ";
				$queryImpuestos .= " Order by pi.id_impuesto ".$ordenform;
				//echo $queryImpuestos.'<br>';
				$resImpues = $this->queryArray($queryImpuestos);

				//si tiene caracteristicas
				if($carac!=''){
					$idProducto = $idProducto.'_'.$carac;
				}else{
					$idProducto = $idProducto;
				}
				//echo $idProducto.'<br>';
				foreach ($resImpues['rows'] as $key => $valueImpuestos) {
						//echo 'Clave='.$valueImpuestos["clave"].'<br>';
						if ($valueImpuestos["clave"] == 'IEPS') {
							//echo 'Y'.$producto_impuesto;
							$producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
							$producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);
						} elseif($valueImpuestos["clave"]=='IVAR' || $valueImpuestos["clave"]=='ISR'){

							$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
							//$producto_impuestoR = (($subtotal) * $valueImpuestos["valor"] / 100);
							$producto_impuestoR += (($subtotal) * $valueImpuestos["valor"] / 100);
							//$producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);

						}else {
							
							if ($ieps != 0) {   
								//echo 'tiene iepswowkowkdokwdkowdkwkdowkdowdowdowkokwdodokwokdokwooo';
								$producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);
								 /*if($valueImpuestos["retenido"]==1){
									$nombreret=$valueImpuestos["nombre"];
									$producto_impuesto_ret =  (($subtotal) * $valueImpuestos["retenido"] / 100);//sacco el retenido
								   
								}   */                            
							} else {
								
								//echo 'nohayieps';
								//$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
							   // echo 'Y'.$producto_impuesto;
								//exit();
							  /*  if($valueImpuestos["retenido"]==1){
									$nombreret=$valueImpuestos["nombre"];
						   
									$producto_impuesto_ret =  (($subtotal) * $valueImpuestos["valor"] / 100);//sacco el retenido 
								}else{ */
									$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
									$producto_impuesto2 += (($subtotal) * $valueImpuestos["valor"] / 100);
								//}                                 
							}
						}

						$_SESSION['caja'][$idProducto]->impuesto = str_replace(",", "", $_SESSION['caja'][$idProducto]->impuesto) + $producto_impuesto ;
						$_SESSION['caja'][$idProducto]->suma_impuestos += $suma_impuestos;
						$_SESSION['caja'][$idProducto]->cargos->$valueImpuestos["nombre"] = $producto_impuesto;
						//echo $valueImpuestos["nombre"].' '.$valueImpuestos["valor"].'='.$producto_impuesto.'<br>';
						//$total += $producto_impuesto;
						$_SESSION['caja']['cargos']['impuestos'][$valueImpuestos["clave"]] = $_SESSION['caja']['cargos']['impuestos'][$valueImpuestos["clave"]] + $producto_impuesto;
						$_SESSION['caja']['cargos']['impuestosPorcentajes'][$valueImpuestos["nombre"]] = $_SESSION['caja']['cargos']['impuestosPorcentajes'][$valueImpuestos["nombre"]] + $producto_impuesto;
						
						$_SESSION['caja']['cargos']['impuestosFactura'][$valueImpuestos["clave"]][$valueImpuestos["valor"]] = $_SESSION['caja']['cargos']['impuestosFactura'][$valueImpuestos["clave"]][$valueImpuestos["valor"]] + $producto_impuesto;
						$_SESSION['caja']['cargos']['impuestosPdf'][$valueImpuestos["clave"]][$valueImpuestos["valor"]]['Valor'] = $_SESSION['caja']['cargos']['impuestosPdf'][$valueImpuestos["clave"]][$valueImpuestos["valor"]]['Valor'] + $producto_impuesto;

				}

				$ieps=0;
				//echo $producto_impuestoR.'<br>'.($subtotal+$producto_impuesto2);

				//echo 'total='.($subtotal+$producto_impuesto).'<br>';
				$_SESSION['caja']['cargos']['subtotal'] += $subtotal;
				$_SESSION['caja']['cargos']['total'] += ($subtotal+$producto_impuesto2) - $producto_impuestoR;

			}
		}
		//print_r($_SESSION['caja']);
		//echo json_encode($_SESSION['prueba']);
		//unset($_SESSION['prueba');
	}
	public function agregaPago($tipo, $tipostr, $cantidad, $referencia) {

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
				$_SESSION['pagos-caja']["porPagar"] = number_format($porPagar,2);
			}

				//Cambio en caso de que sea necesario
			$cambio = $_SESSION['pagos-caja']["Abonado"] - $str_total;
			if ($cambio > 0) {
				$_SESSION['pagos-caja']["cambio"] = number_format($cambio,2);
			} else {
				$_SESSION['pagos-caja']["cambio"] = number_format(0, 2);
			}

			$_SESSION['pagos-caja']["Abonado"] = number_format($abonado,2);
			//print_r($_SESSION['pagos-caja']);
			//exit();
			return array("status" => true, "tipo" => $tipo, "tipostr" => $tipostr, "cantidad" => $_SESSION['pagos-caja']["pagos"][$tipo]['cantidad'], "abonado" => $_SESSION['pagos-caja']["Abonado"], "porPagar" => $_SESSION['pagos-caja']["porPagar"], "cambio" => $_SESSION['pagos-caja']["cambio"]);
	}
	public function guardarVenta($cliente, $idFact, $documento, $suspendida, $propina, $comentario){
		//print_r($_SESSION['caja']);
		//print_r($_SESSION['pagos-caja']["pagos"]);
		//exit();
		try {
			$documento=1;
		   if ($suspendida != 0) {
				$this->eliminarSuspendida($suspendida);
			}

			date_default_timezone_set("Mexico/General");
			$fechaactual = date("Y-m-d H:i:s");

			$_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);
			$_SESSION['pagos-caja'] = $this->object_to_array($_SESSION['pagos-caja']);

			$monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
			$cambio = str_replace(",", "", $_SESSION['pagos-caja']["cambio"]);

			//SE calcula el total de los impuestos
			foreach ($_SESSION["caja"]["cargos"]["impuestos"] as $key => $value) {
				$impuestos+= (float) str_replace(",", "", $value);
			}

			if (!is_numeric($cliente)) {
				$cliente = 'NULL';
			}
			//echo $impuestos.'SSSS';

			if ($_SESSION['pagos-caja']["porPagar"] != 0) {
				throw new Exception("No has cubierto el total de la compra.");
			}

			//seleccionas el ultimo id para sacar el nuevo de la venta
			$selectid = "SELECT max(idVenta) as idVenta from app_pos_venta for Update;";
			$result = $this->queryArray($selectid);

			if ($result["rows"] < 1) {
				throw new Exception($result["msg"]);
			}

			$idVenta = $result["rows"][0]["idVenta"] + 1;
			//echo '('.$idVenta.')';
			$envioFac = 0;
			if($documento == 2){
				$envioFac = 1;
			}
			$idALmacenUs = $this->obtenAlm();
			//Se inserta la venta
			$insertVenta = "INSERT INTO app_pos_venta (idVenta,idCliente,monto,estatus,idEmpleado,rfc,documento,fecha,cambio,montoimpuestos,idSucursal,envio,impuestos,subtotal) "
			. "VALUES (" . $idVenta . "," . $cliente . "," . $monto . ",1," . $_SESSION['accelog_idempleado'] . ",''," . $documento . ",'" . $fechaactual . "'," . $cambio . ",'" . $impuestos . "'," . $_SESSION["sucursal"] . ",'".$envioFac."','".json_encode($_SESSION['caja']['cargos']['impuestosPorcentajes'])."','".$_SESSION['caja']['cargos']['subtotal']."');";
			//echo $insertVenta;
			$result = $this->queryArray($insertVenta);

			if ($result["total"] < 0) {
				throw new Exception("Error al registrar la venta 1. " . $result["msg"]);
			}

			//inserta los prodcutos de la venta
			foreach ($_SESSION["caja"] as $key => $producto) {
				if($key!='cargos' && $key!='descGeneral' && $key!='descGeneralCant'){
				$impuestos = 0;
				$producto = (object) $producto;

				///obtiene el total de impuestos
				foreach ($producto->cargos as $key2 => $value2) {
					$impuestos += (float) str_replace(",", "", $value2);
				}
				//echo 'impuestos='.$impuestos.'<br>';
				$selectid = "SELECT max(idventa_producto) as idVentap from app_pos_venta_producto for Update;";
				$result = $this->queryArray($selectid);

				if ($result["rows"] < 1) {
					throw new Exception($result["msg"]);
				}

				$idVentap = $result["rows"][0]["idVentap"] + 1;

			// Si es un extra cambia el comentario por el nombre del extra y limpia el campo de foodware
				if (!empty($producto->descripcion_foodware)) {
					$comentario=$producto->descripcion_foodware;

					$sql = "	UPDATE
									app_campos_foodware
								SET
									descripcion=''
								WHERE
									id_producto=".$producto->idProducto;
					// return $sql;
					$result = $this->query($sql);
				}

			   // $subtotalSTR = str_replace(",", "", $producto->subtotal);
				$insertVenta_Pro = "INSERT INTO app_pos_venta_producto (idventa_producto,idProducto,cantidad,preciounitario,tipodescuento,descuento,subtotal,idVenta,impuestosproductoventa,montodescuento,total,arr_kit,comentario) "
				. "VALUES (" . $idVentap . "," . $producto->idProducto . "," . $producto->cantidad . ",'" . $producto->precio . "','" . $producto->tipodescuento . "','" . $producto->descuento_cantidad . "'," . $producto->importe . "," . $idVenta . ",'" . $impuestos . "','" . str_replace(",", "", $producto->descuento) . "','" . ($impuestos + $producto->importe)  . "','NULL','" . $comentario . "')";
				//echo $insertVenta_Pro.'<br>';
				$resultVenta_Pro = $this->queryArray($insertVenta_Pro);
				$idVentaProdcutoI = $resultVenta_Pro['insertId'];

				if ($resultVenta_Pro["total"] < 0) {
					throw new Exception("Error al registrar la venta del producto 2. " . $resultVenta_Pro["msg"]);
				}

				if ($producto->idProducto == '' || $producto->idProducto == null) {
					throw new Exception("Error al registrar la venta del producto 3. ");
				}


				//Inicia la insercion de los impuestos por producto
				$selectProductoImpuesto = "SELECT i.id idImpuesto,i.nombre as impuesto, i.valor from app_producto_impuesto pi inner join app_impuesto i on i.id=pi.id_impuesto where id_producto=" . $producto->idProducto;
				$resultProductoImpuesto = $this->queryArray($selectProductoImpuesto);

				if ($resultProductoImpuesto["total"] < 0) {
					throw new Exception("Error al consultar los impuestos " . $resultProductoImpuesto["msg"]);
				}
				///Insercion de los impuestos de los productos en la venta.
				foreach ($resultProductoImpuesto["rows"] as $keyImpuesto => $valueImpuesto) {
					$insertventaproductoimpuesto = "INSERT into app_pos_venta_producto_impuesto (idVentaproducto,idImpuesto,porcentaje) values (" . $idVentaProdcutoI. "," . $valueImpuesto["idImpuesto"] . "," . $valueImpuesto["valor"] . ");";
					$resultventaproductoimpuesto = $this->queryArray($insertventaproductoimpuesto);
					//echo $insertventaproductoimpuesto.'<br>';
					/*if ($resultventaproductoimpuesto["status"] < 0) {
						throw new Exception("Error al guardar los impuestos... " . $resultventaproductoimpuesto["msg"]);
					} */
				} ///fin del ciclo de app_pos_venta_producto_impuesto
				$importe = 0;
				//ciclo de salida de almacen
				$insertInventario = "INSERT into app_inventario_movimientos(id_producto,cantidad,importe,id_almacen_origen,fecha,id_empleado,tipo_traspaso,costo,referencia) values('".$producto->idProducto."','".$producto->cantidad."','".$producto->precio."','".$idALmacenUs."','".$fechaactual."','".$_SESSION['accelog_idempleado']."','0','".$producto->importe."','Venta ".$idVenta."')";
				//echo $insertInventario.'<br>';
				$resultInven = $this->queryArray($insertInventario);

				}//fin del if del cliclo
			} //fin del ciclo de prodcutos

				///Insercion de los pagos de la venta
				foreach ($_SESSION['pagos-caja']["pagos"] as $idFormapago => $value) {
					if ($value["cantidad"] > 0) {
						$cantidad = $value["cantidad"];
						$referencia = $value["referencia"];

						$selectid = "SELECT max(id) as idVentaPagos from app_pos_venta_pagos for Update;";
						$result = $this->queryArray($selectid);

						if ($result["rows"] < 1) {
							throw new Exception($result["msg"]);
						}

						$idVentaPagos = $result["rows"][0]["idVentaPagos"] + 1;
						//venta_pagos
						$insertVenta_Pagos = "INSERT INTO app_pos_venta_pagos(id,idVenta,idFormapago,monto,referencia) "
						. "VALUES(" . $idVentaPagos . "," . $idVenta . "," . $idFormapago . "," . str_replace(",", "", $cantidad) . ",'" . $referencia . "')";
						//echo $insertVenta_Pagos.'<br>';
						$resultinsertVenta_Pagos = $this->queryArray($insertVenta_Pagos);

						if ($resultinsertVenta_Pagos["total"] < 0) {
							throw new Exception("No se pudo guardar el cargo del pago. " . $resultinsertVenta_Pagos["msg"]);
						}
					}
				} //fin del ciclo de los pago

		   // exit();





			return array("status" => true, "idVenta" => $idVenta);
		} catch (Exception $e) {
			return array("status" => false, "msg" => $e->getMessage());
		}
	}//fin funcion guarda venta
	public function guardarPedido($cliente, $idFact, $documento, $suspendida, $comentario,$moneda,$obs){
		//print_r($_SESSION['caja']);
		//print_r($_SESSION['pagos-caja']["pagos"]);
		//exit();
		try {
			$documento=1;

			date_default_timezone_set("Mexico/General");
			$fechaactual = date("Y-m-d H:i:s");

			$_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);
			$_SESSION['pagos-caja'] = $this->object_to_array($_SESSION['pagos-caja']);
			//print_r($_SESSION["caja"]);
			//exit();
			$monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
			$cambio = str_replace(",", "", $_SESSION['pagos-caja']["cambio"]);

			//SE calcula el total de los impuestos
			foreach ($_SESSION["caja"]["cargos"]["impuestos"] as $key => $value) {
				$impuestos+= (float) str_replace(",", "", $value);
			}

			if (!is_numeric($cliente)) {
				$cliente = 'NULL';
			}
			//echo $impuestos.'SSSS';

		   /* if ($_SESSION['pagos-caja']["porPagar"] != 0) {
				throw new Exception("No has cubierto el total de la compra.");
			} */

			//seleccionas el ultimo id para sacar el nuevo de la venta
			$selectid = "SELECT max(id) as id from cotpe_pedido for Update;";
			$result = $this->queryArray($selectid);

			if ($result["rows"] < 1) {
				throw new Exception($result["msg"]);
			}

			$idPedido = $result["rows"][0]["id"] + 1;
			//echo '('.$idVenta.')';
			$envioFac = 0;
			if($documento == 2){
				$envioFac = 1;
			}
			$idALmacenUs = $this->obtenAlm();
					   ///obtiene el tipo de cambio
		   // $query34 = 'SELECT * FROM cont_tipo_cambio where moneda='.$moneda.' order by fecha desc limit 1;';
			$query34 = 'SELECT c.*,m.codigo FROM cont_tipo_cambio c, cont_coin m where c.moneda=m.coin_id and  c.moneda='.$moneda.' order by c.fecha desc limit 1;';
			$res34 = $this->queryArray($query34); 

			$query35 = 'SELECT codigo from cont_coin where coin_id='.$moneda;
			$res35 = $this->queryArray($query35);
			$codMoneda = $res35['rows'][0]['codigo']; 
		
			$insertPedido = "INSERT INTO cotpe_pedido (idCliente,total,idempleado,fecha,observaciones,idMoneda,tipo_cambio,impuestosJson,descuentoGeneral,descCant) values ('".$cliente."','".$_SESSION['caja']['cargos']['total']."','".$_SESSION['accelog_idempleado']."','".$fechaactual."','esto es una prueba','".$moneda."','".$res34['rows'][0]['tipo_cambio']."','".json_encode($_SESSION['caja']['cargos']['impuestosPorcentajes'])."','".$_SESSION['caja']['descGeneral']."','".$_SESSION['caja']['descGeneralCant']."')";
			$result = $this->queryArray($insertPedido);
			//echo 'djdjhdjdj';
			if ($result["total"] < 0) {
				throw new Exception("Error al registrar el pedido 1. " . $result["msg"]);
			}

			//inserta los prodcutos de la venta
			foreach ($_SESSION["caja"] as $key => $producto) {
				if($key!='cargos' && $key!='descGeneral' && $key!='descGeneralCant'){
				$impuestos = 0;
				$producto = (object) $producto;

				///obtiene el total de impuestos
				foreach ($producto->cargos as $key2 => $value2) {
					$impuestos += (float) str_replace(",", "", $value2);
				}
				//echo 'impuestos='.$impuestos.'<br>';
				$selectid = "SELECT max(id) as idPP from cotpe_pedido_producto for Update;";
				$result = $this->queryArray($selectid);

				if ($result["rows"] < 1) {
					throw new Exception($result["msg"]);
				}

				$idPP = $result["rows"][0]["idPP"] + 1;

				$insertPP = "INSERT INTO cotpe_pedido_producto (idPedido,idProducto,cantidad,idunidad,precio,importe,caracteristicas,impuestos,descuento,descuentoCantidad,tipoDes) values ('".$idPedido."','".$producto->idProducto."','".$producto->cantidad."','1','".$producto->precio."','".$producto->importe."','".$producto->caracteristicas."','".$producto->impuesto."','".$producto->descuento."','".$producto->descuento_cantidad."','".$producto->tipodescuento."')";
								///caracteristicass
				if($producto->caracteristicas!=''){
					$caracteristica = preg_replace('/\*/', ',', $producto->caracteristicas);
					$caracteristicareplace = preg_replace('/([0-9])+/', '\'\0\'', $caracteristica);
					$caracteristicareplace=addslashes($caracteristicareplace);
					$caracteristicareplace = trim($caracteristicareplace, ',');
				}else{
					$caracteristicareplace = "'0'";   
				}
				


				//echo $insertVenta_Pro.'<br>';
				$resultVenta_Pro = $this->queryArray($insertPP);
				$idVentaProdcutoI = $resultVenta_Pro['insertId'];
				if($caracteristicareplace==''){
					$caracteristicareplace = "'0'";
				}
				$insertInventario = 'INSERT into app_inventario_movimientos(id_producto,id_producto_caracteristica,cantidad,importe,id_almacen_origen,fecha,id_empleado,tipo_traspaso,costo,referencia,origen) values("'.$producto->idProducto.'","'.$caracteristicareplace.'","'.$producto->cantidad.'","'.$producto->precio.'","'.$idALmacenUs.'","'.$fechaactual.'","'.$_SESSION['accelog_idempleado'].'","3","'.$producto->importe.'","Apartado Pedido '.$idPedido.'","2")';
				//echo $insertInventario.'<br>';
				$resultInven = $this->queryArray($insertInventario);  

				$insertInMo = "";


				if ($resultVenta_Pro["total"] < 0) {
					throw new Exception("Error al registrar la venta del producto 2. " . $resultVenta_Pro["msg"]);
				}

				if ($producto->idProducto == '' || $producto->idProducto == null) {
					throw new Exception("Error al registrar la venta del producto 3. ");
				}

				$importe = 0;

				}//fin del if del cliclo
			} //fin del ciclo de prodcuto

		   // exit();
		$queryClient="SELECT c.nombre,c.direccion,c.colonia,c.email,c.cp, e.estado,m.municipio,c.rfc,c.num_ext,c.num_int , ppp.pais";
		$queryClient.=" from comun_cliente c left join estados e on c.idEstado=e.idestado left join municipios m on c.idMunicipio=m.idmunicipio left join paises ppp on c.idPais=ppp.idpais";
		$queryClient.=" where c.id=".$cliente;

		$result = $this->queryArray($queryClient);
		$Email = $result["rows"][0]["email"];
	  ////////DATOS EMISOR
		$queryOganizacion="SELECT o.nombreorganizacion,o.RFC,r.descripcion as regimen,o.domicilio,e.estado,m.municipio,o.cp,o.colonia,o.paginaweb,o.logoempresa ";
		$queryOganizacion.=" from organizaciones o, estados e,municipios m, nomi_regimenfiscal r ";
		$queryOganizacion.=" where o.idestado=e.idestado and o.idmunicipio=m.idmunicipio and o.idregfiscal = r.idregfiscal";
		$result2 = $this->queryArray($queryOganizacion);
		   // print_r($_SESSION["pedido"]["charges"]);
		//echo '('.$Email.')';
	   // var_dump($result2);

	   unlink('../../modulos/cotizaciones/cotizacionesPdf/pedido_'.$idPedido.'.php');
		////////////////PDF
	 

		include "../../modulos/SAT/PDF/COTIZACIONESPDF.php";
		$obj = new CFDIPDF( );

			$nrec = $result["rows"][0]["num_ext"].' Int.'.$result["rows"][0]["num_int"];
			$obj->datosCFD($idPedido, $fechaactual, 'Pedido', $codMoneda);
			$obj->lugarE('MEXICO');
		 
			
			$obj->datosEmisor($result2["rows"][0]["nombreorganizacion"], $result2["rows"][0]["RFC"], $result2["rows"][0]["domicilio"], $result2["rows"][0]["estado"], $result2["rows"][0]["colonia"], $result2["rows"][0]["municipio"], $result2["rows"][0]["estado"], $result2["rows"][0]["cp"], "Mexico", '');

			$obj->datosReceptor($result["rows"][0]["nombre"], $result["rows"][0]["rfc"], $result["rows"][0]["direccion"] . $nrec, $result["rows"][0]["municipio"], $result["rows"][0]["colonia"], $result["rows"][0]["municipio"], $result["rows"][0]["estado"], $result["rows"][0]["cp"],$result["rows"][0]["pais"] );

			$obj->agregarConceptos($_SESSION['caja']);
			//if (!isset($var)) {
				//$_SESSION['caja']['cargos']['impuestosPdf'] =  array();
			//}
			$obj->agregarTotal($_SESSION["caja"]["cargos"]["subtotal"], $_SESSION["caja"]["cargos"]["total"], $_SESSION['caja']['cargos']['impuestosPdf']);

			$obj->agregarMetodo('eeeeeeeeee', '', $codMoneda);
			//$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
			$obj->agregarObservaciones($obs);

			$obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);
	
			$obj->borrarConcepto();
			//exit();

				if ($Email != '') {	
				
					require_once('../../modulos/phpmailer/sendMail.php');
					
					$mail->From = "mailer@netwarmonitor.com";
					$mail->FromName = "NetwarMonitor";
					$mail->Subject = "Pedido";
					$mail->AltBody = "NetwarMonitor";
					$mail->MsgHTML('Envio de Pedido');

					$mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/pedido_'.$idPedido.".pdf");
					$mail->AddAddress($Email, $Email);
					
				
				 @$mail->Send();
			
						//unset($_SESSION['pedido']);
						//return array('status' => true);
				}else{
						//unset($_SESSION['pedido']);
						//return array('status' => false);
				}

			


				unset($_SESSION['caja']);

			return array("status" => true, "idPedido" => $idPedido);
		} catch (Exception $e) {
			return array("status" => false, "msg" => $e->getMessage());
		}
	} /// fin de guardar pedido
	public function actualizaPedido($cliente, $idFact, $documento, $suspendida, $comentario,$moneda,$idPedido,$obs){
		try {
			$documento=1;

			date_default_timezone_set("Mexico/General");
			$fechaactual = date("Y-m-d H:i:s");

			$_SESSION["caja"] = $this->object_to_array($_SESSION["caja"]);
			$_SESSION['pagos-caja'] = $this->object_to_array($_SESSION['pagos-caja']);

			$monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
			$cambio = str_replace(",", "", $_SESSION['pagos-caja']["cambio"]);

			//SE calcula el total de los impuestos
			foreach ($_SESSION["caja"]["cargos"]["impuestos"] as $key => $value) {
				$impuestos+= (float) str_replace(",", "", $value);
			}
			if (!is_numeric($cliente)) {
				$cliente = 'NULL';
			}
			//echo $impuestos.'SSSS';
			$envioFac = 0;
			if($documento == 2){
				$envioFac = 1;
			}
			$idALmacenUs = $this->obtenAlm();
					   ///obtiene el tipo de cambio
		   // $query34 = 'SELECT * FROM cont_tipo_cambio where moneda='.$moneda.' order by fecha desc limit 1;';
			$query34 = 'SELECT c.*,m.codigo FROM cont_tipo_cambio c, cont_coin m where c.moneda=m.coin_id and  c.moneda='.$moneda.' order by c.fecha desc limit 1;';
			$res34 = $this->queryArray($query34); 

			$query35 = 'SELECT codigo from cont_coin where coin_id='.$moneda;
			$res35 = $this->queryArray($query35);
			$codMoneda = $res35['rows'][0]['codigo']; 
		
			$updatePedido = "UPDATE cotpe_pedido set idCliente='".$cliente."', total='".$_SESSION['caja']['cargos']['total']."', idempleado='".$_SESSION['accelog_idempleado']."', fecha='".$fechaactual."', idMoneda='".$moneda."', tipo_cambio='".$tipo_cambio."', impuestosJson='".json_encode($_SESSION['caja']['cargos']['impuestosPorcentajes'])."', descuentoGeneral='".$_SESSION['caja']['descGeneral']."', descCant='".$_SESSION['caja']['descGeneralCant']."' WHERE id=".$idPedido;
			
			$result = $this->queryArray($updatePedido);

			if ($result["total"] < 0) {
				throw new Exception("Error al registrar el pedido 1. " . $result["msg"]);
			}
				//Borra e inserta los prodcutos de la venta
				$deleteProd = 'DELETE from cotpe_pedido_producto where idPedido='.$idPedido;
	
				$resDelete = $this->queryArray($deleteProd);

				foreach ($_SESSION["caja"] as $key => $producto) {
					if($key!='cargos' && $key!='descGeneral' && $key!='descGeneralCant'){
					$impuestos = 0;
					$producto = (object) $producto;

					///obtiene el total de impuestos
					foreach ($producto->cargos as $key2 => $value2) {
						$impuestos += (float) str_replace(",", "", $value2);
					}
					//echo 'impuestos='.$impuestos.'<br>';
					$selectid = "SELECT max(id) as idPP from cotpe_pedido_producto for Update;";
					$result = $this->queryArray($selectid);

					if ($result["rows"] < 1) {
						throw new Exception($result["msg"]);
					}

					$idPP = $result["rows"][0]["idPP"] + 1;

					$insertPP = "INSERT INTO cotpe_pedido_producto (idPedido,idProducto,cantidad,idunidad,precio,importe,caracteristicas,impuestos,descuento,descuentoCantidad,tipoDes) values ('".$idPedido."','".$producto->idProducto."','".$producto->cantidad."','1','".$producto->precio."','".$producto->importe."','".$producto->caracteristicas."','".$producto->impuesto."','".$producto->descuento."','".$producto->descuento_cantidad."','".$producto->tipodescuento."')";
					//echo $insertVenta_Pro.'<br>';
					$resultVenta_Pro = $this->queryArray($insertPP);
					$idVentaProdcutoI = $resultVenta_Pro['insertId'];

					if ($resultVenta_Pro["total"] < 0) {
						throw new Exception("Error al registrar la venta del producto 2. " . $resultVenta_Pro["msg"]);
					}

					if ($producto->idProducto == '' || $producto->idProducto == null) {
						throw new Exception("Error al registrar la venta del producto 3. ");
					}

					$importe = 0;

					}//fin del if del cliclo
				} //fin del ciclo de prodcuto
   // exit();
		/*$queryClient="SELECT c.nombre,c.direccion,c.colonia,c.email,c.cp, e.estado,m.municipio,c.rfc,c.num_ext,c.num_int, ppp.pais ";
		$queryClient.=" from comun_cliente c, estados e,municipios m ";
		$queryClient.=" where c.idEstado=e.idestado and c.idMunicipio=m.idmunicipio and c.id=".$cliente; */
		$queryClient="SELECT c.nombre,c.direccion,c.colonia,c.email,c.cp, e.estado,m.municipio,c.rfc,c.num_ext,c.num_int , ppp.pais";
		$queryClient.=" from comun_cliente c left join estados e on c.idEstado=e.idestado left join municipios m on c.idMunicipio=m.idmunicipio left join paises ppp on c.idPais=ppp.idpais";
		$queryClient.=" where c.id=".$cliente;

		$result = $this->queryArray($queryClient);
		$Email = $result["rows"][0]["email"];
	  ////////DATOS EMISOR
		$queryOganizacion="SELECT o.nombreorganizacion,o.RFC,r.descripcion regimen,o.domicilio,e.estado,m.municipio,o.cp,o.colonia,o.paginaweb,o.logoempresa ";
		$queryOganizacion.=" from organizaciones o, estados e,municipios m, nomi_regimenfiscal r ";
		$queryOganizacion.=" where o.idestado=e.idestado and o.idmunicipio=m.idmunicipio and o.idregfiscal = r.idregfiscal";
		$result2 = $this->queryArray($queryOganizacion);
		   // print_r($_SESSION["pedido"]["charges"]);
		//echo '('.$Email.')';
	   // var_dump($result2);
	   unlink('../../modulos/cotizaciones/cotizacionesPdf/pedido_'.$idPedido.'.pdf');
		////////////////PDF


		include "../../modulos/SAT/PDF/COTIZACIONESPDF.php";
		$obj = new CFDIPDF( );

			$nrec = $result["rows"][0]["num_ext"].' Int.'.$result["rows"][0]["num_int"];
			$obj->datosCFD($idPedido, $fechaactual, 'Pedido', $codMoneda);
			$obj->lugarE('MEXICO');
		 
			
			$obj->datosEmisor($result2["rows"][0]["nombreorganizacion"], $result2["rows"][0]["RFC"], $result2["rows"][0]["domicilio"], $result2["rows"][0]["estado"], $result2["rows"][0]["colonia"], $result2["rows"][0]["municipio"], $result2["rows"][0]["estado"], $result2["rows"][0]["cp"], "Mexico", '');

			$obj->datosReceptor($result["rows"][0]["nombre"], $result["rows"][0]["rfc"], $result["rows"][0]["direccion"] . $nrec, $result["rows"][0]["municipio"], $result["rows"][0]["colonia"], $result["rows"][0]["municipio"], $result["rows"][0]["estado"], $result["rows"][0]["cp"], $result["rows"][0]["pais"]);

			$obj->agregarConceptos($_SESSION['caja']);
			//if (!isset($var)) {
				//$_SESSION['caja']['cargos']['impuestosPdf'] =  array();
			//}
			$obj->agregarTotal($_SESSION["caja"]["cargos"]["subtotal"], $_SESSION["caja"]["cargos"]["total"], $_SESSION['caja']['cargos']['impuestosPdf']);
			   
			$obj->agregarMetodo('eeeeeeeeee', '', $codMoneda);
			//$obj->agregarSellos($datosTimbrado['csdComplemento'], $datosTimbrado['selloCFD'], $datosTimbrado['selloSAT']);
			$obj->agregarObservaciones($observ);
			$obj->generar("../../netwarelog/archivos/1/organizaciones/" . $result2["rows"][0]["logoempresa"] . "", 0);
			$obj->borrarConcepto();
			//exit();

				if ($Email != '') {

					require_once('../../modulos/phpmailer/sendMail.php');

					$mail->From = "mailer@netwarmonitor.com";
					$mail->FromName = "NetwarMonitor";
					$mail->Subject = "Pedido";
					$mail->AltBody = "NetwarMonitor";
					$mail->MsgHTML('Envio de Pedido');
					$mail->AddAttachment('../../modulos/cotizaciones/cotizacionesPdf/pedido_'.$idPedido.".pdf");
					$mail->AddAddress($Email, $Email);


				 @$mail->Send();

						//unset($_SESSION['pedido']);
						//return array('status' => true);
				}else{
						//unset($_SESSION['pedido']);
						//return array('status' => false);
				}




				unset($_SESSION['caja']);

			return array("status" => true, "idPedido" => $idPedido);
		} catch (Exception $e) {
			return array("status" => false, "msg" => $e->getMessage());
		}

	}
	public function clientePedido($idCliente){
		$query = "SELECT * from comun_cliente where id=".$idCliente;
		$res = $this->queryArray($query);

		return $res['rows'];
	}

	public function buscaClienteP($empleado){
		$query = "SELECT id from administracion_usuarios where idempleado=".$empleado;
		$res = $this->queryArray($query);

		return $res;
	}

	public function facturar($idFact, $idVenta, $bloqueo, $mensaje,$consumo) {
	   /* print_r($_SESSION['caja']);
		exit();*/
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
			$updateVenta = $this->queryArray("UPDATE app_pos_venta set observacion = '" . $mensaje . "' where idVenta =" . $idVenta);
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
		$queryFormaPago = " SELECT nombre,referencia from app_pos_venta_pagos vp inner join forma_pago fp on vp.idFormapago = fp.idFormapago where vp.idVenta=" . $idVenta;
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
		//echo 'Forma de pago='.$formapago;
		$Email = $df->correo;

		$parametros['DatosCFD']['FormadePago'] = "Pago en una sola exhibicion";
		$parametros['DatosCFD']['MetododePago'] = utf8_decode($formapago);
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
		//Empieza a llenar los conceptos
		foreach ($_SESSION['caja'] as $key => $producto) {
			if ($key != 'cargos') {
				$producto = (object) $producto;
				$descuentogeneral = 0;
				///desceuntos
				//echo "( descuento -> ".$producto->descuento_cantidad.")";
			   /* if ($producto->tipodescuento == "%") {
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
				} */
				$conceptosDatos[$x]["Cantidad"] = $producto->cantidad;
				$conceptosDatos[$x]["Unidad"] = $producto->unidad;
				$conceptosDatos[$x]["Precio"] = $producto->precio;
				if ($producto->descripcion != '') {
					$conceptosDatos[$x]["Descripcion"] = trim($producto->descripcion . " " . $textodescuento);
				} else {
					$conceptosDatos[$x]["Descripcion"] = trim($producto->nombre . " " . $textodescuento);
				}
				$textodescuento = '';
				//$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio - str_replace(",", "", $producto->descuento) );
				$conceptosDatos[$x]['Importe'] = ($producto->cantidad * $producto->precio);
				$consumoTotal +=  $conceptosDatos[$x]['Importe']*1;
				$x++;


			}//fin del if del ciclo
		}//fin del cilo de llenar conceptos

		$nn2 = $_SESSION['caja']['cargos']['impuestosFactura'];
		$nnf = $_SESSION['caja']['cargos']['impuestosPdf'];
		/* FACTURACION AZURIAN
		============================================================== */
		require_once('../../modulos/SAT/config.php');

		date_default_timezone_set("Mexico/General");
		$fecha = date('Y-m-d') . 'T' . date('H:i:s', strtotime("-7 minute"));


		$logo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1;";
		$logo = $this->queryArray($logo);
		$r3 = $logo["rows"][0];

		$azurian = array();
		//echo $bloqueo.'??';
		if ($bloqueo == 0) {
			//echo 'entro a bloqueo';
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
		/* IMPUESTOS
		============================================================== */
		if ($nn2 == '') {
			$nn2["IVA"]["0.0"]["Valor"] = 0.00;
		}
		if ($nnf == '') {
			$nnf["IVA"]["0.0"]["Valor"] = 0.00;
		}
		$nn = $nn2;
		$azurian['nn']['nn'] = $nn;
		$azurian['nnf']['nnf'] = $nnf;
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
		//se emepiza a llenar los conceptos en el arreglo de azurian
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
		}
		//////////impuestos azurian
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
						$tieps+=number_format($val, 2, '.', '');
					}
					if ($clave == 'IVA') {
						$tiva+=number_format($val, 2, '.', '');
					}
					$traslads.='|' . $clave . '|';
				   // $traslads.='' . $clavetasa . '|';
					$traslads.='' . number_format($clavetasa,2) . '|';
					$traslads.=number_format($val, 2, '.', '');
					$trasladsimp+=number_format($val, 2, '.', '');
					$trasxml.="<cfdi:Traslado impuesto='" . $clave . "' tasa='" . number_format($clavetasa,2) . "' importe='" . number_format($val, 2, '.', '') . "' />";
				}
			} elseif ($clave == 'ISR' || $clave == 'IVAR') {
				$hayret = 1;

				foreach ($nn[$clave] as $clavetasa => $val) {
				if($clave == 'IVAR'){
					$clave = substr($clave, 0, -1);
					$king = 1;
				}
					$tisr+=number_format($val, 2, '.', '');
					$retenids.='|' . $clave . '|';
					$retenidsT.='' . number_format($val, 2, '.', '') . '|';
					$retenids.=number_format($val, 2, '.', '');
					$retenciones+=number_format($val, 2, '.', '');
					$retexml.="<cfdi:Retencion impuesto='" . $clave . "' importe='" . number_format($val, 2, '.', '') . "' />";
					/*if($king ==1){
						$clave = 'IVAR';
						$king = 0;
					} */
				}
			}
		}////fin del foreach nn

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
		  if($hayret == 1){
			$cadRet = '|'.str_replace(',', '', number_format($tisr,2));
		  }else{
			$cadRet = '';
		  }

		  $azurian['Impuestos']['isr'] = $retenids.$cadRet;
		  $azurian['Impuestos']['iva'] = $traslads . '|' . number_format($trasladsimp, 2, '.', '');

		  $azurian['Impuestos']['totalImpuestosRetenidos'] = number_format($retenciones, 2, '.', '');
		  $azurian['Impuestos']['totalImpuestosTrasladados'] = number_format($trasladsimp, 2, '.', '');

		$ivas.=$isr . $iva;

		$azurian['Impuestos']['ivas'] = $ivas;
		/*print_r($azurian);
		echo json_encode($azurian);
		exit();*/
		unset($_SESSION['pagos-caja']);
		unset($_SESSION['caja']);


		require_once('../../modulos/lib/nusoap.php');
		require_once('../../modulos/SAT/funcionesSAT.php');




	}//fin funcion facturar();
	public function pendienteFacturacion($idFacturacion, $monto, $cliente, $idventa, $trackId, $azurian, $documento) {

		$azurian = base64_encode($azurian);
		date_default_timezone_set("Mexico/General");
		$fechaactual = date('Y-m-d H:i:s');
		$tipo = ($documento = 2 ? 'F' : 'R');

		if (is_numeric($cliente)) {
			$query = "INSERT into app_pendienteFactura values(''," . $idventa . ",'" . $fechaactual . "'," . $cliente . ",'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "');";
			$resultquery = $this->queryArray($query);

				//echo $query;
			return array("status" => true, "type" => 1);
		} else {
			$query = "INSERT into app_pendienteFactura values(''," . $idventa . ",'" . $fechaactual . "',NULL,'" . $monto . "',0,'" . $trackId . "','" . $azurian . "','" . $tipo . "');";
				//echo $query;
			$resultquery = $this->queryArray($query);
			return array("status" => true, "type" => 2);
		}
	}
	public function prodPedi($idPedido){
		$query = "SELECT * from cotpe_pedido where id=".$idPedido;
		$res1 = $this->queryArray($query);

		$query2 = "SELECT pe.*, pr.codigo from cotpe_pedido_producto pe, app_productos pr where pe.idProducto=pr.id and idPedido=".$idPedido;
		$res2 = $this->queryArray($query2);

		return array('generales' => $res1['rows'] ,'productos' => $res2['rows'] );
	}
	public function envioFactura($uid, $Email, $azurian, $doc) {

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
		$obj->agregarTotal($azurian['Basicos']['subTotal'], $azurian['Basicos']['total'], $azurian['nnf']['nnf']);
		$obj->agregarMetodo($azurian['Basicos']['metodoDePago'], '', $azurian['Basicos']['total']);
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
	public function guardarFacturacion($UUID, $noCertificadoSAT, $selloCFD, $selloSAT, $FechaTimbrado, $idComprobante, $idFact, $idVenta, $noCertificado, $tipoComp, $monto, $cliente, $trackId, $idRefact, $azurian, $estatus) {
		$azurian = base64_encode($azurian);
		$fechaactual = preg_replace('/T/', ' ', $FechaTimbrado);
		if ($idRefact == 'c') {
			$tipoComp = 'C';
			$queryRespuesta = "UPDATE app_respuestaFacturacion SET borrado=2 WHERE idSale='$idVenta'";
			$this->queryArray($queryRespuesta);
		}

		$insertRespuestaFacturacion = "INSERT INTO app_respuestaFacturacion "
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
			$updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale in (" . $idRefact . ")";
			$this->queryArray($updatePendienteFactura);
		}

		if ($idRefact > 0 && $idRefact != 'c') {
			$updatePendienteFactura = "UPDATE app_pendienteFactura SET facturado=1 WHERE id_sale='$idRefact'";
			$this->queryArray($updatePendienteFactura);
		}
		$queryEnvio = "UPDATE app_pos_venta set envio=2 where idVenta=".$idVenta;
		$this->queryArray($queryEnvio);

		return $insertedId;
	}
	public function datosorganizacion(){
		$selectOrg = "SELECT * from organizaciones c left join estados e on e.idestado=c.idestado left join municipios m on m.idmunicipio=c.idmunicipio where idorganizacion=1";
		$resultSelect = $this->queryArray($selectOrg);
		return $resultSelect['rows'];
	}
	public function datosventa($idventa){
		$selectVenta = "SELECT
		v.idVenta as folio,
		v.fecha as fecha,
		v.cambio as cambio,
		v.impuestos as jsonImpuestos,
		CASE WHEN c.nombre IS NOT NULL
			   THEN c.nombre
			   ELSE 'Publico general'
		END AS cliente,
		e.nombre as empleado,
		s.nombre as sucursal,
		CASE WHEN v.estatus =1
			   THEN 'Activa'
			   ELSE 'Cancelada'
		END AS estatus,
		v.montoimpuestos as impuestos,
		(v.monto) as monto
		 from app_pos_venta v left join comun_cliente c on c.id=v.idCliente inner join  empleados e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal
		 where v.idVenta=".$idventa;
		$resutl = $this->queryArray($selectVenta);
		return $resutl['rows'];
	}
	public function productosventa($idVenta){
		$selProd = "	SELECT
							IF(vp.comentario!='', CONCAT(p.nombre, vp.comentario),
								IF(f.descripcion!='', CONCAT(p.nombre, f.descripcion), p.nombre))
							AS nombre, p.descripcion_corta,
							p.id, p.codigo, vp.preciounitario, vp.cantidad, vp.montodescuento, vp.total,
							vp.impuestosproductoventa, vp.comentario
						FROM
							app_pos_venta_producto vp
						LEFT JOIN
								app_productos p
							ON
								vp.idProducto=p.id
						LEFT JOIN
								app_campos_foodware f
							ON
								p.id=f.id_producto
						WHERE
							vp.idVenta=".$idVenta;
		$resSel = $this->queryArray($selProd);
		return $resSel['rows'];
	}
	public function pagos($idVenta){
		$selectPagos = "SELECT vp.monto, fp.nombre from app_pos_venta_pagos vp inner join app_pos_venta v on v.idVenta=vp.idVenta inner join forma_pago fp on vp.idFormapago=fp.idFormapago where v.idVenta=".$idVenta;
		$resPagos = $this->queryArray($selectPagos);
		return $resPagos['rows'];
	}
	public function eliminaProducto($idProducto){
		unset($_SESSION['caja'][$idProducto]);
		$sessionArray = $this->object_to_array($_SESSION['caja']);

		$productosTotal = 0;
		foreach ($sessionArray as $key => $value) {
			if($key !='cargos' && $key!='descGeneral' && $key!='descGeneralCant'){
				$stringTaxes .=$value['idProducto'].'-'.$value['precio'].'-'.$value['cantidad'].'-'.$value['formula'].'-'.$value['caracteristicas'].'/';
				$productosTotal += $value['cantidad'];
			}
		}
		
		$this->calculaImpuestos($stringTaxes);
	   // print_r($_SESSION['caja']);
		////regresa los productos en orden de incersion
		$ar = $_SESSION['caja'];
		$nar=array();
		foreach ($ar as $key => $value) {
			$nar[$key.'+']=$ar[$key];
		}

		return array('estatus' =>true,'productos' =>$nar, 'cargos' => $_SESSION['caja']['cargos'],"count" => count($_SESSION['caja']), 'totalProductos' => $productosTotal);
		
	}
	public function cancelarCaja() {
		  unset($_SESSION['caja']);
		  unset($_SESSION['pagos-caja']);
		  return true;
	}
	public function suspenderVenta($idFact, $doc, $cliente, $nombre, $suspendida) {

		try {
			  date_default_timezone_set("Mexico/General");
			  $fechaactual = date("Y-m-d H:i:s");

			  $monto = str_replace(",", "", $_SESSION["caja"]["cargos"]["total"]);
			  $cambio = str_replace(",", "", $_SESSION['pagos-caja']["cambio"]);
			  foreach ($_SESSION["caja"]["cargos"]["impuestos"] as $key => $value) {
				$impuestos+=$value;
			}
			$almacen = $this->obtenAlm();
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
			if($cliente==''){
				$cliente = 0;
			}
				//Guardamos la venta suspendida
			$insertVentaSuspendida = "INSERT INTO app_pos_venta_suspendida (s_almacen,s_cambio,s_cliente,s_documento,s_empleado,s_funcion,s_idFact,s_impuestos,s_monto,s_pagoautomatico,s_sucursal,s_impuestost,arreglo1,arreglo2,identi,fecha) VALUES "
			. "('" . $almacen . "','" . $cambio . "','" . $cliente . "','" . $doc . "'," . $empleado . ",'suspenderVenta','" . $idFact . "','" . $impuestos . "','" . $monto . "',0,'" . $sucursal . "','" . $impuestos . "','" . $arr . "','" . $arr2 . "','" . $nombre . " - " . $fechaactual . " - $" . $monto . "','" . $fechaactual . "');";

			$resultinsertVentaSuspendida = $this->queryArray($insertVentaSuspendida);


			if (!$resultinsertVentaSuspendida["status"]) {
				throw new Exception("Error al suspender la venta.");
			}

			/*foreach ($_SESSION['caja'] as $key => $value) {
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
			} */

			unset($_SESSION['caja']);
			unset($_SESSION['pagos-caja']);
		   // $this->commit();
			//$this->eliminarSuspendida($suspendida);
			return array("status" => true);
		} catch (Exception $e) {
			//$this->rollback();
			return array("status" => false, "msg" => $e->getMessage());
		}
	}
/*    public function eliminarSuspendida($id) {

		try {

			$this->iniTrans();
			$datosSuspendida = "SELECT arreglo1, s_almacen from app_pos_venta_suspendida where id='$id' ";

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

			$eliminarSuspendida = "Delete from app_pos_venta_suspendida where id =" . $id;

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
	} */

	public function cargarSuspendida($id_susp) {
		//echo 'sdedededeedde';
		try {
				//Consultamos la informacion de la venta suspendida

			$datosSuspendida = "SELECT id, s_almacen, s_cambio, s_cliente, s_documento, s_empleado, s_funcion, s_idFact, s_impuestos, s_monto, s_pagoautomatico, s_sucursal, s_impuestost, arreglo1, arreglo2, identi, fecha, borrado";
			$datosSuspendida .= " from app_pos_venta_suspendida ";
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
			//echo 'e333333333';
			return array('estatus' => true, "productos" => $_SESSION['caja'], "cargos" => $_SESSION["caja"]['cargos'], "cliente" => $resultSuspendida["rows"][0]["s_cliente"]);
		} catch (Exception $e) {
			//echo 'eeeeeee';
			return array("status" => false, "msg" => $e);
		}
	}
	public function recalcula($idProducto,$cantidad,$precio,$field){
		//echo $idProducto.'XXX';
		$xyz = 1;
		if($xyz == 1){
			$_SESSION['caja'][$idProducto]->cantidad = $cantidad;
			$_SESSION['caja'][$idProducto]->precio = $precio;
			$_SESSION['caja'][$idProducto]->importe = ($precio * $cantidad);
		}else{
			$_SESSION['caja'][$idProducto]['cantidad'] = $cantidad;
			$_SESSION['caja'][$idProducto]['precio'] = $precio;
			$_SESSION['caja'][$idProducto]['importe'] = ($precio * $cantidad);
		}
 

		$sessionArray = $this->object_to_array($_SESSION['caja']);
		$totalProductos = 0;
		foreach ($sessionArray as $key => $value) {
			if($key !='cargos' && $key!='descGeneral' && $key!='descGeneralCant'){
				$stringTaxes .=$value['idProducto'].'-'.$value['precio'].'-'.$value['cantidad'].'-'.$value['formula'].'-'.$value['caracteristicas'].'/';
				$totalProductos += $value['cantidad'];
			}
		}

		if($field == "precio"){
			//$_SESSION['caja'][$idProducto]->nombre = $_SESSION['caja'][$idProducto]->descripcion;
			while( strrpos($_SESSION['caja'][$idProducto]->nombre, '[', 0) )
				$_SESSION['caja'][$idProducto]->nombre = substr( $_SESSION['caja'][$idProducto]->nombre , 0, (strrpos($_SESSION['caja'][$idProducto]->nombre, '[', 0))  ) ;
		}
		$this->calculaImpuestos($stringTaxes);

		return array('estatus' =>true,'productos' =>$_SESSION['caja'], 'cargos' => $_SESSION['caja']['cargos'],"count" => count($_SESSION['caja']), 'totalProductos' => $totalProductos );
		
	}
	public function eliminarSuspendida($id) {

		try {


			$eliminarSuspendida = "DELETE from app_pos_venta_suspendida where id =" . $id;
			$resutEliminaSuspendida = $this->queryArray($eliminarSuspendida);

			if (!$resutEliminaSuspendida["status"]) {
				throw new Exception("No se pudo eliminar la venta suspendida.");
			}

			//$this->commit();
			return array("status" => true);
		} catch (Exception $e) {
			//$this->rollback();
			return array("status" => false, "msg" => $e);
		}
	}
	public function touchProducts(){
		//$selectProd = "SELECT * from app_productos where status=1";
		$selectProd.="SELECT p.*, if(sum(vp.cantidad)!='', sum(vp.cantidad), 0) as cantidad";
		$selectProd.=" from app_productos p";
		$selectProd.=" left join app_pos_venta_producto vp on p.id=vp.idProducto";
		$selectProd.=" where p.status=1";
		$selectProd.=" group by p.id";
		$selectProd.=" order by cantidad desc";

		$restSelec =$this->queryArray($selectProd);

		$empleado=$_SESSION["accelog_idempleado"];
		$cliente = $this->buscaClienteP($empleado);
		$cliente = $cliente['rows'][0]['id'];
		$clientes = $this->clientePedido($cliente); 

		//print_r($clientes);
		foreach ($restSelec['rows'] as $key => $value) {
		//echo $value['id'].'<br>';
			if($clientes[0]['id_lista_precios'] > 0 && $clientes[0]['id_lista_precios']!=''){

				$selPrLis = "SELECT l.id, l.nombre, l.porcentaje, l.descuento, lp.id_producto";
				$selPrLis.=" from app_lista_precio l";
				$selPrLis.=" left join app_lista_precio_prods lp on lp.id_lista=l.id";
				$selPrLis.=" where l.id =".$clientes[0]['id_lista_precios']." and lp.id_producto=".$value['id'];
				$resPrLis = $this->queryArray($selPrLis);
				//print_r($resPrLis);
				$idListaPrecios = $clientes[0]['id_lista_precios'];
				$descuento = 0;
				$precioFinal = 0;
				$descuento = $value['precio'] * $resPrLis['rows'][0]['porcentaje']/ 100;
				if($resPrLis['rows'][0]['descuento'] == 1){
					$precioFinal = (float) $value['precio'] - (float) $descuento;
				}else{
					$precioFinal = (float) $value['precio'] + (float) $descuento;
				}

	
			}else{
				$precioFinal = $value['precio'];
				$idListaPrecios = 0;
			} 

			$imp = $this->calImpu($value['id'],$precioFinal,$value['formulaIeps']);
			//echo $value['nombre'].'='.$imp.'<br> ';
			$restSelec['rows'][$key]['precio']= $imp;
		}


		return  $restSelec['rows'];
	}
	public function calImpu($idProducto,$precio,$formula){

				if($formula==2){
					$ordenform = 'ASC';
				}else{
					$ordenform = 'DESC';
				}
				$subtotal = $precio;

				$queryImpuestos = "select p.id,p.precio, i.valor, i.clave,pi.formula,i.nombre";
				$queryImpuestos .= " from app_impuesto i, app_productos p ";
				$queryImpuestos .= " left join app_producto_impuesto pi on p.id=pi.id_producto ";
				$queryImpuestos .= " where p.id=" . $idProducto . " and i.id=pi.id_impuesto ";
				$queryImpuestos .= " Order by pi.id_impuesto ".$ordenform;
				//echo $queryImpuestos.'<br>';
				$resImpues = $this->queryArray($queryImpuestos);
//print_r($resImpues['rows']);
				foreach ($resImpues['rows'] as $key => $valueImpuestos) {
					if($valueImpuestos["clave"] == 'IEPS'){
						$producto_impuesto = $ieps = (($subtotal) * $valueImpuestos["valor"] / 100);
					}else{
						if($ieps!=0){
							$producto_impuesto = ((($subtotal + $ieps)) * $valueImpuestos["valor"] / 100);
						}else{
							//echo '/'.$subtotal.'-X'.$valueImpuestos["valor"].'X/';
							$producto_impuesto = (($subtotal) * $valueImpuestos["valor"] / 100);
							//echo '('.$producto_impuesto.')<br>';
						}
					}
				}
				//echo $producto_impuesto.'<br>';
				$precioNeto = $subtotal + $producto_impuesto;

				return $precioNeto;

	}
	public function estados(){
		$query = 'Select * from estados';
		$result = $this->queryArray($query);
		return $result['rows'];
	}
	public function municipios($idEstado){
		$queryM = "SELECT * from municipios where idestado=".$idEstado;
		$result = $this->queryArray($queryM);
		return $result['rows'];
	}
	public function munici(){
		$queryM = "SELECT * from municipios";
		$result = $this->queryArray($queryM);
		return $result['rows'];
	}
	public function listaPrecios(){
		$query = 'SELECT * from app_lista_precio where activo=1';
		$result = $this->queryArray($query);

		return $result['rows'];
	}
	public function moneda(){
		$query = "SELECT * from cont_coin";
		$result = $this->queryArray($query);

		return $result['rows'];
	}
	public function ventasCaja(){
		date_default_timezone_set("Mexico/General");
		$fechaactual = date("Y-m-d");

		$selectVentas ="SELECT
		v.idVenta as folio,
		v.fecha as fecha,
		v.envio as envio,
		CASE WHEN c.nombre IS NOT NULL
		THEN c.nombre
		ELSE 'Publico general'
		END AS cliente,
		e.nombre as empleado,
		s.nombre as sucursal,
		CASE WHEN v.estatus =1
		THEN 'Activa'
		ELSE 'Cancelada'
		END AS estatus,
		v.montoimpuestos as iva,
		(v.monto) as monto
		from app_pos_venta v left join comun_cliente c on c.id=v.idCliente inner join  empleados e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal where fecha like '%".$fechaactual."%'; " ;
		$resultVentas = $this->queryArray($selectVentas);

		return $resultVentas['rows'];
	}
	public function detalleVenta($idVenta){
	   $secltVent = "SELECT * from app_pos_venta where idVenta=".$idVenta;
	   $resSelc = $this->queryArray($secltVent);
	   //print_r($resSelc);
	   $productos = $this->productosventa($idVenta);
	   $impuestos_venta = json_decode($resSelc['rows'][0]['impuestos']);
	   $impuestos_venta = $this->object_to_array($impuestos_venta);
	   $pagos = $this->pagos($idVenta);

	   return array('products' => $productos,'taxes' => $impuestos_venta,'pay' => $pagos, 'total' => $resSelc['rows'][0]['monto']);
	}
	public function cancelarVenta($idVenta){

		date_default_timezone_set("Mexico/General");
		$fechaactual = date("Y-m-d H:i:s");
		///Cambia el estatus de la venta
		$updateVent = "UPDATE app_pos_venta set estatus=0 where idVenta=".$idVenta;
		$resUpdate = $this->queryArray($updateVent);


		$selAl = "SELECT idSucursal from app_pos_venta where idVenta=".$idVenta;
		$resAl = $this->queryArray($selAl);
		$idsucursal = $resAl['rows'][0]['idSucursal'];

		$sel = "SELECT * from mrp_sucursal where idSuc=".$_SESSION['sucursal'];
		$res =$this->queryArray($sel);

		$idAlmacen = $res['rows'][0]['idAlmacen'];




		$selPro = 'SELECT idProducto,cantidad,total from app_pos_venta_producto where idVenta='.$idVenta;
		$resSel = $this->queryArray($selPro);

		foreach ($resSel['rows'] as $key => $value) {
			$inser = "INSERT into app_inventario_movimientos(id_producto,cantidad,importe,id_almacen_origen,fecha,id_empleado,tipo_traspaso,costo,referencia) values('".$value['idProducto']."','".$value['cantidad']."','".$value['total']."','".$idAlmacen."','".$fechaactual."','".$_SESSION['accelog_idempleado']."','1','".$importe."','Cancelacion Venta ".$idVenta."')";
			$x = $this->queryArray($inser);

		}

		return  array('estatus' => true );

	}
	public function descuentoGeneral($descuento){
		//echo 'XXXX'.$descuento;
		//print_r($_SESSION['caja']);
		//exit();
		$stringTaxes = '';
		$y1 = 0;
		$_SESSION['caja']['cargos']['total'];
		if($descuento<10){
			$descuento='0'.$descuento;
		}
		$x = '0.'.$descuento;
		if($descuento > 99){
			$x = 0;

		}
		//$y = (float) $_SESSION['caja']['cargos']['subtotal'] * (float) $x;
		//$_SESSION['caja']['cargos']['descGeneral'] = $y;
		//$_SESSION['caja']['cargos']['subtotal'] =  $_SESSION['caja']['cargos']['subtotal'] - $y;
		//$_SESSION['caja']['cargos']['total'] =  $_SESSION['caja']['cargos']['total'] - $y;
		//echo '['.(float) $_SESSION['caja']['cargos']['subtotal'] * (float) $x; 
		$_SESSION['caja']['cargos']['descGeneral'] = 100;  
		$sessCaja = $this->object_to_array($_SESSION['caja']);
		foreach ($sessCaja as $key => $value) {
			if($key !='cargos' && $key!='descGeneral' && $key!='pedido'){
				if($x == 0){
					$value['precio'] = 0;
				}
				$desc = $value['precio']*$x;
				$y1 += floatval($desc) * floatval($value['cantidad']); 
				$value['precio'] =  $value['precio'] - $desc;

				$stringTaxes .=$key.'-'.$value['precio'].'-'.$value['cantidad'].'-'.$value['formula'].'/';
				$productosTotal += $value['cantidad'];
			}
		}
		session_start();
		if($descuento > 99){
			$y1 = $_SESSION['caja']['cargos']['subtotal'];
		} 
		$_SESSION['caja']['descGeneral']= $y1;
		$_SESSION['caja']['descGeneralCant'] = $descuento;


		$this->calculaImpuestos($stringTaxes);


		return array('estatus' =>true,'productos' =>$_SESSION['caja'], 'cargos' => $_SESSION['caja']['cargos'], 'totalProductos' => $productosTotal, 'descGeneral' => $y1);
	}
	public function verificaRfcmodal($rfc){
		$select = "SELECT f.id,f.nombre,f.rfc,f.razon_social,f.correo,f.pais,f.regimen_fiscal,f.domicilio,f.num_ext,f.cp,f.colonia,e.estado,f.ciudad,f.municipio from comun_facturacion f,estados e where f.rfc='".$rfc."' and f.estado=e.idestado";
		$resSelct = $this->queryArray($select);

		if($resSelct['total']>0){
			return array('estatus' => true ,'datosFac' => $resSelct['rows']);
		}else{
			return array('estatus' => false );
		}

	}
	public function datosFacturacionCliente($idFact){
	  /*$datosFacturacion = "SELECT nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
			e.estado estado,ciudad,municipio,regimen_fiscal,cf.estado as idEstado
			from comun_facturacion cf left join estados e on  e.idestado=cf.estado
			where  id=" . $idFact; */
		$datosFacturacion ="SELECT nombre, domicilio,cp,colonia,num_ext,pais,correo,razon_social,rfc,cf.id as idFac,
			e.estado estado,ciudad,cf.municipio,regimen_fiscal,cf.estado as idEstado, m.idmunicipio as idMunicipio
			from comun_facturacion cf left join estados e on  e.idestado=cf.estado
			left join municipios m on cf.municipio=m.municipio
			where  id=".$idFact;

			$result = $this->queryArray($datosFacturacion);

		return array("Datafact" => $result['rows']);
	}
	public function updateDatosFac($idFac,$rfc,$razSoc,$email,$pais,$regimen,$domicilio,$numero,$cp,$col,$estado,$municipio,$ciudad){
		$selcMuni = "SELECT * from municipios where idmunicipio=".$municipio;
		$resmunici = $this->queryArray($selcMuni);
		$municipioNombre = $resmunici['rows'][0]['municipio'];

		$update = "UPDATE comun_facturacion set rfc='".$rfc."', razon_social='".$razSoc."', correo='".$email."', pais='".$pais."', regimen_fiscal='".$regimen."', domicilio='".$domicilio."', num_ext='".$numero."', cp='".$cp."', colonia='".$col."', estado='".$estado."', ciudad='".$ciudad."', municipio='".$municipioNombre."' where id=".$idFac;

		$res = $this->queryArray($update);

		return  array('estatus' => true , 'Datos' => $res['rows'] );

	}
	public function oneFact($idComunFactu,$idVenta){

		require_once('../../modulos/SAT/config.php');
		date_default_timezone_set("Mexico/General");
		$fecha=date('Y-m-d').'T'.date('H:i:s',strtotime("-5 minute"));

		$SeleCad = "SELECT cadenaOriginal FROM app_pendienteFactura WHERE id_sale=".$idVenta;
		$cadenaOri = $this->queryArray($SeleCad);
		//echo $cadenaOri['rows'][0]['cadenaOriginal'];
		$azurian=base64_decode($cadenaOri['rows'][0]['cadenaOriginal']);

		$azurian = str_replace("\\", "", $azurian);
		if($azurian!=''){
			$azurian=json_decode($azurian);
		}
		$azurian = $this->object_to_array($azurian);

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

		/* Datos Receptor
		============================================================== */
		if($idComunFactu!=''){

			$selComFac = "SELECT * FROM comun_facturacion WHERE id=".$idComunFactu;
			$result = $this->queryArray($selComFac);
			//Estado
			$selEstado ="SELECT estado from estados where idestado=".$result['rows'][0]['estado'];
			$resultEstado = $this->queryArray($selEstado);


			$idCliente=$rs{'nombre'};
			$azurian['Receptor']['rfc']=strtoupper($result['rows'][0]['rfc']);
			$azurian['Receptor']['nombre']=strtoupper($result['rows'][0]['razon_social']);
			$azurian['DomicilioReceptor']['calle']=$result['rows'][0]['domicilio'];
			$azurian['DomicilioReceptor']['noExterior']=$result['rows'][0]['num_ext'];
			$azurian['DomicilioReceptor']['colonia']=$result['rows'][0]['colonia'];
			$azurian['DomicilioReceptor']['localidad']=$result['rows'][0]['ciudad'];
			$azurian['DomicilioReceptor']['municipio']=$result['rows'][0]['municipio'];
			$azurian['DomicilioReceptor']['estado']=$resultEstado['rows'][0]['estado'];
			$azurian['DomicilioReceptor']['pais']=$result['rows'][0]['pais'];
			$azurian['DomicilioReceptor']['codigoPostal']=$result['rows'][0]['cp'];
			$azurian['Correo']['Correo'] = $result['rows'][0]['correo'];

		}else{
			$idCliente='';
			$azurian['Receptor']['rfc']='XAXX010101000';
			$azurian['Receptor']['nombre']='Factura generica';
			$azurian['DomicilioReceptor']['calle']='';
			$azurian['DomicilioReceptor']['noExterior']='';
			$azurian['DomicilioReceptor']['colonia']='';
			$azurian['DomicilioReceptor']['localidad']='';
			$azurian['DomicilioReceptor']['municipio']='';
			$azurian['DomicilioReceptor']['estado']='';
			$azurian['DomicilioReceptor']['pais']='';
			$azurian['DomicilioReceptor']['codigoPostal']='';
			$azurian['Correo']['Correo'] = '';
		}


		$serFol = "SELECT * FROM pvt_serie_folio WHERE id=1";
		$rs3 = $this->queryArray($serFol);

		$selecLogo = "SELECT logoempresa FROM organizaciones WHERE idorganizacion=1";
		$rs4 = $this->queryArray($selecLogo);

		$azurian['org']['logo'] = $rs4['rows'][0]['logoempresa'];

		/* Datos serie y folio
		============================================================== */
		$azurian['Basicos']['serie']=$rs3['rows'][0]['serie']; //No obligatorio
		$azurian['Basicos']['folio']=$rs3['rows'][0]['folio'];

		/* Datos Emisor
		============================================================== */
		$azurian['Emisor']['rfc']=strtoupper($parametros['EmisorTimbre']['RFC']);
		$azurian['Emisor']['nombre']=strtoupper($parametros['EmisorTimbre']['RazonSocial']);
		/* Datos Fiscales Emisor
		============================================================== */
		$azurian['FiscalesEmisor']['calle']=$parametros['EmisorTimbre']['Calle'];
		$azurian['FiscalesEmisor']['noExterior']=$parametros['EmisorTimbre']['NumExt'];
		$azurian['FiscalesEmisor']['colonia']=$parametros['EmisorTimbre']['Colonia'];
		$azurian['FiscalesEmisor']['localidad']=$parametros['EmisorTimbre']['Ciudad'];
		$azurian['FiscalesEmisor']['municipio']=$parametros['EmisorTimbre']['Municipio'];
		$azurian['FiscalesEmisor']['estado']=$parametros['EmisorTimbre']['Estado'];
		$azurian['FiscalesEmisor']['pais']=$parametros['EmisorTimbre']['Pais'];
		$azurian['FiscalesEmisor']['codigoPostal']=$parametros['EmisorTimbre']['CP'];

		/* Datos Regimen
		============================================================== */
		$azurian['Regimen']['Regimen']=$parametros['EmisorTimbre']['RegimenFiscal'];

		/* Fecha Factura
		============================================================== */
		$azurian['Basicos']['fecha']=$fecha;

		/* Impuestos
		============================================================== */
		$tisr=$azurian['Impuestos']['totalImpuestosRetenidos'];
		$tiva=$azurian['Impuestos']['totalImpuestosTrasladados'];
		$tieps=$azurian['Impuestos']['totalImpuestosIeps'];

	//    $positionPath='../../webapp/modulos';


		require_once('../../modulos/lib/nusoap.php');
		require_once('../../modulos/SAT/funcionesSAT.php');

	}
	public function Iniciarcaja($sucursal,$monto){
		date_default_timezone_set("Mexico/General");
		$fechaactual = date("Y-m-d H:i:s");
		$_SESSION['sucursal'] = $sucursal;

		$insertInicioCaja = "INSERT INTO app_pos_inicio_caja(id,fecha,monto,idUsuario,idCortecaja,idSucursal) values('','" . $fechaactual . "','" . $monto . "'," . $_SESSION['accelog_idempleado'] . ",NULL," . $sucursal . ")";

		$resultInsert = $this->queryArray($insertInicioCaja);

		$query = "select  s.idSuc, s.nombre sucursal,a.idAlmacen ,a.nombre almacen from mrp_sucursal s, almacen a where s.idAlmacen=a.idAlmacen and s.idSuc=" . $sucursal;

		$resultQuery = $this->queryArray($query);

		$id = $resultQuery["rows"][0]["idSuc"];
		$sucursal = $resultQuery["rows"][0]["sucursal"];
		$idAlmacen = $resultQuery["rows"][0]["idAlmacen"];

		return '<input type="hidden" id="caja-sucursal" value="' . $idSuc . '"><input type="hidden" id="caja-almacen" value="' . $idAlmacen . '">Sucursal:' . $sucursal;
	}
	public function getInfoProducto($id){

		return  array('nombre' => $_SESSION['caja'][$id]->nombre , 'precio' =>$_SESSION['caja'][$id]->precio);
	}
	public function getCut($init, $end, $onlyShow , $iduser){


				if($onlyShow==0){
					//echo 'Entro al False';
					date_default_timezone_set("Mexico/General");
					$fechaactual = date("Y-m-d H:i:s");

					$iduser = $_SESSION['accelog_idempleado'];

					$selIniCaj = "SELECT max(fecha) as fechaInicio from app_pos_inicio_caja where idUsuario=".$iduser;
					$resFechaIni = $this->queryArray($selIniCaj);

					$SelemontoInicial ="SELECT monto from app_pos_inicio_caja where idUsuario='".$iduser."' and fecha='".$resFechaIni['rows'][0]['fechaInicio']."'";
					$resMon = $this->queryArray($SelemontoInicial);

					$montoInical = $resMon['rows'][0]['monto'];


					$init = $resFechaIni['rows'][0]['fechaInicio'];
					$end = $fechaactual;

				}else{
					//echo 'entro al true';
				}



				$sql  = 'SELECT ';
				$sql .= '   "Ventas" AS Flag, ';
				$sql .= '   v.idVenta, ';
				$sql .= '   v.fecha, ';
				$sql .= '   c.nombre, ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 1 AND v.idVenta = vp.idVenta )  AS Efectivo , ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 4 AND v.idVenta = vp.idVenta ) AS TCredito, ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 5 AND v.idVenta = vp.idVenta ) AS TDebito, ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 6 AND v.idVenta = vp.idVenta ) AS CxC, ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 2 AND v.idVenta = vp.idVenta ) AS Cheque, ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 7 AND v.idVenta = vp.idVenta ) AS Trans, ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 8 AND v.idVenta = vp.idVenta ) AS SPEI, ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 3 AND v.idVenta = vp.idVenta ) AS TRegalo, ';
				$sql .= '   ( SELECT if(ISNULL(SUM(vp.monto)),0.00,SUM(vp.monto))  FROM app_pos_venta_pagos vp WHERE vp.idFormapago = 9 AND v.idVenta = vp.idVenta ) AS Ni, ';
				//$sql .= '     ';
				$sql .= '   REPLACE(FORMAT(v.cambio, 2),",","") as cambio, ';
				$sql .= '   REPLACE(FORMAT(v.montoimpuestos, 2), ",", "") AS Impuestos, ';
				$sql .= '   REPLACE(FORMAT((v.monto - v.montoimpuestos), 2 ), ",", "") AS Monto, ';
				$sql .= '   REPLACE(FORMAT(v.monto, 2), ",", "") AS Importe ';
				$sql .= 'FROM ';
				$sql .= '   app_pos_venta v ';
				$sql .= '   LEFT JOIN app_pos_venta_pagos p ON p.idVenta = v.idVenta ';
				$sql .= '   LEFT JOIN comun_cliente c ON v.idCliente = c.id ';
				$sql .= 'WHERE ';
				$sql .= '   v.estatus = 1 ';
				$sql .= '   AND ';
				//$sql .= '   v.idEmpleado = ' . $iduser . ' ';
				//$sql .= '   AND ';
				$sql .= '   v.fecha BETWEEN ';
				$sql .= '   "' . $init . '" ';
				$sql .= '   AND ';
				$sql .= '   "' . $end . '" ';
				$sql .= 'GROUP BY ';
				$sql .= '   v.idVenta ';
				$resVentas = $this->queryArray($sql);
				//echo $sql.'<br>';
				///Obtiene los productos vendidos
				$sql2 = 'SELECT ';
				$sql2 .= '   "Productos" AS Flag, ';
				$sql2 .= '   p.codigo, ';
				$sql2 .= '   p.nombre, ';
				$sql2 .= '   sum(vp.cantidad) AS Cantidad, ';
				$sql2 .= '   REPLACE(FORMAT(vp.preciounitario,2), ",", "") AS preciounitario, ';
				$sql2 .= '   REPLACE(FORMAT(sum(vp.montodescuento), 2), ",", "") AS Descuento, ';
				$sql2 .= '   REPLACE(FORMAT(sum(vp.impuestosproductoventa), 2), ",", "") AS Impuestos, ';
				//$sql .= ' REPLACE(FORMAT(sum( (vp.subtotal + vp.impuestosproductoventa) - vp.descuento ), 2), ",", "") AS Subtotal, ';
				$sql2 .= '   REPLACE(FORMAT(sum(vp.total), 2), ",", "") AS Subtot, ';
				$sql2 .= '   0.00, ';
				$sql2 .= '   0.00, ';
				$sql2 .= '   0.00, ';
				$sql2 .= '   0.00, ';
				$sql2 .= '   0.00, ';
				$sql2 .= '   0.00, ';
				$sql2 .= '   0.00, ';
				$sql2 .= '   0.00, ';
				$sql2 .= '   0.00 ';
				$sql2 .= 'FROM ';
				$sql2 .= '   app_pos_venta_producto vp ';
				$sql2 .= '   INNER JOIN app_productos p ON vp.idProducto = p.id ';
				$sql2 .= 'WHERE ';
				$sql2 .= '   vp.idVenta IN(SELECT idVenta from app_pos_venta v WHERE v.idEmpleado = ' . $iduser . ' AND v.estatus = 1 AND v.fecha BETWEEN "' . $init . '" AND "' . $end . '") ';
				$sql2 .= 'GROUP BY ';
				$sql2 .= '   p.id ';
				$resProductos = $this->queryArray($sql2);
				/*var_dump($resProductos);
				echo $sql2;
				print_r($resVentas['rows']);
				echo '<br/><br/><br/>';
				print_r($resProductos['rows']); */
				//echo $sql2;
				//retiros de caja
				$sql3 = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha from app_pos_retiro_caja r, accelog_usuarios u where r.idempleado=u.idempleado and fecha between  '".$init."' and '".$end."' and r.idempleado=".$iduser;
				//echo $sql3;
				$resRetiros = $this->queryArray($sql3);

				foreach ($resVentas['rows'] as $key => $value) {
				   $x = $value['Efectivo'] - $value['cambio'];
				   $totalX += $x;
				}


				foreach ($resRetiros['rows'] as $key1 => $value1) {
				   $totalRetiros += $value1['cantidad'];
				}

				$saldoDisponible = ($montoInical+$totalX) - $totalRetiros;

			return  array('ventas' => $resVentas['rows'] ,'productos' => $resProductos['rows'], 'retiros' => $resRetiros['rows'], 'desde' => $init, 'hasta' => $end, 'montoInical' => $montoInical, 'monto_ventas' => $totalX, 'saldoDisponible' => $saldoDisponible );
	}
	public function eliminarPago($pago) {

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
	public function crearCorte($fecha_inicio,$fecha_fin,$saldo_inicial,$monto_venta,$saldo_disponible,$retiro_caja,$deposito_caja,$retiros){

		$saldo_final = ( $saldo_disponible - $retiro_caja ) + $deposito_caja;

				$qry  = "INSERT INTO app_pos_corte_caja ";
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
				$res1 = $this->queryArray($qry);
				$id = $res1['insertId'];

				$token =explode("-", $retiros);
				foreach ($token as $key => $value) {
					if(is_numeric($value)){
						$updt = "UPDATE venta_retiro_caja set idcorte=".$id." where id=".$value;
						$resUPdt = $this->queryArray($updt);
					}

				}

				$qry  = "SELECT ";
				$qry .= "au.idSuc ";
				// $qry .= "mp.nombre ";
				$qry .= "FROM ";
				$qry .= "administracion_usuarios au ";
				// $qry .= "mrp_sucursal mp ";
				$qry .= "WHERE ";
				//$qry .= "mp.idSuc=au.idSuc ";
				$qry .= "au.idempleado = " . $_SESSION['accelog_idempleado'] . ";";

				$res2 = $this->queryArray($qry);
				$sucursal_id = $res2['rows'][0]['idSuc'];

				$qry  = "UPDATE ";
				$qry .= "app_pos_inicio_caja ";
				$qry .= "SET ";
				$qry .= "idCortecaja = " . $id . " ";
				$qry .= "WHERE ";
				$qry .= "idCortecaja IS NULL ";
				$qry .= "AND idSucursal = " . $sucursal_id . " ";
				$qry .= "AND idUsuario = " . $_SESSION['accelog_idempleado'] . ";";

				$res3 = $this->queryArray($qry);

				return  array('idCorte' => $id );
	}
	public function obtenAlm(){
		$sel = "SELECT * from mrp_sucursal where idSuc=".$_SESSION['sucursal'];
		$res =$this->queryArray($sel);

		return $res['rows'][0]['idAlmacen'];
	}

	public function newClientDatfact($idFac,$rfc,$razSoc,$email,$pais,$regimen,$domicilio,$numero,$cp,$col,$estado,$municipio,$ciudad){

		$queryCliente = "INSERT INTO comun_cliente (codigo,nombre,nombretienda,direccion,colonia,email,celular,cp,idEstado,idMunicipio,rfc,curp,telefono1,telefono2,limite_credito,dias_credito,num_ext,num_int,id_clasificacion,permitir_vtas_credito,id_tipo_credito,permitir_exceder_limite,dcto_pronto_pago,intereses_moratorios,id_lista_precios,envios,comision_vta,comision_cobranza) values ";
		$queryCliente .="('".$codigo."','".$razSoc."','".$tienda."','".$domicilio."','".$col."','".$email."','".$celular."','".$cp."','".$estado."','".$municipio."','".$rfc."','".$curp."','".$tel1."','".$tel2."','".$limiteCredito."','".$diasCredito."','".$numext."','".$mumint."','".$tipoClas."','".$perVenCre."','".$tipoDeCredito."','".$perExLim."','".$descuentoPP."','".$interesesMoratorios."','".$listaPrecio."','".$enviosDom."','".$comisionVenta."','".$comisionCobranza."')";
		$insertClienteRes = $this->queryArray($queryCliente);
		$idClienteInsert = $insertClienteRes['insertId'];



		$selcMuni = "SELECT * from municipios where idmunicipio=".$municipio;
		$resmunici = $this->queryArray($selcMuni);
		$municipioNombre = $resmunici['rows'][0]['municipio'];

		$insertCo = "INSERT into comun_facturacion(nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,estado,ciudad,municipio) values('".$idClienteInsert."','".$rfc."','".$razSoc."','".$email."','".$pais."','".$regimen."','".$domicilio."','".$numero."','".$cp."','".$col."','".$estado."','".$ciudad."','".$municipioNombre."')";
		$resInsert = $this->queryArray($insertCo);

		if(is_numeric($resInsert['insertId'])){
			return  array('estatus' => true );
		}else{
			return  array('estatus' => false );
		}



	}
	public function ventasGrid(){

		$selectVentas ="SELECT
		v.idVenta as folio,
		v.fecha as fecha,
		v.envio as envio,
		CASE WHEN c.nombre IS NOT NULL
		THEN c.nombre
		ELSE 'Publico general'
		END AS cliente,
		e.nombre as empleado,
		s.nombre as sucursal,
		CASE WHEN v.estatus =1
		THEN 'Activa'
		ELSE 'Cancelada'
		END AS estatus,
		v.montoimpuestos as iva,
		(v.monto) as monto
		from app_pos_venta v left join comun_cliente c on c.id=v.idCliente inner join  empleados e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal" ;
		$resultVentas = $this->queryArray($selectVentas);

		return  array('ventas' => $resultVentas['rows']);
	}
	public function ventasIndex()
	{
		$result2 = $this->touchProducts();


		$query3 = "SELECT * from accelog_usuarios";
		$result3 = $this->queryArray($query3);

		$query45 = "SELECT * from comun_cliente";
		$result5 = $this->queryArray($query45);

		//return $result['rows'];
		return array('productos' => $result2 ,  'usuarios' => $result3['rows'], 'clientes' => $result5['rows']);


	}
	public function buscarVentas($cliente,$empleado,$desde,$hasta){

	$inicio = $desde;
	$fin = $hasta;
	$filtro=1;

	if($fin!="")
	{
		list($a,$m,$d)=explode("-",$fin);
		$fin=$a."-".$m."-".((int)$d+1);
	}


	if($inicio!="" && $fin=="")
	{
		$filtro.=" and  fecha >= '".$inicio."' ";
	}
	if($fin!="" && $inicio=="")
	{
		$filtro.=" and  fecha <= '".$fin."' ";
	}
	if($inicio!="" && $fin!="")
	{
		$filtro.=" and  fecha <= '".$fin."' and   fecha >= '".$inicio."' ";
	}

	if(is_numeric($estatus))
	{
		$filtro.=" and estatus=".$estatus;
	}

	if(is_numeric($sucursal))
	{
		$filtro.=" and idSucursal=".$sucursal;
	}
	if(is_numeric($vendedor))
	{
		$filtro.=" and v.idEmpleado=".$vendedor;
	}
	if(is_numeric($cliente))
	{
		if($cliente==0)
			{$filtro.=" and c.nombre is null ";

		}else{  $filtro.=" and idCliente=".$cliente;}
	}
		$selectVentas ="SELECT
		v.idVenta as folio,
		v.fecha as fecha,
		v.envio as envio,
		CASE WHEN c.nombre IS NOT NULL
		THEN c.nombre
		ELSE 'Publico general'
		END AS cliente,
		e.nombre as empleado,
		s.nombre as sucursal,
		CASE WHEN v.estatus =1
		THEN 'Activa'
		ELSE 'Cancelada'
		END AS estatus,
		v.montoimpuestos as iva,
		(v.monto) as monto
		from app_pos_venta v left join comun_cliente c on c.id=v.idCliente inner join  empleados e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal where  ".$filtro." order by folio desc" ;
		//echo $selectVentas;
		$resultVentas = $this->queryArray($selectVentas);

		return  array('ventas' => $resultVentas['rows']);

	}
	public function buscaVentaCaja($idVenta){
		 $selectVentas ="SELECT
		v.idVenta as folio,
		v.fecha as fecha,
		v.envio as envio,
		CASE WHEN c.nombre IS NOT NULL
		THEN c.nombre
		ELSE 'Publico general'
		END AS cliente,
		e.nombre as empleado,
		s.nombre as sucursal,
		CASE WHEN v.estatus =1
		THEN 'Activa'
		ELSE 'Cancelada'
		END AS estatus,
		v.montoimpuestos as iva,
		(v.monto) as monto
		from app_pos_venta v left join comun_cliente c on c.id=v.idCliente inner join  empleados e on e.idempleado=v.idEmpleado inner join mrp_sucursal s on s.idSuc=v.idSucursal where v.idVenta =".$idVenta;
		$resultVentas = $this->queryArray($selectVentas);

		return  array('venta' => $resultVentas['rows']);
	}
	public function datosretiro($idRetiro){
		$datosRetiro = "SELECT r.id,r.cantidad,r.concepto, u.usuario, r.fecha, s.nombre as sucursal from app_pos_retiro_caja r, accelog_usuarios u, mrp_sucursal s where r.idSucursal=s.idSuc and r.idempleado=u.idempleado and id=".$idRetiro;
	   ;
		$result = $this->queryArray($datosRetiro);
		return $result['rows'];

	}
	public function graficar($desde,$hasta){

	$inicio = $desde;
	$fin = $hasta;
	//$filtro=1;

	if($fin!="")
	{
		list($a,$m,$d)=explode("-",$fin);
		$fin=$a."-".$m."-".((int)$d+1);
	}


	if($inicio!="" && $fin=="")
	{
		$filtro.=" where fecha >= '".$inicio."' ";
	}
	if($fin!="" && $inicio=="")
	{
		$filtro.=" where fecha <= '".$fin."' ";
	}
	if($inicio!="" && $fin!="")
	{
		$filtro.=" where fecha <= '".$fin."' and   fecha >= '".$inicio."' ";
	}




		$sel = 'SELECT p.nombre as label , sum(cantidad) as value';
		$sel.= ' from app_pos_venta_producto vp';
		$sel.= ' INNER JOIN app_productos p ON p.id = vp.idProducto';
		$sel.= ' INNER JOIN app_pos_venta v on v.idVenta=vp.idVenta';
		$sel.= ' INNER JOIN accelog_usuarios u on u.idempleado = v.idEmpleado';
		$sel.= ' '.$filtro;
		$sel.= ' group by idProducto';
		$sel.= ' order by cantidad desc';
		$sel.= ' limit 5';

		$resGra = $this->queryArray($sel);



		$sel2 = 'SELECT v.fecha as y, sum(v.monto) as a';
		$sel2.= ' from app_pos_venta v';
		//$sel2.= ' INNER JOIN app_pos_venta_producto vp on v.idVenta=vp.idVenta';
		$sel2.= ' '.$filtro;
		$sel2.= ' group by month(v.fecha)';
		//echo $sel2;
		//exit();
		$resGra2 = $this->queryArray($sel2);


		return array('dona' => $resGra['rows'], 'linea' => $resGra2['rows'] );

	}
	public function getCortes(){
		$query = 'SELECT c.idCortecaja, c.fechainicio, c.fechafin, c.saldoinicialcaja, c.montoventa, c.retirocaja, c.abonocaja, c.saldofinalcaja, u.usuario from app_pos_corte_caja c , accelog_usuarios u where c.idEmpleado=u.idempleado';
		$res1 = $this->queryArray($query);

		return array('cortes' =>  $res1['rows']);
	}
	public function saldosCorte($idCorte){
		$selc = 'SELECT c.*, u.usuario from app_pos_corte_caja c , accelog_usuarios u where c.idEmpleado=u.idempleado and idCortecaja='.$idCorte;
		$result = $this->queryArray($selc);

		return $result['rows'];
	}
	public function cortesfiltrados($empleado,$desde,$hasta){

		$filtro = '';

		if($empleado!=0){
			$filtro .= ' and c.idEmpleado='.$empleado;
		}
		if($desde!='' && $hasta!=''){
			$filtro .=' and fechainicio BETWEEN "'.$desde.'" AND "'.$hasta.'" ';
		}


		$query = 'SELECT c.idCortecaja, c.fechainicio, c.fechafin, c.saldoinicialcaja, c.montoventa, c.retirocaja, c.abonocaja, c.saldofinalcaja, u.usuario from app_pos_corte_caja c , accelog_usuarios u where c.idEmpleado=u.idempleado'.$filtro;
		//echo $query;
		$res1 = $this->queryArray($query);

		return array('cortes' =>  $res1['rows']);
	}
	public function enviarTicket($idVenta,$correo){


			$organizacion = $this->datosorganizacion();
			$venta = $this->datosventa($idVenta);
			$productos = $this->productosventa($idVenta);
			$impuestos_venta = json_decode($venta[0]['jsonImpuestos']);
			$impuestos_venta = $this->object_to_array($impuestos_venta);
			$pagos = $this->pagos($idventa);
			$content='';
			/*$content .='<div id="contenedor" style="font-family:Euphemia UCAS; font-color:black;">';
			$content .='   <div align="center" style="height:80px;background:#a1b62c;">';
			$content .='    <img src="https://www.netwarmonitor.com/assets/img/netwarmonitor.png?1435793573" alt="" align="center" style="padding-top:2%">';
			$content .='</div>'; */

			$imagen='../../netwarelog/archivos/1/organizaciones/'.$organizacion[0]['logoempresa'];
			$imagesize=getimagesize($imagen);
			$porcentaje=0;
			if($imagesize[0]>200 && $imagesize[1]>90){
				if($imagesize[0]>$imagesize[1]){
					$porcentaje=intval(($imagesize[1]*100)/$imagesize[0]);
					$imagesize[0]=200;
					$imagesize[1]=(($porcentaje*200)/100);
				}else{
					$porcentaje=intval(($imagesize[0]*100)/$imagesize[1]);
					$imagesize[0]=200;
					$imagesize[1]=(($porcentaje*200)/100);
				}
			}
			$src="";
			if($imagen!="" && file_exists($imagen)){
				$src='<img src="'.$imagen.'" style="width:'.$imagesize[0].'px;height:'.$imagesize[1].'px;display:block;margin:0 auto 0 auto;"/>';
			}

			$content .='<div align="center">'.$src.'</div>';
			//info empresa
			$content .='<div align="center">';
			$content .='                <div id="empresa"><h2>'.$organizacion[0]['nombreorganizacion'].'</h2></div>';
			$content .='                <div id="direccion">'.utf8_decode($organizacion[0]['domicilio']." ".$organizacion[0]['municipio'].",".$organizacion[0]['estado']).'</div>';
			$content .='                <div id="rfc">'.'</div>';
			$content .='                <div id="documento">Ticket de Compra : '.$venta[0]['folio'].'</div>';
			$content .='                <div id="fecha">'.$this->formatofecha($venta[0]['fecha']).'</div>';
			$content .='                <div id="sucursal">Sucursal: '.$_SESSION["sucursalNombre"].'</div>';
			$content .='                <div id="cliente">Cliente: '.$venta[0]['cliente'].'</div>';
			$content .='                <div id="empleado">Empleado: '.$venta[0]['empleado'].'</div>';
			$content .='            </div>';
			//tabla de productos
			$content .='<div id="productos" align="center">
						<table>
							<tr>
								<td align="center">Cantidad</td>
								<td align="center">Producto</td>
								<td align="center">Total</td>
							</tr>';
							$sub = 0;
							foreach ($productos as $key => $value) {
							 $content.= "<tr>";
							 $content.= "<td style='text-align:center;'>".$value['cantidad']."</td>";
							 $content.= "<td style='text-align:center;' class='textWrap'>".$value['nombre']."</td>";
							 $content.= "<td style='text-align:right;'>$".number_format(($value['cantidad'] * $value['preciounitario']),2)."</td>";
							 $content.= "</tr>";
							 $sub +=($value['cantidad'] * $value['preciounitario']);
							}
			$content .='<tr>
						<td colspan="2" style="text-align:right;border-top:2px solid #000000;"><b>Subtotal:</b></td>
						<td colspan="1" style="text-align:right;border-top:2px solid #000000;">$'.number_format($sub,2,".",",").'</td>
						</tr>';

					foreach ($impuestos_venta as $key2 => $value2) {
						//ech$content.=o 'CCCC'.$key;
						$content.= '<tr>';
						$content.= '<td colspan="2" style="text-align:right;">'.$key2.'</td>';
						$content.= '<td colspan="1" style="text-align:right;">$'.number_format($value2,2).'</td>';
						$content.= '</tr>';
						$totalimpuestos+=$value2;
					}
			$content.='<tr>
							<td colspan="2" style="text-align:right;"><b>Total:</b></td>
							<td colspan="1" style="text-align:right">$'.number_format($sub+$totalimpuestos,2,".",",").'</td>
						</tr>';
			$content.='<tr><td colspan="6">&nbsp;</td></tr>';

				foreach ($pagos as $key => $value) {
					$content.= '<tr><td colspan="2" style="text-align:right;"><b>'.$value['nombre'].'</b></td>';
					$content.= '<td colspan="1" style="text-align:right">$'.number_format($value['monto'],2).'</td></tr>';
				}

			$content.='<tr>
						<td colspan="2" style="text-align:right;"><b>Cambio</b></td>
						<td colspan="1" style="text-align:right">$'.number_format($venta[0]['cambio'],2,".",",").'</td>
					 </tr>';
			$content .='</table>';

			$configTikcet = "SELECT ticket_config FROM pvt_configura_facturacion WHERE id=1";
			$res = $this->queryArray($configTikcet);
			if($res['rows'][0]['ticket_config']>0){

					$url="netwarmonitor.mx/clientes/".$_SESSION['accelog_nombre_instancia']."/facturar";
					if(strlen($url) >50)
					{
						//echo $url;
						/*$url1 = substr($url, 0,50);
						$url2 = substr($url, 51);

						echo $url1."</br>";
						echo $url2; */
					}else
					{
						//echo $url;
					}
				$longuitud=strlen($_SESSION['accelog_nombre_instancia']);
				$codinstancia=$_SESSION['accelog_nombre_instancia'][0].$_SESSION['accelog_nombre_instancia'][$longuitud-1];

				$fecha=str_replace('-', '', $venta[0]['fecha'] );
				$fecha=str_replace(':', '', $fecha);
				$fecha=str_replace(' ', '', $fecha);
		//echo "Codigo sin convertir:".$codinstancia.$fecha.$venta->folio.";";
				//$codigoHex=base64_encode($codinstancia.$fecha.$venta->folio);
				$codigoHex = $codinstancia.dechex($fecha.$venta[0]['folio']);
				$codigoFactura=$codigoHex;
				//echo $codigoFactura;

				$content.='<div id="codigoFac" align="center" style="background:#CFCFC4;">
								<div>Para obtener tu factura ingresa a la direccion</div>
								<div id="urlFac">'.$url.'</div>
								<div> Ingresando el Siguiente codigo:</div>
								<div id="codigoFac" style="font-size:20px"> <strong>'.$codigoFactura.'</strong></div>
							</div>';

			}
		   /* $content .='<div id="promocion" align="center" style="background:#a1b62c;font-size:12px;">
							<div><img src="https://www.netwarmonitor.com/assets/img/netwarmonitor.png?1435793573" alt="" align="center" style="padding-top:2%"></div>
							<div>¿Deseas tener un punto de venta igual?</div>
							<div>Av. 18 de Marzo #287, La Nogalera</div>
							<div>CP 44470, Guadalajara ,Jalisco</div>
							<div>Tel: 4849384948758</div>
						</div>'; */
			$content .='</div>';




			require_once('../../modulos/phpmailer/sendMail.php');

			$mail->From = "mailer@netwarmonitor.com";
			$mail->FromName = "NetwarMonitor";
			$mail->Subject = "Ticket de Venta";
			$mail->AltBody = "NetwarMonitor";
			$mail->MsgHTML($content);
			/*$mail->AddAttachment('../../modulos/facturas/'. $uid .'.xml');
			$mail->AddAttachment('../../modulos/facturas/'. $uid .'.pdf'); */
			$mail->AddAddress($correo, $correo);


			@$mail->Send();

			return  array('estatus' => true );

	}

	public function formatofecha($fecha){
			list($anio,$mes,$rest)=explode("-",$fecha);
			list($dia,$hora)=explode(" ",$rest);

			return $dia."/".$mes."/".$anio." ".$hora;
	}
	/*public gridFacturas(){

	} */
	public function obtenCaracteristicas($idProducto){

			$tieneAlgo = 0;
			$que = "SELECT id,ruta_imagen, nombre from app_productos where codigo='".$idProducto."'";
			$res = $this->queryArray($que);
			$imagen = $res['rows'][0]['ruta_imagen'];
			$nombreP = $res['rows'][0]['nombre'];

			if($imagen==''){
				$imagen='noimage.jpeg';
			}
			///Caracteristicas
			$myQuery = "SELECT e.id as idcp, e.nombre as nombrecp
			FROM  app_producto_caracteristicas d
			LEFT JOIN app_caracteristicas_padre e on e.id=d.id_caracteristica_padre
			WHERE d.id_producto='".$res['rows'][0]['id']."' order by idcp;";
			$producto = $this->queryArray($myQuery);
			
			if($producto['total'] > 0){
				foreach ($producto['rows'] as $key => $value) {
					$selec = "SELECT id_caracteristica_padre,id,nombre from app_caracteristicas_hija where activo=1 and id_caracteristica_padre=".$value['idcp'];
					$result = $this->queryArray($selec);

					$carac[$value['nombrecp']] = $result['rows'];
				}
				$tieneAlgo++;
			}



			//lotes
			$arrPedis=array();
			 $myQuery = "SELECT a.id,a.no_lote from app_producto_lotes a
				inner join app_inventario_movimientos b on b.id_lote=a.id
				WHERE b.id_producto='".$res['rows'][0]['id']."' group by a.id;";

			$pedimentos = $this->queryArray($myQuery);
			if($pedimentos['total']>0){
				foreach ($pedimentos['rows'] as $k => $v) {
	 

					$myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
	@e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
	 = ".$res['rows'][0]['id']." ".$carac." AND id_pedimento = 0 AND id_lote = ".$v['id']."  ) AS entradas,
	@s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
	 = ".$res['rows'][0]['id']." ".$carac." AND id_pedimento = 0 AND id_lote = ".$v['id']."  ) AS salidas,
	(IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
	FROM app_almacenes a WHERE a.activo = 1
	ORDER BY a.codigo_sistema;";

					$totpedis = $this->queryArray($myQuery2);
					$cant=0;
					foreach ($totpedis['rows'] as $k2 => $v2) {
						//$cant+=$v2['cantidad'];

						if($v2['cantidad']>0){
							$arrPedis[]=array('idLote'=>$v['id'].'-'.$v2['id'].'-'.$v2['cantidad'].'-#*-'.$v['no_lote'].' ('.$v2['nombre'].')', 'cantidad'=>$v2['cantidad'], 'numero'=>'Lote: '.$v['no_lote'].' - '.$v2['nombre']);
						}
					}

					
				}
				$tieneAlgo++;
			}
			

			
			//return $arrPedis;

				//print_r($arrPedis);
				//exit();

			return array('tieneCar' => $tieneAlgo, 'cararc' => $carac, 'lotes'=> $arrPedis, 'series'=> $series, 'imagen'=> $imagen, 'nombreProd'=> $nombreP);
	} 
	public function getExisCara($idProducto,$caracteristicas){

			$selIdPr = "SELECT id from app_productos where codigo='".$idProducto."'";
			$resIdPr = $this->queryArray($selIdPr);

			$idProducto =  $resIdPr['rows'][0]['id'];

			$caracteristicas = preg_replace('/([0-9])+/', '\'\0\'', $caracteristicas);
			$caracteristicas = trim($caracteristicas, ',');
			if($caracteristicas != '0'){
					$carac = " AND id_producto_caracteristica =\"".$caracteristicas."\" ";
			}else{
				$carac='';
			}


				 $myQuery2="SELECT a.id, a.codigo_manual, a.codigo_sistema, a.nombre, 
@e := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_destino = a.id AND id_producto
 = ".$idProducto." ".$carac." ) AS entradas,
@s := (SELECT SUM(cantidad) FROM app_inventario_movimientos WHERE id_almacen_origen = a.id AND id_producto
 = ".$idProducto." ".$carac." ) AS salidas,
(IFNULL(@e,0) - IFNULL(@s,0)) AS cantidad
FROM app_almacenes a WHERE a.activo = 1
ORDER BY a.codigo_sistema;";
//echo $myQuery2;
	$idAlmacen = $this->obtenAlm();
	$cantidad = 0;
				$totpedis = $this->queryArray($myQuery2);
				//print_r($totpedis);
				$cant=0;
				foreach ($totpedis['rows'] as $k2 => $v2) {
					//echo $idAlmacen.'?';
					if (preg_match("/^['".$idAlmacen."']/", $v2['codigo_sistema'])) {
						//echo 'enttit';
						$cantidad += floatval($v2['cantidad']);
					} else {
						//echo "No se encontró ninguna coincidencia.<br>";
					}
				}
		
					//echo '['.$cantidad.']';
				return  array('cantidadExis' => $cantidad );

	}

	public function cambiaCantidad($idProducto, $descuento, $tipo) {

		 //print_r($_SESSION['caja']);
		 //exit();
		$cantidad = $_SESSION['caja'][$idProducto]->cantidad;

		$cantidad = str_replace(",", "", $cantidad);
		$_SESSION['caja'][$idProducto]->subtotal = $cantidad * $_SESSION['caja'][$idProducto]->precio;
		$_SESSION['caja'][$idProducto]->descuento = 0.0;
		$_SESSION['caja'][$idProducto]->tipodescuento = $tipo;
		$_SESSION['caja'][$idProducto]->descuento_cantidad = $descuento;

		if ($tipo != '' && $descuento != 0.0) {
			if ($tipo == "%") {
				$_SESSION['caja'][$idProducto]->descuento = ($_SESSION['caja'][$idProducto]->precio * $cantidad) * $descuento / 100;
				$_SESSION['caja'][$idProducto]->descuento_neto = $_SESSION['caja'][$idProducto]->descuento;
				$_SESSION['caja'][$idProducto]->nombre = $_SESSION['caja'][$idProducto]->nombre.' [Descuento:$'.number_format($_SESSION['caja'][$idProducto]->descuento_neto,2).']';
			} else if ($tipo == "$") {
				$_SESSION['caja'][$idProducto]->descuento = number_format($descuento, 2);
				$_SESSION['caja'][$idProducto]->descuento_neto = $_SESSION['caja'][$idProducto]->descuento;
				$_SESSION['caja'][$idProducto]->nombre = $_SESSION['caja'][$idProducto]->nombre.' [Descuento:$'.number_format($_SESSION['caja'][$idProducto]->descuento_neto,2).']';
			}
		} else {
			$_SESSION['caja'][$idProducto]->descuento_neto = 0.0;
		}

		
		$_SESSION['caja'][$idProducto]->importe = ($_SESSION['caja'][$idProducto]->precio * $cantidad) - str_replace(",", "", $_SESSION['caja'][$idProducto]->descuento);
		$_SESSION['caja'][$idProducto]->precio = ($_SESSION['caja'][$idProducto]->importe / $cantidad);

		$sessionArray = $this->object_to_array($_SESSION['caja']);
		$totalProductos = 0;
		foreach ($sessionArray as $key => $value) {
			if($key !='cargos' && $key!='descGeneral' && $key!='descGeneralCant'){
				$stringTaxes .=$value['idProducto'].'-'.$value['precio'].'-'.$value['cantidad'].'-'.$value['formula'].'-'.$value['caracteristicas'].'/';
				$totalProductos += $value['cantidad'];
			}
		}
		$this->calculaImpuestos($stringTaxes);

		return array('estatus' =>true,'productos' =>$_SESSION['caja'], 'cargos' => $_SESSION['caja']['cargos'],"count" => count($_SESSION['caja']), 'totalProductos' => $totalProductos );
	}

///////////////// ******** ---- 	listar_pedidos			------ ************ //////////////////
//////// Obtiene los pedidos de la comanda y los regresa en un Array
	// Como parametros puede recibir:
		// codigo-> codigo de la comanda
		// persona -> numero de la persona

	function listar_pedidos($objeto){
	// Filtra por persona si existe
		$condicion = (!empty($objeto['persona'])) ? ' AND a.npersona='.$objeto['persona'] : '' ;

		$sql="	SELECT
					a.idproducto AS idProducto, b.codigo, b.nombre, b.descripcion_larga AS descripcion,
					u.nombre AS unidad, b.id_unidad_venta AS idunidad, b.precio,
					SUM(a.cantidad) AS cantidad, b.ruta_imagen, (b.precio*SUM(a.cantidad)) AS importe,
					'' AS inpuestos, '' AS suma_impuestos, '' AS cargos,b.formulaIeps AS formula,
					a.npersona, a.opcionales, a.adicionales, a.normales, c.tipo, c.nombre AS nombreu,
					c.domicilio
				FROM
					com_pedidos a
				INNER JOIN
						app_productos b
					ON
						b.id=a.idproducto
				LEFT JOIN
						com_comandas d
					ON
						d.codigo='".$objeto['codigo']."'
				LEFT JOIN
						com_mesas c
					ON
						c.id_mesa=d.idmesa
				LEFT JOIN
						app_unidades_medida u
					ON
						b.id_unidad_venta=u.id
				WHERE
					idcomanda=(
						SELECT
							id
						FROM
							com_comandas
						WHERE
							codigo='".$objeto['codigo']."'
					)
				AND
					a.status!=3".
				$condicion."
				GROUP BY
					a.npersona, a.idProducto, a.opcionales, a.adicionales
				ORDER BY
					a.npersona ASC, a.id ASC";
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_pedidos		------ ************ //////////////////

///////////////// ******** ---- 	listar_productos		------ ************ //////////////////
//////// Obtiene el nombre de los productos y su costo
	// Como parametros puede recibir:
		// adicionales-> string con los id de los adicionales

	function listar_productos($objeto){
	// Si viene el id del estado Filtra por el id del estado
		$condicion.=(!empty($objeto['adicionales']))?' AND b.id IN('.$objeto['adicionales'].')':'';

		$sql="	SELECT
					b.codigo, b.id
				FROM
					app_productos b
				WHERE
					1=1 ".
				$condicion;
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_productos	------ ************ //////////////////

///////////////// ******** ---- 			id_venta		------ ************ //////////////////
//////// Obtiene el ultimo ID de las ventas
	// Como parametros puede recibir:

	function id_venta($objeto){
		$sql = "	SELECT
						MAX(idVenta) AS id_venta
					FROM
						app_pos_venta
					FOR UPDATE";
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 			FIN id_venta	------ ************ //////////////////

///////////////// ******** ---- 			pagar_comanda	------ ************ //////////////////
//////// Actualiza el status de la comanda a pagado y guarda el ID de la venta
	// Como parametros puede recibir:
		// id_venta -> ID de la venta
		// codigo -> Codigo de la comanda

	function pagar_comanda($objeto){
		$sql = "	UPDATE
						com_comandas
					SET
						status=1,
						id_venta = IF(id_venta!='', CONCAT(id_venta, ', ".$objeto['id_venta']."'), ".$objeto['id_venta'].")
					WHERE
						codigo='".$objeto['codigo']."'";
		// return $sql;
		$result = $this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN pagar_comanda	------ ************ //////////////////

///////////////// ******** ---- 		listar_comandas		------ ************ //////////////////
//////// Consulta las comandas pagadas y las regresa en un array
	// Como parametros puede recibir:
		// codigo -> codigo de la comanda
		// persona -> No de persona
		// status -> Estatus de la comanda

	function listar_comandas($objeto){
	// Filtra por la persona si existe
		$condicion .= (!empty($objeto['persona'])) ? ' AND tickets LIKE(\'%'.$objeto['persona'].'%\')' : '' ;
	// Filtra por el status si existe
		$condicion .= (!empty($objeto['status'])) ? ' AND status='.$objeto['status'] : ' AND status=1' ;

		$sql = "	SELECT
						id, id_venta
					FROM
						com_comandas
					WHERE
						codigo='".$objeto['codigo']."'".
					$condicion;
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_comandas		------ ************ //////////////////

///////////////// ******** ---- 	cambiar_descipcion		------ ************ //////////////////
//////// Actualiza el la descripcion del producto en la tabla app_campos_foodware
	// Como parametros puede recibir:
		// id -> ID del producto
		// descripcion -> texto que se cambiara

	function cambiar_descipcion($objeto){
		$sql = "	UPDATE
						app_campos_foodware
					SET
						descripcion='".$objeto['descripcion']."'
					WHERE
						id_producto=".$objeto['id'];
		// return $sql;
		$result = $this->query($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN cambiar_descipcion		------ ************ //////////////////

///////////////// ******** ---- 		cambiar_tickets			------ ************ //////////////////
//////// Actualiza el los tickets con el numero de persona
	// Como parametros puede recibir:
		// persona -> Numero de persona
		// codigo -> Codigo de la comanda

	function cambiar_tickets($objeto){
		$sql = "	UPDATE
						com_comandas
					SET
						tickets = IF(tickets!='', CONCAT(tickets, ', ".$objeto['persona']."'), '".$objeto['persona']."')
					WHERE
						codigo='".$objeto['codigo']."'";
		// return $sql;
		$result = $this->query($sql);

		return $result;
	}

///////////////// ******** ---- 		FIN cambiar_tickets		------ ************ //////////////////

///////////////// ******** ---- 	listar_pedidos_sub_comanda	------ ************ //////////////////
////////Optiene los pedidos de la ssub comanda y los regresa en un array
	// Como parametros puede recibir:
		// codigo -> Codigo de la sub comanda

	function listar_pedidos_sub_comanda($objeto){
	// Optiene los IDs de los pedidos
		$pedidos = "	SELECT
							pedidos
						FROM
							com_sub_comandas s
						WHERE
							s.codigo='".$objeto['codigo']."'";
		$pedidos = $this->queryArray($pedidos);
		$pedidos = $pedidos['rows'][0]['pedidos'];

	// Consulta los productos
		$sql = "	SELECT
						pe.cantidad, p.codigo, p.nombre, pe.adicionales
					FROM
						com_pedidos pe
					INNER JOIN
							app_productos p
						ON
							pe.idproducto=p.id
					WHERE
						pe.id IN(".$pedidos.")";
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- FIN listar_pedidos_sub_comanda	------ ************ //////////////////

///////////////// ******** ---- 		pagar_sub_comanda		------ ************ //////////////////
//////// Actualiza el status de la  sub comanda a pagado y guarda el ID de la venta
	// Como parametros puede recibir:
		// id_venta -> ID de la venta
		// codigo -> Codigo de la comanda

	function pagar_sub_comanda($objeto){
		$sql = "	SET @comanda=(	SELECT
										idpadre
									FROM
										com_sub_comandas
									WHERE
										codigo='".$objeto['codigo']."');

					UPDATE
						com_comandas
					SET
						status=1,
						id_venta = IF(id_venta!='', CONCAT(id_venta, ', ".$objeto['id_venta']."'), ".$objeto['id_venta'].")
					WHERE
						id=@comanda;

					UPDATE
						com_sub_comandas
					SET
						estatus=1
					WHERE
						codigo='".$objeto['codigo']."';";
		// return $sql;
		$result = $this->dataTransact($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN pagar_sub_comanda		------ ************ //////////////////

///////////////// ******** ---- 	listar_sub_comandas			------ ************ //////////////////
//////// Consulta las sub comandas pagadas y las regresa en un array
	// Como parametros puede recibir:
		// codigo -> codigo de la sub comanda

	function listar_sub_comandas($objeto){
		$sql = "	SELECT
						CONCAT(c.id, '-', s.id) AS id, c.id_venta
					FROM
						com_sub_comandas s
					INNER JOIN
							com_comandas c
						ON
							s.idpadre=c.id
					WHERE
						s.codigo='".$objeto['codigo']."'
					AND
						s.estatus=1";
		// return $sql;
		$result = $this->queryArray($sql);

		return $result;
	}

///////////////// ******** ---- 	FIN listar_sub_comandas		------ ************ //////////////////

	public function caract(){
		$myquery = "SELECT * from app_caracteristicas_padre;";
		$result = $this->queryArray($myquery);

		$myquery1 = "SELECT * from app_caracteristicas_hija;";
		$result1 = $this->queryArray($myquery1);

		return array('padre' => $result['rows'] , 'hija' => $result1['rows']);                               
	}
	public function listarCompra($desde,$hasta,$idcliente,$idproducto,$user,$horaInicio,$horaFinal){
		$filtro     = '1 = 1';
		$producto1  = implode('","', $idproducto);
		$cliente    = implode('","', $idcliente);
		$desde1     = $desde." ".$horaInicio;
		$hasta1     = $hasta." ".$horaFinal;

		if($desde!='' && $hasta!=''){
			$filtro .=' and cp.fecha BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
		}
		if($producto1!=""){
			if($producto1=='0'){
				$filtro .='';
			}else{
				$filtro .=' and (p.id IN ("'.$producto1.'"))';
			}
		}

		
		if($user == 0){
			if($cliente!=""){
				if($cliente=='0'){
					$filtro .='';
				}else{
					$filtro .=' and (cp.idCliente IN ("'.$cliente.'"))';
				}
			}
		}else{
			$filtro .=' and cp.idCliente = "'.$idcliente.'"';
		}

		$query = "SELECT p.id idprod, cpp.id, p.nombre, sum(cpp.cantidad) cantidad, sum(cpp.importe) costoCompra, sum(cpp.impuestos) impuestos, cpp.caracteristicas , u.nombre as unidad
					from cotpe_pedido_producto cpp
					left join cotpe_pedido cp on cp.id = cpp.idPedido
					left join app_productos p on p.id = cpp.idProducto
					left join app_unidades_medida u on u.id=p.id_unidad_compra 
					where ".$filtro."  
					group by cpp.idProducto, cpp.caracteristicas;";
					//echo $query;
		$result = $this->queryArray($query);

		
		$query2 = "SELECT p.id idprod, cpp.id, p.nombre, sum(cpp.cantidad) cantidad, sum(cpp.importe) costoCompra, sum(cpp.impuestos) impuestos, cpp.caracteristicas, cc.nombre nombreCliente , u.nombre as unidad
					from cotpe_pedido_producto cpp
					left join cotpe_pedido cp on cp.id = cpp.idPedido
					left join app_productos p on p.id = cpp.idProducto
					left join comun_cliente cc on cc.id = cp.idCliente
					left join app_unidades_medida u on u.id=p.id_unidad_compra 
					where ".$filtro."  
					group by cpp.idProducto, cpp.caracteristicas, cp.idCliente;";
		$result2 = $this->queryArray($query2);
		
		//return $result['rows'];

		return array('prod' => $result['rows'] , 'prod2' => $result2['rows']);
	}

	public function listarCompra2($desde,$hasta,$idcliente,$idproducto,$user,$horaInicio,$horaFinal){ // para formato de impresion RESUMIDO

		$filtro     = '1 = 1';
		$producto1  = implode('","', $idproducto);
		$cliente    = implode('","', $idcliente);
		$desde1     = $desde." ".$horaInicio;
		$hasta1     = $hasta." ".$horaFinal;

		if($desde!='' && $hasta!=''){
			$filtro .=' and cp.fecha BETWEEN "'.$desde1.'" and "'.$hasta1.'" ';
		}
		if($producto1!=""){
			if($producto1=='0'){
				$filtro .='';
			}else{
				$filtro .=' and (p.id IN ("'.$producto1.'"))';
			}
		}

		
		if($user == 0){
			if($cliente!=""){
				if($cliente=='0'){
					$filtro .='';
				}else{
					$filtro .=' and (cp.idCliente IN ("'.$cliente.'"))';
				}
			}
		}else{
			$filtro .=' and cp.idCliente = "'.$idcliente.'"';
		}

		
		$query = "SELECT p.id idprod, cpp.id, p.nombre, sum(cpp.cantidad) cantidad, sum(cpp.importe) costoCompra, sum(cpp.impuestos) impuestos, cpp.caracteristicas, cc.nombre nombreCliente, um.clave unidad, cp.idCliente
					from cotpe_pedido_producto cpp
					left join cotpe_pedido cp on cp.id = cpp.idPedido
					left join app_productos p on p.id = cpp.idProducto
					left join comun_cliente cc on cc.id = cp.idCliente
					left join app_unidades_medida um on um.id = p.id_unidad_compra
					where ".$filtro."  
					group by cpp.idProducto, cpp.caracteristicas, cp.idCliente
					order by cp.idCliente, cpp.idProducto, cpp.caracteristicas;"; 
		$result = $this->queryArray($query);

		return $result['rows'];

	}
	public function listaProveedores($id){
		$select = 'SELECT pr.idPrv,pr.razon_social,pp.id_unidad
				from mrp_proveedor pr, app_producto_proveedor pp
				where pr.idPRv = pp.id_proveedor and pp.id_producto='.$id;
 
		$res = $this->queryArray($select);
		if($res['total']>0){
			return $res['rows'];
		}else{
			return false;
		}

	} 
	public function selectListaCompra($idclienteLog){
   
		$filtro = '1 = 1';

		if($idclienteLog == 0){
			$filtro .= '';
		}else{
			$filtro .= ' and id = '.$idclienteLog.'';
		}        

		$myquery = "SELECT id, nombre from app_productos where status = 1;";
		$productos = $this->queryArray($myquery);

		$myquery1 = "SELECT id, nombre nombreCliente, nombretienda from comun_cliente where ".$filtro.";";
		$clientes = $this->queryArray($myquery1);

		return array('clientes' => $clientes['rows'] , 'productos' => $productos['rows']);  
	}

	public function buscarClasificadores( $clasificador, $patron, $parentClasific) {

		switch ($clasificador) {
			case '1':
				$tabla = 'app_departamento';
				$filtro = "";
				break;
			case '2':
				$tabla = 'app_familia';
				$filtro = "AND id_departamento='$parentClasific'";
				break;
			case '3':
				$tabla = 'app_linea';
				$filtro = "AND id_familia='$parentClasific'";
				break;
			default:
				# code...
				break;
		}
		if($parentClasific == "")
			$filtro = "";

		$sql = "SELECT	id, nombre as text
				FROM	$tabla
				WHERE	nombre LIKE '%$patron%' $filtro";

		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	public function touchProducts2($departamento, $familia, $linea, $limit){
		$sql = "SELECT	p.*, cf.rate as cantidad 
				FROM	app_productos p
				LEFT JOIN app_campos_foodware cf ON p.id=cf.id_producto
				WHERE	p.status=1 AND ( departamento like '%$departamento%' OR departamento IS NULL) AND ( familia like '%$familia%' OR familia IS NULL) AND ( linea like '%$linea%' OR linea IS NULL) 
				GROUP BY p.id
				ORDER BY cantidad DESC
				$limit";

		$res =$this->queryArray($sql);
		
		foreach ($res['rows'] as $key => $value) {
			$imp = $this->calImpu($value['id'],$value['precio'],$value['formulaIeps']);
			$res['rows'][$key]['precio']= $imp;
		}

		return  $res['rows'];
	}

} ///fin de la clase
?>
