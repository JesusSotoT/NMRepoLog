<?php
   function object_to_array($data) {
    if (is_array($data) || is_object($data)) {
      $result = array();
      foreach ($data as $key => $value) {
        $result[$key] = object_to_array($value);
      }
      return $result;
    }
  return $data;
  } 
//Actualiza Menus Selectivos

ini_set("display_errors",1);


  //Coneccion a Base de Datos NetwarStore - Transversal
  $strDBHost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
  $strDBUsr="nmdevel";
  $strDBPwd="nmdevel";

  $strDBName="_dbmlog0000009942"; //randazzo_demo

  $objCon = mysqli_connect($strDBHost,$strDBUsr,$strDBPwd,$strDBName);
  mysqli_query($objCon, "SET NAMES 'utf8'");
  mysqli_set_charset($objCon,"UTF-8");

  //$strSql = "select * from customer where id in (select idcustomer from appclient where idapp='1011') and status_instancia<>4";

  $strSql = "select idVenta from app_pos_venta_producto where idproducto = 0 group by idVenta;";

  $rstCustomer = mysqli_query($objCon, $strSql);
  
  //Consigue los Datos para Agregarlos a la Tabla Temporal
  $sql_NS_Values="";
  $intTr = 0;
  $azuriant = '';
  while($objCustomer=mysqli_fetch_array($rstCustomer)){
        
        echo '('.$objCustomer['idVenta'].')<br>';
        $selCadena = "SELECT cadenaOriginal from app_pendienteFactura where id_sale=".$objCustomer['idVenta'];
        $reCa = mysqli_query($objCon, $selCadena);

        $selProductos = "select * from app_pos_venta_producto where idVenta=".$objCustomer['idVenta'].' and idProducto=0';
        $reProd = mysqli_query($objCon, $selProductos);
        //$objProd=mysqli_fetch_array($reProd);
        //print_r($objProd);
        while($objCadena=mysqli_fetch_array($reCa)){
              //echo $objCadena['cadenaOriginal'];
              $azuriant=base64_decode($objCadena['cadenaOriginal']);
              $azuriant = str_replace("\\", "", $azuriant);
              if($azuriant!=''){ 
                $azuriant=json_decode($azuriant); 
              } 
              $azuriant = object_to_array($azuriant);

              $xCon = $azuriant['Conceptos']['conceptosOri'];
              //$xCon = 'botón';
              /*$xCon = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
                  return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
              }, $xCon); */

              //echo $xCon.'<br>';              
              $xCon = explode('|',$azuriant['Conceptos']['conceptosOri']);
              //print_r($xCon);
              //echo 'cantidad='.$xCon[1].' Producto='.$xCon[3].' precio='.$xCon[9].'<br>';
              for($i = 2; $i < count($xCon); $i+=5) {
                //á = u00e1
                //Á = u00c1 
                $xCon[$i+1] = str_replace('u00e1','á',$xCon[$i+1]);
                $xCon[$i+1] = str_replace('u00c1','Á',$xCon[$i+1]);
            
                //é = u00e9
                //É = u00c9
                $xCon[$i+1] = str_replace('u00e9','é',$xCon[$i+1]);
                $xCon[$i+1] = str_replace('u00c9','É',$xCon[$i+1]);
                //ì = u00eD
                //Í = u00cD
                $xCon[$i+1] = str_replace('u00eD','ì',$xCon[$i+1]);
                $xCon[$i+1] = str_replace('u00cD','Í',$xCon[$i+1]);
                //ó = u00f3
                //Ó = u00c3
                $xCon[$i+1] = str_replace('u00f3','ó',$xCon[$i+1]);
                $xCon[$i+1] = str_replace('u00c3','Ó',$xCon[$i+1]);
                //ú = u00fA
                //Ú = u00dA
                $xCon[$i+1] = str_replace('u00fA','ú',$xCon[$i+1]);
                $xCon[$i+1] = str_replace('u00dA','Ú',$xCon[$i+1]);
                //echo 'cantidad='.$xCon[$i].' producto='.$xCon[$i+1].' precio='.$xCon[$i+2].'<br>';

                

              }
         }

         while($objProd=mysqli_fetch_array($reProd)){
            //echo $objProd['id'].'<br>';
            $selId = 'SELECT id from app_productos where nombre="'.$objProd['comentario'].'";';
            $resId = mysqli_query($objCon, $selId);
            while($idProd=mysqli_fetch_array($resId)){
              $idProducto = $idProd['id'];
            }

            $upda = "UPDATE app_pos_venta_producto set idProducto='".$idProducto."' where idventa_producto='".$objProd['idventa_producto']."'";
            echo $upda.'<br>';
            //mysqli_query($objCon, $upda);
         }
 
        //$sqlINSERT="update app_pos_venta_producto set idproducto = '".$objCustomer['idproducto']."',  cantidad = '".$objCustomer['cantidad']."', subtotal = ".$objCustomer['total'].", total = ".$objCustomer['total'].", comentario = '' where idVenta = ".$objCustomer['idVenta']." and comentario = '".$objCustomer['nombre']."' and idproducto = 0;";
        //mysqli_query($objCon, $sqlINSERT);
        //(echo $sqlINSERT."<br>";
  }

echo "Proceso Concluido!!!!!!<br>";
mysqli_close($objCon);
unset($objCon);


  //echo "Tabla Temporal:<br>$sql_NST_table<br>";
  //echo "Valores:<br>$sql_NS_Values<br>";
?>
