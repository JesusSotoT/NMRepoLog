<!DOCTYPE html>
<html>
	<script type="text/javascript" src="../js/jquery-1.10.2.min.js"></script>
	<LINK href="../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
<LINK href="../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />

<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet">
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="../../punto_venta/js/ui.datepicker-es-MX.js"></script>
<script type="text/javascript">
	$(function(){
	$.datepicker.setDefaults($.datepicker.regional['es-MX']);
	$("#fin").datepicker({dateFormat: "yy-mm-dd"});
	$("#inicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
	  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
		$('#fin').datepicker('setDate', parsedDate);
		$('#fin').datepicker( "option", "minDate", parsedDate);
	}});
	
		
	
	});
	function buscaclient(){
		var cliente=jQuery('#busca').val();
		if(cliente!="--Elija un Cliente--"){
		if(cliente!="todos"){
		$.post("consulta.php",{opc:1,cliente:cliente},
	function(respues) {
		$('#datos').html(respues); 
		
		
   	});	
   	
   	
   }else{
   window.location.reload();
   }
	}else{
	alert("Elija un Cliente");
	}
}
	function buscafecha(){
		var inicia=jQuery('#inicio').val();
		var fin=jQuery('#fin').val();
		
		$.post("consulta.php",{opc:2,inicio:inicia,fin:fin},
	function(respues) {
		$('#datos').html(respues); 
		
		
   	});	
   }
</script>
<body>
<?php
    include("../../../netwarelog/webconfig.php");
		$conection = new mysqli($servidor,$usuariobd,$clavebd,$bd);	
?>
<fieldset style=" font-size:12px;font-weight:bold; color:#6E6E6E;vertical-align:top; display:inline-block;">
	<legend>B&uacute;squeda por Cliente</legend>
	
	<select id="busca" style="font-size: 10px;">
		<?php
		$busca=$conection->query("select c.idCxc ID,cc.nombre Nombre,c.idCliente
from cxc c,comun_cliente cc
where cc.id=c.idCliente and   c.estatus= 0 GROUP BY  cc.nombre");
		if($busca->num_rows>0){ ?>
		<option selected>--Elija un Cliente--</option>
		
			
		<?php	while($cliente=$busca->fetch_array(MYSQLI_ASSOC)){ ?>
				<option value="<?php echo $cliente['idCliente']; ?>"><?php echo $cliente['Nombre']; ?></option>
		<?php } ?>
		
		<option value="todos">Todos</option>
		<?php }else{?>	
		
		<option selected>--No hay Clientes--</option>
		<?php } ?>
		</select>
	<input type="button" id="busca" value="Buscar" onclick="buscaclient();"/>
</fieldset>

<fieldset style=" font-size:12px;font-weight:bold; color:#6E6E6E;width: 50%;vertical-align:top; display:inline-block;">
	<legend>B&uacute;squeda por Fecha</legend>
	
	Fecha Inicio<input type="text" id="inicio" />
	Fecha Final<input type="text" id="fin" />
	<input type="button" id="busca" value="Buscar" onclick="buscafecha();"/>
</fieldset>
<br></br><br>
<table class="busqueda" id="datos" cellpadding="3" cellspacing="1" width="95%" height="95%" >
			<tr class="tit_tabla_buscar" title="Segmento de búsqueda" style="font-size: 9pt;">
			<th align="center">ID</th>
			<th align="center">Fecha Cargo</th>
			<th align="center">Fecha Vencimiento</th>
			<th align="center">Nombre</th>
			<th align="center">Concepto</th>
			<th align="center">Folio de venta</th>
			<th align="center">Monto</th>
			<th align="center">Saldo Abonado</th>
			<th align="center">Saldo Actual</th>
			</tr>
				<?php $consul=$conection->query("select c.idCxc ID,cc.nombre Nombre,
				c.concepto,c.idVenta,c.monto,c.saldoabonado,c.saldoactual, SaldoActual,c.estatus,c.fechacargo,c.fechavencimiento
from cxc c,comun_cliente cc
where cc.id=c.idCliente 
and c.estatus=0 "); 
	//$paginas=($consul->num_rows/$paginacion);if($consul->num_rows%$paginacion!=0){$paginas++;}
				$Saldoabonado=0;
				$SaldoActual=0;
				$monto=0;
                while($lista=$consul->fetch_array(MYSQLI_ASSOC)){ ?>
          <tr class="busqueda_fila" style=" color:#6E6E6E; font-size: 10pt;">
                <td align="center"> 
			<?php echo $lista['ID']; ?>
				</td >
				<td align="center">
			<?php echo $lista['fechacargo']; ?>
				</td>
				<td align="center">
			<?php echo $lista['fechavencimiento']; ?>
			
				</td>
				 <td align="center">
			<?php echo $lista['Nombre']; ?>
				</td>
				<td align="center">
			<?php echo $lista['concepto']; ?>
				</td>
				<td align="center">
			<?php echo $lista['idVenta']; ?>
				</td>
				<td align="center">
			<?php echo $lista['monto']; ?>
<?php $monto=$monto+$lista['monto']; ?>

				</td>
				<td align="center">
			<?php echo $lista['saldoabonado']; ?>
				<?php $Saldoabonado=$Saldoabonado+$lista['saldoabonado']; ?>
				</td>
				<td align="center">
			<?php echo $lista['SaldoActual']; ?>
			<?php $SaldoActual=$SaldoActual+$lista['SaldoActual']; ?>
				</td>
				
				
			</tr>
			<?php }?>
<tr style="background:#333333;color: #FFFFFF"><td></td><td></td>
	<td></td><td></td>
	<td></td><td style="font-size: 14px;font-weight:bold;font: color:">Totales</td>
	<td style="font-size: 14px;font-weight:bold">$<?php echo $monto; ?></td>
	<td style="font-size: 14px;font-weight:bold">$<?php echo $Saldoabonado; ?></td>
	<td style="font-size: 14px;font-weight:bold">$<?php echo $SaldoActual; ?></td></tr>
	
	<?php	for($j=0;$j<5;$j++)
		{	?>
		<tr class="busqueda_fila"><tr class="busqueda_fila2">
	<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
		</tr>
		<?php }?>
			
			
		</table>



</body>
</html>