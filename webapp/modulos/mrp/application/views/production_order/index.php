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
<!--<link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>netwarelog/design/default/netwarlog.css" / -->  
<?php include('../../netwarelog/design/css.php');?>
<LINK href="<?php echo $base_url;?>netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

<LINK href="<?php echo $base_url;?>netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />	
<LINK href="<?php echo base_url();?>css/mrp.css" title="estilo" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
 <link href="http://netdna.jboxcdn.com/0.3.1/jBox.css" rel="stylesheet"> <!--<Libreria de la notificacion -->

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script type="text/javascript">var baseUrl='<?php echo base_url(); ?>';</script>	
</script><script type="text/javascript" src="<?php echo base_url(); ?>js/production_order.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.alphanumeric.js"></script>
<script src="http://netdna.jboxcdn.com/0.3.1/jBox.min.js"></script><!--Libreria de la notificacion -->
<script type="text/javascript">
	$(function(){
		
		$(".float").numeric({allow:"."});
	});

function notificacion() {
    new jBox('Notice', {
        content: 'Las letras rojas indican que no tienes los suficientes insumos o si tu cantidad a producir es mayor a la que tienes,<br> revisa tu stock de insumos.',
        autoClose: 10000,
        color: 'green'
    });
}

</script>
</head>

<body>
  <div class="nmwatitles">Orden de producción</div>
<table width="95%" align="center">
<tr>
  <td><h3>Seleccione una sucursal para efectuar la orden de producción</h3>
  <label>Sucursal </label><?php echo $sucursales;?>
  <label>Almacen </label><span id="almacenes"><select class="long-input nminputselect"><option>-Seleccione-</option></select></span>
  </td>
   
  <td align="right">
  <input type="button" value="Regresar a listado de ordenes de producción" onclick="window.location='<?php echo base_url();?>index.php/production_order/index'" class="nminputbutton">
  </td>
</tr>

    <tr><td colspan="2">
<fieldset ><legend>Selecciona la producción que deseas generar y posteriormente genera la orden.</legend>
<table width="100%" align="center">
    <tr>
    <td><label>Cantidad</label><input type="text" id="cantidad" class="input-long float nminputtext" maxlength="6"></td>
     <td><label>Producto</label><?php echo $productos;?></td>
    <td><label>Unidad</label><span id="cbounidades"><?php echo $unidades;?></span></td>
    <td><input type="button" value="Agregar a orden de producción" onClick="add();" class="nminputbutton_color2"></td>
    <td><img id="preloader" src="<?php echo base_url();?>/images/preloader.gif"></td>
    </tr>
</table>
</fieldset>
</td></tr></table>
<span id="orden_produccion"><?php echo $orden_produccion;?></span>
<table width="95%" align="center">
    <tr>
    <td align="left"><input type="button" value="Quitar elementos de la lista" onclick="quitaelemento();" class="nminputbutton"></td>	
    
    <!-- <td align="center"><input type="button" value="Producir productos" onclick="produccion();"></td>	 -->
    		
    <td align="right"><br><input type="button" value="Generar ordenes de produccion" onClick="CreateOrden();" class="nminputbutton"></td>
   </tr></table>
   <!--Div del texto de la modal de las opciones de orden de produccion -->
   <div id="contenido-opc" style="display:none;">
    <table>
        <tr>
          <td>
            <label><strong>Primera</strong></label>
            <p>Este proceso, apartara los insumos para producir el productos y  agregará al inventario los productos terminados una vez que se complete la orden de produccion</p>
          </td>
          <td>
            <input type="radio" value="1" name="opciOrd" id="opcionOrd1">
          </td>
        </tr>
        <tr>
          <td>
            <label><strong>Segunda</strong></label>
            <p>Este proceso, descontará los insumos para producir el productos y  agregará al inventario los productos terminado</p>
          </td>
          <td>
            <input type="radio" value="2" name="opciOrd" id="opcionOrd2">
          </td>
        </tr>
    </table>
    </div>
  <!--Fin del div -->  
</body>
</html>
