<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<style type="text/css">
		.cuerpo_r{width: 420px; height: 380px;  padding: 7px; border: 0px solid; font-family: arial;}
		
	</style>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/movimientos_aux_base_gravable.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
</head>
<body>
	
	<div class="cuerpo_r" style="width:100% !important;">
		<div class="nmwatitles">Movimientos auxiliares por base gravable</div>
		<div class="fondo">
			<table width="100%" height="93%" class="tabla_filtro">
				<tr>
					<td width="50%"> 
						<div ><input type="checkbox" id="considera_per" value="1" checked>Considerar periodo de causacion</div>
					</td>
					<td width="50%">
					</td>
				</tr>
				<tr>
					<td>
						<div>Ejercicio</div>
						<div class="filtro_select">
							<select id="sel_ejercicio" class="nminputselect">
									<?php
									$res=$ejercicio->fetch_object();
									echo "<option id='ej_".$res->id."' value='".$res->id."' selected>".$res->NombreEjercicio."</option>";
									while($res=$ejercicio->fetch_object()){
										echo "<option id='ej_".$res->id."' value='".$res->id."'>".$res->NombreEjercicio."</option>";
									}
									?>
								</select>
						</div>
					</td>
					<td>

					</td>
				</tr>
				<tr>
					<td>
						<div>Periodo inicial</div>
						<div class="filtro_select">
							<select id="per_ini" class="nminputselect">
								<option id="per_ini_1" value="1" selected>Enero</option>
								<option id="per_ini_2" value="2">Febrero</option>
								<option id="per_ini_3" value="3">Marzo</option>
								<option id="per_ini_4" value="4">Abril</option>
								<option id="per_ini_5" value="5">Mayo</option>
								<option id="per_ini_6" value="6">Junio</option>
								<option id="per_ini_7" value="7">Julio</option>
								<option id="per_ini_8" value="8">Agosto</option>
								<option id="per_ini_9" value="9">Septiembre</option>
								<option id="per_ini_10" value="10">Octubre</option>
								<option id="per_ini_11" value="11">Noviembre</option>
								<option id="per_ini_12" value="12">Diciembre</option>
							</select>
						</div>
					</td>
					<td>
						<div>Periodo final</div>
						<div class="filtro_select">
							<select id="per_fin" class="nminputselect">
								<option id="per_fin_1" value="1" selected>Enero</option>
								<option id="per_fin_2" value="2">Febrero</option>
								<option id="per_fin_3" value="3">Marzo</option>
								<option id="per_fin_4" value="4">Abril</option>
								<option id="per_fin_5" value="5">Mayo</option>
								<option id="per_fin_6" value="6">Junio</option>
								<option id="per_fin_7" value="7">Julio</option>
								<option id="per_fin_8" value="8">Agosto</option>
								<option id="per_fin_9" value="9">Septiembre</option>
								<option id="per_fin_10" value="10">Octubre</option>
								<option id="per_fin_11" value="11">Noviembre</option>
								<option id="per_fin_12" value="12">Diciembre</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div>Movimientos del</div>
						<div class="filtro_text">
							<input type="date" class="nminputtext" id="fecha_ini" style="width:82% !important;" placeholder="aaaa-mm-dd" disabled>
						</div>
					</td>
					<td>
						<div>Al</div>
						<div class="filtro_text">
							<input type="date" class="nminputtext" id="fecha_fin" style="width:82% !important;" placeholder="aaaa-mm-dd" disabled>
						</div>
					</td>
				</tr>
				<tr>
					<td class="filtro_radiobox">
						<div style="top:10px;">Movimientos de</div>
						<div class="filtro_select" style="border: 1px solid; width: 86%;">
							<div><input type="radio" class="nminputradio" id="rad_cau" value="0" name="radio_mov">IVA Causado</div>
							<div><input type="radio" class="nminputradio" id="rad_acr" value="1" name="radio_mov" checked>IVA Acreditable</div>
							<div><input type="radio" class="nminputradio" id="rad_amb" value="2" name="radio_mov">Ambos</div>
						</div>
					</td>
					<td class="filtro_radiobox">
						<div style="top:10px;">Operaciones</div>
						<div class="filtro_select" style="border: 1px solid; width: 86%;">
							<div><input type="radio" class="nminputradio" id="reliva" value="0" name="radio_op" disabled>Relacionadas IVA</div>
							<div><input type="radio" class="nminputradio" id="noreliva" value="1" name="radio_op" checked disabled>No Relacionadas IVA</div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div>Cuenta de IVA transladado</div>
						<div class="filtro_select">
							<select id="cuenta_Trans" class="nminputselect">
							<?php
								$cont = count($cuentaTrans);
								for($i=0;$i<$cont;$i++){
									echo '<option value="'.$cuentaTrans[$i]->account_id.'">'.$cuentaTrans[$i]->manual_code.'_'.$cuentaTrans[$i]->description.'</option>';
								}
							?>
							</select>
						</div>
					</td>
					<td>
						<div>Cuenta de IVA acreditable</div>
						<div class="filtro_select">
							<select id="cuenta_Acred" class="nminputselect">
							<?php
								$cont = count($cuentaAcred);
								for($i=0;$i<$cont;$i++){
									echo '<option value="'.$cuentaAcred[$i]->account_id.'">'.$cuentaAcred[$i]->manual_code.'_'.$cuentaAcred[$i]->description.'</option>';
								}
							?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div ><input type="checkbox" id="totalxdia">Total por dia</div>
					</td>
					<td>
						<div>Tasas de IVA</div>
						<div class="filtro_select">
							<select id="tasa_sel" class="nminputselect">
								<option id='tasa_0' selected>Todos</option>
								<?php
										while($rqry_eje=$tasaIVA->fetch_object()){
											echo "<option id='tasa_".$rqry_eje->id."' value='".$rqry_eje->valor."'>".$rqry_eje->tasa."</option>";	
										}
									?>
								<option>Otra tasa</option>
								<option>IVA Retenido</option>
								<option>ISR Retenido</option>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<div ><input type="checkbox" class="nminputcheck" id="sin_caus">Solo movimientos sin periodo de causacion</div>
					</td>
				</tr>
				<tr>
					
					<td colspan="2">
						<div ><input type="checkbox" class="nminputcheck" id="acred100">Acreditar 100% del IVA retenido</div>
					</td>
				</tr>
				<tr>
					<td>
						<div ><input type="checkbox" class="nminputcheck" id="toexcel" value="1">Exportar a excel</div>
					</td>
					<td>
						<div style="float:right; margin-right:20px;"><input type="button" class="nminputbutton" value="Ejecutar Reporte" onclick="reporte_post()"></div>
					</td>
				</tr>
			</table>	
			
		</div>

	</div>
<div id="div_reporte"></div>
</body>
</html>