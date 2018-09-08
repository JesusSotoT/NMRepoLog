<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>   
		<link rel="stylesheet" href="css/style.css" type="text/css">
		<script src="js/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="js/select2/select2.css" />
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/ui.datepicker-es-MX.js"></script>
		<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
		<script type="text/javascript" src='js/flujo.js'></script>
	</head>

	<body>
		<div style="width:50%;background: #F2F2F2;" align="center" class="container well" >	
			<div class="panel panel-default" >
				<div class="panel-heading"  style="height: 46px"><b style="font-size: 16px;">Posición Bancaria Diaria Concentrada</b></div> 
					<div class="panel-body" >	
						<form action="index.php?c=Flujo&f=reportePosicion" id="filtro" method="post">
						<ul class="list-group" >
							<li class="list-group-item">
								<label class="control-label" ><b style="font-size: 14px">Del</b></label>
								<input type="date" id="fechainicio" name="fechainicio" class="form-control" style="width:150px; "> 
							</li>
							<li class="list-group-item">
								<label class="control-label" ><b style="font-size: 14px">Al</b></label>
								<input type="date" id="fechafin" name="fechafin" class="form-control" style="width:150px; ">
							</li>
							<li class="list-group-item">
								<label class="control-label" ><b style="font-size: 14px">Moneda:</b></label>
								<select id="moneda" name="moneda" style="width: 150px;" onchange="cuentasPorMoneda()">
									<option value="0" selected="">--Seleccione--</option>
									<?php while($moni = $moneda->fetch_array()){ ?>
									<option value="<?php echo $moni['coin_id']; ?>" ><?php echo $moni['description']?></option>
									<?php } ?>
								</select>
								<i class='fa fa-refresh fa-spin ' id="progres" style="display: none"></i>
							</li>
							<li class="list-group-item">
								<label class="control-label" ><b style="font-size: 14px">Cuenta:</b></label>
								<select id="cuenta" name="cuenta"  style="150px">
								
								</select>
							</li>
							
							<li class="list-group-item">																
								<button type="button" class="btnposicion btn-primary btn-lg " id="btnbtnposicion" style="width: 200px" data-loading-text="Consultando<i class='fa fa-refresh fa-spin '></i>">Consultar</button>
							</li>
						</ul>	
						</form>	
			</div>
		</div>
	
	</body>
</html>