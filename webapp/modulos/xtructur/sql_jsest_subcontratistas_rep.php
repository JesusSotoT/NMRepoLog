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
    $mysqli->query("UPDATE constru_estimaciones_subcontratista SET borrado=1 WHERE id in ($id);");
    exit();
}

if(isset($oper) && $oper=='add'){
    $id_clave = $_POST['id_clave'];
    $vol_anterior = $_POST['vol_anterior'];
    $SQL="SELECT id FROM constru_estimaciones_subcontratista WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_bit_subcontratista=0 and borrado=0 AND id_insumo='$id_clave';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'RP';
        exit();
    }

    $vol_tope = $_POST['vol_tope'];
    $vol_estimacion = $_POST['vol_estimacion'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $mysqli->query("INSERT INTO constru_estimaciones_subcontratista (id_obra,id_insumo,id_bit_subcontratista,vol_tope,sestmp,vol_estimacion,vol_anterior) VALUES ('$id_obra', '$id_clave',0, '$vol_tope','$sestmp','$vol_estimacion','$vol_anterior');");
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

  $SQL = "SELECT a.*, c.pu_subcontrato, c.codigo, c.descripcion, c.unidtext, d.vol_tope*c.pu_subcontrato as total, d.vol_tope,
 a.vol_estimacion, d.vol_tope*c.pu_subcontrato as total, a.vol_anterior
    from constru_estimaciones_bit_subcontratista b
    inner join constru_estimaciones_subcontratista a on a.id_bit_subcontratista=b.id
    left join constru_recurso c on c.id=a.id_insumo
    left join constru_vol_tope d on d.id_clave=c.id AND (d.id_area=b.id_area or d.id_area=a.id_area) AND d.id_obra='$id_obra'
    WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_subcontratista='$id_des' AND a.borrado=0 AND b.id='$id_des' ORDER BY a.$sidx $sord LIMIT $start,$limit";
/*
 $SQL = "SELECT c.id_destajista, c.id as iid, a.id as ido, b.id as id_insumo, a.vol_est, b.unidtext, b.descripcion, b.pu_destajo, b.codigo, d.vol_tope, d.vol_tope*b.pu_destajo as total, a.vol_anterior
 FROM constru_estimaciones_bit_destajista c
 inner join constru_estimaciones_destajista a on a.id_bit_destajista=c.id
 left join constru_recurso b on b.id=a.id_clave 
left join constru_vol_tope d on d.id_clave=b.id AND d.id_area=a.id_area AND d.id_obra='$id_obra'
 WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_destajista='$id_des' AND a.borrado=0 AND c.id='$id_des' ORDER BY a.$sidx $sord LIMIT $start,$limit";
 */
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    /*
    $acumulado=$vanterior+$row[vol_estimacion];
    $poreje=$row[vol_tope]-$acumulado;
    $impfin=$row[pu_subcontrato]*$row[vol_estimacion]; 
    */
     // $SQL ="SELECT sum(a.vol_estimacion) as vol_anterior from constru_estimaciones_subcontratista a where a.id_bit_subcontratista>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_insumo='".$row['id_insumo']."';";


    //$result2 = $mysqli->query($SQL);
    //$row2 = $result2->fetch_array();
/*

    $vol_anterior = $row2['vol_anterior']-$row['vol_estimacion'];
    $vol_acumulado = $row['vol_anterior']+$row['vol_estimacion'];
    $vol_ejecutar = $row['vol_tope']-$vol_acumulado;
    $imp_est=$row['pu_subcontrato']*$row['vol_estimacion'];
    */

    $vol_acumulado=$row['vol_anterior']+$row['vol_estimacion'];
    $vol_ejecutar=$row['vol_tope']-$vol_acumulado;
    $imp_est=$row['pu_subcontrato']*$row['vol_estimacion'];

    
    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['codigo'],$row['descripcion'],$row['unidtext'],$row['vol_tope'],$row['pu_subcontrato'],$row['total'],$row['vol_anterior'],$row['vol_estimacion'],$vol_acumulado,$vol_ejecutar,$imp_est);
    $i++;
}        

echo json_encode($responce);
?>