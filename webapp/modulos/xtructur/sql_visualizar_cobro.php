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

$oper = $_POST['oper'];
$id_partida = 0;
$sema = $_GET['sema'];
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
left join constru_entrada_almacen f on f.id_oc=a.id and f.id_insumo=b.id_clave  AND f.id_req=c1.id
WHERE a.estatus=3 AND a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0;";
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

$result = $mysqli->query('SELECT * FROM forma_pago where idFormapago=1 or idFormapago=7;');
$selop='<option value="0">Seleccione</option>';
while($row = $result->fetch_array()) {
    $selop.='<option value="'.$row['idFormapago'].'">'.$row['nombre'].'</option>';
}


$SQL = "SELECT b.id_alta, a.id as iidd, a.id_cliente, a.fecha, concat('CLI-',b.id_alta,' - ',b.nombre,' ',b.paterno,' ',b.materno) Cliente, concat('ESTCLI-',a.id) estimacion, a.imp_estimacion, 
concat('<input value=\"',0,'\" id=\"',a.id_cliente,'\" class=\"quis__\" est=\"ESTCLI\" name=\"',a.id,'\">') cant
FROM constru_estimaciones_bit_cliente a
left join constru_info_tdo b on b.id_alta=a.id_cliente
WHERE a.id_obra='$id_obra' AND estatus='1'
ORDER BY cliente, 2 desc";



/*
/* ,
CASE WHEN TMP_ORDER = 0 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 1 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 2 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 4 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 5 THEN id_alta ELSE 0 END ASC, iidd;
*/

$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
//$responce->userdata = array('Importe'=>600);

$prisum=0;
$secsum=0;
$tersum=0;
$i=0;

while($row = $result->fetch_array()) {

    $adjunselop='<select class="selopp_5" style="width:100px;">'.$selop.'</select>';
    $esti=$row['iidd'];
    $SQL ="SELECT if(sum(a.imp_sem) is null,0,sum(a.imp_sem)) as imp_sem from constru_cobros a 
    left join constru_bit_cobros b on b.id=id_bit_cobros
     where a.id_obra='$id_obra' AND b.estatus=1 and id_esti='$esti' order by a.id;";
    $result2 = $mysqli->query($SQL);
    $row2 = $result2->fetch_array();
    $sumap=$row2['imp_sem'];
    $sp=$row['imp_estimacion']-($sumap*1);
    $prisum+=$row['imp_estimacion'];
    $secsum+=$sumap;
    $tersum+=$sp;
    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    if($sp>0){

        $responce->rows[$i]['iidd']=$i;
        $responce->rows[$i]['cell']=array($row['Cliente'],substr($row['fecha'],0,10),$row['estimacion'],$row['imp_estimacion'],$sumap,$sp,$row['cant'],$adjunselop);
        $i++;
    }
}  
  
echo json_encode($responce);
?>