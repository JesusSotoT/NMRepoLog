<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
  exit();
}else{
  $cookie_xtructur = unserialize($_COOKIE['xtructur']);
  $id_obra = $cookie_xtructur['id_obra'];
}

$oper = $_POST['oper'];

$id_des = $_GET['id_des'];
$sestmp = $_GET['sestmp'];
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database
include('conexiondb.php');
$mysqli->query("set names 'utf8'");

if(isset($oper) && $oper=='del'){
    $id = $_POST['id'];
    "Delete from constru_avancevol  WHERE id in ($id);";
    $mysqli->query("Delete from constru_avancevol  WHERE id in ($id);");
    exit();
}

if(isset($oper) && $oper=='add'){
    $id_clave = $_POST['id_clave'];
    $vol_anterior = $_POST['volumen_anterior'];
    $SQL="SELECT id FROM constru_avancevol WHERE  sestemp='$sestmp' AND id_bit_avancevol=0  AND id_clave='$id_clave';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'RP';
        exit();
    }

    $vol_tope = $_POST['vol_tope'];
    $vol_estimacion = $_POST['vol_estimacion'];
    $fecha_entrega = $_POST['fecha_entrega'];


    $mysqli->query("INSERT INTO constru_avancevol (id_clave,id_bit_avancevol,sestemp,volumen) VALUES ('$id_clave',0, '$sestmp','$vol_estimacion');");
    exit();
}

if(isset($oper) && $oper=='edit'){
    $id = $_POST['id'];
    $id_clave = $_POST['id_clave'];

    $cantidad = $_POST['cantidad'];

    $fecha_entrega = $_POST['fecha_entrega'];

    $mysqli->query("UPDATE constru_avancevol SET id_clave='$id_clave', cantidad='$cantidad' WHERE id='$id';");
    exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_avancevol WHERE  sestemp>0";
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

 $SQL = "SELECT a.*, c.precio_costo, c.codigo, c.descripcion, c.unidtext
    from constru_avancevol a
    left join constru_recurso c on c.id=a.id_clave
    WHERE  a.sestemp='$sestmp'  ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    $SQL ="SELECT sum(unidad) as totcant FROM constru_recurso WHERE id_obra='$id_obra' AND codigo='".$row['codigo']."';";

    $result3 = $mysqli->query($SQL);
    $row3 = $result3->fetch_array();
    $vol_tope=$row3['totcant'];
    $total=$vol_tope*$row['precio_costo'];

    $SQL ="SELECT sum(a.volumen) as vol_anterior from constru_avancevol a 
    inner join constru_bit_avancevol b on b.id=a.id_bit_avancevol 
    where a.id_bit_avancevol>0 AND a.sestemp!='$sestmp'  AND a.id_clave='".$row['id_clave']."';";

    $result2 = $mysqli->query($SQL);
    $row2 = $result2->fetch_array();
    $vol_anterior = $row2['vol_anterior']-$row['volumen'];
    $vol_acumulado = $row2['vol_anterior']+$row['volumen'];
    $vol_ejecutar = $vol_tope-$vol_acumulado;


    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array('','','','',$row['codigo'],$row['descripcion'],$row['unidtext'],$vol_tope,$row['precio_costo'],$total,$row2['vol_anterior'],$vol_acumulado,$vol_ejecutar,$row['volumen']);
    $i++;
}        

echo json_encode($responce);
?>