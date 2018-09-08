<script src="../../modulos/cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function(){
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};
	var resultadoacumulado;
	var resultado;
	var saldo=0;
	var ingresosacumulados=0;
	var egresosacumulados=0;
	var ingresos=0;
	var egresos=0;
	var naturaleza;
	var tipo;
	var meses=0;
	function escribe(naturaleza, tipo, tabla, res, acumulado)
	{
		if(tipo == 1 && naturaleza == 'ACREEDORA')
		{
			res = res * -1;
		}

		if(tipo == 2 && naturaleza == 'DEUDORA')
		{
			res = res * -1;
		}

		if(acumulado)
		{
			$('td:nth-child(10)',tabla).text(res);
		}
		else
		{
			$('td:nth-child(9)',tabla).text(res);
		}
			//$('td:nth-child(9)',tabla).text("$"+res.format());
			return res;
	}
$('.subtotal').css('text-align','right');	
$('#tabla_reporte th[colspan=9]').attr('colspan',10);
	$("#tabla_reporte tr").each(function(index)
	{
		if($('td',this).length > 1)
		{
			if($(this).hasClass('trencabezado'))
			{
				$(this).append("<td>Cantidad de Mes</td><td>Cantidad Acumulada</td>");
			}
			else
			{
				var clase = $('td:nth-child(8)',this).text();
				$(this).append("<td class='extra'></td><td class='extra'></td><td class='extra'></td><td class='extra'></td>");
				var cargos = parseFloat($('td:nth-child(3)',this).text());
				var abonos = parseFloat($('td:nth-child(4)',this).text());
				var cargosacumulados = parseFloat($('td:nth-child(1)',this).text());
				var abonosacumulados = parseFloat($('td:nth-child(2)',this).text());
				if(isNaN(cargos)){cargos=0}
				if(isNaN(abonos)){abonos=0}
				if(isNaN(cargosacumulados)){cargosacumulados=0}
				if(isNaN(abonosacumulados)){abonosacumulados=0}
				if($('td:nth-child(7)',this).text() == 'ACREEDORA')
				{
				
					resultado=abonos-cargos;
					resultadoacumulado=abonosacumulados-cargosacumulados;
					naturaleza = 'ACREEDORA';
				}
				else
				{
					resultadoacumulado=cargosacumulados-abonosacumulados;
					resultado=cargos-abonos;
					naturaleza = 'DEUDORA';
				
				}
					
					$('td:nth-child(9)',this).text(resultado);
					$('td:nth-child(10)',this).text(resultadoacumulado);
					$('td:nth-child(11)',this).text("$"+resultado.format());
					$('td:nth-child(12)',this).text("$"+resultadoacumulado.format());
					
					
					
						if($('td:nth-child(5)',this).text().match("^4.1"))
						{
							//resultado = escribe(naturaleza,2,this,resultado)
							resultadoacumulado = escribe(naturaleza,2,this,resultadoacumulado,1);
							ingresosacumulados += resultadoacumulado;
							resultado = escribe(naturaleza,2,this,resultado,0);
							ingresos += resultado;
						}
						if($('td:nth-child(5)',this).text().match("^4.2"))
						{
							//resultado = escribe(naturaleza,1,this,resultado);
							resultadoacumulado = escribe(naturaleza,1,this,resultadoacumulado,1);
							egresosacumulados += resultadoacumulado;
							resultado = escribe(naturaleza,1,this,resultado,0);
							egresos += resultado;
						}	
				
			}					
		}				
	});
	
	var subsuma=0;
	var subsumaacumulada=0;
	$("tr").each(function(index)
	{
		if($('td:nth-child(10)',this).text()!='' && $('td:nth-child(10)',this).text()!='Cantidad')
		{
			subsuma += parseFloat($('td:nth-child(10)',this).text())
			subsumaacumulada += parseFloat($('td:nth-child(10)',this).text())
			sumames += parseFloat($('td:nth-child(9)',this).text())
			

		}
		else
		{
			if(subsuma!=0)
			{

				$(this).append("<td colspan='2' style='font-size:14px;font-weight:bold;border:1px solid gray;text-align:right;background-color: rgb(239, 239, 239)'></td><td colspan='1'style='font-size:14px;font-weight:bold;border:1px solid gray;text-align:left;background-color: rgb(239, 239, 239)' class='mes'>$"+sumames.format()+"</td><td class='ubsumas' style='font-size:14px;font-weight:bold;border:1px solid gray;text-align:left;background-color: rgb(239, 239, 239)' colspan='1'>$"+subsuma.format()+"</td>");
				$("tr:not(:contains('$NaN')) td").css('border','0px');
				$(".ubsumas:contains('$NaN')").remove()
				$(".mes:contains('$NaN')").remove()
			}	
			subsuma=0
			sumames=0

		}		
	if($('td:nth-child(10)',this).text()=='0')
	{
	$(this).prev().find("th").remove()
	
	$('td',this).remove();
	}
	});
	
	
	if(ingresos<0)
	{
		$("th:contains('Tipo: Ingresos')",this).css('color','red');
	}
	$("th:contains('Tipo: Ingresos')",this).text("Total Ingresos: $"+ingresos.format()).css('font-size','15px');
	
	if(egresos<0)
	{
		$("th:contains('Tipo: Egresos')",this).css('color','red');
	}
	$("th:contains('Tipo: Egresos')",this).text("Total Egresos: $"+egresos.format()).css('font-size','15px');
	$("th:contains('Subtotal [Tipo: Resultados]')",this).remove();
	$("th:contains('Tipo:Resultados')",this).remove();
	
	saldo = ingresosacumulados-egresosacumulados;
	m1 = $('.mes:first').text().replace(/\,/g,'')
	m1 = m1.replace('$','')
	m2 = $('.mes:last').text().replace(/\,/g,'')
	m2 = m2.replace('$','')
	meses = parseFloat(m1 - m2) 
	//alert(meses)
	$(".subtotal:last").parent().removeAttr('style').css('background-color','black')
	$(".subtotal:last").parent().after().html("<td colspan='2' style='color:white;'>Total Resultados del Ejercicio:</td><td colspan='1' id='totalMeses'> $"+ meses.format() +"</td><td colspan='1' id='totalSaldo'> $"+saldo.format()).css('font-size','20px')+"</td>";
	if(saldo<0)
	{
		$("#totalSaldo").css('color','red');
	}
	else
	{
		$("#totalSaldo").css('color','white');
	}
	if(meses<0)
	{
		$("#totalMeses").css('color','red');
	}
	else
	{
		$("#totalMeses").css('color','white');
	}
	$('.extra').css('border','0px')
	//$('.trencabezado').remove()
	$('.trencabezado td:nth-child(1)').remove()
	$('.trencabezado td:nth-child(1)').remove()
	$('.trencabezado td:nth-child(1)').remove()
	$('.trencabezado td:nth-child(1)').remove()
	$('.trencabezado td:nth-child(1)').remove()
	$('.trencabezado td:nth-child(2)').remove()
	$('.trencabezado td:nth-child(2)').text('Cuenta de Mayor');
	$('.trencabezado td:nth-child(5)').text('Total del Grupo').removeAttr('class').removeAttr('style');
	
	$('.tdcontenido:nth-child(1)').remove()
	$('.tdcontenido:nth-child(1)').remove()
	$('.tdcontenido:nth-child(1)').remove()
	$('.tdcontenido:nth-child(1)').remove()
	$('.tdcontenido:nth-child(1)').remove()
	$('.tdcontenido:nth-child(2)').remove()
	$('.extra:nth-child(3)').remove()
	$('.extra:nth-child(3)').remove()

	
	$('th').css('width','500px')
	$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='../../modulos/cont/images/images.jpg' width='35px'></a></td>")


	
});	
function generaexcel()
			{
				$().redirect('../../modulos/cont/views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte table:contains('Estado de Resultados')").html(), 'name': 'Estado de Resultados de ' + $('#empresa tr td').html()});
			}
</script>
