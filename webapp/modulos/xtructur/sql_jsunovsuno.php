<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
 
include('conexiondb.php');

$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
if($result->num_rows>0){
    $row = $result->fetch_array();
    $id_presupuesto=$row['id'];
}else{
    $id_presupuesto='';
}


$oper = $_POST['oper'];

$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database
 
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){
    $id = $_POST['id'];
    $mysqli->query("UPDATE constru_recurso SET borrado=1 WHERE id in ($id);");
    exit();

}

if(isset($oper) && $oper=='add'){

    $id = $_POST['id'];
    
    $naturaleza = $_POST['naturaleza'];
    $descripcion = $_POST['descripcion'];
    $codigo = $_POST['codigo_clave'];
    if($naturaleza=='Extra'){
        $codigo = 'EXT-'.$codigo;
    }elseif ($naturaleza=='No cobrable') {
        $codigo = 'OTO-'.$codigo;
    }
    $unidtext = $_POST['unidtext'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $unidad = $_POST['unidad'];

    $mysqli->query("INSERT INTO constru_recurso (naturaleza, id_partida, id_naturaleza, unidtext, codigo, unidad, descripcion, precio_costo, precio_venta, id_presupuesto) VALUES ('$naturaleza','$id',1,'$unidtext','$codigo','$unidad','$descripcion','$precio_costo','$precio_venta','$id_presupuesto');");
    exit();
}

if(isset($oper) && $oper=='edit'){

    $id = $_POST['id'];
    $naturaleza = $_POST['naturaleza'];
    $descripcion = $_POST['descripcion'];
    $codigo = $_POST['codigo_clave'];
    if($naturaleza=='Extra'){
        $codigo = 'EXT-'.$codigo;
    }elseif ($naturaleza=='No cobrable') {
        $codigo = 'OTO-'.$codigo;
    }
    $id_um = $_POST['id_um'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $unidad = $_POST['unidad'];
    $corto = $_POST['corto'];

    $mysqli->query("UPDATE constru_recurso SET naturaleza='$naturaleza', id_um='$id_um', codigo='$codigo', unidad='$unidad', corto='$corto', descripcion='$descripcion', precio_costo='$precio_costo', precio_venta='$precio_venta' WHERE id='$id';");
    exit();
}



$total_pages = 1;

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
if($id_presupuesto!=''){
$SQL = "SELECT a.*,
if(sum(clisum.tsum) is null,0,sum(clisum.tsum)) as  estimacion,
-- if(sum(clisum2.tsum2)+sum(clisum3.tsum3) is null,0,sum(clisum2.tsum2)+sum(clisum3.tsum3)) as  pagado
if(sum(clisum2.tsum2) is null,0,sum(clisum2.tsum2))+if(sum(clisum3.tsum3) is null,0,sum(clisum3.tsum3)) as pagado
FROM constru_recurso as a
LEFT JOIN (
    SELECT a.estatus, a.id_obra, b.id_insumo, if(sum(b.vol_estimacion) is null,0,sum(b.vol_estimacion)) as tsum
    from constru_estimaciones_bit_cliente a
    left join constru_estimaciones_cliente b on b.id_bit_cliente=a.id and b.borrado=0
    group by b.id_insumo) as clisum
    on clisum.id_insumo=a.id and clisum.id_obra=a.id_obra and clisum.estatus=1
LEFT JOIN (
    SELECT a.estatus, a.id_obra, b.id_clave, if(sum(b.vol_est) is null,0,sum(b.vol_est)) as tsum2
    from constru_estimaciones_bit_destajista a
    left join constru_estimaciones_destajista b on b.id_bit_destajista=a.id and b.borrado=0
    group by b.id_clave) as clisum2
    on clisum2.id_clave=a.id and clisum2.id_obra=a.id_obra and clisum2.estatus=1
LEFT JOIN (
    SELECT a.estatus, a.id_obra, b.id_insumo, if(sum(b.vol_estimacion) is null,0,sum(b.vol_estimacion)) as tsum3
    from constru_estimaciones_bit_subcontratista a
    left join constru_estimaciones_subcontratista b on b.id_bit_subcontratista=a.id and b.borrado=0
    group by b.id_insumo) as clisum3
    on clisum3.id_insumo=a.id and clisum3.id_obra=a.id_obra  and clisum2.estatus=1 WHERE 1=1 ".$cad." AND a.id_presupuesto='$id_presupuesto' AND a.borrado=0 AND a.id_obra='$id_obra' group by a.id ORDER BY a.id_naturaleza desc, a.id LIMIT $start,$limit";
    $result = $mysqli->query($SQL);

    $responce->page = 1;
    $responce->total = 1;
    $responce->records = 1000000;
    $i=0;
    while($row = $result->fetch_array()) {
    $diferencia=($row['estimacion']*1)-$row['pagado']*1;
        $responce->rows[$i]['id']=$row['id'];
        $responce->rows[$i]['cell']=array($row['naturaleza'],$row['codigo'],$row['descripcion'],$row['unidtext'],$row['unidad'],$row['estimacion'],$row['pagado'],$diferencia);
        $i++;
    }
}else{
    $responce=array();
}       
echo json_encode($responce);
?>