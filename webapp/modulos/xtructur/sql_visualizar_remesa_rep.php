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

//Filtros nuevos
if(!isset($_GET['filtro_semana'])){ $filtro_semana=0; }else{ $filtro_semana=$_GET['filtro_semana']; }
if(!isset($_GET['filtro_mes'])){ $filtro_mes=0; }else{ $filtro_mes=$_GET['filtro_mes']; }
if(!isset($_GET['filtro_estatus'])){ $filtro_estatus='x'; }else{ $filtro_estatus=$_GET['filtro_estatus']; }
if(!isset($_GET['filtro_proveedor'])){ $filtro_proveedor=' '; }else{ $filtro_proveedor=$_GET['filtro_proveedor']; }
//Fin filtros nuevos

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

$cadenafiltro = '';
if($filtro_mes!=0){
   $filtro_semana=0; 
}

if($filtro_semana!=0){

    $cadenafiltro.=" AND yearweek(a.fecha,1)='".$filtro_semana."' ";
}

if($filtro_mes!=0){
    $ym = explode('-', $filtro_mes);
    $cadenafiltro.=" AND YEAR(a.fecha) = '".$ym[0]."' AND MONTH(a.fecha) = '".$ym[1]."'";
}

if($filtro_estatus!='x'){
    $cadenafiltro.=" AND a.estatus = '".$filtro_estatus."' ";
}


if($filtro_proveedor!=' '){
    $cadenafiltro.=" AND c.proviene = '".$filtro_proveedor."' ";
}

$i=0;
$SQL2="SELECT c.id_esti, c.imp_sem, c.proviene,
CASE a.estatus 
when 1 then concat('Pago:',b.id,' Fecha: ',b.fecha,' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"autorizarem(0,',a.id,');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarem(2,',a.id,');\" >')
when 0 then concat('Pago:',b.id,' Fecha: ',b.fecha,' ','<font color=\"#ff0000\">Estimacion Cancelada</font>')
when 2 then concat('Pago:',b.id,' Fecha: ',b.fecha,' ','<font color=\"#070\">Estimacion Autorizada</font>')    
when 3 then concat('Pago:',b.id,' Fecha: ',b.fecha,' ','<font color=\"#070\">Estimacion Autorizada</font>')    
End as pago
FROM constru_bit_remesa a 
inner join constru_bit_remesas b on b.id_bit_remesa=a.id
inner join constru_remesas c on c.id_bit_remesas=b.id AND c.imp_sem>0
where  a.id_obra='$id_obra' ".$cadenafiltro." order by b.fecha desc,a.id desc,c.id_esti desc;";

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
//$responce->userdata = array('Importe'=>600);

$result2 = $mysqli->query($SQL2);
while($row2 = $result2->fetch_array()){
    if($row2['proviene']=='ESTPROV'){
        $SQL= "SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTPROV-',a.id) estimacion, a.factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti']." order by a.fecha desc";
    }
    if($row2['proviene']=='ESTIND'){
        $SQL="SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTIND-',a.id) estimacion, a.factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_estimaciones_bit_indirectos a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id_prov>0  AND a.id=".$row2['id_esti']." order by a.fecha desc";
    }
    if($row2['proviene']=='ESTSUB'){
        $SQL = "SELECT b.id_alta, a.id as iidd, a.id_subcontratista, a.fecha, concat('SUBC-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTSUB-',a.id) estimacion, a.factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_subcontratista,'\" class=\"quis__\" name=\"',a.id,'\">') cants
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti']."  order by a.fecha desc";
    }
    if($row2['proviene']=='ESTCAJA'){
        $SQL="SELECT 1,a.id as iidd, a.id, a.fecha, concat('CAJA-CHICA') Proveedor, concat('ESTCAJA-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_estimaciones_bit_chica a
-- LEFT JOIN constru_estimaciones_chica b on b.id_bit_chica=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti']." order by a.fecha desc";
    }
    if($row2['proviene']=='ESTDEST'){
        $SQL ="SELECT a.id_dest as id_alta, a.id as iidd, a.id_dest, a.fecha, concat(b.nombre,' - ',b.paterno,' - ',b.materno) Proveedor, concat('ESTDEST-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_dest,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_bit_nominadest a
left join constru_info_tdo b on b.id_alta=a.id_dest
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti']." order by a.fecha desc";
    }
    if($row2['proviene']=='ESTNOMC'){
        $SQL = "SELECT 1 as id_alta, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CENTRAL') Proveedor, concat('ESTNOMC-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=1 AND a.id=".$row2['id_esti']." order by a.fecha desc";
    }
    if($row2['proviene']=='ESTNOMOC'){
        $SQL="SELECT 2 as id_alta, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CAMPO') Proveedor, concat('ESTNOMOC-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=2 AND a.id=".$row2['id_esti']." order by a.fecha desc";
    }


/*
echo $SQL = "(SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTPROV-',a.id) estimacion, a.factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 0 as TMP_ORDER
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTIND-',a.id) estimacion, a.factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 1 as TMP_ORDER
FROM constru_estimaciones_bit_indirectos a
left join constru_info_sp b on b.id_alta=a.id_prov
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id_prov>0  AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_subcontratista, a.fecha, concat('SUBC-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTSUB-',a.id) estimacion, a.factura, a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_subcontratista,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 2 as TMP_ORDER
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT 1,a.id as iidd, a.id, a.fecha, concat('CAJA-CHICA') Proveedor, concat('ESTCAJA-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 3 as TMP_ORDER
FROM constru_estimaciones_bit_chica a
-- LEFT JOIN constru_estimaciones_chica b on b.id_bit_chica=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT a.id_dest as id_alta, a.id as iidd, a.id_dest, a.fecha, concat(b.nombre,' - ',b.paterno,' - ',b.materno) Proveedor, concat('ESTDEST-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_dest,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 4 as TMP_ORDER
FROM constru_bit_nominadest a
left join constru_info_tdo b on b.id_alta=a.id_dest
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT 1, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CENTRAL') Proveedor, concat('ESTNOMC-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 5 as TMP_ORDER
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=1 AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT 1, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CAMPO') Proveedor, concat('ESTNOMOC-',a.id) estimacion, '', a.total, 
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 5 as TMP_ORDER
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=2 AND a.id=".$row2['id_esti'].")
ORDER BY TMP_ORDER,
CASE WHEN TMP_ORDER = 0 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 1 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 2 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 4 THEN id_alta ELSE 0 END ASC, iidd,
CASE WHEN TMP_ORDER = 5 THEN id_alta ELSE 0 END ASC, iidd;";

*/

$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$prisum=0;
$secsum=0;
$tersum=0;



    $SQL3 ="SELECT if(sum(a.imp_sem) is null,0,sum(a.imp_sem)) as imp_sem from constru_remesas a
    inner join constru_bit_remesas b on b.id=id_bit_remesas
    inner join constru_bit_remesa c on c.id=b.id_bit_remesa
     where a.id_obra='$id_obra' AND a.proviene='".$row2['proviene']."' AND a.id_prov='".$row['id_alta']."' AND c.estatus=2 AND a.id_esti='".$row['iidd']."';";
    $result3 = $mysqli->query($SQL3);
    $row3 = $result3->fetch_array();
    $sumap=$row3['imp_sem'];
    $sp=$row['total']-($sumap*1);
    //$sp=($row['total']-($sumap*1))-$row2['imp_sem'];
    $prisum+=$row['total'];
    $secsum+=$sumap;
    $tersum+=$sp;

    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $responce->rows[$i]['iidd']=$i;
    $responce->rows[$i]['cell']=array($row2['pago'],$row['Proveedor'],substr($row['fecha'],0,10),$row['estimacion'],$row['factura'],$row['total'],$sumap,$sp,$row2['imp_sem'],'OOO');
    $i++;
}  

//$responce->userdata = array('Importe'=>$prisum, 'Pagado_en_emesas'=>$secsum, 'Saldo_por_pagar'=>$tersum);      
echo json_encode($responce);
?>