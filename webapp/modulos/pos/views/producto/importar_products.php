<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Ordenes de Compra</title>
<link rel="stylesheet" href="">

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

<style>
	.row {
		margin-top: 0.5em !important;
	}
	h5, h4, h3 {
		background-color: #eee;
		padding: 0.4em;
	}
	.modal-title {
		background-color: unset !important;
		padding: unset !important;
	}
	.nmwatitles, [id="title"] {
		padding: 8px 0 3px !important;
		background-color: unset !important;
	}
	@media only screen and (max-width: 520px){
		.smart{
			font-size: 2em !important;
			margin-left: 2em !important;
		}
	}
	.btn2{
		background-color: white; 
		color: 40542a; 
		opacity: 0.8;
		border-radius: 10px; 
		padding: 0.4em 1.5em;
		margin-bottom: 0.5em;
		margin-right: 1em;
		border-color: transparent;
	}
	.btn2:hover{
		background-color: 472036;
		color: white;
	}
	.btn3 {
		background-color: transparent;
		border: 1px solid white;
		color: white;
		border-radius: 3px;
		padding: 0.4em 0.4em;
		margin-bottom: 0.5em;
		margin-right: 1em;
		margin-top: 1em;
	}
	.btn3:hover {
		background-color: white;
	}
</style>
<!-- ///////////////////////////// -->	 

<div class="container">
	<div class="row">
		<div class="col-md-1 col-sm-1"> </div>
		<div class="col-md-15 col-sm-15">
			<h3 class="nmwatitles text-center">Importar productos</h3>
			<section style="min-height: 650px; background: transparent url('./images/productos.png') no-repeat scroll center center / cover ;">
				<br> <br>
				<div class="row">
					<div class="col-md-9 col-sm-9 col-xs-9">
						<label style="font-weight: 100; font-size: 4.5em; padding-top: 0.75em; padding-left: 0.8em; color: white; letter-spacing: 0.03em;"></label>
					</div>
					<br> <br>
					<div class="col-md-2 col-sm-2 col-xs-2 text-right">
						<button class="btn3" onclick="$('#modal2').modal('show');" onmouseover="$('#sr_img').attr('src', './images/icono_nota_gris.png');" onmouseout="$('#sr_img').attr('src', './images/icono_nota_blanco.png');"><img id="sr_img" src="./images/icono_nota_blanco.png" style="width: 3em;"></button>
					</div>
				</div>
				<div class="row" style="margin-top: 0.5em !important;">
					<div class="col-md-10 col-sm-10 col-xs-10">
						<label class="smart" style="color: #2795C8; font-weight: 500; font-size: 5em; letter-spacing: 0.03em;">&nbsp; </label>
					</div>
				</div>
				<?php
					$url =  'index.php?c=producto&f=subeLayout';
 				?>
 				<form action=<?php echo $url; ?> method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
					<div class="row" style="margin-top: 15em !important;">
						<div class="col-md-12 text-center">
							<button type="button" class="btn2 btn_2" style="font-size: 1.5em;">&nbsp;&nbsp;Descargar&nbsp;&nbsp;</button>
							<button type="button" class="btn2 btn_1" style="font-size: 1.5em;">&nbsp;&nbsp;&nbsp;Examinar&nbsp;&nbsp;&nbsp;</button>
							<button type="submit" class="btn2 " type='submit' onclick='cargar_productos()' style="font-size: 1.5em;">Previsualizar</button>
                			<input type='file' size="100" name="layout" class="hidden myfile">
                		</div>
                	</div>
                </form>
				<script type="text/javascript">
					$(".btn_1:first").on("click", function(){ $(".myfile:first").click(); });
					$(".btn_2:first").on("click", function(){ $('#modal').modal('show'); });
				</script>                
      		</section>
		</div>
	</div>
</div>

<div id='modal' class="modal fade" tabindex="-1" role="dialog" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Descargar plantillas</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <img src='../../img/xls_icon.gif'> <a href='importacion/productos.xls'>Descarga la plantilla para los productos</a>
            <hr/>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-md-6 col-md-offset-6">
          <input class="btn btn-danger btnMenu" type='button' value='Cerrar' onclick="$('#modal').modal('hide');">
        </div>
      </div>
    </div>
  </div>
</div>

<div id='modal2' class="modal fade" tabindex="-1" role="dialog" >
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Información</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<b>Nota:</b><br>
						·La lista de productos no debe rebasar los 900 elementos por carga.<br>
						·No se deben insertar comillas (") ni comillas simples (') en ningún campo<br>
						·En los campos de stock y en el precio solo deben insertarse números y ningún otro caracter<br>
						·La clave/código no debe contener espacios ni caracteres especiales, solo números y letras<br>
						.El campo tipo producto puede ser "Producto","PRODUCIR PRODUCTO","MATERIAL DE PRODUCCION","KIT DE PRODUCTOS",<br>
						"PRODUCTO DE CONSUMO" y "SERVICIO".<br>
						.Las unidades , la unidad base es "Unidad".<br>
						.Se debe indicar en el campo vendiable con un "Si" o "No" si el producto es vendible.<br>
						.Para importar con proveedor, tiene que estar previamente registrado y agregarlo con el nombre tal cual esta registrada además de su costo.<br>
						.Para importar con Almacen se tiene que tener previamente registrado el almacen, así como la sucursal tendrá que ser la correspondiente al almacén. <br>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div class="col-md-6 col-md-offset-6">
					<input class="btn btn-danger btnMenu" type='button' value='Cerrar' onclick="$('#modal2').modal('hide');">
				</div>
			</div>
		</div>
	</div>
</div>
