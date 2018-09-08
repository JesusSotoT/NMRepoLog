<style>  @media print{h1.saltopagina{display:block !important;page-break-after:always !important; width:100% !important;}</style>
<?php $count = 0; foreach ($mesas as $key => $value) { $count++;?>
	<?php if($ajustes['tipo_vista_qr'] == 1){ ?>
		<div style="PAGE-BREAK-AFTER: always; width: 5cm; height: 8.5cm; border: 1px solid; border-radius: 2mm; display: table; float:left; margin-top: 0.5cm; margin-left: 0.5cm;">
			<div style="display: table-cell; height: 8.5cm;vertical-align: middle;">
				<?php if (!empty($logo) && $ajustes['mostrar_logo_qr'] == 1) { ?>
					<div class="logo" style="text-align: center">
						<input type="image" src="<?php echo $logo ?>" style="height:25%; width: auto; max-width: 95;"/>
					</div>
				<?php } ?>
				<?php if (!empty($organizacion[0]['nombreorganizacion']) && $ajustes['mostrar_info_qr'] == 1) { ?>
					<div class="info_correo" style="text-align: center; width: 95%; font-weight: bold; margin-top: 5px; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
				<?php } ?>
				<div style="text-align: center; font-weight: bold; margin-top: 5px; font-size:15px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $value['nombre']?></div>
				<div id="qr" style="text-align: center">
					<img style="width: auto; max-width: 95%; height: 49%;" src="<?php echo $value['qr']?>"/>
				</div>
			</div>
		</div>
		<?php if($count == 6) { ?>
		<h1 class="saltopagina"></h1>
		<?php $count = 0;} ?>
	<?php } else { ?>
		<div style="width: 8.5cm; height: 5cm; border: 1px solid; border-radius: 2mm; float:left; margin-top: 0.5cm; margin-left: 0.5cm;">
			<div style="width: 50%; float: left;" >
				<div style="width: 100%; margin: 0; display: table; height: 100%;">
					<div  style="width: 100%; margin: 0; display: table-cell; vertical-align: middle">
						<?php if (!empty($logo) && $ajustes['mostrar_logo_qr'] == 1) { ?>
							<div class="logo" style="text-align: center">
								<input type="image" src="<?php echo $logo ?>" style="width:95%; max-height: 65%"/>
							</div>
						<?php } ?>
						<?php if (!empty($organizacion[0]['nombreorganizacion']) && $ajustes['mostrar_info_qr'] == 1) { ?>
							<div class="info_correo" style="text-align: center; font-weight: bold; margin-top: 5px; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $organizacion[0]['nombreorganizacion'];?></div>
						<?php } ?>
					</div>
				</div>
			</div>
			<div style="width: 50%; float: left;" >
				<div style="width: 100%; margin: 0; display: table; height: 100%;">
					<div style="width: 100%; margin: 0; display: table-cell; vertical-align: middle">
						<div style="text-align: center; font-weight: bold; margin-top: 5px; font-size:13px;font-family: Tahoma,'Trebuchet MS',Arial;"><?php echo $value['nombre']?></div>
						<div id="qr" style="margin-top: 5px; text-align: center">
							<img style="width: 95%; max-height: 80%" src="<?php echo $value['qr']?>"/>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<?php if($count == 8) { ?>
		<h1 class="saltopagina"></h1>
		<?php $count = 0;} ?>
	<?php } ?>
<?php } ?>


	