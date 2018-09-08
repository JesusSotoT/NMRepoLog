<!DOCTYPE>
<html>
<head>
	<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
	<script src="../../libraries/dataTable/js/datatables.min.js" type="text/javascript"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js" type="text/javascript"></script>
	<script src="../../libraries/export_print/buttons.html5.min.js" type="text/javascript"></script>
	<script src="../../libraries/export_print/dataTables.buttons.min.js" type="text/javascript"></script>
	<script src="../../libraries/export_print/jszip.min.js" type="text/javascript"></script>
	<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<script type="text/javascript" src="js/conceptos.js"></script>
</head>
<body>
	<br><br>
	<div class="container well">
		<h3>Catalogo de Conceptos</h3>
		<div class="row">
			<div class="col-sm-12 col-md-2">
				<button class="btn btn-primary" onclick="newConcepto();">
					<i class="fa fa-plus" aria-hidden="true"></i> Nuevo Concepto
				</button>
			</div>
		</div><br>
		<table class="table table-striped table-bordered" id="tabla">
			<thead>
				<tr>
					<th style="width:10px !important;">Concepto</th>
					<th style="width:30px !important;">Tipo</th>
					<th style="width:30px !important;">Descripcion</th>
					<th style="width:40px !important;">Clave SAT</th>
					<th style="width:30px !important;">Global</th>
					<th style="width:5px !important;">Liquidacion</th>
					<th style="width:30px !important;">Especie</th>
				</tr>
			</thead>
			<tbody>
				<?php while ($e = $listaConceptos->fetch_object() ){
					$global = "<span class='glyphicon glyphicon-remove-sign' style='color:#FA5858'></span>";
					$liquidacion = "<span class='glyphicon glyphicon-remove-sign' style='color:#FA5858'></span>"; 
					$especie = "<span class='glyphicon glyphicon-remove-sign' style='color:#FA5858'></span>";     
					if( $e->global == 1 ){
						$global = "<span class='glyphicon glyphicon-check' style='color:#0080FF'></span>"; 
					}
					if( $e->liquidacion == 1 ){
						$liquidacion = "<span class='glyphicon glyphicon-check' style='color:#0080FF'></span>"; 
					}
					if( $e->especie == 1 ){
						$especie = "<span class='glyphicon glyphicon-check' style='color:#0080FF'></span>"; 
					} ?>
					<tr class="out" onmouseout="this.className='out'" onmouseover="this.className='over'" >
						<td style="width:10px !important;"><?php echo $e->concepto; ?></td>
						<td style="width:30px !important;"><?php echo $e->tipo; ?></td>
						<td style="width:30px !important;"><?php echo $e->descripcion; ?></td>
						<td style="width:40px !important;"><?php echo $e->sat; ?></td>
						<td align="center" style="width:30px !important;"><?php echo $global;?></td>
						<td align="center" style="width:5px !important;"><?php echo $liquidacion; ?></td>
						<td align="center" style="width:30px !important;"><?php echo $especie; ?></td>
						<td style="width:105px !important;">
							<a href="index.php?c=Catalogos&f=conceptos&editar=<?php echo $e->idconcepto; ?>" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span>Editar</a>
							&ensp;
							<a href="#" class="btn btn-danger btn-xs active" onclick="accionEliminarConcepto(<?php echo $e->idconcepto;?>);"><span class="glyphicon glyphicon-remove" id="<?php echo $e->idconcepto;?>"></span>Eliminar</a>	
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

		</div>
		

	</body>
	</html>