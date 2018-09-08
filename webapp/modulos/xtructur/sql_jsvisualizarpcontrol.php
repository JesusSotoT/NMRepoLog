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
if($result->num_rows>0){
	$row = $result->fetch_array();
	$id_presupuesto=$row['id'];
}else{
	$id_presupuesto='';
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
 
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){

	$id = $_POST['id'];
	$naturaleza = $_POST['name'];
	if($naturaleza=='Catalogo'){
		echo 'CAT';
		exit();
	}else{
		$mysqli->query("UPDATE constru_recurso SET borrado=1 WHERE id in ($id);");
		exit();
	}

}

if(isset($oper) && $oper=='add'){

	$id = $_POST['id'];
	
	$naturaleza = $_POST['naturaleza'];
	$descripcion = $_POST['descripcion'];
	$codigo = $_POST['codigo_clave'];
	if($naturaleza=='Extra'){
		$codigo = 'EXT-'.$codigo;
	}elseif ($naturaleza=='No cobrable') {
		$codigo = 'OTO-'.$codigo;
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

	$mysqli->query("INSERT INTO constru_recurso (naturaleza, id_partida, id_naturaleza, unidtext, codigo, unidad, descripcion, precio_costo, precio_venta, id_presupuesto, id_obra) VALUES ('$naturaleza','$id',1,'$unidtext','$codigo','$unidad','$descripcion','$precio_costo','$precio_costo','$id_presupuesto', '$id_obra');");
	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$naturaleza = $_POST['naturaleza'];
	$descripcion = $_POST['descripcion'];
	$codigo = $_POST['codigo_clave'];
	if($naturaleza=='Extra'){
		$codigo = 'EXT-'.$codigo;
	}elseif ($naturaleza=='No cobrable') {
		$codigo = 'OTO-'.$codigo;
	}

	if($naturaleza=='Catalogo'){
		$contra = $_POST['contra'];
		if($contra!='SUP3R4DM1N'){
			echo 'admin';
			exit();
		}
	}
	
	$id_um = $_POST['id_um'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];
	$corto = $_POST['corto'];

	$mysqli->query("UPDATE constru_recurso SET naturaleza='$naturaleza', id_um='$id_um', codigo='$codigo', unidad='$unidad', corto='$corto', descripcion='$descripcion', precio_costo='$precio_costo', precio_venta='$precio_costo' WHERE id='$id';");
	exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_recurso WHERE id_partida=0 AND borrado=0 AND id_presupuesto='$id_presupuesto';";
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
if($id_presupuesto!=''){
	$SQL = "SELECT a.*, (a.unidad*a.precio_costo) as tc, (a.unidad*a.precio_venta) as tv FROM constru_recurso a WHERE 1=1 ".$cad." AND a.id_presupuesto='$id_presupuesto' AND a.borrado=0 AND a.autorizado=1 ORDER BY a.id_naturaleza desc, a.id LIMIT $start,$limit";
	$result = $mysqli->query($SQL);

$count=$result->num_rows;
	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	while($row = $result->fetch_array()) {
	    $responce->rows[$i]['id']=$row['id'];
	    $responce->rows[$i]['cell']=array($row['id'],$row['naturaleza'],$row['codigo'],$row['descripcion'],$row['unidtext'],$row['unidad'],$row['precio_costo'],$row['tc'],$row['pu_destajo'],$row['pu_subcontrato']);
	    $i++;
	}
}else{
	$responce=array();
}     

echo json_encode($responce);
?>