<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
    //$timesesion = $cookie_xtructur['time'];
    //$laobra=$cookie_xtructur['obra'];

}
 
include('conexiondb.php');

$SQL = "SELECT id FROM constru_presupuesto WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$id_presupuesto=$row['id'];

$SQL = "SELECT correo_can FROM constru_config WHERE id_obra='$id_obra';";
$result = $mysqli->query($SQL);
$row = $result->fetch_array();
$correo=$row['correo_can'];

$oper = $_POST['oper'];
$id_partida = 0;
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];


$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction


//Filtros nuevos
if(!isset($_GET['filtro_semana'])){ $filtro_semana=0; }else{ $filtro_semana=$_GET['filtro_semana']; }
if(!isset($_GET['filtro_mes'])){ $filtro_mes=0; }else{ $filtro_mes=$_GET['filtro_mes']; }
if(!isset($_GET['filtro_estatus'])){ $filtro_estatus='x'; }else{ $filtro_estatus=$_GET['filtro_estatus']; }
if(!isset($_GET['filtro_proveedor'])){ $filtro_proveedor=0; }else{ $filtro_proveedor=$_GET['filtro_proveedor']; }
//Fin filtros nuevos

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
$SQL = "SELECT x.id lol, c.clave, c.descripcion, c.unidtext,  b.cantidad Rcantidad, c.precio, b.cantidad*b.precio_compra importe, b.cantidad*c.precio importec, a.id pedid, b.id pedsid, c.id insuid,
 b.precio_compra, x1.*
FROM constru_estimaciones_bit_prov x
LEFT JOIN constru_pedis a on a.id=x.id_oc
LEFT JOIN constru_pedidos b1 on b1.id_pedid=a.id
LEFT JOIN constru_requis c1  on c1.id=b1.id_requis
LEFT JOIN constru_requisiciones b on b.id_requi=c1.id
LEFT JOIN constru_insumos c on c.id=b.id_clave
LEFT JOIN constru_estimaciones_prov x1 on x1.id_clave=c.id AND x1.id_bit_prov=x.id
WHERE x.id_obra='$id_obra' AND x.borrado=0 AND b1.borrado=0 AND x.id='$id_des' ORDER BY x.id desc, c.id desc, $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;



while($row = $result->fetch_array()) {
    $responce->rows[$i]['lol']=$i;
    $responce->rows[$i]['cell']=array($row['clave'],$row['descripcion'],$row['unidtext'],$row['Rcantidad'],$row['precio_compra'],$row['importe'],$row['vol_anterior'],$row['vol_gris'],$row['vol_acu'],$row['vol_eje'],$row['imp_est']);
    $i++;
}   
*/

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
    $cadenafiltro.=" AND a.estatus = '".$filtro_estatus."' ";
}

if($filtro_proveedor!=0){
    $cadenafiltro.=" AND a.id_prov = '".$filtro_proveedor."' ";
}


if($correo==1){

$SQL = "SELECT
concat(b.razon_social_sp) as Proveedor,
CASE a.estatus 
WHEN 0 THEN concat('Estimacion-',a.id,' OC-',a.id_oc, ' Fecha: ',substr(fecha,1,10),' ','<input type=\"button\" value=\"Cancelar\" style=\"cursor:pointer\" data-toggle=\"modal\" data-target=\"#mailmodal\"  data-eid=',a.id, ' > ',' <input type=\"button\" value=\"Autorizar\" style=\"cursor:pointer\" onclick=\"autorizarestAll(\'prov\',',a.id,',',1,',',0,');\" >')
WHEN 2 THEN concat('Estimacion-',a.id,' OC-',a.id_oc, ' Fecha: ',substr(fecha,1,10),' ','<font color=\"#ff0000\">Estimacion Cancelada</font>')
WHEN 1 THEN concat('Estimacion-',a.id,' OC-',a.id_oc, ' Fecha: ',substr(fecha,1,10),' ','<font color=\"#070\">Estimacion Autorizada</font>')
END as Estimacion,
wc.clave, wc.descripcion, wc.unidtext, wb.cantidad Rcantidad, 
wc.precio, wb.cantidad*wb.precio_compra importe, wb.cantidad*wc.precio importec, a.id pedid, wb.id pedsid, wc.id insuid,
 wb.precio_compra, wx1.*,  if(wb.elprov is null,wa.id_prov,wb.elprov) as prreal, wa.id_prov as prrep


FROM constru_estimaciones_bit_prov a
inner join constru_info_sp b on b.id_alta=a.id_prov
inner join constru_altas alt on alt.id=b.id_alta
LEFT JOIN constru_pedis wa on wa.id=a.id_oc
LEFT JOIN constru_pedidos wb1 on wb1.id_pedid=wa.id
LEFT JOIN constru_requis wc1  on wc1.id=wb1.id_requis
LEFT JOIN constru_requisiciones wb on wb.id_requi=wc1.id
LEFT JOIN constru_insumos wc on wc.id=wb.id_clave
LEFT JOIN constru_estimaciones_prov wx1 on wx1.id_clave=wc.id AND wx1.id_bit_prov=a.id
-- inner join constru_estimaciones_prov c on c.id_bit_prov=a.id
-- left join constru_recurso d on d.id=c.id_insumo
-- left join constru_vol_tope e on e.id_clave=d.id AND (e.id_area=a.id_area or e.id_area=c.id_area)
WHERE a.id_obra='$id_obra'".$cadenafiltro."  AND a.borrado=0  AND alt.id_tipo_alta=5 AND (alt.estatus='Alta' OR alt.estatus='Incapacitado') group by wx1.id  ORDER BY  $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;}
else{
$SQL = "SELECT
concat(b.razon_social_sp) as Proveedor,
CASE a.estatus 
WHEN 1 THEN concat('Estimacion-',a.id,' OC-',a.id_oc, ' Fecha: ',substr(fecha,1,10),' ','<font color=\"#070\">Estimacion Autorizada</font>')
END as Estimacion,
wc.clave, wc.descripcion, wc.unidtext, wb.cantidad Rcantidad, 
wc.precio, wb.cantidad*wb.precio_compra importe, wb.cantidad*wc.precio importec, a.id pedid, wb.id pedsid, wc.id insuid,
 wb.precio_compra, wx1.*,  if(wb.elprov is null,wa.id_prov,wb.elprov) as prreal, wa.id_prov as prrep


FROM constru_estimaciones_bit_prov a
inner join constru_info_sp b on b.id_alta=a.id_prov
inner join constru_altas alt on alt.id=b.id_alta
LEFT JOIN constru_pedis wa on wa.id=a.id_oc
LEFT JOIN constru_pedidos wb1 on wb1.id_pedid=wa.id
LEFT JOIN constru_requis wc1  on wc1.id=wb1.id_requis
LEFT JOIN constru_requisiciones wb on wb.id_requi=wc1.id
LEFT JOIN constru_insumos wc on wc.id=wb.id_clave
LEFT JOIN constru_estimaciones_prov wx1 on wx1.id_clave=wc.id AND wx1.id_bit_prov=a.id
-- inner join constru_estimaciones_prov c on c.id_bit_prov=a.id
-- left join constru_recurso d on d.id=c.id_insumo
-- left join constru_vol_tope e on e.id_clave=d.id AND (e.id_area=a.id_area or e.id_area=c.id_area)
WHERE a.id_obra='$id_obra'".$cadenafiltro."  AND a.estatus=1 And a.borrado=0  AND alt.id_tipo_alta=5 AND (alt.estatus='Alta' OR alt.estatus='Incapacitado') group by wx1.id  ORDER BY  $sidx $sord LIMIT $start,$limit";
$result = $mysqli->query($SQL);

$count=$result->num_rows;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

}
$i=0;
/*
$folders=scandir('csvxlsm');
foreach ($folders as $f => $nom) {
 	if($nom=='.'||$nom=='..'){
        continue;
    }

	$var1=explode('-',$nom);

	if($var1[0]=='aestprov'){
		$diff=time()-substr($var1[1],0,-4);
		//echo floor($diff / (60*60) ).' - ';
	}
}

$fp = fopen('csvxlsm/aestprov-'.$timesesion.'.csv', 'w');
fputcsv($fp, array('Obra seleccionada: '.$laobra));
fputcsv($fp, array(' '));
fputcsv($fp, array('Reporte de Estimacion de Proveedores | Generado el dia: '.date('Y-m-d')));
fputcsv($fp, array(' '));
fputcsv($fp,array('Proveedor','Estimacion','Clave','Descripcion','Unidad','Volumen OC','PU Compra','Importe','Vol. Anterior','Entrada','Acumulado','Por ejecutar','Imp. Estimacion','Estatus'));

*/
while($row = $result->fetch_array()) {
	if($row['prreal']!=$row['prrep']){
        continue;
    }

    
    //fputcsv($fp, array($row['Proveedor'],$row['Estimacion'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['vol_acu'],$row['precio_compra'],$row['importe'],$row['vol_anterior'],$row['vol_gris'],$row['vol_acu'],$row['vol_eje'],$row['imp_est']));

    $responce->rows[$i]['id']=$i;
    $responce->rows[$i]['cell']=array($row['Proveedor'],$row['Estimacion'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['vol_acu'],$row['precio_compra'],$row['importe'],$row['vol_anterior'],$row['vol_gris'],$row['vol_acu'],$row['vol_eje'],$row['imp_est']);
    $i++;


}
//fclose($fp); 

echo json_encode($responce);
?>