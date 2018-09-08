<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
	<!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Reportes</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	</head>
	<body>
		<!--[if lt IE 7]>
		<p class="browsehappy">Estas utilizando un navegador <strong>descontinuado</strong> . Por favor <a href="http://browsehappy.com/">Actualiza tu navegador</a> para mejorar tu experiencia.</p>
		<![endif]-->
		<img src="../../../../netwarelog/archivos/1/organizaciones/x.png" height="55" alt="Logo">
			<img src="../../../../netwarelog/repolog/img/impresora.png" height="16" width="16" alt="Imprimir" onclick="javascript:window.print();" style="float:right; padding-right:15px" class='noprint'>
		<h1 style="background-color:transparent;">
			Ventas por Cliente
		</h1>
		<div class="container">
			<table class="reporte noprint">
				<tr class="trcontenido">
					<td class="tdcontenido">
						<label for="init">Fecha Inicial: </label>
					</td>
					<td class="tdcontenido">
						<input type="text" name="init" id="init" readonly>
					</td>
				</tr>
				<tr class="trcontenido">
					<td class="tdcontenido">
						<label for="final">Fecha Final: </label>
					</td>
					<td class="tdcontenido">
						<input type="text" name="final" id="final" readonly>
					</td>
				</tr>
				<tr class="trcontenido">
					<td class="tdcontenido">
						<label for="sucursal" title="Haga doble click para seleccionar/deseleccionar todos">Sucursales:</label>
					</td>
					<td class="tdcontenido">
						<select name="sucursal" id="sucursal" multiple>
						</select>
					</td>
				</tr>
				<tr class="trcontenido">
					<td class="tdcontenido">
						<label for="bars">Grafico de Barras</label>
					</td>
					<td class="tdcontenido">
						<input type="checkbox" name="bars" id="bars">
					</td>
				</tr>
				<tr class="trcontenido">
					<td class="tdcontenido">
						<label for="lines">Grafico de Lineas</label>
					</td>
					<td class="tdcontenido">
						<input type="checkbox" name="lines" id="lines">
					</td>
				</tr>
				<tr class="trcontenido">
					<td class="tdcontenido">
						<label for="hybrid">Grafico Hibrido</label>
					</td>
					<td class="tdcontenido">
						<input type="checkbox" name="hybrid" id="hybrid">
					</td>
				</tr>
				<tr class="trcontenido">
					<td class="tdcontenido">
						<label for="pie">Grafico de Pastel</label>
					</td>
					<td class="tdcontenido">
						<input type="checkbox" name="pie" id="pie">
					</td>
				</tr>
				<tr>
					<th colspan="2" style="text-align:right">
						<input type="submit" value="Generar" id='gen'>
					</th>
				</tr>
			</table>
			<hr>

			<div class="reportTable"></div>
			<div id="chart1" class="chart"></div>
			<br>
			<div id="chart2" class="chart"></div>
			<br>
			<div id="chart3" class="chart"></div>
			<br>
			<div id="chart4" class="chart"></div>
			<br>
		</div>
		<link rel="stylesheet" href="../css/index.css">
		<link rel="stylesheet" href="../../../../netwarelog/utilerias/css_repolog/estilo-1.css">
		<link rel="stylesheet" href="../../../../netwarelog/catalog/css/view.css">
		<link rel="stylesheet" href="../../../../netwarelog/catalog/css/estilo.css">
		<link rel="stylesheet" href="../../../cont/css/jquery-ui.css" type="text/css" media="screen" title="no title" charset="utf-8"/>
		<link rel="stylesheet" href='../js/plugins/jquery.jqplot.min.css'>
		<script src="../../../cont/js/jquery-1.10.2.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../../cont/js/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
		
		<!-- El plugin JSONTable -->
		<script src="../js/jquery.jsontable.js"></script>
		<!-- El plugin JSONTable -->
		
		<script src="../js/plugins/jquery.jqplot.min.js"></script>
		<script src="../js/plugins/jqplot-plugins/jqplot.dateAxisRenderer.js"></script>
		<script src="../js/plugins/jqplot-plugins/jqplot.canvasTextRenderer.js"></script>
		<script src="../js/plugins/jqplot-plugins/jqplot.canvasAxisTickRenderer.js"></script>
		<script src="../js/plugins/jqplot-plugins/jqplot.categoryAxisRenderer.js"></script>
		<script src="../js/plugins/jqplot-plugins/jqplot.barRenderer.js"></script>
		<script src="../js/plugins/jqplot-plugins/jqplot.pieRenderer.min.js"></script>
		
		<script>
			$(document).ready(function() {
				// Inicia operacion con select de sucursales
					fillSelect();
					$("label[for=sucursal]").parent().bind('dblclick', function(evt){
						switch(evt.type){
							case "dblclick":
								if($("#sucursal option:selected").length === $("#sucursal option").length)
								{
									$("#sucursal option").prop('selected',false);
									$("#sucursal option:nth-child(1)").prop('selected',true);
								}								
								else
								{
									$("#sucursal option").prop('selected',true);
								}
								break;
						}
					});
					$("#sucursal").blur(function(event) {
						if ( $("option:selected", this).length === 0 )
						{
							alert("No puedes consultar sin seleccionar sucursales.");
							$("option:nth-child(1)",this).prop('selected', true);
						}
					});
				// Termina operacion con select de sucursales

				// Inicia generacion de Fechas
					$.datepicker.regional.es = {
						closeText: 'Cerrar',
						prevText: 'Mes Anterior',
						nextText: 'Mes Siguiente',
						currentText: 'Hoy',
						monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
						monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
						dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
						dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
						dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
						weekHeader: 'Sm',
						dateFormat: 'yy-mm-dd',
						firstDay: 1,
						isRTL: false,
						showMonthAfterYear: false,
						yearSuffix: ''
					};
					$.datepicker.setDefaults($.datepicker.regional.es);
					$( "#init" ).datepicker({
						changeMonth: true,
						changeYear: true,
						onClose: function( selectedDate ) {
							$( "#final" ).datepicker( "option", "minDate", selectedDate );
						}
					});
					$( "#final" ).datepicker({
						changeMonth: true,
						changeYear: true,
						onClose: function( selectedDate ) {
							$( "#init" ).datepicker( "option", "maxDate", selectedDate );
						}
					});
					$("#init,#final").val(toJqueryFormat());
				// Termina generacion de Fechas


				$('#gen').click(function() {
					initDate = $("#init").val();
					finalDate = $("#final").val();
					sucursal = $("#sucursal").val();
					if( sucursal !== null && sucursal.toString() !== "" )
					{
						$.get('../controllers/report.php',
							{
								method : 'clientSales',
								initDate : initDate,
								finalDate : finalDate,
								sucursal : sucursal
							},
							function(data, textStatus, xhr) {
							reportArray = [];
							orderedArray = [];
							try{
								data = $.parseJSON(data);
							}
							catch(err){
								console.log(data);
							}
							
							if(data.length === 0)
							{
								$('.chart,.reportTable').html("");
								alert("La busqueda no obtuvo resultados.");
							}
							else
							{
								$(".reportTable").JSONTable(data);

								for (var i = data.length - 1; i >= 0; i--)
								{
									date = data[i].Nombre.toString();
									monto = parseFloat( data[i].Ventas, 10 );
									line = [ date, monto ];
									reportArray.push( line );
								}

								if ($(".reportTable table").length === 1)
								{
									$('.reportTable table').addClass('busqueda');
									$('.reportTable thead tr').addClass('tit_tabla_buscar').css('font-size', '10pt');
									$('.reportTable tbody tr').addClass('busqueda_fila').css('font-size', '10pt');

									$(".reportTable table tbody tr").each(function(){
										td = $("td:nth-child(1)", this);
										txt = Currency("$", parseFloat(td.text(), 10) );
										td.text(txt);
									});
								}

								if (reportArray.length > 0)
								{
									if( $("#bars").prop( 'checked' )   === true )
									{
										bar_rendering( reportArray, "Reporte: Ventas por Producto", "chart1" );
									}
									else
									{
										$("#chart1").html("").hide();
									}
									
									if( $("#lines").prop( 'checked' )  === true )
									{
										line_render( reportArray, "Reporte: Ventas por Producto", "chart2" );
									}
									else
									{
										$("#chart2").html("").hide();
									}

									if( $("#hybrid").prop( "checked" ) === true )
									{
										hybridRender(reportArray, "Reporte: Ventas por Producto","chart3");
									}
									else
									{
										$("#chart3").html("").hide();
									}

									if( $("#pie").prop( "checked" )  === true )
									{
										reportArray = order(reportArray);

										do{
											date  = reportArray[0][0];
											monto = reportArray[0][1];
											orderedArray.push([date, monto]);
											reportArray.shift();
											if (reportArray.length > 0)
											{
												date  = reportArray[ reportArray.length -1 ][0];
												monto = reportArray[ reportArray.length -1 ][1];
												orderedArray.push([date, monto]);
												reportArray.pop();
											}
											
										}while(reportArray.length);
										
										pieRender(orderedArray,"Reporte: Ventas por Producto","chart4", toPorcent( orderedArray ) );
									}
									else
									{
										$("#chart4").html("").hide();
									}
								}
							}
						});
					}
					else
					{
						alert("Es necesario que seleccione al menos una sucursal.");
					}
						
				});
			});

			//Inician Herramientas de Graficacion
				function bar_rendering (data,title,selector)
				{
					$("#" + selector).html("").show();
					var plot1 = $.jqplot(selector, 
						[data], 
						{
							title: title,
							series:[{renderer:$.jqplot.BarRenderer}],
							axesDefaults: 
							{
								tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
								tickOptions: 
								{
									angle: -30,
									fontSize: '10pt'
								}
							},
							axes:
							{
								xaxis:
								{
									renderer: $.jqplot.CategoryAxisRenderer
								}
							}
						});
				}

				function line_render ( data, title, selector )
				{
					$("#" + selector).html("").show();
					var plot1 = $.jqplot(selector, 
						[data], 
						{
							title: title,
							axesDefaults: 
							{
								tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
								tickOptions: 
								{
									angle: -30,
									fontSize: '10pt'
								}
							},
							axes: 
							{
								xaxis: 
								{
									renderer: $.jqplot.CategoryAxisRenderer
								}
							}
						});
				}

				function hybridRender (data, title, selector)
				{
					$("#" + selector).html("").show();
					var line1 = data;
					var line2 = data;
					
					var plot2 = $.jqplot(selector, [line1, line2], {
						title : title,
						series:[{renderer:$.jqplot.BarRenderer}, {xaxis:'x2axis', yaxis:'y2axis'}],
						axesDefaults: {
							tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
							tickOptions: {
								angle: 30
							}
						},
						axes: {
							xaxis: {
								renderer: $.jqplot.CategoryAxisRenderer
							},
							x2axis: {
								renderer: $.jqplot.CategoryAxisRenderer
							},
							yaxis: {
								autoscale:true
							},
							y2axis: {
								autoscale:true
							}
						}
					});	
				}

				function pieRender (data, title, selector, porcent)
				{
					$("#" + selector).html("").show();
					var plot1 = jQuery.jqplot (selector, [data], 
					{ 
						title: title,
						seriesDefaults: {
						renderer: jQuery.jqplot.PieRenderer, 
						rendererOptions: {
							showDataLabels: true,
							dataLabels : porcent,
							diameter : 300,
							sliceMargin: 4,
							dataLabelThreshold : 0,
							dataLabelPositionFactor :1.2,
							dataLabelNudge : 10,
								//dataLabelFormatString : "%s"
							}
						}, 
						legend: { show:true, location: 'e' }
					});
				}
			//Terminan Herramientas de Graficacion

			// Inician Utilidades
				function toJqueryFormat(dias)
				{
					now = new Date();
					if(dias > 0)
						now.setDate(now.getDate() + dias);
						
					month = (now.getMonth() < 10 ) ? "0" + ( parseInt( now.getMonth(), 10) + 1 )  : ( parseInt( now.getMonth(), 10) + 1 );
					day = (now.getDate() < 10 ) ? "0" + now.getDate() : now.getDate();
					now = now.getFullYear() + "-" + month + "-" + day;
					return now;
				}
				function Currency(sSymbol, vValue) 
				{
					aDigits = vValue.toFixed(2).split(".");
					aDigits[0] = aDigits[0].split("").reverse().join("").replace(/(\d{3})(?=\d)/g, "$1,").split("").reverse().join("");
					return sSymbol + aDigits.join(".");
				}
				function order(arr)
				{
					minor = 0;
					for(var i =0 ; i < arr.length; i++ )
					{
						for(var j = 0; j < arr.length; j++)
						{
							if(parseInt(arr[i][1], 10) < parseInt(arr[j][1],10))
							{
								menor  = arr[j];
								arr[j] = arr[i];
								arr[i] = menor;
							}
						}
					}
					return arr;
				}
			// Terminan Utilidades

			// Inicia Fix Para el grafico de Pastel
				function toPorcent(data)
				{
					total = 0;
					porcent = Array();
					for (var i = 0; i < data.length; i++) {
						total += parseFloat( data[i][1], 10 );
					}
					for (i = 0; i < data.length; i++) {
						xVal = ( ( data[ i ][1] * 100 ) / total );
						porcent.push( xVal.toFixed(2) + "%" );
					}
					return porcent;
				}
			// Termina Fix Para el grafico de Pastel

			// Inicia webService para llenar combo de sucursales
				function fillSelect()
				{
					$.get('../controllers/report.php',{method : 'getSucursals'} ,function(data){
						str = "";
						try
						{
							data = $.parseJSON(data);
							for (var i = 0; i < data.length; i++)
							{
								str += (i === 0) ? 
									"<option value='" + data[i].id + "' selected='selected'>" + data[i].name + "</option>" 
									: 
									"<option value='" + data[i].id + "'>" + data[i].name + "</option>" ;
							}

							$("#sucursal").append(str);
						}
						catch(err)
						{
							console.log(data);
						}
					});
				}
			// Termina webService para llenar combo de sucursales
		</script>
	</body>
</html>