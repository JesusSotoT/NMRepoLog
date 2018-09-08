<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
  exit();
}else{
  $cookie_xtructur = unserialize($_COOKIE['xtructur']);
  $id_obra = $cookie_xtructur['id_obra'];
}
if(!session_id()){
	session_start();
	$session_req=$_SESSION['req'];
}else{
	$session_req=$_SESSION['req'];
}


$oper = $_POST['oper'];

$sestmp = $_GET['sestmp'];
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
	$mysqli->query("DELETE FROM constru_requisiciones WHERE id in ($id);");
	exit();
}

if(isset($oper) && $oper=='add'){
	$id_clave = $_POST['id_clave'];
   	$SQL="SELECT id FROM constru_requisiciones WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_requi=0 and borrado=0 AND id_clave='$id_clave';";
	$result = $mysqli->query($SQL);
	if($result->num_rows>0) {
		echo 'RP';
		exit();
	}

	$cantidad = $_POST['cantidad'];
	$fecha_entrega = $_POST['fecha_entrega'];
	$mysqli->query("INSERT INTO constru_requisiciones (id_obra,id_clave,id_requi,cantidad,fecha_entrega,sestmp) VALUES ('$id_obra', '$id_clave',0, '$cantidad', '$fecha_entrega','$sestmp');");
	exit();
}

if(isset($oper) && $oper=='edit'){
	$id = $_POST['id'];
	$id_clave = $_POST['id_clave'];

	$cantidad = $_POST['cantidad'];

	$fecha_entrega = $_POST['fecha_entrega'];

	$mysqli->query("UPDATE constru_requisiciones SET id_clave='$id_clave', cantidad='$cantidad', fecha_entrega='$fecha_entrega' WHERE id='$id';");
	exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_requisiciones WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_requi=0 and borrado=0;";
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


$SQL = "SELECT a.id as ido, a.cantidad, b.unidtext, b.descripcion, b.precio, a.fecha_entrega, b.clave from constru_requisiciones a left join constru_insumos b on b.id=a.id_clave WHERE a.id_obra='$id_obra' ".$cad." AND a.sestmp='$sestmp' AND a.id_requi=0 AND a.borrado=0 AND a.sestmp='$session_req'  ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['ido'];
    $responce->rows[$i]['cell']=array('',$row['clave'],$row['unidtext'],$row['cantidad'],$row['descripcion'],$row['precio']);
    $i++;
}        

echo json_encode($responce);
?>