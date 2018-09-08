<?php include("../../funcionesBD/gridP.php") ?>

	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html lang="sp">
	<head>						
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title></title>	
		
		<LINK href="../../../../netwarelog/catalog/css/view.css" title="estilo" rel="stylesheet" type="text/css" />
		<LINK href="../../../../netwarelog/design/default/netwarlog.css" title="estilo" rel="stylesheet" type="text/css" />

		<LINK href="../../../../netwarelog/catalog/css/estilo.css" title="estilo" rel="stylesheet" type="text/css" />
		<link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"/>		
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
		
		<script src="../../../punto_venta/js/ui.datepicker-es-MX.js"></script>
		<script src="../../js/paginaciongrid.js"></script>

    <link rel="stylesheet" href="../../../../libraries/dataTable/css/datatablesboot.min.css">
<!--    <script src="../../libraries/dataTable/js/datatables.min.js"></script> -->
    <script src="../../../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../../../libraries/dataTable/js/dataTables.bootstrap.min.js"></script>

    <!-- Modificaciones RCA -->
    <link rel="stylesheet" type="text/css" href="../../../../libraries/dataTable/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../../../libraries/dataTable/css/buttons.dataTables.min.css">
    <script src="../../../../libraries/export_print/jquery.dataTables.min.js"></script>
    <script src="../../../../libraries/export_print/dataTables.buttons.min.js"></script>
    <script src="../../../../libraries/export_print/buttons.html5.min.js"></script>
    <script src="../../../../libraries/export_print/jszip.min.js"></script>
		
		<script>
			$(function(){
			$("#preloader").hide();	
			$.datepicker.setDefaults($.datepicker.regional['es-MX']);
			$("#ffin").datepicker({dateFormat: "yy-mm-dd"});
			$("#finicio").datepicker({dateFormat: "yy-mm-dd",onSelect: function (dateText, inst) {
			  var parsedDate = $.datepicker.parseDate('yy-mm-dd', dateText);
				$('#ffin').datepicker('setDate', parsedDate);
				$('#ffin').datepicker( "option", "minDate", parsedDate);
			}});
setTimeout(function(){ 
	      $('#table1').DataTable({
                            dom: 'Bfrtip',
                            buttons: [ 'excel' ],
                            language: {
                                search: "Buscar",
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
}, 3000);


				
			
			});
		</script>
		<link rel="stylesheet" type="text/css" href="../../../../libraries/bootstrap/dist/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="../../css/imprimir_bootstrap.css" />
		<style type="text/css">
		    .btnMenu{
		        border-radius: 0; 
		        width: 100%;
		        margin-bottom: 0.3em;
		        margin-top: 0.3em;
		    }
		    .row
		    {
		        margin-top: 0.5em !important;
		    }
		    h4, h3{
		        background-color: #eee;
		        padding: 0.4em;
		    }
		    .nmwatitles, [id="title"] {
		        padding: 8px 0 3px !important;
		        background-color: unset !important;
		    }
		    .select2-container{
		        width: 100% !important;
		    }
		    .select2-container .select2-choice{
		        background-image: unset !important;
		        height: 31px !important;
		    }
		    @media print{
				#imprimir,#filtros,#excel, .botones, input[type="button"], button, button[type="button"], .btnMenu{
					display:none;
				}
				.table-responsive{
					overflow-x: unset;
				}
				#imp_cont{
					width: 100% !important;
				}
			}
		</style>
	</head>
	
	<body>

		<div class="container" style="width:100%">
			<div class="row">
				<div class="col-sm-1">
				</div>
				<div class="col-sm-10" id="imp_cont">
					<h3 class="nmwatitles text-center">
						Cuenta por pagar
					</h3>
					<h4>Filtro de b&uacute;squeda por cuenta por pagar</h4>
					<div class="row">
						<div class="col-sm-4">
							<label>Cuentas desde:</label>
							<input type="text" readonly="" id="finicio" class="form-control"/>
						</div>
						<div class="col-sm-4">
							<label>Hasta:</label>
							<input type="text" readonly="" id="ffin" class="form-control"/>
						</div>
						<div class="col-sm-4">
							<label>Proveedores:</label>
							<select id="idProveedor" class="form-control">
								<option value="" option="selected">--Selecciona--</option>
				  				<?php echo proveedores_filtro();?>
				  			</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<label>Incluir Saldadas</label>
							<input type="checkbox" id="saldadas">
						</div>
						<div class="col-sm-3">
							<input type="button" value="Buscar cuentas por pagar" onclick="buscacxp();" class="btn btn-primary btnMenu"/></label>
						</div>
						<div class="col-sm-3">
							<input type="button" value="Limpiar filtros" onclick="limpiafiltroscxp();" class="btn btn-primary btnMenu"/></label>
						</div>
						<div class="col-sm-3">
							<img id="preloader" src="../../../../modulos/mrp/images/preloader.gif">
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 tablaResponsiva">
							<div class="table-responsive">
								<span id="grid"> <?php echo gridCxp();?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</body>
	</html> 

