<?php
include_once('conexion.php');
    $consult = new Consult;
    //$conection = $consult -> conection();
	include("../../../netwarelog/webconfig.php");

$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);

   $namerut=$_REQUEST['namerut'];
	$idtrans=$_REQUEST['idtrans'];
    $total=$_REQUEST['total'];
	$client=$_REQUEST['client'];
	$idoferta=$_REQUEST['idofert'];
	
$idclient = explode(",", $client);
$trans = explode(",", $idtrans);
$consultarut=$consult->consulruta($conection, $namerut);
 
 $res=$consultarut->num_rows;
 if($res>0){
	$conection -> close();
	echo "0";
	exit;
} else{
 $inserutofert=$conection->query("INSERT INTO sms_ruta_oferta (nombre,idTransporte,status,fecha,cantidadtotal) values ('".$namerut."',".$trans[0].",2,'".date('Y-m-d H:i:s')."',".$total.")");
 if(!$inserutofert){
	 echo "1";
 }else{
 $consultaruta=$consult->consulruta($conection, $namerut);
 $r1=$consultaruta->num_rows;
 if($r1>0){
	if($ruta=$consultaruta->fetch_array(MYSQLI_ASSOC)){
		$orden=1; 
		$ok=false;
		 for($i=0;$i<count($idclient);$i++){
		 	
$consuidofer=$consult->smsOfertaClient($conection,$idclient[$i],$idoferta);
			 
			if($idofer=$consuidofer->fetch_array(MYSQLI_ASSOC)){
		
$inserutaofertclient=$conection->query("INSERT INTO sms_ruta_oferta_cliente (idOfertacliente,status,orden,idRutaOferta) values (".$idofer['id'].",0,".$orden.",".$ruta['id'].")") ;
 if($r1=$consultaruta->num_rows){
 	$global=$conection->query("UPDATE sms_oferta_cliente set estatus=1 where id=".$idofer['id']);
if($global){
	$ok=true;
}else{$ok=false;
		
}
 	
 }else{ echo "1";}
			}//if $idofer	
			$orden++;
}//del for	
	 }//if de ruta
// // 	
// // 	
}else{
 echo  "1";
}
// // 
	if($ok==true){
	echo "2";
}else{
	echo "1";
}
 }//else if(!$inserutofert

}//primer if else

	//$cli=$consult->clientes($conection,$idclient[$i]);
 // if($clientes=$cli->fetch_array(MYSQLI_ASSOC)){
  	
	
	
	
  //}//if de clientes
	