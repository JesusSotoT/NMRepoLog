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

if(isset($oper) && $oper=='add'){

	$id = $_POST['id'];
	
	$naturaleza = $_POST['naturaleza'];
	$descripcion = $_POST['descripcion'];
	$codigo = $_POST['codigo_clave'];
	$unidtext = $_POST['unidtext'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];

	$mysqli->query("INSERT INTO constru_recurso (naturaleza, id_partida, id_naturaleza, unidtext, codigo, unidad, descripcion, precio_costo, precio_venta, id_presupuesto) VALUES ('$naturaleza','$id',1,'$unidtext','$codigo','$unidad','$descripcion','$precio_costo','$precio_venta','$id_presupuesto');");
	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$descripcion = $_POST['descripcion'];
	$codigo = $_POST['codigo_clave'];
	$id_um = $_POST['id_um'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];
	$corto = $_POST['corto'];

	$mysqli->query("UPDATE constru_recurso SET id_um='$id_um', codigo='$codigo', unidad='$unidad', corto='$corto', descripcion='$descripcion', precio_costo='$precio_costo', precio_venta='$precio_venta' WHERE id='$id';");
	exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_recurso WHERE borrado=0 AND id_presupuesto='$id_presupuesto';";
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

$SQL = "SELECT a.*, (a.unidad*a.precio_costo) as tc, (a.unidad*a.precio_venta) as tv, count(b.id) totala FROM constru_recurso a 
LEFT JOIN constru_asignaciones b on b.id_recurso=a.id
WHERE 1=1 ".$cad." AND a.id_presupuesto='$id_presupuesto' AND a.borrado=0 AND a.autorizado=1 GROUP BY a.id ORDER BY a.id LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['id'],$row['naturaleza'],$row['codigo'],$row['descripcion'],$row['unidtext'],$row['unidad'],$row['precio_costo'],$row['tc'],$row['totala']);
    $i++;
}        
echo json_encode($responce);
?>