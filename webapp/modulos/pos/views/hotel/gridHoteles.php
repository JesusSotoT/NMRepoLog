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
	<script src="js/hotel.js"></script>
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
						Hoteles
						<button class="btn btn-primary" data-toggle="modal" data-target=".nuevoHotel">
							<i class="fa fa-plus" aria-hidden="true"></i> Nuevo Hotel
						</button>
					</h3>
				</div>
				<div class='col-sm-12 col-md-2'>
					<button class="btn btn-default" title='Subir productos mediante layout' onclick='mostrar_layout()'><span class='glyphicon glyphicon-upload'></span></button>
				</div>
				<!-- <div class='col-sm-12 col-md-2'>
					<button class="btn btn-default" title='Subir precios mediante layout' onclick='mostrar_layout2()'><span class='glyphicon glyphicon-tags'></span></button>
				</div> -->
<!--
				<div class='col-sm-12 col-md-2'>
					<button class="btn btn-default" onclick='mostrarMas();'>Mostrar mas</button>
					<input type="hidden" value="100" id="rango">
				</div>
				<div class='col-sm-12 col-md-2'>
					<button class="btn btn-default" onclick='mostrarTodos();'>Mostrar todos</button>
					<input type="hidden" value="100" id="rango">
				</div>
-->			
			</div>

			<div class='row' id='layout_row'>
				<div class='col-sm-12 col-md-offset-2 col-md-5'>
					<b>Subir productos mediante layout</b> / <a href='importacion/hoteles.xls'>Descargar</a><br />
					<form action='index.php?c=hotel&f=subeLayout' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
						<input type='file' id='layout' name='layout'><br />
						<button type='submit' onclick='cargar_productos()'>Cargar</button>
					</form>
				</div>
			</div>


			<div class="row">
				<div class="col-sm-12" style="overflow:auto;">
					<table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
						<thead>
							<tr>
								<th>Clave</th>
								<th>Nombre</th>
								<th>Estatus</th>
								<th>Acciones</th>
							</tr>
						</thead>

						<tbody>
							<?php 
								foreach ($hotelesGrid as $key => $value) {
									if($value['estatus']==1){
										$status = '<span class="label label-success">Activo</span>';
										$botones = '<button class="btn btn-primary btn-xs active" title="Editar" data-toggle="modal" data-target=".editarHotel" onclick="modalEditarHotel('. "{$value['id']} , '{$value['clave']}'  , '{$value['nombre']}'  , '{$value['estatus']}'" .')">
												<span class="glyphicon glyphicon-edit"></span>
											</button>
											<a href="#" class="btn btn-danger btn-xs active" onclick="cambiarEstatus('. "{$value['id']} , '{$value['clave']}'  , '{$value['nombre']}'  , '0'" .')" title="Desactivar">
												<span class="glyphicon glyphicon-remove"></span>
											</a>';
									} else {
										$status = '<span class="label label-danger">Inactivo</span>';
										$botones = '<a href="#" class="btn btn-info btn-xs active" onclick="cambiarEstatus('. "{$value['id']} , '{$value['clave']}'  , '{$value['nombre']}'  , '1'" .')" title="Activar">
												<span class="glyphicon glyphicon-check"></span>
											</a>';
									}

									echo 	"<tr>
												<td>{$value['clave']}</td>
												<td>{$value['nombre']}</td>
												<td>$status</td>
												<td>$botones</td>
											</tr>";
								}
							?>

						</tbody>
					</table>
				</div>
			</div>
		</div>

<div class="modal fade nuevoHotel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="gridSystemModalLabel">Nuevo Hotel</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
					    <label for="inputClave" class="col-sm-3 control-label">Clave</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="inputClave" >
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="inputNombre" class="col-sm-3 control-label">Nombre</label>
					    <div class="col-sm-9">
					      <input type="text" class="form-control" id="inputNombre" >
					    </div>
					  </div>
					  <div class="form-group">
					    <label for="inputEstatus" class="col-sm-3 control-label">Estatus</label>
					    <div class="col-sm-9">
					    	<select class="form-control" id="inputEstatus">
					    		<option value="1" selected>Activo</option>
					    		<option value="0">Inactivo</option>
					    	</select>
					    </div>
					  </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="guardarHotel();">Guardar</button>
			</div>

		</div>
	</div>
</div>



<div class="modal fade editarHotel" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="gridSystemModalLabel">Editar Hotel</h4>
			</div>
			<div class="modal-body">
			<form class="form-horizontal">
				<input type="hidden" id="inputUpId">
				<div class="form-group">
				    <label for="inputClave" class="col-sm-3 control-label">Clave</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="inputUpClave" >
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="inputNombre" class="col-sm-3 control-label">Nombre</label>
				    <div class="col-sm-9">
				      <input type="text" class="form-control" id="inputUpNombre" >
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="inputEstatus" class="col-sm-3 control-label">Estatus</label>
				    <div class="col-sm-9">
				    	<select class="form-control" id="inputUpEstatus">
				    		<option value="1" selected>Activo</option>
				    		<option value="0">Inactivo</option>
				    	</select>
				    </div>
				  </div>
			</form>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<button type="button" class="btn btn-primary" onclick="editarHotel();">Editar</button>
			</div>

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