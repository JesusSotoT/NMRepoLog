<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
function week_bounds( $date, &$start, &$end ) {
        $date = strtotime( $date );
        // Find the start of the week, working backwards
        $start = $date;
        while( date( 'w', $start ) > 1 ) {
          $start -= 86400; // One day
        }
        // End of the week is simply 6 days from the start
        $end = date( 'Y-m-d', $start + ( 6 * 86400 ) );
        $start = date( 'Y-m-d', $start );
    }
week_bounds(date('Y-m-d'), $fstart, $fend);
$id_des = $_GET['id_des'];
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
    $importehr = $_POST['importehr'];

    $SQL = "SELECT id FROM constru_tomaduria WHERE id_dest='$id_des' AND id_obra='$id_obra' AND per_ini='$sd' AND per_fin='$ed' AND id_empleado='$id';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        $mysqli->query("UPDATE constru_tomaduria SET hre='$lun', diasf='$mar', impdf='$impdf', descinf='$descinf', fini='$fini', difd='$difd', imphe='$importehr' WHERE id_empleado='$id' AND per_ini='$sd' AND per_fin='$ed' AND id_obra='$id_obra';");
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

$SQL = "SELECT  a.per_ini pi, a.per_fin pf, d.nombre as anom, concat('DEST-',cc.id,' - ',c.nombre,' ',c.paterno,' ',c.materno) as empleado, b.dt as diast, b.he, b.df, b.idt, b.ihe, b.idf, b.desci, b.finis, b.subt, b.difd, b.total, cc.id as idemp, a.id 
FROM constru_bit_nominadest a
LEFT JOIN constru_gen_nomdest b on b.id_bit_nom=a.id
LEFT JOIN constru_info_tdo c on c.id_alta=b.id_emp 
 LEFT JOIN constru_altas cc on cc.id=c.id_alta
LEFT JOIN constru_especialidad d on d.id=cc.id_area
WHERE a.id_obra='$id_obra' AND a.id='$id_des' LIMIT $start , $limit";

$result = $mysqli->query($SQL);


$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['idemp'];
    $responce->rows[$i]['cell']=array($row['id'],$row['pi'],$row['pf'],$row['anom'],$row['empleado'],$row['diast'],$row['he'],$row['df'],$row['idt'],$row['ihe'],$row['idf'],$row['desci'],$row['finis'],$row['subt'],$row['difd'],$row['total']);
    $i++;
}        
echo json_encode($responce);
?>