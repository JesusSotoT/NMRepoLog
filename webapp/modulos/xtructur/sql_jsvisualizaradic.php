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


$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
if($result->num_rows>0){
	$row = $result->fetch_array();
	$id_presupuesto=$row['id'];
}else{
	$id_presupuesto='';
}
$mysqli->query("set names 'utf8'");

if(isset($oper) && $oper=='del'){

$rid = $_POST['id'];


$SQL="DELETE FROM constru_recurso WHERE id in ('$rid');";
	$mysqli->query("DELETE FROM constru_recurso WHERE id in ('$rid');");
	exit();
}



if(isset($oper) && $oper=='add'){
	$codigo = $_POST['Codigo'];
	$id_clave = $_POST['id_recurso'];
   	$SQL="SELECT codigo FROM constru_recurso WHERE sestemp='$sestmp' and id_obra='$id_obra' and borrado=0 AND codigo='$codigo' and id_bit_solicitud=0;";
	$result = $mysqli->query($SQL);
	if($result->num_rows>0) {
		echo 'RP';
		exit();
	}
	$codigo = $_POST['Codigo'];
	$unidad = $_POST['Cantidad'];
	$descripcion = $_POST['Descripcion'];
	$precio = $_POST['precio'];
	$unidtext = $_POST['Unidad'];
$justificacion = $_POST['Justificacion'];

if($unidad<=0){


echo 'NO0';
		exit();

}

	 $mysqli->query("INSERT INTO constru_recurso (naturaleza,codigo,unidad,descripcion,justificacion,precio_costo,precio_venta,id_presupuesto,unidtext,id_obra,autorizado,id_bit_solicitud,sestemp,id_naturaleza,id_um,id_partida) VALUES ('Adicional', '$codigo','$unidad', '$descripcion','$justificacion' , '$precio','$precio', '$id_presupuesto','$unidtext','$id_obra', 0, 0,'$sestmp',0,1,0);");

	exit();
}

if(isset($oper) && $oper=='edit'){
	$codigo = $_POST['Codigo'];
	$justificacion = $_POST['Justificacion'];
	$unidad = $_POST['Cantidad'];
	$descripcion = $_POST['Descripcion'];
	$precio = $_POST['precio'];
	$unidtext = $_POST['Unidad'];
	$rid = $_POST['id'];
	$mysqli->query("UPDATE constru_recurso SET unidad='$unidad', codigo='$codigo',descripcion='$descripcion',justificacion='$justificacion',precio_venta='$precio', precio_costo='$precio',unidtext='$unidtext' WHERE id='$rid' and sestemp>0 and id_bit_solicitud=0;");
	exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_recurso WHERE id_obra='$id_obra' and naturaleza='Adicional'  and borrado=0;";
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

	$id_recurso = $_POST['id_recurso'];

 $SQL="SELECT a.*, (a.unidad*a.precio_costo) as tc, (a.unidad*a.precio_venta) as tv
FROM constru_recurso a
WHERE a.id_bit_solicitud=0 AND a.naturaleza='Adicional' And a.id_obra='$id_obra' AND a.borrado=0 and a.sestemp='$sestmp' ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);
$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
	$responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['codigo'],$row['id_bit_solicitud'],$row['naturaleza'],$row['descripcion'],$row['unidtext'],$row['precio_costo'],$row['unidad'],$row['justificacion'],$row['tc'],$row['id']);
    $i++;
}        

echo json_encode($responce);
?>