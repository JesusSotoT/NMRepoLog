<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor, "nmdevel", "nmdevel", "netwarstore");
mysqli_query($objCon, "SET NAMES 'utf8'");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    <script src="js/jquery-1.11.1.min.js"></script>
</head>
<body>
    <div class=" nmwatitles ">Publicacion de avisos</div><br />
    <div id="divWorking" style="display: none; text-align: center;"><img src="img/spinner.gif" /></div>
    <div id="divError" style=" display: none; background-color: #FFF0C9; border: 1px #8B0000 solid; border-radius: 5px; color:#8B0000; padding: 10px 10px 10px 10px; text-align: center; margin-bottom: 10px;"></div>
    <div id="divSuccess" style=" display: none; background-color: #c8ffc8; border: 1px #007800 solid; border-radius: 5px; color:#007800; padding: 10px 10px 10px 10px; text-align: center; margin-bottom: 10px;"></div></div>
    <div id="divAnnouncements">
        <input type="button" class=" nminputbutton " value="Nuevo mensaje"><br /><br />
        <table>
            <tbody>
            <tr>
                <td class=" nmcatalogbusquedatit ">De</td>
                <td class=" nmcatalogbusquedatit ">Hasta</td>
                <td class=" nmcatalogbusquedatit ">Usuario</td>
                <td class=" nmcatalogbusquedatit ">Titulo</td>
                <td class=" nmcatalogbusquedatit ">Aviso</td>
            </tr>
            <?php

            ?>
            </tbody>
        </table>
    </div>
    <div id="divForm" style="display: none;">

    </div>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>
