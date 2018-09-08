<script>
$(document).ready(function(){
	var total = 0;
	var totalAbonos = 0;
	var totalCargos = 0;
	var totalDeudor = 0;
	var totalAcreedor = 0;
	
	$('td').each(function(){
		if($(this).text().length === 0 )
		{
			$(this).text(0);
		}
	});

	$("#tabla_reporte > tbody tr").each(function()
	{
		var nature = parseInt($("td:nth-child(1)",this).text(),10);
		$("td:nth-child(1)",this).remove();
		$(this).css('text-align','center');
		
		if($(this).hasClass('trencabezado'))
		{
			$(this).append("<td>Saldo Acreedor</td><td>Saldo Deudor</td>");
		}
		else
		{
			var abonos = parseFloat($("td:nth-child(3)",this).text());
			var cargos = parseFloat($("td:nth-child(2)",this).text());
			var txt = "";
			totalAbonos += abonos;
			totalCargos += cargos;
			
			if(nature === 1)
			{
				total = abonos - cargos;
				totalAcreedor += total;
				if(total >= 0)
				{
					txt = "<td>" + total + "</td><td>" + 0 + "</td>";
				}
				else
				{
					txt = "<td style='color:red;'>" + total + "</td><td>" + 0 + "</td>";
				}		
			}
			else
			{
				total = cargos - abonos;
				if(total >= 0)
				{
					txt = "<td>" +  0 + "</td><td>" + total + "</td>";
				}
				else
				{
					txt = "<td>" +  0+ "</td><td style='color:red;'>" + total + "</td>";
				}
				
			
				totalDeudor += total;
			}
			$(this).append(txt);
		}
	});
	
	var finalTxt =  '<tr style="text-align:center">';
		finalTxt += '	<td style="border:solid 1px;background-color:#efefef;text-align:left;font-size:12px;" class="subtotal">';
		finalTxt += '		<b>TOTAL</b>';
		finalTxt += '	</td>';
		finalTxt += '	<td style="background-color:#efefef;" >';
		finalTxt += '		<b>' + totalAbonos + '</b>';
		finalTxt += '	</td>';
		finalTxt += '	<td style="background-color:#efefef;" >';
		finalTxt += '		<b>' + totalCargos + '</b>';
		finalTxt += '	</td>';
		finalTxt += '	<td style="background-color:#efefef;" >';
		finalTxt += '		<b>' + totalAcreedor + '</b>';
		finalTxt += '	</td>';
		finalTxt += '	<td style="background-color:#efefef;" >';
		finalTxt += '	<b>' + totalDeudor + '</b>';
		finalTxt += '	</td>';
		finalTxt += '</tr>';
	$("#tabla_reporte > tbody").append(finalTxt);
	$('#tabla_reporte tbody tr').each(function(){
		if(!$(this).hasClass('trencabezado'))
		{
			$('td', this).each(function(index){
				if(index > 0)
				{
					var txt = parseFloat($(this).text().trim());
					txt = Currency("$",txt);
					$(this).text(txt);
				}
			});
		}
	});
	
});
function Currency(sSymbol, vValue) 
{
	aDigits = vValue.toFixed(2).split(".");
	aDigits[0] = aDigits[0].split("").reverse().join("").replace(/(\d{3})(?=\d)/g, "$1,").split("").reverse().join("");
	return sSymbol + aDigits.join(".");
}
</script>