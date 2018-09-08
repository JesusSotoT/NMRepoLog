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







$SQL = "SELECT
a.id pedis,hh.partida,  
case when a.fecha_utilizacion is null or date(a.fecha_utilizacion)='0000-00-00' then concat('REQ-',a.id,'<input type=\"checkbox\" value=\"',a.id,'\" class=\"ccbox\" style=\"cursor:pointer;\" id=\"',a.id,'\">',' <button class=\"btn btn-primary btn-xs\" onclick=\"addconcepto(',a.id,');\">Agregar concepto</button>') 
else concat('REQ-',a.id,'<input type=\"checkbox\" value=\"',a.id,'\" class=\"ccbox\" style=\"cursor:pointer;\" id=\"',a.id,'\">',' <button class=\"btn btn-primary btn-xs\" onclick=\"addconcepto(',a.id,');\">Agregar concepto</button>',' - Fecha requerida de entrega ',substr(a.fecha_utilizacion,1,10))
end as Requisicion,
concat('RT-',d.id,' - ',d.nombre,' ',d.paterno,' ',d.materno) as Solicito_Req,
 c.clave, c.descripcion, c.unidtext,  
concat('<input value=\"',b.cantidad,'\" id=\"cant_',c.id,'\" class=\"cquis_',a.id,'_\" maxic=\"',c.unidad,'\">') Rcantidad,
 -- b.cantidad Rcantidad, 
 c.precio, b.cantidad*c.precio importe, a.id pedid, b.id pedsid, c.id insuid,
concat('<input value=\"',0,'\" id=\"',c.id,'\" class=\"quis_',a.id,'_\" name=\"',a.id,'\">') llego, c.precio, c.id as id_insumo, b.cantidad, c.unidad, g.id_clave as claveCancel, e.id_pedid, b.cancelado, b.id_clave as claveins, b.id_pedido, pedi.estatus as pedistat
FROM constru_requis a
LEFT JOIN constru_requisiciones b on b.id_requi=a.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
left JOIN constru_info_tdo d on d.id_alta=a.solicito
left JOIN constru_pedidos e on e.id_requis=a.id
inner join constru_especialidad f on f.id=a.id_area
left join constru_partida h on h.id=a.id_part
left join constru_cat_partidas hh on hh.id=h.id_cat_partida
left join constru_pedis pedi on pedi.id=b.id_pedido
left join constru_ocCanceladas g on g.id_requi=a.id and g.id_clave=b.id_clave
WHERE a.estatus=3 AND a.id_obra='$id_obra' AND a.borrado=0 group by a.id, a.id, b.id  ORDER BY $sidx $sord LIMIT $start,$limit";

$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;



while($row = $result->fetch_array()) {
           if($row['id_pedido']!=0 && ($row['pedistat']==3 || $row['pedistat']==1) ){
                continue;
           }

            
           /*$SQLX="SELECT a.id_pedid, b.estatus, c.* from constru_pedidos a
                inner join constru_pedis b on b.id=a.id_pedid
                left join constru_ocCanceladas c on c.id_pedi=b.id
                 where a.id_requis='".$row['pedis']."' and c.id_clave='".$row['claveins']."';";*/
  
            

       
            

            if($row['cancelado']==1){

                    continue;
                
            }

$clave=$row['claveins'];
             $SQLp="SELECT elprov,precio_compra, fecha_captura  from constru_requisiciones where id_clave='$clave' and cancelado=0 and id_obra='$id_obra' and id_pedido!=0  order by fecha_captura desc limit 1;;";
$resultp = $mysqli->query($SQLp);
$rowp = $resultp->fetch_array();

$SQLs="SELECT a.id, c.razon_social_sp as name FROM constru_altas a 
LEFT JOIN constru_info_sp c on c.id_alta=a.id 
WHERE   a.id_obra='$id_obra' AND a.borrado=0 AND a.id_tipo_alta=5 order by c.razon_social_sp;";
$results = $mysqli->query($SQLs);

$selop='';
while($rows = $results->fetch_array()) {
    if($rows['id']==$rowp['elprov']){
    $selop.='<option selected="selected" value="'.$rows['id'].'">'.$rows['name'].'</option>';}
    else{
$selop.='<option value="'.$rows['id'].'">'.$rows['name'].'</option>';

    }
}

            
            $adjunselop='<select onchange="vermetprov();" class="selopp_'.$row['pedis'].'">'.$selop.'</select>';

            $SQL2 = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($row['clave'])."' AND borrado=0;";
            $result2 = $mysqli->query($SQL2);
            $row2 = $result2->fetch_array();
            $totcant=$row2['totcant'];


            $SQL3 = "SELECT if( sum(a.cantidad) is null,0,sum(a.cantidad) ) as vol_est from constru_requisiciones a 
            Inner join constru_requis b on b.id=a.id_requi AND b.estatus!=2
            where a.id_requi>0 AND a.sestmp>0 AND a.id_obra='$id_obra' AND a.id_clave='".$row['id_insumo']."'
            order by a.id DESC LIMIT 1;";
            $result3 = $mysqli->query($SQL3);
            $row3 = $result3->fetch_array();

            $vol_est=$row3['vol_est'];

            $real=($totcant-$vol_est)+$row['cantidad'];

            $rcantidad='<input onkeyup="validaVolumen('.$row['pedis'].','.$row['id_insumo'].');" value="'.$row['cantidad'].'" id="c_'.$row['pedis'].'_'.$row['id_insumo'].'" class="cquis_'.$row['pedis'].'_" maxic="'.$row['unidad'].'" maxicreal="'.$real.'" valoro="'.$row['cantidad'].'">';



            

    //$responce->rows[$i]['cell']=array($row['Requisicion'],$row['clave'],$row['descripcion'],$row['partida'],$row['unidtext'],$rcantidad,$row['precio'],$rowp['precio_compra'],$row['llego'],$adjunselop);


    /*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
    if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
    if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $responce->rows[$i]['requis']=$row['requis'];
    $responce->rows[$i]['cell']=array($row['Requisicion'],$row['clave'],$row['descripcion'],$row['partida'],$row['unidtext'],$rcantidad,$row['precio'],$row['llego'],$rowp['precio_compra'],$adjunselop);

    $i++;
}        
echo json_encode($responce);
?>