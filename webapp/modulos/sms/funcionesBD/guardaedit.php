<?php
   include_once('conexion.php');
    $consult = new Consult;
    //conection = $consult -> conection();
	include("../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

    $namerut=$_REQUEST['namerut'];
	$idtrans=$_REQUEST['idtrans'];
    $totaledit=$_REQUEST['total'];
    $client=$_REQUEST['client'];
	$idoferta=$_REQUEST['idofert'];
	$idruta=$_REQUEST['idruta'];
	$totalruta=$_REQUEST['totalruta'];
$idclient = explode(",", $client);
$trans = explode(",", $idtrans);

$total=$totalruta+($totaledit);

$consultarut=$conection->query("select * from sms_ruta_oferta where  id <> ".$idruta." and nombre='".$namerut."'");
 
 $res=$consultarut->num_rows;
 if($res>0){
	$conection -> close();
	echo "0";
	exit;
} else{ 

  if($client=="undefined"){
	$updacantidad=$conection->query("UPDATE sms_ruta_oferta SET nombre='".$namerut."', idTransporte=".$trans[0].", fecha='".date('Y-m-d H:i:s')."', cantidadtotal=".$total." WHERE id=".$idruta);
 	if($updacantidad){
 		echo "2";
 	}else{ echo "1"; }
 }else{$orden=1;
for($i=0;$i<count($idclient);$i++){
		 	
$consuidofer=$consult->smsOfertaClient($conection,$idclient[$i],$idoferta);//sacar el id de la ofertaclient
			 
			if($idofer=$consuidofer->fetch_array(MYSQLI_ASSOC)){
		
$ver=$conection->query("select * from sms_ruta_oferta_cliente where idOfertacliente=".$idofer['id']) ;
 if($ver->num_rows>0){
 	$updat=$conection->query("delete from sms_ruta_oferta_cliente where idOfertacliente=".$idofer['id']);
if($updat){
 $updastatus1=$conection->query("UPDATE sms_oferta_cliente SET estatus=0 WHERE id=".$idofer['id']);
 		 // $res1=$conection->num_rows;
 		  $updacantida=$conection->query("UPDATE sms_ruta_oferta SET nombre='".$namerut."', idTransporte=".$trans[0].", fecha='".date('Y-m-d H:i:s')."', cantidadtotal=".$total." WHERE id=".$idruta);
		  //$res2=$conection->num_rows;
		  //if(($res1 && $res2)>0 ){
		  	
		 // }
 		// echo "2";
 }else{
 	echo '1';
 }
//  



	
 }else{
$ofertclient=$conection->query("INSERT INTO sms_ruta_oferta_cliente (idOfertacliente,status,orden,idRutaOferta) values (".$idofer['id'].",0,".$orden.",".$idruta.")") ;
 
 if($ofertclient){
 $updastatus=$conection->query("UPDATE sms_oferta_cliente SET estatus=1 WHERE id=".$idofer['id']);
 $updacantidad=$conection->query("UPDATE sms_ruta_oferta SET nombre='".$namerut."', idTransporte=".$trans[0].", fecha='".date('Y-m-d H:i:s')."', cantidadtotal=".$total." WHERE id=".$idruta);
		 
 		 
 }else{
 	echo '1';
 }
 
 }		
 }
 //}
$orden++;
 }//del for	
 $consulta=$conection->query("select * from sms_ruta_oferta_cliente where  idRutaOferta=".$idruta);
$res1=$consulta->num_rows;
if($res1>0){
	echo "2";
	// $updacantidad=$conection->query("UPDATE sms_ruta_oferta SET nombre='".$namerut."', idTransporte=".$trans[0].", fecha='".date('Y-m-d H:i:s')."', cantidadtotal=".$total." WHERE id=".$idruta);
} else{
		$updat=$conection->query("delete from sms_ruta_oferta where id=".$idruta);
		echo "2";
	 // $updacantidad=$conection->query("UPDATE sms_ruta_oferta SET nombre='".$namerut."', idTransporte=".$trans[0].", fecha='".date('Y-m-d H:i:s')."', cantidadtotal=0 WHERE id=".$idruta);
}

 }//else

 
}

////////////////////////////////////////	
// if($client=="undefined"){
// 
// $valida=$conection->query("select * from sms_ruta_oferta where  and nombre='".$namerut."'");
// 
// }else{
	// echo "tienealgo";
// }	
	
	
	

 


?>