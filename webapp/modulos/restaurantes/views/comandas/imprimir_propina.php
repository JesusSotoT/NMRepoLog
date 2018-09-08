<?php
// Valida que tenga logo 
	if (!empty($objeto['logo'])) { ?>
		<div style="text-align: center">
			<input type="image" src="<?php echo $objeto['logo'] ?>" style="width:180px"/>
		</div><?php
	} ?>
	
	<table align="center" style="width:100%; margin-top:15px">
		<tr style="width:100%">
			<td style="width:100%">
				<div style="text-align:center;">
					<div style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;">
						<?php echo $organizacion[0]['nombreorganizacion']; ?>
					</div>
					<div style="text-align: center; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;">
						<?php echo utf8_decode($datos_sucursal[0]['direccion']." ".$datos_sucursal[0]['municipio'].",".$datos_sucursal[0]['estado']); ?>
					</div>
					<div style="width: 100%;text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;border-bottom:3px solid;">
						Sucursal: <?php echo $datos_sucursal[0]["nombre"]; ?>
					</div>
					<div style="text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
						Del: <?php echo $objeto['f_ini']; ?>
					</div>
					<div style="text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
						Al: <?php echo $objeto['f_fin']; ?>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: left;">
					<div style="width: 55%; float: left; text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
						Recibo la cantidad de <strong>$ <?php echo $objeto['total_propina'] ?></strong> <br />
						a la fecha <strong><?php echo date('Y-m-d H:i'); ?></strong>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: left;">
					<div style="width: 55%; float: left; text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
						<?php  echo $objeto['mesero']; ?>
					</div>
				</div>
			</td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
			<td>
				<div style="text-align: left;">
					<div style="width: 55%; float: left; text-align: left; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
						<hr />
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div style="text-align: center;">
					<div style="width: 55%; float: left; text-align: center; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;">
						Firma
					</div>
				</div>
			</td>
		</tr>
	</table>