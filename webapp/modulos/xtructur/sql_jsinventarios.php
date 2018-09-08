<?php
$_GET   = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if(!isset($_COOKIE['xtructur'])){
	echo 323; exit();
}else{
    $cookie_xtructur = unserialize($_COOKIE['xtructur']);
    $id_obra = $cookie_xtructur['id_obra'];
}

$oper = $_POST['oper'];


if(isset($_GET['filtro_familia'])){
	$filtro_familia = $_GET['filtro_familia'];
}else{
	$filtro_familia = 0;
}

if(isset($_GET['filtro_recibio'])){
	$filtro_recibio = $_GET['filtro_recibio'];
}else{
	$filtro_recibio = 0;
}
$_search = $_GET['_search'];

$searchField = $_GET['searchField'];
$search = $_GET['searchString'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;
// connect to the database

include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){
	$id = $_POST['id'];
	$mysqli->query("UPDATE constru_insumos SET borrado=1 WHERE id in ($id);");
	exit();
}

if(isset($oper) && $oper=='add'){

	$id = $_POST['id'];
	$naturaleza = $_POST['naturaleza'];
	$descripcion = $_POST['descripcion'];
	$codigo_clave = $_POST['codigo_clave'];
	if($naturaleza=='Extra'){
		$codigo_clave = 'EXT-'.$codigo_clave;
	}elseif ($naturaleza=='No cobrable') {
		$codigo_clave = 'OTO-'.$codigo_clave;
	}
	$unidtext = $_POST['unidtext'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];
	

	$mysqli->query("INSERT INTO constru_insumos (id_obra, unidtext, naturaleza, clave, unidad, descripcion, precio) VALUES ('$id_obra', '$unidtext', '$naturaleza', '$codigo_clave','$unidad','$descripcion','$precio_costo');");

	exit();
}

if(isset($oper) && $oper=='edit'){

	$id = $_POST['id'];
	$naturaleza = $_POST['naturaleza'];
	$descripcion = $_POST['descripcion'];
	$codigo_clave = $_POST['codigo_clave'];
	if($naturaleza=='Extra'){
		$codigo_clave = 'EXT-'.$codigo_clave;
	}elseif ($naturaleza=='No cobrable') {
		$codigo_clave = 'OTO-'.$codigo_clave;
	}
	$unidtext = $_POST['unidtext'];
	$precio_costo = $_POST['precio_costo'];
	$precio_venta = $_POST['precio_venta'];
	$unidad = $_POST['unidad'];


	$mysqli->query("UPDATE constru_insumos SET unidtext='$unidtext', naturaleza='$naturaleza', clave='$codigo_clave', unidad='$unidad', descripcion='$descripcion', precio='$precio_costo' WHERE id='$id';");
	
	exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_insumos WHERE borrado=0 AND id_obra='$id_obra';";
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
	if(preg_match('/^\(/', $searchField)){

	}else{
		$searchField='a.'.$searchField;
	}
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


if($filtro_familia!=0){
	$cad.=" AND a.id_familia='".$filtro_familia."' ";
}

if($filtro_recibio!=0){
	$cad.=" AND f.id_recibio='".$filtro_recibio."' ";
}

  $SQL ="SELECT a.*,a.precio as cprecio, (a.unidad*a.precio) as tc, (a.unidad*a.precio) as tv, 
	
	(SELECT if(sum(b.llego) is null,0, sum(b.llego)) FROM constru_entrada_almacen b where b.id_obra=a.id_obra and b.id_insumo=a.id) as sumallego,
	
(SELECT if(sum(b.llego*f.precio_compra) is null,0, sum(b.llego*f.precio_compra)) FROM constru_entrada_almacen b left join constru_pedis c on c.id=b.id_oc left join constru_pedidos d on d.id_pedid=c.id left join constru_requis e on e.id=d.id_requis left join constru_requisiciones f on f.id_requi=e.id where b.id_obra=a.id_obra and b.id_insumo=a.id and b.id_insumo=f.id_clave) as totalllego,

	(SELECT if(sum(c.salio) is null,0, sum(c.salio)) FROM constru_salida_almacen c where c.id_obra=a.id_obra and c.id_insumo=a.id) as sumasalio,


(SELECT if(sum(b.salio*f.precio_compra) is null,0, sum(b.salio*f.precio_compra)) FROM constru_salida_almacen b left join constru_pedis c on c.id=b.id_oc left join constru_pedidos d on d.id_pedid=c.id left join constru_requis e on e.id=d.id_requis left join constru_requisiciones f on f.id_requi=e.id where b.id_obra=a.id_obra and b.id_insumo=a.id and b.id_insumo=f.id_clave) as totalsalio,


	if(sum(bb.cantidad) is null,0, sum(bb.cantidad)) as salidatras,
	if(sum(cc.cantidad) is null,0, sum(cc.cantidad)) as entradatras
FROM constru_insumos a 

left join constru_bit_traspasos b on b.id_obra_salida=a.id_obra and b.estatus=4
left join constru_traspasos bb on bb.id_bit_traspaso=b.id and bb.id_clave=a.id

left join constru_bit_traspasos c on c.id_obra_entrada=a.id_obra and c.estatus=4
left join constru_traspasos cc on cc.id_bit_traspaso=c.id and cc.id_clave_sal=a.id
left join constru_salida_almacen e on e.id_insumo=a.id
left join constru_bit_salidas f on f.id=e.id_bit_salida

WHERE 1=1 ".$cad." AND
 a.id_obra='$id_obra' AND a.borrado=0 AND a.naturaleza!='Adicional'
Group by a.id LIMIT $start,$limit;";

//$SQL = "SELECT a.*, (a.unidad*a.precio) as tc, (a.unidad*a.precio) as tv FROM constru_insumos a WHERE 1=1 ".$cad." AND a.id_obra='$id_obra' AND a.borrado=0 ORDER BY a.id LIMIT $start,$limit";
$result = $mysqli->query($SQL);


$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {

	$SQL2 = "SELECT sum(unidad) as totcant FROM constru_insumos WHERE id_obra='$id_obra' AND clave='".addslashes($row['clave'])."' AND borrado=0;";
	$result2 = $mysqli->query($SQL2);
	$row2 = $result2->fetch_array();
	$row['unidad']=$row2['totcant'];
 
    $cand=$row['cprecio']*$row['unidad'];
	$restante=$row['unidad']-$row['sumallego'];
	$almacen=$row['sumallego']-$row['sumasalio'];
	$almacen=($almacen+$row['entradatras'])-$row['salidatras'];

	if($row['sumallego']*$row['precio']<=0){ $tot1=0; }else{ $tot1=$row['sumallego']*$row['precio']; }
	if($row['sumasalio']*$row['precio']<=0){ $tot2=0; }else{ $tot2=$row['sumasalio']*$row['precio']; }
	if($restante*$row['precio']<=0){ $tot3=0; }else{ $tot3=$restante*$row['precio']; }
	if($almacen*$row['precio']<=0){ $tot4=0; }else{ $tot4=$almacen*$row['precio']; }

    $responce->rows[$i]['id']=$row['id'];
    $responce->rows[$i]['cell']=array($row['naturaleza'],$row['clave'],$row['descripcion'],$row['unidtext'],$row['unidad'],$cand,$row['sumallego'],$row['totalllego'],$row['sumasalio'],$row['totalsalio'],$row['entradatras'],$row['salidatras'],$restante,$tot3,$almacen,$tot4);
    $i++;
}        
echo json_encode($responce);
?>