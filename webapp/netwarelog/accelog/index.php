<?php

ini_set("display_errors",1);

//HTTPOnly
ini_set('session.cookie_httponly',1);
// Estableciendo sesión de idioma a nivel instancia

$txtStylePath = "";

//Avoid session error
session_regenerate_id(); // replace the Session ID
$ok = @session_start();
if(!$ok){
	session_regenerate_id(true); // replace the Session ID
	session_start();
}
if(isset($_COOKIE["PHPSESSID"])){
	unset($_COOKIE["g"]);
	unset($_COOKIE["PHPSESSID"]);
	session_destroy();
	session_start();
}

/**
 * VERIFICA INSTANCIA
 */
$arrInstanciaG = explode("/",$_SERVER['REQUEST_URI']);
if(array_search('facturar',$arrInstanciaG)!=0){
    $strInstanciaG = $arrInstanciaG[array_search('facturar',$arrInstanciaG) - 1];
}else{
    $strInstanciaG = $arrInstanciaG[array_search('webapp',$arrInstanciaG) - 1];
}

$_SESSION["instancia"]=$strInstanciaG;
$reset_vars=true;

//VALIDA VERSION Y SITIO SEGURO
//determinando el servidor
if($_SERVER['SERVER_NAME']=="edu.netwarmonitor.com"){
        $servidor ="unmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
        $usuariobd="unmdev";
        $clavebd="&=98+69unmdev";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
}elseif($_SERVER['SERVER_NAME']=="localhost"){
        $servidor ="192.168.1.11";
        $usuariobd="nmdevel";
        $clavebd="nmdevel";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
}else{
        $servidor  = "nmdb.cyv2immv1rf9.us-west-2.rds.amazonaws.com";
        $usuariobd = "nmdevel";
        $clavebd = "nmdevel";
        $bd = "nmdev";
        $accelog_variable = "netappmitranetwarelog1";
}
//DETERMINANDO BASE DE DATOS
		$objConG = mysqli_connect($servidor,$usuariobd , $clavebd, "netwarstore");
		$strSqlG = "SELECT usuario_db,pwd_db,nombre_db,cobranza FROM customer WHERE instancia = '" . $strInstanciaG . "';";
		$rstWebconfigG = mysqli_query($objConG, $strSqlG);
		while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
				$usuariobd = $objWebconfigG['usuario_db'];
				$clavebd = $objWebconfigG['pwd_db'];
				$bd = $objWebconfigG['nombre_db'];
				$accelog_variable = str_replace('_dbmlog', '', $objWebconfigG['nombre_db']) . "mlog";
				$cobranza=$objWebconfigG['cobranza'];
		}
		unset($objWebconfigG);
		mysqli_free_result($rstWebconfigG);
		unset($rstWebconfigG);

		//Recupera la version del Sistema
		$strSqlG = "SELECT version FROM $bd.netwarelog_version";
		$rstWebconfigG = mysqli_query($objConG, $strSqlG);
		while ($objWebconfigG = mysqli_fetch_assoc($rstWebconfigG)) {
				$version = $objWebconfigG['version'];
		}
		unset($objWebconfigG);
		mysqli_free_result($rstWebconfigG);
		unset($rstWebconfigG);

		mysqli_close($objConG);
		unset($strSqlG);

if($version>=2){
    //Evalua si esta en un sitio inseguro de swer asi lo redirecciona a uno seguro
    $arrInstanciaG = explode("/",$_SERVER['REQUEST_URI']);
    $strInstanciaG = $arrInstanciaG[array_search('webapp',$arrInstanciaG) - 1];
    $dom=$_SERVER['SERVER_NAME'];
    if($dom=='www.netwarmonitor.mx'){
            //Redirigir a Sitio Seguro
            header('Location: https://www.netwarmonitor.com/clientes/'.$strInstanciaG.'/webapp/netwarelog/accelog/index.php');
    }
}




/**
 * Preparing CSRF protection
 */
include "../catalog/clases/clcsrf.php";

/**
 * Getting gettext files for translation
 */

require_once("../../../lib/streams.php");
require_once("../../../lib/gettext.php");

//$locale_lang = $_GET['lang'];
$locale_lang = "en_US";
$locale_file = new FileReader("locale/$locale_lang/LC_MESSAGES/index.mo");
$locale_fetch = new gettext_reader($locale_file);

function _t($text){
	global $locale_fetch;
	return $locale_fetch->translate($text);
}

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

	<link rel="icon" type="image/icon" href="http://www.netwarmonitor.mx/assets/img/ico16px.png">
	<link rel="apple-touch-icon" href="http://www.netwarmonitor.mx/assets/img/ico60px.png">
	<link rel="apple-touch-icon" sizes="76x76" href="http://www.netwarmonitor.mx/assets/img/ico76px.png">
	<link rel="apple-touch-icon" sizes="120x120" href="http://www.netwarmonitor.mx/assets/img/ico120px.png">
	<link rel="apple-touch-icon" sizes="152x152" href="http://www.netwarmonitor.mx/assets/img/ico152px.png">

    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Netwarmonitor</title>

    <link href="../../libraries/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../design/index/index.css" rel="stylesheet" type="text/css" />

    <script>
        function mensaje(m){
            switch (m)
            {
                case 1:
                    alert("<?php echo _t("Usuario o contraseña no válidos."); ?>");
                    break;
                case 3:
                    alert("<?php echo _t("El correo electronico no es válido."); ?>");
                    break;
                case 2:
                    alert("<?php echo _t("El correo que proporciono esta asociado a otro usuario, escriba otro."); ?>");
                    break;
								case 4:
										alert("<?php echo _t("Servicio Suspendido por falta de pago, favor de comunicarse  al área de cuentas por cobrar  al tel. 30029300 ext 807 para realizar el pago y restablecer el sistema."); ?>");
										break;
            }
        }

    </script>

</head>
<body<?php
if($error==1) echo " onload='mensaje(1)'";
if($error==3) echo " onload='mensaje(2)'";
if($error==2) echo " onload='mensaje(3)'";
if($error==4) echo " onload='mensaje(4)'";

$ro="";
if($cobranza==1){
		$ro="hidden='true'";
		echo "<br><br><br><br><br><br>
					<div id='divlogin_container'>
						<center><strong><font color=red size=19>Servicio Suspendido por falta de pago, favor de comunicarse  al área de cuentas por cobrar  al tel. 30029300 ext 807 o al 01800 2777 321, para realizar el pago y restablecer el sistema.</font></strong></center>
					</div>";
}

?>>


	<br /><br /><br /><br />
	<div id="divlogin_container" <?php echo $ro;?>>
    <div id="divlogin">
        <form id="frmaccess" name="frmaccess" action="validapwd.php" method="post">

            <?php
            //CSRF
            $form_names = $csrf->form_names(array('txtusuario','txtclave'),true);
            echo $csrf->input_token($token_id,$token_value);
            //error_log("\nANTES tokenid:".$token_id."  tokenval:".$token_value."  txtusuario:".$form_names['txtusuario']);


						?>


            <input
            	class="input"
            	placeholder="<?php echo _t("Escriba su usuario"); ?>"
            	type="text"
            	id="txtusuario"
            	name="<?php echo $form_names['txtusuario']; ?>"
            	value="<?php echo $log; $ro?>">

            <br /><br />
						<input
            	class="input"
            	placeholder="<?php echo _t("Contraseña"); ?>"
         		type="password"
         		AUTOCOMPLETE="off"
         		id="txtclave"
         		name="<?php echo $form_names['txtclave']; ?>"
         		value="<?php echo $pwd; $ro?>">

			<br /><br />
            <?php
            	$checked='';
            	if($info=="g"){
                	$checked='checked="checked"';
            	}
            ?>
            <span>
            	<input value="g" type="checkbox" id="chkinfo" name="chkinfo" <?php echo $checked; ?>> <?php echo _t("Recordar Contraseña"); ?>
            </span>

            <br /><br />
            <input
            	type="submit"
            	id="btnsubmit"
            	name="btnsubmit"
            	value="<?php echo _t("Iniciar sesión"); ?>">

            <br /><br />

			<a href="javascript:opd();" class="footerlink"><?php echo _t("Recuperar la contraseña.") ?></a>

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

						<?php
							if($cobranza==1){
									echo "<strong>Servicio Suspendido por falta de pago, favor de comunicarse  al área de cuentas por cobrar  al tel. 30029300 ext 807 para realizar el pago y restablecer el sistema.</strong>";
							}
						?>

            <input type="hidden" id="d" name="d" value="<?php echo $d; ?>">
            <input type="hidden" id="stylepath" name="stylepath" value="<?php echo $txtStylePath; ?>">
        </form>
    </div>
    </div>

<div class="footer">

<div id="divsupport">
	<span  id="chatImageSpan"></span>
	<div  id="sysaidChatInc"></div>
		<script type='text/javascript'>var fc_CSS=document.createElement('link');fc_CSS.setAttribute('rel','stylesheet');var fc_isSecured = (window.location && window.location.protocol == 'https:');var fc_lang = document.getElementsByTagName('html')[0].getAttribute('lang'); var fc_rtlLanguages = ['ar','he']; var fc_rtlSuffix = (fc_rtlLanguages.indexOf(fc_lang) >= 0) ? '-rtl' : '';fc_CSS.setAttribute('type','text/css');fc_CSS.setAttribute('href',((fc_isSecured)? 'https://d36mpcpuzc4ztk.cloudfront.net':'http://assets1.chat.freshdesk.com')+'/css/visitor'+fc_rtlSuffix+'.css');document.getElementsByTagName('head')[0].appendChild(fc_CSS);var fc_JS=document.createElement('script'); fc_JS.type='text/javascript'; fc_JS.defer=true;fc_JS.src=((fc_isSecured)?'https://d36mpcpuzc4ztk.cloudfront.net':'http://assets.chat.freshdesk.com')+'/js/visitor.js';(document.body?document.body:document.getElementsByTagName('head')[0]).appendChild(fc_JS);window.livechat_setting= 'eyJ3aWRnZXRfc2l0ZV91cmwiOiJuZXR3YXJtb25pdG9yLmZyZXNoZGVzay5jb20iLCJwcm9kdWN0X2lkIjpudWxsLCJuYW1lIjoiTmV0d2FybW9uaXRvciIsIndpZGdldF9leHRlcm5hbF9pZCI6bnVsbCwid2lkZ2V0X2lkIjoiODk3MjVhMjQtZDk1MS00M2Q0LTgzN2UtZmQ5M2NjMWQzZTdjIiwic2hvd19vbl9wb3J0YWwiOmZhbHNlLCJwb3J0YWxfbG9naW5fcmVxdWlyZWQiOmZhbHNlLCJsYW5ndWFnZSI6bnVsbCwidGltZXpvbmUiOm51bGwsImlkIjo5MDAwMDMyNjYzLCJtYWluX3dpZGdldCI6MSwiZmNfaWQiOiI0MDA4MWE4MTI0MzdiZmQzMWU2NTBlYmNhNjY1ZTNhZiIsInNob3ciOjEsInJlcXVpcmVkIjoyLCJoZWxwZGVza25hbWUiOiJOZXR3YXJtb25pdG9yIiwibmFtZV9sYWJlbCI6Ik5vbWJyZSIsIm1lc3NhZ2VfbGFiZWwiOiJNZW5zYWplIiwicGhvbmVfbGFiZWwiOiJUZWzDqWZvbm8iLCJ0ZXh0ZmllbGRfbGFiZWwiOiJDYW1wbyBkZSB0ZXh0byIsImRyb3Bkb3duX2xhYmVsIjoiTWVuw7ogZGVzcGxlZ2FibGUiLCJ3ZWJ1cmwiOiJuZXR3YXJtb25pdG9yLmZyZXNoZGVzay5jb20iLCJub2RldXJsIjoiY2hhdC5mcmVzaGRlc2suY29tIiwiZGVidWciOjEsIm1lIjoiWW8iLCJleHBpcnkiOjE0ODkwODEyNDQwMDAsImVudmlyb25tZW50IjoicHJvZHVjdGlvbiIsImVuZF9jaGF0X3RoYW5rX21zZyI6IsKhR3JhY2lhcyEiLCJlbmRfY2hhdF9lbmRfdGl0bGUiOiJGaW5hbGl6YWNpw7NuIiwiZW5kX2NoYXRfY2FuY2VsX3RpdGxlIjoiQ2FuY2VsYXIiLCJzaXRlX2lkIjoiNDAwODFhODEyNDM3YmZkMzFlNjUwZWJjYTY2NWUzYWYiLCJhY3RpdmUiOjEsInJvdXRpbmciOm51bGwsInByZWNoYXRfZm9ybSI6MSwiYnVzaW5lc3NfY2FsZW5kYXIiOm51bGwsInByb2FjdGl2ZV9jaGF0IjowLCJwcm9hY3RpdmVfdGltZSI6MTUsInNpdGVfdXJsIjoibmV0d2FybW9uaXRvci5mcmVzaGRlc2suY29tIiwiZXh0ZXJuYWxfaWQiOm51bGwsImRlbGV0ZWQiOjAsIm1vYmlsZSI6MSwiYWNjb3VudF9pZCI6bnVsbCwiY3JlYXRlZF9hdCI6IjIwMTYtMDEtMTRUMjI6NDk6MzUuMDAwWiIsInVwZGF0ZWRfYXQiOiIyMDE3LTAyLTA4VDIzOjE2OjE0LjAwMFoiLCJjYkRlZmF1bHRNZXNzYWdlcyI6eyJjb2Jyb3dzaW5nX3N0YXJ0X21zZyI6IllvdXIgc2NyZWVuc2hhcmUgc2Vzc2lvbiBoYXMgc3RhcnRlZCIsImNvYnJvd3Npbmdfc3RvcF9tc2ciOiJZb3VyIHNjcmVlbnNoYXJpbmcgc2Vzc2lvbiBoYXMgZW5kZWQiLCJjb2Jyb3dzaW5nX2RlbnlfbXNnIjoiWW91ciByZXF1ZXN0IHdhcyBkZWNsaW5lZCIsImNvYnJvd3NpbmdfYWdlbnRfYnVzeSI6IkFnZW50IGlzIGluIHNjcmVlbiBzaGFyZSBzZXNzaW9uIHdpdGggY3VzdG9tZXIiLCJjb2Jyb3dzaW5nX3ZpZXdpbmdfc2NyZWVuIjoiWW91IGFyZSB2aWV3aW5nIHRoZSB2aXNpdG9y4oCZcyBzY3JlZW4gIiwiY29icm93c2luZ19jb250cm9sbGluZ19zY3JlZW4iOiJZb3UgaGF2ZSBhY2Nlc3MgdG8gdmlzaXRvcuKAmXMgc2NyZWVuICIsImNvYnJvd3NpbmdfcmVxdWVzdF9jb250cm9sIjoiUmVxdWVzdCB2aXNpdG9yIGZvciBzY3JlZW4gYWNjZXNzICIsImNvYnJvd3NpbmdfZ2l2ZV92aXNpdG9yX2NvbnRyb2wiOiJHaXZlIGFjY2VzcyBiYWNrIHRvIHZpc2l0b3IgIiwiY29icm93c2luZ19zdG9wX3JlcXVlc3QiOiJFbmQgeW91ciBzY3JlZW5zaGFyaW5nIHNlc3Npb24iLCJjb2Jyb3dzaW5nX3JlcXVlc3RfY29udHJvbF9yZWplY3RlZCI6IllvdXIgcmVxdWVzdCB3YXMgZGVjbGluZWQiLCJjb2Jyb3dzaW5nX2NhbmNlbF92aXNpdG9yX21zZyI6IlNjcmVlbnNoYXJpbmcgaXMgY3VycmVudGx5IHVuYXZhaWxhYmxlIiwiY29icm93c2luZ19hZ2VudF9yZXF1ZXN0X2NvbnRyb2wiOiJBZ2VudCBpcyByZXF1ZXN0aW5nIGFjY2VzcyB0byB5b3VyIHNjcmVlbiIsImNiX3ZpZXdpbmdfc2NyZWVuX3ZpIjoiQWdlbnQgY2FuIHZpZXcgeW91ciBzY3JlZW4gIiwiY2JfY29udHJvbGxpbmdfc2NyZWVuX3ZpIjoiQWdlbnQgaGFzIGFjY2VzcyB0byB5b3VyIHNjcmVlbiAiLCJjYl92aWV3X21vZGVfc3VidGV4dCI6IllvdXIgYWNjZXNzIHRvIHRoZSBzY3JlZW4gaGFzIGJlZW4gd2l0aGRyYXduICIsImNiX2dpdmVfY29udHJvbF92aSI6IkFsbG93IGFnZW50IHRvIGFjY2VzcyB5b3VyIHNjcmVlbiAiLCJjYl92aXNpdG9yX3Nlc3Npb25fcmVxdWVzdCI6IkFnZW50IHNlZWtzIGFjY2VzcyB0byB5b3VyIHNjcmVlbiAifX0=';
		</script>
		<script type="text/javascript">
			var txt_for_chat = "";
			txt_for_chat = "<i class='fa fa-question-circle fa-lg'></i>";
			txt_for_chat+= "<span class='lbl_menu'>&nbsp;Soporte Técnico</span>";
			$("#chatImageSpan a").html("");
		</script>
</div><br>



    <a href="http://www.netwarmonitor.mx/index.php" target="_blank" class=" footerlink ">Netwarmonitor</a>&nbsp;&bull;
    <a href="http://www.appministra.com" target="_blank" target="_blank" class=" footerlink ">Appministra</a>&nbsp;&bull;
    <a href="http://www.acontia.mx" target="_blank" target="_blank" class=" footerlink ">Acontia</a>&nbsp;&bull;
    <a href="http://www.netwarmonitor.mx/privacidad.php" target="_blank" class=" footerlink "><?php echo _t("Aviso de privacidad"); ?></a>&nbsp;&bull;
    <a href="https://www.facebook.com/NetwarmonitorMX/" target="_blank" class=" footerlink ">Facebook</a>&nbsp;&bull;
    <a href="http://twitter.com/netwarmonitor" target="_blank" class=" footerlink ">Twitter</a>&nbsp;&bull;
    <a href="https://www.youtube.com/channel/UCfxtdKPoczzpR0tiBcIiffQ" target="_blank" class=" footerlink ">Youtube</a>
</div>



<!--
	Loading landing
 -->
<iframe
	id="frBackground"
	src="../../../landing/index.php"
	frameborder="0"
></iframe>

<script src="../../libraries/jquery.min.js"></script>
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>


</body>
</html>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-75293719-1', 'auto');
  ga('send', 'pageview');

</script>
