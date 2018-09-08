<?php
  include('../../netwarelog/webconfig.php');
  ini_set("display_errors",1);

  class Prontipagos {

    var $codeTransaction;
    var $statusTransaction;
    var $codeDescription;
    var $dateTransaction;
    var $transactionID;
    var $trAmount;
    var $trSku;


    function transaccionGeneral($codeTransaction, $statusTransaction, $codeDescription, $dateTransaction, $transactionID, $trAmount, $trSku){
      $strSQL = "";

    }

    function prueba(){
      echo "Base: " . $_SESSION['bd'];
      echo "Usuario: " .$usuariobd;
      echo "Clave DB" . $clavebd;


    }
    function transaccionLocal($codeTransaction, $statusTransaction, $codeDescription, $dateTransaction, $transactionID, $amount, $sku){
      $objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
      mysqli_query($objCon, "SET NAMES 'utf8'");

      $strSQL = "insert into prontipagos_transacciones
                (codeTransaction, statusTransaction, codeDescription, dateTransaction, transactionID, amount, sku)
                values
                ('$codeTransaction', '$statusTransaction', '$codeDescription', '$dateTransaction', $transactionID, $amount, '$sku')";

      //$rstUser = mysqli_query($objCon,$strSql);
      //$objCon->query($strSQL);
      //echo $objCon->num_rows;

      //echo "DB: $bd";
      //echo $strSQL;
      //echo var_dump $rstUser;
    }
  }


  echo $servidor;

?>
