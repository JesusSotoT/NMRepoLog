<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
  echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

session_start();
$id_presupuesto=$_SESSION['xtructur']['id_presupuesto'];

$oper = $_POST['oper'];

$id_partida = $_GET['id_partida'];
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

	$id = $_POST['id'];
	$descripcion = $_POST['descripcion'];
	$naturaleza = $_POST['naturaleza'];
	$codigo = $_POST['codigo_clave'];
	$unidtext = $_POST['unidtext'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];

	$mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, id_um, naturaleza, codigo, unidad, descripcion, precio_costo, unidtext, id_presupuesto, precio_venta) VALUES ('0',1,'$id_um','$naturaleza', '$codigo','$unidad','$descripcion','$precio_costo', '$unidtext', '$id_presupuesto', '$precio_costo');");
	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$descripcion = $_POST['descripcion'];
	$codigo = $_POST['codigo'];
	$id_um = $_POST['id_um'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];
	$corto = $_POST['corto'];

	$mysqli->query("UPDATE constru_recurso SET id_um='$id_um', codigo='$codigo', unidad='$unidad', corto='$corto', descripcion='$descripcion', precio_costo='$precio_costo', precio_venta='$precio_venta' WHERE id='$id';");
	exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_recurso WHERE id_partida='$id_partida' AND borrado=0;";
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

$SQL = "SELECT a.*, (a.unidad*a.precio_costo) as tc, (a.unidad*a.precio_venta) as tv
FROM constru_recurso a
left join constru_asignaciones g on g.id_recurso=a.id AND a.id_obra=g.id_obra
WHERE 1=1 AND a.id_obra='$id_obra' AND g.id_partida='$id_partida' AND a.borrado=0 ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array("",$row['id'],$row['naturaleza'],$row['codigo'],$row['descripcion'],$row['unidtext'],$row['unidad'],$row['precio_venta'],$row['tv'],'','','');
    $i++;
}        
echo json_encode($responce);
?>