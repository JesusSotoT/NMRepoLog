<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

session_start();
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
	$mysqli->query("UPDATE constru_contratista SET borrado=1 WHERE id in ($id);");
	exit();
}

if(isset($oper) && $oper=='add'){


	session_start();
	$instancia = $_SESSION["accelog_nombre_instancia"];

	if($instancia=='d5studio'){
		$SQL="SELECT count(*) as totalconstru FROM constru_contratista WHERE borrado=0";
		$result = $mysqli->query($SQL);
		$row = $result->fetch_array();
		$totalconstru= $row['totalconstru'];

		if($totalconstru>0){
			echo 'NOMAS';
			exit();
		}
	}

	$nombre = $_POST['nombre'];
	$domicilio = $_POST['domicilio'];
	$colonia = $_POST['colonia'];
	$estado = $_POST['estado'];
	$rfc = $_POST['rfc'];
	//$padron = $_POST['padron'];
	$ciudad = $_POST['ciudad'];
	$telefono = $_POST['telefono'];
	//$cnic = $_POST['cnic'];
	$imss = $_POST['imss'];

	$mysqli->query("INSERT INTO constru_contratista (nombre,domicilio,colonia,estado,rfc,ciudad,telefono,imss) VALUES ('$nombre','$domicilio','$colonia','$estado','$rfc','$ciudad','$telefono','$imss');");
	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
	$domicilio = $_POST['domicilio'];
	$colonia = $_POST['colonia'];
	$estado = $_POST['estado'];
	$rfc = $_POST['rfc'];
	//$padron = $_POST['padron'];
	$ciudad = $_POST['ciudad'];
	$telefono = $_POST['telefono'];
	//$cnic = $_POST['cnic'];
	$imss = $_POST['imss'];

	$mysqli->query("UPDATE constru_contratista SET nombre='$nombre', domicilio='$domicilio', colonia='$colonia', estado='$estado', rfc='$rfc', ciudad='$ciudad', telefono='$telefono', imss='$imss' WHERE id='$id';");
	exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_contratista WHERE borrado=0;";
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


if($_SESSION['accelog_idempleado']*1>=49){
	$nfiltro=' AND a.id>4 ';
}else{
	$nfiltro='  ';	
}


$SQL="SELECT a.* FROM constru_contratista a WHERE 1=1 ".$cad." AND a.borrado=0 ".$nfiltro." ORDER BY $sidx $sord LIMIT $start , $limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row[id];
    $responce->rows[$i]['cell']=array($row[id],$row[nombre],$row[domicilio],$row[colonia],$row[estado],$row[rfc],$row[ciudad],$row[telefono],$row[imss]);
    $i++;
}        
echo json_encode($responce);
?>