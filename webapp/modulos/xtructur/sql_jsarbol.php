<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

ini_set('memory_limit','256M');
ini_set('display_errors', 1);
set_time_limit(300);
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


	$total_pages = 1;

if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
if($start<0) $start=0;
if($_search=='true'){
	$soper=$_GET['searchOper'];
	$searchField='e.'.$searchField;
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


/* $SQL = "SELECT a.codigo Agrupador, a.nombre anom, b.codigo Area, b.nombre arnom, c.codigo Especialidad, c.nombre espnom, d.codigo Partida, d.nombre prtnom, e.*, (e.unidad*e.precio_venta) as importet, e.precio_venta*(1-((f.por_utilidad/100)+(f.por_indirecto/100)+(f.factor_salario/100))) as pdes, e.precio_venta*(1-((f.por_utilidad/100)+(f.por_indirecto/100))) as psub, a.id as agrid, b.id as espid, c.id as areid, d.id as parid, e.id as recid */
$SQL = "SELECT da.partida as prtnom, ba.especialidad as espnom, a.codigo Agrupador, a.nombre anom, b.codigo Area, b.nombre arnom, c.codigo Especialidad,  d.codigo Partida, d.nombre prtnomv, e.*, (e.unidad*e.precio_venta) as importet, e.pu_destajo as pdes, e.pu_subcontrato as psub, a.id as agrid, b.id as espid, c.id as areid, d.id as parid, e.id as recid,
 if(v.vol_tope is null,concat('<input value=\"',e.unidad,'\" rcant=\"',e.unidad,'\" rant=\"',0,'\" id=\"',e.id,'\" class=\"quis_rec_\" par=\"',d.id,'\" name=\"',b.id,'\">'),concat('<input value=\"',v.vol_tope,'\" rcant=\"',e.unidad,'\" rant=\"',v.vol_tope,'\" id=\"',e.id,'\" class=\"quis_rec_\" par=\"',d.id,'\" name=\"',b.id,'\">')) vtope, d.id as idpp, e.id as idprod, b.id as idarr
FROM constru_agrupador a 
left join constru_especialidad b on b.id_agrupador=a.id
left join constru_area c on c.id_especialidad=b.id
left join constru_cat_especialidad ba on ba.id=c.id_cat_especialidad
left join constru_partida d on d.id_area=c.id
left join constru_cat_partidas da on da.id=d.id_cat_partida
left join constru_asignaciones g on g.id_partida=d.id AND g.id_obra='$id_obra'
left join constru_recurso e on e.id=g.id_recurso AND e.id_obra='$id_obra'
left join constru_vol_tope v on v.id_clave=g.id_recurso AND v.id_area=b.id
where 1=1 ".$cad."  AND v.id_partida=d.id AND a.id_obra='$id_obra' AND a.borrado=0 AND c.borrado=0 AND d.borrado=0 AND da.borrado=0 AND e.borrado=0 
ORDER BY a.id, b.id, c.id, d.id asc, g.id, e.id, $sidx $sord LIMIT $start,$limit";


$result = $mysqli->query($SQL);
$count=$result->num_rows;

@$responce->page = 1;
$responce->total = 1;
$responce->records = $count;
$i=0;

$d=1;

while($row = $result->fetch_array()) {


	/*$S="UPDATE constru_vol_tope SET id_partida='".$row['idpp']."' WHERE id_obra='$id_obra' AND id_clave='".$row['idprod']."' AND id_area='".$row['idarr']."' AND id_partida=0 AND borrado=0; ";
	$mysqli->query($S);*/



	if($row['recid']!=''){ $idn='R-'.$row['recid'].'-'.$d; }
	if($row['recid']=='' && $row['parid']!=''){ $idn='P-'.$row['parid'].'-'.$d; }
	if($row['recid']=='' && $row['parid']==''  && $row['areid']!=''){ $idn='A-'.$row['areid'].'-'.$d; }
	if($row['recid']=='' && $row['parid']==''  && $row['areid']=='' && $row['espid']!=''){ $idn='E-'.$row['espid'].'-'.$d; }
	if($row['recid']=='' && $row['parid']==''  && $row['areid']=='' && $row['espid']=='' && $row['agrid']!=''){ $idn='A-'.$row['agrid'].'-'.$d; }



    $responce->rows[$i]['id']=$idn;
    $responce->rows[$i]['cell']=array($row['anom'],$row['arnom'],$row['espnom'],$row['prtnom'],$row['naturaleza'],$row['codigo'],$row['descripcion'],$row['unidtext'],$row['unidad'],$row['precio_venta'],$row['importet'],$row['pdes'],$row['psub'],$row['vtope']);
    $i++;
    $d++;
}        
echo json_encode($responce);
?>