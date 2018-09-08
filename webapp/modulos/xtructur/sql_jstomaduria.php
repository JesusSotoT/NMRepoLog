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
function xxano($fecha){
    $date = new DateTime($fecha);
    $week = $date->format("W");
    $ano=explode('-', $fecha);
    $ano=$ano[0];

    return $ano.'-'.$week;
}
week_bounds(date('Y-m-d'), $fstart, $fend);
$ano = date('Y');
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
if(isset($oper) && $oper=='add'){
    $ano = $_POST['ano'];
    $semana = $_POST['semana'];
    $id_empleado = $_POST['id_empleado'];
    $lunes = $_POST['lun'];
    $martes = $_POST['mar'];
    $miercoles = $_POST['mie'];
    $jueves = $_POST['jue'];
    $viernes = $_POST['vie'];
    $sabado = $_POST['sab'];
    $domingo = $_POST['dom'];

    $xxano = xxano($sd);

    $mysqli->query("INSERT INTO constru_tomaduria (id_obra,id_empleado,ano,semana,lun,mar,mie,jue,vie,sab,dom,xxano) VALUES ('$id_obra','$id_empleado','$ano','$semana','$lunes','$martes','$miercoles','$jueves','$viernes','$sabado','$domingo','$xxano');");
    exit();
}

if(isset($oper) && $oper=='edit'){
    $id = $_POST['id'];
    $lun = $_POST['lun'];
    $mar = $_POST['mar'];
    $mie = $_POST['mie'];
    $jue = $_POST['jue'];
    $vie = $_POST['vie'];
    $sab = $_POST['sab'];
    $startDate = $_POST['startDate2'];
    $endDate = $_POST['endDate2'];

    $xxano = xxano($sd);

    $multipleids = $_POST['multipleids'];


    foreach ($multipleids as $k => $id) {

        $SQL = "SELECT id FROM constru_tomaduria WHERE id_obra='$id_obra' AND per_ini='$startDate' AND per_fin='$endDate' AND id_empleado='$id';";
        $result = $mysqli->query($SQL);
        if($result->num_rows>0) {
            $mysqli->query("UPDATE constru_tomaduria SET lun='$lun', mar='$mar', mie='$mie', jue='$jue', vie='$vie', sab='$sab', xxano='$xxano' WHERE id_empleado='$id' AND per_ini='$startDate' AND per_fin='$endDate' AND id_obra='$id_obra';");
        }else{
            $mysqli->query("INSERT INTO constru_tomaduria (id_dest,id_obra,id_empleado,per_ini,per_fin,lun,mar,mie,jue,vie,sab,xxano) VALUES ('$id_des','$id_obra','$id','$startDate','$endDate','$lun','$mar','$mie','$jue','$vie','$sab','$xxano');");
        }

        $mysqli->query("UPDATE constru_tomaduria SET lun='$lun', mar='$mar', mie='$mie', jue='$jue', vie='$vie', sab='$sab' WHERE id='$id';");

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



$SQL = "SELECT c.per_ini, c.per_fin, 'Varias' as narea, a.id as idemp, concat('DEST-',a.id,' ',b.nombre,' ',b.paterno) as empleado, c.*,  a.f_ingreso
FROM constru_altas a 
 LEFT JOIN constru_info_tdo b on b.id_alta=a.id 
 LEFT JOIN constru_tomaduria c on c.id_empleado=a.id AND c.per_ini='$sd' AND c.per_fin='$ed' AND c.id_dest='$id_des'
 LEFT JOIN constru_especialidad d on d.id=a.id_area
 WHERE a.id_obra='$id_obra' AND a.id='$id_des' and a.borrado=0 LIMIT 1
UNION ALL
SELECT c.per_ini, c.per_fin, d.nombre as narea, a.id as idemp, concat('EMP-',a.id,' ',b.nombre,' ',b.paterno) as empleado, c.* ,  a.f_ingreso
FROM constru_altas a 
LEFT JOIN constru_info_tdo b on b.id_alta=a.id
LEFT JOIN constru_tomaduria c on c.id_empleado=a.id AND c.per_ini='$sd' AND c.per_fin='$ed' AND c.id_dest='$id_des'
 LEFT JOIN constru_especialidad d on d.id=a.id_area
 WHERE a.id_obra='$id_obra'  AND a.f_ingreso<='$ed' AND a.id_responsable='$id_des' and a.borrado=0 LIMIT $start , $limit";
$result = $mysqli->query($SQL);

$count=$result->num_rows;

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

/*
$begin = new DateTime($sd);
$end = new DateTime($ed);

$interval = DateInterval::createFromDateString('1 day');
$period = new DatePeriod($begin, $interval, $end);

   foreach ( $period as $dt ){
        $fechass=$dt->format( "Y-m-d" );

    }
    */

while($row = $result->fetch_array()) {
    

    $responce->rows[$i]['id']=$row['idemp'];
    $responce->rows[$i]['cell']=array($row['id'],$sd,$ed,$row['narea'],$row['empleado'],$row['lun'],$row['mar'],$row['mie'],$row['jue'],$row['vie'],$row['sab'],$row['dom']);
    $i++;
}        
echo json_encode($responce);
?>