<?php
	//echo $sql;
?>
<script>
 $(function(){
	var natureVal = '';
	var total = 0;
	$("#tabla_reporte tr").each(function(index){
		$(this).css('text-align','center');
		$('th:contains("Subtotal [")',this).text("Subtotal:").attr('colspan',2);
		$("th:contains('Cuenta:')",this).attr('colspan',5);
		var txt = '';
		var txt2 = "";
			
			if($('td:nth-child(1)',this).text().length > 0)
			{
				natureVal = $('td:nth-child(1)',this).text();
			}
			$('td:nth-child(1)',this).remove();
			natureVal = natureVal.toLowerCase();

		if($(this).hasClass('trencabezado'))
		{
			txt  = "<td>Total Contable</td>";
			txt2 = "<td>Subtotal</td>";
		}
		else
		{
			if($('th',this).hasClass('subtotal'))
			{
				var abonos = parseFloat($('td:nth-child(2)',this).text().replace(',',''));
				var cargos = parseFloat($('td:nth-child(3)',this).text().replace(',',''));
				
				switch(natureVal)
				{
					case 'deudora':
					total = abonos - cargos;					
						break;
					case 'acreedora':			
					total = cargos - abonos;
						break;
				}
				
				txt = "<td>" + total + "</td>";
				txt2 = "<td></td>";
			}
			else
			{
				txt  = "<td>-</td>";
				txt2 = "<td></td>";	
			}
		}
		if($('td',this).length > 1)
		{
			$('th:contains("TOTAL")',this).attr('colspan',2);
			if($( 'th:contains("Subtotal:")',this).length === 0 && $('th:contains("TOTAL")',this).length === 0 )
			{
				//$(this).prepend(txt2);
			}		
			$(this).append(txt);
			
			if( $('th:contains("TOTAL")',this).length > 0 && parseInt($('th:contains("TOTAL")',this).siblings().last().text(),10) !== 0 )
			{
				$("#tabla_reporte").before("<div style='text-align:center; color:red; font-family:Arial;font-size:25px;'>EXISTEN POLIZAS SIN CUADRAR.</div>");
			}
		}
	
	});
	
 }).ready();
function generaexcel()
			{
				$().redirect('../../modulos/cont/views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte table:contains('Estado de Resultados')").html(), 'name': 'Estado de Resultados de ' + $('#empresa tr td').html()});
			}
</script>