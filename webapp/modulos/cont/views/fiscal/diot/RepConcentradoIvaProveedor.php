<?php
 $url=str_replace('contenido','contenidoexcel',$_SERVER["REQUEST_URI"]);
?>
<!DOCTYPE html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
		<script language='javascript' src='js/pdfmail.js'></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
		<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
	<style type="text/css" >
			 .tdlink {border: 1px solid  #424242; cursor: pointer}
			 .detalle {text-decoration:none; color:#000000;}
			 .prove { height:30px !important;}
			 .total {font-weight: bold;background:#424242; }
			 .cierre {font-weight: bold;background:#424242; color:#F2FBEF;}
			 .nodato {font-size:14px; text-align: center;	height: 34px; font-weight:bold;}
			 @media print
			{
				#imprimir,#filtros,#excel, #botones
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
				#imp_rep{
					width: 100%;
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

	<?php 
$titulo1="font-size:10px;background-color:#f6f7f8;font-weight:bold;height:30px;";
$subtitulo="font-size:10px;font-weight:bold;height:30px;text-align:left;margin-left:10px;"

?>

<body>
<input type='hidden' value='Concentrado de IVA por proveedor.' id='titulo'>
<div class="container" style="width:100%">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-1">
				</div>
				<div class="col-md-10" id="imp_rep">
					<input type='hidden' value='Egresos sin control de IVA.' id='titulo'>
					<section id="tabla">
						<h3 class="nmwatitles text-center">
							Concentrado de IVA por Proveedor<br>
							<section id="botones">
								<a href="javascript:window.print();"> <img  border="0" src="../../netwarelog/design/default/impresora.png" width="20px"> </a>
								<a href="javascript:mail();"><img border="0" title="Enviar reporte por correo electrónico" src="../../../webapp/netwarelog/repolog/img/email.png"></a>
								<a id="filtros" href="index.php?c=ConcentradoIVAProveedor&f=verconcentrado" onclick=""> <img border="0" src="../../netwarelog/repolog/img/filtros.png" title="Haga click aquí para cambiar los filtros..."> </a>
								<a href="javascript:generaexcel();"><img src="images/images.jpg" title="Exportar a Excel" width="25px" height="25px"></a>
								<a href="javascript:pdf();"><img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0"></a> 

							</section>
						</h3>
						<section id='imprimible'>
							<div class="row">
								<?php
								$url = explode('/modulos',$_SERVER['REQUEST_URI']);
								if($logo == 'logo.png') $logo = 'x.png';
								$logo = str_replace(' ', '%20', $logo);
								?>
								<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:7px;text-align:right;color:gray;">
									Fecha de impresión: <label id="fech"><?php echo $fecha; ?></label>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12" style='text-align:center;font-size:18px;'>
									<b style='text-align:center;'><?php echo $empresa;?></b>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12 col-sm-12" style='background:white;color:#576370;text-align:center;font-size:12px'> 	
									<b style="font-size:15px;">Concentrado de IVA por Proveedor</b><br>
									<b id="periodo"><?php echo $periodo; ?></b>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
											<div class="table-responsive">
												<table align="center" class="busqueda table" id="datos" width="100%" cellpadding="3" style="font-size:9px;" >
													<thead>
														<tr style='<?php echo $titulo1; ?>'>
														<th>Importe Base</th>
														<th>Otros</th>
														<th>Tasa</th>
														<th>IVA Acreditable</th>
														<th>Importe Antes Retenciones</th>
														<th>IVA Retenido</th>
														<th>ISR Retenido</th>
														<th>Total Erogacion</th>
														<th>IVA Pagado no Acreditable</th>
														</tr>
													</thead>
													<tbody><?php 
													$cont=0;
													foreach ($dato as $key => $d) {
														//var_dump($taenviar);
														// print_r($taenviar[ $d['razon_social']]);
														// $ca='index.php?c=auxiliar_controlIva&f=VerReporte&fecha_ini='.$inicio.'&fecha_fin='.$fin.'&periodoAcreditamiento=1&periodo_inicio='.$p1.'&periodo_fin='.$p2.'&pinicial='.$d['idProveedor'].'&pfinal=';
														// $ca.=$d['idProveedor'].'&ejercicio='.$e.'&prov=1&noAplica=0&tasas[]='.$taenviar[ $d['razon_social']];
																	// //print_r($taenviar[$d['razon_social']]);  
														
														
														 ?>
													
														
														
														<tr  style="background-color:#edeff1;height:30px;" >
															<td colspan="3" style="text-align:center;"><b><?php echo $d['razon_social']; ?></b></td>
															<td colspan="2" style="text-align:center;"><b>RFC: <?php echo$d['rfc']; ?></b></td>
															
															<td colspan="2" style="text-align:center;"><b>CURP: <?php echo $d['curp']; ?></b></td>
															<td colspan="2" style="text-align:center;"><b><?php echo $d['tipotercero']; ?></b></td>
														</tr>
													
													<?php	foreach ($d['tasas'] as $key => $value) {
														
																if($value==0 ){//tasas sin importe
																	if($muestra==1){  ?>
																		
																		<tr >
																		<td align="right">0</td>
																		<td align="right">0</td>
																		<td align="right"><?php echo $key; ?></td>
																		<td align="right">0</td>
																		<td align="right">0</td>
																		<td align="right">0</td>
																		<td align="right">0</td>
																		<td align="right">0</td>
																		<td align="right">0</td>
																		</tr>
																	
												    <?php 	    	}
																}else{
																if($value['tasa']=='Otra Tasa 1' || $value['tasa']=='Otra Tasa 2'){
																	$tasa=$value['tasa'].'('.$value['valor'].'%)';
																}else{
																	$tasa=$value['tasa'];
																} ?>
										               
											
														<tr class="prove" style="font-size:9px;">
															 <td align="right"><?php echo number_format($value['importeBase'],2,'.',',');?></td>
															 <td align="right"><?php echo number_format($value['otraserogaciones'],2,'.',',');?></td>
															 <td align="right"><?php echo $tasa;?></td>
															 <td align="right"><?php echo number_format($value['acredita'],2,'.',',');?></td>
															 <td align="right"><?php echo number_format($value['importeBase']+$value['otraserogaciones']+$value['acredita'],2,'.',',');?></td>
															 <td align="right"><?php echo number_format($value['ivaRetenido'],2,'.',',');?></td>
															 <td align="right"><?php echo number_format($value['isrRetenido'],2,'.',',');?></td>
															 <td align="right"><?php echo number_format($value['totalerogacion'],2,'.',',');?></td>
															 <td align="right"><?php echo number_format($value['ivaPagadoNoAcreditable'],2,'.',',');?></td>
															 </tr>
												<?php		}//else
														if($muestra==1){
															if($value['tasa']=='Otra Tasa 1'){
																$o1='';
															}else{
																$o1= '
																<tr style="">
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">Otra Tasa 1</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																</tr>';
															}
															if($value['tasa']=='Otra Tasa 2'){
																$o2='';
															}else{
																$o2='
																<tr style="">
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">Otra Tasa 2</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																	<td align="right">0</td>
																</tr>';
															}
															
														}//else{ $o1=""; $o2="";}
													}		
															echo @$o1.@$o2;	
										// 					
															
										// 										
															foreach ($d['suma'] as $key => $d3) {
															if($d3!=''){ 
																?>
															
																 <tr style="font-size:9px;font-weight:bold;text-align:right;background-color:#edeff1;height:30px;">
																<?php 
																// $ca='index.php?c=auxiliar_controlIva&f=VerReporte&fecha_ini='.$inicio.'&fecha_fin='.$fin.'&periodoAcreditamiento=1&periodo_inicio='.$p1.'&periodo_fin='.$p2.'&pinicial='.$d['idProveedor'].'&pfinal=';
														// $ca.=$d['idProveedor'].'&ejercicio='.$e.'&prov=1&noAplica=0&tasas[]='.$taenviar[ $d['razon_social']];
														if(!isset($inicio)){
															$inicio=0;
															$fin=0;
															
														}if(!isset($p1)){
															$p1=0;
															$p2=0;
														}
														$t=($taenviar[ $d['razon_social'] ]);
														
																 echo "<td  align='right' style='cursor:pointer;color:blue;text-decoration:underline;' onclick='javascript:manda(".$inicio.",".$fin.",".$p1.",".$p2.",".$d['idProveedor'].",".$e.",".$periodoAcreditamiento.",".json_encode($t).")'>".number_format($d3["importeBase"],2,'.',',')."</td>";
																 ?>
																 <td align="right"><?php echo number_format($d3['otraserogaciones'],2,'.',',');?></td>
																 <td></td>
																 <td align="right"><?php echo number_format($d3['acredita'],2,'.',',');?></td>
																 <td align="right"><?php echo number_format($d3['importeBase']+$d3['otraserogaciones']+$d3['acredita'],2,'.',',');?></td>
																 <td align="right"><?php echo number_format($d3['ivaRetenido'],2,'.',',');?></td>
																 <td align="right"><?php echo number_format($d3['isrRetenido'],2,'.',',');?></td>
																 <td align="right"><?php echo number_format($d3['totalerogacion'],2,'.',',');?></td>
																 <td align="right"><?php echo number_format($d3['ivaPagadoNoAcreditable'],2,'.',',');?></td>
														</tr>
														<tr>
																 <td colspan="9">
																 
																 </td>
																 </tr>
													<?php				}
														}
										 					 
														
														$cont++;	
															
													}
													?>

													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
						</section>
					</section>
					<section id="r">
					</section>
				</div>
			</div>
		</div>
	</div>
</div>

<!--GENERA PDF*************************************************-->
<div id="divpanelpdf" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Generar PDF</h4>
            </div>
            <form id="formpdf" action="libraries/pdf/examples/generaPDF.php" method="post" target="_blank" onsubmit="generar_pdf()">
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
					<input type='hidden' name='tipoDocu' value='hg'>
					<input type='hidden' value='<?php echo "http://".$_SERVER['SERVER_NAME'].$url[0]."/netwarelog/archivos/1/organizaciones/$logo"; ?>' name='logo' />
					<input type='hidden' name='nombreDocu' value='Concentrado de iva por proveedor'>
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
			<div id="loading" style="position: absolute; top:30%; left: 50%;display:none;">
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
	</body>
	<script>
	function generaexcel()
			{
				$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': $('#titulo').val()});
			}
		function manda(fecha_ini,fecha_fin,periodo_inicio,periodo_fin,pinicial,ejercicio,periodoAcreditamiento,tasas){
			$.post('ajax.php?c=auxiliar_controlIva&f=VerReporte',
			{fecha_ini:fecha_ini,
			 fecha_fin:fecha_fin,
			 periodo_inicio:periodo_inicio,
			 periodo_fin:periodo_fin,
			 pinicial:pinicial,
			 pfinal:pinicial,
			 ejercicio:ejercicio,
			 porProv:'1',
			 noAplica:'0',
			 tasas:tasas,
			 prov:'algunos',
			 periodoAcreditamiento:periodoAcreditamiento
			},function(respues){
			  $("#tabla").hide();
			  $("#r").show()
			  $("#r").html('<br><input type="button" style="margin:0 auto;color: red;border-bottom-color: blue;" onclick="javascript:regre();" value="Regresar">');

              $("#r").append(respues);
			});
		}
		function regre(){
			$("#tabla").show();
			 $("#r").hide();
		}
	</script>

	</html>