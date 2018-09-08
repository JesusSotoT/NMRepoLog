<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	exit();
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
	$mysqli->query("UPDATE constru_proforma SET borrado=1 WHERE id in ($id);");
	exit();

}
if(isset($oper) && $oper=='add'){
	$por_utilidad = $_POST['por_utilidad'];
	$de_utilidad = $_POST['de_utilidad'];
	$por_indirecto = $_POST['por_indirecto'];
	$de_indirecto = $_POST['de_indirecto'];
	$factor_salario = $_POST['factor_salario'];
	$mysqli->query("INSERT INTO constru_proforma (id_obra,por_utilidad,de_utilidad,por_indirecto,de_indirecto,factor_salario) VALUES ('$id_obra','$por_utilidad','$de_utilidad','$por_indirecto','$de_indirecto','$factor_salario');");
	exit();
}

if(isset($oper) && $oper=='edit'){
	$id = $_POST['id'];
	$por_utilidad = $_POST['por_utilidad'];
	$de_utilidad = $_POST['de_utilidad'];
	$por_indirecto = $_POST['por_indirecto'];
	$de_indirecto = $_POST['de_indirecto'];
	$factor_salario = $_POST['factor_salario'];
	$mysqli->query("UPDATE constru_proforma SET por_utilidad='$por_utilidad', de_utilidad='$de_utilidad', por_indirecto='$por_indirecto', de_indirecto='$de_indirecto', factor_salario='$factor_salario' WHERE id='$id';");
	exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_proforma WHERE id_obra='$id_obra' AND borrado=0;";
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

$SQL = "SELECT a.* FROM constru_proforma a WHERE 1=1 ".$cad." AND a.id_obra='$id_obra' AND borrado=0 ORDER BY $sidx $sord LIMIT $start , $limit";
$result = $mysqli->query($SQL);


$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
	$responce->rows[$i]['id']=$row['id'];
	$responce->rows[$i]['cell']=array($row['id'],$row['por_utilidad'],$row['de_utilidad'],$row['por_indirecto'],$row['de_indirecto'],$row['factor_salario'],$row['indirecto']);
	$i++;
}        
echo json_encode($responce);
?>