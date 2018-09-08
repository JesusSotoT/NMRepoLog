<?php
	
include("../../netwarelog/webconfig.php");
$conexion = new mysqli($servidor,$usuariobd,$clavebd,$bd);
$sql="select cu.account_id,cu.description,cu.manual_code from cont_accounts cu,
cont_config c where  cu.`affectable`=1 and cu.removed=0 
and cu.status=1 and cu.father_account_id=
(select c.main_father from cont_accounts c  where c.manual_code='".$_REQUEST['mayor']."')";
$cuentas = $conexion->query($sql);	
$array= array();
if($ta=$cuentas->fetch_array()){
	 $array[]= $ta['account_id'];
}
	echo json_encode($array);


?>