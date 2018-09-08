
<script type="text/javascript" src="../../modulos/cont/js/jquery-1.10.2.min.js"></script>
<script src="../../modulos/cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script language='javascript'>
$(document).ready(function(){
	Number.prototype.format = function() {
	    return this.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
	};
	var resultado;
	var activos=0;
	var pasivos=0;
	var capital=0;
	var resultados=0;
	var saldo=0;
	var ingresos=0;
	var egresos=0;
	var naturaleza;
	var tipo;
	function escribe(naturaleza, tipo, tabla, res)
	{
		if(tipo == 1 && naturaleza == 'ACREEDORA')
		{
			res = res * -1;
		}

		if(tipo == 2 && naturaleza == 'DEUDORA')
		{
			res = res * -1;
		}
			$('td:nth-child(8)',tabla).text(res);
			$('td:nth-child(9)',tabla).text("$"+res.format());
			return res;
	}

$('.subtotal').css('text-align','right');	
$('#tabla_reporte th[colspan=9]').attr('colspan',10);
	$("#tabla_reporte tr").each(function(index)
	{

		$("td:contains('%')",this).css('text-align','left');
		if($('td',this).length > 1)
		{
			
			if($(this).hasClass('trencabezado'))
			{
				$(this).append("<td>Cantidad</td><td></td>");
			}
			else
			{
				var clase = $('td:nth-child(6)',this).text();
				$(this).append("<td class='extra'></td><td class='extra'></td>");
				var cargos = parseFloat($('td:nth-child(1)',this).text());
				var abonos = parseFloat($('td:nth-child(2)',this).text());
				if(isNaN(cargos)){cargos=0}
				if(isNaN(abonos)){abonos=0}
				if($('td:nth-child(5)',this).text() == 'ACREEDORA')
				{
				
					resultado=abonos-cargos;
					naturaleza = 'ACREEDORA';
					
				}
				else
				{
					resultado=cargos-abonos;
					naturaleza = 'DEUDORA';
				
				}
					
					
					if($('td:nth-child(3)',this).text().match("^1"))
					{
						resultado = escribe(naturaleza,1,this,resultado)
						activos += resultado;
					}
					if($('td:nth-child(3)',this).text().match("^2"))
					{
						resultado = escribe(naturaleza,2,this,resultado)
						pasivos += resultado;
					}
					if($('td:nth-child(3)',this).text().match("^3"))
					{
						resultado = escribe(naturaleza,2,this,resultado)
						capital += resultado;
					}
					if($('td:nth-child(3)',this).text().match("^4"))
					{
						if($('td:nth-child(3)',this).text().match("^4.1"))
						{
							resultado = escribe(naturaleza,2,this,resultado)
							ingresos += resultado;
						}
						if($('td:nth-child(3)',this).text().match("^4.2"))
						{
							resultado = escribe(naturaleza,1,this,resultado)
							egresos += resultado;
						}
					//$(this).prev().remove();
					$(this).remove();
					
					}
				
						
				
			}					
		}				
	});
	
	var subsuma=0;
	$("tr").each(function(index)
	{
		
		if($('td:nth-child(8)',this).text()!='' && $('td:nth-child(8)',this).text()!='Cantidad'){
		subsuma += parseFloat($('td:nth-child(8)',this).text())
		

		}else{
		if(subsuma!=0)
		{
		$(this).prev().append("<td class='ubsumas' style='font-size:14px;font-weight:bold;border:0px;text-align:right;'>$"+subsuma.format()+"</td>")
		}	
		subsuma=0
		}		
	if($('td:nth-child(8)',this).text()=='0')
	{
	//$(this).prev().find("th").remove()
	
	$(this).remove();
	}
	});
	if(activos<0)
	{
		$("th:contains('Tipo: Activo')",this).css('color','red');
	}
	$("th:contains('Tipo: Activo')",this).css('background-color','000000').css("color","white");
	$("th:contains('Tipo: Activo')",this).text("Suma de Activos: $"+activos.format()).css('font-size','15px');
	
	if(pasivos<0)
	{
		$("th:contains('Tipo: Pasivo')",this).css('color','red');
	}
	$("th:contains('Tipo: Pasivo')",this).text("Total Pasivos: $"+pasivos.format()).css('font-size','15px');
	
	if(capital<0)
	{
		$("th:contains('Tipo: Capital')",this).css('color','red');
	}
	$("th:contains('Tipo: Capital')",this).text("Total Capital: $"+capital.format()).css('font-size','15px');
	
	if(resultados<0)
	{
		$("th:contains('Tipo: Resultados')",this).css('color','red');
	}
	resultados = ingresos - egresos;
	$("th:contains('Tipo: Resultados')",this).text("Total Resultados del ejercicio: $"+resultados.format()).css('font-size','15px');
	$("tr:contains('Total Resultados')",this).after("<tr><td style='border:none;'><br /></td></tr><tr><th style='border: 1px solid; background-color:#000000;color:white; text-align: right; font-size: 15px; width: 500px;' class='subtotal' colspan='6' >Suma de Pasivo, Capital y Resultados: $"+(pasivos + capital + resultados).format()+" </th></tr>");

	
	
	saldo = activos - (pasivos + capital + resultados);
	$(".subtotal:last").text("Diferencia: $"+saldo.format()).css('font-size','20px');
	if(saldo<0)
	{
	$(".subtotal:last").css('color','red');
	}
	$('.extra').css('border','0px')
	$('.trencabezado td:nth-child(1)').remove()
	$('.trencabezado td:nth-child(1)').remove()
	$('.trencabezado td:nth-child(1)').remove()
	$('.trencabezado td:nth-child(2)').remove()			
	$('.trencabezado td:nth-child(3)').remove()		
	$('.trencabezado td:nth-child(2)').text('Cuenta de Mayor');
	$('.trencabezado td:nth-child(4)').text('Total del grupo');

	
	
	
	//$('.trencabezado').remove()
	$('.tdcontenido:nth-child(1)').remove()
	$('.tdcontenido:nth-child(1)').remove()
	$('.tdcontenido:nth-child(1)').remove()
	$('.tdcontenido:nth-child(2)').remove()
	$('.tdcontenido:nth-child(3)').remove()
	$('.tdcontenido:nth-child(3)').remove()
	$('.extra:nth-child(3)').remove()

	$('.tdcontenido:nth-child(1)').css('border','0px')
	$('.tdcontenido:nth-child(2)').css('border','0px')
	$("th:contains('Tipo:Resultados')",this).remove();
	$("th:contains('Grupo:INGRESOS')",this).remove();
	$("th:contains('Grupo:EGRESOS')",this).remove();
	
	$('.tdmoneda').css('border','0px')
	$('th').css('width','500px')
	$("th:contains('TOTAL')").remove()
	$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='../../modulos/cont/images/images.jpg' width='35px'></a></td>")
	
});	
function generaexcel()
			{
				$().redirect('../../modulos/cont/views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte table:contains('Balance General')").html(), 'name': 'Balance General de ' + $('#empresa tr td').html()});
			}
			
 // document.getElementById("tabla_reporte").onclick=function(e){ 
     // if(!e)e=window.event; 
     // if(!e.target) e.target=e.srcElement; 
    // var TR=e.target;
     // while( TR.nodeType==1 && TR.tagName.toUpperCase()!="TR" )
         // TR=TR.parentNode;
     // var celdas=TR.getElementsByTagName("TD");
     // if( celdas.length!=0 ){
    	 // var cuenta=celdas[0].innerHTML;
    	 // var peri=$("td[class='nmrepologtitle']").html();
    	 // peri=peri.replace('<span>Balance General</span><br><br>EJERCICIO=<b>','');
    	 // peri=peri.replace('</b> &nbsp; PERIODO=<b>','/');  
    	 // peri=peri.replace('</b> &nbsp; ','');
    	 // peri=peri.replace(/\s/g,'');
    	 // peri=peri.split('/');
    	 // var ano=peri[0];
    	 // var periodo=meses(peri[1]);     	 
// 
    	// // //window.location="index.php?c=Reports&f=movcuentas_despues"
    	   // $.post('../../modulos/cont_repolog/afectamayor.php',{
    	 	  // mayor:cuenta
    	  // },function (resp){
//     	  	
    	  	    	  // var cuentass = $.parseJSON(resp);
    	  	  // //var cuentass = JSON.stringify(resp);
   	 	 // // //window.location="index.php?c=Reports&f=movcuentas_despues&cuentas="+resp+"&rango=1&f3_3="+ano+"&f3_1="+periodo+"&f3_2=01&f4_3="+ano+"&f4_1="+periodo+"&f4_2=31";
    	   // //window.location="../../modulos/cont/index.php?c=Reports&f=movcuentas_despues&a=1&cuentas[]="+cuentass+"&f3_3="+ano+"&f3_1="+periodo+"&f3_2=01&f4_3="+ano+"&f4_1="+periodo+"&f4_2=31";
    	   // });
//     	
    	 // // // window.location="../../modulos/cont/index.php?c=Reports&f=movcuentas_despues&cuentas="+cuent+"&f3_3="+ano+"&f3_1="+periodo+"&f3_2=01&f4_3="+ano+"&f4_1="+periodo+"&f4_2=31";
// // 
     // }
//          
       // // // window.location="index.php?c=Reports&f=movcuentas";
 // }
function meses(mess){
	var mes=[];
	 mes['ENERO']='01'
	 mes['FEBRERO']='02'
	 mes['MARZO']='03'
	 mes['ABRIL']='04'
	 mes['MAYO']='05'
	 mes['JUNIO']='06'
	 mes['JULIO']='07'
	 mes['AGOSTO']='08'
	 mes['SEPTIEMBRE']='09'
	 mes['OCTUBRE']=10
	 mes['NOVIEMBRE']=11
	 mes['DICIEMBRE']=12

	 return mes[mess];
}
//	alert($("td[title='Codigo']").html());
</script>