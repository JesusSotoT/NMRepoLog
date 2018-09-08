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
$_search = $_GET['_search'];
$searchField = $_GET['searchField'];
$search = $_GET['searchString'];

$page = $_GET['page']; // get the requested page
$limit = $_GET['rows']; // get how many rows we want to have into the grid
$sidx = $_GET['sidx']; // get index row - i.e. user click to sort
$sord = $_GET['sord']; // get the direction
if(!$sidx) $sidx =1;

include('conexiondb.php');
$mysqli->query("SET NAMES utf8");

if(isset($oper) && $oper=='del'){
	$id = $_POST['id'];
	$mysqli->query("UPDATE constru_desgloce SET borrado=1 WHERE id in ($id);");
	exit();

}
if(isset($oper) && $oper=='add'){
	//$clave = $_POST['clave'];
	//$descripcion = $_POST['descripcion'];
	//$importe = $_POST['importe'];
	//$mysqli->query("INSERT INTO constru_desgloce (id_obra, descripcion, importe) VALUES ('$id_obra','$importe');");
	//exit();
}

if(isset($oper) && $oper=='edit'){
	$importe = $_POST['importe'];

	$SQL="SELECT indirecto_campo FROM constru_proforma2 WHERE id_obra='$id_obra';";
	$result = $mysqli->query($SQL);
	if($result->num_rows>0) {
		$row = $result->fetch_array();
		$costo=$row['indirecto_campo']*1;
	}else{
		$costo=0;
	}

	$SQL="SELECT if(sum(importe) is null,0,sum(importe)) as importe from constru_desgloce where id_obra='$id_obra';";
	$result = $mysqli->query($SQL);
	if($result->num_rows>0) {
		$row = $result->fetch_array();
		$suma=$row['importe']*1;
		$sumatotal=$suma+($importe*1);
	}else{
		$sumatotal=($importe*1);
	}

	if($sumatotal>$costo){
		echo 'RP';
		exit();
	}

	$id = $_POST['id'];
	$mysqli->query("UPDATE constru_desgloce SET importe='$importe' WHERE id='$id';");
	exit();
}

$SQL = "SELECT COUNT(*) AS count FROM constru_desgloce WHERE id_obra='$id_obra' AND borrado=0;";
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

 //select * from constru_cuentas_cargo WHERE id_costo=25

$SQL = "SELECT concat('DCLAV-',a.id) as clave, a.importe, b.cargo, a.id, a.por,
if(a.por is null, concat(' <input onkeyup=\"cambiapor(',a.id,')\" value=\"0.0000\" id=\"cant_',a.id,'\" class=\"cquis__\"> '), concat(' <input onkeyup=\"cambiapor(',a.id,')\" value=\"',a.por,'\" id=\"cant_',a.id,'\" class=\"cquis__\"> ')  ) as porimput,
c.indirecto_campo
FROM constru_desgloce a 
left join constru_cuentas_cargo b on b.id=a.id_cc
inner join constru_proforma2 c on c.id_obra=a.id_obra
WHERE  1=1 AND a.id_obra='$id_obra' ".$cad."  ORDER BY a.id LIMIT $start , $limit";
$result = $mysqli->query($SQL);


$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = $result->fetch_array()) {
	$importe = ($row['por']/100)*($row['indirecto_campo']*1);
	$responce->rows[$i]['id']=$row['id'];
	$responce->rows[$i]['cell']=array($row['clave'],$row['cargo'],$row['porimput'],$importe);
	$i++;
}        
echo json_encode($responce);
?>