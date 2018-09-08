<style>
	.verticalLine {
    	border-left: thick solid #000000;
	}
	.verticalLine2 {
    	border-left: thick solid #909090 ;
	}
	.horizontallLine {
    	border-bottom: thick solid #909090 ;
	}
	.horizontallLine2 {
    	border-bottom: thick solid #000000 ;
	}
	.centrado{
    	text-align:center;
  	}
  	.cursivo{
    	font-style: italic;
  	}
  	.leftT{
    	text-align:left;
  	}
  	.rightT{
    	text-align:right;
  	}
  	.texto-vertical-2 {
	    writing-mode: vertical-lr;
	    transform: rotate(180deg);
	    padding-left: 120px;
	}
	.min8{
    min-width: 400px;
	}
	.min{
    min-width: 600px;
	}
	.min2{
    min-width: 10px;
	}
	.minH2{
    min-height: 10px;
	}
	.max{
    max-height: 200px;
	}
	.max2{
    max-width: 200px;
	}
	.max3{
    max-width: 400px;
	}
	.max4{
    max-width: 500px;
	}
	.bord{
		border:1px solid black;
	}
	.back{
    	background-color: #FFFFFF;
	}
</style>
<?php
// Valida que existan utilidades
	if (empty($utilidades)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se detecto informacion *</span></h3>
		</div><?php
		
		return 0;
	} ?>

<div class="row">
	<div class="col-xs-12">
		<input id="yyyy" type="hidden" value="<?php echo $year; ?>">
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse1"> 
							<i class="fa fa-line-chart" ></i><strong> Graficas</strong>
						</a>
					</h4>
				</div>
				<div id="collapse1" class="panel-collapse collapse in">
					<div class="panel-body">
						<div class="col-sm-12" id="graficas" style="overflow:auto">
							<!--
							<div class = " centrado cursivo" style="float:left; width:440px; height: 60px;"><h3>Mix Real</h3>
							</div> <br><br><br>
							-->
							<div id="grafica_productos_detalle_dona" class="col-xs-6 max4 min8" aling = "center" style="height: 300px; width: 40%; position: relative;">
								
								 <div style="position: absolute; right: 170px; bottom: -30px;"> <h3 class="cursivo centrado">Mix Real</h3></div>
							</div>

							<div id="grafica_productos_detalle_lineal" class="col-xs-6 min max4 " style="height: 40% width: 40%;">
								<div class="col-xs-12">
									<div class="col-xs-4" style="float:left; width:70px; height: 200px;">
										<div style="float:left; width:90px; height: 60px;"></div>
										<div class="rightT">
											Alta
										</div>
										<div style="float:left; width:90px; height: 20px;"></div>
										<div class="texto-vertical-2 ">
											<h3>Popularidad</h3>
										</div>
										<div style="float:left; width:90px; height: 20px;"></div>
										<div class="rightT">
											Baja
										</div>
									</div>
									<div class="col-xs-9 max3">
										<div class="col-xs-12 centrado cursivo">
											<h3>Matriz de ingeniería de menús</h3>
										</div>
										<div class="col-xs-6 verticalLine horizontallLine min2 minH2 max2">
											<h3> <?php echo $mixG['cab']; ?> <img height="100" width="100" src="../../modulos/restaurantes/images/caballobatalla.png"></h3>
										</div>
										<div class="col-xs-6 verticalLine2 horizontallLine min2 minH2 max2">
											<h3> <?php echo $mixG['est']; ?> <img height="100" width="100" src="../../modulos/restaurantes/images/estrella.png"></h3>
										</div>
										<div class="col-xs-6 verticalLine horizontallLine2 min2 minH2 max2">
											<h3> <?php echo $mixG['per']; ?> <img height="100" width="100" src="../../modulos/restaurantes/images/perro.png"></h3>
										</div>
										<div class="col-xs-6 verticalLine2 horizontallLine2 min2 minH2 max2">
											<h3> <?php echo $mixG['rom']; ?> <img height="100" width="100" src="../../modulos/restaurantes/images/rompecabezas.png"></h3>
										</div>
										<div class="col-xs-12 centrado">
											<div class="col-xs-3 leftT">Baja</div>
											<div class="col-xs-6"><h3>Rentabilidad</h3></div>
											<div class="col-xs-3 rightT">Alta</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
    			</div>
 			</div>
		</div>
	</div>
</div>
<div class="container panel-body">
	<div class="col-sm-12 panel-body" style="overflow:auto">
		<table id="tabla_utilidades" class="table table-striped table-bordered back" style="overflow:auto" cellspacing="0" width="70%" height="70%">
			<thead>
				<tr>
					<td style="display: none;"></td>
					<td></td>
					<td></td>
					<td></td>
					<td  align="center"> <b>Promedio Ganacia Bruta</b><br> $<?php echo number_format($promGB / $cont , 2) ?></td>
					<td  align="center"> <b>Promedio #Ventas</b><br><?php echo number_format($promV  / $cont, 2) ?></td>
					<td  align="center"> <b>Total Ganancia Bruta</b><br> $<?php echo number_format($sumGB,2) ?></td>
					<td  align="center"> <b>MIX REAL</b><br><?php echo number_format($mixT) ?>%</td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td style="display: none;"><strong>Popularidad</strong></td>		
					<td style="font-size: 14px;"  ><strong>Producto</strong></td>		
					<td style="font-size: 14px;"  align="center"><strong>Costo</strong></td>
					<td style="font-size: 14px;"  align="center"><strong>Precio Venta</strong></td>
					<td style="font-size: 14px;" ><strong>Ganacia Bruta</strong></td>
					<td style="font-size: 14px;" ><strong>Numero de Ventas</strong></td>
					<td style="font-size: 14px;" ><strong>Ganacia Bruta Total</strong></td>
					<td style="font-size: 14px;" ><strong>Mix Real</strong></td>
					<td style="font-size: 14px;" ><strong>Rentabilidad</strong></td>
					<td style="font-size: 14px;" ><strong>Popularidad</strong></td>
					<td style="font-size: 14px;" ><strong>Calificacion</strong></td>
				</tr>
			</thead>
			<tbody><?php
					$promGB = $promV = $sumGB = $cont = $mixT =0;
			// $utilidades es un array que viene desde el controlador
				foreach ($utilidades as $key => $value) {
					$promGB += ($value['ganancia'] / $value['ventas']); // Promedio ganacia bruta
					$promV  += $value['ventas']; 						// Promedio Ventas
					$sumGB  += $value['ganancia']; 						// Total Ganacia Bruta
					$cont ++;
				}

				foreach ($utilidades as $key => $value) {
					$total += $value['ganancia'];  

					$utilidadU = $value['ganancia'] / $value['ventas'];
					$mix = number_format(($value['ganancia'] / $sumGB) * 100,2);
					$mixT += $mix; 

					$rent = $pop = $cal ='';

					$rent = ($utilidadU > ($promGB / $cont)) ? 'ALTA' : 'BAJA';
					$pop = ($value['ventas'] > ($promV  / $cont )) ? 'ALTA' : 'BAJA';

					if ($rent == 'ALTA' and $pop == 'ALTA'){ $cal = 'ESTRELLA'; }
					if ($rent == 'ALTA' and $pop == 'BAJA'){ $cal = 'ROMPECABEZAS'; }
					if ($rent == 'BAJA' and $pop == 'ALTA'){ $cal = 'CABALLO'; }
					if ($rent == 'BAJA' and $pop == 'BAJA'){ $cal = 'PERRO'; } ?>
					
					<tr>
						<td style="display: none;"><?php echo $value['rate'] ?></td>
						<td style="font-size: 14px;"><?php echo $value['nombre'] ?></td>
						<td style="font-size: 14px;" align="center">$<?php echo number_format($value['costo'] ,2)?></td>				
						<td style="font-size: 14px;" align="center">$<?php echo number_format($value['precio'] ,2)?></td>	
						<td style="font-size: 14px;" align="center">$<?php echo number_format($utilidadU ,2)?></td>
						<td style="font-size: 14px;" align="center"><?php echo number_format($value['ventas'],2) ?></td>
						<td style="font-size: 14px;" align="center">$<?php echo number_format($value['ganancia'],2) ?></td>
						<td style="font-size: 14px;" align="center"><?php echo $mix ?>%</td>
						<td style="font-size: 14px;" align="center"><?php echo $rent ?></td>
						<td style="font-size: 14px;" align="center"><?php echo $pop ?></td>
						<td style="font-size: 14px;" align="center"><?php echo $cal ?></td>	
					</tr> <?php
				} ?>
			</tbody><br>
		</table>
	</div>					
</div>

<div><br><br><br>
	<p>.</p>
</div>

<script>
	
	$(document).ready(function() {
		var yyyy = $("#yyyy").val();
		$("#ano").val(2016).prop('selected', 'selected');
		//$('#ano').html('');
		//$('#ano').append('<option selected="selected" value="'+yyyy+'">'+yyyy+'</option>');
		for (var i = 0; i <= 4; i++) {
			yyyy = yyyy -1;
			$('#ano').append('<option value="'+yyyy+'">'+yyyy+'</option>');
		};


	});
	$("#total").html('<h4>Total: <strong>$'+<?php echo $total ?>+'</strong><h4>');
	
	comandas.graficar({
		div:'grafica_productos_detalle', 
		x:'fecha', 
		y:'ventas', 
		label:'Ventas', 
		dona:<?php echo json_encode($dona) ?>, 
	});
</script>