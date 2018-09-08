<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
  exit();
}else{
  $cookie_xtructur = unserialize($_COOKIE['xtructur']);
  $id_obra = $cookie_xtructur['id_obra'];
}

$sestmp = $_GET['sestmp'];
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
	$mysqli->query("UPDATE constru_pedidos SET borrado=1 WHERE id in ($id);");
	exit();

}
if(isset($oper) && $oper=='add'){
	$id_proveedor = $_POST['id_proveedor'];
	$id_requis = $_POST['id_requisicion'];
	$fecha_entrega = $_POST['fecha_entrega'];

	$SQL="SELECT id FROM constru_pedidos WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_pedid=0 and borrado=0 AND id_requis='$id_requis';";
	$result = $mysqli->query($SQL);
	if($result->num_rows>0) {
		echo 'RP';
		exit();
	}


	$mysqli->query("INSERT INTO constru_pedidos (id_obra, id_proveedor, id_requis,fecha_entrega,sestmp) VALUES ('$id_obra', '$id_proveedor','$id_requis', '$fecha_entrega','$sestmp');");
	exit();
}

if(isset($oper) && $oper=='edit'){
	$id = $_POST['id'];
	$id_proveedor = $_POST['id_proveedor'];
	$id_requis = $_POST['id_requisicion'];
	$fecha_entrega = $_POST['fecha_entrega'];

	$mysqli->query("UPDATE constru_pedidos SET id_proveedor='$id_proveedor', id_requis='$id_requis', fecha_entrega='$fecha_entrega' WHERE id='$id';");
	exit();
}

$SQL="SELECT COUNT(*) AS count FROM constru_pedidos WHERE id_obra='$id_obra' AND sestmp>0 AND id_pedid=0 AND borrado=0;";
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


$SQL = "SELECT a.id as ido, SUBSTRING(b.fecha_entrega,1,10) fecha_entrega, concat('REQ-',a.id_requis) req from constru_pedidos a inner join constru_requis b on b.id=a.id_requis WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_pedid=0 AND a.borrado=0 ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['ido'];
    $responce->rows[$i]['cell']=array($row['req'],$row['fecha_entrega']);
    $i++;
}        
echo json_encode($responce);
?>