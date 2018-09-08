<script>
	$(document).ready(function(){
		var txt = $('b:contains("Contabilidad - Hoja de Diario")').parent().text();
		regex =  /Hoja de DiarioPOLIZA=TODOS/g;
		if(!regex.test(txt))
		{
			var html = $('b:contains("Contabilidad - Hoja de Diario")').parent().html();
			html = html.replace("PERIODO INICIAL","");
			html = html.replace("PERIODO FINAL","");
			$('b:contains("Contabilidad - Hoja de Diario")').parent().html(html);
			$('b:contains("Contabilidad - Hoja de Diario")').next().next().nextAll().remove();
			html = $('b:contains("Contabilidad - Hoja de Diario")').parent().html();
			html = html.trim();
			html = html.replace("= &nbsp;",'');
			html = html.replace("= &nbsp;",'');
			$('b:contains("Contabilidad - Hoja de Diario")').parent().html(html);
		}

		$('#tabla_reporte tr').each(function(index){
			
			if($('th:nth-child(1)', this).hasClass('subtotal'))
			{
				$(this).addClass('subtotal');
			}
			if($('td', this).length == 7 && !$(this).hasClass('trcontenido'))
			{
				$(this).addClass('trcontenido');
			}		
			
			if(!$(this).hasClass('subtotal') && !$(this).hasClass('trencabezado'))// Formateo de Fechas
			{
				var txt = $('td:nth-child(2)', this).text().slice(0,10);
				$('td:nth-child(2)', this).text(txt);				
			}
				
			if($(this).hasClass('subtotal'))// Tipo dinero en Subtotales
			{
				var tot_1 = parseFloat($('td:nth-child(2)', this).text().replace(',',''));
				
				$('td:nth-child(2)', this).text(Currency("$",tot_1));
				var tot_2 = parseFloat($('td:nth-child(3)', this).text().replace(',',''));
				
				$('td:nth-child(3)', this).text(Currency("$", tot_2));
				
			}
			
			
			if($(this).hasClass('trcontenido'))// Tipo dinero en cargos/abonos
			{	
				var tot_1 = parseFloat($('td:nth-child(6)', this).text());
				if(!isNaN(tot_1))
				{
					$('td:nth-child(6)', this).text(Currency("$", tot_1));
				}
				var tot_2 = parseFloat($('td:nth-child(7)', this).text());
				if(!isNaN(tot_2))
				{
					$('td:nth-child(7)', this).text(Currency("$", tot_2));
				}
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