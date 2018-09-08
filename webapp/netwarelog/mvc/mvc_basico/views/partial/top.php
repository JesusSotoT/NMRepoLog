<?php
//ini_set('display_errors', '0');
?>
<!DOCTYPE html>
<html>
<head>
<title>Acontia 2.0</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="text/javascript" src="../../libraries/jquery.min.js"></script>
<script type="text/javascript" src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<title>Título</title>
<div ID="waitDiv" style="position:absolute;margin-left:350px;visibility:hidden">
<img src="images/loading2.gif" width='70'>
</div>
<style>
	@media print {
  a[href]:after {
    content: none !important;
  }
}
</style>
<SCRIPT>
<!--
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
//-->
</SCRIPT>
</head>
<body>