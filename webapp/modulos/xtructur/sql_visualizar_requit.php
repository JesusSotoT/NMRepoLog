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

if(!isset($_GET['filtro_semana'])){ $filtro_semana=0; }else{ $filtro_semana=$_GET['filtro_semana']; }
if(!isset($_GET['filtro_mes'])){ $filtro_mes=0; }else{ $filtro_mes=$_GET['filtro_mes']; }
if(!isset($_GET['filtro_estatus'])){ $filtro_estatus='x'; }else{ $filtro_estatus=$_GET['filtro_estatus']; }

$SQL = "SELECT COUNT(*) AS count FROM constru_requis a
LEFT JOIN constru_requisiciones b on b.id_requi=a.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
LEFT JOIN constru_especialidad es on es.id=a.id_area
left JOIN constru_info_tdo d on d.id_alta=a.solicito
WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b.borrado=0;";
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

if($filtro_mes!=0){
   $filtro_semana=0; 
}

if($filtro_semana!=0){

    $cadenafiltro.=" AND yearweek(a.fecha_captura,1)='".$filtro_semana."' ";
}

if($filtro_mes!=0){
    $ym = explode('-', $filtro_mes);
    $cadenafiltro.=" AND YEAR(a.fecha_captura) = '".$ym[0]."' AND MONTH(a.fecha_captura) = '".$ym[1]."'";
}

if($filtro_estatus!='x'){
    $cadenafiltro.=" AND a.estatus = '".$filtro_estatus."' ";
}

$SQL = "SELECT correo_can,correo_aut from constru_config where id_obra='$id_obra';";
    $result = $mysqli->query($SQL);
    if($result->num_rows>0) {
      $row = $result->fetch_array();
        $correocan=$row['correo_can'];
           $correo_aut=$row['correo_aut'];
      }


if($correocan==1){


$SQL = "SELECT a.id, concat('RT-',d.id,' - ',d.nombre,' ',d.paterno,' ',d.materno) as Solicito, a.id reqis, 
CASE a.estatus 
WHEN 1 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"modalcancelReq(',a.id,');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizaReq(',a.id,',',$correo_aut,');\" >')
WHEN 2 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<font color=\"#ff0000\">Requisicion Cancelada</font>')
WHEN 3 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<font color=\"#070\">Requisicion Autorizada</font> ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#passmodal\"  data-eid=',a.id, ' > ')
WHEN 4 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<font color=\"#070\">Requisicion Autorizada (Con orden de compra)</font>')
END as Requisicion, es.nombre as anom,
-- concat('REQ-',a.id) as Requisicion, 
a.fecha_entrega, c.clave, c.descripcion, c.unidtext,  concat('<input rrr=\"1\" type=\"text\" name=\"num\" class=\"quis__',a.id,'\" value=\"',b.cantidad,'\" id=\"', b.id,'\" />') as Rcantidad, c.precio, b.cantidad*c.precio importe, a.id reqid, b.id reqsid, c.id insuid, f.partida, h.especialidad, 
case when a.fecha_utilizacion is null or date(a.fecha_utilizacion)='0000-00-00' then concat(emp.nombre,' ',emp.apellidos)
else concat(emp.nombre,' ',emp.apellidos,' - Fecha requerida de entrega ',substr(a.fecha_utilizacion,1,10))
end as Solicito,
CASE a.estatus 
WHEN 1 THEN 'Pendiente'
WHEN 2 THEN 'Cancelada'
WHEN 3 THEN 'Autorizada'
WHEN 4 THEN 'Autorizada (OC)'
END as estatus
FROM constru_requis a
LEFT JOIN constru_requisiciones b on b.id_requi=a.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
LEFT JOIN constru_especialidad es on es.id=a.id_area
left JOIN constru_info_tdo d on d.id_alta=a.solicito
left JOIN constru_partida e on e.id=a.id_part
left JOIN constru_cat_partidas f on f.id=e.id_cat_partida
left JOIN constru_area g on g.id=a.id_esp
left JOIN constru_cat_especialidad h on h.id=g.id_cat_especialidad
left JOIN administracion_usuarios emp on emp.idempleado = a.solicito
WHERE a.id_area is not null AND a.id_obra='$id_obra'".$cadenafiltro." AND a.borrado=0 AND b.borrado=0  ORDER BY a.id DESC, b.id, $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;}

else{

$SQL="SELECT a.id, concat('RT-',d.id,' - ',d.nombre,' ',d.paterno,' ',d.materno) as Solicito, a.id reqis, 
CASE a.estatus 
WHEN 1 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" onclick=\"cancelReq(',a.id,');\" > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizaReq(',a.id,',',$correo_aut,');\" >')
WHEN 2 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<font color=\"#ff0000\">Requisicion Cancelada</font>')
WHEN 3 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<font color=\"#070\">Requisicion Autorizada</font> ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#passmodal\"  data-eid=',a.id, ' > ')
WHEN 4 THEN concat('REQ-',a.id,' / Area: ',es.nombre,' ','<font color=\"#070\">Requisicion Autorizada (Con orden de compra)</font>')
END as Requisicion, es.nombre as anom,
-- concat('REQ-',a.id) as Requisicion, 
a.fecha_entrega, c.clave, c.descripcion, c.unidtext,  concat('<input type=\"text\" name=\"num\" class=\"quis__',a.id,'\"  value=\"',b.cantidad,'\" id=\"', b.id,'\" />') as Rcantidad, c.precio, b.cantidad*c.precio importe, a.id reqid, b.id reqsid, c.id insuid, f.partida, h.especialidad,
case when a.fecha_utilizacion is null or date(a.fecha_utilizacion)='0000-00-00' then concat(emp.nombre,' ',emp.apellidos)
else concat(emp.nombre,' ',emp.apellidos,' - Fecha requerida de entrega ',substr(a.fecha_utilizacion,1,10))
end as Solicito,
CASE a.estatus 
WHEN 1 THEN 'Pendiente'
WHEN 2 THEN 'Cancelada'
WHEN 3 THEN 'Autorizada'
WHEN 4 THEN 'Autorizada (OC)'
END as estatus
FROM constru_requis a
LEFT JOIN constru_requisiciones b on b.id_requi=a.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
LEFT JOIN constru_especialidad es on es.id=a.id_area
left JOIN constru_info_tdo d on d.id_alta=a.solicito
left JOIN constru_partida e on e.id=a.id_part
left JOIN constru_cat_partidas f on f.id=e.id_cat_partida
left JOIN constru_area g on g.id=a.id_esp
left JOIN constru_cat_especialidad h on h.id=g.id_cat_especialidad
left JOIN administracion_usuarios emp on emp.idempleado = a.solicito
WHERE a.id_area is not null AND a.id_obra='$id_obra'".$cadenafiltro." AND a.borrado=0 AND b.borrado=0  ORDER BY a.id DESC, b.id, $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;


}
$i=0;

while($row = $result->fetch_array()) {

	$SQL3 ="SELECT a.*, (a.unidad*a.precio) as tc, (a.unidad*a.precio) as tv,
	(SELECT if(sum(b.llego) is null,0, sum(b.llego)) FROM constru_entrada_almacen b where b.id_obra=a.id_obra and b.id_insumo=a.id) as sumallego,
	(SELECT if(sum(c.salio) is null,0, sum(c.salio)) FROM constru_salida_almacen c where c.id_obra=a.id_obra and c.id_insumo=a.id) as sumasalio,
	if(sum(bb.cantidad) is null,0, sum(bb.cantidad)) as salidatras,
	if(sum(cc.cantidad) is null,0, sum(cc.cantidad)) as entradatras
	FROM constru_insumos a 

	left join constru_bit_traspasos b on b.id_obra_salida=a.id_obra and b.estatus=4
	left join constru_traspasos bb on bb.id_bit_traspaso=b.id and bb.id_clave=a.id

	left join constru_bit_traspasos c on c.id_obra_entrada=a.id_obra and c.estatus=4
	left join constru_traspasos cc on cc.id_bit_traspaso=c.id and cc.id_clave_sal=a.id

	WHERE 1=1 ".$cad." AND
	 a.id_obra='$id_obra' AND a.borrado=0 AND a.naturaleza!='Adicional' AND a.id='".$row['insuid']."'
	Group by a.id LIMIT $start,$limit;";

	$result3 = $mysqli->query($SQL3);
	$row3 = $result3->fetch_array();



$SQL4 = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($row['clave'])."' AND borrado=0;";
	$result4 = $mysqli->query($SQL4);
	$row4 = $result4->fetch_array();
	$totales=$row4['totcant'];
	$restante=$totales-$row3['sumallego'];
	$almacen=$row3['sumallego']-$row3['sumasalio'];
	$almacen=($almacen+$row3['entradatras'])-$row3['salidatras'];
	$inventario=round($almacen,4);



/*
$SQL2 = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($row['clave'])."' AND borrado=0;";
	$result2 = $mysqli->query($SQL2);
	$row2 = $result2->fetch_array();
	$row['unidad']=$row2['totcant'];
	*/


	/*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
	if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
	if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $responce->rows[$i]['requis']=$row['requis'];
    $responce->rows[$i]['cell']=array($row['Requisicion'],$row['Solicito'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['Rcantidad'],$row['precio'],$row['importe'],$inventario,$restante,$row['estatus']);
    $i++;
}        
echo json_encode($responce);
?>