
<html lang="sp">
	<head>
        <!--LINK href="../../../webapp/netwarelog/utilerias/css_repolog/estilo-1.css" title="estilo" rel="stylesheet" type="text/css" / -->
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--TITULO VENTANA-->
		<title>Movimientos por Cuentas</title>
		<meta name="generator" content="Netbeans">
		<meta name="author" content="Omar Mendoza"><!-- Date: 2010-08-07 -->
		<meta name="author-icons" content="Rachel Fu"><!-- Date: 2010-08-07 -->

        <!--PLUG IN CATALOG-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>	

		
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script type="text/javascript">
			
			function pdf(){
				/*	
				var anchopadre = document.getElementById("tabla_reporte").parent.style.width;
				alert(anchopadre);
				var anchotabla = document.getElementById("tabla_reporte").style.width;
			  var pleft = (anchopadre / 2)+(anchotabla/2);
				alert(pleft);
				*/
				var contenido_html = $("#idcontenido_reporte").html();
				//contenido_html = contenido_html.replace(/\"/g,"\\\"");
				$("#contenido").val(contenido_html);	
				
				$("#divpanelpdf").modal('show');
			}
			function generar_pdf(){
				$("#divpanelpdf").modal('hide');
				//$("#loading").fadeIn(500);
			}
			function cancelar_pdf(){
				$("#divpanelpdf").modal('hide');
			}
			function pdf_generado(){
				alert("OK");
			}
			//$('#frpdf').load(function() {
  		//	alert("the iframe has been loaded");
			//});	
			//document.getElementById("frpdf").onload = function() {
  		//	alert("myframe is loaded");
			//};
	
			//$('#frpdf').ready(function () {
			//  alert("perfecto");
			//});
			function mail(){
				var msg = "Registre el correo electrónico a quién desea enviarle el reporte:";
				var a = prompt(msg,"@netwaremonitor.com");
				if(a!=null){
					var html_contenido_reporte;
					html_contenido_reporte = $("#idcontenido_reporte").html();
					$("#loading").fadeIn(500);
					$("#divmsg").load("../../../webapp/netwarelog/repolog/mail.php?a="+a, {reporte:html_contenido_reporte});
				}
			}	
		</script>
<script language='javascript'>
$(document).ready(function(){
	
	$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a></td>")

	//$("th:contains('Subtotal [')").text('Subtotal').next().next().after("<th style='border:solid 1px;background-color:#efefef;text-align:left;font-size:12px;'></th>");
	//$("th:contains('TOTAL')").text('Total de la Cuenta').next().next().after("<th style='border:solid 1px;background-color:#efefef;text-align:left;font-size:12px;'></th>");
	if(parseInt($("#saldosSin").val()))
	{
		$("td[tt='despues']").each(function()
		{
			if(!parseFloat($(this).text()))
				$("tr[cl='"+$(this).attr('cl')+"']").remove()
		});
	}
});	
function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $("#idcontenido_reporte").html(), 'name': $("#titulo").val()});
			}
</script>
<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
<style type="text/css">
	.tit_tabla_buscar td
	{
		font-size:medium;
	}

	#logo_empresa /*Logo en pdf*/
	{
		display:none;
	}

	@media print
	{
		#imprimir,#filtros,#excel,#email_icon, #botones
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
	}
	.btnMenu{
      	border-radius: 0; 
      	width: 100%;
      	margin-bottom: 0.3em;
      	margin-top: 0.3em;
  	}
  	.row
  	{
      	margin-top: 0.5em !important;
  	}
  	h4, h3{
      	background-color: #eee;
      	padding: 0.4em;
  	}
  	.modal-title{
  		background-color: unset !important;
  		padding: unset !important;
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
  	.twitter-typeahead{
  		width: 100% !important;
  	}
  	.tablaResponsiva{
        max-width: 100vw !important; 
        display: inline-block;
    }
</style>
	</head>

	<body>
	<?php //Nuevo Commit
	$url = explode('/modulos',$_SERVER['REQUEST_URI']);
	if($logo == 'logo.png') $logo = 'x.png';
	$logo = str_replace(' ', '%20', $logo);
	?>

		<!--GENERA PDF*************************************************-->
		<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
		    <div class="modal-dialog modal-sm">
		        <div class="modal-content">
		            <div class="modal-header">
		            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		                <h4 class="modal-title">Generar PDF</h4>
		            </div>
		            <form id="formpdf" action="libraries/pdf/examples/polizasImpresion.php" method="post" target="_blank" onsubmit="generar_pdf()">
			            <div class="modal-body">
			                <div class="row">
			                	<div class="col-md-6">
			                		<label>Escala (%):</label>
									<select id="cmbescala" name="cmbescala" class="form-control">
										<?php
											for($i=100; $i > 0; $i--){
												echo '<option value='. $i .'>' . $i . '</option>';
											}
										?>
									</select>
			                	</div>
			                	<div class="col-md-6">
			                		<label>Orientación:</label>
			                		<select id="cmborientacion" name="cmborientacion" class="form-control">
										<option value='P'>Vertical</option>
										<option value='L'>Horizontal</option>
									</select>
			                	</div>
			                </div>
			                <textarea id="contenido" name="contenido" style="display:none"></textarea>
							<input type='hidden' name='nombreDocu' value='libro_mayor'>
							<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
			            </div>
			            <div class="modal-footer">
			            	<div class="row">
			                    <div class="col-md-6">
			                    	<input type="submit" value="Crear PDF" autofocus class="btn btn-primary btnMenu">
			                    </div>
			                    <div class="col-md-6">
			                    	<input type="button" value="Cancelar" onclick="cancelar_pdf()" class="btn btn-danger btnMenu">
			                    </div>
			                </div>
			            </div>
			        </form>
		        </div>
		    </div>
		</div>
		<!--GENERA PDF*************************************************-->

		<!-- MAIL -->
		<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;z-index:500;">
		<div 
			id="divmsg"
			style="
				opacity:0.8;
				position:relative;
				background-color:#000;
				color:white;
				padding: 20px;
				-webkit-border-radius: 20px;
				border-radius: 10px;
				left:-50%;
				top:-30%
			">
			<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...
			</center>
		</div>
		</div>
		<script>
			function cerrarloading(){
				$("#loading").fadeOut(0);
				var divloading="<center><img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'><br>Cargando...</center>";
				$("#divmsg").html(divloading);
			}
		</script>

		
		<div class="container" style="width:100%">
			<div class="row">
				<div class="col-md-12">
					<h3 class="nmwatitles text-center">
						Libro de Mayor<br>
						<section id="botones">
							<a href="javascript:window.print();"><img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0"></a>
		                    <a href="index.php?c=Reports&f=libro_mayor"> <img src="../../../webapp/netwarelog/repolog/img/filtros.png" title ="Haga clic aqui para cambiar filtros..." border="0"> </a>
							<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
							<a href="javascript:mail();"> <img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0"> </a>
							<a href="javascript:pdf();"> <img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"> </a>		
						</section>
					</h3>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<form id="reporte" name="reporte"  method="post" action="redirecciona.php">
								<input type='hidden' value='Movimiento por Cuentas.' id='titulo'>
								<section id='idcontenido_reporte'>
									<div class="row">
										<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;color:gray;">
											<b>Fecha de Impresión<br><?php echo date("d/m/Y H:i:s"); ?></b>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 col-sm-12" style='text-align:center;font-size:18px;'>
											<b style='text-align:center;'><?php echo $empresa;?></b>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
											<b style='font-size:16px;'>Libro de Mayor</b><br>
											Del <b><?php echo $fecha_antes;?> </b>Al <b> <?php echo $fecha_despues;?></b>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
													<div class="table-responsive">
														<!-- TABLA REPORTE --> 
														<table  style="max-width:1350px;text-align:center;font-size:9px;" id="tabla_reporte" class="reporte table" border="0" align="center" >
									                	<tr style="background-color:#d2d7dc;height:30px;color:black;font-size:10px;font-weight:bold;">
										                	<td style="width:10%;">Fecha</td>
										                	<td style="width:20%;">Concepto de Movimiento</td>
										                	<td style="width:20%;">Cargos</td>
										                	<td style="width:20%;">Abonos</td>
										                	<td style="width:30%;">Saldo</td>
										                	
										                </tr>
										
									                <!--Separador****|****-->
													<!--Separador**|**-->
															<?php
															$cont=0;
															$cantidadMovs=$datos->num_rows;
															echo "<input type='hidden' id='saldosSin' value='$saldosSin'>";
															while($info = $datos->fetch_object())
															{

																	if($anterior != $info->account_id)
																	{
																				
																				if($cont!=0)
																					{
																						echo "<tr id='sub-$info->account_id' style='font-weight:bold;background-color:#f6f7f8;height:30px;' tipo='subtotal' cl='$anterior'></tr>";
																						echo "<tr style='height:30px;' cl='$anterior'>
																						<td></td>
																						<td></td>
																						
																						<td style='background-color:#dbdfe3'></td>
																						<td style='background-color:#dbdfe3';><strong>Saldo Despues:</strong></td>
																						<td style='background-color:#dbdfe3' cl='$anterior' tt='despues'> ".number_format($this->ReportsModel->Saldos($anterior,$fecha_despues,'Despues',1,$_REQUEST['segmento'],$saldo,$_REQUEST['sucursal']),2)."</strong></td>
																						</tr><!--Separador**|**-->";
																						if($anteriorMayor != $info->main_father AND intval($_REQUEST['tipo']))
																						{
																							echo "<tr style='height:30px;'>
																									<td colspan='2'></td>
																									
																									<td style='background-color:#dbdfe3'></td>
																									<td style='background-color:#dbdfe3'><strong>Saldo Mayor Despues:</strong></td>
																									<td style='background-color:#dbdfe3'> ".number_format($this->ReportsModel->Saldos($anteriorMayor,$fecha_despues,'Despues',0,$_REQUEST['segmento'],$saldo,$_REQUEST['sucursal']),2)."</strong></td>
																									</tr><!--Separador**|**-->";
																						}
																					}
																				if($anteriorMayor != $info->main_father AND intval($_REQUEST['tipo']))
																				{
																					$NM = explode("/",$info->NombreMayor);	
																					echo "<tr class='otros' style='background-color:#edeff1;height:30px;text-align:left;' tipo='Mayores'>
																					<td colspan=2>Cuenta Mayor: <b>$NM[0]</b></td>
																					<td colspan=1><b>$NM[1]</b></td>
																					<td style='text-align:center;'><strong>Saldo Mayor Antes: </strong></td><td style='text-align:center;'>".number_format($this->ReportsModel->Saldos($info->IdMayor,$fecha_antes,'Antes',0,$_REQUEST['segmento'],$saldo,$_REQUEST['sucursal']),2)."</strong></td>
																					</tr>";	
																				}

																				echo "<tr class='otros' cl='$info->account_id' style='background-color:#edeff1;height:30px;text-align:left;' tipo='Cuentas'>
																				<td colspan=2>Cuenta: <b>$info->Codigo_Cuenta</b></td>
																				<td colspan=1><b>$info->Descripcion_Cuenta</b></td>
																				<td style='text-align:center;'><strong>Saldo Antes: </strong></td>
																				<td style='text-align:center;'>".number_format($this->ReportsModel->Saldos($info->account_id,$fecha_antes,'Antes',1,$_REQUEST['segmento'],$saldo,$_REQUEST['sucursal']),2)."</strong></td>
																				</tr>";
																	}
																	if(strtotime($info->Fecha) >= strtotime($fecha_antes))
																	{
																		echo "
																		<tr class='' style:'font-size:8px;' tipo='movimientos' cl='$info->account_id'>
																		<td class='tdcontenido' title='Fecha'>".$info->Fecha."</td>
																
																		
																		<td class='tdcontenido' title='Concepto_Movimiento'>".$info->Concepto_Movimiento."</td>
																		
																		<td class='tdcontenido' title='Cargos'>".$info->Cargos."</td>
																		<td class='tdcontenido' title='Abonos'>".$info->Abonos."</td>
																		
																		<td class='tdcontenido' title='Saldo'>".number_format($info->SaldoDespues,2)."</td></tr>";

																	}


																	//$tercer = $anterior;
																	$anterior = $info->account_id;
																	$anteriorMayor = $info->main_father;
																	//$saldo_antes = $info->SaldoAntes;
																	//$saldo_despues = $info->SaldoDespues;
																	$cont++;
																	if($cont == $cantidadMovs)
																	{
																		echo "<tr id='sub-$info->account_id' style='font-weight:bold;background-color:#f6f7f8;height:30px;' tipo='subtotal'></tr>";
																		echo "<tr style='height:30px;' cl='$info->account_id'><td colspan='2'></td><td style='background-color:#dbdfe3';></td><td style='background-color:#dbdfe3';><strong>Saldo Despues:</strong></td><td colspan='2' style='background-color:#dbdfe3;' cl='$info->account_id' tt='despues'> ".number_format($this->ReportsModel->Saldos($anterior,$fecha_despues,'Despues',1,$_REQUEST['segmento'],$saldo,$_REQUEST['sucursal']),2)."</strong></td></tr>";
																		if(intval($_REQUEST['tipo']))
																		{
																			echo "<tr style='height:30px;'><td colspan='2'></td><td style='background-color:#dbdfe3;'></td><td style='background-color:#dbdfe3;'><strong>Saldo Mayor Despues:</strong></td><td style='background-color:#dbdfe3;'> ".number_format($this->ReportsModel->Saldos($anteriorMayor,$fecha_despues,'Despues',0,$_REQUEST['segmento'],$saldo,$_REQUEST['sucursal']),2)."</strong></td></tr>";
																		}
																	}
															}
															?>                
															              
															              <tr  id='xx' style='font-weight:bold;background-color:#d2d7dc;height:30px;' tipo='total'><td colspan='2'>Totales:</td></tr>  
									            
									            		</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</section>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

    </body>
        <script language='javascript'>
$(document).ready(function()
{

	//Proceso que recorre los valores de los elementos y los suma. 
	//Cuando termina agrega los elementos con los resultados
	
	var total = 0;
	var subtotal = 0;
	for(var f = 3; f <= 4; f++)//Recorre cada columna
	{
		$('#xx').append("<td id='"+f+"'></td>");//Anexa un elemento a la tabla
		$("#tabla_reporte tr").each(function(index)//Recorre cada fila
		{
			if($(this).attr('tipo') == 'movimientos')//Si es un elemento de tipo numero hace la suma de los resulados
			{
				total += parseFloat($("td:nth-child("+f+")",this).text())//Sumatoria total
				subtotal += parseFloat($("td:nth-child("+f+")",this).text())//Sumatoria del proveedor
			}

			if($(this).next().attr('tipo') == 'subtotal' && $(this).next().next().attr('tipo') != 'movimientos')//Si es un campo del tipo subtotal y el siguiente elemento no es de tipo numero entonces agrega elementos
				{
					if(f==3)//si se trata del primer barrido agraga td`s de relleno a la tabla
					{
						$(this).next().append("<td colspan='2'>Subtotal:</td>");
					}
					
					if(isNaN(subtotal)){subtotal=0;}

					$(this).next().append("<td>"+subtotal.toLocaleString('en-US', {minimumFractionDigits: 2})+"</td>");//Agrega la suma del subtotal
					if(f==4) 
						$(this).next().append("<td></td>");
					subtotal=0;	//se reinicia la suma
				}
				
				if($(this).next().attr('tipo') == 'subtotal' && $(this).next().next().attr('tipo') == 'movimientos')//Si es un campo del tipo subtotal y el siguiente elemento no es de tipo numero entonces agrega elementos
				{
					$(this).next().remove()
				}
		});	
		
		if(isNaN(total)){total=0;}
		
		$("#"+f).text(total.toLocaleString('en-US', {minimumFractionDigits: 2}));//Agrega el total al elemento
		total = 0;
	}
	//$('[tipo=subtotal]').append('<td></td>')
	$('[tipo=total]').append("<td colspan='2'></td>")
	$('.tdcontenido:nth-child(10)').remove()
	$('.tdcontenido:nth-child(10)').remove()
	$('.tdencabezado:nth-child(9)').remove()
	$('.tdencabezado:nth-child(9)').remove()

});
</script>
        		
</html>
