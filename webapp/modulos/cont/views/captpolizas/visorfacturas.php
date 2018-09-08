<link rel="stylesheet" href="css/visor_facturas.css">
<div class="main-wrapper container">
	<!-- Header -->
	<section id="header">
		<div class="row">
			<div class="col-md-12 main_title_container">
				<h2>Factura Electrónica (CFDI)</h2>
			</div>
		</div>
		<div class="row">
			<!-- Lado Izquierdo Header -->
			<div class="col-md-3" style="padding-right:0;padding-left:0;">
				<div class="row">
					<!-- Emisor -->
					<div class="col-md-12 contenedor">
						<div class="contenedor-titulo">
							<h5 class="titulo">Emisor</h5>
						</div>
						<ul id="emisor">
							<?php 
							$emisor = $json['Comprobante']['cfdi:Emisor'];
							foreach ($emisor as $key => $value) {
								//echo($key.": ".$value."<br>");
								if (is_array($value)) {
									foreach ($value as $campo => $valor) {
										if ($key != 'cfdi:RegimenFiscal' && $key != 'cfdi:ExpedidoEn') {
											$campo = str_replace("@","",strtoupper($campo));
											$valor = strtoupper($valor);
											echo("<li><b>$campo</b>: $valor");
										}
									}
								} else {
									if ($key != '@RegimenFiscal') {
										$key = str_replace("@","",strtoupper($key));
										$value = strtoupper($value);
										echo("<li><b>$key:</b>$value</li>");
									}
								}
							} ?>
						</ul>
					</div>
				</div>
			</div>
			<!-- // Lado izquierdo header -->
			<!-- Centro -->
			<div class="col-md-3 col-md-offset-1">
				<div class="row">
					<!-- Receptor -->
					<div class="col-md-12 contenedor">
						<div class="contenedor-titulo">
							<h5 class="titulo">Receptor</h5>
						</div>
						<ul id="receptor">
						<?php 
						$receptor = $json['Comprobante']['cfdi:Receptor'];
						foreach ($receptor as $key => $value) {
							if (is_array($value)) {
								foreach ($value as $campo => $valor) {
									if ($key != 'cfdi:RegimenFiscal') {
										$campo = str_replace("@","",strtoupper($campo));
										$valor = strtoupper($valor);
										echo("<li><b>$campo</b>: $valor");
									}
								}
							} else {
								$key = str_replace("@","",strtoupper($key));
								$value = strtoupper($value);
								echo("<li><b>$key:</b> $value</li>");
							}
						} ?>
						</ul>
					</div>
					<!-- // Receptor -->
				</div>
			</div>
			<!-- // Centro -->
			<!-- Lado Derecho header -->
			<div class="col-md-4 col-md-offset-1" style="padding-right:0;padding-left:0;">
				<div class="row">
					<!-- Fecha -->
					<div id="container_fecha" class="col-md-6 contenedor">
						<div class="contenedor-titulo">
							<h5 class="titulo">Fecha</h5>
						</div>
						<p id="fecha"><?php echo($factura['fecha'])?></p>
					</div>
					<!-- Serie y Folio -->
					<div id="container_serie_folio" class="col-md-5 col-md-offset-1 contenedor">
						<div class="contenedor-titulo">
							<h5 class="titulo">Factura</h5>
						</div>
						<p id="serie_folio">
						<?php if (empty($factura['serie']) && empty($factura['folio'])) {
							echo("-");
						} else {
							echo($factura['serie']." ".$factura['folio']);
						}?>
						</p>
					</div>
				</div>
				<!-- // fecha, serie y folio -->
				<!-- uuid -->
				<div class="row">
					<div class="col-md-12 contenedor" id="container_uuid">
						<div class="contenedor-titulo">
							<h5 class="titulo">Folio Fiscal</h5>
						</div>
						<p id="uuid"><?php echo($factura['uuid'])?></p>
					</div>
				</div>
				<!-- // uuid -->
				<div class="row">
					<!-- Regimen Fiscal -->
					<div id="container_regimen" class="col-md-12 contenedor">
						<div class="contenedor-titulo">
							<h5 class="titulo">Regimen Fiscal</h5>
						</div>
						<p id="regimen">
							<?php
							if (!empty($json['Comprobante']['cfdi:Emisor']['cfdi:RegimenFiscal'])) {
								$regimen = $json['Comprobante']['cfdi:Emisor']['cfdi:RegimenFiscal']['@Regimen'];
							} else {
								$regimen = $json['Comprobante']['cfdi:Emisor']['@RegimenFiscal'];
							}
							$regimen = strtoupper($regimen);
							echo($regimen);
							?>
						</p>
					</div>
				</div>
			</div>
			<!-- // Lado Derecho header -->
		</div>
	</section>
	<!-- // Header -->

	<!-- Productos -->
	<section id="container_productos">
		<table class="table table-responsive table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th>Cantidad</th>
					<th>Unidad</th>
					<th>Descripción</th>
					<th>Valor Unitario</th>
					<th>Importe</th>
				</tr>				
			</thead>
			<tbody>
				<?php 
				if (!is_array($json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'][0])) {
					$conceptos = $json['Comprobante']['cfdi:Conceptos'];
				} else {
					$conceptos = $json['Comprobante']['cfdi:Conceptos']['cfdi:Concepto'];	
				}

				foreach ($conceptos as $index => $array) {
					echo("<tr>");
					foreach ($array as $concepto => $valor) {
						//echo($concepto.": ".$valor."<br>");
						if ($concepto == "@valorUnitario" || $concepto == "@ValorUnitario" || $concepto == "@importe" || $concepto == '@Importe') {
							$valor = $this->stringFormatMoney($valor);
							echo("<td style='text-align:right;'>$valor</td>");
						} else if($concepto == "@noIdentificacion" || $concepto == "@NoIdentificacion" || $concepto == "cfdi:ComplementoConcepto" || $concepto == "cfdi:Impuestos" || $concepto == "@ClaveUnidad"){
							#No imprimir
						} else if($concepto == "@ClaveProdServ"){
							#No Imprimir parte 2
						} else if($concepto == "@descripcion" || $concepto == "@Descripcion") {
							echo("<td style='text-align:left;'>$valor</td>");
						}
						else {
							echo("<td>$valor</td>");
						}
					}
					echo("</tr>");
				} ?>
			</tbody>
			<tfoot>
				<?php
				$impuestos = $json['Comprobante']['cfdi:Impuestos'];
				foreach ($impuestos as $campo => $valor) {
					echo("<tr class='grey20'>");
					if (!is_array($valor)) {
						$campo = str_replace("@","",$campo);
						if ($campo == 'totalImpuestosRetenidos') {
							$campo = "Retenidos";
						} else if($campo == 'totalImpuestosTrasladados'){
							$campo = "Trasladados";
						}
						echo("<td colspan='3'></td>");
						$valor = $this->stringFormatMoney($valor);
						echo("<td><b>$campo: </b></td><td>$valor</td>");
					}
					echo("</tr>");
				}
				if (isset($descuento)) {
					echo("<tr class='grey20'><td colspan='3'></td>");
					$descuento = $this->stringFormatMoney($descuento);
					echo"<td><b>Descuento: </b></td><td>$descuento</td></tr>";
				}
				?>
				<tr class="grey20">
					<td colspan="3"></td>
					<td><b>Subtotal:</b></td><td><?php echo($this->stringFormatMoney($subtotal));?></td>
				</tr>
				<tr class="grey30">
					<td colspan="3"></td>
					<td><b>Total:</b></td><td><?php echo($this->stringFormatMoney($total));?></td>
				</tr>
				<tr class="grey40">
					<td colspan='5' id="total_letra"><b><?php echo($this->cantidadLetra($total));?></b></td>
				</tr>
			</tfoot>
		</table>
	</section>

	<!-- Nomina -->
	<section id="container_nomina" class="hidden">
		<h3>Datos Nomina</h2>
	</section>

	<!-- Cadena original -->
	<section id="container_cadena_original">
		<div class="row">
			<div class="col-md-12 contenedor">
				<div class="contenedor-titulo">
					<h5 class="titulo">Cadena Original del complemento de certificación digital del SAT</h5>
				</div>
				<p id="cadena_original"><?php echo($coccd); ?></p>
			</div>
		</div>
	</section>

	<!-- Sello emisor -->
	<section id="container_sello_digital">
		<div class="row">
			<div class="col-md-12 contenedor">
				<div class="contenedor-titulo">
					<h5 class="titulo">Sello digital del emisor</h5>
				</div>
				<p id="sello_emisor"><?php echo($selloEmisor); ?></p>
			</div>
		</div>
	</section>

	<!-- Sello SAT -->
	<section id="container_sello_sat">
		<div class="row">
			<div class="col-md-12 contenedor">
				<div class="contenedor-titulo">
					<h5 class="titulo">Sello Digital del SAT</h5>
				</div>
				<p id="sello_sat"><?php echo($selloSAT); ?></p>
			</div>
		</div>
	</section>

		<!-- QR -->
	<section id="container_sello_sat">
		<div class="row">
			<div class="col-md-12 contenedor">
				<div class="contenedor-titulo">
					<h5 class="titulo">Datos Generales</h5>
				</div>
				<ul id="datos_generales">
					<li><b>Fecha y hora de emisión: </b><?php echo($this->dateFormatString($json['Comprobante']['@fecha'])); ?></li>
					<li><b>Fecha y hora de certificación: </b><?php echo($this->dateFormatString($timbreFiscal['FechaTimbrado'])); ?></li>
					<li><b>Certificado emisor: </b><?php echo($certificado_emisor);?></li>
					<li><b>Certificado SAT: </b><?php echo($certificado_SAT);?></li>
					<li><b>Tipo de Comprobante: </b><?php echo($tipo_comprobante);?></li>
					<li><b>Metodo de pago: </b><?php echo($metodo_pago);?></li>
					<li><b>Forma de Pago: </b><?php echo strtoupper($forma_pago);?></li>
					<li><b>Moneda: </b><?php echo($moneda);?></li>
				</ul>
			</div>
		</div>
	</section>
</div>
