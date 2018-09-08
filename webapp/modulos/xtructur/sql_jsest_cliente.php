<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
  exit();
}else{
  $cookie_xtructur = unserialize($_COOKIE['xtructur']);
  $id_obra = $cookie_xtructur['id_obra'];
}

$ar=$_GET['ar'];
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
    $mysqli->query("DELETE FROM constru_estimaciones_cliente WHERE id in ($id) AND id_obra='$id_obra';");
    exit();
}

if(isset($oper) && $oper=='add'){
    $id_clave = $_POST['id_clave'];
    $vol_anterior = $_POST['vol_anterior'];
    $SQL="SELECT id FROM constru_estimaciones_cliente WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_bit_cliente=0 and borrado=0 AND id_insumo='$id_clave';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'RP';
        exit();
    }

    $vol_tope = $_POST['vol_tope'];
    $vol_estimacion = $_POST['vol_estimacion'];
    $fecha_entrega = $_POST['fecha_entrega'];
    $mysqli->query("INSERT INTO constru_estimaciones_cliente (id_obra,id_insumo,id_bit_cliente,vol_tope,sestmp,vol_estimacion,vol_anterior) VALUES ('$id_obra', '$id_clave',0, '$vol_tope','$sestmp','$vol_estimacion','$vol_anterior');");
    exit();
}

if(isset($oper) && $oper=='edit'){


}


$SQL="SELECT COUNT(*) AS count FROM constru_requisiciones WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_requi=0 and borrado=0;";
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


$SQL = "SELECT a.id as ido, b.id as id_insumo, a.vol_estimacion, b.unidtext, b.descripcion, b.precio_costo, b.codigo
 from constru_estimaciones_cliente a 
 left join constru_recurso b on b.id=a.id_insumo  
 WHERE a.id_obra='$id_obra' AND a.sestmp='$sestmp' AND a.id_bit_cliente=0 AND a.borrado=0  ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    $SQL ="SELECT sum(unidad) as totcant FROM constru_recurso WHERE id_obra='$id_obra' AND codigo='".$row['codigo']."' AND borrado=0;";

    $result3 = $mysqli->query($SQL);
    $row3 = $result3->fetch_array();
    $vol_tope=$row3['totcant'];
    $total=$vol_tope*$row['precio_costo'];

     $SQL ="SELECT sum(a.vol_estimacion) as vol_anterior from constru_estimaciones_cliente a 
    inner join constru_estimaciones_bit_cliente b on b.id=a.id_bit_cliente AND b.estatus!=2
     where a.id_bit_cliente>0 AND a.sestmp='$sestmp' AND a.id_obra='$id_obra' AND a.id_insumo='".$row['id_insumo']."';";

    $result2 = $mysqli->query($SQL);
    $row2 = $result2->fetch_array();
    $vol_anterior = $row2['vol_anterior'];
    $vol_acumulado = $vol_anterior+$row['vol_estimacion'];
    $vol_ejecutar = $vol_tope-$vol_acumulado;
    $imp_est=$row['precio_costo']*$row['vol_estimacion'];

    

   /* $acumulado=$vanterior+$row[vol_estimacion];
    $poreje=$row[vol_tope]-$acumulado;
    $impfin=$row[pu_subcontrato]*$row[vol_estimacion];*/
    $responce->rows[$i]['id']=$row['ido'];
    $responce->rows[$i]['cell']=array('','','','',$row['codigo'],$row['descripcion'],$row['unidtext'],$vol_tope,$row['precio_costo'],$total,$vol_anterior,$row['vol_estimacion'],$vol_acumulado,$vol_ejecutar,$imp_est);
    $i++;
}        

echo json_encode($responce);
?>