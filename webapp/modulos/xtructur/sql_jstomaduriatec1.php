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
$sd = $_GET['sd'];
$ed = $_GET['ed'];
$sema = $_GET['sema'];
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
    $mysqli->query("UPDATE constru_tomaduriatec1 SET borrado=1 WHERE id in ($id);");
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

    $multipleids = $_POST['multipleids'];


foreach ($multipleids as $k => $id) {
        # code...
    

    //if($id_des==1){
        $diasarr=array(0 =>$lun, 1=>$mar, 2=>$mie, 3=>$jue, 4=>$vie, 5=>$sab, 6=>0);
        $begin = new DateTime( $startDate );
        $end = new DateTime( $endDate );
        $end->modify('+1 day');

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $x=0;
        foreach ( $period as $dt ){
            if($diasarr[$x]==1){ $diasarr[6]=1;}
            $laff =  $dt->format( "Y-m-d" );

            $SQL = "SELECT id FROM constru_lista15 WHERE fecha='$laff' AND id_obra='$id_obra' AND id_tecnico='$id';";
            $result = $mysqli->query($SQL);
            if($result->num_rows>0) {
                $mysqli->query("UPDATE constru_lista15 SET asistio=".$diasarr[$x]." WHERE id_obra='$id_obra' AND id_tecnico='$id' AND fecha='$laff' ;");
            }else{
                $mysqli->query("INSERT INTO constru_lista15 (id_obra,id_tecnico,fecha,asistio) VALUES ('$id_obra','$id','$laff',".$diasarr[$x].");");
            }
            $x++;
        }
       // exit();
    //}

    


     $SQL = "SELECT id FROM constru_tomaduria_tec1 WHERE id_obra='$id_obra' AND per_ini='$startDate' AND per_fin='$endDate' AND id_tecnico='$id';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        $mysqli->query("UPDATE constru_tomaduria_tec1 SET lun='$lun', mar='$mar', mie='$mie', jue='$jue', vie='$vie', sab='$sab' WHERE id_tecnico='$id' AND per_ini='$startDate' AND per_fin='$endDate' AND id_obra='$id_obra';");
    }else{
        $mysqli->query("INSERT INTO constru_tomaduria_tec1 (id_tecnico,id_obra,per_ini,per_fin,lun,mar,mie,jue,vie,sab) VALUES ('$id','$id_obra','$startDate','$endDate','$lun','$mar','$mie','$jue','$vie','$sab');");
    }

}

    //$mysqli->query("UPDATE constru_tomaduriatec1 SET lun='$lun', mar='$mar', mie='$mie', jue='$jue', vie='$vie', sab='$sab' WHERE id_tecnico='$id';");
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



$SQL = "SELECT a.id as idemp, concat('TEC-',a.id,' ',b.nombre,' ',b.paterno) as empleado, c.* FROM constru_altas a 
 LEFT JOIN constru_info_tdo b on b.id_alta=a.id 
 LEFT JOIN constru_tomaduria_tec1 c on c.id_tecnico=a.id AND c.per_ini='$sd' AND c.per_fin='$ed'
 WHERE a.id_depto='$id_des' AND a.id_tipo_alta=1 AND a.id_obra='$id_obra' AND a.borrado=0  AND (a.estatus='Alta' OR a.estatus='Incapacitado') LIMIT $start , $limit";
$result = $mysqli->query($SQL);

$count=$result->num_rows;

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['idemp'];
    $responce->rows[$i]['cell']=array($sd,$ed,$row['empleado'],$row['lun'],$row['mar'],$row['mie'],$row['jue'],$row['vie'],$row['sab']);
    $i++;
}        
echo json_encode($responce);
?>