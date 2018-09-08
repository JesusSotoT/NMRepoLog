<?php

//Actualiza Menus Selectivos

ini_set("display_errors",1);


  //Coneccion a Base de Datos NetwarStore - Transversal
  $strDBHost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
  $strDBUsr="nmdevel";
  $strDBPwd="nmdevel";
  $strDBName="netwarstore";
  $strSalt="\$2a\$07\$store.netwarmo00000000\$";
  $objCon = mysqli_connect($strDBHost,$strDBUsr,$strDBPwd,$strDBName);
  mysqli_query($objCon, "SET NAMES 'utf8'");

  //Define arreglo de tablas para consulta de datos -->> Agregar Consulta para que genere de manera automatica las tablas de años
  $arrTables = array("netwarelog_transacciones_2016_s1","netwarelog_transacciones_2015_s2","netwarelog_transacciones_2015_s1","netwarelog_transacciones_2014_s2","netwarelog_transacciones_2014_s1","netwarelog_transacciones_2013_s2","netwarelog_transacciones_2013_s1");
  $arrRecords = array("accelog_usuarios","mrp_producto","venta","pvt_respuestaFacturacion","comun_cliente","cont_accounts","cont_polizas","com_comandas","mrp_orden_produccion");
  mysqli_set_charset($objCon,"UTF-8");

  //Cambio de producto, Appdescript netwastore.


  $strSql = "select * from customer where status_instancia<>4";
  $rstCustomer = mysqli_query($objCon, $strSql);

  //Consigue los Datos para Agregarlos a la Tabla Temporal
  $sql_NS_Values="";
  $intTr = 0;
  $sqlevalua="";
  $valmenu=0;
  while($objCustomer=mysqli_fetch_array($rstCustomer)){
    //Evalua la version por el Menu Asignado
            $vac=1;
            $sqlevalua="select count(idmenu) cuantos  from ".$objCustomer['nombre_db'] .".accelog_perfiles_me where idmenu=2051 or idmenu=1974";

              $rstevalua = mysqli_query($objCon, $sqlevalua);
              while($objevalua=mysqli_fetch_array($rstevalua)){
                $valmenu=$objevalua['cuantos'];
              }
              mysqli_free_result($rstevalua);
              if($valmenu>0){
                $vac=2;
                $sqlactualiza="update ".$objCustomer['nombre_db'] .".netwarelog_version set version=$vac";
                mysqli_query($objCon, $sqlactualiza);
              }
            echo "Version Actualizada ". $objCustomer['nombre_db'] ."-$vac <br>";

        //$sqlINSERT="INSERT IGNORE INTO ".$objCustomer['nombre_db'] .".accelog_perfiles_me (idperfil, idmenu) VALUES (2, 2202);";
        //$sqlINSERT="INSERT IGNORE INTO ".$objCustomer['nombre_db'] .".accelog_perfiles_me (idperfil, idmenu) VALUES (2, 2292);";
        //mysqli_query($objCon, $sqlINSERT);
        //echo $sqlINSERT."<br>";
  }

echo "Proceso Concluido!!!!!!<br>";
mysqli_close($objCon);
unset($objCon);

  //echo "Tabla Temporal:<br>$sql_NST_table<br>";
  //echo "Valores:<br>$sql_NS_Values<br>";
?>
