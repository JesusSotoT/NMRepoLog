<?php
ini_set("display_errors",0);
$id=$_GET['id'];

  //Coneccion a Base de Datos NetwarStore - Transversal
  $strDBHost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
  $strDBUsr="nmdevel";
  $strDBPwd="nmdevel";
  $strDBName="netwarstore";
  $objCon = mysqli_connect($strDBHost,$strDBUsr,$strDBPwd,$strDBName);
  mysqli_query($objCon, "SET NAMES 'utf8'");

  $strSql = "SELECT ifnull(status_instancia,1) status_instancia  FROM customer where id=$id;";
  $rstCustomer = mysqli_query($objCon, $strSql);
  while($objCustomer=mysqli_fetch_array($rstCustomer)){
    $status_instancia=$objCustomer['status_instancia'];
    if ($status_instancia==2){
      $status_final=1;
    }elseif($status_instancia==1){
      $status_final=2;
    }

    $sql_marca="Update customer set status_instancia=$status_final where id=".$id;
  }
  //echo $sql_marca;
	mysqli_query($objCon, $sql_marca);

	mysqli_close($objCon);
  unset($objCon);

  echo "Marcada comom DEMO, haga clic en segundo botón del mouse y seleccione regresar";

	?>
