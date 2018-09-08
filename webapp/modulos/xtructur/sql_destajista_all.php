<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
 
include('conexiondb.php');

$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$id_presupuesto=$row['id'];

$SQL = "SELECT correo_can FROM constru_config WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$correo=$row['correo_can'];

$oper = $_POST['oper'];
$id_partida = 0;
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];


$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction

//Filtros nuevos
if(!isset($_GET['filtro_semana'])){ $filtro_semana=0; }else{ $filtro_semana=$_GET['filtro_semana']; }
if(!isset($_GET['filtro_mes'])){ $filtro_mes=0; }else{ $filtro_mes=$_GET['filtro_mes']; }
if(!isset($_GET['filtro_estatus'])){ $filtro_estatus='x'; }else{ $filtro_estatus=$_GET['filtro_estatus']; }
if(!isset($_GET['filtro_proveedor'])){ $filtro_proveedor=0; }else{ $filtro_proveedor=$_GET['filtro_proveedor']; }
//Fin filtros nuevos


if(!$sidx) $sidx =1;
// connect to the database

$mysqli->query("SET NAMES utf8");

$SQL = "SELECT COUNT(*) AS count FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
left JOIN constru_info_tdo d on d.id_alta=a.solicito
WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0;";
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

$cadenafiltro = '';
if($filtro_mes!=0){
   $filtro_semana=0; 
}

if($filtro_semana!=0){

    $cadenafiltro.=" AND yearweek(fecha,1)='".$filtro_semana."' ";
}

if($filtro_mes!=0){
    $ym = explode('-', $filtro_mes);
    $cadenafiltro.=" AND YEAR(fecha) = '".$ym[0]."' AND MONTH(fecha) = '".$ym[1]."'";
}

if($filtro_estatus!='x'){
    $cadenafiltro.=" AND a.estatus = '".$filtro_estatus."' ";
}

if($filtro_proveedor!=0){
    $cadenafiltro.=" AND a.id_destajista = '".$filtro_proveedor."' ";
}

if($correo==1){

$SQL = "SELECT
concat(b.nombre,' ',b.paterno) as Maestro,
CASE a.estatus 
WHEN 0 THEN concat('Estimacion-',a.id,' Fecha: ',substr(fecha,1,10),' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#mailmodal\"  data-eid=',a.id, '  > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'des\',',a.id,',',1,',',0,');\" >')
WHEN 2 THEN concat('Estimacion-',a.id,' Fecha: ',substr(fecha,1,10),' ','<font color=\"#ff0000\">Estimacion Cancelada</font>')
WHEN 1 THEN concat('Estimacion-',a.id,' Fecha: ',substr(fecha,1,10),' ','<font color=\"#070\">Estimacion Autorizada</font>')
END as Estimacion,
d.codigo, d.descripcion, d.unidtext, d.pu_destajo, e.vol_tope*d.pu_destajo as total, c.vol_anterior, c.vol_est, e.vol_tope
FROM constru_estimaciones_bit_destajista a
inner join constru_info_tdo b on b.id_alta=a.id_destajista
inner join constru_altas alt on alt.id=b.id_alta
inner join constru_estimaciones_destajista c on c.id_bit_destajista=a.id
left join constru_recurso d on d.id=c.id_clave
left join constru_vol_tope e on e.id_clave=d.id AND (e.id_area=a.id_area or e.id_area=c.id_area)
WHERE a.id_obra='$id_obra'".$cadenafiltro."  AND c.borrado=0  AND a.borrado=0 AND alt.id_tipo_alta=2 AND (alt.estatus='Alta' OR alt.estatus='Incapacitado') ORDER BY  $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;}

else {

$SQL = "SELECT
concat(b.nombre,' ',b.paterno) as Maestro,
CASE a.estatus 
WHEN 0 THEN concat('Estimacion-',a.id,' Fecha: ',substr(fecha,1,10),' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'des\',',a.id,',',2,',','0',');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'des\',',a.id,',',1,',',0,');\" >')
WHEN 2 THEN concat('Estimacion-',a.id,' Fecha: ',substr(fecha,1,10),' ','<font color=\"#ff0000\">Estimacion Cancelada</font>')
WHEN 1 THEN concat('Estimacion-',a.id,' Fecha: ',substr(fecha,1,10),' ','<font color=\"#070\">Estimacion Autorizada</font>')
END as Estimacion,
d.codigo, d.descripcion, d.unidtext, d.pu_destajo, e.vol_tope*d.pu_destajo as total, c.vol_anterior, c.vol_est, e.vol_tope
FROM constru_estimaciones_bit_destajista a
inner join constru_info_tdo b on b.id_alta=a.id_destajista
inner join constru_altas alt on alt.id=b.id_alta
inner join constru_estimaciones_destajista c on c.id_bit_destajista=a.id
left join constru_recurso d on d.id=c.id_clave
left join constru_vol_tope e on e.id_clave=d.id AND (e.id_area=a.id_area or e.id_area=c.id_area)
WHERE a.id_obra='$id_obra'".$cadenafiltro."  AND c.borrado=0  AND a.borrado=0 AND alt.id_tipo_alta=2 AND (alt.estatus='Alta' OR alt.estatus='Incapacitado') ORDER BY  $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;}
$i=0;

while($row = $result->fetch_array()) {
	$acumulado=$row['vol_anterior']+$row['vol_est'];
    $poreje=$row['vol_tope']-$acumulado;
    $impfin=$row['pu_destajo']*$row['vol_est'];
    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['Maestro'],$row['Estimacion'],$row['codigo'],$row['descripcion'],$row['unidtext'],$row['vol_tope'],$row['pu_destajo'],$row['total'],$row['vol_anterior'],$row['vol_est'],$acumulado,$poreje,$impfin);
    $i++;
}        
echo json_encode($responce);
?>