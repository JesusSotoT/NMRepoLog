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
$sema = $_POST['sema'];

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
    $mysqli->query("DELETE FROM constru_estimaciones_subcontratista WHERE id in ($id) AND id_obra='$id_obra';");
    exit();
}

if(isset($oper) && $oper=='add'){
    $id_clave = $_POST['id_clave'];

    $Agrupador = $_POST['Agrupador'];
    $Area = $_POST['Area'];
    $Especialidad = $_POST['Especialidad'];
    $Partida = $_POST['Partida'];

    $vol_anterior = $_POST['vol_anterior'];
    $SQL="SELECT id FROM constru_estimaciones_subcontratista WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_bit_subcontratista=0 and borrado=0 AND id_insumo='$id_clave' AND id_agru='$Agrupador' AND id_area='$Area' AND id_esp='$Especialidad' AND id_part='$Partida';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'RP';
        exit();
    }

    $vol_tope = $_POST['vol_tope'];
    $vol_estimacion = $_POST['vol_estimacion'];
    $fecha_entrega = $_POST['fecha_entrega'];

    $mysqli->query("INSERT INTO constru_estimaciones_subcontratista (id_obra,id_insumo,id_bit_subcontratista,vol_tope,sestmp,vol_estimacion,vol_anterior,id_agru,id_area,id_esp,id_part) VALUES ('$id_obra', '$id_clave',0, '$vol_tope','$sestmp','$vol_estimacion','$vol_anterior','$Agrupador','$Area','$Especialidad','$Partida');");
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


$SQL = "SELECT a.id as ido, b.id as id_insumo, a.vol_estimacion, b.unidtext, b.descripcion, b.pu_subcontrato, b.codigo, c.vol_tope, c.vol_tope*b.pu_subcontrato as total, a.id_agru, a.id_area, a.id_esp, a.id_part
 from constru_estimaciones_subcontratista a 
 left join constru_recurso b on b.id=a.id_insumo  
left join constru_vol_tope c on c.id_clave=b.id AND c.id_area='$ar' AND c.id_obra='$id_obra'
 WHERE a.id_obra='$id_obra' AND a.sestmp='$sestmp' AND a.id_bit_subcontratista=0 AND a.borrado=0  AND a.id_part=c.id_partida   ORDER BY a.$sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {

$SQL = "SELECT if( sum(a.vol_estimacion) is null,0,sum(a.vol_estimacion) ) as vol_estimacion from constru_estimaciones_subcontratista a 
Inner join constru_estimaciones_bit_subcontratista b on b.id=a.id_bit_subcontratista AND b.estatus!=2
 where a.id_bit_subcontratista>0 AND (a.id_area='$ar' OR b.id_area='$ar') -- AND a.sestmp='$sestmp' 
 AND a.id_obra='$id_obra' 
 AND a.id_insumo='".$row['id_insumo']."'  order by a.id DESC LIMIT 1;";


    $result2 = $mysqli->query($SQL);
    $row2 = $result2->fetch_array();
    $vol_anterior = $row2['vol_estimacion'];
    $vol_acumulado = $vol_anterior+$row['vol_estimacion'];
    $vol_ejecutar = $row['vol_tope']-$vol_acumulado;
    $imp_est=$row['pu_subcontrato']*$row['vol_estimacion'];

    

   /* $acumulado=$vanterior+$row[vol_estimacion];
    $poreje=$row[vol_tope]-$acumulado;
    $impfin=$row[pu_subcontrato]*$row[vol_estimacion];*/
    $responce->rows[$i]['id']=$row['ido'];
    $responce->rows[$i]['cell']=array($row['id_agru'],$row['id_area'],$row['id_esp'],$row['id_part'],$row['codigo'],$row['descripcion'],$row['unidtext'],$row['vol_tope'],$row['pu_subcontrato'],$row['total'],$vol_anterior,$row['vol_estimacion'],$vol_acumulado,$vol_ejecutar,$imp_est);
    $i++;
}        

echo json_encode($responce);
?>