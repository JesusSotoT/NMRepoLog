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
$id_remesa = $_GET['idremesa'];
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
    $mysqli->query("UPDATE constru_cheques SET borrado=1 WHERE id in ($id);");
    exit();

}

if(isset($oper) && $oper=='add'){
    $por_utilidad = $_POST['por_utilidad'];
    $de_utilidad = $_POST['de_utilidad'];
    $por_indirecto = $_POST['por_indirecto'];
    $de_indirecto = $_POST['de_indirecto'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];
    $factor_salario = $_POST['factor_salario'];

    $mysqli->query("INSERT INTO constru_proforma2 (id_obra,por_utilidad,de_utilidad,por_indirecto,de_indirecto,factor_salario) VALUES ('$id_obra','$por_utilidad','$de_utilidad','$por_indirecto','$de_indirecto','$factor_salario');");
    exit();
}

if(isset($oper) && $oper=='edit'){
    $id = $_POST['id'];
    $no_cheque = $_POST['no_cheque'];
    //$validacion_cheque = $_POST['validacion_cheque'];
    $banco = $_POST['banco'];
    $fecha_expedicon = $_POST['fecha_expedicon'];
    $estatus_cheque = $_POST['estatus_cheque'];
    $estatus_factura = $_POST['estatus_factura'];


    $mysqli->query("UPDATE constru_cheques SET no_cheque='$no_cheque', banco='$banco', fecha_expedicion='$fecha_expedicon', estatus_cheque='$estatus_cheque', estatus_factura='$estatus_factura' WHERE id='$id';");
    exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_cheques WHERE id_obra='$id_obra';";
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

$SQL = "SELECT a.* FROM constru_cheques a WHERE 1=1 ".$cad." AND a.id_obra='$id_obra' and id_remesa='$id_remesa' ORDER BY $sidx $sord LIMIT $start , $limit";
$result = $mysqli->query($SQL);


$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['no_cheque'],'1',$row['banco'],$row['fecha_expedicion'],$row['estatus_cheque'],$row['estatus_factura']);
    $i++;
}        
echo json_encode($responce);
?>