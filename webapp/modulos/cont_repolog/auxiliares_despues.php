<!--// data-type : Cargo o Abono
// data-code : Codigo de Cuenta. Para saber si es Activo, Pasivo, Capital o Resultados
// data-nature : Acreedor o Deudor -->
<script src="../../modulos/cont/js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script>
	$(document).ready(function(){
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
						$(this).remove();// .css('background-color','green');
					}else{
						$(this).prev().remove();
						$(this).next().not('.final').remove();
						$(this).next().not('.final').remove();
						$(this).next().not('.final').remove();
						$(this).remove();
					}
				}
			}
		});
	// });
		$("#tabla_reporte tr").each(function(index) {
			if($('td', this).length === 10)
				$('td:nth-child(1), td:nth-child(2), td:nth-child(3)', this).remove();
		});

		$('#tabla_reporte tr').each(function(index){
			var code   = null;
			var nature = null;

			if ( index === 0 )
			{// Eliminacion de cabecera
				$('td:nth-child(1)', this).remove();//Code
				$('td:nth-child(1)', this).remove();//Naturaleza
				$('td:nth-child(5)', this).remove();//Tipo
				$(this).append('<td>Total Contable</td>');// Total Contable
			}
			else
			{// Control de contenidos

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
						$(this).addClass('mayor');
					}

					if( $("th:nth-child(1):contains('Cuenta:')", this).length == 1 )
					{
						$("th:nth-child(1):contains('Cuenta:')", this).attr('colspan','5');
						$(this).addClass('kuenta');// remembering Lauris :3
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
					$(this).after("<tr class='s_i gen' data-nature='" + nature + "' data-code='" + code + "' data-long-code='" + codeFull + "'><td colspan='4'><b>Saldos Iniciales</b></td><td>0</td></tr>");
				}

				$('.final').removeClass('subtotal');
				if( $("td:nth-child(7):contains('Movimientos Corrientes')", this).length == 1 )
				{
					if( codes_array.indexOf( $('td:nth-child(1)', this).text().trim() ) === -1 ) codes_array.push( $('td:nth-child(1)', this).text().trim() );
					$(this).addClass('m_c');
				}
				if($("td:nth-child(7):contains('SALDOS INICIALES')", this).length == 1 )
				{
					if( codes_array.indexOf( $('td:nth-child(1)', this).text().trim() ) === -1 ) codes_array.push( $('td:nth-child(1)', this).text().trim() );
					$(this).addClass('s_i');
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
					$('td:nth-child(7)', this).text( total ).prevAll().remove();
					$('td:nth-child(1)', this).before("<td colspan='4'><b>Saldos Iniciales</b></td>");

					suma_si = total;
					
					if($(this).prev().hasClass('s_i'))
					{
						$(this).prev().remove();
					}
				}

				if( $(this).hasClass('m_c') )
				{
					code = $('td:nth-child(1)', this).text().trim();
					$(this).attr('data-long-code',code);
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
					//console.log("Code: " + code + " Nature: " + nature + " Suma Cargos: " + suma_cargos + " Suma Abonos: " + suma_abonos + " Total mas Saldos Inciales: " + final_total);
					//console.log( "Nature: " + nature + " Saldos Iniciales: " + suma_si );
					
					switch(nature)
					{
						case "DEUDORA":
							suma_si_deudora += suma_si;
							suma_deudora += final_total;
							break;
						case "ACREEDORA":
							suma_si_acreedora += suma_si;
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
		
		// INICIA SECCION AJAX DE OBTENCION DE NATURALEZA DEL PADRE
		var jsonResult;
		$.post("../../modulos/cont_repolog/request.php",{ data : codes_string }, function(data){
			//console.log(data);
			//alert("Success Post");
			jsonResult = $.parseJSON(data);
			//console.log(jsonResult);
			for( var i = 0; i < jsonResult.length ; i++ )
			{
				//console.log('Codigo: ' + jsonResult[i].account_code + ' , Naturaleza del padre: ' + jsonResult[i].father_nature );
				$('tr[data-long-code="' + jsonResult[i].account_code + '"]').each(function(){
					$(this).attr('data-father-nature', jsonResult[i].father_nature );
					if( $(this).prev().hasClass('gen') )
					{
						$(this).prev().attr('data-father-nature', jsonResult[i].father_nature);
						$(this).prev().attr('data-long-code', $(this).data('long-code') );
					}
				});
			}
		})
		.done(function(){
			if($('#balanza_2').length === 1)
			{
				$("#balanza_2 tbody tr.movimiento").each(function(){
					switch($(this).data('father-nature'))
					{
						case 1:
							$("td:nth-child(2)",this).css('text-align','right');
							$("td:nth-child(5)",this).css('text-align','right');
							break;
						case 2:
							$("td:nth-child(2)",this).css('text-align','left');
							$("td:nth-child(5)",this).css('text-align','left');
							break;
					}
				});
			}
		});
		// TERMINA SECCION AJAX DE OBTENCION DE NATURALEZA DEL PADRE
		
		// Inicia Limpieza de saldos Iniciales en 0
		$('#tabla_reporte tr').each(function(){
			//    es saldo inicial      y   su anterior es cuenta            y  la siguiente es de subtotal
			if($(this).hasClass('s_i') && $(this).prev().hasClass('kuenta') && $(this).next().hasClass('subtotal') && $('td:nth-child(2)',this).text() == "0")
			{
				$(this).prev().remove();
				$(this).next().remove();
				$(this).next().remove();
				$(this).remove();
			}
			
			if($(this).hasClass('clasif') && $(this).prev().prev().hasClass('mayor'))
			{
				$(this).prev().prev().remove();
			}
			
			if($(this).hasClass('clasif') && $(this).prev().prev().hasClass('clasif'))
			{
				$(this).prev().prev().remove();
			}
			
			if($(this).hasClass('mayor') && $(this).prev().prev().hasClass('mayor'))
			{
				$(this).prev().prev().remove();
			}
		});
		// Termina Limpieza de saldos Iniciales en 0
		

		$('.final td:nth-child(2)').text(final_cargos);
		$('.final td:nth-child(3)').text(final_abonos);
		$('.final').append('<td><p style="margin:0;padding:0;text-align:left;">' + suma_deudora + '</p><p style="margin:0;padding:0;text-align:right;">' + suma_acreedora + '</p></td>');
		$('.final th:nth-child(1)').after('<td><p style="margin:0;padding:0;text-align:left;">' + suma_si_deudora + '</p><p style="margin:0;padding:0;text-align:right;">' + suma_si_acreedora + '</p></td>');
		var table  = "<table id='balanza' class='reporte'>";
				table += "	<thead>";
				table += "		<tr class='trencabezado'>";
				table += "			<td>";
				table += "				Cuenta";
				table += "			</td>";
				table += "			<td>";
				table += "				Saldos Iniciales";
				table += "			</td>";
				table += "			<td>";
				table += "				Cargos";
				table += "			</td>";
				table += "			<td>";
				table += "				Abonos";
				table += "			</td>";
				table += "			<td>";
				table += "				Saldo Final";
				table += "			</td>";
				table += "		<tr>";
				table += "	</thead>";
				table += "	<tbody>";
				table += "	</tbody>";
				table += "	</table>";			
		
		$("#tabla_reporte").before(table);
		var table_vodi = "";// Again Lauris :3


		$("#tabla_reporte tr").each(function(index){
			if(index > 0)
			{
				if($(this).hasClass('clasif') || $(this).hasClass('mayor') )
				{
					var txt = $('th:nth-child(1)', this).text().trim();
					txt        = txt.replace("Clasificacion:","");
					txt        = txt.replace("Cuenta_de_Mayor:","Cuenta de Mayor: ");
					txt        = txt.replace("Cuenta:","Cuenta: ");
					table_vodi += "<tr class='" + $(this).attr('class') + "'><th colspan='5' style='background-color:silver;border:1px solid black;color:black;' >" + txt + "</th></tr>";
				}

				if($(this).hasClass('kuenta') )
				{
					var name     = $('th:nth-child(1)', this).text().replace("Cuenta:","").trim();
					var longCode = '';
					var nature   = '';
					if(!$(this).next().data('long-code'))
					{
						longCode = $(this).next().next().data('long-code');
						nature   = $(this).next().next().data('nature');
					}
					else
					{
						longCode = $(this).next().data('long-code');
						nature   = $(this).next().data('nature');
					}
					table_vodi += "<tr class='trcontenido movimiento' data-nature='" + nature + "' data-long-code='" + longCode + "' ><td style='padding:3px; border: 1px solid gray'>" + name + "</td>";
				}

				if($(this).hasClass('s_i'))
				{
					table_vodi += "<td style='padding:3px; border: 1px solid gray' data-nature='" + $(this).attr('data-nature') + "' data-father-nature='" + $(this).attr('data-father-nature') + "'>" + $('td:nth-child(2)', this).text().trim() + "</td>";
				}

				if($(this).hasClass('subtotal'))
				{
					table_vodi += "<td style='padding:3px; border: 1px solid gray'>" + $('td:nth-child(2)', this).text().trim() + "</td>";
					table_vodi += "<td style='padding:3px; border: 1px solid gray'>" + $('td:nth-child(3)', this).text().trim() + "</td>";
					table_vodi += "<td style='padding:3px; border: 1px solid gray'>" + $('td:nth-child(4)', this).text().trim() + "</td></tr>";
				}
			}
		});
		$("#balanza tbody").append( table_vodi );
		$('#balanza tbody tr.mayor').each(function(index) {
			if(!$(this).next().hasClass('movimiento'))
				$(this).remove();
		});
		// INICIA SECCION DE ALINEACION POR NATURALEZA A NIVEL DE AFECTABLES
			$('#balanza tbody tr.movimiento').each(function(){
				switch($(this).data('nature'))
				{
					case "DEUDORA":
						$('td:nth-child(2)', this).css('text-align','left');
						$('td:nth-child(5)', this).css('text-align','left');
						break;
					case "ACREEDORA":
						$('td:nth-child(2)', this).css('text-align','right');
						$('td:nth-child(5)', this).css('text-align','right');
						break;
				}
				if($('td', this).length === 6){
					$(this).css('background-color', 'green');
					console.log( parseFloat($('td:nth-child(5)', this).text(), 10).toFixed(2) + " y " + parseFloat($('td:nth-child(6)', this).text(), 10).toFixed(2) );
				}
			});
		// TERMINA SECCION DE ALINEACION POR NATURALEZA A NIVEL DE AFECTABLES
			table  = "<table id='balanza_2' class='reporte'>";
			table += "	<thead>";
			table += "		<tr class='trencabezado'>";
			table += "			<td>";
			table += "				Cuenta";
			table += "			</td>";
			table += "			<td>";
			table += "				Saldos Iniciales";
			table += "			</td>";
			table += "			<td>";
			table += "				Cargos";
			table += "			</td>";
			table += "			<td>";
			table += "				Abonos";
			table += "			</td>";
			table += "			<td>";
			table += "				Saldo Final";
			table += "			</td>";
			table += "		<tr>";
			table += "	</thead>";
			table += "	<tbody>";
			table += "	</tbody>";
			table += "	</table>";

		$("#balanza tbody").css('font-size','12px');
		$("#balanza").before(table);

		table_vodi      = "";
		suma_si         = 0;
		suma_cargos     = 0;
		suma_abonos     = 0;
		var suma_sf     = 0;
		var suma_mayor  = 0;
		$("#balanza tbody tr").each(function(){
			
			var name = "";
			if($(this).hasClass('clasif'))
			{
				table_vodi += "<tr class='clasif'>" + $(this).html().trim() + "</tr>";
			}

			if($(this).hasClass('mayor'))
			{
				name = $('th:nth-child(1)', this).text().replace("Cuenta:","").trim();
				name = name.replace("Cuenta de Mayor: ","");
				table_vodi += "<tr class='trcontenido movimiento' ><td style='padding:3px; border: 1px solid gray'>" + name + "</td>";
			}

			if($(this).hasClass('movimiento'))
			{
				suma_si     += parseFloat( $('td:nth-child(2)', this).text() );
				suma_cargos += parseFloat( $('td:nth-child(3)', this).text() );
				suma_abonos += parseFloat( $('td:nth-child(4)', this).text() );
				suma_sf     += parseFloat( $('td:nth-child(5)', this).text() );
				
				if(!$(this).next().hasClass('movimiento'))
				{
					table_vodi  += "<td style='padding:3px; border: 1px solid gray' data-long-code='" + $(this).data('long-code') + "'>" + suma_si + "</td>";
					table_vodi  += "<td style='padding:3px; border: 1px solid gray'>" + suma_cargos + "</td>";
					table_vodi  += "<td style='padding:3px; border: 1px solid gray'>" + suma_abonos + "</td>";
					table_vodi  += "<td style='padding:3px; border: 1px solid gray'>" + suma_sf     + "</td></tr>";
					suma_si     = 0;
					suma_cargos = 0;
					suma_abonos = 0;
					suma_sf     = 0;
					suma_mayor  = 0;
				}
			}
	});
		$("#balanza_2 tbody").append( table_vodi );
		$('#balanza_2 tbody tr.movimiento').each(function(){
			$(this).attr('data-long-code', $('td:nth-child(2)',this).data('long-code') );
		});
		$("#balanza_2").css('font-size','12px');
		var lastLine  = "<tr class='subtotal'>";
		lastLine += "	<td style='background-color:silver;border:1px solid black;color:black;'>";
		lastLine += "TOTAL" ;
		lastLine += "	</td>";
		lastLine += "	<td style='background-color:silver;border:1px solid black;color:black;'>";
		lastLine += $('.final td:nth-child(2)').html();
		lastLine += "	</td>";
		lastLine += "	<td style='background-color:silver;border:1px solid black;color:black;'>";
		lastLine += $('.final td:nth-child(3)').text();
		lastLine += "	</td>";
		lastLine += "	<td style='background-color:silver;border:1px solid black;color:black;'>";
		lastLine += $('.final td:nth-child(4)').text();
		lastLine += "	</td>";
		lastLine += "	<td style='background-color:silver;border:1px solid black;color:black;'>";
		lastLine += $('.final td:nth-child(5)').html();
		lastLine += "	</td>";
		lastLine += "</tr>";
		
		$("#balanza tr").last().after( lastLine );
		$("#balanza_2 tr").last().after( lastLine );		
		$("#balanza tr").each(function(i){
			if(i > 0)
			{
				if(!$(this).hasClass('subtotal') )
				{
					$('td', this).each(function(index){
						if(index > 0)
						{
							var num = parseFloat( $(this).text() );
							if(num < 0)
							{
								$(this).text( Currency("$",num) ).css('color','red');
							}
							else
							{
								$(this).text( Currency("$",num) );
							}
						}
					});
				}
				else
				{
					$('td:nth-child(2) p', this).each(function(){
						var num = parseFloat($(this).text());
						if(num < 0)
						{
							$(this).text( Currency("$",num) ).css('color','red');
						}
						else
						{
							$(this).text( Currency("$",num) );
						}
					});
					$('td', this).each(function(index){
						if(index > 1 && index < 4)
						{
							var num = parseFloat( $(this).text() );
							if(num < 0)
							{
								$(this).text( Currency("$",num) ).css('color','red');
							}
							else
							{
								$(this).text( Currency("$",num) );
							}
						}
					});
					$('td:nth-child(5) p', this).each(function(){
						var num = parseFloat($(this).text());
						if(num < 0)
						{
							$(this).text( Currency("$",num) ).css('color','red');
						}
						else
						{
							$(this).text( Currency("$",num) );
						}
					});
				}
			}
		});
		$("#balanza_2 tr").each(function(i){
			if(i > 0)
			{
				if(!$(this).hasClass('subtotal') )
				{
					$('td', this).each(function(index){
						if(index > 0)
						{
							var num = parseFloat( $(this).text() );
							if(num < 0)
							{
								$(this).text( Currency("$",num) ).css('color','red');
							}
							else
							{
								$(this).text( Currency("$",num) );
							}
						}
					});
				}
				else
				{
					$('td:nth-child(2) p', this).each(function(){
						var num = parseFloat($(this).text());
						if(num < 0)
						{
							$(this).text( Currency("$",num) ).css('color','red');
						}
						else
						{
							$(this).text( Currency("$",num) );
						}
					});
					$('td', this).each(function(index){
						if(index > 1 && index < 4)
						{
							var num = parseFloat( $(this).text() );
							if(num < 0)
							{
								$(this).text( Currency("$",num) ).css('color','red');
							}
							else
							{
								$(this).text( Currency("$",num) );
							}
						}
					});
					$('td:nth-child(5) p', this).each(function(){
						var num = parseFloat($(this).text());
						if(num < 0)
						{
							$(this).text( Currency("$",num) ).css('color','red');
						}
						else
						{
							$(this).text( Currency("$",num) );
						}
					});
				}
			}
		});
		$("#balanza,#balanza_2,#tabla_reporte").attr('border','0').css('width', "100%");
		$("#balanza tbody tr th[colspan=5]").css('text-align','left');
		$("#balanza_2 tbody tr th[colspan=5]").css('text-align','left');
		var txt_exec = $("#exec").text();
		$("#balanza tbody tr.mayor").each(function(){ 
			text = $('th', this).text().replace("Cuenta de Mayor: ","").trim();
			s    = $("#balanza_2 td:contains('" + text + "')");
			if(s.length){
				saldo_inicial = s.parent('tr').children('td:nth-child(2)').text();
				cargos        = s.parent('tr').children('td:nth-child(3)').text();
				abonos        = s.parent('tr').children('td:nth-child(4)').text();
				saldo_final   = s.parent('tr').children('td:nth-child(5)').text();
				$("th", this).attr('colspan', '1');
				$(this).append(
					'<td style="border: 1px solid black; color: black; text-align: left; background-color: silver;"><b>' + saldo_inicial + 
					'</b></td><td style="border: 1px solid black; color: black; text-align: left; background-color: silver;"><b>' + cargos + 
					'</b></td><td style="border: 1px solid black; color: black; text-align: left; background-color: silver;"><b>' + abonos + 
					'</b></td><td style="border: 1px solid black; color: black; text-align: left; background-color: silver;"><b>' + saldo_final+ "</b></td>");
			}  
		});
		$("#exec, #tabla_reporte, #balanza_2").remove();
		$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='../../modulos/cont/images/images.jpg' width='35px'></a></td>")
	});
	
	function getTotal(code, nature, cargo, abono)
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
				$().redirect('../../modulos/cont/views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte table:contains('Auxiliares de Catálogo')").html(), 'name': 'Auxiliares de Catálogo de ' + $('#empresa tr td').html()});
			}
</script>