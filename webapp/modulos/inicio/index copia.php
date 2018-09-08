<? session_start(); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
       <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
</head>
<body>
<?php
require("tipos_de_cambio.php");
?>
<!-- <div class=" nmwatitles ">
    <?php
    $arrInstancia = explode("/",$_SERVER['REQUEST_URI']);
    ?>
    Bienvenido a <?php echo $arrInstancia[array_search('webapp',$arrInstancia) - 1];; ?>.netwarmonitor.mx
</div> -->
<?php
include_once("../../netwarelog/catalog/conexionbd.php");



$q=mysql_query("SELECT * FROM accelog_perfiles_me WHERE idmenu=146;");
if(mysql_num_rows($q) >0){
    $q=mysql_query("SELECT * FROM pvt_configura_facturacion where (rfc='' or regimen='' or pais='' or razon_social='' or calle='' or num_ext='' or colonia='' or ciudad='' or municipio='' or estado='' or cp='' or cer='' or llave='' or clave='');");
    if(mysql_num_rows($q)>0){
        ?>
        <div class=" nmwelcomenotifications " >
            <div>Aviso</div>
            Tus datos de facturación no estan configurados, para poder facturar debes llenar tus datos fiscales y cargar tus archivos CSD.
            <br /><br />
            Esto lo puedes hacer en el menú <strong>[Facturación]</strong> en la opción <strong>[Configuración de Facturación]</strong> ó haciendo click <a href="../../netwarelog/catalog/gestor.php?idestructura=234&ticket=testing">AQUÍ</a>
        </div>
    <?php }
}
$q=mysql_query("SELECT * FROM accelog_perfiles_me WHERE idmenu=142;");
if(mysql_num_rows($q)>0){
    $sql=mysql_query("select * from cont_config");
    if(mysql_num_rows($sql) ==0){
        ?>
        <div class=" nmwelcomenotifications " >
             <a href="../../modulos/cont/index.php?c=Config">AQUÍ</a>
        </div>
        <?php
    }
}
$q=mysql_query("SELECT * FROM accelog_perfiles_me WHERE idmenu=1726 or idmenu=1664;");
if(mysql_num_rows($q)<0){

        ?>
        <div class=" nmwelcomenotifications " >
            <div><h3>AVISO</h3></div>
            A todos los usarios de los productos Netwarmonitor, les comentamos que a partir de la próxima semana estaremos realizando algunas actualizaciones a Appministra de acuerdo a la siguiente lista:
            <br />
            <ul>
                <li>Reordenamiento en el menú de inventarios (tendrás las mismas funciones solo cambia el orden y algunos nombres de submenús).</li>
                <li>Mejoramos movimientos entre almacenes, podrás realizar varios movimientos a la vez entre tus almacenes y tener en transito la mercancía hasta su recepción al almacén destino.</li>
                <li>Mejoramos la plantilla de importar productos y ahora tiene más opciones para capturar menos en el sistema.</li>
            </ul>
            <br />
            También te indicamos de mejoras ya instaladas en tu instancia: <br />
            <ul>
                <li>En caja cuentas con un menú “Retiro de caja” para que tengas un control de las disposiciones de efectivo de tu caja para pago a tus proveedores y esto se vea reflejado en el corte de caja</li>
                <li>En corte de caja cuentas con un botón que te ayudará con el arqueo de tu efectivo.</li>
                <li>Cuentas por pagar y cobrar también sufrieron de modificaciones para mejorar estos menús.</li>
            </ul>
            <br />
            Y estamos trabajando para que proximanete cuentes con un módulo de "Corizaciones y Pedidos".
            <br /><br />
            Te invitamos de probar de estas mejoras en cuanto estén liberadas, por este medio
            confirmaremos de esto y cualquier cosa que no quede clara en su funcionamiento
            marcarnos al 01800 2777 321 en la opción 2 con nuestra mesa de consultoría que te  apoyará en lo que requieras.
            <br />

        </div>
        <?php

}
//########## Announcements ##########
/*
$strInstanciaG = "sistema";
$dteDateannouncements = date("Y-m-d");
$objConannouncements = mysqli_connect($servidor,"nmdevel","nmdevel","netwarstore");
mysqli_query($objConannouncements, "SET NAMES 'utf8'");
$strSqlannouncements = "SELECT strTitle, strAnnouncement FROM announcements WHERE (announcements.idApp IN (SELECT idapp FROM appclient, customer WHERE appclient.idcustomer = customer.id AND customer.instancia = '" . $strInstanciaG . "') OR announcements.idApp = 'ALL' ) AND announcements.dteFrom <= '" . $dteDateannouncements . "' AND dteTo >= '" . $dteDateannouncements . "' ORDER BY announcements.id DESC;";
$rstAnnouncements = mysqli_query($objConannouncements,$strSqlannouncements);
if(mysqli_num_rows($rstAnnouncements)!=0){
    while($objAnnouncements=mysqli_fetch_assoc($rstAnnouncements)){
?>
        <div class=" nmwelcomenotifications " ><div><?php echo $objAnnouncements['strTitle'];?></div><?php echo $objAnnouncements['strAnnouncement'];?></div>
<?php
    }
    unset($objAnnouncements);
}
mysqli_free_result($rstAnnouncements);
unset($rstAnnouncements);
mysqli_close($objConannouncements);
unset($objConannouncements);
*/
//########## Announcements ##########
?>
<br><br>
</body>
</html>
