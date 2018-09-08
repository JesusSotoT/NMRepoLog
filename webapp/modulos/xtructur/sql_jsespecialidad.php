<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

$oper = $_POST['oper'];

$id_agrupador = $_GET['id_agrupador'];
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
  $mysqli->query("UPDATE constru_especialidad SET borrado=1 WHERE id in ($id);");
  $mysqli->query("UPDATE constru_area SET borrado=1 WHERE id_especialidad in ($id);");
  exit();
}

if(isset($oper) && $oper=='edit'){

  $id = $_POST['id'];
  $nombre = $_POST['nombre'];
  $mysqli->query("UPDATE constru_especialidad SET nombre='$nombre' WHERE id='$id';");
  exit();


}

if(isset($oper) && $oper=='add'){
  $id = $_POST['id'];
  $nombre = $_POST['nombre'];

  $mysqli->query("INSERT INTO constru_especialidad (id_agrupador, nombre, codigo) VALUES ('$id','$nombre','$codigo');");
  $last_id = $mysqli->insert_id;
  $codigo='AR-'.$last_id;
  $mysqli->query("UPDATE constru_especialidad SET codigo='$codigo' WHERE id='$last_id';");

/*
  $mysqli->query("INSERT INTO constru_area (id_especialidad, nombre, codigo,id_cat_especialidad) VALUES ('$last_id','$nombre','$codigo',1);");
  $lld = $mysqli->insert_id;
  $codigo='ESP-'.$lld;
  $mysqli->query("UPDATE constru_area SET codigo='$codigo' WHERE id='$lld';");

  $mysqli->query("INSERT INTO constru_area (id_especialidad, nombre, codigo,id_cat_especialidad) VALUES ('$last_id','$nombre','$codigo',2);");
  $lld = $mysqli->insert_id;
  $codigo='ESP-'.$lld;
  $mysqli->query("UPDATE constru_area SET codigo='$codigo' WHERE id='$lld';");
*/
  exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_especialidad WHERE id_agrupador='$id_agrupador' AND borrado=0;";
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

$SQL = "SELECT a.*, sum(d.unidad*d.precio_costo) as total_costo, sum(d.unidad*d.precio_venta) as total_venta FROM constru_especialidad a LEFT JOIN constru_area b on b.id_especialidad=a.id LEFT JOIN constru_partida c on c.id_area=b.id LEFT JOIN constru_recurso d on d.id_partida=c.id WHERE 1=1 ".$cad." AND a.id_agrupador='$id_agrupador' AND a.borrado=0 GROUP BY a.id ORDER BY $sidx $sord LIMIT $start , $limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array("",$row['id'],$row['codigo'],$row['nombre'],$row['total_venta']);
    $i++;
}        
echo json_encode($responce);
?>