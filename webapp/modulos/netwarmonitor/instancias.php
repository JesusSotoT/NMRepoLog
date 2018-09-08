<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor,"nmdevel","nmdevel","netwarstore");
mysqli_set_charset($objCon,"utf8");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/jquery.mask.min.js"></script>
</head>
<body>
<div class=" nmwatitles ">
    Status de Instancias
</div>
<?php
$strSql = "SELECT idapp, appname FROM appdescrip WHERE active = 1 ORDER BY idapp;";
$rstApps = mysqli_query($objCon,$strSql);
$strArrApps = '';
while($objApps = mysqli_fetch_array($rstApps)){
    $strArrApps .= '"' . $objApps['idapp'] . '", ';
    ?>
    <input type="checkbox" id="chk<?php echo $objApps['idapp'];?>" name="chk<?php echo $objApps['idapp'];?>" value="<?php echo $objApps['idapp'];?>"><label for="chk<?php echo $objApps['idapp'];?>"><?php echo $objApps['appname'];?></label><br />
    <?php
}
$strArrApps = substr($strArrApps,0,strlen($strArrApps) - 2);
unset($objApps);
mysqli_free_result($rstApps);
unset($rstApps);
?>
<br />
<input type="button" id="btnSearch" name="btnSearch" value="Ver" onclick="searchApps();" />
<br /><br />
<div id="divResults" name="divResults" style="background-color: #66AACC; display: block; margin: 0px 10px 0px 0px; padding: 20px 20px 20px 20px"></div>
<script>
    function searchApps(){
        var $arrApps = new Array(<?php echo $strArrApps; ?>);
        $strApps = "";
        for($intIx=0;$intIx<$arrApps.length;$intIx++){
            if($('#chk' + $arrApps[$intIx]).prop('checked')){
                $strApps += $arrApps[$intIx] + ",";
            }
        }
        $strApps = $strApps.substr(0,$strApps.length - 1);
        if($strApps==''){
            alert('Seleccione al menos una aplicacion a buscar');
        }else{
            $.post('getinstancias.php',{strApps:$strApps},function($databack) {
                $('#divResults').html('');
                $('#divResults').html($databack);
            });
        }
    }
</script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>