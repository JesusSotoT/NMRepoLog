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
    $mysqli->query("UPDATE constru_estimaciones_chica SET borrado=1 WHERE id in ($id);");
    exit();
}


if(isset($oper) && $oper=='add'){
    $id_proveedor = $_POST['id_proveedor'];
    $concepto = $_POST['concepto'];
    $unidtext = $_POST['unidtext'];
    $cantidad = $_POST['cantidad'];
    $val_factura = $_POST['val_factura'];
    $factura = $_POST['factura'];
    $id_cc = $_POST['id_cc'];
    $iva = $_POST['iva'];

/*
    $SQL="SELECT id FROM constru_estimaciones_chica WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_bit_chica=0 and borrado=0 AND id_proveedor='$id_proveedor' AND $concepto= ;";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'RP';
        exit();
    }
    */

    $mysqli->query("INSERT INTO constru_estimaciones_chica (id_obra,id_proveedor,id_bit_chica,concepto,sestmp,unidad,cantidad,val_fact,factura,id_cc,iva) VALUES ('$id_obra', '$id_proveedor',0, '$concepto','$sestmp','$unidtext','$cantidad','$val_factura','$factura','$id_cc','$iva');");
    exit();
}

if(isset($oper) && $oper=='edit'){
   $id=$_POST['id'];
   $id_proveedor = $_POST['id_proveedor'];
    $concepto = $_POST['concepto'];
    $unidtext = $_POST['unidtext'];
    $cantidad = $_POST['cantidad'];
    $val_factura = $_POST['val_factura'];
    $factura = $_POST['factura'];
    $id_cc = $_POST['id_cc'];
    $iva = $_POST['iva'];

    $mysqli->query("UPDATE constru_estimaciones_chica SET id_proveedor='$id_proveedor', concepto='$concepto', unidad='$unidtext',cantidad='$cantidad',val_fact='$val_factura',factura='$factura',id_cc='$id_cc',iva='$iva' WHERE id='$id';");
    exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_estimaciones_chica WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_bit_chica=0 and borrado=0;";
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


$SQL = "SELECT a.id_proveedor, a.concepto, a.unidad, a.cantidad, a.val_fact, a.val_fact as importe, (a.val_fact)*(a.iva/100) as iva, ((a.val_fact)*(a.iva/100)) + (a.val_fact) as total, concat('PROV-',b.id,' ',c.razon_social_sp) prov, a.factura, d.cargo, a.id
 from constru_estimaciones_chica a
 left join constru_altas b on b.id=a.id_proveedor
 left join constru_info_sp c on c.id_alta=b.id 
  left join constru_cuentas_cargo d on d.id=a.id_cc 
 WHERE a.id_obra='$id_obra' AND a.sestmp='$sestmp' AND a.id_bit_chica=0 AND a.borrado=0  ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['prov'],$row['concepto'],$row['unidad'],$row['cantidad'],$row['val_fact'],$row['val_fact'],$row['iva'],$row['total'],$row['factura'],$row['cargo']);
    $i++;
}        

echo json_encode($responce);
?>