<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
    echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
 
$id_prov = $_GET['id_prov'];
$id_pedi = $_GET['id_pedi'];
$id_des = $_GET['id_des'];
$sestmp = $_GET['sestmp'];
$estpor = $_GET['estpor'];

include('conexiondb.php');
$mysqli->query("DELETE FROM constru_estimaciones_prov WHERE id_obra='$id_obra' AND id_oc='$id_pedi' AND id_bit_prov=0;");

$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$id_presupuesto=$row['id'];

$oper = $_POST['oper'];
$id_partida = 0;
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

$SQL = "SELECT COUNT(*) AS count FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
left JOIN constru_info_tdo d on d.id_alta=a.solicito
WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0;";
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

/*
$id_prov = $_GET['id_prov'];
$id_pedi = $_GET['id_pedi'];
$id_des = $_GET['id_des'];
$sestmp = $_GET['sestmp'];
*/

$SQL = "SELECT a.id lol, c.clave, c.descripcion, c.unidtext,  b.cantidad Rcantidad, c.precio, b.cantidad*b.precio_compra importe, b.cantidad*c.precio importec, a.id pedid, b.id pedsid, c.id insuid,
 b.precio_compra, c1.id as idrq,
 if(b.elprov is null,a.id_prov,b.elprov) as prreal, a.id_prov as prrep
FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id AND b.borrado=0
LEFT JOIN constru_insumos c on c.id=b.id_clave
left JOIN constru_info_tdo d on d.id_alta=a.solicito
WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 AND a.id='$id_pedi' ORDER BY a.id desc, c.id desc, $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);
$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;



while($row = $result->fetch_array()) {
    if($row['prreal']!=$row['prrep']){
        continue;
    }

    //echo "UPDATE constru_estimaciones_prov SET id_req='".$row['idrq']."' WHERE id_obra='$id_obra' AND id_clave ='".$row['insuid']."' AND id_oc='$id_pedi' AND id_bit_prov!=0;";

    $mysqli->query("UPDATE constru_estimaciones_prov SET id_req='".$row['idrq']."' WHERE id_obra='$id_obra' AND id_clave ='".$row['insuid']."' AND id_oc='$id_pedi' AND id_bit_prov!=0; AND id_req!=0");

    $SQL ="SELECT sum(a.llego) as tot_entradas from constru_entrada_almacen a
     where a.id_oc='$id_pedi' AND a.id_obra='$id_obra' AND a.id_insumo='".$row['insuid']."' AND a.id_req='".$row['idrq']."' ;";
    $result3 = $mysqli->query($SQL);
    $row3 = $result3->fetch_array();

    $entrada1 = $row3['tot_entradas'];

    $SQL ="SELECT sum(a.vol_gris) as tot_entradas_est from constru_estimaciones_prov a
    where a.id_obra='$id_obra' AND a.id_bit_prov>0 AND a.id_oc='$id_pedi' AND a.id_req='".$row['idrq']."' AND a.borrado=0 AND a.id_clave='".$row['insuid']."';";
    $result4 = $mysqli->query($SQL);
    $row4 = $result4->fetch_array();

    if($row4['tot_entradas_est']==$row['Rcantidad']){
        $entrada =0;
    }else{
        $entrada = $entrada1-$row4['tot_entradas_est'];
    }
    


    $SQL ="SELECT sum(a.vol_gris) as vol_gris from constru_estimaciones_prov a where a.id_bit_prov>0 AND a.id_oc='$id_pedi' AND a.id_obra='$id_obra' AND a.borrado=0 AND a.id_clave='".$row['insuid']."' AND a.id_req='".$row['idrq']."';";

    $result2 = $mysqli->query($SQL);
    $row2 = $result2->fetch_array();
    $vol_gris = $row2['vol_gris'];
    $vol_acumulado = $vol_gris+$entrada;
    $vol_ejecutar = $row[Rcantidad]-$vol_acumulado;
    $imp_est=$row[precio_compra]*$entrada;

    if($estpor=='o'){
        $entrada =$row['Rcantidad']-$vol_gris;
        $vol_acumulado=$row['Rcantidad'];
        $vol_ejecutar=0;
        $imp_est=$row[precio_compra]*$entrada;
    }

    $mysqli->query("INSERT INTO constru_estimaciones_prov (id_obra,id_clave,id_oc,id_bit_prov,sestmp,vol_anterior,vol_gris,vol_acu,vol_eje,imp_est,id_req) VALUES ('$id_obra','".$row['insuid']."','$id_pedi',0,'$sestmp','$vol_gris','$entrada','$vol_acumulado','$vol_ejecutar','$imp_est','".$row['idrq']."');");


    


    $responce->rows[$i]['lol']=$row['lol'];
    $responce->rows[$i]['cell']=array($row['clave'],$row['descripcion'],$row['unidtext'],$row['Rcantidad'],$row['precio_compra'],$row['importe'],$vol_gris,$entrada,$vol_acumulado,$vol_ejecutar,$imp_est);
    $i++;
}        
echo json_encode($responce);
?>