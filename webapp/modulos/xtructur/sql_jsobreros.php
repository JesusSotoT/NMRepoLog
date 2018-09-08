<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

$id_tipo_alta=3; //Obreros

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
	$id_responsable = $_POST['id_responsable'];
	$id_agrupador = $_POST['id_agrupador'];
	$id_area = $_POST['id_area'];
	$id_depto = 0;
	$tipo_alta = $_POST['tipo_alta'];
	$oc_inst = $_POST['oc_inst'];
	$id_familia = $_POST['id_familia'];
	$id_categoria = $_POST['id_categoria'];
	$id_alta = $_POST['id_alta'];
	$nombre = $_POST['nombre'];
	$paterno = $_POST['paterno'];
	$materno = $_POST['materno'];
	$domicilio = $_POST['domicilio'];
	$colonia = $_POST['colonia'];
	$cp = $_POST['cp'];
	$municipio = $_POST['municipio'];
	$estado = $_POST['estado'];
	$civil = $_POST['civil'];
	$tel_personal = $_POST['tel_personal'];
	$casado_con = $_POST['casado_con'];
	$contacto_con = $_POST['contacto_con'];
	$telefono_con = $_POST['telefono_con'];
	$fecha_nacimiento = $_POST['fecha_nacimiento'];
	$correo = $_POST['correo'];

	$acta = $_POST['acta'];
	$ife = $_POST['ife'];
	$curp = $_POST['curp'];
	$imss = $_POST['imss'];
	$infonavit = $_POST['infonavit'];
	$carta_penales = $_POST['carta_penales'];
	$domicilio_d = $_POST['domicilio_d'];

	$contrato_e = $_POST['contrato_e'];
	$foto_e = $_POST['foto_e'];
	$acta_e = $_POST['acta_e'];
	$ife_e = $_POST['ife_e'];
	$curp_e = $_POST['curp_e'];
	$imss_e = $_POST['imss_e'];
	$infonavit_e = $_POST['infonavit_e'];
	$carta_penales_e = $_POST['carta_penales_e'];
	$domicilio_e = $_POST['domicilio_e'];


	$mysqli->query("INSERT INTO constru_altas (id_obra, id_tipo_alta, estatus, f_captura, f_ingreso, f_alta_i, f_baja_i, id_responsable, id_agrupador, id_area, id_depto, tipo_alta, oc_inst, id_familia, id_categoria) VALUES ('$id_obra','$id_tipo_alta','$estatus','$f_captura','$f_ingreso','$f_alta_i','$f_baja_i','$id_responsable', '$id_agrupador', '$id_area','$id_depto','$tipo_alta','$oc_inst','$id_familia','$id_categoria');");

	$id_alta = $mysqli->insert_id;
	if($id_alta>0){
		$mysqli->query("INSERT INTO constru_info_tdo (id_alta, nombre, paterno, materno, domicilio, colonia, cp, municipio, estado, civil, tel_personal, casado_con, contacto_con, telefono_con, fecha_nacimiento,correo) VALUES ('$id_alta','$nombre','$paterno','$materno','$domicilio','$colonia','$cp','$municipio','$estado','$civil','$tel_personal','$casado_con','$contacto_con','$telefono_con','$fecha_nacimiento','$correo');");

		$mysqli->query("INSERT INTO constru_docs (id_alta, acta, ife, curp, imss, infonavit, carta_penales, domicilio) VALUES ('$id_alta','$acta','$ife','$curp','$imss','$infonavit','$carta_penales','$domicilio_d');");

		$mysqli->query("INSERT INTO constru_escaneo (id_alta, contrato_e, foto_e, acta_e, ife_e, curp_e, imss_e, infonavit_e, carta_penales_e, domicilio_e) VALUES ('$id_alta','$contrato_e','$foto_e','$acta_e','$ife_e','$curp_e','$imss_e','$infonavit_e','$carta_penales_e','$domicilio_e');");
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
	$id_responsable = $_POST['id_responsable'];
	$id_agrupador = $_POST['id_agrupador'];
	$id_area = $_POST['id_area'];
	$id_depto = $_POST['id_depto'];
	$tipo_alta = $_POST['tipo_alta'];
	$oc_inst = $_POST['oc_inst'];
	$id_familia = $_POST['id_familia'];
	$id_categoria = $_POST['id_categoria'];
	$id_alta = $_POST['id_alta'];
	$nombre = $_POST['nombre'];
	$paterno = $_POST['paterno'];
	$materno = $_POST['materno'];
	$domicilio = $_POST['domicilio'];
	$colonia = $_POST['colonia'];
	$cp = $_POST['cp'];
	$municipio = $_POST['municipio'];
	$estado = $_POST['estado'];
	$civil = $_POST['civil'];
	$tel_personal = $_POST['tel_personal'];
	$casado_con = $_POST['casado_con'];
	$contacto_con = $_POST['contacto_con'];
	$telefono_con = $_POST['telefono_con'];
	$fecha_nacimiento = $_POST['fecha_nacimiento'];
	$correo = $_POST['correo'];

	$acta = $_POST['acta'];
	$ife = $_POST['ife'];
	$curp = $_POST['curp'];
	$imss = $_POST['imss'];
	$infonavit = $_POST['infonavit'];
	$carta_penales = $_POST['carta_penales'];
	$domicilio_d = $_POST['domicilio_d'];

	$contrato_e = $_POST['contrato_e'];
	$foto_e = $_POST['foto_e'];
	$acta_e = $_POST['acta_e'];
	$ife_e = $_POST['ife_e'];
	$curp_e = $_POST['curp_e'];
	$imss_e = $_POST['imss_e'];
	$infonavit_e = $_POST['infonavit_e'];
	$carta_penales_e = $_POST['carta_penales_e'];
	$domicilio_e = $_POST['domicilio_e'];

	$mysqli->query("UPDATE constru_altas SET estatus='$estatus', f_captura='$f_captura', f_ingreso='$f_ingreso', f_alta_i='$f_alta_i', f_baja_i='$f_baja_i', id_responsable='$id_responsable', id_agrupador='$id_agrupador', id_area='$id_area', id_depto='$id_depto', tipo_alta='$tipo_alta', oc_inst='$oc_inst', id_familia='$id_familia',id_categoria='$id_categoria' WHERE id='$id';");
	$mysqli->query("UPDATE constru_info_tdo SET nombre='$nombre', paterno='$paterno', materno='$materno', domicilio='$domicilio', colonia='$colonia', cp='$cp', municipio='$municipio', estado='$estado', civil='$civil', tel_personal='$tel_personal', casado_con='$casado_con', contacto_con='$contacto_con', telefono_con='$telefono_con', fecha_nacimiento='$fecha_nacimiento', correo='$correo' WHERE id_alta='$id';");

	$mysqli->query("UPDATE constru_docs SET acta='$acta', ife='$ife', curp='$curp', imss='$imss', infonavit='$infonavit', carta_penales='$carta_penales', domicilio='$domicilio_d' WHERE id_alta='$id';");

	$mysqli->query("UPDATE constru_escaneo SET contrato_e='$contrato_e', foto_e='$foto_e', acta_e='$acta_e', ife_e='$ife_e', curp_e='$curp_e', imss_e='$imss_e', infonavit_e='$infonavit_e', carta_penales_e='$carta_penales_e', domicilio_e='$domicilio_e' WHERE id_alta='$id';");

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


 $SQL = "SELECT concat('ID-',a.id) as idobr, cpc.especialidad as especialidad, a.*, b.familia as familia, if(d.sal_semanal>0,concat(d.categoria,' / $',d.sal_semanal),concat(d.categoria,' / $',d.sal_mensual)) as categoria, c.id as idc, c.nombre, c.paterno, c.materno, c.domicilio, c.colonia, c.cp, c.municipio, c.estado, c.civil, c.tel_personal, c.casado_con, c.contacto_con, c.telefono_con, c.fecha_nacimiento, e.departamento, c.correo, f.acta, f.ife, f.curp, f.imss, f.infonavit, f.carta_penales, f.domicilio domicilio_d, g.contrato_e, g.foto_e, g.acta_e, g.ife_e, g.curp_e, g.imss_e, g.infonavit_e, g.carta_penales_e, g.domicilio_e, a.id_agrupador, pa.nombre nomagru,  pax.nombre nomare FROM constru_altas a 
 LEFT JOIN constru_familias b on b.id=a.id_familia 
 LEFT JOIN constru_info_tdo c on c.id_alta=a.id 
 LEFT JOIN constru_categoria d on d.id=a.id_categoria 
 LEFT JOIN constru_deptos e on e.id=a.id_depto 
 LEFT JOIN constru_docs f on f.id_alta=a.id 
 LEFT JOIN constru_escaneo g on g.id_alta=a.id 
	LEFT JOIN constru_agrupador pa on pa.id=a.id_agrupador 
LEFT JOIN constru_especialidad pax on pax.id=a.id_area 
	LEFT JOIN constru_cat_especialidad cpc on cpc.id=a.oc_inst
 WHERE 1=1 ".$cad." AND a.id_obra='$id_obra' AND a.id_tipo_alta=$id_tipo_alta AND a.borrado=0 ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['idobr'],$row['estatus'],$row['f_captura'],$row['f_ingreso'],$row['f_alta_i'],$row['f_baja_i'],$row['nomagru'],$row['nomare'],$row['id_responsable'],$row['tipo_alta'],$row['familia'],$row['categoria'],$row['id_alta'],$row['nombre'],$row['paterno'],$row['materno'],$row['domicilio'],$row['colonia'],$row['cp'],$row['municipio'],$row['estado'],$row['civil'],$row['tel_personal'],$row['correo'],$row['casado_con'],$row['contacto_con'],$row['telefono_con'],$row['fecha_nacimiento'],$row['acta'],$row['ife'],$row['curp'],$row['imss'],$row['infonavit'],$row['carta_penales'],$row['domicilio_d'],$row['contrato_e'],$row['foto_e'],$row['acta_e'],$row['ife_e'],$row['curp_e'],$row['imss_e'],$row['infonavit_e'],$row['carta_penales_e'],$row['domicilio_e']);
    $i++;
}        
echo json_encode($responce);
?>