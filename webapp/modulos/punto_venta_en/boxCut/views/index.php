<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html lang="es-mx">
	<head>						
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Titulo</title>	
		
		<LINK href="../../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
<!--<LINK href="../../../../netwarelog/design/default/netwarlog.css"   title="estilo" rel="stylesheet" type="text/css" / -->
		<?php include('../../../../netwarelog/design/css.php'); ?>
	    <LINK href="../../../../netwarelog/design/<?php echo $strGNetwarlogCSS;?>/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" /> <!--NETWARLOG CSS-->

		<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>		
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		
	<!-- Slect con buscador -->
		<script src="../../js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="../../js/select2/select2.css" />
		
		<script src="../../../punto_venta/js/ui.datepicker-es-MX.js"></script>
		<!-- <script src="../../js/paginaciongrid.js"></script> -->

		<link rel="stylesheet" type="text/css" href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
		<script src="../../../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
		
		<script>
			$(function(){
				var len = page(0);
				
				if(len === 0)
				{
					alert("No existen cortes de caja");
				}

				$("#prev").attr('data-counter', '0');
				$("#next").attr('data-counter', '15');

				$("#prev,#next").click(function(event) {
					id = $(this).attr('id');
					count = parseInt( $(this).attr('data-counter'), 10 );
					index = parseInt( $("#index").val(), 10 );
					switch(id)
					{
						case "prev":
							
							$(this).prop('disabled', true);
							
							if( index > 0 )
								index -= 15;
							
							page( index );
							console.log("page de " + index);
						
							break;
						case "next":

							$(this).prop('disabled', true);
							index += 15;
							page( index );
							console.log( "page de " + index );

							break;
					}
					$("#index").val( index );
				});

				$('body').delegate('.busqueda tbody tr', 'click', function(event) {
				
					id = $(this).attr('data-id');
					init = $(this).attr('data-init');
					end = $(this).attr('data-end');
					idusuario = $(this).attr('data-user');

					$("#id").val( id );
					$("#init").val( init );
					$("#end").val( end );
					$("#iduser").val(idusuario);
					$("#showCut").submit();
					// alert("ID: " + id + ", INIT: " + init + ", END: " + end);

				});

				$.datepicker.setDefaults($.datepicker.regional['es-MX']);
				$("#ffin").datepicker({dateFormat: "yy-mm-dd"});
				$("#finicio").datepicker({dateFormat: "yy-mm-dd"});
				
				// $("#finicio").onSelect(function (dateText, inst) {
					// var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
					// $('#ffin').datepicker.formatDate('yy/mm/dd', new Date());
					// $('#ffin').datepicker( "option", "minDate", parsedDate);
				// });

				$("#newCut").click(function(){
					$( this ).prop('disabled', true);
					$.post("../../../punto_venta/funcionesBD/haysuspendidas.php", function(data){
						if(parseInt(data,10) === 0)
						{
							window.location="boxCut.php";
						}
						else
						{
							alert("There are " + parseInt(data, 10) + " suspenden sales. Return to POS in order to resume or cancel them.");
						}
						$( "#newCut" ).prop('disabled', false);
					});
				});
			});

			function page(numIndex)
			{
				var data = null;
				var init = ( $("#finicio").val().length !== 0 ) ? $("#finicio").val() : 0;
				var end  = $("#ffin").val();
				var user  =$("#selec_usuarios").val();

				$.get(
					'../controllers/boxCut.php', 
					{method:'getBoxCuts', page: numIndex, init : init, end : end, user : user} ,
					function(data)
					{
						try
						{
							data = $.parseJSON(data);
							body = "";
							$('#next,#prev').prop('disabled', false);
							
							if( $("#preloader").css('display') === 'inline' )
								$("#preloader").hide();

							for (var i = 0; i < data.length; i++)
							{
								
								if ( ( i % 2 ) === 0 )
								{
									body += '<tr class="nmcatalogbusquedacont_1" data-id="' + data[i].idCortecaja + '" data-init="' + data[i].fechainicio + '" data-end="' + data[i].fechafin + '" data-user="'+data[i].idEmpleado+'">';
								}
								else
								{
									body += '<tr class="nmcatalogbusquedacont_2" data-id="' + data[i].idCortecaja + '" data-init="' + data[i].fechainicio + '" data-end="' + data[i].fechafin + '" data-user="'+data[i].idEmpleado+'" >';
								}

								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].idCortecaja + "</center></a>";
								body += "	</td>";
								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].fechainicio + "</center></a>";
								body += "	</td>";
								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].fechafin + "</center></a>";
								body += "	</td>";
								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].saldoinicialcaja + "</center></a>";
								body += "	</td>";
								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].montoventa + "</center></a>";
								body += "	</td>";
								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].retirocaja + "</center></a>";
								body += "	</td>";
								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].abonocaja + "</center></a>";
								body += "	</td>";
								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].saldofinalcaja + "</center></a>";
								body += "	</td>";
								body += "	<td>";
								body += "		<a class='a_registro'><center>" + data[i].usuario + "</center></a>";
								body += "	</td>";
								body += '</tr>';
							}

							$(".busqueda tbody").html(body);

							disableButtons(numIndex);
							return numIndex;
						}
						catch(e)
						{
							console.log(data);
							data = '';
							alert("Unexpected error. Please reload page.");

						}
						return 0;
					}
				);
			}
			
			function disableButtons(index)
			{
				if(parseInt(index, 10) === 0)
				{
					$("#prev").prop('disabled', 'disabled');
				}
				else
				{
					$("#prev").prop('disabled', false);
				}

				if( $(".busqueda tbody tr").length < 15 )
				{
					$("#next").prop('disabled', true);
				}
				else if( $(".busqueda tbody tr").length === 0 )
				{
					$("#prev").click();
				}
				else
				{
					$("#next").prop('disabled', false);
				}
			}

			function validateFind()
			{
				if( $("#finicio").val() === "" )
				{
					alert("Initial date must be picked");
					return false;
				}

				if( $("#ffin").val() === "" )
				{
					alert("End date should be picked");
					return false;
				}
				
				$("#index").val(0);
				index = 0;

				page(index);
			}

			function limpiafiltroscortes()
			{
				$("#finicio, #ffin").val("");
				$("#index").val(0);
				page(0);
			}
			
			function listar_usuarios($objet){
				$.get(
					'../controllers/boxCut.php', 
					{method:'listar_usuarios', id: $objet['id'], usuario : $objet['usuario']} ,
					
					function(resp){
						usuarios = $.parseJSON(resp);
						
						$options='<option value="*" selected>- All -</option>';
						
						$.each(usuarios, function(i,item){
							$options+='<option value="'+item.idempleado+'">'+item.usuario+'</option>';
						});
						
						$("#selec_usuarios").html($options);
				
						$("#selec_usuarios").select2({
						     width : "150px"
						});
					}
				);
			}
		</script>
		<script>listar_usuarios({id:'', usuario:''})</script>
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
	        h4, h3{
				background-color: #eee;
				padding: 0.4em;
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
		    @media print{
				#imprimir,#filtros,#excel, .botones, input[type="button"], button
				{
					display:none;
				}
				#logo_empresa
				{
					display:block;
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

		<div class="container" id="imp_cont">
	        <div class="row">
	            <div class="col-md-12">
	                <h3 class="nmwatitles text-center">
	                	POS End-of-Day<br>
	                	<img class="nmwaicons" type="button" id="prev" src="../../../../netwarelog/design/default/pag_ant.png" >
	                	<a href='javascript:window.print();'>
	                		<img class="nmwaicons" src="../../../../netwarelog/design/default/impresora.png" border="0">
	                	</a>
	        			<img class="nmwaicons" type="button" id="next" src="../../../../netwarelog/design/default/pag_sig.png" >
	                </h3>
	            </div>
	        </div>
	        <form action="showCut.php" method="POST" id="showCut">
				<input type="hidden" id="id" name="id">
				<input type="hidden" id="init" name="init">
				<input type="hidden" id="end" name="end">
				<input type="hidden" id="iduser" name="iduser">
			</form>
			<input type="hidden" name="index" value="0" id="index">
	        <h4>Search filter by End-of-Day date</h4>
	        <section>
	            <div class="row">
	                <div class="col-sm-4">
	                    <label>Start date:</label>
	                    <input type="text" readonly="" id="finicio" class="form-control"/>
	                </div>
	                <div class="col-sm-4">
	                    <label>End date:</label>
	                    <input type="text" readonly="" id="ffin" class="form-control"/>
	                </div>
	                <div class="col-sm-4">
	                    <label>User:</label></br>
	                    <select style="width: 100%;" name="selec_usuarios" id="selec_usuarios" title="Cuenta bancaria">
						</select>
	                </div>
	            </div>
	            <div class="row botones">
	                <div class="col-md-2">
	                    <button type="button" class="btn btn-primary btnMenu col-md-6" onclick="validateFind();">Search</button>
	                </div>
	                <div class="col-md-2">
	                    <button type="button" class="btn btn-primary btnMenu col-md-6" onclick="limpiafiltroscortes();">Clear</button>
	                </div>
	                <div class="col-md-4">
	                	<img style="display:none" id="preloader" src="../../../../modulos/mrp/images/preloader.gif">
	                </div>
	                <div class="col-md-4">
	        			<button class="btn btn-success btnMenu col-md-6" id="newCut">Add End-of-Day</button>
	        		</div>
	            </div>
	        </section>
	        <section>
	        	<div class="row">
	        		<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
	        			<div class="table-responsive">
	        				<table class="busqueda table" border="1" cellpadding="3" cellspacing="1">
								<thead>
									<tr class="tit_tabla_buscar">
										<td class="nmcatalogbusquedatit" align="center">
											ID
										</td>
										<td class="nmcatalogbusquedatit" align="center">
											Start date
										</td>
										<td class="nmcatalogbusquedatit" align="center">
											End date
										</td>
										<td class="nmcatalogbusquedatit" align="center">
											POS opening balance
										</td>
										<td class="nmcatalogbusquedatit" align="center">
											Sales amount
										</td>
										<td class="nmcatalogbusquedatit" align="center" >
											POS withdrawal
										</td>
										<td class="nmcatalogbusquedatit" align="center" >
											POS deposit
										</td>
										<td class="nmcatalogbusquedatit" align="center">
											POS final balance
										</td>
										<td class="nmcatalogbusquedatit" align="center">
											User
										</td>
									</tr>			
								</thead>
								<tbody>
								</tbody>
							</table>
	        			</div>
	        		</div>
	        	</div>
	        </section>
	    </div>
	</body>
</html> 

