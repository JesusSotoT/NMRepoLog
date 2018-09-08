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



$ano = date('Y');
$sd = $_GET['sd'];
$ed = $_GET['ed'];
$id_edif = $_GET['edif'];
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
    $importehr = $_POST['importehr'];
    $totale = $_POST['totale'];


    /*
    $SQL = "SELECT if(sum(total) is null,0,sum(total) ) as subtotal1 from constru_estimaciones_bit_destajista WHERE id_obra='$id_obra' AND id_destajista='$id_des' AND semana='$semana' AND estatus=1;";
    */

    //exit();

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

$SQL="SELECT nominadomingo FROM constru_config WHERE id_obra='$id_obra'; ";
$result = $mysqli->query($SQL);
$rowx = $result->fetch_array();
$nomdomingo=$rowx['nominadomingo'];

 $SQL = "SELECT e.sal_semanal, c.per_ini, c.per_fin, 'Varias' as anom, a.id as idemp, concat('DEST-',a.id,' ',b.nombre,' ',b.paterno,' ',b.materno) as empleado, c.*, (c.lun+c.mar+c.mie+c.jue+c.vie+c.sab) as diast, (e.sal_semanal/6)*(c.lun+c.mar+c.mie+c.jue+c.vie+c.sab) as importedt, ((e.sal_semanal/6)*(c.lun+c.mar+c.mie+c.jue+c.vie+c.sab))+c.impdf-c.descinf as sub1, ((e.sal_semanal/6)*(c.lun+c.mar+c.mie+c.jue+c.vie+c.sab))+c.impdf-c.descinf+c.difd as totalp, c.imphe, e.dias
FROM constru_altas a 
 LEFT JOIN constru_info_tdo b on b.id_alta=a.id 
 LEFT JOIN constru_tomaduria c on c.id_empleado=a.id AND c.per_ini='$sd' AND c.per_fin='$ed' AND c.id_dest='$id_des'
 LEFT JOIN constru_categoria e on e.id=a.id_categoria
 WHERE a.id_obra='$id_obra' AND a.id='$id_des' and a.borrado=0 LIMIT 1
UNION ALL 
SELECT e.sal_semanal, c.per_ini, c.per_fin, d.nombre as anom, a.id as idemp, concat('EMP-',a.id,' ',b.nombre,' ',b.paterno,' ',b.materno) as empleado, c.*, (c.lun+c.mar+c.mie+c.jue+c.vie+c.sab) as diast, (e.sal_semanal/6)*(c.lun+c.mar+c.mie+c.jue+c.vie+c.sab) as importedt, ((e.sal_semanal/6)*(c.lun+c.mar+c.mie+c.jue+c.vie+c.sab))+c.impdf-c.descinf as sub1, ((e.sal_semanal/6)*(c.lun+c.mar+c.mie+c.jue+c.vie+c.sab))+c.impdf-c.descinf+c.difd as totalp, c.imphe, e.dias
FROM constru_altas a 
LEFT JOIN constru_info_tdo b on b.id_alta=a.id 
 LEFT JOIN constru_tomaduria c on c.id_empleado=a.id AND c.per_ini='$sd' AND c.per_fin='$ed' AND c.id_dest='$id_des'
 LEFT JOIN constru_especialidad d on d.id=a.id_area
 LEFT JOIN constru_categoria e on e.id=a.id_categoria
 WHERE a.id_obra='$id_obra'  AND a.id_responsable='$id_des' and a.borrado=0 LIMIT $start , $limit";
$result = $mysqli->query($SQL);


$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $dt=($row['diast']*1);
    if($nomdomingo==1){
        if($dt>0){
            $dt++;
        }
    }



    $saldiario=($row['sal_semanal']*1)/$row['dias'];
    $salsal=($dt*1)*($saldiario*1);

    $sub1=$salsal+($row['impdf']*1)-($row['descinf']*1)+($row['fini']*1)+($row['imphe']*1);
    $tote=$sub1+($row[difd]*1);
    $responce->rows[$i]['id']=$row['idemp'];
    $responce->rows[$i]['cell']=array($row['id'],$sd,$ed,$row['anom'],$row['empleado'],$dt,$row['hre'],$row['diasf'],$salsal,$row['imphe'],$row['impdf'],$row['descinf'],$row['fini'],$sub1,$row['difd'],$tote);
    $i++;
}        
echo json_encode($responce);
?>