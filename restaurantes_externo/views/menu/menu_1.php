<!-- Sistema -->
<link rel="stylesheet" href="../webapp/modulos/restaurantes/css/configuracion/menu_1.css">

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
	<!-- Nombre restaurante -->
		<div class="row">
			<div class="col-xs-12" align="center">
				<h1 class="titulo"><?php echo $objeto['nombre_restaurante'] ?></h1>
			</div>
		</div>
	<!-- Logo -->
		<div class="row">
			<div class="col-xs-4" align="center">
				<img src="../webapp/modulos/restaurantes/images/Menu_Digital/example_food08.png" alt=" " class="img-responsive">
			</div>
			<div class="col-xs-4" align="center">
				<img src="../webapp/modulos/restaurantes/images/Menu_Digital/example_food10.png" alt=" " class="img-responsive">
			</div>
			<div class="col-xs-4" align="center">
				<img src="../webapp/modulos/restaurantes/images/Menu_Digital/example_food09.png" alt=" " class="img-responsive">
			</div>
		</div><?php
		
		$i = 1;
		
		foreach ($productos as $key => $value) {
			if ($i == 1) {
				$clase = 'izquierda';
				$alinear = 'left';
				$puntear = 'punteado';
				$puntear_2 = '';
				$i = 2; ?>
				<br /><br />
				<div class="row salto_pagina"><?php
			} else {
				$clase = 'derecha';
				$alinear = 'right';
				$puntear = '';
				$puntear_2 = 'punteado';
				$i = 1;
			} 
			
			if(!empty($value['datos'][0])){ ?>
				<div class="col-xs-5" align="<?php echo $alinear ?>">
					<div class="row">
						<div class="col-xs-12 categoria_<?php echo $clase ?>">
							<h3><?php echo $value['categoria'] ?></h3>
						</div>
					</div><?php
					foreach ($value['datos'] as $k => $v) { ?>
						<div class="row">
							<div class="col-xs-8">
								<h5><?php echo $v['nombre'] ?></h5>
							</div>
							<div class="col-xs-4">
								<h5>$ <?php echo $v['precio'] ?></h5>
							</div>
						</div><?php
					} ?>
				</div><?php
			
			// Cierra el Row si es la segunda div
				if ($i == 1) { ?>
					</div><?php
				}else{ ?>
					<div class="col-xs-2">
						<!-- Dibv para generar espacio -->
					</div><?php
				}
			}
		} ?>
	</div>
</div>