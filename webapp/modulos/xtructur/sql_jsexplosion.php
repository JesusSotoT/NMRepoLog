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
// connect to the database

include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){

	$id = $_POST['id'];
	$naturaleza = $_POST['name'];
	if($naturaleza=='Catalogo'){
		echo 'CAT';
		exit();
	}else{
		$mysqli->query("UPDATE constru_insumos SET borrado=1 WHERE id in ($id);");
		exit();
	}
	
}

if(isset($oper) && $oper=='add'){

	$id = $_POST['id'];
	$naturaleza = $_POST['naturaleza'];
	$descripcion = $_POST['descripcion'];
	$codigo_clave = $_POST['codigo_clave'];
	if($naturaleza=='Extra'){
		$codigo_clave = 'EXT-'.$codigo_clave;
	}elseif ($naturaleza=='No cobrable') {
		$codigo_clave = 'OTO-'.$codigo_clave;
	}elseif ($naturaleza=='0') {
		echo 'sel';
		exit();
	}
	
	if($naturaleza=='Catalogo'){
		$contra = $_POST['contra'];
		if($contra!='SUP3R4DM1N'){
			echo 'admin';
			exit();
		}
	}

	$unidtext = $_POST['unidtext'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];
	

	$mysqli->query("INSERT INTO constru_insumos (id_obra, unidtext, naturaleza, clave, unidad, descripcion, precio) VALUES ('$id_obra', '$unidtext', '$naturaleza', '$codigo_clave','$unidad','$descripcion','$precio_costo');");

	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$naturaleza = $_POST['naturaleza'];
	$descripcion = $_POST['descripcion'];
	$codigo_clave = $_POST['codigo_clave'];
	if($naturaleza=='Extra'){
		$codigo_clave = 'EXT-'.$codigo_clave;
	}elseif ($naturaleza=='No cobrable') {
		$codigo_clave = 'OTO-'.$codigo_clave;
	}elseif ($naturaleza=='0') {
		echo 'sel';
		exit();
	
	}

	if($naturaleza=='Catalogo'){
		$contra = $_POST['contra'];
		if($contra!='SUP3R4DM1N'){
			echo 'admin';
			exit();
		}
	}
	$unidtext = $_POST['unidtext'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];


	$mysqli->query("UPDATE constru_insumos SET unidtext='$unidtext', naturaleza='$naturaleza', clave='$codigo_clave', unidad='$unidad', descripcion='$descripcion', precio='$precio_costo' WHERE id='$id';");
	
	exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_insumos WHERE borrado=0 AND id_obra='$id_obra';";
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

$SQL = "SELECT a.*, (a.unidad*a.precio) as tc, (a.unidad*a.precio) as tv FROM constru_insumos a WHERE 1=1 ".$cad." AND a.id_obra='$id_obra' AND a.borrado=0 ORDER BY a.id LIMIT $start,$limit";
$result = $mysqli->query($SQL);


$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['naturaleza'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['unidad'],$row['precio'],$row['tc'],$row['precio'],$row['tv']);
    $i++;
}        
echo json_encode($responce);
?>