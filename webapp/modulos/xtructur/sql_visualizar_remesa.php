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





$SQL = "(SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTPROV-',a.id,' OC-' ,a.id_oc) estimacion, concat('<input  class=\"fac\" value=\"',a.factura,'\"/>') factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" est=\"ESTPROV\" name=\"',a.id,'\"/>') cant, 6 as TMP_ORDER,
c.fpago as fpago, 'ESTPROV' as proviene
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
left join constru_pedis c on c.id=a.id_oc
WHERE a.id_obra='$id_obra' AND a.estatus='1'
order by a.id)
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('INDI-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTIND-',a.id) estimacion, concat('<input class=\"fac\" value=\"',a.factura,'\"/>') factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" est=\"ESTIND\" name=\"',a.id,'\"/>') cant, 7 as TMP_ORDER
, 0 as fpago,  'ESTIND' as proviene
FROM constru_estimaciones_bit_indirectos a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id_prov>0
order by a.id)
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_subcontratista, a.fecha, concat('SUBC-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTSUB-',a.id) estimacion, concat('<input  class=\"fac\" value=\"',a.factura,'\"/>')  factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_subcontratista,'\" class=\"quis__\" est=\"ESTSUB\" name=\"',a.id,'\"/>') cant, 5 as TMP_ORDER
, 0 as fpago, 'ESTSUB' as proviene
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
WHERE a.id_obra='$id_obra' AND estatus='1')
UNION ALL
(SELECT 1,a.id as iidd, a.id, a.fecha, concat('CAJA-CHICA') Proveedor, concat('ESTCAJA-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id,'\" class=\"quis__\" est=\"ESTCAJA\" class=\"',a.id,'\"/>') cant, 8 as TMP_ORDER
, 0 as fpago, 'ESTCAJA' as proviene
FROM constru_estimaciones_bit_chica a
-- LEFT JOIN constru_estimaciones_chica b on b.id_bit_chica=a.id
WHERE a.id_obra='$id_obra' AND estatus='1')
UNION ALL
(SELECT a.id_dest as id_alta, a.id as iidd, a.id_dest, a.fecha, concat(b.nombre,' - ',b.paterno,' - ',b.materno) Proveedor, concat('ESTDEST-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_dest,'\" class=\"quis__\"  est=\"ESTDEST\" name=\"',a.id,'\"/>') cant, 0 as TMP_ORDER
, 0 as fpago, 'ESTDEST' as proviene
FROM constru_bit_nominadest a
left join constru_info_tdo b on b.id_alta=a.id_dest
WHERE a.id_obra='$id_obra' AND estatus='1')
UNION ALL
(SELECT 1, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CENTRAL') Proveedor, concat('ESTNOMC-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" est=\"ESTNOMC\" name=\"',a.id,'\"/>') cant, 2 as TMP_ORDER
, 0 as fpago, 'ESTNOMC' as proviene
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=1)
UNION ALL
(SELECT 1, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CAMPO') Proveedor, concat('ESTNOMOC-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" est=\"ESTNOMOC\" name=\"',a.id,'\"/>') cant, 3 as TMP_ORDER
, 0 as fpago, 'ESTNOMOC' as proviene
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=2)
ORDER BY TMP_ORDER, proveedor, 2 desc;";

/*
/* ,
CASE WHEN TMP_ORDER = 0 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 1 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 2 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 4 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 5 THEN id_alta ELSE 0 END ASC, iidd;
*/

$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
//$responce->userdata = array('Importe'=>600);

$prisum=0;
$secsum=0;
$tersum=0;
$i=0;

while($row = $result->fetch_array()) {
    $result2 = $mysqli->query('SELECT * FROM forma_pago;');
$selop='<option value="0">Seleccione</option>';
while($row2 = $result2->fetch_array()) {
    if($row['fpago']==$row2['idFormapago']){
     $selop.='<option selected="selected" value="'.$row2['idFormapago'].'">'.$row2['nombre'].'</option>';
       }
       else{
$selop.='<option value="'.$row2['idFormapago'].'">'.$row2['nombre'].'</option>';
       }
}

    $adjunselop='<select class="selopp_5" style="width:100px;">'.$selop.'</select>';


    $SQL ="SELECT if(sum(a.imp_sem) is null,0,sum(a.imp_sem)) as imp_sem from constru_remesas a 
    inner join constru_bit_remesas b on b.id=id_bit_remesas
    inner join constru_bit_remesa c on c.id=b.id_bit_remesa
     where a.id_obra='$id_obra' AND a.id_prov='".$row['id_prov']."' AND a.proviene='".$row['proviene']."' AND c.estatus=2 AND a.id_esti='".$row['iidd']."';";
    $result2 = $mysqli->query($SQL);
    $row2 = $result2->fetch_array();
    $sumap=$row2['imp_sem'];
    $sp=$row['total']-($sumap*1);
    $prisum+=$row['total'];
    $secsum+=$sumap;
    $tersum+=$sp;
    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    if($sp>0){
        $responce->rows[$i]['iidd']=$i;
        $responce->rows[$i]['cell']=array($row['Proveedor'],substr($row['fecha'],0,10),$row['estimacion'],$row['factura'],$row['total'],$sumap,$sp,$row['cant'],$adjunselop);
        $i++;
    }
}  
$responce->userdata = array('Importe'=>$prisum, 'Pagado_en_emesas'=>$secsum, 'Saldo_por_pagar'=>$tersum);      
echo json_encode($responce);
?>