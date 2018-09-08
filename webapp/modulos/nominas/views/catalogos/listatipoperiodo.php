<!DOCTYPE >
<html>
	<head>
		  	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>

			<script type="text/javascript" src="js/tipoperiodo.js"></script>

	</head>
	<body>
		<br><br>
		<div class="container well">
			<h3>Catalogo de Tipos de Periodos</h3>
			<div class="row">
				<div class="col-sm-12 col-md-2">
					<button class="btn btn-primary" onclick="newTipo();">
						<i class="fa fa-plus" aria-hidden="true"></i> Nuevo Tipo de Periodo
					</button>
				</div>
			</div><br>
			<table class="table table-hover table-fixed" style="background-color: rgb(249, 249, 249); border: 1px solid rgb(200, 200, 200); width: 1133px;" id="tableGrid" role="grid" aria-describedby="tableGrid_info">
				<thead>
					<th>Fecha</th>
					<th>Nombre</th>
					<th>Dias periodo</th>
					<th>Dias pago</th>
					<th>Periodo de trabajo</th>
					<th>Periodicidad de pago</th>
					<th>Estatus</th>
					<th></th>
				</thead>
				<body>
					<?php while ($e = $tipoperiodo->fetch_object() ){ $ok=1; $activo = "<span class='label label-success'>Activo</span>";
							if( $e->activo != 1 ){ $ok =0; $activo = "<span class='label label-danger'>Inactivo</span>"; } ?>
						<tr>
							<td><?php echo $e->fechainicio; ?></td>
							<td><?php echo $e->nombre ; ?></td>
							<td align="right"><?php echo $e->diasperiodo; ?></td>
							<td align="right"><?php echo $e->diaspago; ?></td>
							<td align="right"><?php echo $e->periodotrabajo; ?></td>
							<td><?php echo $e->clave." ". $e->descripcion; ?></td>
							<td><?php echo $activo; ?></td>
							<td>
								<?php if($ok){?>
									<a href="index.php?c=Catalogos&f=tipoPeriodoview&editar=<?php echo $e->idtipop; ?>" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a>
									<a href="#" class="btn btn-danger btn-xs active" onclick="accionTipo(<?php echo $e->idtipop; ?>,0);"><span class="glyphicon glyphicon-remove"></span> Desactivar</a>	
							    <?php }else{?>
							   	 	<a href="#" class="btn btn-info btn-xs active" onclick="accionTipo(<?php echo $e->idtipop; ?>,1);"><span class="glyphicon glyphicon-check"></span> Activar</a>
							    	<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</body>
			</table>

		</div>
		
	
	</body>
</html>