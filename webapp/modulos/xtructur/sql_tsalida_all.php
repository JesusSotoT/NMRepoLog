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

session_start();
$idusr = $_SESSION['accelog_idempleado'];

$oper = $_POST['oper'];
$id_partida = 0;
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];


$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = 'a.id desc'; // get the direction
if(!$sidx) $sidx =1;
// connect to the database

$mysqli->query("SET NAMES utf8");

$SQL = "SELECT count(*) as count
from constru_bit_solicitudes a 
left join constru_recurso b on b.id_bit_solicitud=a.id
where a.id_obra='$id_obra';";
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


$SQL = "SELECT b.*,a.id_solicito,a.fecha_solicito,a.justificacion_os,c.precio,c.unidtext,c.descripcion,c.clave,c.precio*b.cantidad as importe,
CASE a.estatus
WHEN 0 THEN concat('Salidas Traspaso-',a.id ,' Obra ',d.obra,' Fecha: ',a.fecha_solicito,'  <button type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#myModal\"  data-eid=',a.id, '>Cancelar</button> ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'tsal\',',a.id,',',3,');\" >')
WHEN 2 THEN concat('Salidas Traspaso-',a.id ,' Obra ',d.obra,' Fecha: ',a.fecha_solicito,' <font color=\"#ff0000\">Salida Traspaso Cancelada</font>')
else concat('Salidas Traspaso-',a.id ,' Obra ',d.obra,' Fecha: ',a.fecha_solicito,' <font color=\"#070\">Salida Traspaso Autorizada</font>')
END as Estimacion
from constru_traspasos b
left join constru_generales d on d.id=b.id_obra_sal
left join constru_bit_traspasos a on a.id=b.id_bit_traspaso
left join constru_insumos c on c.id=b.id_clave
where b.id_obra_sal='$id_obra' and a.id_quien_os='$idusr'
 ORDER BY   $sord LIMIT $start,$limit;";


$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['clave'],$row['descripcion'],$row['cantidad'],$row['unidtext'],$row['precio'],$row['importe'],$row['Estimacion']);
    $i++;
}       
echo json_encode($responce);
?>