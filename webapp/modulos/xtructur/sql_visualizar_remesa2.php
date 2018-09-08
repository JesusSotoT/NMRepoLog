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
$sema = $_GET['sema'];
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

$SQL = "SELECT COUNT(*) AS count FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
left JOIN constru_info_tdo d on d.id_alta=a.solicito
left join constru_entrada_almacen f on f.id_oc=a.id and f.id_insumo=b.id_clave  AND f.id_req=c1.id
WHERE a.estatus=3 AND a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0;";
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

$SQL = "SELECT a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTPRO-',a.id) estimacion, a.factura, a.total, concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant, c.imp_sem
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
left join constru_remesas c on c.semana='$sema' AND c.id_esti=a.id
WHERE a.id_obra='$id_obra' AND estatus='1'
UNION ALL
SELECT a.id as iidd, a.id_subcontratista, a.fecha, concat('SUBC-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTSUB-',a.id) estimacion, a.factura, a.total, concat('<input value=\"',0,'\" id=\"',a.id_subcontratista,'\" class=\"quis__\" name=\"',a.id,'\">') cant, c.imp_sem
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
left join constru_remesas c on c.semana='$sema' AND c.id_esti=a.id
WHERE a.id_obra='$id_obra' AND estatus='1'
;";

$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $SQL ="SELECT if(sum(a.imp_sem) is null,0,sum(a.imp_sem)) as imp_sem from constru_remesas a 
     where a.id_obra='$id_obra' AND a.id_prov='".$row['id_prov']."' AND a.id_esti='".$row['iidd']."';";
    $result2 = $mysqli->query($SQL);
    $row2 = $result2->fetch_array();
    $sumap=$row2['imp_sem'];
    $sp=$row['total']-($sumap*1);

    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $responce->rows[$i]['iidd']=$row['iidd'];
    $responce->rows[$i]['cell']=array($row['Proveedor'],substr($row['fecha'],0,10),$row['estimacion'],$row['factura'],$row['total'],$sumap,$sp,$row['imp_sem']);
    $i++;
}        
echo json_encode($responce);
?>