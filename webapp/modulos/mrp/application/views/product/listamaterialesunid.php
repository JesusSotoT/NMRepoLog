<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>

<html>
<head>
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url; ?>netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.alphanumeric.js"></script> 
	<script>
		$(function(){
			
			$(".float").numeric({allow:'.'});
			
		});
		
	</script> 
</head>
<body>
<table><tr class="listadofila"><td class="campo">
<div id="listaproductosunid">
	<select id='ventaunid'>
		<option value="0">Selecciona</option>
		<?php foreach ($unidades as $key => $value) { ?>
			<option value=""> <?php echo $value->compuesto; ?> </option>
		<?php } ?>
	</select>
	 1 Carrete equivale a  <input type="text" id="convers"> Metros
</div>
</td></tr></table>
</body>
</html>