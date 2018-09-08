<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
 
date_default_timezone_set('America/Mexico_City');
 
include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

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




$SQL="(SELECT count(*) as count
FROM constru_bit_remesas a
left join constru_remesas b on b.id_bit_remesas=a.id
left join constru_bit_remesa c on c.id=a.id_bit_remesa 
WHERE b.imp_sem>0 and a.id_obra='$id_obra' AND c.estatus='2')";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$count = $row['total'];

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


 $SQL="(SELECT b.id as iidd,time(a.fecha) as tsol,date(a.fecha) as fsol, concat('ESTREM-',b.id_esti) estimacion, b.imp_sem as total, 
concat('<input value=\"',0,'\" id=\"',c.id,'\" class=\"quis__\" est=\"ESTREM\" name=\"',c.id,'\">') cant,TIMEDIFF(a.fechaaut, a.fecha) tiempo,time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,
CASE b.proviene
when 'ESTDEST' then 'Maestros'
when 'ESTPROV' then 'Proveedor'
when 'ESTSUB' then 'Subcontratistas'
when 'ESTIND' then 'Indirectos'
when 'ESTCAJA' then 'Caja Chica'
when 'ESTNOMC' then 'Tecnicos'
when 'ESTNOMOC' then 'Tecnicos'
end as cat,
CASE b.proviene
when 'ESTDEST' then concat(e.nombre,' ',e.paterno,' ',e.materno)
when 'ESTNOMC' then concat('Nomina Oficina central - EST-',b.id_esti)
when 'ESTNOMOC' then concat('Nomina Oficina campo - EST-',b.id_esti)
when 'ESTCAJA' then concat('Caja Chica - EST-',b.id_esti)
ELSE d.razon_social_sp
end as proveedor,
CASE b.proviene

when 'ESTDEST' then 1
when 'ESTPROV' then 2
when 'ESTSUB' then 3
when 'ESTIND' then 4
when 'ESTNOMC' then 5
when 'ESTNOMOC' then 5
when 'ESTCAJA' then 6
end as orden
FROM constru_bit_remesas a
left join constru_remesas b on b.id_bit_remesas=a.id
left join constru_bit_remesa c on c.id=a.id_bit_remesa 
left join accelog_usuarios u on u.idempleado=a.id_solicito
left join accelog_usuarios u2 on u2.idempleado=a.idaut
left join constru_info_sp d on d.id_alta=b.id_prov
left join constru_info_tdo e on e.id_alta=b.id_prov
WHERE b.imp_sem>0 and a.id_obra='$id_obra' AND c.estatus='2'
order by orden,proveedor, b.id);";

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


    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $i=0;

while($row = $result->fetch_array()){ 
       if ($row['cat']==null)continue;
        $responce->rows[$i]['id']=$i;
        $responce->rows[$i]['cell']=array($row['tiempo'],$row['total'],$row['proveedor'],$row['aut'],$row['faut'],$row['taut'],$row['sol'],$row['fsol'],$row['tsol'],$row['proveedor'],$row['cat'],$row['estimacion']);
        $i++;
    }
  
    
echo json_encode($responce);
?>