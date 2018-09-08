<?php
switch($_REQUEST['t']){
	case 'provision':
		$filtro = 'index.php?c=automaticasMonedaExt&f=provisionmultipleExt';
		$filtromxn = 'index.php?c=Captpolizas&f=provisionmultiple';
	break;
	case 'provisiond'://provi detallada
		$filtro = 'index.php?c=automaticasMonedaExt&f=provisionmultipleExt&detalle=1';
		$filtromxn = 'index.php?c=Captpolizas&f=provisionmultiple&detalle=1';
	break;
	case 'pago':
		$filtro = 'index.php?c=automaticasMonedaExt&f=verpagoext';
		$filtromxn = 'index.php?c=Captpolizas&f=verpoliprove';
	break;
	case 'cobro':
		$filtro = 'index.php?c=automaticasMonedaExt&f=vercobroext';
		$filtromxn = 'index.php?c=Captpolizas&f=verpolicli';
	break;		
		//index.php?c=automaticasMonedaExt&f=provisionmultipleExt
}
?>

<style type="text/css">
	.row
	{
		margin-top: 1em !important;
	}
	.btnMenu{
		border-radius: 0; 
		width: 100%;
		margin-bottom: 1em;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4" style="border: 1px solid; padding: 1em;">
			<h3>Selecciona un tipo de moneda</h3>
			<div class="row">
				<div class="col-md-12">
					<button onclick="window.location='<?php echo $filtromxn; ?>';" class="btn btn-primary btn-lg btnMenu">Polizas en Pesos (MXN Pesos)</button>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<button onclick="window.location='<?php echo $filtro; ?>';" class="btn btn-primary btn-lg btnMenu">Polizas en Moneda Extranjera</button>
				</div>
			</div>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>