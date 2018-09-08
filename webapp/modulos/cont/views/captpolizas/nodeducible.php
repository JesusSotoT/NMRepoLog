<style type="text/css">
 
#tabla{	border: solid 1px #333;	width: 300px; }
#tabla tbody tr{ background: #999; }
#tabla tfoot tr{ background: #999; }
.fila-base{ display: none; } /* fila base oculta */
.eliminar{ cursor: pointer; color: #000; }
input[type="text"]{ width: 80px; } /* ancho a los elementos input="text" */
 
</style>
<script type="text/javascript">

$(function(){
	// Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
	$("#agregar").on('click', function(){
		$("#tabla tbody tr:eq(0)").clone().removeClass('fila-base').appendTo("#tabla tbody");
	});
 
	// Evento que selecciona la fila y la elimina 
	$(document).on("click",".eliminar",function(){
		var parent = $(this).parents().get(0);
		$(parent).remove();
		calculaNoDeducible();
	});
	
	
});

</script>
<div id="userdeducible">
	<div class="nmwatitles">No Deducibles <label id="anticipode"></label></div>
	
<br>
<form action="index.php?c=CaptPolizas&f=nodeducibleUser" method="POST"> 
<input type="hidden" id="idanticipo" name="idanticipo">
<font style="color: red;alignment-adjust: central"> No deducibles registrados ya no apareceran en la lista.</font>
<br>
<table id="tabla" width="100%">
	<thead>
		<tr>
			
			<th>Monto</th>
			<th>Concepto</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
 
	<tbody>
 		<tr class="fila-base">
			<td><input type="text" id="importe[]" name="importe[]" onkeyup="calculaNoDeducible();"/></td>
			<td><input type="text" id="concepto[]" name="concepto[]" size="255" /></td>
			<td class="eliminar">Eliminar</td>
		</tr>
		
		
	</tbody>
	<tfoot></tfoot>
</table>
<br>
<input type="button" id="agregar" value="+" />
<input type="submit" style="display: none" id="envia" />
</form>
<div align="center" class="nmwatitles">
	Monto Total:&nbsp; $<label style="color: red;" id="totalnodedu" >0.00</label>
	</div>
</div>