<!-- bootstrap min CSS -->
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">

<!-- Sistema -->
<link rel="stylesheet" href="css/configuracion/menu_2.css">
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
	<!-- Fondo tenedores -->
		<div class="row">
			<div class="col-xs-12 cabecera">
				<!-- Imagenes de fondo -->
			</div>
		</div>
	<!-- Logo -->
		<div class="col-xs-6 logo" align="center">
			<img 
				class="img-responsive img-logo"
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
			
			if(!empty($value['datos'])){
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
					foreach ($value['datos'] as $k => $v) {
						if(!empty($v['data'])){  ?>
							<div class="row">
									<div class="col-xs-7">
										<h5><?php if(!empty($v['data']['link']) || !empty($v['data']['resena'])){ ?><a class="conta" target="_blank" style="" href="http://www.netwarmonitor.mx/clientes/<?php echo $_SESSION['accelog_nombre_instancia'];?>/restaurantes_externo/ajax.php?c=externo&f=detalles_producto&id=<?php echo $v['data']['idProducto']?>"><?php echo $v['data']['nombre'] ?></a><?php } else { ?> <?php echo $v['data']['nombre'] ?> <?php } ?></h5>
									</div>
									<div class="col-xs-5">
										<h5>$ <?php echo $v['data']['precio'] ?></h5>
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
						<img 
						 	class="img-responsive img-centro"
							align="left"
							src="images/Menu_Digital/example_food0<?php echo $imagen ?>.png" 
							alt=" ">
					</div><?php
				}
			}
		} ?>
	</div>
</div>