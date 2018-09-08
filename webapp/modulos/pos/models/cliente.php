<?php
//Carga la clase de coneccion con sus metodos para consultas o transacciones
//require("models/connection.php"); // funciones mySQL
require("models/connection_sqli_manual.php"); // funciones mySQLi

class ClienteModel extends Connection
{
	public function indexGrid()
	{
		$myQuery = "SELECT * from comun_cliente";
		$resultados = $this->queryArray($myQuery);
		return array('clientes' =>$resultados['rows'] ,'total' => $resultados['total'] );
	}
	public function razonSocialCliente($idcliente)
	{
		$query = 'select razon_social from comun_facturacion where nombre = ' . $idcliente;
		$result = $this->queryArray($query);
		return $result['rows'][0];
	}
	public function paises(){
		$query = 'SELECT * from paises;';
		$result = $this->queryArray($query);
		return $result['rows'];
	}
	public function estados2($idPais){
		$query = 'Select * from estados where idpais = '.$idPais;
		$result = $this->queryArray($query);
		return $result['rows'];
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
	public function cuentas(){
		$query = ' SELECT account_id, manual_code, description FROM cont_accounts where main_account = 3 AND removed=0 AND  currency_id = 1 AND main_father = (SELECT CuentaClientes FROM cont_config) ORDER BY manual_code ASC;';
		$resCu = $this->queryArray($query);

		return $resCu['rows'];
	}
	public function datosCliente($idCliente){
		$query = 'SELECT * from comun_cliente where id='.$idCliente;
		$result = $this->queryArray($query);

		$idTmp = $result['rows'][0]['idPais'];
		$sql = "SELECT  pais
				FROM    paises
				WHERE   idpais = $idTmp";
		$res = $this->queryArray($sql);
		$result['rows'][0]['descPais'] = $res['rows'][0]['pais'];

		$idTmp = $result['rows'][0]['idEstado'];
		$sql = "SELECT  estado
				FROM    estados
				WHERE   idestado = $idTmp";
		$res = $this->queryArray($sql);
		$result['rows'][0]['descEstado'] = $res['rows'][0]['estado'];

		$idTmp = $result['rows'][0]['idMunicipio'];
		$sql = "SELECT  municipio
				FROM    municipios
				WHERE   idmunicipio = $idTmp";
		$res = $this->queryArray($sql);
		$result['rows'][0]['descMunicipio'] = $res['rows'][0]['municipio'];

		return array("basicos" => $result['rows']);
	}
	public function obtenEmple(){
		$query = "SELECT  * from nomi_empleados";
		$result = $this->queryArray($query);

		return array("empleados" => $result['rows']);
	}
	public function datosClienteFact($idCliente){
		$query = 'SELECT f.*, m.idmunicipio as idMunicipio 
					FROM comun_facturacion f
					LEFT JOIN municipios m ON f.municipio=m.municipio
					WHERE f.nombre='.$idCliente;
		$result = $this->queryArray($query);


		return array("fact" => $result['rows']);
	}
	public function creditos(){
		$queryCre = "SELECT * from app_tipo_credito where activo=1";
		$rescredi = $this->queryArray($queryCre);

		return $rescredi['rows'];
	}
	public function moneda(){
		$query = "SELECT * from cont_coin";
		$result = $this->queryArray($query);

		return $result['rows'];
	}
	public function clasificadoresTipos($id_clasif){

		if($id_clasif == 0){
			$filtro = ' tipo = 1 ';
		}else{
			$filtro = 'tipo = '.$id_clasif.' ';
		}
		// comentar si se requiere filtrado
		//$filtro = ' 1 = 1 ';

		$queryClas = "SELECT * from app_clasificadores where ".$filtro." and activo=1";
		$resClas= $this->queryArray($queryClas);

		return $resClas['rows'];
	}
	public function bancos(){
		$query = "SELECT * from cont_bancos";
		$resBan = $this->queryArray($query);

		return $resBan['rows'];
	}

	public function guardaCliente($idCliente,$codigo,$nombre,$tienda,$numint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$cumpleanos,$rfc,$curp,$diasCredito,$limiteCredito,$moneda,$listaPrecio,$razonSocial,$emailFacturacion,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$tipoDeCredito,$descuentoPP,$interesesMoratorios,$perVenCre,$perExLim,$comisionVenta,$comisionCobranza,$empleado,$enviosDom,$tipoClas,$idComunFact,$regimenFact,$banco,$numCuenta,$cuentaCont,$pais,$paisFact,$bandera,$prepolizas_provision,$prepolizas_pago,$cuentas_gastos){

		$queryCliente = "INSERT INTO comun_cliente (codigo,nombre,nombretienda,direccion,colonia,email,celular,cp,idPais,idEstado,idMunicipio,rfc,curp,telefono1,telefono2,ciudad,cumpleanos,limite_credito,dias_credito,num_ext,num_int,id_clasificacion,permitir_vtas_credito,id_tipo_credito,permitir_exceder_limite,dcto_pronto_pago,intereses_moratorios,id_lista_precios,envios,comision_vta,comision_cobranza,idBanco,numero_cuenta_banco,idVendedor,cuenta,id_moneda,id_prepoliza,id_prepoliza_pagos,id_cuenta_gasto) values ";
		$queryCliente .="('".$codigo."','".$nombre."','".$tienda."','".$direccion."','".$colonia."','".$email."','".$celular."','".$cp."','".$pais."','".$estado."','".$municipio."','".$rfc."','".$curp."','".$tel1."','".$tel2."','".$ciudad."','".$cumpleanos."','".$limiteCredito."','".$diasCredito."','".$numext."','".$numint."','".$tipoClas."','".$perVenCre."','".$tipoDeCredito."','".$perExLim."','".$descuentoPP."','".$interesesMoratorios."','".$listaPrecio."','".$enviosDom."','".$comisionVenta."','".$comisionCobranza."','".$banco."','".$numCuenta."','".$empleado."','".$cuentaCont."','".$moneda."',".$prepolizas_provision.",".$prepolizas_pago.",".$cuentas_gastos.")";
		$insertClienteRes = $this->queryArray($queryCliente);
		$idClienteInsert = $insertClienteRes['insertId'];

		if($rfc!='' && $razonSocial!='' ){
			if($bandera==2){
				$paisFact = 1;
				$comunFact = $this->guardaComunFact($idClienteInsert,$rfc,$razonSocial,$direccion,$numext,$numint,$colonia,$cp,$estado,$municipio,$ciudadFact,$email,$regimenFact,$paisFact);
			}else{
				$comunFact = $this->guardaComunFact($idClienteInsert,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$regimenFact,$paisFact);
			}
		}else{
			$comunFact = 0;
		}


		return array('status' => true , 'idClienteInser' => $idClienteInsert, 'comun_fac' => $comunFact, 'nombre' => $nombre);

	}
	public function guardaComunFact($idClienteInsert,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$regimenFact,$paisFact){

		$extInt = '';
		if($numintFact!=''){
			$extInt = $numextFact.' Int. '.$numintFact;
		}else{
			$extInt = $numextFact;
		}
		$selcMuni = "SELECT * from municipios where idmunicipio=".$municipiosFact;
		$resmunici = $this->queryArray($selcMuni);
		$municipioNombre = $resmunici['rows'][0]['municipio'];

		$selp = "SELECT pais from paises where idpais=".$paisFact;
		$resPais = $this->queryArray($selp);
		$paisNom = $resPais['rows'][0]['pais'];



		$insertCo = "INSERT into comun_facturacion(nombre,rfc,razon_social,correo,pais,regimen_fiscal,domicilio,num_ext,cp,colonia,idPais,estado,ciudad,municipio) values('".$idClienteInsert."','".$rfc."','".$razonSocial."','".$emailFacturacion."','".$paisNom."','".$regimenFact."','".$direccionFact."','".$extInt."','".$cpFact."','".$coloniaFact."','".$paisFact."','".$estadoFact."','".$ciudadFact."','".$municipioNombre."')";
		//echo $insertCo;
		$resInsert = $this->queryArray($insertCo);

		return $resInsert['insertId'];

	}

	public function updateClientePortal($idCliente,$nombre,$tienda,$numint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$pais,$prepolizas,$cuentas_gastos){

		$update = "UPDATE comun_cliente set  nombre='".$nombre."', nombretienda='".$tienda."', direccion='".$direccion."', colonia='".$colonia."', email='".$email."', celular='".$celular."', cp='".$cp."', idPais='".$pais."', idEstado='".$estado."', idMunicipio='".$municipio."',  telefono1='".$tel1."', telefono2='".$tel2."', ciudad='".$ciudad."',  num_ext='".$numext."', num_int='".$numint."', where id=".$idCliente;
		$resUpdate = $this->queryArray($update);

		return array('status' => true , 'idClienteInser' => $idCliente, 'comun_fac' => 0);

	}

	public function updateCliente($idCliente,$codigo,$nombre,$tienda,$mumint,$numext,$direccion,$colonia,$cp,$estado,$municipio,$email,$celular,$tel1,$tel2,$ciudad,$cumpleanos,$rfc,$curp,$diasCredito,$limiteCredito,$moneda,$listaPrecio,$razonSocial,$emailFacturacion,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$tipoDeCredito,$descuentoPP,$interesesMoratorios,$perVenCre,$perExLim,$comisionVenta,$comisionCobranza,$empleado,$enviosDom,$tipoClas,$idComunFact,$regimenFact,$banco,$numCuenta,$cuentaCont,$pais,$paisFact,$prepolizas_provision,$prepolizas_pago,$cuentas_gastos){

		$update = "UPDATE comun_cliente set codigo='".$codigo."', nombre='".$nombre."', nombretienda='".$tienda."', direccion='".$direccion."', colonia='".$colonia."', email='".$email."', celular='".$celular."', cp='".$cp."', idPais='".$pais."', idEstado='".$estado."', idMunicipio='".$municipio."', rfc='".$rfc."', curp='".$curp."', telefono1='".$tel1."', telefono2='".$tel2."', ciudad='".$ciudad."', cumpleanos='".$cumpleanos."', limite_credito='".$limiteCredito."', dias_credito='".$diasCredito."', num_ext='".$numext."', num_int='".$numint."', id_moneda='".$moneda."', id_clasificacion='".$tipoClas."', permitir_vtas_credito='".$perVenCre."', id_tipo_credito='".$tipoDeCredito."', permitir_exceder_limite='".$perExLim."', dcto_pronto_pago='".$descuentoPP."', intereses_moratorios='".$interesesMoratorios."', id_lista_precios='".$listaPrecio."', envios='".$enviosDom."', comision_vta='".$comisionVenta."', comision_cobranza='".$comisionCobranza."', idBanco='".$banco."', numero_cuenta_banco='".$numCuenta."', idVendedor='".$empleado."', cuenta='".$cuentaCont."', id_prepoliza=".$prepolizas_provision.", id_prepoliza_pagos=".$prepolizas_pago.", id_cuenta_gasto=".$cuentas_gastos." where id=".$idCliente;
		$resUpdate = $this->queryArray($update);

		if($idComunFact!=''){
			$comunFact = $this->updateComunFact($idCliente,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$idComunFact,$regimenFact,$paisFact);

		}elseif($rfc!='' && $razonSocial!='' && $idComunFact==''){
			$comunFact = $this->guardaComunFact($idCliente,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$regimenFact,$paisFact);
		}

		return array('status' => true , 'idClienteInser' => $idCliente, 'comun_fac' => $comunFact);

	}
	public function updateComunFact($idCliente,$rfc,$razonSocial,$direccionFact,$numextFact,$numintFact,$coloniaFact,$cpFact,$estadoFact,$municipiosFact,$ciudadFact,$emailFacturacion,$idComunFact,$regimenFact,$paisFact){

		$extInt = '';
		//echo 'rr'.$numextFact;
		//echo 'int'.$numintFact;
		if($numintFact!=''){
			$extInt = $numextFact.' Int. '.$numintFact;
		}else{
			$extInt = $numextFact;
		}
	/*    echo 'XX '. $extInt;
		exit(); */
		$selcMuni = "SELECT * from municipios where idmunicipio=".$municipiosFact;
		$resmunici = $this->queryArray($selcMuni);
		$municipioNombre = $resmunici['rows'][0]['municipio'];
		$selPai= 'SELECT pais from paises where idpais='.$paisFact;
		$resPA = $this->queryArray($selPai);
		$paisName = $resPA['rows'][0]['pais'];

		$update = "UPDATE comun_facturacion set rfc='".$rfc."', razon_social='".$razonSocial."', correo='".$emailFacturacion."', pais='".$paisName."', regimen_fiscal='', domicilio='".$direccionFact."', num_ext='".$extInt."', cp='".$cpFact."', colonia='".$coloniaFact."', idPais='".$paisFact."', estado='".$estadoFact."', ciudad='".$ciudadFact."', regimen_fiscal='".$regimenFact."', municipio='".$municipioNombre."' where nombre=".$idCliente." and id=".$idComunFact;
		//echo $update;
		//exit();
		$resUpdate = $this->queryArray($update);

		return $idComunFact;

	}
	public function borraCliente($idCliente){
		$sel = 'UPDATE comun_cliente set borrado=1 where id='.$idCliente;
		$res = $this->queryArray($sel);

		return  array('estatus' => true );

	}
	public function activaCliente($idCliente){
		$sel = 'UPDATE comun_cliente set borrado=0 where id='.$idCliente;
		$res = $this->queryArray($sel);

		return  array('estatus' => true );

	}

	public function buscarLocalizacion( $idLoc, $patron, $parentLoc) {

		switch ($idLoc) {
			case '1':
				$id = 'idpais';
				$nombre = 'pais';
				$tabla = 'paises';
				$filtro = "";
				break;
			case '2':
				$id = 'idestado';
				$nombre = 'estado';
				$tabla = 'estados';
				$filtro = "AND idpais='$parentLoc'";
				break;
			case '3':
				$id = 'idmunicipio';
				$nombre = 'municipio';
				$tabla = 'municipios';
				$filtro = "AND idestado='$parentLoc'";
				break;
			default:
				# code...
				break;
		}
		if($parentLoc == "")
			$filtro = "AND 1 = 2";

		$sql = "SELECT  $id AS id, $nombre as text
				FROM    $tabla
				WHERE   $nombre LIKE '%$patron%' $filtro";

		$res = $this->queryArray($sql);
		foreach ($res as $key => $value) {
			$value = htmlspecialchars($value['text']);
		}

		return json_encode( $res );
	}

	function nuevoPais( $nombre ){
		$sql = "INSERT INTO paises (pais)
				VALUES  ('$nombre')";
		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	function nuevoEstado( $nombre , $idPais){
		$sql = "INSERT INTO estados (estado, idpais)
				VALUES  ('$nombre', '$idPais')";
		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	function nuevoMunicipio( $nombre , $idEstado){
		$sql = "INSERT INTO municipios (municipio, idestado)
				VALUES  ('$nombre', '$idEstado')";
		$res = $this->queryArray($sql);

		return json_encode( $res );
	}

	function existeClientePortal($correoportal,$userportal,$passportal){
		$sql = "SELECT nombreusuario, clave from administracion_usuarios WHERE nombreusuario='$userportal';";
		$res = $this->queryArray($sql);
		return $res;
	}

	function fencripta($pwd, $salt) {
		$resultado = crypt($pwd, $salt);
		return $resultado;
	}

	function modificaUsuarioPortal($passportal,$cliente){

		session_start();
		$idempleado= $_SESSION['accelog_idempleado'];// 5        
		$accelogV = $_SESSION['accelog_variable'];
		$accelog_salt = "$2a$07$".$accelogV."aaaaaaa$";
		$calve = $this->fencripta($passportal, $accelog_salt);

		$sql = "UPDATE accelog_usuarios SET clave='$calve' WHERE idempleado='$idempleado';";
		$this->query($sql);

		$sql = "UPDATE administracion_usuarios SET clave='$passportal', confirmaclave='$passportal' WHERE idempleado='$idempleado';";
		$this->query($sql);

	}

	function guardarUsuarioPortal($correoportal,$userportal,$passportal,$nombre){
		//2406 menu portal cliente en configuracion

		session_start();
		$acceperfil= $_SESSION['accelog_idperfil'];// 5        
		$accelogV = $_SESSION['accelog_variable'];
		$accelogV = $_SESSION['accelog_variable'];
		$idorg= $_SESSION["accelog_idorganizacion"];


		$accelog_salt = "$2a$07$".$accelogV."aaaaaaa$";
		$calve = $this->fencripta($passportal, $accelog_salt);

		$sql = "SELECT idperfil from accelog_perfiles WHERE nombre='PORTALCLIENTE';";
		$res = $this->queryArray($sql);

		if($res['total']>0){
			$idperfil=$res['rows'][0]['idperfil'];
			$sqlx = "SELECT * from accelog_perfiles_me WHERE idperfil='$idperfil' AND idmenu='2406';";
			$resx = $this->queryArray($sqlx);
			if($resx['total']==0){
				$sql = "INSERT INTO accelog_perfiles_me (idperfil, idmenu) values ('$idperfil','2406')";
				$this->query($sql);
			}            
		}else{
			$sql = "INSERT INTO accelog_perfiles (nombre, visible) values ('PORTALCLIENTE','-1')";
			$idperfil = $this->insert_id($sql);
			 $sql = "INSERT INTO accelog_perfiles_me (idperfil, idmenu) values ('$idperfil','2406')";
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
		$mail->Subject = "Portal de Clientes";
		$mail->AltBody = "Portal de Clientes";
		$mail->MsgHTML($h);
		$mail->AddAddress($correoportal, $correoportal);

		if($mail->Send()){
			echo 1;
		}else{
			echo 0;
		}

	}

	public function listaCargos($idPrvCli,$cobrar_pagar)
	{
		if(!intval($cobrar_pagar))
			$cliProv = "(SELECT dias_credito FROM comun_cliente WHERE id = p.id_prov_cli) AS diascredito, ";
		else
			$cliProv = "(SELECT diascredito FROM mrp_proveedor WHERE idPrv = p.id_prov_cli) AS diascredito, ";

		$myQuery = "SELECT p.id, p.tipo_cambio, (SELECT codigo FROM cont_coin WHERE coin_id = p.id_moneda) AS moneda, p.tipo_cambio, p.fecha_pago, @c := p.cargo*p.tipo_cambio, p.cargo, 
		$cliProv 
		p.concepto, @p := IFNULL((SELECT SUM(pr.abono) FROM app_pagos_relacion pr WHERE pr.id_tipo = 0 AND pr.id_documento = p.id  AND (SELECT id_moneda FROM app_pagos WHERE id = pr.id_pago) = 1),0) AS pagos,
@p2 := IFNULL((SELECT SUM(pr.abono*(SELECT tipo_cambio FROM app_pagos WHERE id = pr.id_pago)) FROM app_pagos_relacion pr WHERE pr.id_tipo = 0 AND pr.id_documento = p.id AND (SELECT id_moneda FROM app_pagos WHERE id = pr.id_pago) != 1),0) AS pagos2,
					(@c-(@p+@p2)) AS saldo
					FROM app_pagos p
					WHERE p.id_prov_cli = $idPrvCli
					AND p.cobrar_pagar = $cobrar_pagar
					AND p.cargo > 0
					";
		return $this->query($myQuery);

	}

	public function listaFacturas($idPrvCli,$cobrar_pagar)
	{
		//$conexion_bancos = $this->conectado_bancos();

		if(intval($cobrar_pagar))
		{
			$myQuery = "SELECT rq.tipo_cambio AS rq_tipo_cambio, r.id_oc, (SELECT codigo FROM cont_coin WHERE coin_id = rq.id_moneda) AS Moneda, r.concepto AS desc_concepto, r.id_oc AS id, r.fecha_factura,0 AS no_factura,SUM(r.imp_factura) AS imp_factura,SUM(r.imp_factura*IF(rq.tipo_cambio = 0,1,rq.tipo_cambio)) AS importe_pesos, r.xmlfile, (SELECT diascredito FROM mrp_proveedor WHERE idPrv = c.id_proveedor) AS diascredito,
					@c := (SELECT SUM(rp.cargo) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = r.id_oc AND rp.id_tipo=1 AND p.cobrar_pagar = 1),
					@a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = r.id_oc AND rp.id_tipo=1 AND p.cobrar_pagar = 1),
					(IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
					FROM app_recepcion_xml r INNER JOIN app_ocompra c ON c.id = r.id_oc
					INNER JOIN app_requisiciones rq ON rq.id = id_requisicion
					WHERE c.id_proveedor = $idPrvCli AND xmlfile != ''
					GROUP BY id_oc
					ORDER BY id_oc;";
		}
		else
		{
			if(intval($idPrvCli))
			{
				$myQuery = "SELECT rf.id AS id, rf.origen,v.tipo_cambio AS rq_tipo_cambio, v.idVenta AS id_oventa, 
							(SELECT codigo FROM cont_coin WHERE coin_id = IFNULL(1,v.moneda)) AS Moneda, CONCAT('Venta POS: ',v.idVenta) AS desc_concepto, rf.folio, rf.id AS idres, rf.fecha AS fecha_factura,@total := vp.monto AS imp_factura, (@total*IF(v.tipo_cambio = 0,1,v.tipo_cambio)) AS importe_pesos, rf.xmlfile, (SELECT dias_credito FROM comun_cliente WHERE id = v.idCliente) AS diascredito,
							@c := (SELECT SUM(rp.cargo*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
							@a := (SELECT SUM(rp.abono*p.tipo_cambio) FROM app_pagos_relacion rp INNER JOIN app_pagos p ON p.id = rp.id_pago WHERE rp.id_documento = rf.id AND rp.id_tipo=1 AND p.cobrar_pagar = 0),
							(IFNULL(@a,0) - IFNULL(@c,0)) AS pagos
							FROM app_pos_venta_pagos vp
							INNER JOIN app_pos_venta v ON vp.idVenta = v.idVenta
							INNER JOIN app_respuestaFacturacion rf ON rf.idSale = v.idVenta
							LEFT JOIN comun_cliente c ON c.id = v.idCliente
							WHERE v.idCliente = $idPrvCli AND estatus = 1 AND v.documento = 2 
							AND vp.idFormapago = 6 AND rf.origen = 2 AND rf.borrado != 1";
			}
		   

		}

		$listaFacturas = $this->query($myQuery);
		return $listaFacturas;
	}



	function listaCotis($idCliente)
	{
		$myQuery = "SELECT a.id, SUBSTRING(a.fecha,1,10) as fff, cc.nombre, b.nombreEmpleado, TRUNCATE(a.total,2) as importe, a.urgente, a.activo, a.aceptada, a.cadenaCoti, sum(xxx.nuevo) as cnuevos, cp.id as idcotpe, cp.status
		FROM app_requisiciones_venta a
		left join app_requisiciones_venta_comentarios xxx on xxx.id_coti=a.id
		INNER JOIN nomi_empleados b on b.idEmpleado=a.id_solicito
		left join comun_cliente cc on cc.id=a.id_cliente
		LEFT JOIN app_area_empleado c on c.id=b.id_area_empleado
		left JOIN (SELECT a2.precio, a2.cantidad, a2.id as fff, a2.id_requisicion
				   FROM app_requisiciones_datos_venta a2
				   inner JOIN app_productos b2 on b2.id=a2.id_producto) as s2 on s2.id_requisicion=a.id

		left join cotpe_pedido cp on cp.idCotizacion=a.id and cp.origen=1
		WHERE a.pr=1 and a.id_cliente='$idCliente'
		GROUP BY a.id
		ORDER BY a.id desc;";

		$listaReq = $this->queryArray($myQuery);
		return $listaReq;

	}

	function listaOrdenesCompra()
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
			left JOIN (SELECT b2.costo, a2.cantidad, b2.id_producto, a2.id as fff, a2.id_requisicion, b2.id_proveedor
			FROM app_requisiciones_datos a2
			inner JOIN app_costos_proveedor b2 on b2.id_producto=a2.id_producto) as s2 on s2.id_requisicion=a.id and s2.id_proveedor=a.id_proveedor
			LEFT join (Select r.total, r.id_requisicion, r.id as idoc from app_ocompra r) d on d.id_requisicion=a.id
			where a.activo!=3 and a.activo!=0 and a.pr!=2
			GROUP BY a.id
			ORDER BY a.id desc;";

			$listaReq = $this->query($myQuery);
			return $listaReq;
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
