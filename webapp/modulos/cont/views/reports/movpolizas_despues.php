<html lang="sp" style="overflow-x: hidden !important;">
	<head>
	  <!--LINK href="../../../webapp/netwarelog/utilerias/css_repolog/estilo-1.css" title="estilo" rel="stylesheet" type="text/css" / -->
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<link rel="stylesheet" href="css/imprimir_bootstrap.css" type="text/css">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<!--TITULO VENTANA-->
		<title>Movimientos de Polizas</title>

	  <!--PLUG IN CATALOG-->
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	  <script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
		<script src="js/modalmovpolizas.js" type="text/javascript"></script>
	  <script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
		<script src="../../libraries/jquery.tablesorter.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	  <script type="text/javascript">
			$(document).ready(function () {
				$("#tabla_reporte").tablesorter();
		  	$('table.bh tbody tr').append("<td><a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a></td>");
			});
		</script>
		<style type="text/css">
		.aa > .theader-displayer:after {
			content: "";
			height: 0;
			width: 0;
			display: block;
			position: absolute;
			bottom: .8em;
			right: .1em;
			border-bottom: 3px solid black;
			border-top: 3px solid transparent;
			border-left: 3px solid transparent;
			border-right: 3px solid transparent;
		}
		.aa > .theader-displayer:before {
			content: "";
			height: 0;
			width: 0;
			display: block;
			position: absolute;
			bottom: .2em;
			right: .1em;
			border-bottom: 3px solid transparent;
			border-top: 3px solid black;
			border-left: 3px solid transparent;
			border-right: 3px solid transparent;
		}
		th {
			position: relative;
			cursor: pointer;
		}

		tr.aa{
			z-index: 100;
			border-bottom: 1px solid #000 !important;
		}
		th, tr {
			text-align: center;
		}
		th, td {
			padding: .5em;
		}
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
		    display: block;
			}
		}

		.btnMenu
		{
		  border-radius: 0;
			width: 100%;
			margin-bottom: 0.3em;
			margin-top: 0.3em;
		}

		.row
		{
		  margin-top: 0.5em !important;
		}

		.modal-title{
			background-color: unset !important;
			padding: unset !important;
		}

		.nmwatitles, [id="title"] {
		  padding: 8px 0 3px !important;
		 	background-color: unset !important;
		}

		.select2-container
		{
		  width: 100% !important;
		}
		.select2-container .select2-choice
		{
		  background-image: unset !important;
		 	height: 31px !important;
		}
		.twitter-typeahead{
			width: 100% !important;
		}
		.hidden {
		  display: hidden;
		}
		</style>
	</head>

	<body>
	<?php 
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
								<input type='hidden' name='nombreDocu' value='movimientos_cuentas'>
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
					<div id="divmsg" accesskey=""style="opacity:0.8; position:relative; background-color:#000; color:white; padding: 20px; -webkit-border-radius: 20px; border-radius: 10px; left:-50%; top:-30%;">
						<center>
							<img src='../../../webapp/netwarelog/repolog/img/loading-black.gif' width='50'>
							<br>Cargando...
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
						Reporte de Movimientos de Polizas<br>
						<section id="botones">
  						<a href="javascript:window.print();">
								<img src="../../../webapp/netwarelog/repolog/img/impresora.png" border="0">
							</a>
  	          <a href="index.php?c=Reports&f=movpolizas">
								<img src="../../../webapp/netwarelog/repolog/img/filtros.png" title ="Haga clic aqui para cambiar filtros..." border="0">
							</a>
  						<a href='javascript:generaexcel()' id='excel'>
								<img src='images/images.jpg' width='35px'>
							</a>
  						<a href="javascript:mail();">
								<img src="../../../webapp/netwarelog/repolog/img/email.png" title ="Enviar reporte por correo electrónico" border="0">
							</a>
  						<a href="javascript:pdf();">
								<img src="../../../webapp/netwarelog/repolog/img/pdf.gif" title ="Generar reporte en PDF" border="0">
							</a>
						</section>
					</h3>
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<form id="reporte" name="reporte"  method="post" action="redirecciona.php">
								<input type='hidden' value='Movimiento de Polizas y facturas.' id='titulo'>
								<section id='idcontenido_reporte'>
									<div class="row">
										<div class="col-md-6 col-sm-6 col-md-offset-6 col-sm-offset-6" style="font-size:10px;text-align:right;color:gray;">
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
											<b style='font-size:16px;'>Movimientos de Polizas</b><br>
											Del <b><?php echo $fecha_antes;?> </b>Al <b> <?php echo $fecha_despues;?></b>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
													<div class="table-responsive">
														<!-- TABLA REPORTE -->
														<table style="text-align:center; width:100%;" id="tabla_reporte" class="reporte table table-striped" border="0" align="center" onchange="getImporte(<?php echo($info->mov_id);?>)">
															<thead>
																<tr class="table-header aa">
																	<th class="theader-displayer" width='10%'>Número Poliza</th>
																	<th class="theader-displayer" width='10%'>Tipo de poliza</th>
																	<th class="theader-displayer" width='5%'>Id Periodo</th>
																	<th class="theader-displayer" width='10%'>Concepto</th>
																	<th class="theader-displayer" width='10%'>Fecha</th>
																	<th class="theader-displayer" width='10%'>Facturas Asociadas</th>
																	<th class="theader-displayer" width='10%'>UUID</th>
																	<th class="theader-displayer" width='10%'>Folio</th>
																	<th width='5%'>Serie</th>
																	<th class="theader-displayer" width='10%'>Total de Poliza</th>
																	<th width='10%'>Total XML's</th>
																</tr>
															</thead>

											        <!--Separador**|**-->
															<?php
															while($info = $datos->fetch_object()){
																//Inicializamos / Reiniciamos los valores
																$xml = [];
																$uuid = "-";
																$folio = "-";
																$serie = "-";
																$totalxml = "-";
																$contieneMultipleFactura = false;
																$importe = 0;
																//Validamos que no tenga multiples facturas
                                if ($info->MultipleFacturas == "0") {
																	//Si no tiene factura, le decimos al cliente que no hay factura.
                                  if ($info->mov_factura == "-" || $info->mov_factura == "") {
                                  	$nombre_xml = "Este registro no tiene facturas asociadas.";
																		$uuid = "-";
																		$folio = "-";
																		$serie = "-";
																		$totalxml = "-";
                                  }
																	//Si tiene factura se asignara el nombre del archivo
																	else {
																		$nombre_xml = $info->mov_factura;
																		if (!empty($nombre_xml)) {
																			$arr_nombre = explode("_", $nombre_xml);
																			$uuid = preg_replace('/\.[^.]+$/','',$arr_nombre[2]);
																			if (empty($uuid)) {
																				$uuid = "-";
																			}
																		} else {
																			$nombre_xml = "-";
																		}

																		//Buscamos datos por el uuid de la factura
																		if ($uuid != "-") {
																			$datos_factura = $this->obtener_datos_factura($uuid);
																			
																			$datos_factura = $datos_factura->fetch_assoc();
																			$folio = $datos_factura['folio'];
																			if (empty($datos_factura['folio'])) {
																				$folio = "-";
																			}

																			$serie = $datos_factura['serie'];
																			if (empty($datos_factura['serie'])) {
																				$serie = "-";
																			}

																			$importe = $datos_factura['importe'];
																			if (empty($datos_factura['importe'])) {
																				$importe = "-";
																			}
																		}

																		//Si no tiene multiples facturas, $contieneMultipleFactura se iguala a falso
																		$contieneMultipleFactura = false;
                                  }

																//Si tiene multiples facturas
																} else if($info->MultipleFacturas == "1") {
                                  $info->MultipleFacturas = "
                                  <a href='javascript:modalFacturas($info->mov_id)' class='btn btn-info btn-block btn-sm' role='button'>
                                  Ver Facturas
                                  </a>";
																	//Si tiene multiples facturas, $contieneMultipleFactura se iguala a verdadero
																	$contieneMultipleFactura = true;
																	$importe = $this->importes($info->pol_id,$info->num_mov);
																	$totalxml = "$ ".number_format($importe, 2);
                                }

																//Mientras el movimiento actual sea diferente al anterior
                                if ($info->mov_id != $anterior) {
                                  echo("
                                    <tr cl=".$info->mov_id.">
	                                    <td><a target='_blank' href='javascript:verpoliza(".$info->pol_id.")'>".$info->NumPol."</a></td>
	                                    <td>".$info->titulo."</td>
	                                    <td>".$info->idperiodo."</td>
	                                    <td>".$info->concepto."</td>
	                                    <td>".$info->fecha."</td>
																	");

																	//Si contiene multiples facturas el boton de ver facturas se expande 3 columnas para mostrar el desglose de las facturas
																	if($contieneMultipleFactura){
																		echo ("
																			<td colspan='4'>".$info->MultipleFacturas."</td>
																		");
																	//Si no contiene multiples facturas el boton de ver facturas no se muestra
																	} else {
																		echo("
																			<td>".$nombre_xml."</td>
																			<td>".$uuid."</td>
																			<td>".$folio."</td>
																			<td>".$serie."</td>
																		");
																	}
																	echo("
																			<td>$ ".number_format($info->Importe,2)."</td>
																			<td>$ ".number_format($importe, 2)."</td>
                                    </tr>
                                  ");
																	$totalxmls += $importe;
																	$totalpolizas += $info->Importe;
                                  $anterior = $info->mov_id;
                                }
															}
															?>
															<tfoot>
																<tr stlye="background: #ddd !important;">
																	<td colspan="7"></td>
																	<td colspan="2"><b>Total Polizas:</b><br>$
																		<?php echo(number_format($totalpolizas,2));?>
																	</td>
																	<td colspan="2"><b>Total XML's:</b><br>$
																		<?php echo (number_format($totalxmls,2));?>
																	</td>
																</tr>
															</tfoot>
									          </table> <!-- Tabla -->
													</div> <!-- // table responsive -->
												</div> <!-- // col -->
											</div> <!-- // row -->
										</div> <!-- // col -->
									</div> <!-- // row -->
								</section> <!-- // Contenido reporte -->
							</form> <!-- // Reporte -->
						</div> <!-- // col 2 -->
					</div> <!-- // row 2 -->
				</div> <!-- // main col -->
			</div> <!-- // main row -->
		</div> <!-- // container -->

    <!-- Modal para mostrar cuando un registro tiene facturas multiples -->
    <!-- El ajax esta en este mismo archivo tiene el nombre de modalFacturas -->
    <div class="modal fade" tabindex="-1" role="dialog" id="modalFacturas">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <!-- Se rellena con ajax -->
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

  </body>
</html>
