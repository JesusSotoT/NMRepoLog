<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Orden de producción</title>
<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />	
<LINK href="<?php echo base_url(); ?>css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
 <!-- <link rel="stylesheet" type="text/css" href="../../../../netwarelog/design/default/netwarlog.css" /-->  	
 <!-- <link rel="stylesheet" type="text/css" href="../../../netwarelog/design/default/netwarlog.css" / -->  	
<?php include('../../netwarelog/design/css.php');?>
<LINK href="../../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';
$(function(){
	
	$(".preload").hide();
/*	$("#fecha_terminacion").datepicker();
	$("#fecha_terminacion").datepicker('option',
		{dateFormat: 'yy-mm-dd',minDate:0});
	$("#fecha_fin").datepicker();
	$("#fecha_fin").datepicker('option',
		{dateFormat: 'yy-mm-dd',minDate:0}); */
 $( "#fecha_terminacion" ).datepicker({
	dateFormat: 'yy-mm-dd', 	
	minDate:0, 	
	defaultDate: "+1w",
	changeMonth: true,
	numberOfMonths: 1,
	onClose: function( selectedDate ) {
	$( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
}
});
$( "#fecha_fin" ).datepicker({
	dateFormat: 'yy-mm-dd',
	defaultDate: "+1w",
	changeMonth: true,
	numberOfMonths: 1,
	onClose: function( selectedDate ) {
	$( "#fecha_terminacion" ).datepicker( "option", "maxDate", selectedDate );
}
});
 $( "#fecha_inicio" ).datepicker({
	dateFormat: 'yy-mm-dd', 	
	minDate:0, 	
	defaultDate: "+1w",
	changeMonth: true,
	numberOfMonths: 1,
	onClose: function( selectedDate ) {
	$( "#fecha_fin" ).datepicker( "option", "minDate", selectedDate );
}
});
$( "#fecha_fin" ).datepicker({
	dateFormat: 'yy-mm-dd',
	defaultDate: "+1w",
	changeMonth: true,
	numberOfMonths: 1,
	onClose: function( selectedDate ) {
	$( "#fecha_inicio" ).datepicker( "option", "maxDate", selectedDate );
}
});

	
});	
</script>	
</script><script type="text/javascript" src="<?php echo base_url(); ?>js/production_order.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.alphanumeric.js"></script>

</head>
<body>
<h3>Seleccione el proveedor para cada material y posteriormente generar las ordenes de compra</h3>
<table width="100%"><tr><td><label>Fecha de Produccion</label><input type="text" id="fecha_inicio" class="nminputtext"><label> Fecha de terminacion</label><input type="text" id="fecha_fin" class="nminputtext"></td><td align="right"><input type="button" value="Regresar" class="nminputbutton" onclick="window.location='<?php echo base_url();?>/index.php/production_order/index2'"></td></tr></table>
<br>
<?php echo $materiales;?>
</body>
</html>
