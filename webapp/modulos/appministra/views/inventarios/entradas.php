<?php 
	header('Content-Type: text/html; charset=utf-8');
?>

<input type='hidden' id='ejercicio_actual' value="<?php echo $configuracionPeriodos['ejercicio_actual']?>">
<input type='hidden' id='periodo_actual' value="<?php echo $configuracionPeriodos['id_periodo_actual']?>">
<input type='hidden' id='periodos_abiertos' value="<?php echo $configuracionPeriodos['periodos_abiertos']?>">
<input type='hidden' id='primer_ejercicio' value="<?php echo $primer_ejercicio?>">
<input type='hidden' id='ultimo_ejercicio' value="<?php echo $ultimo_ejercicio?>">
<script src="../../libraries/select2/dist/js/select2.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/select2/dist/css/select2.min.css" />
<script language='javascript' src='js/bootstrap-datepicker.es.js'></script>

<!-- Modificaciones RC -->
<script src="../../libraries/export_print/jquery.dataTables.min.js"></script>
<script src="../../libraries/export_print/dataTables.buttons.min.js"></script>
<script src="../../libraries/export_print/buttons.html5.min.js"> </script>
<script src="../../libraries/export_print/jszip.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../libraries/dataTable/css/buttons.dataTables.min.css">

<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$("#lay_file").attr("abierto","0").hide();
		$("#producto").select2({'width':'100%'});
		inicializa_movimientos();
		$.fn.modal.Constructor.prototype.enforceFocus = function () {};
		$("#tipo").val(1).trigger("change")
		$("#caracteristicas,#otrascarac").hide();
		var fechaInicial,fechaFinal,fechaActual;
		fechaActual = $("#ejercicio_actual").val()+'-'+$("#periodo_actual").val()+'-01'

		if(parseInt($("#periodos_abiertos").val())) {
			fechaInicial = $("#primer_ejercicio").val()+'-'+'01-01'
			fechaFinal = $("#ultimo_ejercicio").val()+'-'+'12-31'
		} else {
			fechaInicial = $("#ejercicio_actual").val()+'-'+$("#periodo_actual").val()+'-01'
			fechaFinal = $("#ejercicio_actual").val()+'-'+(parseInt($("#periodo_actual").val())+1)+'-00'
		}

		$('#fecha_pedimento,#fecha_fabricacion,#fecha_caducidad').datepicker({ 
			format: "yyyy-mm-dd",
			language: "es"
		});

		$('#fecha_mov').datepicker({
			format: "yyyy-mm-dd",
			language: "es",
			startDate: fechaInicial,
			endDate: fechaFinal
		});
	});

	function mostrar_layout() {
		if(!parseInt($("#lay_file").attr("abierto")))
			$("#lay_file").attr("abierto","1").show("slow");
		else
			$("#lay_file").attr("abierto","0").hide("slow");
	}

	function validar(t) {
		if(t.layout.value == '') {
			alert("Agregue un archivo xls.");
			return false;
		}
	}


	function transeries(idProd) {
		var string = $('#allseries').val();
		var array = string.split(',');
		var i = 0;
		var faltante = $('#cantidad').val() - array.length;

		if (faltante == 0) {
			if(array.length <= 1000) {
				for (i=0; i<=array.length - 1; i++) {
					$('#serie-'+((i*1)+1)).val(array[i].replace(' ', ''));
				};
			} else {
				alert('Excede los mil registros!');
			}
		} else {
			alert ('Hay una diferencia de '+faltante+' registros, favor de verificar');
		}
	}
</script>

<style>
	.row {
		margin-bottom:20px;
	}
	.container {
		margin-top:20px;
	}
</style>

<?php
	require "views/partial/modal-generico.php";
?>
<div class="container well">
	<div class="row">
		<div class="col-xs-12 col-md-12"><h3>Entradas y Salidas de Almacén</h3></div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-md-12 table-responsive">
			<div id='boton_virtual'><button class='btn btn-primary btn-sm' data-toggle="modal" data-target=".bs-example-modal-md" onclick='nuevo_movimiento()'>Nuevo <span class='glyphicon glyphicon-plus'></span></button> <button class='btn btn-primary btn-sm lay' onclick='mostrar_layout()'>Inventario Inicial <span class='glyphicon glyphicon-upload'></span></button>
			<div class='row' id='lay_file'>
				<div class='col-sm-12 col-md-offset-1 col-md-5'>
					<b>Subir inventario inicial mediante layout</b> / <a href='importacion/inventarios.xls'>Descargar</a><br />
					<form action='index.php?c=inventarios&f=subeLayout' method='post' name='archivo' enctype="multipart/form-data" id='arch' onsubmit='return validar(this)'>
						<input type='file' id='layout' name='layout'><br />
						<button type='submit'>Cargar</button>
					</form>
				</div>
			</div>
			</div>
			<table id="tabla-data" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
				<thead>
					<tr><th>Id</th><th>Fecha</th><th>Producto</th><th>Cantidad</th><th>Importe</th><th>Almacen Origen</th><th>Almacen Destino</th><th>Empleado</th><th>Tipo</th><th>Referencia</th><th>Accion</th></tr>
				</thead>
				<tbody id='trs'>
				</tbody>
			</table>
		</div>
	</div>            
</div>
<!-- <script language='javascript' src='../../libraries/dataTable/js/datatables.min.js'></script> -->
<script language='javascript' src='../../libraries/dataTable/js/dataTables.bootstrap.min.js'></script>

<link rel="stylesheet" href="../../libraries/dataTable/css/datatables.min.css">
<link rel="stylesheet" href="../../libraries/dataTable/css/datatablesboot.min.css">
<script language='javascript' src='js/inventarios.js'></script>
<script language='javascript' src='js/bootstrap-datepicker.min.js'></script>
<link rel="stylesheet" type="text/css" href="css/bootstrap-datepicker.min.css">

<!--AQUI ESTAN LOS MODALS-->
<div id='modal-principal' principal-scroll='1' class="modal fade bs-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog modal-md">
	<div class="modal-content">
		<div id='blanco' class='well' style='width:598px;height:100%;z-index:1;position:absolute;color:green;margin-top:180px;'>&nbsp;&nbsp;Cargando...</div>
	  <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
				<h4 id="modal-label">Nuevo Movimiento a Almacen</h4>
			</div>
	  <div class="modal-body well">
		<div class="row">
			<div class="col-xs-4 col-md-offset-1 col-md-4">
			<input type='hidden' id='sinexistencias' value='<?php echo $salidasSinExistencia; ?>'>
				<b>Tipo:</b>
			</div>
			<div class="col-xs-6 col-md-7 input-group">
				<select id='tipo' class='form-control' onchange="inv(1)">
					<option value='0'>Salida</option>
					<option value='1'>Entrada</option>
				</select>
			</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Producto:</b>
				</div>
				<div class="col-xs-6 col-md-7 input-group">
					<select id='producto' onchange="inv(1)">
						<option value='0'>Ninguno</option>
						<?php
						while($l = $listaProductos->fetch_assoc())
						{

							$id_costeo = $l['id_tipo_costeo'];
							if(!$id_costeo)
								$id_costeo = 0;
							echo "<option value='".$l['id']."' precio='".$l['precio']."' unidad='".$l['unidad']."' moneda='".$l['moneda']."' id_costeo='".$id_costeo."'>( ".$l['codigo']." ) ".$l['nombre']."</option>";
						}
						?>
					</select>
				</div>
		</div>
		<div class="row" id='caracteristicas'>
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Caracteristicas:</b>
				</div>
				<div class="col-xs-4 col-md-7 input-group" id='listaCaracteristicas'></div>
		</div>
	
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Cantidad:</b>
				</div>
				<div class="col-xs-6 col-md-7 input-group">
					<input type='text' id='cantidad' class='form-control' onchange='disponibilidad();valcoma(this);costo(this);' onkeypress="return NumCheck(event, this)"><span class="input-group-addon" id='unidad'></span>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Importe:</b>
				</div>
				<div class="col-xs-6 col-md-7 input-group">
					<input type='text' id='importe' class='form-control' onchange='valcoma(this);costo(this)' onkeypress="return NumCheck(event, this)"><span class="input-group-addon" id='moneda'></span>
				</div>
		</div>
		<div class="row" id='otrascarac'>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Almacen Origen:</b>
				</div>
				<div class="col-xs-6 col-md-7 input-group">
					<select id='almacen_origen' class='form-control' onchange='disponibilidad()'>
						<option value='0'>Ninguno</option>
					</select>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Almacen Destino:</b>
				</div>
				<div class="col-xs-6 col-md-7 input-group">
					<select id='almacen_destino' class='form-control'>
						<option value='0'>Ninguno</option>
						<?php
						  $nombre_anterior = ''; 
						  $codigo_sistema_anterior = 'z';
						  
						while($l = $listaAlmacenes->fetch_assoc())
						{
							$num = substr_count($l['codigo_sistema'], '.');
							$vacio = "";
							for($i=1;$i<=$num;$i++)
								$vacio .= "|&nbsp;&nbsp;&nbsp;";
							
								
							$select .= "<option value='".$l['id']."'>$vacio".$l['nombre']."</option>";
							
						}    
						echo $select;
						?>
					</select>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Costo Unitario:</b>
				</div>
				<div class="col-xs-6 col-md-7 input-group">
					<input type='text' id='costo' class='form-control' onchange='valcoma(this);costo(this)' onkeypress="return NumCheck(event, this)">
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Referencia:</b>
				</div>
				<div class="col-xs-6 col-md-7 input-group">
					<input type='text' id='referencia' class='form-control'>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Fecha:</b>
				</div>
				<div class="col-xs-6 col-md-7 input-group">
					<input type='text' id='fecha_mov' class='form-control' value=''>
				</div>
		</div>
	  </div>
			<div class="modal-footer">
				<button class='btn btn-default btn-sm' onclick='guardar_movimiento()'>Guardar</button><button class='btn btn-default btn-sm' onclick='cancelar_movimiento()'>Cancelar</button>
			</div>      
	</div>
  </div>
</div>
<!--Pedimentos**************************-->
<div class="modal fade bs-pedimentos-modal-md" tabindex="-1" role="dialog" aria-labelledby="pedimentos">
  <div class="modal-dialog modal-md">
	<div class="modal-content">
	  <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
				<h4 id="modal-label">Pedimentos</h4>
			</div>
	  <div class="modal-body well">
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b># de Pedimento:</b>
				</div>
				<div class="col-xs-4 col-md-7">
				   <input type='text' id='numero_pedimento'>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Aduana:</b>
				</div>
				<div class="col-xs-4 col-md-7">
				   <input type='text' id='aduana'>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b># de Aduana:</b>
				</div>
				<div class="col-xs-4 col-md-7">
				   <input type='text' id='numero_aduana'>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Tipo de Cambio:</b>
				</div>
				<div class="col-xs-4 col-md-7">
				   <input type='text' id='cambio'>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Fecha:</b>
				</div>
				<div class="col-xs-4 col-md-7">
				   <input type='text' id='fecha_pedimento'>
				</div>
		</div>
	  </div>
			<div class="modal-footer">
				<button class='btn btn-default btn-sm' onclick='genera_pedimentos()'>Guardar</button><button class='btn btn-default btn-sm' onclick="cancelar_pls('pedimentos')">Cancelar</button>
			</div>      
	</div>
  </div>
</div>
<!--Lotes**************************-->
<div class="modal fade bs-lotes-modal-md" tabindex="-1" role="dialog" aria-labelledby="lotes">
  <div class="modal-dialog modal-md">
	<div class="modal-content">
	  <div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
				<h4 id="modal-label">Lotes</h4>
			</div>
	  <div class="modal-body well">
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b># de Lote:</b>
				</div>
				<div class="col-xs-4 col-md-7">
					<input type='text' id='numero_lote'>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Fecha Fabricación:</b>
				</div>
				<div class="col-xs-4 col-md-7">
				   <input type='text' id='fecha_fabricacion'>
				</div>
		</div>
		<div class="row">
				<div class="col-xs-4 col-md-offset-1 col-md-4">
					<b>Fecha Caducidad:</b>
				</div>
				<div class="col-xs-4 col-md-7">
				   <input type='text' id='fecha_caducidad'>
				</div>
		</div>

	  </div>
			<div class="modal-footer">
				<button class='btn btn-default btn-sm' onclick='genera_lotes()'>Guardar</button><button class='btn btn-default btn-sm' onclick="cancelar_pls('lotes')">Cancelar</button>
			</div>      
	</div>
  </div>
</div>

<!--Series**************************-->
<div class="modal fade bs-series-modal-md" tabindex="-1" role="dialog" aria-labelledby="series">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header panel-heading" style='background-color:#337ab7;color:#FFFFFF;'>
				<h4 id="modal-label">Series</h4>
			</div>

			<div>
				<div align="center">
					<textarea data-toggle="tooltip"  title="No debe exceder 1000 series!" rows="2" cols="50" id="allseries" placeholder="Copie y pegue las series separadas por coma (,) y sin espacios."></textarea> <br>
					<button onclick="transeries('+series+');" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-chevron-down"></span> Transferir series</button>
				</div>
				<div> </div>
			</div>

			<div class="modal-body well">
				<div class="row" id='inputSeries'> </div>
			</div>

			<div class="modal-footer">
				<button class='btn btn-default btn-sm' onclick="genera_series()">Guardar</button><button class='btn btn-default btn-sm' onclick="cancelar_pls('series')">Cancelar</button>
			</div>
		</div>
	</div>
</div>
<a id='printer' style='width:10px;color:white;' >.</a>