<script>
	$(document).ready(function(){
		$('#tabla_reporte tr').each(function(index){
			if(index === 0)
			{// Seccion de Encabezado
				// Incia seccion de eliminacion de encabezados inutiles
					$('td:nth-child(1)', this).remove();//Clasficiacion
					$('td:nth-child(2)', this).remove();//Naturaleza
					$('td:nth-child(2)', this).remove();//Fecha
					$('td:nth-child(2)', this).remove();//Poliza
					$('td:nth-child(5)', this).remove();//Flag
					$(this).append('<td>Saldo Actual</td>');
					$('td:nth-child(1)', this).text('Cuenta');
					$('td:nth-child(2)', this).text('Nombre').after("<td>Saldos Iniciales</td>");
				// Termina seccion de eliminacion de encabezados inutiles
			}
			else
			{
				// Inicia seccion de asignacion de clases
					
					if( $('td', this).length == 9 && !$(this).hasClass('trcontenido') )
					{
						$(this).addClass('trcontenido');
					}
					

						$('th[colspan=9]').attr('colspan','6').parent('tr').addClass('titulo');
					
					if($('th.subtotal',this).length === 1)
					{
						$(this).addClass('subtotal');
					}
					
					if($('td', this).length != 9 && $(this).hasClass('trcontenido'))
					{
						$(this).removeClass('trcontenido');
					}
				// Termina seccion de asignacion de clases
			}
			
			// Inicia Suma de registros Cargo/Abono
				if( ($(this).hasClass('trcontenido') && $(this).prev().hasClass('trcontenido')) && ($('td:nth-child(2)', this).text() ==  $(this).prev().children('td:nth-child(2)').text() )  && ($('td:nth-child(9)',this).text() == $(this).prev().children('td:nth-child(9)', this).text())  )
				{
					cargo      = parseFloat($('td:nth-child(7)',this).text());
					abono      = parseFloat($('td:nth-child(8)',this).text());
					prev_cargo = parseFloat($(this).prev().children('td:nth-child(7)').text());
					prev_abono = parseFloat($(this).prev().children('td:nth-child(8)').text());
					cargo += prev_cargo;
					abono += prev_abono;
					$(this).prev().remove();
					$('td:nth-child(7)', this).text(cargo);
					$('td:nth-child(8)', this).text(abono);
				}
			// Termina Suma de registros Cargo/Abono
			
			// Inicia creacion de saldos Actuales
				if($(this).hasClass('trcontenido'))
				{
					var code = $('td:nth-child(2)', this).text();
					code = code[0];
					var nature = $('td:nth-child(3)', this).text().toUpperCase();
					var cargo_c = parseFloat($('td:nth-child(7)', this).text());
					var abono_c = parseFloat($('td:nth-child(8)', this).text());
					$(this).append( "<td class='tdcontenido'>" + getTotal(code,nature,cargo_c,abono_c) + "</td>" );
				}
			// Termina creacion de saldos Actuales
			// Inicia seccion de Calculo de saldos actuales a tipos subtotal
				if( $(this).hasClass('subtotal') )
				{	
					var prev_code   = $(this).prev().prev().children('td:nth-child(2)').text();
						prev_code   = prev_code[0];
					var prev_nature = $(this).prev().prev().children('td:nth-child(3)').text().toUpperCase();
					var sub_cargo   = $('td:nth-child(2)', this).text();
						sub_cargo   = sub_cargo.replace(',','');
						sub_cargo   = parseFloat(sub_cargo);
					var sub_abono   = $('td:nth-child(3)', this).text();
						sub_abono   = sub_abono.replace(',','');
						sub_abono   = parseFloat(sub_abono);
						$('td:nth-child(2)', this).text(sub_cargo);
						$('td:nth-child(3)', this).text(sub_abono);
					$(this).append( "<td style='background-color:#efefef;' >" + getTotal(prev_code,prev_nature,sub_cargo,sub_abono) + "</td>" );
					$('th.subtotal', this).attr('colspan','2');
				}
			// Termina seccion de Calculo de saldos actuales a tipos subtotal y modificacion de colspan
			// Inicia formato de movimientos
				if( $(this).hasClass('trcontenido') )
				{
					$('td:nth-child(1)', this).css('display','none');//;.remove();// Clasificacion
					$('td:nth-child(3)', this).css('display','none');//.remove();// Naturaleza
					$('td:nth-child(4)', this).css('display','none');//.remove();// Fecha
					$('td:nth-child(5)', this).css('display','none');//.remove();// Poliza
					$('td:nth-child(9)', this).css('display','none');//.remove();// Flag	
					$(this).attr( 'data-flag', $('td:nth-child(9)', this).text().trim() );				
				}
			// Termina formato de movimientos
		});
		// Inicia conversion a tipo dinero para impresion
		$('#tabla_reporte tr.trcontenido').each(function(){
			var cargo = $('td:nth-child(7)', this).text().trim();
			var abono = $('td:nth-child(8)', this).text().trim();
			var total = $('td:nth-child(10)', this).text().trim();
			cargo = parseFloat(cargo);
			abono = parseFloat(abono);
			total = parseFloat(total);
			cargo = Currency("$", cargo);
			abono = Currency("$", abono);
			total = Currency("$", total);
			$('td:nth-child(7)', this).text(cargo);
			$('td:nth-child(8)', this).text(abono);
			$('td:nth-child(10)', this).text(total);
			$('td:nth-child(7)', this).before('<td class="tdcontenido s_i">$0.00</td>');
		});
		
		$("#tabla_reporte tr.subtotal").each(function(){
			var cargo = $('td:nth-child(2)', this).text().trim();
			var abono = $('td:nth-child(3)', this).text().trim();
			var total = $('td:nth-child(4)', this).text().trim();
			cargo = parseFloat(cargo);
			abono = parseFloat(abono);
			total = parseFloat(total);
			cargo = Currency("$", cargo);
			abono = Currency("$", abono);
			total = Currency("$", total);
			$('td:nth-child(2)', this).text(cargo);
			$('td:nth-child(3)', this).text(abono);
			$('td:nth-child(4)', this).text(total);
			$('td:nth-child(2)', this).before('<td style="background-color:#efefef;" >$0.00</td>');
		});		
		// Termina conversion a tipo dinero para impresion
		$("#tabla_reporte tr").each(function(){
			if($(this).hasClass('trcontenido'))
			{
				switch( $(this).attr('data-flag').toUpperCase() )
				{
					case "SALDOS INICIALES":
						if( $('td:nth-child(2)',this).text() != $(this).next().children('td:nth-child(2)').text() )
						{
							var s_i   = $('td:nth-child(11)', this).text();
							
							if(s_i != '$0.00')
							{
								$('td:nth-child(7)', this).text(s_i);
								$('td:nth-child(8), td:nth-child(9)',this).text("$0.00");
							}
							else
							{
								if($(this).prev().hasClass('titulo') && $(this).next().next().hasClass('subtotal'))
								{
									$(this).prev().remove();
									$(this).next().remove();
									$(this).next().remove();
									$(this).next().remove();	
								}
								$(this).remove();
							}
						}
						break;
					case "MOVIMIENTOS CORRIENTES":
						// Los movimientos corrientes revisan si
						if( $('td:nth-child(2)', this).text() != $(this).prev().children('td:nth-child(2)').text() )
						{
						//	alert("Estoy solita y soy de movimientos Corrientes : "  + $('td:nth-child(2)', this).text() + ". Somos " + $('td', this).length);
						}
						else
						{
						
							var saldos_ini = $(this).prev().children('td:nth-child(11)').text();
							saldos_ini = unCurrency(saldos_ini);
							var saldos_fin = $('td:nth-child(11)',this).text();
							saldos_fin = unCurrency(saldos_fin);
							saldos_fin = (saldos_fin) + (saldos_ini);
							$('td:nth-child(11)', this).text( Currency("$",saldos_fin) );
							 
							$('td:nth-child(7)', this).text(Currency("$",saldos_ini));
							
							//alert("Tengo Familia :3");
							$(this).prev().remove();
						}
						break;
				}
			}
		});
		
		var main_saldo_ini = 0;
		var main_saldo_fin = 0;
		var main_cargo     = 0;
		var main_abono     = 0;
		
		$('#tabla_reporte tr').each(function(index){//7,8,9,11
			if($(this).hasClass('trcontenido'))
			{
				nature = $('td:nth-child(3)', this).text().toUpperCase();
				switch(nature)
				{
					case "DEUDORA":
						$('td:nth-child(7),td:nth-child(11)', this).css('text-align','left');
						break;
					case "ACREEDORA":
						$('td:nth-child(7),td:nth-child(11)', this).css('text-align','right');
						break;
				}
			
				main_saldo_ini +=  parseFloat( unCurrency( $('td:nth-child(7)', this ).text() ) );
				main_cargo     +=  parseFloat( unCurrency( $('td:nth-child(8)', this ).text() ) );
				main_abono     +=  parseFloat( unCurrency( $('td:nth-child(9)', this ).text() ) );
				main_saldo_fin +=  parseFloat( unCurrency( $('td:nth-child(11)', this ).text() ) );
				//alert($('td:nth-child(7)', this).text());
			}
			
			if($(this).hasClass('subtotal'))
			{
				//alert($("th:nth-child(1)", this).text().trim() + ": " + main_saldo_fin);
				$('td:nth-child(2)', this).text(Currency("$", main_saldo_ini ));				
				$('td:nth-child(3)', this).text(Currency("$", main_cargo ));
				$('td:nth-child(4)', this).text(Currency("$", main_abono ));// OK
				$('td:nth-child(5)', this).text(Currency("$", main_saldo_fin ));
				main_saldo_ini = 0;
				main_saldo_fin = 0;
				main_cargo = 0;
				main_abono = 0;
			}
		});
		
		$("#tabla_reporte tr.titulo").each(function(){
			var txt = $(this).text().trim();
			txt = txt.replace("Cuenta_de_Mayor:","Cuenta_de_Mayor: ");
			$('th:nth-child(1)').attr('colspan','2');
			//alert(txt + ": " + $('th.subtotal:contains("' + txt + '")').length);
			main_saldo_ini = $('th:contains("' + txt + '")').parent('tr').children('td:nth-child(2)').text();
			main_cargo     = $('th:contains("' + txt + '")').parent('tr').children('td:nth-child(3)').text();
			main_abono     = $('th:contains("' + txt + '")').parent('tr').children('td:nth-child(4)').text();
			main_saldo_fin = $('th:contains("' + txt + '")').parent('tr').children('td:nth-child(5)').text();
			var new_columns  = "<td style='background-color:silver;border:1px solid black;font-weight:normal;color:black;text-align:left;font-size:14px' >";
				new_columns +=	main_saldo_ini;
				new_columns += "</td>";
				new_columns += "<td style='background-color:silver;border:1px solid black;font-weight:normal;color:black;text-align:left;font-size:14px' >";
				new_columns +=	main_cargo;
				new_columns += "</td>";
				new_columns += "<td style='background-color:silver;border:1px solid black;font-weight:normal;color:black;text-align:left;font-size:14px' >";
				new_columns +=	main_abono;
				new_columns += "</td>";
				new_columns += "<td style='background-color:silver;border:1px solid black;font-weight:normal;color:black;text-align:left;font-size:14px' >";
				new_columns +=	main_saldo_fin;
				new_columns += "</td>";
			$(this).append(new_columns);
			txt = txt.replace("Cuenta_de_Mayor","Cuenta de Mayor");
			$('th:nth-child(1)', this).text(txt);
		});
		$('tr.subtotal').remove();
		$('td:contains("$-")').css('color','red');
		$('td').each(function(){
			
			if($(this).css('display') == 'none')
			{
				//$(this).remove();
			}
		});
		
	});
	
	function  unCurrency(value)
	{
		value = value.replace("$",'');
		value = value.replace(',','');
		value = value.replace('.00','');
		value = parseFloat(value);
		return value;
	}
		
	function getTotal(code,nature,cargo,abono)
	{
		var total = 0;
		switch(code)
		{
			case '1'://  ACTIVO
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
			case '2'://  PASIVO
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
			case '3'://  CAPITAL
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
			case '4'://  RESULTADOS
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
	
</script>