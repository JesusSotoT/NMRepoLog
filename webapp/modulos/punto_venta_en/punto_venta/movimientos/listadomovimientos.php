<!DOCTYPE html>
<html>
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
	<LINK href="../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />

<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../punto_venta/js/ui.datepicker-es-MX.js"></script>
<script type="text/javascript">
function almacenes(){
	window.location="almacenes.php";
}

$(function(){
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$("#fin").datepicker({dateFormat: "yy-mm-dd"});
	$("#inicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
	  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		$('#fin').datepicker('setDate', parsedDate);
		$('#fin').datepicker( "option", "minDate", parsedDate);
	}});
	
		
	
	});
	
	function buscalmacen(){
		var alma=jQuery('#busca').val();
		if(alma!="todos"){
		$.post("consultas.php",{opc:9,a:alma},
	function(respues) {
		$('#datos').html(respues); 
		
		
   	});	
   	
   	
   }else{
   window.location.reload();
   }
	}
	function buscafecha(){
		var inicia=jQuery('#inicio').val();
		var fin=jQuery('#fin').val();
		
		$.post("consultas.php",{opc:10,inicio:inicia,fin:fin},
	function(respues) {
		$('#datos').html(respues); 
		
		
   	});	
   }
</script>
	<body>
		<?php 
		include("../../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);
		$i=0;
		$pagina=1;
		// if($pagina==1){$pag_anterior=1;}else{$pag_anterior=$pagina-1;}
	// if(($pagina+1)>$paginas){$pag_siguiente=$pagina;}else{$pag_siguiente=$pagina+1;}	
		?>

		<div class="tipo">
	<table><tbody>
		<tr>
	<td><input type="button" value="<" onclick="paginacionGridCxc(<?php //echo $pag_anterior;?>,1);"></td>
	<td><input type="button" value=">" onclick="paginacionGridCxc(<?php //echo $pag_siguiente;?>,1);" ></td>
	<td><a href="javascript:window.print();">
	<img src="../../../netwarelog/repolog/img/impresora.png" border="0"></a></td>
	<td><b>Historial de movimientos</b></td></tr></tbody></table></div><br>
	
<fieldset style=" font-size:12px;font-weight:bold; color:#6E6E6E;vertical-align:top; display:inline-block;">
	<legend>B&uacute;squeda por almac&eacute;n</legend>
	
	<select id="busca" style="font-size: 10px;">
		<?php
		$busca=$conection->query("select idAlmacen,nombre from almacen");
		if($busca->num_rows>0){ ?>
		<option selected>--Elija un almac&eacute;n--</option>
		
			
		<?php	while($almacen=$busca->fetch_array(MYSQLI_ASSOC)){ ?>
				<option value="<?php echo $almacen['idAlmacen']; ?>"><?php echo $almacen['nombre']; ?></option>
		<?php } ?>
		
		<option value="todos">Todos</option>
		<?php }else{?>	
		
		<option selected>--No hay almacenes registrados--</option>
		<?php } ?>
		</select>
	<input type="button" id="busca" value="Buscar" onclick="buscalmacen();"/>
</fieldset>
<fieldset style=" font-size:12px;font-weight:bold; color:#6E6E6E;width: 50%;vertical-align:top; display:inline-block;">
	<legend>B&uacute;squeda por Fecha</legend>
	
	Fecha Inicio<input type="text" id="inicio" />
	Fecha Final<input type="text" id="fin" />
	<input type="button" id="busca" value="Buscar" onclick="buscafecha();"/>
</fieldset>


<div style="width: 95%;text-align: right;"><input type="button" value="Mover mercanc&iacute;a" onclick="almacenes();"></div>
<br>
	
	
		<table class="busqueda" id="datos" cellpadding="3" cellspacing="1" width="95%" height="95%" >
			<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th align="center">ID</th>
			<th align="center">Almac&eacute;n Origen</th>
			<th align="center">Cantidad Total Origen</th>
			<th align="center">Movimiento</th>
			<th align="center">Almac&eacute;n Destino</th>
			<th align="center">Cantidad Total Destino</th>
			<th align="center">Fecha</th>
			</tr>
				<?php $consul=$conection->query("select mm.id,
				(select nombre from almacen where idAlmacen=mm.idAlmacenOrigen) almacenorigen,
				mm.cantidadtotalOrigen,
				concat(mm.cantidadmovimiento,' ',u.compuesto,' de ',p.nombre) movimiento,
                (select nombre from almacen where idAlmacen=mm.idAlmacenDestino) almacendestino,
                mm.cantidadtotalDestino,mm.fechamovimiento
                from movimientos_mercancia mm,mrp_producto p,mrp_unidades u,almacen a
                where   mm.idAlmacenDestino=a.idAlmacen and mm.idProducto=p.idProducto
                and mm.idUnidad=u.idUni GROUP BY mm.id;"); 
	//$paginas=($consul->num_rows/$paginacion);if($consul->num_rows%$paginacion!=0){$paginas++;}
				
                while($lista=$consul->fetch_array(MYSQLI_ASSOC)){ ?>
                	<tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
                <td align="center"> 
			<?php echo $lista['id']; ?>
				</td >
				 <td align="center">
			<?php echo $lista['almacenorigen']; ?>
				</td>
				<td align="center">
			<?php echo $lista['cantidadtotalOrigen']; ?>
				</td>
				<td align="center">
			<?php echo $lista['movimiento']; ?>
				</td>
				<td align="center">
			<?php echo $lista['almacendestino']; ?>
				</td>
				<td align="center">
			<?php echo $lista['cantidadtotalDestino']; ?>
				</td>
				<td align="center">
			<?php echo $lista['fechamovimiento']; ?>
				</td>
				
			</tr>
			<?php }
			
	
		for($j=$i;$j<5;$j++)
		{	?>
		<tr class="busqueda_fila"><tr class="busqueda_fila2">
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>
		<?php }
	
			?>
			
			
		</table>
		<?php $conection->close(); ?>
</body>

</html>