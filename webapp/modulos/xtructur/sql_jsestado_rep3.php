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
$mes = $_GET['mes'];
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
    $mysqli->query("UPDATE constru_proforma2 SET borrado=1 WHERE id in ($id);");
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
    $costo_directo = $_POST['costo_directo'];
    $costo_directo_p = $_POST['costo_directo_p'];
    $indirecto_campo = $_POST['indirecto_campo'];
    $indirecto_campo_p = $_POST['indirecto_campo_p'];
    $indirecto_oc = $_POST['indirecto_oc'];
    $indirecto_oc_p = $_POST['indirecto_oc_p'];
    $utilidad = $_POST['utilidad'];
    $utilidad_p = $_POST['utilidad_p'];
    $importe_pres = $_POST['importe_pres'];
    $importe_presu_p = $_POST['importe_presu_p'];
    $factor_salario = $_POST['factor_salario'];

    $mysqli->query("UPDATE constru_proforma2 SET costo_directo='$costo_directo', costo_directo_p='$costo_directo_p', indirecto_campo='$indirecto_campo', indirecto_campo_p='$indirecto_campo_p', indirecto_oc='$indirecto_oc', indirecto_oc_p='$indirecto_oc_p', utilidad='$utilidad', utilidad_p='$utilidad_p', importe_pres='$importe_pres', importe_presu_p='$importe_presu_p', factor_salario='$factor_salario' WHERE id='$id';");
    exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_proforma2 WHERE id_obra='$id_obra' AND borrado=0;";
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

$year = date('Y');
$mesnum=$mes*1;


$beg = (int) date('W', strtotime("first monday of $year-$mes"));
$end = (int) date('W', strtotime("last  monday of $year-$mes"));

$weeks = join(', ', range(1, $end));

if(strlen($mes)==1){
    $mes='0'.$mes;
}

$d=cal_days_in_month(CAL_GREGORIAN,$mes,$year);
$first_day= $year.'-01-01';
$last_day= $year.'-'.$mes.'-'.$d;

$SQL = "SELECT a.total
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.fecha between '".$first_day."' AND '".$last_day."'
UNION ALL
SELECT a.total
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.fecha between '".$first_day."' AND '".$last_day."'
;";
$grandt=0;
$result = $mysqli->query($SQL);
while($row = $result->fetch_array()) {
    $grandt+=$row['total'];
}

$SQL = "SELECT sum(imp_sem) as totpagado FROM constru_remesas WHERE id_obra='$id_obra' AND semana in ($weeks) AND borrado=0;";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$totpagado=$row['totpagado'];


$pasvencidos=$grandt-$totpagado;
$pasvencer=0;
$totalpasivos=$pasvencidos+$pasvencer;
$egreacum=$totpagado+$totalpasivos;

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

    $responce->rows[$i]['id']=1;
    $responce->rows[$i]['cell']=array($pasvencidos,0,$totalpasivos,$totpagado,$egreacum);
    $i++;

echo json_encode($responce);
?>