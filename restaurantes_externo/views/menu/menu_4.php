<!-- bootstrap min CSS -->
<link rel="stylesheet" href="../webapp/libraries/bootstrap/dist/css/bootstrap.min.css">

<!-- Sistema -->
<link rel="stylesheet" href="../webapp/modulos/restaurantes/css/configuracion/menu_4.css">
<style>
	.cabecera {
        background-image: url("../webapp/modulos/restaurantes/images/Menu_Digital/fondo_food4.png");
        background-color: #EDECE8;
        background-repeat: repeat-x;
		background-size: 760px 120px;
        height: 150px;
    }
    .titulo {
        font-family: helvetica;
        color: #8A407C;
    }
    .conta {
        font-family: helvetica;
        color: #210F0D;
        background-color: #EDECE8;
    }
    .categoria {
        font-family: helvetica;
        color: #F2F7EC;
        background-image: url("../webapp/modulos/restaurantes/images/Menu_Digital/etiqueta_food.png");
        background-position: left bottom;
		background-size: 200px 40px;
        background-repeat: no-repeat;
        z-index: 1;
    }
    .logo {
        top: 30px;
        position: absolute;
        margin-left: auto;
        margin-right: auto;
        left: 0;
        right: 0;
        z-index: 1;
    }
    .img-logo {
        max-width: 100%;
    }
    .img-centro {
        width: 100px;
        height: 100px;
        text-align: left !important;
    }
    .menu {
        color: #210F0D;
    }
    div.salto_pagina {
        page-break-after: always;
        page-break-inside: avoid;
    }
</style>
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
				src="../webapp/netwarelog/archivos/1/organizaciones/<?php echo $logo ?>" 
				alt=" ">
		</div>
		<div class="row">
			<div class="col-xs-1" align="center">
				<!-- Div para generar espacio -->
			</div>
			<div class="col-xs-2" align="center">
				<img src="../webapp/modulos/restaurantes/images/Menu_Digital/example_food04.png" alt=" " class="img-responsive">
			</div>
			<div class="col-xs-2" align="center">
				<!-- Div para generar espacio -->
			</div>
			<div class="col-xs-2" align="center">
				<img src="../webapp/modulos/restaurantes/images/Menu_Digital/example_food05.png" alt=" " class="img-responsive">
			</div>
			<div class="col-xs-2" align="center">
				<!-- Div para generar espacio -->
			</div>
			<div class="col-xs-2" align="center">
				<img src="../webapp/modulos/restaurantes/images/Menu_Digital/example_food06.png" alt=" " class="img-responsive">
			</div>
			<div class="col-xs-1" align="center">
				<!-- Div para generar espacio -->
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
			
			if(!empty($value['datos'][0])){
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
						<div class="col-xs-12 categoria">
							<h3><?php echo $value['categoria'] ?></h3>
						</div>
					</div><?php
					foreach ($value['datos'] as $k => $v) { ?>
						<div class="row">
							<div class="col-xs-7">
								<h5><?php echo $v['nombre'] ?></h5>
							</div>
							<div class="col-xs-5">
								<h5><strong>$ <?php echo $v['precio'] ?></strong></h5>
							</div>
						</div><?php
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