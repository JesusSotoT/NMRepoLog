<?php
//ini_set('display_errors', '0');
?>
<!DOCTYPE html>
<html>
<head>
<title>Acontia 3.0</title>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css"/>
<link rel="stylesheet" href="css/bootstrap/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
<script src='../../libraries/jquery.min.js' type='text/javascript'></script>
<script src="js/moment.js" type="text/javascript"></script>
<title>Título.</title>
<!-- <div ID="waitDiv" style="position:absolute;margin-left:350px;visibility:hidden">
<img src="images/loading2.gif" width='70'>
</div> -->
<style>
	@media print {
  a[href]:after {
    content: none !important;
  }
}
</style>
<SCRIPT>
var DHTML = (document.getElementById || document.all || document.layers);
function ap_getObj(name) {
if (document.getElementById)
{ return document.getElementById(name).style; }
else if (document.all)
{ return document.all[name].style; }
else if (document.layers)
{ return document.layers[name]; }
}
function ap_showWaitMessage(div,flag) {
if (!DHTML) return;
var x = ap_getObj(div); x.visibility = (flag) ? 'visible':'hidden'
if(! document.getElementById) if(document.layers) x.left=280/2; return true; } ap_showWaitMessage('waitDiv', 3);
function cambiar_instancia()
{
	window.parent.agregatab("../../modulos/cont/index.php?c=Edu&f=panel_inst", "Conectar Datos",'',2381);
}
//-->
</SCRIPT>
</head>
<body>
<?php
	if(isset($_COOKIE['inst_lig']))
	{
		echo "<div style='font-size:20px;text-align:center;background-color:#f5f5f5;border:1px solid #e3e3e3;margin-bottom:10px;' class='col-xs-12 col-md-12'>Conectado a: ".$_COOKIE['inst_lig']."</div>";
		echo "<center><a href='javascript:cambiar_instancia()' id='cambiar_instancia'>Cambiar Instancia</a></center>";
	}
?>
<br/>
