<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
include('inc/curl.php');
$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
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
<div class=" nmwatitles ">Saldo en Prontipagos</div><br />
<div id="divBalance">
    <table>
        <tbody>
            <tr>
                <td class=" nmcatalogbusquedatit " style="text-align: center">Cuenta</td>
                <td class=" nmcatalogbusquedatit " style="text-align: center">Saldo</td>
            </tr>
    <?php
    $strSql = "SELECT * FROM prontipagos_configuracion;";
    $rstCredentials = mysqli_query($objCon, $strSql);
    if(mysqli_num_rows($rstCredentials)==0){
        ?>
            <tr>
                <td colspan="2">Aún no ha configurado su acceso a Prontipagos</td>
            </tr>
        <?php
    }else{
        while($objCredentials=mysqli_fetch_assoc($rstCredentials)){
            $strUsr = $objCredentials['strUser'];
            $strPwd = $objCredentials['strPassword'];
        }
        unset($objCredentials);
        $strXMLBody = '
            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:siv="http://prontipagos.ws.com">
                <soapenv:Header/>
                <soapenv:Body>
                    <siv:balanceService/>
                </soapenv:Body>
            </soapenv:Envelope>
        ';

        $xmlResult = execCurl($strUsr,$strPwd,'balanceService',$strXMLBody);


        $objDOM = new DOMDocument();
        $objDOM->loadXML($xmlResult);
        $nodeBalances = $objDOM->getElementsByTagName('balances');
        foreach($nodeBalances as $objNode){
            ?>
            <tr>
                <td class=" nmcatalogbusquedacont_1 "><?php echo $objNode->getElementsByTagName('accountId')->item(0)->nodeValue; ?></td>
                <td class=" nmcatalogbusquedacont_1 ">$ <?php echo number_format($objNode->getElementsByTagName('balance')->item(0)->nodeValue,2,".",","); ?></td>
            </tr>
            <?php
        }
        unset($objNode);
        unset($nodeBalances);
        unset($objDOM);
    };
    mysqli_free_result($rstCredentials);
    unset($rstCredentials);
    ?>
        </tbody>
    </table>
</div>
<script>
</script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>
