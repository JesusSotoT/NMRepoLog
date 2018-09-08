<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
mysqli_query($objCon, "SET NAMES 'utf8'");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php include('../../netwarelog/design/css.php');?>
    <!--LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    <script src="js/jquery-1.11.1.min.js"></script>
</head>
<body>
<div class=" nmwatitles ">Configuración Prontipagos</div><br />
<div id="divWorking" style="display: none; text-align: center;">
    <img src="img/spinner.gif" />
</div>
<div id="divUP">
    <div id="divError" style=" display: none; background-color: #FFF0C9; border: 1px #8B0000 solid; border-radius: 5px; color:#8B0000; padding: 10px 10px 10px 10px; text-align: center; margin-bottom: 10px;"></div>
    <div id="divSuccess" style=" display: none; background-color: #c8ffc8; border: 1px #007800 solid; border-radius: 5px; color:#007800; padding: 10px 10px 10px 10px; text-align: center; margin-bottom: 10px;"></div>
    <?php
    $strSql = "SELECT * FROM prontipagos_configuracion";
    $rstUP = mysqli_query($objCon, $strSql);
    $strSts = "M";
    $strUsr = "";
    $strMaskedPwd = "";
    if(mysqli_num_rows($rstUP)==0){
        $strSts = "NORESULTS";
    }else{
        while($objUP=mysqli_fetch_assoc($rstUP)){
            $strUsr = $objUP['strUser'];
            $strMaskedPwd = "";
            for($intIx = 0; $intIx<strlen($objUP['strPassword'])-4;$intIx++){
                $strMaskedPwd .= "*";
            }
            $strMaskedPwd .= substr($objUP['strPassword'],strlen($objUP['strPassword'])-4,strlen($objUP['strPassword']));
        }
        unset($objUP);
    }
    mysqli_free_result($rstUP);
    unset($rstUp);
    ?>
    <table>
        <tbody>
        <tr>
            <td class=" nmcatalogbusquedatit " style="text-align: center">Usuario</td>
            <td class=" nmcatalogbusquedatit " style="text-align: center">Contraseña</td>
            <td></td>
        </tr>
        <tr id="trInputs" <?php if($strSts==''){ echo ' style="display: none";'; };?>>
            <td class=" nmcatalogbusquedacont_1 ">
                <input type="text" id="txtUsr" name="txtUsr" class=" nminputtext " value="">
            </td>
            <td class=" nmcatalogbusquedacont_1 ">
                <input type="password" id="txtPwd" name="txtPwd" class=" nminputtext " value="">
            </td>
            <td>
                <input type="button" id="btnValid" name="btnValid" onclick="getProducts();" class=" nminputbutton " value="Validar">
            </td>
        </tr>
        <tr id="trLabels" <?php if($strSts!=''){ echo ' style="display: none";'; };?>>
            <td class=" nmcatalogbusquedacont_1 " id="tdUsr" name="tdUsr">
                <?php echo $strUsr;?>
            </td>
            <td class=" nmcatalogbusquedacont_1 " id="tdPwd" name="tdPwd">
                <?php echo $strMaskedPwd;?>
            </td>
            <td></td>
        </tr>
        </tbody>
    </table>
</div>
<script>
    function getProducts(){
        $('#divError').hide();
        $('#divError').html('');
        $('#divSuccess').hide();
        $('#divSuccess').html('');
        $('#divUP').slideUp('slow',function(){
            $('#divWorking').slideDown('slow',function(){
                if($('#txtUsr').val().trim()!='' || $('#txtPwd').val().trim()!=''){
                    $strD = "strUsr=" + $('#txtUsr').val().trim() + "&strPwd=" + $('#txtPwd').val().trim();
                    $.ajax({
                        data: $strD,
                        type: "POST",
                        dataType: "json",
                        url: "getproducts.php",
                        success: function (databack) {
                            if(databack.intResult==0){
                                $('#divError').show();
                                $('#divError').html(databack.strResult);
                            }else{
                                $('#divSuccess').show();
                                $('#divSuccess').html(databack.strResult);
                                $('#trInputs').hide();
                                $('#trLabels').show();
                                $('#tdUsr').html(databack.strUsr);
                                $('#tdPwd').html(databack.strPwd);
                            }
                            $('#divWorking').slideUp('slow',function(){
                                $('#divUP').slideDown('slow');
                            });
                        }
                    });
                }else{
                    $('#divError').html('Ingrese su Usuario y Contraseña proporcionados por Prontipagos');
                    $('#divError').show();
                    $('#divWorking').slideUp('slow',function(){
                        $('#divUP').slideDown('slow');
                    });
                };
            });
        });
    }
</script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>
