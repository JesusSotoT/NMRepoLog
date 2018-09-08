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

$oper = $_POST['oper'];
$id_partida = 0;
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];


$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database

$mysqli->query("SET NAMES utf8");
$SQL = "SELECT correo_can FROM constru_config WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$correo=$row['correo_can'];

$SQL = "SELECT count(*) as count
from constru_bit_solicitudes a 
left join constru_recurso b on b.id_bit_solicitud=a.id
where a.id_obra='$id_obra';";
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

if($correo==1){
$SQL = "SELECT b.*,b.precio_costo*b.unidad as importe,a.naturaleza as naturaleza,a.id as id,
CASE a.estatus 
WHEN 0 THEN concat('Adicional-',a.id,' <input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#mailmodal\"  data-eid=',a.id, ' >  ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'sol\',',a.id,',',1,',',0,');\" >')
WHEN 2 THEN concat('Adicional-',a.id,' <font color=\"#ff0000\">Adicional Cancelada</font>')
WHEN 1 THEN concat('Adicional-',a.id,' <font color=\"#070\">Adicional Autorizada</font>')
END as Estimacion
from constru_recurso b
left join constru_bit_solicitudes a on a.id=b.id_bit_solicitud
where b.id_obra='$id_obra' and a.naturaleza='Adicional'
 ORDER BY  $sidx $sord LIMIT $start,$limit;";


$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;}
else{
$SQL = "SELECT b.*,b.precio_costo*b.unidad as importe,a.naturaleza as naturaleza,a.id as id,
CASE a.estatus 
WHEN 0 THEN concat('Adicional-',a.id,' <input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'sol\',',a.id,',',2,',','0',');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'sol\',',a.id,',',1,',',0,');\" >')
WHEN 2 THEN concat('Adicional-',a.id,' <font color=\"#ff0000\">Adicional Cancelada</font>')
WHEN 1 THEN concat('Adicional-',a.id,' <font color=\"#070\">Adicional Autorizada</font>')
END as Estimacion
from constru_recurso b
left join constru_bit_solicitudes a on a.id=b.id_bit_solicitud
where b.id_obra='$id_obra' and a.naturaleza='Adicional'
 ORDER BY  $sidx $sord LIMIT $start,$limit;";


$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;}
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['naturaleza'],$row['descripcion'],$row['justificacion'],$row['unidtext'],$row['unidad'],$row['precio_costo'],$row['importe'],$row['Estimacion']);
    $i++;
}       
echo json_encode($responce);
?>