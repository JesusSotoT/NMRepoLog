<!DOCTYPE html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/jquery.number.js"></script>
	<link rel="stylesheet" type="text/css" href="css/ingresos.css" />
	<script src="../cont/js/moment.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
	<script src="js/conciliacion.js"></script>
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script> 
	<script src="js/sessionejercicio.js"></script>
</head>
<body>
<div style="width:98%;background: #F2F2F2;" align="" class="container well" >
	<div class="panel panel-default" >
		<div class="panel-heading"  style="height: 46px;font-family: Courier;" align="center"><b style="font-size:25px;">Conciliacion Polizas / Documentos</b></div> 
	</div>
	<div class="panel-body">
		<?php
		if($bancos == 1 && $acontia == 1){?>
			
		<div class="panel panel-primary" >
			<div class="panel-heading" align="left" style="height: 45px">
				<b style="font-size:16px;">Conciliar por :</b>
				</div>
			<div class="row">
				<br>
				<div class="col-md-2">
					<input type="radio"  name="opc" onclick="polizas()"/> Polizas<br>
					<input type="radio"  name="opc" onclick="documento()"/> Documentos

				</div>
				<div class="col-md-1">
					<b>Fecha:</b> <b style="color: red"></b>
				</div>
				<div class="col-md-2">
					<input id="fechainicio" name="fechainicio"  type="text" placeholder="" class="form-control" value="">
				</div>
				<div class="col-md-1" align="center">Al
				</div>
				<div class="col-md-2">
					<input id="fechafin" name="fechafin"  type="text" placeholder="" class="form-control" value="">
				</div>
				<div class="col-md-2" align="right">
					<button type="button" class="btn btn-primary" id="load" style="center"   data-loading-text="<i class='fa fa-refresh fa-spin '></i>">Conciliar</button>
				</div>	
				
			</div>
			<br>			

		</div>	
			
			
			
			
			
			
			
			
		<?php	
		}else{?>
			<b style="color:red">Solo puede utilizar esta opcion si tiene BANCOS y ACONTIA</b>
			<br>
			Contrate al <br>
			Tel.: +52 (33) 3675 6800<br>
			Tel.: 01 (800) APPS 321 - 01 (800) 2777 321 <br>
			ventas@netwarmonitor.com <br>
			<a target="_blank" href="http://www.netwarmonitor.mx/index.php">www.netwarmonitor.mx</a>
		<?php	
		}
		?>
	</div>	
</div>
</body>
</html>