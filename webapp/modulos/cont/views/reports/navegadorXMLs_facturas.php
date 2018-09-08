<script src="../cont/js/jquery-1.10.2.min.js"></script>
<script src="../cont/js/jquery-ui.js"></script>
<script src="js/redirect.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="../../libraries/jquery.tablesorter.js" type="text/javascript" language="javascript" charset="UTF-8"></script>
<script src="js/moment.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="../../libraries/bootstrap/dist/js/bootstrap.min.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<script language='javascript'>

$(document).ready(function () {
	generarTotal();
	getCountRows();
	$("#checkAll").change(function() {
  	$('input:checkbox').not(this).prop('checked', this.checked);
	});
	$('#fecha_antes').datepicker({dateFormat: 'dd-mm-yy'});
	$('#fecha_despues').datepicker({dateFormat: 'dd-mm-yy'});
	$("#listado").tablesorter({ 
		headers: { 
			 3: { sorter:false},
			14: { sorter:false}
    } 
  }); 
});

function getCountRows() {
	var count;
	count = $('#listado tbody tr').length;
	
	var actualRows = $('#listado tbody tr').filter(function () {
		return $(this).attr('visible') === 'true';
	}).length;
	$('#rowCount').html("Mostrando <b>"+ actualRows +"</b> de <b>" + count + "</b> facturas.");
}

function generarTotal() {
	var sum = 0, cantidad, total;
	$('#listado tbody tr').each(function(index) {
		cantidad = $('td:nth-child(9)', this).attr('cantidad');
		isVisible = $(this).attr('visible');
		if (isVisible === 'true') {
			sum = parseFloat(sum) + parseFloat(cantidad);
		}
		total = numberWithCommas(sum.toFixed(2));
	});
	if (total == undefined) {
		total = 0.00;
	}
	$('#total').html("$ "+total);
}

function agregarFiltro() {
  // Declarando variables 
  var fecha_antes, fecha_despues, filter, table, tr, td, i, fa, fd, fc, contentBtn;
  fecha_antes = $('#fecha_antes').val();
  fecha_despues = $('#fecha_despues').val();
  table = document.getElementById("listado");
  tr = table.getElementsByTagName("tr");

	fa = moment(fecha_antes, 'DD-MM-YYYY');
	fd = moment(fecha_despues, 'DD-MM-YYYY');

	//Validamos que la fecha antes / inicial se mayor que la final 
	if (fa > fd) {
		alert("Debe ingresar una fecha inicial menor a la final.");
	} else {
		//Validamos que los campos no esten vacios
		if (fecha_antes == '') {
			alert("Debe seleccionar un valor para la fecha inicial");
			fecha_antes.css('border', '1px solid #af0000');
		} else if (fecha_despues == ''){
			alert("Debe seleccionar un valor para la fecha final");
			fecha_despues.css('border', '1px solid #af0000');
		} else {
		  // Recorremos todas las filas para ver cuales entran en el rango de fecha
		  $("#listado tbody tr").each(function(index) {
		  	td = $("td:nth-child(1)",this).text();
		  	td = td.split("T");
		  	fc = moment(td[0], 'YYYY-MM-DD');
		  	/* fc = fecha a checar
					 fa = fecha antes / fecha inicial
					 fd = fecha despues / fecha final */
		  	if (fc >= fa && fc <= fd) {
		  		this.style.display = '';
		  		this.setAttribute('visible', true);
		  	} else {
		  		this.style.display = 'none';
		  		this.removeAttribute('visible');
		  	}
		  });
		  $('#borrar_filtro').css('visibility', 'visible');
		  generarTotal();
		  getCountRows();
		}
	}
}

$('.listado tbody').on('change', function() {
	generarTotal();
	getCountRows();
});

function borrarFiltro() {
	 $("#listado tbody tr").each(function(index)
	 {
	 	this.style.display = '';
	 	$(this).attr('visible','true')
	 });
	 $('#fecha_antes').val('');
	 $('#fecha_despues').val('');
	 $('#borrar_filtro').css('visibility', 'hidden'); 
	 generarTotal();
	 getCountRows();
} 


$(function(){
//EXTENDIENDO LA FUNCION CONTAINS PARA HACERLO CASE-INSENSITIVE
  $.extend($.expr[":"], {
"containsIN": function(elem, i, match, array) {
return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
}
});
//-------------------------------------------------------------

	// INICIA GENERACION DE BUSQUEDA
			$("#busqueda").bind("keyup", function(evt){
				//console.log($(this).val().trim());
				if(evt.type == 'keyup')
				{
					$(".listado tbody tr:containsIN('"+$(this).val().trim()+"')").css('display','table-row');
					$(".listado tbody tr:containsIN('*1_-{}*')").css('display','table-row');
					$(".listado tbody tr:not(:containsIN('"+$(this).val().trim()+"'))").css('display','none');
					$(".listado tbody tr:not(:containsIN('"+$(this).val().trim()+"'))").attr('visible', false);
					$(".listado tbody tr:containsIN('*1_-{}*')").attr('visible', true);
					generarTotal();
					getCountRows();
					if($(this).val().trim() === '')
					{
						$(".listado tbody tr").css('display','table-row');
						$(".listado tbody tr").attr('visible', true);
						generarTotal();
						getCountRows();
					}
				}
			});
		// TERMINA GENERACION DE BUSQUEDA
		$('[data-toggle="tooltip"]').tooltip().click(function(event) {
		  event.preventDefault();
		});;

});

 	function eliminar(archivo)
 	{
 		var confirmacion = confirm("Esta seguro de eliminar este archivo: \n"+archivo);
 		if(confirmacion)
 		{
 			$.post("ajax.php?c=Reports&f=EliminarArchivo",
		 	{
				Archivo: archivo
			 },
			 function(data)
			 {
			 	location.reload();
				alert('Eliminado')
			 });
 		}
 	}

 	function actualiza()
 	{
 		location.reload()
 	}

 	function check_file()
 	{
  	var ext = $('#factura').val();
  	ext = ext.split('.');
  	ext = ext.slice(-1)[0];
 		console.log('extension archivo: '+ext)
  	if(ext != 'zip' && ext != 'xml')
  	{
  		alert("Archivo Inválido \nEl archivo debe tener una extensión xml o zip.");
  		$("#factura").val('');
  	}
  }

  function generaexcel()
  {

  	$().redirect('views/fiscal/generaexcel.php', {'cont': $('#imprimible').html(), 'name': 'Facturas Almacen Digital'});
  }

  function canceladas()
  {
  	$(".btn").attr('disabled',true)
  	$("a,input:file,img").hide();
  	$('#canc_load').css('display','block');
  	$.post("ajax.php?c=CaptPolizas&f=canceladas",
			function(data)
			{
				console.log('Canceladas: ',data);
				$('#canc_load').css('display','none');
				if(parseInt(data))
				{
					alert('Hubieron '+data+' cancelados')
					location.reload();
				}
				else
				{
					alert('No hubo cancelados')
					$(".btn").attr('disabled',false)
					$("a,input:file,img").show();
				}
			});
  }
$(document).ready(function(){
	<?php
	if($bancos==1){
		$link1 = "/ <a href='index.php?c=Reports&f=almacenXML&bancos=1'>Documentos Bancarios</a>";
		$link2 = "/ <b>Documentos Bancarios</b>";

	}else{
		$link1 = "";
		$link2 = "";

	}
	if(isset($_GET['bancos']) ){
		$tipo = "Documento";
	}else{
		$tipo = "Poliza";
	}
	?>
	$('#canc_load').css('display','none');
});
</script>
<script language='javascript' src='js/pdfmail.js'></script>
<style>
.hide-excel{
	display: none;
}
#rowCount{
	padding-left: 1em;
	text-align: left;
}
.sorter-false {
	cursor: default;
}
.aa > .theader-displayer:after {
  content: "";
  height: 0;
  width: 0;
  display: block;
  position: absolute;
  bottom: .8em;
  right: .1em;
  border-bottom: 3px solid black;
	border-top: 3px solid transparent;
	border-left: 3px solid transparent;
	border-right: 3px solid transparent;
}
.aa > .theader-displayer:before {
  content: "";
  height: 0;
  width: 0;
  display: block;
  position: absolute;
  bottom: .2em;
  right: .1em;
  border-bottom: 3px solid transparent;
	border-top: 3px solid black;
	border-left: 3px solid transparent;
	border-right: 3px solid transparent;
}
.itemList
{
	height:50px;
}
.btn-block {
  margin-top: 0 !important;
}
th {
  position: relative;
  cursor: pointer;
}
button > .material-icons, div > .material-icons {
  font-size: 1.35em;
}
th, tr {
  text-align: center;
}
th{
  padding: .5em;
  word-break: keep-all;
}
td {
	padding: .5em;
	hyphens: auto;
	word-break: break-all !important;
}
table {
	border: #ddd;
}
.btn-block{
  	border-radius: 0;
  	width: 100%;
  	margin-bottom: 0.3em;
  	margin-top: 0.3em;
}
.row
{
  	margin-top: 0.5em !important;
}
.title-grey-bg{
  	background-color: #eee;
  	padding: 0.4em;
}
.modal-title{
	background-color: unset !important;
	padding: unset !important;
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
.twitter-typeahead{
	width: 100% !important;
}
.tablaResponsiva{
    max-width: 100vw !important;
    display: inline-block;
}
.p0{padding:0;}
    .glyphicon-refresh-animate {
-animation: spin .7s infinite linear;
-ms-animation: spin .7s infinite linear;
-webkit-animation: spinw .7s infinite linear;
-moz-animation: spinm .7s infinite linear;
}
@keyframes spin {
    from { transform: scale(1) rotate(0deg);}
    to { transform: scale(1) rotate(360deg);}
}
  
@-webkit-keyframes spinw {
    from { -webkit-transform: rotate(0deg);}
    to { -webkit-transform: rotate(360deg);}
}

@-moz-keyframes spinm {
    from { -moz-transform: rotate(0deg);}
    to { -moz-transform: rotate(360deg);}
}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<h3 class="nmwatitles text-center">
				Almacen de XML's Facturas
				<a href='javascript:generaexcel()' id='excel'><img src='images/images.jpg' width='35px'></a>
			</h3>
			<h4 class="title-grey-bg">Buscador</h4>
			<div class="row">
				<div class="col-md-3">
					<input type='text' class="form-control" id='busqueda' name='busqueda' placeholder='Buscar'>
				</div>
				<div class="col-md-2">
					<input type='button' value='Actualizar' onclick='actualiza()' class='btn btn-primary btn-block'>
        </div>
        <?php
        if(!isset($_GET['asignadas']) && !isset($_GET['bancos']) && !isset($_GET['canceladas']))
        {
        	$carpeta_dir = 'temporales';
        ?>
        <div class="col-md-2">
	        <input type='button' value='Descarga Masiva SAT' onclick='loadXMLDownloaderPage()' class='btn btn-primary btn-block'>
          <script type="text/javascript" src="../cont/massxml/massxml.js"></script>
				</div>
				<div class="col-md-2">
					<input type='button' value='Validar Canceladas' onclick='canceladas()' class='btn btn-primary btn-block'>
				</div>
				<?php
				}
				?>
				<div class="col-md-2">
					<div id="canc_load" class="row" style="display:none;font-size:12px;padding:2px;">
      			<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>
    			</div>
				</div>
			</div>

			<!-- Filtrar por fecha -->
			<h4 class="title-grey-bg">Filtrar por fecha:</h4>
			<div class="form-inline">
				<!-- Fecha inicial -->
			  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
			    <div class="input-group-addon"><i class="material-icons">date_range</i></div>
			  	<input type="text" class="form-control" name="fecha_antes" id="fecha_antes" placeholder="Fecha inicial">
			  </div>
			  <!-- Fecha final -->
			  <div class="input-group mb-2 mr-sm-2 mb-sm-0">
			    <div class="input-group-addon"><i class="material-icons">date_range</i></div>
			    <input type="text" class="form-control" name="fecha_despues" id="fecha_despues" placeholder="Fecha final">
			  </div>
			  <!-- Mandar filtro -->
				<button id="agregar_filtro" class="btn btn-sm" onclick="agregarFiltro()" style="background: #eee; border: 1px solid #ccc;">
					<i class="material-icons">send</i>
				</button>
				<!-- Borrar filtro -->
				<button id="borrar_filtro" class="btn btn-sm" onclick="borrarFiltro()" style="background: #eee; border: 1px solid #ccc; visibility: hidden;">
					<i class="material-icons">delete</i>
				</button>
			</div> <!-- // Filtrar por fecha -->


			<h4 class="title-grey-bg">Almacenar facturas</h4>
			<section>
				<?php
					if(isset($_GET['asignadas'])){
						$carpeta_dir = '[1-9]*';
						$status = "Tipo de Poliza";
				?>
				<div class="row">
					<div class="col-md-6">
						<a href='index.php?c=Reports&f=almacenXML'>Temporales</a> / <b>Asignadas</b> <?php echo $link1;?> / <a href='index.php?c=Reports&f=almacenXML&canceladas=1'>Canceladas</a>
					</div>
				</div>
				<?php
					}elseif(!isset($_GET['asignadas']) && !isset($_GET['bancos']) && !isset($_GET['canceladas'])){
						$status = "Status";
				?>
				<form name='fac' id='fac' action='' method='post' enctype='multipart/form-data'>
					<div class="row">
						<div class="col-md-6">
							<label>Subir factura(s) xml o zip:</label>
							<?php $tip = "Solo se admiten archivos con extension zip o xml, en caso de ser zip estos deben llamarse igual que la carpeta que contiene los xmls."; ?>
							<button class="btn-default btn-block" style="width: auto !important;" data-toggle="tooltip" title="<?php echo $tip; ?>">?</button>
							<input type='file' name='factura[]' id='factura' onchange='check_file()'>
						</div>
						<div class="col-md-2">
							<input type='hidden' name='plz' id='plz' value='<?php echo $numPoliza['id']; ?>'>
							<span id='verif' style='color:green;display:none;'>Verificando...</span>
							<input type='submit' id='buttonFactura' value='Asociar Facturas' class="btn btn-primary btn-block">
						</div>
					</div>
				</form>
				<div class="row">
					<div class="col-md-6">
						<b>Temporales</b> / <a href='index.php?c=Reports&f=almacenXML&asignadas=1'>Asignadas</a> <?php echo $link1; ?> / <a href='index.php?c=Reports&f=almacenXML&canceladas=1'>Canceladas</a>
					</div>
				</div>
				<?php
					}
					elseif(isset($_GET['bancos'])){
						$carpeta_dir = 'documentosbancarios';
						$status = "Status";
				?>
						<div class="row">
							<div class="col-md-6">
								<a href='index.php?c=Reports&f=almacenXML'>Temporales</a> / <a href='index.php?c=Reports&f=almacenXML&asignadas=1'>Asignadas</a> / <b>Documentos Bancarios</b> / <a href='index.php?c=Reports&f=almacenXML&canceladas=1'>Canceladas</a>
							</div>
						</div>
				<?php
					}
					if(isset($_GET['canceladas'])){
						$carpeta_dir = 'canceladas';
						$status = "Status";
					?>
						<div class="row">
							<div class="col-md-6">
								<a href='index.php?c=Reports&f=almacenXML'>Temporales</a> / <a href='index.php?c=Reports&f=almacenXML&asignadas=1'>Asignadas</a> <?php echo $link1;?> / <b>Canceladas</b>
							</div>
						</div>
					<?php
						}
					?>
			</section>
			<section id='imprimible'>
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="table-responsive">
							<table border='1' class='listado table-striped' id="listado">
								<thead>
									<tr class='aa' style="background: rgb(51, 122, 183); color:white;
									  z-index: 100; border-bottom: 1px solid rgb(25, 61, 91) !important;
									  border: 1px solid rgb(30, 102, 160) !important;">
										<th class="theader-displayer" style="width: 6.66% !important;color:white;">Fecha Timbre</th>
										<th class="theader-displayer" style="width: 6.66% !important;">RFC</th>
										<th class="theader-displayer" style="width: 6.66% !important;">Razón Social</th>
										<th class="sorter-false" 			style="width: 6.66% !important;"></th>
										<th class="theader-displayer" style="width: 6.66% !important;"><?php echo $tipo;?></th>
										<th class="theader-displayer" style="width: 6.66% !important;">Tipo de Factura</th>
										<th class="theader-displayer" style="width: 6.66% !important;">Sub-Total</th>
										<th class="theader-displayer" style="width: 6.66% !important;">Impuestos IVA</th>
										<th class="theader-displayer" style="width: 6.66% !important;">Total</th>
										<th class="theader-displayer" style="width: 6.66% !important;">Folio</th>
										<th class="theader-displayer" style="width: 9.66% !important;">UUID</th>
										<th class="theader-displayer" style="width: 6.66% !important;">Metodo de Pago</th>
										<th class="theader-displayer" style="width: 6.66% !important;">Fecha de Subida</th>
										<th class="theader-displayer" style="width: 7.66% !important;"><?php echo($status); ?></th>
										<th class="sorter-false" 			style="width: 2.66% !important;">
											<input type="checkbox" id="checkAll" data-sorter="false">
										</th>
										<th class="hide-excel">Dirección de Emisor</th>
										<th class="hide-excel">Dirección de Receptor</th>
										<th class="hide-excel">Versión Factura</th>

									</tr>
								</thead>
								<tbody>
									<?php
										
										$n = 2;
										
										if(isset($_COOKIE['inst_lig']))
											$n = 10;
										$dir = $this->path()."xmls/facturas/$carpeta_dir/*.xml";

										//echo $dir;
										// Abrir un directorio, y proceder a leer su contenido
										//POSIBLE PAGINACION
										//$archivos = array_slice(glob($dir,GLOB_NOSORT),0,30);
										$archivos = glob($dir,GLOB_NOSORT);
										array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);
										$color = '';
										$contador=0;
										global $xp;
										
										if(!isset($_GET['bancos']))
										{
											foreach($archivos as $file)
											{
												//echo $file."<br />";
												$soloruta = str_replace("/".basename($file), '', $file);
												$carpeta = explode('/',$file);

												if($carpeta[$n] != 'repetidos' && $carpeta[$n] != 'documentosbancarios') 
												{
													//Cargamos el xml
													$aa = simplexml_load_file($file);
													//Validamos que sea la version 3.2
													if (isset($aa['version']) && $aa['version'] == 3.2) 
													{
														//Obtenemos el contenido del archivo
														$texto 	= file_get_contents($file);
														//Generamos un nuevo xml
														$xml 	= new DOMDocument();
														//Le añadimos el contenido del archivo anterior
														$xml->loadXML($texto);
														//Obtenemos las rutas del xml
														$xp = new DOMXpath($xml);
                						$data['version'] = $aa['version'];
                						$data['rfc'] = $this->getpath("//@rfc");
                						$data['uuid'] = $this->getpath("//@UUID");
                						$data['calle'] = $this->getpath("//@calle");
                						$data['folio'] = $this->getpath("//@folio");
                						$data['total'] = $this->getpath("//@total");
                						$data['unidad'] = $this->getpath("//@unidad");
                						$data['MesFin'] = $this->getpath("//@MesFin");
														$data['nombre'] = $this->getpath("//@nombre");
														$data['importe'] = $this->getpath("//@importe");
                						$data['impuesto'] = $this->getpath("//@impuesto");
                						$data['cantidad'] = $this->getpath("//@cantidad");
                						$data['subtotal'] = $this->getpath("//@subTotal");
                						$data['nomina'] = $this->getpath("//@NumEmpleado");
                						$data['descuento'] = $this->getpath("//@descuento");
                						$data['descripcion'] = $this->getpath("//@descripcion");
                						$data['descripcion2'] = $this->getpath("//@descripcion");
														$data['metodoDePago'] = $this->getpath("//@metodoDePago");
                						$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
                						$data['valorUnitario'] = $this->getpath("//@valorUnitario");
                						$data['tipoDeComprobante'] = $this->getpath("//@tipoDeComprobante");
														//Mientras tenga titulos
														if($namespaces = $aa->getNamespaces(true)){
															if(!$data['MesFin']){
																//Obtenemos los nodos hijos del xml
																$child = $aa->children($namespaces['cfdi']);
																//Obtenemos los datos de emisor
																if($child->Emisor){
																	foreach($child->Emisor[0]->attributes() AS $a => $b){
																		if($a == 'rfc'){
																			$rfcEmisor = $b;
																		}
																		if($a == 'nombre'){
																			$nombreEmisor = $b;
																		}
																	}
																} 
																else {
																	$rfcEmisor = "No Disponible";
																	$nombreEmisor = "No Disponible";
																}
																if ($child->Emisor->DomicilioFiscal){
																	$data['calleEmisor'] = '';
																	foreach ($child->Emisor->DomicilioFiscal[0]->attributes() as $campo => $valor){
																		$data['calleEmisor'] .= strtoupper($valor." ");
																	}
																} 
																else{
																	$data['calleEmisor'] = "-";
																}
																//Obtenemos los datos de receptor
																if($child->Receptor){
																	foreach($child->Receptor[0]->attributes() AS $a => $b){
																		if($a == 'rfc'){
																			$rfcReceptor = $b;
																		}
																		if($a == 'nombre'){
																			$nombreReceptor = $b;
																		}
																	}
																}
																if ($child->Receptor->Domicilio) {
																	$data['calleReceptor'] = '';
																	foreach ($child->Receptor->Domicilio[0]->attributes() as $campo => $valor) {
																		$data['calleReceptor'] .= strtoupper($valor." ");
																	}
																} 
																else 
																{
																	$data['calleReceptor'] = "-";
																}
																$encontro = 0;
																if($rfcEmisor == $RFCInstancia) 
																{
																	$tipoDeComprobante = 'Ingreso';
																	$rfcTercero = $rfcReceptor;
																	$nombreTercero = $nombreReceptor;
																	$encontro = 1;
																}
																if($rfcReceptor == $RFCInstancia) 
																{
																	$tipoDeComprobante = 'Egreso';
																	$rfcTercero = $rfcEmisor;
																	$nombreTercero = $nombreEmisor;
																	$encontro = 1;
																}
																//Si no encuentra emisor o receptor se le asigna Otro
																if(!$encontro) 
																{
																	$tipoDeComprobante = 'Otro';
																	$rfcTercero = $rfcEmisor;
																	$nombreTercero = $nombreEmisor;
																}
																//Si es nomina...
																if($data['nomina']) 
																{ 
																	$tipoDeComprobante = "Nomina";
																}
																//Obtenemos el tipo de comprobante
																$data['tipocomprobante'] = $tipoDeComprobante;
																if($child->Impuestos) {
																	$totalImpuestosTrasladados = 0;
																	foreach($child->Impuestos[0]->attributes() AS $a => $b){
																		if($a == 'totalImpuestosTrasladados'){
																			$totalImpuestosTrasladados = $b;
																		}
																	}
																	/*if (isset($child->Impuestos->Traslados->Traslado)) {
																		$totalImpuestosTrasladados = $child->Impuestos->Traslados->Traslado->attributes()->importe;
																		var_dump($child->Impuestos->Traslados);
																		echo("<br>");
																	}*/
																}
																//Recorremos el hijo "Complemento" para obtener los valores
																if($child->Complemento){
																	foreach($child->Complemento[0]->children($namespaces['tfd'])->attributes() AS $a => $b){
																		if($a == 'FechaTimbrado'){
																			$FechaTimbrado = $b;
																		}
																		if($a == 'UUID'){
																			$UUID = $b;
																		}
																	}
																//Si no encuentra nodos, la fecha de timbrado se muestra como "No Disponible"	
																} 
																else {
																	$FechaTimbrado = 'No Disponible.';
																}
																$importes = 0;
																//Obtenemos el concepto
																for($i=0;$i<=(count($child->Conceptos->Concepto)-1);$i++){
																	foreach($child->Conceptos->Concepto[$i]->attributes() AS $a => $b){
																		if($a == 'importe'){
																			$importes += floatval($b);
																		}
																	}
																}
															} 
															else {
																$rfcTercero = $this->getpath("//@RFCRecep");
																if(!$rfcTercero) { 
																	$rfcTercero = ""; 
																}
																$data['rfc'] = $rfcTercero;
																$nombreTercero = $this->getpath("//@NomDenRazSocR");
																$FechaTimbrado = $this->getpath("//@FechaTimbrado");
																$data['FechaTimbrado'] = $FechaTimbrado;
																$tipoDeComprobante = "Retencion e Inf. Pagos ";
																$data['tipocomprobante'] = $tipoDeComprobante;
																$importes = "Total Retencion\n<b>".number_format($this->getpath("//@montoTotOperacion"),2,'.',',')."</b>";
																//pongo este campo como total porq en el visor del sat este monto lo pone como el total
																$data['total'] = $this->getpath("//@montoTotOperacion");
																$data['montoTotGrav'] = $this->getpath("//@montoTotGrav");
																$data['montoTotExent'] = $this->getpath("//@montoTotExent");
																$data['montoTotRet'] = $this->getpath("//@montoTotRet");
																$data['complemento'] = $this->ReportsModel->complementoRetencion($this->getpath("//@CveRetenc"));
																if($data['complemento']==25) {
																	$data['complemento'].="->".$this->getpath("//@DescRetenc");
																}
																$data['nombre'] = $nombreTercero;
																$data['BaseRet'] = $this->getpath("//@BaseRet");
																$data['Impuesto'] = $this->getpath("//@Impuesto");
																$data['montoRet'] = $this->getpath("//@montoRet");
																$data['TipoPagoRet'] = $this->getpath("//@TipoPagoRet");
																$data['metodoDePago'] = $this->getpath("//@metodoDePago");
															}
													
															if(!$data['MesFin'])
															{
																$importes = number_format($importes,2);
															}
														}
													} 
													elseif(isset($aa['Version']) && $aa['Version'] == 3.3)
													{

														//Obtenemos el contenido del archivo
														$texto 	= file_get_contents($file);
														//Generamos un nuevo xml
														$xml 	= new DOMDocument();
														//Le añadimos el contenido del archivo anterior
														$xml->loadXML($texto);
														//Obtenemos las rutas del xml
														$xp = new DOMXpath($xml);		
														$data['version'] = $aa['Version'];
	              						$data['uuid'] = $this->getpath("//@UUID");
	              						$data['calle'] = $this->getpath("//@calle");
	              						$data['folio'] = $this->getpath("//@Folio");
														$data['nombre'] = $this->getpath("//@nombre");
	              						$data['subtotal']	= $this->getpath("//@SubTotal");
	              						$data['impuesto'] = $this->getpath("//@Impuesto");
	              						$data['nomina'] = $this->getpath("//@NumEmpleado");
														$data['metodoDePago'] = $this->getpath("//@MetodoPago");
	              						$data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
	              						$data['valorUnitario'] = $this->getpath("//@valorUnitario");
                						$data['tipoDeComprobante'] = $this->getpath("//@TipoDeComprobante");

                						$FechaTimbrado = $data['FechaTimbrado'];
                						$importes = number_format(floatval($aa['SubTotal'])); 
                						$data['total'] = floatval($aa['Total']);

			                      //Obtenemos los titulos
			                      if($namespaces = $aa->getNamespaces(true)){
		                      		//Obtenemos hijos
															$child = $aa->children($namespaces['cfdi']);
															//Obtenemos los datos de emisor
															if($child->Emisor) {
																foreach($child->Emisor[0]->attributes() AS $campo => $valor) {
																	if($campo == 'Rfc') {
																		$rfcEmisor = $valor;
																	}
																	if($campo == 'Nombre') {
																		$nombreEmisor = $valor;
																	}
																}
															} else {
																$rfcEmisor = "No Disponible";
																$nombreEmisor = "No Disponible";
															}
															if ($child->Emisor->DomicilioFiscal) {
																foreach ($child->Emisor->DomicilioFiscal[0]->attributes() as $campo => $valor) {
																	if ($campo == "Calle") {
																		$data['calleEmisor'] = $valor;
																	}
																}
															} else {
																$data['calleEmisor'] = "-";
															}
															//Obtenemos los datos de receptor
															if($child->Receptor) {
																foreach($child->Receptor[0]->attributes() AS $campo => $valor) {
																	if($campo == 'Rfc') {
																		$rfcReceptor = $valor;
																	}
																	if($campo == 'Nombre') {
																		$nombreReceptor = $valor;
																	}
																}
															}
															if ($child->Receptor->Domicilio) {
																foreach ($child->Receptor->Domicilio[0]->attributes() as $campo => $valor) {
																	if ($campo == "Calle") {
																		$data['calleReceptor'] = $valor;
																	}
																}
															} else {
																$data['calleReceptor'] = "-";
															}
															if (isset($data['tipoDeComprobante'])) {
																if ($data['tipoDeComprobante'] == "I") {
																	$data['tipoDeComprobante'] = "Ingreso";
																} else if($data['tipoDeComprobante'] == "E"){
																	$data['tipoDeComprobante'] = "Egreso";
																} else {
																	$data['tipoDeComprobante'] = "Otro";
																}
															}

															$encontro = 0;
																if($rfcEmisor == $RFCInstancia) {
																	$tipoDeComprobante = 'Ingreso';
																	$rfcTercero = $rfcReceptor;
																	$nombreTercero = $nombreReceptor;
																	$encontro = 1;
																}
																if($rfcReceptor == $RFCInstancia) {
																	$tipoDeComprobante = 'Egreso';
																	$rfcTercero = $rfcEmisor;
																	$nombreTercero = $nombreEmisor;
																	$encontro = 1;
																}
																//Si no encuentra emisor o receptor se le asigna Otro
																if(!$encontro) {
																	$tipoDeComprobante = 'Otro';
																	$rfcTercero = $rfcEmisor;
																	$nombreTercero = $nombreEmisor;
																}
																//Si es nomina...
																if($data['nomina']) { 
																	$tipoDeComprobante = "Nomina";
																}
																//Obtenemos el tipo de comprobante
																$data['tipocomprobante'] = $tipoDeComprobante;
																if($child->Impuestos) {
																	foreach($child->Impuestos[0]->attributes() AS $a => $b){
																		if($a == 'TotalImpuestosTrasladados') {
																			$totalImpuestosTrasladados = $b;
																		} else {
																			$totalImpuestosTrasladados = 0;
																		}
																	}
																}
			                      }
													}													
												}
												if(isset($data['nomina']))
													$data['tipoDeComprobante'] = "Nomina";
												if($carpeta[$n] == 'temporales' && !isset($_GET['canceladas'])){
													if(!isset($_GET['asignadas']) && (!isset($_GET['bancos']))){
														if (strpos($name, '_') !== false){
															$name = explode('_',basename($file));
														} 
														else {
															$name = basename($file);
														}
														$name = str_replace('.xml', '', $name);
														$totalImpuestosTrasladados = floatval($totalImpuestosTrasladados);
              							$data['basename'] = basename($file);
              							$importesVal = str_replace(",", "", $importes);
              							$color = '';
              							$checkbox_l = "<input type='checkbox' xml='".$file."'>";
              							if(preg_match('/-Cobro|-Pago|Parcial-|-Nomina/', $file))
              							{
              								$color = "style='background-color:#E5E5E5;'";
              								$checkbox_l = "";
              							}
														echo "<tr class='itemList' visible='true' $color>
														<td>". $FechaTimbrado ."</td>
														<td>". $rfcTercero ." </td>
														<td>". $nombreTercero ." </td>
                						<td>			
                  					<a href='".htmlspecialchars($file)."' target='_blank'>Ver</a>
                  					/ <a href='controllers/visorpdf.php?name=".$data['basename']."&logo=".$logo."&id=".$carpeta[$n]."' target='_blank'>Previa</a>
                						</td>
														<td><center>Temporal</center></td>
														<td><center>".$tipoDeComprobante."</center></td>
														<td>".$data['subtotal']."</td>
														<td>".number_format($totalImpuestosTrasladados,2,'.','')."</td>
														<td cantidad='".$data['total']."'>". number_format($data['total'], 2,'.','')."</td>";
														#Folio
														if (isset($data['folio'])) {
															echo('<td>'.$data['folio'].'</td>');	
														} else {
															echo('<td>-</td>');
														}
														#//Folio
														#UUID
														if (isset($data['uuid'])) {
															if (is_array($data['uuid'])) {
																$data['uuid'] = $data['uuid'][1];
															}
															echo('<td>'.$data['uuid'].'</td>');	
														} else {
															echo('<td>'.$name[2].'</td>');
														}
														#//UUID
														echo "<td>".$data['metodoDePago']."</td>
														<td><center>".date ("d/m/Y H:i:s",filectime($file))."</center></td>";
														echo "<td><i style='color:red;'>No Asignada</i></td>";
														echo "<td>$checkbox_l</td>";
														echo "<td class='hide-excel'>".$data['calleEmisor']."</td>
																	<td class='hide-excel'>".$data['calleReceptor']."</td>
																	<td class='hide-excel'>".$data['version']."</td>
														</tr>";
													}
												} 
												elseif(isset($_GET['asignadas']) && $carpeta[$n] != 'canceladas') 
												{
													$numpol = $this->ReportsModel->numpol($carpeta[$n]);
													if(intval($numpol) > 0) 
													{
														$totalImpuestosTrasladados = floatval($totalImpuestosTrasladados);
														$name = explode('_',basename($file));
														$name = str_replace('.xml', '', $name);
                						$data['basename'] = basename($file);
                						$importesVal = str_replace(",", "", $importes);
                						$id_pol = $carpeta[$n];
                						$tipo_pol = $this->obtener_tipo_poliza($id_pol);
                						#Validamos las calles de emisor y receptor
														echo "<tr class='itemList' visible='true' $color>
														<td>". $FechaTimbrado ."</td>
														<td>". $rfcTercero ."</td>
														<td>". $nombreTercero ."</td>
                						<td>
                  					<a href='".htmlspecialchars($file)."' target='_blank'>Ver</a>
                  					/ <a href='controllers/visorpdf.php?name=".$data['basename']."&logo=".$logo."&id=".$carpeta[$n]."' target='_blank'>Previa</a>
                						</td>
														<td><center><a href='index.php?c=CaptPolizas&f=ModificarPoliza&id=".$carpeta[$n]."' target='_blank'>$numpol</a></center></td>
														<td><center>$tipoDeComprobante</center></td>
														<td> ".$importes."</td>
														<td> ".number_format($totalImpuestosTrasladados,2,'.','')."</td>
														<td cantidad='".$data['total']."'>". number_format($data['total'], 2,'.','')."</td>";
														#Folio
														if (isset($data['folio'])) 
														{
															echo('<td>'.$data['folio'].'</td>');	
														} 
														else 
														{
															echo('<td>'.$name[0].'</td>');
														}
														#//Folio
														#UUID
														if (isset($data['uuid'])) 
														{
															if (is_array($data['uuid'])) 
															{
																$data['uuid'] = $data['uuid'][1];
															}
															echo('<td>'.$data['uuid'].'</td>');	
														} 
														else 
														{
															echo('<td>'.$name[2].'</td>');
														}
														#//UUID
														echo " <td>".$data['metodoDePago']."</td><td><center>".date ("d/m/Y H:i:s",filectime($file))."</center> </td>";
														echo "<td>".$tipo_pol."</td>";
														echo "<td><input type='checkbox' xml='".$file."'></td>";
														echo "<td class='hide-excel'>".$data['calleEmisor']."</td>
																	<td class='hide-excel'>".$data['calleReceptor']."</td>
																	<td class='hide-excel'>".$data['version']."</td>
														</tr>";
													}
												} elseif(isset($_GET['canceladas']) && $carpeta[$n] == 'canceladas') {
													$name = explode('_',basename($file));
													$name = str_replace('.xml', '', $name);
													$totalImpuestosTrasladados = floatval($totalImpuestosTrasladados);
			                    $importesVal = str_replace(",", "", $importes);
	                        $total = $importesVal+$totalImpuestosTrasladados;
			                    $data['basename'] = basename($file);
													echo "<tr class='itemList' $color>
													<td>". $rfcTercero ." </td>
													<td>". $nombreTercero ." </td>
													<td>". $FechaTimbrado ."</td>
		                      <td>
		                        <a href='".htmlspecialchars($file)."' target='_blank'>Ver</a>
		                        / <a href='controllers/visorpdf.php?name=".$data['basename']."&logo=".$logo."&id=".$carpeta[$n]."' target='_blank'>Previa</a>
		                      </td>
													<td><center>Canceladas</center></td>
													<td><center>".$data['tipoDeComprobante']."</center></td>
													<td> ". $importes."</td>
													<td> ".number_format($totalImpuestosTrasladados,2,'.','')."</td>
													<td cantidad='$total'>". number_format($total, 2,'.','')."</td>
													<td>".$name[0]."</td>
													<td>".$name[2]."</td>
													<td>".$data['metodoDePago']."</td>
													<td><center>".date ("d/m/Y H:i:s",filectime($file))."</center></td>";
													echo "<td><i style='color:red;'>No Asignada</i></td>";
													echo "<td><img style='cursor:pointer;cursor:hand;' src='images/eliminado.png' title='Eliminar XML' onclick=eliminaxml('".$file."')/></td>
													</tr>";
												}
											}
										} else {
											$archivos = glob($this->path()."xmls/facturas/documentosbancarios/*/*",GLOB_NOSORT);
											array_multisort(array_map('filectime', $archivos),SORT_DESC,$archivos);

										foreach($archivos as $val) {
											$soloruta = str_replace("/".basename($val), '', $val);
											$carpeta = explode('/',$val);
											$aa = simplexml_load_file($val);
											$texto 	= file_get_contents($val);
											$xml 	= new DOMDocument();
											$xml->loadXML($texto);
											$xp = new DOMXpath($xml);
											if ($aa['version'] == '3.2') {
												$data['rfc']           = $this->getpath("//@rfc");
												$data['nombre']        = $this->getpath("//@nombre");
												$data['unidad']        = $this->getpath("//@unidad");
						            $data['cantidad']      = $this->getpath("//@cantidad");
						            $data['subtotal']      = $this->getpath("//@subTotal");
						            $data['descuento']     = $this->getpath("//@descuento");
						            $data['descripcion']   = $this->getpath("//@descripcion");
						            $data['descripcion2']  = $this->getpath("//@descripcion");
												$data['nomina']        = $this->getpath("//@NumEmpleado");
												$data['metodoDePago']  = $this->getpath("//@metodoDePago");
						            $data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
						            $data['valorUnitario'] = $this->getpath("//@valorUnitario");
						            $data['impuesto']      = $this->getpath("//@totalImpuestosTrasladados");

												$FechaTimbrado = $data['FechaTimbrado'];
	                      $importes = number_format(floatval($aa['subTotal'])); 
	                      $data['total'] = floatval($aa['total']);	
											
											} else if ($aa['Version'] == '3.3') {
	                      $data['uuid']          = $this->getpath("//@UUID");
	                      $data['folio']         = $this->getpath("//@Folio");
												$data['nombre']        = $this->getpath("//@nombre");
												$data['metodoDePago']  = $this->getpath("//@MetodoPago");
	                      $data['nomina']        = $this->getpath("//@NumEmpleado");
	                      $data['FechaTimbrado'] = $this->getpath("//@FechaTimbrado");
	                      $data['valorUnitario'] = $this->getpath("//@valorUnitario");
	                      $data['impuesto']      = $this->getpath("//@TotalImpuestosTrasladados");

												$FechaTimbrado = $data['FechaTimbrado'];
	                      $importes = number_format(floatval($aa['SubTotal'])); 
	                      $data['total'] = floatval($aa['Total']);	
											}
										
											if($namespaces = $aa->getNamespaces(true)) {
												$child = $aa->children($namespaces['cfdi']);
												foreach($child->Emisor[0]->attributes() AS $a => $b) {
													if($a == 'rfc') {
														$rfcEmisor = $b;
													}
													if($a == 'nombre') {
														$nombreEmisor = $b;
													}
												}
												foreach($child->Receptor[0]->attributes() AS $a => $b) {
													if($a == 'rfc') {
														$rfcReceptor = $b;
													}
													if($a == 'nombre') {
														$nombreReceptor = $b;
													}
												}
												$encontro = 0;
												if($rfcEmisor == $RFCInstancia) {
													$tipoDeComprobante = 'Ingreso';
													$rfcTercero = $rfcReceptor;
													$nombreTercero = $nombreReceptor;
													$encontro = 1;
												}
												if($rfcReceptor == $RFCInstancia) {
													$tipoDeComprobante = 'Egreso';
													$rfcTercero = $rfcEmisor;
													$nombreTercero = $nombreEmisor;
													$encontro = 1;
												}
												if(!$encontro) {
													$tipoDeComprobante = 'Otro';
													$rfcTercero = $rfcEmisor;
													$nombreTercero = $nombreEmisor;
												}
												if($data['nomina']) {
													$tipoDeComprobante = "Nomina";
												}
												$data['tipocomprobante']= $tipoDeComprobante;
												foreach($child->Impuestos[0]->attributes() AS $a => $b) {
													if($a == 'totalImpuestosTrasladados') {
														$totalImpuestosTrasladados = $b;
													} else {
														$totalImpuestosTrasladados = 0;
													}
												}
												foreach($child->Complemento[0]->children($namespaces['tfd'])->attributes() AS $a => $b){
													if($a == 'FechaTimbrado') {
														$FechaTimbrado = $b;
													}
												}
												if (!isset($importes)) {
													$importes = 0;
													for($i=0;$i<=(count($child->Conceptos->Concepto)-1);$i++) {
														foreach($child->Conceptos->Concepto[$i]->attributes() AS $a => $b) {
															if($a == 'importe') {
																$importes += floatval(number_format($b),2);
															}
														}
													}
												}
												$doc = $this->ReportsModel->documentoBancario($carpeta[$n+1]);
												$totalImpuestosTrasladados = floatval($totalImpuestosTrasladados);
												if (strpos($name, '_') !== false) {
													$name = explode('_',basename($file));
												} else {
													$name = basename($file);
												}
												$name = str_replace('.xml', '', $name);
												echo "<tr visible='true'>
												<td style='width:150px !important;text-align:center;'>". $FechaTimbrado ."</td>
												<td style='width:150px !important;'>". $rfcTercero ."</td>
												<td style='width:250px !important;'>". $nombreTercero ."</td>
		                    <td>
		                      <a href='".htmlspecialchars($val)."' target='_blank'>Ver</a>
		                      / <a href='controllers/visorpdf.php?name=".$data['basename']."&logo=".$logo."&id=".$carpeta[$n]."' target='_blank'>Previa</a>
		                    </td>		                 
												<td><center>$doc</center></td>
												<td><center>$tipoDeComprobante</center></td>
												<td>".$importes."</td>
												<td>".number_format($data['impuesto'],2,'.','')."</td>
												<td cantidad='".$data['total']."'>". number_format($data['total'], 2,'.','')."</td>";

												//Pintamos el folio
												if (isset($data['folio'])) {
													echo('<td>'.$data['folio'].'</td>');	
												} else {
													echo('<td>'.$name[0].'</td>');
												}
												//Pintamos el uuid 
												if (isset($name[2])) {
													echo('<td>'.$name[2].'</td>');
												} else {
													echo('<td>'.$data['uuid'][1].'</td>');	
												}
												echo "<td>".$data['metodoDePago']."</td><td style='width:150px !important;'><center>".date ("d/m/Y H:i:s",filectime($val))."</center></td>";
												echo "<td>Asignada</td>";
												echo "<td><input type='checkbox' xml='".$file."'></td>
												</tr>";
											}
										}
									} ?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="11" id="rowCount"></td>
										<td colspan="1"><b>Total:<b></td>
										<td colspan="3" id="total"></td>
									</tr>
								</tfoot>
							</table>
						</div>
				</div>
			</section>
			<!-- Acciones para seleccionados -->
			<div class="row">
				<?php if($carpeta[$n] == 'temporales' && !isset($_GET['canceladas'])) { ?>
				<div class="col-md-3 col-md-offset-6">
					<?php } else { ?>
					<div class="col-md-3 col-md-offset-9">
					<?php } ?>
						<button class="btn btn-info btn-block" onclick="descargarXMLs()">
							Descargar Seleccionados
						</button>
					</div>
					<?php if($carpeta[$n] == 'temporales' && !isset($_GET['canceladas'])) { ?>
					<div class="col-md-3">
						<button class="btn btn-danger btn-block" onclick="eliminarSeleccionados()">
							Eliminar Seleccionados
						</button>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function eliminarSeleccionados() {
	//Validamos que el usuario sabe que esta borrando facturas
	if(confirm('¿Realmente desea eliminar los XMLs?')) {
		//Recorremos las filas
		$("#listado tbody tr").each(function(index) {
			//Obtenemos las filas seleccionadas a traves de los checkbox
			td = $("td:nth-child(15) input:checked", this).attr('xml');
			//Validamos que el checkbox este seleccionado
			if(td != undefined){
				//Eliminamos el xml
				eliminaxml(td);
  		}
		});
		actualiza();
	}
}

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(?:\d{3})+(?!\d))/g, ",");
}

function eliminaxml(xml){
	$.post("ajax.php?c=Reports&f=Eliminarxml",
	{
		xml: xml
	});
}

function descargarXMLs() {
	//Validamos que el usuario tenga seleccionado algun checkbox
	if ($("td:nth-child(15 ) input:checked").length > 0) {
		//Si el usuario confirma que desea descargar los xmls...
		if (confirm('¿Desea descargar los XMLs?')) {
		var xmls = new Array();
			//Obtenemos los nombres de los registros en la tabla
			$('#listado tbody tr').each(function(index) {
				xmlFile = $("td:nth-child(15 ) input:checked", this).attr('xml');
				isVisible = $(this).attr('visible');
				if (xmlFile != undefined && isVisible == 'true') {
					xmls.push(xmlFile);	
				}						
			});
			//Luego los descargamos por ajax
			$.post('ajax.php?c=backup&f=descargarXMLS',
			{
				xmls: xmls
			},
			function (data) {
				console.log(data);
				$('#hiddenContainer').html(data.download);
				var linkZip = $('#zipPathXML').val();
				console.log(linkZip);
				if(linkZip != undefined) {
					window.location = linkZip;
					$.post('ajax.php?c=backup&f=borrarZip',
					{
						link: linkZip
					},
					function (data){
						console.log(data);
					});
				} else {
					alert("Hubo un error y no se genero.")
				}
			}, "JSON");	
		}
	//Si no, le informamos que tiene que seleccionar un checkbox para poder descargar	 
	} else {
		alert("Seleccione al menos un registro para poder descargar.");
	}
}

$( '#fac' )
  .submit( function( e ) {
  	$('#verif').css('display','inline');
    $.ajax( {
      url: 'ajax.php?c=CaptPolizas&f=subeFacturaZip',
      type: 'POST',
      data: new FormData( this ),
      processData: false,
      contentType: false
    } ).done(function( data1 ) {
    	//$("#Facturas").dialog('refresh')
    	console.log(data1)

			$('#factura').val('')
			data1 = data1.split('-/-*');
			$('#verif').css('display','none');

			if(parseInt(data1[0]))
			{
				if(parseInt(data1[3]))
				{
					alert('Los siguientes '+data1[3]+' archivos no son validos: \n'+data1[4])
					console.log('Los siguientes '+data1[3]+' archivos no son validos: \n'+data1[4]);
				}

				if(parseInt(data1[1]))
				{
					alert(data1[1]+' Archivos Validados: \n'+data1[2])
					console.log(data1[1]+' Archivos Validados: \n'+data1[2]);
				}
				//alert(parseInt(data1[5]))
				if(parseInt(data1[5])){
					abrefacturasrepetidas();

				}else{
					location.reload();
				}
			}
			else
			{
				alert("El archivo zip no cumple con el formato correcto\nDebe llamarse igual que la carpeta que contiene los xmls.\nSólo debe contener una carpeta.");
			}


  	});
    e.preventDefault();
  });
 function reloadfacturas(){
 	$.post("ajax.php?c=CaptPolizas&f=facturas_dialog",
		 	{
				IdPoliza: 'temporales'
			},
			function()
			{
				$( '#fac' ).attr("disabled",true);
				location.reload();
			 	//$('#listaFacturas').html(data2)

			});
	}

function afrAgregar(){
	var copiar = [];
		for(var i = 1 ; i<=$(".copia").length; i++)
		{
			if($("#copia-"+i).is(':checked'))
			{
				copiar.push($("#copia-"+i).val());
			}
		}
		$("#load").show();
	$.post("ajax.php?c=CaptPolizas&f=copiaRepetidos",{
		opc:1,
		xml: copiar

		},function(r){$("#load").hide();
				reloadfacturas();
		});
}

function afrCancelar(){
	$.post("ajax.php?c=CaptPolizas&f=copiaRepetidos",{
	opc: 0
	},function(r){$("#load").hide();
	reloadfacturas();
	});
}

function abrefacturasrepetidas(){

	$.post("ajax.php?c=CaptPolizas&f=listaRepetidos",
		 	{

			},
			function(callback)
			{
				$("#repe").html(callback);
			});

	$('#almacen').modal('show');
}
function buttondesclick(v)
	{
		$("."+v).attr('checked',false);
	}
</script>
<div id="hiddenContainer"></div>
<div id="almacen" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal- style="width: 7.69% !important;"">
            	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Archivos repetidos</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                	<div class="col-md-12">
                		<label>Seleccione los archivos que desea copiar o cancele para no hacer nada</label>
									</div>
                </div>
                <div class="row" style="display: none;" id="load">
                	<div class="col-md-12">
                		<label style="color: green;">Espere un momento...</label>
									</div>
                </div>
                <div class="row">
                	<div class="col-md-12 col-sm-12 col-xs-12 tablaResponsiva">
                		<div class="table-responsive">
                			<table id='repe' class="table">
                			</table>
                		</div>
                	</div>
                </div>
            	</div>
            <div class="modal-footer">
            	<div class="row">
                    <div class="col-md-3 col-md-offset-6">
                      <button type="button" class="btn btn-primary btn-block" onclick="javascript:afrAgregar();">Almacenar</button>
                    </div>
                    <div class="col-md-3">
                      <button type="button" class="btn btn-danger btn-block" onclick="javascript:afrCancelar();">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
