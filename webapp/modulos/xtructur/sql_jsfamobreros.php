<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

$id_categoria_familia = 2;

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
if(isset($oper) && $oper=='add'){

	$familia = $_POST['familia'];
	$mysqli->query("INSERT INTO constru_familias (id_obra,id_categoria_familia,familia) VALUES ('$id_obra', '$id_categoria_familia', '$familia');");
	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$familia = $_POST['familia'];
	$mysqli->query("UPDATE constru_familias SET id_categoria_familia='$id_categoria_familia', familia='$familia' WHERE id='$id';");

	exit();
}

$SQL="SELECT COUNT(*) AS count FROM constru_familias WHERE id_obra='$id_obra' AND id_categoria_familia='$id_categoria_familia' AND borrado=0;";
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


$SQL = "SELECT a.*, concat('FAMI-',a.id) as clv FROM constru_familias a WHERE a.id_obra='$id_obra' AND id_categoria_familia='$id_categoria_familia' AND a.borrado=0 ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['clv'],$row['familia']);
    $i++;
}        
echo json_encode($responce);
?>