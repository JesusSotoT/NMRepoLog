<?php
include_once('conexion.php');
include_once('factura.php');
    $consult = new Consult;
    //$conection = $consult -> conection();
	include("../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
   // echo $idOfertaCli=$_REQUEST['id'];
 // $factu=$_REQUEST['res'];
  $idruta=$_REQUEST['idruta'];;
  		//$factura[];
	 //$fact[];
	
 	 	
$resu="";
$f="";
//$valor=array();
$r="";


 
 //var factura=new Array();
 
 	
 $ofertacl=$consult->rutaofertaclient($conection, $idruta);//consulta sms_ruta_oferta_cliente
while($idrutaoferta=$ofertacl->fetch_array(MYSQLI_ASSOC)){
	
         $factura= fact($idrutaoferta['idOfertacliente']);
			//$factura=explode("//", $factur);
 $upt=$conection->query("update  sms_ruta_oferta_cliente set factura='".$factura."' where idOfertacliente=".$idrutaoferta['idOfertacliente']);
  // $val=$conection->affected_rows;
if($upt){
	$uptruta=$conection->query("update  sms_ruta_oferta set status=1 where id=".$idruta);
//$valrut=$conection->affected_rows;
if($uptruta){
	$ok="2";
 }else{
	  $ok="1";
}
 // //
}else{
	  echo "Error al iniciar la ruta";
}      

           }    

	if($ok=="2"){
	echo "//2";
}else{
	echo "//1";
}
 
?>