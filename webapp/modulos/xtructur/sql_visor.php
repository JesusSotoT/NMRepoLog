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

$SQL = "SELECT correo,correo_can FROM constru_config WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$correo=$row['correo'];
$correocan=$row['correo_can'];

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

session_start();
if (in_array($_SESSION['accelog_nombre_instancia'], $cookie_xtructur['instancias_acontia'])){
 	$adjuntaXML=" <a style=\"margin-top:4px\" onclick=\"adjuntarxmlinfo(',a.id,');\"  class=\"btn btn-default btn-xs\"><span class=\"glyphicon glyphicon-info-sign\"></span> Factura\'s adjuntas</a>";
}else{
	$adjuntaXML="";
}


if($correo==1){

$SQL = "SELECT
CASE a.estatus 
WHEN 1 THEN concat('OC-',' ',if(a.fechaaut is null,'',concat(' - ',substr(a.fechaaut,1,10))),' ',a.id,' ',' Pendiente')
WHEN 2 THEN concat('OC-',' ',if(a.fechaaut is null,'',concat(' - ',substr(a.fechaaut,1,10))),' ',a.id,' ','<font color=\"#ff0000\">Orden Cancelada</font>')
WHEN 3 THEN concat('OC-',' ',if(a.fechaaut is null,'',concat(' - ',substr(a.fechaaut,1,10))),' ',a.id,' ','<font color=\"#070\">Orden Autorizada</font> ".$adjuntaXML." ')
END as Orden, 
if(a.idaut is null, concat(' ',emp.nombre, ' ', emp.apellido1, ' ', emp.apellido2),concat(' ',emp.nombre, ' ', emp.apellido1, ' ', emp.apellido2,' / Autorizo: ',emp2.nombre, ' ', emp2.apellido1, ' ', emp2.apellido2)) as Solicito,
-- concat('RT-',d.id,' - ',d.nombre,' ',d.paterno,' ',d.materno) as Solicito, 
a.id pedis, 
-- concat('REQ-',a.id) as Requisicion, 
concat('REQ-',c1.id) Requisicion,
a.fecha_entrega, c.clave, c.descripcion, c.unidtext,  b.cantidad Rcantidad, c.precio, b.cantidad*b.precio_compra importe, b.cantidad*c.precio importec, a.id pedid, b.id pedsid, c.id insuid,concat('IDPROV-',e2.id_alta,' - ',e2.razon_social_sp) prov, es.nombre as area, f.partida, h.especialidad,
CASE a.estatus 
WHEN 1 THEN 'Pendiente'
WHEN 2 THEN 'Cancelada'
WHEN 3 THEN 'Autorizada'
END as estatus,-- b.precio_compra
CASE a.estatus
WHEN 1 THEN b.precio_compra
WHEN 2 THEN can.precio_compra
WHEN 3 THEN b.precio_compra
END as precio_compra,
CASE a.estatus
WHEN 1 THEN b.cantidad
WHEN 2 THEN can.cantidad
WHEN 3 THEN b.cantidad
END as cantvalida,
if(b.elprov is null,a.id_prov,b.elprov) as prreal, e2.id_alta as prrep, b.id_pedido
FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id and b.borrado=0
LEFT JOIN constru_insumos c on c.id=b.id_clave

LEFT JOIN constru_ocCanceladas can on can.id_pedi=a.id AND can.id_requi=b1.id_requis and can.id_clave=c.id

left JOIN empleados emp on emp.idempleado=a.solicito
left JOIN empleados emp2 on emp2.idempleado=a.idaut
left JOIN constru_info_sp e2 on e2.id_alta=a.id_prov
LEFT JOIN constru_especialidad es on es.id=c1.id_area
left JOIN constru_partida e on e.id=c1.id_part
left JOIN constru_cat_partidas f on f.id=e.id_cat_partida
left JOIN constru_area g on g.id=c1.id_esp
left JOIN constru_cat_especialidad h on h.id=g.id_cat_especialidad
WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 ORDER BY a.id desc, b.id, $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

}
else{

$SQL = "SELECT
CASE a.estatus 
WHEN 1 THEN concat('OC-',a.id,' ',if(a.fechaaut is null,'',concat(' - ',substr(a.fechaaut,1,10))),' ','Pendiente')
WHEN 2 THEN concat('OC-',a.id,' ',if(a.fechaaut is null,'',concat(' - ',substr(a.fechaaut,1,10))),' ','<font color=\"#ff0000\">Orden Cancelada</font>')
WHEN 3 THEN concat('OC-',a.id,' ',if(a.fechaaut is null,'',concat(' - ',substr(a.fechaaut,1,10))),' ','<font color=\"#070\">Orden Autorizada</font> ".$adjuntaXML." ')
END as Orden, 
if(a.idaut is null, concat(' ',emp.nombre, ' ', emp.apellido1, ' ', emp.apellido2),concat(' ',emp.nombre, ' ', emp.apellido1, ' ', emp.apellido2,' / Autorizo: ',emp2.nombre, ' ', emp2.apellido1, ' ', emp2.apellido2)) as Solicito,
-- concat('RT-',d.id,' - ',d.nombre,' ',d.paterno,' ',d.materno) as Solicito, 
a.id pedis, 
-- concat('REQ-',a.id) as Requisicion, 
concat('REQ-',c1.id) Requisicion,
a.fecha_entrega, c.clave, c.descripcion, c.unidtext,  b.cantidad Rcantidad, c.precio, b.cantidad*b.precio_compra importe, b.cantidad*c.precio importec, a.id pedid, b.id pedsid, c.id insuid,concat('IDPROV-',e2.id_alta,' - ',e2.razon_social_sp) prov, es.nombre as area, f.partida, h.especialidad,
CASE a.estatus 
WHEN 1 THEN 'Pendiente'
WHEN 2 THEN 'Cancelada'
WHEN 3 THEN 'Autorizada'
END as estatus,-- b.precio_compra
CASE a.estatus
WHEN 1 THEN b.precio_compra
WHEN 2 THEN can.precio_compra
WHEN 3 THEN b.precio_compra
END as precio_compra,
CASE a.estatus
WHEN 1 THEN b.cantidad
WHEN 2 THEN can.cantidad
WHEN 3 THEN b.cantidad
END as cantvalida,
if(b.elprov is null,a.id_prov,b.elprov) as prreal, e2.id_alta as prrep, b.id_pedido
FROM constru_pedis a
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id and b.borrado=0
LEFT JOIN constru_insumos c on c.id=b.id_clave

LEFT JOIN constru_ocCanceladas can on can.id_pedi=a.id AND can.id_requi=b1.id_requis and can.id_clave=c.id

left JOIN empleados emp on emp.idempleado=a.solicito
left JOIN empleados emp2 on emp2.idempleado=a.idaut
left JOIN constru_info_sp e2 on e2.id_alta=a.id_prov
LEFT JOIN constru_especialidad es on es.id=c1.id_area
left JOIN constru_partida e on e.id=c1.id_part
left JOIN constru_cat_partidas f on f.id=e.id_cat_partida
left JOIN constru_area g on g.id=c1.id_esp
left JOIN constru_cat_especialidad h on h.id=g.id_cat_especialidad
WHERE a.id_obra='$id_obra' AND a.borrado=0 AND b1.borrado=0 ORDER BY a.id desc, b.id, $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

}

 
$i=0;



$uppedidos='';
while($row = $result->fetch_array()) {



	if($row['id_pedido']==0){
		if($row['prreal']!=$row['prrep']){
			continue;
		}
	}

	if($row['id_pedido']>0){
		if($row['pedis']!=$row['id_pedido'] && $row['estatus']!='Cancelada'){
			continue;
		}
	}

	if($row['cantvalida']==null){
		continue;
	}


	$importec=$row['cantvalida']*$row['precio'];
	$importe=$row['cantvalida']*$row['precio_compra'];
	/*if($row['reqid']!=''){ $idn='REQ-'.$row['reqid']; }
	if($row['reqid']=='' && $row['reqsid']!=''){ $idn='REQS-'.$row['parid']; }
	if($row['reqid']=='' && $row['reqsid']==''  && $row['areid']!=''){ $idn='INS-'.$row['areid']; }*/
    $responce->rows[$i]['requis']=$row['requis'];
    $responce->rows[$i]['cell']=array($row['Orden'],$row['Solicito'],$row['Requisicion'],$row['prov'],$row['area'],$row['especialidad'],$row['partida'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['cantvalida'],$row['precio'],$importec,$row['precio_compra'],$importe);
    $i++;
}        
echo json_encode($responce);
?>