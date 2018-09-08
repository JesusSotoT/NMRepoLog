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
	<script src="js/ajustesinventario.js"></script>
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
	</script>

	<body>
		<div class="container well">
			<div class="row">
				<div class='col-sm-8'>
					<h3>
						Ajustes de Inventario
						
						<a class="btn btn-primary" href="index.php?c=inventario&f=recalculoinventario" > 
							<i class="fa fa-plus" aria-hidden="true"></i>  Nuevo Ajuste 
						</a>

					</h3>
				</div>

			</div>


			<div class="row">
				<div class="col-sm-12" style="overflow:auto;">
					<table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
						<thead>
							<tr>
								<th>Fecha</th>
								<th>Responsable</th>
								<th># Productos movidos</th>
								<th>Acciones</th>
							</tr>
						</thead>

						<tbody>
							<?php 
								foreach ($movimientos as $key => $value) { 									echo 	"<tr>
												<td>{$value['fecha']}</td>
												<td>{$value['usuario']}</td>
												<td>{$value['movimientos']}</td>
												<td>
													<button class='verMovimientos' href='#' class='btn btn-primary btn-xs active' title='Editar'>
														<span class='glyphicon glyphicon-edit'></span>
													</button>
												</td>
											</tr>";
								}
							?>

						</tbody>
					</table>
				</div>
			</div>
		</div>



		<div id='modalMovimientos' class="modal fade" tabindex="-1" role="dialog">
	        <div class="modal-dialog modal-lg">
	            <div class="modal-content">
	                <div class="modal-header modal-header-default">
	                    <button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title"> <span id='fechaAjuste' ></span> </h4>
	                </div>

	                <div class="modal-body">
	                    <div style="height:500px;overflow:auto;">
	                        <div class="row">
	                            <div class="col-sm-12">
	                                <table class="table table-bordered" id="tableSale">
	                                    <thead>
	                                        <tr>
	                                            <th>Cantidad</th>
	                                            <th>Producto</th>
	                                            <th>Serie</th>
	                                            <th>Lote</th>
	                                            <th>Almacen Origen</th>
	                                            <th>Almacen Destino</th>
	                                        </tr>
	                                    </thead>
	                                    <tbody id="tablaMovimientos" style="overflow-y: scroll;">

	                                    </tbody>
	                                </table>
	                            </div>
	                        </div>  
	                    </div>                  
	                </div>

	            </div>
	        </div>
	    </div>  

		

		

	</body>
</html>