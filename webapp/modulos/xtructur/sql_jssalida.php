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

$SQL = "SELECT COUNT(*) AS count FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
left JOIN constru_info_tdo d on d.id_alta=a.solicito
left join constru_entrada_almacen f on f.id_oc=a.id and f.id_insumo=b.id_clave  AND f.id_req=c1.id
left join constru_salida_almacen g on g.id_oc=a.id and g.id_insumo=b.id_clave  AND g.id_req=c1.id
WHERE a.estatus=3 AND a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 ;";
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


$SQL = "SELECT
a.id, a.id as ocid, c1.id as rid,
CASE a.salida_alm 
WHEN 0 THEN concat('<input type=\"checkbox\" value=\"',a.id,'\" class=\"ccbox\" style=\"cursor:pointer;\" id=\"',a.id,'\">')
WHEN 1 THEN 'Salidas agotadas'
WHEN 2 THEN 'Autorizada'
END as entrada_alm,
CASE a.estatus 
WHEN 1 THEN concat('OC-',a.id,' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"cancelOC(',a.id,');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizaOC(',a.id,');\" >')
WHEN 2 THEN concat('OC-',a.id,' ','<font color=\"#ff0000\">Orden Cancelada</font>')
WHEN 3 THEN concat('OC-',a.id,' ')
END as Orden, concat('Usuario',' - ',d.nombre,' ',d.apellidos) as Solicito, a.id pedis, 
-- concat('REQ-',a.id) as Requisicion, 
concat('REQ-',c1.id,' / Area: ',es.nombre) Requisicion,
concat('Usuario',' - ',d.nombre,' ',d.apellidos) as Solicito_Req,
a.fecha_entrega, c.clave, c.descripcion, c.unidtext,  b.cantidad Rcantidad, c.precio, b.cantidad*c.precio importe, a.id pedid, b.id pedsid, c.id insuid,
CASE a.estatus 
WHEN 1 THEN 'Pendiente'
WHEN 2 THEN 'Cancelada'
WHEN 3 THEN 'Autorizada'
END as estatus,
if(SUM(f.llego) is null,0,SUM( f.llego )) llegoc,
if(b.elprov is null,a.id_prov,b.elprov) as prreal, a.id_prov as prrep
FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id AND b.borrado=0
LEFT JOIN constru_insumos c on c.id=b.id_clave
left JOIN administracion_usuarios d on d.idempleado=a.solicito
left join constru_entrada_almacen f on f.id_oc=a.id and f.id_insumo=b.id_clave  AND f.id_req=c1.id
LEFT JOIN constru_especialidad es on es.id=c1.id_area
WHERE a.estatus=3 AND a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 group by a.id, c1.id, b.id  ORDER BY a.id desc, b.id, $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL); 

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {

    if($row['prreal']!=$row['prrep']){
        continue;
    }

    $SQL = "SELECT if(SUM(salio) is null,0,SUM(salio)) as csalida FROM constru_salida_almacen 
WHERE id_obra='$id_obra' AND id_oc='".$row['ocid']."' AND id_req='".$row['rid']."' AND id_insumo='".$row['insuid']."';";
$result2 = $mysqli->query($SQL);
$row2 = $result2->fetch_array();
$almacen = $row['llegoc']-$row2['csalida'];
$rcant=$almacen;
$llego='<input value="0" rcant="'.$rcant.'"  id="'.$row['insuid'].'" class="quis_'.$row['ocid'].'_" name="'.$row['rid'].'" >';

    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $responce->rows[$i]['ocid']=$row['ocid'];
    $responce->rows[$i]['cell']=array($row['Orden'].$row['entrada_alm'],$row['Solicito'],$row['Requisicion'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['Rcantidad'],$almacen,$row2['csalida'],$llego);
    $i++;
}        
echo json_encode($responce);
?>