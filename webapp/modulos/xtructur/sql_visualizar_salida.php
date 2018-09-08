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


$SQL = "SELECT concat('OC-',a.id_oc,' Proveedor:',e2.razon_social_sp) as Orden,
concat('REQ-',c.id) as Requisicion, concat('SAL-',a.id,' / ',a.fecha,' ','<input type=\"button\" value=\"Borrar\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#delmodal\"  data-eid=',a.id, '  > ') as Salida,
e.clave, e.descripcion, e.unidtext,  d.cantidad Rcantidad, e.precio,
b.salio, b.id_insumo, a.fecha, d.precio_compra, d.precio_compra*b.salio as importec
from constru_bit_salidas a
inner join constru_salida_almacen b on b.id_bit_salida=a.id AND b.id_oc=a.id_oc
LEFT JOIN constru_requis c  on c.id=b.id_req AND c.id=b.id_req
LEFT JOIN constru_requisiciones d on d.id_requi=c.id AND d.id_clave=b.id_insumo AND d.borrado=0
LEFT JOIN constru_insumos e on e.id=d.id_clave AND e.id=b.id_insumo
LEFT JOIN constru_pedidos g on g.id_requis=c.id
LEFT JOIN constru_pedis f on f.id=g.id_pedid
LEFT JOIN constru_info_sp e2 on e2.id_alta=f.id_prov
WHERE a.borrado=0 AND a.id_obra='$id_obra'  ORDER BY a.id_oc desc, a.id desc, d.id, $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $responce->rows[$i]['Orden']=$row['Orden'];
    $responce->rows[$i]['cell']=array($row['Orden'],$row['Requisicion'],$row['Salida'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['salio'],$row['precio_compra'],$row['importec']);
    $i++;
}        
echo json_encode($responce);
?>