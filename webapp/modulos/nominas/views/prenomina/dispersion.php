<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="js/dispersion.js"></script>
	<link rel="stylesheet" href="css/bootstrap-datetimepicker.css">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<script type="text/javascript" src="js/moment.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
	<link   rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link   rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css"> 
	<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<link   rel="stylesheet" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script> 
</head>
<br>
<body>
	<div class="container well">
		<div style="height: 46px;font-family: Courier;background-color:#F5F5F5;" align="center"><b style="font-size:25px;">Dispersión</b></div> 
		<br>
		<div class="col-md-12" align="left">
			<br>
			<b>Dispersion</b>
			<button class="btn btn-default" onclick="newDispersion()">
				<i class="fa fa-plus" aria-hidden="true"></i> Nuevo
			</button>
			<br><br>
		</div>
		<div class="alert alert-info wrap" cellspacing="0" width="100%">
			<table id="tablainicio" cellpadding="0" class="tablainicio table table-striped table-bordered" style="width: 100%">
				<thead> 
					<tr style="background-color:#B4BFC1;color:#000000;">
						<th  style="width: 40px;font-weight: bold">No. Dispersion</th>
						<th  style="width: 40px;font-weight: bold">Fecha Aplicacion</th>
						<th  style="width: 100px;font-weight: bold">Fecha Adelanto</th>
						<th  style="width: 60px;font-weight: bold">Tipo de pago</th>
						<th  style="width: 60px;font-weight: bold">Monto</th>
						<th  style="width: 60px;font-weight: bold">Estatus</th>
						<th  style="width: 60px;font-weight: bold">Acciones</th>
					</tr>
				</thead>
				<tbody>
					<?php

					while($in = $cargarDatosDispersos->fetch_assoc()){

						?>
						<tr>
							<td style="width: 60px;" class="prueba"><?php echo $in['idConsecutivo'];?></td>
							<td style="width: 20px" class="prueba"><?php echo  $in['fechaAplicacion'];?></td>
							<td style="width: 20px" class="prueba"><?php echo  $in['fechaAdelanto'];?></td>
							<td style="width: 20px" class="prueba"><?php echo  $in['nombrepago'];?></td>
							<td style="width: 20px;text-align: right;" class="prueba"><?php echo number_format($in['monto'],2,'.',',')?></td>
							<td style="width: 20px" class="prueba"><?php echo  $in['estatus'];?></td>
							<td><a href="#" class="btn btn-danger btn-xs active" onclick="accionEliminarDispersion('<?php echo $in['idEmpleado'] ?>','<?php echo  $in['idnomp']?>')"><span class="glyphicon glyphicon-remove" id="<?php echo $in['idEmpleado'] ?>',echo  $in['idnomp']?>"></span>Eliminar</a>	</td>
						</tr>
						<?php  
					} 
					?> 

				</table>
			</div>
		</div>
	</div>	
</body>
</html>