<?php
ini_set("display_errors",0);
include('../../netwarelog/webconfig.php');
include('inc/curl.php');
$usuariobd  = "nmdevel";
$clavebd    = "nmdevel";

$objCon = mysqli_connect($servidor, $usuariobd, $clavebd, "netwarstore");
mysqli_query($objCon, "SET NAMES 'utf8'");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <?php include('../../netwarelog/design/css.php');?>
    <LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    <script src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript">

    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data)
    {
        var mywindow = window.open('', 'divSuccess', 'height=400,width=600');
        mywindow.document.write('<html><head><title>my div</title>');
        /*Opcional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close();
        mywindow.focus();

        mywindow.print();
        mywindow.close();

        return true;
    }
    </script>
</head>
<body>

<div class=" nmwatitles ">Pago de servicios en Prontipagos
    <a href="javascript:PrintElem('#divSuccess');"><img src="img/impresora.png" border="0"></a>
</div>

<br />
<div id="divWorking" style="display: none; text-align: center;"><img src="img/spinner.gif" /></div>
<div id="divError" style=" display: none; background-color: #FFF0C9; border: 1px #8B0000 solid; border-radius: 5px; color:#8B0000; padding: 10px 10px 10px 10px; text-align: center; margin-bottom: 10px;"></div>

<div id="divSuccess" style=" display: none; padding: 10px 10px 10px 10px; font-size: 40px; text-align: center; margin-bottom: 10px;">
    <table style="margin: 0px auto 0px auto;">
        <tbody>
        <tr><td style="font-size=12pt">Resultado de la transacción</td></tr>

        <tr><td style="font-size:12pt ">Transacción</td></tr>
        <tr><td id="tdcodeTransaction" style="font-size:12pt"></td></tr>

        <tr><td style="font-size:12pt ">Estatus Transacción</td></tr>
        <tr><td id="tdstatusTransaction" style="font-size:12pt"></td></tr>

        <tr><td style="font-size:12pt ">Código</td></tr>
        <tr><td id="tdcodeDescription" style="font-size:12pt"></td></tr>

        <tr><td style="font-size:12pt ">Fecha</td></tr>
        <tr><td id="tddateTransaction" style="font-size:12pt"></td></tr>

        <tr><td style="font-size:12pt ">Número transacción</td></tr>
        <tr><td id="tdfolioTransaction" style="font-size:12pt"></td></tr>
        <tr>
            <td>
                <input type="button" value="Aceptar" onclick="closeSell();" class=" nminputbutton " />
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div id="divConfirm" style="display: none; text-align: center">
    <table style="margin: 0px auto 0px auto; font-size: 40px;">
        <tbody>
        <tr>
            <td>¿Los datos son correctos?</td>
        </tr>
        <tr><td id="tdCReferenceTit" class=" nmcatalogbusquedatit "></td></tr>
        <tr><td id="tdCReference" class=" nmcatalogbusquedacont_2 "></td></tr>
        <tr><td class=" nmcatalogbusquedatit ">Importe</td></tr>
        <tr><td id="tdCAmount" class=" nmcatalogbusquedacont_2 "></td></tr>
        <tr>
            <td>
                <input type="button" value="Aceptar" onclick="procSell();" style=" vertical-align: middle; text-align: center; font-size: 14pt; font-weight: bold; color:#101010; border-radius: 5px; padding: 3px 15px 3px 15px; background-color: #98ac31; cursor: pointer; " />
                <input type="button" value="Cancelar" onclick="cancelSell();" style=" vertical-align: middle; text-align: center; font-size: 14pt; font-weight: bold; color:#FFFFFF; border-radius: 5px; padding: 3px 15px 3px 15px; background-color: #8B0000; cursor: pointer; "/>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div id="divSell">
    <table>
        <tbody>
            <tr>
                <td class=" nmcatalogbusquedatit " style="width: 200px; padding-left: 10px;" >Servicio</td>
                <td class=" nmcatalogbusquedacont_1 ">
                    <select id="selProduct" name="selProduct" class=" nminputselect " style="width: 322px;" onchange="getProduct();">
                        <option value="-1" selected="selected">--Seleccionar --</option>
                        <?php
                        $strSql = "SELECT intId, strDescription FROM prontipagos_products;";
                        $rstProducts = mysqli_query($objCon,$strSql);
                        while($objProductos=mysqli_fetch_assoc($rstProducts)){
                            ?>
                            <option value="<?php echo $objProductos['intId'] ;?>"><?php echo $objProductos['strDescription'] ;?></option>
                        <?php
                        }
                        unset($objProductos);
                        mysqli_free_result($rstProducts);
                        unset($rstProducts);
                        ?>
                    </select>
                </td>
            </tr>
            <tr id="trLoading" name="trLoading" style="display: none">
                <td colspan="2" align="center"><img src="img/spinner.gif" /></td>
            </tr>
            <tr id="trReference" name="trReference" style="display: none">
                <td class=" nmcatalogbusquedatit " style="width: 200px; padding-left: 10px;" id="tdReference" name="tdReference" ></td>
                <td class=" nmcatalogbusquedacont_1 "><input type="text" class="nminputtext" style="width: 300px;" id="txtReference" name="txtReference"></td>
            </tr>
            <tr id="trAmount" name="trAmount" style="display: none">
                <td class=" nmcatalogbusquedatit " style="width: 200px; padding-left: 10px;" id="tdAmount" name="tdAmount" >Importe</td>
                <td class=" nmcatalogbusquedacont_1 "><input type="text" class="nminputtext" style="width: 300px;" id="txtAmount" name="txtAmount"></td>
            </tr>
            <tr id="trSku" name="trSku" style="display: none">
                <td class=" nmcatalogbusquedatit " style="width: 200px; padding-left: 10px;">SKU</td>
                <td id="tdSku" name="tdSku" class=" nmcatalogbusquedacont_1 "></td>
            </tr>
            <tr id="trProc" name="trProc" style="display: none; margin-top: 20px;">
                <td colspan="2" align="center">
                    <input type="button" value="Procesar" onclick="confirm();" style=" vertical-align: middle; text-align: center; font-size: 14pt; font-weight: bold; color:#101010; border-radius: 5px; padding: 3px 15px 3px 15px; background-color: #98ac31; cursor: pointer; " />
                    <input type="button" value="Limpiar" onclick="clean();" style=" vertical-align: middle; text-align: center; font-size: 14pt; font-weight: bold; color:#FFFFFF; border-radius: 5px; padding: 3px 15px 3px 15px; background-color: #8B0000; cursor: pointer; "/>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    function getProduct(){
        $('#trReference').hide();
        $('#trAmount').hide();
        $('#trSku').hide();
        $('#trProc').hide();
        $('#trLoading').slideDown('fast',function(){
            $intId = $('#selProduct').val();
            $('#tdReference').html('');
            $('#txtReference').val('');
            $('#txtAmount').val('');
            $('#txtAmount').removeAttr('disabled');
            $('#tdSku').html('');
            if($intId!=-1){
                $strQueryString = "product=" + $intId;
                $.ajax({
                    data: $strQueryString,
                    type: "POST",
                    dataType: "json",
                    url: "getproduct.php",
                    success: function($jsnData) {
                        $('#tdReference').html($jsnData.strReference);
                        $('#tdCReferenceTit').html($jsnData.strReference);
                        if($jsnData.blnFixedFee==1){
                            $decPrice = $jsnData.decPrice * 1;
                            $('#txtAmount').val($decPrice.toFixed(2));
                            $('#txtAmount').attr('disabled','disabled');
                        }
                        $('#tdSku').html($jsnData.strSku);
                        $('#trLoading').slideUp('fast',function(){
                            $('#trReference').show();
                            $('#trAmount').show();
                            $('#trSku').show();
                            $('#trProc').show();
                        })
                    }
                });
            };
        })
    };

    function confirm(){
        $('#divError').hide();
        $('#divError').html('');
        $('#divSuccess').hide();
        //$('#divSuccess').html('');
        $('#divSell').slideUp('slow',function(){
            if($('#txtReference').val()=="" || $('#txtAmount').val()==""){
                $('#divError').html('Ingrese el importe y la referencia');
                $('#divSell').slideDown('slow',function(){
                    $('#divError').show();
                })
            }else{
                $('#tdCReference').html($('#txtReference').val());
                $('#tdCAmount').html($('#txtAmount').val());
                $('#divConfirm').slideDown('slow');
            }
        })
    }

    function clean(){
        $('#divError').hide();
        $('#divError').html('');
        $('#divSuccess').hide();
        //$('#divSuccess').html('');
        $('#trReference').hide();
        $('#trAmount').hide();
        $('#trSku').hide();
        $('#trProc').hide();
        $('#tdReference').html('');
        $('#txtReference').val('');
        $('#txtAmount').val('');
        $('#txtAmount').removeAttr('disabled');
        $('#tdCReference').html('');
        $('#tdCAmount').html('');
        $('#selProduct').val(-1);
    }

    function procSell(){
        $('#divConfirm').slideUp('slow',function(){
            $('#divWorking').slideDown('slow',function(){
                $strQueryString = "product=" + $('#selProduct').val() + "&reference=" + $('#txtReference').val() + "&amount=" + $('#txtAmount').val();
                $.ajax({
                    data: $strQueryString,
                    type: "POST",
                    dataType: "json",
                    url: "procsell.php",
                    success: function($jsnData) {
                        $('#tdcodeTransaction').html($jsnData.codeTransaction);
                        $('#tdstatusTransaction').html($jsnData.statusTransaction);
                        $('#tdcodeDescription').html($jsnData.codeDescription);
                        $('#tddateTransaction').html($jsnData.dateTransaction);
                        $('#tdtransactionId').html($jsnData.transactionId);
                        $('#tdfolioTransaction').html($jsnData.folioTransaction);
                        $('#tdadditionalInfo').html($jsnData.additionalInfo);
                        $('#divWorking').slideUp('slow',function(){
                            $('#divSuccess').slideDown('slow');
                        })
                    }
                });
            })
        })
    };

    function cancelSell(){
        $('#divConfirm').slideUp('slow',function(){
            $('#divSell').slideDown('slow');
        })
    }

    function closeSell(){
        $('#divSuccess').slideUp('slow',function(){
            clean();
            $('#divSell').slideDown('slow');
        })
    }
</script>
</body>
</html>
<?php
mysqli_close($objCon);
unset($objCon);
?>
