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

if(isset($oper) && $oper=='edit'){

    $id = $_POST['id'];
    $estache = $_POST['Estatus_cheque'];
    $estafact = $_POST['Estatus_factura'];
    $fechaexp = $_POST['Fecha_expedicion'];
    $nocheque = $_POST['No_cheque'];
    $banco = $_POST['Banco'];

    $SQL="SELECT id FROM constru_cheques WHERE id_obra='$id_obra'  AND no_cheque='$nocheque' and id!=$id;";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'ec';
        exit();
    }



    $mysqli->query("UPDATE constru_cheques SET no_cheque='$nocheque', banco='$banco', fecha_expedicion='$fechaexp', estatus_cheque='$estache', estatus_factura='$estafact' WHERE id='$id';");
    exit();
}

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
$i=0;
$SQL2="SELECT b.id as idbitrem, c.id_esti, c.imp_sem, a.semana, c.proviene, c.id as idremesa,concat('Rem-',a.id,' Fecha: ',a.fecha) as remesa FROM constru_bit_remesa a 
inner join constru_bit_remesas b on b.id_bit_remesa=a.id
inner join constru_remesas c on c.id_bit_remesas=b.id AND c.imp_sem>0
where  a.id_obra='$id_obra';";

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
//$responce->userdata = array('Importe'=>600);

$result2 = $mysqli->query($SQL2);
while($row2 = $result2->fetch_array()) {
    if($row2['proviene']=='ESTPROV'){
         $SQL= "SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTPROV-',a.id) estimacion, a.factura, a.total, c.no_cheque, c.banco,
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
left join constru_cheques c on c.id_remesa=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti']."";
    }

    if($row2['proviene']=='ESTSUB'){
        $SQL= "SELECT b.id_alta, a.id as iidd, a.id_subcontratista, a.fecha, concat('SUBC-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTSUB-',a.id) estimacion, a.factura, a.total, c.no_cheque, c.banco,
concat('<input value=\"',0,'\" id=\"',a.id_subcontratista,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
left join constru_cheques c on c.id_remesa=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti']."";
    }

    if($row2['proviene']=='ESTIND'){
        $SQL= "SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTIND-',a.id) estimacion, a.factura, a.total, c.no_cheque, c.banco,
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_estimaciones_bit_indirectos a
left join constru_info_sp b on b.id_alta=a.id_prov
left join constru_cheques c on c.id_remesa=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id_prov>0  AND a.id=".$row2['id_esti']."";
    }

    if($row2['proviene']=='ESTCAJA'){
        $SQL= "SELECT 1,a.id as iidd, a.id, a.fecha, concat('CAJA-CHICA') Proveedor, concat('ESTCAJA-',a.id) estimacion, '', a.total, 1, 2,
concat('<input value=\"',0,'\" id=\"',a.id,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_estimaciones_bit_chica a
-- LEFT JOIN constru_estimaciones_chica b on b.id_bit_chica=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti']."";
    }

    if($row2['proviene']=='ESTDEST'){
        $SQL= "SELECT a.id_dest as id_alta, a.id as iidd, a.id_dest, a.fecha, concat(b.nombre,' - ',b.paterno,' - ',b.materno) Proveedor, concat('ESTDEST-',a.id) estimacion, '', a.total, c.no_cheque, c.banco,
concat('<input value=\"',0,'\" id=\"',a.id_dest,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_bit_nominadest a
left join constru_info_tdo b on b.id_alta=a.id_dest
left join constru_cheques c on c.id_remesa=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti']."";
    }

    if($row2['proviene']=='ESTNOMC'){
        $SQL ="SELECT 1 as id_alta, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CENTRAL') Proveedor, concat('ESTNOMC-',a.id) estimacion, '', a.total, 1, 2,
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=1 AND a.id=".$row2['id_esti']."";
    }
    if($row2['proviene']=='ESTNOMOC'){
        $SQL = "SELECT 2 as id_alta, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CAMPO') Proveedor, concat('ESTNOMOC-',a.id) estimacion, '', a.total, 1, 2,
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" name=\"',a.id,'\">') cant
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=2 AND a.id=".$row2['id_esti']."";
    }

/*
$SQL = "(SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTPROV-',a.id) estimacion, a.factura, a.total, c.no_cheque, c.banco,
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 0 as TMP_ORDER
FROM constru_estimaciones_bit_prov a
left join constru_info_sp b on b.id_alta=a.id_prov
left join constru_cheques c on c.id_remesa=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_prov, a.fecha, concat('PROV-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTIND-',a.id) estimacion, a.factura, a.total, c.no_cheque, c.banco,
concat('<input value=\"',0,'\" id=\"',a.id_prov,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 1 as TMP_ORDER
FROM constru_estimaciones_bit_indirectos a
left join constru_info_sp b on b.id_alta=a.id_prov
left join constru_cheques c on c.id_remesa=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id_prov>0  AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT b.id_alta, a.id as iidd, a.id_subcontratista, a.fecha, concat('SUBC-',b.id_alta,' - ',b.razon_social_sp) Proveedor, concat('ESTSUB-',a.id) estimacion, a.factura, a.total, c.no_cheque, c.banco,
concat('<input value=\"',0,'\" id=\"',a.id_subcontratista,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 2 as TMP_ORDER
FROM constru_estimaciones_bit_subcontratista a
left join constru_info_sp b on b.id_alta=a.id_subcontratista
left join constru_cheques c on c.id_remesa=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT 1,a.id as iidd, a.id, a.fecha, concat('CAJA-CHICA') Proveedor, concat('ESTCAJA-',a.id) estimacion, '', a.total, 1, 2,
concat('<input value=\"',0,'\" id=\"',a.id,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 3 as TMP_ORDER
FROM constru_estimaciones_bit_chica a
-- LEFT JOIN constru_estimaciones_chica b on b.id_bit_chica=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT a.id_dest as id_alta, a.id as iidd, a.id_dest, a.fecha, concat(b.nombre,' - ',b.paterno,' - ',b.materno) Proveedor, concat('ESTDEST-',a.id) estimacion, '', a.total, c.no_cheque, c.banco,
concat('<input value=\"',0,'\" id=\"',a.id_dest,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 4 as TMP_ORDER
FROM constru_bit_nominadest a
left join constru_info_tdo b on b.id_alta=a.id_dest
left join constru_cheques c on c.id_remesa=a.id
WHERE a.id_obra='$id_obra' AND estatus='1' AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT 1, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CENTRAL') Proveedor, concat('ESTNOMC-',a.id) estimacion, '', a.total, 1, 2,
concat('<input value=\"',0,'\" id=\"',a.id_tecnico,'\" class=\"quis__\" name=\"',a.id,'\">') cant, 5 as TMP_ORDER
FROM constru_bit_nominaca a
WHERE a.id_obra='$id_obra' AND a.estatus='1' AND a.id_tecnico=1 AND a.id=".$row2['id_esti'].")
UNION ALL
(SELECT 1, a.id as iidd, a.id_tecnico, a.fecha, concat('NOMINA TEC-CAMPO') Proveedor, concat('ESTNOMOC-',a.id) estimacion, '', a.total, 1, 2,
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

        $cad='';


        $SQL3 ="SELECT if(sum(a.imp_sem) is null,0,sum(a.imp_sem)) as imp_sem from constru_remesas a 
         where a.id_obra='$id_obra' AND a.id_prov='".$row['id_alta']."' AND a.id_esti='".$row['iidd']."' and a.proviene='".$row2['proviene']."' AND a.proviene='".$row2['proviene']."';";
        $result3 = $mysqli->query($SQL3);
        $row3 = $result3->fetch_array();
        $sumap=$row3['imp_sem'];
        $sp=$row['total']-($sumap*1);
        $prisum+=$row['total'];
        $secsum+=$sumap;
        $tersum+=$sp;

        $SQL5 ="SELECT if(a.imp_sem is null,0,a.imp_sem) as imp_semana, a.id as ididremesa from constru_remesas a 
         where a.id_obra='$id_obra' AND a.id_prov='".$row['id_alta']."' AND a.id_esti='".$row['iidd']."' AND a.semana='".$row2['semana']."'  AND a.id_bit_remesas='".$row2['idbitrem']."'  AND a.proviene='".$row2['proviene']."';";
        $result5 = $mysqli->query($SQL5);
        $row5 = $result5->fetch_array();
        $semsem=$row5['imp_semana'];

        /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
        if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
        if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
        $SQL4 ="SELECT a.estatus_factura from constru_cheques a 
         where a.id_obra='$id_obra' AND a.remesa='".$row2['idremesa']."';";
        $result4 = $mysqli->query($SQL4);
        if($result4->num_rows>0){
 while($row4 = $result4->fetch_array()) {

              $stfa= $row4['estatus_factura'];
if($stfa==1){$check=' Disabled ';}else{$check='';}

          }}

        $cad.="<input style='font-size:10px;' type='checkbox' value='".$row['iidd']."' class='ccbox' style='cursor:pointer;' id='".$row['iidd']."' noremesa='".$row5['ididremesa']."'".$check." > ";
       // $cad.=$row['estimacion'].' / '.$row['Proveedor'].' /  Importe: '.$row['total'].' / Pagado: '.$sumap.' / Cheque: '.$semsem;

        $cad.=$row['estimacion'].' / '.$row['Proveedor'].' /  Importe: '.$row['total'].' / Cheque: '.$semsem.' / Factura: '.$row['factura'];

             $SQL4 ="SELECT a.* from constru_cheques a 
         where a.id_obra='$id_obra' AND a.remesa='".$row2['idremesa']."';";
        $result4 = $mysqli->query($SQL4);
        if($result4->num_rows>0){
            while($row4 = $result4->fetch_array()) {

              $stche= $row4['estatus_cheque'];
              $stfa= $row4['estatus_factura'];

              if($stche==1) $txt_stche='Depositado';
              if($stche==2) $txt_stche='Entregado';
              if($stche==3) $txt_stche='Cancelado';
              if($stche==4) $txt_stche='Devuelto';

              if($stfa==1) $txt_stfa='Pagada';
              if($stfa==2) $txt_stfa='Pago parcial';

               $responce->rows[$i]['id']=$row4['id'];
                $responce->rows[$i]['cell']=array($row2['remesa'],$row['Proveedor'],$cad,$row4['no_cheque'],'',$row4['banco'],$row4['fecha_expedicion'],$txt_stche,$txt_stfa,$row4['fecha']);
                $i++;



            }
        }else{
            $responce->rows[$i]['id']='n'.$i;
            $responce->rows[$i]['cell']=array($row2['remesa'],$row['Proveedor'],$cad);
            $i++;
        }
        
    
}
$responce->userdata = array('Importe'=>$prisum, 'Pagado_en_emesas'=>$secsum, 'Saldo_por_pagar'=>$tersum);      
echo json_encode($responce);
?>