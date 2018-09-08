<?php 
include("../../netwarelog/webconfig.php");
$idalmacen=$_REQUEST['a'];
 $idsucursal=$_REQUEST['s'];
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$valida=$conection->query("select  a.idAlmacen,
s.nombre, a.nombre from almacen a,
mrp_sucursal s where a.idAlmacen=".$idalmacen." and s.idSuc=".$idsucursal." and a.idAlmacen=s.idAlmacen GROUP BY s.nombre
");
if($si=$valida->num_rows>0){
	echo "data";
}else{ 
   $echo=	 $conection->query("delete from almacen_sucursal where  idSucursal=".$idsucursal." and idAlmacen=".$idalmacen);
if($echo){
	echo "ok";
}else{
	echo "no";
}

}
$conection->close();
?>