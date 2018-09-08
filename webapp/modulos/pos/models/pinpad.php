<?php 
ini_set("display_errors", 1); error_reporting(E_ALL);
//echo dirname(__FILE__); exit();
require(dirname(__FILE__) . "/connection_sqli_manual.php"); 



 class pinpadcModel extends Connection
    {
      //public function pinpad(){
        public function pinpad($monto,$forma){
      

$sql = "INSERT INTO app_pos_venta_pendiente(monto,formadepago,estatus) VALUES ('$monto','$forma',0);";
			$last_id = $this->insert_id($sql);
          $estatus=0;


          while($estatus==0){

          $sql = "SELECT estatus FROM app_pos_venta_pendiente where id='$last_id';";
		$result = $this -> queryArray($sql);



		$estatus=$result["rows"][0]["estatus"];
  }




        if($estatus==1){

          return true;}
        elseif ($estatus==2){

        	return false;
        }
}}

//}

?>