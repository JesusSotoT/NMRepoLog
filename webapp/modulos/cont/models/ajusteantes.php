<?php
$val=$_REQUEST['opc'];
include("../../../netwarelog/webconfig.php");
$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
switch ($val) {
	case 1:
		$sql=$conection->query("select * from cont_tipo_cambio where moneda=".$_REQUEST['moneda']." and fecha='".$_REQUEST['fecha']."'");
		if($sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
	case 2:
		$sql=$conection->query("select * from cont_tipo_cambio where moneda=".$_REQUEST['moneda']." and fecha='".$_REQUEST['fecha']."' and id=".$_REQUEST['idtipo']);
		if(@$sql->num_rows>0){
			echo 1;
		}else{
			echo 0;
		}
	break;
}
?>