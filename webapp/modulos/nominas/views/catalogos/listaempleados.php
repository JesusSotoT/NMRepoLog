<!DOCTYPE >
<html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>	
	<script type="text/javascript" src="js/empleados.js"></script>
	<script type="text/javascript" src="js/modalempleados.js"></script>
	<link   rel="stylesheet" href="css/bootstrap-datetimepicker.css">
	<script type="text/javascript" src="js/moment.min.js"></script>
	<script type="text/javascript" src="js/bootstrap-datetimepicker.js"></script>
	<script type="text/javascript" src="../../libraries/numeral.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
	<link   rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css"> 

	<?php
	echo "<input id='fechainic'  value='$valiperioact[1]' style='display:none' /> ";
	?> 

</head>
<body>
	<input type="hidden" value="<?php echo $Nominas; ?>" name="nominas" id="nominas" />
      
	<!--Modal-->
	<div id="myModal"  class="modal fade" role="dialog" data-backdrop="static">
		<div class="modal-dialog "  id="mdialTamanio" style="width: 28%" > 
			<!--Modal contenido-->
			<div class="modal-content  alert-info ">
				<div class="modal-header ">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h5 class="modal-title "> 
					</h5>
				</div>
				<div class="modal-body "> 
					<div class="row"> 
						<div class="col-md-2"> 
							<label for="">Fecha:</label>
						</div>
						<div class="col-md-10">
							<div class='input-group date' id='fecha'>
								<input type='text' id="txtfecha" class="form-control" readonly/>
								<span class="input-group-addon">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>	 
					<br>
					<br>
					<div class="row">
						<div class="col-sm-9 col-xs-12 col-md-offset-6" >       
							<button type="button" class="btn btn-danger btn-sm " data-dismiss="modal"><span class="btn-group btn-group-xs fa fa-times"></span> Cancelar</button>
							<button type="button" class="btn btn-primary btn-sm" id="btnbaja" data-loading-text="<i class='fa fa-refresh fa-spin'></i>"><span class=" btn-group btn-group-xs glyphicon glyphicon-floppy-disk" style="align-content: right" disabled="false"></span> Aceptar</button>
						</div>
					</div>
				</div>
			</div> 
		</div>
	</div>

	<div class="container well">
		<h3>Empleados</h3>
		<div class="row">
			<div class="col-sm-12 col-md-2">
				<button class="btn btn-primary" onclick="newEmpleado();">
					<i class="fa fa-plus" aria-hidden="true"></i> Nuevo Empleado
				</button>
			</div>
		</div><br>
		<div>

			<table class="table table-hover table-fixed" style="background-color: rgb(249, 249, 249); border: 1px solid rgb(200, 200, 200); width: 95%;" id="tableGrid" role="grid" aria-describedby="tableGrid_info">
				<thead>
					<th>Codigo</th>
					<th>Nombre</th>
					<th>N.S.S.</th>
					<th>R.F.C.</th>
					<th>C.U.R.P.</th>
					<th>Estatus</th>
					<th></th>
				</thead>
				<body>
					<?php while ($e = $empleados->fetch_object() ){ $ok=1; $activo = "<span class='label label-success'>Activo</span>";
					if( $e->activo == 2 ){
						$ok =2; $activo = "<span class='label label-warning'>Baja</span>"; 
					}
					else if( $e->activo == 3){

						$ok =3; $activo = "<span class='label label-danger'>Reingreso</span>"; 
					}

					?>
					<tr>
						<td><?php echo $e->codigo; ?></td>
						<td><?php echo $e->nombreEmpleado. " " .$e->apellidoPaterno. " " .$e->apellidoMaterno ; ?></td>
						<td><?php echo $e->nss; ?></td>
						<td><?php echo $e->rfc; ?></td>
						<td><?php echo $e->curp; ?></td>
						<td><?php echo $activo; ?></td>
						<td>
							<?php if($ok==1){?>
							<a href="index.php?c=Catalogos&f=empleadoview&editar=<?php echo $e->idEmpleado; ?>" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a>
							<a href="#" class="btn btn-danger btn-xs active" onclick="accionEmpleado(<?php echo $e->idEmpleado; ?>,2);"><span class="glyphicon glyphicon-remove"></span>Baja</a>	
							<?php }else if ($ok == 2 ){?>
							<a href="#" class="btn btn-info btn-xs active" onclick="accionEmpleado(<?php echo $e->idEmpleado; ?>,3);"><span class="glyphicon glyphicon-check"></span> Reingreso</a>
							<?php }else if( $ok == 3){?>
							<a href="index.php?c=Catalogos&f=empleadoview&editar=<?php echo $e->idEmpleado; ?>" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a>
							<a href="#" class="btn btn-danger btn-xs active" onclick="accionEmpleado(<?php echo $e->idEmpleado; ?>,2);"><span class="glyphicon glyphicon-remove"></span> Baja</a>	
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
				</body>
			</table>
		</div>
	</div>	
</body>
</html>