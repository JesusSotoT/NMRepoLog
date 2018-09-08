<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
$ano = date('Y');
$sema = $_GET['sema'];
$sd = $_GET['sd'];
$ed = $_GET['ed'];
$id_des = $_GET['id_des'];
$sem = $_GET['sem'];
$oper = $_POST['oper'];
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;

include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){
    $id = $_POST['id'];
    $mysqli->query("UPDATE constru_tomaduria SET borrado=1 WHERE id in ($id);");
    exit();

}


if(isset($oper) && $oper=='edit'){
    $id = $_POST['id'];
    $hre = $_POST['hre'];
    $diasf = $_POST['diasf'];
    $impdf = $_POST['impdf'];
    $descinf = $_POST['descinf'];
    $fini = $_POST['fini'];
    $difd = $_POST['difd'];
    $imphe = $_POST['importehr'];

    $SQL = "SELECT id FROM constru_tomaduria_tec1 WHERE id_tecnico='$id' AND id_obra='$id_obra' AND per_ini='$sd' AND per_fin='$ed';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        $mysqli->query("UPDATE constru_tomaduria_tec1 SET imphe='$imphe', hre='$hre', impdf='$impdf', descinf='$descinf', fini='$fini' WHERE id_tecnico='$id' AND per_ini='$sd' AND per_fin='$ed' AND id_obra='$id_obra';");
    }

    exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_tomaduria WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$count = 10000;


if( $count >0 ) {
    $total_pages = ceil($count/$limit);
} else {
    $total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)

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

/*
$SQL = "SELECT a.id as idemp, concat('TEC-',a.id,' ',b.nombre,' ',b.paterno) as empleado, c.* FROM constru_altas a 
 LEFT JOIN constru_info_tdo b on b.id_alta=a.id 
 LEFT JOIN constru_tomaduria_tec1 c on c.id_tecnico=a.id AND c.semana='$sema' AND c.ano='$ano'
 WHERE a.id_depto='$id_des' AND a.id_tipo_alta=1 AND a.id_obra='$id_obra' AND a.borrado=0 LIMIT $start , $limit";
 */

/*$SQL = "SELECT count(d.id) as asist, e.sal_mensual, c.per_ini, a.id as idemp, concat('TEC-',a.id,' - ',b.nombre,' ',b.paterno) as
 empleado, c.*, 
(count(d.id)) as diast, 
(e.sal_semanal/6)*(count(d.id)) as importedt, 
    ((e.sal_semanal/6)*(count(d.id)))+c.impdf-c.descinf as sub1, 
    ((e.sal_semanal/6)*(count(d.id)))+c.impdf-c.descinf as totalp 
    FROM constru_altas a  
 LEFT JOIN constru_info_tdo b on b.id_alta=a.id 
 LEFT JOIN constru_tomaduria_tec1 c on c.id_tecnico=a.id AND c.per_ini>='$sd' AND c.per_fin<='$ed'
 LEFT JOIN constru_lista15 d on d.id_tecnico=a.id AND d.fecha between '$sd' AND '$ed' AND d.asistio=1
 LEFT JOIN constru_categoria e on e.id=a.id_categoria
 WHERE a.id_depto='$id_des' AND a.id_tipo_alta=1 AND a.id_obra='$id_obra' AND a.borrado=0 GROUP BY a.id LIMIT $start , $limit";
*/

$SQL = "SELECT count(distinct(d.id) ) as asist, e.sal_mensual, c.per_ini, a.id as idemp, concat('RT-',a.id,' - ',b.nombre,' - ',b.paterno) as empleado, c.*, 
(count(distinct(d.id) )) as diast, 
(e.sal_semanal/6)*(count(distinct(d.id) )) as importedt, 
    ((e.sal_semanal/6)*(count(distinct(d.id) )))+c.impdf-c.descinf as sub1, 
    ((e.sal_semanal/6)*(count(distinct(d.id) )))+c.impdf-c.descinf as totalp, t.per_ini, t.per_fin
FROM constru_bit_nominaca t
LEFT JOIN constru_altas a on a.id_depto=t.id_tecnico AND a.id_tipo_alta=1 AND a.borrado=0 AND a.id_obra='$id_obra'
 LEFT JOIN constru_info_tdo b on b.id_alta=a.id 
 LEFT JOIN constru_tomaduria_tec1 c on c.id_tecnico=a.id AND c.per_ini>=t.per_ini AND c.per_fin<=t.per_fin
  LEFT JOIN constru_lista15 d on d.id_tecnico=a.id AND d.fecha between t.per_ini AND t.per_fin AND d.asistio=1
 LEFT JOIN constru_categoria e on e.id=a.id_categoria
 WHERE t.id='$id_des' AND t.id_obra='$id_obra' AND t.borrado=0 GROUP BY a.id LIMIT $start , $limit";
$result = $mysqli->query($SQL);

$count=$result->num_rows;

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $dt=($row['asist']*1);


    $sal15=($row['sal_mensual']*1)/2;
    $saldiario=$sal15/15;

    $salsal=($dt*1)*($saldiario*1);

    $sub1=$salsal-($row['descinf']*1)+($row['fini']*1)+($row['imphe']*1);

    $responce->rows[$i]['id']=$row['idemp'];
    $responce->rows[$i]['cell']=array($row['id'],$row['per_ini'],$row['per_fin'],$row['empleado'],$row['asist'],$row['hre'],$salsal,$row['imphe'],$row['descinf'],$row['fini'],$sub1,$sub1);
    $i++;
}        
echo json_encode($responce);
?>