<?php
ini_set("display_errors",0);


if($_SERVER['SERVER_NAME']=="edu.netwarmonitor.com"){
        $strDBHost ="unmdbplus.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
        $strDBUsr="unmdev";
        $strDBPwd="&=98+69unmdev";
        $bdnmadmin="_dbmlog0000003550";
        $camposadd=",c.codigoprofesor,c.profesor,c.nrc";
}else{
        $strDBHost  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
        $strDBUsr = "nmdevel";
        $strDBPwd = "nmdevel";
        $bdnmadmin="_dbmlog0000005471";
        $camposadd="";
}

  //Coneccion a Base de Datos NetwarStore - Transversal
  //$strDBHost="nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
  //$strDBUsr="nmdevel";
  //$strDBPwd="nmdevel";

  $strDBName="netwarstore";
  $objCon = mysqli_connect($strDBHost,$strDBUsr,$strDBPwd,$strDBName);
  mysqli_query($objCon, "SET NAMES 'utf8'");

//Genera SQL de Tabla Temporal para NMADMIN
      $sql_NST_table="Delete From $bdnmadmin.nstore_admin_temporal;";
      mysqli_query($objCon, $sql_NST_table);

      $sql_NST_table="INSERT INTO $bdnmadmin.nstore_admin_temporal Select c.id,c.Instancia , c.nombre_db BaseDatos, c.fecha FechaCreacion, ac.limitdate FechaVencimiento, c.fechaultimoacceso, ac.idapp Apps,
                      rc.email MailRegistrante, c.razon RazonSocial, c.nombre NombreContacto, c.rfc,c.estado, c.colonia, c.direccion, c.cp, c.telefono, c.giro, usuario_master, pwd_master, ac.installkey,
                      ad.appname, c.status_instancia,c.productos,c.ventas,c.facturas,c.polizas,c.idventa $camposadd
                      from netwarstore.regcustomer rc
                      inner join netwarstore.customer c on c.idclient=rc.id
                      inner join netwarstore.appclient ac on ac.idcustomer=c.id and rc.id=ac.idclient
                      inner join netwarstore.appdescrip ad on ad.idapp=ac.idapp
                      where (status_instancia<>4 or status_instancia is null) and c.nombre_db <>'' and instancia<>''
                      order by  c.fechaultimoacceso desc, c.instancia;";
    mysqli_query($objCon, $sql_NST_table);

//Genera Sql Tabla para NMWADMIN
    $bdnmadmin="_dbmlog0000009984";
    $sql_NST_table="Delete From $bdnmadmin.nstore_admin_temporal;";
    mysqli_query($objCon, $sql_NST_table);

    $sql_NST_table="INSERT INTO $bdnmadmin.nstore_admin_temporal Select c.id,c.Instancia , c.nombre_db BaseDatos, c.fecha FechaCreacion, ac.limitdate FechaVencimiento, c.fechaultimoacceso, ac.idapp Apps,
                    rc.email MailRegistrante, c.razon RazonSocial, c.nombre NombreContacto, c.rfc,c.estado, c.colonia, c.direccion, c.cp, c.telefono, c.giro, usuario_master, pwd_master, ac.installkey,
                    ad.appname, c.status_instancia,c.productos,c.ventas,c.facturas,c.polizas,c.idventa $camposadd
                    from netwarstore.regcustomer rc
                    inner join netwarstore.customer c on c.idclient=rc.id
                    inner join netwarstore.appclient ac on ac.idcustomer=c.id and rc.id=ac.idclient
                    inner join netwarstore.appdescrip ad on ad.idapp=ac.idapp
                    where (status_instancia<>4 or status_instancia is null) and c.nombre_db <>'' and instancia<>''
                    order by  c.fechaultimoacceso desc, c.instancia;";
    mysqli_query($objCon, $sql_NST_table);


  mysqli_close($objCon);
  unset($objCon);

?>
