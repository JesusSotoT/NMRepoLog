<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

$id_tipo_alta=5; //Proveedores

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
$mysqli->query("set names 'utf8'");

if(isset($oper) && $oper=='del'){
	$id = $_POST['id'];
	$mysqli->query("UPDATE constru_altas SET borrado=1 WHERE id in ($id);");
	$mysqli->query("UPDATE mrp_proveedor SET status=0 WHERE id_xtructur in ($id);");
	exit();
}
if(isset($oper) && $oper=='add'){

	$estatus = $_POST['estatus'];
	$f_captura = $_POST['f_captura'];
	$f_ingreso = $_POST['f_ingreso'];
	$f_alta_i = $_POST['f_alta_i'];
	$f_baja_i = $_POST['f_baja_i'];
	$id_responsable = 0;

	$id_agrupador = $_POST['id_agrupador'];
	$id_especialidad = $_POST['id_especialidad'];
	$id_area = $_POST['id_area'];
	$id_partida = $_POST['id_partida'];

	$id_depto = 0;

	$alta=$_POST['alta'];

if($alta==4){$tipo_alta = "Subcontratista";}
if($alta==5){$tipo_alta = "Proveedor";}
	


	$oc_inst = 0;
	$id_familia = 0;
	$id_categoria = 0;

	$razon_social_sp = $_POST['razon_social_sp'];
	$rfc_sp = $_POST['rfc_sp'];
	$calle_sp = $_POST['calle_sp'];
	$colonia_sp = $_POST['colonia_sp'];
	$cp_sp = $_POST['cp_sp'];
	$municipio_sp = $_POST['municipio_sp'];
	$estado_sp = $_POST['estado_sp'];
	$tel_emp_sp = $_POST['tel_emp_sp'];
	$paterno_sp = $_POST['paterno_sp'];
	$materno_sp = $_POST['materno_sp'];
	$nombres_sp = $_POST['nombres_sp'];
	$tel_personal_sp = $_POST['tel_personal_sp'];
	$correo_sp = $_POST['correo_sp'];

	$dias_credito = $_POST['dias_credito'];
	$limite_credito = $_POST['limite_credito'];

	$cuenta = $_POST['cuenta'];
	$pais = $_POST['pais'];
	$municipios = $_POST['municipio'];
	$estado = $_POST['estado'];

	$banco = $_POST['banco'];
	$cuentabanco = $_POST['cuentabanco'];
	$tasa=$_POST['tasa'];
		$stringBanco=$_SESSION['stringbanc'];


	
		//$pais=1; //n
		//$estado=1; //n
		$municipios=1; //n
		$tipoTercero=$_POST['ttercero'];
		$tipoTerceroOperacion=$_POST['toperacion'];
		//$cuenta=1; //n
		$numidfiscal='';
		$nombrextranjero='';
		$nacionalidad='';
		$ivaretenido=$_POST['ivar'];
		$isretenido=$_POST['isrr'];
		$idtipoiva=$_POST['tiva'];
		$tipo=$_POST['tprov'];
		$beneficiario=$_POST['ben'];
		$cuentaCliente=$_POST['ccliente'];
		$nombre_contacto=$nombres_sp.' '.$paterno_sp.' '.$materno_sp;
		$nombre_contacto=trim($nombre_contacto);
		$nombre_comercial=$razon_social_sp;
		$tipoClas=0;
		$no_ext='';
		$no_int='';
		$saldo=0;
		$ciudad='';
		
				$imp=$_POST['imp'];
            	$ad1=$_POST['ad1'];
            	$ad2=$_POST['ad2'];
            	$ad3=$_POST['ad3'];
            	$ant=$_POST['ant'];
            	$gar=$_POST['gar'];
            	$ret=$_POST['ret'];

	$fis = $_POST['fis'];
	if($fis==1 && ($tipoTercero==0 || $tipoTerceroOperacion==0)) {
		echo 'ob';
		exit();
	}


	$nacionalidad=$_POST['na'];
			$numidfiscal=$_POST['idf'];

	$mysqli->query("INSERT INTO constru_altas (id_obra, id_tipo_alta, estatus, f_captura, f_ingreso, f_alta_i, f_baja_i, id_responsable, id_agrupador, id_especialidad, id_area, id_partida, id_depto, tipo_alta, oc_inst, id_familia, id_categoria) VALUES ('$id_obra','$alta','$estatus','$f_captura','$f_ingreso','$f_alta_i','$f_baja_i','$id_responsable','$id_agrupador','$id_especialidad','$id_area','$id_partida','$id_depto','$tipo_alta','$oc_inst','$id_familia','$id_categoria');");

	$id_alta = $mysqli->insert_id;
		$codigo='PROV-'.$id_alta;

	if($id_alta>0){



		$mysqli->query("INSERT INTO constru_info_sp (id_alta, razon_social_sp, rfc_sp, calle_sp, colonia_sp, cp_sp, municipio_sp, estado_sp, tel_emp_sp, paterno_sp, materno_sp, nombres_sp, tel_personal_sp, correo_sp, dias_credito, limite_credito,id_pais,id_estado,id_municipio,cuenta_acont,banco,cuenta,imp_cont,ade1,ade2,ade3,anticipo,por_fondo_garantia,por_retencion) VALUES ('$id_alta','$razon_social_sp','$rfc_sp','$calle_sp','$colonia_sp','$cp_sp','$municipio_sp','$estado_sp','$tel_emp_sp','$paterno_sp','$materno_sp','$nombres_sp','$tel_personal_sp','$correo_sp','$dias_credito','$limite_credito','$pais','$estado','$municipios','$cuenta','$banco','$cuentabanco','$imp','$ad1','$ad2','$ad3','$ant','$gar','$ret');");




		$mysqli->query("INSERT INTO mrp_proveedor (codigo,razon_social,rfc,telefono,email,web,diascredito,idpais,idestado,idmunicipio,idtipotercero,idtipoperacion,cuenta,numidfiscal,nombrextranjero,nacionalidad,ivaretenido,isretenido,idtipoiva,idtipo,beneficiario_pagador,cuentacliente,nombre,nombre_comercial,clasificacion,limite_credito,status,calle,no_ext,no_int,cp,saldo,colonia,ciudad,id_xtructur,idTasaPrvasumir) values ('".$codigo."','".$razon_social_sp."','".$rfc_sp."','".$tel_emp_sp."','".$correo_sp."','','".$dias_credito."','".$pais."','".$estado."','".$municipios."','".$tipoTercero."','".$tipoTerceroOperacion."','".$cuenta."','".$numidfiscal."','".$nombrextranjero."','".$nacionalidad."','".$ivaretenido."','".$isretenido."','".$idtipoiva."','".$tipo."','".$beneficiario."','".$cuentaCliente."','".$nombre_contacto."','".$nombre_comercial."','".$tipoClas."','".$limite_credito."','-1','".$calle_sp."','".$no_ext."','".$no_int."','".$cp_sp."','".$saldo."','".$colonia_sp."','".$ciudad."','".$id_alta."','".$tasa."');");

		$id_p = $mysqli->insert_id;


		$SQL="SELECT * from cont_tasas where id ='$tasa';";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
     $tasan=$row['tasa'];
      $tasav=$row['valor'];

    }


    	$arraystringBanc = explode("#", $stringBanco);
			$arraystringBancR = array_reverse($arraystringBanc);
			unset($arraystringBancR[0]);
			$arraystringBanc = array_reverse($arraystringBancR); 
			foreach ($arraystringBanc as $key => $value2) {
				$arrayBanc = explode("-", $value2);
				$idbanco = $arrayBanc[1];
				$numCT = $arrayBanc[2];

				$queryB = "INSERT INTO cont_bancosPrv (idbanco, idPrv, numCT) VALUES ('".$idbanco."','".$id_p."','".$numCT."');";
				$mysqli->query($queryB);
			}

    $mysqli->query("INSERT INTO cont_tasaPrv(idPrv,tasa,valor,visible) values('".$id_p."','".$tasan."','".$tasav."',1);");


	$id_tasa = $mysqli->insert_id;

    $mysqli->query("UPDATE mrp_proveedor SET idTasaPrvasumir='$id_tasa' where idPrv='$id_p';" );




	}

	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$estatus = $_POST['estatus'];
	$f_captura = $_POST['f_captura'];
	$f_ingreso = $_POST['f_ingreso'];
	$f_alta_i = $_POST['f_alta_i'];
	$f_baja_i = $_POST['f_baja_i'];
	$id_responsable = 0;

	$id_agrupador = $_POST['id_agrupador'];
	$id_especialidad = $_POST['id_especialidad'];
	$id_area = $_POST['id_area'];
	$id_partida = $_POST['id_partida'];

	$id_depto = 0;
	$tipo_alta = $_POST['tipo_alta'];
	$oc_inst = 0;
	$id_familia = 0;
	$id_categoria = 0;
	$stringBanco=$_SESSION['stringbanc'];
	$razon_social_sp = $_POST['razon_social_sp'];
	$rfc_sp = $_POST['rfc_sp'];
	$calle_sp = $_POST['calle_sp'];
	$colonia_sp = $_POST['colonia_sp'];
	$cp_sp = $_POST['cp_sp'];
	$municipio_sp = $_POST['municipio_sp'];
	$estado_sp = $_POST['estado_sp'];
	$tel_emp_sp = $_POST['tel_emp_sp'];
	$paterno_sp = $_POST['paterno_sp'];
	$materno_sp = $_POST['materno_sp'];
	$nombres_sp = $_POST['nombres_sp'];
	$tel_personal_sp = $_POST['tel_personal_sp'];
	$correo_sp = $_POST['correo_sp'];

	$dias_credito = $_POST['dias_credito'];
	$limite_credito = $_POST['limite_credito'];

	$nombre_contacto=$nombres_sp.' '.$paterno_sp.' '.$materno_sp;
	$nombre_contacto=trim($nombre_contacto);
	$nombre_comercial=$razon_social_sp;

	$cuenta = $_POST['cuenta'];
	$pais = $_POST['pais'];
	$municipios = $_POST['municipio'];
	$estado = $_POST['estado'];

	
        $ivaretenido=$_POST['ivar'];
		$isretenido=$_POST['isrr'];
		$idtipoiva=$_POST['tiva'];
		$tipo=$_POST['tprov'];
		$beneficiario=$_POST['ben'];
		$cuentaCliente=$_POST['ccliente'];
			$tasa=$_POST['tasa'];
				$tipoTercero=$_POST['ttercero'];
		$tipoTerceroOperacion=$_POST['toperacion'];
			$alta=$_POST['alta'];
				$imp=$_POST['imp'];
            	$ad1=$_POST['ad1'];
            	$ad2=$_POST['ad2'];
            	$ad3=$_POST['ad3'];
            	$ant=$_POST['ant'];
            	$gar=$_POST['gar'];
            	$ret=$_POST['ret'];
            	if($alta==4){$tipo_alta = "Subcontratista";}
if($alta==5){$tipo_alta = "Proveedor";}

		$fis = $_POST['fis'];
	if($fis==1 && ($tipoTercero==0 || $tipoTerceroOperacion==0)) {
		echo 'ob';
		exit();
	}

	$mysqli->query("UPDATE constru_altas SET estatus='$estatus', f_captura='$f_captura', f_ingreso='$f_ingreso', f_alta_i='$f_alta_i', f_baja_i='$f_baja_i', id_responsable='$id_responsable', id_agrupador='$id_agrupador', id_especialidad='$id_especialidad', id_area='$id_area', id_partida='$id_partida', tipo_alta='$tipo_alta', oc_inst=0,id_tipo_alta='$alta' WHERE id='$id';");
	$mysqli->query("UPDATE constru_info_sp SET razon_social_sp='$razon_social_sp', rfc_sp='$rfc_sp', calle_sp='$calle_sp', colonia_sp='$colonia_sp', cp_sp='$cp_sp', municipio_sp='$municipio_sp', estado_sp='$estado_sp', tel_emp_sp='$tel_emp_sp', paterno_sp='$paterno_sp', materno_sp='$materno_sp', nombres_sp='$nombres_sp', tel_personal_sp='$tel_personal_sp', correo_sp='$correo_sp', dias_credito='$dias_credito', limite_credito='$limite_credito',id_pais='$pais',id_estado='$estado',id_municipio='$municipios',cuenta_acont='$cuenta', banco='$banco', cuenta='$cuentabanco', imp_cont='$imp',ade1='$ad1',ade2='$ad2',ade3='$ad3',anticipo='$ant',por_fondo_garantia='$gar',por_retencion='$ret' WHERE id_alta='$id';");




		$SQL="SELECT * from cont_tasas where id ='$tasa';";
  $result = $mysqli->query($SQL);
  if($result->num_rows>0) {
    $row = $result->fetch_array();
     $tasan=$row['tasa'];
      $tasav=$row['valor'];

    }



    $SQL="SELECT idPrv FROM mrp_proveedor WHERE id_xtructur='$id';";
	$result = $mysqli->query($SQL);
	$row_cnt = $result->num_rows;
	if($row_cnt>0){
		$row = $result->fetch_array();
		$idpp=$row['idPrv'];}

    $mysqli->query("DELETE from cont_tasaPrv where idPrv='$idPrv';");


    $mysqli->query("INSERT INTO cont_tasaPrv(idPrv,tasa,valor,visible) values('".$idpp."','".$tasan."','".$tasav."',1);");


	$id_tasa = $mysqli->insert_id;

$nacionalidad=$_POST['na'];
			$numidfiscal=$_POST['idf'];

	$mysqli->query("UPDATE mrp_provee
		cp='$cp_sp',
		colonia='$colonia_sp',
		ivaretenido='$ivaretenido',
		isretenido='$isretenido',
		idtipoiva='$idtipoiva',
		idtipo='$tipo',
		beneficiario_pagador='$beneficiario',
		cuentacliente='$cuentaCliente',
		idTasaPrvasumir='$id_tasa',
		idtipotercero='$tipoTercero',
		idtipoperacion='$tipoTerceroOperacion',
		nacionalidad='$nacionalidad',
			numidfiscal='$numidfiscal'
		WHERE id_xtructur='$id';");

	$SQL="SELECT idPrv FROM mrp_proveedor WHERE id_xtructur='$id';";
	$result = $mysqli->query($SQL);
	$row_cnt = $result->num_rows;
	if($row_cnt>0){
		$row = $result->fetch_array();
		$idpp=$row['idPrv'];
	}

	if (isset($_SESSION['stringbanc'])){

$queryB = "DELETE from cont_bancosPrv where idPrv='$idpp'";
				$mysqli->query($queryB);

    	$arraystringBanc = explode("#", $stringBanco);
			$arraystringBancR = array_reverse($arraystringBanc);
			unset($arraystringBancR[0]);
			$arraystringBanc = array_reverse($arraystringBancR); 
			foreach ($arraystringBanc as $key => $value2) {
				$arrayBanc = explode("-", $value2);
				$idbanco = $arrayBanc[1];
				$numCT = $arrayBanc[2];

				$queryB = "INSERT INTO cont_bancosPrv (idbanco, idPrv, numCT) VALUES ('".$idbanco."','".$idpp."','".$numCT."');";
				$mysqli->query($queryB);
			}}

	exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_altas WHERE id_tipo_alta in (4,5) AND borrado=0;";
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
if($start<0) $start=0;
if($_search=='true'){
	$soper=$_GET['searchOper'];
	$searchField='a.'.$searchField;
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



unset($_SESSION['stringbanc']);


$SQL = "SELECT concat('PROV-',a.id) as idpro,a.id_tipo_alta ,pro.*,cpc.especialidad as especialidad, a.*, c.id as idc, c.razon_social_sp, c.rfc_sp, c.calle_sp, c.colonia_sp, c.cp_sp, c.municipio_sp, c.estado_sp, c.tel_emp_sp, c.paterno_sp, c.materno_sp, c.nombres_sp, c.tel_personal_sp, c.correo_sp, a.id_agrupador, pa.nombre nomagru, pb.nombre nomesp, pc.nombre nomare, pd.nombre nompar, c.dias_credito, c.limite_credito, xp.pais, xe.estado, xm.municipio, CONCAT(manual_code, ' ',description) nombre_cuenta, c.banco, c.cuenta,c.imp_cont,c.ade1,c.ade2,c.ade3,c.anticipo,c.por_fondo_garantia,c.por_retencion FROM constru_altas a 
LEFT JOIN constru_info_sp c on c.id_alta=a.id 
left join paises xp on xp.idpais=c.id_pais
left join estados xe on xe.idestado=c.id_estado
left join municipios xm on xm.idmunicipio=c.id_municipio
left join cont_accounts cc on cc.account_id=c.cuenta_acont
	LEFT JOIN constru_agrupador pa on pa.id=a.id_agrupador 
	LEFT JOIN constru_area pb on pb.id=a.id_especialidad
		LEFT JOIN constru_cat_especialidad cpc on cpc.id=a.oc_inst
	LEFT JOIN constru_especialidad pc on pc.id=a.id_area
	LEFT JOIN constru_partida pd on pd.id=a.id_especialidad
	LEFT JOIN mrp_proveedor pro on pro.id_xtructur=a.id
WHERE 1=1 ".$cad." AND a.id_obra='$id_obra' AND a.id_tipo_alta in (4,5) AND a.borrado=0 ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
	            	if($row['id_tipo_alta']==4){$tipo_alta = "Subcontratista";}
if($row['id_tipo_alta']==5){$tipo_alta = "Proveedor";}
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['idpro'],$tipo_alta,$row['estatus'],$row['f_captura'],$row['f_ingreso'],$row['dias_credito'],$row['limite_credito'],$row['razon_social_sp'],$row['rfc_sp'],$row['calle_sp'],$row['colonia_sp'],$row['cp_sp'],$row['pais'],$row['estado'],$row['municipio'],$row['tel_emp_sp'],$row['paterno_sp'],$row['materno_sp'],$row['nombres_sp'],$row['tel_personal_sp'],$row['correo_sp'],$row['nombre_cuenta'],'Si',$row['idtipo'],$row['beneficiario_pagador'],$row['cuentacliente'],$row['idtipotercero'],$row['idtipoperacion'],$row['ivaretenido'],$row['isretenido'],$row['idtipoiva'],$row['idTasaPrvasumir'],$row['nacionalidad'],$row['numidfiscal'],$row['imp_cont'],$row['ade1'],$row['ade2'],$row['ade3'],$row['anticipo'],$row['por_fondo_garantia'],$row['por_retencion']); 
    $i++;
}        
echo json_encode($responce);
?>