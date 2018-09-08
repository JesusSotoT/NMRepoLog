<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Ordenes de Compra</title>
		<link rel="stylesheet" href="">
	</head>

	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<script src="../../libraries/jquery.min.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="js/producto.js"></script>
<!--Select 2 -->
	<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

	<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<!-- Modificaciones RC -->
	<script src="../../libraries/dataTable/js/datatables.min.js"></script>
	<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
	<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
	<script src="../../libraries/export_print/buttons.html5.min.js"></script>
	<script src="../../libraries/export_print/jszip.min.js"></script>
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
	<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

	<script>
		$(document).ready(function() {
			$('#tableGrid').DataTable({
				dom: 'Bfrtip',
				buttons: ['excel'],
				language: {
					search: "Buscar:",
					lengthMenu:"",
					zeroRecords: "No hay datos.",
					infoEmpty: "No hay datos que mostrar.",
					info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
					paginate: {
						first:      "Primero",
						previous:   "Anterior",
						next:       "Siguiente",
						last:       "Último"
					},
				},
				aaSorting : [[0,'desc' ]]
			});
			$("#layout_row").attr("abierto","0").hide();
			$("#layout_precios").attr("abierto","0").hide();
		});

		function mostrar_layout() {
			if(!parseInt($("#layout_row").attr("abierto")))
				$("#layout_row").attr("abierto","1").show("slow");
			else
				$("#layout_row").attr("abierto","0").hide("slow");
		}

		function mostrar_layout2() {
			if(!parseInt($("#layout_precios").attr("abierto")))
				$("#layout_precios").attr("abierto","1").show("slow");
			else
				$("#layout_precios").attr("abierto","0").hide("slow");
		}

		function validar(t) {
			if(t.layout.value == '') {
				alert("Agregue un archivo xls.");
				return false;
			}
		}
	</script>

	<body>
		<div class="container well">
			<div class="row">
				<div class='col-sm-8'>
					<h3>
						Productos
						<button class="btn btn-primary" onclick="newProduct();"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Producto</button>
					</h3>
				</div>
				<div class='col-sm-12 col-md-2'>
					<button class="btn btn-default" title='Subir productos mediante layout' onclick='mostrar_layout()'><span class='glyphicon glyphicon-upload'></span></button>
				</div>
				<div class='col-sm-12 col-md-2'>
					<button class="btn btn-default" title='Subir precios mediante layout' onclick='mostrar_layout2()'><span class='glyphicon glyphicon-tags'></span></button>
				</div>
<!--
				<div class='col-sm-12 col-md-2'>
					<button class="btn btn-default" onclick='mostrarMas();'>Mostrar mas</button>
					<input type="hidden" value="100" id="rango">
				</div>
				<div class='col-sm-12 col-md-2'>
					<button class="btn btn-default" onclick='mostrarTodos();'>Mostrar todos</button>
					<input type="hidden" value="100" id="rango">
				</div>
-->			</div>

			<div class='row' id='layout_row'>
				<div class='col-sm-12 col-md-offset-2 col-md-5'>
					<b>Subir productos mediante layout</b> / <a href='importacion/productos.xls'>Descargar</a><br />
					<form action='index.php?c=producto&f=subeLayout' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
						<input type='file' id='layout' name='layout'><br />
						<button type='submit' onclick='cargar_productos()'>Cargar</button>
					</form>
				</div>
			</div>

			<!-- Actualiza Precios -->
			<div class='row' id='layout_precios'>
				<div class='col-sm-12 col-md-offset-2 col-md-5'>
					<b>Actualizar precios mediante layout</b><br />
					<form action='index.php?c=producto&f=subeLayoutPrecios' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
						<input type='file' id='layout' name='layout'><br />
						<button type='submit' onclick='cargar_productos()'>Cargar</button>
					</form>
				</div>
			</div>

	<!--    <div class='row' id='layout_cxc'>
				<div class='col-sm-12 col-md-offset-2 col-md-5'>
					<b>Carga Saldos cxc</b><br />
					<form action='index.php?c=producto&f=subeLayoutCxc' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
						<input type='file' id='layout' name='layout'><br />
						<button type='submit' onclick='cargar_productos()'>Cargar</button>
					</form>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<label id="totlaProdsLabel">Total: <?php echo $productosGrid['total']; ?></label>
					<input type="hidden" id="totlaProds" value="<?php echo $productosGrid['total']; ?>">
				</div>
			</div>
-->
			<div class="row">
				<div class="col-sm-12" style="overflow:auto;">
					<table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
						<thead>
							<tr>
								<th>ID</th>
								<th>Código</th>
								<th>Nombre</th>
								<th>Precio</th>
								<th>Costo</th>
								<th>Proveedor</th>
								<th>Última modificación</th>
								<th>Estatus</th>
								<th>Modificar</th>
							</tr>
						</thead>

						<tbody>
							<?php
								$status = '';
								$total = 0;
								$empleado = '';
								foreach ($productosGrid['productos'] as $key => $value) {
									if($value['tipo_producto']!=88888){
										if($value['status']==1){
											$status = '<span class="label label-success">Activo</span>';
											$botones = '<a href="index.php?c=producto&f=index&idProducto='.$value['id'].'" class="btn btn-primary btn-xs active" title="Editar">
													<span class="glyphicon glyphicon-edit"></span>
												</a>
												<a href="#" class="btn btn-danger btn-xs active" onclick="borraProducto('.$value['id'].');" title="Desactivar">
													<span class="glyphicon glyphicon-remove"></span>
												</a>';
										} else {
											$status = '<span class="label label-danger">Inactivo</span>';
											$botones = '<a href="#" class="btn btn-info btn-xs active" onclick="activar('.$value['id'].');" title="Activar">
													<span class="glyphicon glyphicon-check"></span>
												</a>';
										}

										echo '<tr>';
										echo '<td>'.$value['id'].'</td>';
										echo '<td>'.$value['codigo'].'</td>';
										echo '<td>  <a href="index.php?c=producto&f=index&idProducto='.$value['id'].'" title="Editar">'.$value['nombre'].'</a> </td>';
										echo '<td>$'.number_format($value['precio'],2).'</td>';
										echo '<td>$'.number_format($value['costo'],2).'</td>';
										echo '<td>'.$value['idProve'].'</td>';
										echo '<td>'.$value['fecha_mod'].'</td>';
										echo '<td>'.$status.'</td>';
										echo '<td>';
										// echo '<a href="index.php?c=producto&f=index&idProducto='.$value['id'].'" class="btn btn-primary btn-xs active"><span class="glyphicon glyphicon-edit"></span> Editar</a> ';
										//echo '<a href="#" class="btn btn-danger btn-xs active" onclick="borraProducto('.$value['id'].');"><span class="glyphicon glyphicon-remove"></span> Borrar</a>';
										echo $botones;
										echo '</td>';
										echo '</tr>';
										$total ++;
									}
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<!--          Molda Warning           -->
		<div id="modalElimina" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content panel-warning">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="modal-header panel-heading">
						<h4 id="modal-label">Desactivar producto</h4>
					</div>
					<div class="modal-body">
						<p>¿Deseas desactivar este producto?</p>
						<input type="hidden" id="eliminaProd">
					</div>
					<div class="modal-footer">
						<button id="modal-btnconf2-uno" type="button" class="btn btn-danger" onclick="borraProducto2();">Desactivar</button>
						<button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>						
					</div>
				</div>
			</div>
		</div>

		<div id="modalActiva" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content panel-warning">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<div class="modal-header panel-heading">
						<h4 id="modal-label">Activar!</h4>
					</div>
					<div class="modal-body">
						<p>Deseas activar este producto?</p>
						<input type="hidden" id="activaProd">
					</div>
					<div class="modal-footer">
						<button id="modal-btnconf2-uno" type="button" class="btn btn-danger" onclick="activar2();">Activar</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>