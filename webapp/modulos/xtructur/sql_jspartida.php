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
$id_area = $_GET['id_area'];
$id_esp = $_GET['id_esp'];
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
  $mysqli->query("UPDATE constru_partida SET borrado=1 WHERE id in ($id);");
  $mysqli->query("DELETE FROM constru_asignaciones WHERE id_partida in ($id) AND id_area='$id_esp' AND id_obra='$id_obra';");
  exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$nombre = $_POST['nombre'];
  if(preg_match('/^.nv0./', $nombre)){
    $nombre = preg_replace("/^.nv0./", "", $nombre);
    $result = $mysqli->query("SELECT id FROM constru_cat_partidas WHERE partida='$nombre' AND borrado=0 limit 1;");
    if($result->num_rows>0){
      echo '&nbsp; Esta nombre de partida ya existe';
      exit();
    }else{
      $mysqli->query("INSERT INTO constru_cat_partidas (partida) VALUES ('$nombre');");
      $id_partida = $mysqli->insert_id;
      $mysqli->query("UPDATE constru_partida SET nombre='$id_partida', id_cat_partida='$id_partida' WHERE id='$id';");
      exit();
    }
      
  }else{
    $result = $mysqli->query("SELECT id FROM constru_partida WHERE id_area='$id_area' AND id_cat_partida='$nombre' AND borrado=0;");
    if($result->num_rows>0){
      echo '&nbsp; Esta partida ya fue asignada con anterioridad ';
      exit();
    }else{
      $mysqli->query("UPDATE constru_partida SET nombre='$nombre', id_cat_partida='$nombre' WHERE id='$id';");
    exit();
    }
  }
	
}

if(isset($oper) && $oper=='add'){
  $id = $_POST['id'];
  $nombre = $_POST['nombre'];
  if(preg_match('/^.nv0./', $nombre)){
    $nombre = preg_replace("/^.nv0./", "", $nombre);
    $result = $mysqli->query("SELECT id FROM constru_cat_partidas WHERE partida='$nombre' AND borrado=0 limit 1;");
    if($result->num_rows>0){
      $row = $result->fetch_array();
      $id_partida=$row['id'];
      $result = $mysqli->query("SELECT id FROM constru_partida WHERE id_area='$id' AND id_cat_partida='$id_partida' AND borrado=0;");
      if($result->num_rows>0){
        echo '&nbsp; Esta partida ya fue asignada con anterioridad ';
        exit();
      }
    }else{
      $mysqli->query("INSERT INTO constru_cat_partidas (partida) VALUES ('$nombre');");
      $id_partida = $mysqli->insert_id;
      $result = $mysqli->query("SELECT id FROM constru_partida WHERE id_area='$id' AND id_cat_partida='$id_partida' AND borrado=0;");
      if($result->num_rows>0){
        echo '&nbsp; Esta partida ya fue asignada con anterioridad ';
        exit();
      }
    }
      $mysqli->query("INSERT INTO constru_partida (id_area, nombre, codigo, id_cat_partida, id_obra) VALUES ('$id','$id_partida','$codigo','$id_partida','$id_obra');");
      $last_id = $mysqli->insert_id;
      $codigo='PRT-'.$last_id;
      $mysqli->query("UPDATE constru_partida SET codigo='$codigo' WHERE id='$last_id';");
      exit();
  }else{
    $result = $mysqli->query("SELECT id FROM constru_partida WHERE id_area='$id' AND id_cat_partida='$nombre' AND borrado=0;");
    if($result->num_rows>0){
      echo '&nbsp; Esta partida ya fue asignada con anterioridad ';
      exit();
    }else{
      $mysqli->query("INSERT INTO constru_partida (id_area, nombre, codigo, id_cat_partida, id_obra) VALUES ('$id','$nombre','$codigo','$nombre','$id_obra');");
      $last_id = $mysqli->insert_id;
      $codigo='PRT-'.$last_id;
      $mysqli->query("UPDATE constru_partida SET codigo='$codigo' WHERE id='$last_id';");
      exit();
    }
  }
  	
	exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_partida WHERE id_area='$id_area' AND borrado=0;";
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

$SQL="SELECT a.*, sum(b.unidad*b.precio_costo) as total_costo, sum(b.unidad*b.precio_venta) as total_venta, c.partida FROM constru_partida a LEFT JOIN constru_recurso b on b.id_partida=a.id LEFT JOIN constru_cat_partidas c on c.id=a.id_cat_partida WHERE 1=1 ".$cad." AND a.id_area='$id_area' AND a.borrado=0 GROUP BY a.id ORDER BY $sidx $sord LIMIT $start , $limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array("",$row['id'],$row['codigo'],$row['partida'],$row['total_venta']);
    $i++;
}        
echo json_encode($responce);
?>