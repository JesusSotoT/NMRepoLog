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

$oper = $_POST['oper'];
$id_partida = 0;
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

$SQL = "SELECT concat('RT-',d.id,' - ',d.nombre,' ',d.paterno,' ',d.materno) as Solicito, a.id reqis, 
CASE a.estatus 
WHEN 1 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"cancelReq(',a.id,');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizaReq(',a.id,');\" >')
WHEN 2 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<font color=\"#ff0000\">Requisicion Cancelada</font>')
WHEN 3 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<font color=\"#070\">Requisicion Autorizada</font>')
END as Requisicion,
-- concat('REQ-',a.id) as Requisicion, 
a.fecha_entrega, c.clave, c.descripcion, c.unidtext,  b.cantidad Rcantidad, c.precio, b.cantidad*c.precio importe, a.id reqid, b.id reqsid, c.id insuid,
CASE a.estatus 
WHEN 1 THEN 'Pendiente'
WHEN 2 THEN 'Cancelada'
WHEN 3 THEN 'Autorizada'
END as estatus
FROM constru_requis a
LEFT JOIN constru_requisiciones b on b.id_requi=a.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
LEFT JOIN constru_especialidad es on es.id=a.id_area
left JOIN constru_info_tdo d on d.id_alta=a.solicito
WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b.borrado=0  ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $responce->rows[$i]['requis']=$row['requis'];
    $responce->rows[$i]['cell']=array($row['Requisicion'],$row['Solicito'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['Rcantidad'],$row['precio'],$row['importe'],$row['estatus']);
    $i++;
}        
echo json_encode($responce);
?>