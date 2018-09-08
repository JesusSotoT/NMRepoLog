<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/rep_fiscal.css">
	<style type="text/css">
		.cuerpo_f{width: 420px; height:200px;  padding: 7px; border: 0px solid; font-family: arial;}
		
	</style>
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/ConcFlujoEfec_Pago_provisional_IVA.js"></script>
	<script src="js/select2/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
</head>
<body>
	
	<div class="cuerpo_f" style="width:100% !important;">
		<div class="nmwatitles">Conc. de efec. y pago prov. de IVA</div>
		<div class="fondo">
			<table width="100%" height="93%" class="tabla_filtro">
				<tr>
					<td width="50%"></td><td width="50%"></td>
				</tr>
				<tr>
					<td>
						<div>Movimientos del</div>
						<div class="filtro_text" style="width:96% !important;">
							<input type="date" class="nminputtext" id="fecha_ini" placeholder="aaaa-mm-dd">
						</div>
					</td>
					<td>
						<div>Al</div>
						<div class="filtro_text" style="width:96% !important;">
							<input type="date" class="nminputtext" id="fecha_fin" placeholder="aaaa-mm-dd">
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div style="top:10px;">Cuentas de flujo de efectivo</div>
						<div class="filtro_select" style="border: 1px solid; width:86%;">
							<div><input type="radio" id="rad_cau" value="0" name="radio_tipo" checked>Todas</div>
							<div><input type="radio" id="rad_acr" value="1" name="radio_tipo" >Algunas</div>
						</div>
					</td>
					<td>
					</td>
				</tr>
				<tr>
					<td>
						<div>Cuenta inicial</div>
						<div class="filtro_select">
							<select id="cuenta_Ini" disabled class="nminputselect">
							<?php
								$cont = count($cuentas);
								for($i=0;$i<$cont;$i++){
									echo '<option value="'.$cuentas[$i]->account_id.'">'.$cuentas[$i]->manual_code.'_'.$cuentas[$i]->description.'</option>';
								}
							?>
							</select>
						</div>
					</td>
					<td>
						<div>Cuenta final</div>
						<div class="filtro_select">
							<select id="cuenta_Fin" disabled class="nminputselect">
							<?php
								$cont = count($cuentas);
								for($i=0;$i<$cont;$i++){
									echo '<option value="'.$cuentas[$i]->account_id.'">'.$cuentas[$i]->manual_code.'_'.$cuentas[$i]->description.'</option>';
								}
							?>
							</select>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div ><input type="checkbox" class="nminputcheck" id="toexcel" value="1">Exportar a excel
						<br>
						<input type="checkbox" class="nminputcheck" id="aplica" value="1">Mostrar solo los que aplican.</div>	
						</div>
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