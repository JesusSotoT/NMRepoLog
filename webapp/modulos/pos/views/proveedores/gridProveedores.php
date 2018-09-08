<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Catálogo Proveedores</title>
		<link rel="stylesheet" href="">
	</head>
	
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../../libraries/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="../../libraries/font-awesome/css/font-awesome.min.css">
	<script src="../../libraries/jquery.min.js"></script>
	<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="js/proveedores.js"></script>
	<!--Select 2 -->
	
	<script src="../../libraries/select2/dist/js/select2.min.js"></script>
	<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />

	<!--Modificaciones RC -->
	<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
	<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
	<script src="../../libraries/export_print/buttons.html5.min.js"></script>
	<script src="../../libraries/export_print/jszip.min.js"></script>
   <!--Button Print css -->
	<link rel="stylesheet" href="../../libraries/dataTable/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

	<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
	<!--<script src="../../libraries/dataTable/js/datatables.min.js"></script>-->
	<script src="../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>
   	
   	<script>
   		$(document).ready(function() {

   			$('#tableGrid').DataTable({
   				dom: 'Bfrtip',
				buttons: [ 'excel' ],
				language: {
					search: "Buscar:",
					lengthMenu:"",
					zeroRecords: "No hay datos.",
					infoEmpty: "No hay datos que mostrar.",
					info:"Mostrando del _START_ al _END_ de _TOTAL_ proveedores",
					paginate: {
						first:      "Primero",
						previous:   "Anterior",
						next:       "Siguiente",
						last:       "Último"
					},
				},
				aaSorting : [[0,'desc' ]]
			});

//			$("#tableGrid_wrapper").find(".dt-buttons").append('<div style="padding-left:5px;padding-right:5px; font-size:20px; margin-bottom:20px;" class="btn"><b>PROVEEDORES</b></div>');			
//			$("#tableGrid_wrapper").find(".dt-buttons").append('<a class="dt-button buttons-print" style="padding-top:0px;padding-bottom:0px;padding-left:0px;padding-right:0px;"><button onclick="newProve();" class="btn btn-primary btn-sm"><i aria-hidden="true" class="fa fa-plus"></i> Nuevo Proveedor</button></a>');
//			$(".buttons-excel").addClass('pull-right');

   		});
   	</script>

   	<body>
   		<div class="container well">
   			<div class="row">
   				<div class="col-sm-8">
   					<h3>Proveedores
   						<button class="btn btn-primary" onclick="newProve();"><i class="fa fa-plus" aria-hidden="true"></i> Nuevo Proveedor</button>
   					</h3>
   				</div>
   			</div>
   			
   			<div class="row">
   				<div class="col-sm-12" style="overflow:auto;">
					<table class="table table-hover table-fixed" style="background-color:#F9F9F9; border:1px solid #c8c8c8;" id="tableGrid">
						<thead>
							<tr>
								<th>ID</th>
								<th>Código</th>
								<th>RFC</th>
								<th>Razón Social</th>
								<th>Contacto</th>
								<th>Teléfono</th>
								<th>Correo Electrónico</th>
								<th>Min.Piezas</th>
								<th>Min.Import.</th>
								<th>Entrega</th>
								<th>Estatus</th>
								<th>Acciones</th> 
							</tr>
						</thead>

						<tbody>
							<?php
								$status="";
								foreach ($proveedores['proveedores'] as $key => $value) {
									if($value['status']==-1){// estaus -1 activado
										$status = '<span class="label label-success">Activo</span>';
									}else{
										$status = '<span class="label label-danger">Inactivo</span>';
									}
									echo '<tr>';
									echo '<td>'.$value['idPrv'].'</td>';
									echo '<td>'.$value['codigo'].'</td>';
									echo '<td>'.$value['rfc'].'</td>';
									echo '<td> <a href="index.php?c=proveedores&f=index&idProveedor='.$value['idPrv'].'" title "Editar">'.$value['razon_social'].'</a> </td>';
									echo '<td>'.$value['nombre'].'</td>';
									echo '<td>'.$value['telefono'].'</td>';
									echo '<td>'.$value['email'].'</td>';
									echo '<td>'.$value['minimo_piezas'].'</td>';
									echo '<td>$'.number_format($value['minimo_importe_pedido'],2).'</td>';
									echo '<td>'.$value['lugar_entrega'].'</td>';
									echo '<td>'.$status.'</td>';
									//echo '<td>'.$value['Autorizacion'].'</td>';
									//echo '<td>'.$value['Estatus'].'</td>';
									//Columna de los botones
									echo '<td>';
									if($value['status']==-1){ // activado
										//echo '<a class="btn btn-primary btn-xs active"><scandir(directory)pan class="glyphicon glyphicon-signal" title="Reporte" enabled = "false"></span></a>';
										echo '<a onclick="borraProve('.$value['idPrv'].');" class="btn btn-danger btn-xs active"><span class="glyphicon glyphicon-remove" title="Desactivar"></span></a> &nbsp;';
										if ($value['limite_credito'] > 0) {
											echo '<a onclick="cuentasP('.$value['idPrv'].');" class="btn btn-default btn-xs active"><span class="glyphicon glyphicon glyphicon-paste" title="Cuentas por pagar"></span></a>';
										}
									}else{ // Desactivado
										echo '<a class="btn btn-primary btn-xs  glyphicon glyphicon-ok" onclick="activaProve('.$value['idPrv'].');" class="btn btn-primary btn-md active" title="Activar"></a>';
									}
									echo '</td>';
									echo '</tr>';
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
</html>
