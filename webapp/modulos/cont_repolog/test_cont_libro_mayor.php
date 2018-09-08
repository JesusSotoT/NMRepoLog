<!--// data-type : Cargo o Abono
// data-code : Codigo de Cuenta. Para saber si es Activo, Pasivo, Capital o Resultados
// data-nature : Acreedor o Deudor -->
<script src="../../modulos/cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script>
	$(document).ready(function(){
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(4)').remove()
		$('.trencabezado td:nth-child(3)').remove()
		$('.trencabezado td:nth-child(7)').remove()
		$('.trencabezado td:nth-child(8)').remove()
		$("td [title='h1']").remove();
		$("td [title='h2']").remove();
		$("td [title='h3']").remove();
		$("td [title='h4']").remove();
		$("td [title='h5']").remove();
		$("td [title='h6']").remove();
		$("td [title='h7']").remove();
		$("td [title='h8']").remove();
		$("td [title='idperiodo']").remove();
		var suma_cargos       =  0;// Suma de Cargos para Calcular Subtotales
		var suma_abonos       =  0;// Suma de Abonos para Calcular Subtotales
		var suma_si           =  0;	// Suma de saldos iniciales para Calcular los Subtotales
		var final_cargos      =  0;// para la linea de total
		var final_abonos      =  0;// para la linea de total
		var suma_deudora      =  0;// para la linea de total
		var suma_acreedora    =  0;// para la linea de total
		var suma_si_deudora   =  0;// Suma de saldos iniciales deudores
		var suma_si_acreedora =  0;// Suma de saldos iniciales acreedores
		var codes_array       = [];//Array de codigos automaticos (necesario para realizar el AJAX de obtencion de naturalezas)
		var codes_string      = "";//Cadena que sera enviada al server para obtener las naturalezas
		var limit_year        = new Date($("#__LIMIT_YEAR").text().trim(),0,1);
		$("#__LIMIT_YEAR").remove();
		$('#tabla_reporte tr').last().prev().addClass('final');
		$("#tabla_reporte tr").each(function(index) {
			if ($('td', this).length === 10 && $('td:nth-child(1)', this).text().trim() == "4")
			{
				date_split = $('td:nth-child(6)', this).text().trim().split("-");
				for (var i = date_split.length - 1; i >= 0; i--)
					date_split[i] = parseInt(date_split[i]);
				date_td = new Date( date_split[0], date_split[1] - 1, date_split[2] );
				if( date_td < limit_year ){
					if ($(this).next().children('td').length === 10){
						$(this).remove();
					}else{
						$(this).prev().remove();// .css('background-color','red');// .remove();
						$(this).next().not('.final').remove();//.css('background-color','red');
						$(this).next().not('.final').remove();// .css('background-color','red');
						$(this).next().not('.final').remove();// .css('background-color','red');
						$(this).remove();// .css('background-color','red');
					}
				}
			}
		});
		$('#tabla_reporte tr').each(function(index) {
			if(
				$(this).next().html()  == false // Si tiene un espacio entre cuentas de mayor
				&& ($(this).next().next().children('th:contains("Cuenta_de_Mayor:")').length === 1 || $(this).next().next().hasClass('final')  )// Si su sigiente elemento visible es cuenta de mayor
				&& $('th:contains("Cuenta_de_Mayor:")', this).length === 1 // Si esta es cuenta de mayor
			){
				$(this).next().remove();// .children('*').css('background-color','red');
				$(this).remove();
			}
		});
		$('#tabla_reporte tr').each(function(index) {
			if(
				$(this).next().html() == false // Si contiene una linea de espacio
				&& $("th:contains('Clasificacion:')", this).length === 1 // y si es una linea de Clasificacion
				&& $(this).next().next().hasClass('final')
			){
				$(this).next().remove();
				$(this).remove();
			}
		});

		$("#tabla_reporte tr").each(function(index) {
			if($('td', this).length === 10)
				$('td:nth-child(1), td:nth-child(2), td:nth-child(3)', this).remove();
		});
		$('#tabla_reporte tr').each(function(index){

			if ( index === 0 )
			{// Eliminacion de cabecera
				$('td:nth-child(1)', this).remove();//Code
				$('td:nth-child(1)', this).remove();//Naturaleza
				$('td:nth-child(5)', this).remove();//Tipo
				$(this).append('<td>Total Contable</td>');// Total Contable
			}
			else
			{// Control de contenidos

				var code   = null;
				var nature = null;

				if( $('th', this).length == 1 )
				{
					if( $('th:nth-child(1):contains("Clasificacion:")', this).length == 1 )
					{
						$('th:nth-child(1):contains("Clasificacion:")', this).attr('colspan','5');
						$(this).addClass('clasif');
					}

					if( $("th:nth-child(1):contains('Cuenta_de_Mayor:')", this).length == 1 )
					{
						$("th:nth-child(1):contains('Cuenta_de_Mayor:')", this).attr('colspan','5');
						var n_txt = $('th:nth-child(1)', this).text();
						n_txt = n_txt.replace("Cuenta_de_Mayor:","");
						
						$('th:nth-child(1)', this).html("Cuenta de Mayor:<b>"+n_txt + "</b>");
						$(this).addClass('mayor');
					}

					if( $("th:nth-child(1):contains('Cuenta:')", this).length == 1 )
					{
						$("th:nth-child(1):contains('Cuenta:')", this).attr('colspan','5');
						$(this).addClass('kuenta');// remembering Lauris :3
						// Inicia transmision de padre de mayor
						if( $(this).prev().prev().hasClass('mayor') )
						{
							codeFull = $(this).next().children('td:nth-child(1)').text().trim();
							$(this).prev().prev().attr('data-long-code',codeFull).css('color','blue');
						}
						// Termina transmision de padre de mayor
					}

					if($('th:nth-child(1)', this).hasClass('subtotal'))
					{
						$('th:nth-child(1)', this).attr('colspan','2');
						$(this).addClass('subtotal');

					}

				}

				if($(this).hasClass('subtotal') && $(this).hasClass('kuenta') )
				{
					$(this).removeClass('kuenta');
				}

				if($(this).hasClass('kuenta') && !$(this).next().hasClass('s_i'))
				{
					code = codeFull = $(this).next().children('td:nth-child(1)').text().trim();					
					code = code[0];
					nature = $(this).next().children('td:nth-child(2)').text().trim().toUpperCase();
					$(this).after("<tr class='s_i' data-nature='" + nature + "'data-code='" + code + "' data-long-code='" + codeFull + "' ><td colspan='4'><b>Saldos Iniciales</b></td><td>0</td></tr>");
					// Inicia transmision de padre de mayor
						if( $(this).prev().prev().hasClass('mayor') )
						{
							$(this).prev().prev().attr('data-long-code',codeFull).css('color','blue');
						}
					// Termina transmision de padre de mayor
				}

				$('.final').removeClass('subtotal');
				if( $("td:nth-child(7):contains('Movimientos Corrientes')", this).length == 1 )
				{
					$(this).addClass('m_c');
					if( codes_array.indexOf( $('td:nth-child(1)', this).text().trim() ) === -1 ) codes_array.push( $('td:nth-child(1)', this).text().trim() );
				}
				if($("td:nth-child(7):contains('SALDOS INICIALES')", this).length == 1 )
				{
					$(this).addClass('s_i');
					if( codes_array.indexOf( $('td:nth-child(1)', this).text().trim() ) === -1 ) codes_array.push( $('td:nth-child(1)', this).text().trim() );
				}
				

				if( ($(this).prev().children('td:nth-child(1)', this).text().trim() == $('td:nth-child(1)', this).text().trim() ) &&  $(this).hasClass('s_i') && $(this).prev().hasClass('s_i'))
				{
					//5:cargos
					//6:abonos
					var prev_cargos = parseFloat( $(this).prev().children('td:nth-child(5)').text().trim() );
					var prev_abonos = parseFloat( $(this).prev().children('td:nth-child(6)').text().trim() );
					var cargos = parseFloat($('td:nth-child(5)', this).text().trim());
					var abonos = parseFloat($('td:nth-child(6)', this).text().trim());
					cargos += prev_cargos;
					abonos += prev_abonos;
					$('td:nth-child(5)', this).text(cargos);
					$('td:nth-child(6)', this).text(abonos);
				}

				if($(this).hasClass('s_i'))
				{
					code = $('td:nth-child(1)', this).text().trim();
					$(this).attr('data-long-code',code);
					code = code[0];
					nature = $('td:nth-child(2)', this).text().trim().toUpperCase();
					var cargo = parseFloat( $('td:nth-child(5)', this).text().trim() );
					var abono = parseFloat( $('td:nth-child(6)', this).text().trim() );
					$(this).attr('data-code', code);
					$(this).attr('data-nature', nature);
					var total = getTotal(code, nature, cargo, abono);
					if($(this).prev().hasClass('s_i'))
					{
						total += parseFloat( $(this).prev().children('td:nth-child(2)').text() );
					}
					//alert( "TOTAL: " + total + "Total Anterior" + $(this).prev().children('td:nth-child(2)').text() );
					$('td:nth-child(7)', this).text( total ).prevAll().remove();
					$('td:nth-child(1)', this).before("<td colspan='4'><b>Saldos Iniciales</b></td>");

					suma_si = total;
					switch(nature)
					{
						case "DEUDORA":
							suma_si_deudora += total;
							break;
						case "ACREEDORA":
							suma_si_acreedora += total;
							break;
					}
					if($(this).prev().hasClass('s_i'))
					{
						$(this).prev().remove();
					}
				}

				if( $(this).hasClass('m_c') )
				{	
					code = $('td:nth-child(1)', this).text().trim();
					$(this).attr('data-long-code', code);
					code = code[0];
					nature = $('td:nth-child(2)', this).text().trim().toUpperCase();
					$(this).attr('data-code', code);
					$(this).attr('data-nature', nature);
					$('td:nth-child(1),td:nth-child(2)', this).remove();
					$('td:nth-child(5)', this).text('-').css('text-align','center');
					suma_cargos += parseFloat( $('td:nth-child(3)', this).text() );
					suma_abonos += parseFloat( $('td:nth-child(4)', this).text() );
				}

				if($(this).hasClass('subtotal'))
				{
					var final_total = 0;
					if($(this).prev().children('td').length === 0)
					{
						$(this).prev().remove();
					}

					code   = $(this).prev().attr('data-code');
					nature = $(this).prev().attr('data-nature');
					
					$(this).attr('data-nature', nature);
					$(this).attr('data-code', code);

					final_cargos += suma_cargos;
					final_abonos += suma_abonos;
					final_total = getTotal(code, nature, suma_cargos, suma_abonos);	
					final_total += suma_si;
					//console.log("Code: " + code + " Nature: " + nature + " Suma Cargos: " + suma_cargos + " Suma Abonos: " + suma_abonos + " Saldos Iniciales:" + suma_si + " Total: " + final_total);
					//console.log( "Nature: " + nature + " Saldos Iniciales: " + suma_si );
					switch(nature)
					{
						case "DEUDORA":
							suma_deudora += final_total;
							break;
						case "ACREEDORA":
							suma_acreedora += final_total;
							break;
					}

					$('td:nth-child(2)', this).text(suma_cargos);
					$('td:nth-child(3)', this).text(suma_abonos).after('<td style="background-color:#efefef;">' + final_total + "</td>");

					suma_cargos = 0;
					suma_abonos = 0;
					suma_si = 0;

				}

			}
		});
		codes_string = codes_array.join(",");
		
		//INICIA SECCION AJAX DE OBTENCION DE NATURALEZA DEL PADRE
		var jsonResult;
		$.post("../../modulos/cont_repolog/request.php",{ data : codes_string }, function(data){
			//console.log(data);
			jsonResult = $.parseJSON(data);
			//console.log(jsonResult);
			for( var i = 0; i < jsonResult.length ; i++ )
			{
				//console.log('Codigo: ' + jsonResult[i].account_code + ' , Naturaleza del padre: ' + jsonResult[i].father_nature );
				$('tr[data-long-code="' + jsonResult[i].account_code + '"]').attr('data-father-nature', jsonResult[i].father_nature );
			}
			
		}).done(function(){
			// Inicia seccion de alineacion de saldos iniciales y finales para mainX
				if($('#mainX').length === 1)
				{
					$('#mainX tbody tr.mayor').each(function(){
						switch($(this).data('father-nature'))
						{
							case 1:
								$(this).next().children('td:nth-child(2)').css('text-align','right');//Saldo Inicial
								$(this).next().next().children('td:nth-child(4)').css('text-align','right');//Saldo Final
								break;
							case 2:
								$(this).next().children('td:nth-child(2)').css('text-align','left');//Saldo Inicial
								$(this).next().next().children('td:nth-child(4)').css('text-align','left');//Saldo Final
								break;
						}
					});
				}
			// Inicia seccion de alineacion de saldos iniciales y finales para mainX
		});
		//INICIA SECCION AJAX DE OBTENCION DE NATURALEZA DEL PADRE
		// Inicia Limpieza de saldos Iniciales en 0
		$('#tabla_reporte tr').each(function(){
			//    es saldo inicial      y   su anterior es cuenta            y  la siguiente es de subtotal         y  su saldo inicial debe ser 0
			if($(this).hasClass('s_i') && $(this).prev().hasClass('kuenta') && $(this).next().hasClass('subtotal') && $('td:nth-child(2)',this).text() == "0")
			{
				//alert("Pequeña coincidencia!");
				
				$(this).prev().remove();
				$(this).next().remove();
				$(this).next().remove();
				$(this).remove();
			}
			
			if($(this).hasClass('mayor') && $(this).prev().prev().hasClass('mayor'))
			{
				$(this).prev().prev().remove();
			}
			
			if($(this).hasClass('clasif') && $(this).prev().prev().hasClass('mayor'))
			{
				$(this).prev().prev().remove();
			}
			if($(this).hasClass('clasif') && $(this).prev().prev().hasClass('clasif'))
			{
				$(this).prev().prev().remove();
			}
			
		});
		// Termina Limpieza de saldos Iniciales en 0
		// Inicia seccion de Calculo de total final
		if(final_cargos < 0)
		{
			$('.final td:nth-child(2)').text( Currency("$",final_cargos) ).css('color','red');
		}
		else
		{
			$('.final td:nth-child(2)').text( Currency("$",final_cargos) );
		}
		
		if(final_abonos < 0)
		{
			$('.final td:nth-child(3)').text( Currency("$",final_abonos) ).css('color','red');
		}
		else
		{
			$('.final td:nth-child(3)').text( Currency("$",final_abonos) );
		}

		final_total = final_cargos.toFixed(2) - final_abonos.toFixed(2);
		if( parseFloat( final_total, 10 ) !== 0 )
		{
			$('#tabla_reporte').before('<div style="color:red;font-size:25px">Sumas Incorrectas</div>');
			$('.final').append("<td style='background-color:#efefef;color:red'>" + Currency("$", final_total) + "</td>");
		}
		else
		{
			$('.final').append("<td style='background-color:#efefef;'>" + Currency("$", final_total) + "</td>");
		}
		// Termina seccion de Calculo de total final
		
		// Filtro para reporte a nivel de cuentas de mayor
			if( $("#exec").text() == "2" )
			{
				table  = "<table id='mainX' class='reporte' border='0' style='width:100%'>";
				table += "	<thead>";
				table += "		<tr class='trencabezado'>";
				table += "			<td>";
				table += "				Totales";
				table += "			</td>";
				table += "			<td>";
				table += "				Cargos";
				table += "			</td>";
				table += "			<td>";
				table += "				Abonos";
				table += "			</td>";
				table += "			<td>";
				table += "				Saldos";
				table += "			</td>";
				table += "		<tr>";
				table += "	</thead>";
				table += "	<tbody>";
				table += "	</tbody>";
				table += "	</table>";
		
				$("#tabla_reporte").before(table);
				table_vodi = "";
				suma_si = 0;// Suma de saldos Iniciales
				suma_cargos = 0;// Suma de Cargos
				suma_abonos = 0;// Suma de Abonos
				var main_account = "";
				var suma_st_cargos = 0;// Subtotal de cargos a nivel de mayor
				var suma_st_abonos = 0;// Subtotal de cargos a nivel de mayor
				var suma_st_total  = 0;// Subtotal de cargos a nivel de mayor
				var longCode = '';     // Codigo completo para obtener la naturaleza del padre
				$('#tabla_reporte tr').each(function(){
					if($(this).hasClass('clasif'))
					{
						table_vodi += "<tr>" + $(this).html() + "</tr>";
					}
					if($(this).hasClass('mayor'))
					{
							
							table_vodi += "<tr class='mayor' data-long-code='" + longCode + "'>" + main_account + "</tr>";
							table_vodi += "<tr class='s_i' title='Saldos Iniciales'>";
							table_vodi += "	<th colspan='3' style='border:solid 1px;background-color:white;text-align:left;font-size:12px;' >Saldo Inicial: </th>";
							table_vodi += "	<td style='background-color:white;'>" + Currency ("$",suma_si) + "</td>";
							table_vodi += "</tr>";
							table_vodi += "<tr>";
							table_vodi += "	<td style='background-color:#efefef;'>Total</td>";
							table_vodi += "	<td style='background-color:#efefef;' title='Cargos'>" + Currency("$", suma_st_cargos) + "</td>";
							table_vodi += "	<td style='background-color:#efefef;' title='Abonos'>" + Currency("$", suma_st_abonos) + "</td>";
							table_vodi += "	<td style='background-color:#efefef;' title='Total'>" + Currency("$", suma_st_total) + "</td>";
							table_vodi += "</tr><tr><td style='border:none;height:20px;'></td></tr>";
							suma_si = 0;
							suma_st_cargos = 0;
							suma_st_abonos = 0;
							suma_st_total  = 0;
						main_account = $(this).html();
					}
					if($(this).hasClass('s_i'))
					{	
						suma_si += parseFloat( $('td:nth-child(2)', this).text().trim() );
						//alert("Suma: " + suma_si + ". Saldo: " + $('td:nth-child(2)', this).text() );
						longCode = $(this).data('long-code');
					}
					if($(this).hasClass('subtotal'))
					{
						suma_st_cargos += parseFloat( $('td:nth-child(2)', this).text() );
						suma_st_abonos += parseFloat( $('td:nth-child(3)', this).text() );
						suma_st_total  += parseFloat( $('td:nth-child(4)', this).text() );
					}
					if($(this).hasClass('final'))
					{
						table_vodi += "<tr  class='mayor' data-long-code='" + longCode + "'>" + main_account + "</tr>";
						table_vodi += "<tr class='s_i'  title='Saldos Iniciales'>";
						table_vodi += "	<th colspan='3' style='border:solid 1px;background-color:white;text-align:left;font-size:12px;' >Saldo Inicial: </th>";
						table_vodi += "	<td style='background-color:white;'>" + Currency("$", suma_si) + "</td>";
						table_vodi += "</tr>";
						table_vodi += "<tr class='subtotal'>";
						table_vodi += "	<td style='background-color:#efefef;'>Total</td>";
						table_vodi += "	<td style='background-color:#efefef;' title='Cargos' >" + Currency("$", suma_st_cargos) + "</td>";
						table_vodi += "	<td style='background-color:#efefef;'title='Abonos' >" + Currency("$", suma_st_abonos) + "</td>";
						table_vodi += "	<td style='background-color:#efefef;' title='Total'>" + Currency("$", suma_st_total) + "</td>";
						table_vodi += "</tr><tr><td style='border:none;height:20px;'></td></tr>";
						var n_txt = $(this).html();
						n_txt = n_txt.replace("colspan='2'","");
						n_txt = n_txt.replace('colspan="2"',"");
						table_vodi += "<tr style='height:20px;'></tr><tr style='color:black'>" + n_txt + "</tr>";
					}
				});
				$("#mainX tbody").append(table_vodi);
				$("#mainX tbody tr:nth-child(2)").remove();
				$("#mainX tbody tr:nth-child(2)").remove();
				$("#mainX tbody tr:nth-child(2)").remove();
				$("#mainX tbody tr:nth-child(2)").remove();
				$('#tabla_reporte').remove();
			}
			$('#exec').remove();
		// Filtro para reporte a nivel de cuentas de mayor
			if( $("#mainX").length !== 0 )
			{
				var wid = $("#mainX tbody tr th:first-child").css('width');
				wid = wid.replace('px','');
				wid = parseFloat( wid );
				if(wid < 500)
				{
					$("#mainX tbody tr th:first-child").css('width','264px');
				}
				if($('#mainX tbody tr th:first-child').width() < 400)
				{
					$("#mainX tbody tr th:first-child").width(300);
				}
			}
			
		// Inicia Seccion de Conversion a tipo Dinero
			$('#tabla_reporte tr').each(function(index){
				if($(this).hasClass('s_i'))
				{
					var num = parseFloat($('td:nth-child(2)', this).text());
					if( num < 0 )
					{
						$("td:nth-child(2)", this).css('color','red');	
					}
					$('td:nth-child(2)', this).text( Currency("$", num) );
				}
				
				if( $(this).hasClass('m_c') )
				{
					$('td',this).each(function(i){
						if( i > 1 )
						{
							var num = parseFloat( $(this).text() );
							if( num < 0 )
							{
								$(this).css('color','red');	
							}
							if(!isNaN(num))
							{
								$(this).text( Currency("$", num));
							}
							
						}
					});
				}
				if( $(this).hasClass('subtotal') )
				{
					$('td',this).each(function(i){
						var num = parseFloat( $(this).text() );
						if( num < 0 )
						{
							$(this).css('color','red');	
						}
						if(!isNaN(num))
						{
							$(this).text( Currency("$", num));
						}
					});
				}
		});	
// Inicia Seccion de Conversion a tipo Dinero
		
		//DOMPDF CRACK
		$('#tabla_reporte').css({
			"margin-left" : "auto", "margin-right": "auto"
		});
		//DOMPDF CRACK
		// INICIA SECCION DE ALINEACION POR NATURALEZA EN SALDOS INICIALES
			$('#tabla_reporte .m_c').each(function(){
				var nature = $(this).attr('data-nature');
				switch(nature)
				{
					case "DEUDORA":
						$(this).prev().children('td:nth-child(2)').css('text-align','left');
						$(this).next().children('td:nth-child(4)').css('text-align','left');
						break;
					case "ACREEDORA":
						$(this).prev().children('td:nth-child(2)').css('text-align','right');
						$(this).next().children('td:nth-child(4)').css('text-align','right');
						break;
				}
				
			});
		// TERMINA SECCION DE ALINEACION POR NATURALEZA EN SALDOS INICIALES
		//$('.final').append('<td><p style="margin:0;padding:0;text-align:left;">' + suma_deudora + '</p><p style="margin:0;padding:0;text-align:right;">' + suma_acreedora + '</p></td>');
		
		$("td:contains('$')").css("text-align","right")
		$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='../../modulos/cont/images/images.jpg' width='35px'></a></td>")
	});	
	function getTotal(code,nature,cargo,abono)
	{
		var total = 0;
		switch(code)
		{
			case '1': // ACTIVO
				switch(nature)
				{
					case "DEUDORA":
						total = cargo - abono;
						break;
					case "ACREEDORA":
						total = abono - cargo;
						break;
					
				}
				break;
			case '2': // PASIVO
				switch(nature)
				{
					case "DEUDORA":
						total = cargo - abono;
						break;
					case "ACREEDORA":
						total = abono - cargo;
						break;
				}
				break;
			case '3': // CAPITAL
				switch(nature)
				{
					case "DEUDORA":
						total = cargo - abono;
						break;
					case "ACREEDORA":
						total = abono - cargo;
						break;
				}
				break;
			case '4': // RESULTADOS
				switch(nature)
				{
					case "DEUDORA":
						total = cargo - abono;
						break;
					case "ACREEDORA":
						total = abono - cargo;
						break;
				}
				break;
		}	
		return total;
	}

	function Currency(sSymbol, vValue) 
	{
		aDigits = vValue.toFixed(2).split(".");
		aDigits[0] = aDigits[0].split("").reverse().join("").replace(/(\d{3})(?=\d)/g, "$1,").split("").reverse().join("");
		return sSymbol + aDigits.join(".");
	}

	function generaexcel()
			{
				$().redirect('../../modulos/cont/views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte table:contains('Contabilidad - Libro de Mayor')").html(), 'name': 'Libro de Mayor de ' + $('#empresa tr td').html()});
			}
</script>