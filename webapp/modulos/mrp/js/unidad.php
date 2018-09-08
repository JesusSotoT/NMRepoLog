<?php
   $numero=$_REQUEST['nombre'];
   $opc=$_REQUEST['opc'];
    include("../../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
   switch ($opc) {
       case 1:
           
   $sql=$conection->query("select * from mrp_unidades where compuesto='".$numero."'");
   if($sql->num_rows>0){
   	echo "si";
   }else{
   	echo "no";
   }
   
     break;
	   case 2:
		$sql=$conection->query("select idUni from mrp_unidades where compuesto='".$numero."'");
   if($sql->num_rows>0){
   	echo 'siesta';
   }else{
   	echo "no";
   }   
	   break;
	   case 3:
		   $id=$_REQUEST['id'];
		   $sql=$conection->query("select * from mrp_unidades where compuesto='".$numero."' and idUni=".$id);
   if($sql->num_rows>0){
   	echo "mismo";
   }else{
   	echo "no";
   }
		   break;
	   
   }
?>