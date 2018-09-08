<?php

//Actualiza Menus Selectivos

ini_set("display_errors",0);


  //Coneccion a Base de Datos NetwarStore - Transversal
  $strDBHost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
  $strDBUsr="nmdevel";
  $strDBPwd="nmdevel";

  $strDBName="netwarstore"; //buenprovecho

  $objCon = mysqli_connect($strDBHost,$strDBUsr,$strDBPwd,$strDBName);
  mysqli_query($objCon, "SET NAMES 'utf8'");
  mysqli_set_charset($objCon,"UTF-8");

  $strSql2 = "select nombre_db from customer where id in (select idcustomer from appclient where idapp='1002') and status_instancia<>4";
  $rstCustomer2 = mysqli_query($objCon, $strSql2);
  //echo $rstCustomer;
  while($objCustomer2=mysqli_fetch_array($rstCustomer2)){
        $strSql = "select p.idproducto, pr.nombre, pr.precio, ( pr.precio *  SUM(p.cantidad) )as total, v.idVenta, SUM(p.cantidad) as cantidad from ".$objCustomer2['nombre_db'].".com_pedidos p
        LEFT JOIN ".$objCustomer2['nombre_db'].".com_comandas c ON c.id = p.idcomanda
        LEFT JOIN ".$objCustomer2['nombre_db'].".app_pos_venta v ON c.id_venta = v.idVenta
        LEFT JOIN ".$objCustomer2['nombre_db'].".app_productos pr ON pr.id = p.idproducto
        where idproducto != 0 AND dependencia_promocion = 0 AND id_promocion = 0 and idcomanda in (select id from ".$objCustomer2['nombre_db'].".com_comandas where id_venta in (select idventa from ".$objCustomer2['nombre_db'].".app_pos_venta_producto where idproducto = 0)) group by p.idproducto, v.idVenta;";
        $rstCustomer = mysqli_query($objCon, $strSql);
        while($objCustomer=mysqli_fetch_array($rstCustomer)){
              //$sqlINSERT="INSERT IGNORE INTO ".$objCustomer['nombre_db'] .".accelog_perfiles_me (idperfil, idmenu) VALUES (2, 2085), (2, 2086), (2, 2088), (2,2095), (2, 2098), (2, 2100);";
              $sqlINSERT="update ".$objCustomer2['nombre_db'].".app_pos_venta_producto set idproducto = '".$objCustomer['idproducto']."',  cantidad = '".$objCustomer['cantidad']."', subtotal = ".$objCustomer['total'].", total = ".$objCustomer['total'].", comentario = '' where idVenta = ".$objCustomer['idVenta']." and comentario = '".$objCustomer['nombre']."' and idproducto = 0;";
              //mysqli_query($objCon, $sqlINSERT);
              echo $sqlINSERT."<br>";
        }
  }

echo "Proceso Concluido!!!!!!<br>";
mysqli_close($objCon);
unset($objCon);

  //echo "Tabla Temporal:<br>$sql_NST_table<br>";
  //echo "Valores:<br>$sql_NS_Values<br>";
?>
