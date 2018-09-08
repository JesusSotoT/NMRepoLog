<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
  exit();
}else{
  $cookie_xtructur = unserialize($_COOKIE['xtructur']);
  $id_obra = $cookie_xtructur['id_obra'];
}

$oper = $_POST['oper'];

$id_des = $_GET['id_des'];
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
$mysqli->query("set names 'utf8'");

if(isset($oper) && $oper=='del'){
    $id = $_POST['id'];
    $mysqli->query("UPDATE constru_estimaciones_destajista SET borrado=1 WHERE id in ($id);");
    exit();
}

if(isset($oper) && $oper=='add'){
    $id_clave = $_POST['id_clave'];
    $SQL="SELECT id FROM constru_estimaciones_destajista WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_bit_destajista=0 and borrado=0 AND id_insumo='$id_clave';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'RP';
        exit();
    }

    $vol_tope = $_POST['vol_tope'];
    $vol_estimacion = $_POST['vol_estimacion'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $mysqli->query("INSERT INTO constru_estimaciones_destajista (id_obra,id_insumo,id_bit_destajista,vol_tope,sestmp,vol_estimacion) VALUES ('$id_obra', '$id_clave',0, '$vol_tope','$sestmp','$vol_estimacion');");
    exit();
}

if(isset($oper) && $oper=='edit'){
    $id = $_POST['id'];
    $id_clave = $_POST['id_clave'];

    $cantidad = $_POST['cantidad'];

    $fecha_entrega = $_POST['fecha_entrega'];

    $mysqli->query("UPDATE constru_requisiciones SET id_clave='$id_clave', cantidad='$cantidad', fecha_entrega='$fecha_entrega' WHERE id='$id';");
    exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_requisiciones WHERE id_obra='$id_obra' AND sestmp>0 AND id_requi=0 and borrado=0;";
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


 $SQL = "SELECT c.id_destajista, c.id as iid, a.id as ido, b.id as id_insumo, a.vol_est, b.unidtext, b.descripcion, b.pu_destajo, b.codigo, d.vol_tope, d.vol_tope*b.pu_destajo as total, a.vol_anterior
 FROM constru_estimaciones_bit_destajista c
 inner join constru_estimaciones_destajista a on a.id_bit_destajista=c.id
 left join constru_recurso b on b.id=a.id_clave 
left join constru_vol_tope d on d.id_clave=b.id AND (d.id_area=c.id_area or d.id_area=a.id_area) AND d.id_obra='$id_obra'
 WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_destajista='$id_des' AND a.borrado=0 AND c.id='$id_des' ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    $acumulado=$row['vol_anterior']+$row['vol_est'];
    $poreje=$row['vol_tope']-$acumulado;
    $impfin=$row['pu_destajo']*$row['vol_est'];
    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['codigo'],$row['descripcion'],$row['unidtext'],$row['vol_tope'],$row['pu_destajo'],$row['total'],$row['vol_anterior'],$row['vol_est'],$acumulado,$poreje,$impfin);
    $i++;
}        

echo json_encode($responce);
?>