<style>
.lateralR {
	float: left;
}
.lateralL {
	float: left;
}
.divhead1 {
	min-height: 150px !important;
	width: 15%;
	/*
	border-style: solid;
	border-color: red;
	*/
}
.divhead2 {
	min-height: 150px !important;
   	width: 35%;
   	/*
  	border-style: solid;
  	border-color: red;
  	*/
}
.divhead3 {
	min-height: 150px !important;
   	width: 50%;
   	/*
  	border-style: solid;
  	border-color: gray;
  	*/
}
.panel-primary > .panel-heading2 {
    background: #009999; color: #fff;
}
.panel-heading3 {
    background: #2a2a2a; color: #fff;
}
.divborder{
	border-bottom-style: solid;
  	border-color: black;
}
</style>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Implementacion Inicial</title>
    <link rel="stylesheet" href="">
</head>
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">

    <script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="../../libraries/jquery.min.js"></script>
    <script src="js/dash.js"></script>

    <!-- morris -->
    <link rel="stylesheet" href="../../libraries/morris.js-0.5.1/morris.css">
    <script src="../../libraries/morris.js-0.5.1/raphael.min.js"></script>
    <script src="../../libraries/morris.js-0.5.1/morris.min.js"></script>

<body>
	<div class="container well col-md-12">
		<!--DIV FLOTANTE PARA DASH PRINCIPAL-->
		<div class="lateralR col-md-9">
			<div class ="col-md-12">
				<!--DIVS PARA EL ENCABEZADO-->
				<div class="divhead1 col-md-2">Logo
				</div>
				<div class="divhead2 col-md-4">Plan
				</div>
				<div class="divhead3 col-md-6">
					<div class="text-center text-muted col-md-12"> <h4>¿Que quieres hacer?</h4></div>
					<div class="col-md-12">
						<div class="col-md-1"></div>
						<div class="col-md-2 text-center">
	                        <img style="cursor: pointer" width="60" height="60" src="images/prov.png" onclick="imgprov();">
	                    </div>
	                    <div class="col-md-2 text-center">
	                        <img style="cursor: pointer" width="60" height="60" src="images/comp.png" onclick="imgcomp();">
	                    </div>
	                    <div class="col-md-2 text-center">
	                        <img style="cursor: pointer" width="60" height="60" src="images/invent.png" onclick="imginvent();">
	                    </div>
	                    <div class="col-md-2 text-center">
	                        <img style="cursor: pointer" width="60" height="60" src="images/ventas.png" onclick="imgventas();">
	                    </div>
	                    <div class="col-md-2 text-center">
	                        <img style="cursor: pointer" width="60" height="60" src="images/client.png" onclick="imgclient();">
	                    </div>
					</div>
				</div>
				<!--DIV PARA CUERPO (GRAFICA) -->
				<div class="col-md-12">
					<div class="text-muted" style="height: 240px;">
						<div class="col-md-6">Histórico de Ventas</div>
						<div class="col-md-6 text-right">VENTAS: $0.00</div>
						<div class="col-md-12 divborder"></div>
						<div class="col-md-12"id="grafVentas" style="height: 210px;">
						</div>
					</div>
				</div>
				<!--DIVS PARA FOOTER (CUNETAS) -->
				<div class="col-md-12">
					<div class="col-md-6">
						<div class="panel panel-primary">
							  <div class="panel-heading small" style="height: 20px;padding:0">Cuentas por Pagar</div>
							  <div class="panel-body small">
							  	<div class="col-md-12">
							  		<div class="col-md-1"><span class="label label-primary">MXN</span></div>
							  		<div id="saldoFP" class="col-md-9 text-right" style="font-size:22px;"></div>
							  		<div class="col-md-1"><label class="text-left text-muted">MXN</label></div>
							  		<div class="col-md-9 text-right">Retrasado:</div>
							  		<div id="saldoSinV" class="col-md-1" id="pRetrasado"></div>
							  		<div class="col-md-9 text-right">1 - 15 días:</div>
							  		<div id="saldo1_15" class="col-md-1" id="p30dias"></div>
							  		<div class="col-md-9 text-right">16 - 30 días:</div>
							  		<div id="saldo16_30" class="col-md-1" id="p60dias"></div>
							  		<div class="col-md-9 text-right">31 - 45 días:</div>
							  		<div id="saldo31_45" class="col-md-1" id="p90dias"></div>
							  		<div class="col-md-9 text-right">Más de 45 días:</div>
							  		<div id="saldo45mas" class="col-md-1" id="pmasdias"></div>
							  	</div>
							  </div>
							  <div class="panel-footer text-right" style="height: 20px;padding:0">
							  	<a href="" style="font-size:12px;" onclick="repCuentasPagar();">Ir a Reporte Detallado de Cuentas por Pagar</a>
							  </div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-primary">
							  <div class="panel-heading2 small" style="height: 20px;padding:0">Cuentas por Cobrar</div>
							  <div class="panel-body small">
							  	<div class="col-md-12">
							  		<div class="col-md-1"><span class="label label-primary">MXN</span></div>
							  		<div id="saldoFC" class="col-md-9 text-right" style="font-size:22px;"></div>
							  		<div class="col-md-1"><label class="text-left text-muted">MXN</label></div>
							  		<div class="col-md-9 text-right">Retrasado:</div>
							  		<div id="saldoSinVC" class="col-md-1" id="pRetrasado"></div>
							  		<div class="col-md-9 text-right">1 - 15 días:</div>
							  		<div id="saldo1_15C" class="col-md-1" id="p30dias"></div>
							  		<div class="col-md-9 text-right">16 - 30 días:</div>
							  		<div id="saldo16_30C" class="col-md-1" id="p60dias"></div>
							  		<div class="col-md-9 text-right">31 - 45 días:</div>
							  		<div id="saldo31_45C" class="col-md-1" id="p90dias"></div>
							  		<div class="col-md-9 text-right">Más de 45 días:</div>
							  		<div id="saldo45masC" class="col-md-1" id="pmasdias"></div>
							  	</div>
							  </div>
							  <div class="panel-footer text-right" style="height: 20px;padding:0">
							  	<a href="" style="font-size:12px;" onclick="repCuentasCobrar();">Ir a Reporte Detallado de Cuentas por Pagar</a>
							  </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--DIV FLOTANTE PARA LAS NOTIFICACIONES-->
		<div class="lateralL col-md-3">
			<div class="panel panel-info">
						  <div class="panel-heading" style="background: #2a2a2a; color: #fff;">Actividad Reciente</div>
						  <div class="panel-body" style="overflow-y:scroll; height:500px;width:300px;">asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd <br> asd
						  </div>
					</div>
		</div>
	</div>
</body>
<script>

	  $(document).ready(function(){
	  	cuentasPagar();
	  	cuentasCobrar();
	  	ventas();
    });

	


	
</script>