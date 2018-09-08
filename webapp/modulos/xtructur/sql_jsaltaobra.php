<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

session_start();
$idperfil = preg_replace('/\(|\)/','',$_SESSION['accelog_idperfil']);
$oper = $_POST['oper'];

$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database

include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){
	$id = $_POST['id'];
	$mysqli->query("UPDATE constru_generales SET borrado=1 WHERE id in ($id);");
	exit();
}

if(isset($oper) && $oper=='add'){
	$licitacion = $_POST['licitacion'];
	$contrato = $_POST['contrato'];
	$cliente = $_POST['cliente'];
	$construye = $_POST['construye'];
	$obra = $_POST['obra'];
	$localizacion = $_POST['localizacion'];
	$clave = $_POST['clave'];
	$inicio = $_POST['inicio'];
	$termino = $_POST['termino'];
	//$hon = $_POST['hon'];
	$iva = $_POST['iva'];
	//$fsr = $_POST['fsr'];
	$presupuesto = $_POST['presupuesto'];
	$anticipo = $_POST['anticipo'];
	$ade1 = $_POST['ade1'];
	$ade2 = $_POST['ade2'];
	$ade3 = $_POST['ade3'];
	$ade4 = $_POST['ade4'];
	$residente = $_POST['residente'];
		$gerentedeo = $_POST['gerentedeo'];
		$dproyectos = $_POST['dproyectos'];
		$admono = $_POST['admono'];
	$director = $_POST['director'];
	$superintendencia = $_POST['superintendencia'];
	$control = $_POST['control'];
	$sup_imss = $_POST['supervision'];
	/*$elaboro = $_POST['elaboro'];
	$revision = $_POST['revision'];
	$autorizacion = $_POST['autorizacion'];
	*/
	$id_presupuesto = $_POST['id_presupuesto'];

	$fecha_contrato = $_POST['fecha_contrato'];
	$no_compromiso = $_POST['no_compromiso'];
	$fecha_compromiso = $_POST['fecha_compromiso'];
	$no_obra = $_POST['no_obra'];
	$telefono = $_POST['telefono'];
	$horai = $_POST['horai'];
	$horaf = $_POST['horaf'];
	$logo = $_POST['logoe'];

$correo_sp = $_POST['correo_sp'];

	$dias_credito = $_POST['dias_credito'];
	$limite_credito = $_POST['limite_credito'];

	$cuenta = $_POST['cuenta'];
	$pais = $_POST['pais'];
	$municipios = $_POST['municipio'];
	$estado = $_POST['estado'];
		$colonia_sp = $_POST['colonia_sp'];
	$cp_sp = $_POST['cp_sp'];
		$numext = $_POST['numext'];
		$rs = $_POST['rs'];
	$rfc = $_POST['rfc'];



	


	$i=$mysqli->query("INSERT INTO constru_generales (licitacion,contrato,cliente,construye,obra,localizacion,clave,inicio,termino,iva,presupuesto,anticipo,residente,director,superintendencia,control,sup_imss, id_presupuesto,fecha_contrato,no_compromiso,fecha_compromiso,no_obra,ade1,ade2,ade3,ade4,telefono,horai,horaf,gerentedeo,dproyectos,admono,logo,id_pais,id_estado,id_municipio,cuenta_acont,colonia,cp,email,dias_credito,limite_credito,rfc,razon_social,num_ext) VALUES ('$licitacion','$contrato','$cliente','$construye','$obra','$localizacion','$clave','$inicio','$termino','$iva','$presupuesto','$anticipo','$residente','$director','$superintendencia','$control','$sup_imss','$id_presupuesto','$fecha_contrato','$no_compromiso','$fecha_compromiso','$no_obra','$ade1','$ade2','$ade3','$ade4','$telefono','$horai','$horaf','$gerentedeo','$dproyectos','$admono','$logo','$pais','$estado','$municipios','$cuenta','$colonia_sp','$cp_sp','$correo_sp','$dias_credito','$limite_credito','$rfc','$rs','$numext');");
	if($i==1){
	$id_obra=$mysqli->insert_id;


	$codigo='CLI-'.$id_obra;
	$tienda='';

	$curp='';
	$ciudad='';
	$cumpleanos='';

	$numint='';
	$tipoClas=0;
	$perVenCre=0;
	$tipoDeCredito=0;
	$perExLim=0;
	$descuentoPP=0;
	$interesesMoratorios=0;
	$listaPrecio=0;
	$enviosDom='';
	$comisionVenta=0;
	$comisionCobranza=0;
	$banco='';
	$numCuenta='';
	$empleado='';
	$moneda=1;


	$mysqli->query("INSERT INTO comun_cliente (codigo,nombre,nombretienda,direccion,colonia,email,celular,cp,idPais,idEstado,idMunicipio,rfc,curp,telefono1,telefono2,ciudad,cumpleanos,limite_credito,dias_credito,num_ext,num_int,id_clasificacion,permitir_vtas_credito,id_tipo_credito,permitir_exceder_limite,dcto_pronto_pago,intereses_moratorios,id_lista_precios,envios,comision_vta,comision_cobranza,idBanco,numero_cuenta_banco,idVendedor,cuenta,id_moneda,id_xtructur) values ('".$codigo."','".$cliente."','".$tienda."','".$localizacion."','".$colonia_sp."','".$correo_sp."','".$telefono."','".$cp_sp."','".$pais."','".$estado."','".$municipios."','".$rfc."','".$curp."','".$telefono."','".$telefono."','".$ciudad."','".$cumpleanos."','".$limite_credito."','".$dias_credito."','".$numext."','".$numint."','".$tipoClas."','".$perVenCre."','".$tipoDeCredito."','".$perExLim."','".$descuentoPP."','".$interesesMoratorios."','".$listaPrecio."','".$enviosDom."','".$comisionVenta."','".$comisionCobranza."','".$banco."','".$numCuenta."','".$empleado."','".$cuenta."','".$moneda."','".$id_obra."')");
 
	$id_comun=$mysqli->insert_id;

	$mysqli->query("INSERT INTO comun_facturacion (rfc,razon_social,num_ext,cliPro) VALUES ('$rfc','$rs','$numext','$id_comun');");





		$mysqli->query("INSERT INTO constru_proforma (id_obra) VALUES ('$id_obra');");
		$mysqli->query("INSERT INTO constru_proforma2 (id_obra) VALUES ('$id_obra');");
		$mysqli->query("INSERT INTO constru_desgloce (id_obra,id_cc) SELECT $id_obra,id FROM constru_cuentas_cargo WHERE id_costo=25;");

			$mysqli->query("INSERT INTO constru_familias (id_obra,id_categoria_familia,familia) VALUES
				('$id_obra',2,'MAESTRO'),
				('$id_obra',2,'SOBRESTANTE'),
				('$id_obra',2,'CABO'),
				('$id_obra',2,'OFICIAL'),
				('$id_obra',2,'OPERADOR'),
				('$id_obra',2,'MANIOBRISTA'),
				('$id_obra',2,'AYUDANTE ESPECIALIZADO'),
				('$id_obra',2,'PEON');");


			$mysqli->query("INSERT INTO constru_familias (id_obra,id_categoria_familia,familia) VALUES
				('$id_obra',1,'DIRECTOR'),
				('$id_obra',1,'GERENTE'),
				('$id_obra',1,'SUPERINTENDENTE'),
				('$id_obra',1,'JEFE INGENIEROS'),
				('$id_obra',1,'COORDINADOR OBRA'),
				('$id_obra',1,'COORDINADOR CONTROL OBRA'),
				('$id_obra',1,'RESIDENTE'),
				('$id_obra',1,'AUXILIAR RESIDENTE'),
				('$id_obra',1,'AUXILIAR CONTROL'),
				('$id_obra',1,'TOPOGRAFO'),
				('$id_obra',1,'PROYECTISTA'),
				('$id_obra',1,'JEFE TALLER'),
				('$id_obra',1,'DIBUJANTE'),
				('$id_obra',1,'COORDINADOR COMPRAS'),
				('$id_obra',1,'JEFE COMPRAS'),
				('$id_obra',1,'ADMINISTRADOR OBRA'),
				('$id_obra',1,'CONTADOR'),
				('$id_obra',1,'AUXILIAR ADMINISTRATIVO'),
				('$id_obra',1,'JEFE ALMACEN'),
				('$id_obra',1,'SECRETARIA'),
				('$id_obra',1,'JEFE SEGURIDAD E HIGIENE'),
				('$id_obra',1,'CHOFER'),
				('$id_obra',1,'AUXILIAR DE SEGURIDAD E HIGIENE'),
				('$id_obra',1,'AUXILIAR ALMACEN'),
				('$id_obra',1,'AYUDANTE ALMACEN'),
				('$id_obra',1,'CHECADOR TIEMPO'),
				('$id_obra',1,'AFANADORA'),
				('$id_obra',1,'VELADOR Y/O VIGILANTE'),
				('$id_obra',1,'MECANICO'),
				('$id_obra',1,'OTROS');");

			$mysqli->query("INSERT INTO constru_config (id_obra,autorizaciones) VALUES ('$id_obra',0);");

			$idusr = $_SESSION['accelog_idempleado'];



			$mysqli->query("INSERT INTO constru_obrasusuario (iduser, idobra) VALUES ($idperfil, $id_obra);");

	}
	exit();
}

if(isset($oper) && $oper=='edit'){
	$horai = $_POST['horai'];
	$horaf = $_POST['horaf'];
	$id = $_POST['id'];
	$licitacion = $_POST['licitacion'];
	$contrato = $_POST['contrato'];
	$cliente = $_POST['cliente'];
	$construye = $_POST['construye'];
	$obra = $_POST['obra'];
	$localizacion = $_POST['localizacion'];
	$clave = $_POST['clave'];
	$inicio = $_POST['inicio'];
	$termino = $_POST['termino'];
	//$hon = $_POST['hon'];
	$iva = $_POST['iva'];
	//$fsr = $_POST['fsr'];
	$presupuesto = $_POST['presupuesto'];
	$anticipo = $_POST['anticipo'];
	$ade1 = $_POST['ade1'];
	$ade2 = $_POST['ade2'];
	$ade3 = $_POST['ade3'];
	$ade4 = $_POST['ade4'];
	$residente = $_POST['residente'];
		$gerentedeo = $_POST['gerentedeo'];
		$dproyectos = $_POST['dproyectos'];
		$admono = $_POST['admono'];
	$director = $_POST['director'];
	$superintendencia = $_POST['superintendencia'];
	$control = $_POST['control'];
	$sup_imss = $_POST['supervision'];
	/*$elaboro = $_POST['elaboro'];
	$revision = $_POST['revision'];
	$autorizacion = $_POST['autorizacion'];
	*/
	$id_presupuesto = $_POST['id_presupuesto'];

	$fecha_contrato = $_POST['fecha_contrato'];
	$no_compromiso = $_POST['no_compromiso'];
	$fecha_compromiso = $_POST['fecha_compromiso'];
	$no_obra = $_POST['no_obra'];
	$telefono = $_POST['telefono'];
	$logo = $_POST['logoe'];

	$correo_sp = $_POST['correo_sp'];

	$dias_credito = $_POST['dias_credito'];
	$limite_credito = $_POST['limite_credito'];

	$cuenta = $_POST['cuenta'];
	$pais = $_POST['pais'];
	$municipios = $_POST['municipio'];
	$estado = $_POST['estado'];
		$colonia_sp = $_POST['colonia_sp'];
	$cp_sp = $_POST['cp_sp'];
	$numext = $_POST['numext'];
		$rs = $_POST['rs'];
	$rfc = $_POST['rfc'];


	$mysqli->query("UPDATE constru_generales SET licitacion='$licitacion', contrato='$contrato', cliente='$cliente', construye='$construye', obra='$obra', localizacion='$localizacion', clave='$clave', inicio='$inicio', termino='$termino', iva='$iva', presupuesto='$presupuesto', anticipo='$anticipo', residente='$residente',director='$director', superintendencia='$superintendencia', control='$control', sup_imss='$sup_imss', id_presupuesto='$id_presupuesto', fecha_contrato='$fecha_contrato', no_compromiso='$no_compromiso', fecha_compromiso='$fecha_compromiso', no_obra='$no_obra', ade1='$ade1', ade2='$ade2', ade3='$ade3', ade4='$ade4', telefono='$telefono',horai='$horai',horaf='$horaf',gerentedeo='$gerentedeo',dproyectos='$dproyectos',admono='$admono',logo='$logo',email='$correo_sp', 
		dias_credito='$dias_credito',
		id_pais='$pais',
		id_estado='$estado',
		id_municipio='$municipios',
		cuenta_acont='$cuenta',
	limite_credito='$limite_credito',
	cp='$cp_sp',
		colonia='$colonia_sp',
		rfc='$rfc',
		razon_social='$rs',
		num_ext='$numext'

		 WHERE id='$id';");


	$mysqli->query("UPDATE comun_cliente SET nombre='$cliente',
		direccion='$localizacion',
		colonia='$colonia_sp',
		email='$correo_sp',
		celular='$telefono',
		cp='$cp_sp',
		idPais='$pais',
		idEstado='$estado',
		idMunicipio='$municipios',
		telefono1='$telefono',
		telefono2='$telefono',
		limite_credito='$limite_credito',
		dias_credito='$dias_credito',
		cuenta='$cuenta'
		WHERE id_xtructur='$id';");


	$SQL="SELECT id from comun_cliente where id_xtructur='$id';";
	$result = $mysqli->query($SQL);
$rows = $result->fetch_array();
$idcomun=$rows['id'];



	$mysqli->query("DELETE from comun_facturacion where cliPro='$idcomun';");
	

	$mysqli->query("INSERT INTO comun_facturacion (rfc,razon_social,num_ext,cliPro) VALUES ('$rfc','$rs','$numext','$idcomun');");

exit();

}

$SQL = "SELECT COUNT(*) AS count FROM constru_generales WHERE borrado=0;";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$count = $row['count'];

if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)

if($_search=='true'){
	$soper=$_GET['searchOper'];
	if($soper=='eq'){
		$cad=" AND ".$searchField."='".$search."' ";
	}elseif($soper=='ne'){
		$cad=" AND ".$searchField."!='".$search."' ";
	}elseif($soper=='cn'){
		$cad=" AND ".$searchField." LIKE  '%".$search."%' ";
	}elseif($soper=='nc'){
		$cad=" AND ".$searchField." NOT LIKE  '%".$search."%' ";
	}elseif($soper=='lt'){
		$cad=" AND ".$searchField." <  ".$search." ";
	}elseif($soper=='gt'){
		$cad=" AND ".$searchField." >  ".$search." ";
	}else{
		echo 'Operador de busqueda incorrecto';
		exit();
	}
}else{
	$cad='';
}



	$nfiltro='  ';	


if($idperfil==2){
$SQL="SELECT a.*, b.nombre nomconst ,xp.pais, xe.estado, xm.municipio, concat(description,' (',manual_code,')') as nombre_cuenta FROM constru_generales a LEFT JOIN constru_contratista b on b.id=a.construye  
left join paises xp on xp.idpais=a.id_pais
left join estados xe on xe.idestado=a.id_estado
left join municipios xm on xm.idmunicipio=a.id_municipio
left join cont_accounts cc on cc.account_id=a.cuenta_acont


WHERE 1=1 ".$cad." AND a.borrado=0 ".$nfiltro."  ORDER BY $sidx $sord LIMIT $start , $limit";
}else{
$SQL="SELECT a.*, b.nombre nomconst,xp.pais, xe.estado, xm.municipio, concat(description,' (',manual_code,')') as nombre_cuenta FROM constru_generales a LEFT JOIN constru_contratista b on b.id=a.construye
	left join constru_obrasusuario c on  a.id=c.idobra  

	left join paises xp on xp.idpais=a.id_pais
left join estados xe on xe.idestado=a.id_estado
left join municipios xm on xm.idmunicipio=a.id_municipio
left join cont_accounts cc on cc.account_id=a.cuenta_acont
  WHERE 1=1 ".$cad." AND a.borrado=0 ".$nfiltro." AND c.iduser='$idperfil' group by a.id ORDER BY $sidx $sord LIMIT $start , $limit";
}
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['obra'],$row['cliente'],$row['licitacion'],$row['nomconst'],$row['clave'],$row['contrato'],$row['horai'],$row['horai'],$row['localizacion'],$row['gerentedeo'],$row['dproyectos'],$row['admono'],$row['inicio'],$row['termino'],$row['iva'],$row['presupuesto'],$row['anticipo'],$row['ade1'],$row['ade2'],$row['ade3'],$row['ade4'],$row['dias_credito'],$row['limite_credito'],$row['colonia'],$row['cp'],$row['pais'],$row['estado'],$row['municipio'],$row['email'],$row['nombre_cuenta'],$row['residente'],$row['director'],$row['superintendencia'],$row['control'],$row['sup_imss'],$row['fecha_contrato'],$row['no_compromiso'],$row['fecha_compromiso'],$row['no_obra'],$row['telefono'],$row['rfc'],$row['razon_social'],$row['num_ext'],$row['logo']);
    $i++;
}        
echo json_encode($responce);
?>