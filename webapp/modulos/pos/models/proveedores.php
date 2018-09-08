<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL 
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ProveedoresModel extends Connection {
	public function indexGrid() {
		$myQuery = "SELECT * from mrp_proveedor;";
		$resultados = $this->queryArray($myQuery);
		return array('proveedores' =>$resultados['rows'] ,'total' => $resultados['total'] );
	}

	public function paises(){
		$query = 'SELECT * from paises where idpais IN (1,43, 47,54,85);';
		$result = $this->queryArray($query);
		return $result['rows'];
	}

	public function estados(){
		$query = 'Select * from estados';
		$result = $this->queryArray($query);
		return $result['rows'];
	}
	public function estados2($idPais){
		$query = 'Select * from estados where idpais = '.$idPais;
		$result = $this->queryArray($query);
		return $result['rows'];
	}

	public function munici(){
		$queryM = "SELECT * from municipios";
		$result = $this->queryArray($queryM);
		return $result['rows'];
	}

	public function municipios($idEstado){
		$queryM = "SELECT * from municipios where idestado=".$idEstado;
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

	public function creditos(){
		$queryCre = "SELECT * from app_tipo_credito where activo=1";
		$rescredi = $this->queryArray($queryCre);
		return $rescredi['rows'];
	}

	public function clasificadoresTipos(){
		//$queryClas = "SELECT * FROM app_clasificadores where padre = 4 and activo=1";
		$queryClas = "SELECT * FROM app_clasificadores where tipo = 2 and  padre > 0 and activo=1;";
		$resClas= $this->queryArray($queryClas);
		return $resClas['rows'];
	}

	public function obtenEmple(){
		$query = "SELECT  * from nomi_empleados";
		$result = $this->queryArray($query);
		return array("empleados" => $result['rows']);
	}

	public function bancos(){
		$query = "SELECT * from cont_bancos";
		$resBan = $this->queryArray($query);
		return $resBan['rows'];
	}

	public function cuentas(){
		$query = ' SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaClientes FROM cont_config) ORDER BY manual_code ASC;';
		$resCu = $this->queryArray($query);
		return $resCu['rows'];
	}
	
	public function datosProveedor($idProveedor){
		$query = 'SELECT * FROM mrp_proveedor WHERE idPrv='.$idProveedor;
		$result = $this->queryArray($query);

		$idTmp = $result['rows'][0]['idpais'];
		$sql = "SELECT  pais
				FROM    paises
				WHERE   idpais = $idTmp";
		$res = $this->queryArray($sql);
		$result['rows'][0]['descPais'] = $res['rows'][0]['pais'];

		$idTmp = $result['rows'][0]['idestado'];
		$sql = "SELECT  estado
				FROM    estados
				WHERE   idestado = $idTmp";
		$res = $this->queryArray($sql);
		$result['rows'][0]['descEstado'] = $res['rows'][0]['estado'];

		$idTmp = $result['rows'][0]['idmunicipio'];
		$sql = "SELECT  municipio
				FROM    municipios
				WHERE   idmunicipio = $idTmp";
		$res = $this->queryArray($sql);
		$result['rows'][0]['descMunicipio'] = $res['rows'][0]['municipio'];

		$query2 = 'SELECT bp.id, bp.idbanco, bp.numCT, ba.nombre FROM cont_bancosPrv bp
					LEFT JOIN cont_bancos ba on ba.idbanco = bp.idbanco 
					WHERE bp.idPrv='.$idProveedor;
		$result2 = $this->queryArray($query2);

		$query3 = 'SELECT * FROM pos_contactos 
					WHERE idPrv='.$idProveedor;
		$result3 = $this->queryArray($query3);

		$query8 = 'SELECT * from comun_facturacion where nombre="'.$idProveedor.'" and cliPro=2';
		$res8 = $this->queryArray($query8);

		return array("basicos" => $result['rows'], "bancos" => $result2['rows'], "contactos" => $result3['rows'], 'fact' => $res8['rows']);
	}

	// datos fiscales
	public function tipoProveedor() {
		$query = 'SELECT * FROM tipo_proveedor;';
		$result = $this->queryArray($query);
		return $result['rows'];
	}

	public function cuentap(){
		$query = "SELECT account_id, CONCAT(manual_code, ' ',description) nombre_cuenta ,account_type,account_nature FROM cont_accounts ca WHERE currency_id=1 AND  affectable =1;";
		return $this->query($query);
	}

	public function cuentaCliente(){
		$query = "SELECT co.account_id, CONCAT(manual_code, ' ',description) nombre_cuenta
			 FROM cont_accounts co
			 WHERE co.status=1 
			 AND co.removed=0 
			 AND co.affectable=1 ORDER BY manual_code ASC;";
		$result = $this->queryArray($query);
		return $result['rows'];
	}
	
	public function tipoTercero(){
		$query = "SELECT * from cont_tipo_tercero;";
		$result = $this->queryArray($query);
		return $result['rows'];
	}

	public function tipoIva(){
		$query = "SELECT * FROM cont_tipo_iva;";
		$result = $this->queryArray($query);
		return $result['rows'];
	}

	public function tasas($idProveedor,$idtasaAsumir){
		$query = "SELECT * FROM cont_tasaPrv WHERE idPrv = ".$idProveedor.";";
		$result = $this->queryArray($query);

		$query1 = "SELECT * FROM cont_tasaPrv WHERE id = ".$idtasaAsumir.";";
		$result1 = $this->queryArray($query1);

		return array("tasas" => $result['rows'], "tasasAsumir" => $result1['rows']);
	}
	
	public function tipoOpercaion(){
		$query = 'SELECT o.id, o.tipoOperacion FROM cont_tipo_operacion as o;';
		$result = $this->queryArray($query);
		return $result['rows'];
	}

	public function borraProve($id){

		$query = 'UPDATE mrp_proveedor SET status = 0 WHERE idPrv='.$id.';';
		$result = $this->queryArray($query);
		return $result['status'];

	}

		public function activaProve($id){

		$query = 'UPDATE mrp_proveedor SET status = -1 WHERE idPrv='.$id.';';
		$result = $this->queryArray($query);
		return $result['status'];


	}

	public function borraContactoProve($id){
		$query = 'DELETE FROM pos_contactos WHERE idCont='.$id.';';
		$result = $this->queryArray($query);
		return $result['status'];
	}

	public function tipoOpercaion2($tipoTercero){
		$query = 'SELECT o.id, o.tipoOperacion 
			FROM cont_tipo_operacion as o
			INNER JOIN cont_relacion_ter_oper as rel on o.id=rel.idtipoperacion
			INNER JOIN cont_tipo_tercero as ter on  rel.idtipotercero= ter.id
			AND ter.id= '.$tipoTercero.';';
		$result = $this->queryArray($query);
		return $result['rows'];
	}

	public function saveProvedor($idProveedor,$codigo,$tipoClas,$razon_social,$rfc,$nombre_comercial,$calle,$no_ext,$no_int,$colonia,$cp,$pais,$estado,$municipios,$nombre_contacto,$email,$telefono,$web,$stringCont,$diasCredito,$saldo,$limiteCredito,$tipo,$cuenta,$beneficiario,$cuentaCliente,$tipoTercero,$tipoTerceroOperacion,$numidfiscal,$nombrextranjero,$nacionalidad,$ivaretenido,$isretenido,$idtipoiva,$tasa,$tasas,$stringBanco,$aux,$ciudad,$tasaAsumir,$minimoPieza,$minimoImportePedido,$lugarEntrega,$prepolizas_provision,$prepolizas_pago,$cuentas_gastos,$rfcFac,$razonSocialF,$emailFacturacion) {
		//echo $lugarEntrega;
		if($aux == 1){//save
			$queryProvedores = "INSERT INTO mrp_proveedor (codigo,razon_social,rfc,telefono,email,web,diascredito,idpais,idestado,idmunicipio,idtipotercero,idtipoperacion,cuenta,numidfiscal,nombrextranjero,nacionalidad,ivaretenido,isretenido,idTasaPrvasumir,idtipoiva,idtipo,beneficiario_pagador,cuentacliente,nombre,nombre_comercial,clasificacion,limite_credito,status,calle,no_ext,no_int,cp,saldo,colonia,ciudad,minimo_piezas,minimo_importe_pedido,lugar_entrega,id_prepoliza,id_prepoliza_pagos,id_cuenta_gasto) values ('".$codigo."','".$razon_social."','".$rfc."','".$telefono."','".$email."','".$web."','".$diasCredito."','".$pais."','".$estado."','".$municipios."','".$tipoTercero."','".$tipoTerceroOperacion."','".$cuenta."','".$numidfiscal."','".$nombrextranjero."','".$nacionalidad."','".$ivaretenido."','".$isretenido."','0','".$idtipoiva."','".$tipo."','".$beneficiario."','".$cuentaCliente."','".$nombre_contacto."','".$nombre_comercial."','".$tipoClas."','".$limiteCredito."','-1','".$calle."','".$no_ext."','".$no_int."','".$cp."','".$saldo."','".$colonia."','".$ciudad."','".$minimoPieza."','".$minimoImportePedido."','".$lugarEntrega."',".$prepolizas_provision.",".$prepolizas_pago.",".$cuentas_gastos.")";
			$insertProveedores = $this->queryArray($queryProvedores);
			$idProveedoresInsert = $insertProveedores['insertId'];
			if($rfcFac!=''){
				$qi = 'INSERT into comun_facturacion(nombre,rfc,razon_social,correo,pais,estado,municipio,cliPro) values("'.$idProveedoresInsert.'","'.$rfcFac.'","'.$razonSocialF.'","'.$emailFacturacion.'","'.$pais.'","'.$estado.'","'.$municipios.'","2")';
				$this->queryArray($qi);
			}
		}

		if($aux == 2){//edit
			$queryE = "UPDATE mrp_proveedor SET idPrv = '".$idProveedor."', codigo = '".$codigo."', razon_social = '".$razon_social."', rfc = '".$rfc."', telefono = '".$telefono."', email = '".$email."', web = '".$web."', diascredito = '".$diasCredito."', idpais = '".$pais."', idestado = '".$estado."', idmunicipio = '".$municipios."', idtipotercero = '".$tipoTercero."', idtipoperacion = '".$tipoTerceroOperacion."', cuenta = '".$cuenta."', numidfiscal = '".$numidfiscal."', nombrextranjero = '".$nombrextranjero."', nacionalidad = '".$nacionalidad."', ivaretenido = '".$ivaretenido."', isretenido = '".$isretenido."', idtipoiva = '".$idtipoiva."', idtipo = '".$tipo."', beneficiario_pagador = '".$beneficiario."', cuentacliente = '".$cuentaCliente."', nombre = '".$nombre_contacto."', nombre_comercial = '".$nombre_comercial."', clasificacion = '".$tipoClas."', limite_credito = '".$limiteCredito."', calle = '".$calle."', no_ext = '".$no_ext."', no_int = '".$no_int."', cp = '".$cp."', saldo = '".$saldo."', colonia = '".$colonia."', ciudad = '".$ciudad."', minimo_piezas='".$minimoPieza."', minimo_importe_pedido='".$minimoImportePedido."', lugar_entrega='".$lugarEntrega."', id_prepoliza=".$prepolizas_provision.", id_prepoliza_pagos=".$prepolizas_pago.", id_cuenta_gasto=".$cuentas_gastos." WHERE idPrv = '".$idProveedor."';";
			$resultE = $this->queryArray($queryE);
			$idProveedoresInsert = $idProveedor;

			$sel = "SELECT * from comun_facturacion where nombre='".$idProveedor."'";
			$resSel1 = $this->queryArray($sel);
			 if($resSel1['total'] > 0){
				if($rfcFac!=''){
					$qi = 'UPDATE comun_facturacion set rfc="'.$rfcFac.'", razon_social="'.$razonSocialF.'", correo="'.$emailFacturacion.'", pais="'.$pais.'", estado="'.$estado.'", municipio="'.$municipios.'" where nombre="'.$idProveedoresInsert.'"';
					$this->queryArray($qi);
				}
			 }else{
				if($rfcFac!=''){
					$qi = 'INSERT into comun_facturacion(nombre,rfc,razon_social,correo,pais,estado,municipio,cliPro) values("'.$idProveedoresInsert.'","'.$rfcFac.'","'.$razonSocialF.'","'.$emailFacturacion.'","'.$pais.'","'.$estado.'","'.$municipios.'","2")';
					$this->queryArray($qi);
				}
			 }
		}

		//tasas
			$limpiatasa = $this->queryArray("Delete FROM cont_tasaPrv where idPrv = ".$idProveedoresInsert.";");
			$tasas = str_replace(' ', '', $tasas);
			$arrayTasas = explode(",", $tasas);
			$arra = array_reverse($arrayTasas);
			unset($arra[0]); 
			//print_r($arra);

			// PARA GUARDAR LA TASA A ASUMIR
			$queryA = "INSERT INTO cont_tasaPrv (idPrv, tasa, valor, visible) VALUES ('".$idProveedoresInsert."','".$tasa."','".$tasaAsumir."','1');";
			$resultA = $this->queryArray($queryA);
			// PARA GUARDAR LA TASA A ASUMIR FIN

			foreach ($arra as $key => $value) {
				$arrayTasas2 = explode("-", $value);
				$valor = $arrayTasas2[0];
				$tasa2 = $arrayTasas2[1];
				$queryT = "INSERT INTO cont_tasaPrv (idPrv, tasa, valor, visible) VALUES ('".$idProveedoresInsert."','".$tasa2."','".$valor."','1');";

				$result = $this->queryArray($queryT);
			}
		//tasas fin

		//update tasa a asumir       
			// consultar tabla para sacar el id con idprv y $tasa
			$query = 'SELECT id FROM cont_tasaPrv where idPrv = '.$idProveedoresInsert.' and tasa = "'.$tasa.'";';
			$result = $this->queryArray($query);
			$idtasaAsumirR = $result['rows'][0]['id'];

			//update tasa a asumir
			$queryU = "UPDATE mrp_proveedor SET idTasaPrvasumir = ".$idtasaAsumirR."  WHERE idPrv=".$idProveedoresInsert.";";
			$resultU = $this->queryArray($queryU);			
		//update tasa a asumir fin 
		

		//tabla contactos proveedor
			echo $stringCont;

			$arraystringCont = explode("#", $stringCont);
			$arraystringContR = array_reverse($arraystringCont);
			unset($arraystringContR[0]); 
			$arraystringCont = array_reverse($arraystringContR);
			foreach ($arraystringCont as $key => $value1) {
				$arrayCont = explode("-", $value1);
				$nombre = $arrayCont[1];
				$cargo = $arrayCont[2];
				$email = $arrayCont[3];
				$telefonoC = $arrayCont[4];
				$celularC = $arrayCont[5];

				$queryC = "INSERT INTO pos_contactos (nombre, cargo, email, telefono, celular, idPrv) VALUES ('".$nombre."','".$cargo."','".$email."','".$telefonoC."','".$celularC."','".$idProveedoresInsert."');";
				$resultC = $this->queryArray($queryC);
			}
		//tabla contactos proveedor fin

		//tabla bancos proveedor        
			$arraystringBanc = explode("#", $stringBanco);
			$arraystringBancR = array_reverse($arraystringBanc);
			unset($arraystringBancR[0]);
			$arraystringBanc = array_reverse($arraystringBancR); 
			foreach ($arraystringBanc as $key => $value2) {
				$arrayBanc = explode("-", $value2);
				$idbanco = $arrayBanc[1];
				$numCT = $arrayBanc[2];

				$queryB = "INSERT INTO cont_bancosPrv (idbanco, idPrv, numCT) VALUES ('".$idbanco."','".$idProveedoresInsert."','".$numCT."');";
				$resultB = $this->queryArray($queryB);
			}
		//tabla bancos proveedor fin
		return 0;
	}

	public function saldoProv($id) {
		$queryS = "SELECT (IFNULL(
					(SELECT SUM(IFNULL(r.imp_factura * IF(rq.tipo_cambio = 0, 1, rq.tipo_cambio), 0)) as saldo 
					FROM app_recepcion_xml r INNER JOIN
						app_ocompra c ON c.id = r.id_oc INNER JOIN
						app_requisiciones rq ON rq.id = c.id_requisicion
					WHERE c.id_proveedor = ".$id." AND xmlfile != ''), 0) - (
				IFNULL(
					(SELECT SUM(IFNULL(
						(SELECT SUM(rp.abono * p.tipo_cambio) 
						FROM app_pagos_relacion rp INNER JOIN
							app_pagos p ON p.id = rp.id_pago
						WHERE rp.id_documento = c.id AND rp.id_tipo = 1 AND p.cobrar_pagar = 1), 0)) AS saldo
					FROM app_ocompra c
					WHERE c.id_proveedor = ".$id."),0 ))) + 
			(SELECT (IFNULL(SUM(p.cargo * p.tipo_cambio), 0) - 
				IFNULL(SUM(IFNULL(
					(SELECT SUM(pr.abono * pa.tipo_cambio)
					FROM app_pagos_relacion pr INNER JOIN
						app_pagos pa ON pa.id = pr.id_pago
					WHERE pr.id_tipo = 0 AND pr.id_documento = p.id AND pa.cobrar_pagar = 1), 0)), 0)) AS saldo
				FROM app_pagos p
				WHERE p.id_prov_cli = ".$id." AND p.cobrar_pagar = 1 and p.cargo > 0) AS saldoGral ";
		$resultS = $this->queryArray($queryS);
		return $resultS['rows'];
	}

	function existeProveedorPortal($correoportal,$userportal,$passportal){
		$sql = "SELECT nombreusuario, clave from administracion_usuarios WHERE nombreusuario='$userportal';";
		$res = $this->queryArray($sql);
		return $res;
	}

	function fencripta($pwd, $salt) {
		$resultado = crypt($pwd, $salt);
		return $resultado;
	}

	function guardarUsuarioPortal($correoportal,$userportal,$passportal,$nombre){
		
		//2407 menu portal proveedores en configuracion

		session_start();
		$acceperfil= $_SESSION['accelog_idperfil'];// 5        
		$accelogV = $_SESSION['accelog_variable'];
		$accelogV = $_SESSION['accelog_variable'];
		$idorg= $_SESSION["accelog_idorganizacion"];


		$accelog_salt = "$2a$07$".$accelogV."aaaaaaa$";
		$calve = $this->fencripta($passportal, $accelog_salt);

		$sql = "SELECT idperfil from accelog_perfiles WHERE nombre='PORTALPROVEEDOR';";
		$res = $this->queryArray($sql);

		if($res['total']>0){
			$idperfil=$res['rows'][0]['idperfil'];
			$sqlx = "SELECT * from accelog_perfiles_me WHERE idperfil='$idperfil' AND idmenu='2407';";
			$resx = $this->queryArray($sqlx);
			if($resx['total']==0){
				$sql = "INSERT INTO accelog_perfiles_me (idperfil, idmenu) values ('$idperfil','2407')";
				$this->query($sql);
			}            
		}else{
			$sql = "INSERT INTO accelog_perfiles (nombre, visible) values ('PORTALCLIENTE','-1')";
			$idperfil = $this->insert_id($sql);
			 $sql = "INSERT INTO accelog_perfiles_me (idperfil, idmenu) values ('$idperfil','2407')";
			$this->query($sql);
		}


		$sql = "INSERT INTO empleados (nombre, apellido1, apellido2, idorganizacion, visible, administrador) values ('$nombre', '----', '----', '$idorg', '-1', 0)";
		$id_empleado = $this->insert_id($sql);

		$sql = "INSERT INTO accelog_usuarios (idempleado, usuario, clave, css) values ('$id_empleado', '$userportal', '$calve', 'default')";
		$this->query($sql);

		$sql = "INSERT INTO administracion_usuarios (nombre, apellidos, nombreusuario, clave, confirmaclave, correoelectronico, foto, idperfil, idempleado,  idSuc) values ('$nombre', '', '$userportal', '$passportal', '$passportal', '$correoportal', '', '$idperfil', '$id_empleado',  NULL)";
		$this->query($sql);

		$sql = "INSERT INTO accelog_usuarios_per (idperfil, idempleado) values ('$idperfil', '$id_empleado')";
		$this->query($sql);

	}

	function enviaCorreoPortal($correoportal,$userportal,$passportal,$nombre){
		session_start();
		$nombre_inst=$_SESSION["accelog_nombre_instancia"];
		require_once('../../modulos/phpmailer/sendMail.php');

		$h='<br>';
		$h.='<b>Url acceso:</b> <a href="http://'.$nombre_inst.'.netwarmonitor.mx">http://'.$nombre_inst.'.netwarmonitor.mx</a><br>';
		$h.='<b>Usuario:</b> '.$userportal.'<br>';
		$h.='<b>Contraseña:</b> '.$passportal.'<br>';

		$mail->From = "mailer@netwarmonitor.com";
		$mail->FromName = "NetwarMonitor";
		$mail->Subject = "Portal de Proveedores";
		$mail->AltBody = "Portal de Proveedores";
		$mail->MsgHTML($h);
		$mail->AddAddress($correoportal, $correoportal);

		if($mail->Send()){
			echo 1;
		}else{
			echo 0;
		}

	}

	function listaOrdenesCompra($idProveedor)
		{
			$myQuery = "SELECT b.id, SUBSTRING(a.fecha,1,10), bb.nombreEmpleado as nombre, cc.nombre as nomarea,'Egreso', SUM(s2.cantidad*s2.costo) as importe, a.urgente, a.activo, a.id as idreq from app_ocompra b
inner join app_requisiciones a on a.id=b.id_requisicion
INNER JOIN nomi_empleados bb on bb.idEmpleado=a.id_solicito
LEFT JOIN app_area_empleado cc on cc.id=bb.id_area_empleado

left JOIN (SELECT b2.costo, a2.cantidad, b2.id_producto, a2.id as fff, a2.id_requisicion, b2.id_proveedor
					   FROM app_requisiciones_datos a2
					   inner JOIN app_costos_proveedor b2 on b2.id_producto=a2.id_producto) as s2 on s2.id_requisicion=a.id and s2.id_proveedor=a.id_proveedor
			 WHERE  (a.activo=1 OR a.activo=4 OR a.activo=5 OR a.activo=6)
			 GROUP BY a.id
			ORDER BY a.id desc;";
/*

<th>No. OC.</th>
						<th>Fecha</th>
						<th>Proveedor</th>
						<th>Solicitante</th>
						<th>Fecha entrega</th>
						<th>Almacen</th>
						<th>Total</th>
						<th>Prioridad</th>
						<th>Estatus</th>
						<th class="no-sort" style="text-align: center;">Acciones</th>*/

			$myQuery = "SELECT d.idoc, SUBSTRING(a.fecha,1,10), pr.razon_social, b.nombreEmpleado as nombre, SUBSTRING(a.fecha_entrega,1,10) as fechaf, alm.nombre as almacen, if(d.total is null,TRUNCATE(a.total,2), TRUNCATE(d.total,2) ) as importe, a.urgente, a.activo, a.id as idreq
			FROM app_requisiciones a
			INNER JOIN nomi_empleados b on b.idEmpleado=a.id_solicito
			left join mrp_proveedor pr on pr.idPrv=a.id_proveedor
			left join app_almacenes alm on alm.id=a.id_almacen
			LEFT JOIN app_area_empleado c on c.id=b.id_area_empleado
			left JOIN (SELECT b2.costo, a2.cantidad, b2.id_producto, a2.id as fff, a2.id_requisicion
, b2.id_proveedor
					   FROM app_requisiciones_datos a2
					   inner JOIN app_costos_proveedor b2 on b2.id_producto=a2.id_producto) as s2 on
 s2.id_requisicion=a.id and s2.id_proveedor=a.id_proveedor
			LEFT join (Select r.total, r.id_requisicion, r.id as idoc from app_ocompra r) d on d.id_requisicion=a.id
			where a.activo!=3 and a.activo!=0 and a.pr!=2 -- AND a.id_proveedor='$idProveedor'
			GROUP BY a.id
			ORDER BY a.id desc;";



			$listaReq = $this->query($myQuery);
			return $listaReq;

		}

		function listaRecepcionesAdju($idoc)
		{
			if($idoc>0){
				$add=' WHERE a.id_oc='.$idoc.' ';
			}else{
				$add='';
			}
			$myQuery = "SELECT b.id, a.id as idr, SUBSTRING(a.fecha_recepcion,1,10) as fechar, a.no_factura, SUBSTRING(a.fecha_factura,1,10) as fechaf, a.imp_factura, a.estatus, a.activo, a.id_consignacion, a.total FROM app_recepcion a 
			inner join app_ocompra b on b.id=a.id_oc 
			".$add.";";



			$listaReq = $this->query($myQuery);
			return $listaReq;

		}

		function listaXmlsCompra($idoc){
			$myQuery = "SELECT * from app_recepcion_xml where id_oc='$idoc' order by id;";
			$listaReq = $this->query($myQuery);
			return $listaReq;
		}

		function rfcOrganizacion(){
			$sql=$this->query("select RFC from organizaciones ");
			return $sql->fetch_assoc();
		}

		function guardaXmlAdju($fac_folio,$fac_fecha,$fac_total,$fac_uuid,$fac_concepto,$xmlfile,$idoc,$subtotal)
		{

			date_default_timezone_set("Mexico/General");
			$fecha_subida=date('Y-m-d H:i:s'); 

			$myQuery = "INSERT INTO app_recepcion_xml (id_oc,fecha_factura,imp_factura,xmlfile,concepto,fecha_subida) VALUES ('$idoc','$fac_fecha','$fac_total','$xmlfile','$fac_concepto','$fecha_subida');";
			$last_id = $this->insert_id($myQuery);

			if($last_id>0){

				$myQuery = "SELECT a.id_recepcion from app_recepcion_datos a
						inner join app_recepcion b on b.id=a.id_recepcion
						where b.id_oc=".$idoc.";";
				$resultque = $this->queryArray($myQuery);

				if($resultque['total']>0){
					foreach ($resultque['rows'] as $k => $v) {
						$myQuery2 = "DELETE FROM app_pagos where concepto='Recepcion-".$v['id_recepcion']."' ";
						$this->query($myQuery2);
					}
				}

				///////////////////////ACONTIA///////////////////////////////
				///////////////////////////////////	/////////////////////////
				/*
				//Si tiene acontia y esta conectado
				$conexion_acontia = $this->query("SELECT conectar_acontia, pol_autorizacion FROM app_configuracion WHERE id = 1");
				$conexion_acontia = $conexion_acontia->fetch_assoc();
				
				if(intval($conexion_acontia['conectar_acontia']))
				{
					//Buscar el tipo de gasto
					$tipo_gasto = $this->query("SELECT rq.id_tipogasto, c.id_proveedor, rq.tipo_cambio FROM app_requisiciones rq 
												INNER JOIN app_ocompra c ON c.id_requisicion = rq.id WHERE c.id = $idoc");
					$tipo_gasto = $tipo_gasto->fetch_assoc();
					$id_proveedor = $tipo_gasto['id_proveedor'];
					$tipo_cambio = $tipo_gasto['tipo_cambio'];
					if(!intval($tipo_cambio))
						$tipo_cambio = 1;
					$tipo_gasto = $tipo_gasto['id_tipogasto'];

					//Si la compra esta relacionada a un tipo de gasto continua
					if(intval($tipo_gasto))
					{
						//Busca si es poliza automatica HACE UN LIMIT POR SI EXISTE MAS DE UNA TOMARA LA ULTIMA CONFIGURACION
						$automatica = $this->query("SELECT* FROM app_tpl_polizas WHERE id > 9 AND id_gasto = $tipo_gasto ORDER BY id DESC LIMIT 1");
						$automatica = $automatica->fetch_assoc();
						$idpol = $automatica['id'];

						//Si es automatica y se genera por documento CONTINUA
						if(intval($automatica['automatica']) && intval($automatica['poliza_por_mov']) == 1)
						{
							$fecha = explode('-',$fac_fecha);

							//Busca el id del ejercicio, si no existe, busca el ultimo y le suma al id para sacar el ejercicio
							$ejercicio = $this->query("SELECT Id FROM cont_ejercicios WHERE NombreEjercicio = ".$fecha[0]);
							$ejercicio = $ejercicio->fetch_assoc();
							$ejercicio = $ejercicio['Id'];

							//Si no existe calcula el Id
							if(!intval($ejercicio))
							{
								$ejercicioAntes = $this->query("SELECT * FROM cont_ejercicios ORDER BY Id DESC LIMIT 1");
								$ejercicioAntes = $ejercicioAntes->fetch_assoc();
								$nuevoEj = intval($fecha[0]) - intval($ejercicioAntes['NombreEjercicio']);
								$ejercicio = intval($ejercicioAntes['Id']) + $nuevoEj;
							}
							$numpol = $this->query("SELECT pp.numpol+1 FROM cont_polizas pp WHERE pp.idtipopoliza = ".$automatica['id_tipo_poliza']." AND pp.activo = 1 AND pp.idejercicio = $ejercicio AND pp.idperiodo = ".intval($fecha[1])." ORDER BY pp.numpol DESC LIMIT 1");
							$numpol = $numpol->fetch_assoc();
							$numpol = $numpol['numpol'];
							if(!intval($numpol))
								$numpol = 1;
							$activo = 1;
							if(intval($conexion_acontia['pol_autorizacion']))
								$activo = 0;

							//Genera la poliza
							$id_poliza_acontia = $this->insert_id("INSERT INTO cont_polizas(idorganizacion, idejercicio, idperiodo, numpol, idtipopoliza, referencia, concepto, fecha, fecha_creacion, activo, eliminado, pdv_aut, usuario_creacion, usuario_modificacion)
								 VALUES(1,$ejercicio,".intval($fecha[1]).",$numpol,".$automatica['id_tipo_poliza'].",'Poliza Fac. $fac_uuid','".$automatica['nombre_poliza']." $fac_concepto','$fac_fecha',DATE_SUB(NOW(), INTERVAL 6 HOUR), $activo, 0, 0, ".$_SESSION["accelog_idempleado"].", 0)");
							$cont = 0;//Contador de movimientos
							
							$cuentas_poliza = $this->query("SELECT id_cuenta, tipo_movto, id_dato, nombre_impuesto FROM app_tpl_polizas_mov WHERE activo = 1 AND id_tpl_poliza = $idpol");
							
							$ruta   = "../cont/xmls/facturas/";//Ruta donde se copiara
							//Genera Movimientos de la poliza
							while($cp = $cuentas_poliza->fetch_assoc())
							{
								$cont++;
								//Cargo o abono
								if(intval($cp['tipo_movto']) == 1)
									$tipo_movto = "Abono";
								if(intval($cp['tipo_movto']) == 2)
									$tipo_movto = "Cargo";

								//dependiendo el tipo de dato sera el valor que tomara.
								if(intval($cp['id_dato']) == 2)
								{
									//Si es el subtotal
									$importe = $subtotal;
								}
								elseif(intval($cp['id_dato']) == 3)
								{
									$importe = 0;
									if($cp['nombre_impuesto'])
									{
										$impu = str_replace('%', '', $cp['nombre_impuesto']);
										$impu = explode(' ', $impu);
										//Si es el impuesto
										$aa = simplexml_load_file($ruta.'temporales/'.$xmlfile);
										if($namespaces = $aa->getNamespaces(true))
										{
											$child = $aa->children($namespaces['cfdi']);
											for($j=0;$j<=(count($child->Impuestos->Traslados->Traslado)-1);$j++)
											{
												$bandera1 = $bandera2 = $cantidad = 0;
												foreach($child->Impuestos->Traslados->Traslado[$j]->attributes() AS $a => $b)
												{
													if($a == 'impuesto' && strtoupper($b) == $impu[0])
														$bandera1 = 1;
													
													if($impu[1] != 'EXENTO')
													{
														if($a == 'tasa' && floatval($b) == floatval($impu[1]))
															$bandera2 = 1;
													}
													else
													{
														if($a == 'tasa' && $b == $impu[1])
															$bandera2 = 1;
													}
													
													if($a == 'importe')
														$cantidad = $b;

													if($bandera1 && $bandera2 && $cantidad)
														$importe = $cantidad;
												}
											}
										}
										//unset($aa);
									}
								}
								else
								{
									//Si es total, cliente o proveedor agrega el total en el importe
									$importe = $fac_total;
								}

								

								$id_mov = $this->insert_id("INSERT INTO cont_movimientos(IdPoliza, NumMovto, IdSegmento, IdSucursal, Cuenta, TipoMovto, Importe, Referencia, Concepto, Activo, FechaCreacion, Factura, FormaPago, tipocambio) 
								VALUES($id_poliza_acontia, $cont, 1, 1, ".$cp['id_cuenta'].", '$tipo_movto', $importe, '','".$automatica['nombre_poliza']." $fac_concepto $impuesto', $activo, DATE_SUB(NOW(), INTERVAL 6 HOUR), '$xmlfile', 1, $tipo_cambio)");
								$ids_movs .= $id_mov.",";

								//Crear carpeta y copiar xml de la factura, ya se que esta no es el controlador pero no quedaba de otra, asi que hare una excepcion.
								if(!file_exists($ruta.$id_poliza_acontia))//Si no existe la carpeta de ese poliza la crea
								{
									mkdir ($ruta.$id_poliza_acontia, 0777);
								}
								copy($ruta.'temporales/'.$xmlfile, $ruta.$id_poliza_acontia."/".$xmlfile);    
								

							}
							$this->query("UPDATE app_recepcion_xml SET id_poliza_mov = '$ids_movs' WHERE id = $last_id");
							$ids_movs = '';
						}
					}
				}
*/

				//Termina conexion con acontia
				////////////////////////////////////////////////////////////
				////////////////////////////////////////////////////////////

			}

			return $last_id;
		}

	function obtener_prepolizas_pago(){
		$myQuery = "SELECT id, nombre_poliza AS nombre FROM cont_tpl_polizas WHERE provision = 0;";
		$Result  = $this->query($myQuery);
		return $Result;
	}

	function obtener_prepolizas_provision(){
		$myQuery = "SELECT id, nombre_poliza AS nombre FROM cont_tpl_polizas WHERE provision = 1;";
		$Result  = $this->query($myQuery);
		return $Result;
	}

	function obtener_cuenta_gasto_padre(){
		$myQuery = "SELECT account_code FROM cont_accounts AS a INNER JOIN cont_config AS c ON a.account_id = IF(c.CuentasGastosPolizas = -1, 0, c.CuentasGastosPolizas);";
		$Result  = $this->query($myQuery);
		return $Result;
	}

	function obtener_cuentas_gasto($cuenta_gasto){
		$myQuery = "SELECT account_id AS id, description AS nombre FROM cont_accounts WHERE main_account = 3 AND removed = 0 AND account_code LIKE '$cuenta_gasto%' ORDER BY manual_code ASC;";
		$Result  = $this->query($myQuery);
		return $Result;
	}
}
?>