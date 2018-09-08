<?php
require '../../../netwarelog/webconfig.php';
$conNMDB = mysqli_connect($servidor,$usuariobd,$clavebd, $bd);
mysqli_query($conNMDB,"SET NAMES '" . DB_CHARSET . "'");
$strSql = "SELECT rfc, pass_ciec FROM pvt_configura_facturacion;";
$rstDB = mysqli_query($conNMDB,$strSql);
while($objDB=mysqli_fetch_assoc($rstDB)){
    $strRFC = $objDB['rfc'];
    $strCIEC = $objDB['pass_ciec'];
}
$strSql = "SELECT RFC FROM organizaciones WHERE idorganizacion = 1;";
$rstDB = mysqli_query($conNMDB,$strSql);
while($objDB=mysqli_fetch_assoc($rstDB))
    $strRFC = $objDB['RFC'];
unset($objDB);
mysqli_free_result($rstDB);
unset($rstDB);
mysqli_close($conNMDB);
unset($conNMDB);

//if($strRFC=='AAA010101AAA'){
//    $strRFC = 'IHA000314A38';
//}
if($strRFC=='XXX000000XXX'){
    $strRFC = '';
}
if($strCIEC=='----'){
    $strCIEC = '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Accontia :: XML's mass download</title>
    <LINK href="../../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
    <link rel="stylesheet" type="text/css" href="../../../libraries/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../../libraries/datepicker/css/bootstrap-datepicker.min.css">
    <script src="../../../libraries/jquery.min.js" type="text/javascript"></script>
    <script src="../../../libraries/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="../../../libraries/datepicker/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
    <script src="../../../libraries/datepicker/js/bootstrap-datepicker.es.js" type="text/javascript"></script>
    <style type="text/css">
        .btnMenu{
            border-radius: 0;
            width: 100%;
            margin-bottom: 0.3em;
            margin-top: 0.3em;
        }
        .row
        {
            margin-top: 0.5em !important;
        }
        h5, h4, h3{
            background-color: #eee;
            padding: 0.4em;
        }
        .modal-title{
            background-color: unset !important;
            padding: unset !important;
        }
        .nmwatitles, [id="title"] {
            padding: 8px 0 3px !important;
            background-color: unset !important;
        }
        .select2-container{
            width: 100% !important;
        }
        .select2-container .select2-choice{
            background-image: unset !important;
            height: 31px !important;
        }
        .twitter-typeahead{
            width: 100% !important;
        }
        .tablaResponsiva{
            max-width: 100vw !important;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3 class="nmwatitles text-center">
                Descarga Masiva de XML's desde el SAT
            </h3>
            <h4>Datos</h4>
            <div class="row">
                <div class="col-sm-12 col-md-2">
                    <?php
                    if($strRFC==''){
                        ?>
                        <input type="text" class="form-control" value="" id="strRFC" name="strRFC" placeholder="RFC">
                        <?php
                    }else{
                        ?>
                        <input type="text" class="form-control" value="<?php echo $strRFC; ?>" id="strRFC" name="strRFC" placeholder="RFC" disabled="disabled">
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-12 col-md-2">
                    <?php
                    if($strCIEC==''){
                        ?>
                        <input type="password" class="form-control" value="" id="strCIEC" name="strCIEC" placeholder="Contraseña CIEC">
                        <?php
                    }else{
                        ?>
                        <input type="password" class="form-control" value="<?php echo $strCIEC; ?>" id="strCIEC" name="strCIEC" placeholder="Contraseña CIEC" disabled="disabled">
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-12 col-md-2">
                    <input id="dteFrom" placeholder='Desde' class="form-control" value="" onchange="valDatesFrom();">
                </div>
                <div class="col-sm-12 col-md-2">
                    <input id="dteTo"  placeholder='Hasta' class="form-control" value="" onchange="valDatesTo();">
                </div>
                <div class="col-sm-12 col-md-2">
                    <select id="strDocument" name="strDocument" class="form-control">
                        <option selected="selected" value="R">Recibidos</option>
                        <option value="E">Emitidos</option>
                    </select>
                </div>
                <div class="col-sm-12 col-md-2">
                <input type='checkbox' id='strNuevosPc'>
                Guardar Proveedores o Clientes Nuevos
                </div>
                <div class="col-sm-12 col-md-5"></div>
                <div class="col-sm-12 col-md-2" syle='text-align:center;'>
                    <img src="https://cfdiau.sat.gob.mx/nidp/jcaptcha.jpg" />
                    <input class="form-control"
                    id="jcaptcha"
                    placeholder="Captcha"
                    type="text"
                    name="jcaptcha"
                    size="25"
                    maxlength="16"
                    autocomplete="off">

                    <input type="button" class='btn btn-primary btnMenu' value="Descargar" id="btnGET" name="btnGET" onclick="getXMLs();">
                    <input type="button" class='btn btn-primary btnMenu' value="Volver al Almacén" id="btnBack" name="btnBack" onclick="backToDigitalWHouse();">
                </div>
                <div class="col-sm-12 col-md-5"></div>
            </div>
            <h4>Resultado</h4>
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div id="strResult" name="strResult" style="background-color: #FFFFFF; color:#07AA9E; border:1px #FFFFFF solid; border-radius: 5px; font-size: 12pt; text-align: center; display: block; padding: 10px 10px 10px 10px;"></div>
                    <div id="divGrid" name="divGrid" style="background-color: #FFFFFF; color:#07AA9E; border:1px #FFFFFF solid; border-radius: 5px; font-size: 12pt; text-align: center; display: none; padding: 10px 10px 10px 10px; overflow-x: auto; overflow-y: auto; max-height: 200px;">
                        <table id="tblGrid" name="tblGrid">
                            <thead>
                            <tr>
                                <th>Efecto</th>
                                <th>Estado</th>
                                <th>Folio Fiscal</th>
                                <th>Fecha</th>
                                <th>Emisor</th>
                                <th>RFC Emisor</th>
                                <th>Receptor</th>
                                <th>RFC Receptor</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('document').ready(function(){
        $('#dteFrom').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
        });
        $('#dteTo').datepicker({
            format: "yyyy-mm-dd",
            language: "es"
        });
    });
</script>
<script type="text/javascript" src="massxml.js?v=3.0"></script>
</body>
</html>

