<?php
ini_set("display_errors",0);


$strDBHost ="unmdbplus.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
$strDBUsr="unmdev";
$strDBPwd="&=98+69unmdev";
$bdnmadmin="_dbmlog0000003550";

  //Coneccion a Base de Datos NetwarStore - Transversal
  //$strDBHost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
  //$strDBUsr="nmdevel";
  //$strDBPwd="nmdevel";
  $strDBName="netwarstore";
  $objCon = mysqli_connect($strDBHost,$strDBUsr,$strDBPwd,$strDBName);
  mysqli_query($objCon, "SET NAMES 'utf8'");

  //Define arreglo de tablas para consulta de datos -->> Agregar Consulta para que genere de manera automatica las tablas de años
  $arrTables = array("netwarelog_transacciones_2016_s2");
  $arrRecords = array("accelog_usuarios","mrp_producto","venta","pvt_respuestaFacturacion","comun_cliente","cont_accounts","cont_polizas","com_comandas","mrp_orden_produccion");
  mysqli_set_charset($objCon,"UTF-8");
  $strSql = "SELECT * FROM customer WHERE idclient <> 0 ORDER BY fecha DESC, id DESC;";
  $rstCustomer = mysqli_query($objCon, $strSql);

  //Consigue los Datos para Agregarlos a la Tabla Temporal
  $sql_NS_Values="";
  $intTr = 0;
  while($objCustomer=mysqli_fetch_array($rstCustomer)){
  //if ($intTr<=10) { //Pruebas Lotes
      $intTr++;
      //Consiguiendo Fecha Ultimo Acceso
      $dteLastAccess = "1900-01-01";
      for($intIx=0;$intIx<count($arrTables);$intIx++){
          $strSql = "SELECT count(*) AS 'count' FROM information_schema.tables WHERE table_schema = '" . $objCustomer['nombre_db'] . "' AND table_name = '" . $arrTables[$intIx] . "';";
          $rstTable = mysqli_query($objCon,$strSql);

          while($objTable=mysqli_fetch_array($rstTable)){
              if($objTable['count']>0){
                  $bdcliente=$objCustomer['nombre_db'];
                  $strSql = "SELECT max(fecha) AS 'fecha' FROM " . $objCustomer['nombre_db'] . "." .$arrTables[$intIx] . " WHERE nombreproceso = 'ACCELOG - acceso concedido' AND usuario NOT IN (SELECT usuario FROM " . $objCustomer['nombre_db'] . ".accelog_usuarios WHERE idempleado = 1) ;";
                  $rstLastAccess = mysqli_query($objCon, $strSql);
                  if(mysqli_num_rows($rstLastAccess)>0){
                      while ($objLastAccess = mysqli_fetch_array($rstLastAccess)) {
                          if(!is_null($objLastAccess['fecha'])) {
                              $dteLastAccess = date_format(date_create($objLastAccess['fecha']), "Y-m-d H:m:s");
                              $intIx = count($arrTables);
                          }
                      }
                  }
              };
          };
          unset($objTable);
          mysqli_free_result($rstTable);
          unset($rstTable);
      };
      $sqlIFUA="Update netwarstore.customer set fechaultimoacceso='".$dteLastAccess."', polizas=(select count(id) cuantas from $bdcliente.cont_polizas) where instancia='".$objCustomer['instancia']."';";
      echo $sqlIFUA;

      mysqli_query($objCon, $sqlIFUA);
  //};
}

echo "Proceso Concluido!!!!!!<br>";
mysqli_close($objCon);
unset($objCon);


  //echo "Tabla Temporal:<br>$sql_NST_table<br>";
  //echo "Valores:<br>$sql_NS_Values<br>";
?>
