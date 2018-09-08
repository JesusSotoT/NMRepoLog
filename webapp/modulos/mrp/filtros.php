<?php
include_once("../../netwarelog/catalog/conexionbd.php"); 
switch (@$_POST['funcion']) {
	case 'cargaLineas2':cargaLineas2($_POST['iddepa'],$_POST['idFamilia']);break;
	case 'cargaFamilias2':cargaFamilias2($_POST['idDepartamento']);break;
	case 'cargaProductos':cargaProductos($_POST['idLinea']);break;
	case 'productosexistencias':productosexistencias($_POST['iddepa'],$_POST['idfamilia'],$_POST['idlinea']);break;
}
    function cargaproductos($id=0)
{
	if($id!=0){$filtro=" where idLinea=".$id." and vendible=1 and estatus=1";}else{$filtro="where vendible=1";}	
		
	$cbo='<select id="producto" name="producto" onchange="cargaProducto(this.value);" class="form-control">';
	  $cbo.='<option value="">-Seleccione-</option>';
	$query = mysql_query("select idProducto id,nombre  from mrp_producto ".$filtro);
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	echo $cbo;	
}
 function cargafamilias2($iddep=0)
{
	$filtro="";
	if($iddep!=0){$filtro=" where idDep=".$iddep;}
		
	$cbo='<select id="familia" name="familia" onchange="cargaLineas2('.$iddep.',this.value); loadproductos('.$iddep.',this.value,0);" class="form-control">';
	$cbo.='<option value="0">-Todos-</option>';
	$query = mysql_query("select idFam id,nombre  from mrp_familia ".$filtro);
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.utf8_decode($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	echo $cbo;	
}
 function cargalineas2($iddep=0,$idfam=0)
{
	$filtro="";
	if($idfam!=0){$filtro=" where idFam=".$idfam;}
	else
		if($iddep!=0){$filtro=" where idFam IN (SELECT idFam from mrp_familia where idDep=$iddep)";}

		
	$cbo='<select id="linea" name="linea" onchange="loadproductos('.$iddep.','.$idfam.',this.value);" class="form-control">';
	$cbo.='<option value="0">-Todos-</option>';
	$query = mysql_query("select idLin id,nombre  from mrp_linea ".$filtro);
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}
	$cbo.='</select>';
	echo $cbo;	
}
function productosexistencias($iddepa=0,$idfamilia=0,$idlinea=0)
{
	$filtro="";
	$condicional = "";
	if($idlinea!=0){
		$filtro="where idLinea=".$idlinea;
	}
	elseif($idfamilia!=0){
		$filtro="where idLinea IN (select idLin from mrp_linea where idFam=".$idfamilia.")";
	}
	elseif($iddepa!=0){
		$filtro="where idLinea IN (select idLin from mrp_linea where idFam IN (select idFam from mrp_familia where idDep=".$iddepa."))";
	}
	
	if($idlinea == 0 && $idfamilia ==0 && $iddepa == 0){
		$condicional=" where estatus = 1";
	}else{
		$condicional=" and estatus = 1";
	}

		
	$cbo='<select id="producto" name="producto" onchange="cargaexistencias(this.value);" class="form-control">';
	$query = mysql_query("select idProducto id,nombre from mrp_producto ".$filtro.$condicional." ORDER BY nombre asc");
	//print_r($query);
    if((mysql_num_rows($query)>0)){
    		  $cbo.='<option value="">-Seleccione producto-</option>';
		
    while ($row = mysql_fetch_array($query))
    {
		$cbo.='<option value="'.$row["id"].'">'.($row["nombre"]).'</option>';
	}}else{
$cbo.='<option value="">No hay productos</option>';
	}
	$cbo.='</select>';
	echo $cbo;
}
  
?>