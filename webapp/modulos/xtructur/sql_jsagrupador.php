<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

$oper = $_POST['oper'];
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;

include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){
	$id = $_POST['id'];
	$mysqli->query("UPDATE constru_agrupador SET borrado=1 WHERE id in ($id);");
	exit();
}

if(isset($oper) && $oper=='edit'){
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$mysqli->query("UPDATE constru_agrupador SET nombre='$nombre' WHERE id='$id';");
	exit();
}

if(isset($oper) && $oper=='add'){
	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$mysqli->query("INSERT INTO constru_agrupador (id_presupuesto, nombre, codigo, id_obra) VALUES ('$id_presupuesto','$nombre','$codigo','$id_obra');");
	$last_id = $mysqli->insert_id;
	$codigo='A-'.$last_id;
	$mysqli->query("UPDATE constru_agrupador SET codigo='$codigo' WHERE id='$last_id';");

	exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_agrupador WHERE id_obra='$id_obra' AND borrado=0;";
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
	if(preg_match('/^\(/', $searchField)){

	}else{
		$searchField='a.'.$searchField;
	}
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

 $SQL="SELECT a.*, sum(e.unidad*e.precio_costo) as total_costo, sum(e.unidad*e.precio_venta) as total_venta FROM constru_agrupador a LEFT JOIN constru_especialidad b on b.id_agrupador=a.id AND b.borrado=0 LEFT JOIN constru_area c on c.id_especialidad=b.id AND c.borrado=0 LEFT JOIN constru_partida d on d.id_area=c.id AND d.borrado=0 LEFT JOIN constru_recurso e on e.id_partida=d.id AND e.borrado=0 WHERE 1=1 ".$cad." AND a.id_obra='$id_obra' AND a.borrado=0 GROUP BY a.id ORDER BY $sidx $sord LIMIT $start , $limit";
$result = $mysqli->query($SQL);


$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array("",$row['codigo'],$row['nombre'],$row['total_venta']);
    $i++;
}        
echo json_encode($responce);
?>