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
select count(*) as count from constru_bit_nominaca
WHERE estatus=1 AND id_obra='$id_obra' AND borrado=0)
union
(select count(*) as count from constru_bit_nominadest
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


$SQL="(SELECT a.id_dest as id_alta, a.id as iidd, a.id_dest, time(a.fecha) as tsol,date(a.fecha) as fsol, concat(b.nombre,' - ',b.paterno,' - ',b.materno) Proveedor, concat('Nomina-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_dest,'\" class=\"quis__\"  est=\"Nomina-\" name=\"',a.id,'\">') cant,TIMEDIFF(a.fechaaut, a.fecha) tiempo,time(a.fechaaut) as taut,date(a.fechaaut) as faut ,u.usuario as sol,u2.usuario as aut,'Maestros' as cat,0 as TMP_ORDER
FROM constru_bit_nominadest a
left join constru_info_tdo b on b.id_alta=a.id_dest
left join accelog_usuarios u on u.idempleado=a.id_aut
left join accelog_usuarios u2 on u2.idempleado=a.id_aut2
WHERE a.id_obra='$id_obra' AND estatus='1')
UNION ALL
(SELECT 1, a.id as iidd, a.id_tecnico, time(a.fecha) as tsol,date(a.fecha) as fsol, concat('Oficina Central') Proveedor, concat('Nomina-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" est=\"Nomina-\" name=\"',a.id,'\">') cant,timediff(a.fechaaut , a.fecha)  tiempo,time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,'Técnicos-Adminsitrativos' as cat,2 as TMP_ORDER
FROM constru_bit_nominaca a
left join accelog_usuarios u on u.idempleado=a.id_aut
left join accelog_usuarios u2 on u2.idempleado=a.id_aut2
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=1)
UNION ALL
(SELECT 1, a.id as iidd, a.id_tecnico, time(a.fecha) as tsol,date(a.fecha) as fsol, concat('Oficina Campo') Proveedor, concat('Nomina-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" est=\"Nomina-\" name=\"',a.id,'\">') cant,timediff(a.fechaaut , a.fecha)  tiempo,time(a.fechaaut) as taut,date(a.fechaaut) as faut,u.usuario as sol,u2.usuario as aut,'Técnicos-Adminsitrativos' as cat,3 as TMP_ORDER
FROM constru_bit_nominaca a
left join accelog_usuarios u on u.idempleado=a.id_aut
left join accelog_usuarios u2 on u2.idempleado=a.id_aut2
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=2)
ORDER BY TMP_ORDER, proveedor, estimacion desc;";

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
        $responce->rows[$i]['cell']=array($row['tiempo'],$row['total'],$row['Proveedor'],$row['aut'],$row['faut'],$row['taut'],$row['sol'],$row['fsol'],$row['tsol'],$row['estimacion'],$row['Proveedor'],$row['cat'],$row['iidd']);
        $i++;
    }
  
    
echo json_encode($responce);
?>