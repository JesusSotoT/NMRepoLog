<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script src='../../libraries/jquery.min.js' type='text/javascript'></script>
<script language='javascript' src='../../libraries/bootstrap/dist/js/bootstrap.min.js'></script>
<script language='javascript' src='../../libraries/datepicker/js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="../../libraries/datepicker/css/bootstrap-datepicker.min.css">
<script language='javascript' src='../../libraries/datepicker/js/bootstrap-datepicker.es.js'></script>
<script type="text/javascript" src="../cont/massxml/massxml.js"></script>
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script type="text/javascript" charset="utf-8">
$(document).ready(function() {
	$('#inicial,#final').datepicker({
				format: "yyyy-mm-dd",
				language: "es"
			});
});

function poliza_manual()
{
	window.parent.agregatab('../../modulos/cont/index.php?c=CaptPolizas&f=Ver','Captura','',0);
}

function buscar()
{
	$.post("ajax.php?c=Almacen&f=listaFacturas",
	{
		inicial : $("#inicial").val(),
		final 	:$("#final").val()
	},
	function(data)
	{
		//alert(data)
		$('#tabla-data').DataTable().destroy();
		var datos = jQuery.parseJSON(data);
                $('#tabla-data').DataTable( {
                    language: {
                        search: "Buscar:",
                        lengthMenu:"Mostrar _MENU_ elementos",
                        zeroRecords: "No hay datos.",
                        infoEmpty: "No hay datos que mostrar.",
                        info:"Mostrando del _START_ al _END_ de _TOTAL_ elementos",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        }
                     },
                     "order": [[ 0, "asc" ]],
                     data:datos,
                     columns: [
                        { data: 'num' },
                        { data: 'fecha' },
                        { data: 'tipo' },
                        { data: 'serie' },
                        { data: 'folio' },
                        { data: 'emisor' },
                        { data: 'concepto' },
                        { data: 'cuenta' },
                        { data: 'total' },
                        { data: 'poliza' },
                        { data: 'receptor' },
                        { data: 'pago' },
                        { data: 'descargar' }
                    ]
                });
                var cantidad = 0;
                $(".importes").each(function(index)
				{
					cantidad += parseFloat($(this).attr('cantidad'))
				});
				alert(cantidad)
	});
}

</script>
<div class="col-xs-12 col-md-10 col-md-offset-1">
	<div class='row' style='border-bottom:4px double #eee;'>
		<div class="col-xs-12 col-md-4" style='text-align:left;'>
			<img src='images/logo_acontia.jpg' style='width:60px;'>
		</div>
		<div class="col-xs-12 col-md-4" style='text-align:center;'>
			<b class='empresa' style='font-size:20px;'><?php echo $datos_empresa['nombreorganizacion'] ?></b>
		</div>
		<div class="col-xs-12 col-md-4" style='text-align:right;'>
			<input type="button" value="Poliza Manual" onclick="poliza_manual()" class="btn btn-info">
			<input type="button" value="Descarga Masiva SAT" onclick="loadXMLDownloaderPage()" class="btn btn-primary">
		</div>
	</div>
	<div class='row' style='margin-top:20px;'>
		<div class="col-xs-12 col-md-3 col-md-offset-3" style='text-align:center;'>
			<div class="input-group">
                <span class="input-group-addon glyphicon glyphicon-calendar" id="basic-addon1"></span>
                <input type="text" class="form-control" value="" id='inicial' placeholder="Fecha Inicial">
             </div>
        </div>
        <div class="col-xs-12 col-md-3" style='text-align:center;'>     
             <div class="input-group">
                <span class="input-group-addon glyphicon glyphicon-calendar" id="basic-addon1"></span>
                <input type="text" class="form-control" value="" id='final' placeholder="Fecha Final">
             </div>
		</div>
		<div class="col-xs-12 col-md-1" style='text-align:center;'>     
             <button id='buscar' class='btn btn-default' onclick='buscar()'>Buscar</button>
		</div>
	</div>
	<div class='row' style='margin-top:20px;'>
		<table id="tabla-data" class="table table-striped table-hover" cellspacing="0" width="100%">
				<thead style='background-color:#337ab7;color:white;'>
					<tr><th>#</th><th>Fecha</th><th>Tipo</th><th>Serie</th><th>Folio</th><th>Emisor</th><th>Concepto</th><th>Cuenta Contable</th><th>Total</th><th>Poliza</th><th>Receptor</th><th>Pago</th><th>Descargar</th></tr>
				</thead>
				<tbody id='trs'>
				</tbody>
			</table>
	</div>
	<div class='row' style='margin-top:20px;border-top:4px double #eee;'>
		totales
	</div>
</div>