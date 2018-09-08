<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}
 

//Filtros nuevos
if(!isset($_GET['filtro_semana'])){ $filtro_semana=0; }else{ $filtro_semana=$_GET['filtro_semana']; }
if(!isset($_GET['filtro_mes'])){ $filtro_mes=0; }else{ $filtro_mes=$_GET['filtro_mes']; }
if(!isset($_GET['filtro_estatus'])){ $filtro_estatus='x'; }else{ $filtro_estatus=$_GET['filtro_estatus']; }
if(!isset($_GET['filtro_proveedor'])){ $filtro_proveedor=0; }else{ $filtro_proveedor=$_GET['filtro_proveedor']; }
//Fin filtros nuevos



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

include('conexiondb.php');
$mysqli->query("set names 'utf8'");
$SQL = "SELECT correo_can FROM constru_config WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$correo=$row['correo_can'];

if(isset($oper) && $oper=='del'){
    $id = $_POST['id'];
    $mysqli->query("UPDATE constru_estimaciones_destajista SET borrado=1 WHERE id in ($id);");
    exit();
}


if(isset($oper) && $oper=='add'){
    $clave = $_POST['clave'];
    $concepto = $_POST['concepto'];
    $unidtext = $_POST['unidtext'];
    $cantidad = $_POST['cantidad'];
    $pu_indirecto = $_POST['pu_indirecto'];

    $SQL="SELECT id FROM constru_estimaciones_indirectos WHERE id_obra='$id_obra' AND sestmp='$sestmp' AND id_bit_insumos=0 and borrado=0 AND id_insumo='$id_clave';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
        echo 'RP';
        exit();
    }


    $mysqli->query("INSERT INTO constru_estimaciones_indirectos (id_obra,clave,id_bit_indirectos,concepto,sestmp,unidtext,cantidad,pu_indirecto) VALUES ('$id_obra', '$clave',0, '$concepto','$sestmp','$unidtext','$cantidad','$pu_indirecto');");
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
// connect to the database
$SQL="SELECT COUNT(*) AS count FROM constru_estimaciones_chica WHERE id_obra='$id_obra' AND sestmp>0  and borrado=0;";
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


//Filtors nuevos
$cadenafiltro = '';
if($filtro_mes!=0){
   $filtro_semana=0; 
}

if($filtro_semana!=0){

    $cadenafiltro.=" AND yearweek(fecha,1)='".$filtro_semana."' ";
}

if($filtro_mes!=0){
    $ym = explode('-', $filtro_mes);
    $cadenafiltro.=" AND YEAR(fecha) = '".$ym[0]."' AND MONTH(fecha) = '".$ym[1]."'";
}

if($filtro_estatus!='x'){
    $cadenafiltro.=" AND e.estatus = '".$filtro_estatus."' ";
}

if($filtro_proveedor!=0){
    $cadenafiltro.=" AND a.id_proveedor = '".$filtro_proveedor."' ";
}

if($correo==1){
$SQL = "SELECT a.id_proveedor,
CASE e.estatus 
WHEN 0 THEN concat('Estimacion-',e.id,' Fecha: ',substr(fecha,1,10),' <input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#mailmodal\"  data-eid=',e.id, ' >',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'cc\',',e.id,',',1,',',0,');\" >')
WHEN 2 THEN concat('Estimacion-',e.id,' Fecha: ',substr(fecha,1,10),' <font color=\"#ff0000\">Estimacion Cancelada</font>')
WHEN 1 THEN concat('Estimacion-',e.id,' Fecha: ',substr(fecha,1,10),' <font color=\"#070\">Estimacion Autorizada</font>')
END as Estimacion,
concat('PROV-',b.id,' ',c.razon_social_sp) prov, a.concepto, a.unidad, a.cantidad, a.val_fact, a.val_fact as importe, (a.val_fact)*(a.iva/100) as iva, ((a.val_fact)*(a.iva/100)) + (a.val_fact) as total, a.factura, d.cargo
 from constru_estimaciones_bit_chica e
    left join constru_estimaciones_chica a on e.id=a.id_bit_chica
       left join constru_cuentas_cargo d on d.id=a.id_cc
     left join constru_altas b on b.id=a.id_proveedor
      left join constru_info_sp c on c.id_alta=b.id   
      where e.id_obra=$id_obra ".$cadenafiltro."
      ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);



$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;}
else{
$SQL = "SELECT a.id_proveedor,
CASE e.estatus 
WHEN 0 THEN concat('Estimacion-',e.id,' Fecha: ',substr(fecha,1,10),' <input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'cc\',',e.id,',',2,',','0',');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'cc\',',e.id,',',1,',',0,');\" >')
WHEN 2 THEN concat('Estimacion-',e.id,' Fecha: ',substr(fecha,1,10),' <font color=\"#ff0000\">Estimacion Cancelada</font>')
WHEN 1 THEN concat('Estimacion-',e.id,' Fecha: ',substr(fecha,1,10),' <font color=\"#070\">Estimacion Autorizada</font>')
END as Estimacion,
concat('PROV-',b.id,' ',c.razon_social_sp) prov, a.concepto, a.unidad, a.cantidad, a.val_fact, a.val_fact as importe, (a.val_fact)*(a.iva/100) as iva, ((a.val_fact)*(a.iva/100)) + (a.val_fact) as total, a.factura, d.cargo
 from constru_estimaciones_bit_chica e
    left join constru_estimaciones_chica a on e.id=a.id_bit_chica
       left join constru_cuentas_cargo d on d.id=a.id_cc
     left join constru_altas b on b.id=a.id_proveedor
      left join constru_info_sp c on c.id_alta=b.id   
      where e.id_obra=$id_obra ".$cadenafiltro."
      ORDER BY $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);



$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;}


$i=0;


while($row = $result->fetch_array()) {
    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['Estimacion'],$row['prov'],$row['concepto'],$row['unidad'],$row['cantidad'],$row['val_fact'],$row['importe'],$row['iva'],$row['total'],$row['factura'],$row['cargo']);
    $i++;
}        


echo json_encode($responce);

/*$SQL = "SELECT
concat(b.razon_social_sp) as Subcontratista,
CASE a.estatus 
WHEN 0 THEN concat('Estimacion-',a.id,' Semana: ',a.xxano,' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'sub\',',a.id,',',2,');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'sub\',',a.id,',',1,');\" >')
WHEN 2 THEN concat('Estimacion-',a.id,' Semana: ',a.xxano,' ','<font color=\"#ff0000\">Estimacion Cancelada</font>')
WHEN 1 THEN concat('Estimacion-',a.id,' Semana: ',a.xxano,' ','<font color=\"#070\">Estimacion Autorizada</font>')
END as Estimacion,
d.codigo, d.descripcion, d.unidtext, d.pu_subcontrato, e.vol_tope*d.pu_subcontrato as total, c.vol_anterior,
 c.vol_estimacion, e.vol_tope
FROM constru_estimaciones_bit_subcontratista a
inner join constru_info_sp b on b.id_alta=a.id_subcontratista
inner join constru_altas alt on alt.id=b.id_alta
inner join constru_estimaciones_subcontratista c on c.id_bit_subcontratista=a.id
left join constru_recurso d on d.id=c.id_insumo
left join constru_vol_tope e on e.id_clave=d.id AND (e.id_area=a.id_area or e.id_area=c.id_area)
WHERE a.id_obra='$id_obra'  AND c.borrado=0  AND a.borrado=0 AND alt.id_tipo_alta=4 AND (alt.estatus='Alta' OR alt.estatus='Incapacitado') ORDER BY  $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);*/
?>



