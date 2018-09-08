<?php

/*** 
 * Init 
 */
ini_set("display_errors",1);
//HTTPOnly
ini_set('session.cookie_httponly',1);
//CSRF
session_regenerate_id();
session_start();

/**
 * Getting installed apps
 */
$servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
$arrInstanciaG = explode("/",$_SERVER['REQUEST_URI']);
if(array_search('facturar',$arrInstanciaG)!=0){
    $strInstanciaG = $arrInstanciaG[array_search('facturar',$arrInstanciaG) - 1];
}else{
    $strInstanciaG = $arrInstanciaG[array_search('webapp',$arrInstanciaG) - 1];
}
if($strInstanciaG=="mlog"){
    $usuariobd = "nmdevel";
    $clavebd = "nmdevel";
    $bd = "nmdev";
}else {
    $objConG = mysqli_connect($servidor, "nmdevel", "nmdevel", "netwarstore");
    $strSqlG = "SELECT * FROM customer WHERE instancia = '" . $strInstanciaG . "';";
    $rstWebconfigG = mysqli_query($objConG, $strSqlG);
    while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
        $usuariobd = $objWebconfigG['usuario_db'];
        $clavebd = $objWebconfigG['pwd_db'];
        $bd = $objWebconfigG['nombre_db'];
    }
    unset($objWebconfigG);
    mysqli_free_result($rstWebconfigG);
    unset($rstWebconfigG);
    mysqli_close($objConG);
    unset($strSqlG);
}

$_SESSION["instancia"]=$strInstanciaG;
$reset_vars=true;

/** 
 * Preparing CSRF protection
 */
include "../catalog/clases/clcsrf.php";

$error = 0;
if(isset($_GET['e'])){
    $error = $_GET['e'];
}

$s_cerrada = 0;
if(isset($_GET['s'])){
    $s_cerrada = $_GET['s'];
}

$org=1; //$super_idorganizacion;
$log="";
$pwd="";
$d = "";
$info="";

if(isset($_COOKIE["g"])) $org = $_COOKIE["g"];
if(isset($_COOKIE["n"])) $log = $_COOKIE["n"];

//error_log("cookie d => ".$_COOKIE["d"]);
if(isset($_COOKIE["d"])){
    $d = $_COOKIE["d"];
    $pwd = "mip130719";
}
if(isset($_COOKIE["i"])) $info = $_COOKIE["i"];

$fecha="";
$dia="";
$mes="";
$año="";

$fecha=date("Y-m-d");

$dia=date("d");
$mes=date("m");
$año=date("Y");

?>

<!DOCTYPE html>
<html>

<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <title>:: NetwarMonitor-Aplicaciones Web para Empresas ::</title>

    <!--link href="../design/default/netwarlog.css" rel="stylesheet" type="text/css" /-->
    <link href="../design/index/index.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../catalog/js/jquery.js"></script>

    <script>
        function mensaje(m){
            switch (m)
            {
                case 1:
                    alert("Usuario o contraseña no válidos.");
                    break;
                case 3:
                    alert("El correo electronico no es válido.");
                    break;
                case 2:
                    alert("EL correo que proporciono esta asociado a otro usuario, escriba otro.");
                    break;
            }
        }

    </script>

</head>
<body<?php
if($error==1) echo " onload='mensaje(1)'";
if($error==3) echo " onload='mensaje(2)'";
if($error==2) echo " onload='mensaje(3)'";
?>>

<div style="disply:block; position:fixed;width:100%; text-align: right; top:0px; bottom:90px; overflow: auto; ">
    <div style=" display: inline-block; margin: 20px 40px 0px 0px; text-align: rigth; font-size: 14pt; color:#98ac31; font-weight: bold;">
        Bienvenido<br /><?php echo $strInstanciaG;?>.netwarmonitor.mx
    </div><br />
    <?php
    $objCon = mysqli_connect($servidor, $usuariobd, $clavebd, $bd);
    mysqli_query($objCon, "SET NAMES 'utf8'");

    $strLogoEmpresa = "x.png";
    $strSql = "SELECT logoempresa FROM organizaciones;";
    $rstOrgImg = mysqli_query($objCon,$strSql);
    while($objOrgImg=mysqli_fetch_assoc($rstOrgImg)){
        if($objOrgImg['logoempresa']!="x.png"){
            $strLogoEmpresa = $objOrgImg['logoempresa'];
        }
        ?>
    <?php
    }
    unset($objOrgImg);
    mysqli_free_result($rstOrgImg);
    unset($rstOrgImg);
    if($strLogoEmpresa!='x.png') {
        ?>
        <div
            style="display:inline-block; top:0px; vertical-align: top; margin: 15px 20px 0px 0px; background-color: rgba(255,255,255,1); border: 0px; border-radius: 5px; width: 200px;  height: 200px; padding: 0px; border: 5px #FFFFFF solid; background-image: url('../archivos/1/organizaciones/<?php echo $strLogoEmpresa;?>'); background-repeat: no-repeat; background-position: center center; background-size: 100% auto; "></div>
    <?php
    }
    mysqli_close($objCon);
    unset($objCon);
    ?>
    <?php


    ?>
    <div style="display: inline-block; color:#FFFFFF; font-size: 14pt; font-weight: normal; width: 201px; text-align: left; margin: 15px 40px 0px 0px; padding: 20px 20px 20px 20px; border: 1px #FFFFFF solid; border-radius: 5px; height: 230px;">
        <form id="frmaccess" name="frmaccess" action="validapwd.php" method="post">
            <?php
            //CSRF
            $form_names = $csrf->form_names(array('txtusuario','txtclave'),true);
            echo $csrf->input_token($token_id,$token_value);
            //error_log("\nANTES tokenid:".$token_id."  tokenval:".$token_value."  txtusuario:".$form_names['txtusuario']);
            ?>
            Usuario:<br />
            <input style="background-color: transparent; margin: 10px 0px 10px 0px; width: 180px; padding: 6px 10px 6px 10px; border: 1px #FFFFFF solid; font-size: 10pt; color:#FFFFFF" type="text" id="txtusuario" name="<?php echo $form_names['txtusuario']; ?>" value="<?php echo $log; ?>"><br />
            Contraseña:<br/>
            <input style="background-color: transparent; margin: 10px 0px 10px 0px; width: 180px; padding: 6px 10px 6px 10px; border: 1px #FFFFFF solid; font-size: 10pt; color:#FFFFFF" type="password" AUTOCOMPLETE="off" id="txtclave" name="<?php echo $form_names['txtclave']; ?>" value="<?php echo $pwd; ?>"><br />
            <input type="submit" id="btnsubmit" name="btnsubmit" value="Ingresar" style="background-color: #15263A; color:#98ac31; margin-bottom: 10px; font-size: 14pt; font-weight: bold; border: 0px; padding: 6px 10px 6px 10px; width: 200px; box-shadow: 4px 4px 10px #000000; cursor: pointer; "><br />
            <?php
            $checked='';
            if($info=="g"){
                $checked='checked="checked"';
            }
            ?>
            <span style="color: #FFFFFF; font-size: 9pt;"><input value="g" type="checkbox" id="chkinfo" name="chkinfo" <?php echo $checked; ?>> Guardar Contraseña</span><br />
            <a href="javascript:opd();" style="font-size: 9pt; color:#FFFFFF;">Olvide mi contraseña</a>
            <script type="text/javascript">

                function opd(){

                    $("#msgrespuesta").html("[ Espere un momento por favor ... ] &nbsp;&nbsp;");
                    var pcorreo=prompt("Capture el correo electrónico del usuario:");

                    $.ajax({
                        type: 	"post",
                        url: 		"ecl.php",
                        async: 	false,
                        data: 	{ c: pcorreo, <?php echo $token_id; ?>:"<?php echo $token_value; ?>" }
                }).done(function(msg){
                    alert(msg);
                    $("#msgrespuesta").html("");
                });

                }
            </script>

            <input type="hidden" id="d" name="d" value="<?php echo $d; ?>">
            <input type="hidden" id="stylepath" name="stylepath" value="<?php echo $txtStylePath; ?>">
        </form>
    </div>
    <br/>
    <div style="display: inline-block; width: 280px; height: 100px; margin: 20px 40px 0px 0px; border: 0px; padding: 3px; border-radius: 5px;  background-image: url(../design/index/img/netwarlog_logo_400.png); background-position: center center; background-size: 100% auto; background-repeat: no-repeat">
        &nbsp;
    </div>
</div>
<div style="position: absolute; height: 50px; padding: 5px 40px 5px 40px; bottom: 30px; left: 0px; right: 0px; font-size: 14pt;  ">
    <?php
    $objCon = mysqli_connect($servidor, "nmdevel", "nmdevel", "netwarstore");
    mysqli_query($objCon, "SET NAMES 'utf8'");

    if($strInstanciaG=='mlog'){
        $intIdCustomer = 0;
        $intIdClient = 0;
    }else{
        $strSql = "SELECT id, idclient FROM customer WHERE instancia = '" . $strInstanciaG ."';";
        $rstApps = mysqli_query($objCon,$strSql);
        while($objApps=mysqli_fetch_assoc($rstApps)){
            $intIdCustomer = $objApps['id'];
            $intIdClient = $objApps['idclient'];
        }
        unset($objApps);
        mysqli_free_result($rstApps);
        unset($rstApps);
    };

    $strSql = "SELECT idapp FROM appclient WHERE idclient = " . $intIdClient . " AND idcustomer = " . $intIdCustomer . " ORDER BY id;";
    $rstApps = mysqli_query($objCon,$strSql);
    while($objApps=mysqli_fetch_assoc($rstApps)){
        ?>
        <img src="../design/index/img/<?php echo $objApps['idapp'];?>.png" style="margin-right: 40px;" />
    <?php
    }
    unset($objApps);
    mysqli_free_result($rstApps);
    unset($rstApps);
    mysqli_close($objCon);
    unset($objCon);
    ?>
    <div style="display: block; float: right;">
        <span " id="chatImageSpan"></span>
        <div  id="sysaidChatInc">
        </div>
        <script type="text/javascript">
            var chatQueue="1";
            var enduserportal="0";
            var chatAccnt='netwarmonitor';
            var chatUrlPreffix="http://netwarmonitor.sysaidit.com:80/";
            offlineImage="img/consultoria_off.png";onlineImage="img/consultoria_on.png";
        </script>
        <script type="text/javascript" src="http://netwarmonitor.sysaidit.com:80/ChatImage?queue=1&amp;script=yes">
        </script>
    </div>
</div>
<div style="position: absolute; height: 30px; line-height: 30px; width: 100%; background-color: #98ac31; bottom: 0px; font-size: 10pt; color:#3C3C3C; font-weight: normal; text-align: center;  ">
    <a href="http://www.netwarmonitor.mx/index.php" target="_blank" class=" footerlink ">Netwarmonitor</a>&nbsp;&bull;
    <a href="http://www.appministra.com" target="_blank" target="_blank" class=" footerlink ">Appministra</a>&nbsp;&bull;
    <a href="http://www.acontia.mx" target="_blank" target="_blank" class=" footerlink ">Acontia</a>&nbsp;&bull;
    <a href="http://www.netwarmonitor.mx/privacidad.php" target="_blank" class=" footerlink ">Aviso de privacidad</a>&nbsp;&bull;
    <a href="https://www.facebook.com/pages/Netwarmonitor/219278564896353" target="_blank" class=" footerlink ">Facebook</a>&nbsp;&bull;
    <a href="http://twitter.com/netwarmonitor" target="_blank" class=" footerlink ">Twitter</a>&nbsp;&bull;
    <a href="http://www.youtube.com/user/netwarmonitor" target="_blank" class=" footerlink ">Youtube</a>
</div>
</body>
</html>
