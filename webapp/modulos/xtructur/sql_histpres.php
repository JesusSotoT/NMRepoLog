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
FROM constru_bit_solicitudes a
WHERE a.id_obra='$id_obra')";
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


 $SQL="select timediff(fechaaut,fecha) as tiempo,total,u2.usuario as autorizo,date(fechaaut) as autf,time(fechaaut) as auth,u.usuario as solicito,date(fecha) as solf,time(fecha) as solh,naturaleza from constru_bit_solicitudes 
left join accelog_usuarios u on u.idempleado=id_solicito
left join accelog_usuarios u2 on u2.idempleado=id_aut
where id_obra='$id_obra' and estatus=1 order by naturaleza";

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
        $responce->rows[$i]['cell']=array($row['tiempo'],$row['total'],$row['autorizo'],$row['autf'],$row['auth'],$row['solicito'],$row['solf'],$row['solh'],$row['naturaleza']);
        $i++;
    }
  
    
echo json_encode($responce);
?>