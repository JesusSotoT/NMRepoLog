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




$SQL="SELECT sum(count) as total from((
select count(*) as count from constru_estimaciones_bit_chica
WHERE estatus=1 AND id_obra='$id_obra' AND borrado=0)
union
(select count(*) as count from constru_estimaciones_bit_cliente
WHERE estatus=1 AND id_obra='$id_obra' AND borrado=0)
union
(select count(*) as count from constru_estimaciones_bit_destajista
WHERE estatus=1 AND id_obra='$id_obra' AND borrado=0)
union
(select count(*) as count from constru_estimaciones_bit_indirectos
WHERE estatus=1 AND id_obra='$id_obra' AND borrado=0)
union
(select count(*) as count from constru_estimaciones_bit_prov
WHERE estatus=1 AND id_obra='$id_obra' AND borrado=0)
union
(select count(*) as count from constru_estimaciones_bit_subcontratista
WHERE estatus=1 AND id_obra='$id_obra' AND borrado=0)) as t;";
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


$SQL="(SELECT b.id_alta, a.id as iidd, a.id_destajista,time(a.fecha) as tsol,date(a.fecha) as fsol, concat(b.nombre,' ',b.paterno,' ',b.materno) Proveedor, concat('ESTDES-',a.id) estimacion, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_destajista,'\" class=\"quis__\" est=\"ESTDES\" name=\"',a.id,'\">') cant,TIMEDIFF(a.fechaaut, a.fecha) tiempo,time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,'Maestros' as cat, 1 as TMP_ORDER
FROM constru_estimaciones_bit_destajista a
left join constru_info_tdo b on b.id_alta=a.id_destajista
left join accelog_usuarios u on u.idempleado=a.id_autorizo
left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
WHERE a.id_obra='$id_obra' AND estatus='1'
order by a.id)
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_prov, time(a.fecha) as tsol,date(a.fecha) as fsol, concat(b.razon_social_sp) Proveedor, concat('ESTPROV-',a.id) estimacion, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" est=\"ESTPROV\" name=\"',a.id,'\">') cant,TIMEDIFF(a.fechaaut, a.fecha) tiempo,time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,'Proveedores' as cat, 2 as TMP_ORDER
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
left join accelog_usuarios u on u.idempleado=a.id_autorizo
left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
WHERE a.id_obra='$id_obra' AND estatus='1'
order by a.id)
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_prov, time(a.fecha) as tsol,date(a.fecha) as fsol, concat(b.razon_social_sp) Proveedor, concat('ESTIND-',a.id) estimacion, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" est=\"ESTIND\" name=\"',a.id,'\">') cant,TIMEDIFF(a.fechaaut, a.fecha) tiempo, time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,'Indirectos' as cat,3 as TMP_ORDER
FROM constru_estimaciones_bit_indirectos a
left join constru_info_sp b on b.id_alta=a.id_prov
left join accelog_usuarios u on u.idempleado=a.id_autorizo
left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id_prov>0
order by a.id)
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_subcontratista, time(a.fecha) as tsol,date(a.fecha) as fsol, concat(b.razon_social_sp) Proveedor, concat('ESTSUB-',a.id) estimacion, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_subcontratista,'\" class=\"quis__\" est=\"ESTSUB\" name=\"',a.id,'\">') cant,TIMEDIFF(a.fechaaut, a.fecha) tiempo, time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,'Subcontratistas' as cat,4 as TMP_ORDER
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
left join accelog_usuarios u on u.idempleado=a.id_autorizo
left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
WHERE a.id_obra='$id_obra' AND estatus='1')
UNION ALL
(SELECT 1,a.id as iidd, a.id, time(a.fecha) as tsol,date(a.fecha) as fsol, concat('CAJA-CHICA') Proveedor, concat('ESTCAJA-',a.id) estimacion, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id,'\" class=\"quis__\" est=\"ESTCAJA\" name=\"',a.id,'\">') cant,TIMEDIFF(a.fechaaut, a.fecha) tiempo, time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,'Caja Chica' as cat,5 as TMP_ORDER
FROM constru_estimaciones_bit_chica a
left join accelog_usuarios u on u.idempleado=a.id_autorizo
left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
WHERE a.id_obra='$id_obra' AND estatus='1')
UNION ALL
(SELECT 1,a.id as iidd, a.id, time(a.fecha) as tsol,date(a.fecha) as fsol, concat('CLIENTES') Proveedor, concat('ESTCLI-',a.id) estimacion, a.imp_estimacion as total, 
concat('<input value=\"',0,'\" id=\"',a.id,'\" class=\"quis__\" est=\"ESTCLI\" name=\"',a.id,'\">') cant,TIMEDIFF(a.fechaaut, a.fecha) tiempo, time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,'Cliente' as cat,6 as TMP_ORDER
FROM constru_estimaciones_bit_cliente a
left join accelog_usuarios u on u.idempleado=a.id_autorizo
left join accelog_usuarios u2 on u2.idempleado=a.id_autorizo2
WHERE a.id_obra='$id_obra' AND estatus='1');";

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
        $responce->rows[$i]['id']=$i;
        $responce->rows[$i]['cell']=array($row['tiempo'],$row['total'],$row['Proveedor'],$row['aut'],$row['faut'],$row['taut'],$row['sol'],$row['fsol'],$row['tsol'],$row['Proveedor'],$row['cat'],$row['iidd']);
        $i++;
    }
  
    
echo json_encode($responce);
?>