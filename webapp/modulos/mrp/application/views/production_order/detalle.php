<?php 
$this->load->helper('url');
$base_url=str_replace("modulos/mrp/","",base_url());
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Orden de producción</title>
<LINK href="<?php echo $base_url;?>netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="<?php echo $base_url;?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />	
<LINK href="<?php echo base_url();?>css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<!--  <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>netwarelog/design/default/netwarlog.css" /-->   
<?php include('../../netwarelog/design/css.php');?>
        <LINK href="<?php echo $base_url;?>netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';
$(function(){
	$(".accordion" ).accordion({active: false,heightStyle:"content",collapsible: true });
});	


	
</script>	
</script><script type="text/javascript" src="<?php echo base_url(); ?>js/production_order.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.alphanumeric.js"></script>


</head>

<body>
  <div class="nmwatitles">Orden de producción</div>
<table width="95%" align="center">
<tr>
  <td>
  <label>Almacen:</label><strong><?php echo $almacen;?></strong></td>
  <td>
   	<?php 
		$propiedad1=$propiedad2=$propiedad3=$propiedad4="";
		switch ($estatus) {
		    case 0:
		        $propiedad1="selected";
		        break;
		    case 1:
		        $propiedad2="selected";
		        break;
		    case 2:
		        $propiedad3="selected";
		        break;
			case 2:
		       $propiedad4="selected";
			    break;
		}

   	?>
	   	<select id="estatus" class="nminputselect">
	  		<!--<option <?php echo $propiedad1;?> value="0">Registrada</option>-->
	  		<option <?php echo $propiedad2;?> value="1">En proceso</option>
      		<option <?php echo $propiedad3;?> value="2">Terminada</option>
      		<option <?php echo $propiedad4;?> value="3">Cancelada</option>
	  	</select>
	 	<?php 
	   	if($estatus==0 || $estatus==1){?> 	
	  	<input type="button" value="Cambiar estatus" onclick="terminacancelado(<?php echo $id;?>);" class="nminputbutton_color2">
  	<?php } ?>
  	</td>
  <td align="right" width="20%">
  <input type="button" value="Regresar a listado de ordenes de produccion" onclick="window.location='<?php echo base_url();?>index.php/production_order/index'" class="nminputbutton">
  </td>
  

</tr>

</tr></table>
<br>
<span id="orden_produccion"><?php echo $detalle;?></span>
<div id="etapas" style="margin:0px 0px 100px 27px;">



</div>



</body>
</html>
