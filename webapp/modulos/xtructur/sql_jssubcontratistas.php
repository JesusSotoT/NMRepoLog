<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

$id_tipo_alta=4; //Subcontratistas

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
	$tipo_alta = $_POST['tipo_alta'];
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

	$imp_cont = $_POST['imp_cont'];
	$ade1 = $_POST['ade1'];
	$ade2 = $_POST['ade2'];
	$ade3 = $_POST['ade3'];
	$anticipo = $_POST['anticipo'];

	$dias_credito = $_POST['dias_credito'];
	$limite_credito = $_POST['limite_credito'];

	$Fondo_garantia = $_POST['Fondo_garantia'];
	$Retencion = $_POST['Retencion'];

	$mysqli->query("INSERT INTO constru_altas (id_obra, id_tipo_alta, estatus, f_captura, f_ingreso, f_alta_i, f_baja_i, id_responsable, id_agrupador, id_especialidad, id_area, id_partida, id_depto, tipo_alta, oc_inst, id_familia, id_categoria) VALUES ('$id_obra','$id_tipo_alta','$estatus','$f_captura','$f_ingreso','$f_alta_i','$f_baja_i','$id_responsable','$id_agrupador','$id_especialidad','$id_area','$id_partida','$id_depto','$tipo_alta','$oc_inst','$id_familia','$id_categoria');");

	$id_alta = $mysqli->insert_id;
	if($id_alta>0){

		$mysqli->query("INSERT INTO constru_info_sp (id_alta, razon_social_sp, rfc_sp, calle_sp, colonia_sp, cp_sp, municipio_sp, estado_sp, tel_emp_sp, paterno_sp, materno_sp, nombres_sp, tel_personal_sp, correo_sp,imp_cont,ade1,ade2,ade3,anticipo,dias_credito,limite_credito,por_fondo_garantia,por_retencion) VALUES ('$id_alta','$razon_social_sp','$rfc_sp','$calle_sp','$colonia_sp','$cp_sp','$municipio_sp','$estado_sp','$tel_emp_sp','$paterno_sp','$materno_sp','$nombres_sp','$tel_personal_sp','$correo_sp','$imp_cont','$ade1','$ade2','$ade3','$anticipo','$dias_credito','$limite_credito','$Fondo_garantia','$Retencion');");

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

	$imp_cont = $_POST['imp_cont'];
	$ade1 = $_POST['ade1'];
	$ade2 = $_POST['ade2'];
	$ade3 = $_POST['ade3'];
	$anticipo = $_POST['anticipo'];

	$dias_credito = $_POST['dias_credito'];
	$limite_credito = $_POST['limite_credito'];

	$Fondo_garantia = $_POST['Fondo_garantia'];
	$Retencion = $_POST['Retencion'];

	$mysqli->query("UPDATE constru_altas SET estatus='$estatus', f_captura='$f_captura', f_ingreso='$f_ingreso', f_alta_i='$f_alta_i', f_baja_i='$f_baja_i', id_responsable='$id_responsable', id_agrupador='$id_agrupador', id_especialidad='$id_especialidad', id_area='$id_area', id_partida='$id_partida', tipo_alta='$tipo_alta', oc_inst='$oc_inst' WHERE id='$id';");
	$mysqli->query("UPDATE constru_info_sp SET razon_social_sp='$razon_social_sp', rfc_sp='$rfc_sp', calle_sp='$calle_sp', colonia_sp='$colonia_sp', cp_sp='$cp_sp', municipio_sp='$municipio_sp', estado_sp='$estado_sp', tel_emp_sp='$tel_emp_sp', paterno_sp='$paterno_sp', materno_sp='$materno_sp', nombres_sp='$nombres_sp', tel_personal_sp='$tel_personal_sp', correo_sp='$correo_sp', imp_cont='$imp_cont', ade1='$ade1', ade2='$ade2', ade3='$ade3', anticipo='$anticipo', dias_credito='$dias_credito', limite_credito='$limite_credito', por_fondo_garantia='$Fondo_garantia', por_retencion='$Retencion' WHERE id_alta='$id';");


	exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_altas WHERE id_obra='$id_obra' AND id_tipo_alta=$id_tipo_alta AND borrado=0;";
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

$SQL = "SELECT concat('SUBC-',a.id) as idsub, cpc.especialidad as especialidad, a.*, c.id as idc, c.razon_social_sp, c.rfc_sp, c.calle_sp, c.colonia_sp, c.cp_sp, c.municipio_sp, c.estado_sp, c.tel_emp_sp, c.paterno_sp, c.materno_sp, c.nombres_sp, c.tel_personal_sp, c.correo_sp, a.id_agrupador, pa.nombre nomagru, pb.nombre nomesp, pc.nombre nomare, pd.nombre nompar, c.imp_cont, c.ade1, c.ade2, c.ade3, c.anticipo, c.dias_credito, c.limite_credito, c.por_fondo_garantia, c.por_retencion FROM constru_altas a 
LEFT JOIN constru_info_sp c on c.id_alta=a.id 
	LEFT JOIN constru_agrupador pa on pa.id=a.id_agrupador 
	LEFT JOIN constru_area pb on pb.id=a.id_especialidad
	LEFT JOIN constru_cat_especialidad cpc on cpc.id=a.oc_inst
	LEFT JOIN constru_especialidad pc on pc.id=a.id_area
	LEFT JOIN constru_partida pd on pd.id=a.id_especialidad
WHERE 1=1 ".$cad." AND a.id_obra='$id_obra' AND a.id_tipo_alta=$id_tipo_alta AND a.borrado=0 ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['idsub'],$row['estatus'],$row['f_captura'],$row['f_ingreso'],$row['dias_credito'],$row['limite_credito'],$row['tipo_alta'],$row['razon_social_sp'],$row['rfc_sp'],$row['calle_sp'],$row['colonia_sp'],$row['cp_sp'],$row['municipio_sp'],$row['estado_sp'],$row['tel_emp_sp'],$row['paterno_sp'],$row['materno_sp'],$row['nombres_sp'],$row['tel_personal_sp'],$row['correo_sp'],$row['imp_cont'],$row['ade1'],$row['ade2'],$row['ade3'],$row['anticipo'],$row['por_fondo_garantia'],$row['por_retencion']);
    $i++;
}        
echo json_encode($responce);
?>