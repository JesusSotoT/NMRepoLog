<?php
	
	$id_alm=$_REQUEST['id_alm'];//18
	$id_suc=$_REQUEST['id_suc'];//15
	$id_prim=$_REQUEST['id_prim'];//12
	$consulta="";
	$consulta.="update almacen_sucursal set idAlmacen=".$id_prim." where idAlmacen=".$id_alm." and idSucursal=".$id_suc.";";
	$consulta.="update mrp_sucursal set idAlmacen=".$id_alm." where idSuc=".$id_suc.";";

	include("../../netwarelog/webconfig.php");

	$conection1 = new mysqli($servidor,$usuariobd,$clavebd,$bd);

	$sucu=$conection1->multi_query($consulta);
	$conection1->close();
	echo $consulta;

?>