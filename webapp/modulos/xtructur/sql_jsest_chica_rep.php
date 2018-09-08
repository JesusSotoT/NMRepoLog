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
    $mysqli->query("UPDATE constru_estimaciones_destajista SET borrado=1 WHERE id in ($id);");
    exit();
}


if(isset($oper) && $oper=='add'){
    $clave = $_POST['clave'];
    $concepto = $_POST['concepto'];
    $unidtext = $_POST['unidtext'];
    $cantidad = $_POST['cantidad'];
    $pu_indirecto = $_POST['pu_indirecto'];

    $SQL="SELECT id FROM constru_estimaciones_indirectos WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_bit_insumos=0 and borrado=0 AND id_insumo='$id_clave';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'RP';
        exit();
    }


    $mysqli->query("INSERT INTO constru_estimaciones_indirectos (id_obra,clave,id_bit_indirectos,concepto,sestmp,unidtext,cantidad,pu_indirecto) VALUES ('$id_obra', '$clave',0, '$concepto','$sestmp','$unidtext','$cantidad','$pu_indirecto');");
    exit();
}

if(isset($oper) && $oper=='edit'){
    $id = $_POST['id'];
    $id_clave = $_POST['id_clave'];

    $cantidad = $_POST['cantidad'];

    $fecha_entrega = $_POST['fecha_entrega'];

    $mysqli->query("UPDATE constru_requisiciones SET id_clave='$id_clave', cantidad='$cantidad', fecha_entrega='$fecha_entrega' WHERE id='$id';");
    exit();
}


$SQL="SELECT COUNT(*) AS count FROM constru_estimaciones_chica WHERE id_obra='$id_obra' AND sestmp>0 AND id_bit_chica='$id_des' and borrado=0;";
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


$SQL = "SELECT a.id_proveedor, a.concepto, a.unidad, a.cantidad, a.val_fact, a.val_fact as importe, (a.val_fact)*(a.iva/100) as iva, ((a.val_fact)*(a.iva/100)) + (a.val_fact) as total, concat('PROV-',b.id,' ',c.razon_social_sp) prov, a.factura, d.cargo
 from constru_estimaciones_chica a
 left join constru_altas b on b.id=a.id_proveedor
 left join constru_info_sp c on c.id_alta=b.id 
   left join constru_cuentas_cargo d on d.id=a.id_cc
 WHERE a.id_obra='$id_obra' AND a.sestmp>0 AND a.id_bit_chica='$id_des' AND a.borrado=0  ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['prov'],$row['concepto'],$row['unidad'],$row['cantidad'],$row['val_fact'],$row['importe'],$row['iva'],$row['total'],$row['factura'],$row['cargo']);
    $i++;
}      

echo json_encode($responce);
?>