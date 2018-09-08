<script src="../../libraries/JsBarcode.all.min.js"></script>
<!-- bootstrap min CSS -->
<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">

<script src="js/comandas/comandas.js"></script><?php 

foreach ($_SESSION['cerrar_personalizado']['finalizados'] as $a => $b) { ?>
	<br /> 
	<div style="text-align:left;font-size:14px"><?php
	// Valida que tenga logo
		if (!empty($comanda['logo'])) { ?>
			<div>
				<input type="image" src="../../netwarelog/archivos/1/organizaciones/<?php echo $objeto['logo'] ?>" style="width:180px"/>
			</div><?php
		} ?>
		<div style="border-bottom:1px solid;border-top:1px solid;font-size:12px;font-family:Arial;margin-top:10px;padding-top:8px">
			Comanda No: <?php echo $objeto['idpadre'] ?> - <?php echo $b['id'] ?>  / Mesa: <?php echo $objeto['mesa'] ?>
		</div><?php
		$impuestos = 0;
		foreach ($b['pedidos'] as $key => $value) { 
			$impuestos += $value['impuestos'] ?>
			
			<div style="border-bottom:1px solid;font-size:12px;font-family:Arial;margin-left:10px;margin-top:15px">
					Pedido: <?php echo $key+1 ?>
			</div>
			<div style="margin-left:15px">
				<div class="row" style="font-size:12px;font-family:Arial;">
					<div class="col-xs-1"></div>
					<div class="col-xs-6"><?php echo $value['nombre'] ?></div>
					<div class="col-xs-4" align="right"><?php echo $value['precio_ticket'] ?></div>
				</div>
			</div><?php
			if($value['extras']){ ?>
				<div style="margin-left:15px"><?php
					foreach ($value['extras'] as $k => $v) {
						$impuestos += $v['impuestos'] ?>
					
						<div class="row" style="font-size:12px;font-family:Arial;">
							<div class="col-xs-1"></div>
							<div class="col-xs-6">=> Extra: <?php echo $v['nombre'] ?></div>
							<div class="col-xs-4" align="right">$ <?php echo $v['costo'] ?></div>
						</div><?php
					} ?>
				</div><?php
			}
		} 
	
	// Valida si se debe de mostrar la propina
		if($objeto['mostrar']==1){ ?>
			<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
				Propina sugerida: <?php echo $b['propina'] ?>
	      </div><?php
		} ?>
		
		<!-- <div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
			Sub total: <strong>$ <?php echo round(($b['total'] - $impuestos ), 2)  ?></strong>
		</div>
		<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
			Iva: <strong>$ <?php echo $impuestos ?></strong>
		</div> -->
		<div style="border-top:1px solid;border-bottom:1px solid;font-size:12px;font-family:Arial;padding:5px;margin-top:5px">
			Total: <strong>$ <?php echo $b['total'] ?></strong>
		</div>
	<!-- Codigo de barras -->
		<div style="margin-top:10px">
			<img id="<?php echo $b['codigo'] ?>" style="width:190px;margin-left:-3px;"/>
		</div>
		<script>
			comandas.codigo_barras({id:'<?php echo $b['codigo'] ?>', codigo:'<?php echo $b['codigo'] ?>'});
		</script>
	</div><?php
}
?>