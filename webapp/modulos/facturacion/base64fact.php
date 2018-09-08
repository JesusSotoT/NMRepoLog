<?php
ini_set("display_errors",1);

  //ini_set('display_errors', 1);
  function object_to_array($data){
    if (is_array($data) || is_object($data))
      {
          $result = array();
          foreach ($data as $key => $value){
              $result[$key] = object_to_array($value);
          }
          return $result;
      }
      return $data;
  }
	include "../../netwarelog/webconfig.php";
	include "../../netwarelog/catalog/conexionbd.php";
    
    
      $result = $conexion->consultar(" SELECT cadenaOriginal,id FROM _dbmlog0000000987.pvt_respuestaFacturacion;");
      $azuriant="";
                          
      while($rs = $conexion->siguiente($result)){
          $azuriant=base64_decode($rs{'cadenaOriginal'});
          $azuriant = str_replace("\\", "", $azuriant);
          if($azuriant!=''){ $azuriant=json_decode($azuriant); }
          $azuriant = object_to_array($azuriant);
          $foliodeserie=$azuriant["Basicos"]["folio"];
          
          if($foliodeserie!=''){ 
            
            $sqlUd="Update _dbmlog0000000987.pvt_respuestaFacturacion set foliodeserie=".$foliodeserie." where id=".$rs["id"];
            //echo  $sqlUd;
            $conexion->consultar($sqlUd);
          }
          
      }
    $conexion->cerrar_consulta($result);    


//['emisor']['folio']

?>