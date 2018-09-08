<!-- bootstrap min CSS -->
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">

<!-- Sistema -->
<link rel="stylesheet" href="css/configuracion/menu_3.css">
<?php
// Valida que existan productos o recetas
	if (empty($productos)) {?>
		<div align="center">
			<h3><span class="label label-default">* No se hay productos *</span></h3>
		</div><?php
		
		return 0;
	} 
?>
<div class="conta row">
	<div class="col-xs-12">
	<!-- Fondo cabecera -->
		<div class="row">
			<div class="col-xs-12 cabecera">
				<!-- Imagenes de fondo -->
			</div>
		</div>
	<!-- Logo -->
		<div class="col-xs-6 logo" align="center">
			<img 
				class="img-logo"
				src="../../netwarelog/archivos/1/organizaciones/<?php echo $objeto['logo'] ?>" 
				alt=" ">
		</div>
		<div class="row">
			<div class="col-xs-12 fondo_logo">
				
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 menu" align="center">
				<h1 style="font-size: 60px">Menú</h1>
			</div>
		</div><?php
		
		$i = 1;
		$imagen = 1;
		
		foreach ($productos as $key => $value) {
			if ($i == 1) {
				$i = 2; ?>
				<br /><br />
				<div class="row salto_pagina"><?php
			} else {
				$i = 1;
			} 
			
			if(!empty($value['datos'][0]['data'])){
				$espacio = 'padding-left: 50px !important';
				
			// Cierra el Row si es la segunda div
				if ($i == 2) { 
					$espacio = ''; ?>
					<div class="col-xs-1">
						<!-- Div para generar espacio a la izquierda -->
					</div><?php
				} ?>
				<div class="col-xs-4" align="left" style="<?php echo $espacio ?>">
					<div class="row">
						<div class="col-xs-12 categoria" align="center">
							<h3><?php echo $value['categoria'] ?></h3>
						</div>
					</div><?php
					foreach ($value['datos'] as $k => $v) {
						if(!empty($v['data'])){  ?>
							<div class="row">
									<div class="col-xs-7">
										<h5><?php echo $v['data']['nombre'] ?></h5>
									</div>
									<div class="col-xs-5">
										<h5><strong>$ <?php echo $v['data']['precio'] ?></strong></h5>
									</div>
							</div><?php
						}
					} ?>
				</div><?php
				
				if ($i == 1) { ?>
					<div class="col-xs-1">
						<!-- Div para generar espacio a la derecha -->
					</div><?php
				} 
			// Cierra el Row si es la segunda div
				if ($i == 1) { ?>
					</div><?php
				}else{ 
					$imagen ++;
					
					if ($imagen == 4) {
						$imagen = 1;
					} ?>
					
					<div class="col-xs-2" align="left">
						<!-- Div para generar espacio entre categorias -->
					</div><?php
				}
			}
		} ?>
	</div>
</div>