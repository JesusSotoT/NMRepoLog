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
$row = $result->fetch_array();
$id_presupuesto=$row['id'];

@$oper = $_POST['oper'];
$id_partida = 0;
@$_search = $_GET['_search'];
@$searchField = $_GET['searchField'];
@$search = $_GET['searchString'];


$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database

$mysqli->query("SET NAMES utf8");
if(isset($oper) && $oper=='add'){

    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $codigo = $_POST['codigo'];
    $unidtext = $_POST['unidtext'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $unidad = $_POST['unidad'];

    $mysqli->query("INSERT INTO constru_recurso (id_partida, id_naturaleza, unidtext, codigo, unidad, descripcion, precio_costo, precio_venta) VALUES ('$id',1,'$unidtext','$codigo','$unidad','$descripcion','$precio_costo','$precio_venta');");
    exit();
}

if(isset($oper) && $oper=='edit'){

    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];
    $codigo = $_POST['codigo'];
    $id_um = $_POST['id_um'];
    $precio_costo = $_POST['precio_costo'];
    $precio_venta = $_POST['precio_venta'];
    $unidad = $_POST['unidad'];
    $corto = $_POST['corto'];

    $mysqli->query("UPDATE constru_recurso SET id_um='$id_um', codigo='$codigo', unidad='$unidad', corto='$corto', descripcion='$descripcion', precio_costo='$precio_costo', precio_venta='$precio_venta' WHERE id='$id';");
    exit();
}

//$SQL = "SELECT COUNT(*) AS count FROM constru_recurso WHERE borrado=0 AND id_presupuesto='$id_presupuesto';";
//$result = $mysqli->query($SQL);
//$row = $result->fetch_array();
$count = 76;

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

$SQL = "SELECT a.id cpid, a.cp Costo_Proyecto, b.id ccid, b.cc Centro_Costo, c.id costoid, c.costo Costo, d.id cargoid, d.cargo Cargo FROM constru_cuentas_cp a
left join constru_cuentas_cc b on b.id_cp=a.id
left join constru_cuentas_costo c on c.id_cc=b.id
left join constru_cuentas_cargo d on d.id_costo=c.id
group by cpid, ccid, costoid, cargoid ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

@$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;

while($row = $result->fetch_array()) {
    if($row['cargoid']!=''){ $idn='CA-'.$row['cargoid']; }
    if($row['cargoid']=='' && $row['costoid']!=''){ $idn='CO-'.$row['costoid']; }
    if($row['cargoid']=='' && $row['costoid']==''  && $row['ccid']!=''){ $idn='CC-'.$row['ccid']; }
    if($row['cargoid']=='' && $row['costoid']==''  && $row['ccid']=='' && $row['cpid']!=''){ $idn='CP-'.$row['cpid']; }
    $total=0;
/*
    $SQL = "SELECT sum(salio*d.precio_compra) as total FROM constru_bit_salidas a
    left join constru_salida_almacen b on b.id_bit_salida=a.id
    left join constru_requis c on c.id=b.id_req
    left join constru_requisiciones d on d.id_requi=c.id AND d.id_clave=b.id_insumo
    WHERE a.id_obra='$id_obra' AND a.id_cc='".$row['cargoid']."' AND b.estatus=1;";
            $result2 = $mysqli->query($SQL);
            $row2 = $result2->fetch_array();
            $total+= $row2['total'];

   /* $SQL = "SELECT sum(a.subtotal1) as total FROM constru_estimaciones_bit_destajista a
    WHERE a.id_obra='$id_obra' AND a.id_cc='".$row['cargoid']."' AND a.estatus=1;";
            $result2 = $mysqli->query($SQL);
            $row2 = $result2->fetch_array();
            $total+= $row2['total'];*/

   /* $SQL = "SELECT sum(a.imp_estimacion) as total FROM constru_estimaciones_bit_subcontratista a
    WHERE a.id_obra='$id_obra' AND a.id_cc='".$row['cargoid']."' AND a.estatus=1;";
            $result2 = $mysqli->query($SQL);
            $row2 = $result2->fetch_array();
            $total+= $row2['total'];
            */

    //Estimaciones caja chica
    
/*
    //Estimaciones indirectos
    $SQL = "SELECT sum(a.imp_estimacion) as total FROM constru_estimaciones_bit_indirectos a
    WHERE a.id_obra='$id_obra' AND a.id_cc='".$row['cargoid']."' AND a.estatus=1;";
            $result2 = $mysqli->query($SQL);
            $row2 = $result2->fetch_array();
            $total+= $row2['total'];

    // Nominas de destajistas y obreros
    $SQL = "SELECT sum(a.total) as total FROM constru_bit_nominadest a
    WHERE a.id_obra='$id_obra' AND a.id_cc='".$row['cargoid']."' AND a.estatus=1;";
            $result2 = $mysqli->query($SQL);
            $row2 = $result2->fetch_array();
            $total+= $row2['total'];

    // Nominas de tecnicos
    $SQL = "SELECT sum(a.total) as total FROM constru_bit_nominaca a
    WHERE a.id_obra='$id_obra' AND a.id_cc='".$row['cargoid']."' AND a.estatus=1;";
            $result2 = $mysqli->query($SQL);
            $row2 = $result2->fetch_array();
            $total+= $row2['total'];
            */

 
    $responce->rows[$i]['id']=$idn;
    $responce->rows[$i]['cell']=array($row['Costo_Proyecto'],$row['Centro_Costo'],$row['Costo'],$row['Cargo']);
    $i++;
}        
echo json_encode($responce);
?>