<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/conciliacion.js"></script>
<script src="../../libraries/tableExport/FileSaver.min.js"></script>
<!-- <link rel="stylesheet" type="text/css" href="../../libraries/tableExport/tableexport.min.css"> -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/TableExport/3.3.13/css/tableexport.min.css">
<script type="text/javascript" href="../../libraries/tableexport.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/3.3.5/js/tableexport.min.js"></script>
<link   rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script src="../../libraries/dataTable/js/datatables.min.js"></script>
<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script> 
<title>conciliación</title>
</head>
<body>
<div id="exTab2" class="container well" style="width: 98%;" >
<ul class="nav nav-tabs">
<li class="active">
<a href="#1" data-toggle="tab">1.Generar archivo RFC</a>
</li>
<li><a href="#2" data-toggle="tab" onclick="conciliResp();">2.Conciliar  con resultado de SAT</a>
</li>
</ul>
<div class="tab-content">
<div class="tab-pane active" id="1">
<br>
<div class="panel panel-default">
<div class="panel-heading">Muestra el listado de RFC obtenido en el catálogo de empleados y de los recibos electrónicos timbrados en el ejercicio seleccionado.</div>
<div class="panel-body">
<div class="alert alert-warning">Genere el archivo para el SAT y valide los RFC en: 
	<A target="_blank" HREF="https://www.siat.sat.gob.mx/PTSC/"> https://www.siat.sat.gob.mx/PTSC</A>
	<br><br>
	<p style="font-weight: bold">Opción:Consultas->Verificación de autenticidad->Validador de RFC´s</p>
</div> 
<div class="alert alert-info" style="overflow-x: scroll;"> 
	<table id="tableprinci" cellpadding="0" class="table-responsive table table-striped table-bordered dt-responsive "   style="width: 100%;">
		<thead> 
			<tr style="background-color:#B4BFC1;color:#000000;">
				<th>Código empleado</th>
				<th>Nombre empleado</th> 
				<th hidden>pruebas</th>
				<th>RFC</th>
				<th hidden></th> 
				<th>CURP</th>
				<th >Fecha de Nacimiento</th>
				<th>Entidad Federativa </th>
				<th>Último Estado</th>
				<th>Fecha Estado</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			while($in = $empleadosConc->fetch_assoc()){	?>
			<tr>
				<td class="tableData"><?php echo $in['codigo'];?></td>
				<td><?php echo $in['apellidoPaterno'].' '.$in['apellidoMaterno'].' '.$in['nombreEmpleado'];?></td>
				<td hidden><?php echo $in['numRegi'];?></td>
				<td><?php echo $in['rfc'];?></td>
				<td hidden></td>
				<td><?php echo $in['curp'];?></td>
				<td><?php echo $in['fechaNacimiento'];?></td>
				<td ><?php echo $in[''];?></td>
				<td><?php echo $in['descripcionEstatus'];?></td>
				<td><?php echo $in['fechaActual'];?></td>
			</tr>
			<?php  } ?>
		</tbody>
	</table>
	<div class="alert alert-warning"><a class="alert-warning glyphicon glyphicon-info-sign"></a> Nota: El validador del SAT solo acepta archivos de máximo de 5,000 registros.</div>
</div>
</div>
</div>
</div>
<!--termina primer pestaña-->
<div class="tab-pane" id="2">
<br>
<div class="panel panel-default">
<div class="panel-heading">Muestra en cada RFC el resultado de la validación de la página SAT.</div>
<div class="panel-body">
<div class="alert alert-warning">Archivo del resultado de SAT:
	<br><br>
	<div class="row">
		<div class="col-lg-6 col-sm-6 col-12">
			<div class="input-group">
				<label class="input-group-btn">
					<span class="btn btn-primary">
						Importar Archivo <input type="file" style="display: none;"  id="file-input">
					</span>
				</label>
				<input type="text" class="form-control" readonly>
			</div>
			<span class="help-block">
				Carge el archivo de RESPUESTA_SAT_RFC.txt
			</span>
		</div>
	</div>
	<textarea id="contenidoarchivo" hidden></textarea> 
</div>
<div class="col-md-12 alert alert-info" style="overflow-x: scroll;">
	<table id="tableconci" cellspacing="0" width="100%" class="table-responsive table table-striped table-bordered dt-responsive nowrap">
		<thead> 
			<tr style="background-color:#B4BFC1;color:#000000;">
				<th hidden>idEmpleado</th>
				<th>Código empleado</th>
				<th>Nombre Empleado</th> 
				<th hidden>numeroRegistro</th>
				<th>RFC</th>
				<th hidden></th> 
				<th>CURP</th>
				<th>Fecha de Nacimiento</th>
				<th>Último Estado</th>
				<th>Fecha Estado</th>
				<th >Resultado SAT</th>
			</tr>
		</thead>
		<tbody >
			<?php 
			while($in = $empleadosConcdos->fetch_assoc()){	?>
			<tr>
				<td hidden class="idEmpleado"> <?php echo $in['idEmpleado'];?></td>
				<td><?php echo $in['codigo'];?></td>
				<td><?php echo $in['apellidoPaterno'].' '.$in['apellidoMaterno'].' '.$in['nombreEmpleado'];?></td>
				<td hidden><?php echo $in['numRegi'];?></td>
				<td class ="tdRFC"><?php echo $in['rfc'];?></td>
				<td hidden></td>
				<td><?php echo $in['curp'];?></td>
				<td><?php echo $in['fechaNacimiento'];?></td>
				<td><?php echo $in['descripcionEstatus'];?></td>
				<td><?php echo $in['fechaActual'];?></td>
				<td class="tdResultadoSAT"></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
