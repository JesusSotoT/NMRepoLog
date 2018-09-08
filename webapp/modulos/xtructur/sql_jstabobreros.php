<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

$id_tipo_alta=2; //Tecnicos Administrativos

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
	$mysqli->query("UPDATE constru_categoria SET borrado=1 WHERE id in ($id);");
	exit();
}

if(isset($oper) && $oper=='add'){

	$id_familia = $_POST['id_familia'];
	$clave_cat = $_POST['clave_cat'];
	$categoria = $_POST['categoria'];
	$sal_semanal = $_POST['sal_semanal'];
	$dias = $_POST['dias'];


	if($dias>7 || $dias<1) {
		echo 'RP';
		exit();
	}



	$mysqli->query("INSERT INTO constru_categoria (id_familia,clave_cat,categoria,sal_semanal,dias) VALUES ('$id_familia', '$clave_cat', '$categoria', '$sal_semanal','$dias');");
	$last_id = $mysqli->insert_id;
	$cate='CATE-'.$last_id;
	$mysqli->query("UPDATE constru_categoria SET clave_cat='$cate' WHERE id='$last_id';");

	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$id_familia = $_POST['id_familia'];
	$clave_cat = $_POST['clave_cat'];
	$categoria = $_POST['categoria'];
	$sal_semanal = $_POST['sal_semanal'];
	$dias = $_POST['dias'];

		if($dias>7 || $dias<1) {
		echo 'RP';
		exit();
	}


	$mysqli->query("UPDATE constru_categoria SET id_familia='$id_familia', categoria='$categoria', sal_semanal='$sal_semanal', dias='$dias' WHERE id='$id';");
	exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_categoria a LEFT JOIN constru_familias b on b.id=a.id_familia AND b.id_obra='$id_obra' AND b.id_categoria_familia='2' WHERE b.id_obra='$id_obra' AND a.borrado=0;";
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


$SQL = "SELECT a.*, if(b.id_categoria_familia=1,concat('Tecnicos - ',b.familia),concat('Obreros - ',b.familia) ) as familia FROM constru_categoria a LEFT JOIN constru_familias b on b.id=a.id_familia AND b.id_obra='$id_obra' ".$cad." AND b.id_categoria_familia='2' WHERE b.id_obra='$id_obra' AND a.borrado=0 ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['familia'],$row['clave_cat'],$row['categoria'],$row['sal_semanal'],$row['dias'],'');
    $i++;
}        
echo json_encode($responce);
?>