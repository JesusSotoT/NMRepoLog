<?php
	@session_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../../../netwarelog/catalog/css/view.css"   title="estilo" rel="stylesheet" type="text/css" />
		<!--<LINK href="../../../../netwarelog/design/default/netwarlog.css"   title="estilo" rel="stylesheet" type="text/css" / -->
		<?php include('../../../../netwarelog/design/css.php');?>
	    <LINK href="../../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

		<script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script type="text/javascript" src="../../../punto_venta/js/jquery.numeric.js"></script>
		<style>
			[id=pagos_div] tbody tr td:nth-child(2)
			{
				max-width: 150px;
			}
			[id=pagos_div] tbody tr td:last-child,
			[id=productos_div] tbody tr td:last-child,
			[id=cxc_div] tbody tr td:last-child
			{
				text-align: right;
				padding-right: 5px;
			}
		</style>
		<script src="../../../punto_venta/js/ui.datepicker-es-MX.js"></script>
		<script src="../../js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<link rel="stylesheet" type="text/css" href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
		<script src="../../../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>

		<!-- <script type="text/javascript" src="../../../punto_venta/js/corte_caja.js"></script> -->
		<!-- <script type="text/javasctipt" src="../../../punto_venta/js/paginaciongrid.js"></script> -->
		<!--Script del autocompletado del campo de productos !-->
		<script type="text/javascript">
			$.fn.disable = function() {
				return this.each(function() {
					if (typeof this.disabled !== "undefined")
					{
						$(this).data('jquery.disabled', this.disabled);
						this.disabled = true;
					}
				});
			};

			$.fn.enable = function() {
				return this.each(function() {
					if (typeof this.disabled !== "undefined")
					{
						this.disabled = $(this).data('jquery.disabled');
					}
				});
			};

			$(document).ready(function() {
				$(".numeric").numeric({ precision: 12, scale: 2 });

				$(".throwback").click(function(event) {
					location.href = "index.php";
				});

				$("#retiro_caja").blur(function(event) {
					var val = ( $(this).val() === null || $(this).val().trim().length === 0 ) ? 0 : $(this).val();
					var availabeValue = parseFloat( $("#saldo_disponible").val(), 10);

					if ( val < 0 )
					{
						promotedValue = ( val * -1 );
						finalValue = ( confirm("El valor no puede ser negativo. Tal vez quizo decir " + promotedValue ) === true ) ? promotedValue : 0;
						$(this).val( finalValue );
						$(this).focus().blur();
					}
					else if( val > availabeValue )
					{
						alert("El retiro no puede ser mayor a las existencias. Intente Otra vez.");
						$(this).val("").focus();
					}
				});

				$("#deposito_caja").blur(function(event) {
					var val = ( $(this).val() === null || $(this).val().trim().length === 0 ) ? 0 : $(this).val();
					if ( val < 0 )
					{
						promotedValue = ( val * -1 );
						finalValue = ( confirm("El valor no puede ser negativo. Tal vez quizo decir " + promotedValue ) === true ) ? promotedValue : 0;
						$(this).val( finalValue );
						$(this).focus().blur();
					}
				});

				$("#send").click(function(event){// Evento para guardar Corte de Caja

						var retiros = [];

						var $this, input, text, obj;
						$('.idret').each(function() {
						   $this = $(this);
						   $input = $this.find("td");
						   text = $this.text();
						   obj = {};
						   obj['idre'] = text;
						   retiros.push(obj);
						});


					$(this).prop('disabled', true);
					$("#alerta_guardar").html("<div style='color: #01a05f; text-align: right;'><b>Se esta&#769; generando su corte. Por favor sea paciente. <img src='../../img/preloader.gif'></b></div>");

					// Inicia seccion de validaciones - Aqui contiene las alertas
						v1  = validation(["fecha_inicio","fecha_fin"],"date");
						v2  = validation(["saldo_inicial","monto_ventas","saldo_disponible","retiro_caja","deposito_caja"],"number");
						tot = v1 + v2 ;
					// Termina seccion de validaciones

					if ( tot === 2 )
					{
						$.post('../controllers/boxCut.php',
							{ method: 'sales', date: $("#fecha_fin").val() },
							function(data, textStatus, xhr) {
								try
								{
									data = $.parseJSON( data );

									if ( parseFloat( data[0].ventas, 10 ) > 0 )
									{
										alert("There are " + data[0].ventas + " sales performed out of the period. Reloading..." );
										location.href = "boxCut.php";
									}
									else
									{
										retiro_cajaVar   = ( $("#retiro_caja").val().trim().length   === 0 ) ? 0 : $("#retiro_caja").val();
										deposito_cajaVar = ( $("#deposito_caja").val().trim().length === 0 ) ? 0 : $("#deposito_caja").val();

										$.post('../controllers/boxCut.php',
										{
											method           : 'newCut',
											fecha_inicio     : $("#fecha_inicio").val(),
											fecha_fin        : $("#fecha_fin").val(),
											saldo_inicial    : unCurrency( $("#saldo_inicial").val() ),
											monto_ventas     : unCurrency( $("#monto_ventas").val() ),
											saldo_disponible : unCurrency( $("#saldo_disponible").val() ),
											retiro_caja      : retiro_cajaVar,
											deposito_caja    : deposito_cajaVar,
											retiros          : retiros

										},
										function(req, textStatus, xhr)
										{
											if(req)
											{
												$("#alerta_guardar div").fadeOut();
												alert("End-of-Day performed successfully.");
												setTimeout( function(){
													location.href = "index.php";
												}, 3000);

												//////////////////////////////////////////////////
												//Al guardar el corte tomamos la consulta de la venta y la llevamos al sistema contable
													// Queda pendiente la confirmacion de Ivan
													// $.post("../../../cont/ajax.php?c=CaptPolizas&f=InsertPolMovPDV",
													//	{
													//	Fecha : $('#fecha_fin').val()
													//	},
													//	function(req)
													//	{
													//		$("#alerta_guardar").empty();
													//		alert("El corte de caja se generó con éxito");
													//		//alert(data)
													//		window.location="index.php";
													//	}
													// );
												//Termina cambios para el sistema contable
												//////////////////////////////////////////////////
											}
											else
											{
												$("#alerta_guardar div").fadeOut();
												$("#send").enable();
												alert("Unexpected error while performind End-of-Day.");
											}
										});
									}
								}
								catch (e)
								{
									location.href = "boxCut.php";
								}

							}
						);
					}
					else
					{
						$("#alerta_guardar div").fadeOut();
						setTimeout(function(){ location.href = "boxCut.php"; }, 1000);
					}

				});
				data = "";
				/* jshint ignore:start */
				<?php
					$_POST['method'] =  "getSales";
					require '../controllers/boxCut.php';
				?>
				/* jshint ignore:end */
				if ( data !== "" )
				{	//console.log(data);
					for (var i = data.length - 1; i >= 0; i--)
					{
						switch(data[i].Flag)
						{
							case "Ventas":
								line = ( (i%2) === 1 ) ? "<tr class='busqueda_fila'>" : "<tr class='busqueda_fila2'>";
								line += "	<td>";
								line += data[i].idVenta;
								line += "	</td>";
								line += "	<td>";
								line += ( data[i].nombre !== null ) ? data[i].nombre : "General Public";
								line += "	</td>";
								line += "	<td>";
								line += data[i].fecha;
								line += "	</td>";
								line += "	<td>";
								line += data[i].Efectivo;
								line += "	</td>";
								line += "	<td>";
								line += data[i].TCredito;
								line += "	</td>";
								line += "	<td>";
								line += data[i].TDebito;
								line += "	</td>";
								line += "	<td>";
								line += data[i].CxC;
								line += "	</td>";
								line += "	<td>";
								line += data[i].Cheque;
								line += "	</td>";
								line += "	<td>";
								line += data[i].Trans;
								line += "	</td>";
								line += "	<td>";
								line += data[i].SPEI;
								line += "	</td>";
								line += "	<td>";
								line += data[i].TRegalo;
								line += "	</td>";
								line += "	<td>";
								line += data[i].Ni;
								line += "	</td>";
								line += "<td>";
								line += data[i].cambio;
								line += "	</td>";
								line += "	<td>";
								line += data[i].Impuestos;
								line += "	</td>";
								line += "	<td>";
								line += data[i].Monto;
								line += "	</td>";
								line += "	<td>";
								line += data[i].Importe;
								line += "	</td>";
								line += "	<td>";
								line += parseFloat(data[i].Efectivo, 10) - parseFloat(data[i].cambio,10);
								line += "	</td>";
								line += "</tr>";

								$("#pagos_div tbody").append( line );
								break;
							case "Productos":
								line = ( (i%2) === 1 ) ? "<tr class='busqueda_fila'>" : "<tr class='busqueda_fila2'>";
								line += "	<td>";
								line += data[i].idVenta;// Codigo
								line += "	</td>";
								line += "	<td>";
								line += data[i].fecha;// Nombre
								line += "	</td>";
								line += "	<td>";
								line += data[i].nombre;// Cantidad
								line += "	</td>";
								line += "	<td>";
								line += data[i].Efectivo;// Precio Unitario
								line += "	</td>";
								line += "	<td>";
								line += data[i].TCredito;// Descuento
								line += "	</td>";
								line += "	<td>";
								line += data[i].TDebito;// Impuestos
								line += "	</td>";
								line += "	<td>";
								line += data[i].CxC;// Subtotal
								line += "	</td>";
								line += "</tr>";
								$("#productos_div tbody").append(line);
								break;
							case "CxC":
								line = ( (i%2) === 1 ) ? "<tr class='busqueda_fila'>" : "<tr class='busqueda_fila2'>";
								line += "	<td>";
								line += data[i].idVenta;// ID de pago
								line += "	</td>";
								line += "	<td>";
								line += data[i].fecha;// Fecha de registro de cuenta por cobrar
								line += "	</td>";
								line += "	<td>";
								line += data[i].nombre;// Fecha de Vencimiento de Cuenta por cobrar
								line += "	</td>";
								line += "	<td>";
								line += data[i].TCredito;// Fecha del abono
								line += "	</td>";
								line += "	<td>";
								line += data[i].Efectivo;// Cliente
								line += "	</td>";
								line += "	<td>";
								line += data[i].Cheque;// Recibio
								line += "	</td>";
								line += "	<td>";
								line += data[i].CxC;// Forma de Pago
								line += "	</td>";
								line += "	<td>";
								line += data[i].TDebito;// Monto abonado
								line += "	</td>";
								line += "	<td>";
								line += ( parseInt(data[i].Trans, 10) === 1 ) ? data[i].TDebito : "0.00";// Suma De Ingresos
								line += "	</td>";
								line += "</tr>";
								$("#cxc_div tbody").append(line);
								break;
							case "LastData":
								$("#fecha_inicio").val( data[i].idVenta );
								$("#fecha_fin").val( data[i].fecha );
								$('label[for=fecha_inicio]').text( data[i].idVenta );
								$('label[for=fecha_fin]').text( data[i].fecha );
								break;
							case "SaldoIni":
								if ( data[i].idVenta === null  || data[i].idVenta === "")
								{
									alert("No ha iniciado caja. Inicie caja por favor.");
									location.href = 'index.php';
								}
								else
								{
									$("#saldo_inicial").val(data[i].idVenta);
									$("label[for=saldo_inicial]").text( data[i].idVenta );
								}

								break;

						}
					}
					/*$.ajax({
						url: '../controllers/boxCut.php',
						type: 'POST',
						dataType: 'json',
						data: {method: 'getRetiros'},
					})
					.done(function(resp) {
						var table;
						var total=0;
						$.each(resp, function(index, val) {
							 alert(val.concepto);
							 var cantidad = val.cantidad * 1;
							 //console.log(cantidad);
							 table +="<tr class='busqueda_fila'>";
							 table +="<td>"+val.id+"</td>";
							 table +="<td>"+cantidad.toFixed(2)+"</td>";
							 table +="<td>"+val.concepto+"</td>";
							 table +="<td>"+val.fecha+"</td>";

							total +=cantidad;
						});
						table +="<tr><td></td><td></td><td></td><td id='totalretiros' style='background-color:red;text-align:right;padding-right: 5px;'>$"+total+"</td></tr>"
						$("#retiro_div tbody").append(table);

					})
					.fail(function() {
						console.log("error");
					})
					.always(function() {
						console.log("complete");
					}); */

					var element = {};

					if( $("#pagos_div tbody tr").length )
					{
						element = {
							Efectivo  : 0,
							TCredito  : 0,
							TDebito   : 0,
							CxC       : 0,
							Cheque    : 0,
							Trans     : 0,
							SPEI      : 0,
							TRegalo   : 0,
							cambio    : 0,
							Impuestos : 0,
							Monto     : 0,
							Importe   : 0,
							Ni        : 0,
							Ingresos  : 0
						};
						$("#pagos_div tbody tr").each(function(index, el){
							element.Efectivo  += parseFloat($('td:nth-child(4)', this).text(), 10);
							element.TCredito  += parseFloat($('td:nth-child(5)', this).text(), 10);
							element.TDebito   += parseFloat($('td:nth-child(6)', this).text(), 10);
							element.CxC       += parseFloat($('td:nth-child(7)', this).text(), 10);
							element.Cheque    += parseFloat($('td:nth-child(8)', this).text(), 10);
							element.Trans     += parseFloat($('td:nth-child(9)', this).text(), 10);
							element.SPEI      += parseFloat($('td:nth-child(10)', this).text(), 10);
							element.TRegalo   += parseFloat($('td:nth-child(11)', this).text(), 10);
							element.Ni        += parseFloat($('td:nth-child(12)', this).text(), 10);
							element.cambio    += parseFloat($('td:nth-child(13)', this).text(), 10);
							element.Impuestos += parseFloat($('td:nth-child(14)', this).text(), 10);
							element.Monto     += parseFloat($('td:nth-child(15)', this).text(), 10);
							element.Importe   += parseFloat($('td:nth-child(16)', this).text(), 10);
							element.Ingresos  += parseFloat($('td:nth-child(17)', this).text(), 10);
						});
						line  = "<tr class='nmsubtitle'>";
							line += "	<td colspan='3'>";
							line += "Totals";
							line += "	</td>";
							line += "	<td>";
							line += element.Efectivo;
							line += "	</td>";
							line += "	<td>";
							line += element.TCredito;
							line += "	</td>";
							line += "	<td>";
							line += element.TDebito;
							line += "	</td>";
							line += "	<td>";
							line += element.CxC;
							line += "	</td>";
							line += "	<td>";
							line += element.Cheque;
							line += "	</td>";
							line += "	<td>";
							line += element.Trans;
							line += "	</td>";
							line += "	<td>";
							line += element.SPEI;
							line += "	</td>";
							line += "	<td>";
							line += element.TRegalo;
							line += "	</td>";
							line += "	<td>";
							line += element.Ni;
							line += "	</td>";
							line += "	<td>";
							line += element.cambio;
							line += "	</td>";
							line += "	<td>";
							line += element.Impuestos;
							line += "	</td>";
							line += "	<td>";
							line += element.Monto;
							line += "	</td>";
							line += "	<td style='background-color:red' id='tot_vta'>";
							line += element.Importe;
							line += "	</td>";
							line += "	<td style='background-color:green;text-align:right;padding-right: 5px;' id='tot_ingreso'>";
							line += element.Ingresos;
							line += "	</td>";
						line += "</tr>";
						$("#pagos_div tfoot").append( line );
						element = null;
						setCurrency( '#pagos_div tbody tr', 3, 16 );
						setCurrency( '#pagos_div tfoot tr', 1, 14 );
					}
					else
					{
						line  = "<tr class='nmsubtitle'>";
						line += "	<td colspan='17' style='text-align:center;color:red;text-align:center;font-weight:bold;'>No payments registered for the period.";
						line += "	</td>";
						line += "</tr>";
						$("#pagos_div tbody").append( line );
					}

					if ( $("#productos_div tbody tr").length )
					{
						element = {
							Descuento : 0,
							Impuestos : 0,
							Subtotal  : 0
						};
						$("#productos_div tbody tr").each(function(index, el){
							element.Descuento += parseFloat( $("td:nth-child(5)", this).text(), 10);
							element.Impuestos += parseFloat( $("td:nth-child(6)", this).text(), 10);
							element.Subtotal  += parseFloat( $("td:nth-child(7)", this).text(), 10);
						});

						total = ( element.Subtotal - element.Descuento ) + element.Impuestos;
						//console.log( total );
						line  = "<tr class='nmsubtitle'>";
						line += "	<td colspan='4'>";
						line += "Totales";
						line += "	</td>";
						line += "	<td>";
						line += element.Descuento;
						line += "	</td>";
						line += "	<td>";
						line += element.Impuestos;
						line += "	</td>";
						line += "	<td style='background-color:red;text-align:right;padding-right: 5px;' id='tot_prod'>";
						line += element.Subtotal;
						line += "	</td>";
						line += "</tr>";
						// line += "<tr>";
						// line += "	<td colspan='6'>";
						// line += "Total";
						// line += "	</td>";
						// line += "	<td>";
						// line += " " + total + " ";
						// line += "	</td>";
						// line += "</tr>";

						setCurrency('#productos_div tbody tr', 3, 6);

						$("#productos_div tfoot").append( line );
						setCurrency('#productos_div tfoot tr', 1, 3);
						element = null;
						line = null;
					}
					else
					{
						line  = "<tr>";
						line += "	<td colspan='7' style='text-align:center;color:red; font-weight:bold;'>No sold products for the period.";
						line += "	</td>";
						line += "</tr>";
						$("#productos_div tbody").append( line );
					}

					if ( $("#cxc_div tbody tr").length )
					{
						element = {
							monto : 0,
							total : 0
						};

						$("#cxc_div tbody tr").each(function(index, el) {
							element.monto += parseFloat( $('td:nth-child(8)', this).text(), 10 );
							element.total += parseFloat( $('td:nth-child(9)', this).text(), 10 );
							$("td:nth-child(8)", this).text( Currency( "$", parseFloat($("td:nth-child(8)", this).text(), 10) ) );
							$("td:nth-child(9)", this).text( Currency( "$", parseFloat($("td:nth-child(9)", this).text(), 10) ) );
						});

						line  = "<tr>";
						line += '	<td colspan="7" style="text-align:right; padding-right:15px;">';
						line += "		Total";
						line += "	</td>";
						line += '	<td style="background-color:red;text-align:right;">';
						line +=  Currency("$", element.monto );
						line += "	</td>";
						line += '	<td id="tot_cxc" style="background-color:green;text-align:right;padding-right: 5px;">';
						line += Currency( "$", element.total ) ;
						line += "	</td>";
						line += "</tr>";

						$("#cxc_div tfoot").append( line );
					}
					else
					{
						line  = "<tr class='nmsubtitle'>";
						line += "	<td colspan='8' style='text-align:center;color:red; font-weight:bold;'>No AR payments for the period.";
						line += "	</td>";
						line += "</tr>";
						$("#cxc_div tbody").append( line );
					}

					// DEBIDO A QUE NO PUEDEN HABER PAGOS SIN PRODUCTOS ENTONCES
					tot_ingreso = ( $("#tot_ingreso").length !== 0 ) ? unCurrency( $("#tot_ingreso").html() ) : 0;
					tot_cxc     = ( $("#tot_cxc").length !== 0 ) ? unCurrency( $("#tot_cxc").html() ) : 0;


					if( $("#tot_vta").text() === $("#tot_prod").text())
					{
						monto_ventas = tot_cxc + tot_ingreso;
						monto_ventas = monto_ventas.toFixed(2);
						$("#monto_ventas").val( monto_ventas );
						s_i = parseFloat( unCurrency( $("#saldo_inicial").val() ), 10 );
						s_i += tot_ingreso + tot_cxc;
						$("#saldo_disponible").val( s_i.toFixed(2) );
					}
					else
					{
						alert("Alert. There is a difference between total sold items and total sales.");
						monto_ventas = tot_cxc + tot_ingreso;
						monto_ventas = monto_ventas.toFixed(2);
						$("#monto_ventas").val( monto_ventas );
						s_i = parseFloat( unCurrency( $("#saldo_inicial").val() ), 10 );
						s_i += tot_ingreso + tot_cxc;
						$("#saldo_disponible").val( s_i.toFixed(2) );
						// $("#send").prop( 'disabled', true );
					}
					var desde = $('#fecha_inicio').val();
					var hasta = $('#fecha_fin').val();

					$.ajax({
						url: '../controllers/boxCut.php',
						type: 'POST',
						dataType: 'json',
						data: {method : 'getRetiros',
								desde : desde,
								hasta : hasta,
								idcorte :'A'
									},
					})
					.done(function(resp) {

						var table;
						var total=0;
						$.each(resp, function(index, val) {

							 var cantidad = val.cantidad * 1;
							 console.log(cantidad);
							 table +="<tr class='busqueda_fila'>";
							 table +="<td class='idret'>"+val.id+"</td>";
							 table +="<td>"+cantidad.toFixed(2)+"</td>";
							 table +="<td>"+val.concepto+"</td>";
							 table +="<td>"+val.fecha+"</td>";
							 table +="<td>"+val.usuario+"</td>";

							total +=cantidad;
						});
						table +="<tr><td></td><td></td><td></td><td></td><td id='totalretiros' style='background-color:red;text-align:right;padding-right: 5px;'>$"+total.toFixed(2)+"</td></tr>"
						$("#retiro_div tbody").append(table);
						var saldoDisponible = $('#saldo_disponible').val();
						var saldoDispoFinal = saldoDisponible - total;
						$('#saldo_disponible').val(saldoDispoFinal);
					})
					.fail(function() {
						console.log("error");
					})
					.always(function() {
						console.log("complete");
					});
				}
			});

			function pdf(user)
			{
				//Plugg in que envia gran cantidad de datos en un post o get
				//Nota---Se modifico el plugg in (Ivan Cuenca) para que se habra en una pagina emergente _blank
				//Documentacion: http://www.avramovic.info/razno/jquery/redirect/
				$().redirect('pdf.php', {'cont': $('#topdf').html(), 'name': user});
			}

			function validation(selectors, type)
			{
				var i = selectors.length - 1;
				switch(type)
				{
					case "date":
						for (i ; i >= 0; i--)
						{
							var pattern = /^(19|2\d)\d\d-(0[1-9]|1[012])-([123]0|[012][1-9]|31)[ t]([01]\d|2[0-3]):([0-5]\d)(?::([0-5]\d))?$/i;
    						var x = pattern.test($( "#" + selectors[i] ).val());

							if(!x)
							{
								alert("Existe un error sobre las Fechas. Revise las fechas ubicadas en el .");
								return 0;
							}
							else
								return 1;
						}
						break;
					case "number":
						for ( i ; i >= 0; i-- )
						{
							switch( selectors[i] )
							{
								case "deposito_caja":
								case "retiro_caja":
									value = ( $("#" + selectors[i] ).val().trim().length === 0 ) ? 0 : $( "#" + selectors[i] ).val();
									break;
								default:
									if ( $( "#" + selectors[i] ).val().trim().length === 0 )
									{
										alert("Invalid POD balances. Please review End-of-Day calculations.");
										return 0;
									}
									else
										value = $( "#" + selectors[i] ).val();
									break;
							}

							if( isNaN( unCurrency( value ) ) )
							{
								alert("There are non-numerical values in the End-of-Day balances, withdrawals or deposits.");
								return 0;
							}
							else
								return 1;
						}
						break;
					case "sales":
						if( validation(['fecha_fin'],'date') === 1 )
						{
							$.post('../controllers/boxCut.php',
								{ method: 'sales', date: $("#fecha_fin").val() },
								function(data, textStatus, xhr) {
									try
									{
										data = $.parseJSON( data );
										if ( parseFloat( data[0].ventas, 10 ) > 0 )
										{
											alert("There are " + data[0].ventas + " sales out of date range. Reloading..." );
											location.href = "boxCut.php";
											response = 0;
											$("#validation").html( response );
										}
										else
											response = 1;
											$("#validation").html( response );
									}
									catch (e)
									{
										response = 0;
										$("#validation").html( response );
									}

								}
							);

							return $("#validation").html();
						}
						else
						{
							return 0;
						}
						break;
				}
			}

			function Currency(sSymbol, vValue)
			{
				aDigits = vValue.toFixed(2).split(".");
				aDigits[0] = aDigits[0].split("").reverse().join("").replace(/(\d{3})(?=\d)/g, "$1,").split("").reverse().join("");
				return sSymbol + aDigits.join(".");
			}

			function setCurrency(selector ,init, end)
			{
				$(selector).each(function() {
					$('td', this).each(function(index, el) {
						if( index >= init && index <= end )
						{

							str = Currency("$", parseFloat($(this).text(), 10) );
							$(this).text( str );
						}
					});
				});
			}

			function unCurrency(val)
			{
				val = val.toString();
				newStr = "";
				for (var i = 0; i < val.length; i++)
				{
					if( val[i] != "," && val[i] != "$" )
						newStr += val[i];
				}
				val = parseFloat( newStr, 10 );
				return val;
			}
			function arc(){

				var tot_ingreso = $('#tot_ingreso').text();
				tot_ingreso = tot_ingreso.substring(1)*1;

				//$("#arqueo").dialog({width: 330});
				$('#arqueo').modal('show');
				$('#totefect').text('$'+tot_ingreso);
				/*$("#dialog").dialog({

									buttons: {
					"Cerrar": function () {
					$(this).dialog("close");
						}
					}
				}); */
			}
			function calarque(){
				// var mil = $('#1000').val() * 1000; -- Commenting out, no $1000 bills
				var mil = 0;
				// var quini = $('#500').val() * 500; -- Commenting out, no $500 bills
				var quini = 0;
				// var dosie = $('#200').val() * 200; -- Commenting out, no $200 bills
				var dosie = 0;
				var cien = $('#100').val() * 100;
				var cincue = $('#50').val() * 50;
				var veinte = $('#20').val() * 20;
				var diez = $('#10').val() * 10;
				var cinco = $('#05').val() * 5;
				var dos = $('#02').val() * 2;
				var uno = $('#01').val() * 1;
				var cincen = $('#050').val() * 0.50;
				var veinticincen = $('#025').val() * 0.25;
				// var veintecen = $('#020').val() * .20; -- Commenting out, not $0.20 coins
				var veintecen = 0;
				var diezcen = $('#010').val() * 0.10;
				var cincen = $('#005').val() * 0.05;
				var oncen = $('#001').val() * 0.01;
				var total = mil+quini+dosie+cien+cincue+veinte+diez+cinco+dos+uno+cincen+veinticincen+veintecen+diezcen+cincen+oncen;
				$('#totalArc').val(total);

			}
			function comparqu(){
				var tot_ingreso = $('#tot_ingreso').text();
				tot_ingreso = tot_ingreso.substring(1)*1;
				var total_arq = $('#totalArc').val()*1;

				if(tot_ingreso==total_arq){
					alert('Las cantidades coinciden');
				}else{
					var	dif = tot_ingreso - total_arq;
					dif = dif+'';
					dif2 = dif.substring(1);
					alert('Hay una diferecnia de $'+dif2);
				}
			}
		</script>
		<link href="../../css/imprimir_bootstrap.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			.btnMenu{
	            border-radius: 0;
	            width: 100%;
	            margin-bottom: 1em;
	            margin-top: 1em;
	        }
	        .row
	        {
	            margin-top: 1em !important;
	        }
	        .nmwatitles, [id="title"] {
				padding: 8px 0 3px !important;
				background-color: unset !important;
			}
			.select2-container{
				width: 100% !important;
			}
			.select2-container .select2-choice{
				background-image: unset !important;
				height: 31px !important;
			}
	        .tablaResponsiva{
		        max-width: 100vw !important;
		        display: inline-block;
		    }
		    .nmsubtitle{
		    	color: black !important;
		    }
		    @media print{
				#imprimir,#filtros,#excel, .botones, input[type="button"], button, button[type="button"], .btnMenu{
					display:none;
				}
				.table-responsive{
					overflow-x: unset;
				}
				#imp_cont{
					width: 100% !important;
				}
			}
		</style>
	</head>
	<body>

		<div id='arqueo' class="modal fade" tabindex="-1" role="dialog">
	        <div class="modal-dialog">
	            <div class="modal-content">
	                <div class="modal-header">
	                	<h4 class="modal-title">Reconciliation</h4>
	                </div>
	                <div class="modal-body">
	                    <div class="row">
	                        <div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
	                    		<div class="table-responsive">
	                    			<table class="table">
										<!--
										<tr>
											<td><label>$1000</label></td>
											<td><input type="text" id="1000" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
									-->
										<!--
										<tr>
											<td><label>$500 </label></td>
											<td><input type="text" id="500" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
									-->
										<!--
										<tr>
											<td><label>$200 </label></td>
											<td><input type="text" id="200" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
									-->
										<tr>
											<td><label>$100 </label></td>
											<td><input type="text" id="100" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$50  </label></td>
											<td><input type="text" id="50" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$20  </label></td>
											<td><input type="text" id="20" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$10  </label></td>
											<td><input type="text" id="10" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$5   </label></td>
											<td><input type="text" id="05" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$2   </label></td>
											<td><input type="text" id="02" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$1   </label></td>
											<td><input type="text" id="01" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$0.50 </label></td>
											<td><input type="text" id="050" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$0.25 </label></td>
											<td><input type="text" id="025" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$0.10 </label></td>
											<td><input type="text" id="010" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$0.05 </label></td>
											<td><input type="text" id="005" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										<tr>
											<td><label>$0.01 </label></td>
											<td><input type="text" id="001" onkeyup="calarque();" value="0" class="form-control"></td>
										</tr>
										
										<tr>
											<td><label>Total CASH: $</label></td>
											<td><input type="text" id="totalArc" value="0" class="form-control" readonly></td>
										</tr>
										<tr>
											<td><label>Total : $</label></td>
											<td><label id="totefect"></label></td>
										</tr>
									</table>
	                    		</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="modal-footer">
	                	<button id="compara" class="btn btn-primary btnMenu col-md-6" onclick="comparqu();">Comparar</button>
	                </div>
	            </div>
	        </div>
	    </div>

		<div class="container" id="imp_cont">
			<div id="validation"></div>
	        <div class="row">
	            <div class="col-md-12">
	                <h3 class="nmwatitles text-center">
	                	POS End-of-Day<br>
	                	<a href='javascript:window.print();'>
					    	<img class="nmwaicons" border='0' src='../../../../netwarelog/design/default/impresora.png'>
					    </a>
					    <!--<a href='javascript:pdf("Corte de Caja")' id='pdflink'>
					    	<img src='../../../../netwarelog/repolog/img/pdf.gif' title='Generar PDF' id='dopdf'>
					    </a>-->
	                </h3>
	            </div>
	        </div>
	        <input id='id_corte' type='hidden'>
			<input type="hidden" name="init" id="init">
			<input type="hidden" name="end" id="end">
			<input type="hidden" name="index" value="0" id="index">
			<section>
	        	<div class="row">
	        		<div class="col-sm-9">

	        		</div>
	        		<div class="col-sm-3">
	        			<button class="btn btn-success btnMenu col-md-6 throwback" id="newCut">Back to listing</button>
	        		</div>
	        	</div>
	        </section>
	        <div id='notifica_fecha_div' class="row"></div>
	        <h3>Search filter by End-of-Day date</h3>
	        <section>
	            <div class="row">
	                <div class="col-sm-6">
	                    <label>Start date:</label>
	                    <input id='fecha_inicio' type='text' readonly class="form-control">
	                </div>
	                <div class="col-sm-6">
	                    <label>End date:</label>
	                    <input id='fecha_fin' type='text' readonly class="form-control">
	                </div>
	            </div>
	        </section>
	        <section id='topdf'>
	        	<section>
	        		<div class="row" id='print_pdf'>
		                <div class="col-sm-6">
		                    <label>Start date:</label>
		                    <label for='fecha_inicio'></label>
		                </div>
		                <div class="col-sm-6">
		                    <label>End date:</label>
		                    <label for='fecha_fin'></label>
		                </div>
		            </div>
	        	</section>
	        	<div id='aviso_canceladas'></div>
	        	<h4>Payments</h4>
	        	<section>
	        		<div class="row">
	        			<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
	        				<div class="table-responsive">
	        					<table class="busqueda table" id='pagos_div' cellpadding='0' cellspacing='0' width='100%' style='font-size: 12px;'>
									<thead>
										<tr class="tit_tabla_buscar">
											<th class="nmcatalogbusquedatit">Sale ticket number</th>
											<th class="nmcatalogbusquedatit">Customer</th>
											<th class="fechahora nmcatalogbusquedatit">Date and time</th>
											<th class="nmcatalogbusquedatit">Cash</th>
											<th class="nmcatalogbusquedatit">CC</th>
											<th class="nmcatalogbusquedatit">DC</th>
											<th class="nmcatalogbusquedatit">CR</th>
											<th class="nmcatalogbusquedatit">CH</th>
											<th class="nmcatalogbusquedatit">TRA</th>
											<th class="nmcatalogbusquedatit">Wire</th>
											<th class="nmcatalogbusquedatit">TR</th>
											<th class="nmcatalogbusquedatit">Ni</th>
											<th class="nmcatalogbusquedatit">Change</th>
											<th class="nmcatalogbusquedatit">TAX</th>
											<th class="nmcatalogbusquedatit">Amount</th>
											<th class="nmcatalogbusquedatit">Total</th>
											<th class="nmcatalogbusquedatit">Income <br>(Cash - Change)</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot></tfoot>
								</table>
	        				</div>
	        			</div>
	        		</div>
	        	</section>
	        	<h4>Sold items</h4>
	        	<section>
	        		<div class="row">
	        			<div class="col-md-12 col-sm-12 col-xs-12">
	        				<div class="table-responsive">
		        				<table id='productos_div' class="busqueda table">
									<thead>
										<tr class="tit_tabla_buscar">
											<th class="nmcatalogbusquedatit">Code</th>
											<th class="nmcatalogbusquedatit">Item</th>
											<th class="nmcatalogbusquedatit">Amount</th>
											<th class="nmcatalogbusquedatit">Unit price</th>
											<th class="nmcatalogbusquedatit">Discounts</th>
											<th class="nmcatalogbusquedatit">Tax</th>
											<th class="nmcatalogbusquedatit">Subtotal</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot></tfoot>
								</table>
	        				</div>
	        			</div>
	        		</div>
	        	</section>
	        	<h4>Account receivable payments</h4>
	        	<section>
	        		<div class="row">
	        			<div class="col-md-12 col-sm-12 col-xs-12">
	        				<div class="table-responsive">
	        					<table class="busqueda table" id="cxc_div">
									<thead>
										<tr class="tit_tabla_buscar">
											<th class="nmcatalogbusquedatit">Payment ID</th>
											<th class="nmcatalogbusquedatit">Registry date (AR)</th>
											<th class="nmcatalogbusquedatit">Due date (AR)</th>
											<th class="nmcatalogbusquedatit">Deposit date</th>
											<th class="nmcatalogbusquedatit">Customer</th>
											<th class="nmcatalogbusquedatit">Receipt</th>
											<th class="nmcatalogbusquedatit">Payment method</th>
											<th class="nmcatalogbusquedatit">Amount</th>
											<th class="nmcatalogbusquedatit">POS income</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot></tfoot>
								</table>
	        				</div>
	        			</div>
	        		</div>
	        	</section>
	        	<h4>POS withdrawals</h4>
	        	<section>
	        		<div class="row">
	        			<div class="col-md-12 col-sm-12 col-xs-12">
	        				<div class="table-responsive">
	        					<table class="busqueda table" id="retiro_div">
									<thead>
										<tr class="tit_tabla_buscar">
											<th class="nmcatalogbusquedatit">Withdrawal ID</th>
											<th class="nmcatalogbusquedatit">Amount</th>
											<th class="nmcatalogbusquedatit">Concept</th>
											<th class="nmcatalogbusquedatit">Date</th>
											<th class="nmcatalogbusquedatit">User</th>
										</tr>
									</thead>
									<tbody></tbody>
									<tfoot></tfoot>
								</table>
	        				</div>
	        			</div>
	        		</div>
	        	</section>
	        </section>
	        <h3>Balances</h3>
	        <section>
	        	<div class="row">
	        		<div class="col-sm-4">
	        			<label>POS Opening balance:</label>
	        			$<input id='saldo_inicial' type='text' class='numeric form-control' maxlength='10' readonly>
	        		</div>
	        		<div class="col-sm-4">
	        			<label>Period sales amount:</label>
	        			$<input id='monto_ventas' type='text' class='numeric form-control' maxlength='10' readonly>
	        		</div>
	        		<div class="col-sm-4">
	        			<label>POS available balance:</label>
	        			$<input id='saldo_disponible' type='text' class='numeric form-control' maxlength='10' readonly>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-sm-4">
	        			<button onclick="arc();" class="btn btn-primary btnMenu col-md-4">Reconciliation</button>
	        		</div>
	        		<div class="col-sm-4">
	        		</div>
	        		<div class="col-sm-4">
	        		</div>
	        	</div>
	        </section>
	        <h3>Payments / Withdrawals</h3>
	        <section>
	        	<div class="row">
	        		<div class="col-sm-6">
	        			<label>POS withdrawal:</label>
	        			$<input id='retiro_caja' type='text' class='numeric form-control' style='background-color: #FFCCDD;' maxlength='10'>
	        		</div>
	        		<div class="col-sm-6">
	        			<label>POS payment:</label>
	        			$<input id='deposito_caja'	type='text' class='numeric form-control' style='background-color: #A9F5A9;' maxlength='10'>
	        		</div>
	        	</div>
	        	<div class="row">
	        		<div class="col-sm-6">
	        			<button id='send' class="btn btn-primary btnMenu col-md-4">Save</button>
	        		</div>
	        		<div class="col-sm-6">
	        		</div>
	        	</div>
	        </section>
	    </div>

	</body>
</html>
