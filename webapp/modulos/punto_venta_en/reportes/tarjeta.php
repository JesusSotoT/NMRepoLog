<?php
   $numero=$_REQUEST['nombre'];
    include("../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
   switch ($opc) {
       case 1:
         
   $sql=$conection->query("select * from tarjeta_regalo where  numero=".$numero);
   if($sql->num_rows>0){
   	echo "si";
   }else{
   	echo "no";
   }
   break;
   
   
   
   }
?>